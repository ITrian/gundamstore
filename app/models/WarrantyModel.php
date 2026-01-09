<?php
class WarrantyModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 1. Tìm đích danh theo Serial (Giữ nguyên)
    public function findBySerial($keyword) {
        $sql = "SELECT s.serial, 'SERIAL' as loaiTimKiem,
                       hh.tenHH, hh.maHH, hh.model, hh.loaiHang,
                       lh.maLo, lh.ngayNhap, lh.hanBaoHanh,
                       n.tenNCC, n.maNCC
                FROM hanghoa_serial s
                JOIN lohang lh ON s.maLo = lh.maLo
                JOIN hanghoa hh ON lh.maHH = hh.maHH
                LEFT JOIN nhacungcap n ON hh.maNCC = n.maNCC
                WHERE s.serial = :keyword";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => $keyword]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. (MỚI) Tìm theo Mã hàng hoặc Tên hàng (Dành cho hàng Lô)
    public function findByProduct($keyword) {
        // Lấy danh sách các LÔ HÀNG của sản phẩm này
        $sql = "SELECT lh.maLo, lh.ngayNhap, lh.hanBaoHanh,
                       hh.tenHH, hh.maHH, hh.model, hh.loaiHang,
                       n.tenNCC, n.maNCC,
                       'LO' as loaiTimKiem
                FROM lohang lh
                JOIN hanghoa hh ON lh.maHH = hh.maHH
                LEFT JOIN nhacungcap n ON hh.maNCC = n.maNCC
                WHERE (hh.maHH = :keyword OR hh.tenHH LIKE :keywordLike)
                ORDER BY lh.ngayNhap DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'keyword' => $keyword,
            'keywordLike' => "%$keyword%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Tạo phiếu bảo hành (Nâng cấp để cho phép Serial bị NULL)
    public function createTicket($data) {
        $sql = "INSERT INTO phieubh (maBH, serial, ngayNhan, moTaLoi, trangThai, maND) 
                VALUES (:maBH, :serial, NOW(), :moTaLoi, 0, :maND)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function getTicketDetail($maBH) {
        // Lấy thông tin phiếu + Tên nhân viên tạo
        $sql = "SELECT p.*, nd.tenND 
                FROM phieubh p
                LEFT JOIN nguoidung nd ON p.maND = nd.maND
                WHERE p.maBH = :maBH";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['maBH' => $maBH]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Lấy thêm tên sản phẩm dựa vào mã Serial/Lô lưu trong phiếu
            $ticket['tenSP'] = $this->getProductNameByCode($ticket['serial']);
        }

        return $ticket;
    }

    // Hàm phụ: Tìm tên sản phẩm dựa trên Serial hoặc Mã Lô
    private function getProductNameByCode($code) {
        // Cách 1: Thử tìm trong bảng Serial
        $sqlSerial = "SELECT hh.tenHH 
                      FROM hanghoa_serial s 
                      JOIN lohang lh ON s.maLo = lh.maLo
                      JOIN hanghoa hh ON lh.maHH = hh.maHH
                      WHERE s.serial = :code";
        $stmt = $this->conn->prepare($sqlSerial);
        $stmt->execute(['code' => $code]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) return $result['tenHH'];

        // Cách 2: Nếu không thấy, thử tìm trong bảng Lô hàng
        $sqlLo = "SELECT hh.tenHH 
                  FROM lohang lh
                  JOIN hanghoa hh ON lh.maHH = hh.maHH
                  WHERE lh.maLo = :code";
        $stmt2 = $this->conn->prepare($sqlLo);
        $stmt2->execute(['code' => $code]);
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        return $result2 ? $result2['tenHH'] : "Không xác định";
    }
}
?>