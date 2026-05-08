<?php
// =====================================================
// delete_book.php - 刪除書籍
// POST: book_id
// =====================================================

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$book_id = trim($body['book_id'] ?? '');

if (!$book_id) {
    jsonResponse(false, '缺少 book_id');
}

$pdo = getDB();

// 確認書籍存在
$check = $pdo->prepare("SELECT status FROM book WHERE book_id = ?");
$check->execute([$book_id]);
$book = $check->fetch();

if (!$book) {
    jsonResponse(false, '找不到此書籍');
}
if ((int)$book['status'] === 1) {
    jsonResponse(false, '此書籍目前已借出，無法刪除');
}

// 刪除相關借閱紀錄，再刪書籍
$pdo->prepare("DELETE FROM borrow WHERE book_id = ?")->execute([$book_id]);
$pdo->prepare("DELETE FROM book WHERE book_id = ?")->execute([$book_id]);

jsonResponse(true, '書籍刪除成功');
