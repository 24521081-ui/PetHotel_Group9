<?php

use App\Http\Controllers\Api\PublicContent\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->name('api.public.home');
