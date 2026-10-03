# Web bán hàng PHP MVC - Bài 1 đến Bài 3

Project hoàn chỉnh theo nội dung thực hành COS340:

- Bài 1: cấu trúc MVC, router, quản lý sản phẩm và giao diện Bootstrap.
- Bài 2: MySQL/PDO, CRUD sản phẩm, CRUD danh mục, upload và hiển thị ảnh.
- Bài 3: giỏ hàng bằng session, cập nhật/xóa giỏ hàng, checkout, lưu đơn hàng và chi tiết đơn hàng bằng transaction.

## Yêu cầu

- PHP 8.1 trở lên, extension `pdo_mysql` và `fileinfo`.
- MySQL 5.7 trở lên hoặc MariaDB tương thích.
- Apache bật `mod_rewrite` và cho phép `.htaccess` (`AllowOverride All`).

## Cài đặt bằng Laragon

1. Giải nén thư mục `webbanhang` vào `C:\laragon\www\webbanhang`.
2. Khởi động Apache và MySQL trong Laragon.
3. Mở HeidiSQL hoặc phpMyAdmin và chạy file `database/my_store.sql`.
4. Nếu tài khoản MySQL không phải `root`/mật khẩu rỗng, sửa giá trị mặc định trong `app/config/database.php` hoặc cấu hình biến môi trường `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
5. Truy cập `http://localhost/webbanhang/`.

Thư mục `uploads` phải có quyền ghi để sử dụng chức năng tải ảnh.

## Luồng kiểm tra

1. Vào Danh mục, thử thêm/sửa/xóa danh mục.
2. Vào Sản phẩm, thêm sản phẩm có ảnh; thử xem chi tiết, sửa và xóa.
3. Thêm sản phẩm vào giỏ, thay đổi số lượng hoặc xóa khỏi giỏ.
4. Checkout, nhập thông tin nhận hàng và xác nhận.
5. Kiểm tra dữ liệu trong các bảng `orders` và `order_details`.

