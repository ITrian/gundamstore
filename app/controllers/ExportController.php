<?php
class ExportController extends Controller {
    private $exportModel;

    public function __construct() {
        $this->requireLogin();
        $this->exportModel = $this->model('ExportModel');
    }

    public function index() {
        // Lấy dữ liệu thật
        $orders = $this->exportModel->getAllOrders();

        $data = [
            'title' => 'Quản lý Xuất kho', 
            'orders' => $orders
        ];
        
        $this->view('export/index', $data);
    }

    // Hiển thị chi tiết một phiếu xuất
    public function show($maPX) {
        $export = $this->exportModel->getExportById($maPX);
        if (!$export) {
            die('Không tìm thấy phiếu xuất');
        }

        $lines = $this->exportModel->getExportLines($maPX);

        $data = [
            'title' => 'Chi tiết Phiếu Xuất',
            'export' => $export,
            'lines' => $lines
        ];

        $this->view('export/show', $data);
    }
    
    // Giữ nguyên hàm create của bạn
    public function create() {
        $partnerModel = $this->model('PartnerModel');
        $productModel = $this->model('ProductModel');
        $vitriModel = $this->model('VitriModel');

        $data = [
            'title' => 'Tạo phiếu xuất kho',
            'customers' => $partnerModel->getCustomers(),
            'products' => $productModel->getAll(),
            'vitri' => $vitriModel->getAll()
        ];
        $this->view('export/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/export');
            return;
        }

        $db = Database::getInstance()->getConnection();

    // Lấy dữ liệu từ form (UI đã chọn Auto FIFO: không cần nhận maLo/vitri/allocations)
        $maKH = $_POST['maKH'] ?? null;
        $productIds = $_POST['product_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $prices = $_POST['price'] ?? [];
        $serialsArr = $_POST['serials'] ?? [];

        // Basic validation
        if (!$maKH || empty($productIds)) {
            die('Dữ liệu không hợp lệ: thiếu khách hàng hoặc hàng hóa');
        }

        try {
            $db->beginTransaction();

            // Sinh mã phiếu xuất đơn giản: PX + timestamp
            $maPX = 'PX' . time();
            // Đảm bảo không trùng (nếu trùng, append random)
            $stmtChk = $db->prepare("SELECT COUNT(*) FROM phieuxuat WHERE maPX = :maPX");
            $stmtChk->execute([':maPX' => $maPX]);
            if ($stmtChk->fetchColumn() > 0) {
                $maPX = 'PX' . time() . rand(100,999);
            }

            $maND = $_SESSION['user_id'] ?? null;

            // Insert header phieuxuat
            $stmt = $db->prepare("INSERT INTO phieuxuat (maPX, ngayXuat, maKH, ghiChu, maNDXuat) VALUES (:maPX, NOW(), :maKH, :ghiChu, :maND)");
            $stmt->execute([
                ':maPX' => $maPX,
                ':maKH' => $maKH,
                ':ghiChu' => '',
                ':maND' => $maND
            ]);

            // For each line, validate and insert ct_phieuxuat, and auto-allocate stock FIFO
            $lineCount = count($productIds);
            for ($i = 0; $i < $lineCount; $i++) {
                $maHH = $productIds[$i];
                $qty = isset($quantities[$i]) ? (int)$quantities[$i] : 0;
                $price = isset($prices[$i]) ? (float)$prices[$i] : 0.0;
                $serialJson = $serialsArr[$i] ?? '';

                if ($qty <= 0) continue; // skip empty lines

                // If SERIAL type and serials provided, ensure counts match
                if (!empty($serialJson)) {
                    $selectedSerials = json_decode($serialJson, true);
                    if (!is_array($selectedSerials) || count($selectedSerials) != $qty) {
                        throw new Exception('Số serial chọn không khớp với số lượng cho sản phẩm ' . $maHH);
                    }
                }

                // Insert chi tiết phiếu xuất (maPX, maHH, soLuong, donGia)
                $stmtCt = $db->prepare("INSERT INTO ct_phieuxuat (maPX, maHH, soLuong, donGia) VALUES (:maPX, :maHH, :soLuong, :donGia)");
                $stmtCt->execute([
                    ':maPX' => $maPX,
                    ':maHH' => $maHH,
                    ':soLuong' => $qty,
                    ':donGia' => $price
                ]);

                // If serials provided: mark as exported (trangThai = 0) và ghi ct_phieuxuat_serial
                if (!empty($serialJson)) {
                    $selectedSerials = json_decode($serialJson, true);
                    $stmtSerial = $db->prepare("UPDATE hanghoa_serial SET trangThai = 0, maViTri = NULL WHERE serial = :serial AND trangThai = 1");
                    $stmtCtSerial = $db->prepare("INSERT INTO ct_phieuxuat_serial (maPX, maHH, serial) VALUES (:maPX, :maHH, :serial)");
                    foreach ($selectedSerials as $s) {
                        $stmtSerial->execute([':serial' => $s]);
                        if ($stmtSerial->rowCount() == 0) {
                            throw new Exception('Serial không hợp lệ hoặc đã được xuất: ' . $s);
                        }

                        // lưu log serial theo phiếu xuất
                        $stmtCtSerial->execute([
                            ':maPX'   => $maPX,
                            ':maHH'   => $maHH,
                            ':serial' => $s
                        ]);
                    }
                    continue;
                }

                // Auto FIFO across lots for this product: lấy các lô theo ngày nhập ASC và trừ tồn ở các vị trí
                $stmtLots = $db->prepare("SELECT lh.maLo, COALESCE(SUM(lvt.soLuong),0) as soLuongCon
                                          FROM lohang lh
                                          LEFT JOIN lo_hang_vi_tri lvt ON lh.maLo = lvt.maLo
                                          WHERE lh.maHH = :maHH
                                          GROUP BY lh.maLo
                                          ORDER BY lh.ngayNhap ASC");
                $stmtLots->execute([':maHH' => $maHH]);
                $lots = $stmtLots->fetchAll(PDO::FETCH_ASSOC);

                $remain = $qty;
                $stmtPos = $db->prepare("SELECT maLVT, maViTri, soLuong FROM lo_hang_vi_tri WHERE maLo = :maLo AND soLuong > 0 ORDER BY maViTri ASC");
                $stmtUpd = $db->prepare("UPDATE lo_hang_vi_tri SET soLuong = soLuong - :use WHERE maLVT = :maLVT AND soLuong >= :use");
                $stmtCtLo = $db->prepare("INSERT INTO ct_phieuxuat_lo (maPX, maHH, maLo, maViTri, soLuong) VALUES (:maPX, :maHH, :maLo, :maViTri, :soLuong)");
                foreach ($lots as $lt) {
                    if ($remain <= 0) break;
                    $take = min((int)$lt['soLuongCon'], $remain);
                    if ($take <= 0) continue;

                    // reduce across positions of this lot
                    $stmtPos->execute([':maLo' => $lt['maLo']]);
                    $rows = $stmtPos->fetchAll(PDO::FETCH_ASSOC);

                    $rem2 = $take;
                    foreach ($rows as $r) {
                        if ($rem2 <= 0) break;
                        $avail = (int)$r['soLuong'];
                        $use = min($avail, $rem2);

                        // trừ tồn theo vị trí
                        $stmtUpd->execute([':use' => $use, ':maLVT' => $r['maLVT']]);
                        if ($stmtUpd->rowCount() == 0) {
                            throw new Exception('Không thể cập nhật tồn vị trí (xung đột tồn kho)');
                        }

                        // lưu log chi tiết xuất theo lô/vị trí
                        $stmtCtLo->execute([
                            ':maPX'    => $maPX,
                            ':maHH'    => $maHH,
                            ':maLo'    => $lt['maLo'],
                            ':maViTri' => $r['maViTri'],
                            ':soLuong' => $use
                        ]);

                        $rem2 -= $use;
                    }
                    if ($rem2 > 0) throw new Exception('Không đủ tồn trong lô để xuất');
                    $remain -= $take;
                }
                if ($remain > 0) {
                    throw new Exception('Không đủ tồn tổng để xuất sản phẩm ' . $maHH);
                }
            }

            $db->commit();
            header('Location: ' . BASE_URL . '/export');
            return;

        } catch (Exception $e) {
            $db->rollBack();
            // In production you'd log $e->getMessage()
            die('Lỗi khi lưu phiếu xuất: ' . $e->getMessage());
        }
    }
}
?>