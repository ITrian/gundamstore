<?php
class HomeModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Đếm tổng số mặt hàng (Master Data)
    public function countProducts() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM HANGHOA");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    // 2. Tính tổng số lượng tồn kho (Sum quantity)
    public function sumInventory() {
        $stmt = $this->conn->prepare("SELECT SUM(soLuongTon) as total FROM TONKHO");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0; // Trả về 0 nếu null
    }

    // 3. Lấy danh sách hàng sắp hết (Tồn <= 10)
    public function getLowStockProducts($limit = 5) {
        // Query này join 3 bảng để tính tổng tồn theo từng mã hàng
        // HAVING tongTon <= 10: Lọc ra hàng sắp hết
        $sql = "SELECT h.maHH, h.tenHH, COALESCE(SUM(tk.soLuongTon), 0) as tongTon
                FROM HANGHOA h
                LEFT JOIN LOHANG lh ON h.maHH = lh.maHH
                LEFT JOIN TONKHO tk ON lh.maLo = tk.maLo
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