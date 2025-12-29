<?php
// Kiểm tra file khóa, nếu chưa có thì bắt đi cài đặt
if (!file_exists('install.lock')) {
    header('Location: install.php');
    exit;
}

require_once 'config.php';
require_once INCLUDES_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/post.class.php';

$post = new Post();
$posts = $post->getAll('published', 10);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo SITE_NAME; ?></title>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; margin: 0; text-align: center; }
        .header { background: #fff; padding: 30px; border-bottom: 1px solid #ddd; }
        .content { max-width: 600px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo SITE_NAME; ?></h1>
    </div>
    <div class="content">
        <?php if(empty($posts)): ?>
            <p>Chào mừng! Bạn đã cài đặt thành công. <br><a href="admin/">Đăng nhập Admin</a> để viết bài đầu tiên.</p>
        <?php else: ?>
            <?php endif; ?>
    </div>
</body>
</html>
