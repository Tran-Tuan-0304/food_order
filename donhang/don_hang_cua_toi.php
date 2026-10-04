<?php
include '../includes/header.php';
include '../config/ketnoi.php';

if (!isset($_SESSION['dang_nhap'])) {
    header("Location: ../auth/dang_nhap.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id_nguoi_dung = ? ORDER BY ngay_dat DESC");
$stmt->bind_param("i", $_SESSION['id_nguoi_dung']);
$stmt->execute();
$don_hangs = $stmt->get_result();

function tenTrangThai($ma) {
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

// Lấy sẵn 1 ảnh đại diện (món đầu tiên) của mỗi đơn, để thẻ nhìn sinh động hơn thay vì toàn chữ
function anhDaiDien($conn, $id_don_hang) {
    $stmt = $conn->prepare("
        SELECT mon_an.hinh_anh FROM chi_tiet_don_hang
        JOIN mon_an ON chi_tiet_don_hang.id_mon = mon_an.id
        WHERE id_don_hang = ? LIMIT 1
    ");
    $stmt->bind_param("i", $id_don_hang);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (!empty($row['hinh_anh'])) ? $row['hinh_anh'] : 'no-image.jpg';
}
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">📋 Đơn hàng của tôi</h2>

    <?php if ($don_hangs->num_rows == 0): ?>
        <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
        <a href="../index.php" class="btn btn-brand">Đặt món ngay</a>
    <?php else: ?>

        <?php while ($don = $don_hangs->fetch_assoc()):
            $anh = anhDaiDien($conn, $don['id']);
        ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-4">
                <div class="d-flex gap-3">
                    <img src="../assets/img/<?php echo $anh; ?>" style="width:64px;height:64px;object-fit:cover;border-radius:10px;">

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div>
                                <span class="fw-bold fs-5">Đơn #<?php echo $don['id']; ?></span>
                                <span class="text-muted ms-2 small"><?php echo date('d/m/Y H:i', strtotime($don['ngay_dat'])); ?></span>
                            </div>
                            <span class="badge bg-<?php echo mauTrangThai($don['trang_thai']); ?> fs-6 px-3 py-2">
                                <?php echo tenTrangThai($don['trang_thai']); ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span class="fw-bold fs-5" style="color:var(--primary);"><?php echo number_format($don['tong_tien']); ?>đ</span>
                            <div class="d-flex gap-2">
                                <a href="chi_tiet_don_cua_toi.php?id=<?php echo $don['id']; ?>" class="btn btn-sm btn-brand">Xem chi tiết</a>
                                <?php if ($don['trang_thai'] == 'cho_xac_nhan'): ?>
                                    <a href="huy_don.php?id=<?php echo $don['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hủy đơn hàng này?')">Hủy đơn</a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-danger" disabled title="Đơn đã được xử lý, không thể hủy">Hủy đơn</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>