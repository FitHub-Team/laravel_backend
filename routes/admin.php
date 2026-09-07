<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\TrainerController;


// ==========================================
// Admin Authentication
// ==========================================

Route::post(
    'login',
    [AdminAuthController::class, 'login']
);

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        'logout',
        [AdminAuthController::class, 'logout']
    );

});


// ==========================================
// Users Management API
// ==========================================

// عرض المستخدمين
Route::get(
    'users',
    [AdminUserController::class, 'index']
);

// تفاصيل مستخدم
Route::get(
    'users/{id}',
    [AdminUserController::class, 'show']
);

// تعديل مستخدم
Route::put(
    'users/{id}',
    [AdminUserController::class, 'update']
);

// حذف مستخدم
Route::delete(
    'users/{id}',
    [AdminUserController::class, 'destroy']
);


// ==========================================
// Trainers Management API
// ==========================================

Route::get(
    'trainers',
    [TrainerController::class, 'index']
);

Route::post(
    'trainers',
    [TrainerController::class, 'store']
);

Route::get(
    'trainers/{id}',
    [TrainerController::class, 'show']
);

Route::post(
    'trainers/{id}',
    [TrainerController::class, 'update']
);

Route::patch(
    'trainers/{id}/toggle-approval',
    [TrainerController::class, 'toggleApproval']
);

Route::delete(
    'trainers/{id}',
    [TrainerController::class, 'destroy']
);
