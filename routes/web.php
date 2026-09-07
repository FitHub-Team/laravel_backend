<?php

use App\Http\Controllers\Admin\DashboardController;
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
// Admin Routes
// ==========================================

Route::prefix('admin')->group(function () {

    // ==========================================
    // Login
    // ==========================================

    Route::get(
        '/login',
        [AdminAuthController::class, 'showLoginForm']
    )->name('admin.login');

    Route::post(
        '/login',
        [AdminAuthController::class, 'loginWeb']
    )->name('admin.login.submit');

    Route::post(
        '/logout',
        [AdminAuthController::class, 'logoutWeb']
    )->name('admin.logout');

    // ==========================================
    // Dashboard
    // ==========================================

    Route::get(
        '/',
        [AdminAuthController::class, 'dashboard']
    )->name('admin.dashboard');


    // ==========================================
    // Users Management
    // ==========================================

    // صفحة إدارة المستخدمين
    Route::get(
        '/users',
        [AdminUserController::class, 'userManage']
    )->name('admin.users.manage');


    // ==========================================
    // Trainers Management
    // ==========================================

    // صفحة إدارة المدربين
    Route::get(
        '/trainers',
        [TrainerController::class, 'trainerManage']
    )->name('admin.trainer.manage');

});


// ==========================================
// Dashboard
// ==========================================

Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');


// ==========================================
// Coaches Requests & Actions
// ==========================================

// عرض طلبات المدربين
Route::get(
    '/admin/coaches/requests',
    [TrainerController::class, 'coachRequests']
)->name('admin.coaches.requests');


// الموافقة على المدرب
Route::post(
    '/admin/coaches/{id}/approve',
    [TrainerController::class, 'approve']
)->name('admin.coaches.approve');


// رفض المدرب
Route::post(
    '/admin/coaches/{id}/reject',
    [TrainerController::class, 'reject']
)->name('admin.coaches.reject');


// ==========================================
// Trainer Details
// ==========================================

// تفاصيل المدرب
Route::get(
    '/admin/trainers/{id}/details',
    [TrainerController::class, 'getDetails']
)->name('admin.trainer.details');


// تغيير حالة اعتماد المدرب
Route::post(
    '/admin/trainers/{id}/toggle-approval',
    [TrainerController::class, 'toggleApproval']
)->name('admin.trainer.toggleApproval');


// حذف المدرب
Route::delete(
    '/admin/trainers/{id}',
    [TrainerController::class, 'destroy']
)->name('admin.trainer.destroy');
