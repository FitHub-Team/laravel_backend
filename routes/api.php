<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\coach\PackageController;
use App\Http\Controllers\Api\coach\WorkoutPlanController;
use App\Http\Controllers\Api\coach\NutritionPlanController;
use App\Http\Controllers\Api\coach\ProgressController;
use App\Http\Controllers\Api\user\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

// Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Profile routes
    Route::prefix('profile/setting')->group(function () {
        Route::post('/store', [UserSettingProfileController::class, 'store']);
        Route::get('/show', [UserSettingProfileController::class, 'show']);
        Route::put('/update', [UserSettingProfileController::class, 'update']);
    });

    // Coach Profile Routes
    Route::prefix('coach/profile')->group(function () {
        Route::post('/store', [CoachSettingProfileController::class, 'store']);
        Route::get('/show', [CoachSettingProfileController::class, 'show']);
        Route::put('/update', [CoachSettingProfileController::class, 'update']);
    });

    // Coach Packages & Trainees Routes
    Route::prefix('coach')->group(function () {
        // Packages CRUD
        Route::apiResource('packages', PackageController::class);

        // Trainees & Plans Routes
        Route::get('/trainees/{id}', [CoachSettingProfileController::class, 'showTraineeDetails']);
        Route::post('/trainees/{id}/workout-plan', [WorkoutPlanController::class, 'storeOrUpdate']);
        Route::post('/trainees/{id}/nutrition-plan', [NutritionPlanController::class, 'storeOrUpdate']);
        Route::get('/trainees/{id}/progress', [ProgressController::class, 'show']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
});

// User profile
Route::prefix('user')->group(function () {
    Route::get('profile/{user}', [UserProfileController::class, 'index']);
    Route::get('profile/{user}/coaches', [UserProfileController::class, 'getCoaches']);
});

// Reset Pass
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);

// Admin Routes
Route::prefix('admin')->group(base_path('routes/admin.php'));

// Public Routes
Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);