<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::post('/tasks', [TaskController::class, 'submit']);
Route::get('/tasks/{id}/status', [TaskController::class, 'status']);
Route::get('/tasks/{id}/result', [TaskController::class, 'result']);