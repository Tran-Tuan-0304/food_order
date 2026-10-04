<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$hanh_dong = $_POST['hanh_dong'] ?? $_GET['hanh_dong'] ?? '';

if ($hanh_dong == 'them') {
    $ma = strtoupper(trim($_POST['ma']));
    $loai = $_POST['loai'];
    $gia_tri = $_POST['gia_tri'];
    $don_toi_thieu = $_POST['don_toi_thieu'] !== '' ? $_POST['don_toi_thieu'] : 0;
    $giam_toi_da = $_POST['giam_toi_da'] !== '' ? $_POST['giam_toi_da'] : null;
    $so_luong = $_POST['so_luong'];
    $han_dung = $_POST['han_dung'] !== '' ? $_POST['han_dung'] : null;

    $stmt = $conn->prepare("INSERT INTO ma_giam_gia (ma, loai, gia_tri, don_toi_thieu, giam_toi_da, so_luong, han_dung) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdddis", $ma, $loai, $gia_tri, $don_toi_thieu, $giam_toi_da, $so_luong, $han_dung);
    $stmt->execute();

} elseif ($hanh_dong == 'xoa') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM ma_giam_gia WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

} elseif ($hanh_dong == 'tat' || $hanh_dong == 'bat') {
    $id = $_GET['id'];
    $trang_thai = $hanh_dong == 'bat' ? 1 : 0;
    $stmt = $conn->prepare("UPDATE ma_giam_gia SET kich_hoat = ? WHERE id = ?");
    $stmt->bind_param("ii", $trang_thai, $id);
    $stmt->execute();
}

header("Location: ma_giam_gia.php");
exit;
?>