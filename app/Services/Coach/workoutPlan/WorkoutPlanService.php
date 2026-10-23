<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Writes workout plans and manages draft / active / completed transitions.
 */
class WorkoutPlanService
{
    public function __construct(
        private WorkoutPlanQueryService $queries
    ) {}

    // Keep existing controllers working while queries live in their own service.
    public function getPlanByTrainee(int $coachId, int $traineeId): ?WorkoutPlan
    {
        return $this->queries->getPlanByTrainee($coachId, $traineeId);
    }

    public function getPlanById(int $coachId, int $planId): WorkoutPlan
    {
        return $this->queries->getPlanById($coachId, $planId);
    }

    public function getPlansByCoach(int $coachId): Collection
    {
        return $this->queries->getPlansByCoach($coachId);
    }

    /**
     * Save the current editable draft; send it when status is active.
     */
    public function saveOrUpdatePlan(
        int $coachId,
        int $traineeId,
        array $data
    ): WorkoutPlan {
        $status = $data['status'] ?? 'draft';

        if (!in_array($status, ['draft', 'active'], true)) {
            throw ValidationException::withMessages([
                'status' => ['حالة الخطة غير صالحة.'],
            ]);
        }

        return DB::transaction(function () use ($coachId, $traineeId, $data, $status) {
            $this->lockTrainee($traineeId);

            $plan = $this->getOrCreateDraft($coachId, $traineeId, [
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ]);

            $this->replacePlanContent($plan, $data);

            if ($status === 'active') {
                $this->activateDraft($plan);
            }

            return $plan->load(['days', 'workoutExercises']);
        });
    }

    /**
     * Convert an AI response to a draft without overwriting published versions.
     */
    public function saveAIPlan(
        int $coachId,
        int $traineeId,
        array $aiResult
    ): WorkoutPlan {
        return DB::transaction(function () use ($coachId, $traineeId, $aiResult) {
            $this->lockTrainee($traineeId);

            $startDate = now();
            $durationWeeks = max(1, (int) ($aiResult['duration_weeks'] ?? 1));

            $plan = $this->getOrCreateDraft($coachId, $traineeId, [
                'start_date' => $startDate->toDateString(),
                'end_date' => $startDate->copy()->addWeeks($durationWeeks)->toDateString(),
            ]);

            $this->replacePlanContent($plan, $this->convertAIResult($aiResult));

            return $plan->load(['days', 'workoutExercises']);
        });
    }

    /**
     * Send an already saved draft using its plan ID, without rewriting exercises.
     */
    public function sendDraftPlan(int $coachId, int $planId): WorkoutPlan
    {
        return DB::transaction(function () use ($coachId, $planId) {
            $target = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->findOrFail($planId);

            $this->lockTrainee((int) $target->trainee_id);

            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->activateDraft($plan);

            return $plan->load(['days', 'workoutExercises']);
        });
    }

    /**
     * Serialize writes for the same trainee within a transaction.
     */
    private function lockTrainee(int $traineeId): void
    {
        User::query()->whereKey($traineeId)->lockForUpdate()->firstOrFail();
    }

    /**
     * Create or update only a draft. Published versions are never overwritten.
     * Call this after locking the trainee.
     */
    private function getOrCreateDraft(
        int $coachId,
        int $traineeId,
        array $attributes
    ): WorkoutPlan {
        $plan = WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->where('status', 'draft')
            ->orderByDesc('id')
            ->first();

        $lastPublishedVersion = (int) WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->max('version');

        $values = array_merge($attributes, [
            'version' => $lastPublishedVersion + 1,
        ]);

        if ($plan) {
            $plan->update($values);
            return $plan;
        }

        return WorkoutPlan::create(array_merge($values, [
            'coach_id' => $coachId,
            'trainee_id' => $traineeId,
            'status' => 'draft',
        ]));
    }

    /**
     * Replace a draft's schedule and exercises in the enclosing transaction.
     */
    private function replacePlanContent(WorkoutPlan $plan, array $data): void
    {
        $plan->days()->delete();
        $plan->workoutExercises()->delete();

        foreach ($data['days'] ?? [] as $day) {
            $plan->days()->create([
                'day' => $day['day'],
                'date' => $day['date'] ?? null,
                'focus' => $day['focus'] ?? null,
            ]);
        }

        foreach ($data['exercises'] ?? [] as $exercise) {
            $plan->workoutExercises()->create([
                'exercise_id' => $exercise['exercise_id'] ?? null,
                'custom_exercise_id' => $exercise['custom_exercise_id'] ?? null,
                'day_of_week' => $exercise['day_of_week'],
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
                'rest_time' => $exercise['rest_time'] ?? null,
                'notes' => $exercise['notes'] ?? null,
                'exercise_name' => $exercise['exercise_name'] ?? null,
                'target_muscle' => $exercise['target_muscle'] ?? null,
                'equipment' => $exercise['equipment'] ?? null,
            ]);
        }
    }

    /**
     * Archive the previous active plan and publish this draft atomically.
     */

    private function activateDraft(WorkoutPlan $plan): void
    {
        // Only drafts can be sent
        if ($plan->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' => ['يمكن إرسال المسودات فقط.'],
            ]);
        }

        // Make sure the plan contains exercises
        if (!$plan->workoutExercises()->exists()) {
            throw ValidationException::withMessages([
                'exercises' => ['لا يمكن إرسال خطة بدون تمارين.'],
            ]);
        }

        // Calculate dates when the plan is sent
        $startDate = today('Asia/Hebron');
        $endDate = $startDate->copy()->addDays(6);

        // Get latest published version
        $lastPublishedVersion = (int) WorkoutPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', '!=', 'draft')
            ->max('version');

        // Protect version numbering
        if ((int) $plan->version <= $lastPublishedVersion) {
            $plan->update([
                'version' => $lastPublishedVersion + 1,
            ]);
        }

        // Archive the previous active plan
        WorkoutPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', 'active')
            ->where('id', '!=', $plan->id)
            ->update([
                'status' => 'completed',
            ]);

        // Activate plan with a one-week duration
        $plan->update([
            'status' => 'active',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }


    /**
     * Preserve the existing AI weeks -> days/exercises storage format.
     */
    private function convertAIResult(array $aiResult): array
    {
        $dayNames = [
            1 => 'sunday',
            2 => 'monday',
            3 => 'tuesday',
            4 => 'wednesday',
            5 => 'thursday',
            6 => 'friday',
            7 => 'saturday',
        ];

        $days = [];
        $exercises = [];

        foreach ($aiResult['weeks'] ?? [] as $week) {
            foreach ($week['days'] ?? [] as $day) {
                $dayOfWeek = $dayNames[$day['day_number'] ?? null] ?? null;

                if ($dayOfWeek === null) {
                    continue;
                }

                $days[] = [
                    'day' => $dayOfWeek,
                    'date' => null,
                    'focus' => $day['session']['focus'] ?? null,
                ];

                if (!empty($day['is_rest']) || empty($day['session'])) {
                    continue;
                }

                foreach ($day['session']['exercises'] ?? [] as $exercise) {
                    $exercises[] = [
                        'exercise_id' => null,
                        'custom_exercise_id' => null,
                        'day_of_week' => $dayOfWeek,
                        'sets' => (int) ($exercise['sets'] ?? 0),
                        'reps' => (string) ($exercise['reps'] ?? ''),
                        'rest_time' => $exercise['rest_seconds'] ?? null,
                        'notes' => null,
                        'exercise_name' => $exercise['name'] ?? null,
                        'target_muscle' => $exercise['target_muscle'] ?? null,
                        'equipment' => $exercise['equipment'] ?? null,
                    ];
                }
            }
        }

        return [
            'days' => $days,
            'exercises' => $exercises,
        ];
    }

    // Permanently delete a draft plan
    public function deleteDraftPlan(
        int $coachId,
        int $planId
    ): void {
        DB::transaction(function () use ($coachId, $planId) {

            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($plan->status !== 'draft') {
                throw ValidationException::withMessages([
                    'status' => 'يمكن حذف المسودات فقط.',
                ]);
            }

            // Delete related data first
            $plan->workoutExercises()->delete();
            $plan->days()->delete();

            // Delete the draft itself
            $plan->delete();
        });
    }

    // Cancel an active plan without deleting it
    public function cancelActivePlan(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return DB::transaction(function () use (
            $coachId,
            $planId
        ) {
            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($plan->status !== 'active') {
                throw ValidationException::withMessages([
                    'status' => 'يمكن إلغاء الخطط النشطة فقط.',
                ]);
            }

            $plan->update([
                'status' => 'cancelled',
            ]);

            return $plan->fresh();
        });
    }

    /**
     * Duplicate an active workout plan into a new draft.
     */
    public function duplicatePlan(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return DB::transaction(function () use ($coachId, $planId) {

            // 1. Find the source plan belonging to this coach
            $sourcePlan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->firstOrFail();

            // 2. Lock trainee to prevent conflicting plan operations
            User::query()
                ->whereKey($sourcePlan->trainee_id)
                ->lockForUpdate()
                ->firstOrFail();

            // 3. Re-read source plan after acquiring the lock
            $sourcePlan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            // 4. Only active plans can be duplicated
            if ($sourcePlan->status !== 'active') {
                throw ValidationException::withMessages([
                    'status' => [
                        'يمكن استخدام الخطط النشطة فقط كنسخة جديدة.',
                    ],
                ]);
            }

            // 5. Prevent overwriting an existing draft
            $existingDraft = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->where('status', 'draft')
                ->first();

            if ($existingDraft) {
                throw ValidationException::withMessages([
                    'draft' => [
                        'يوجد مسودة بالفعل لهذا المشترك. افتحي المسودة الحالية أولاً.',
                    ],
                ]);
            }

            // 6. Calculate the next version
            $lastVersion = (int) WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->max('version');

            $nextVersion = $lastVersion + 1;

            // 7. Create a new draft without copying old dates
            $newPlan = WorkoutPlan::create([
                'coach_id' => $coachId,
                'trainee_id' => $sourcePlan->trainee_id,
                'version' => $nextVersion,
                'status' => 'draft',
                'start_date' => null,
                'end_date' => null,
            ]);

            // 8. Load original plan days and exercises
            $sourcePlan->load([
                'days',
                'workoutExercises',
            ]);

            // 9. Copy workout days
            foreach ($sourcePlan->days as $day) {

                $newDay = $day->replicate([
                    'workout_plan_id',
                ]);

                // Dates will be assigned when needed
                $newDay->date = null;

                $newPlan->days()->save($newDay);
            }

            // 10. Copy all exercises and their settings
            foreach ($sourcePlan->workoutExercises as $exercise) {

                $newExercise = $exercise->replicate([
                    'workout_plan_id',
                ]);

                $newPlan->workoutExercises()->save($newExercise);
            }

            // 11. Return the new draft
            return $newPlan->load([
                'days',
                'workoutExercises',
            ]);
        });
    }
    public function getVersionsByCoach(int $coachId): Collection
    {
        return $this->queries->getVersionsByCoach($coachId);
    }
   
}
