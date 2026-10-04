<?php
include '../includes/header.php';
include 'kiem_tra_quyen.php';
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">👤 Trang quản trị — Xin chào, <?php echo $_SESSION['ho_ten']; ?></h2>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="mon_an.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">🍽️</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Quản lý món ăn</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="banner.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">🖼️</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Quản lý banner</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="don_hang.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">📦</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Quản lý đơn hàng</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="ma_giam_gia.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">🎟️</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Quản lý mã giảm giá</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="chi_phi.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">💰</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Quản lý chi phí</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="thong_ke.php" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="fs-1 mb-2">📊</div>
                        <h5 class="fw-bold" style="color:var(--dark);">Thống kê doanh thu</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="mt-4">
        <a href="../auth/dang_xuat.php" class="btn btn-outline-danger">Đăng xuất</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>