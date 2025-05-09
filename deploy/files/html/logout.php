<?php
require_once 'includes/functions.php';

// 执行注销
logoutUser();

// 重定向到首页
header('Location: index.php');
exit; 