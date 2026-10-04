<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include '../config/ham_dung_chung.php';

$ten_khach = $_POST['ten_khach'];
$sdt = $_POST['sdt'];
$dia_chi = $_POST['dia_chi'];
$phuong_thuc_tt = $_POST['phuong_thuc_tt'] ?? 'cod';

$tong_tien = 0;
foreach ($_SESSION['gio_hang'] as $mon) {
    $tong_tien += $mon['gia'] * $mon['so_luong'];
}

$phi_ship = tinhPhiShip($tong_tien);
$id_ma_giam_gia = $_SESSION['voucher']['id'] ?? null;
$tien_giam = $_SESSION['voucher']['tien_giam'] ?? 0;
$tong_thanh_toan = $tong_tien + $phi_ship - $tien_giam;

$id_nguoi_dung = isset($_SESSION['dang_nhap']) ? $_SESSION['id_nguoi_dung'] : null;

$stmt = $conn->prepare("INSERT INTO don_hang (id_nguoi_dung, ten_khach, sdt, dia_chi_giao, tong_tien, id_ma_giam_gia, tien_giam, phi_ship, phuong_thuc_tt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssdidds", $id_nguoi_dung, $ten_khach, $sdt, $dia_chi, $tong_thanh_toan, $id_ma_giam_gia, $tien_giam, $phi_ship, $phuong_thuc_tt);
$stmt->execute();

$id_don_hang_moi = $conn->insert_id;

$stmt2 = $conn->prepare("INSERT INTO chi_tiet_don_hang (id_don_hang, id_mon, so_luong, don_gia) VALUES (?, ?, ?, ?)");
foreach ($_SESSION['gio_hang'] as $id_mon => $mon) {
    $stmt2->bind_param("iiid", $id_don_hang_moi, $id_mon, $mon['so_luong'], $mon['gia']);
    $stmt2->execute();
}

// Nếu đã đăng nhập, cập nhật lại SĐT/địa chỉ mới nhất vào hồ sơ - để lần đặt sau tự điền đúng thông tin này
if ($id_nguoi_dung) {
    $stmt3 = $conn->prepare("UPDATE nguoi_dung SET sdt = ?, dia_chi = ? WHERE id = ?");
    $stmt3->bind_param("ssi", $sdt, $dia_chi, $id_nguoi_dung);
    $stmt3->execute();
}

// Nếu có dùng voucher, trừ đi 1 lượt sử dụng còn lại
if ($id_ma_giam_gia) {
    $stmt3 = $conn->prepare("UPDATE ma_giam_gia SET so_luong = so_luong - 1 WHERE id = ?");
    $stmt3->bind_param("i", $id_ma_giam_gia);
    $stmt3->execute();
}
// Ghi mốc đầu tiên vào lịch sử đơn hàng, để thanh tiến độ có điểm bắt đầu
$stmt4 = $conn->prepare("INSERT INTO lich_su_don_hang (id_don_hang, trang_thai, ghi_chu) VALUES (?, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận')");
$stmt4->bind_param("i", $id_don_hang_moi);
$stmt4->execute();

unset($_SESSION['gio_hang']);
unset($_SESSION['voucher']);

header("Location: thanh_cong.php?id=" . $id_don_hang_moi);
exit;
?>