<?php
// Nếu chưa đăng nhập, hoặc đăng nhập nhưng không phải admin -> đá về trang chủ
if (!isset($_SESSION['dang_nhap']) || $_SESSION['vai_tro'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
?>