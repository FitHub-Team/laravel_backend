<?php

use App\Http\Controllers\AI\AiCalculateCalories;
use App\Http\Controllers\AI\AIWorkoutPlanController;
use App\Http\Controllers\AI\NutritionPlanController as AINutritionPlanController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\ProfileOptionController;
use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\user\UserProfileController;
use App\Http\Controllers\Api\user\NutritionPlanController as UserNutritionPlanController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;
use App\Http\Controllers\Api\user\TraineeProgressController;
use App\Http\Controllers\Api\user\WorkoutPlanController as UserWorkoutPlanController;
use App\Http\Controllers\Api\user\DashboardController as UserDashboardController; 
use App\Http\Controllers\Api\user\HealthInformationController;
use App\Http\Controllers\Api\user\TraineeGoalController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\coach\AvailabilityController;
use App\Http\Controllers\Api\coach\DashboardController;
use App\Http\Controllers\Api\coach\WorkoutPlanController;
use App\Http\Controllers\Api\coach\NutritionPlanController as CoachNutritionPlanController;
use App\Http\Controllers\Api\coach\NutritionPlanController;
use App\Http\Controllers\Api\coach\ProgressController;
use App\Http\Controllers\Api\coach\SubscriptionController as CoachSubscriptionController;
use App\Http\Controllers\Api\coach\WorkoutPlan\ExerciseController;
use App\Http\Controllers\Api\coach\WorkoutPlan\CustomExerciseController;
use App\Http\Controllers\Api\coach\WorkoutPlan\WorkoutPlanController;
use App\Http\Controllers\Api\General\BrowseCoachController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

/*
|--------------------------------------------------------------------------
| Protected Routes - Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | General Browsing Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/coaches', [BrowseCoachController::class, 'index']);
    Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);

    // Health Information Routes
    Route::prefix('health-information')->group(function () {
        Route::get('/', [HealthInformationController::class, 'show']);
        Route::put('/update', [HealthInformationController::class, 'update']);
    });

    // Goal Information Routes
    Route::prefix('goal-information')->group(function () {
        Route::get('/', [TraineeGoalController::class, 'show']);
        Route::put('/update', [TraineeGoalController::class, 'update']);
    });

    Route::post('/subscription-request', [RequestSubscriptionController::class, 'requestSubscription']);
    Route::get('/subscription-requests', [RequestSubscriptionController::class, 'getMyRequests']);
    
    Route::prefix('workout-plans')->group(function () {
        Route::get('/', [UserWorkoutPlanController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | Trainee Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('trainee')
        ->middleware(['user'])
        ->group(function () {
            /*
            |----------------------------------------------------------------------
            | User Profile Settings
            |----------------------------------------------------------------------
            */
            Route::prefix('profile/setting')->group(function () {
                Route::get('/show', [UserSettingProfileController::class, 'show']);
                Route::put('/update', [UserSettingProfileController::class, 'update']);
                Route::put('/update/avatar', [UserSettingProfileController::class, 'updateProfilePhoto']);
                Route::delete('/update/avatar/delete', [UserSettingProfileController::class, 'deleteProfilePhoto']);
            });

            /*
            |----------------------------------------------------------------------
            | User Profile
            |----------------------------------------------------------------------
            */
            Route::prefix('profile')->group(function () {
                Route::get('/', [UserProfileController::class, 'index']);
                Route::get('/{user}/coaches', [UserProfileController::class, 'getCoaches']);
            });

            /*
            |----------------------------------------------------------------------
            | Subscription
            |----------------------------------------------------------------------
            */
            Route::post('/subscription-request', [RequestSubscriptionController::class, 'requestSubscription']);
            Route::get('/subscription-requests', [RequestSubscriptionController::class, 'getMyRequests']);

            /*
            |----------------------------------------------------------------------
            | Workout Plans
            |----------------------------------------------------------------------
            */
            Route::prefix('workout-plans')->group(function () {
                Route::get('/', [UserWorkoutPlanController::class, 'index']);
            });

            /*
            |----------------------------------------------------------------------
            | Nutrition Plans
            |----------------------------------------------------------------------
            */
            Route::get('/nutrition-plans', [UserNutritionPlanController::class, 'index']);

            /*
            |----------------------------------------------------------------------
            | Progress
            |----------------------------------------------------------------------
            */
            Route::prefix('progress')->group(function () {
                Route::get('/', [TraineeProgressController::class, 'index']);
                Route::post('/', [TraineeProgressController::class, 'store']);
                Route::put('/{id}/upload-photo', [TraineeProgressController::class, 'updatePhoto']);
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Coach Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('coach')
        ->middleware(['coach'])
        ->group(function () {
            /*
            |----------------------------------------------------------------------
            | Dashboard
            |----------------------------------------------------------------------
            */
            Route::get('/dashboard', [DashboardController::class, 'index']);

            /*
            |----------------------------------------------------------------------
            | Coach Profile Settings
            |----------------------------------------------------------------------
            */
            Route::prefix('profile/setting')->group(function () {
                Route::get('/show', [CoachSettingProfileController::class, 'show']);
                Route::post('/update', [CoachSettingProfileController::class, 'update']);
                Route::post('/update/avatar', [CoachSettingProfileController::class, 'updateProfilePhoto']);
                Route::delete('/delete/avatar', [CoachSettingProfileController::class, 'deleteProfilePhoto']);
            });

            /*
            |----------------------------------------------------------------------
            | AI
            |----------------------------------------------------------------------
            */
            Route::prefix('ai/generate')->group(function () {
                Route::post('/nutrition-plan', [AINutritionPlanController::class, 'generateNutrition']);
                Route::post('/workout-plan/{trainee}', [AIWorkoutPlanController::class, 'generateWorkout']);
                Route::post('/calculate-calories', [AiCalculateCalories::class, 'generateCalculate']);
            });

            /*
            |----------------------------------------------------------------------
            | Subscription Requests
            |----------------------------------------------------------------------
            */
            Route::post('/subscription-request', [RequestSubscriptionController::class, 'requestSubscription']);
            Route::get('/subscription-requests', [RequestSubscriptionController::class, 'getMyRequests']);

            /*
            |----------------------------------------------------------------------
            | Coach Availability
            |----------------------------------------------------------------------
            */
            Route::prefix('availabilities')->group(function () {
                Route::get('/', [AvailabilityController::class, 'index']);
                Route::post('/', [AvailabilityController::class, 'store']);
            });

            /*
            |----------------------------------------------------------------------
            | Subscription & Trainees Management
            |----------------------------------------------------------------------
            */
            Route::get('/subscriptions/pending', [CoachSubscriptionController::class, 'pendingRequests']);
            Route::put('/subscriptions/{id}/accept', [CoachSubscriptionController::class, 'acceptRequest']);
            Route::put('/subscriptions/{id}/reject', [CoachSubscriptionController::class, 'rejectRequest']);
            Route::get('/trainees', [CoachSubscriptionController::class, 'myTrainees']);
            Route::get('/trainees/{trainee_id}', [CoachSubscriptionController::class, 'showTraineeDetails'])->middleware('trainee.access');

            /*
            |----------------------------------------------------------------------
            | Trainee Details & Plans
            |----------------------------------------------------------------------
            */
            Route::prefix('trainees')->group(function () {
                Route::get('/{id}', [CoachSettingProfileController::class, 'showTraineeDetails'])->middleware('trainee.access');
                Route::get('/{traineeId}/workout-plan', [WorkoutPlanController::class, 'index']);
                Route::post('/{id}/workout-plan', [WorkoutPlanController::class, 'storeOrUpdate']);
                Route::post('/{id}/workout-plan/update', [WorkoutPlanController::class, 'storeOrUpdate']);
                Route::post('/{id}/nutrition-plan', [CoachNutritionPlanController::class, 'storeOrUpdate']);
                Route::get('/{id}/progress', [ProgressController::class, 'show']);ٍ
            });

            /*
            |----------------------------------------------------------------------
            | Public Exercise Library
            |----------------------------------------------------------------------
            */
            Route::get('/exercises', [ExerciseController::class, 'index']);

            /*
            |----------------------------------------------------------------------
            | Custom Exercises
            |----------------------------------------------------------------------
            */
            Route::apiResource('custom-exercises', CustomExerciseController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | Current Authenticated User
    |--------------------------------------------------------------------------
    */
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Email Verification & Password Reset
|--------------------------------------------------------------------------
*/
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(base_path('routes/admin.php'));

/*
|--------------------------------------------------------------------------
| Profile Options
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/goals', [ProfileOptionController::class, 'getGoals']);
    Route::get('/activity-levels', [ProfileOptionController::class, 'getActivityLevels']);
});

/*
|--------------------------------------------------------------------------
| Chat
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chat/{userId}', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'store']);
});