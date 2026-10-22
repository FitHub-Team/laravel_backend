
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileOptionController;

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

    // Shared Routes
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
