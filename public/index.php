<?php
session_start();

// Load Config
require_once '../app/config/config.php';

// Autoload các file Core
require_once '../app/core/Database.php'; // File này bạn đã có ở bước trước
require_once '../app/core/App.php';
require_once '../app/core/Controller.php'; // Tạo file này ở bước sau

// Khởi chạy App
$app = new App();
?>