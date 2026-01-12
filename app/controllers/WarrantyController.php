<?php
class WarrantyController extends Controller {
    private $warrantyModel;

    public function __construct() {
        $this->requireLogin();
        // Chỉ người có quyền Bảo hành (hoặc Admin) mới được vào
        $this->requirePermission('Q_BAOHANH');
        $this->warrantyModel = $this->model('WarrantyModel');
    }

    public function index() {
        $result = null;
        $type = '';
        $message = "";

        if (isset($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
            
            // 1. Tìm Serial trước
            $serialInfo = $this->warrantyModel->findBySerial($keyword);

            if ($serialInfo) {
                $result = $serialInfo;
                $type = 'SINGLE';
            } else {
                // 2. Tìm theo sản phẩm (Lô)
                $batchList = $this->warrantyModel->findByProduct($keyword);
                if (!empty($batchList)) {
                    $result = $batchList;
                    $type = 'LIST';
                } else {
                    $message = "Không tìm thấy dữ liệu khớp!";
                }
            }
        }

        $this->view('warranty/index', [
            'title' => 'Tra cứu bảo hành',
            'result' => $result,
            'type' => $type,
            'message' => $message,
            'keyword' => $_GET['keyword'] ?? ''
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maBH = 'BH' . time();
            
            // Lấy dữ liệu từ form (bao gồm cả maHH vừa thêm)
            $data = [
                'maBH' => $maBH,
                'maHH' => $_POST['maHH'],   // <-- MỚI: Bắt buộc phải có
                'serial' => $_POST['serial'],
                'moTaLoi' => $_POST['moTaLoi'],
                'maND' => $_SESSION['user_id']
            ];

            if ($this->warrantyModel->createTicket($data)) {
                // Chuyển sang trang chi tiết phiếu
                header('Location: ' . BASE_URL . '/warranty/detail/' . $maBH);
                exit;
            } else {
                die("Lỗi: Không thể tạo phiếu bảo hành. Kiểm tra lại dữ liệu đầu vào.");
            }
        }
    }

    // Hàm detail giữ nguyên logic hiển thị
    public function detail($maBH) {
        $ticket = $this->warrantyModel->getTicketDetail($maBH);
        if (!$ticket) die("Không tìm thấy phiếu!");
        $this->view('warranty/detail', ['title' => 'Chi tiết phiếu', 'ticket' => $ticket]);
    }
}
?>