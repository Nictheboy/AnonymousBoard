<?php
// Database connection settings
$host = 'mysql';  // Docker service name for MySQL
$dbname = 'anonymous_board';
$username = 'anon_user';
$password = 'anon_password';

// Attempt database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Use UTF-8 encoding
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
} 