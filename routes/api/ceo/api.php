<?php

use App\Http\Controllers\Api\Ceo\BranchController;
use App\Http\Controllers\Api\Ceo\DashboardController;
use App\Http\Controllers\Api\Ceo\FinanceController;
use App\Http\Controllers\Api\Ceo\ServiceController;
use App\Http\Controllers\Api\Ceo\VendorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:ceo'])
    ->prefix('ceo')
    ->name('api.ceo.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/services', [ServiceController::class, 'index'])->name('services');
        Route::get('/branches', [BranchController::class, 'index'])->name('branches');
        Route::get('/vendors', [VendorController::class, 'index'])->name('vendors');
        Route::get('/finance', [FinanceController::class, 'index'])->name('finance');
    });
