<?php
class PhieuDatHangModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        // Lấy thêm tổng giá trị đơn (tổng soLuong * donGia theo chi tiết)
        $sql = "SELECT pd.*, n.tenNCC,
                       COALESCE(SUM(ct.soLuong * ct.donGia), 0) AS tongGiaTri
                FROM phieudathang pd
                LEFT JOIN NHACUNGCAP n ON pd.maNCC = n.maNCC
                LEFT JOIN ct_phieudathang ct ON ct.maDH = pd.maDH
                GROUP BY pd.maDH
                ORDER BY pd.ngayDatHang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($maDH) {
        $sql = "SELECT pd.*, n.tenNCC FROM phieudathang pd LEFT JOIN NHACUNGCAP n ON pd.maNCC = n.maNCC WHERE pd.maDH = :maDH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maDH' => $maDH]);
        $header = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$header) return false;

    $sql = "SELECT *, COALESCE(soLuongDaNhap,0) AS soLuongDaNhap FROM ct_phieudathang WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maDH' => $maDH]);
        $lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['header' => $header, 'lines' => $lines];
    }

    // Lấy danh sách đơn đặt hàng theo nhà cung cấp (dùng cho form nhập kho)
    public function getOrdersBySupplier($maNCC) {
        // Chỉ lấy các đơn đang chờ nhập (0) hoặc nhập thiếu (1)
        // 0 = chờ nhập, 1 = nhập thiếu, 2 = nhập đủ (không cho chọn nữa)
        $sql = "SELECT maDH, ngayDatHang, trangThai
                FROM phieudathang
                WHERE maNCC = :maNCC AND trangThai IN (0,1)
                ORDER BY ngayDatHang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maNCC' => $maNCC]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn đặt hàng kèm thông tin sản phẩm
    public function getOrderLinesWithProduct($maDH) {
        // Sử dụng cột soLuongDaNhap mới trên ct_phieudathang
        // soLuongConLai = c.soLuong - COALESCE(c.soLuongDaNhap, 0)
        // Chỉ lấy những dòng còn thiếu (soLuongConLai > 0)
        $sql = "SELECT c.maHH,
                       h.tenHH,
                       c.soLuong,
                       c.donGia,
                       (c.soLuong - COALESCE(c.soLuongDaNhap, 0)) AS soLuongConLai
                FROM ct_phieudathang c
                JOIN hanghoa h ON h.maHH = c.maHH
                WHERE c.maDH = :maDH
                  AND (c.soLuong - COALESCE(c.soLuongDaNhap, 0)) > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maDH' => $maDH]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuppliers() {
        $sql = "SELECT maNCC, tenNCC FROM NHACUNGCAP WHERE trangThai = 1 ORDER BY tenNCC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProducts() {
        // use loaiHang column on hanghoa table (LO or SERIAL) instead of checking hanghoa_serial
        $sql = "SELECT maHH, tenHH, loaiHang FROM HANGHOA ORDER BY tenHH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($header, $lines) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO phieudathang (maDH, ngayDatHang, maNCC, trangThai, maND) VALUES (:maDH, :ngayDatHang, :maNCC, :trangThai, :maND)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':maDH' => $header['maDH'],
                ':ngayDatHang' => $header['ngayDatHang'],
                ':maNCC' => $header['maNCC'],
                ':trangThai' => $header['trangThai'],
                ':maND' => $header['maND']
            ]);

            $sql = "INSERT INTO ct_phieudathang (maDH, maHH, soLuong, donGia) VALUES (:maDH, :maHH, :soLuong, :donGia)";
            $stmt = $this->conn->prepare($sql);
            foreach ($lines as $ln) {
                $stmt->execute([
                    ':maDH' => $header['maDH'],
                    ':maHH' => $ln['maHH'],
                    ':soLuong' => $ln['soLuong'],
                    ':donGia' => $ln['donGia']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
