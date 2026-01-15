<?php
require_once 'config.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: /admin");
        exit;
    }

    $error = "Sai tài khoản hoặc mật khẩu";
}
?>

<form method="post">
    <h2>Login</h2>
    <?= isset($error) ? "<p style='color:red'>$error</p>" : "" ?>
    <input name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button>Login</button>
</form>
