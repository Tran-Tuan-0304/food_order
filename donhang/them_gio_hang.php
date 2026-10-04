<?php
session_start();
include '../config/ketnoi.php';

$id_mon = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM mon_an WHERE id = ?");
$stmt->bind_param("i", $id_mon);
$stmt->execute();
$mon = $stmt->get_result()->fetch_assoc();

if (!isset($_SESSION['gio_hang'])) {
    $_SESSION['gio_hang'] = [];
}

if (isset($_SESSION['gio_hang'][$id_mon])) {
    $_SESSION['gio_hang'][$id_mon]['so_luong']++;
} else {
    $_SESSION['gio_hang'][$id_mon] = [
        'ten_mon' => $mon['ten_mon'],
        'gia'     => $mon['gia'],
        'so_luong' => 1
    ];
}

header("Location: ../index.php");
exit;
?>