<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminPasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/location', [AuthController::class, 'index'])->name('location');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'submitForgotPasswordRequest'])->name('forgot-password.submit');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Admin Routes (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/password-reset-requests', [AdminPasswordResetController::class, 'index'])
        ->name('admin.password-reset.index');
    Route::post('/password-reset/{id}/set-password', [AdminPasswordResetController::class, 'setPassword'])
        ->name('admin.password-reset.set-password');
    Route::post('/password-reset/{id}/reject', [AdminPasswordResetController::class, 'reject'])
        ->name('admin.password-reset.reject');
});