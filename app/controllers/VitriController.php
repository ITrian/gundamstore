<?php
class VitriController extends Controller {
    private $vitriModel;

    public function __construct() {
        $this->requireLogin();
        $this->vitriModel = $this->model('VitriModel');
    }

    public function index() {
        $rows = $this->vitriModel->getAll();
        $data = ['title' => 'Quản lý Vị trí', 'rows' => $rows];
        $this->view('vitri/index', $data);
    }

    public function create() {
        $data = ['title' => 'Thêm Vị trí mới'];
        $this->view('vitri/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $day = trim($_POST['day']);
            $ke = trim($_POST['ke']);
            $o = trim($_POST['o']);
            $ma = strtoupper(preg_replace('/\s+/', '', $day)) . '-' . strtoupper(preg_replace('/\s+/', '', $ke)) . '-' . strtoupper(preg_replace('/\s+/', '', $o));
            if ($this->vitriModel->exists($ma)) {
                die('Vị trí đã tồn tại');
            }
            $data = ['day' => $day, 'ke' => $ke, 'o' => $o];
            if ($this->vitriModel->create($data)) {
                header('Location: ' . BASE_URL . '/vitri');
            } else {
                die('Lỗi khi tạo vị trí');
            }
        }
    }

    public function edit($id) {
        $row = $this->vitriModel->find($id);
        if (!$row) die('Không tìm thấy vị trí');
        $data = ['title' => 'Sửa Vị trí', 'row' => $row];
        $this->view('vitri/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maViTri'];
            $data = ['day' => trim($_POST['day']), 'ke' => trim($_POST['ke']), 'o' => trim($_POST['o'])];
            if ($this->vitriModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/vitri');
            } else {
                die('Lỗi cập nhật vị trí');
            }
        }
    }

    public function delete($id) {
        if ($this->vitriModel->delete($id)) {
            header('Location: ' . BASE_URL . '/vitri');
        } else {
            die('Lỗi xóa vị trí');
        }
    }
}
