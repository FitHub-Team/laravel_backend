<?php

namespace App\Repositories;

use App\Models\WorkoutPlan;
use App\Repositories\Contracts\WorkoutPlanRepositoryInterface;

class WorkoutPlanRepository implements WorkoutPlanRepositoryInterface
{
    public function getUserWorkoutPlans(int $traineeId)
    {
        return WorkoutPlan::with('exercises')
            ->where('trainee_id', $traineeId)
            ->orderBy('start_date','desc')
            ->get();
    }
}

