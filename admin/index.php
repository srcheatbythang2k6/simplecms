<?php
require_once '../config.php';
require_once '../includes/functions.php';
check_login();

$db = db_connect();

// Xử lý thêm bài viết mới
if (isset($_POST['add_post'])) {
    $title = $db->real_escape_string($_POST['title']);
    $content = $db->real_escape_string($_POST['content']);
    $db->query("INSERT INTO posts (title, content) VALUES ('$title', '$content')");
}

$posts = get_posts();
?>
<h1>Trang Quản Trị CMS</h1>
<form method="POST">
    <input type="text" name="title" placeholder="Tiêu đề bài viết" required><br>
    <textarea name="content" placeholder="Nội dung" required></textarea><br>
    <button type="submit" name="add_post">Đăng bài</button>
</form>

<hr>
<h3>Danh sách bài viết đã đăng:</h3>
<ul>
    <?php foreach ($posts as $p): ?>
        <li><?php echo $p['title']; ?> - <a href="../post.php?id=<?php echo $p['id']; ?>">Xem</a></li>
    <?php endforeach; ?>
</ul>
<a href="../login.php">Đăng xuất</a>
