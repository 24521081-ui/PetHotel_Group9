# Demo Script - Pet Hotel Web

Tai lieu nay duoc lap tu code hien co trong project Laravel `pet-hotel`: cac file route trong `routes/web`, `routes/api`, controller trong `app/Http/Controllers`, model trong `app/Models`, migration trong `database/migrations`, seeder trong `database/seeders`, view Blade trong `resources/views`, JS/CSS trong `public/assets`.

## 1. Chuan bi truoc demo

### 1.1. Chay project

Theo `README.md`, project duoc cau hinh uu tien Oracle, nhung co the dung MySQL neu `.env` hien tai dang tro toi MySQL.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Mo web:

```text
http://127.0.0.1:8000
```

Ghi chu khi kiem tra tren may hien tai: lenh `php artisan route:list` khong chay duoc vi `php` chua co trong PATH. Khi demo tren may co PHP, nen chay lai `php artisan route:list` de doi chieu route.

### 1.2. Seeder va du lieu mau

`database/seeders/DatabaseSeeder.php` dang goi:

- `CleanupSeeder`
- `UserSeeder`
- `BranchRoomSeeder`
- `ProductServiceSeeder`
- `PeoplePetSeeder`
- `CouponInventorySeeder`
- `BookingSeeder`
- `OrderSeeder`
- `AuditLogSeeder`
- `OracleSequenceSeeder`

Du lieu demo hien co da du:

- 4 chi nhanh: Quan 1, Thu Duc, Quan 7, Go Vap.
- 3 loai phong: `type_room_id` 1, 2, 3, ung voi normal/vip/luxury tren giao dien.
- Nhieu phong moi chi nhanh, gom phong AVAILABLE va MAINTENANCE.
- Tai khoan admin, manager, groomer/nhan vien, customer.
- Khach hang va thu cung mau.
- Dich vu, san pham, ton kho chi nhanh.
- Booking, booking_room, booking_room_pet, booking_service_pet.
- Orders, order_details, payments, coupon, booking_coupon_log.

### 1.3. Tai khoan demo

Tat ca tai khoan seed dung mat khau:

```text
password123
```

| Vai tro | Email | Route sau dang nhap |
|---|---|---|
| Admin/CEO | `admin.demo@pethotel.test` | `/ceo/dashboard` |
| Quan ly chi nhanh | `manager.govap@pethotel.test` | `/manager/dashboard` |
| Quan ly Quan 1 | `manager.q1@pethotel.test` | `/manager/dashboard` |
| Nhan vien/groomer | `groomer.govap@pethotel.test` | `/manager/dashboard` |
| Khach hang | `customer.small@pethotel.test` | `/` |
| Khach hang phong vua | `customer.medium@pethotel.test` | `/` |
| Khach hang booking hoan tat | `customer.large@pethotel.test` | `/` |

## 2. Khao sat chuc nang theo vai tro

### 2.1. Khach vang lai

| Chuc nang | URL/route | Controller | View | Bang lien quan | Dau vao | Ket qua mong doi |
|---|---|---|---|---|---|---|
| Trang chu | `GET /`, `home` | `Web\Default\HomeController@index` | `client.home.index` | Khong truy van DB trong controller | Khong co | Hien thi trang chu |
| Danh sach chi nhanh | `GET /branches`, `branches.index` | `Web\Default\BranchController@index` | `client.branches.index` + partials | `branch` | `search`, `keyword`, `district` | Hien thi chi nhanh dang active, co loc tim kiem/khu vuc |
| Chi tiet chi nhanh | `GET /branches/{branchId}` | `Web\Default\BranchController@show` | `client.branches.show` | `branch`, `type_room`, `room` | `branchId` | Hien thong tin chi nhanh, loai phong tai chi nhanh |
| Dich vu | `GET /services`, `/services/spa`, `/services/grooming`, `/services/{serviceId}` | `Web\Default\ServiceController` | `client.services.grooming` | Hien tai web controller khong query DB | `serviceId` neu co | Hien trang dich vu/grooming |
| Loai phong public | `GET /services/type-room` | `Web\Default\RoomController@index` | `client.rooms.dog` | Khong query DB o action index | Khong co | Hien trang phong cho |
| Phong cho/meo | `GET /rooms/dog`, `/rooms/cat`, `/pet-hotel/dogs`, `/pet-hotel/cats` | `RoomController`, `HotelController` | `client.rooms.dog`, `client.rooms.cat` | Khong query DB o action dog/cat | Khong co | Hien trang phong theo loai thu cung |
| Chi tiet loai phong theo species | `GET /rooms/{type}/{species}` | `RoomController@showByTypeAndSpecies` | `client.rooms.type-room` hoac `rooms.normal.dog` | `type_room`, `room`, `booking_room`, `booking`, `services` | `type` normal/vip/luxury, `species` dog/cat, `check_in`, `check_out` | Hien phong theo loai, tinh phong trong theo ngay |
| Chi tiet type room | `GET /type-room/{typeRoomId}` | `RoomController@typeRoom` | `client.rooms.type-room` | `type_room`, `room`, `services` | `typeRoomId` | Hien thong tin loai phong va danh sach phong |
| Dang ky | `GET/POST /authentication/register` | `Authentication\RegisterController` | `auth.register` | `users`, `customer` | `full_name`, `email`, `phone`, `address`, `password`, `password_confirmation` | Tao user role CUSTOMER, tao customer, dang nhap |
| Dang nhap | `GET/POST /authentication/login` | `Authentication\LoginController` | `auth.login` | `users`, `customer`, `employee` | `email`, `password`, `remember` | Dang nhap, dieu huong theo role |
| Quen/dat lai mat khau | `GET/POST /authentication/forgot-password`, `/reset-password` | `ForgotPasswordController`, `ResetPasswordController` | `auth.forgot-password`, `auth.reset-password` | Chua xu ly DB that trong controller | Form | Tra ve thong bao placeholder |

### 2.2. Khach hang da dang nhap

| Chuc nang | URL/route | Controller | View | Bang lien quan | Dau vao | Ket qua mong doi |
|---|---|---|---|---|---|---|
| Xem ho so | `GET /profile`, `/customer/profile/account` | `Profile\AccountController@show` | `client.profile.index` | `users`, `customer`, `employee`, `branch` | Session dang nhap | Hien thong tin ca nhan |
| Sua ho so | `GET /profile/edit`, `POST /profile` | `Profile\AccountController@edit/update` | `client.profile.edit` | `users`, `customer`, `employee` | Ten, email, phone, birthday, address, avatar, password moi | Cap nhat profile trong transaction |
| Xem thu cung | `GET /pets`, `/profile/pets`, `/customer/profile/pets` | `Profile\PetController@index` | `client.pets.index`, `_list` | `pet`, `booking_room_pet`, `booking_room`, `booking` | Session dang nhap | Hien thu cung va trang thai dang o phong |
| Them thu cung | `GET /pets/create`, `POST /pets` | `Profile\PetController@create/store` | `client.pets.create`, `_form` | `pet`, `customer` | Ten, species, gender, breed, weight, notes, image | Tao thu cung moi |
| Sua thu cung | `GET /pets/{petId}/edit`, `POST /pets/{petId}` | `Profile\PetController@edit/update` | `client.pets.edit`, `_form` | `pet`, `booking_room_pet`, `booking` | Thong tin thu cung | Cap nhat neu thu cung khong dang CHECKED_IN |
| Xoa thu cung | Khong thay route xoa trong code | Khong co | Khong co | `pet` | Khong co | Chua co chuc nang xoa theo code hien tai |
| Chon chi nhanh booking | `GET /booking` | `Customer\BookingController@selectBranch` | `client.bookings.select-branch` | `branch` | Khong co | Hien danh sach chi nhanh co nut dat phong |
| Tao booking theo chi nhanh | `GET /booking/branch/{branchId}`, `/booking/branches/{branchId}` | `Customer\BookingController@createFromBranch` | `client.bookings.create`, partials | `branch`, `type_room`, `room`, `pet`, `services`, `booking` | `branchId`, query `room_id`, `check_in`, `check_out` neu co | Hien form dat phong |
| Luu booking | `POST /booking` | `Customer\BookingController@store` | Redirect | `booking`, `booking_room`, `booking_room_pet`, `booking_service_pet`, `orders`, `order_details`, `payments`, `audit_log`, `room` | `branch_id`, `room_type`, ngay checkin/checkout, `pet_ids`, `service_pet_ids`, `booking_action` | Tao booking PENDING, giu phong bang lock, tao order/payment pending; neu pay thi sang thanh toan |
| Xem lich su booking | `GET /profile/history-booking` | `Customer\BookingController@index` | `client.profile.history-booking.index`, `item` | `booking`, `branch`, `booking_room`, `pet`, `orders` | Session dang nhap | Hien lich su active/done/cancelled |
| Chi tiet booking | `GET /booking/{bookingId}` | `Customer\BookingController@show` | `client.bookings.show` | `booking`, `branch`, `booking_room`, `room`, `type_room`, `pet`, `services`, `orders` | `bookingId` thuoc customer | Hien chi tiet dat phong |
| Thanh toan | `GET /payment?booking_id=...`, `/payment/booking/{bookingId}` | `Customer\PaymentController@create/show` | `client.payments.create` | `booking`, `orders`, `order_details`, `payments`, `coupon` | `bookingId` | Hien thong tin hoa don |
| Ap dung coupon | `POST /payment/booking/{bookingId}/coupon` | `PaymentController@applyCoupon` | JSON/AJAX | `coupon`, `orders` | `coupon_code` | Tra preview discount/grand total |
| Xu ly thanh toan | `POST /payment/booking/{bookingId}` | `PaymentController@process` | Redirect success | `booking`, `orders`, `payments`, `coupon`, `booking_coupon_log`, `room`, `customer`, `users` | Ten, phone, email, payment_method `cod/wallet/bank`, coupon | Cap nhat order COMPLETED, payment SUCCESS, booking CONFIRMED, room IN_USE |
| Ket qua thanh toan | `GET /payment/success`, `/payment/failed` | `PaymentController@success/failed` | `client.payments.success` hoac redirect | `orders`, `payments`, `booking`, `room` | `booking_id` | Success neu order completed, failed huy pending payment |

### 2.3. Nhan vien/le tan

Trong code hien tai khong co prefix rieng `/receptionist`. `LoginController` dieu huong role `MANAGER`, `RECEPTIONIST`, `GROOMER` ve `/manager/dashboard`. Vi vay demo nhan vien/le tan nen dung tai khoan groomer/manager va noi ro day la phan van hanh chi nhanh hien dang dung chung khu vuc manager.

| Chuc nang | URL/route | Controller | View/API | Bang lien quan | Dau vao | Ket qua mong doi |
|---|---|---|---|---|---|---|
| Dang nhap nhan vien | `POST /authentication/login` | `LoginController@store` | `auth.login` | `users`, `employee` | Email groomer/manager, password | Redirect `/manager/dashboard` |
| Dashboard chi nhanh | `GET /manager/dashboard` | `Web\Manager\DashboardController@index` | `pages.placeholder` | Khong query DB o web controller | Session role manager | Hien placeholder tong quan |
| Dashboard API | `GET /api/manager/dashboard` | `Api\Manager\DashboardController@index` | JSON | `booking`, `orders`, `room` | Session auth role manager | Tong so booking, order, phong trong |
| Danh sach dich vu | `GET /manager/services`, `GET /api/manager/services` | Web placeholder, API `ServiceController@index` | `pages.placeholder`, JSON | `services`, `category_services` | Khong co | API tra danh sach dich vu active |
| Ton kho | `GET /manager/inventory`, `GET /api/manager/inventory` | Web placeholder, API `InventoryController@index` | `pages.placeholder`, JSON | `branch_inventory`, `branch`, `product` | Khong co | API tra ton kho theo chi nhanh/san pham |
| Bao cao | `GET /manager/reports`, `GET /api/manager/reports` | Web placeholder, API `ReportController@index` | `pages.placeholder`, JSON | `orders`, `payments`, `branch` | Khong co | API tra doanh thu payment SUCCESS va 50 order gan nhat |
| Xac nhan booking/check-in/check-out | Khong thay route thao tac rieng trong web/api | Model `Booking` co hook sync room khi update status | Khong co UI rieng | `booking`, `room` | Can update DB/controller bo sung neu demo that | Chua co nut UI theo code hien tai |

### 2.4. Quan ly chi nhanh

Quan ly dung `/manager/*`. Cac man hinh web la placeholder, cac API doc du lieu that.

| Chuc nang | URL/route | Controller | View/API | Bang lien quan | Dau vao | Ket qua mong doi |
|---|---|---|---|---|---|---|
| Tong quan | `/manager/dashboard`, `/api/manager/dashboard` | Web/API Manager Dashboard | `pages.placeholder`, JSON | `booking`, `orders`, `room` | Session manager | Xem so lieu van hanh |
| Dich vu | `/manager/services`, `/api/manager/services` | Web/API Manager Service | `pages.placeholder`, JSON | `services`, `category_services` | Session manager | Xem danh muc dich vu |
| Ton kho | `/manager/inventory`, `/api/manager/inventory` | Web/API Manager Inventory | `pages.placeholder`, JSON | `branch_inventory`, `product`, `branch` | Session manager | Xem ton kho |
| Bao cao | `/manager/reports`, `/api/manager/reports` | Web/API Manager Report | `pages.placeholder`, JSON | `payments`, `orders` | Session manager | Xem doanh thu paid va orders |

### 2.5. Admin/CEO

| Chuc nang | URL/route | Controller | View/API | Bang lien quan | Dau vao | Ket qua mong doi |
|---|---|---|---|---|---|---|
| Tong quan CEO | `/ceo/dashboard`, `/api/ceo/dashboard` | `Web\Ceo\DashboardController`, `Api\Ceo\DashboardController` | `pages.placeholder`, JSON | `branch`, `services`, `booking`, `orders` | Session role CEO/Admin | Tong quan he thong |
| Quan ly chi nhanh | `/ceo/branches`, `/api/ceo/branches` | `Ceo\BranchController` | `pages.placeholder`, JSON | `branch`, `room`, `booking`, `employee` | Session CEO | API tra chi nhanh va count rooms/bookings/employees |
| Quan ly dich vu | `/ceo/services`, `/api/ceo/services` | `Ceo\ServiceController` | `pages.placeholder`, JSON | `services`, `category_services` | Session CEO | API tra danh muc dich vu |
| Doi tac/nha cung cap | `/ceo/vendors`, `/api/ceo/vendors` | `Ceo\VendorController` | `pages.placeholder`, JSON | `product` | Session CEO | API tra message va 20 product |
| Tai chinh | `/ceo/finance`, `/api/ceo/finance` | `Ceo\FinanceController` | `pages.placeholder`, JSON | `payments`, `orders` | Session CEO | Doanh thu paid, pending_orders, paid_orders |

## 3. Demo luong khach hang

### Buoc 1

- Nguoi thuc hien: Khach vang lai.
- Muc tieu demo: Gioi thieu website Pet Hotel.
- URL can vao: `/`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Trang chu hien thi, co dieu huong den chi nhanh, phong, dich vu, dang nhap/dang ky.
- Loi thuong gap: Asset CSS/JS khong load neu chua chay `npm run build` hoac public path sai.
- Giai thich ngan: "Day la trang cong khai de khach xem thong tin truoc khi co tai khoan."

### Buoc 2

- Nguoi thuc hien: Khach vang lai.
- Muc tieu demo: Xem va loc chi nhanh.
- URL can vao: `/branches`.
- Du lieu can nhap: Thu tim `Go Vap` hoac chon district neu UI co filter.
- Ket qua mong doi: Danh sach chi nhanh active, dia chi, gio mo cua, nut dat phong.
- Loi thuong gap: Khong co du lieu neu chua seed `BranchRoomSeeder`.
- Giai thich ngan: "Chi nhanh lay tu bang `branch`, bo loc xu ly qua `PublicBranchService`."

### Buoc 3

- Nguoi thuc hien: Khach vang lai.
- Muc tieu demo: Xem chi tiet chi nhanh va loai phong.
- URL can vao: `/branches/4`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Hien Pet Hotel Go Vap va cac loai phong co o chi nhanh.
- Loi thuong gap: 404 neu `branch_id` khong ton tai hoac inactive.
- Giai thich ngan: "Trang chi nhanh ket noi bang `branch`, `room`, `type_room`."

### Buoc 4

- Nguoi thuc hien: Khach vang lai.
- Muc tieu demo: Xem phong cho/meo theo hang phong.
- URL can vao: `/rooms/normal/dog`, `/rooms/vip/cat`, `/rooms/luxury/dog`.
- Du lieu can nhap: Neu co, nhap `check_in` va `check_out`.
- Ket qua mong doi: Hien thong tin loai phong, gia, suc chua, phong con trong, dich vu phu hop.
- Loi thuong gap: Sai slug ngoai `normal`, `vip`, `luxury` se 404.
- Giai thich ngan: "Trang nay tinh phong ban theo booking co status PENDING/CONFIRMED/CHECKED_IN."

### Buoc 5

- Nguoi thuc hien: Khach moi.
- Muc tieu demo: Dang ky tai khoan.
- URL can vao: `/authentication/register`.
- Du lieu can nhap: Ho ten, email moi, phone moi, dia chi, password va confirm.
- Ket qua mong doi: Tao dong trong `users` va `customer`, tu dong dang nhap ve `/`.
- Loi thuong gap: Email/phone trung bi validation 422.
- Giai thich ngan: "Dang ky gom ca tai khoan dang nhap va ho so khach hang."

### Buoc 6

- Nguoi thuc hien: Khach hang demo.
- Muc tieu demo: Dang nhap.
- URL can vao: `/authentication/login`.
- Du lieu can nhap: `customer.small@pethotel.test` / `password123`.
- Ket qua mong doi: Dang nhap thanh cong, ve trang chu.
- Loi thuong gap: Sai password, tai khoan inactive.
- Giai thich ngan: "He thong dieu huong theo role; CUSTOMER ve trang client."

### Buoc 7

- Nguoi thuc hien: Khach hang.
- Muc tieu demo: Quan ly thu cung.
- URL can vao: `/pets`.
- Du lieu can nhap: Them thu cung moi tai `/pets/create`; sua thu cung tai `/pets/{petId}/edit`.
- Ket qua mong doi: Them/sua bang `pet`. Thu cung dang CHECKED_IN khong duoc sua.
- Loi thuong gap: Chua co route xoa thu cung; image qua 2MB bi validation.
- Giai thich ngan: "Thu cung thuoc customer, khi booking chi duoc chon thu cung cua minh."

### Buoc 8

- Nguoi thuc hien: Khach hang.
- Muc tieu demo: Tao booking.
- URL can vao: `/booking`, sau do chon chi nhanh hoac vao truc tiep `/booking/branch/4`.
- Du lieu can nhap: Chi nhanh, loai phong, ngay nhan/tra, thu cung, dich vu kem theo, chon giu cho hoac thanh toan.
- Ket qua mong doi: Tao `booking`, gan `room`, gan `pet`, tao service neu chon, sinh `orders`, `order_details`, `payments`.
- Loi thuong gap: Ngay nhan trong qua khu; phong het; thu cung vuot can nang/suc chua; thu cung da co booking trung lich.
- Giai thich ngan: "Repository dung transaction va `lockForUpdate()` de tranh hai khach giu cung mot phong."

### Buoc 9

- Nguoi thuc hien: Khach hang.
- Muc tieu demo: Thanh toan booking.
- URL can vao: `/payment/booking/{bookingId}` hoac `/payment?booking_id={bookingId}`.
- Du lieu can nhap: Ten, phone, email, payment method `cod`, `wallet`, `bank`, coupon `DEMO10` hoac `WELCOME50`.
- Ket qua mong doi: Order COMPLETED, Payment SUCCESS, Booking CONFIRMED, phong IN_USE, sang `/payment/success?booking_id=...`.
- Loi thuong gap: Coupon het han/khong dat gia tri toi thieu; phone/email trung.
- Giai thich ngan: "Thanh toan cap nhat order, payment, booking va room trong transaction."

### Buoc 10

- Nguoi thuc hien: Khach hang.
- Muc tieu demo: Xem lich su dat phong/giao dich.
- URL can vao: `/profile/history-booking`, `/booking/{bookingId}`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Hien booking active/done/cancelled, chi tiet phong, thu cung, dich vu, tong tien.
- Loi thuong gap: Neu xem booking cua khach khac se bi redirect ve lich su.
- Giai thich ngan: "Lich su chi lay booking theo customer dang dang nhap."

## 4. Demo luong nhan vien/le tan

### Buoc 11

- Nguoi thuc hien: Nhan vien/groomer.
- Muc tieu demo: Dang nhap khu van hanh.
- URL can vao: `/authentication/login`.
- Du lieu can nhap: `groomer.govap@pethotel.test` / `password123`.
- Ket qua mong doi: Redirect `/manager/dashboard`.
- Loi thuong gap: Middleware role trong route manager chi nhan `role:manager`; neu middleware khong map GROOMER thanh manager thi co the bi 403.
- Giai thich ngan: "Theo code, RECEPTIONIST/GROOMER duoc controller login dieu huong ve manager, nhung middleware can ho tro role tuong ung."

### Buoc 12

- Nguoi thuc hien: Nhan vien/quan ly chi nhanh.
- Muc tieu demo: Xem tong quan booking/order/phong trong.
- URL can vao: `/manager/dashboard`, co the goi `/api/manager/dashboard`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Web hien placeholder; API tra count booking, order, available_rooms.
- Loi thuong gap: API can session auth dung role.
- Giai thich ngan: "Phan web admin hien la khung giao dien; API da doc du lieu that."

### Buoc 13

- Nguoi thuc hien: Nhan vien.
- Muc tieu demo: Xem booking/order de xu ly tai quay.
- URL can vao: `/api/manager/reports`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: JSON co `paid_revenue` va 50 order gan nhat kem branch/payment.
- Loi thuong gap: Chua co man hinh Blade rieng cho danh sach booking/order nhan vien.
- Giai thich ngan: "Trong code hien tai, luong nhan vien moi co API bao cao va placeholder UI, chua co nut confirm/check-in/check-out."

### Buoc 14

- Nguoi thuc hien: Nhan vien.
- Muc tieu demo: Giai thich check-in/check-out theo code hien co.
- URL can vao: Khong co route UI rieng.
- Du lieu can nhap: Neu demo bang SQL/admin DB: update `booking.status` thanh `CHECKED_IN`, sau do `CHECKED_OUT`.
- Ket qua mong doi: Model `Booking` co event `updated` dong bo `room.status` thanh `IN_USE` khi CHECKED_IN va `AVAILABLE` khi CHECKED_OUT/COMPLETED/CANCELLED.
- Loi thuong gap: Cap nhat truc tiep SQL se khong chay Eloquent model event; chi chay neu update qua Laravel/Eloquent.
- Giai thich ngan: "Logic dong bo phong da co trong model, nhung UI thao tac nhan vien chua duoc route hoa."

## 5. Demo luong quan ly/admin

### Buoc 15

- Nguoi thuc hien: Quan ly chi nhanh.
- Muc tieu demo: Xem dashboard, dich vu, ton kho, bao cao.
- URL can vao: `/manager/dashboard`, `/manager/services`, `/manager/inventory`, `/manager/reports`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Cac trang placeholder hien section tuong ung.
- Loi thuong gap: Chua co form CRUD web that.
- Giai thich ngan: "Phan quan ly da co route va khung UI; du lieu chi tiet nam o API."

### Buoc 16

- Nguoi thuc hien: Quan ly chi nhanh.
- Muc tieu demo: Xem ton kho that.
- URL can vao: `/api/manager/inventory`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: JSON tu `branch_inventory` kem `branch`, `product`.
- Loi thuong gap: Neu trinh duyet hien 403, dang nhap sai role/session.
- Giai thich ngan: "Ton kho duoc quan ly theo cap chi nhanh va san pham."

### Buoc 17

- Nguoi thuc hien: Admin/CEO.
- Muc tieu demo: Dang nhap quan tri cap cao.
- URL can vao: `/authentication/login`.
- Du lieu can nhap: `admin.demo@pethotel.test` / `password123`.
- Ket qua mong doi: Redirect `/ceo/dashboard`.
- Loi thuong gap: Middleware `role:ceo` can map role `ADMIN` neu muon admin vao CEO.
- Giai thich ngan: "Admin/CEO xem toan he thong, khac voi manager theo chi nhanh."

### Buoc 18

- Nguoi thuc hien: Admin/CEO.
- Muc tieu demo: Quan ly tong quan he thong.
- URL can vao: `/ceo/dashboard`, `/api/ceo/dashboard`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: Web placeholder; API tra so chi nhanh, dich vu, booking, order.
- Loi thuong gap: Role middleware khong cho ADMIN vao neu cau hinh chi chap nhan `ceo`.
- Giai thich ngan: "Dashboard CEO gom cac KPI toan he thong."

### Buoc 19

- Nguoi thuc hien: Admin/CEO.
- Muc tieu demo: Xem chi nhanh va so lieu lien quan.
- URL can vao: `/ceo/branches`, `/api/ceo/branches`.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: API tra chi nhanh kem `rooms_count`, `bookings_count`, `employees_count`.
- Loi thuong gap: Web chua co CRUD create/edit/delete that.
- Giai thich ngan: "CEO co goc nhin chuoi chi nhanh."

### Buoc 20

- Nguoi thuc hien: Admin/CEO.
- Muc tieu demo: Xem dich vu, vendor/san pham, tai chinh.
- URL can vao: `/ceo/services`, `/ceo/vendors`, `/ceo/finance` va cac API tuong ung.
- Du lieu can nhap: Khong co.
- Ket qua mong doi: API services doc `services`; vendors tra product; finance tra doanh thu paid va count order.
- Loi thuong gap: `paid_orders` API dang dem status `PAID`, trong seeder order thanh cong la `COMPLETED`, nen count co the bang 0.
- Giai thich ngan: "Phan CEO tap trung theo doi danh muc va tai chinh toan he thong."

## 6. JS, AJAX va view lien quan

- Booking UI: `resources/views/client/bookings/create.blade.php`, partials `selection`, `summary`, `modals`; JS `public/assets/client/js/booking.js`; CSS `booking.css`.
- Branch UI: `client/branches/index.blade.php`, partials map/filter/detail; JS `branches.js`, `branch-show.js`.
- Payment UI: `client/payments/create.blade.php`, `success.blade.php`; JS `payment.js`, `payment-success.js`.
- Pet UI: `client/pets/index/create/edit`, partial `_form`, `_list`; JS `pet-form.js`, `pet-edit.js`.
- Auth UI: `auth/login.blade.php`, `auth/register.blade.php`; JS `public/assets/auth/js/login.js`, `register.js`.

## 7. Luu y trung thuc khi thuyet trinh

- Luong khach hang, booking, payment la luong that, co transaction va lock.
- Luong public chi nhanh/loai phong lay du lieu that.
- Luong manager/CEO web hien tai chu yeu la placeholder; API da doc du lieu that.
- Chua thay route xoa thu cung.
- Chua thay UI nhan vien rieng de confirm/check-in/check-out/order tai quay; model co logic sync room status khi booking status thay doi qua Eloquent.
- Khong can tao seeder moi vi seeder hien tai da du du lieu demo chinh.

## 8. Demo Oracle: kiem tra phong tieu chuan Go Vap bi full ngay 26-27

Loi noi khi demo:

> Bay gio em kiem tra trong Oracle xem phong tieu chuan tai Go Vap co bi full ngay 26-27 khong. Du lieu demo tao 2 booking chiem ca GV-M201 va GV-M202 trong khoang ngay nay.

SQL Oracle:

```sql
SELECT
    b.booking_id,
    c.full_name AS customer_name,
    p.pet_name,
    brn.branch_name,
    r.room_number,
    tr.type_name,
    b.checkin_expected_at,
    b.checkout_expected_at,
    b.status
FROM booking b
JOIN customer c ON c.customer_id = b.customer_id
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
JOIN branch brn ON brn.branch_id = b.branch_id
LEFT JOIN booking_room_pet brp ON brp.booking_room_id = br.booking_room_id
LEFT JOIN pet p ON p.pet_id = brp.pet_id
WHERE brn.branch_name = 'Pet Hotel Gò Vấp'
  AND tr.type_name = 'Phòng tiêu chuẩn'
  AND b.status IN ('PENDING', 'CONFIRMED', 'CHECKED_IN')
  AND b.checkin_expected_at < TO_TIMESTAMP('2026-05-27 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
  AND b.checkout_expected_at > TO_TIMESTAMP('2026-05-26 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
ORDER BY r.room_number, b.checkin_expected_at;
```

Giai thich khi thay 2 phong:

> Ket qua co GV-M201 va GV-M202, nghia la ca 2 phong tieu chuan o chi nhanh Go Vap deu da co booking active trong ngay 26-27. Vi vay khi khach chon Phong tieu chuan tai Go Vap trong khoang ngay nay, he thong se khong con phong trong. Du lieu demo cung ghi ro Lucky o GV-M201 tu 26-28 va Coco o GV-M202 tu 26-27.

Neu du lieu seed hien tai dang dat ten loai phong la `Phòng vừa` thay vi `Phòng tiêu chuẩn`, doi dieu kien:

```sql
AND tr.type_name = 'Phòng vừa'
```
