<?php

use App\Http\Controllers\Web\Ceo\BranchController;
use App\Http\Controllers\Web\Ceo\DashboardController;
use App\Http\Controllers\Web\Ceo\FinanceController;
use App\Http\Controllers\Web\Ceo\ServiceController;
use App\Http\Controllers\Web\Ceo\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phân hệ Giám đốc điều hành (CEO Web Routes)
|--------------------------------------------------------------------------
| Cung cấp các Endpoint cho Frontend UI dành riêng cho cấp quản lý cao nhất.
| Trọng tâm: Phân tích số liệu tổng thể, chuỗi chi nhánh và dòng tiền.
| Lưu ý: Middleware bảo mật (auth + role:ceo) và prefix '/ceo' đã được bọc từ file gốc.
*/

// Chuyển hướng thông minh khi truy cập thư mục gốc của phân hệ CEO
Route::redirect('/', '/ceo/dashboard')->name('index');

/*
|--------------------------------------------------------------------------
| Các Định tuyến Hiển thị Giao diện (View Routes)
|--------------------------------------------------------------------------
| Được căn chỉnh thẳng hàng để tối ưu hóa việc bảo trì và rà soát mã nguồn.
*/

// Giao diện Tổng quan 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Giao diện Dịch vụ 
Route::get('/services',  [ServiceController::class, 'index'])->name('services');

// Giao diện Chi nhánh 
Route::get('/branches',  [BranchController::class, 'index'])->name('branches');

// Giao diện Nhà cung cấp 
Route::get('/vendors',   [VendorController::class, 'index'])->name('vendors');

// Giao diện Tài chính 
Route::get('/finance',   [FinanceController::class, 'index'])->name('finance');