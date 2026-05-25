<?php

use App\Http\Controllers\Api\Customer\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('customer.api.token')
    ->prefix('customer/booking')
    ->name('api.customer.booking.')
    ->controller(BookingController::class)
    ->group(function () {
        // GET /api/customer/booking/form-data
        Route::get('/form-data', 'formData')->name('form-data');

        // GET /api/customer/booking/branch/{branchId}
        Route::get('/branch/{branchId}', 'formDataFromBranch')->name('from-branch');

        // GET /api/customer/booking
        Route::get('/', 'index')->name('index');
        
        // POST /api/customer/booking
        Route::post('/', 'store')->name('store');

        // GET /api/customer/booking/{bookingId}
        Route::get('/{bookingId}', 'show')->name('show');

        // PATCH /api/customer/booking/{bookingId}/cancel
        Route::patch('/{bookingId}/cancel', 'cancel')->name('cancel');
    });
