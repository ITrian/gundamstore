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

    // Lấy tất cả người dùng ĐANG HOẠT ĐỘNG kèm vai trò
    public function getAllUsers() {
        $sql = "SELECT nd.*, vt.tenVaiTro 
                FROM nguoidung nd 
                JOIN vaitro vt ON nd.maVaiTro = vt.maVaiTro
                WHERE nd.hoatDong = 1
                ORDER BY nd.maND ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách người dùng BỊ KHÓA
    public function getLockedUsers() {
        $sql = "SELECT nd.*, vt.tenVaiTro 
                FROM nguoidung nd 
                JOIN vaitro vt ON nd.maVaiTro = vt.maVaiTro
                WHERE nd.hoatDong = 0
                ORDER BY nd.maND ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Khôi phục tài khoản (Mở khóa)
    public function restore($id) {
        $sql = "UPDATE nguoidung SET hoatDong = 1 WHERE maND = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy chi tiết user
    public function find($id) {
        $sql = "SELECT * FROM nguoidung WHERE maND = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả vai trò
    public function getAllRoles() {
        $sql = "SELECT * FROM vaitro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo người dùng mới
    public function create($data) {
        $sql = "INSERT INTO nguoidung (maND, tenND, email, sdt, taiKhoan, matKhau, maVaiTro) 
                VALUES (:maND, :tenND, :email, :sdt, :taiKhoan, :matKhau, :maVaiTro)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Cập nhật người dùng
    public function update($id, $data) {
        // Build sql dynamically depending on if password is provided
        $sql = "UPDATE nguoidung SET tenND=:tenND, email=:email, sdt=:sdt, maVaiTro=:maVaiTro";
        if (!empty($data['matKhau'])) {
            $sql .= ", matKhau=:matKhau";
        }
        $sql .= " WHERE maND=:id";
        
        $stmt = $this->conn->prepare($sql);
        $params = [
            ':tenND' => $data['tenND'],
            ':email' => $data['email'],
            ':sdt' => $data['sdt'],
            ':maVaiTro' => $data['maVaiTro'],
            ':id' => $id
        ];
        if (!empty($data['matKhau'])) {
            $params[':matKhau'] = $data['matKhau'];
        }
        
        return $stmt->execute($params);
    }

    // Xóa người dùng (Cập nhật logic khóa tài khoản)
    public function delete($id) {
        // 1. Kiểm tra xem user này đã từng tạo dữ liệu quan trọng chưa
        // Các bảng cần check: phieunhap (maND), phieuxuat (maNDXuat), phieubh (maND)
        
        $hasData = false;

        // Check phiếu nhập
        $sql1 = "SELECT COUNT(*) FROM phieunhap WHERE maND = :id";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([':id' => $id]);
        if ($stmt1->fetchColumn() > 0) $hasData = true;

        // Check phiếu xuất (chỉ check nếu chưa phát hiện)
        if (!$hasData) {
            $sql2 = "SELECT COUNT(*) FROM phieuxuat WHERE maNDXuat = :id";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([':id' => $id]);
            if ($stmt2->fetchColumn() > 0) $hasData = true;
        }

        // Check phiếu bảo hành
        if (!$hasData) {
            // Kiểm tra bảng phieubh nếu tồn tại column maND
            // Tạm thời giả định bảng phieubh có maND như context WarrantyModel
            $sql3 = "SELECT COUNT(*) FROM phieubh WHERE maND = :id";
            $stmt3 = $this->conn->prepare($sql3);
            $stmt3->execute([':id' => $id]);
            if ($stmt3->fetchColumn() > 0) $hasData = true;
        }

        if ($hasData) {
            // Nếu đã có dữ liệu => KHÔNG XÓA mà CHUYỂN TRẠNG THÁI hoatDong = 0 (Khóa)
            $sqlLock = "UPDATE nguoidung SET hoatDong = 0 WHERE maND = :id";
            $stmtLock = $this->conn->prepare($sqlLock);
            return $stmtLock->execute([':id' => $id]);
        } else {
             // Nếu chưa tạo gì cả => XÓA VĨNH VIỄN
            $sqlDel = "DELETE FROM nguoidung WHERE maND = :id";
            $stmtDel = $this->conn->prepare($sqlDel);
            return $stmtDel->execute([':id' => $id]);
        }
    }

    public function checkIdExists($id) {
        $sql = "SELECT COUNT(*) FROM nguoidung WHERE maND = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function checkUsernameExists($username) {
        $sql = "SELECT COUNT(*) FROM nguoidung WHERE taiKhoan = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetchColumn() > 0;
    }
}
?>