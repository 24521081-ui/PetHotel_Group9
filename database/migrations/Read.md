# Thiết kế database chức năng giảm giá / coupon / discount

Tài liệu này phân tích phần giảm giá cho Laravel Pet Hotel Web dựa trên schema và code hiện tại sau khi chuyển sang Oracle. Tài liệu chỉ là đề xuất thiết kế, chưa yêu cầu sửa migration/controller/model ngay.

## 1. Hiện trạng trong project

Project hiện đã có các thành phần giảm giá sau:

- Bảng `coupon`
- Bảng `booking_coupon_log`
- Cột `orders.coupon_id`
- Cột `orders.discount_amount`
- Cột `orders.grand_total`
- Model `Coupon`
- Model `BookingCouponLog`
- Quan hệ `Order -> coupon`
- Quan hệ `Booking -> couponLogs`
- Logic nhập mã giảm giá ở màn hình thanh toán
- Logic tính giảm giá trong `app/Repositories/Eloquent/PaymentRepository.php`

Không thấy bảng `coupons`, `discount`, `promotion`, `order_coupon`, `coupon_customer`, `coupon_branch`, `coupon_service`, hoặc `coupon_type_room`.

Không thấy thư mục/file `database/database_mau` trong project hiện tại.

## 2. Bảng `coupon` hiện có

Migration hiện tại: `2026_05_19_000014_create_coupon_table.php`

Thuộc tính hiện có:

| Cột | Ý nghĩa | Ghi chú |
|---|---|---|
| `coupon_id` | Khóa chính | Numeric auto increment, phù hợp Oracle/Laravel hiện tại |
| `coupon_code` | Mã khách nhập | Unique, max 50 |
| `employee_id` | Nhân viên tạo/quản lý coupon | FK tới `employee.employee_id`, nullable |
| `discount_type` | Loại giảm giá | `FIXED` hoặc `PERCENT` |
| `discount_value` | Giá trị giảm | > 0 |
| `max_discount` | Số tiền giảm tối đa | Hữu ích cho `PERCENT` |
| `min_order_value` | Giá trị đơn tối thiểu | >= 0 |
| `max_uses` | Tổng lượt dùng tối đa | nullable |
| `used_count` | Số lượt đã dùng | >= 0 |
| `effective_from` | Bắt đầu hiệu lực | DateTime |
| `expired_at` | Hết hiệu lực | DateTime |
| `is_active` | Bật/tắt coupon | 0/1 |
| `notes` | Ghi chú | nullable |
| `created_at`, `updated_at` | Timestamp Laravel | Có |

Constraint hiện có:

- PK: `coupon_id`
- Unique: `uq_coupon_code` trên `coupon_code`
- FK: `fk_coupon_employee`, `employee_id -> employee.employee_id`, `nullOnDelete`
- Check: `discount_type IN ('FIXED','PERCENT')`
- Check: `discount_value > 0`
- Check: nếu `PERCENT` thì `discount_value <= 100`
- Check: `min_order_value >= 0`
- Check: `max_uses IS NULL OR max_uses > 0`
- Check: `used_count >= 0`
- Check: `expired_at > effective_from`
- Check: `is_active IN (0,1)`

Đánh giá:

- Bảng này đã đủ cho phương án coupon cơ bản.
- Không nên đổi tên `coupon` thành `coupons` vì model, migration, repository và seeders đang dùng `coupon`.
- Không nên đổi `coupon_code` thành `code` ngay, vì UI/controller/repository đang dùng `coupon_code`.
- Không nên đổi `FIXED` thành `FIXED_AMOUNT` ngay, vì constraint và logic hiện tại đang dùng `FIXED`.

## 3. Bảng `booking_coupon_log` hiện có

Migration hiện tại: `2026_05_19_000022_create_booking_coupon_log_table.php`

Thuộc tính hiện có:

| Cột | Ý nghĩa | Ghi chú |
|---|---|---|
| `booking_coupon_log_id` | Khóa chính | Numeric auto increment |
| `booking_id` | Booking được áp coupon | FK tới `booking.booking_id` |
| `coupon_id` | Coupon được dùng | FK tới `coupon.coupon_id` |
| `applied_at` | Thời điểm áp dụng | Default current timestamp |
| `notes` | Ghi chú | nullable |
| `created_at`, `updated_at` | Timestamp Laravel | Có |

Constraint hiện có:

- PK: `booking_coupon_log_id`
- Unique: `uq_bcl_booking_coupon_time` trên `booking_id`, `coupon_id`, `applied_at`
- FK: `fk_bcl_booking`, `booking_id -> booking.booking_id`, `cascadeOnDelete`
- FK: `fk_bcl_coupon`, `coupon_id -> coupon.coupon_id`

Đánh giá:

- Bảng này đang đóng vai trò lịch sử coupon theo booking.
- Hiện chưa lưu snapshot như `coupon_code_snapshot`, `discount_type_snapshot`, `discount_value_snapshot`, `discount_amount`.
- Nếu coupon bị chỉnh sau khi order đã thanh toán, log cũ không đủ dữ liệu để tái dựng lịch sử chính xác. May mắn là `orders.discount_amount` vẫn lưu số tiền đã giảm.

## 4. Bảng `orders` liên quan giảm giá

Migration hiện tại: `2026_05_19_000020_create_orders_table.php`

Các cột liên quan:

| Cột | Ý nghĩa |
|---|---|
| `coupon_id` | Coupon áp dụng cho order, nullable |
| `subtotal` | Tổng trước giảm |
| `discount_amount` | Số tiền giảm |
| `grand_total` | Tổng sau giảm |

Constraint liên quan:

- FK: `fk_orders_coupon`, `coupon_id -> coupon.coupon_id`, `nullOnDelete`
- Check: `discount_amount >= 0`
- Check: `grand_total >= 0`
- Check: `grand_total = subtotal - discount_amount`

Đánh giá:

- Code hiện tại tính giảm giá ở cấp `orders`, không phải trực tiếp ở `booking`.
- `booking_coupon_log` chỉ ghi log theo booking sau khi áp mã.
- Với logic hiện tại, mỗi `orders` chỉ có tối đa một `coupon_id`.
- Nếu muốn một order dùng nhiều coupon thì cần bảng kết `order_coupon`, nhưng việc này sẽ thay đổi logic payment hiện tại.

## 5. Logic hiện tại trong code

File chính: `app/Repositories/Eloquent/PaymentRepository.php`

Luồng hiện tại:

1. Người dùng nhập `coupon_code` ở `resources/views/client/payments/create.blade.php`.
2. `Web\Customer\PaymentController` validate `coupon_code`.
3. `PaymentRepository::previewCouponForUser()` kiểm tra coupon để preview.
4. `PaymentRepository::confirmBookingPaymentForUser()` khóa order bằng `lockForUpdate()`.
5. Tìm coupon bằng `UPPER(coupon_code)`.
6. Kiểm tra:
   - coupon tồn tại
   - `is_active`
   - `effective_from`, `expired_at`
   - `max_uses`, `used_count`
   - `min_order_value`
7. Tính `discount_amount`.
8. Update `orders.coupon_id`, `orders.discount_amount`, `orders.grand_total`.
9. Tăng `coupon.used_count`.
10. Tạo bản ghi `booking_coupon_log`.
11. Update `booking.total_amount` theo `orders.grand_total`.

Kết luận: Web hiện tại áp giảm giá ở cấp `order`, nhưng ghi lịch sử theo `booking`.

## 6. Nghiệp vụ giảm giá cần hỗ trợ

### 6.1 Giảm giá theo mã coupon

Đang có và đang hoạt động.

Ví dụ:

- `WELCOME50`
- `PET10`

Áp dụng tại checkout/payment.

### 6.2 Giảm giá theo khách hàng

Nghiệp vụ:

- Coupon chỉ dành cho khách VIP, khách mới, hoặc nhóm khách được chỉ định.
- Một coupon có nhiều customer.
- Một customer có nhiều coupon.

Quan hệ: `coupon` N-N `customer`

Cần bảng kết: `coupon_customer`

### 6.3 Giảm giá theo chi nhánh

Nghiệp vụ:

- Coupon chỉ áp dụng tại một số chi nhánh.
- Một coupon áp dụng cho nhiều branch.
- Một branch có nhiều coupon.

Quan hệ: `coupon` N-N `branch`

Cần bảng kết: `coupon_branch`

### 6.4 Giảm giá theo dịch vụ

Nghiệp vụ:

- Coupon chỉ áp dụng cho grooming, bathing, health check...
- Một coupon áp dụng cho nhiều service.
- Một service có nhiều coupon.

Quan hệ: `coupon` N-N `services`

Cần bảng kết: `coupon_service`

### 6.5 Giảm giá theo loại phòng

Nghiệp vụ:

- Coupon chỉ áp dụng cho phòng tiêu chuẩn, VIP, Luxury...
- Một coupon áp dụng cho nhiều type_room.
- Một type_room có nhiều coupon.

Quan hệ: `coupon` N-N `type_room`

Cần bảng kết: `coupon_type_room`

### 6.6 Giảm giá theo order

Nghiệp vụ:

- Khi khách thanh toán, order cần ghi nhận coupon đã áp dụng.
- Hiện tại `orders.coupon_id` hỗ trợ 1 coupon/order.
- Nếu muốn nhiều coupon/order, cần `order_coupon`.

Quan hệ nếu mở rộng: `orders` N-N `coupon` thông qua `order_coupon`

### 6.7 Giảm giá theo booking

Nghiệp vụ:

- Booking là nguồn tạo order trong web hiện tại.
- `booking_coupon_log` đang ghi log theo booking.
- Tuy nhiên tiền cuối cùng nằm ở `orders`.

Khuyến nghị:

- Tiếp tục tính tiền và giảm giá ở `orders`.
- Giữ `booking_coupon_log` để log lịch sử theo booking.
- Không chuyển toàn bộ discount sang booking vì sẽ làm lệch logic hiện tại của payment/order.

## 7. Đề xuất bảng chính `coupon`

Vì project đã có bảng `coupon`, nên không thiết kế lại từ đầu. Nên giữ tên bảng và các cột hiện tại, chỉ bổ sung khi cần.

### 7.1 Thiết kế nên dùng ở giai đoạn hiện tại

Giữ:

- `coupon_id`
- `coupon_code`
- `employee_id`
- `discount_type`
- `discount_value`
- `max_discount`
- `min_order_value`
- `max_uses`
- `used_count`
- `effective_from`
- `expired_at`
- `is_active`
- `notes`
- `created_at`
- `updated_at`

Có thể bổ sung sau:

| Cột đề xuất | Kiểu Laravel Oracle-safe | Lý do |
|---|---|---|
| `coupon_name` | `string('coupon_name', 100)->nullable()` | Tên chương trình dễ hiển thị trong admin |
| `usage_limit_per_customer` | `integer('usage_limit_per_customer')->nullable()` | Giới hạn mỗi khách dùng bao nhiêu lần |
| `created_by_user_id` | `bigInteger('created_by_user_id')->nullable()` | Nếu muốn gắn với `users.id` thay vì `employee_id` |
| `status` | `string('status', 20)->nullable()` | Chỉ thêm nếu cần workflow ACTIVE/INACTIVE/EXPIRED/DRAFT |

Lưu ý:

- Hiện đã có `is_active`, chưa nên thêm `status` nếu chưa cần workflow phức tạp.
- Nếu thêm `status`, phải định nghĩa rõ quan hệ giữa `status` và `is_active`, nếu không sẽ dư nghĩa.
- Nếu web hiện tại đang dùng `employee_id`, không nên thay bằng `created_by_user_id` ngay.

### 7.2 Mapping tên theo yêu cầu nghiệp vụ

| Tên nghiệp vụ phổ biến | Tên hiện tại trong project |
---|---|
| `code` | `coupon_code` |
| `max_discount_amount` | `max_discount` |
| `min_order_amount` | `min_order_value` |
| `start_date` | `effective_from` |
| `end_date` | `expired_at` |
| `usage_limit` | `max_uses` |
| `FIXED_AMOUNT` | `FIXED` |

Không nên đổi tên ngay vì sẽ ảnh hưởng migration, seeders, models, repository, views và JS.

## 8. Các bảng kết N-N đề xuất

Các bảng dưới đây là mở rộng nghiệp vụ. Không nhất thiết tạo ngay nếu mục tiêu hiện tại là demo web ổn định.

### 8.1 `coupon_customer`

Mục đích: giới hạn coupon cho một số khách hàng.

Thuộc tính đề xuất:

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `coupon_customer_id` | id | PK |
| `coupon_id` | bigInteger | FK -> `coupon.coupon_id` |
| `customer_id` | bigInteger | FK -> `customer.customer_id` |
| `assigned_at` | timestamp nullable | Thời điểm gán coupon |
| `used_count` | integer default 0 | Số lần khách này dùng coupon |
| `is_active` | tinyInteger default 1 | Bật/tắt gán coupon |
| `notes` | text nullable | Ghi chú |
| `created_at`, `updated_at` | timestamps | Laravel |

Cardinality:

- `coupon` 1-N `coupon_customer`
- `customer` 1-N `coupon_customer`
- `coupon` N-N `customer` thông qua `coupon_customer`

Constraint:

- Unique: `coupon_id`, `customer_id`
- Check: `used_count >= 0`
- Check: `is_active IN (0,1)`

### 8.2 `coupon_branch`

Mục đích: giới hạn coupon cho chi nhánh.

Thuộc tính đề xuất:

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `coupon_branch_id` | id | PK |
| `coupon_id` | bigInteger | FK -> `coupon.coupon_id` |
| `branch_id` | bigInteger | FK -> `branch.branch_id` |
| `created_at`, `updated_at` | timestamps | Laravel |

Cardinality:

- `coupon` N-N `branch` thông qua `coupon_branch`

Constraint:

- Unique: `coupon_id`, `branch_id`

### 8.3 `coupon_service`

Mục đích: giới hạn coupon cho dịch vụ.

Thuộc tính đề xuất:

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `coupon_service_id` | id | PK |
| `coupon_id` | bigInteger | FK -> `coupon.coupon_id` |
| `service_id` | bigInteger | FK -> `services.service_id` |
| `created_at`, `updated_at` | timestamps | Laravel |

Cardinality:

- `coupon` N-N `services` thông qua `coupon_service`

Constraint:

- Unique: `coupon_id`, `service_id`

### 8.4 `coupon_type_room`

Mục đích: giới hạn coupon cho loại phòng.

Thuộc tính đề xuất:

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `coupon_type_room_id` | id | PK |
| `coupon_id` | bigInteger | FK -> `coupon.coupon_id` |
| `type_room_id` | bigInteger | FK -> `type_room.type_room_id` |
| `created_at`, `updated_at` | timestamps | Laravel |

Cardinality:

- `coupon` N-N `type_room` thông qua `coupon_type_room`

Constraint:

- Unique: `coupon_id`, `type_room_id`

### 8.5 `order_coupon`

Mục đích: lưu lịch sử coupon đã áp dụng cho order, hỗ trợ nhiều coupon/order và snapshot.

Thuộc tính đề xuất:

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `order_coupon_id` | id | PK |
| `order_id` | bigInteger | FK -> `orders.order_id` |
| `coupon_id` | bigInteger nullable | FK -> `coupon.coupon_id`, nullable để giữ lịch sử nếu coupon bị xóa |
| `customer_id` | bigInteger nullable | FK -> `customer.customer_id` |
| `coupon_code_snapshot` | string(50) | Mã tại thời điểm áp dụng |
| `discount_type_snapshot` | string(20) | Loại giảm tại thời điểm áp dụng |
| `discount_value_snapshot` | decimal(10,2) | Giá trị giảm tại thời điểm áp dụng |
| `discount_amount` | decimal(10,2) | Số tiền thực giảm |
| `applied_at` | timestamp | Thời điểm áp dụng |
| `status` | string(20) | APPLIED/REMOVED/REFUNDED |
| `notes` | text nullable | Ghi chú |
| `created_at`, `updated_at` | timestamps | Laravel |

Cardinality:

- `orders` 1-N `order_coupon`
- `coupon` 1-N `order_coupon`
- `customer` 1-N `order_coupon`

Constraint:

- Check: `discount_amount >= 0`
- Check: `status IN ('APPLIED','REMOVED','REFUNDED')`
- Check: `discount_type_snapshot IN ('FIXED','PERCENT')`

Đánh giá:

- Đây là bảng tốt nhất nếu muốn hóa đơn cũ không bị ảnh hưởng khi coupon đổi.
- Nhưng nếu thêm ngay sẽ cần sửa `PaymentRepository`, model và có thể view/report.

## 9. Có cần bảng kết nhiều-nhiều không?

Hiện tại: chưa cần nếu chỉ demo nhập mã giảm giá ở checkout.

Cần bảng kết nếu có các rule sau:

- Coupon chỉ dành cho một số khách: cần `coupon_customer`
- Coupon chỉ áp dụng ở chi nhánh cụ thể: cần `coupon_branch`
- Coupon chỉ áp dụng cho một số dịch vụ: cần `coupon_service`
- Coupon chỉ áp dụng cho một số loại phòng: cần `coupon_type_room`
- Một order được dùng nhiều coupon: cần `order_coupon`

Nếu chưa có UI/admin quản lý các điều kiện này, thêm quá nhiều bảng sẽ làm tăng độ phức tạp và dễ làm gãy migration/seeder/controller.

## 10. Phương án A - Tối giản, dễ chạy web hiện tại

### Bảng dùng

- `coupon`
- `booking_coupon_log`
- `orders.coupon_id`
- `orders.discount_amount`
- `orders.grand_total`

### Cách hoạt động

- Khách nhập `coupon_code` ở checkout.
- Repository kiểm tra coupon.
- Repository tính `discount_amount`.
- Repository cập nhật `orders`.
- Repository ghi `booking_coupon_log`.

### Ưu điểm

- Phù hợp code hiện tại nhất.
- Ít sửa migration.
- Ít sửa model/controller.
- Ít rủi ro làm hỏng thanh toán.
- Đủ cho demo chức năng giảm giá.

### Nhược điểm

- Mỗi order chỉ dùng được một coupon.
- Chưa giới hạn coupon theo customer/branch/service/type_room.
- `booking_coupon_log` chưa có snapshot chi tiết.
- Nếu coupon đổi sau này, log không đủ thông tin để tái dựng toàn bộ lịch sử.

### Bổ sung nên làm nếu chọn A

Nên bổ sung snapshot nhẹ vào `booking_coupon_log`:

- `coupon_code_snapshot`
- `discount_type_snapshot`
- `discount_value_snapshot`
- `discount_amount`

Như vậy vẫn giữ logic hiện tại nhưng lịch sử chắc hơn.

## 11. Phương án B - Đầy đủ nghiệp vụ, sát thực tế

### Bảng dùng

- `coupon`
- `coupon_customer`
- `coupon_branch`
- `coupon_service`
- `coupon_type_room`
- `order_coupon`
- Có thể giữ `booking_coupon_log` hoặc thay vai trò bằng `order_coupon`

### Ưu điểm

- ERD đầy đủ, rõ quan hệ N-N.
- Hỗ trợ coupon theo khách/chi nhánh/dịch vụ/loại phòng.
- Hỗ trợ nhiều coupon trên một order nếu business cho phép.
- Có snapshot lịch sử chuẩn ở `order_coupon`.
- Phù hợp hệ thống khuyến mãi chuyên nghiệp.

### Nhược điểm

- Cần sửa `PaymentRepository`.
- Cần thêm model relationships.
- Cần sửa seeder.
- Cần sửa admin UI nếu muốn quản lý các điều kiện áp dụng.
- Cần quyết định rule conflict khi nhiều coupon cùng áp dụng.

## 12. Khuyến nghị hiện tại

Khuyến nghị chọn Phương án A trong giai đoạn hiện tại.

Lý do:

- Web hiện tại đã có coupon flow ở payment.
- `orders` đã có đủ `coupon_id`, `discount_amount`, `grand_total`.
- `PaymentRepository` đang tính giảm giá theo order.
- `booking_coupon_log` đã tồn tại và đang được dùng.
- Mục tiêu hiện tại là chuyển Oracle, giữ business logic và tránh gãy migration/controller.

Với ERD/báo cáo, có thể vẽ Phương án A là phần hiện tại và vẽ các bảng của Phương án B dưới dạng mở rộng tương lai.

## 13. ERD text cho phương án hiện tại

### Entity chính

`coupon`

- PK: `coupon_id`
- FK: `employee_id -> employee.employee_id`
- Unique: `coupon_code`

`orders`

- PK: `order_id`
- FK: `customer_id -> customer.customer_id`
- FK: `branch_id -> branch.branch_id`
- FK: `booking_id -> booking.booking_id`
- FK: `coupon_id -> coupon.coupon_id`

`booking`

- PK: `booking_id`
- FK: `customer_id -> customer.customer_id`
- FK: `branch_id -> branch.branch_id`

`booking_coupon_log`

- PK: `booking_coupon_log_id`
- FK: `booking_id -> booking.booking_id`
- FK: `coupon_id -> coupon.coupon_id`

### Cardinality hiện tại

- `employee` 1-N `coupon`
- `coupon` 1-N `orders`
- `coupon` 1-N `booking_coupon_log`
- `booking` 1-N `booking_coupon_log`
- `customer` 1-N `booking`
- `booking` 1-N `orders` về mặt schema hiện tại, nhưng migration `2026_05_24_000002_add_unique_index_to_orders_booking_id.php` làm `orders.booking_id` unique nên thực tế là `booking` 1-0/1 `orders`
- `orders` 1-N `order_details`
- `orders` 1-0/1 `payments`

## 14. ERD text cho phương án mở rộng

### Entity và junction table

`coupon_customer`

- PK: `coupon_customer_id`
- FK: `coupon_id -> coupon.coupon_id`
- FK: `customer_id -> customer.customer_id`
- `coupon` N-N `customer`

`coupon_branch`

- PK: `coupon_branch_id`
- FK: `coupon_id -> coupon.coupon_id`
- FK: `branch_id -> branch.branch_id`
- `coupon` N-N `branch`

`coupon_service`

- PK: `coupon_service_id`
- FK: `coupon_id -> coupon.coupon_id`
- FK: `service_id -> services.service_id`
- `coupon` N-N `services`

`coupon_type_room`

- PK: `coupon_type_room_id`
- FK: `coupon_id -> coupon.coupon_id`
- FK: `type_room_id -> type_room.type_room_id`
- `coupon` N-N `type_room`

`order_coupon`

- PK: `order_coupon_id`
- FK: `order_id -> orders.order_id`
- FK: `coupon_id -> coupon.coupon_id`
- FK: `customer_id -> customer.customer_id`
- `orders` 1-N `order_coupon`
- `coupon` 1-N `order_coupon`
- Có snapshot để giữ lịch sử.

## 15. Bảng nên đưa vào ERD

### ERD hiện tại nên đưa

- `coupon`
- `booking_coupon_log`
- `orders`
- `booking`
- `customer`
- `employee`

### ERD mở rộng nên đưa thêm

- `coupon_customer`
- `coupon_branch`
- `coupon_service`
- `coupon_type_room`
- `order_coupon`
- `branch`
- `services`
- `type_room`

## 16. Oracle-safe migration đề xuất cho phương án A

Không cần tạo lại `coupon`. Nếu muốn tăng chất lượng lịch sử, tạo migration alter `booking_coupon_log`.

Ví dụ:

```php
Schema::table('booking_coupon_log', function (Blueprint $table) {
    $table->string('coupon_code_snapshot', 50)->nullable();
    $table->string('discount_type_snapshot', 20)->nullable();
    $table->decimal('discount_value_snapshot', 10, 2)->nullable();
    $table->decimal('discount_amount', 10, 2)->default(0);
});

DB::statement("ALTER TABLE booking_coupon_log ADD CONSTRAINT ck_bcl_disc_type CHECK (discount_type_snapshot IS NULL OR discount_type_snapshot IN ('FIXED','PERCENT'))");
DB::statement('ALTER TABLE booking_coupon_log ADD CONSTRAINT ck_bcl_disc_amt CHECK (discount_amount >= 0)');
```

Lưu ý Oracle-safe:

- Không dùng `enum`.
- Không dùng `unsigned`.
- Dùng `string + CHECK`.
- Tên constraint ngắn dưới 30 ký tự.
- Không dùng `restrictOnDelete`; nếu cần restrict thì bỏ `onDelete`.

## 17. Oracle-safe migration đề xuất cho phương án B

Chỉ nên tạo khi đã có yêu cầu quản lý coupon nâng cao.

Ví dụ bảng `coupon_branch`:

```php
Schema::create('coupon_branch', function (Blueprint $table) {
    $table->id('coupon_branch_id');
    $table->bigInteger('coupon_id');
    $table->bigInteger('branch_id');
    $table->timestamps();

    $table->unique(['coupon_id', 'branch_id'], 'uq_cbranch_pair');
    $table->foreign('coupon_id', 'fk_cbranch_coupon')
        ->references('coupon_id')
        ->on('coupon')
        ->cascadeOnDelete();
    $table->foreign('branch_id', 'fk_cbranch_branch')
        ->references('branch_id')
        ->on('branch')
        ->cascadeOnDelete();
});
```

Ví dụ bảng `order_coupon`:

```php
Schema::create('order_coupon', function (Blueprint $table) {
    $table->id('order_coupon_id');
    $table->bigInteger('order_id');
    $table->bigInteger('coupon_id')->nullable();
    $table->bigInteger('customer_id')->nullable();
    $table->string('coupon_code_snapshot', 50);
    $table->string('discount_type_snapshot', 20);
    $table->decimal('discount_value_snapshot', 10, 2);
    $table->decimal('discount_amount', 10, 2);
    $table->timestamp('applied_at')->useCurrent();
    $table->string('status', 20)->default('APPLIED');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->foreign('order_id', 'fk_oc_order')
        ->references('order_id')
        ->on('orders')
        ->cascadeOnDelete();
    $table->foreign('coupon_id', 'fk_oc_coupon')
        ->references('coupon_id')
        ->on('coupon')
        ->nullOnDelete();
    $table->foreign('customer_id', 'fk_oc_customer')
        ->references('customer_id')
        ->on('customer')
        ->nullOnDelete();
});

DB::statement("ALTER TABLE order_coupon ADD CONSTRAINT ck_oc_type CHECK (discount_type_snapshot IN ('FIXED','PERCENT'))");
DB::statement("ALTER TABLE order_coupon ADD CONSTRAINT ck_oc_status CHECK (status IN ('APPLIED','REMOVED','REFUNDED'))");
DB::statement('ALTER TABLE order_coupon ADD CONSTRAINT ck_oc_amount CHECK (discount_amount >= 0)');
```

## 18. Model relationships đề xuất

### Hiện tại nên giữ

`Coupon`

```php
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
}

public function orders()
{
    return $this->hasMany(Order::class, 'coupon_id', 'coupon_id');
}

public function bookingCouponLogs()
{
    return $this->hasMany(BookingCouponLog::class, 'coupon_id', 'coupon_id');
}
```

`Order`

```php
public function coupon()
{
    return $this->belongsTo(Coupon::class, 'coupon_id', 'coupon_id');
}
```

`Booking`

```php
public function couponLogs()
{
    return $this->hasMany(BookingCouponLog::class, 'booking_id', 'booking_id');
}
```

### Nếu chọn phương án B

`Coupon`

```php
public function customers()
{
    return $this->belongsToMany(Customer::class, 'coupon_customer', 'coupon_id', 'customer_id');
}

public function branches()
{
    return $this->belongsToMany(Branch::class, 'coupon_branch', 'coupon_id', 'branch_id');
}

public function services()
{
    return $this->belongsToMany(Service::class, 'coupon_service', 'coupon_id', 'service_id');
}

public function typeRooms()
{
    return $this->belongsToMany(TypeRoom::class, 'coupon_type_room', 'coupon_id', 'type_room_id');
}
```

`Order`

```php
public function couponApplications()
{
    return $this->hasMany(OrderCoupon::class, 'order_id', 'order_id');
}
```

`Customer`

```php
public function coupons()
{
    return $this->belongsToMany(Coupon::class, 'coupon_customer', 'customer_id', 'coupon_id');
}
```

## 19. Logic tính giảm giá đề xuất

Luồng chuẩn:

1. Chuẩn hóa code: trim, uppercase.
2. Tìm coupon theo `coupon_code`.
3. Khóa coupon/order bằng transaction nếu xác nhận thanh toán.
4. Kiểm tra `is_active`.
5. Kiểm tra `effective_from <= now < expired_at`.
6. Kiểm tra `max_uses` và `used_count`.
7. Kiểm tra `min_order_value`.
8. Nếu có `usage_limit_per_customer`, kiểm tra số lần customer đã dùng.
9. Nếu có bảng scope:
   - kiểm tra `coupon_customer`
   - kiểm tra `coupon_branch`
   - kiểm tra `coupon_service`
   - kiểm tra `coupon_type_room`
10. Tính giảm giá:
   - Nếu `PERCENT`: `discount = subtotal * discount_value / 100`
   - Nếu có `max_discount`: `discount = min(discount, max_discount)`
   - Nếu `FIXED`: `discount = discount_value`
   - Luôn `discount = min(discount, subtotal)`
11. Cập nhật:
   - `orders.coupon_id`
   - `orders.discount_amount`
   - `orders.grand_total = subtotal - discount_amount`
12. Ghi log:
   - hiện tại: `booking_coupon_log`
   - mở rộng: `order_coupon`
13. Tăng `coupon.used_count` sau khi thanh toán thành công.

Nguyên tắc:

- Không để `grand_total` âm.
- Payment luôn tính theo `orders.grand_total`.
- Log nên lưu snapshot để hóa đơn cũ không đổi khi coupon đổi.

## 20. File cần sửa/tạo nếu triển khai

### Nếu chọn phương án A

Migration:

- Tạo migration mới để thêm snapshot vào `booking_coupon_log`.

Model:

- Có thể giữ nguyên.
- Nếu thêm cột snapshot thì không bắt buộc sửa model vì đang dùng `$guarded = []`.

Repository:

- Sửa `PaymentRepository` khi tạo `BookingCouponLog::create()` để ghi snapshot:
  - `coupon_code_snapshot`
  - `discount_type_snapshot`
  - `discount_value_snapshot`
  - `discount_amount`

Seeder:

- Sửa `OrderSeeder` để seed thêm snapshot cho `booking_coupon_log`.

### Nếu chọn phương án B

Migration:

- Tạo `coupon_customer`
- Tạo `coupon_branch`
- Tạo `coupon_service`
- Tạo `coupon_type_room`
- Tạo `order_coupon`

Model:

- Tạo `OrderCoupon`
- Có thể tạo model cho các bảng kết nếu cần quản lý trực tiếp.
- Thêm relationship vào `Coupon`, `Customer`, `Branch`, `Service`, `TypeRoom`, `Order`.

Repository:

- Sửa `PaymentRepository::validCouponForOrder()`.
- Sửa `PaymentRepository::discountAmountFor()`.
- Sửa nơi ghi log coupon.

Controller/View:

- Nếu chỉ nhập code thì không cần sửa UI.
- Nếu admin quản lý phạm vi áp dụng coupon thì cần thêm form chọn customer/branch/service/type_room.

## 21. Rủi ro cần chú ý

- Không đổi tên bảng `coupon` vì code hiện tại dùng bảng này.
- Không đổi `coupon_code` thành `code` nếu chưa refactor toàn project.
- Không đổi `discount_type` từ `FIXED` sang `FIXED_AMOUNT` nếu chưa sửa constraint, seeder và repository.
- Nếu thêm `order_coupon` nhưng vẫn giữ `orders.coupon_id`, phải quyết định nguồn dữ liệu chính là bảng nào.
- Nếu cho nhiều coupon/order, constraint `orders.grand_total = subtotal - discount_amount` vẫn đúng nhưng cần tính `discount_amount` là tổng giảm.
- Nếu thêm rule theo service/type_room, phải phân biệt giảm trên toàn order hay chỉ giảm phần line item đủ điều kiện.

## 22. Kết luận

Thiết kế phù hợp nhất hiện tại là giữ cấu trúc:

- `coupon`
- `orders.coupon_id`
- `orders.discount_amount`
- `orders.grand_total`
- `booking_coupon_log`

Sau đó chỉ bổ sung snapshot vào `booking_coupon_log` nếu cần lịch sử tốt hơn.

Các bảng `coupon_customer`, `coupon_branch`, `coupon_service`, `coupon_type_room`, `order_coupon` nên đưa vào ERD như phần mở rộng tương lai, chưa nên triển khai ngay nếu mục tiêu là ổn định web và tránh phát sinh lỗi khi chạy Oracle migration/seed.
