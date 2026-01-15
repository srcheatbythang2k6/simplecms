<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'simplecms');
define('DB_USER', 'simplecms_user');
define('DB_PASS', 'password');

define('SITE_URL', 'http://localhost');

define('AUTH_KEY', 'changeme');
define('SECURE_AUTH_KEY', 'changeme');

session_start();
