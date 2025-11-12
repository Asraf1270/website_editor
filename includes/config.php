<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'website_editor');
define('DB_USER', 'root');
define('DB_PASS', '');

// PDO connection
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}
?>