<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// 如果用户已登录，重定向到首页
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据
    $username = isset($_POST['username']) ? cleanInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // 验证数据
    if (empty($username) || empty($password)) {
        $error = '请输入用户名和密码';
    } else {
        // 尝试登录
        $result = loginUser($username, $password);
        
        if ($result['success']) {
            // 登录成功，重定向到首页
            header('Location: index.php');
            exit;
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
    <title>登录 - 匿名墙</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>用户登录</h1>
            <p>登录后即可在匿名墙发布内容</p>
        </header>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">登录</button>
        </form>
        
        <div class="auth-links">
            <p>没有账号? <a href="register.php">注册</a></p>
            <p><a href="index.php">返回首页</a></p>
        </div>
    </div>
</body>
</html> 