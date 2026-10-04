<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$sql = "SELECT mon_an.*, danh_muc.ten_danh_muc 
        FROM mon_an 
        JOIN danh_muc ON mon_an.id_danh_muc = danh_muc.id";
$ket_qua = $conn->query($sql);

$danh_sach_dm = $conn->query("SELECT * FROM danh_muc");
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">🍽️ Quản lý món ăn</h2>

    <h3 class="fw-bold mt-4 mb-3">Thêm món mới</h3>
    <form action="xu_ly_mon_an.php" method="POST" enctype="multipart/form-data" class="card shadow-sm border-0 p-4 mb-4">
        <input type="hidden" name="hanh_dong" value="them">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Tên món</label>
                <input type="text" name="ten_mon" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Danh mục</label>
                <select name="id_danh_muc" class="form-select" required>
                    <?php while ($dm = $danh_sach_dm->fetch_assoc()): ?>
                        <option value="<?php echo $dm['id']; ?>"><?php echo $dm['ten_danh_muc']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá bán (đ)</label>
                <input type="number" name="gia" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá gốc (đ) <span class="text-muted fw-normal small">— để trống nếu không giảm giá</span></label>
                <input type="number" name="gia_goc" class="form-control" placeholder="VD: 40000">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá vốn (đ)</label>
                <input type="number" name="gia_von" class="form-control" placeholder="VD: 15000" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="mo_ta" class="form-control"></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Hình ảnh</label>
                <input type="file" name="hinh_anh" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-brand w-100">Thêm món</button>
            </div>
        </div>
    </form>

    <h3 class="fw-bold mb-3">Danh sách món ăn hiện có</h3>
    <div class="card shadow-sm border-0 p-3">
    <table class="table table-hover align-middle mb-0">
        <thead>
        <tr>
            <th>Ảnh</th><th>Tên món</th><th>Giá bán</th><th>Giá gốc</th><th>Giá vốn</th><th>Danh mục</th><th>Tồn kho</th><th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($mon = $ket_qua->fetch_assoc()):
            $anh = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
        ?>
        <tr>
            <td><img src="../assets/img/<?php echo $anh; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:6px;"></td>
            <td><?php echo $mon['ten_mon']; ?></td>
            <td><?php echo number_format($mon['gia']); ?>đ</td>
            <td><?php echo !empty($mon['gia_goc']) ? number_format($mon['gia_goc']).'đ' : '<span class="text-muted">—</span>'; ?></td>
            <td><?php echo number_format($mon['gia_von']); ?>đ</td>
            <td><?php echo $mon['ten_danh_muc']; ?></td>
            <td>
                <a href="xu_ly_mon_an.php?hanh_dong=<?php echo $mon['con_hang'] ? 'het_hang' : 'con_hang_lai'; ?>&id=<?php echo $mon['id']; ?>"
                   class="badge <?php echo $mon['con_hang'] ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none">
                    <?php echo $mon['con_hang'] ? 'Còn hàng' : 'Hết hàng'; ?>
                </a>
            </td>
            <td>
                <div class="d-flex gap-1 flex-wrap">
                    <a href="sua_mon.php?id=<?php echo $mon['id']; ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <a href="xu_ly_mon_an.php?hanh_dong=xoa&id=<?php echo $mon['id']; ?>" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Xóa món này?')">Xóa</a>
                    <button type="button" class="btn btn-sm noi-bat-toggle <?php echo $mon['noi_bat'] ? 'btn-warning' : 'btn-outline-warning'; ?>"
                            data-id="<?php echo $mon['id']; ?>">
                        <?php echo $mon['noi_bat'] ? '⭐ Đặc sắc' : '☆ Đánh dấu'; ?>
                    </button>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<script>
document.querySelectorAll('.noi-bat-toggle').forEach(function(nut) {
    nut.addEventListener('click', function() {
        const idMon = this.dataset.id;
        const nutBam = this;
        fetch('ajax_toggle_noibat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + idMon
        })
        .then(response => response.json())
        .then(data => {
            if (data.thanh_cong) {
                if (data.noi_bat == 1) {
                    nutBam.textContent = '⭐ Đặc sắc';
                    nutBam.classList.remove('btn-outline-warning');
                    nutBam.classList.add('btn-warning');
                } else {
                    nutBam.textContent = '☆ Đánh dấu';
                    nutBam.classList.remove('btn-warning');
                    nutBam.classList.add('btn-outline-warning');
                }
            }
        });
    });
});
</script>
<?php include '../includes/footer.php'; ?>