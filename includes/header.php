<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$so_luong_gio = 0;
if (isset($_SESSION['gio_hang'])) {
    foreach ($_SESSION['gio_hang'] as $mon) {
        $so_luong_gio += $mon['so_luong'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>😋 Ngon Mỗi Ngày - Đặt đồ ăn online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/food_order/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: var(--primary);">
  <div class="container">
    <!-- Bấm vào logo/tên quán sẽ về trang chủ - không cần link "Trang chủ" riêng nữa -->
    <a class="navbar-brand fw-bold fs-4" href="/food_order/index.php">😋 Ngon Mỗi Ngày</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">

      <!-- Ô tìm kiếm chuyển lên thanh menu, hiện ở mọi trang -->
      <form action="/food_order/index.php" method="GET" class="d-flex mx-lg-4 my-2 my-lg-0 flex-grow-1 position-relative" style="max-width:400px;">
    <input type="text" name="tim" id="o-tim-kiem" class="form-control form-control-sm"
           placeholder="Tìm món ăn..."
           style="<?php echo isset($_GET['tim']) && $_GET['tim'] != '' ? 'padding-right:28px;' : ''; ?>"
           value="<?php echo isset($_GET['tim']) ? htmlspecialchars($_GET['tim']) : ''; ?>">

    <?php if (isset($_GET['tim']) && $_GET['tim'] != ''): ?>
    <!-- Nút xóa tìm kiếm - chỉ hiện khi đang có từ khóa, bấm là về thẳng trang chủ -->
    <a href="/food_order/index.php" title="Xóa tìm kiếm"
       style="position:absolute; right:52px; top:50%; transform:translateY(-50%); color:#999; text-decoration:none; font-size:14px; line-height:1;">
        ✕
    </a>
    <?php endif; ?>

    <button class="btn btn-light btn-sm ms-1" type="submit">🔍</button>
</form>

      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item">
          <a class="nav-link" href="/food_order/cart.php">
            Giỏ hàng
            <span id="badge-gio-hang" class="badge rounded-pill"
                  style="background:#fff;color:var(--primary); <?php echo $so_luong_gio>0?'':'display:none;'; ?>">
                <?php echo $so_luong_gio; ?>
            </span>
          </a>
        </li>
        <?php if (isset($_SESSION['dang_nhap'])): ?>
          <li class="nav-item">
            <a class="btn btn-light btn-sm fw-bold ms-lg-2" href="<?php echo $_SESSION['vai_tro']=='admin' ? '/food_order/admin/tai_khoan.php' : '/food_order/tai_khoan.php'; ?>">
              👤 Xin chào, <?php echo $_SESSION['ho_ten']; ?>
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-light btn-sm fw-bold ms-lg-2" href="/food_order/auth/dang_nhap.php">Đăng nhập</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<a href="/food_order/cart.php" class="gio-hang-noi" title="Xem giỏ hàng">
    🛒
    <span id="badge-gio-hang-noi" class="badge-noi" style="<?php echo $so_luong_gio>0?'':'display:none;'; ?>">
        <?php echo $so_luong_gio; ?>
    </span>
</a>    