
<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;

use App\Http\Controllers\Api\user\SettingProfileController as UserSettingProfileController;
use App\Http\Controllers\Api\user\UserProfileController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;

use App\Http\Controllers\Api\coach\SettingProfileController as CoachSettingProfileController;
use App\Http\Controllers\Api\coach\AvailabilityController;
use App\Http\Controllers\Api\coach\WorkoutPlanController;
use App\Http\Controllers\Api\coach\NutritionPlanController;
use App\Http\Controllers\Api\coach\ProgressController;
use App\Http\Controllers\Api\coach\SubscriptionController as CoachSubscriptionController;

use App\Http\Controllers\Api\General\BrowseCoachController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

// Public Routes
// ------------------------
// Browse coaches
Route::get('/coaches', [BrowseCoachController::class, 'index']);
// Public coach profile
Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);
// Public user profile
Route::prefix('user')->group(function () {

    Route::get('/profile/{user}', [UserProfileController::class, 'index']);
    Route::get('/profile/{user}/coaches', [UserProfileController::class, 'getCoaches']);
});

// Email Verification & Password Reset


Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend']);
Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
Route::post('/resend-verification', [EmailVerificationController::class, 'resend']);
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

//Authenticated Routes


Route::middleware('auth:sanctum')->group(function () {

    // Common Authenticated Routes

    Route::post('/logout', [AuthController::class, 'logout']);

    // User / Trainee Routes


    Route::prefix('profile/setting')->group(function () {

        Route::get('/show', [ UserSettingProfileController::class,'show' ]);
        Route::put('/update', [ UserSettingProfileController::class, 'update']);
        Route::put('/update/avatar', [ UserSettingProfileController::class,'updateProfilePhoto']);
    });


    // Request subscription to a coach
    Route::post('/request-subscription', [RequestSubscriptionController::class,'requestSubscription']);

    // User's subscription requests
    Route::get('/my-requests', [ RequestSubscriptionController::class,'getMyRequests' ]);


     //فعكططططططططططططططططططططططططط-‘÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷÷++++++++Coach Routes
    //--------------------------------------------------------------------------
   

    Route::middleware('coach')
        ->prefix('coach')
        ->group(function () {
            // Coach Profile 
            Route::prefix('profile/setting')->group(function () {
                Route::get('/show', [CoachSettingProfileController::class,'show']);
                Route::post('/update', [CoachSettingProfileController::class,'update']);
                Route::put('/update/avatar', [CoachSettingProfileController::class,'updateProfilePhoto']);
            });

            // Coach Availability

            Route::get('/availabilities', [AvailabilityController::class,'index']);
            Route::post('/availabilities', [AvailabilityController::class,'store']);
            // Coach - Trainees
            Route::get('/trainees', [CoachSubscriptionController::class,'myTrainees']);
            Route::get('/trainees/{id}', [CoachSubscriptionController::class,'showTraineeDetails']);


            // Coach - Workout & Nutrition Plans
            Route::post('/trainees/{id}/workout-plan', [WorkoutPlanController::class,'storeOrUpdate']);
            Route::post('/trainees/{id}/nutrition-plan', [NutritionPlanController::class,'storeOrUpdate']);
            Route::get('/trainees/{id}/progress', [ProgressController::class,'show']);

            // Coach - Subscription Management

            Route::get('/subscriptions/pending', [CoachSubscriptionController::class,'pendingRequests']);
            Route::put('/subscriptions/{id}/accept', [CoachSubscriptionController::class,'acceptRequest']);
            Route::put('/subscriptions/{id}/reject', [CoachSubscriptionController::class,'rejectRequest']);
        });
});



//Admin Routes
Route::prefix('admin')
    ->group(base_path('routes/admin.php'));
