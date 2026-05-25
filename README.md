<p align="center">
  <a href="https://www.uit.edu.vn/" title="Trường Đại học Công nghệ Thông tin">
    <img src="https://i.imgur.com/WmMnSRt.png" alt="Trường Đại học Công nghệ Thông tin | University of Information Technology" width="750">
  </a>
</p>

<h1 align="center">ĐỒ ÁN</h1>
<h2 align="center">Môn học: HỆ QUẢN TRỊ CƠ SỞ DỮ LIỆU</h2>
<h3 align="center">Mã lớp: IS210.Q22</h3>
<h3 align="center">Đề tài: Hệ thống quản lý chuỗi khách sạn thú cưng</h3>
<h3 align="center">Nhóm thực hiện: Nhóm 9</h3>
<h3 align="center">GVHD: ThS. Đỗ Thị Minh Phụng</h3>

---

## NHÓM THỰC HIỆN

| STT | MSSV | Họ và tên |
|-----|------|-----------|
| 1 | 24521045 | Trần Đức Mạnh |
| 2 | 24521034 | Châu Gia Lương |
| 3 | 24521081 | Nguyễn Văn Minh |
| 4 | 24521093 | Nguyễn Thế Mỹ |

---

# Pet Hotel Laravel - Oracle Setup

Dự án **Pet Hotel** được xây dựng bằng **Laravel** và đã được chuẩn bị để chạy với **Oracle Database** thông qua package `yajra/laravel-oci8`.

Oracle Database được giả định là đã được cài đặt sẵn trên máy. Người clone project chỉ cần cấu hình đúng thông tin Oracle user/schema trong file `.env`, sau đó chạy migration, seeder và khởi động website.

---

## 1. Yêu cầu môi trường

Trước khi chạy project, cần đảm bảo máy đã cài đặt:

- PHP 8.2 trở lên
- Composer
- Node.js và npm
- Oracle Database service đang chạy, ví dụ `FREEPDB1`
- Oracle user/schema, ví dụ `PET_HOTEL`
- PHP extension `oci8` đã được bật
- Package `yajra/laravel-oci8` đã có trong project

Kiểm tra PHP extension `oci8`:

```bash
php -m
php --ri oci8
```

Nếu kết quả có hiển thị `oci8`, nghĩa là PHP đã nhận extension Oracle.

---

## 2. Clone project và di chuyển vào thư mục project

Clone source code từ GitHub:

```bash
git clone <repo-url>
cd pet-hotel
```

Nếu project nằm trong thư mục khác, cần di chuyển đúng vào thư mục chứa project.

Ví dụ trên Windows:

```bash
cd D:\test\PetHotel_Group9
```

Nếu terminal đang ở thư mục khác, có thể dùng lệnh `cd ../` để quay lại thư mục cha, sau đó dùng `cd <tên_thư_mục>` để vào đúng folder project.

Ví dụ:

```bash
cd ../
cd ../
cd test
cd PetHotel_Group9
```

Khi terminal hiển thị đúng đường dẫn project, có thể tiếp tục chạy các lệnh cài đặt.

---

## 3. Cài đặt dependency

Chạy các lệnh sau để cài đặt thư viện PHP và frontend:

```bash
composer install
npm install
npm run build
```

Sau đó tạo file môi trường `.env` từ file mẫu:

```bash
cp .env.example .env
```

Trên Windows PowerShell có thể copy file `.env` bằng:

```powershell
Copy-Item .env.example .env
```

Hoặc dùng Command Prompt:

```cmd
copy .env.example .env
```

Tiếp theo, tạo application key cho Laravel:

```bash
php artisan key:generate
```

---

## 4. Cấu hình Oracle trong file `.env`

Mở file `.env` và cập nhật phần cấu hình database theo Oracle trên máy cá nhân.

Ví dụ:

```env
DB_CONNECTION=oracle
DB_HOST=127.0.0.1
DB_PORT=1521
DB_DATABASE=FREEPDB1
DB_SERVICE_NAME=FREEPDB1
DB_TNS=
DB_USERNAME=PET_HOTEL
DB_PASSWORD=your_password
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=11g
ORA_MAX_NAME_LEN=30
```

Giải thích một số thông tin quan trọng:

| Biến cấu hình | Ý nghĩa |
|---|---|
| `DB_CONNECTION` | Loại database sử dụng. Với project này là `oracle` |
| `DB_HOST` | Địa chỉ Oracle Database, thường là `127.0.0.1` nếu chạy local |
| `DB_PORT` | Cổng Oracle, thường là `1521` |
| `DB_DATABASE` | Tên service/PDB Oracle, ví dụ `FREEPDB1` |
| `DB_SERVICE_NAME` | Tên service Oracle, thường giống `DB_DATABASE` |
| `DB_USERNAME` | Oracle user/schema dùng cho project |
| `DB_PASSWORD` | Mật khẩu của Oracle user/schema |
| `DB_CHARSET` | Bộ mã ký tự, nên dùng `AL32UTF8` để hỗ trợ tiếng Việt |
| `ORA_MAX_NAME_LEN` | Giới hạn độ dài tên object Oracle, nên để `30` |

Lưu ý:

- Các giá trị như `DB_DATABASE`, `DB_SERVICE_NAME`, `DB_USERNAME` và `DB_PASSWORD` phải được thay đổi theo cấu hình Oracle trên máy cá nhân.
- Không commit file `.env` thật hoặc password Oracle thật lên GitHub.

---

## 5. Tạo Oracle user/schema nếu chưa có

Nếu chưa có user/schema Oracle, có thể tạo bằng tài khoản có quyền DBA.

Ví dụ đăng nhập bằng tài khoản quản trị Oracle rồi chạy:

```sql
CREATE USER PET_HOTEL IDENTIFIED BY your_password;

GRANT CONNECT, RESOURCE TO PET_HOTEL;

ALTER USER PET_HOTEL QUOTA UNLIMITED ON USERS;
```

Trong đó:

| Thành phần | Ý nghĩa |
|---|---|
| `PET_HOTEL` | Tên user/schema Oracle dùng cho project |
| `your_password` | Mật khẩu của user/schema |
| `CONNECT` | Quyền kết nối vào Oracle |
| `RESOURCE` | Quyền tạo một số object như table, sequence, procedure |
| `QUOTA UNLIMITED ON USERS` | Cho phép user tạo dữ liệu trong tablespace `USERS` |

Sau khi tạo xong user/schema, cập nhật lại `.env`:

```env
DB_USERNAME=PET_HOTEL
DB_PASSWORD=your_password
```

---

## 6. Xóa cache cấu hình Laravel

Sau khi chỉnh file `.env`, chạy các lệnh sau để Laravel nhận lại cấu hình mới:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 7. Chạy migration, seeder và web

Chạy lệnh sau để tạo lại toàn bộ bảng và dữ liệu mẫu:

```bash
php artisan migrate:fresh --seed
```

Lệnh này sẽ:

- Xóa các bảng cũ nếu có
- Tạo lại database schema bằng migration
- Chạy seeder để thêm dữ liệu mẫu
- Tạo tài khoản demo, chi nhánh, phòng, khách hàng, thú cưng, booking, order, payment và các dữ liệu liên quan

Sau đó khởi động Laravel server:

```bash
php artisan serve
```

Mở trình duyệt và truy cập:

```text
http://127.0.0.1:8000
```

---

## 8. Tài khoản demo

Các tài khoản seed mặc định sử dụng chung mật khẩu:

```text
password123
```

Một số tài khoản demo có thể dùng:

```text
admin.demo@pethotel.test
manager.govap@pethotel.test
groomer.govap@pethotel.test
customer.small@pethotel.test
customer.medium@pethotel.test
customer.large@pethotel.test
customer.capacity@pethotel.test
```

Ngoài ra, người dùng cũng có thể tự tạo tài khoản khách hàng mới trực tiếp trên website thông qua trang đăng ký.

---

## 9. Luồng demo chính

Luồng demo tập trung vào chức năng phía khách hàng:

1. Đăng ký tài khoản khách hàng mới
2. Đăng nhập tài khoản khách hàng
3. Xem và cập nhật hồ sơ cá nhân
4. Xem danh sách thú cưng
5. Thêm thú cưng mới
6. Cập nhật thông tin thú cưng
7. Tạo booking đặt phòng
8. Chọn dịch vụ đi kèm
9. Áp dụng mã giảm giá
10. Thanh toán
11. Xem lịch sử booking
12. Xem chi tiết booking
13. Demo truy xuất đồng thời bằng hai tài khoản khách hàng

---

## 10. Một số đường dẫn thường dùng

```text
/authentication/register
/authentication/login
/profile
/profile/edit
/pets
/pets/create
/booking
/booking/branch/4
/payment/booking/{bookingId}
/profile/history-booking
/booking/{bookingId}
```

API kiểm tra phòng trống:

```text
/api/booking/branch/{branchId}/room-types/availability
```

---

## 11. Ghi chú về Oracle Migration

Project đã được cấu hình ưu tiên sử dụng Oracle để tránh chạy nhầm sang MySQL hoặc SQLite.

Các điểm đã cấu hình gồm:

- `.env.example` sử dụng Oracle làm database mặc định
- `config/database.php` fallback sang `oracle` và có connection `oracle`
- `config/queue.php` fallback database batch/failed jobs sang Oracle
- `composer.json` không tạo file SQLite mặc định
- `phpunit.xml` không ép test dùng SQLite in-memory

Vì vậy, khi clone project về, người dùng chỉ cần cấu hình đúng file `.env` theo Oracle trên máy cá nhân là có thể chạy migration, seeder và website.

---

## 12. Lệnh chạy nhanh

Nếu đã cài đủ môi trường và cấu hình `.env`, có thể chạy nhanh theo thứ tự sau:

```bash
git clone <repo-url>
cd pet-hotel
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate:fresh --seed
php artisan serve
```

Trên Windows PowerShell, nếu lệnh `cp` không chạy thì dùng:

```powershell
Copy-Item .env.example .env
```

Sau đó truy cập:

```text
http://127.0.0.1:8000
```

---

## 13. Ghi chú khi demo

Một số phần quản lý như Manager và CEO có thể đang là giao diện placeholder, nhưng API đã đọc dữ liệu thật từ database.

Luồng khách hàng, booking và payment là luồng chính dùng để demo. Đây là luồng có tạo dữ liệu thật và có xử lý transaction.

Trong nghiệp vụ đặt phòng, backend không được chỉ dựa vào dữ liệu phòng trống đang hiển thị trên giao diện. Khi khách hàng bấm xác nhận booking, hệ thống cần kiểm tra lại availability trong transaction và khóa phòng trước khi tạo booking, nhằm tránh việc hai khách hàng đặt trùng một phòng trong cùng thời gian.
