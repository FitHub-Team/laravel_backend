<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\WorkoutPlanRequest;
use App\Services\Coach\WorkoutPlanService;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    protected WorkoutPlanService $workoutPlanService;

    public function __construct(WorkoutPlanService $workoutPlanService)
    {
        $this->workoutPlanService = $workoutPlanService;
    }

    public function index(Request $request, $traineeId)
    {
        $plan = $this->workoutPlanService->getPlanByTrainee($request->user()->id, $traineeId);

        return response()->json([
            'status' => true,
            'data' => $plan
        ], 200);
    }

    public function storeOrUpdate(WorkoutPlanRequest $request, $trainee_id)
    {
        $validatedData = $request->validated();

        $plan = $this->workoutPlanService->saveOrUpdatePlan(
            $request->user()->id,
            $trainee_id,
            $validatedData
        );

        return response()->json([
            'status' => true,
            'message' => 'Workout plan saved successfully',
            'data' => $plan
        ], 200);
    }
}
