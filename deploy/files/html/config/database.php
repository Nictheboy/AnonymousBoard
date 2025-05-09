<?php
// Database connection settings
// $host = 'mysql';  // Docker service name for MySQL - Replaced by socket connection
$db_socket = '/run/mysqld/mysqld.sock';
$dbname = 'kasilab';
$username = 'root';
$password = 'root';

// Attempt database connection
try {
    $pdo = new PDO("mysql:unix_socket=$db_socket;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Use UTF-8 encoding
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
} 