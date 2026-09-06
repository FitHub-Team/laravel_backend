<?php

namespace App\Services\User;

use App\Repositories\Contracts\WorkoutPlanRepositoryInterface;

class WorkoutPlan
{
    public function __construct(
        private WorkoutPlanRepositoryInterface $workoutPlanRepository
    ) {}
    // get trenee plane all
    public function getTraineeWorkoutPlans(int $traineeId)
    {
        return $this->workoutPlanRepository
            ->getUserWorkoutPlans($traineeId);
    }
}
