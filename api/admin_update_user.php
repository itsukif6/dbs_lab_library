<?php
// =====================================================
// admin_update_user.php - 管理員修改使用者姓名/Email
// POST: user_id, name, email
// 🔒 管理員專用
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$user_id = trim($body['user_id'] ?? '');
$name    = trim($body['name']    ?? '');
$email   = trim($body['email']   ?? '');

if (!$user_id || !$name || !$email) {
    jsonResponse(false, '請填寫所有欄位');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Email 格式無效');
}
if (mb_strlen($name) > 10) {
    jsonResponse(false, '姓名不可超過 10 個字');
}

$pdo = getDB();

// 確認使用者存在
$check = $pdo->prepare("SELECT user_id FROM user WHERE user_id = ?");
$check->execute([$user_id]);
if (!$check->fetch()) {
    jsonResponse(false, '找不到此使用者');
}

// 確認 Email 未被其他人佔用
$dup = $pdo->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
$dup->execute([$email, $user_id]);
if ($dup->fetch()) {
    jsonResponse(false, '此 Email 已被其他帳號使用');
}

$pdo->prepare("UPDATE user SET name = ?, email = ? WHERE user_id = ?")
    ->execute([$name, $email, $user_id]);

jsonResponse(true, "使用者 {$user_id} 的資料已更新");
