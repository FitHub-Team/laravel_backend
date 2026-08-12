<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/onboarding/complete', [OnboardingController::class, 'completeProfile']);


    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });
});




// reset pass
    
    Route::post(
        '/email/verification-notification',
        [EmailVerificationController::class, 'resend']
    );
});




// reset pass 
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get(
    '/email/verify/{id}/{hash}',
    [EmailVerificationController::class, 'verifyEmail']
)
    ->middleware('signed')
    ->name('verification.verify');
