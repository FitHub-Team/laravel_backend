<?php

namespace App\Repositories\Contracts;

interface WorkoutPlanRepositoryInterface
{
    public function getUserWorkoutPlans(int $userId);
}