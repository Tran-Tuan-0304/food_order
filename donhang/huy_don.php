<?php
session_start();
include '../config/ketnoi.php';

if (!isset($_SESSION['dang_nhap'])) {
    header("Location: ../auth/dang_nhap.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ? AND id_nguoi_dung = ?");
$stmt->bind_param("ii", $id, $_SESSION['id_nguoi_dung']);
$stmt->execute();
$don = $stmt->get_result()->fetch_assoc();

if ($don && $don['trang_thai'] == 'cho_xac_nhan') {
    $upd = $conn->prepare("UPDATE don_hang SET trang_thai = 'da_huy' WHERE id = ?");
    $upd->bind_param("i", $id);
    $upd->execute();

    // Nếu đơn có dùng mã giảm giá -> hoàn lại 1 lượt dùng cho mã đó, vì đơn chưa thực sự hoàn tất
    if (!empty($don['id_ma_giam_gia'])) {
        $hoan = $conn->prepare("UPDATE ma_giam_gia SET so_luong = so_luong + 1 WHERE id = ?");
        $hoan->bind_param("i", $don['id_ma_giam_gia']);
        $hoan->execute();
    }

    $log = $conn->prepare("INSERT INTO lich_su_don_hang (id_don_hang, trang_thai, ghi_chu) VALUES (?, 'da_huy', 'Khách hàng đã hủy đơn hàng')");
    $log->bind_param("i", $id);
    $log->execute();
}

header("Location: don_hang_cua_toi.php");
exit;
?>