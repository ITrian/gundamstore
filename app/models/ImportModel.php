<?php
class ImportModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Lấy danh sách phiếu nhập để hiển thị
   public function getAllReceipts() {
        // JOIN bảng PHIEUNHAP với NHACUNGCAP và NGUOIDUNG
        // Để lấy được tenNCC và tenND thay vì chỉ hiện mã số
        $sql = "SELECT pn.*, ncc.tenNCC, nd.tenND 
                FROM PHIEUNHAP pn
                JOIN NHACUNGCAP ncc ON pn.maNCC = ncc.maNCC
                LEFT JOIN NGUOIDUNG nd ON pn.maND = nd.maND
                ORDER BY pn.ngayNhap DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Xử lý tạo phiếu nhập (QUAN TRỌNG NHẤT)
    public function createReceipt($data, $details) {
        // $data: Thông tin chung (maPN, maNCC, ...)
        // $details: Mảng các mặt hàng (maHH, soLuong, donGia...)

        try {
            // Bắt đầu giao dịch (Transaction)
            $this->conn->beginTransaction();

            // A. Insert bảng PHIEUNHAP
            $sql = "INSERT INTO PHIEUNHAP (maPN, maNCC, maND, ghiChu, ngayNhap) 
                    VALUES (:maPN, :maNCC, :maND, :ghiChu, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);

            // B. Duyệt qua từng sản phẩm để xử lý
            foreach ($details as $item) {
                // 1. Insert Chi tiết phiếu nhập
                $sqlDetail = "INSERT INTO CT_PHIEUNHAP (maPN, maHH, soLuong, donGia) 
                              VALUES (:maPN, :maHH, :soLuong, :donGia)";
                $stmtDetail = $this->conn->prepare($sqlDetail);
                $stmtDetail->execute([
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':soLuong' => $item['soLuong'],
                    ':donGia' => $item['donGia']
                ]);

                // 2. Tạo mã Lô hàng tự động (Ví dụ: LO + Mã HH + Time)
                $maLo = 'LO-' . $item['maHH'] . '-' . time(); // VD: LO-SP01-16999999
                
                // 3. Insert Bảng LOHANG
                $sqlLo = "INSERT INTO LOHANG (maLo, maPN, maHH, maNCC, soLuongNhap, ngayNhap, hanBaoHanh) 
                          VALUES (:maLo, :maPN, :maHH, :maNCC, :soLuong, NOW(), :hanBaoHanh)";
                $stmtLo = $this->conn->prepare($sqlLo);
                $stmtLo->execute([
                    ':maLo' => $maLo,
                    ':maPN' => $data['maPN'],
                    ':maHH' => $item['maHH'],
                    ':maNCC' => $data['maNCC'],
                    ':soLuong' => $item['soLuong'],
                    ':hanBaoHanh' => $item['hanBaoHanh'] ?? null
                ]);

                // 4. Insert hoặc Update bảng TONKHO
                // Logic: Mỗi Lô hàng là duy nhất, nên Insert mới vào TONKHO
                $sqlTon = "INSERT INTO TONKHO (maLo, soLuongTon) VALUES (:maLo, :soLuongTon)";
                $stmtTon = $this->conn->prepare($sqlTon);
                $stmtTon->execute([
                    ':maLo' => $maLo,
                    ':soLuongTon' => $item['soLuong']
                ]);
            }

            // Nếu chạy đến đây không lỗi -> Lưu tất cả
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Nếu có lỗi -> Hủy toàn bộ thao tác
            $this->conn->rollBack();
            return "Lỗi: " . $e->getMessage();
        }
    }
}
?>