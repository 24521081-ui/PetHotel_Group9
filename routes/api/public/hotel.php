<?php

use App\Http\Controllers\Api\PublicContent\HotelController;
use Illuminate\Support\Facades\Route;

Route::prefix('pet-hotel')
    ->name('api.public.pet-hotel.')
    ->controller(HotelController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dogs', 'dogs')->name('dogs');
        Route::get('/cats', 'cats')->name('cats');
        Route::get('/{areaId}', 'show')->name('show');
    });
