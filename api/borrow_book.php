<?php
// =====================================================
// borrow_book.php - 借書登記
// POST: book_id, due_days (預設14天)
// 🔒 需登入，借閱者固定為登入者本人
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$book_id  = trim($body['book_id']  ?? '');
$due_days = (int)($body['due_days'] ?? 14);
$user_id  = currentUserId(); // 從 Session 取得，不接受前端傳入

if (!$book_id) {
    jsonResponse(false, '請提供 book_id');
}

$pdo = getDB();

// 確認書籍可借
$bookStmt = $pdo->prepare("SELECT status, title FROM book WHERE book_id = ?");
$bookStmt->execute([$book_id]);
$book = $bookStmt->fetch();

if (!$book) {
    jsonResponse(false, '找不到此書籍');
}
if ((int)$book['status'] === 1) {
    jsonResponse(false, '此書籍目前已借出');
}

$borrow_id   = generateUUID();
$borrow_date = date('Y-m-d');
$due_date    = date('Y-m-d', strtotime("+{$due_days} days"));
// return_date 為 NULL 表示尚未歸還

// 新增借閱紀錄
$pdo->prepare("
    INSERT INTO borrow (borrow_id, user_id, book_id, borrow_date, due_date, return_date)
    VALUES (?, ?, ?, ?, ?, NULL)
")->execute([$borrow_id, $user_id, $book_id, $borrow_date, $due_date]);

// 更新書籍狀態為借出
$pdo->prepare("UPDATE book SET status = 1 WHERE book_id = ?")->execute([$book_id]);

jsonResponse(true, "《{$book['title']}》借閱成功！歸還期限：{$due_date}");
