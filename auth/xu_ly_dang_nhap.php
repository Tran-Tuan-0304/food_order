<?php
include '../includes/header.php';
include '../config/ketnoi.php';

$email = $_POST['email'];
$mat_khau = $_POST['mat_khau'];
$quay_lai = $_POST['quay_lai'] ?? '';

$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$nguoi_dung = $stmt->get_result()->fetch_assoc();

if ($nguoi_dung && password_verify($mat_khau, $nguoi_dung['mat_khau'])) {
    $_SESSION['dang_nhap'] = true;
    $_SESSION['id_nguoi_dung'] = $nguoi_dung['id'];
    $_SESSION['ho_ten'] = $nguoi_dung['ho_ten'];
    $_SESSION['vai_tro'] = $nguoi_dung['vai_tro'];

    // Nếu có trang cần quay lại (VD: đang xem món ăn thì bị yêu cầu đăng nhập) -> quay đúng về đó
    // Kiểm tra chuỗi phải bắt đầu bằng /food_order/ để tránh bị lợi dụng chuyển hướng sang trang lạ
    if ($quay_lai !== '' && strpos($quay_lai, '/food_order/') === 0) {
        header("Location: " . $quay_lai);
    } else {
        header("Location: ../index.php");
    }
    exit;
} else {
    $link = "dang_nhap.php?loi=" . urlencode("Email hoặc mật khẩu không đúng");
    if ($quay_lai !== '' && strpos($quay_lai, '/food_order/') === 0) {
        $link .= "&quay_lai=" . urlencode($quay_lai);
    }
    header("Location: " . $link);
    exit;
}
?>