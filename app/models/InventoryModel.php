<?php
class InventoryModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllStock() {
        // Query này lấy Tồn kho + Thông tin Lô + Tên Hàng + Vị trí (nếu có)
        // Sử dụng LEFT JOIN vì có thể hàng chưa được gán vị trí cụ thể
        $sql = "SELECT 
                    tk.soLuongTon,
                    lh.maLo,
                    lh.hanBaoHanh,
                    hh.tenHH,
                    hh.maHH,
                    -- Gom nhóm vị trí nếu 1 lô nằm nhiều chỗ (Ví dụ: A-01, B-02)
                    GROUP_CONCAT(CONCAT(vt.day, '-', vt.ke, '-', vt.o) SEPARATOR ', ') as viTriCuThe
                FROM TONKHO tk
                JOIN LOHANG lh ON tk.maLo = lh.maLo
                JOIN HANGHOA hh ON lh.maHH = hh.maHH
                LEFT JOIN LO_HANG_VI_TRI lvt ON lh.maLo = lvt.maLo
                LEFT JOIN VITRI vt ON lvt.maViTri = vt.maViTri
                WHERE tk.soLuongTon > 0
                GROUP BY tk.maLo, hh.maHH
                ORDER BY hh.tenHH ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>