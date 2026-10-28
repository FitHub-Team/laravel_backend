<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\GenerateNutritionPlanRequest;
use App\Models\User;
use App\Services\AI\AiService;
use Google\Service\ServiceControl\Auth;
use App\Services\Coach\nutritionPlan\NutritionPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NutritionPlanController extends Controller
{
    public function __construct(
        private AiService $aiService,
        private NutritionPlanService $nutritionPlanService
    ) {
    }

    /**
     * Generate a nutrition plan for the authenticated coach's trainee
     * when trainee_id is provided; otherwise use the current user profile.
     */
    public function generateNutrition(GenerateNutritionPlanRequest $request): JsonResponse
    {
        $coach = Auth::user();
        $traineeId = $request->input('trainee_id');

        $targetUser = $traineeId
            ? User::query()->findOrFail($traineeId)
            : $coach;

        $profile = $targetUser->userProfile()
            ->with([
                'goal',
                'ActivityLevel',
                'healthConditions',
                'dietaryRestrictions',
            ])->first();

        if (!$profile) {
            return response()->json([
                'message' => 'بيانات الملف الشخصي للمستخدم غير موجودة.',
            ], 422);
        }

        $data = [
            'age' => Carbon::parse($profile->date_of_birth)->age,
            'weight_kg' => $profile->weight,
            'height_cm' => $profile->height,
            'gender' => $profile->gender,
            'activity_level' => $profile->activityLevel?->code,
            'goal' => $profile->goal?->code,
            'pace' => $request->input('pace', 'moderate'),
            'meals_per_day' => $request->input('meals_per_day', 3),
            'allergies' => $profile->dietaryRestrictions->pluck('name')->values()->toArray(),
            'medical_conditions' => $profile->healthConditions->pluck('name')->values()->toArray(),
        ];

        if ($request->filled('allergies')) {
            $data['allergies'] = $request->input('allergies');
        }
        if ($request->filled('medical_conditions')) {
            $data['medical_conditions'] = $request->input('medical_conditions');
        }

        $result = $this->aiService->generateNutritionPlan($data);

        // Optionally persist as draft when coach generates for a trainee
        $saveAsDraft = filter_var($request->input('save_as_draft', false), FILTER_VALIDATE_BOOLEAN);
        $savedPlan = null;

        if ($saveAsDraft && $traineeId) {
            $savedPlan = $this->nutritionPlanService->saveAIPlan(
                (int) $coach->id,
                (int) $traineeId,
                is_array($result) ? $result : []
            );
        }

        return response()->json([
            'status' => true,
            'data' => $result,
            'saved_plan' => $savedPlan,
        ]);
    }
}
