<?php

use App\Http\Controllers\Api\PublicContent\RoomController;
use App\Http\Controllers\Api\PublicContent\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('services')
    ->name('api.public.services.')
    ->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/spa', [ServiceController::class, 'spa'])->name('spa');
        Route::get('/grooming', [ServiceController::class, 'grooming'])->name('grooming');

        Route::prefix('type-room')
            ->name('room.')
            ->group(function () {
                Route::get('/', [RoomController::class, 'index'])->name('index');
                Route::get('/{roomId}', [RoomController::class, 'show'])->name('show');
            });

        Route::get('/{serviceId}', [ServiceController::class, 'show'])->name('show');
    });
