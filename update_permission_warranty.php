<?php
define('APP_ROOT', dirname(__FILE__) . '/app');
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

try {
    // 1. Thêm quyền Q_BAOHANH nếu chưa có
    $sql1 = "INSERT IGNORE INTO quyen (maQuyen, tenQuyen, moTa) VALUES ('Q_BAOHANH', 'Quản lý bảo hành', 'Quyền tiếp nhận và xử lý bảo hành');";
    $db->exec($sql1);
    
    // 2. Cấp quyền Q_BAOHANH cho VT_ADMIN và VT_KHO
    $sql2 = "INSERT IGNORE INTO quyen_vaitro (maVaiTro, maQuyen) VALUES ('VT_ADMIN', 'Q_BAOHANH');";
    $sql3 = "INSERT IGNORE INTO quyen_vaitro (maVaiTro, maQuyen) VALUES ('VT_KHO', 'Q_BAOHANH');";
    $db->exec($sql2);
    $db->exec($sql3);

    echo "Cập nhật quyền Bảo hành thành công!";

} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>