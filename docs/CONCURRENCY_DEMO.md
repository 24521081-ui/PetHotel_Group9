# Concurrency Demo - Pet Hotel Database

Tai lieu nay thiet ke demo 4 van de truy xuat dong thoi tren cac bang that cua he thong Pet Hotel: `booking`, `booking_room`, `room`, `branch_inventory`, `orders`, `payments`, `product`, `customer`, `pet`.

Ghi chu:

- Cac vi du SQL uu tien MySQL/InnoDB vi project dang duoc yeu cau demo MySQL. Neu dung Oracle, doi cu phap isolation/lock tuong ung.
- Nen demo tren database clone hoac sau khi da `migrate:fresh --seed`, vi cac vi du co `COMMIT` de hien tuong xay ra that.
- Nen mo 2 terminal SQL doc lap: Session A va Session B.
- Dat autocommit ve 0 trong tung session khi can transaction.
- Sau demo co the chay lai `php artisan migrate:fresh --seed` de reset du lieu.

## 1. Chuan bi chung

Kiem tra du lieu mau:

```sql
SELECT booking_id, customer_id, branch_id, status, total_amount
FROM booking
ORDER BY booking_id;

SELECT branch_inventory_id, branch_id, product_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4
ORDER BY product_id;

SELECT room_id, branch_id, type_room_id, room_number, status
FROM room
WHERE branch_id = 4
ORDER BY room_id;
```

Du lieu seed phu hop:

- `booking_id = 1`: booking confirmed tai chi nhanh Go Vap.
- `branch_inventory_id = 22` den `28`: ton kho chi nhanh 4 neu seed theo thu tu branch/product.
- `room_id = 421`, `422`: phong lon Go Vap.

Neu ID khac do database da thay doi, hay lay ID bang cau query tren va thay vao cac script.

## 2. Non-repeatable read

### Y tuong demo

Session A doc trang thai booking lan 1. Session B cap nhat trang thai booking va commit. Session A doc lai cung booking lan 2 va thay gia tri thay doi trong cung transaction.

Bang dung: `booking`.

Du lieu chon: `booking.booking_id = 1`, cot `status`.

Isolation de de xuat hien: `READ COMMITTED`.

### Session A

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT booking_id, status
FROM booking
WHERE booking_id = 1;

-- Dung lai o day, chuyen sang Session B.
```

### Session B

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

UPDATE booking
SET status = 'CHECKED_IN',
    checkin_actual_at = NOW(),
    updated_at = NOW()
WHERE booking_id = 1;

COMMIT;
```

### Session A doc lai

```sql
SELECT booking_id, status
FROM booking
WHERE booking_id = 1;

COMMIT;
```

Ket qua mong doi: lan doc 1 thay `CONFIRMED`, lan doc 2 thay `CHECKED_IN`.

### Laravel minh hoa

```php
DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

DB::transaction(function () {
    $first = Booking::where('booking_id', 1)->value('status');

    // Trong luc nay request/terminal khac update booking_id = 1 va commit.

    $second = Booking::where('booking_id', 1)->value('status');
});
```

### Cach phong tranh

- Dung `REPEATABLE READ` de moi transaction thay mot snapshot on dinh.
- Khi can doc roi xu ly nghiep vu dua tren gia tri do, dung `SELECT ... FOR UPDATE` hoac Eloquent `lockForUpdate()`.
- Giu transaction ngan va chi lock dung dong can xu ly.

Vi du lock:

```sql
START TRANSACTION;

SELECT booking_id, status
FROM booking
WHERE booking_id = 1
FOR UPDATE;

-- Xu ly nghiep vu dua tren status.
COMMIT;
```

## 3. Phantom read

### Y tuong demo

Session A dem so booking cua chi nhanh Go Vap trong ngay 2026-05-26. Session B insert mot booking moi thoa dieu kien va commit. Session A dem lai va thay xuat hien them dong moi.

Bang dung: `booking`.

Dieu kien demo: `branch_id = 4`, ngay trong khoang checkin/checkout.

Isolation de de xuat hien: `READ COMMITTED`.

### Session A

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT COUNT(*) AS booking_count
FROM booking
WHERE branch_id = 4
  AND checkin_expected_at < '2026-05-27 00:00:00'
  AND checkout_expected_at > '2026-05-26 00:00:00'
  AND status IN ('PENDING', 'CONFIRMED', 'CHECKED_IN');

-- Dung lai o day, chuyen sang Session B.
```

### Session B

Chon mot customer co san, vi du `customer_id = 5`, va insert booking moi.

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

INSERT INTO booking (
    customer_id,
    branch_id,
    checkin_expected_at,
    checkout_expected_at,
    status,
    total_amount,
    special_notes,
    created_at,
    updated_at
) VALUES (
    5,
    4,
    '2026-05-26 09:00:00',
    '2026-05-27 12:00:00',
    'CONFIRMED',
    150000,
    'DEMO_PHANTOM_READ booking inserted during Session A',
    NOW(),
    NOW()
);

COMMIT;
```

### Session A dem lai

```sql
SELECT COUNT(*) AS booking_count
FROM booking
WHERE branch_id = 4
  AND checkin_expected_at < '2026-05-27 00:00:00'
  AND checkout_expected_at > '2026-05-26 00:00:00'
  AND status IN ('PENDING', 'CONFIRMED', 'CHECKED_IN');

COMMIT;
```

Ket qua mong doi: `booking_count` lan 2 lon hon lan 1.

### Laravel minh hoa

```php
DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

DB::transaction(function () {
    $first = Booking::where('branch_id', 4)
        ->where('checkin_expected_at', '<', '2026-05-27 00:00:00')
        ->where('checkout_expected_at', '>', '2026-05-26 00:00:00')
        ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
        ->count();

    // Request/terminal khac insert booking thoa dieu kien va commit.

    $second = Booking::where('branch_id', 4)
        ->where('checkin_expected_at', '<', '2026-05-27 00:00:00')
        ->where('checkout_expected_at', '>', '2026-05-26 00:00:00')
        ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
        ->count();
});
```

### Cach phong tranh

- Dung `REPEATABLE READ` hoac `SERIALIZABLE` khi can bao dam tap ket qua khong thay doi.
- Dung index phu hop tren `booking(branch_id, checkin_expected_at, checkout_expected_at, status)`.
- Khi nghiep vu giu phong, khong chi dem count; nen lock dong phong cu the bang `SELECT ... FOR UPDATE`. Code hien tai trong `BookingRepository::assignRoomAndServicesWithLock()` da dung `lockForUpdate()` khi chon phong.

## 4. Lost Update

### Y tuong demo

Hai session cung doc ton kho mot san pham. Ca hai cung tinh so luong moi dua tren gia tri cu, roi update. Ket qua chi mat mot lan tru, lam ton kho sai.

Bang dung: `branch_inventory`.

Du lieu chon: ton kho chi nhanh 4, product 1.

Cot that trong migration/seeder: `quantity_in_stock`.

Isolation de de xuat hien: thuong gap khi ung dung doc roi update theo gia tri cu ma khong lock/atomic update.

### Kiem tra dong ton kho

```sql
SELECT branch_inventory_id, branch_id, product_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1;
```

Gia su `quantity_in_stock = 8000`.

### Session A

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1;

-- Gia su ung dung tinh: new_quantity = 8000 - 100 = 7900.
-- Dung lai, chuyen sang Session B.
```

### Session B

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1;

-- Session B cung thay 8000 va tinh new_quantity = 8000 - 200 = 7800.
UPDATE branch_inventory
SET quantity_in_stock = 7800,
    last_updated = NOW(),
    updated_at = NOW()
WHERE branch_id = 4 AND product_id = 1;

COMMIT;
```

### Session A cap nhat tre

```sql
UPDATE branch_inventory
SET quantity_in_stock = 7900,
    last_updated = NOW(),
    updated_at = NOW()
WHERE branch_id = 4 AND product_id = 1;

COMMIT;

SELECT quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1;
```

Ket qua sai: ton kho con `7900`. Ket qua dung neu tru 100 va 200 phai la `7700`.

### Cach phong tranh 1: pessimistic lock

SQL:

```sql
START TRANSACTION;

SELECT quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1
FOR UPDATE;

UPDATE branch_inventory
SET quantity_in_stock = quantity_in_stock - 100,
    last_updated = NOW(),
    updated_at = NOW()
WHERE branch_id = 4 AND product_id = 1;

COMMIT;
```

Laravel:

```php
DB::transaction(function () {
    $inventory = BranchInventory::where('branch_id', 4)
        ->where('product_id', 1)
        ->lockForUpdate()
        ->firstOrFail();

    $inventory->update([
        'quantity_in_stock' => $inventory->quantity_in_stock - 100,
        'last_updated' => now(),
    ]);
});
```

### Cach phong tranh 2: optimistic lock

Hien tai migration khong co cot `version`. Neu muon demo optimistic locking that, co the them migration rieng:

```php
Schema::table('branch_inventory', function (Blueprint $table) {
    $table->unsignedInteger('version')->default(1);
});
```

Cap nhat:

```sql
UPDATE branch_inventory
SET quantity_in_stock = 7900,
    version = version + 1,
    last_updated = NOW(),
    updated_at = NOW()
WHERE branch_id = 4
  AND product_id = 1
  AND version = 1;
```

Neu `affected rows = 0`, nghia la co session khac da update truoc; ung dung doc lai va retry.

Khuyen nghi: khong them migration nay neu chi can tai lieu demo, vi se lam doi schema.

### Cach phong tranh 3: atomic update

Day la cach gon nhat cho ton kho:

```sql
UPDATE branch_inventory
SET quantity_in_stock = quantity_in_stock - 100,
    last_updated = NOW(),
    updated_at = NOW()
WHERE branch_id = 4
  AND product_id = 1
  AND quantity_in_stock >= 100;
```

Laravel:

```php
$affected = BranchInventory::where('branch_id', 4)
    ->where('product_id', 1)
    ->where('quantity_in_stock', '>=', 100)
    ->decrement('quantity_in_stock', 100, [
        'last_updated' => now(),
        'updated_at' => now(),
    ]);

if ($affected === 0) {
    throw new RuntimeException('Ton kho khong du.');
}
```

## 5. Deadlock

### Y tuong demo

Hai session lock 2 dong ton kho theo thu tu nguoc nhau:

- Session A lock product 1 roi product 2.
- Session B lock product 2 roi product 1.

MySQL/InnoDB se phat hien deadlock va rollback mot transaction.

Bang dung: `branch_inventory`.

Du lieu chon: branch 4, product 1 va product 2.

### Session A

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT branch_inventory_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1
FOR UPDATE;

-- Dung lai, chuyen sang Session B lock product 2.
```

### Session B

```sql
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
START TRANSACTION;

SELECT branch_inventory_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 2
FOR UPDATE;

-- Dung lai, quay ve Session A.
```

### Session A co gang lock dong Session B dang giu

```sql
SELECT branch_inventory_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 2
FOR UPDATE;

-- Lenh nay se cho Session B.
```

### Session B co gang lock dong Session A dang giu

```sql
SELECT branch_inventory_id, quantity_in_stock
FROM branch_inventory
WHERE branch_id = 4 AND product_id = 1
FOR UPDATE;
```

Ket qua mong doi: mot session gap loi MySQL tuong tu:

```text
ERROR 1213 (40001): Deadlock found when trying to get lock; try restarting transaction
```

Sau do rollback session bi loi:

```sql
ROLLBACK;
```

Session con lai:

```sql
COMMIT;
```

### Laravel minh hoa

Vi du tao deadlock khi hai request update hai product theo thu tu nguoc nhau:

```php
DB::transaction(function () {
    BranchInventory::where('branch_id', 4)
        ->where('product_id', 1)
        ->lockForUpdate()
        ->first();

    sleep(5);

    BranchInventory::where('branch_id', 4)
        ->where('product_id', 2)
        ->lockForUpdate()
        ->first();
});
```

Request khac dung thu tu nguoc:

```php
DB::transaction(function () {
    BranchInventory::where('branch_id', 4)
        ->where('product_id', 2)
        ->lockForUpdate()
        ->first();

    sleep(5);

    BranchInventory::where('branch_id', 4)
        ->where('product_id', 1)
        ->lockForUpdate()
        ->first();
});
```

### Cach phong tranh

- Luon lock du lieu theo cung mot thu tu. Vi du luon sort `product_id ASC` truoc khi lock.
- Giu transaction ngan, khong cho nguoi dung cho trong transaction.
- Dung index phu hop, dac biet voi dieu kien `branch_id`, `product_id`.
- Retry transaction khi gap SQLSTATE `40001` hoac MySQL error `1213`.
- Tranh cap nhat nhieu bang/dong theo thu tu khong nhat quan.

Laravel retry:

```php
DB::transaction(function () use ($items) {
    collect($items)
        ->sortBy('product_id')
        ->each(function ($item) {
            BranchInventory::where('branch_id', $item['branch_id'])
                ->where('product_id', $item['product_id'])
                ->lockForUpdate()
                ->firstOrFail();
        });

    // Update sau khi da lock theo thu tu thong nhat.
}, 3);
```

## 6. Lien he voi code hien tai

Trong `app/Repositories/Eloquent/BookingRepository.php`, luong tao booking da co cac diem bao ve quan trong:

- Lock thu cung bang `Pet::whereIn(...)->lockForUpdate()`.
- Chon phong trong va lock bang `Room::...->lockForUpdate()->first()`.
- Tao `booking`, `booking_room`, `booking_room_pet`, `booking_service_pet`, `orders`, `payments` trong transaction.

Trong `app/Repositories/Eloquent/PaymentRepository.php`, luong thanh toan:

- Lock `booking` va `orders` bang `lockForUpdate()`.
- Lock coupon khi validate bang `validCouponForOrder(..., $lock = true)`.
- Cap nhat `orders`, `payments`, `booking`, `room` trong transaction.

Day la diem co the noi voi hoi dong: he thong khong chi co giao dien dat phong, ma da bat dau ap dung transaction va lock o cac nghiep vu co nguy co tranh chap du lieu.

## 7. Co can route/controller demo rieng khong?

Khong can tao route demo rieng trong Laravel cho hien tai, vi:

- 4 hien tuong co the demo ro hon bang 2 terminal SQL.
- Khong lam anh huong luong booking/payment that.
- Khong can them migration hoac sua schema.
- Neu muon UI bam demo, co the tao sau trong prefix `/demo/concurrency`, nhung nen tach rieng va chi dung du lieu co tien to `DEMO_`.
