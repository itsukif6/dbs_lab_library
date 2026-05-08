<?php
// =====================================================
// get_users.php - 取得使用者清單 (借書時選擇用)
// =====================================================

require_once 'config.php';

$pdo  = getDB();
$stmt = $pdo->query("SELECT user_id, name, email FROM user ORDER BY name ASC");
$users = $stmt->fetchAll();

jsonResponse(true, '', $users);
