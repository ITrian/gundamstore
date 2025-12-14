<?php
// app/core/Controller.php
class Controller {
    // Hàm gọi Model
    public function model($model) {
        require_once APP_ROOT . '/models/' . $model . '.php';
        return new $model();
    }

    // Hàm gọi View
    public function view($view, $data = []) {
        // Tách mảng data thành các biến riêng lẻ
        extract($data);
        
        // Kiểm tra file view có tồn tại không
        if (file_exists(APP_ROOT . '/views/' . $view . '.php')) {
            require_once APP_ROOT . '/views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }
}
?>