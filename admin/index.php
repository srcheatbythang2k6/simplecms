<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Xin chào, <?= $_SESSION['user'] ?></p>
    <a href="/post.php">➕ New Post</a>
</body>
</html>
