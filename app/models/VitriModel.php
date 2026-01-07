<?php
class VitriModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        // Return positions with their capacity and current occupied points (sum of soLuong * heSoChiemCho)
        $sql = "SELECT v.maViTri, v.day, v.ke, v.o, v.sucChuaToiDa, v.trangThai,
                       COALESCE(SUM(lvt.soLuong * hh.heSoChiemCho), 0) AS totalAtPosition
                FROM vitri v
                LEFT JOIN lo_hang_vi_tri lvt ON lvt.maViTri = v.maViTri
                LEFT JOIN lohang lh ON lvt.maLo = lh.maLo
                LEFT JOIN hanghoa hh ON lh.maHH = hh.maHH
                GROUP BY v.maViTri, v.day, v.ke, v.o, v.sucChuaToiDa, v.trangThai
                ORDER BY v.day ASC, v.ke ASC, v.o ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT maViTri, day, ke, o, sucChuaToiDa, trangThai FROM vitri WHERE maViTri = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $ma = $this->makeMaViTri($data['day'], $data['ke'], $data['o']);
        $sql = "INSERT INTO vitri (maViTri, day, ke, o, sucChuaToiDa, trangThai) VALUES (:ma, :day, :ke, :o, :suc, :tt)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma,
            ':day' => $data['day'],
            ':ke' => $data['ke'],
            ':o' => $data['o'],
            ':suc' => isset($data['sucChuaToiDa']) ? (int)$data['sucChuaToiDa'] : 100,
            ':tt' => isset($data['trangThai']) ? (int)$data['trangThai'] : 1
        ]);
    }

    public function update($id, $data) {
        // allow updating day/ke/o but keep maViTri consistent
        $sql = "UPDATE vitri SET day = :day, ke = :ke, o = :o, sucChuaToiDa = :suc, trangThai = :tt WHERE maViTri = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':day' => $data['day'],
            ':ke' => $data['ke'],
            ':o' => $data['o'],
            ':suc' => isset($data['sucChuaToiDa']) ? (int)$data['sucChuaToiDa'] : 100,
            ':tt' => isset($data['trangThai']) ? (int)$data['trangThai'] : 1,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM vitri WHERE maViTri = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function exists($ma) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vitri WHERE maViTri = :ma");
        $stmt->execute([':ma' => $ma]);
        return $stmt->fetchColumn() > 0;
    }

    private function makeMaViTri($day, $ke, $o) {
        // normalize and produce code like D1-K2-O03 or DAY1-KE2-O3 depending on input
        $d = preg_replace('/\s+/', '', strtoupper($day));
        $k = preg_replace('/\s+/', '', strtoupper($ke));
        $o = preg_replace('/\s+/', '', strtoupper($o));
        return sprintf('%s-%s-%s', $d, $k, $o);
    }
}
