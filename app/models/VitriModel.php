<?php
class VitriModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM vitri ORDER BY day ASC, ke ASC, o ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM vitri WHERE maViTri = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $ma = $this->makeMaViTri($data['day'], $data['ke'], $data['o']);
        $sql = "INSERT INTO vitri (maViTri, day, ke, o) VALUES (:ma, :day, :ke, :o)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma,
            ':day' => $data['day'],
            ':ke' => $data['ke'],
            ':o' => $data['o']
        ]);
    }

    public function update($id, $data) {
        // allow updating day/ke/o but keep maViTri consistent
        $sql = "UPDATE vitri SET day = :day, ke = :ke, o = :o WHERE maViTri = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':day' => $data['day'],
            ':ke' => $data['ke'],
            ':o' => $data['o'],
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
