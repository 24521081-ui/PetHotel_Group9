# Demo Checklist - Pet Hotel Web

Trang thai dung khi tap demo:

- `Chua demo`: chua thuc hien.
- `Da demo`: da thuc hien thanh cong.
- `Loi`: gap loi can ghi chu.

| STT | Chuc nang | Vai tro lien quan | URL/API | Muc do | Trang thai | Ghi chu |
|---:|---|---|---|---|---|---|
| 1 | Trang chu | Khach vang lai | `/` | Cao | Chua demo | Gioi thieu tong quan website |
| 2 | Xem danh sach chi nhanh | Khach vang lai | `/branches` | Cao | Chua demo | Co loc search/district |
| 3 | Xem chi tiet chi nhanh | Khach vang lai | `/branches/{branchId}` | Cao | Chua demo | Nen dung `/branches/4` |
| 4 | Xem dich vu grooming/spa | Khach vang lai | `/services`, `/services/grooming`, `/services/spa` | Trung binh | Chua demo | Web controller tra cung view grooming |
| 5 | Xem phong cho | Khach vang lai | `/rooms/dog` | Trung binh | Chua demo | Trang tinh |
| 6 | Xem phong meo | Khach vang lai | `/rooms/cat` | Trung binh | Chua demo | Trang tinh |
| 7 | Xem phong normal cho | Khach vang lai | `/rooms/normal/dog` | Cao | Chua demo | Co tinh phong trong theo ngay |
| 8 | Xem phong vip cho/meo | Khach vang lai | `/rooms/vip/dog`, `/rooms/vip/cat` | Cao | Chua demo | Slug `vip` map type_room_id 2 |
| 9 | Xem phong luxury cho/meo | Khach vang lai | `/rooms/luxury/dog`, `/rooms/luxury/cat` | Cao | Chua demo | Slug `luxury` map type_room_id 3 |
| 10 | Dang ky tai khoan | Khach vang lai | `/authentication/register` | Cao | Chua demo | Can email/phone moi |
| 11 | Dang nhap customer | Khach hang | `/authentication/login` | Cao | Chua demo | `customer.small@pethotel.test` |
| 12 | Xem ho so | Khach hang | `/profile` | Trung binh | Chua demo | Lay `users` + `customer` |
| 13 | Sua ho so | Khach hang | `/profile/edit`, `POST /profile` | Trung binh | Chua demo | Co validate email/phone |
| 14 | Xem thu cung | Khach hang | `/pets` | Cao | Chua demo | Hien trang thai dang o phong |
| 15 | Them thu cung | Khach hang | `/pets/create`, `POST /pets` | Cao | Chua demo | DOG/CAT/BIRD/RABBIT/OTHER |
| 16 | Sua thu cung | Khach hang | `/pets/{petId}/edit` | Trung binh | Chua demo | Khong sua neu CHECKED_IN |
| 17 | Xoa thu cung | Khach hang | Khong co route | Thap | Chua demo | Chua ho tro theo code |
| 18 | Chon chi nhanh dat phong | Khach hang | `/booking` | Cao | Chua demo | Hien danh sach chi nhanh |
| 19 | Tao booking tu chi nhanh | Khach hang | `/booking/branch/4` | Cao | Chua demo | Form co branch, room type, pet, service |
| 20 | API availability room type | Khach hang | `/api/booking/branch/{branchId}/room-types/availability` | Cao | Chua demo | AJAX tu booking UI |
| 21 | Giu cho booking | Khach hang | `POST /booking`, `booking_action=hold` | Cao | Chua demo | Tao booking/order/payment pending |
| 22 | Thanh toan ngay booking | Khach hang | `POST /booking`, `booking_action=pay` | Cao | Chua demo | Redirect sang payment |
| 23 | Xem hoa don thanh toan | Khach hang | `/payment/booking/{bookingId}` | Cao | Chua demo | Tao/lay order |
| 24 | Ap dung coupon | Khach hang | `POST /payment/booking/{bookingId}/coupon` | Trung binh | Chua demo | Thu `DEMO10` hoac `WELCOME50` |
| 25 | Xu ly thanh toan | Khach hang | `POST /payment/booking/{bookingId}` | Cao | Chua demo | Order COMPLETED, payment SUCCESS |
| 26 | Xem payment success | Khach hang | `/payment/success?booking_id=...` | Cao | Chua demo | Chi thanh cong neu order completed |
| 27 | Xem lich su booking | Khach hang | `/profile/history-booking` | Cao | Chua demo | Active/done/cancelled |
| 28 | Xem chi tiet booking | Khach hang | `/booking/{bookingId}` | Cao | Chua demo | Chi booking cua customer dang nhap |
| 29 | Dang nhap nhan vien/groomer | Nhan vien/le tan | `/authentication/login` | Trung binh | Chua demo | `groomer.govap@pethotel.test` |
| 30 | Dashboard manager web | Nhan vien/quan ly | `/manager/dashboard` | Trung binh | Chua demo | Placeholder |
| 31 | Dashboard manager API | Nhan vien/quan ly | `/api/manager/dashboard` | Cao | Chua demo | Count booking/order/available_rooms |
| 32 | Dich vu manager API | Quan ly | `/api/manager/services` | Trung binh | Chua demo | Lay `services` active |
| 33 | Ton kho manager API | Quan ly | `/api/manager/inventory` | Cao | Chua demo | Lay `branch_inventory` |
| 34 | Bao cao manager API | Quan ly | `/api/manager/reports` | Cao | Chua demo | Revenue + orders |
| 35 | Check-in/check-out booking | Nhan vien/le tan | Khong co UI route | Cao | Chua demo | Chi co model event khi update qua Eloquent |
| 36 | Dang nhap admin/CEO | Admin/CEO | `/authentication/login` | Cao | Chua demo | `admin.demo@pethotel.test` |
| 37 | CEO dashboard web | Admin/CEO | `/ceo/dashboard` | Trung binh | Chua demo | Placeholder |
| 38 | CEO dashboard API | Admin/CEO | `/api/ceo/dashboard` | Cao | Chua demo | Count branch/service/booking/order |
| 39 | CEO branches API | Admin/CEO | `/api/ceo/branches` | Cao | Chua demo | Branch with counts |
| 40 | CEO services API | Admin/CEO | `/api/ceo/services` | Trung binh | Chua demo | Danh muc dich vu |
| 41 | CEO vendors/products API | Admin/CEO | `/api/ceo/vendors` | Trung binh | Chua demo | Message + product |
| 42 | CEO finance API | Admin/CEO | `/api/ceo/finance` | Cao | Chua demo | Revenue, pending_orders |
| 43 | Audit log | Admin/CEO/DB demo | Bang `audit_log` | Trung binh | Chua demo | BookingRepository co ghi audit khi tao booking |
| 44 | Non-repeatable read | DB demo | SQL 2 sessions | Cao | Chua demo | Xem `CONCURRENCY_DEMO.md` |
| 45 | Phantom read | DB demo | SQL 2 sessions | Cao | Chua demo | Xem `CONCURRENCY_DEMO.md` |
| 46 | Lost Update | DB demo | SQL 2 sessions | Cao | Chua demo | Dung `branch_inventory` |
| 47 | Deadlock | DB demo | SQL 2 sessions | Cao | Chua demo | Dung 2 dong `branch_inventory` |

## Checklist chuan bi du lieu

| Du lieu | Bang | Seeder | Muc do | Trang thai | Ghi chu |
|---|---|---|---|---|---|
| Tai khoan demo | `users` | `UserSeeder` | Cao | Chua demo | Password `password123` |
| Chi nhanh | `branch` | `BranchRoomSeeder` | Cao | Chua demo | 4 chi nhanh |
| Loai phong | `type_room` | `BranchRoomSeeder` | Cao | Chua demo | 3 loai phong |
| Phong | `room` | `BranchRoomSeeder` | Cao | Chua demo | Co AVAILABLE/MAINTENANCE |
| Nhan vien | `employee` | `PeoplePetSeeder` | Cao | Chua demo | Manager/groomer |
| Khach hang | `customer` | `PeoplePetSeeder` | Cao | Chua demo | 6 customer |
| Thu cung | `pet` | `PeoplePetSeeder` | Cao | Chua demo | Chu yeu DOG |
| San pham | `product`, `category_product` | `ProductServiceSeeder` | Trung binh | Chua demo | Vat tu dich vu |
| Dich vu | `services`, `category_services` | `ProductServiceSeeder` | Cao | Chua demo | Tam, grooming, kiem tra, cat mong |
| Ton kho | `branch_inventory` | `CouponInventorySeeder` | Cao | Chua demo | Moi chi nhanh moi product |
| Coupon | `coupon` | `CouponInventorySeeder` | Trung binh | Chua demo | `DEMO10`, `WELCOME50`, `VIP15` |
| Booking | `booking`, `booking_room`, `booking_room_pet`, `booking_service_pet` | `BookingSeeder` | Cao | Chua demo | 6 booking mau |
| Order/payment | `orders`, `order_details`, `payments` | `OrderSeeder` | Cao | Chua demo | Pending/partial/completed |
| Audit | `audit_log` | `AuditLogSeeder`, repository | Trung binh | Chua demo | Lich su thay doi |
