<?php

use App\Http\Controllers\Web\Manager\DashboardController;
use App\Http\Controllers\Web\Manager\InventoryController;
use App\Http\Controllers\Web\Manager\ReportController;
use App\Http\Controllers\Web\Manager\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phân hệ Quản lý (Manager Web Routes)
|--------------------------------------------------------------------------
| Chịu trách nhiệm cung cấp các Endpoint (điểm truy cập) cho Frontend UI.
| Quản lý các phân hệ cốt lõi: Bảng điều khiển, Dịch vụ, Kho bãi, Báo cáo.
| Lưu ý: Middleware bảo mật (auth + role:manager) đã được bọc từ file gốc.
*/

// Chuyển hướng mặc định khi truy cập nhánh thư mục gốc của Manager
Route::redirect('/', '/manager/dashboard')->name('index');

/*
|--------------------------------------------------------------------------
| Các Định tuyến Hiển thị Giao diện (View Routes)
|--------------------------------------------------------------------------
| Được căn chỉnh thẳng hàng để dễ dàng quan sát và bảo trì.
*/

// Giao diện Tổng quan (Theo dõi số liệu, lịch hẹn trong ngày...)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Giao diện Dịch vụ (Quản lý các gói Spa, Grooming, danh mục phòng lưu trú...)
Route::get('/services',  [ServiceController::class, 'index'])->name('services');

// Giao diện Kho vật tư (Quản lý lượng thức ăn thú cưng, vật dụng, sữa tắm...)
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

// Giao diện Báo cáo (Thống kê doanh thu, hiệu suất hoạt động...)
Route::get('/reports',   [ReportController::class, 'index'])->name('reports');