<?php

namespace App\Services\Coach;

use App\Models\NutritionPlan;
use Illuminate\Support\Facades\DB;

class NutritionPlanService
{
    public function saveOrUpdatePlan(int $coachId, int $traineeId, array $data): NutritionPlan
    {
        return DB::transaction(function () use ($coachId, $traineeId, $data) {
            $plan = NutritionPlan::updateOrCreate(
                [
                    'coach_id' => $coachId,
                    'trainee_id' => $traineeId,
                ],
                [
                    'title' => $data['title'],
                    'daily_calories' => $data['daily_calories'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]
            );

            $plan->meals()->delete();
            
            foreach ($data['meals'] as $mealData) {
                $plan->meals()->create($mealData);
            }

            return $plan->load('meals');
        });
    }

    public function getPlanByTrainee(int $coachId, int $traineeId): ?NutritionPlan
    {
        return NutritionPlan::where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->with('meals')
            ->first();
    }
}