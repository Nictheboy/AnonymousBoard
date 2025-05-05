<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and validate form data
    $content = isset($_POST['content']) ? cleanInput($_POST['content']) : '';
    $nickname = isset($_POST['nickname']) ? cleanInput($_POST['nickname']) : null;
    
    // Validate content (required)
    if (empty($content)) {
        $error = "内容不能为空";
    } else {
        // Add post to database
        $success = addPost($content, $nickname);
        
        if ($success) {
            // Redirect to homepage after successful post
            header("Location: index.php");
            exit;
        } else {
            $error = "发布失败，请稍后再试";
        }
    }
    
    // If there was an error, redirect back with error message
    if (isset($error)) {
        header("Location: index.php?error=" . urlencode($error));
        exit;
    }
} else {
    // If not a POST request, redirect to homepage
    header("Location: index.php");
    exit;
} 