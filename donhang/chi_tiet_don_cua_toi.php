<?php
include '../includes/header.php';
include '../config/ketnoi.php';

if (!isset($_SESSION['dang_nhap'])) {
    header("Location: ../auth/dang_nhap.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ? AND id_nguoi_dung = ?");
$stmt->bind_param("ii", $id, $_SESSION['id_nguoi_dung']);
$stmt->execute();
$don = $stmt->get_result()->fetch_assoc();

if (!$don) {
    header("Location: don_hang_cua_toi.php");
    exit;
}

// Thêm mon_an.hinh_anh vào câu truy vấn để hiển thị ảnh từng món
$stmt2 = $conn->prepare("
    SELECT chi_tiet_don_hang.*, mon_an.ten_mon, mon_an.hinh_anh
    FROM chi_tiet_don_hang 
    JOIN mon_an ON chi_tiet_don_hang.id_mon = mon_an.id 
    WHERE id_don_hang = ?
");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$chi_tiet = $stmt2->get_result();

$stmt3 = $conn->prepare("SELECT * FROM lich_su_don_hang WHERE id_don_hang = ? ORDER BY thoi_gian ASC");
$stmt3->bind_param("i", $id);
$stmt3->execute();
$lich_su = $stmt3->get_result();

function tenTrangThai2($ma) {
    $ds = ['cho_xac_nhan'=>'Chờ quán xác nhận','da_nhan'=>'Quán đã nhận đơn','dang_lam'=>'Đang chuẩn bị món','dang_giao'=>'Đang giao','hoan_thanh'=>'Hoàn thành','da_huy'=>'Đã hủy'];
    return $ds[$ma] ?? $ma;
}
function mauTrangThai2($ma) {
    $ds = ['cho_xac_nhan'=>'warning','da_nhan'=>'info','dang_lam'=>'primary','dang_giao'=>'success','hoan_thanh'=>'success','da_huy'=>'secondary'];
    return $ds[$ma] ?? 'secondary';
}
?>

<div class="container my-4">
    <a href="don_hang_cua_toi.php" class="btn btn-outline-secondary btn-sm mb-3">← Quay lại danh sách</a>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">Đơn hàng #<?php echo $don['id']; ?></h2>
        <span class="badge bg-<?php echo mauTrangThai2($don['trang_thai']); ?> fs-6 px-3 py-2">
            <?php echo tenTrangThai2($don['trang_thai']); ?>
        </span>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <!-- Danh sách món ăn kèm ảnh -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">🍽️ Món đã đặt</h5>
                    <?php while ($mon = $chi_tiet->fetch_assoc()):
                        $anh = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
                    ?>
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                        <img src="../assets/img/<?php echo $anh; ?>" style="width:55px;height:55px;object-fit:cover;border-radius:8px;">
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?php echo $mon['ten_mon']; ?></div>
                            <div class="text-muted small"><?php echo number_format($mon['don_gia']); ?>đ × <?php echo $mon['so_luong']; ?></div>
                        </div>
                        <div class="fw-bold"><?php echo number_format($mon['don_gia'] * $mon['so_luong']); ?>đ</div>
                    </div>
                    <?php endwhile; ?>

                    <div class="d-flex justify-content-between pt-3 mt-2">
                        <span>Phí vận chuyển</span>
                        <span><?php echo $don['phi_ship'] == 0 ? 'Miễn phí' : number_format($don['phi_ship']).'đ'; ?></span>
                    </div>
                    <?php if ($don['tien_giam'] > 0): ?>
                    <div class="d-flex justify-content-between" style="color:var(--secondary);">
                        <span>Giảm giá</span>
                        <span>-<?php echo number_format($don['tien_giam']); ?>đ</span>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2 pt-2 border-top">
                        <span>Tổng cộng</span>
                        <span style="color:var(--primary);"><?php echo number_format($don['tong_tien']); ?>đ</span>
                    </div>
                </div>
            </div>

            <!-- Tiến độ -->
            <?php if ($don['trang_thai'] == 'da_huy'): ?>
                <div class="alert alert-secondary">❌ Đơn hàng này đã bị hủy.</div>
            <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">🕒 Tiến độ đơn hàng</h5>
                    <ul class="list-unstyled mb-0">
                        <?php if ($lich_su->num_rows == 0): ?>
                            <!-- Đơn đặt trước khi hệ thống ghi lịch sử chi tiết, chỉ hiện trạng thái hiện tại -->
                            <li class="d-flex mb-3">
                                <div class="me-3" style="color:var(--secondary);">●</div>
                                <div>
                                    <div class="fw-bold"><?php echo tenTrangThai2($don['trang_thai']); ?></div>
                                    <div class="small text-muted">Đơn này được đặt trước khi hệ thống lưu lịch sử chi tiết, không có đầy đủ mốc thời gian từng bước.</div>
                                </div>
                            </li>
                        <?php else: while ($ls = $lich_su->fetch_assoc()): ?>
                        <li class="d-flex mb-3">
                            <div class="me-3" style="color:var(--secondary);">●</div>
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($ls['ghi_chu']); ?></div>
                                <div class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($ls['thoi_gian'])); ?></div>
                            </div>
                        </li>
                        <?php endwhile; endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-5">
            <!-- Địa chỉ giao hàng -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">📍 Giao đến</h5>
                    <p class="mb-0"><?php echo htmlspecialchars($don['dia_chi_giao']); ?></p>
                </div>
            </div>

            <!-- Thanh toán -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">💳 Thanh toán</h5>
                    <p class="mb-1"><?php echo $don['phuong_thuc_tt'] == 'chuyen_khoan' ? '🏦 Chuyển khoản ngân hàng' : '💵 Tiền mặt khi nhận hàng (COD)'; ?></p>
                    <?php if ($don['phuong_thuc_tt'] == 'chuyen_khoan'): ?>
                        <p class="mb-0">
                            Trạng thái:
                            <?php if ($don['trang_thai_tt'] == 'da_thanh_toan'): ?>
                                <span class="badge bg-success">Đã thanh toán</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Chưa thanh toán</span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($don['ten_shipper'])): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">🛵 Shipper phụ trách</h5>
                    <p class="mb-1"><strong>Tên:</strong> <?php echo htmlspecialchars($don['ten_shipper']); ?></p>
                    <p class="mb-0"><strong>SĐT:</strong> <?php echo htmlspecialchars($don['sdt_shipper']); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($don['trang_thai'] == 'cho_xac_nhan'): ?>
                <a href="huy_don.php?id=<?php echo $don['id']; ?>" class="btn btn-outline-danger w-100" onclick="return confirm('Hủy đơn hàng này?')">Hủy đơn</a>
            <?php else: ?>
                <button class="btn btn-outline-danger w-100" disabled>Hủy đơn</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>