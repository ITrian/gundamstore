<?php
class PartnerModel {
    // Lấy số thứ tự lớn nhất của mã NCC/KH hiện tại
    public function getMaxCode($table, $col, $prefix) {
        $sql = "SELECT MAX(CAST(SUBSTRING($col, LENGTH('$prefix')+1) AS UNSIGNED)) as max_code FROM $table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row && $row['max_code'] ? (int)$row['max_code'] : 0;
    }
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // --- 1. LẤY DANH SÁCH (Chỉ lấy Active) ---
    public function getSuppliers() {
        // Chỉ lấy những NCC đang hoạt động (trangThai = 1)
        $sql = "SELECT * FROM NHACUNGCAP WHERE trangThai = 1 ORDER BY tenNCC ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCustomers() {
        // Chỉ lấy những KH đang hoạt động (trangThai = 1)
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

    // --- 3. LẤY CHI TIẾT ĐỂ SỬA ---
    public function getPartnerByCode($table, $colName, $code) {
        $sql = "SELECT * FROM $table WHERE $colName = :code";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }

    // --- 4. CẬP NHẬT (UPDATE) ---
    public function updatePartner($table, $pkCol, $data) {
        // pkCol là chuỗi 'NCC' hoặc 'KH' để ghép thành tenNCC/tenKH
        $colName = ($table == 'NHACUNGCAP') ? 'tenNCC' : 'tenKH';
        $pkName = ($table == 'NHACUNGCAP') ? 'maNCC' : 'maKH';

        $sql = "UPDATE $table SET 
                $colName = :name, 
                diaChi = :address, 
                sdt = :phone, 
                email = :email 
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

    // --- 5. XÓA HOẶC KHÓA (Logic nghiệp vụ quan trọng) ---
    public function deleteOrLock($type, $code) {
        if ($type == 'supplier') {
            // Check bảng PHIEUNHAP
            $checkSql = "SELECT COUNT(*) FROM PHIEUNHAP WHERE maNCC = :code";
            $table = "NHACUNGCAP";
            $pk = "maNCC";
        } else {
            // Check bảng PHIEUXUAT
            $checkSql = "SELECT COUNT(*) FROM PHIEUXUAT WHERE maKH = :code";
            $table = "KHACHHANG";
            $pk = "maKH";
        }

        $stmt = $this->conn->prepare($checkSql);
        $stmt->execute([':code' => $code]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // CÓ GIAO DỊCH -> KHÔNG XÓA, CHỈ KHÓA (Soft Delete)
            $sql = "UPDATE $table SET trangThai = 0 WHERE $pk = :code";
            $this->conn->prepare($sql)->execute([':code' => $code]);
            return "locked"; 
        } else {
            // CHƯA CÓ GIAO DỊCH -> XÓA VĨNH VIỄN (Hard Delete)
            $sql = "DELETE FROM $table WHERE $pk = :code";
            $this->conn->prepare($sql)->execute([':code' => $code]);
            return "deleted";
        }
    }

    public function getInactivePartners($type) {
        if ($type == 'supplier') {
            $table = "NHACUNGCAP";
            $colOrder = "tenNCC";
        } else {
            $table = "KHACHHANG";
            $colOrder = "tenKH";
        }

        // Lấy những dòng có trangThai = 0
        $sql = "SELECT * FROM $table WHERE trangThai = 0 ORDER BY $colOrder ASC";
        
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
    
    // Hàm kiểm tra tồn tại
    public function checkExists($table, $column, $code) {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :code";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetchColumn() > 0;
    }
}
?>