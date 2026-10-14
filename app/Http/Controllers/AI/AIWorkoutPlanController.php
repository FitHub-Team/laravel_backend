<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AI\AiService;
use App\Services\AI\WorkoutExerciseResolver;
use Illuminate\Support\Facades\Auth;

class AIWorkoutPlanController extends Controller
{
    public function __construct(
        private AiService $aiService,
        private WorkoutExerciseResolver $exerciseResolver
    ) {}

    public function generateWorkout(User $trainee)
    {
        $coach = Auth::user();

        // IMPORTANT:
        // Ensure this coach is authorized to generate
        // a workout plan for this trainee.
        // Apply your existing Policy or coach-trainee check here.

        $profile = $trainee->userProfile()
            ->with([
                'goal',
                'activityLevel',
                'healthConditions',
            ])
            ->first();

        if (!$profile) {
            return response()->json([
                'message' =>
                    'بيانات الملف الشخصي للمستخدم غير موجودة.',
            ], 422);
        }

        $availableDays = $profile->available_days ?? [];

        $trainingDays = is_array($availableDays)
            ? (count($availableDays) ?: 3)
            : 3;

        $data = [
            'user_level' => 'beginner',
            'goal' => $profile->goal?->code,
            'training_days_per_week' => $trainingDays,
            'medical_restrictions' => $profile->healthConditions
                ->pluck('name')
                ->values()
                ->toArray(),
            'height_cm' => $profile->height,
            'weight_kg' => $profile->weight,
        ];

        // Step 1: Generate plan using AI
        $result = $this->aiService->generateWorkoutPlan($data);

        // Step 2: Resolve exercise IDs from database
        $result = $this->exerciseResolver->resolve($result);

        // Step 3: Return enriched plan
        return response()->json([
            'status' => true,
            'data' => $result,
        ]);
    }
}
