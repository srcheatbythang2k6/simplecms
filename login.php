<?php
require_once 'config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Đây là bản demo, mật khẩu mặc định là 'admin'
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'admin') {
        $_SESSION['user_id'] = 1;
        header("Location: admin/index.php");
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>
<form method="POST">
    <h2>Đăng nhập hệ thống</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Đăng nhập</button>
</form>
