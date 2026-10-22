
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileOptionController;
use App\Http\Controllers\Api\coach\RecipeController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Protected Routes - Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | General Browsing Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/coaches', [BrowseCoachController::class, 'index']);
    Route::get('/coaches/{id}', [CoachSettingProfileController::class, 'showPublicProfile']);
    Route::get('/coaches/{id}/reviews', [BrowseCoachController::class, 'getReviews']);
    Route::get('/coaches/{id}/subscription-status', [BrowseCoachController::class, 'getSubscriptionStatus']);
    Route::get('/coaches/{id}/subscription-details', [BrowseCoachController::class, 'getSubscriptionDetails']);

Route::middleware('auth:sanctum')->group(function () {

    // Shared routes for authenticated users
    require __DIR__ . '/shared.php';

    // Trainee Routes
    Route::prefix('trainee')
        ->middleware('user')
        ->group(__DIR__ . '/trainee.php');

    // Coach Routes
    Route::prefix('coach')
        ->middleware('coach')
        ->group(__DIR__ . '/coach.php');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->group(__DIR__ . '/admin.php');

/*
|--------------------------------------------------------------------------
| Profile Options
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    Route::get('/goals', [
        ProfileOptionController::class,
        'getGoals',
    ]);

    Route::get('/activity-levels', [
        ProfileOptionController::class,
        'getActivityLevels',
    ]);
});
/*
|--------------------------------------------------------------------------
| Chat
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chat/{userId}', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'store']);
});
