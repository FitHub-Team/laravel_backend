<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutExercise;
use App\Models\WorkoutPlan;
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

        $data = [
            'user_level' => 'beginner',
            'goal' => $profile->goal?->code,
            'training_days_per_week' => count($profile->available_days ?? []),
            'medical_restrictions' => $profile->healthConditions
                ->pluck('name')
                ->values()
                ->toArray(),

            'height_cm' => $profile->height,

            'weight_kg' => $profile->weight,
        ];
        // dd($data);

        $result = $this->aiService->generateWorkoutPlan($data);
        dd($result);
        // حفظ الخطة
        $workoutPlan = WorkoutPlan::create([
            'trainee_id' => $trainee->id,
            'coach_id'=>$coach->id,
            'title'=>$profile->goal?->title,
          

        ]);

        foreach ($result['weeks'] as $week) {
            foreach ($week['days'] as $day) {
                foreach ($day['exercises'] as $exercise) {

                    WorkoutExercise::create([
                        'workout_plan_id' => $workoutPlan->id,
                        'exercise_id' => $exercise['exercise_id'],
                        'day_of_week' => $day['day'],
                        'sets' => $exercise['sets'],
                        'reps' => $exercise['reps'],
                        'rest_time' => $exercise['rest_time'],
                    ]);
                }
            }
        }

        return response()->json($result);
    }
}
