<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// 如果用户已登录，重定向到首页
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取并验证表单数据
    $username = isset($_POST['username']) ? cleanInput($_POST['username']) : '';
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // 验证数据
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = '所有字段都是必填的';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '请输入有效的电子邮件地址';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = '用户名必须在3-50个字符之间';
    } elseif (strlen($password) < 8) {
        $error = '密码必须至少包含8个字符';
    } elseif ($password !== $confirmPassword) {
        $error = '两次输入的密码不匹配';
    } else {
        // 尝试注册用户
        $result = registerUser($username, $email, $password);
        
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册 - 匿名墙</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>注册账号</h1>
            <p>创建账号以便在匿名墙发布内容</p>
        </header>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?php echo $success; ?>
                <p><a href="login.php">点击这里登录</a></p>
            </div>
        <?php else: ?>
            <form action="register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">用户名:</label>
                    <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">电子邮件:</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">密码:</label>
                    <input type="password" id="password" name="password" required>
                    <small>密码必须至少包含8个字符</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">确认密码:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit">注册</button>
            </form>
            
            <div class="auth-links">
                <p>已有账号? <a href="login.php">登录</a></p>
                <p><a href="index.php">返回首页</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 