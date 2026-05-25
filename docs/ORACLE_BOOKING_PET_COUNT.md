# Oracle SQL - Dem so luong thu cung trong moi booking

Muc tieu: hien thi danh sach booking cua mot khach hang va dem so luong thu cung trong tung booking.

Bang lien quan:

- `booking`
- `customer`
- `users`
- `branch`
- `booking_room`
- `booking_room_pet`
- `pet`

## SQL Oracle

```sql
SELECT
    b.booking_id,
    u.email,
    c.full_name,
    brn.branch_name,
    b.checkin_expected_at,
    b.checkout_expected_at,
    b.status,
    b.total_amount,
    COUNT(DISTINCT p.pet_id) AS pet_count
FROM booking b
JOIN customer c
    ON c.customer_id = b.customer_id
JOIN users u
    ON u.id = c.user_id
JOIN branch brn
    ON brn.branch_id = b.branch_id
LEFT JOIN booking_room br
    ON br.booking_id = b.booking_id
LEFT JOIN booking_room_pet brp
    ON brp.booking_room_id = br.booking_room_id
LEFT JOIN pet p
    ON p.pet_id = brp.pet_id
WHERE u.email = 'customer.small@pethotel.test'
GROUP BY
    b.booking_id,
    u.email,
    c.full_name,
    brn.branch_name,
    b.checkin_expected_at,
    b.checkout_expected_at,
    b.status,
    b.total_amount
ORDER BY b.booking_id;
```

## Giai thich

`booking` khong noi truc tiep den `pet`. Mot booking duoc gan phong qua `booking_room`, sau do thu cung duoc gan vao phong booking qua `booking_room_pet`.

Vi vay duong join dung la:

```text
booking -> booking_room -> booking_room_pet -> pet
```

Dung:

```sql
COUNT(DISTINCT p.pet_id) AS pet_count
```

de dem so thu cung duy nhat trong moi booking, tranh bi dem lap neu sau nay mot booking co nhieu dong lien quan.

Neu chi muon xem them ten cac thu cung trong booking, co the dung `LISTAGG`:

```sql
SELECT
    b.booking_id,
    u.email,
    c.full_name,
    brn.branch_name,
    b.checkin_expected_at,
    b.checkout_expected_at,
    b.status,
    b.total_amount,
    COUNT(DISTINCT p.pet_id) AS pet_count,
    LISTAGG(DISTINCT p.pet_name, ', ') WITHIN GROUP (ORDER BY p.pet_name) AS pet_names
FROM booking b
JOIN customer c
    ON c.customer_id = b.customer_id
JOIN users u
    ON u.id = c.user_id
JOIN branch brn
    ON brn.branch_id = b.branch_id
LEFT JOIN booking_room br
    ON br.booking_id = b.booking_id
LEFT JOIN booking_room_pet brp
    ON brp.booking_room_id = br.booking_room_id
LEFT JOIN pet p
    ON p.pet_id = brp.pet_id
WHERE u.email = 'customer.small@pethotel.test'
GROUP BY
    b.booking_id,
    u.email,
    c.full_name,
    brn.branch_name,
    b.checkin_expected_at,
    b.checkout_expected_at,
    b.status,
    b.total_amount
ORDER BY b.booking_id;
```
