<?php
class CategoryController extends Controller {
    private $categoryModel;

    public function __construct() {
        $this->requireLogin();
        $this->categoryModel = $this->model('CategoryModel');
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        $data = [
            'title' => 'Danh sách Danh mục',
            'categories' => $categories
        ];
        $this->view('categories/index', $data);
    }

    public function create() {
        $data = ['title' => 'Thêm Danh mục'];
        $this->view('categories/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'maDanhMuc' => $_POST['maDanhMuc'],
                'tenDanhMuc' => $_POST['tenDanhMuc']
            ];

            // Kiểm tra trùng mã
            $exists = $this->categoryModel->find($data['maDanhMuc']);
            if ($exists) { die('Mã danh mục đã tồn tại!'); }

            if ($this->categoryModel->create($data)) {
                header('Location: ' . BASE_URL . '/category');
            } else {
                die('Lỗi khi tạo danh mục');
            }
        }
    }

    public function edit($id) {
        $category = $this->categoryModel->find($id);
        if (!$category) { die('Không tìm thấy danh mục'); }
        $data = ['title' => 'Cập nhật Danh mục', 'category' => $category];
        $this->view('categories/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maDanhMuc'];
            $data = ['tenDanhMuc' => $_POST['tenDanhMuc']];
            if ($this->categoryModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/category');
            } else {
                die('Lỗi cập nhật danh mục');
            }
        }
    }

    public function delete($id) {
        // Nếu có hàng hóa liên kết, không cho xóa
        if ($this->categoryModel->hasProducts($id)) {
            // redirect back with error message
            header('Location: ' . BASE_URL . '/category?error=has_products');
            return;
        }

        if ($this->categoryModel->delete($id)) {
            header('Location: ' . BASE_URL . '/category');
        } else {
            die('Lỗi xóa danh mục');
        }
    }
}
?>
