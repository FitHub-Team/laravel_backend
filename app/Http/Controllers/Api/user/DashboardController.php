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

            // جلب بيانات البروفايل بنفس منطق SettingProfileController
            $profile = $user->userProfile()->with([
                'goal',
                'activityLevel',
                'healthConditions',
                'dietaryRestrictions',
                'trainingLocation',
            ])->first();

            $age = $profile?->date_of_birth
                ? Carbon::parse($profile->date_of_birth)->age
                : null;

            $profileData = [
                'fullname' => $user->full_name,
                'email' => $user->email,
                'age' => $age,
                'gender' => $profile?->gender,
                'height' => $profile?->height,
                'weight' => $profile?->weight,
                'goal' => $profile?->goal?->title,
                'profile_photo' => $profile?->profile_photo ? asset('storage/' . $profile->profile_photo) : null,
            ];

            // جلب خطط التمارين والتغذية والتقدم باستخدام الـ Services  
            $workoutPlans = $this->workoutPlanService->getTraineeWorkoutPlans($traineeId);
            $nutritionPlans = $this->nutritionPlanService->getTraineeNutritionPlan($traineeId);
            $progressData = $this->progressService->getTraineeProgress($traineeId);

            // جلب التحديات الأسبوعية النشطة لعرضها في أسفل صفحة الهوم
            $weeklyChallenges = WeeklyChallenge::where('is_active', true)->get();

            return response()->json([
                'status' => true,
                'message' => 'Trainee dashboard data fetched successfully',
                'data' => [
                    'profile' => $profileData,
                    'workout_plans' => $workoutPlans,
                    'nutrition_plans' => $nutritionPlans,
                    'progress' => $progressData,
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