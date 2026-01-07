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

    public function find($id) {
        $sql = "SELECT * FROM hanghoa WHERE maHH = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Insert includes loaiHang
    $sql = "INSERT INTO hanghoa (maHH, tenHH, loaiHang, heSoChiemCho, maDanhMuc, maNCC, maDVT, model, thuongHieu, moTa) 
        VALUES (:maHH, :tenHH, :loaiHang, :heSo, :maDanhMuc, :maNCC, :maDVT, :model, :thuongHieu, :moTa)";
        $stmt = $this->conn->prepare($sql);
        // Ensure array has loaiHang key
        $params = [
            ':maHH' => $data['maHH'],
            ':tenHH' => $data['tenHH'],
            ':loaiHang' => $data['loaiHang'] ?? 'LO',
            ':heSo' => isset($data['heSoChiemCho']) ? (int)$data['heSoChiemCho'] : 1,
            ':maDanhMuc' => $data['maDanhMuc'],
            ':maNCC' => $data['maNCC'],
            ':maDVT' => $data['maDVT'],
            ':model' => $data['model'],
            ':thuongHieu' => $data['thuongHieu'],
            ':moTa' => $data['moTa']
        ];
        return $stmt->execute($params);
    }

    public function update($id, $data) {
    $sql = "UPDATE hanghoa SET tenHH = :tenHH, loaiHang = :loaiHang, heSoChiemCho = :heSo, maDanhMuc = :maDanhMuc,
        maNCC = :maNCC, maDVT = :maDVT, model = :model, thuongHieu = :thuongHieu, moTa = :moTa
                WHERE maHH = :maHH";
        $stmt = $this->conn->prepare($sql);
        $params = [
            ':tenHH' => $data['tenHH'],
            ':loaiHang' => $data['loaiHang'] ?? 'LO',
            ':heSo' => isset($data['heSoChiemCho']) ? (int)$data['heSoChiemCho'] : 1,
            ':maDanhMuc' => $data['maDanhMuc'],
            ':maNCC' => $data['maNCC'],
            ':maDVT' => $data['maDVT'],
            ':model' => $data['model'],
            ':thuongHieu' => $data['thuongHieu'],
            ':moTa' => $data['moTa'],
            ':maHH' => $id
        ];
        return $stmt->execute($params);
    }

    public function delete($id) {
        // Prevent delete if there are lots referencing this product
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM lohang WHERE maHH = :id");
        $stmt->execute(['id' => $id]);
        $cnt = $stmt->fetchColumn();
        if ($cnt > 0) {
            return false;
        }

        $sql = "DELETE FROM hanghoa WHERE maHH = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>