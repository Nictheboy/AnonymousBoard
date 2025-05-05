<?php
require_once 'config/database.php';

try {
    // 创建用户表
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        INDEX idx_deleted_at (deleted_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    
    // 检查posts表是否已存在
    $stmt = $pdo->query("SHOW TABLES LIKE 'posts'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        // 如果表已存在，添加新字段并修改结构
        // 检查是否已经有user_id列
        $stmt = $pdo->query("SHOW COLUMNS FROM posts LIKE 'user_id'");
        $userIdExists = $stmt->rowCount() > 0;
        
        if (!$userIdExists) {
            // 添加user_id字段和deleted_at字段
            $pdo->exec("ALTER TABLE posts 
                ADD COLUMN user_id INT NOT NULL AFTER id,
                ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
                ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at),
                ADD CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES users(id)");
        }
    } else {
        // 创建新的posts表
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            nickname VARCHAR(50) NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            INDEX idx_deleted_at (deleted_at),
            CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $pdo->exec($sql);
    }
    
    echo "数据库初始化成功。<br>";
    echo "<a href='index.php'>返回主页</a>";
} catch (PDOException $e) {
    die("数据库初始化失败: " . $e->getMessage());
} 