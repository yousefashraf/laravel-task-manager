<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

// Group API routes with authentication middleware
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tasks', [TaskController::class, 'index'])->middleware('permission:task.view');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('permission:task.create');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->middleware('permission:task.view');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware('permission:task.update');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->middleware('permission:task.assign');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->middleware('permission:task.status.update');
});
