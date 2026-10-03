<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\GenerateNutritionPlanRequest;
use App\Services\AI\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NutritionPlanController extends Controller
{
    public function __construct(
        private AiService $aiService
    ) {}
    public function generateNutrition(GenerateNutritionPlanRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->userProfile()
            ->with([
                'goal',
                'ActivityLevel',
                'healthConditions',
                'dietaryRestrictions',
            ])->first();
        if (!$profile) {
            return response()
                ->json(['message' => 'بيانات الملف الشخصي للمستخدم غير موجودة.',], 422);
        }
        $data = [
            'age' => Carbon::parse($profile->date_of_birth)->age,
            'weight_kg' => $profile->weight,
            'height_cm' => $profile->height,
            'gender' => $profile->gender,

            'activity_level' => $profile->activityLevel?->code,
            'goal' => $profile->goal?->code,
            //قيمة افتراضية **
            'pace' => 'moderate',
            'meals_per_day' => $request->input('meals_per_day', 3),
            'allergies' => $profile->dietaryRestrictions->pluck('name')->values()->toArray(),
            'medical_conditions' => $profile->healthConditions->pluck('name')->values()->toArray(),
        ];

        $result = $this->aiService->generateNutritionPlan($data);

        return response()->json($result);
    }
}
