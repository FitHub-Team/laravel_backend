<?php

namespace App\Services\User;

use App\Repositories\Contracts\NutritionPlanRepositoryInterface;


class NutritionPlan
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlanRepository
    ) {}

    public function getTraineeNutritionPlan(int $traineeId)
    {
        return $this->nutritionPlanRepository
            ->getTraineeNutritionPlan($traineeId);
    }
}
