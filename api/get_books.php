<?php
// =====================================================
// get_books.php - 查詢書籍清單 (支援搜尋與篩選)
// GET params: search, category_id, status
// =====================================================

require_once 'config.php';

$pdo = getDB();

$search      = trim($_GET['search']      ?? '');
$category_id = trim($_GET['category_id'] ?? '');
$status      = $_GET['status'] ?? '';

$sql = "
    SELECT
        b.book_id,
        b.title,
        b.author,
        b.keyword,
        b.status,
        c.category_id,
        c.name AS category_name,
        -- 取得目前借閱者資訊 (status=1 且尚未還書)
        u.user_id   AS borrower_id,
        u.name      AS borrower_name,
        u.email     AS borrower_email,
        br.borrow_id,
        br.borrow_date,
        br.due_date
    FROM book b
    JOIN category c ON b.category_id = c.category_id
    LEFT JOIN borrow br ON b.book_id = br.book_id
        AND br.return_date IS NULL
    LEFT JOIN user u ON br.user_id = u.user_id
    WHERE 1=1
";

$params = [];

if ($search !== '') {
    $sql .= " AND (b.title LIKE :s1 OR b.author LIKE :s2 OR b.keyword LIKE :s3 OR b.book_id LIKE :s4)";
    $like = "%{$search}%";
    $params[':s1'] = $like;
    $params[':s2'] = $like;
    $params[':s3'] = $like;
    $params[':s4'] = $like;
}

if ($category_id !== '') {
    $sql .= " AND b.category_id = :cat";
    $params[':cat'] = $category_id;
}

if ($status !== '') {
    $sql .= " AND b.status = :st";
    $params[':st'] = (int)$status;
}

$sql .= " ORDER BY b.title ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

jsonResponse(true, '', $books);
