<?php
class PartnerController extends Controller {
    private $partnerModel;

    public function __construct() {
        $this->requireLogin();
        $this->partnerModel = $this->model('PartnerModel');
    }

    public function index() {
        $this->supplier();
    }

    // --- 1. QUẢN LÝ DANH SÁCH ---
    public function supplier() {
        $data = [
            'title' => 'Quản lý Nhà cung cấp',
            'type' => 'supplier',
            'list' => $this->partnerModel->getSuppliers()
        ];
        $this->view('partners/index', $data);
    }

    public function customer() {
        $data = [
            'title' => 'Quản lý Khách hàng',
            'type' => 'customer',
            'list' => $this->partnerModel->getCustomers()
        ];
        $this->view('partners/index', $data);
    }

    // --- 2. QUẢN LÝ THÊM MỚI ---
    public function create_supplier() {
        $data = ['title' => 'Thêm NCC', 'type' => 'supplier'];
        $this->view('partners/create', $data);
    }

    public function create_customer() {
        $data = ['title' => 'Thêm Khách hàng', 'type' => 'customer'];
        $this->view('partners/create', $data);
    }

    public function store_supplier() {
        $this->store_partner('supplier');
    }

    public function store_customer() {
        $this->store_partner('customer');
    }

    // Hàm chung xử lý lưu (để đỡ viết lặp code)
    private function store_partner($type) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sinh mã tự động
            if ($type == 'supplier') {
                $table = 'NHACUNGCAP';
                $col = 'maNCC';
                // Lấy mã lớn nhất hiện tại
                $max = $this->partnerModel->getMaxCode($table, $col, 'NCC');
                $newCode = 'NCC' . str_pad($max + 1, 3, '0', STR_PAD_LEFT);
                $insertData = [
                    'maNCC' => $newCode,
                    'tenNCC' => $_POST['name'],
                    'diaChi' => $_POST['address'],
                    'sdt' => $_POST['phone'],
                    'email' => $_POST['email']
                ];
                $this->partnerModel->addSupplier($insertData);
            } else {
                $table = 'KHACHHANG';
                $col = 'maKH';
                $max = $this->partnerModel->getMaxCode($table, $col, 'KH');
                $newCode = 'KH' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
                $insertData = [
                    'maKH' => $newCode,
                    'tenKH' => $_POST['name'],
                    'diaChi' => $_POST['address'],
                    'sdt' => $_POST['phone'],
                    'email' => $_POST['email']
                ];
                $this->partnerModel->addCustomer($insertData);
            }
            header('Location: ' . BASE_URL . '/partner/' . $type);
        }
    }

    // --- 3. QUẢN LÝ SỬA (EDIT) ---
    public function edit($type, $code) {
        $table = ($type == 'supplier') ? 'NHACUNGCAP' : 'KHACHHANG';
        $col = ($type == 'supplier') ? 'maNCC' : 'maKH';
        
        $partner = $this->partnerModel->getPartnerByCode($table, $col, $code);
        
        $data = [
            'title' => 'Cập nhật thông tin',
            'type' => $type,
            'partner' => $partner
        ];
        $this->view('partners/edit', $data);
    }

    public function update($type) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $table = ($type == 'supplier') ? 'NHACUNGCAP' : 'KHACHHANG';
            $pkCol = ($type == 'supplier') ? 'NCC' : 'KH';

            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'address' => $_POST['address']
            ];

            if ($this->partnerModel->updatePartner($table, $pkCol, $data)) {
                header('Location: ' . BASE_URL . '/partner/' . $type);
            } else {
                die("Lỗi cập nhật!");
            }
        }
    }

    // --- 4. QUẢN LÝ XÓA (DELETE) ---
    public function delete($type, $code) {
        $this->partnerModel->deleteOrLock($type, $code);
        header('Location: ' . BASE_URL . '/partner/' . $type);
    }

    public function inactive() {
        // 1. Lấy danh sách đã xóa từ Model
        $inactiveSuppliers = $this->partnerModel->getInactivePartners('supplier');
        $inactiveCustomers = $this->partnerModel->getInactivePartners('customer');

        $data = [
            'title' => 'Thùng rác đối tác',
            'inactive_suppliers' => $inactiveSuppliers,
            'inactive_customers' => $inactiveCustomers
        ];
        
        // 2. Gọi View hiển thị
        $this->view('partners/inactive', $data);
    }
    
    // --- THÊM HÀM KHÔI PHỤC (RESTORE) LUÔN ---
    public function restore($type, $code) {
        // Gọi hàm khôi phục trong Model (Bạn cần thêm hàm restorePartner vào Model như bài trước)
        if ($this->partnerModel->restorePartner($type, $code)) {
            header('Location: ' . BASE_URL . '/partner/inactive');
        } else {
            die("Lỗi khôi phục!");
        }
    }

    // Trong file: controllers/PartnerController.php

public function quickAdd() {
    // 1. Nhận dữ liệu
    $tenKH = $_POST['tenKH'] ?? '';
    $sdt   = $_POST['sdt'] ?? '';
    $diaChi = $_POST['diaChi'] ?? '';
    $email = $_POST['email'] ?? '';

    // 2. Validate đơn giản
    if (empty($tenKH) || empty($sdt)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên và SĐT']);
        return;
    }

    // 3. Sinh mã khách hàng tự động (Ví dụ: KH + timestamp)
    $maKH = 'KH' . time(); 

    // 4. Gọi Model để lưu vào Database
    // $this->partnerModel->addCustomer($maKH, $tenKH, $sdt, $diaChi);
    // Giả lập code lưu DB (Bạn thay bằng code thật của bạn):
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO khachhang (maKH, tenKH, sdt, diaChi, email, trangThai) VALUES (?, ?, ?, ?, ?, 1)");
    try {
        $stmt->execute([$maKH, $tenKH, $sdt, $diaChi, $email]);
        // 5. TRẢ VỀ JSON THÀNH CÔNG (Quan trọng để JS tự động chọn)
        echo json_encode([
            'success' => true,
            'data' => [
                'maKH' => $maKH,
                'tenKH' => $tenKH,
                'sdt' => $sdt,
                'diaChi' => $diaChi,
                'email' => $email
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
}
}
?>