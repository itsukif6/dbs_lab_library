<?php
// =====================================================
// send_email_verify.php - 發送 Email 驗證碼（修改信箱用）
// POST: new_email
// 🔒 需登入
// =====================================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'config.php';
require_once 'auth.php';
require_once 'mailer.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$new_email = trim($body['new_email'] ?? '');

if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Email 格式無效');
}

$pdo = getDB();

// 確認新 Email 未被其他人使用
$stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
$stmt->execute([$new_email, currentUserId()]);
if ($stmt->fetch()) {
    jsonResponse(false, '此 Email 已被其他帳號使用');
}

// 產生 6 位數驗證碼，存入 Session（5 分鐘有效）
$code = sprintf("%06d", mt_rand(0, 999999));
$_SESSION['email_verify'] = [
    'new_email' => $new_email,
    'code'      => $code,
    'expires'   => time() + 300,
    'user_id'   => currentUserId(),
];

$subject = "【圖書館系統】Email 變更驗證碼";
$body    = "
<div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
    <h2 style='color: #1a3a5c;'>📧 Email 變更驗證</h2>
    <p>您好，您的 Email 變更驗證碼為：</p>
    <div style='text-align:center; margin: 24px 0;'>
        <span style='font-size: 32px; font-weight: bold; color: #d4a853; letter-spacing: 6px;'>{$code}</span>
    </div>
    <p>請於 <strong>5 分鐘</strong>內輸入此驗證碼，逾期請重新發送。</p>
    <hr style='border: 1px solid #ddd; margin-top: 20px;'>
    <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。若非本人操作請忽略。</p>
</div>";

$name   = $_SESSION['name'] ?? currentUserId();
$isSent = sendMail($new_email, $name, $subject, $body);

if ($isSent) {
    jsonResponse(true, '驗證碼已發送至新信箱，請於 5 分鐘內完成驗證');
} else {
    jsonResponse(false, '驗證碼發送失敗，請確認 SMTP 設定');
}
