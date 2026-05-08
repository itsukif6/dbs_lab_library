<?php
// api/send_verification.php
session_start();
require_once 'config.php';
require_once 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim($body['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Email 格式無效');
}

$pdo = getDB();
// 檢查 Email 是否已被註冊
$stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(false, '此 Email 已經被註冊過囉');
}

// 額外檢查：如果前端有帶 user_id，則檢查該學號是否已註冊，避免重複發送驗證碼
$user_id = trim($body['user_id'] ?? '');
if ($user_id) {
    $stmt = $pdo->prepare("SELECT user_id FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->fetch()) {
        jsonResponse(false, '此學號已經註冊過了，不需再發送驗證碼');
    }
}

// 產生 6 位數驗證碼
$code = sprintf("%06d", mt_rand(0, 999999));

// 將驗證碼與過期時間(5分鐘)存入 Session
$_SESSION['verify_data'] = [
    'email'   => $email,
    'code'    => $code,
    'expires' => time() + 300 
];

// 使用你 mailer.php 現有的 sendMail 邏輯寄信
$subject = "圖書館系統 - 註冊驗證碼";
$body    = "
    <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
        <h2 style='color: #1a3a5c;'>📚 圖書館系統註冊驗證</h2>
        <p>您的驗證碼為：<strong style='font-size:24px; color:#d4a853; letter-spacing: 2px;'>{$code}</strong></p>
        <p>請於 5 分鐘內輸入此驗證碼，逾期請重新發送。</p>
        <hr style='border: 1px solid #ddd; margin-top: 20px;'>
        <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。</p>
    </div>
";

// 呼叫 mailer.php 裡的 sendMail(收件者信箱, 收件者名稱, 主旨, HTML內容)
// 因為是新註冊，名稱暫時帶入 '新使用者'
$isSent = sendMail($email, '新使用者', $subject, $body);

if ($isSent) {
    jsonResponse(true, '驗證碼已發送');
} else {
    jsonResponse(false, '信件發送失敗，請確認 SMTP 與 mail_config.php 設定');
}