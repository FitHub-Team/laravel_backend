<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\WorkoutPlanService;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    protected WorkoutPlanService $workoutPlanService;

    public function __construct(WorkoutPlanService $workoutPlanService)
    {
        $this->workoutPlanService = $workoutPlanService;
    }

    public function showByTrainee(Request $request, $trainee_id)
    {
        $plan = $this->workoutPlanService->getPlanByTrainee($request->user()->id, $trainee_id);

        return response()->json([
            'status' => true,
            'data' => $plan
        ], 200);
    }

    public function storeOrUpdate(Request $request, $trainee_id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'exercises' => 'required|array',
            'exercises.*.day_of_week' => 'required|string',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer',
            'exercises.*.reps' => 'required|integer',
            'exercises.*.rest_time' => 'nullable|string',
            'exercises.*.notes' => 'nullable|string',
        ]);

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