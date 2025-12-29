<?php
/**
 * SimpleCMS Configuration
 */

// 1. Cấu hình Session (Phải ở đầu tiên để hết lỗi Warning)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cấu hình Database
define('DB_HOST', '127.0.0.1'); // Dùng IP để ổn định hơn localhost
define('DB_NAME', 'simplecms');
define('DB_USER', 'BuiThang');
define('DB_PASS', 'mật_khẩu_mysql_của_bạn'); // Thay mật khẩu thật của bạn vào đây
define('DB_CHARSET', 'utf8mb4');

// 3. Cấu hình Website
define('SITE_URL', 'http://192.168.202.134');
define('SITE_NAME', 'SimpleCMS');
define('SITE_DESC', 'Hệ quản trị nội dung mã nguồn mở');

// 4. Cấu hình đường dẫn
define('ROOT_PATH', dirname(__FILE__));
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('THEMES_PATH', ROOT_PATH . '/themes');
define('PLUGINS_PATH', ROOT_PATH . '/plugins');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// 5. Bảo mật & Debug
define('AUTH_KEY', 'phim-bam-bi-mat-cua-ban');
date_default_timezone_set('Asia/Ho_Chi_Minh');
define('DEBUG_MODE', true);

if(DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
