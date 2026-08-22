<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//login with google
Route::post('/login/google',[AuthController::class,'loginWithGoogle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    //profile routes
    Route::prefix('profile')->group(function () {
        Route::post('/store', [ProfileController::class, 'store']);
        Route::get('/show', [ProfileController::class, 'show']);
        Route::put('/update ', [ProfileController::class, 'update']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
});


Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);



Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);

