<?php
session_start();

$id_mon = $_GET['id'];

// Nếu món này có trong giỏ thì xóa hẳn phần tử đó ra khỏi mảng
if (isset($_SESSION['gio_hang'][$id_mon])) {
    unset($_SESSION['gio_hang'][$id_mon]);
}

header("Location: ../cart.php");
exit;
?>