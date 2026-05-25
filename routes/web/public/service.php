<?php

use App\Http\Controllers\Web\Default\RoomController;
use App\Http\Controllers\Web\Default\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phân hệ Dịch vụ & Phòng lưu trú (Services & Rooms)
|--------------------------------------------------------------------------
| Quản lý các luồng truy cập liên quan đến dịch vụ chăm sóc thú cưng 
| (Spa, Grooming) và hệ thống phòng lưu trú (Pet Hotel).
*/

Route::prefix('services')
    ->name('services.')
    ->group(function () {
        
        // 1. Giao diện tổng hợp dịch vụ
        // URL: GET /services
        Route::get('/', [ServiceController::class, 'index'])->name('index');

        /*
        |----------------------------------------------------------------------
        | Định tuyến tĩnh (Static Routes)
        | LƯU Ý: Luôn đặt các đường dẫn tĩnh cố định trước các đường dẫn động 
        | chứa tham số để tránh xung đột (Route Conflict).
        |----------------------------------------------------------------------
        */
        
        // URL: GET /services/spa
        Route::get('/spa', [ServiceController::class, 'spa'])->name('spa');
        
        // URL: GET /services/grooming
        Route::get('/grooming', [ServiceController::class, 'grooming'])->name('grooming');

        /*
        |----------------------------------------------------------------------
        | Phân hệ con: Loại phòng lưu trú (Nested Route Group)
        | Kế thừa prefix '/services' và tạo ra nhánh '/services/type-room'
        |----------------------------------------------------------------------
        */
        Route::prefix('type-room')
            ->name('room.')
            ->group(function () {
                
                // Hiển thị danh sách loại phòng
                // URL: GET /services/type-room
                Route::get('/', [RoomController::class, 'index'])->name('index');
                
                // Hiển thị chi tiết một phòng cụ thể
                // URL: GET /services/type-room/{roomId}
                Route::get('/{roomId}', [RoomController::class, 'show'])->name('show');
                
            });

        /*
        |----------------------------------------------------------------------
        | Định tuyến động (Dynamic Route)
        | Đặt ở cuối cùng đóng vai trò như một "Catch-all" cho nhánh /services
        |----------------------------------------------------------------------
        */
        
        // Hiển thị chi tiết một dịch vụ bất kỳ ngoài Spa/Grooming
        // URL: GET /services/{serviceId}
        Route::get('/{serviceId}', [ServiceController::class, 'show'])->name('show');
        
    });