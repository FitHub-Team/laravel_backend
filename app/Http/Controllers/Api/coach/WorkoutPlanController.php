<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
   
    public function showByTrainee(Request $request, $trainee_id)
    {
        $plan = WorkoutPlan::where('coach_id', $request->user()->id)
            ->where('trainee_id', $trainee_id)
            ->with('exercises')
            ->first();

        return response()->json([
            'status' => true,
            'data' => $plan
        ], 200);
    }
    public function storeOrUpdate(Request $request, $trainee_id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'exercises' => 'required|array',
            'exercises.*.day_of_week' => 'required|string',
            'exercises.*.exercise_name' => 'required|string',
            'exercises.*.sets' => 'required|integer',
            'exercises.*.reps' => 'required|integer',
            'exercises.*.rest_time' => 'nullable|string',
            'exercises.*.notes' => 'nullable|string',
        ]);

        $plan = WorkoutPlan::updateOrCreate(
            [
                'coach_id' => $request->user()->id,
                'trainee_id' => $trainee_id,
            ],
            [
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]
        );

       
        $plan->exercises()->delete();
        foreach ($request->exercises as $exercise) {
            $plan->exercises()->create($exercise);
        }

        return response()->json([
            'status' => true,
            'message' => 'Workout plan saved successfully',
            'data' => $plan->load('exercises')
        ], 200);
    }
}