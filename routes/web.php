<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PremiumController;

Route::get('/', function () {
    return view('welcome');
});

// Ubah rute dashboard untuk menggunakan DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('budgets', BudgetController::class);
    Route::resource('recurring-transactions', RecurringTransactionController::class);

    // Wallet routes, now accessible to all authenticated users
    Route::resource('wallets', WalletController::class);
    Route::post('wallets/{wallet}/set-default', [WalletController::class, 'setDefault'])->name('wallets.set-default');

    // Rute untuk Fitur Premium
    Route::get('/premium', [PremiumController::class, 'index'])
        ->middleware('role:premium,basic')
        ->name('premium.index');

    Route::post('/premium/upgrade', [PremiumController::class, 'upgrade'])
        ->middleware('role:basic')
        ->name('premium.upgrade');

    // Rute untuk Advanced Reporting (Fitur Premium)
    Route::get('/premium/reporting/advanced', [PremiumController::class, 'advancedReporting'])
        ->middleware('role:premium')
        ->name('premium.reporting.advanced');

    // New route for recalculating balances
    Route::post('/dashboard/recalculate-balances', [DashboardController::class, 'recalculateBalances'])
        ->name('dashboard.recalculate-balances');
});

require __DIR__.'/auth.php';
