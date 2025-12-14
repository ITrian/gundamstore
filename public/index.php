<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));

// Autoload đơn giản
spl_autoload_register(function ($class_name) {
    $paths = [
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/app/models/',
        ROOT_PATH . '/app/config/'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path . $class_name . '.php')) {
            require_once $path . $class_name . '.php';
            return;
        }
    }
});

// Lấy Controller và Action từ URL
$controller = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController';
$action = isset($_GET['a']) ? $_GET['a'] : 'index';

if (class_exists($controller)) {
    $obj = new $controller();
    if (method_exists($obj, $action)) {
        $obj->$action();
    } else {
        echo "Action không tồn tại!";
    }
} else {
    echo "Controller không tồn tại!";
}
?>