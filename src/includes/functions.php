<?php
/**
 * 开始会话
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // 设置安全的会话cookie参数
        session_set_cookie_params([
            'lifetime' => 3600, // 1小时
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

/**
 * 检查用户是否已登录
 * 
 * @return boolean 用户是否已登录
 */
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

/**
 * 获取当前登录用户的ID
 * 
 * @return int|null 用户ID，未登录时返回null
 */
function getCurrentUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

/**
 * 获取当前登录用户的用户名
 * 
 * @return string|null 用户名，未登录时返回null
 */
function getCurrentUsername() {
    startSession();
    return $_SESSION['username'] ?? null;
}

/**
 * 注册新用户
 * 
 * @param string $username 用户名
 * @param string $email 电子邮件
 * @param string $password 密码
 * @return array 包含成功状态和消息的数组
 */
function registerUser($username, $email, $password) {
    global $pdo;
    
    try {
        // 检查用户名是否已存在
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND deleted_at IS NULL");
        $stmt->execute(['username' => $username]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => '用户名已被使用'];
        }
        
        // 检查邮箱是否已存在
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND deleted_at IS NULL");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => '邮箱已被注册'];
        }
        
        // 密码哈希
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 插入新用户
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $success = $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
        
        if ($success) {
            return ['success' => true, 'message' => '注册成功，请登录'];
        } else {
            return ['success' => false, 'message' => '注册失败，请稍后再试'];
        }
    } catch (PDOException $e) {
        error_log("Error registering user: " . $e->getMessage());
        return ['success' => false, 'message' => '注册过程中发生错误'];
    }
}

/**
 * 用户登录
 * 
 * @param string $username 用户名
 * @param string $password 密码
 * @return array 包含成功状态和消息的数组
 */
function loginUser($username, $password) {
    global $pdo;
    
    try {
        // 查找用户
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username AND deleted_at IS NULL");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => '用户名或密码不正确'];
        }
        
        // 验证密码
        if (password_verify($password, $user['password'])) {
            // 开始会话并保存用户信息
            startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // 为安全起见，生成新的会话ID
            session_regenerate_id(true);
            
            return ['success' => true, 'message' => '登录成功'];
        } else {
            return ['success' => false, 'message' => '用户名或密码不正确'];
        }
    } catch (PDOException $e) {
        error_log("Error logging in: " . $e->getMessage());
        return ['success' => false, 'message' => '登录过程中发生错误'];
    }
}

/**
 * 用户注销
 */
function logoutUser() {
    startSession();
    
    // 清除会话变量
    $_SESSION = [];
    
    // 删除会话cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // 销毁会话
    session_destroy();
}

/**
 * Get all posts from the database
 * 
 * @return array All posts ordered by creation date (newest first)
 */
function getPosts() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM posts WHERE deleted_at IS NULL ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting posts: " . $e->getMessage());
        return [];
    }
}

/**
 * Add a new post to the database
 * 
 * @param string $content The content of the post
 * @param int $userId The ID of the user
 * @param string $nickname Optional nickname (default is null)
 * @return boolean True if successful, false otherwise
 */
function addPost($content, $userId, $nickname = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, nickname, content) VALUES (:user_id, :nickname, :content)");
        return $stmt->execute([
            'user_id' => $userId,
            'nickname' => $nickname,
            'content' => $content
        ]);
    } catch (PDOException $e) {
        error_log("Error adding post: " . $e->getMessage());
        return false;
    }
}

/**
 * Clean input data
 * 
 * @param string $data Data to be cleaned
 * @return string Cleaned data
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
} 