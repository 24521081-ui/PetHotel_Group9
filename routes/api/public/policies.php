<?php

use App\Http\Controllers\Api\PublicContent\PolicyController;
use Illuminate\Support\Facades\Route;

Route::prefix('policies')
    ->name('api.public.policies.')
    ->controller(PolicyController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{policyId}', 'show')->name('show');
    });
