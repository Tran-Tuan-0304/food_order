<?php
session_start();
unset($_SESSION['voucher']);
header("Location: ../cart.php");
exit;
?>