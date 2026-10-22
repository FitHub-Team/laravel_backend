
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;

// Registration and login
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/login/google', [
    AuthController::class,
    'loginWithGoogle',
]);

// Password reset
Route::post('/forgot-password', [
    PasswordResetController::class,
    'forgotPassword',
]);

Route::post('/reset-password', [
    PasswordResetController::class,
    'resetPassword',
]);

// Email verification
Route::post('/email/verification-notification', [
    EmailVerificationController::class,
    'resend',
]);

Route::get('/email/verify/{id}/{hash}', [
    EmailVerificationController::class,
    'verifyEmail',
])
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/verify-email', [
    EmailVerificationController::class,
    'verifyEmail',
]);

Route::post('/resend-verification', [
    EmailVerificationController::class,
    'resend',
]);
