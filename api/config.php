<?php
// =====================================================
// config.php - 資料庫連線設定
// =====================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'dbs_lab_library');  // TODO: 修改為你的資料庫名稱
define('DB_USER', 'root');             // TODO: 修改為你的資料庫帳號
define('DB_PASS', '');                 // TODO: 修改為你的資料庫密碼
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $e->getMessage()]);
            exit;
        }
    }
    return $pdo;
}

// 統一回傳 JSON
function jsonResponse(bool $success, string $message = '', mixed $data = null): void {
    header('Content-Type: application/json; charset=utf-8');
    $resp = ['success' => $success, 'message' => $message];
    if ($data !== null) $resp['data'] = $data;
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    exit;
}

// 產生 UUID (32碼無連字符)
function generateUUID(): string {
    return str_replace('-', '', sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    ));
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;
