<?php
require_once 'config.php';
require_once 'includes/functions.php';

$db = db_connect();
$id = (int)$_GET['id'];
$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) die("Bài viết không tồn tại.");

echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
echo "<div>" . nl2br(htmlspecialchars($post['content'])) . "</div>";
echo "<a href='index.php'>Quay lại</a>";
?>
