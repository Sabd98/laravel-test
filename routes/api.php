<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum', 'check.user.status'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::middleware(['can:view-users'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store'])->middleware('can:create-user');
    });

    // Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{task}', [TaskController::class, 'update'])->middleware('can:update,task');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('can:delete,task');
    });

    // Logs
    Route::get('/logs', [ActivityLogController::class, 'index'])->middleware('can:view-logs');
});