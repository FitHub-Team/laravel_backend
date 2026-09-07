<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Services\User\NutritionPlan;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
    public function __construct(
        private NutritionPlan $NutritionPlan
    ) {}
    public function index(Request $request)
    {
        try {
            $traineeId = $request->user()->id;

            $plans = $this->NutritionPlan
                ->getTraineeNutritionPlan($traineeId);

            return response()->json([
                'status' => true,
                'message' => 'Nutrition plans fetched successfully',
                'data' => $plans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching Nutrition plans',
            ], 500);
        }
    }
}
