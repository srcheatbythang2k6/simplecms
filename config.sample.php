<?php
/**
 * SimpleCMS Installation Script
 * Tối ưu hóa: Tự động tạo bảng, bảo mật và chống cài đè.
 */

require_once 'config.php';

// 1. Kiểm tra nếu đã cài đặt rồi thì không cho phép vào lại
if (file_exists('install.lock')) {
    die('Hệ thống đã được cài đặt. Để cài lại, hãy xóa file install.lock trên máy chủ.');
}

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Kết nối database thông qua cấu hình trong config.php
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // 2. Tạo cấu trúc bảng dữ liệu (Users và Posts)
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content TEXT,
            excerpt TEXT,
            featured_image VARCHAR(255),
            status ENUM('published', 'draft') DEFAULT 'published',
            author_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id)
        );";
        
        $pdo->exec($sql);

        // 3. Tạo tài khoản Admin từ form
        $admin_user = $_POST['admin_user'];
        $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
        $admin_email = $_POST['admin_email'];

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$admin_user, $admin_pass, $admin_email]);

        // 4. Tạo file khóa để không cho phép cài lại
        file_put_contents('install.lock', 'Installed on ' . date('Y-m-d H:i:s'));

        $message = "Cài đặt thành công! Hãy xóa file install.php để bảo mật.";
    } catch (PDOException $e) {
        $error = true;
        $message = "Lỗi cài đặt: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cài đặt SimpleCMS</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding-top: 50px; }
        .install-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #2271b1; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="install-box">
        <h2>Cài đặt SimpleCMS</h2>
        <?php if ($message): ?>
            <p class="<?php echo $error ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
            <?php if (!$error): ?>
                <a href="index.php">Đến Trang chủ</a> | <a href="admin/login.php">Vào Quản trị</a>
            <?php endif; ?>
        <?php else: ?>
            <form method="POST">
                <label>Tên đăng nhập Admin:</label>
                <input type="text" name="admin_user" required placeholder="Ví dụ: admin">
                
                <label>Email Admin:</label>
                <input type="email" name="admin_email" required placeholder="admin@example.com">
                
                <label>Mật khẩu Admin:</label>
                <input type="password" name="admin_pass" required placeholder="Nhập mật khẩu mạnh">
                
                <button type="submit">Hoàn tất cài đặt</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
