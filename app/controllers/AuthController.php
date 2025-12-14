<?php
class AuthController extends Controller {

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
        session_destroy(); // Hủy toàn bộ session
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
?>