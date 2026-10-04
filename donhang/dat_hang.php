<?php
include '../includes/header.php';
include '../config/ketnoi.php'; // thêm dòng này để lấy được thông tin khách
include '../config/ham_dung_chung.php';

if (empty($_SESSION['gio_hang'])) {
    header("Location: ../index.php");
    exit;
}

$tong_tien = 0;
foreach ($_SESSION['gio_hang'] as $mon) {
    $tong_tien += $mon['gia'] * $mon['so_luong'];
}
$phi_ship = tinhPhiShip($tong_tien);
$tien_giam = $_SESSION['voucher']['tien_giam'] ?? 0;
$tong_thanh_toan = $tong_tien + $phi_ship - $tien_giam;

// Nếu khách đã đăng nhập, lấy sẵn thông tin cũ để điền vào form
$ho_ten_cu = ""; $sdt_cu = ""; $dia_chi_cu = "";
if (isset($_SESSION['dang_nhap'])) {
    $stmt = $conn->prepare("SELECT ho_ten, sdt, dia_chi FROM nguoi_dung WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id_nguoi_dung']);
    $stmt->execute();
    $tt = $stmt->get_result()->fetch_assoc();
    $ho_ten_cu = $tt['ho_ten'];
    $sdt_cu = $tt['sdt'];
    $dia_chi_cu = $tt['dia_chi'];
}
?>

<div class="container">
    <h2>📝 Thông tin đặt hàng</h2>

<p>
    Tạm tính: <?php echo number_format($tong_tien); ?>đ
    <br>Phí vận chuyển: <?php echo $phi_ship == 0 ? 'Miễn phí' : number_format($phi_ship).'đ'; ?>
    <?php if ($tien_giam > 0): ?>
        <br>Giảm giá: -<?php echo number_format($tien_giam); ?>đ
    <?php endif; ?>
    <br>Tổng tiền cần thanh toán: <strong class="fs-5" style="color:var(--primary);"><?php echo number_format($tong_thanh_toan); ?>đ</strong>
</p>
    <form action="xu_ly_dat_hang.php" method="POST">
    <div class="mb-3">
        <label class="form-label fw-bold">Họ tên</label>
        <input type="text" name="ten_khach" class="form-control" value="<?php echo htmlspecialchars($ho_ten_cu); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Số điện thoại</label>
        <input type="text" name="sdt" class="form-control" value="<?php echo htmlspecialchars($sdt_cu); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Địa chỉ giao hàng</label>
        <textarea name="dia_chi" class="form-control" required><?php echo htmlspecialchars($dia_chi_cu); ?></textarea>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold d-block">Phương thức thanh toán</label>

        <div class="form-check card p-3 mb-2 border-0 shadow-sm">
            <input class="form-check-input" type="radio" name="phuong_thuc_tt" value="cod" id="pt-cod" checked>
            <label class="form-check-label w-100" for="pt-cod">
                💵 <strong>Thanh toán khi nhận hàng (COD)</strong>
                <div class="text-muted small">Trả tiền mặt trực tiếp cho shipper khi nhận đồ ăn</div>
            </label>
        </div>

        <div class="form-check card p-3 border-0 shadow-sm">
            <input class="form-check-input" type="radio" name="phuong_thuc_tt" value="chuyen_khoan" id="pt-ck">
            <label class="form-check-label w-100" for="pt-ck">
                🏦 <strong>Chuyển khoản ngân hàng</strong>
                <div class="text-muted small">Quét mã QR để chuyển khoản sau khi đặt hàng thành công</div>
            </label>
        </div>
    </div>

    <button type="submit" class="btn btn-brand w-100 py-2 fw-bold">Xác nhận đặt hàng</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>