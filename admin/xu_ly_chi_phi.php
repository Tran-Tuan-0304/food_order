<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$hanh_dong = $_POST['hanh_dong'] ?? $_GET['hanh_dong'] ?? '';

if ($hanh_dong == 'them') {
    $ten_chi_phi = $_POST['ten_chi_phi'];
    $so_tien = $_POST['so_tien'];
    $ngay = $_POST['ngay'];
    $ghi_chu = $_POST['ghi_chu'];

    $stmt = $conn->prepare("INSERT INTO chi_phi (ten_chi_phi, so_tien, ngay, ghi_chu) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $ten_chi_phi, $so_tien, $ngay, $ghi_chu);
    $stmt->execute();

} elseif ($hanh_dong == 'xoa') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM chi_phi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: chi_phi.php");
exit;
?>