<?php
session_start();
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$id = $_POST['id'];

// Lấy trạng thái hiện tại, đảo ngược lại (đang bật -> tắt, đang tắt -> bật)
$stmt = $conn->prepare("SELECT noi_bat FROM mon_an WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hien_tai = $stmt->get_result()->fetch_assoc();
$gia_tri_moi = $hien_tai['noi_bat'] ? 0 : 1;

$stmt2 = $conn->prepare("UPDATE mon_an SET noi_bat = ? WHERE id = ?");
$stmt2->bind_param("ii", $gia_tri_moi, $id);
$stmt2->execute();

header('Content-Type: application/json');
echo json_encode(['thanh_cong' => true, 'noi_bat' => $gia_tri_moi]);
?>