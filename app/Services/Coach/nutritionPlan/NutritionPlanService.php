<?php

namespace App\Services\Coach\nutritionPlan;

use App\Models\NutritionPlan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Writes nutrition plans and manages draft / active / completed transitions.
 */
class NutritionPlanService
{
    public function __construct(
        private NutritionPlanQueryService $queries
    ) {}

    public function getPlanByTrainee(int $coachId, int $traineeId): ?NutritionPlan
    {
        return $this->queries->getPlanByTrainee($coachId, $traineeId);
    }

    public function getPlanById(int $coachId, int $planId): NutritionPlan
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
    ): NutritionPlan {
        $status = $data['status'] ?? 'draft';

        if (!in_array($status, ['draft', 'active'], true)) {
            throw ValidationException::withMessages([
                'status' => ['حالة الخطة غير صالحة.'],
            ]);
        }

        return DB::transaction(function () use ($coachId, $traineeId, $data, $status) {
            $this->lockTrainee($traineeId);

            $plan = $this->getOrCreateDraft($coachId, $traineeId, [
                'title' => $data['title'] ?? 'خطة تغذية',
                'daily_calories' => $data['daily_calories'] ?? null,
                'protein' => $data['protein'] ?? null,
                'carbs' => $data['carbs'] ?? null,
                'fat' => $data['fat'] ?? null,
                'notes' => $data['notes'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ]);

            $this->replacePlanContent($plan, $data);

            if ($status === 'active') {
                $this->activateDraft($plan);
            }

            return $plan->load(['meals.recipe']);
        });
    }

    /**
     * Convert an AI response to a draft without overwriting published versions.
     */
    public function saveAIPlan(
        int $coachId,
        int $traineeId,
        array $aiResult
    ): NutritionPlan {
        return DB::transaction(function () use ($coachId, $traineeId, $aiResult) {
            $this->lockTrainee($traineeId);

            $converted = $this->convertAIResult($aiResult);

            $plan = $this->getOrCreateDraft($coachId, $traineeId, [
                'title' => $converted['title'],
                'daily_calories' => $converted['daily_calories'],
                'protein' => $converted['protein'],
                'carbs' => $converted['carbs'],
                'fat' => $converted['fat'],
                'notes' => $converted['notes'],
                'start_date' => null,
                'end_date' => null,
            ]);

            $this->replacePlanContent($plan, $converted);

            return $plan->load(['meals.recipe']);
        });
    }

    /**
     * Send an already saved draft using its plan ID.
     */
    public function sendDraftPlan(int $coachId, int $planId): NutritionPlan
    {
        return DB::transaction(function () use ($coachId, $planId) {
            $target = NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->findOrFail($planId);

            $this->lockTrainee((int) $target->trainee_id);

            $plan = NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->activateDraft($plan);

            return $plan->load(['meals.recipe']);
        });
    }

    public function deleteDraftPlan(int $coachId, int $planId): void
    {
        DB::transaction(function () use ($coachId, $planId) {
            $plan = NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($plan->status !== 'draft') {
                throw ValidationException::withMessages([
                    'status' => 'يمكن حذف المسودات فقط.',
                ]);
            }

            $plan->meals()->delete();
            $plan->delete();
        });
    }

    public function cancelActivePlan(int $coachId, int $planId): NutritionPlan
    {
        return DB::transaction(function () use ($coachId, $planId) {
            $plan = NutritionPlan::query()
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

            return $plan->fresh(['meals.recipe']);
        });
    }

    /**
     * Duplicate an active nutrition plan into a new draft.
     */
    public function duplicatePlan(int $coachId, int $planId): NutritionPlan
    {
        return DB::transaction(function () use ($coachId, $planId) {
            $sourcePlan = NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->firstOrFail();

            $this->lockTrainee((int) $sourcePlan->trainee_id);

            $sourcePlan = NutritionPlan::query()
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

            $existingDraft = NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->where('status', 'draft')
                ->first();

            if ($existingDraft) {
                throw ValidationException::withMessages([
                    'draft' => [
                        'يوجد مسودة بالفعل لهذا المشترك. افتح المسودة الحالية أولاً.',
                    ],
                ]);
            }

            $lastVersion = (int) NutritionPlan::query()
                ->where('coach_id', $coachId)
                ->where('trainee_id', $sourcePlan->trainee_id)
                ->max('version');

            $newPlan = NutritionPlan::create([
                'coach_id' => $coachId,
                'trainee_id' => $sourcePlan->trainee_id,
                'title' => $sourcePlan->title,
                'daily_calories' => $sourcePlan->daily_calories,
                'protein' => $sourcePlan->protein,
                'carbs' => $sourcePlan->carbs,
                'fat' => $sourcePlan->fat,
                'notes' => $sourcePlan->notes,
                'version' => $lastVersion + 1,
                'status' => 'draft',
                'start_date' => null,
                'end_date' => null,
            ]);

            $sourcePlan->load('meals');

            foreach ($sourcePlan->meals as $meal) {
                $newMeal = $meal->replicate(['nutrition_plan_id']);
                $newPlan->meals()->save($newMeal);
            }

            return $newPlan->load(['meals.recipe']);
        });
    }

    private function lockTrainee(int $traineeId): void
    {
        User::query()->whereKey($traineeId)->lockForUpdate()->firstOrFail();
    }

    private function getOrCreateDraft(
        int $coachId,
        int $traineeId,
        array $attributes
    ): NutritionPlan {
        $plan = NutritionPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->where('status', 'draft')
            ->orderByDesc('id')
            ->first();

        $lastPublishedVersion = (int) NutritionPlan::query()
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

        return NutritionPlan::create(array_merge($values, [
            'coach_id' => $coachId,
            'trainee_id' => $traineeId,
            'status' => 'draft',
            'title' => $attributes['title'] ?? 'خطة تغذية',
        ]));
    }

    private function replacePlanContent(NutritionPlan $plan, array $data): void
    {
        if (array_key_exists('title', $data) || array_key_exists('daily_calories', $data)
            || array_key_exists('protein', $data) || array_key_exists('carbs', $data)
            || array_key_exists('fat', $data) || array_key_exists('notes', $data)
        ) {
            $plan->update([
                'title' => $data['title'] ?? $plan->title,
                'daily_calories' => $data['daily_calories'] ?? $plan->daily_calories,
                'protein' => $data['protein'] ?? $plan->protein,
                'carbs' => $data['carbs'] ?? $plan->carbs,
                'fat' => $data['fat'] ?? $plan->fat,
                'notes' => $data['notes'] ?? $plan->notes,
            ]);
        }

        $plan->meals()->delete();

        foreach ($data['meals'] ?? [] as $index => $meal) {
            $plan->meals()->create([
                'meal_name' => $meal['meal_name'],
                'components' => $meal['components'] ?? '',
                'calories' => $meal['calories'] ?? null,
                'protein' => $meal['protein'] ?? null,
                'carbs' => $meal['carbs'] ?? null,
                'fat' => $meal['fat'] ?? null,
                'time_to_eat' => $meal['time_to_eat'] ?? null,
                'recipe_id' => $meal['recipe_id'] ?? null,
                'sort_order' => $meal['sort_order'] ?? $index,
            ]);
        }
    }

    private function activateDraft(NutritionPlan $plan): void
    {
        if ($plan->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' => ['يمكن إرسال المسودات فقط.'],
            ]);
        }

        if (!$plan->meals()->exists()) {
            throw ValidationException::withMessages([
                'meals' => ['لا يمكن إرسال خطة بدون وجبات.'],
            ]);
        }

        $startDate = today('Asia/Hebron');
        $endDate = $startDate->copy()->addDays(6);

        $lastPublishedVersion = (int) NutritionPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', '!=', 'draft')
            ->max('version');

        if ((int) $plan->version <= $lastPublishedVersion) {
            $plan->update([
                'version' => $lastPublishedVersion + 1,
            ]);
        }

        NutritionPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', 'active')
            ->where('id', '!=', $plan->id)
            ->update([
                'status' => 'completed',
            ]);

        $plan->update([
            'status' => 'active',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }

    /**
     * Normalize AI nutrition payload into storage format.
     */
    private function convertAIResult(array $aiResult): array
    {
        $summary = $aiResult['summary'] ?? $aiResult;

        $meals = [];
        $sourceMeals = $aiResult['meals'] ?? $aiResult['daily_meals'] ?? [];

        foreach ($sourceMeals as $index => $meal) {
            $components = $meal['components']
                ?? $meal['description']
                ?? $meal['foods']
                ?? '';

            if (is_array($components)) {
                $components = collect($components)
                    ->map(function ($item) {
                        if (is_string($item)) {
                            return $item;
                        }
                        if (is_array($item)) {
                            return $item['name'] ?? $item['food'] ?? json_encode($item, JSON_UNESCAPED_UNICODE);
                        }
                        return (string) $item;
                    })
                    ->implode(' + ');
            }

            $meals[] = [
                'meal_name' => $meal['meal_name'] ?? $meal['name'] ?? $meal['title'] ?? ('وجبة ' . ($index + 1)),
                'components' => is_string($components) ? $components : '',
                'calories' => $meal['calories'] ?? $meal['kcal'] ?? null,
                'protein' => $meal['protein'] ?? $meal['protein_g'] ?? null,
                'carbs' => $meal['carbs'] ?? $meal['carbohydrates'] ?? $meal['carbs_g'] ?? null,
                'fat' => $meal['fat'] ?? $meal['fats'] ?? $meal['fat_g'] ?? null,
                'time_to_eat' => $meal['time_to_eat'] ?? $meal['time'] ?? null,
                'recipe_id' => $meal['recipe_id'] ?? null,
                'sort_order' => $meal['sort_order'] ?? $index,
            ];
        }

        return [
            'title' => $aiResult['title'] ?? $summary['title'] ?? 'خطة تغذية بالذكاء الاصطناعي',
            'daily_calories' => $summary['daily_calories']
                ?? $summary['total_calories']
                ?? $summary['calories']
                ?? $aiResult['daily_calories']
                ?? null,
            'protein' => $summary['protein'] ?? $summary['protein_g'] ?? $aiResult['protein'] ?? null,
            'carbs' => $summary['carbs'] ?? $summary['carbohydrates'] ?? $aiResult['carbs'] ?? null,
            'fat' => $summary['fat'] ?? $summary['fats'] ?? $aiResult['fat'] ?? null,
            'notes' => $aiResult['notes'] ?? $summary['notes'] ?? null,
            'meals' => $meals,
        ];
    }
}
