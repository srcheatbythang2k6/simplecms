<?php
require_once 'config.php';
require_once 'includes/db.php';

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$pdo->exec($sql);

$adminPass = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->exec("INSERT IGNORE INTO users (id, username, password) VALUES (1, 'admin', '$adminPass')");

echo "✅ Installation completed.<br>";
echo "👉 Admin login: <b>admin / admin123</b>";
