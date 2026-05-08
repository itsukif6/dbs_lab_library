<?php
// =====================================================
// update_email.php - 驗證碼正確後更新 Email
// POST: code
// 🔒 需登入
// =====================================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'config.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$code = trim($body['code'] ?? '');

if (!$code) {
    jsonResponse(false, '請輸入驗證碼');
}

// 檢查 Session 中的驗證資料
if (empty($_SESSION['email_verify'])) {
    jsonResponse(false, '請先發送驗證碼');
}

$verify = $_SESSION['email_verify'];

if ($verify['user_id'] !== currentUserId()) {
    jsonResponse(false, '驗證資料不符，請重新發送');
}
if (time() > $verify['expires']) {
    unset($_SESSION['email_verify']);
    jsonResponse(false, '驗證碼已過期，請重新發送');
}
if ($verify['code'] !== $code) {
    jsonResponse(false, '驗證碼錯誤');
}

$new_email = $verify['new_email'];
$pdo       = getDB();

// 再次確認新 Email 未被佔用（防止驗證期間被搶注）
$dup = $pdo->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
$dup->execute([$new_email, currentUserId()]);
if ($dup->fetch()) {
    jsonResponse(false, '此 Email 已被其他帳號使用');
}

$pdo->prepare("UPDATE user SET email = ? WHERE user_id = ?")
    ->execute([$new_email, currentUserId()]);

// 同步更新 Session
$_SESSION['email'] = $new_email;
unset($_SESSION['email_verify']);

jsonResponse(true, "Email 已成功更新為 {$new_email}");
