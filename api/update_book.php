<?php
// =====================================================
// update_book.php - 修改書籍資訊（管理員專用）
// POST: book_id, title, author, category_id, keyword
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireAdmin(); // 🔒 管理員才可使用

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body        = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$book_id     = trim($body['book_id']     ?? '');
$title       = trim($body['title']       ?? '');
$author      = trim($body['author']      ?? '');
$category_id = trim($body['category_id'] ?? '');
$keyword     = trim($body['keyword']     ?? '');

if (!$book_id || !$title || !$author || !$category_id) {
    jsonResponse(false, '請填寫所有必填欄位');
}

$pdo = getDB();

// 確認書籍存在
$check = $pdo->prepare("SELECT book_id FROM book WHERE book_id = ?");
$check->execute([$book_id]);
if (!$check->fetch()) {
    jsonResponse(false, '找不到此書籍');
}

// 確認分類存在
$catCheck = $pdo->prepare("SELECT category_id FROM category WHERE category_id = ?");
$catCheck->execute([$category_id]);
if (!$catCheck->fetch()) {
    jsonResponse(false, '找不到指定分類');
}

$pdo->prepare("
    UPDATE book SET title = ?, author = ?, category_id = ?, keyword = ?
    WHERE book_id = ?
")->execute([$title, $author, $category_id, $keyword, $book_id]);

jsonResponse(true, "書籍《{$title}》資料已更新");
