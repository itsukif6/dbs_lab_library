<?php
// =====================================================
// update_profile.php - 更新使用者姓名
// POST: name
// 🔒 需登入，只能修改自己的資料
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$name = trim($body['name'] ?? '');

if (!$name) {
    jsonResponse(false, '姓名不可為空');
}
if (mb_strlen($name) > 10) {
    jsonResponse(false, '姓名不可超過 10 個字');
}

$pdo = getDB();
$pdo->prepare("UPDATE user SET name = ? WHERE user_id = ?")
    ->execute([$name, currentUserId()]);

// 同步更新 Session
$_SESSION['name'] = $name;

jsonResponse(true, '姓名已更新成功');
