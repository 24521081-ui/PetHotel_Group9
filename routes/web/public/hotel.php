<?php

use App\Http\Controllers\Web\Default\HotelController;
use Illuminate\Support\Facades\Route;

Route::prefix('pet-hotel')->name('pet-hotel.')->group(function () {
    Route::get('/', [HotelController::class, 'index'])->name('index');
    Route::get('/dogs', [HotelController::class, 'dogs'])->name('dogs');
    Route::get('/cats', [HotelController::class, 'cats'])->name('cats');
    Route::get('/{areaId}', [HotelController::class, 'show'])->name('show');
});
