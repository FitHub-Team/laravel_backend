<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Models\WorkoutExercise;
// use App\Models\WorkoutPlan;
use App\Services\AI\AiService;
use Illuminate\Support\Facades\Auth;

class AIWorkoutPlanController extends Controller
{
    public function __construct(
        private AiService $aiService
    ) {}
    public function generateWorkout(User $trainee)
{
    $coach = Auth::user();

    $profile = $trainee->userProfile()
        ->with([
            'goal',
            'activityLevel',
            'healthConditions',
        ])
        ->first();

    if (!$profile) {
        return response()->json([
            'message' => 'بيانات الملف الشخصي للمستخدم غير موجودة.',
        ], 422);
    }

    $trainingDays = count($profile->available_days ?? []) ?: 3;

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

    $result = $this->aiService->generateWorkoutPlan($data);

    return response()->json([
        'status' => true,
        'data' => $result,
    ]);
}
}
