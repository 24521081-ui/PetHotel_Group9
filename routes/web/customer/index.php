<?php

use Illuminate\Support\Facades\Route;

Route::prefix('profile')
    ->name('profile.')
    ->group(function () {
        require __DIR__.'/profile.php';
    });

Route::prefix('booking')
    ->name('booking.')
    ->group(function () {
        require __DIR__.'/booking.php';
    });

Route::prefix('payment')
    ->name('payment.')
    ->group(function () {
        require __DIR__.'/payment.php';
    });
