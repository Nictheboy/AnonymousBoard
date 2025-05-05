<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// 检查用户是否已登录
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// 获取当前用户ID
$userId = getCurrentUserId();

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取并验证表单数据
    $content = isset($_POST['content']) ? cleanInput($_POST['content']) : '';
    $nickname = isset($_POST['nickname']) ? cleanInput($_POST['nickname']) : null;
    
    // 验证内容（必填）
    if (empty($content)) {
        $error = "内容不能为空";
    } else {
        // 添加帖子到数据库，关联当前用户
        $success = addPost($content, $userId, $nickname);
        
        if ($success) {
            // 发布成功后重定向到首页
            header("Location: index.php?success=" . urlencode("发布成功"));
            exit;
        } else {
            $error = "发布失败，请稍后再试";
        }
    }
    
    // 如果出现错误，重定向回首页并显示错误消息
    if (isset($error)) {
        header("Location: index.php?error=" . urlencode($error));
        exit;
    }
} else {
    // 如果不是 POST 请求，重定向到首页
    header("Location: index.php");
    exit;
} 