<?php
// api/mail_config.php

// SMTP 伺服器設定
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls'); // 或者使用 'ssl' (Port 465)

// 寄件者帳號資訊
define('MAIL_USERNAME', 'chatbotnsysucse@gmail.com');      // TODO: 填入你的 Gmail
define('MAIL_PASSWORD', 'tjnv bzpn ktyk dofa');   // TODO: 填入 16 位數應用程式密碼
define('MAIL_FROM_NAME', '圖書館系統');          // 寄件者顯示名稱

// 字體設定
define('MAIL_CHARSET', 'UTF-8');
?>