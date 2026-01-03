<?php
// File: public/reset_password.php

// 1. Cấu hình Database (Bạn kiểm tra lại user/pass/dbname của máy bạn nếu khác)
$host = 'localhost';
$dbname = 'khohanggiadung';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Tạo mã hash chuẩn từ chính máy của bạn
    $passMoi = '123456';
    $hashMoi = password_hash($passMoi, PASSWORD_DEFAULT);

    // 3. Cập nhật trực tiếp vào DB
    $sql = "UPDATE nguoidung SET matKhau = :pass WHERE taiKhoan = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['pass' => $hashMoi]);

    echo "<h1>Thành công!</h1>";
    echo "<p>Mật khẩu của admin đã được reset về: <b>123456</b></p>";
    echo "<p>Mã hash mới: " . $hashMoi . "</p>";
    echo "<p>Hãy xóa file này và thử đăng nhập lại.</p>";

} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}
?>