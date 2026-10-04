<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_order_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt charset để hiển thị tiếng Việt không bị lỗi font
$conn->set_charset("utf8mb4");
?>