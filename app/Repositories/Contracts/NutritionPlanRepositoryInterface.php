<?php

namespace App\Repositories\Contracts;

interface NutritionPlanRepositoryInterface
{
    public function getTraineeNutritionPlan(int $traineeId);
}