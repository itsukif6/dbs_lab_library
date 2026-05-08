<?php
// =====================================================
// auth.php - Session 保護 Helper
// 在需要登入保護的 API 頂部 require 此檔案
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 要求必須登入，否則中斷並回傳 401
 */
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => '請先登入',
            'code'    => 'UNAUTHORIZED',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

/**
 * 要求必須為管理員，否則中斷並回傳 403
 */
function requireAdmin(): void {
    requireLogin();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => '權限不足，此功能僅限管理員',
            'code'    => 'FORBIDDEN',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

/**
 * 取得目前登入的 user_id
 */
function currentUserId(): string {
    return $_SESSION['user_id'] ?? '';
}

/**
 * 取得目前登入的 role
 */
function currentRole(): string {
    return $_SESSION['role'] ?? '';
}

/**
 * 是否為管理員
 */
function isAdmin(): bool {
    return currentRole() === 'admin';
}
