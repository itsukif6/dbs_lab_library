<?php
// =====================================================
// notify_return.php - 發送催還通知 Email 給借書人
// POST: book_id
// 🔒 需登入，請求者自動為登入者
// =====================================================

require_once 'config.php';
require_once 'auth.php';
require_once 'mailer.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$book_id = trim($body['book_id'] ?? '');
$requester_user_id = currentUserId(); // 從 Session 取得

if (!$book_id) {
    jsonResponse(false, '請提供 book_id');
}

$pdo = getDB();

// 取得書籍資訊
$bookStmt = $pdo->prepare("SELECT title, status FROM book WHERE book_id = ?");
$bookStmt->execute([$book_id]);
$book = $bookStmt->fetch();

if (!$book) {
    jsonResponse(false, '找不到此書籍');
}
if ((int)$book['status'] === 0) {
    jsonResponse(false, '此書籍目前並非借出狀態，無需催還');
}

// 取得請求者姓名
$reqStmt = $pdo->prepare("SELECT name FROM user WHERE user_id = ?");
$reqStmt->execute([$requester_user_id]);
$requester = $reqStmt->fetch();
if (!$requester) {
    jsonResponse(false, '找不到請求者資料');
}

// 取得借閱者資訊
$borrowStmt = $pdo->prepare("
    SELECT u.user_id, u.name, u.email, br.due_date
    FROM borrow br
    JOIN user u ON br.user_id = u.user_id
    WHERE br.book_id = ? AND br.return_date IS NULL
    ORDER BY br.borrow_date DESC
    LIMIT 1
");
$borrowStmt->execute([$book_id]);
$borrower = $borrowStmt->fetch();

if (!$borrower) {
    jsonResponse(false, '找不到借閱者資料');
}

// 發送 Email，並連動將通知寫入資料庫
$ok = sendReturnNotification(
    $borrower['email'],
    $borrower['name'],
    $book['title'],
    $requester['name'],
    (string)$borrower['user_id'] // 使用正確的變數與欄位名稱
);

if ($ok) {
    jsonResponse(true, "已成功發送催還通知給 {$borrower['name']}（{$borrower['email']}）");
} else {
    jsonResponse(false, "Email 發送失敗，請確認 SMTP 設定（mailer.php TODO 區段）");
}