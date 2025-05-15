<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'checkUserStatus'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('can:view-users');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:create-user');

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware('can:manage-tasks,task');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/logs', [LogController::class, 'index'])->middleware('can:view-logs');
});