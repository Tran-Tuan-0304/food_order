<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$don_hangs = $conn->query("SELECT * FROM don_hang ORDER BY ngay_dat DESC");

function nhanTrangThai($ma) {
    $ds = [
        'cho_xac_nhan' => 'Chờ quán xác nhận',
        'da_nhan'      => 'Quán đã nhận đơn',
        'dang_lam'     => 'Đang chuẩn bị món',
        'dang_giao'    => 'Đang giao',
        'hoan_thanh'   => 'Hoàn thành',
        'da_huy'       => 'Đã hủy'
    ];
    return $ds[$ma] ?? $ma;
}
function mauTrangThai($ma) {
    $ds = [
        'cho_xac_nhan' => 'warning',
        'da_nhan'      => 'info',
        'dang_lam'     => 'primary',
        'dang_giao'    => 'success',
        'hoan_thanh'   => 'success',
        'da_huy'       => 'secondary'
    ];
    return $ds[$ma] ?? 'secondary';
}
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">📦 Quản lý đơn hàng</h2>

    <?php if (isset($_GET['loi'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['loi']); ?></div>
    <?php endif; ?>

    <?php if ($don_hangs->num_rows == 0): ?>
        <p class="text-muted">Chưa có đơn hàng nào.</p>
    <?php endif; ?>

    <?php while ($don = $don_hangs->fetch_assoc()): ?>
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <span class="fw-bold fs-5">Đơn #<?php echo $don['id']; ?></span>
                    <span class="text-muted ms-2"><?php echo date('d/m/Y H:i', strtotime($don['ngay_dat'])); ?></span>
                </div>
                <span class="badge bg-<?php echo mauTrangThai($don['trang_thai']); ?> fs-6 px-3 py-2">
                    <?php echo nhanTrangThai($don['trang_thai']); ?>
                </span>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="text-muted small">Khách hàng</div>
                    <div class="fw-bold"><?php echo htmlspecialchars($don['ten_khach']); ?></div>
                    <div class="small"><?php echo htmlspecialchars($don['sdt']); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Tổng tiền</div>
                    <div class="fw-bold fs-5" style="color:var(--primary);"><?php echo number_format($don['tong_tien']); ?>đ</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Thanh toán</div>
                    <div>
                        <?php if ($don['phuong_thuc_tt'] == 'chuyen_khoan'): ?>
                            🏦 Chuyển khoản
                            <?php if ($don['trang_thai_tt'] == 'da_thanh_toan'): ?>
                                <span class="badge bg-success ms-1">Đã TT</span>
                            <?php else: ?>
                                <a href="xac_nhan_thanh_toan.php?id=<?php echo $don['id']; ?>" class="btn btn-sm btn-outline-success ms-1">Xác nhận đã CK</a>
                            <?php endif; ?>
                        <?php else: ?>
                            💵 Tiền mặt (COD)
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($don['ten_shipper'])): ?>
            <div class="alert alert-light border py-2 mb-3 small">🛵 Shipper: <strong><?php echo htmlspecialchars($don['ten_shipper']); ?></strong> — <?php echo htmlspecialchars($don['sdt_shipper']); ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pt-3 border-top">
                <?php if ($don['trang_thai'] == 'da_huy'): ?>
                    <span class="text-muted">Đơn đã bị khách hủy, không thể chỉnh sửa.</span>
                <?php else: ?>
                <form action="xu_ly_don_hang.php" method="POST" class="d-flex align-items-center gap-2">
                    <label class="fw-bold mb-0">Cập nhật trạng thái:</label>
                    <input type="hidden" name="id" value="<?php echo $don['id']; ?>">
                    <input type="hidden" name="ten_shipper" value="<?php echo htmlspecialchars($don['ten_shipper'] ?? ''); ?>">
                    <input type="hidden" name="sdt_shipper" value="<?php echo htmlspecialchars($don['sdt_shipper'] ?? ''); ?>">
                    <select name="trang_thai" class="form-select" style="width:auto;"
                            data-gia-tri-cu="<?php echo $don['trang_thai']; ?>"
                            onchange="xuLyDoiTrangThai(this)">
                        <option value="cho_xac_nhan" <?php if($don['trang_thai']=='cho_xac_nhan') echo 'selected'; ?>>Chờ quán xác nhận</option>
                        <option value="da_nhan" <?php if($don['trang_thai']=='da_nhan') echo 'selected'; ?>>Quán đã nhận đơn</option>
                        <option value="dang_lam" <?php if($don['trang_thai']=='dang_lam') echo 'selected'; ?>>Đang chuẩn bị món</option>
                        <option value="dang_giao" <?php if($don['trang_thai']=='dang_giao') echo 'selected'; ?>>Đang giao</option>
                        <option value="hoan_thanh" <?php if($don['trang_thai']=='hoan_thanh') echo 'selected'; ?>>Hoàn thành</option>
                        <option value="da_huy" <?php if($don['trang_thai']=='da_huy') echo 'selected'; ?>>Đã hủy</option>
                    </select>
                </form>
                <?php endif; ?>
                <a href="chi_tiet_don.php?id=<?php echo $don['id']; ?>" class="btn btn-brand">Xem chi tiết →</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<script>
function xuLyDoiTrangThai(select) {
    const form = select.form;
    if (select.value === 'dang_giao') {
        const ten = prompt('Nhập tên shipper:');
        if (ten === null) { select.value = select.dataset.giaTriCu; return; }
        const sdt = prompt('Nhập số điện thoại shipper:') || '';
        form.querySelector('[name="ten_shipper"]').value = ten;
        form.querySelector('[name="sdt_shipper"]').value = sdt;
    }
    form.submit();
}
</script>

<?php include '../includes/footer.php'; ?>