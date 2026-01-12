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
    // Hàm bắt buộc đăng nhập (Middleware)
    // Các Controller con sẽ gọi hàm này ở đầu mỗi function
    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    // Hàm kiểm tra quyền, nếu không có thì chặn
    protected function requirePermission($permissionCode) {
        $this->requireLogin();
        // Gọi hàm global checkPermission từ config
        if (!checkPermission($permissionCode)) {
            die("Bạn không có quyền truy cập chức năng này!"); 
            // Hoặc redirect về trang chủ: header('Location: ' . BASE_URL . '/home'); exit;
        }
    }
}
?>