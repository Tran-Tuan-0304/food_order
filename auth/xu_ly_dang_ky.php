<?php
include '../includes/header.php';
include '../config/ketnoi.php';

$ho_ten = $_POST['ho_ten'];
$email = $_POST['email'];
$mat_khau = $_POST['mat_khau'];
$sdt = $_POST['sdt'];
$quay_lai = $_POST['quay_lai'] ?? '';

$stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$ket_qua = $stmt->get_result();

// Hàm nhỏ: ghép sẵn tham số quay_lai vào link, nếu có, để không bị mất qua từng bước chuyển trang
function linkKemQuayLai($duong_dan, $quay_lai) {
    if ($quay_lai !== '' && strpos($quay_lai, '/food_order/') === 0) {
        return $duong_dan . "?quay_lai=" . urlencode($quay_lai);
    }
    return $duong_dan;
}

if ($ket_qua->num_rows > 0) {
    $link = linkKemQuayLai("dang_ky.php", $quay_lai);
    $link .= (strpos($link, '?') !== false ? '&' : '?') . "loi=" . urlencode("Email này đã được đăng ký");
    header("Location: " . $link);
    exit;
}

$mat_khau_ma_hoa = password_hash($mat_khau, PASSWORD_DEFAULT);

$stmt2 = $conn->prepare("INSERT INTO nguoi_dung (ho_ten, email, mat_khau, sdt, vai_tro) VALUES (?, ?, ?, ?, 'khach_hang')");
$stmt2->bind_param("ssss", $ho_ten, $email, $mat_khau_ma_hoa, $sdt);
$stmt2->execute();

header("Location: " . linkKemQuayLai("dang_nhap.php", $quay_lai));
exit;
?>