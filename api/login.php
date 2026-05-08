<?php
// =====================================================
// api/login.php - 登入 API
// POST: user_id, password
// =====================================================

// 確保 Session 已經啟動
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$user_id  = trim($body['user_id']  ?? '');
$password = trim($body['password'] ?? '');

if (!$user_id || !$password) {
    jsonResponse(false, '請輸入學號與密碼');
}

$pdo  = getDB();
// 撈取使用者的帳號與雜湊密碼
$stmt = $pdo->prepare("SELECT user_id, name, email, password, role FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse(false, '帳號不存在');
}

// =====================================================
// 🔒 密碼雙重驗證機制
// 1. === 比對：相容系統原本手動建置的明碼測試帳號
// 2. password_verify：驗證新註冊經過 Bcrypt Hash 的加密密碼
// =====================================================
$passwordOk = ($user['password'] === $password) || password_verify($password, $user['password']);

if (!$passwordOk) {
    jsonResponse(false, '密碼錯誤');
}

// =====================================================
// ✅ 登入成功：建立安全 Session
// =====================================================
session_regenerate_id(true); // 重新產生 Session ID，防止 Session Fixation 攻擊

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['name']    = $user['name'];
$_SESSION['email']   = $user['email'];
$_SESSION['role']    = $user['role'];

// 回傳給前端 Vue
jsonResponse(true, '登入成功', [
    'user_id' => $user['user_id'],
    'name'    => $user['name'],
    'email'   => $user['email'],
    'role'    => $user['role'],
]);