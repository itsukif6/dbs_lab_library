<?php
// =====================================================
// mailer.php - Email 發送工具 (使用 PHPMailer)
// =====================================================

require_once 'mail_config.php';
require_once __DIR__ . '/../vendor/autoload.php'; 
require_once 'config.php'; // 為了使用 getDB() 和 generateUUID()

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail(string $toEmail, string $toName, string $subject, string $htmlBody): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = (MAIL_ENCRYPTION === 'tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = MAIL_CHARSET;

        $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * 新增：將通知寫入資料庫的共用函數
 */
function saveNotificationToDB(string $user_id, string $typeName, string $message) {
    if (empty($user_id)) return; // 沒傳學號就不寫入
    try {
        $pdo = getDB();
        // 根據通知名稱取得 type_id
        $stmt = $pdo->prepare("SELECT type_id FROM notification_type WHERE name = ? LIMIT 1");
        $stmt->execute([$typeName]);
        $type = $stmt->fetch();
        
        if ($type) {
            $insert = $pdo->prepare("
                INSERT INTO notification (notification_id, user_id, type_id, message, create_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $insert->execute([generateUUID(), $user_id, $type['type_id'], $message]);
        }
    } catch (Exception $e) {
        error_log("Notification DB Error: " . $e->getMessage());
    }
}

/**
 * 發送「催還通知」Email 給借書人 (新增 $user_id 參數)
 */
function sendReturnNotification(string $borrowerEmail, string $borrowerName, string $bookTitle, string $requesterName, string $user_id = ''): bool {
    $subject = "【圖書館通知】書籍歸還提醒";
    $body = "
    <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
        <h2 style='color: #1a3a5c;'>📚 圖書館歸還提醒</h2>
        <p>親愛的 <strong>{$borrowerName}</strong> 同學，您好：</p>
        <p>您目前借閱的書籍 <strong>《{$bookTitle}》</strong> 有其他同學希望借閱，</p>
        <p>煩請您在方便時盡速歸還，感謝您的配合！</p>
        <hr style='border: 1px solid #ddd;'>
        <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。</p>
    </div>";
    
    $isSent = sendMail($borrowerEmail, $borrowerName, $subject, $body);
    
    // 信件寄出後，寫入資料庫
    if ($isSent && $user_id !== '') {
        $msg = "您借閱的《{$bookTitle}》有其他同學希望借閱，請盡速歸還！";
        saveNotificationToDB($user_id, '催還通知', $msg);
    }
    return $isSent;
}

/**
 * 發送「新書上架」Email 通知 (新增 $user_id 參數)
 */
function sendNewBookNotification(string $userEmail, string $userName, string $bookTitle, string $author, string $categoryName, string $user_id = ''): bool {
    $subject = "【圖書館通知】新書上架：{$bookTitle}";
    $body = "
    <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 30px; background: #f9f9f9; border-radius: 8px;'>
        <h2 style='color: #1a3a5c;'>📗 新書上架通知</h2>
        <p>親愛的 <strong>{$userName}</strong> 同學，您好：</p>
        <p>圖書館新增了一本好書，歡迎借閱！</p>
        <table style='width:100%; border-collapse: collapse; margin: 20px 0;'>
            <tr><td style='padding: 8px; background:#eef2f7;'><strong>書名</strong></td><td style='padding: 8px;'>{$bookTitle}</td></tr>
            <tr><td style='padding: 8px; background:#eef2f7;'><strong>作者</strong></td><td style='padding: 8px;'>{$author}</td></tr>
            <tr><td style='padding: 8px; background:#eef2f7;'><strong>分類</strong></td><td style='padding: 8px;'>{$categoryName}</td></tr>
        </table>
        <hr style='border: 1px solid #ddd;'>
        <p style='color: #999; font-size: 12px;'>此為系統自動發送，請勿直接回覆。</p>
    </div>";
    
    $isSent = sendMail($userEmail, $userName, $subject, $body);
    
    // 信件寄出後，寫入資料庫
    if ($isSent && $user_id !== '') {
        $msg = "新書上架推薦：《{$bookTitle}》已經可以借閱囉！";
        saveNotificationToDB($user_id, '新書通知', $msg);
    }
    return $isSent;
}