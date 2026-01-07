<?php
class ImportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ... (Giữ nguyên các hàm getSuppliers, getProducts) ...

    public function getSuppliers() {
        $stmt = $this->conn->prepare("SELECT * FROM nhacungcap");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM hanghoa ORDER BY tenHH ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllImports() {
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND 
                FROM phieunhap pn
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN nguoidung nd ON pn.maND = nd.maND
                ORDER BY pn.ngayNhap DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get full import info by maPN, including header, detail lines, lots, locations and serials
     * Returns array with 'header' and 'lines' where each line contains product info and nested lots
     */
    public function getImportById($maPN) {
        // header
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND
                FROM phieunhap pn
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN nguoidung nd ON pn.maND = nd.maND
                WHERE pn.maPN = :maPN LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPN' => $maPN]);
        $header = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$header) return null;

        // detail lines
        $sqlLines = "SELECT ct.*, hh.tenHH
                     FROM ct_phieunhap ct
                     LEFT JOIN hanghoa hh ON ct.maHH = hh.maHH
                     WHERE ct.maPN = :maPN";
        $stmt = $this->conn->prepare($sqlLines);
        $stmt->execute([':maPN' => $maPN]);
        $lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // For each line, fetch the lot(s) created for this PN+HH and associated locations and serials
        foreach ($lines as &$ln) {
            $ln['lots'] = [];
            $sqlLohang = "SELECT * FROM lohang WHERE maPN = :maPN AND maHH = :maHH";
            $stmtL = $this->conn->prepare($sqlLohang);
            $stmtL->execute([':maPN' => $maPN, ':maHH' => $ln['maHH']]);
            $lohangs = $stmtL->fetchAll(PDO::FETCH_ASSOC);

            foreach ($lohangs as $lh) {
                $lot = $lh;
                // locations for this lot
                $sqlLoc = "SELECT lvt.*, v.day, v.ke, v.o FROM lo_hang_vi_tri lvt LEFT JOIN vitri v ON lvt.maViTri = v.maViTri WHERE lvt.maLo = :maLo";
                $stmtLoc = $this->conn->prepare($sqlLoc);
                $stmtLoc->execute([':maLo' => $lh['maLo']]);
                $locs = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);
                $lot['locations'] = $locs;

                // serials for this lot
                $sqlSerials = "SELECT serial, trangThai, maViTri FROM hanghoa_serial WHERE maLo = :maLo";
                $stmtS = $this->conn->prepare($sqlSerials);
                $stmtS->execute([':maLo' => $lh['maLo']]);
                $serials = $stmtS->fetchAll(PDO::FETCH_ASSOC);
                $lot['serials'] = $serials;

                $ln['lots'][] = $lot;
            }
        }

        return ['header' => $header, 'lines' => $lines];
    }

    public function createImportTransaction($data, $products) {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo Phiếu Nhập
            $sqlPN = "INSERT INTO phieunhap (maPN, ngayNhap, maNCC, ghiChu, maND) 
                      VALUES (:maPN, NOW(), :maNCC, :ghiChu, :maND)";
            $stmtPN = $this->conn->prepare($sqlPN);
            $stmtPN->execute([
                ':maPN' => $data['maPN'],
                ':maNCC' => $data['maNCC'],
                ':ghiChu' => $data['ghiChu'],
                ':maND' => $data['maND']
            ]);

            // Lấy một vị trí mặc định (Vị trí đầu tiên trong DB) để xếp hàng vào
            // Thực tế bạn nên cho người dùng chọn vị trí trên Form
            $stmtVT = $this->conn->query("SELECT maViTri FROM vitri LIMIT 1");
            $defaultViTri = $stmtVT->fetchColumn();

            if (!$defaultViTri) {
                throw new Exception("Chưa có dữ liệu Vị trí (Kệ hàng) trong hệ thống!");
            }

            foreach ($products as $item) {
                // Determine product type from hanghoa.loaiHang
                $stmtType = $this->conn->prepare("SELECT loaiHang FROM hanghoa WHERE maHH = :maHH LIMIT 1");
                $stmtType->execute([':maHH' => $item['maHH']]);
                $rowType = $stmtType->fetch(PDO::FETCH_ASSOC);
                $loai = $rowType['loaiHang'] ?? 'LO';

                // create a lot record (maLo) for this receipt line
                $maLo = 'LO-' . $data['maPN'] . '-' . $item['maHH'] . '-' . uniqid();

                // Insert lohang (always create a lot to group the received items)
                $sqlLo = "INSERT INTO lohang (maLo, maPN, maHH, soLuongNhap, ngayNhap, hanBaoHanh) 
                          VALUES (:maLo, :maPN, :maHH, :soLuong, NOW(), :hsd)";
                $stmtLo = $this->conn->prepare($sqlLo);
                $stmtLo->execute([
                    ':maLo' => $maLo,
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':hsd' => !empty($item['hsd']) ? $item['hsd'] : NULL
                ]);

                // Before inserting into lo_hang_vi_tri, check capacity (points) of the position
                // Get product space factor (heSoChiemCho)
                $stmtHeSo = $this->conn->prepare("SELECT heSoChiemCho FROM hanghoa WHERE maHH = :maHH LIMIT 1");
                $stmtHeSo->execute([':maHH' => $item['maHH']]);
                $heSoRow = $stmtHeSo->fetch(PDO::FETCH_ASSOC);
                $heSo = isset($heSoRow['heSoChiemCho']) ? (int)$heSoRow['heSoChiemCho'] : 1;

                // compute required points for this insertion
                $requiredPoints = $heSo * (int)$item['soLuong'];

                // compute current occupied points at the position
                $stmtOcc = $this->conn->prepare(
                    "SELECT COALESCE(SUM(lvt2.soLuong * hh2.heSoChiemCho),0) as occupied
                     FROM lo_hang_vi_tri lvt2
                     JOIN lohang lh2 ON lvt2.maLo = lh2.maLo
                     JOIN hanghoa hh2 ON lh2.maHH = hh2.maHH
                     WHERE lvt2.maViTri = :maViTri"
                );
                $stmtOcc->execute([':maViTri' => $defaultViTri]);
                $occRow = $stmtOcc->fetch(PDO::FETCH_ASSOC);
                $occupiedPoints = isset($occRow['occupied']) ? (int)$occRow['occupied'] : 0;

                // get capacity points
                $stmtCap = $this->conn->prepare("SELECT sucChuaToiDa FROM vitri WHERE maViTri = :maViTri LIMIT 1");
                $stmtCap->execute([':maViTri' => $defaultViTri]);
                $capRow = $stmtCap->fetch(PDO::FETCH_ASSOC);
                $capacityPoints = isset($capRow['sucChuaToiDa']) ? (int)$capRow['sucChuaToiDa'] : 100;

                if (($occupiedPoints + $requiredPoints) > $capacityPoints) {
                    throw new Exception("Vị trí {$defaultViTri} không đủ sức chứa. Cần: {$requiredPoints}, còn trống: " . max(0, $capacityPoints - $occupiedPoints));
                }

                // Insert into lo_hang_vi_tri
                $sqlTon = "INSERT INTO lo_hang_vi_tri (maLo, maViTri, soLuong) VALUES (:maLo, :maViTri, :soLuong)";
                $stmtTon = $this->conn->prepare($sqlTon);
                $stmtTon->execute([
                    ':maLo' => $maLo,
                    ':maViTri' => $defaultViTri, // Tạm thời nhét hết vào vị trí đầu tiên
                    ':soLuong' => $item['soLuong']
                ]);

                // Insert into ct_phieunhap
                $sqlCT = "INSERT INTO ct_phieunhap (maPN, maHH, soLuong, donGia) 
                          VALUES (:maPN, :maHH, :soLuong, :donGia)";
                $stmtCT = $this->conn->prepare($sqlCT);
                $stmtCT->execute([
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':donGia' => $item['donGia']
                ]);

                // If product is SERIAL-managed, insert individual serial records
                if ($loai === 'SERIAL' && !empty($item['serials']) && is_array($item['serials'])) {
                    $sqlSerial = "INSERT INTO hanghoa_serial (serial, maLo, trangThai, maViTri) VALUES (:serial, :maLo, 1, :maViTri)";
                    $stmtSerial = $this->conn->prepare($sqlSerial);
                    foreach ($item['serials'] as $s) {
                        $stmtSerial->execute([
                            ':serial' => $s,
                            ':maLo' => $maLo,
                            ':maViTri' => $defaultViTri
                        ]);
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // Propagate exception so controller can show a clearer error message / handle it
            throw $e;
        }
    }
}
?>