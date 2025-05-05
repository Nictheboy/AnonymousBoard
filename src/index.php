<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get all posts
$posts = getPosts();

// Get error message if exists
$error = isset($_GET['error']) ? $_GET['error'] : '';
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
            <p>在这里分享你的想法，无需注册</p>
        </header>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <section class="post-form">
            <h2>发布新内容</h2>
            <form action="post.php" method="POST">
                <div class="form-group">
                    <label for="nickname">昵称 (可选):</label>
                    <input type="text" id="nickname" name="nickname" placeholder="匿名">
                </div>
                <div class="form-group">
                    <label for="content">内容:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <button type="submit">发布</button>
            </form>
        </section>

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
            <p><a href="init_db.php">初始化数据库</a></p>
        </footer>
    </div>
</body>
</html> 