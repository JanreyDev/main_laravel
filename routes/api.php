<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProjectController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/projects', [ProjectController::class, 'store']);