<?php
define('APP_ROOT', dirname(__FILE__) . '/app');
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

try {
    // Thêm cột maLo và maViTri vào bảng ct_phieuxuat_serial
    $sql = "ALTER TABLE ct_phieuxuat_serial 
            ADD COLUMN maLo VARCHAR(20) DEFAULT NULL,
            ADD COLUMN maViTri VARCHAR(20) DEFAULT NULL;";
    
    $db->exec($sql);
    echo "Đã thêm cột maLo và maViTri vào bảng ct_phieuxuat_serial thành công!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false) {
        echo "Cột đã tồn tại, không cần thêm.";
    } else {
        echo "Lỗi: " . $e->getMessage();
    }
}
?>