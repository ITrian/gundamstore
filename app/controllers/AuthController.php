<?php
class AuthController extends Controller {

    public function index() {
        // Nếu người dùng vào /auth thì tự chuyển sang /auth/login
        $this->login(); 
    }

    // Hiển thị form login hoặc xử lý submit
    public function login() {
        // Nếu đã đăng nhập rồi thì đá về trang chủ
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $data = []; // Chứa thông báo lỗi nếu có

        // Kiểm tra xem người dùng có nhấn nút Submit không (Method POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (empty($username) || empty($password)) {
                $data['error'] = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
            } else {
                // Gọi Model để tìm user
                $userModel = $this->model('UserModel');
                $user = $userModel->getByUsername($username);
                // Kiểm tra mật khẩu (Sử dụng password_verify để so sánh hash)
                if ($user && password_verify($password, $user['matKhau'])) {
                    // Đăng nhập thành công -> Lưu Session
                    $_SESSION['user_id'] = $user['maND'];
                    $_SESSION['user_name'] = $user['tenND'];
                    $_SESSION['user_role'] = $user['maVaiTro'];
                    $_SESSION['role_name'] = $user['tenVaiTro'];

                    // Chuyển hướng vào trang Dashboard
                    header('Location: ' . BASE_URL . '/home/index');
                    exit;
                } else {
                    $data['error'] = "Tài khoản hoặc mật khẩu không chính xác!";
                }
            }
        }

        // Load view login
        $this->view('auth/login', $data);
    }

    // Đăng xuất
public function logout() {
        // 1. Khởi động session nếu chưa có (để tìm được mà hủy)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Xóa sạch các biến trong Session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['role_name']);

        // 3. Hủy hoàn toàn phiên làm việc
        session_destroy();

        // 4. Chuyển hướng về trang đăng nhập
        header('Location: ' . BASE_URL . '/auth/login');
        
        // 5. Kết thúc code ngay lập tức (Quan trọng)
        exit();
    }
}
?>