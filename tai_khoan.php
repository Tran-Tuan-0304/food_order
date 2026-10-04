<?php
include 'includes/header.php';
include 'config/ketnoi.php';

if (!isset($_SESSION['dang_nhap'])) {
    header("Location: auth/dang_nhap.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id_nguoi_dung']);
$stmt->execute();
$tt = $stmt->get_result()->fetch_assoc();

// Cho phép cập nhật SĐT / địa chỉ ngay tại đây
$thong_bao = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $stmt2 = $conn->prepare("UPDATE nguoi_dung SET sdt=?, dia_chi=? WHERE id=?");
    $stmt2->bind_param("ssi", $sdt, $dia_chi, $_SESSION['id_nguoi_dung']);
    $stmt2->execute();
    $tt['sdt'] = $sdt;
    $tt['dia_chi'] = $dia_chi;
    $thong_bao = "Đã cập nhật thông tin!";
}

// Đếm nhanh số đơn hàng đã đặt để hiển thị
$dem = $conn->prepare("SELECT COUNT(*) as tong FROM don_hang WHERE id_nguoi_dung = ?");
$dem->bind_param("i", $_SESSION['id_nguoi_dung']);
$dem->execute();
$so_don = $dem->get_result()->fetch_assoc()['tong'];
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">👤 Tài khoản của tôi</h2>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Thông tin cá nhân</h5>
                    <?php if ($thong_bao): ?><div class="alert alert-success py-2"><?php echo $thong_bao; ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($tt['ho_ten']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($tt['email']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control" value="<?php echo htmlspecialchars($tt['sdt'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="dia_chi" class="form-control"><?php echo htmlspecialchars($tt['dia_chi'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-brand">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">📦 Đơn hàng của tôi</h5>
                        <p class="text-muted mb-0">Bạn đã đặt <?php echo $so_don; ?> đơn hàng</p>
                    </div>
                    <a href="donhang/don_hang_cua_toi.php" class="btn btn-brand">Xem tất cả</a>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <a href="auth/dang_xuat.php" class="btn btn-outline-danger">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>