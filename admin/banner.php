<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$ds_banner = $conn->query("SELECT * FROM banner ORDER BY thu_tu ASC");
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">🖼️ Quản lý banner</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Thêm banner mới</h5>
            <form action="xu_ly_banner.php" method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="hanh_dong" value="them">
                <div class="col-md-4">
                    <label class="form-label">Ảnh banner (nên dùng ảnh ngang, khoảng 1200x400)</label>
                    <input type="file" name="hinh_anh" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tiêu đề hiển thị (không bắt buộc)</label>
                    <input type="text" name="tieu_de" class="form-control" placeholder="VD: Giảm 20% đơn đầu tiên">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Thứ tự</label>
                    <input type="number" name="thu_tu" class="form-control" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-brand w-100">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <?php while ($b = $ds_banner->fetch_assoc()): ?>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <img src="../assets/img/<?php echo $b['hinh_anh']; ?>" class="card-img-top" style="height:140px;object-fit:cover;">
                <div class="card-body">
                    <p class="fw-bold mb-1"><?php echo htmlspecialchars($b['tieu_de'] ?: '(không có tiêu đề)'); ?></p>
                    <p class="small text-muted mb-2">Thứ tự: <?php echo $b['thu_tu']; ?></p>
                    <div class="d-flex gap-2">
                        <a href="xu_ly_banner.php?hanh_dong=<?php echo $b['kich_hoat'] ? 'tat' : 'bat'; ?>&id=<?php echo $b['id']; ?>"
                           class="btn btn-sm <?php echo $b['kich_hoat'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>">
                            <?php echo $b['kich_hoat'] ? 'Tắt' : 'Bật'; ?>
                        </a>
                        <a href="xu_ly_banner.php?hanh_dong=xoa&id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa banner này?')">Xóa</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>