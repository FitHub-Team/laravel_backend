<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
//try -> catch


// profile
// edit ( الوزن , الطول , الاسم ,الهدف , الصورة )





// reset pass

Route::post(
    '/email/verification-notification',
    [EmailVerificationController::class, 'resend']
);


// reset pass
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get(
    '/email/verify/{id}/{hash}',
    [EmailVerificationController::class, 'verifyEmail']
)
    ->middleware('signed')
    ->name('verification.verify');


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});
