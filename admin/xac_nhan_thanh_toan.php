<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$id = $_GET['id'];

$stmt = $conn->prepare("UPDATE don_hang SET trang_thai_tt = 'da_thanh_toan' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: don_hang.php");
exit;
?>