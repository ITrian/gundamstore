<?php
class PartnerModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Lấy danh sách Nhà cung cấp
    public function getSuppliers() {
        // Lấy tất cả, hoặc bạn có thể WHERE loại = 'NCC' nếu muốn
        $sql = "SELECT * FROM NHACUNGCAP ORDER BY tenNCC ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>