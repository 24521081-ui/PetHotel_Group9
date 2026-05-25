<?php

use App\Http\Controllers\Api\PublicContent\BranchController;
use Illuminate\Support\Facades\Route;

Route::prefix('branches')
    ->name('api.public.branches.')
    ->controller(BranchController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
        Route::get('/{branchId}', 'show')->name('show');
    });
