<?php
/**
 * SimpleCMS Installation Script - Clean Version
 */

// 1. Phải nạp file config để lấy thông số database
if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    die("Lỗi: Không tìm thấy file config.php. Hãy tạo file config.php trước.");
}

// 2. Kiểm tra nếu đã có file khóa thì không cho cài đè
if (file_exists('install.lock')) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Kết nối PDO với thông số từ config.php
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // 3. Chạy lệnh tạo bảng (Schema)
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

        // 4. Mã hóa mật khẩu và tạo tài khoản Admin
        $admin_user = $_POST['admin_user'];
        $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
        $admin_email = $_POST['admin_email'];

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$admin_user, $admin_pass, $admin_email]);

        // 5. Khóa bộ cài đặt
        file_put_contents('install.lock', date('Y-m-d H:i:s'));
        $message = "Cài đặt thành công!";
    } catch (PDOException $e) {
        $error = true;
        $message = "Lỗi SQL: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cài đặt SimpleCMS</title>
    <style>
        body { font-family: sans-serif; background: #5d5fef; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: #fff; padding: 40px; border-radius: 12px; shadow: 0 4px 20px rgba(0,0,0,0.1); width: 350px; }
        h2 { margin-bottom: 20px; text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #5d5fef; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .msg { padding: 10px; border-radius: 4px; margin-bottom: 10px; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="box">
        <?php if ($message): ?>
            <div class="msg <?php echo $error ? 'error' : 'success'; ?>"><?php echo $message; ?></div>
            <?php if (!$error): ?>
                <p style="text-align:center;"><a href="index.php">Vào trang chủ</a></p>
            <?php endif; ?>
        <?php else: ?>
            <h2>Cài đặt Admin</h2>
            <form method="POST">
                <input type="text" name="admin_user" placeholder="Tên đăng nhập" required>
                <input type="email" name="admin_email" placeholder="Email" required>
                <input type="password" name="admin_pass" placeholder="Mật khẩu" required>
                <button type="submit">Hoàn tất cài đặt</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
