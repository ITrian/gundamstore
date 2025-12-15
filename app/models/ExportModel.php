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
}
?>