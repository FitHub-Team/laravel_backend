<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\coach\PackageController;
use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
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
    //profile routes
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
});
// user profile
Route::prefix('user')->group(function () {
    Route::get('profile/{user}', [UserProfileController::class, 'index']);
    Route::get('profile/{user}/coaches', [UserProfileController::class, 'getCoaches']);
});
// coach profile



// reset pass

Route::post(
    '/email/verification-notification',
    [EmailVerificationController::class, 'resend']
);


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