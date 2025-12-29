<?php
// 1. Kiểm tra cài đặt trước khi làm bất cứ việc gì khác
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
        body { font-family: sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; }
        .header { background: #fff; padding: 20px; border-bottom: 1px solid #ddd; text-align: center; }
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .post-card { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .empty { text-align: center; padding: 50px; background: #fff; border-radius: 8px; }
        a { color: #2271b1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo SITE_NAME; ?></h1>
        <p><?php echo SITE_DESC; ?></p>
    </div>
    <div class="container">
        <?php if(empty($posts)): ?>
            <div class="empty">
                <h2>Chưa có bài viết nào.</h2>
                <p><a href="admin/">Đăng nhập quản trị</a> để viết bài.</p>
            </div>
        <?php else: ?>
            <?php foreach($posts as $p): ?>
                <div class="post-card">
                    <h2><?php echo htmlspecialchars($p['title']); ?></h2>
                    <p><?php echo mb_substr(strip_tags($p['content']), 0, 150); ?>...</p>
                    <a href="post.php?id=<?php echo $p['id']; ?>">Đọc tiếp →</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
