<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
   
    public function showByTrainee(Request $request, $trainee_id)
    {
        $plan = NutritionPlan::where('coach_id', $request->user()->id)
            ->where('trainee_id', $trainee_id)
            ->with('meals')
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
            'daily_calories' => 'nullable|integer',
            'notes' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.meal_name' => 'required|string',
            'meals.*.components' => 'required|string',
            'meals.*.calories' => 'nullable|integer',
            'meals.*.time_to_eat' => 'nullable|string',
        ]);

        $plan = NutritionPlan::updateOrCreate(
            [
                'coach_id' => $request->user()->id,
                'trainee_id' => $trainee_id,
            ],
            [
                'title' => $request->title,
                'daily_calories' => $request->daily_calories,
                'notes' => $request->notes,
            ]
        );

        $plan->meals()->delete();
        foreach ($request->meals as $meal) {
            $plan->meals()->create($meal);
        }

        return response()->json([
            'status' => true,
            'message' => 'Nutrition plan saved successfully',
            'data' => $plan->load('meals')
        ], 200);
    }
}