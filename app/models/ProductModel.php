<?php
class ProductModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        // SỬA: Query phức tạp hơn để tính tổng tồn từ bảng lo_hang_vi_tri
        $sql = "SELECT h.*, d.tenDanhMuc, n.tenNCC, dv.tenDVT,
                (
                    SELECT COALESCE(SUM(lvt.soLuong), 0)
                    FROM lo_hang_vi_tri lvt
                    JOIN lohang lh ON lvt.maLo = lh.maLo
                    WHERE lh.maHH = h.maHH
                ) as tongTon
                FROM hanghoa h
                LEFT JOIN danhmuc d ON h.maDanhMuc = d.maDanhMuc
                LEFT JOIN nhacungcap n ON h.maNCC = n.maNCC
                LEFT JOIN donvitinh dv ON h.maDVT = dv.maDVT
                ORDER BY h.tenHH ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        // Insert giữ nguyên, nhưng lưu ý tên cột trong SQL mới
        $sql = "INSERT INTO hanghoa (maHH, tenHH, maDanhMuc, maNCC, maDVT, model, thuongHieu, moTa) 
                VALUES (:maHH, :tenHH, :maDanhMuc, :maNCC, :maDVT, :model, :thuongHieu, :moTa)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}
?>