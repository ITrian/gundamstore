<?php
session_start();
// ROOT_PATH sẽ là đường dẫn đến thư mục cha của public (tức là folder Project_KhoGiaDung)
define('ROOT_PATH', dirname(__DIR__));

if (file_exists(ROOT_PATH . '/app/config/config.php')) {
    require_once ROOT_PATH . '/app/config/config.php';
} else {
    die("Lỗi: Không tìm thấy file cấu hình (app/config/config.php)");
}

require_once ROOT_PATH . '/app/core/Database.php';   // Kết nối CSDL
require_once ROOT_PATH . '/app/core/Controller.php'; // Class cha của các Controller
require_once ROOT_PATH . '/app/core/App.php';        // Xử lý URL (Routing)


spl_autoload_register(function ($className) {
    $modelPath = ROOT_PATH . '/app/models/' . $className . '.php';
    if (file_exists($modelPath)) {
        require_once $modelPath;
    }
});


$app = new App();
?>