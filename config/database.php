<?php
$host = '127.0.0.1';
$dbname = 'bus_management';
$username = 'root';
$password = ''; // Mật khẩu root MySQL của bạn (để trống nếu dùng mặc định)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>
