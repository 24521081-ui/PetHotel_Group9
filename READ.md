PHẦN 1: GIỚI THIỆU DATA DEMO
1.1 Mở Oracle kiểm tra 4 chi nhánh

Nói:

Đầu tiên em kiểm tra dữ liệu chi nhánh trong Oracle. Hệ thống hiện có 4 chi nhánh tại TP.HCM, gồm Gò Vấp, Quận 1, Quận 7 và Thủ Đức.

Chạy SQL:

SELECT
branch_id,
branch_name,
phone,
email,
address,
is_active
FROM branch
ORDER BY branch_id;

Giải thích:

Đây là dữ liệu chi nhánh được seed sẵn để khách hàng chọn nơi đặt phòng. Chi nhánh Gò Vấp là flow chính, vì tại đây có kịch bản phòng tiêu chuẩn bị full trong ngày 26-27. Theo dữ liệu demo, 4 chi nhánh gồm Pet Hotel Gò Vấp, Pet Hotel Quận 1, Pet Hotel Quận 7 và Pet Hotel Thủ Đức.

1.2 Kiểm tra tài khoản demo

Nói:

Tiếp theo em kiểm tra các tài khoản dùng để demo. Tất cả tài khoản demo đều dùng mật khẩu password123.

Chạy SQL:

SELECT
id,
name,
email,
role,
is_active,
created_at
FROM users
WHERE email LIKE '%pethotel.test'
ORDER BY role, email;

Giải thích:

Hệ thống có tài khoản quản lý, groomer và tài khoản khách hàng. Trong video này em sẽ dùng các tài khoản customer để demo đặt phòng và dùng admin/manager để xem dữ liệu quản lý. Các tài khoản customer đều có record trong bảng customer và liên kết với users thông qua customer.user_id -> users.id.

Kiểm tra liên kết customer:

SELECT
c.customer_id,
c.user_id,
u.email,
c.full_name,
c.phone
FROM customer c
JOIN users u ON u.id = c.user_id
WHERE u.email IN (
'customer.small@pethotel.test',
'customer.medium@pethotel.test',
'customer.large@pethotel.test'
)
ORDER BY c.customer_id;
1.3 Kiểm tra thú cưng demo

Nói:

Bộ data có nhiều thú cưng với cân nặng khác nhau để demo việc chọn phòng và dịch vụ. Tài khoản Nguyễn Minh Anh có 2 thú cưng là Milo và Bella, phù hợp để demo booking nhiều thú cưng trong cùng một lần đặt phòng.

Chạy SQL:

SELECT
p.pet_id,
p.pet_name,
p.species,
p.breed,
p.sex,
p.weight_kg,
c.full_name AS owner_name,
u.email
FROM pet p
JOIN customer c ON c.customer_id = p.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email LIKE 'customer.%@pethotel.test'
ORDER BY u.email, p.pet_name;

Giải thích:

Milo 5kg và Bella 8kg là chó nhỏ. Lucky 15kg là chó cỡ vừa. Max 30kg là chó lớn. Các cân nặng này giúp demo việc chọn loại phòng và dịch vụ phù hợp.

PHẦN 2: DEMO PHÒNG VÀ LỊCH FULL NGÀY 26-27
2.1 Kiểm tra loại phòng và phòng

Nói:

Hệ thống có 3 loại phòng: Phòng nhỏ, Phòng tiêu chuẩn và Phòng cao cấp. Mỗi chi nhánh có ít nhất 2 phòng available cho mỗi loại phòng.

Chạy SQL:

SELECT
tr.type_room_id,
tr.type_name,
tr.max_slot,
tr.min_weight_kg,
tr.max_weight_kg,
tr.base_price_per_day,
tr.is_active
FROM type_room tr
ORDER BY tr.type_room_id;

Kiểm tra phòng theo chi nhánh:

SELECT
b.branch_name,
tr.type_name,
r.room_id,
r.room_number,
r.status
FROM room r
JOIN branch b ON b.branch_id = r.branch_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
ORDER BY b.branch_name, tr.type_name, r.room_number;

Giải thích:

Ở Gò Vấp có các phòng như GV-S101, GV-S102, GV-M201, GV-M202, GV-L301, GV-L302. Trong đó, phòng tiêu chuẩn Gò Vấp sẽ được seed booking full trong ngày 26-27 để demo kiểm tra phòng trống.

2.2 Kiểm tra Gò Vấp phòng tiêu chuẩn bị full ngày 26-27

Nói:

Bây giờ em kiểm tra trong Oracle xem phòng tiêu chuẩn tại Gò Vấp có bị full ngày 26-27 không. Dữ liệu demo tạo 2 booking chiếm cả GV-M201 và GV-M202 trong khoảng ngày này.

Chạy SQL:

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

Giải thích khi thấy 2 phòng:

Kết quả có GV-M201 và GV-M202, nghĩa là cả 2 phòng tiêu chuẩn ở chi nhánh Gò Vấp đều đã có booking active trong ngày 26-27. Vì vậy khi khách chọn Phòng tiêu chuẩn tại Gò Vấp trong khoảng ngày này, hệ thống sẽ không còn phòng trống. Dữ liệu demo cũng ghi rõ Lucky ở GV-M201 từ 26-28 và Coco ở GV-M202 từ 26-27.

PHẦN 3: DEMO CUSTOMER 1 BOOKING 2 THÚ CƯNG
3.1 Login customer.small

Trên web, login:

Email: customer.small@pethotel.test
Password: password123

Nói:

Em đăng nhập bằng tài khoản Nguyễn Minh Anh. Tài khoản này có 2 thú cưng là Milo 5kg và Bella 8kg, dùng để demo một booking có nhiều thú cưng.

Sau khi login, mở phần thú cưng hoặc hồ sơ.

Nói:

Ở đây mình thấy Milo và Bella. Cả hai đều là chó nhỏ, nên có thể dùng Phòng nhỏ. Theo dữ liệu demo, Phòng nhỏ có max slot là 2, nên 2 thú cưng nhỏ có thể ở chung một phòng.

3.2 Mở booking đã seed sẵn của customer.small

Trên web: vào lịch sử booking hoặc danh sách booking.

Nói:

Em mở booking ngày 26-27 tại Pet Hotel Gò Vấp. Booking này dùng phòng GV-S101 và gắn cả 2 thú cưng Milo, Bella.

Kiểm tra trong Oracle:

SELECT
b.booking_id,
u.email,
c.full_name,
brn.branch_name,
b.checkin_expected_at,
b.checkout_expected_at,
b.status,
b.total_amount
FROM booking b
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
JOIN branch brn ON brn.branch_id = b.branch_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY b.booking_id;<<<<<<< HEAD

# Pet Hotel Laravel - Oracle Setup

Project da duoc chuan bi de chay voi Oracle Database thong qua package
`yajra/laravel-oci8`. Oracle Database duoc gia dinh la da co san; nguoi clone
project chi can cau hinh schema/user Oracle trong `.env`.

## Yeu Cau

- PHP 8.2 tro len
- Composer
- Node.js va npm
- PHP extension `oci8` da bat
- Oracle Database service dang chay, vi du `FREEPDB1`
- Oracle user/schema, vi du `PET_HOTEL`

Kiem tra PHP extension:

```bash
php -m
php --ri oci8
```

## Cai Dat

```bash
git clone <repo-url>
cd pet-hotel
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

Tren Windows PowerShell co the copy env bang:

```powershell
Copy-Item .env.example .env
```

## Cau Hinh Oracle

Sua `.env` theo Oracle hien co:

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

Khong commit `.env` that hoac password Oracle that.

Neu can tao user/schema Oracle, chay bang tai khoan co quyen DBA:

```sql
CREATE USER PET_HOTEL IDENTIFIED BY your_password;
GRANT CONNECT, RESOURCE TO PET_HOTEL;
ALTER USER PET_HOTEL QUOTA UNLIMITED ON USERS;
```

## Chay Migration, Seeder Va Web

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate:fresh --seed
php artisan serve
```

Mo trinh duyet:

```text
http://127.0.0.1:8000
```

Tai khoan seed mac dinh dung password `password123`, vi du:

- `admin@pethotel.test`
- `manager@pethotel.test`
- `customer1@pethotel.test`

## Ghi Chu Oracle Migration

Giai doan cau hinh da uu tien Oracle de nguoi clone project khong chay nham
MySQL hoac SQLite:

- `.env.example` dung Oracle.
- `config/database.php` fallback sang `oracle` va co connection `oracle`.
- `config/queue.php` fallback database batch/failed jobs sang Oracle.
- `composer.json` khong tao file SQLite mac dinh.
- # `phpunit.xml` khong ep test dung SQLite in-memory.
  <p align="center">
    <a href="https://www.uit.edu.vn/" title="Trường Đại học Công nghệ Thông tin">
      <img src="https://i.imgur.com/WmMnSRt.png" alt="Trường Đại học Công nghệ Thông tin | University of Information Technology" width="750">
    </a>
  </p>

<h1 align="center">ĐỒ ÁN</h1>
<h2 align="center">Môn học: HỆ QUẢN TRỊ CƠ SỞ DỮ LIỆU</h2>
<h3 align="center">Mã lớp: IS210.Q22</h3>
<h3 align="center">Đề tài: Hệ thống quản lý chuỗi khách sạn thú cưng</h3>

<p align="center">
  Đây là <b>đồ án môn Hệ quản trị cơ sở dữ liệu</b> của <b>Nhóm 9</b>, 
  thực hiện đề tài <b>Hệ thống quản lý chuỗi khách sạn thú cưng</b>.
</p>

<p align="center">
  Hệ thống hỗ trợ quản lý các nghiệp vụ chính trong chuỗi khách sạn thú cưng như:
  quản lý chi nhánh, phòng, khách hàng, thú cưng, đặt phòng, dịch vụ chăm sóc,
  hóa đơn, thanh toán, mã giảm giá và tồn kho vật tư.
</p>

---

## NHÓM THỰC HIỆN

### Thành viên nhóm 9

| STT | MSSV     | Họ và tên       |
| --- | -------- | --------------- |
| 6   | 24521045 | Trần Đức Mạnh   |
| 7   | 24521034 | Châu Gia Lương  |
| 8   | 24521081 | Nguyễn Văn Minh |
| 9   | 24521093 | Nguyễn Thế Mỹ   |

---

## THÔNG TIN ĐỀ TÀI

| Nội dung        | Thông tin                                 |
| --------------- | ----------------------------------------- |
| Môn học         | Hệ quản trị cơ sở dữ liệu                 |
| Mã lớp          | IS210.Q22                                 |
| Loại đồ án      | Đồ án môn học                             |
| Nhóm thực hiện  | Nhóm 9                                    |
| Đề tài          | Hệ thống quản lý chuỗi khách sạn thú cưng |
| Công nghệ chính | Laravel, Oracle Database                  |
| Cơ sở dữ liệu   | Oracle                                    |

---

## MÔ TẢ NGẮN GỌN

Đề tài xây dựng hệ thống quản lý chuỗi khách sạn thú cưng, tập trung vào việc thiết kế và triển khai cơ sở dữ liệu cho các nghiệp vụ chính như quản lý chi nhánh, loại phòng, phòng, khách hàng, thú cưng, đặt phòng, dịch vụ, hóa đơn, thanh toán, mã giảm giá và tồn kho vật tư.

Hệ thống sử dụng Oracle Database để lưu trữ dữ liệu, áp dụng các ràng buộc toàn vẹn, khóa chính, khóa ngoại và dữ liệu mẫu để phục vụ quá trình kiểm thử, demo và trình bày đồ án.

> > > > > > > e1b84baae4a402fae519b3c71deb48ebf77f46b2

Kiểm tra booking_room:

SELECT
b.booking_id,
br.booking_room_id,
r.room_number,
tr.type_name,
br.assigned_at
FROM booking b
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY b.booking_id, br.booking_room_id;

Kiểm tra booking_room_pet:

SELECT
b.booking_id,
br.booking_room_id,
r.room_number,
p.pet_name,
p.weight_kg
FROM booking b
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN booking_room_pet brp ON brp.booking_room_id = br.booking_room_id
JOIN pet p ON p.pet_id = brp.pet_id
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY p.pet_name;

Giải thích:

Bảng booking lưu thông tin đặt phòng chính. Bảng booking_room lưu phòng được gán cho booking. Bảng booking_room_pet là bảng liên kết giữa phòng đã đặt và thú cưng, nên một booking có thể chứa nhiều thú cưng.

3.3 Kiểm tra dịch vụ của Milo và Bella

Trên web: mở chi tiết booking hoặc phần dịch vụ.

Nói:

Booking này có dịch vụ tắm cho Milo, tắm cho Bella và cắt móng cho Bella. Các dịch vụ sẽ được lưu ở bảng booking_service_pet.

Chạy SQL:

SELECT
b.booking_id,
bsp.booking_service_pet_id,
p.pet_name,
s.service_name,
s.base_price,
bsp.scheduled_at,
bsp.status
FROM booking b
JOIN booking_service_pet bsp ON bsp.booking_id = b.booking_id
JOIN services s ON s.service_id = bsp.service_id
JOIN pet p ON p.pet_id = bsp.pet_id
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY p.pet_name, s.service_name;

Giải thích:

Mỗi dòng trong booking_service_pet là một dịch vụ được đăng ký cho một thú cưng trong booking. Nếu service có trạng thái DONE thì hệ thống sẽ trừ tồn kho. Trong dữ liệu demo, seeder mô phỏng việc trừ kho bằng method applyInventoryUsage(), chỉ trừ khi service có trạng thái DONE.

PHẦN 4: DEMO ORDER, ORDER DETAILS, COUPON, PAYMENT
4.1 Kiểm tra order và giảm giá DEMO10

Trên web: mở hóa đơn/order của booking customer.small.

Nói:

Tiếp theo em kiểm tra hóa đơn. Hóa đơn gồm tiền phòng, tiền dịch vụ, mã giảm giá DEMO10 giảm 10%, sau đó ra tổng tiền cần thanh toán.

Chạy SQL:

SELECT
o.order_id,
o.booking_id,
u.email,
o.status,
o.subtotal,
o.discount_amount,
o.grand_total,
o.payment_method,
o.paid_at,
o.created_at
FROM orders o
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY o.order_id;

Kiểm tra coupon:

SELECT
coupon_id,
code,
coupon_name,
discount_type,
discount_value,
used_count,
is_active,
start_date,
end_date
FROM coupon
WHERE code = 'DEMO10';

Kiểm tra booking_coupon_log:

SELECT
bcl.booking_coupon_log_id,
bcl.booking_id,
bcl.coupon_id,
cp.code,
bcl.discount_amount,
bcl.applied_at,
bcl.status
FROM booking_coupon_log bcl
JOIN coupon cp ON cp.coupon_id = bcl.coupon_id
ORDER BY bcl.booking_coupon_log_id;

Giải thích:

Mã DEMO10 là coupon giảm 10%. Theo data demo, coupon này được áp dụng cho account 1, account 2 và account 3, đồng thời used_count bằng 3 và booking_coupon_log có 3 dòng ghi nhận.

4.2 Kiểm tra order_details

Nói:

Bảng order_details lưu chi tiết từng dòng trong hóa đơn. Mỗi order có một dòng tiền phòng và các dòng tiền dịch vụ. line_total bằng quantity nhân unit_price.

Chạy SQL:

SELECT
od.order_detail_id,
od.order_id,
od.booking_room_id,
od.booking_service_pet_id,
od.note,
od.quantity,
od.unit_price,
od.line_total
FROM order_details od
JOIN orders o ON o.order_id = od.order_id
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY od.order_detail_id;

Giải thích:

Ở đây mình thấy dòng tiền phòng gắn với booking_room_id và dòng dịch vụ gắn với booking_service_pet_id. Cách này giúp hóa đơn thể hiện rõ khách đang trả tiền cho phòng nào và dịch vụ nào.

4.3 Kiểm tra payments

Nói:

Sau khi có hóa đơn, hệ thống lưu giao dịch thanh toán trong bảng payments. Với account 1, payment đang là PENDING để demo trường hợp khách chưa hoàn tất thanh toán.

Chạy SQL:

SELECT
p.payment_id,
p.order_id,
p.payment_method,
p.provider,
p.amount,
p.status,
p.paid_at,
p.note,
p.created_at
FROM payments p
JOIN orders o ON o.order_id = p.order_id
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY p.payment_id;

Giải thích:

Payment PENDING nghĩa là đã tạo yêu cầu thanh toán nhưng chưa xác nhận thành công. Với các order đã thanh toán, payment sẽ có status SUCCESS và paid_at khác null.

PHẦN 5: DEMO CUSTOMER 2 VÀ PHÒNG TIÊU CHUẨN FULL
5.1 Login customer.medium

Login:

Email: customer.medium@pethotel.test
Password: password123

Nói:

Bây giờ em đăng nhập tài khoản Trần Gia Huy. Tài khoản này có pet Lucky 15kg và booking từ ngày 26 đến 28 tại phòng GV-M201.

Trên web: mở booking của Lucky.

Chạy Oracle:

SELECT
b.booking_id,
u.email,
c.full_name,
p.pet_name,
p.weight_kg,
brn.branch_name,
r.room_number,
tr.type_name,
b.checkin_expected_at,
b.checkout_expected_at,
b.status
FROM booking b
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
JOIN branch brn ON brn.branch_id = b.branch_id
JOIN booking_room_pet brp ON brp.booking_room_id = br.booking_room_id
JOIN pet p ON p.pet_id = brp.pet_id
WHERE u.email = 'customer.medium@pethotel.test';

Giải thích:

Booking này là một trong hai booking làm full phòng tiêu chuẩn tại Gò Vấp ngày 26-27. Booking còn lại là của Coco ở phòng GV-M202.

5.2 Kiểm tra order PARTIAL và payment SUCCESS một phần

Chạy SQL:

SELECT
o.order_id,
o.status,
o.subtotal,
o.discount_amount,
o.grand_total,
p.payment_id,
p.amount AS paid_amount,
p.status AS payment_status,
p.paid_at
FROM orders o
LEFT JOIN payments p ON p.order_id = o.order_id
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.medium@pethotel.test'
ORDER BY o.order_id, p.payment_id;

Giải thích:

Order của Lucky có trạng thái PARTIAL và payment SUCCESS một phần. Điều này dùng để demo trường hợp khách đã thanh toán một phần nhưng chưa hoàn tất toàn bộ hóa đơn. Trong tài liệu data demo, account 2 có payment SUCCESS 300.000đ và coupon DEMO10.

PHẦN 6: DEMO CUSTOMER 3 COMPLETED + PAID
6.1 Login customer.large

Login:

Email: customer.large@pethotel.test
Password: password123

Nói:

Tiếp theo em đăng nhập tài khoản Lê Hoàng Nam. Tài khoản này có pet Max 30kg, dùng để demo booking đã hoàn thành và đã thanh toán.

Trên web: mở lịch sử booking/order.

Chạy SQL:

SELECT
b.booking_id,
u.email,
c.full_name,
p.pet_name,
p.weight_kg,
brn.branch_name,
r.room_number,
tr.type_name,
b.status AS booking_status,
o.status AS order_status,
o.grand_total,
pay.status AS payment_status,
pay.amount,
pay.paid_at
FROM booking b
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
JOIN branch brn ON brn.branch_id = b.branch_id
JOIN booking_room_pet brp ON brp.booking_room_id = br.booking_room_id
JOIN pet p ON p.pet_id = brp.pet_id
LEFT JOIN orders o ON o.booking_id = b.booking_id
LEFT JOIN payments pay ON pay.order_id = o.order_id
WHERE u.email = 'customer.large@pethotel.test';

Giải thích:

Booking của Max ở Pet Hotel Thủ Đức là booking đã completed. Order cũng completed và payment SUCCESS. Trường hợp này dùng để demo lịch sử đặt phòng đã hoàn tất.

PHẦN 7: DEMO TỒN KHO TỰ THAY ĐỔI TRÊN ORACLE
7.1 Kiểm tra tồn kho hiện tại sau seed

Nói:

Bây giờ em mở bảng tồn kho trong Oracle. Dữ liệu tồn kho đã được seed theo từng chi nhánh. Khi dịch vụ có trạng thái DONE, hệ thống sẽ trừ vật tư tương ứng trong branch_inventory.

Chạy SQL:

SELECT
b.branch_name,
p.product_name,
p.unit,
bi.quantity_in_stock,
bi.reorder_point,
bi.last_updated
FROM branch_inventory bi
JOIN branch b ON b.branch_id = bi.branch_id
JOIN product p ON p.product_id = bi.product_id
WHERE b.branch_name IN ('Pet Hotel Gò Vấp', 'Pet Hotel Quận 7', 'Pet Hotel Thủ Đức')
ORDER BY b.branch_name, p.product_name;

Giải thích:

Theo tài liệu, Gò Vấp đã bị trừ 2 dịch vụ tắm chó nhỏ, 1 tắm chó vừa và 1 kiểm tra sức khỏe cơ bản. Do đó tồn kho Gò Vấp sau seed còn Sữa tắm cho chó 4800, Dầu xả lông 2920, Khăn vệ sinh 9600, Dung dịch vệ sinh tai 1990, Vật tư cắt móng 2000 và Găng tay kiểm tra sức khỏe 4950.

7.2 So sánh định mức dịch vụ và tồn kho

Chạy SQL để xem định mức:

SELECT
s.service_name,
p.product_name,
spd.quantity,
spd.notes
FROM service_product_detail spd
JOIN services s ON s.service_id = spd.service_id
JOIN product p ON p.product_id = spd.product_id
ORDER BY s.service_name, p.product_name;

Nói:

Bảng service_product_detail cho biết mỗi dịch vụ dùng bao nhiêu vật tư. Ví dụ Tắm chó nhỏ dùng sữa tắm, dầu xả và khăn vệ sinh; Grooming toàn diện dùng nhiều vật tư hơn như sữa tắm, dầu xả, vệ sinh tai, vật tư cắt móng và khăn vệ sinh.

7.3 Demo dữ liệu auto thay đổi khi đổi service sang DONE

Phần này có 2 cách. Sếp chọn cách đúng với code hiện tại.

Cách A — Nếu web đã có nút cập nhật trạng thái dịch vụ

Thao tác trên web:

Login manager.central@pethotel.test hoặc admin.demo@pethotel.test.
Mở danh sách booking/service.
Chọn một booking có dịch vụ đang SCHEDULED hoặc PENDING.
Cập nhật dịch vụ sang DONE.
Quay lại Oracle chạy lại query tồn kho.

Trước khi bấm DONE, chạy Oracle:

SELECT
b.branch_name,
p.product_name,
bi.quantity_in_stock
FROM branch_inventory bi
JOIN branch b ON b.branch_id = bi.branch_id
JOIN product p ON p.product_id = bi.product_id
WHERE b.branch_name = 'Pet Hotel Quận 1'
ORDER BY p.product_name;

Sau khi bấm DONE, chạy lại query y hệt.

Nói:

Trước khi hoàn thành dịch vụ, tồn kho Quận 1 chưa bị trừ vì service đang SCHEDULED. Sau khi chuyển service sang DONE, hệ thống trừ vật tư tương ứng. Đây là phần auto thay đổi dữ liệu tồn kho trong Oracle.

Lưu ý: tài liệu hiện tại ghi Quận 1 chưa trừ kho vì service đang SCHEDULED, nên đây là case tốt nhất để demo thay đổi trước-sau.

Cách B — Nếu web chưa có nút DONE hoặc trigger chưa bật

Nói trung thực khi quay:

Hiện tại trigger/procedure trừ kho Oracle thật chưa bật. Trong bản demo này, seeder đang mô phỏng logic trừ kho bằng method applyInventoryUsage(). Vì vậy tồn kho sau seed đã phản ánh các dịch vụ DONE. Nếu muốn demo thay đổi trực tiếp trước-sau, em sẽ dùng lệnh cập nhật trạng thái và chạy lại logic trừ kho trong backend, không sửa tay quantity trong Oracle.

Tài liệu demo hiện tại ghi rõ trigger/procedure trừ kho Oracle chưa bật; seeder mô phỏng trừ kho bằng applyInventoryUsage(), đọc định mức từ service_product_detail, trừ vào branch_inventory.quantity_in_stock, cập nhật last_updated và không trừ cho service PENDING hoặc SCHEDULED.

Nếu chỉ muốn chứng minh dữ liệu đã bị trừ sau seed, chạy:

SELECT
b.branch_name,
p.product_name,
bi.quantity_in_stock
FROM branch_inventory bi
JOIN branch b ON b.branch_id = bi.branch_id
JOIN product p ON p.product_id = bi.product_id
WHERE b.branch_name = 'Pet Hotel Gò Vấp'
ORDER BY p.product_name;

Giải thích:

Đây là dữ liệu tồn kho sau khi seeder đã mô phỏng việc hoàn thành dịch vụ. Khi triển khai trigger/procedure thật, phần này có thể chuyển từ logic seeder sang logic database hoặc service layer.

PHẦN 8: DEMO DỮ LIỆU TỰ THÊM KHI TẠO BOOKING MỚI TRÊN WEB

Phần này quan trọng vì sếp muốn “thao tác với Oracle để xem dữ liệu auto thay đổi”.

8.1 Trước khi tạo booking mới, đếm số dòng

Nói:

Trước khi tạo booking mới trên web, em sẽ đếm số dòng trong các bảng chính. Sau khi thao tác trên web, em chạy lại query để thấy các bảng tự tăng số dòng.

Chạy SQL:

SELECT 'BOOKING' AS table_name, COUNT(_) AS total_rows FROM booking
UNION ALL
SELECT 'BOOKING_ROOM', COUNT(_) FROM booking_room
UNION ALL
SELECT 'BOOKING_ROOM_PET', COUNT(_) FROM booking_room_pet
UNION ALL
SELECT 'BOOKING_SERVICE_PET', COUNT(_) FROM booking_service_pet
UNION ALL
SELECT 'ORDERS', COUNT(_) FROM orders
UNION ALL
SELECT 'ORDER_DETAILS', COUNT(_) FROM order_details
UNION ALL
SELECT 'PAYMENTS', COUNT(_) FROM payments
UNION ALL
SELECT 'BOOKING_COUPON_LOG', COUNT(_) FROM booking_coupon_log;

Ghi lại kết quả hoặc chụp màn hình.

8.2 Tạo booking mới trên web

Login:

customer.small@pethotel.test
password123

Thao tác:

1. Chọn chi nhánh: Pet Hotel Quận 1
2. Chọn ngày: 2026-05-29 đến 2026-05-30
3. Chọn thú cưng: Milo
4. Chọn phòng: Q1-S102 hoặc phòng nhỏ còn trống
5. Chọn dịch vụ: Cắt móng hoặc Tắm thú cưng/Tắm chó nhỏ tùy UI hiện tại
6. Nhập mã giảm giá: DEMO10 nếu form có
7. Xác nhận đặt phòng
8. Nếu có payment, chọn phương thức thanh toán hoặc để PENDING

Nói:

Em cố tình chọn Quận 1 và ngày 29-30 để tránh trùng lịch full ở Gò Vấp ngày 26-27. Sau khi bấm xác nhận, hệ thống sẽ tự tạo booking, gán phòng, gán thú cưng, tạo dịch vụ, tạo order, order_details và payment nếu flow payment được bật.

8.3 Sau khi tạo booking, chạy lại count

Chạy lại:

SELECT 'BOOKING' AS table_name, COUNT(_) AS total_rows FROM booking
UNION ALL
SELECT 'BOOKING_ROOM', COUNT(_) FROM booking_room
UNION ALL
SELECT 'BOOKING_ROOM_PET', COUNT(_) FROM booking_room_pet
UNION ALL
SELECT 'BOOKING_SERVICE_PET', COUNT(_) FROM booking_service_pet
UNION ALL
SELECT 'ORDERS', COUNT(_) FROM orders
UNION ALL
SELECT 'ORDER_DETAILS', COUNT(_) FROM order_details
UNION ALL
SELECT 'PAYMENTS', COUNT(_) FROM payments
UNION ALL
SELECT 'BOOKING_COUPON_LOG', COUNT(_) FROM booking_coupon_log;

Nói:

Sau khi thao tác trên web, số dòng trong các bảng đã thay đổi. Đây là bằng chứng web đang ghi dữ liệu thật vào Oracle.

8.4 Xem booking mới nhất
SELECT
b.booking_id,
u.email,
c.full_name,
brn.branch_name,
b.checkin_expected_at,
b.checkout_expected_at,
b.status,
b.total_amount,
b.created_at
FROM booking b
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
JOIN branch brn ON brn.branch_id = b.branch_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY b.created_at DESC
FETCH FIRST 5 ROWS ONLY;

Xem phòng mới gán:

SELECT
b.booking_id,
br.booking_room_id,
r.room_number,
tr.type_name,
br.assigned_at
FROM booking b
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN type_room tr ON tr.type_room_id = r.type_room_id
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY b.created_at DESC, br.booking_room_id DESC
FETCH FIRST 5 ROWS ONLY;

Xem pet mới gắn:

SELECT
b.booking_id,
br.booking_room_id,
r.room_number,
p.pet_name,
p.weight_kg
FROM booking b
JOIN booking_room br ON br.booking_id = b.booking_id
JOIN room r ON r.room_id = br.room_id
JOIN booking_room_pet brp ON brp.booking_room_id = br.booking_room_id
JOIN pet p ON p.pet_id = brp.pet_id
JOIN customer c ON c.customer_id = b.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY b.created_at DESC, p.pet_name
FETCH FIRST 10 ROWS ONLY;

Nói:

Booking mới đã sinh ra bản ghi trong booking, booking_room và booking_room_pet. Điều này thể hiện quan hệ 1 booking có thể có phòng và thú cưng đi kèm.

PHẦN 9: DEMO PAYMENT TỰ CẬP NHẬT
9.1 Trước thanh toán

Chạy:

SELECT
o.order_id,
o.status AS order_status,
o.subtotal,
o.discount_amount,
o.grand_total,
p.payment_id,
p.amount,
p.status AS payment_status,
p.paid_at
FROM orders o
LEFT JOIN payments p ON p.order_id = o.order_id
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY o.created_at DESC, p.created_at DESC
FETCH FIRST 5 ROWS ONLY;

Nói:

Trước khi thanh toán, order thường ở trạng thái PENDING và payment cũng có thể là PENDING.

9.2 Thao tác thanh toán trên web

Trên web:

1. Mở hóa đơn/booking mới nhất
2. Chọn thanh toán
3. Chọn phương thức: BANK_TRANSFER hoặc CASH
4. Xác nhận thanh toán
   9.3 Sau thanh toán

Chạy lại query:

SELECT
o.order_id,
o.status AS order_status,
o.subtotal,
o.discount_amount,
o.grand_total,
p.payment_id,
p.amount,
p.status AS payment_status,
p.paid_at
FROM orders o
LEFT JOIN payments p ON p.order_id = o.order_id
JOIN customer c ON c.customer_id = o.customer_id
JOIN users u ON u.id = c.user_id
WHERE u.email = 'customer.small@pethotel.test'
ORDER BY o.created_at DESC, p.created_at DESC
FETCH FIRST 5 ROWS ONLY;

Nói:

Sau khi thanh toán, payment chuyển sang SUCCESS và paid_at có thời gian thanh toán. Nếu hệ thống cập nhật order, trạng thái order cũng chuyển sang PAID hoặc COMPLETED tùy logic hiện tại.

PHẦN 10: DEMO ADMIN/MANAGER XEM DỮ LIỆU

Login:

admin.demo@pethotel.test
password123

Hoặc:

manager.central@pethotel.test
password123

Nói:

Cuối cùng em đăng nhập bằng tài khoản quản lý để xem dữ liệu tổng quan. Tài khoản admin dùng trang /authentication/login và được chuyển tới dashboard quản lý, không dùng customer API.

Thao tác trên web:

1. Mở dashboard
2. Mở danh sách booking
3. Mở order/payment nếu có
4. Mở tồn kho nếu có
5. Lọc theo chi nhánh Gò Vấp hoặc Thủ Đức

Oracle query tổng hợp booking theo chi nhánh:

SELECT
brn.branch_name,
b.status,
COUNT(\*) AS total_booking
FROM booking b
JOIN branch brn ON brn.branch_id = b.branch_id
GROUP BY brn.branch_name, b.status
ORDER BY brn.branch_name, b.status;

Tổng doanh thu/order theo trạng thái:

SELECT
o.status,
COUNT(\*) AS total_orders,
SUM(o.subtotal) AS total_subtotal,
SUM(o.discount_amount) AS total_discount,
SUM(o.grand_total) AS total_grand_total
FROM orders o
GROUP BY o.status
ORDER BY o.status;

Tổng payment thành công:

SELECT
p.status,
COUNT(\*) AS total_payments,
SUM(p.amount) AS total_amount
FROM payments p
GROUP BY p.status
ORDER BY p.status;

Nói:

Các query này cho thấy dữ liệu web ghi vào Oracle có thể được tổng hợp lại cho dashboard: số booking theo chi nhánh, tổng order theo trạng thái và tổng payment theo trạng thái.

PHẦN 11: KẾT LUẬN VIDEO

Nói kết thúc:

Như vậy, em đã demo xong flow chính của hệ thống Pet Hotel. Khách hàng có thể đăng nhập, xem thú cưng, chọn chi nhánh, đặt phòng, chọn dịch vụ, áp mã giảm giá, tạo hóa đơn và thanh toán. Ở phía Oracle, dữ liệu được ghi vào các bảng liên quan như booking, booking_room, booking_room_pet, booking_service_pet, orders, order_details, payments và booking_coupon_log. Ngoài ra, tồn kho trong branch_inventory cũng được cập nhật khi dịch vụ hoàn thành. Điều này chứng minh hệ thống web đang kết nối và thao tác dữ liệu thật với Oracle Database.

Ghi chú quan trọng khi sếp quay

Hiện trong tài liệu demo, phần trừ kho đang là mô phỏng bằng seeder applyInventoryUsage(), chưa phải trigger/procedure Oracle thật. Tài liệu ghi rõ trigger/procedure trừ kho Oracle chưa bật trong giai đoạn demo này; nếu sau này bật trigger/procedure thật thì phải bỏ phần mô phỏng để tránh trừ kho 2 lần.

Nếu thầy/cô hỏi “auto thay đổi là do Oracle trigger hay do backend?”, sếp trả lời:

Ở bản demo hiện tại, phần booking/order/payment là dữ liệu web ghi trực tiếp vào Oracle khi thao tác trên web. Riêng phần trừ kho đang được mô phỏng bằng logic backend/seeder theo service DONE để tránh bật trigger khi migration chưa ổn định. Trong hướng phát triển tiếp theo, logic này có thể chuyển thành Oracle trigger/procedure để database tự động xử lý tập trung.
