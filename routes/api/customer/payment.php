<?php

use App\Http\Controllers\Api\Customer\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('customer.api.token')
    ->prefix('customer/payment')
    ->name('api.customer.payment.')
    ->controller(PaymentController::class)
    ->group(function () {
        Route::get('/booking/{bookingId}', 'show')->name('show');
        Route::post('/booking/{bookingId}', 'process')->name('process');
        Route::get('/success', 'success')->name('success');
        Route::get('/failed', 'failed')->name('failed');
    });
