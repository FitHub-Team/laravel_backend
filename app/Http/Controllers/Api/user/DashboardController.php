<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Services\User\WorkoutPlan;
use App\Services\User\NutritionPlan;
use App\Services\User\TraineeProgress;
use App\Models\WeeklyChallenge;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private WorkoutPlan $workoutPlanService,
        private NutritionPlan $nutritionPlanService,
        private TraineeProgress $progressService
    ) {}

   public function index(Request $request)
    {
        try {
            $user = $request->user();
            $traineeId = $user->id;

            // 1. جلب بيانات البروفايل
            $profile = $user->userProfile()->with([
                'goal',
                'activityLevel',
            ])->first();

            $age = $profile?->date_of_birth
                ? Carbon::parse($profile->date_of_birth)->age
                : null;

            // أحدث قياس تقدم للوزن أو الاعتماد على البروفايل
            $latestProgress = \App\Models\TraineeProgress::where('trainee_id', $traineeId)
                ->latest('recorded_at')
                ->first();

            $currentWeight = $latestProgress?->weight ?? $profile?->weight ?? 0;

            $profileData = [
                'fullname' => $user->full_name,
                'email' => $user->email,
                'age' => $age,
                'gender' => $profile?->gender,
                'height' => $profile?->height,
                'weight' => (float) $currentWeight,
                'target_weight' => $profile?->target_weight ?? 65, // الوزن المستهدف (الهدف)
                'goal' => $profile?->goal?->title ?? 'خسارة الدهون',
                'profile_photo' => $profile?->profile_photo ? asset('storage/' . $profile->profile_photo) : null,
            ];

            // 2. تمرين اليوم (خطة التمرين النشطة)
            $todayWorkoutPlan = $user->workoutPlans()
                ->with('exercises')
                ->where('status', 'active')
                ->latest()
                ->first();

            $workoutSummary = null;
            if ($todayWorkoutPlan) {
                $exercisesCount = $todayWorkoutPlan->exercises->count();
                $workoutSummary = [
                    'id' => $todayWorkoutPlan->id,
                    'title' => $todayWorkoutPlan->title ?? 'تمرين الجزء العلوي والقوة',
                    'exercises_count' => $exercisesCount,
                    'duration_minutes' => 45, 
                    'estimated_calories' => 320, 
                    'status' => $todayWorkoutPlan->status,
                    'exercises' => $todayWorkoutPlan->exercises,
                ];
            }

            // 3. خطة التغذية اليومية مع الوجبات
            $nutritionPlan = \App\Models\NutritionPlan::where('trainee_id', $traineeId)
                ->with('meals')
                ->latest()
                ->first();

            // 4. مستوى التقدم (مثلاً عدد التمارين المكتملة من الإجمالي)
            $progressLevel = [
                'completed' => $latestProgress?->workouts_completed ?? 2,
                'total' => 5,
                'text' => '2/5'
            ];

            // 5. التحديات الأسبوعية النشطة
            $weeklyChallenges = WeeklyChallenge::where('is_active', true)->get();

            return response()->json([
                'status' => true,
                'message' => 'Trainee dashboard data fetched successfully',
                'data' => [
                    'profile' => $profileData,
                    'today_workout' => $workoutSummary,
                    'nutrition_plan' => $nutritionPlan,
                    'progress_level' => $progressLevel,
                    'weekly_challenges' => $weeklyChallenges,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching dashboard data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}