<?php
class ReportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Tính tổng doanh thu (Từ bảng Chi tiết phiếu xuất)
    public function getTotalRevenue() {
        // Tổng tiền = Số lượng * Đơn giá của tất cả các dòng xuất
        $sql = "SELECT SUM(soLuong * donGia) as total FROM CT_PHIEUXUAT";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 2. Đếm số đơn hàng đã xuất
    public function countExportOrders() {
        $sql = "SELECT COUNT(*) as total FROM PHIEUXUAT";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    // 3. Đếm số mặt hàng sắp hết (Tồn kho <= 10)
    public function countLowStock() {
        // Dùng subquery để tính tổng tồn từng món, sau đó đếm những món <= 10
        $sql = "SELECT COUNT(*) as total FROM (
                    SELECT SUM(tk.soLuongTon) as tongTon 
                    FROM HANGHOA h
                    JOIN LOHANG lh ON h.maHH = lh.maHH
                    JOIN TONKHO tk ON lh.maLo = tk.maLo
                    GROUP BY h.maHH
                    HAVING tongTon <= 10
                ) as subquery";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 4. Lấy Top 5 sản phẩm bán chạy nhất
    public function getTopSellingProducts($limit = 5) {
        $sql = "SELECT h.tenHH, 
                       SUM(ct.soLuong) as totalSold, 
                       SUM(ct.soLuong * ct.donGia) as revenue
                FROM CT_PHIEUXUAT ct
                JOIN HANGHOA h ON ct.maHH = h.maHH
                GROUP BY h.maHH, h.tenHH
                ORDER BY totalSold DESC
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>