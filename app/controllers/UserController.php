<?php
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        $this->requireLogin();
        // Chỉ Admin (Q_HETHONG) mới được quản lý người dùng
        $this->requirePermission('Q_HETHONG');
        $this->userModel = $this->model('UserModel');
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        $this->view('users/index', [
            'title' => 'Quản lý Tài khoản',
            'users' => $users
        ]);
    }

    public function create() {
        $roles = $this->userModel->getAllRoles();
        $this->view('users/create', [
            'title' => 'Thêm tài khoản mới',
            'roles' => $roles
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'maND' => trim($_POST['maND']),
                'tenND' => trim($_POST['tenND']),
                'email' => trim($_POST['email']),
                'sdt' => trim($_POST['sdt']),
                'taiKhoan' => trim($_POST['taiKhoan']),
                'matKhau' => password_hash($_POST['matKhau'], PASSWORD_DEFAULT),
                'maVaiTro' => $_POST['maVaiTro']
            ];

            // Validate duplicate ID or Username
            if ($this->userModel->checkIdExists($data['maND'])) {
                die('Mã người dùng đã tồn tại.');
            }
            if ($this->userModel->checkUsernameExists($data['taiKhoan'])) {
                die('Tên đăng nhập đã tồn tại.');
            }

            if ($this->userModel->create($data)) {
                header('Location: ' . BASE_URL . '/user');
            } else {
                die('Lỗi khi tạo người dùng.');
            }
        }
    }

    public function edit($id) {
        $user = $this->userModel->find($id);
        if (!$user) {
            die('Người dùng không tồn tại.');
        }
        $roles = $this->userModel->getAllRoles();
        
        $this->view('users/edit', [
            'title' => 'Cập nhật tài khoản',
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maND']; // hidden field in form
            
            $data = [
                'tenND' => trim($_POST['tenND']),
                'email' => trim($_POST['email']),
                'sdt' => trim($_POST['sdt']),
                'maVaiTro' => $_POST['maVaiTro'],
                'matKhau' => !empty($_POST['matKhau']) ? password_hash($_POST['matKhau'], PASSWORD_DEFAULT) : null
            ];

            if ($this->userModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/user');
            } else {
                die('Lỗi khi cập nhật người dùng.');
            }
        }
    }

    public function delete($id) {
        // Kiểm tra không cho xóa chính mình
        if ($id == $_SESSION['user_id']) {
            die('Bạn không thể xóa tài khoản hiện tại của mình.');
        }
        
        if ($this->userModel->delete($id)) {
            header('Location: ' . BASE_URL . '/user');
        } else {
            die('Lỗi khi xóa người dùng.');
        }
    }

    public function locked() {
        $users = $this->userModel->getLockedUsers();
        $this->view('users/locked', [
            'title' => 'Tài khoản bị khóa',
            'users' => $users
        ]);
    }

    public function restore($id) {
        if ($this->userModel->restore($id)) {
            header('Location: ' . BASE_URL . '/user/locked');
        } else {
            die('Lỗi khi khôi phục tài khoản.');
        }
    }
}
