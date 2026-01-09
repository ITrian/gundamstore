<?php
class ImportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // --- ĐÃ SỬA: Sinh mã phiếu nhập theo định dạng PN-YYMMDD-XXX ---
    private function generateMaPN() {
        // Lấy ngày format YYMMDD (Ví dụ: 260109 cho ngày 09/01/2026)
        $date = date('ymd'); 
        
        // Cấu trúc: PN-260109-001
        // Đếm ký tự: P(1) N(2) -(3) 2(4) 6(5) 0(6) 1(7) 0(8) 9(9) -(10) [Số bắt đầu từ 11]
        $sql = "SELECT MAX(CAST(SUBSTRING(maPN, 11, 3) AS UNSIGNED)) as max_stt 
                FROM phieunhap 
                WHERE maPN LIKE ?";
        
        // Mẫu tìm kiếm: PN-260109-%
        $like = 'PN-' . $date . '-%';
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$like]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nếu chưa có thì bắt đầu là 1, có rồi thì cộng thêm 1
        $stt = isset($row['max_stt']) && $row['max_stt'] ? ((int)$row['max_stt'] + 1) : 1;
        
        // Trả về: PN-260109-001
        return 'PN-' . $date . '-' . str_pad($stt, 3, '0', STR_PAD_LEFT);
    }

    // Sinh tiền tố mã lô: LO + [YYYYMMDD] (Ví dụ: LO20260109)
    private function generateBaseMaLoPrefix() {
        return 'LO' . date('Ymd');
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

    public function getImportById($maPN) {
        // Header
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND
                FROM phieunhap pn
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN nguoidung nd ON pn.maND = nd.maND
                WHERE pn.maPN = :maPN LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPN' => $maPN]);
        $header = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$header) return null;

        // Lines
        $sqlLines = "SELECT ct.*, hh.tenHH
                     FROM ct_phieunhap ct
                     LEFT JOIN hanghoa hh ON ct.maHH = hh.maHH
                     WHERE ct.maPN = :maPN";
        $stmt = $this->conn->prepare($sqlLines);
        $stmt->execute([':maPN' => $maPN]);
        $lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nested Lots, Locations, Serials
        foreach ($lines as &$ln) {
            $ln['lots'] = [];
            $sqlLohang = "SELECT * FROM lohang WHERE maPN = :maPN AND maHH = :maHH";
            $stmtL = $this->conn->prepare($sqlLohang);
            $stmtL->execute([':maPN' => $maPN, ':maHH' => $ln['maHH']]);
            $lohangs = $stmtL->fetchAll(PDO::FETCH_ASSOC);

            foreach ($lohangs as $lh) {
                $lot = $lh;
                $sqlLoc = "SELECT lvt.*, v.day, v.ke, v.o FROM lo_hang_vi_tri lvt LEFT JOIN vitri v ON lvt.maViTri = v.maViTri WHERE lvt.maLo = :maLo";
                $stmtLoc = $this->conn->prepare($sqlLoc);
                $stmtLoc->execute([':maLo' => $lh['maLo']]);
                $lot['locations'] = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);

                $sqlSerials = "SELECT serial, trangThai, maViTri FROM hanghoa_serial WHERE maLo = :maLo";
                $stmtS = $this->conn->prepare($sqlSerials);
                $stmtS->execute([':maLo' => $lh['maLo']]);
                $lot['serials'] = $stmtS->fetchAll(PDO::FETCH_ASSOC);

                $ln['lots'][] = $lot;
            }
        }
        return ['header' => $header, 'lines' => $lines];
    }

    public function createImportTransaction($data, $products) {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo Phiếu Nhập
            // Nếu người dùng không nhập mã, hệ thống tự sinh mã theo format PN-YYMMDD-XXX
            $maPN = !empty($data['maPN']) ? $data['maPN'] : $this->generateMaPN();
            
            // Validate: Đảm bảo mã phiếu nhập chưa tồn tại (Xử lý trường hợp click nhanh gây trùng)
            $stmtCheckPN = $this->conn->prepare("SELECT COUNT(*) FROM phieunhap WHERE maPN = :maPN");
            $stmtCheckPN->execute([':maPN' => $maPN]);
            if ($stmtCheckPN->fetchColumn() > 0) {
                 $maPN = $this->generateMaPN();
            }

            $sqlPN = "INSERT INTO phieunhap (maPN, ngayNhap, maNCC, ghiChu, maND) 
                      VALUES (:maPN, NOW(), :maNCC, :ghiChu, :maND)";
            $stmtPN = $this->conn->prepare($sqlPN);
            $stmtPN->execute([
                ':maPN' => $maPN,
                ':maNCC' => $data['maNCC'],
                ':ghiChu' => $data['ghiChu'],
                ':maND' => $data['maND']
            ]);

            // Lấy danh sách vị trí
            $stmtVT = $this->conn->query("SELECT maViTri, sucChuaToiDa FROM vitri ORDER BY day ASC, ke ASC, o ASC");
            $vitriList = $stmtVT->fetchAll(PDO::FETCH_ASSOC);

            if (!$vitriList || count($vitriList) == 0) {
                throw new Exception("Chưa có dữ liệu Vị trí (Kệ hàng) trong hệ thống!");
            }

            // Logic sinh mã lô (không đổi)
            $prefixLo = $this->generateBaseMaLoPrefix(); 
            $stmtMaxLo = $this->conn->prepare("SELECT MAX(CAST(SUBSTRING(maLo, 11, 4) AS UNSIGNED)) FROM lohang WHERE maLo LIKE ?");
            $stmtMaxLo->execute([$prefixLo . '%']);
            $currentMaxStt = (int)$stmtMaxLo->fetchColumn(); 

            foreach ($products as $index => $item) {
                $currentMaxStt++; 
                $maLo = $prefixLo . str_pad($currentMaxStt, 4, '0', STR_PAD_LEFT);

                // --- LOGIC XỬ LÝ DỮ LIỆU ---
                $stmtType = $this->conn->prepare("SELECT loaiHang, heSoChiemCho FROM hanghoa WHERE maHH = :maHH LIMIT 1");
                $stmtType->execute([':maHH' => $item['maHH']]);
                $rowType = $stmtType->fetch(PDO::FETCH_ASSOC);
                
                $loai = $rowType['loaiHang'] ?? 'LO';
                $heSo = isset($rowType['heSoChiemCho']) ? (int)$rowType['heSoChiemCho'] : 1;
                $soLuongConLai = (int)$item['soLuong'];

                // 2. Insert Lô hàng
                $sqlLo = "INSERT INTO lohang (maLo, maPN, maHH, soLuongNhap, ngayNhap, hanBaoHanh) 
                          VALUES (:maLo, :maPN, :maHH, :soLuong, NOW(), :hsd)";
                $stmtLo = $this->conn->prepare($sqlLo);
                $stmtLo->execute([
                    ':maLo' => $maLo,
                    ':maPN' => $maPN,
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':hsd' => !empty($item['hsd']) ? $item['hsd'] : NULL
                ]);

                // 3. Phân bổ vị trí
                foreach ($vitriList as $vitri) {
                    if ($soLuongConLai <= 0) break;
                    
                    $stmtOcc = $this->conn->prepare(
                        "SELECT COALESCE(SUM(lvt2.soLuong * hh2.heSoChiemCho),0) as occupied
                         FROM lo_hang_vi_tri lvt2
                         JOIN lohang lh2 ON lvt2.maLo = lh2.maLo
                         JOIN hanghoa hh2 ON lh2.maHH = hh2.maHH
                         WHERE lvt2.maViTri = :maViTri"
                    );
                    $stmtOcc->execute([':maViTri' => $vitri['maViTri']]);
                    $occRow = $stmtOcc->fetch(PDO::FETCH_ASSOC);
                    
                    $occupiedPoints = (int)$occRow['occupied'];
                    $capacityPoints = isset($vitri['sucChuaToiDa']) ? (int)$vitri['sucChuaToiDa'] : 100;
                    $availablePoints = $capacityPoints - $occupiedPoints;
                    
                    $maxCanStore = ($heSo > 0) ? floor($availablePoints / $heSo) : $soLuongConLai; 
                    
                    if ($maxCanStore <= 0) continue;
                    
                    $toStore = min($soLuongConLai, $maxCanStore);

                    $sqlTon = "INSERT INTO lo_hang_vi_tri (maLo, maViTri, soLuong) VALUES (:maLo, :maViTri, :soLuong)";
                    $stmtTon = $this->conn->prepare($sqlTon);
                    $stmtTon->execute([
                        ':maLo' => $maLo,
                        ':maViTri' => $vitri['maViTri'],
                        ':soLuong' => $toStore
                    ]);

                    if ($loai === 'SERIAL' && !empty($item['serials']) && is_array($item['serials'])) {
                        $sqlSerial = "INSERT INTO hanghoa_serial (serial, maLo, trangThai, maViTri) VALUES (:serial, :maLo, 1, :maViTri)";
                        $stmtSerial = $this->conn->prepare($sqlSerial);
                        
                        $startIndex = $item['soLuong'] - $soLuongConLai; 
                        $serialsToInsert = array_slice($item['serials'], $startIndex, $toStore);
                        
                        foreach ($serialsToInsert as $s) {
                            $stmtSerial->execute([
                                ':serial' => $s,
                                ':maLo' => $maLo,
                                ':maViTri' => $vitri['maViTri']
                            ]);
                        }
                    }

                    $soLuongConLai -= $toStore;
                }

                if ($soLuongConLai > 0) {
                    throw new Exception("Không đủ sức chứa cho hàng hóa " . $item['maHH'] . ". Thiếu chỗ cho " . $soLuongConLai . " sản phẩm.");
                }

                // 4. Insert Chi tiết phiếu nhập
                $sqlCT = "INSERT INTO ct_phieunhap (maPN, maHH, soLuong, donGia) 
                          VALUES (:maPN, :maHH, :soLuong, :donGia)";
                $stmtCT = $this->conn->prepare($sqlCT);
                $stmtCT->execute([
                    ':maPN' => $maPN,
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':donGia' => $item['donGia']
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>