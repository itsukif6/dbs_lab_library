<?php
// =====================================================
// logout.php - 登出 API
// POST (no body required)
// =====================================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'config.php';

session_unset();
session_destroy();

// 清除 Session Cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

jsonResponse(true, '已成功登出');
