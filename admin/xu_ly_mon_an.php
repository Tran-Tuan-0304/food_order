<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

// Xác định đang THÊM hay XÓA - lấy từ POST (form) hoặc GET (link xóa)
$hanh_dong = $_POST['hanh_dong'] ?? $_GET['hanh_dong'] ?? '';

if ($hanh_dong == 'them') {
    $ten_mon = $_POST['ten_mon'];
    $gia = $_POST['gia'];
    // Giá gốc: nếu ô để trống thì lưu NULL (không giảm giá), không lưu chuỗi rỗng
    $gia_goc = ($_POST['gia_goc'] !== '') ? $_POST['gia_goc'] : null;
    $gia_von = $_POST['gia_von'];
    $mo_ta = $_POST['mo_ta'];
    $id_danh_muc = $_POST['id_danh_muc'];

    $ten_file_anh = null;
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $ten_file_goc = basename($_FILES['hinh_anh']['name']);
        $ten_file_anh = time() . "_" . $ten_file_goc;
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../assets/img/" . $ten_file_anh);
    }

    $stmt = $conn->prepare("INSERT INTO mon_an (ten_mon, gia, gia_goc, gia_von, mo_ta, id_danh_muc, hinh_anh) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddisss", $ten_mon, $gia, $gia_goc, $gia_von, $mo_ta, $id_danh_muc, $ten_file_anh);
    $stmt->execute();

} elseif ($hanh_dong == 'sua') {
    $id = $_POST['id'];
    $ten_mon = $_POST['ten_mon'];
    $gia = $_POST['gia'];
    $gia_goc = ($_POST['gia_goc'] !== '') ? $_POST['gia_goc'] : null;
    $gia_von = $_POST['gia_von'];
    $mo_ta = $_POST['mo_ta'];
    $id_danh_muc = $_POST['id_danh_muc'];

    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $ten_file_goc = basename($_FILES['hinh_anh']['name']);
        $ten_file_anh = time() . "_" . $ten_file_goc;
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../assets/img/" . $ten_file_anh);

        $stmt = $conn->prepare("UPDATE mon_an SET ten_mon=?, gia=?, gia_goc=?, gia_von=?, mo_ta=?, id_danh_muc=?, hinh_anh=? WHERE id=?");
        $stmt->bind_param("sddisssi", $ten_mon, $gia, $gia_goc, $gia_von, $mo_ta, $id_danh_muc, $ten_file_anh, $id);
    } else {
        $stmt = $conn->prepare("UPDATE mon_an SET ten_mon=?, gia=?, gia_goc=?, gia_von=?, mo_ta=?, id_danh_muc=? WHERE id=?");
        $stmt->bind_param("sddissi", $ten_mon, $gia, $gia_goc, $gia_von, $mo_ta, $id_danh_muc, $id);
    }
    $stmt->execute();


} elseif ($hanh_dong == 'xoa') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM mon_an WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

} elseif ($hanh_dong == 'dat_noi_bat' || $hanh_dong == 'bo_noi_bat') {
    $id = $_GET['id'];
    $gia_tri = ($hanh_dong == 'dat_noi_bat') ? 1 : 0;
    $stmt = $conn->prepare("UPDATE mon_an SET noi_bat = ? WHERE id = ?");
    $stmt->bind_param("ii", $gia_tri, $id);
    $stmt->execute();

} elseif ($hanh_dong == 'het_hang' || $hanh_dong == 'con_hang_lai') {
    $id = $_GET['id'];
    $gia_tri = ($hanh_dong == 'con_hang_lai') ? 1 : 0;
    $stmt = $conn->prepare("UPDATE mon_an SET con_hang = ? WHERE id = ?");
    $stmt->bind_param("ii", $gia_tri, $id);
    $stmt->execute();
}

// Xử lý xong, quay lại trang danh sách
header("Location: mon_an.php");
exit;
?>