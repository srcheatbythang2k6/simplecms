<?php
require_once 'config.php';

// Kiểm tra cài đặt
if (file_exists('install.lock')) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // Tạo bảng
        $sql = "CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
                CREATE TABLE IF NOT EXISTS posts (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, content TEXT, excerpt TEXT, featured_image VARCHAR(255), status ENUM('published', 'draft') DEFAULT 'published', author_id INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (author_id) REFERENCES users(id));";
        $pdo->exec($sql);

        // Tạo tài khoản Admin
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['user'], password_hash($_POST['pass'], PASSWORD_DEFAULT), $_POST['email']]);

        file_put_contents('install.lock', date('Y-m-d H:i:s'));
        $message = "Cài đặt thành công!";
    } catch (PDOException $e) {
        $error = true;
        $message = "Lỗi SQL: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cài đặt SimpleCMS</title>
    <style>
        body { background: #5d5fef; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif; }
        .box { background: #fff; padding: 40px; border-radius: 12px; width: 350px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #5d5fef; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px; }
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
                <p style="text-align:center;"><a href="index.php" style="color: #5d5fef;">Vào trang chủ</a></p>
            <?php endif; ?>
        <?php else: ?>
            <h2>Cài đặt Admin</h2>
            <form method="POST">
                <input type="text" name="user" placeholder="Tên đăng nhập" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="pass" placeholder="Mật khẩu" required>
                <button type="submit">Hoàn tất cài đặt</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
