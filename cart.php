<?php
include 'includes/header.php';
include 'config/ham_dung_chung.php';
$tong_tien = 0;
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">🛒 Giỏ hàng của bạn</h2>

    <?php if (empty($_SESSION['gio_hang'])): ?>
        <p class="text-muted">Giỏ hàng đang trống.</p>
        <a href="index.php" class="btn btn-brand">Tiếp tục mua sắm</a>
    <?php else: ?>
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th></th><th>Tên món</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['gio_hang'] as $id => $mon):
                $thanh_tien = $mon['gia'] * $mon['so_luong'];
                $tong_tien += $thanh_tien;
                $anhSp = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
            ?>
            <tr>
                <td><img src="assets/img/<?php echo $anhSp; ?>" style="width:55px;height:55px;object-fit:cover;border-radius:8px;"></td>
                <td><?php echo $mon['ten_mon']; ?></td>
                <td><?php echo number_format($mon['gia']); ?>đ</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="donhang/cap_nhat_gio_hang.php?id=<?php echo $id; ?>&hanh_dong=giam" class="btn btn-outline-secondary">−</a>
                        <span class="btn btn-light disabled"><?php echo $mon['so_luong']; ?></span>
                        <a href="donhang/cap_nhat_gio_hang.php?id=<?php echo $id; ?>&hanh_dong=tang" class="btn btn-outline-secondary">+</a>
                    </div>
                </td>
                <td class="fw-bold"><?php echo number_format($thanh_tien); ?>đ</td>
                <td><a href="donhang/xoa_gio_hang.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa món này khỏi giỏ?')">Xóa</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <?php
        $phi_ship = tinhPhiShip($tong_tien);
        $tien_giam = $_SESSION['voucher']['tien_giam'] ?? 0;
        $tong_thanh_toan = $tong_tien + $phi_ship - $tien_giam;
        ?>

        <div class="row justify-content-end">
            <div class="col-md-5">

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <?php if (isset($_GET['loi_voucher'])): ?>
                            <div class="alert alert-danger py-2 small mb-2"><?php echo htmlspecialchars($_GET['loi_voucher']); ?></div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['voucher'])): ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>🎟️ Đã áp dụng mã <strong><?php echo htmlspecialchars($_SESSION['voucher']['ma']); ?></strong></span>
                                <a href="donhang/xoa_ma_giam_gia.php" class="text-danger small">Hủy mã</a>
                            </div>
                        <?php else: ?>
                            <form action="donhang/ap_ma_giam_gia.php" method="POST" class="d-flex gap-2">
                                <input type="text" name="ma_voucher" class="form-control" placeholder="Nhập mã giảm giá (VD: NGON10)">
                                <button type="submit" class="btn btn-outline-primary" style="--bs-btn-color:var(--primary); --bs-btn-border-color:var(--primary); --bs-btn-hover-bg:var(--primary); --bs-btn-hover-border-color:var(--primary);">Áp dụng</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($tong_tien); ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                Phí vận chuyển
                                <?php if ($phi_ship == 0): ?>
                                    <span class="badge" style="background:var(--secondary);">Miễn phí</span>
                                <?php endif; ?>
                            </span>
                            <span class="<?php echo $phi_ship == 0 ? 'text-decoration-line-through text-muted' : ''; ?>">
                                <?php echo number_format($phi_ship == 0 ? PHI_SHIP_MAC_DINH : $phi_ship); ?>đ
                            </span>
                        </div>
                        <?php if ($tong_tien < DON_TOI_THIEU_FREESHIP): ?>
                        <p class="small text-muted mb-2">
                            Mua thêm <strong><?php echo number_format(DON_TOI_THIEU_FREESHIP - $tong_tien); ?>đ</strong> để được miễn phí ship
                        </p>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['voucher'])): ?>
                        <div class="d-flex justify-content-between mb-2" style="color:var(--secondary);">
                            <span>Giảm giá</span>
                            <span>-<?php echo number_format($tien_giam); ?>đ</span>
                        </div>
                        <?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">Tổng cộng</span>
                            <span class="fw-bold fs-5" style="color:var(--primary);"><?php echo number_format($tong_thanh_toan); ?>đ</span>
                        </div>
                        <a href="donhang/dat_hang.php" class="btn btn-brand w-100">Tiến hành đặt hàng</a>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>