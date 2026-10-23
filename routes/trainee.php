<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\user\SettingProfileController
    as UserSettingProfileController;

use App\Http\Controllers\Api\user\UserProfileController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;

use App\Http\Controllers\Api\user\WorkoutPlanController
    as UserWorkoutPlanController;

use App\Http\Controllers\Api\user\NutritionPlanController
    as UserNutritionPlanController;

use App\Http\Controllers\Api\user\TraineeProgressController;


use App\Http\Controllers\Api\user\DashboardController as UserDashboardController;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [
    UserDashboardController::class,
    'index',
]);

/*
|--------------------------------------------------------------------------
| Profile Settings
|--------------------------------------------------------------------------
*/

Route::prefix('profile/setting')->group(function () {

    Route::get('/show', [
        UserSettingProfileController::class,
        'show',
    ]);

    Route::put('/update', [
        UserSettingProfileController::class,
        'update',
    ]);

    Route::put('/update/avatar', [
        UserSettingProfileController::class,
        'updateProfilePhoto',
    ]);

    Route::delete('/update/avatar/delete', [
        UserSettingProfileController::class,
        'deleteProfilePhoto',
    ]);
});

/*
|--------------------------------------------------------------------------
| User Profile
|--------------------------------------------------------------------------
*/

Route::prefix('profile')->group(function () {

    Route::get('/', [
        UserProfileController::class,
        'index',
    ]);

    Route::get('/{user}/coaches', [
        UserProfileController::class,
        'getCoaches',
    ]);
});

/*
|--------------------------------------------------------------------------
| Subscription
|--------------------------------------------------------------------------
*/

Route::post('/subscription-request', [
    RequestSubscriptionController::class,
    'requestSubscription',
]);

Route::get('/subscription-requests', [
    RequestSubscriptionController::class,
    'getMyRequests',
]);

/*
|--------------------------------------------------------------------------
| Workout Plans
|--------------------------------------------------------------------------
*/

Route::get('/workout-plans', [
    UserWorkoutPlanController::class,
    'index',
]);

/*
|--------------------------------------------------------------------------
| Nutrition Plans
|--------------------------------------------------------------------------
*/

Route::get('/nutrition-plans', [
    UserNutritionPlanController::class,
    'index',
]);

/*
|--------------------------------------------------------------------------
| Progress
|--------------------------------------------------------------------------
*/

Route::prefix('progress')->group(function () {

    Route::get('/', [
        TraineeProgressController::class,
        'index',
    ]);

    Route::post('/', [
        TraineeProgressController::class,
        'store',
    ]);

    Route::put('/{id}/upload-photo', [
        TraineeProgressController::class,
        'updatePhoto',
    ]);
});