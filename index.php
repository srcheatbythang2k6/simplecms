<?php
/**
 * SimpleCMS Index
 * File này điều hướng người dùng và hiển thị danh sách bài viết.
 */

// 1. Kiểm tra sự tồn tại của file cấu hình
// Nếu chưa có config.php, ép người dùng quay về trang cài đặt
if (!file_exists('config.php')) {
    header('Location: install.php');
    exit;
}

// 2. Nhúng các file hệ thống (Chỉ gọi mỗi file 1 lần)
require_once 'config.php';
require_once INCLUDES_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/post.class.php';

// 3. Lấy danh sách bài viết
try {
    $post = new Post();
    $posts = $post->getAll('published', 10);
} catch (Exception $e) {
    // Nếu database lỗi (ví dụ chưa cài bảng), cũng cho về trang install
    if (DEBUG_MODE) {
        die("Lỗi kết nối database: " . $e->getMessage());
    }
    header('Location: install.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo SITE_DESC; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; }
        
        header { background: #fff; border-bottom: 1px solid #e1e4e8; position: sticky; top: 0; z-index: 100; }
        .header-container { max-width: 1200px; margin: 0 auto; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .site-title { font-size: 24px; font-weight: 700; color: #2271b1; text-decoration: none; }
        
        nav ul { display: flex; list-style: none; gap: 20px; }
        nav a { color: #3c434a; text-decoration: none; font-weight: 500; font-size: 15px; }
        nav a:hover { color: #2271b1; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; min-height: 70vh; }
        
        .posts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
        .post-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #e1e4e8; }
        .post-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .post-image { width: 100%; height: 180px; background: #eaecf0; object-fit: cover; border-bottom: 1px solid #eee; }
        .post-content { padding: 20px; }
        .post-title { font-size: 20px; margin-bottom: 10px; line-height: 1.4; }
        .post-title a { color: #1d2327; text-decoration: none; }
        .post-title a:hover { color: #2271b1; }
        
        .post-meta { color: #646970; font-size: 13px; margin-bottom: 12px; }
        .post-excerpt { color: #50575e; font-size: 14px; line-height: 1.6; margin-bottom: 15px; }
        .read-more { color: #2271b1; text-decoration: none; font-weight: 600; font-size: 14px; }
        
        footer { background: #1d2327; color: #d1d1d1; padding: 40px 20px; margin-top: 60px; text-align: center; font-size: 14px; }
        
        .empty-state { text-align: center; padding: 80px 20px; background: white; border-radius: 12px; border: 1px dashed #c3c4c7; }
        .empty-state h2 { color: #3c434a; margin-bottom: 15px; }
        .btn-primary { display: inline-block; background: #2271b1; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin-top: 10px; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="/" class="site-title"><?php echo SITE_NAME; ?></a>
            <nav>
                <ul>
                    <li><a href="/">Trang chủ</a></li>
                    <?php if(function_exists('is_logged_in') && is_logged_in()): ?>
                        <li><a href="/admin">Dashboard</a></li>
                        <li><a href="/logout.php">Thoát</a></li>
                    <?php else: ?>
                        <li><a href="/login.php">Đăng nhập</a></li>
                        <li><a href="/admin">Quản trị</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <?php if(empty($posts)): ?>
            <div class="empty-state">
                <h2>Chào mừng đến với <?php echo SITE_NAME; ?></h2>
                <p>Hệ thống đã sẵn sàng nhưng chưa có bài viết nào.</p>
                <a href="/admin" class="btn-primary">Tạo bài viết đầu tiên</a>
            </div>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach($posts as $p): ?>
                <article class="post-card">
                    <?php if(!empty($p['featured_image'])): ?>
                        <img src="<?php echo SITE_URL . $p['featured_image']; ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" class="post-image">
                    <?php else: ?>
                        <div class="post-image" style="display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <h2 class="post-title">
                            <a href="/post.php?slug=<?php echo $p['slug']; ?>"><?php echo htmlspecialchars($p['title']); ?></a>
                        </h2>
                        <div class="post-meta">
                            <?php echo date('d/m/Y', strtotime($p['created_at'])); ?>
                        </div>
                        <div class="post-excerpt">
                            <?php 
                                $text = !empty($p['excerpt']) ? $p['excerpt'] : $p['content'];
                                echo mb_substr(strip_tags($text), 0, 120) . '...'; 
                            ?>
                        </div>
                        <a href="/post.php?slug=<?php echo $p['slug']; ?>" class="read-more">Đọc tiếp →</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Powered by SimpleCMS.</p>
    </footer>
</body>
</html>
