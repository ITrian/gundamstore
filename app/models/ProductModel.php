<?php
class ProductModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Lấy danh sách hàng hóa kèm số lượng tồn
    public function getAll() {
        // Query này join bảng HANGHOA với TONKHO để tính tổng tồn
        $sql = "SELECT h.*, d.tenDanhMuc, n.tenNCC, dv.tenDVT,
                (SELECT COALESCE(SUM(soLuongTon), 0) 
                 FROM TONKHO tk 
                 JOIN LOHANG lh ON tk.maLo = lh.maLo 
                 WHERE lh.maHH = h.maHH) as tongTon
                FROM HANGHOA h
                LEFT JOIN DanhMuc d ON h.maDanhMuc = d.maDanhMuc
                LEFT JOIN NHACUNGCAP n ON h.maNCC = n.maNCC
                LEFT JOIN DONVITINH dv ON h.maDVT = dv.maDVT
                ORDER BY h.tenHH ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm hàng hóa mới
    public function create($data) {
        $sql = "INSERT INTO HANGHOA (maHH, tenHH, maDanhMuc, maNCC, maDVT, model, thuongHieu, moTa) 
                VALUES (:maHH, :tenHH, :maDanhMuc, :maNCC, :maDVT, :model, :thuongHieu, :moTa)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}
?>