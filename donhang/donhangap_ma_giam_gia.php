<?php
session_start();
include '../config/ketnoi.php';

$ma_nhap = trim($_POST['ma_voucher'] ?? '');

// Tính tổng tiền hiện tại trong giỏ (chưa gồm giảm giá) để kiểm tra điều kiện "đơn tối thiểu"
$tong_tien = 0;
if (isset($_SESSION['gio_hang'])) {
    foreach ($_SESSION['gio_hang'] as $mon) {
        $tong_tien += $mon['gia'] * $mon['so_luong'];
    }
}

function veLaiCartVoiLoi($thong_diep) {
    header("Location: ../cart.php?loi_voucher=" . urlencode($thong_diep));
    exit;
}

if ($ma_nhap === '') {
    veLaiCartVoiLoi("Vui lòng nhập mã giảm giá");
}

$stmt = $conn->prepare("SELECT * FROM ma_giam_gia WHERE ma = ?");
$stmt->bind_param("s", $ma_nhap);
$stmt->execute();
$voucher = $stmt->get_result()->fetch_assoc();

// Kiểm tra lần lượt từng điều kiện, báo đúng lý do khách không dùng được
if (!$voucher) {
    veLaiCartVoiLoi("Mã giảm giá không tồn tại");
}
if (!$voucher['kich_hoat']) {
    veLaiCartVoiLoi("Mã giảm giá này đã ngừng áp dụng");
}
if ($voucher['han_dung'] !== null && $voucher['han_dung'] < date('Y-m-d')) {
    veLaiCartVoiLoi("Mã giảm giá đã hết hạn");
}
if ($voucher['so_luong'] <= 0) {
    veLaiCartVoiLoi("Mã giảm giá đã hết lượt sử dụng");
}
if ($tong_tien < $voucher['don_toi_thieu']) {
    veLaiCartVoiLoi("Đơn hàng cần tối thiểu " . number_format($voucher['don_toi_thieu']) . "đ để dùng mã này");
}

// Tính số tiền được giảm
if ($voucher['loai'] == 'phan_tram') {
    $tien_giam = round($tong_tien * $voucher['gia_tri'] / 100);
    // Nếu có giới hạn giảm tối đa, không cho giảm vượt quá mức đó
    if ($voucher['giam_toi_da'] !== null && $tien_giam > $voucher['giam_toi_da']) {
        $tien_giam = $voucher['giam_toi_da'];
    }
} else {
    // Giảm tiền mặt cố định - không để số tiền giảm vượt quá tổng đơn
    $tien_giam = min($voucher['gia_tri'], $tong_tien);
}

// Lưu voucher đang áp dụng vào Session, dùng xuyên suốt tới lúc đặt hàng
$_SESSION['voucher'] = [
    'id'        => $voucher['id'],
    'ma'        => $voucher['ma'],
    'tien_giam' => $tien_giam
];

header("Location: ../cart.php");
exit;
?>