<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'khohanggiadung');
define('BASE_URL', 'http://localhost/Project_KhoGiaDung/public');
define('APP_ROOT', dirname(dirname(__FILE__)));

function checkPermission($permissionCode) {
    // Nếu chưa start session thì start (phòng hờ)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Admin luôn true
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'VT_ADMIN') {
        return true; 
    }

    if (!isset($_SESSION['user_permissions']) || !is_array($_SESSION['user_permissions'])) {
        return false;
    }

    return in_array($permissionCode, $_SESSION['user_permissions']);
}
?>