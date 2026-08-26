<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\CoachProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes (تسجيل ودخول)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

Route::post('/login', [AuthController::class, 'login'])->name('login');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Profile Routes
    Route::prefix('profile')->group(function () {
        Route::post('/store', [ProfileController::class, 'store']);
        Route::get('/show', [ProfileController::class, 'show']);
        Route::put('/update', [ProfileController::class, 'update']); 
    });

    Route::prefix('coach/profile')->group(function () {
        Route::put('/update', [CoachProfileController::class, 'update']);
        Route::get('/show', [CoachProfileController::class, 'show']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
});

// Password Reset Routes
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Email Verification Routes
Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);