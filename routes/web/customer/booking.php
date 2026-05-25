<?php

use App\Http\Controllers\Web\Customer\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Booking Routes
|--------------------------------------------------------------------------
|
| Nhóm route xử lý chức năng đặt phòng của khách hàng:
| - Tạo đặt phòng mới
| - Tạo đặt phòng từ chi nhánh cụ thể
| - Xem chi tiết đặt phòng trong lịch sử
| - Lưu thông tin đặt phòng
|
*/

Route::controller(BookingController::class)->group(function () {
    Route::get('/', 'create')->name('create'); // Trang tạo mới đặt phòng

    Route::get('/branch/{branchId}', 'createFromBranch')->name('from-branch'); // Tạo đặt phòng từ trang chi tiết chi nhánh

    Route::get('/{bookingId}', 'show')->name('show'); // Trang hiển thị thông tin chi tiết của một booking cụ thể trong lịch sử đặt phòng của khách hàng

    Route::post('/', 'store')->name('store'); // Xử lý lưu thông tin đặt phòng mới, Trigger là khi khách hàng submit form đặt phòng
});