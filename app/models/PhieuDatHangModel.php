<?php
class PhieuDatHangModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT pd.*, n.tenNCC FROM phieudathang pd LEFT JOIN NHACUNGCAP n ON pd.maNCC = n.maNCC ORDER BY pd.ngayDatHang DESC";
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

        $sql = "SELECT * FROM ct_phieudathang WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maDH' => $maDH]);
        $lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['header' => $header, 'lines' => $lines];
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
