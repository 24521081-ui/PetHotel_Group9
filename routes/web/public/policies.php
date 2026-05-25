<?php

use App\Http\Controllers\Web\Default\PolicyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Policies Routes (Các chính sách của hệ thống)
|--------------------------------------------------------------------------
| Định tuyến hiển thị các trang văn bản pháp lý, điều khoản hoặc quy định.
| Khu vực này thường là Public, cho phép tất cả người dùng truy cập.
*/

Route::prefix('policies')
    ->name('policies.')
    ->group(function () {
        
        // 1. Hiển thị danh sách hoặc trang chính tổng hợp các chính sách
        // URL: GET /policies
        Route::get('/', [PolicyController::class, 'index'])->name('index');

        // 2. Hiển thị nội dung chi tiết của một chính sách cụ thể
        // URL: GET /policies/{policyId}
        Route::get('/{policyId}', [PolicyController::class, 'show'])->name('show');
    });