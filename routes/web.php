<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backoffice\AdminsController;
use App\Http\Controllers\Backoffice\AgentsController;
use App\Http\Controllers\Backoffice\ClientsController;
use App\Http\Controllers\Backoffice\MerchantsController;
use App\Http\Controllers\Backoffice\TerminalsController;
use App\Http\Controllers\Backoffice\TransactionsController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');

// Tout ce qui suit nécessite une session portail (access_token en session)
Route::middleware(['auth.portal'])->group(function () {

    Route::view('/auth/success', 'auth.success')->name('auth.success');

    Route::middleware(['role:ADMIN'])->group(function () {
        Route::view('/admin', 'admin.home')->name('admin.home');

        Route::get('/admin/transactions', [TransactionsController::class, 'index'])
            ->name('admin.transactions.index');

        Route::get('/admin/merchants', [MerchantsController::class, 'index'])->name('admin.merchants.index');
        Route::get('/admin/merchants/new', [MerchantsController::class, 'create'])->name('admin.merchants.create');
        Route::post('/admin/merchants', [MerchantsController::class, 'store'])->name('admin.merchants.store');
        Route::get('/admin/merchants/{merchantCode}', [MerchantsController::class, 'show'])->name('admin.merchants.show');
        Route::post('/admin/merchants/{merchantCode}/status', [MerchantsController::class, 'updateStatus'])->name('admin.merchants.status.update');

        Route::get('/admin/terminals', [TerminalsController::class, 'index'])->name('admin.terminals.index');
        Route::get('/admin/terminals/new', [TerminalsController::class, 'create'])->name('admin.terminals.create');
        Route::post('/admin/terminals', [TerminalsController::class, 'store'])->name('admin.terminals.store');
        Route::get('/admin/terminals/{terminalUid}', [TerminalsController::class, 'show'])->name('admin.terminals.show');
        Route::post('/admin/terminals/status', [TerminalsController::class, 'updateStatus'])->name('admin.terminals.status.update');

        Route::get('/admin/admins', [AdminsController::class, 'index'])->name('admin.admins.index');
        Route::get('/admin/admins/new', [AdminsController::class, 'create'])->name('admin.admins.create');
        Route::post('/admin/admins', [AdminsController::class, 'store'])->name('admin.admins.store');
        Route::get('/admin/admins/{adminUsername}', [AdminsController::class, 'show'])->name('admin.admins.show');
        Route::post('/admin/admins/status', [AdminsController::class, 'updateStatus'])->name('admin.admins.status.update');

        Route::get('/admin/agents', [AgentsController::class, 'index'])->name('admin.agents.index');
        Route::get('/admin/agents/new', [AgentsController::class, 'create'])->name('admin.agents.create');
        Route::post('/admin/agents', [AgentsController::class, 'store'])->name('admin.agents.store');
        Route::get('/admin/agents/{agentCode}', [AgentsController::class, 'show'])->name('admin.agents.show');
        Route::post('/admin/agents/{agentCode}/status', [AgentsController::class, 'updateStatus'])->name('admin.agents.status.update');

        Route::get('/admin/clients', [ClientsController::class, 'index'])->name('admin.clients.index');
        Route::get('/admin/clients/{clientCode}', [ClientsController::class, 'show'])->name('admin.clients.show');
    });

    Route::middleware(['role:MERCHANT'])->group(function () {
        Route::view('/merchant', 'merchant.home')->name('merchant.home');
    });

    // Test API (peut échouer si token/claims API)
    Route::get('/health', HealthController::class)->name('health');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
