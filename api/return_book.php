<?php
// =====================================================
// return_book.php - 還書登記
// POST: book_id
// 🔒 需登入（管理員可還任何人的書；學生只能還自己的）
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$book_id = trim($body['book_id'] ?? '');

if (!$book_id) {
    jsonResponse(false, '缺少 book_id');
}

$pdo = getDB();

// 確認書籍存在且為借出狀態
$bookStmt = $pdo->prepare("SELECT status, title FROM book WHERE book_id = ?");
$bookStmt->execute([$book_id]);
$book = $bookStmt->fetch();

if (!$book) {
    jsonResponse(false, '找不到此書籍');
}
if ((int)$book['status'] === 0) {
    jsonResponse(false, '此書籍目前並非借出狀態');
}

// 學生只能歸還自己借的書
if (!isAdmin()) {
    $ownerStmt = $pdo->prepare("
        SELECT user_id FROM borrow
        WHERE book_id = ? AND return_date IS NULL
        ORDER BY borrow_date DESC LIMIT 1
    ");
    $ownerStmt->execute([$book_id]);
    $owner = $ownerStmt->fetch();
    if (!$owner || $owner['user_id'] !== currentUserId()) {
        jsonResponse(false, '你只能歸還自己借閱的書籍');
    }
}

$return_date = date('Y-m-d');

// 更新借閱紀錄的 return_date (最近一筆尚未歸還的)
$updateBorrow = $pdo->prepare("
    UPDATE borrow
    SET return_date = ?
    WHERE book_id = ? AND return_date IS NULL
    ORDER BY borrow_date DESC
    LIMIT 1
");
$updateBorrow->execute([$return_date, $book_id]);

// 更新書籍狀態為可借閱
$pdo->prepare("UPDATE book SET status = 0 WHERE book_id = ?")->execute([$book_id]);

jsonResponse(true, "《{$book['title']}》已成功歸還，歸還日期：{$return_date}");
