<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');

Route::middleware(['auth.portal'])->group(function () {
    Route::view('/auth/success', 'auth.success')->name('auth.success');

    Route::get('/health', HealthController::class)->name('health');

    // Logout complet (local + Keycloak)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
