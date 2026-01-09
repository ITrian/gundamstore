<?php
class WarrantyController extends Controller {
    private $warrantyModel;

    public function __construct() {
        $this->requireLogin();
        $this->warrantyModel = $this->model('WarrantyModel');
    }

    public function index() {
        $result = null;     // Kết quả tìm kiếm
        $type = '';         // Kiểu kết quả: 'SINGLE' (1 cái) hoặc 'LIST' (nhiều lô)
        $message = "";

        if (isset($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);

            // 1. Thử tìm theo Serial trước
            $serialInfo = $this->warrantyModel->findBySerial($keyword);

            if ($serialInfo) {
                // Tìm thấy Serial -> Đây là hàng Serial
                $result = $serialInfo;
                $type = 'SINGLE';
            } else {
                // 2. Không thấy Serial -> Tìm theo Tên/Mã hàng (Hàng Lô)
                $batchList = $this->warrantyModel->findByProduct($keyword);
                
                if (!empty($batchList)) {
                    $result = $batchList;
                    $type = 'LIST';
                } else {
                    $message = "Không tìm thấy Serial hoặc Sản phẩm nào khớp!";
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

    // Xử lý tạo phiếu (Giữ nguyên, nhưng lưu ý serial có thể là mã giả định)
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maBH = 'BH' . time(); 
            // ... (Code lấy dữ liệu giữ nguyên) ...
            $serial = $_POST['serial'];
            
            $data = [
                'maBH' => $maBH,
                'serial' => $serial,
                'moTaLoi' => $_POST['moTaLoi'],
                'maND' => $_SESSION['user_id']
            ];

            if ($this->warrantyModel->createTicket($data)) {
                // THAY ĐỔI DÒNG NÀY: Chuyển sang trang chi tiết
                header('Location: ' . BASE_URL . '/warranty/detail/' . $maBH);
                exit;
            }
        }
    }

    // 2. THÊM HÀM DETAIL: Hiển thị phiếu
    public function detail($maBH) {
        // Lấy thông tin phiếu từ Model
        $ticket = $this->warrantyModel->getTicketDetail($maBH);

        if (!$ticket) {
            die("Không tìm thấy phiếu bảo hành này!");
        }

        $this->view('warranty/detail', [
            'title' => 'Chi tiết phiếu bảo hành',
            'ticket' => $ticket
        ]);
    }
}
?>