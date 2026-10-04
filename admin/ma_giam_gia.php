<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$ds_voucher = $conn->query("SELECT * FROM ma_giam_gia ORDER BY id DESC");
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">🎟️ Quản lý mã giảm giá</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Thêm mã mới</h5>
            <form action="xu_ly_ma_giam_gia.php" method="POST" class="row g-3">
                <input type="hidden" name="hanh_dong" value="them">

                <div class="col-md-3">
                    <label class="form-label">Mã (viết liền, in hoa)</label>
                    <input type="text" name="ma" class="form-control" placeholder="VD: SALE30" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Loại giảm</label>
                    <select name="loai" class="form-select" required>
                        <option value="phan_tram">Theo phần trăm (%)</option>
                        <option value="tien_mat">Số tiền cố định</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giá trị</label>
                    <input type="number" name="gia_tri" class="form-control" placeholder="VD: 10 hoặc 20000" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Đơn tối thiểu</label>
                    <input type="number" name="don_toi_thieu" class="form-control" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giảm tối đa <span class="text-muted small">(chỉ áp % )</span></label>
                    <input type="number" name="giam_toi_da" class="form-control" placeholder="Để trống = không giới hạn">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Số lượt dùng</label>
                    <input type="number" name="so_luong" class="form-control" value="100" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hạn dùng</label>
                    <input type="date" name="han_dung" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-brand w-100">Thêm mã</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Mã</th><th>Loại</th><th>Giá trị</th><th>Đơn tối thiểu</th>
                <th>Giảm tối đa</th><th>Còn lại</th><th>Hạn dùng</th><th>Trạng thái</th><th></th>
            </tr>
        </thead>
        <tbody>
        <?php while ($v = $ds_voucher->fetch_assoc()): ?>
        <tr>
            <td class="fw-bold"><?php echo $v['ma']; ?></td>
            <td><?php echo $v['loai'] == 'phan_tram' ? 'Phần trăm' : 'Tiền mặt'; ?></td>
            <td><?php echo $v['loai'] == 'phan_tram' ? $v['gia_tri'].'%' : number_format($v['gia_tri']).'đ'; ?></td>
            <td><?php echo number_format($v['don_toi_thieu']); ?>đ</td>
            <td><?php echo $v['giam_toi_da'] !== null ? number_format($v['giam_toi_da']).'đ' : '—'; ?></td>
            <td><?php echo $v['so_luong']; ?></td>
            <td><?php echo $v['han_dung'] ?? '—'; ?></td>
            <td>
                <a href="xu_ly_ma_giam_gia.php?hanh_dong=<?php echo $v['kich_hoat'] ? 'tat' : 'bat'; ?>&id=<?php echo $v['id']; ?>"
                   class="badge <?php echo $v['kich_hoat'] ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none">
                    <?php echo $v['kich_hoat'] ? 'Đang bật' : 'Đã tắt'; ?>
                </a>
            </td>
            <td><a href="xu_ly_ma_giam_gia.php?hanh_dong=xoa&id=<?php echo $v['id']; ?>" class="text-danger" onclick="return confirm('Xóa mã này?')">Xóa</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>