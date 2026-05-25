<?php

use App\Http\Controllers\Api\Customer\AuthController;
use Illuminate\Support\Facades\Route;

foreach ([
    'auth' => 'api.auth.',
    'authentication' => 'api.authentication.',
] as $prefix => $name) {
    Route::prefix($prefix)
        ->name($name)
        ->controller(AuthController::class)
        ->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/register', 'register')->name('register');
            Route::post('/logout', 'logout')->middleware('customer.api.token')->name('logout');
            Route::post('/forgot-password', 'forgotPassword')->name('forgot-password');
        });
}
