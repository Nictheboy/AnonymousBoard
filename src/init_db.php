<?php
require_once 'config/database.php';

try {
    // Create posts table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nickname VARCHAR(50) NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "数据库初始化成功。<br>";
    echo "<a href='index.php'>返回主页</a>";
} catch (PDOException $e) {
    die("数据库初始化失败: " . $e->getMessage());
} 