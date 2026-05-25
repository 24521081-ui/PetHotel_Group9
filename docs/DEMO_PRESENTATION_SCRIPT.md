# Demo Presentation Script - Pet Hotel Web

## 1. Loi mo dau

Chao thay co va hoi dong. Hom nay em demo website quan ly khach san thu cung Pet Hotel. He thong ho tro khach hang tim chi nhanh, xem phong, quan ly thu cung, dat phong, chon dich vu kem theo va thanh toan. O phia van hanh, he thong co khu vuc cho nhan vien/quan ly chi nhanh va admin/CEO theo doi booking, dich vu, ton kho, doanh thu va chi nhanh.

Du lieu demo duoc seed san trong database, gom chi nhanh, loai phong, phong, khach hang, nhan vien, thu cung, dich vu, san pham, ton kho, booking, order va payment.

## 2. Gioi thieu vai tro nguoi dung

He thong co 4 nhom vai tro chinh.

Khach vang lai co the xem trang chu, chi nhanh, dich vu va loai phong truoc khi dang ky.

Khach hang da dang nhap co the quan ly ho so, them/sua thu cung, tao booking, chon chi nhanh, chon loai phong, chon thu cung, chon dich vu va thanh toan.

Nhan vien hoac le tan dang dung chung khu vuc manager trong code hien tai. Phan nay dung de xem tong quan van hanh, dich vu, ton kho va bao cao. Mot so thao tac nghiep vu nhu check-in/check-out da co logic dong bo trang thai phong trong model, nhung chua co UI rieng.

Admin/CEO co khu vuc rieng de xem tong quan toan he thong, chi nhanh, dich vu, vendor/san pham va tai chinh.

## 3. Dan tu luong khach hang sang van hanh

Dau tien em se demo phia khach hang.

Em vao trang chu, sau do vao danh sach chi nhanh. O day du lieu duoc lay tu bang `branch`, co the loc theo khu vuc hoac tim kiem. Khi vao chi tiet mot chi nhanh, he thong hien cac loai phong dang co tai chi nhanh do.

Tiep theo em xem phong theo loai: normal, vip va luxury, cho dog hoac cat. Khi nhap ngay nhan va ngay tra phong, he thong kiem tra cac booking dang giu phong hoac da xac nhan de tinh phong con trong.

Sau do em dang nhap bang tai khoan khach hang demo. Khach hang co the xem ho so, them hoac sua thu cung. Khi dat phong, khach chon chi nhanh, loai phong, ngay luu tru, thu cung va dich vu kem theo. Khi submit, backend tao booking trong transaction, gan phong, gan thu cung, tao order va payment pending.

Neu khach chon thanh toan, he thong chuyen sang man hinh payment. Tai day co the nhap ma giam gia, chon phuong thuc thanh toan va xac nhan. Khi thanh toan thanh cong, order chuyen sang completed, payment success, booking confirmed va room duoc cap nhat trang thai.

Sau luong khach hang, em chuyen sang tai khoan manager de cho thay phia van hanh theo doi booking, order, dich vu, ton kho va bao cao qua khu vuc manager/API. Cuoi cung em dang nhap admin/CEO de xem goc nhin toan he thong.

## 4. Vi sao can xu ly truy xuat dong thoi

Trong he thong dat phong va thanh toan, nhieu nguoi co the thao tac cung luc. Vi du hai khach cung dat phong cuoi cung cua mot chi nhanh, hoac hai nhan vien cung tru ton kho cho mot dich vu. Neu khong xu ly transaction va lock, du lieu co the sai: mot phong bi dat hai lan, ton kho bi tru thieu, hoa don bi cap nhat de len nhau.

Vi vay phan backend can quan tam den transaction, isolation level va lock. Trong code hien tai, luong tao booking va thanh toan da dung `DB::transaction()` va `lockForUpdate()` o cac diem quan trong nhu pet, room, booking, order va coupon.

## 5. Giai thich ngan 4 van de concurrency

### Non-repeatable read

Non-repeatable read la khi trong cung mot transaction, Session A doc mot dong lan dau, Session B cap nhat dong do va commit, sau do Session A doc lai cung dong va thay gia tri khac.

Trong he thong nay co the demo bang `booking.status`. Session A doc booking dang `CONFIRMED`, Session B cap nhat thanh `CHECKED_IN`, Session A doc lai thay status da doi. Hien tuong nay de xuat hien o isolation `READ COMMITTED`. Cach tranh la dung `REPEATABLE READ` hoac `SELECT ... FOR UPDATE` khi can dua ra quyet dinh nghiep vu tu gia tri da doc.

### Phantom read

Phantom read la khi Session A query mot tap dong theo dieu kien, Session B insert them dong moi thoa dieu kien va commit, Session A query lai thi thay co them dong moi.

Trong he thong nay co the demo bang danh sach booking cua mot chi nhanh trong ngay 2026-05-26. Session A dem booking, Session B insert booking moi cung ngay va commit, Session A dem lai thay so luong tang. Cach tranh la dung `REPEATABLE READ`/`SERIALIZABLE`, index phu hop, hoac trong nghiep vu dat phong thi lock phong cu the thay vi chi dem booking.

### Lost Update

Lost Update la khi hai session cung doc mot gia tri cu, cung tinh gia tri moi, sau do update de len nhau lam mat mot cap nhat.

Trong he thong nay co the demo bang `branch_inventory.quantity_in_stock`. Session A doc ton kho 8000 va tinh tru 100. Session B cung doc 8000 va tru 200, commit thanh 7800. Session A commit tre thanh 7900, ket qua dung phai la 7700 nhung bi sai. Cach tranh la dung `SELECT ... FOR UPDATE`, optimistic locking bang cot `version`, hoac atomic update `quantity_in_stock = quantity_in_stock - x WHERE quantity_in_stock >= x`.

### Deadlock

Deadlock la khi hai transaction giu lock cua nhau va khong transaction nao tiep tuc duoc. Vi du Session A lock ton kho product 1 roi doi product 2, Session B lock product 2 roi doi product 1. MySQL se phat hien deadlock va rollback mot transaction.

Cach tranh la luon lock du lieu theo cung mot thu tu, giu transaction ngan, dung index phu hop va retry transaction khi gap loi deadlock.

## 6. Ket luan

Qua demo, he thong Pet Hotel khong chi co giao dien cho khach hang dat phong va thanh toan, ma con co cau truc du lieu day du cho chi nhanh, phong, thu cung, dich vu, san pham, ton kho, order va payment.

Diem quan trong la cac nghiep vu co rui ro tranh chap du lieu nhu dat phong va thanh toan da duoc xu ly bang transaction va lock. Dieu nay giup he thong huong toi tinh dung dan du lieu khi nhieu nguoi dung thao tac cung luc.
