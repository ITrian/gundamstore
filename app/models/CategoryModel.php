<?php
class CategoryModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Lấy tất cả danh mục
    public function getAll() {
        $sql = "SELECT * FROM DanhMuc ORDER BY tenDanhMuc ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm theo mã
    public function find($id) {
        $sql = "SELECT * FROM DanhMuc WHERE maDanhMuc = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới
    public function create($data) {
        $sql = "INSERT INTO DanhMuc (maDanhMuc, tenDanhMuc) VALUES (:maDanhMuc, :tenDanhMuc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'maDanhMuc' => $data['maDanhMuc'],
            'tenDanhMuc' => $data['tenDanhMuc']
        ]);
    }

    // Cập nhật
    public function update($id, $data) {
        $sql = "UPDATE DanhMuc SET tenDanhMuc = :tenDanhMuc WHERE maDanhMuc = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'tenDanhMuc' => $data['tenDanhMuc'],
            'id' => $id
        ]);
    }

    // Xóa
    public function delete($id) {
        $sql = "DELETE FROM DanhMuc WHERE maDanhMuc = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Kiểm tra xem danh mục có hàng hoá liên kết hay không
    public function hasProducts($id) {
        $sql = "SELECT COUNT(*) as cnt FROM HANGHOA WHERE maDanhMuc = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['cnt'] > 0);
    }
}
?>
