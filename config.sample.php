<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'simplecms');
define('DB_USER', 'simplecms_user');
define('DB_PASS', 'MAT_KHAU_DB_CUA_BAN');

define('SITE_URL', 'http://192.168.202.134');

define('AUTH_KEY', 'key123');
define('SECURE_AUTH_KEY', 'key456');
