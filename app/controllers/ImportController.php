<?php
class ImportController extends Controller {
    private $importModel;

    public function __construct() {
        $this->requireLogin();
        // Cần quyền nhập kho
        $this->requirePermission('Q_NHAP_KHO');
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
            // Không truyền maPN, để model tự sinh đúng định dạng
            $headerData = [
                // 'maPN' => $maPN, // bỏ dòng này
                'maNCC' => $_POST['maNCC'],
                'ghiChu' => $_POST['ghiChu'],
                'maND' => $_SESSION['user_id'], // Lấy ID người đang đăng nhập
                // Mã đơn đặt hàng (nếu tạo phiếu nhập theo đơn đặt hàng)
                'maDH' => isset($_POST['maDH']) && $_POST['maDH'] !== '' ? $_POST['maDH'] : null
            ];

            // 2. Chuẩn bị dữ liệu Detail (Danh sách hàng)
            // Dữ liệu từ form gửi lên dạng mảng: maHH[], soLuong[], donGia[]
            $products = [];
            $count = count($_POST['product_id']);
            
            for ($i = 0; $i < $count; $i++) {
                // Chỉ lấy dòng nào có chọn sản phẩm
                if (!empty($_POST['product_id'][$i])) {
                    // collect serials if present (one per line)
                    $serialsRaw = $_POST['serials'][$i] ?? '';
                    $serials = [];
                    if (!empty(trim($serialsRaw))) {
                        // split by newline or comma
                        $lines = preg_split('/\r\n|\r|\n|,/', $serialsRaw);
                        foreach ($lines as $ln) {
                            $s = trim($ln);
                            if ($s !== '') $serials[] = $s;
                        }
                    }

                    $qty = 0;
                    if (!empty($serials)) {
                        $qty = count($serials);
                    } else {
                        $qty = (int)($_POST['quantity'][$i] ?? 0);
                    }

                    $products[] = [
                        'maHH' => $_POST['product_id'][$i],
                        'soLuong' => $qty,
                        'donGia' => $_POST['price'][$i],
                        'hsd' => $_POST['expiry'][$i] ?? null, // Hạn sử dụng (nếu có)
                        'serials' => $serials
                    ];
                }
            }

            if (empty($products)) {
                die("Vui lòng chọn ít nhất 1 sản phẩm!");
            }

            // 3. Gọi Model xử lý
            try {
                $this->importModel->createImportTransaction($headerData, $products);
                // Thành công -> Chuyển về trang danh sách nhập kho hoặc Tồn kho
                header('Location: ' . BASE_URL . '/inventory'); 
            } catch (Exception $e) {
                // Show clearer error to help debugging / inform user what to fix
                die("Lỗi hệ thống: " . $e->getMessage());
            }
        }
    }

    // Show details of a single import (phiếu nhập)
    public function show($maPN) {
        $data = $this->importModel->getImportById($maPN);
        if (!$data) {
            die('Không tìm thấy phiếu nhập: ' . htmlspecialchars($maPN));
        }
        $viewData = [
            'title' => 'Chi tiết Phiếu Nhập ' . $maPN,
            'import' => $data['header'],
            'lines' => $data['lines']
        ];
        $this->view('import/show', $viewData);
    }
}
?>