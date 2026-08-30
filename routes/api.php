<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\coach\PackageController;
use App\Http\Controllers\Api\coach\AvailabilityController;
use App\Http\Controllers\Api\user\UserProfileController;
use App\Http\Controllers\Api\coach\SubscriptionController as CoachSubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

// Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // Trainee / User Profile Routes
    Route::prefix('profile/setting')->group(function () {
        Route::post('/store', [UserSettingProfileController::class, 'store']);
        Route::get('/show', [UserSettingProfileController::class, 'show']);
        Route::put('/update', [UserSettingProfileController::class, 'update']);
    });

    // Coach Routes
    Route::prefix('coach')->group(function () {
        
        // Coach Profile
        Route::prefix('profile')->group(function () {
            Route::post('/store', [CoachSettingProfileController::class, 'store']);
            Route::get('/show', [CoachSettingProfileController::class, 'show']);
            Route::post('/update', [CoachSettingProfileController::class, 'update']);
        });

        // Coach Packages
        Route::apiResource('packages', PackageController::class);

        // Coach Availabilities (Working Days & Slots)
        Route::get('/availabilities', [AvailabilityController::class, 'index']);
        Route::post('/availabilities', [AvailabilityController::class, 'store']);

    });

    // Coach Packages CRUD Routes
    Route::prefix('coach')->group(function () {
        Route::apiResource('packages', PackageController::class);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
    // Subscription & Trainees Management
        Route::get('/subscriptions/pending', [CoachSubscriptionController::class, 'pendingRequests']);
        Route::put('/subscriptions/{id}/accept', [CoachSubscriptionController::class, 'acceptRequest']);
        Route::put('/subscriptions/{id}/reject', [CoachSubscriptionController::class, 'rejectRequest']);
        Route::get('/trainees', [CoachSubscriptionController::class, 'myTrainees']);
        Route::get('/trainees/{trainee_id}', [CoachSubscriptionController::class, 'showTraineeDetails']);
});

// Public User Profile Routes
Route::prefix('user')->group(function () {
    Route::get('profile/{user}', [UserProfileController::class, 'index']);
    Route::get('profile/{user}/coaches', [UserProfileController::class, 'getCoaches']);
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

// Admin Routes
//Route::prefix('admin')->group(base_path('routes/admin.php'));
Route::prefix('admin')->group(base_path('routes/admin.php'));

// Public Routes
Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);
