<?php
class PartnerModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // --- HÀM QUAN TRỌNG: Lấy số thứ tự lớn nhất ---
    public function getMaxCode($table, $col, $prefix) {
        // Cắt bỏ tiền tố, ép kiểu sang số để tìm max
        // Ví dụ: KH00005 -> lấy 00005 -> thành số 5
        $sql = "SELECT MAX(CAST(SUBSTRING($col, LENGTH('$prefix')+1) AS UNSIGNED)) as max_code FROM $table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row && $row['max_code'] ? (int)$row['max_code'] : 0;
    }

    // --- 1. LẤY DANH SÁCH ---
    public function getSuppliers() {
        $sql = "SELECT * FROM NHACUNGCAP WHERE trangThai = 1 ORDER BY tenNCC ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCustomers() {
        $sql = "SELECT * FROM KHACHHANG WHERE trangThai = 1 ORDER BY tenKH ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- 2. THÊM MỚI ---
    public function addSupplier($data) {
        $sql = "INSERT INTO NHACUNGCAP (maNCC, tenNCC, diaChi, sdt, email, trangThai) 
                VALUES (:maNCC, :tenNCC, :diaChi, :sdt, :email, 1)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function addCustomer($data) {
        $sql = "INSERT INTO KHACHHANG (maKH, tenKH, diaChi, sdt, email, trangThai) 
                VALUES (:maKH, :tenKH, :diaChi, :sdt, :email, 1)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // --- 3. CÁC HÀM KHÁC (GET, UPDATE, DELETE) ---
    public function getPartnerByCode($table, $colName, $code) {
        $sql = "SELECT * FROM $table WHERE $colName = :code";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }

    public function updatePartner($table, $pkCol, $data) {
        $colName = ($table == 'NHACUNGCAP') ? 'tenNCC' : 'tenKH';
        $pkName = ($table == 'NHACUNGCAP') ? 'maNCC' : 'maKH';

        $sql = "UPDATE $table SET 
                $colName = :name, diaChi = :address, sdt = :phone, email = :email 
                WHERE $pkName = :code";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':code' => $data['code']
        ]);
    }

    public function deleteOrLock($type, $code) {
        if ($type == 'supplier') {
            // Nhà cung cấp: chỉ được xóa hẳn nếu KHÔNG có đơn đặt hàng và KHÔNG có phiếu nhập
            $table = "NHACUNGCAP"; 
            $pk    = "maNCC";

            // Kiểm tra đơn đặt hàng
            $sqlOrder = "SELECT COUNT(*) FROM PHIEUDATHANG WHERE maNCC = :code";
            $stmtOrder = $this->conn->prepare($sqlOrder);
            $stmtOrder->execute([':code' => $code]);
            $hasOrders = $stmtOrder->fetchColumn() > 0;

            // Kiểm tra phiếu nhập
            $sqlImport = "SELECT COUNT(*) FROM PHIEUNHAP WHERE maNCC = :code";
            $stmtImport = $this->conn->prepare($sqlImport);
            $stmtImport->execute([':code' => $code]);
            $hasImports = $stmtImport->fetchColumn() > 0;

            if ($hasOrders || $hasImports) {
                // Ngưng giao dịch (soft delete)
                $sql = "UPDATE $table SET trangThai = 0 WHERE $pk = :code";
                $this->conn->prepare($sql)->execute([':code' => $code]);
                return "locked";
            } else {
                // Không có đơn đặt/phiếu nhập -> xóa hẳn
                $sql = "DELETE FROM $table WHERE $pk = :code";
                $this->conn->prepare($sql)->execute([':code' => $code]);
                return "deleted";
            }
        } else {
            // Khách hàng: giữ nguyên logic cũ, chỉ cần kiểm tra phiếu xuất
            $checkSql = "SELECT COUNT(*) FROM PHIEUXUAT WHERE maKH = :code";
            $table = "KHACHHANG"; $pk = "maKH";

            $stmt = $this->conn->prepare($checkSql);
            $stmt->execute([':code' => $code]);
            
            if ($stmt->fetchColumn() > 0) {
                $sql = "UPDATE $table SET trangThai = 0 WHERE $pk = :code"; // Soft delete
                $this->conn->prepare($sql)->execute([':code' => $code]);
                return "locked"; 
            } else {
                $sql = "DELETE FROM $table WHERE $pk = :code"; // Hard delete
                $this->conn->prepare($sql)->execute([':code' => $code]);
                return "deleted";
            }
        }
    }

    public function getInactivePartners($type) {
        $table = ($type == 'supplier') ? "NHACUNGCAP" : "KHACHHANG";
        $sql = "SELECT * FROM $table WHERE trangThai = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function restorePartner($type, $code) {
        $table = ($type == 'supplier') ? "NHACUNGCAP" : "KHACHHANG";
        $pk = ($type == 'supplier') ? "maNCC" : "maKH";
        $sql = "UPDATE $table SET trangThai = 1 WHERE $pk = :code";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':code' => $code]);
    }
}
?>