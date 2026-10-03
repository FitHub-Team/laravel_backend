<?php

use App\Http\Controllers\AI\AiCalculateCalories;
use App\Http\Controllers\AI\AIWorkoutPlanController;
use App\Http\Controllers\AI\NutritionPlanController as AINutritionPlanController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProfileOptionController;
use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\coach\AvailabilityController;
use App\Http\Controllers\Api\coach\DashboardController;
use App\Http\Controllers\Api\coach\WorkoutPlanController;
use App\Http\Controllers\Api\coach\NutritionPlanController;
use App\Http\Controllers\Api\coach\ProgressController;
use App\Http\Controllers\Api\user\UserProfileController;
use App\Http\Controllers\Api\coach\SubscriptionController as CoachSubscriptionController;
use App\Http\Controllers\Api\General\BrowseCoachController;
use App\Http\Controllers\Api\user\NutritionPlanController as UserNutritionPlanController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;
use App\Http\Controllers\Api\user\TraineeProgressController;
use App\Http\Controllers\Api\user\DashboardController as UserDashboardController; 
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\user\WorkoutPlanController as UserWorkoutPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

// Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Public/Auth Browsing Routes
    Route::get('/coaches', [BrowseCoachController::class, 'index']);
    Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);
    Route::get('/coaches/{id}/reviews', [CoachSettingProfileController::class, 'reviews']);
    Route::get('/coaches/{id}/subscription-status', [CoachSettingProfileController::class, 'subscriptionStatus']);

    // Trainee Routes
    Route::prefix('trainee')->middleware(['user'])->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index']);

        Route::prefix('profile/setting')->group(function () {
            Route::get('/show', [UserSettingProfileController::class, 'show']);
            Route::put('/update', [UserSettingProfileController::class, 'update']);
            Route::put('/update/avatar', [UserSettingProfileController::class, 'updateProfilePhoto']);
            Route::delete('/update/avatar/delete', [UserSettingProfileController::class, 'deleteProfilePhoto']);
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [UserProfileController::class, 'index']);
            Route::get('/{user}/coaches', [UserProfileController::class, 'getCoaches']);
        });

        Route::post('/subscription-request', [RequestSubscriptionController::class, 'requestSubscription']);
        Route::get('/subscription-requests', [RequestSubscriptionController::class, 'getMyRequests']);
        
        Route::prefix('workout-plans')->group(function () {
            Route::get('/', [UserWorkoutPlanController::class, 'index']);
        });

        Route::get('/nutrition-plans', [UserNutritionPlanController::class, 'index']);
        
        Route::prefix('progress')->group(function () {
            Route::get('/', [TraineeProgressController::class, 'index']);
            Route::post('/', [TraineeProgressController::class, 'store']);
            Route::put('/{id}/upload-photo', [TraineeProgressController::class, 'updatePhoto']);
        });
    });

    // Coach Routes (Protected by auth:sanctum and coach middleware)
    Route::middleware(['coach'])->prefix('coach')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);
        
        // Coach Profile Settings
        Route::prefix('profile/setting')->group(function () {
            Route::get('/show', [CoachSettingProfileController::class, 'show']);
            Route::post('/update', [CoachSettingProfileController::class, 'update']);
            Route::put('/update/avatar', [CoachSettingProfileController::class, 'updateProfilePhoto']);
        });

        // AI Routes
        Route::prefix('ai/generate')->group(function () {
            Route::post('/nutrition-plan', [AINutritionPlanController::class, 'generateNutrition']);
            Route::post('/workout-plan/{trainee}', [AIWorkoutPlanController::class, 'generateWorkout']);
            Route::post('/calculate-calories', [AiCalculateCalories::class, 'generateCalculate']);
        });

        // Coach Availabilities
        Route::prefix('availabilities')->group(function () {
            Route::get('/', [AvailabilityController::class, 'index']);
            Route::post('/', [AvailabilityController::class, 'store']);
        });

        // Trainees & Plans Routes
        Route::prefix('trainees')->group(function () {
            Route::get('/{id}', [CoachSettingProfileController::class, 'showTraineeDetails'])->middleware('trainee.access');
            Route::post('/{id}/workout-plan', [WorkoutPlanController::class, 'storeOrUpdate']);
            Route::post('/{id}/nutrition-plan', [NutritionPlanController::class, 'storeOrUpdate']);
            Route::get('/{id}/progress', [ProgressController::class, 'show']);
            Route::get('/{traineeId}/workout-plan', [WorkoutPlanController::class, 'index']);
            Route::post('/{id}/workout-plan/update', [WorkoutPlanController::class, 'storeOrUpdate']);
        });

        // Subscription & Trainees Management
        Route::get('/subscriptions/pending', [CoachSubscriptionController::class, 'pendingRequests']);
        Route::put('/subscriptions/{id}/accept', [CoachSubscriptionController::class, 'acceptRequest']);
        Route::put('/subscriptions/{id}/reject', [CoachSubscriptionController::class, 'rejectRequest']);
        Route::get('/trainees', [CoachSubscriptionController::class, 'myTrainees']);
        Route::get('/trainees/{trainee_id}', [CoachSubscriptionController::class, 'showTraineeDetails'])->middleware('trainee.access');

        // Certifications Route (المسار المطلوب)
        Route::get('/certifications', [CoachSubscriptionController::class, 'getCertifications']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
});

// Verification & Password Reset Routes
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);

// Admin
Route::prefix('admin')->group(
    base_path('routes/admin.php')
);

Route::prefix('v1')->group(function () {
    Route::get('/goals', [ProfileOptionController::class, 'getGoals']);
    Route::get('/activity-levels', [ProfileOptionController::class, 'getActivityLevels']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chat/{userId}', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'store']);
});