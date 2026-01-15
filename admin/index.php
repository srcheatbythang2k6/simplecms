<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Xin chào: <b><?= $_SESSION['user'] ?></b></p>

    <a href="/post.php">➕ Thêm bài viết</a><br><br>
    <a href="/logout.php">🚪 Đăng xuất</a>
</body>
</html>
