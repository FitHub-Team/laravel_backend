<?php

use Illuminate\Support\Facades\Route;

// AI Controllers
use App\Http\Controllers\AI\AiCalculateCalories;
use App\Http\Controllers\AI\AIWorkoutPlanController;
use App\Http\Controllers\AI\NutritionPlanController
as AINutritionPlanController;

// Coach Controllers
use App\Http\Controllers\Api\coach\DashboardController;
use App\Http\Controllers\Api\coach\SettingProfileController
as CoachSettingProfileController;
use App\Http\Controllers\Api\coach\AvailabilityController;
use App\Http\Controllers\Api\coach\SubscriptionController
as CoachSubscriptionController;
use App\Http\Controllers\Api\coach\NutritionPlanController
as CoachNutritionPlanController;
use App\Http\Controllers\Api\coach\ProgressController;
use App\Http\Controllers\Api\coach\RecipeController;

// Workout Controllers
use App\Http\Controllers\Api\coach\WorkoutPlan\WorkoutPlanController;
use App\Http\Controllers\Api\coach\WorkoutPlan\ExerciseController;
use App\Http\Controllers\Api\coach\WorkoutPlan\CustomExerciseController;
use App\Http\Controllers\Api\user\RequestSubscriptionController;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [
    DashboardController::class,
    'index',
]);

/*
|--------------------------------------------------------------------------
| Profile Settings
|--------------------------------------------------------------------------
*/

Route::prefix('profile/setting')->group(function () {

    Route::get('/show', [
        CoachSettingProfileController::class,
        'show',
    ]);

    Route::post('/update', [
        CoachSettingProfileController::class,
        'update',
    ]);

    Route::post('/update/avatar', [
        CoachSettingProfileController::class,
        'updateProfilePhoto',
    ]);

    Route::delete('/delete/avatar', [
        CoachSettingProfileController::class,
        'deleteProfilePhoto',
    ]);
});

/*
|--------------------------------------------------------------------------
| AI Generation
|--------------------------------------------------------------------------
*/

Route::prefix('ai/generate')->group(function () {

    Route::post('/nutrition-plan', [
        AINutritionPlanController::class,
        'generateNutrition',
    ]);

    Route::post('/workout-plan/{trainee}', [
        AIWorkoutPlanController::class,
        'generateWorkout',
    ]);

    Route::post('/calculate-calories', [
        AiCalculateCalories::class,
        'generateCalculate',
    ]);
});

/*
|--------------------------------------------------------------------------
| Subscription Requests
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
| Coach Availability
|--------------------------------------------------------------------------
*/

Route::prefix('availabilities')->group(function () {

    Route::get('/', [
        AvailabilityController::class,
        'index',
    ]);

    Route::post('/', [
        AvailabilityController::class,
        'store',
    ]);
});

/*
|--------------------------------------------------------------------------
| Subscriptions Management
|--------------------------------------------------------------------------
*/

Route::get('/subscriptions/pending', [
    CoachSubscriptionController::class,
    'pendingRequests',
]);

Route::put('/subscriptions/{id}/accept', [
    CoachSubscriptionController::class,
    'acceptRequest',
]);

Route::put('/subscriptions/{id}/reject', [
    CoachSubscriptionController::class,
    'rejectRequest',
]);

/*
|--------------------------------------------------------------------------
| Trainees
|--------------------------------------------------------------------------
*/

Route::get('/trainees', [
    CoachSubscriptionController::class,
    'myTrainees',
]);

Route::get('/trainees/{trainee_id}', [
    CoachSubscriptionController::class,
    'showTraineeDetails',
])->middleware('trainee.access');

/*
|--------------------------------------------------------------------------
| Current Workout Plans
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'workout-plans'], function () {
    Route::get('/', [
        WorkoutPlanController::class,
        'getPlansByCoach',
    ]);

    Route::patch('/{planId}/send', [
        WorkoutPlanController::class,
        'send',
    ]);

    Route::delete('/{planId}', [
        WorkoutPlanController::class,
        'destroyDraft',
    ]);

    Route::patch('/{planId}/cancel', [
        WorkoutPlanController::class,
        'cancel',
    ]);
      Route::get('/versions', [WorkoutPlanController::class, 'getVersionsByCoach']);
});


/*
|--------------------------------------------------------------------------
| Trainee Plans and Versions
|--------------------------------------------------------------------------
*/

Route::prefix('trainees')->group(function () {

    // Existing alternate trainee details route.
    // Review duplication with /trainees/{trainee_id}.
    Route::get('/{id}', [
        CoachSettingProfileController::class,
        'showTraineeDetails',
    ])->middleware('trainee.access');

    // Get trainee workout plan
    Route::get('/{traineeId}/workout-plan', [
        WorkoutPlanController::class,
        'index',
    ]);

    // Create or update workout plan
    Route::post('/{id}/workout-plan', [
        WorkoutPlanController::class,
        'storeOrUpdate',
    ]);

    // Existing compatibility route
    Route::post('/{id}/workout-plan/update', [
        WorkoutPlanController::class,
        'storeOrUpdate',
    ]);

    // Nutrition plan
    Route::post('/{id}/nutrition-plan', [
        CoachNutritionPlanController::class,
        'storeOrUpdate',
    ]);

    // Progress
    Route::get('/{id}/progress', [
        ProgressController::class,
        'show',
    ]);

    // Workout versions
    Route::get('/{traineeId}/workout-plan/versions', [
        WorkoutPlanController::class,
        'versions',
    ]);

    // Specific workout version
    Route::get('/{traineeId}/workout-plan/versions/{planId}', [
        WorkoutPlanController::class,
        'showVersion',
    ]);
});
// 

/*
|--------------------------------------------------------------------------
| Exercise Library
|--------------------------------------------------------------------------
*/

Route::get('/exercises', [
    ExerciseController::class,
    'index',
]);

/*
|--------------------------------------------------------------------------
| Custom Exercises
|--------------------------------------------------------------------------
*/

Route::apiResource(
    'custom-exercises',
    CustomExerciseController::class
);

/*
|--------------------------------------------------------------------------
| Recipes
|--------------------------------------------------------------------------
*/

Route::prefix('recipes')->group(function () {

    Route::get('/', [
        RecipeController::class,
        'index',
    ]);

    Route::post('/', [
        RecipeController::class,
        'store',
    ]);

    Route::get('/{id}', [
        RecipeController::class,
        'show',
    ]);

    Route::put('/{id}', [
        RecipeController::class,
        'update',
    ]);

    Route::delete('/{id}', [
        RecipeController::class,
        'destroy',
    ]);
});
