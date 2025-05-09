<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// 检查是否已登录
$isLoggedIn = isLoggedIn();
$currentUsername = getCurrentUsername();

// 获取所有帖子
$posts = getPosts();

// 获取错误消息（如果存在）
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>匿名墙</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>匿名墙</h1>
            <p>分享你的想法，匿名显示</p>
            
            <div class="auth-status">
                <?php if ($isLoggedIn): ?>
                    <p>欢迎, <?php echo htmlspecialchars($currentUsername); ?>! <a href="logout.php">注销</a></p>
                <?php else: ?>
                    <p><a href="login.php">登录</a> 或 <a href="register.php">注册</a> 以发布内容</p>
                <?php endif; ?>
            </div>
        </header>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <section class="post-form">
                <h2>发布新内容</h2>
                <form action="post.php" method="POST">
                    <div class="form-group">
                        <label for="nickname">显示名称 (可选):</label>
                        <input type="text" id="nickname" name="nickname" placeholder="匿名">
                        <small>留空将显示为"匿名"</small>
                    </div>
                    <div class="form-group">
                        <label for="content">内容:</label>
                        <textarea id="content" name="content" required></textarea>
                    </div>
                    <button type="submit">发布</button>
                </form>
            </section>
        <?php else: ?>
            <div class="login-prompt">
                <p>请<a href="login.php">登录</a>后发布内容</p>
            </div>
        <?php endif; ?>

        <section class="posts">
            <h2>最新内容</h2>
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <div class="post-header">
                            <span class="nickname"><?php echo htmlspecialchars($post['nickname'] ?: '匿名'); ?></span>
                            <span class="time"><?php echo htmlspecialchars($post['created_at']); ?></span>
                        </div>
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-posts">还没有人发布内容，快来成为第一个吧！</p>
            <?php endif; ?>
        </section>
        
        <footer>
            <!-- <p><a href="init_db.php">初始化数据库</a></p> -->
        </footer>
    </div>
</body>
</html> 