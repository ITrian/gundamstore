<?php
class WarrantyModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Tìm thông tin theo Serial (Dành cho hàng có Serial)
    public function findBySerial($keyword) {
        // Logic mới: Join qua lohang -> phieunhap -> nhacungcap
        $sql = "SELECT s.serial, 'SERIAL' as loaiTimKiem,
                       hh.tenHH, hh.maHH, hh.model, hh.loaiHang,
                       lh.maLo, lh.ngayNhap, lh.hanBaoHanh,
                       ncc.tenNCC, ncc.maNCC
                FROM hanghoa_serial s
                JOIN lohang lh ON s.maLo = lh.maLo
                JOIN phieunhap pn ON lh.maPN = pn.maPN      -- Join thêm bảng này
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC -- Để lấy NCC
                JOIN hanghoa hh ON lh.maHH = hh.maHH
                WHERE s.serial = :keyword";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => $keyword]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Tìm theo Mã hàng hoặc Tên hàng (Dành cho hàng Lô)
    public function findByProduct($keyword) {
        // Logic mới: Lấy NCC từ phieunhap
        $sql = "SELECT lh.maLo, lh.ngayNhap, lh.hanBaoHanh,
                       hh.tenHH, hh.maHH, hh.model, hh.loaiHang,
                       ncc.tenNCC, ncc.maNCC,
                       'LO' as loaiTimKiem
                FROM lohang lh
                JOIN phieunhap pn ON lh.maPN = pn.maPN      -- Join thêm
                JOIN nhacungcap ncc ON pn.maNCC = ncc.maNCC -- Join thêm
                JOIN hanghoa hh ON lh.maHH = hh.maHH
                WHERE (hh.maHH = :keyword OR hh.tenHH LIKE :keywordLike)
                ORDER BY lh.ngayNhap DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'keyword' => $keyword,
            'keywordLike' => "%$keyword%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Tạo phiếu bảo hành (CẬP NHẬT QUAN TRỌNG: Thêm maHH)
    public function createTicket($data) {
        // Bảng phieubh mới yêu cầu cột maHH
        $sql = "INSERT INTO phieubh (maBH, maHH, serial, ngayNhan, moTaLoi, trangThai, maND) 
                VALUES (:maBH, :maHH, :serial, NOW(), :moTaLoi, 0, :maND)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // 4. Lấy chi tiết phiếu (Giữ nguyên logic nhưng query đơn giản hơn chút)
    public function getTicketDetail($maBH) {
        $sql = "SELECT p.*, nd.tenND, hh.tenHH 
                FROM phieubh p
                LEFT JOIN nguoidung nd ON p.maND = nd.maND
                LEFT JOIN hanghoa hh ON p.maHH = hh.maHH -- Join trực tiếp để lấy tên hàng
                WHERE p.maBH = :maBH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['maBH' => $maBH]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>