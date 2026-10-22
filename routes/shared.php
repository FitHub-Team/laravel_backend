
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\General\BrowseCoachController;

use App\Http\Controllers\Api\coach\SettingProfileController
    as CoachSettingProfileController;

use App\Http\Controllers\Api\user\HealthInformationController;
use App\Http\Controllers\Api\user\TraineeGoalController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;

use App\Http\Controllers\Api\user\WorkoutPlanController
    as UserWorkoutPlanController;

// Logout
Route::post('/logout', [
    AuthController::class,
    'logout',
]);

// Browse coaches
Route::get('/coaches', [
    BrowseCoachController::class,
    'index',
]);

Route::get('/coaches/{id}', [
    CoachSettingProfileController::class,
    'showPublicProfile',
]);

// Health information
Route::prefix('health-information')->group(function () {

    Route::get('/show', [
        HealthInformationController::class,
        'show',
    ]);

    Route::put('/update', [
        HealthInformationController::class,
        'update',
    ]);
});

// Goal information
Route::prefix('goal-information')->group(function () {

    Route::get('/show', [
        TraineeGoalController::class,
        'show',
    ]);

    Route::put('/update', [
        TraineeGoalController::class,
        'update',
    ]);
});

// Subscription requests
Route::post('/subscription-request', [
    RequestSubscriptionController::class,
    'requestSubscription',
]);

Route::get('/subscription-requests', [
    RequestSubscriptionController::class,
    'getMyRequests',
]);

// Shared workout plans endpoint
Route::get('/workout-plans', [
    UserWorkoutPlanController::class,
    'index',
]);

// Current authenticated user
Route::get('/user', function (Request $request) {

    return response()->json([
        'status' => true,
        'user' => $request->user(),
    ]);
});

// Chat
Route::get('/chat/{userId}', [
    ChatController::class,
    'index',
]);

Route::post('/chat/send', [
    ChatController::class,
    'store',
]);
