<?php
class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Lấy thông tin người dùng theo tài khoản (username)
    public function getByUsername($username) {
        try {
            // SỬA: Đổi NGUOIDUNG -> nguoidung, VAITRO -> vaitro (viết thường)
            $sql = "SELECT nd.*, vt.tenVaiTro 
                    FROM nguoidung nd 
                    JOIN vaitro vt ON nd.maVaiTro = vt.maVaiTro 
                    WHERE nd.taiKhoan = :username";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            // Trả về mảng dữ liệu user hoặc false nếu không tìm thấy
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy danh sách quyền của một user
    public function getPermissions($maND) {
        $sql = "SELECT q.maQuyen 
                FROM nguoidung nd
                JOIN quyen_vaitro qvt ON nd.maVaiTro = qvt.maVaiTro
                JOIN quyen q ON qvt.maQuyen = q.maQuyen
                WHERE nd.maND = :maND";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maND' => $maND]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Trả về mảng 1 chiều các mã quyền
    }
}
?>