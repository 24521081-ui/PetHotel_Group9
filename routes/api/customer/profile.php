<?php

use App\Http\Controllers\Api\Customer\ProfileController;
use Illuminate\Support\Facades\Route;
// Thêm gọi api lấy chi tiết booking
Route::middleware('customer.api.token')
    ->prefix('customer/profile')
    ->name('api.customer.profile.')
    ->controller(ProfileController::class)
    ->group(function () {
        // GET /api/customer/profile
        Route::get('/', 'account')->name('show');

        // GET /api/customer/profile/account
        Route::get('/account', 'account')->name('account');

        // PUT/PATCH /api/customer/profile/account/edit
        Route::match(['put', 'patch'], '/account/edit', 'updateAccount')->name('account.edit');

        // GET /api/customer/profile/history-booking
        Route::get('/history-booking', 'bookings')->name('history-booking');

        // GET /api/customer/profile/pets
        Route::get('/pets', 'pets')->name('pets.index');

        // POST /api/customer/profile/pets/add
        Route::post('/pets/add', 'storePet')->name('pets.store');
    });
