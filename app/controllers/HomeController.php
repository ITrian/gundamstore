<?php
class HomeController extends Controller {

    // --- BỔ SUNG ĐOẠN NÀY ---
    public function __construct() {
        // Gọi hàm kiểm tra đăng nhập từ Controller cha
        // Nếu chưa có Session, nó sẽ tự chuyển hướng sang trang Login
        $this->requireLogin();
    }
    // ------------------------

    public function index() {
        // Code lấy số liệu thống kê của bạn ở đây...
        $data = [
            'title' => 'Dashboard',
            'content' => 'Nội dung trang chủ'
        ];
        $this->view('home/index', $data);
    }
}
?>