<?php
define('DSN', 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');

function db_connect() {
    $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
    return $pdo;
}
?>