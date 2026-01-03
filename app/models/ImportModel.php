<?php
class ImportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ... (Giữ nguyên các hàm getSuppliers, getProducts) ...

    public function getSuppliers() {
        $stmt = $this->conn->prepare("SELECT * FROM nhacungcap");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM hanghoa ORDER BY tenHH ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllImports() {
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND 
                FROM phieunhap pn
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN nguoidung nd ON pn.maND = nd.maND
                ORDER BY pn.ngayNhap DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createImportTransaction($data, $products) {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo Phiếu Nhập
            $sqlPN = "INSERT INTO phieunhap (maPN, ngayNhap, maNCC, ghiChu, maND) 
                      VALUES (:maPN, NOW(), :maNCC, :ghiChu, :maND)";
            $stmtPN = $this->conn->prepare($sqlPN);
            $stmtPN->execute([
                ':maPN' => $data['maPN'],
                ':maNCC' => $data['maNCC'],
                ':ghiChu' => $data['ghiChu'],
                ':maND' => $data['maND']
            ]);

            // Lấy một vị trí mặc định (Vị trí đầu tiên trong DB) để xếp hàng vào
            // Thực tế bạn nên cho người dùng chọn vị trí trên Form
            $stmtVT = $this->conn->query("SELECT maViTri FROM vitri LIMIT 1");
            $defaultViTri = $stmtVT->fetchColumn();

            if (!$defaultViTri) {
                throw new Exception("Chưa có dữ liệu Vị trí (Kệ hàng) trong hệ thống!");
            }

            foreach ($products as $item) {
                $maLo = 'LO-' . $data['maPN'] . '-' . $item['maHH']; 

                // 2. Thêm vào bảng lohang
                $sqlLo = "INSERT INTO lohang (maLo, maPN, maHH, soLuongNhap, ngayNhap, hanBaoHanh) 
                          VALUES (:maLo, :maPN, :maHH, :soLuong, NOW(), :hsd)";
                $stmtLo = $this->conn->prepare($sqlLo);
                $stmtLo->execute([
                    ':maLo' => $maLo,
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':hsd' => !empty($item['hsd']) ? $item['hsd'] : NULL
                ]);

                // 3. SỬA: Thêm vào bảng lo_hang_vi_tri (Thay vì TONKHO)
                $sqlTon = "INSERT INTO lo_hang_vi_tri (maLo, maViTri, soLuong) VALUES (:maLo, :maViTri, :soLuong)";
                $stmtTon = $this->conn->prepare($sqlTon);
                $stmtTon->execute([
                    ':maLo' => $maLo,
                    ':maViTri' => $defaultViTri, // Tạm thời nhét hết vào vị trí đầu tiên
                    ':soLuong' => $item['soLuong']
                ]);

                // 4. Thêm vào chi tiết phiếu nhập
                $sqlCT = "INSERT INTO ct_phieunhap (maPN, maHH, soLuong, donGia) 
                          VALUES (:maPN, :maHH, :soLuong, :donGia)";
                $stmtCT = $this->conn->prepare($sqlCT);
                $stmtCT->execute([
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':donGia' => $item['donGia']
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // Uncomment để xem lỗi nếu cần: echo $e->getMessage(); die();
            return false;
        }
    }
}
?>