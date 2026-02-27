<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// List tasks
Route::get('/tasks', [TaskController::class, 'index']);

// Get single task
Route::get('/tasks/{id}', [TaskController::class, 'show'])->whereUuid('id');

// Create task
Route::post('/tasks', [TaskController::class, 'store']);

// Update task
Route::put('/tasks/{id}', [TaskController::class, 'update'])->whereUuid('id');

// Delete task
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->whereUuid('id');
