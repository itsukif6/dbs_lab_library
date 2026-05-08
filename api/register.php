<?php
// api/register.php
session_start();
require_once 'config.php';
require_once 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$user_id  = trim($body['user_id'] ?? '');
$name     = trim($body['name'] ?? '');
$email    = trim($body['email'] ?? '');
$password = trim($body['password'] ?? '');
$code     = trim($body['code'] ?? '');

if (strlen($user_id) !== 10) {
    jsonResponse(false, '學號格式錯誤，必須為 10 碼');
}
if (!$user_id || !$name || !$email || !$password || !$code) {
    jsonResponse(false, '所有欄位皆為必填');
}

// 1. 檢查 Session 驗證碼
if (!isset($_SESSION['verify_data'])) {
    jsonResponse(false, '請先發送驗證碼');
}
$verifyData = $_SESSION['verify_data'];

if ($verifyData['email'] !== $email) {
    jsonResponse(false, '驗證的 Email 與目前填寫的不符');
}
if (time() > $verifyData['expires']) {
    unset($_SESSION['verify_data']);
    jsonResponse(false, '驗證碼已過期，請重新發送');
}
if ($verifyData['code'] !== $code) {
    jsonResponse(false, '驗證碼錯誤');
}

$pdo = getDB();

// 2. 新增檢查：學號是否重複
$stmt = $pdo->prepare("SELECT user_id FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
if ($stmt->fetch()) {
    jsonResponse(false, '此學號已經註冊過，請直接登入');
}

// 3. 新增檢查：Email 是否重複 (防止一個 Email 註冊多個帳號)
$stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(false, '此 Email 已經被其他學號使用');
}

// 將密碼進行 Hash 處理 (Bcrypt)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    // 4. 寫入 user 表
    $insertUser = $pdo->prepare("
        INSERT INTO user (user_id, name, email, password, role) 
        VALUES (?, ?, ?, ?, 'student')
    ");
    $insertUser->execute([$user_id, $name, $email, $hashedPassword]);

    // 5. 取得「註冊通知」的 type_id
    $typeStmt = $pdo->prepare("SELECT type_id FROM notification_type WHERE name = '註冊通知' LIMIT 1");
    $typeStmt->execute();
    $type = $typeStmt->fetch();
    
    if ($type) {
        $insertNotif = $pdo->prepare("
            INSERT INTO notification (notification_id, user_id, type_id, message, create_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $insertNotif->execute([
            generateUUID(), 
            $user_id, 
            $type['type_id'], 
            "歡迎 $name 加入圖書館系統！"
        ]);
    }

    // 寄送歡迎 Email
    $subject = "歡迎加入圖書館系統！";
    $body = "
    <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
        <h2 style='color: #1a3a5c;'>🎉 歡迎加入圖書館系統</h2>
        <p>親愛的 <strong>{$name}</strong> 同學，您好：</p>
        <p>您的帳號已經成功建立！您現在可以使用學號 <strong>{$user_id}</strong> 登入系統，開始探索與借閱書籍。</p>
        <hr style='border: 1px solid #ddd; margin-top: 20px;'>
        <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。</p>
    </div>";

    // 呼叫寄信函數 (就算信件寄送失敗，因為資料庫已經 commit，註冊依然算成功)
    sendMail($email, $name, $subject, $body);

    $pdo->commit();
    
    unset($_SESSION['verify_data']);
    jsonResponse(true, '註冊成功');

} catch (Exception $e) {
    $pdo->rollBack();
    jsonResponse(false, '資料庫寫入失敗：' . $e->getMessage());
}