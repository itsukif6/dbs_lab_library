<?php
// =====================================================
// check_session.php - 前端頁面載入時驗證登入狀態
// GET (no params)
// =====================================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'config.php';

if (!empty($_SESSION['user_id'])) {
    jsonResponse(true, 'already_login', [
        'user_id' => $_SESSION['user_id'],
        'name'    => $_SESSION['name'],
        'email'   => $_SESSION['email'],
        'role'    => $_SESSION['role'],
    ]);
} else {
    jsonResponse(false, 'not_login');
}
