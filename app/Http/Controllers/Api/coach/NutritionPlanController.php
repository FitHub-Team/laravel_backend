<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\NutritionPlanService;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
    protected NutritionPlanService $nutritionPlanService;

    public function __construct(NutritionPlanService $nutritionPlanService)
    {
        $this->nutritionPlanService = $nutritionPlanService;
    }

    public function showByTrainee(Request $request, $trainee_id)
    {
        $plan = $this->nutritionPlanService->getPlanByTrainee($request->user()->id, $trainee_id);

        return response()->json([
            'status' => true,
            'data' => $plan
        ], 200);
    }

    public function storeOrUpdate(Request $request, $trainee_id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'daily_calories' => 'nullable|integer',
            'notes' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.meal_name' => 'required|string',
            'meals.*.components' => 'required|string',
            'meals.*.calories' => 'nullable|integer',
            'meals.*.time_to_eat' => 'nullable|string',
        ]);

        $plan = $this->nutritionPlanService->saveOrUpdatePlan(
            $request->user()->id, 
            $trainee_id, 
            $validatedData
        );

        return response()->json([
            'status' => true,
            'message' => 'Nutrition plan saved successfully',
            'data' => $plan
        ], 200);
    }
}