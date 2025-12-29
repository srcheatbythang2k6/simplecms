<?php
// Cấu hình Session đặt lên đầu
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sau đó mới đến cấu hình Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'simplecms');
define('DB_USER', 'BuiThang');
define('DB_PASS', 'mật_khẩu_của_bạn');

// ... (giữ nguyên phần Path Configuration bên dưới)
// Path Configuration
define('ROOT_PATH', dirname(__FILE__));
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('THEMES_PATH', ROOT_PATH . '/themes');
define('PLUGINS_PATH', ROOT_PATH . '/plugins');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Security Keys - Generate new ones at https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY', 'put-your-unique-phrase-here');
define('SECURE_AUTH_KEY', 'put-your-unique-phrase-here');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Debug Mode (set to false in production)
define('DEBUG_MODE', true);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Error Reporting
if(DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
