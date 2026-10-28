<?php

namespace App\Repositories;

use App\Models\NutritionPlan;
use App\Repositories\Contracts\NutritionPlanRepositoryInterface;

class NutritionPlanRepository implements NutritionPlanRepositoryInterface
{
    public function getTraineeNutritionPlan(int $traineeId)
    {
        return NutritionPlan::with(['days.meals.foods.recipe'])
            ->where('trainee_id', $traineeId)
            ->whereIn('status', ['active', 'completed'])
            ->orderByDesc('version')
            ->orderByDesc('start_date')
            ->get();
    }
}
