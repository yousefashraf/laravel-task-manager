<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware('auth:api')->group(function () {
    // task routes
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    // Task dependencies (Only managers can add/remove dependencies)
    Route::post('tasks/{task}/dependencies', [TaskController::class, 'addDependency'])->name('tasks.addDependency');
    Route::delete('tasks/{task}/dependencies', [TaskController::class, 'removeDependency'])->name('tasks.removeDependency');
});
