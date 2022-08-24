## Setup

 1. Tạo mới file **.env**, copy nội dung **.env.example** sang và thay đổi giá trị ``DB_DATABASE`` thành database của mình
 2. Chạy ``composer install`` để cài đặt các package **composer**
 3. Chạy ``php artisan key:generate`` để sinh key cho ứng dụng
 4. Chạy ``php artisan migrate`` để tạo các bảng trong database
 5. Chạy ``php artisan jwt:secret`` để tạo key cho **jwt**
 6. Chay ``php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"``
 7. Chạy ``php artisan migrate`` để tạo các bảng trong database cho permission
 8. Cài đặt tool kiểm tra coding convension
  > Download file pre-commit từ đường dẫn: ``https://redmine.digidinos.com/attachments/18257/pre-commit``
  > Copy file pre-commit vào đường dẫn: ``.git/hooks``
  > Note: Nếu là linux cần phân quyền để có thể chạy được file này ``chmod +775 .git/hooks/pre-commit``

## Update
 1. Checkout branch ``develop`` và pull code mới nhất về
 2. Chạy ``composer dump-autoload`` để autoload các class mới
 3. Chạy ``php artisan jwt:secret`` để tạo key cho **jwt**

## Run
Chạy ``php artisan serve`` để khởi động serve

## Debug
  1. Thông báo lỗi: ``Unable to flush cache.`` (Laravel Permission)
  > Giải pháp: Chạy ``sudo php artisan cache:forget spatie.permission.cache && sudo php artisan cache:clear``
