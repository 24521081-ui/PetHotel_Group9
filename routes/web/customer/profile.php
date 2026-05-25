<?php

use App\Http\Controllers\Web\Customer\Profile\AccountController;
use App\Http\Controllers\Web\Customer\Profile\PetController;
use App\Http\Controllers\Web\Customer\Profile\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phân hệ Hồ sơ Khách hàng (Customer Profile Routes)
|--------------------------------------------------------------------------
| Quản lý không gian cá nhân của khách hàng: Thông tin tài khoản, 
| Thú cưng sở hữu và Lịch sử giao dịch.
| Lưu ý: File này thừa hưởng prefix '/customer/profile' từ hệ thống cha.
*/

// Chuyển hướng mặc định khi khách hàng truy cập thư mục gốc của Profile
Route::redirect('/', '/customer/profile/account')->name('index');

/*
|--------------------------------------------------------------------------
| 1. Quản lý Tài khoản (Account)
|--------------------------------------------------------------------------
*/
Route::prefix('account')
    ->name('account.')
    ->controller(AccountController::class)
    ->group(function () {
        Route::get('/', 'show')->name('show');       // Hiển thị form thông tin cá nhân
        Route::post('/', 'update')->name('update');  // Xử lý cập nhật thông tin
    });

/*
|--------------------------------------------------------------------------
| 2. Quản lý Thú cưng (Pets)
|--------------------------------------------------------------------------
*/
Route::prefix('pets')
    ->name('pets.')
    ->controller(PetController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');                 // Danh sách thú cưng
        Route::post('/', 'store')->name('store');                // Thêm mới thú cưng
        
        Route::get('/{petId}/edit', 'edit')->name('edit');       // Form chỉnh sửa thú cưng
        Route::post('/{petId}', 'update')->name('update');       // Lưu thông tin chỉnh sửa
    });

/*
|--------------------------------------------------------------------------
| 3. Lịch sử Giao dịch (Transactions)
|--------------------------------------------------------------------------
*/
Route::prefix('transactions')
    ->name('transactions.')
    ->controller(TransactionController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');                 // Danh sách lịch sử giao dịch
        Route::post('/filter', 'filter')->name('filter');        // Lọc giao dịch (theo ngày, trạng thái...)
    });