<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$id = $_POST['id'];
$trang_thai_moi = $_POST['trang_thai'];
$ten_shipper = $_POST['ten_shipper'] ?? '';
$sdt_shipper = $_POST['sdt_shipper'] ?? '';

// Thứ tự đúng của quy trình xử lý đơn, dùng để chặn việc chọn lùi trạng thái
$thu_tu = ['cho_xac_nhan'=>0,'da_nhan'=>1,'dang_lam'=>2,'dang_giao'=>3,'hoan_thanh'=>4];

// Lấy đầy đủ thông tin đơn hiện tại TRƯỚC khi đổi, để biết trạng thái cũ và có dùng mã giảm giá không
$stmt0 = $conn->prepare("SELECT trang_thai, id_ma_giam_gia FROM don_hang WHERE id = ?");
$stmt0->bind_param("i", $id);
$stmt0->execute();
$don_cu = $stmt0->get_result()->fetch_assoc();
$trang_thai_cu = $don_cu['trang_thai'];

// Kiểm tra tính hợp lệ của việc đổi trạng thái
$hop_le = true;
if ($trang_thai_cu == 'hoan_thanh' || $trang_thai_cu == 'da_huy') {
    // Đơn đã kết thúc (hoàn thành hoặc đã hủy) thì không cho đổi nữa
    $hop_le = false;
} elseif ($trang_thai_moi != 'da_huy' && $thu_tu[$trang_thai_moi] < $thu_tu[$trang_thai_cu]) {
    // Không cho chọn một trạng thái "đứng trước" trạng thái hiện tại (trừ việc hủy đơn, luôn được phép)
    $hop_le = false;
}

if (!$hop_le) {
    header("Location: don_hang.php?loi=" . urlencode("Không thể đổi trạng thái đơn #$id theo hướng này"));
    exit;
}

// Thực hiện cập nhật
if ($trang_thai_moi == 'dang_giao') {
    $stmt = $conn->prepare("UPDATE don_hang SET trang_thai = ?, ten_shipper = ?, sdt_shipper = ? WHERE id = ?");
    $stmt->bind_param("sssi", $trang_thai_moi, $ten_shipper, $sdt_shipper, $id);
} else {
    $stmt = $conn->prepare("UPDATE don_hang SET trang_thai = ? WHERE id = ?");
    $stmt->bind_param("si", $trang_thai_moi, $id);
}
$stmt->execute();

// Nếu admin hủy đơn và đơn này có dùng mã giảm giá -> hoàn lại 1 lượt dùng cho mã đó
if ($trang_thai_moi == 'da_huy' && !empty($don_cu['id_ma_giam_gia'])) {
    $hoan = $conn->prepare("UPDATE ma_giam_gia SET so_luong = so_luong + 1 WHERE id = ?");
    $hoan->bind_param("i", $don_cu['id_ma_giam_gia']);
    $hoan->execute();
}

// Ghi lại mốc thời gian này vào lịch sử đơn hàng - dùng để vẽ thanh tiến độ cho khách xem
$ghi_chu_theo_trang_thai = [
    'cho_xac_nhan' => 'Đơn hàng đang chờ quán xác nhận',
    'da_nhan'      => 'Quán đã nhận đơn của bạn',
    'dang_lam'     => 'Quán đang chuẩn bị món ăn',
    'dang_giao'    => 'Đơn hàng đang được giao' . ($ten_shipper ? " bởi shipper $ten_shipper ($sdt_shipper)" : ''),
    'hoan_thanh'   => 'Đơn hàng đã giao thành công',
    'da_huy'       => 'Đơn hàng đã bị hủy',
];
$ghi_chu = $ghi_chu_theo_trang_thai[$trang_thai_moi] ?? '';

$stmt2 = $conn->prepare("INSERT INTO lich_su_don_hang (id_don_hang, trang_thai, ghi_chu) VALUES (?, ?, ?)");
$stmt2->bind_param("iss", $id, $trang_thai_moi, $ghi_chu);
$stmt2->execute();

header("Location: don_hang.php");
exit;
?>