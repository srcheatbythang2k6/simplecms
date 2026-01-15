<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?,?)");
    $stmt->execute([$_POST['title'], $_POST['content']]);
    echo "✅ Post published";
}
?>

<form method="post">
    <input name="title" placeholder="Title"><br>
    <textarea name="content" placeholder="Content"></textarea><br>
    <button>Publish</button>
</form>
