<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SessionController;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects', [ProjectController::class, 'index']);

Route::get('/sessions', [SessionController::class, 'index']);



Route::post('/register', [AuthController::class, 'register']);