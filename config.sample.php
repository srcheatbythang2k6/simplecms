<?php
/**
 * SimpleCMS Configuration
 * File đã được tối ưu: Sắp xếp Session lên đầu và xóa trùng lặp code.
 */

// 1. Cấu hình Session (Bắt buộc để ở đầu file để tránh lỗi Warning)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Đặt thành 1 nếu bạn sử dụng HTTPS

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cấu hình Database
// Lưu ý: Dùng '127.0.0.1' thay cho 'localhost' để ổn định hơn trên Ubuntu
define('DB_HOST', '127.0.0.1'); 
define('DB_NAME', 'simplecms');
define('DB_USER', 'BuiThang');
define('DB_PASS', 'mật_khẩu_thật_của_bạn'); 
define('DB_CHARSET', 'utf8mb4');

// 3. Cấu hình Site
define('SITE_URL', 'http://192.168.202.134'); // Địa chỉ IP máy chủ của bạn
define('SITE_NAME', 'SimpleCMS');
define('SITE_DESC', 'Open Source Content Management System');

// 4. Cấu hình đường dẫn (Path Configuration)
define('ROOT_PATH', dirname(__FILE__));
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('THEMES_PATH', ROOT_PATH . '/themes');
define('PLUGINS_PATH', ROOT_PATH . '/plugins');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// 5. Bảo mật và Thời gian
// Bạn nên thay đổi các chuỗi AUTH_KEY bên dưới để bảo mật hơn
define('AUTH_KEY', 'phim-bam-bi-mat-cua-ban-o-day');
define('SECURE_AUTH_KEY', 'phim-bam-bao-mat-hon-nua');
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 6. Chế độ Debug và Báo lỗi
define('DEBUG_MODE', true); // Chuyển thành false khi chạy thực tế (production)

if(DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
