<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* SESSION */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* DATABASE CONFIG */
define('DB_HOST', 'localhost');
define('DB_NAME', 'simplecms');
define('DB_USER', 'simplecms_user');
define('DB_PASS', 'MAT_KHAU_MYSQL_CUA_BAN');

/* SITE */
define('SITE_URL', 'http://192.168.202.134');
