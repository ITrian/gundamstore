<?php
class ImportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Lấy danh sách NCC để đổ vào Select box
    public function getSuppliers() {
        $stmt = $this->conn->prepare("SELECT * FROM NHACUNGCAP");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy danh sách Hàng hóa
    public function getProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM HANGHOA ORDER BY tenHH ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // HÀM QUAN TRỌNG: Tạo phiếu nhập + Chi tiết + Lô hàng + Tồn kho
    public function createImportTransaction($data, $products) {
        try {
            // 1. Bắt đầu giao dịch
            $this->conn->beginTransaction();

            // 2. Tạo Phiếu Nhập (PHIEUNHAP)
            $sqlPN = "INSERT INTO PHIEUNHAP (maPN, ngayNhap, maNCC, ghiChu, maND) 
                      VALUES (:maPN, NOW(), :maNCC, :ghiChu, :maND)";
            $stmtPN = $this->conn->prepare($sqlPN);
            $stmtPN->execute([
                ':maPN' => $data['maPN'],
                ':maNCC' => $data['maNCC'],
                ':ghiChu' => $data['ghiChu'],
                ':maND' => $data['maND']
            ]);

            // 3. Xử lý từng mặt hàng trong danh sách
            foreach ($products as $item) {
                // A. Tạo Mã Lô tự động (Ví dụ: LO-[Mã PN]-[Mã HH])
                $maLo = 'LO-' . $data['maPN'] . '-' . $item['maHH']; 

                // B. Thêm vào bảng LOHANG
                $sqlLo = "INSERT INTO LOHANG (maLo, maPN, maHH, maNCC, soLuongNhap, ngayNhap, hanBaoHanh) 
                          VALUES (:maLo, :maPN, :maHH, :maNCC, :soLuong, NOW(), :hsd)";
                $stmtLo = $this->conn->prepare($sqlLo);
                $stmtLo->execute([
                    ':maLo' => $maLo,
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':maNCC' => $data['maNCC'],
                    ':soLuong' => $item['soLuong'],
                    ':hsd' => !empty($item['hsd']) ? $item['hsd'] : NULL // Cho phép null nếu không nhập
                ]);

                // C. Thêm vào bảng TONKHO
                $sqlTon = "INSERT INTO TONKHO (maLo, soLuongTon) VALUES (:maLo, :soLuong)";
                $stmtTon = $this->conn->prepare($sqlTon);
                $stmtTon->execute([
                    ':maLo' => $maLo,
                    ':soLuong' => $item['soLuong']
                ]);

                // D. Thêm vào bảng CT_PHIEUNHAP
                $sqlCT = "INSERT INTO CT_PHIEUNHAP (maPN, maHH, soLuong, donGia) 
                          VALUES (:maPN, :maHH, :soLuong, :donGia)";
                $stmtCT = $this->conn->prepare($sqlCT);
                $stmtCT->execute([
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':donGia' => $item['donGia']
                ]);
            }

            // 4. Nếu mọi thứ Ok -> Lưu vào DB
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Nếu có lỗi -> Hủy toàn bộ thao tác nãy giờ
            $this->conn->rollBack();
            // Ghi log lỗi để debug nếu cần: echo $e->getMessage();
            return false;
        }
    }
    // --- BỔ SUNG HÀM NÀY VÀO CUỐI CLASS ---
    
    // Lấy lịch sử nhập kho
    public function getAllImports() {
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND 
                FROM PHIEUNHAP pn
                JOIN NHACUNGCAP ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN NGUOIDUNG nd ON pn.maND = nd.maND
                ORDER BY pn.ngayNhap DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>