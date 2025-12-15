<?php
class ImportController extends Controller {
    private $importModel;

    public function __construct() {
        $this->requireLogin();
        $this->importModel = $this->model('ImportModel');
    }
    // --- THÊM HÀM INDEX VÀO ĐẦU CLASS (Sau __construct) ---
    
    public function index() {
        // Lấy danh sách phiếu nhập
        $imports = $this->importModel->getAllImports();

        $data = [
            'title' => 'Lịch sử Nhập kho',
            'imports' => $imports
        ];

        // Gọi View danh sách
        $this->view('import/index', $data);
    }

    // Hiển thị form tạo phiếu
    public function create() {
        $data = [
            'title' => 'Tạo Phiếu Nhập Kho',
            'suppliers' => $this->importModel->getSuppliers(),
            'products' => $this->importModel->getProducts()
        ];
        $this->view('import/create', $data);
    }

    // Xử lý lưu dữ liệu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Chuẩn bị dữ liệu Header (Phiếu nhập)
            // Tự sinh mã phiếu: PN + Timestamp (để không trùng)
            $maPN = 'PN' . time(); 
            
            $headerData = [
                'maPN' => $maPN,
                'maNCC' => $_POST['maNCC'],
                'ghiChu' => $_POST['ghiChu'],
                'maND' => $_SESSION['user_id'] // Lấy ID người đang đăng nhập
            ];

            // 2. Chuẩn bị dữ liệu Detail (Danh sách hàng)
            // Dữ liệu từ form gửi lên dạng mảng: maHH[], soLuong[], donGia[]
            $products = [];
            $count = count($_POST['product_id']);
            
            for ($i = 0; $i < $count; $i++) {
                // Chỉ lấy dòng nào có chọn sản phẩm
                if (!empty($_POST['product_id'][$i])) {
                    $products[] = [
                        'maHH' => $_POST['product_id'][$i],
                        'soLuong' => $_POST['quantity'][$i],
                        'donGia' => $_POST['price'][$i],
                        'hsd' => $_POST['expiry'][$i] // Hạn sử dụng (nếu có)
                    ];
                }
            }

            if (empty($products)) {
                die("Vui lòng chọn ít nhất 1 sản phẩm!");
            }

            // 3. Gọi Model xử lý
            if ($this->importModel->createImportTransaction($headerData, $products)) {
                // Thành công -> Chuyển về trang danh sách nhập kho hoặc Tồn kho
                header('Location: ' . BASE_URL . '/inventory'); 
            } else {
                die("Lỗi hệ thống: Không thể tạo phiếu nhập. Vui lòng thử lại.");
            }
        }
    }
}
?>