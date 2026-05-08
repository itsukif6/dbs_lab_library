<?php
// =====================================================
// admin_get_users.php - 取得所有使用者（管理員用）
// GET: search (optional)
// 🔒 管理員專用
// =====================================================

require_once 'config.php';
require_once 'auth.php';

requireAdmin();

$pdo    = getDB();
$search = trim($_GET['search'] ?? '');

$sql    = "SELECT user_id, name, email, role FROM user WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (user_id LIKE :s1 OR name LIKE :s2 OR email LIKE :s3)";
    $like = "%{$search}%";
    $params[':s1'] = $like;
    $params[':s2'] = $like;
    $params[':s3'] = $like;
}

$sql .= " ORDER BY role DESC, name ASC"; // admin 排前面

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

// 補上每個使用者目前的借閱數
foreach ($users as &$u) {
    $bStmt = $pdo->prepare(
        "SELECT COUNT(*) AS cnt FROM borrow WHERE user_id = ? AND return_date IS NULL"
    );
    $bStmt->execute([$u['user_id']]);
    $u['borrow_count'] = (int)$bStmt->fetchColumn();
}

jsonResponse(true, '', $users);
