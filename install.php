<?php
/**
 * SimpleCMS Installer
 * Version: 2.0 - Fixed with comprehensive error handling
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Start session
session_start();

// Function to display error messages
function show_error($title, $message, $details = '') {
    echo '<!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi cài đặt - SimpleCMS</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: #f5f5f5; 
                padding: 40px 20px;
            }
            .container { max-width: 800px; margin: 0 auto; }
            .error-box {
                background: #fff;
                border-left: 4px solid #dc3545;
                border-radius: 8px;
                padding: 30px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 { color: #dc3545; margin-bottom: 20px; font-size: 24px; }
            .message { color: #333; line-height: 1.6; margin-bottom: 20px; }
            .details {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 15px;
                margin-top: 20px;
                font-family: monospace;
                font-size: 13px;
                overflow-x: auto;
            }
            .checklist { 
                background: #fff3cd;
                border: 1px solid #ffc107;
                border-radius: 4px;
                padding: 20px;
                margin-top: 20px;
            }
            .checklist h3 { color: #856404; margin-bottom: 15px; }
            .checklist ul { margin-left: 20px; }
            .checklist li { margin: 8px 0; color: #333; }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 20px;
            }
            .btn:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-box">
                <h1>🚫 ' . htmlspecialchars($title) . '</h1>
                <div class="message">' . $message . '</div>';
    
    if ($details) {
        echo '<div class="details"><strong>Chi tiết lỗi:</strong><br>' . htmlspecialchars($details) . '</div>';
    }
    
    echo '<div class="checklist">
                    <h3>✅ Các bước kiểm tra:</h3>
                    <ul>
                        <li>Đảm bảo PHP version ≥ 7.4</li>
                        <li>Đảm bảo MySQL/MariaDB đang chạy</li>
                        <li>Kiểm tra thông tin database trong config.php</li>
                        <li>Đảm bảo thư mục có quyền ghi (chmod 777 uploads)</li>
                        <li>Xem log: <code>sudo tail -f /var/log/apache2/error.log</code></li>
                    </ul>
                </div>
                <a href="install.php" class="btn">🔄 Thử lại</a>
            </div>
        </div>
    </body>
    </html>';
    exit;
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    show_error(
        'PHP Version không đủ',
        'SimpleCMS yêu cầu PHP 7.4 trở lên. Phiên bản hiện tại: ' . PHP_VERSION
    );
}

// Check required extensions
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    show_error(
        'Thiếu PHP Extensions',
        'Các extension sau chưa được cài đặt: ' . implode(', ', $missing_extensions),
        'Chạy: sudo apt-get install php-' . implode(' php-', $missing_extensions)
    );
}

// Check if already installed
$config_file = __DIR__ . '/config.php';
if (file_exists($config_file)) {
    // Check if database is already set up
    try {
        require_once $config_file;
        if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Check if users table exists
            $tables = $pdo->query("SHOW TABLES LIKE 'scms_users'")->fetchAll();
            if (!empty($tables)) {
                header('Location: index.php');
                exit;
            }
        }
    } catch (Exception $e) {
        // Continue with installation
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        $site_name = trim($_POST['site_name'] ?? '');
        $admin_username = trim($_POST['admin_username'] ?? '');
        $admin_email = trim($_POST['admin_email'] ?? '');
        $admin_password = $_POST['admin_password'] ?? '';
        $db_host = trim($_POST['db_host'] ?? 'localhost');
        $db_name = trim($_POST['db_name'] ?? '');
        $db_user = trim($_POST['db_user'] ?? '');
        $db_pass = $_POST['db_pass'] ?? '';
        
        // Validation
        if (empty($site_name) || empty($admin_username) || empty($admin_email) || empty($admin_password)) {
            throw new Exception('Vui lòng điền đầy đủ thông tin!');
        }
        
        if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email không hợp lệ!');
        }
        
        if (strlen($admin_password) < 6) {
            throw new Exception('Mật khẩu phải có ít nhất 6 ký tự!');
        }
        
        // Test database connection
        try {
            $pdo = new PDO(
                "mysql:host=$db_host;charset=utf8mb4",
                $db_user,
                $db_pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new Exception('Không thể kết nối MySQL: ' . $e->getMessage());
        }
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$db_name`");
        
        // Create tables
        $sql_tables = "
        CREATE TABLE IF NOT EXISTS `scms_users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL,
            `email` varchar(100) NOT NULL,
            `password` varchar(255) NOT NULL,
            `role` enum('admin','editor','author') DEFAULT 'author',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `username` (`username`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
        CREATE TABLE IF NOT EXISTS `scms_posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `content` longtext,
            `excerpt` text,
            `author_id` int(11) NOT NULL,
            `category_id` int(11) DEFAULT NULL,
            `featured_image` varchar(255) DEFAULT NULL,
            `status` enum('published','draft','pending') DEFAULT 'draft',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`),
            KEY `author_id` (`author_id`),
            KEY `category_id` (`category_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
        CREATE TABLE IF NOT EXISTS `scms_categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `slug` varchar(100) NOT NULL,
            `description` text,
            `parent_id` int(11) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
        CREATE TABLE IF NOT EXISTS `scms_options` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `option_name` varchar(100) NOT NULL,
            `option_value` longtext,
            PRIMARY KEY (`id`),
            UNIQUE KEY `option_name` (`option_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        $pdo->exec($sql_tables);
        
        // Insert admin user
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO scms_users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$admin_username, $admin_email, $hashed_password]);
        
        // Insert default options
        $site_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $site_url = rtrim($site_url, '/install.php');
        
        $options = [
            ['site_name', $site_name],
            ['site_url', $site_url],
            ['site_description', 'A Simple Content Management System'],
            ['admin_email', $admin_email]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO scms_options (option_name, option_value) VALUES (?, ?)");
        foreach ($options as $option) {
            $stmt->execute($option);
        }
        
        // Create config.php
        $config_content = "<?php
// Database Configuration
define('DB_HOST', '$db_host');
define('DB_NAME', '$db_name');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
define('DB_PREFIX', 'scms_');
define('DB_CHARSET', 'utf8mb4');

// Path Configuration
define('INCLUDES_PATH', __DIR__ . '/includes');
define('ADMIN_PATH', __DIR__ . '/admin');
define('THEME_PATH', __DIR__ . '/themes');
define('PLUGIN_PATH', __DIR__ . '/plugins');
define('UPLOAD_PATH', __DIR__ . '/uploads');

// Site Configuration
define('SITE_URL', '$site_url');
define('SITE_NAME', '$site_name');

// Security
define('SECURITY_SALT', '" . bin2hex(random_bytes(32)) . "');

// Debug Mode
define('DEBUG_MODE', false);
?>";
        
        if (file_put_contents($config_file, $config_content) === false) {
            throw new Exception('Không thể tạo file config.php. Kiểm tra quyền ghi!');
        }
        
        // Success - redirect
        $_SESSION['install_success'] = true;
        header('Location: install.php?step=complete');
        exit;
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Check if installation is complete
if (isset($_GET['step']) && $_GET['step'] === 'complete' && isset($_SESSION['install_success'])) {
    unset($_SESSION['install_success']);
    echo '<!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cài đặt thành công!</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .success-box {
                background: white;
                border-radius: 12px;
                padding: 60px 40px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                text-align: center;
                max-width: 500px;
            }
            .checkmark {
                font-size: 80px;
                color: #28a745;
                margin-bottom: 20px;
            }
            h1 { color: #333; margin-bottom: 20px; }
            p { color: #666; line-height: 1.6; margin-bottom: 30px; }
            .btn {
                display: inline-block;
                padding: 15px 40px;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s;
            }
            .btn:hover {
                background: #5568d3;
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>
    <body>
        <div class="success-box">
            <div class="checkmark">✅</div>
            <h1>Cài đặt thành công!</h1>
            <p>SimpleCMS đã được cài đặt thành công.<br>Bạn có thể đăng nhập vào admin panel để bắt đầu.</p>
            <a href="admin/" class="btn">Đi tới Admin Panel</a>
        </div>
    </body>
    </html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt SimpleCMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .install-box {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .section-title {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .error {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
        }
        
        .help-text {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }
        
        .system-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .system-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-box">
            <h1>📦 SimpleCMS Installation</h1>
            <div class="subtitle">Chào mừng bạn đến với SimpleCMS! Vui lòng điền thông tin để hoàn tất cài đặt.</div>
            
            <div class="system-info">
                <strong>Thông tin hệ thống:</strong><br>
                PHP Version: <?php echo PHP_VERSION; ?><br>
                Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>
            </div>
            
            <?php if (isset($error_message)): ?>
                <div class="error">
                    <strong>⚠️ Lỗi:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="section-title">🌐 Thông tin Website</div>
                
                <div class="form-group">
                    <label>Tên Website</label>
                    <input type="text" name="site_name" value="<?php echo htmlspecialchars($_POST['site_name'] ?? 'SimpleCMS'); ?>" required>
                    <div class="help-text">Tên hiển thị của website</div>
                </div>
                
                <div class="section-title">👤 Thông tin Admin</div>
                
                <div class="form-group">
                    <label>Tên đăng nhập Admin</label>
                    <input type="text" name="admin_username" value="<?php echo htmlspecialchars($_POST['admin_username'] ?? 'admin'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email Admin</label>
                    <input type="email" name="admin_email" value="<?php echo htmlspecialchars($_POST['admin_email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Mật khẩu Admin</label>
                    <input type="password" name="admin_password" required>
                    <div class="help-text">Tối thiểu 6 ký tự</div>
                </div>
                
                <div class="section-title">🗄️ Thông tin Database</div>
                
                <div class="form-group">
                    <label>Database Host</label>
                    <input type="text" name="db_host" value="<?php echo htmlspecialchars($_POST['db_host'] ?? 'localhost'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Tên Database</label>
                    <input type="text" name="db_name" value="<?php echo htmlspecialchars($_POST['db_name'] ?? 'simplecms'); ?>" required>
                    <div class="help-text">Database sẽ được tạo tự động nếu chưa tồn tại</div>
                </div>
                
                <div class="form-group">
                    <label>Username Database</label>
                    <input type="text" name="db_user" value="<?php echo htmlspecialchars($_POST['db_user'] ?? 'root'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Password Database</label>
                    <input type="password" name="db_pass" value="<?php echo htmlspecialchars($_POST['db_pass'] ?? ''); ?>">
                    <div class="help-text">Để trống nếu không có password</div>
                </div>
                
                <button type="submit">🚀 Cài đặt SimpleCMS</button>
            </form>
        </div>
    </div>
</body>
</html>
