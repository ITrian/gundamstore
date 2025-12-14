<?php
class ProductModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        // Lấy danh sách hàng hóa kèm số lượng tồn kho
        $sql = "SELECT h.*, d.tenDanhMuc, dv.tenDVT, 
                IFNULL((SELECT SUM(soLuongTon) FROM TONKHO tk 
                 JOIN LOHANG lh ON tk.maLo = lh.maLo 
                 WHERE lh.maHH = h.maHH), 0) as tongTon
                FROM HANGHOA h
                LEFT JOIN DanhMuc d ON h.maDanhMuc = d.maDanhMuc
                LEFT JOIN DONVITINH dv ON h.maDVT = dv.maDVT
                ORDER BY h.tenHH ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>