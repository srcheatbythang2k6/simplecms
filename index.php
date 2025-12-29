<?php
// Kiểm tra cài đặt
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
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo SITE_NAME; ?></title>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; text-align: center; padding-top: 50px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2271b1; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo SITE_NAME; ?></h1>
        <p><?php echo SITE_DESC; ?></p>
        <hr>
        <?php if(empty($posts)): ?>
            <p>Hệ thống đã sẵn sàng! <br><a href="admin/">Đăng nhập Admin</a> để bắt đầu viết bài.</p>
        <?php endif; ?>
    </div>
</body>
</html>
