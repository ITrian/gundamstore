<?php
class ProductController extends Controller {
    private $productModel;

    public function __construct() {
        $this->requireLogin();
        $this->productModel = $this->model('ProductModel');
    }

    // Trang danh sách sản phẩm
    public function index() {
        $products = $this->productModel->getAll();
        
        $data = [
            'title' => 'Danh sách Hàng hóa',
            'products' => $products
        ];
        
        $this->view('products/index', $data);
    }

    public function create() {
        // 1. Lấy dữ liệu phụ trợ cho các ô Select box
        // Lưu ý: Bạn cần viết thêm các hàm này trong ProductModel hoặc tạo CategoryModel/UnitModel riêng
        // Ở đây mình ví dụ gọi trực tiếp query đơn giản hoặc giả định Model đã có hàm
        $db = Database::getInstance()->getConnection();

        // Lấy danh mục
        $stmt = $db->query("SELECT * FROM DanhMuc");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy đơn vị tính
        $stmt = $db->query("SELECT * FROM DONVITINH");
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy NCC
        $stmt = $db->query("SELECT * FROM NHACUNGCAP");
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Thêm hàng hóa mới',
            'categories' => $categories,
            'units' => $units,
            'suppliers' => $suppliers
        ];

        $this->view('products/create', $data);
    }

    public function edit($id) {
        $db = Database::getInstance()->getConnection();

        // Lấy danh mục
        $stmt = $db->query("SELECT * FROM DanhMuc");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy đơn vị tính
        $stmt = $db->query("SELECT * FROM DONVITINH");
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy NCC
        $stmt = $db->query("SELECT * FROM NHACUNGCAP");
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $product = $this->productModel->find($id);
        if (!$product) { die('Không tìm thấy sản phẩm'); }

        $data = [
            'title' => 'Cập nhật Hàng hóa',
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
            'suppliers' => $suppliers
        ];

        $this->view('products/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maHH'];
            $data = [
                'tenHH' => $_POST['tenHH'],
                'loaiHang' => $_POST['loaiHang'] ?? 'LO',
                'heSoChiemCho' => $_POST['heSoChiemCho'] ?? 1,
                'maDanhMuc' => $_POST['maDanhMuc'],
                'maNCC' => $_POST['maNCC'],
                'maDVT' => $_POST['maDVT'],
                'model' => $_POST['model'],
                'thuongHieu' => $_POST['thuongHieu'],
                'moTa' => $_POST['moTa']
            ];

            if ($this->productModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/product');
            } else {
                die('Lỗi cập nhật sản phẩm');
            }
        }
    }

    public function delete($id) {
        // Try to delete product; model will prevent deleting if there are lots
        if ($this->productModel->delete($id)) {
            header('Location: ' . BASE_URL . '/product');
        } else {
            // If delete failed due to dependencies, redirect with error
            header('Location: ' . BASE_URL . '/product?error=has_lots');
        }
    }

    // Xử lý lưu sản phẩm vào CSDL
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $data = [
                'maHH' => $_POST['maHH'],
                'tenHH' => $_POST['tenHH'],
                'loaiHang' => $_POST['loaiHang'],
                'heSoChiemCho' => $_POST['heSoChiemCho'] ?? 1,
                'maDanhMuc' => $_POST['maDanhMuc'],
                'maNCC' => $_POST['maNCC'],
                'maDVT' => $_POST['maDVT'],
                'model' => $_POST['model'],
                'thuongHieu' => $_POST['thuongHieu'],
                'moTa' => $_POST['moTa']
            ];

            // Gọi Model để insert
            if ($this->productModel->create($data)) {
                // Thành công -> Về trang danh sách
                header('Location: ' . BASE_URL . '/product');
            } else {
                // Thất bại -> Báo lỗi (Tạm thời die ra màn hình)
                die("Lỗi: Mã hàng đã tồn tại hoặc dữ liệu không hợp lệ.");
            }
        }
    }
}
?>