<?php
class ReportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Tổng tiền nhập hàng (Chi phí) theo khoảng thời gian
    public function getImportCost($fromDate, $toDate) {
        $sql = "SELECT SUM(ct.soLuong * ct.donGia) as total 
                FROM CT_PHIEUNHAP ct
                JOIN PHIEUNHAP pn ON ct.maPN = pn.maPN
                WHERE DATE(pn.ngayNhap) BETWEEN :fromDate AND :toDate";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':fromDate' => $fromDate, ':toDate' => $toDate]);
        return $stmt->fetch()['total'] ?? 0;
    }

    // 2. Tổng tiền xuất hàng (Doanh thu) theo khoảng thời gian
    public function getExportRevenue($fromDate, $toDate) {
        $sql = "SELECT SUM(ct.soLuong * ct.donGia) as total 
                FROM CT_PHIEUXUAT ct
                JOIN PHIEUXUAT px ON ct.maPX = px.maPX
                WHERE DATE(px.ngayXuat) BETWEEN :fromDate AND :toDate";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':fromDate' => $fromDate, ':toDate' => $toDate]);
        return $stmt->fetch()['total'] ?? 0;
    }

    // 3. Đếm số đơn nhập/xuất
    public function countTransactions($table, $dateColumn, $fromDate, $toDate) {
        $sql = "SELECT COUNT(*) as total FROM $table 
                WHERE DATE($dateColumn) BETWEEN :fromDate AND :toDate";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':fromDate' => $fromDate, ':toDate' => $toDate]);
        return $stmt->fetch()['total'];
    }

    // 4. Top 5 sản phẩm bán chạy nhất trong khoảng thời gian
    public function getTopSelling($fromDate, $toDate) {
        $sql = "SELECT h.tenHH, SUM(ct.soLuong) as soLuongBan 
                FROM CT_PHIEUXUAT ct
                JOIN PHIEUXUAT px ON ct.maPX = px.maPX
                JOIN HANGHOA h ON ct.maHH = h.maHH
                WHERE DATE(px.ngayXuat) BETWEEN :fromDate AND :toDate
                GROUP BY h.maHH, h.tenHH
                ORDER BY soLuongBan DESC
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':fromDate' => $fromDate, ':toDate' => $toDate]);
        return $stmt->fetchAll();
    }
}
?>