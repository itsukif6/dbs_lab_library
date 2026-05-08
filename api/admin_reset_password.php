<?php
// =====================================================
// admin_reset_password.php - 管理員還原使用者密碼為學號
// POST: user_id
// 🔒 管理員專用
// =====================================================

require_once 'config.php';
require_once 'auth.php';
require_once 'mailer.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$user_id = trim($body['user_id'] ?? '');

if (!$user_id) {
    jsonResponse(false, '請提供 user_id');
}

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT user_id, name, email FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse(false, '找不到此使用者');
}

// 不允許管理員重置自己的密碼（避免意外）
if ($user_id === currentUserId()) {
    jsonResponse(false, '不可重置自己的密碼，請使用個人資料頁修改');
}

// 新密碼 = 學號（使用 bcrypt 加密）
$newPassword = $user_id;
$hashed      = password_hash($newPassword, PASSWORD_DEFAULT);

$pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?")
    ->execute([$hashed, $user_id]);

// 寄送通知 Email
$subject = "【圖書館系統】您的密碼已被重置";
$emailBody = "
<div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
    <h2 style='color: #1a3a5c;'>🔑 密碼重置通知</h2>
    <p>親愛的 <strong>{$user['name']}</strong> 同學，您好：</p>
    <p>您的圖書館系統帳號密碼已由管理員重置。</p>
    <table style='width:100%; border-collapse: collapse; margin: 20px 0;'>
        <tr>
            <td style='padding: 10px 14px; background:#eef2f7; width:100px;'><strong>帳號</strong></td>
            <td style='padding: 10px 14px;'>{$user['user_id']}</td>
        </tr>
        <tr>
            <td style='padding: 10px 14px; background:#eef2f7;'><strong>新密碼</strong></td>
            <td style='padding: 10px 14px;'>
                <span style='font-size: 20px; font-weight: bold; color: #d4a853; letter-spacing: 2px;'>{$newPassword}</span>
                <span style='font-size: 12px; color: #999;'>（即您的學號）</span>
            </td>
        </tr>
    </table>
    <p style='color: #e74c3c;'>⚠ 請登入後立即至「個人資料」頁面修改密碼。</p>
    <hr style='border: 1px solid #ddd; margin-top: 20px;'>
    <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。</p>
</div>";

$mailSent = sendMail($user['email'], $user['name'], $subject, $emailBody);

// 寫入 notification 資料庫
saveNotificationToDB($user_id, '系統通知', "您的密碼已由管理員重置為學號，請盡快更改。");

$msg = "已將 {$user['name']}（{$user_id}）的密碼重置為學號";
if (!$mailSent) {
    $msg .= "（Email 通知發送失敗，請確認 SMTP 設定）";
}

jsonResponse(true, $msg);
