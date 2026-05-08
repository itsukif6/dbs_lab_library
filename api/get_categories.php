<?php
// =====================================================
// get_categories.php - 取得所有分類
// =====================================================

require_once 'config.php';

$pdo  = getDB();
$stmt = $pdo->query("SELECT category_id, name FROM category ORDER BY CAST(category_id AS UNSIGNED) ASC");
$cats = $stmt->fetchAll();

jsonResponse(true, '', $cats);
