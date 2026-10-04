<?php
session_start();
include '../config/ketnoi.php';

// Chặn ở phía server - không chỉ dựa vào JS phía trình duyệt (JS có thể bị tắt hoặc lách qua)
if (!isset($_SESSION['dang_nhap'])) {
    header('Content-Type: application/json');
    echo json_encode(['thanh_cong' => false, 'loi' => 'Chưa đăng nhập']);
    exit;
}

$id_mon = $_POST['id'];
$so_luong_them = isset($_POST['so_luong']) ? (int)$_POST['so_luong'] : 1;

$stmt = $conn->prepare("SELECT * FROM mon_an WHERE id = ?");
$stmt->bind_param("i", $id_mon);
$stmt->execute();
$mon = $stmt->get_result()->fetch_assoc();

if (!isset($_SESSION['gio_hang'])) {
    $_SESSION['gio_hang'] = [];
}

if (isset($_SESSION['gio_hang'][$id_mon])) {
    $_SESSION['gio_hang'][$id_mon]['so_luong'] += $so_luong_them;
} else {
    $_SESSION['gio_hang'][$id_mon] = [
        'ten_mon'  => $mon['ten_mon'],
        'gia'      => $mon['gia'],
        'so_luong' => $so_luong_them,
        'hinh_anh' => $mon['hinh_anh']
    ];
}

$tong_so_luong = 0;
foreach ($_SESSION['gio_hang'] as $m) {
    $tong_so_luong += $m['so_luong'];
}

header('Content-Type: application/json');
echo json_encode([
    'thanh_cong' => true,
    'so_luong_gio' => $tong_so_luong
]);
?>