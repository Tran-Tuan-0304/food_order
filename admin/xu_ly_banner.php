<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$hanh_dong = $_POST['hanh_dong'] ?? $_GET['hanh_dong'] ?? '';

if ($hanh_dong == 'them') {
    $ten_file_anh = null;
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $ten_file_goc = basename($_FILES['hinh_anh']['name']);
        $ten_file_anh = time() . "_" . $ten_file_goc;
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../assets/img/" . $ten_file_anh);
    }
    $tieu_de = $_POST['tieu_de'];
    $thu_tu = (int)$_POST['thu_tu'];

    $stmt = $conn->prepare("INSERT INTO banner (hinh_anh, tieu_de, thu_tu) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $ten_file_anh, $tieu_de, $thu_tu);
    $stmt->execute();

} elseif ($hanh_dong == 'xoa') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM banner WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

} elseif ($hanh_dong == 'tat' || $hanh_dong == 'bat') {
    $id = $_GET['id'];
    $trang_thai = $hanh_dong == 'bat' ? 1 : 0;
    $stmt = $conn->prepare("UPDATE banner SET kich_hoat = ? WHERE id = ?");
    $stmt->bind_param("ii", $trang_thai, $id);
    $stmt->execute();
}

header("Location: banner.php");
exit;
?>