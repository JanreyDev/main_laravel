<?php

use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/location', [AuthController::class, 'index'])->name('location');


Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard')->middleware('auth');
