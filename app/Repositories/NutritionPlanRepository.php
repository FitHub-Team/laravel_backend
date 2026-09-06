<?php

namespace App\Repositories;

use App\Models\NutritionPlan;
use App\Repositories\Contracts\NutritionPlanRepositoryInterface;

class NutritionPlanRepository implements NutritionPlanRepositoryInterface
{
    public function getTraineeNutritionPlan(int $traineeId)
    {
        return NutritionPlan::with('meals')
            ->where('trainee_id', $traineeId)
            ->latest()
            ->get();
    }
}
