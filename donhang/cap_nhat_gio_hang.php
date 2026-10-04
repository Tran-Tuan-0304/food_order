<?php
session_start();

$id_mon = $_GET['id'];
$hanh_dong = $_GET['hanh_dong'];

if (isset($_SESSION['gio_hang'][$id_mon])) {
    if ($hanh_dong == 'tang') {
        $_SESSION['gio_hang'][$id_mon]['so_luong']++;
    } elseif ($hanh_dong == 'giam') {
        $_SESSION['gio_hang'][$id_mon]['so_luong']--;
        // Nếu giảm xuống 0 thì xóa luôn khỏi giỏ, không để số lượng âm
        if ($_SESSION['gio_hang'][$id_mon]['so_luong'] <= 0) {
            unset($_SESSION['gio_hang'][$id_mon]);
        }
    }
}

header("Location: ../cart.php");
exit;
?>