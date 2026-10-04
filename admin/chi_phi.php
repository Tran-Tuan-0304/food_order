<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$ds_chi_phi = $conn->query("SELECT * FROM chi_phi ORDER BY ngay DESC");

// Tổng chi phí tháng này, hiện luôn cho tiện theo dõi
$tong_thang = $conn->query("
    SELECT COALESCE(SUM(so_tien),0) as tong FROM chi_phi
    WHERE MONTH(ngay) = MONTH(CURDATE()) AND YEAR(ngay) = YEAR(CURDATE())
")->fetch_assoc();
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">💰 Quản lý chi phí</h2>

    <div class="alert alert-light border mb-4">
        Tổng chi phí tháng này: <strong style="color:var(--primary);"><?php echo number_format($tong_thang['tong']); ?>đ</strong>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Thêm khoản chi mới</h5>
            <form action="xu_ly_chi_phi.php" method="POST" class="row g-3">
                <input type="hidden" name="hanh_dong" value="them">
                <div class="col-md-4">
                    <label class="form-label">Tên chi phí</label>
                    <input type="text" name="ten_chi_phi" class="form-control" placeholder="VD: Nhập nguyên liệu tuần 1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Số tiền (đ)</label>
                    <input type="number" name="so_tien" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày chi</label>
                    <input type="date" name="ngay" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-brand w-100">Thêm</button>
                </div>
                <div class="col-12">
                    <label class="form-label">Ghi chú (không bắt buộc)</label>
                    <input type="text" name="ghi_chu" class="form-control">
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr><th>Ngày</th><th>Tên chi phí</th><th>Số tiền</th><th>Ghi chú</th><th></th></tr>
        </thead>
        <tbody>
        <?php while ($cp = $ds_chi_phi->fetch_assoc()): ?>
        <tr>
            <td><?php echo date('d/m/Y', strtotime($cp['ngay'])); ?></td>
            <td><?php echo htmlspecialchars($cp['ten_chi_phi']); ?></td>
            <td class="fw-bold"><?php echo number_format($cp['so_tien']); ?>đ</td>
            <td class="text-muted small"><?php echo htmlspecialchars($cp['ghi_chu']); ?></td>
            <td><a href="xu_ly_chi_phi.php?hanh_dong=xoa&id=<?php echo $cp['id']; ?>" class="text-danger" onclick="return confirm('Xóa khoản chi này?')">Xóa</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>