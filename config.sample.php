<?php
/**
 * SimpleCMS Configuration
 */

// 1. Session Configuration - LUÔN ĐỂ Ở ĐẦU FILE
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Đổi thành 1 nếu dùng HTTPS

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Database Configuration
define('DB_HOST', '127.0.0.1'); // Dùng IP để ổn định hơn localhost trên Linux
define('DB_NAME', 'simplecms');
define('DB_USER', 'BuiThang');
define('DB_PASS', 'mật_khẩu_thật_của_bạn');
define('DB_CHARSET', 'utf8mb4');

// 3. Site Configuration
define('SITE_URL', 'http://192.168.202.134'); // URL của bạn
define('SITE_NAME', 'SimpleCMS');
define('SITE_DESC', 'Open Source Content Management System');

// 4. Path Configuration
define('ROOT_PATH', dirname(__FILE__));
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('THEMES_PATH', ROOT_PATH . '/themes');
define('PLUGINS_PATH', ROOT_PATH . '/plugins');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// 5. Security & Timezone
define('AUTH_KEY', 'put-your-unique-phrase-here');
define('SECURE_AUTH_KEY', 'put-your-unique-phrase-here');
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 6. Debug Mode & Error Reporting
define('DEBUG_MODE', true);
if(DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
