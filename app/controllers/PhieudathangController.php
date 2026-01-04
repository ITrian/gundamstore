<?php
class PhieudathangController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->requireLogin();
        $this->orderModel = $this->model('PhieuDatHangModel');
    }

    // danh sách đơn đặt hàng
    public function index() {
        $orders = $this->orderModel->getAll();
        $data = [
            'title' => 'Đơn đặt hàng',
            'orders' => $orders
        ];
        $this->view('phieudathang/index', $data);
    }

    // form tạo đơn
    public function create() {
        $suppliers = $this->orderModel->getSuppliers();
        $products = $this->orderModel->getProducts();
        $data = [
            'title' => 'Tạo đơn đặt hàng',
            'suppliers' => $suppliers,
            'products' => $products
        ];
        $this->view('phieudathang/create', $data);
    }

    // lưu đơn
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASE_URL . '/phieudathang');
            return;
        }

        $maNCC = $_POST['maNCC'] ?? '';
        $products = $_POST['product'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $prices = $_POST['price'] ?? [];

        if (empty($maNCC) || empty($products)) {
            die('Dữ liệu không hợp lệ.');
        }

        // sinh mã đơn đơn giản
        $maDH = 'PD' . time();
        $ngayDatHang = date('Y-m-d H:i:s');
        $maND = $_SESSION['user_id'] ?? null;

        $lines = [];
        for ($i = 0; $i < count($products); $i++) {
            $p = trim($products[$i]);
            $q = (int)($qtys[$i] ?? 0);
            $pr = (float)($prices[$i] ?? 0);
            if ($p === '' || $q <= 0) continue;
            $lines[] = ['maHH' => $p, 'soLuong' => $q, 'donGia' => $pr];
        }

        if (empty($lines)) { die('Chưa có sản phẩm hợp lệ.'); }

        $header = ['maDH' => $maDH, 'ngayDatHang' => $ngayDatHang, 'maNCC' => $maNCC, 'trangThai' => 0, 'maND' => $maND];

        if ($this->orderModel->create($header, $lines)) {
            header('Location: ' . BASE_URL . '/phieudathang');
            return;
        } else {
            die('Lỗi khi tạo đơn đặt hàng.');
        }
    }

    // xem chi tiết
    public function show($maDH) {
        $order = $this->orderModel->getById($maDH);
        if (!$order) { die('Không tìm thấy đơn hàng'); }
        $data = ['title' => 'Chi tiết đơn đặt hàng', 'order' => $order];
        $this->view('phieudathang/show', $data);
    }
}
?>
