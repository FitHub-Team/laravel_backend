<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Services\User\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function __construct(
        private WorkoutPlan $workoutPlan
    ) {}
    public function index(Request $request)
    {
        try {
            $traineeId = $request->user()->id;

            $plans = $this->workoutPlan
                ->getTraineeWorkoutPlans($traineeId);

            return response()->json([
                'status' => true,
                'message' => 'Workout plans fetched successfully',
                'data' => $plans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
}
