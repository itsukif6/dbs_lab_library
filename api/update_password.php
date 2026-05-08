<?php
// =====================================================
// update_password.php - 使用者自行修改密碼
// POST: old_password, new_password
// 🔒 需登入，需驗證舊密碼
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body         = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$old_password = trim($body['old_password'] ?? '');
$new_password = trim($body['new_password'] ?? '');

if (!$old_password || !$new_password) {
    jsonResponse(false, '請填寫原密碼與新密碼');
}
if (strlen($new_password) < 6) {
    jsonResponse(false, '新密碼長度至少需要 6 個字元');
}
if ($old_password === $new_password) {
    jsonResponse(false, '新密碼不可與原密碼相同');
}

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT password FROM user WHERE user_id = ?");
$stmt->execute([currentUserId()]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse(false, '找不到使用者資料');
}

// 驗證原密碼（支援明文與 bcrypt）
$oldOk = ($user['password'] === $old_password)
      || password_verify($old_password, $user['password']);

if (!$oldOk) {
    jsonResponse(false, '原密碼錯誤，請重新輸入');
}

// 使用 bcrypt 加密新密碼
$hashed = password_hash($new_password, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?")
    ->execute([$hashed, currentUserId()]);

jsonResponse(true, '密碼修改成功');
