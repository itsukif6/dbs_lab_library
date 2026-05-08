<?php
// =====================================================
// add_book.php - 新增書籍，並發送新書通知 Email
// POST: book_id, title, author, category_id, keyword
// =====================================================

require_once 'config.php';
require_once 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, '僅接受 POST 請求');
}

$body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$book_id     = trim($body['book_id']     ?? '');
$title       = trim($body['title']       ?? '');
$author      = trim($body['author']      ?? '');
$category_id = trim($body['category_id'] ?? '');
$keyword     = trim($body['keyword']     ?? '');

// 驗證必填欄位
if (!$book_id || !$title || !$author || !$category_id) {
    jsonResponse(false, '請填寫所有必填欄位 (ISBN、書名、作者、分類)');
}
if (!preg_match('/^\d{13}$/', $book_id)) {
    jsonResponse(false, 'ISBN 格式錯誤，需為 13 位數字');
}

$pdo = getDB();

// 檢查 ISBN 是否重複
$check = $pdo->prepare("SELECT book_id FROM book WHERE book_id = ?");
$check->execute([$book_id]);
if ($check->fetch()) {
    jsonResponse(false, '此 ISBN 已存在，請確認是否重複新增');
}

// 取得分類名稱 (用於 Email)
$catStmt = $pdo->prepare("SELECT name FROM category WHERE category_id = ?");
$catStmt->execute([$category_id]);
$cat = $catStmt->fetch();
if (!$cat) {
    jsonResponse(false, '找不到指定分類');
}

// 新增書籍 (status=0: 可借閱)
$insert = $pdo->prepare("
    INSERT INTO book (book_id, title, author, category_id, status, keyword)
    VALUES (?, ?, ?, ?, 0, ?)
");
$insert->execute([$book_id, $title, $author, $category_id, $keyword]);

// 修改：只抓取 role = 'student' 的學生，避免寄給管理員
$users = $pdo->query("SELECT user_id, name, email FROM user WHERE role = 'student'")->fetchAll();
$failCount = 0;

foreach ($users as $user) {
    $ok = sendNewBookNotification(
        $user['email'],
        $user['name'],
        $title,
        $author,
        (string)$cat['name'],
        $user['user_id'] // 新增：把學號傳進去，這樣 mailer.php 就能幫每個人寫入資料庫的 notification 表
    );
    if (!$ok) $failCount++;
}

$msg = "書籍《{$title}》新增成功！";
if ($failCount > 0) {
    $msg .= "（有 {$failCount} 封通知 Email 發送失敗，請確認 SMTP 設定）";
}

jsonResponse(true, $msg);