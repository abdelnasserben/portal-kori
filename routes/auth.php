<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');

Route::middleware(['auth.portal'])->group(function () {
    Route::view('/auth/success', 'auth.success')->name('auth.success');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
