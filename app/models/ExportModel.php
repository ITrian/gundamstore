<?php
class ExportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllOrders() {
        // Query tính tổng tiền trực tiếp trong câu lệnh
        $sql = "SELECT 
                    px.maPX,
                    px.ngayXuat,
                    kh.tenKH,
                    nd.tenND as nguoiTao,
                    -- Subquery tính tổng tiền của phiếu xuất đó
                    (SELECT COALESCE(SUM(soLuong * donGia), 0) 
                     FROM CT_PHIEUXUAT ct 
                     WHERE ct.maPX = px.maPX) as tongTien
                FROM PHIEUXUAT px
                LEFT JOIN KHACHHANG kh ON px.maKH = kh.maKH
                LEFT JOIN NGUOIDUNG nd ON px.maNDXuat = nd.maND
                ORDER BY px.ngayXuat DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy thông tin header phiếu xuất
    public function getExportById($maPX) {
        $sql = "SELECT px.maPX, px.ngayXuat, px.ghiChu,
                       kh.maKH, kh.tenKH, kh.sdt, kh.diaChi,
                       nd.maND, nd.tenND AS tenND
                FROM PHIEUXUAT px
                LEFT JOIN KHACHHANG kh ON px.maKH = kh.maKH
                LEFT JOIN NGUOIDUNG nd ON px.maNDXuat = nd.maND
                WHERE px.maPX = :maPX";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPX' => $maPX]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách dòng chi tiết phiếu xuất kèm thông tin lô/vị trí/serial nếu có
    public function getExportLines($maPX) {
        // 1. Lấy dòng ct_phieuxuat + thông tin sản phẩm
        $sql = "SELECT ct.maPX, ct.maHH, h.tenHH, ct.soLuong, ct.donGia
                FROM CT_PHIEUXUAT ct
                LEFT JOIN HANGHOA h ON h.maHH = ct.maHH
                WHERE ct.maPX = :maPX";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPX' => $maPX]);
        $lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$lines) return [];

        // Chuẩn bị index theo maHH để gán thêm thông tin
        $byProduct = [];
        foreach ($lines as $idx => $ln) {
            $lines[$idx]['lots']      = [];
            $lines[$idx]['locations'] = [];
            $lines[$idx]['serials']   = [];
            $byProduct[$ln['maHH']] = $idx;
        }

        // 2. Lấy chi tiết lô/vị trí từ ct_phieuxuat_lo
        $sqlLo = "SELECT l.maHH, l.maLo, l.maViTri, l.soLuong
                  FROM ct_phieuxuat_lo l
                  WHERE l.maPX = :maPX";
        $stmtLo = $this->conn->prepare($sqlLo);
        $stmtLo->execute([':maPX' => $maPX]);
        $rowsLo = $stmtLo->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowsLo as $row) {
            $maHH = $row['maHH'];
            if (!isset($byProduct[$maHH])) continue;
            $idx = $byProduct[$maHH];

            // gom lô
            $lines[$idx]['lots'][] = [
                'maLo'    => $row['maLo'],
                'soLuong' => $row['soLuong']
            ];

            // gom vị trí (có thể trùng lặp, đơn giản là liệt kê)
            if (!empty($row['maViTri'])) {
                $lines[$idx]['locations'][] = [
                    'maViTri' => $row['maViTri'],
                    'soLuong' => $row['soLuong']
                ];
            }
        }

        // 3. Lấy serial từ ct_phieuxuat_serial
        $sqlSer = "SELECT s.maHH, s.serial
                   FROM ct_phieuxuat_serial s
                   WHERE s.maPX = :maPX";
        $stmtSer = $this->conn->prepare($sqlSer);
        $stmtSer->execute([':maPX' => $maPX]);
        $rowsSer = $stmtSer->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowsSer as $row) {
            $maHH = $row['maHH'];
            if (!isset($byProduct[$maHH])) continue;
            $idx = $byProduct[$maHH];
            $lines[$idx]['serials'][] = $row['serial'];
        }

        return $lines;
    }
}
?>