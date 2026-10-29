<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkoutPlanDuplicateService
{
    private function lockTrainee(int $traineeId): void
    {
        User::query()
            ->whereKey($traineeId)
            ->lockForUpdate()
            ->firstOrFail();
    }
    public function duplicatePlan(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return DB::transaction(function () use ($coachId, $planId) {
            $sourcePlan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->firstOrFail();

            $this->lockTrainee((int) $sourcePlan->trainee_id);

            $sourcePlan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sourcePlan->status !== 'active') {
                throw ValidationException::withMessages([
                    'status' => [
                        'يمكن استخدام الخطط النشطة فقط كنسخة جديدة.',
                    ],
                ]);
            }

            $existingDraft = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->where('status', 'draft')
                ->exists();

            if ($existingDraft) {
                throw ValidationException::withMessages([
                    'draft' => [
                        'يوجد مسودة بالفعل لهذا المشترك. افتحي المسودة الحالية أولاً.',
                    ],
                ]);
            }

            $lastVersion = (int) WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->max('version');

            $newPlan = WorkoutPlan::create([
                'coach_id' => $coachId,
                'trainee_id' => $sourcePlan->trainee_id,
                'version' => $lastVersion + 1,
                'status' => 'draft',
                'start_date' => null,
                'end_date' => null,
            ]);

            $sourcePlan->load([
                'days',
                'workoutExercises',
            ]);

            // ربط رقم كل يوم قديم برقم اليوم الجديد لربط التمارين المنسوخة به.
            $dayIdMap = [];

            foreach ($sourcePlan->days as $day) {
                $newDay = $day->replicate([
                    'workout_plan_id',
                ]);

                $newDay->date = null;

                $newPlan->days()->save($newDay);

                $dayIdMap[$day->id] = $newDay->id;
            }

            foreach ($sourcePlan->workoutExercises as $exercise) {
                $newDayId =
                    $dayIdMap[$exercise->workout_plan_day_id] ?? null;

                if ($newDayId === null) {
                    throw ValidationException::withMessages([
                        'exercises' => [
                            'يوجد تمرين غير مرتبط بيوم صحيح في الخطة الأصلية.',
                        ],
                    ]);
                }

                $newExercise = $exercise->replicate([
                    'workout_plan_id',
                    'workout_plan_day_id',
                ]);

                $newExercise->workout_plan_day_id = $newDayId;

                // باقي الحقول، ومنها الوقت والسعرات، تُنسخ تلقائيًا
                $newPlan->workoutExercises()->save($newExercise);
            }

            return $newPlan->load([
                'days',
                'workoutExercises',
            ]);
        });
    }
}
