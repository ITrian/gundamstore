<?php
class PartnerController extends Controller {
    private $partnerModel;

    public function __construct() {
        $this->requireLogin();
        // Chỉ Quản lý hàng (hoặc Admin) mới được quản lý đối tác
        $this->requirePermission('Q_QL_HANG');
        $this->partnerModel = $this->model('PartnerModel');
    }

    public function index() { $this->supplier(); }

    // --- QUẢN LÝ DANH SÁCH ---
    public function supplier() {
        $this->view('partners/index', [
            'title' => 'Quản lý Nhà cung cấp', 'type' => 'supplier',
            'list' => $this->partnerModel->getSuppliers()
        ]);
    }

    public function customer() {
        $this->view('partners/index', [
            'title' => 'Quản lý Khách hàng', 'type' => 'customer',
            'list' => $this->partnerModel->getCustomers()
        ]);
    }

    // --- QUẢN LÝ THÊM MỚI (GIAO DIỆN) ---
    public function create_supplier() {
        $this->view('partners/create', ['title' => 'Thêm NCC', 'type' => 'supplier']);
    }

    public function create_customer() {
        $this->view('partners/create', ['title' => 'Thêm Khách hàng', 'type' => 'customer']);
    }

    // --- XỬ LÝ LƯU (FORM SUBMIT) ---
    public function store_supplier() { $this->store_partner('supplier'); }
    public function store_customer() { $this->store_partner('customer'); }

    private function store_partner($type) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($type == 'supplier') {
                // Sinh mã NCC: NCC001
                $max = $this->partnerModel->getMaxCode('NHACUNGCAP', 'maNCC', 'NCC');
                $newCode = 'NCC' . str_pad($max + 1, 3, '0', STR_PAD_LEFT);
                
                $this->partnerModel->addSupplier([
                    'maNCC' => $newCode,
                    'tenNCC' => $_POST['name'],
                    'diaChi' => $_POST['address'],
                    'sdt' => $_POST['phone'],
                    'email' => $_POST['email']
                ]);
            } else {
                // Sinh mã KH: KH00001
                $max = $this->partnerModel->getMaxCode('KHACHHANG', 'maKH', 'KH');
                $newCode = 'KH' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);

                $this->partnerModel->addCustomer([
                    'maKH' => $newCode,
                    'tenKH' => $_POST['name'],
                    'diaChi' => $_POST['address'],
                    'sdt' => $_POST['phone'],
                    'email' => $_POST['email']
                ]);
            }
            header('Location: ' . BASE_URL . '/partner/' . $type);
        }
    }

    // --- QUẢN LÝ SỬA ---
    public function edit($type, $code) {
        $table = ($type == 'supplier') ? 'NHACUNGCAP' : 'KHACHHANG';
        $col = ($type == 'supplier') ? 'maNCC' : 'maKH';
        $partner = $this->partnerModel->getPartnerByCode($table, $col, $code);
        $this->view('partners/edit', ['title' => 'Cập nhật', 'type' => $type, 'partner' => $partner]);
    }

    public function update($type) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $table = ($type == 'supplier') ? 'NHACUNGCAP' : 'KHACHHANG';
            $pkCol = ($type == 'supplier') ? 'NCC' : 'KH'; // Model dùng flag này
            
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

    // --- QUẢN LÝ XÓA & KHÔI PHỤC ---
    public function delete($type, $code) {
        $this->partnerModel->deleteOrLock($type, $code);
        header('Location: ' . BASE_URL . '/partner/' . $type);
    }

    public function inactive() {
        $this->view('partners/inactive', [
            'title' => 'Thùng rác đối tác',
            'inactive_suppliers' => $this->partnerModel->getInactivePartners('supplier'),
            'inactive_customers' => $this->partnerModel->getInactivePartners('customer')
        ]);
    }

    public function restore($type, $code) {
        if ($this->partnerModel->restorePartner($type, $code)) {
            header('Location: ' . BASE_URL . '/partner/inactive');
        } else {
            die("Lỗi khôi phục!");
        }
    }

    // --- [HÀM ĐÃ SỬA LẠI]: THÊM NHANH AJAX ---
    public function quickAdd() {
        // 1. Chỉ nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); return;
        }

        // 2. Lấy dữ liệu
        $tenKH = trim($_POST['tenKH'] ?? '');
        $sdt   = trim($_POST['sdt'] ?? '');
        $diaChi = trim($_POST['diaChi'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($tenKH) || empty($sdt)) {
            echo json_encode(['success' => false, 'message' => 'Tên và SĐT là bắt buộc']);
            return;
        }

        try {
            // --- SỬA LẠI ĐOẠN NÀY (Xóa dòng dùng time()) ---
            
            // Cũ (Sai): $maKH = 'KH' . time(); 
            
            // Mới (Đúng): Tìm mã lớn nhất trong DB rồi cộng 1
            $max = $this->partnerModel->getMaxCode('KHACHHANG', 'maKH', 'KH');
            $maKH = 'KH' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
            
            // -----------------------------------------------

            // 4. Tạo mảng dữ liệu
            $insertData = [
                'maKH' => $maKH,
                'tenKH' => $tenKH,
                'diaChi' => $diaChi,
                'sdt' => $sdt,
                'email' => $email
            ];

            // 5. Gọi Model thêm vào DB
            if ($this->partnerModel->addCustomer($insertData)) {
                echo json_encode([
                    'success' => true,
                    'data' => $insertData // Trả về dữ liệu để JS tự điền
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi DB: Không thể thêm khách.']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
}
?>