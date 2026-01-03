<?php
class HomeModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function countProducts() {
        // Đếm số dòng trong bảng hanghoa
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM hanghoa");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function sumInventory() {
        // SỬA: Tính tổng cột soLuong trong bảng lo_hang_vi_tri
        $stmt = $this->conn->prepare("SELECT SUM(soLuong) as total FROM lo_hang_vi_tri");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getLowStockProducts($limit = 5) {
        // SỬA: Join 3 bảng để tính tổng tồn: hanghoa -> lohang -> lo_hang_vi_tri
        $sql = "SELECT h.maHH, h.tenHH, COALESCE(SUM(lvt.soLuong), 0) as tongTon
                FROM hanghoa h
                LEFT JOIN lohang lh ON h.maHH = lh.maHH
                LEFT JOIN lo_hang_vi_tri lvt ON lh.maLo = lvt.maLo
                GROUP BY h.maHH, h.tenHH
                HAVING tongTon <= 10
                ORDER BY tongTon ASC
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>