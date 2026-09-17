<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\GoalController;
use App\Http\Controllers\Api\Admin\ActivityLevelController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\TrainerController;
use App\Http\Controllers\Api\Admin\AdminUserController;

use Illuminate\Support\Facades\Route;

// ==========================================
// Reset Password
// ==========================================
Route::get('/reset-password', function () {
    return response()->json([
        'message' => 'Reset password page',
        'note' => 'Use token and email from URL'
    ]);
});

// ==========================================
// Admin Routes Group
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Login & Logout
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'loginWeb'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logoutWeb'])->name('logout');

    // Dashboard
    Route::get('/', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.page');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'userManage'])->name('users.manage');

    // Trainers Management
    Route::get('/trainers', [TrainerController::class, 'trainerManage'])->name('trainer.manage');
    Route::get('/trainers/{id}/details', [TrainerController::class, 'getDetails'])->name('trainer.details');
    Route::post('/trainers/{id}/toggle-approval', [TrainerController::class, 'toggleApproval'])->name('trainer.toggleApproval');
    Route::delete('/trainers/{id}', [TrainerController::class, 'destroy'])->name('trainer.destroy');

    // Coaches Requests & Actions
    Route::get('/coaches/requests', [TrainerController::class, 'coachRequests'])->name('coaches.requests');
    Route::post('/coaches/{id}/approve', [TrainerController::class, 'approve'])->name('coaches.approve');
    Route::post('/coaches/{id}/reject', [TrainerController::class, 'reject'])->name('coaches.reject');

    // ==========================================
    // Sports Profile Management (New Views & Actions)
    // ==========================================

    // Activity Levels Management
    Route::get('/activity-level', [ActivityLevelController::class, 'index'])->name('activity-level.manage');
    Route::post('/activity-level', [ActivityLevelController::class, 'store'])->name('activity-level.store');
    Route::patch('/activity-level/{activityLevel}/toggle', [ActivityLevelController::class, 'toggleActive'])->name('activity-level.toggle');
    Route::delete('/activity-level/{activityLevel}', [ActivityLevelController::class, 'destroy'])->name('activity-level.destroy');

    // Goals Management
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.manage');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::patch('/goals/{goal}/toggle', [GoalController::class, 'toggleActive'])->name('goals.toggle');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    // باقي الشاشات مؤقتاً لحين تجهيز Controllers خاصة بها
    Route::get('/health-restrictions', function () {
        return view('admin.health-restrictionsManage');
    })->name('health-restrictions.manage');

    Route::get('/preferences', function () {
        return view('admin.preferencesManage');
    })->name('preferences.manage');

    Route::get('/skills', function () {
        return view('admin.skillsManage');
    })->name('skills.manage');

});

// Clear Cache Route
Route::get('/clear-all-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return 'Done! All cache cleared successfully.';
});
