<?php
include 'config/ketnoi.php';
include 'includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT mon_an.*, danh_muc.ten_danh_muc, danh_muc.id as id_dm FROM mon_an JOIN danh_muc ON mon_an.id_danh_muc = danh_muc.id WHERE mon_an.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mon = $stmt->get_result()->fetch_assoc();

if (!$mon) {
    header("Location: index.php");
    exit;
}

$anh = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
$co_giam_gia = !empty($mon['gia_goc']) && $mon['gia_goc'] > $mon['gia'];
$phantram = $co_giam_gia ? round((1 - $mon['gia'] / $mon['gia_goc']) * 100) : 0;

// Lấy thêm 4 món khác cùng danh mục để gợi ý bên dưới
$stmt2 = $conn->prepare("SELECT * FROM mon_an WHERE id_danh_muc = ? AND id != ? LIMIT 4");
$stmt2->bind_param("ii", $mon['id_danh_muc'], $id);
$stmt2->execute();
$mon_lien_quan = $stmt2->get_result();
?>

<div class="container my-4">

    <!-- Breadcrumb: đường dẫn quay lại -->
    <nav class="mb-3">
        <a href="index.php" class="text-decoration-none text-muted">Trang chủ</a>
        <span class="text-muted"> / </span>
        <a href="index.php#danhmuc-<?php echo $mon['id_dm']; ?>" class="text-decoration-none text-muted"><?php echo $mon['ten_danh_muc']; ?></a>
        <span class="text-muted"> / </span>
        <span class="fw-bold"><?php echo $mon['ten_mon']; ?></span>
    </nav>

    <div class="row g-4">
        <!-- Ảnh lớn -->
        <div class="col-md-5">
            <img src="assets/img/<?php echo $anh; ?>" class="img-fluid rounded shadow-sm w-100" style="aspect-ratio:1/1;object-fit:cover;">
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-md-7">
            <span class="badge rounded-pill mb-2" style="background:var(--secondary);"><?php echo $mon['ten_danh_muc']; ?></span>
            <h2 class="fw-bold"><?php echo $mon['ten_mon']; ?></h2>

            <?php if ($co_giam_gia): ?>
                <div class="mb-2">
                    <span class="text-muted text-decoration-line-through fs-5 me-2"><?php echo number_format($mon['gia_goc']); ?>đ</span>
                    <span class="badge badge-giam">-<?php echo $phantram; ?>%</span>
                </div>
            <?php endif; ?>
            <?php $het_hang = isset($mon['con_hang']) && $mon['con_hang'] == 0; ?>
            <?php if ($het_hang): ?>
                <span class="badge bg-dark mb-3">Hết hàng</span>
            <?php endif; ?>

            <p class="text-muted"><?php echo nl2br(htmlspecialchars($mon['mo_ta'])); ?></p>

            <hr>

            <!-- Bộ chọn số lượng -->
            <label class="fw-bold mb-2 d-block">Số lượng</label>
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="doiSoLuong(-1)">−</button>
                    <input type="text" id="so-luong-input" value="1" class="btn btn-light disabled text-center" style="width:60px;" readonly>
                    <button type="button" class="btn btn-outline-secondary" onclick="doiSoLuong(1)">+</button>
                </div>
            </div>

            <?php if ($het_hang): ?>
                <button type="button" class="btn btn-secondary btn-lg px-5" disabled>Hết hàng</button>
            <?php else: ?>
                <button type="button" class="btn btn-brand btn-lg px-5 btn-them-gio"
                        data-id="<?php echo $mon['id']; ?>" data-qty-input="so-luong-input">
                    🛒 Thêm vào giỏ
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Món khác cùng danh mục -->
    <?php if ($mon_lien_quan->num_rows > 0): ?>
    <h4 class="fw-bold mt-5 mb-3">Món khác cùng danh mục</h4>
    <div class="row row-cols-2 row-cols-md-4 g-3">
        <?php while ($lq = $mon_lien_quan->fetch_assoc()):
            $anhLq = !empty($lq['hinh_anh']) ? $lq['hinh_anh'] : 'no-image.jpg';
        ?>
        <div class="col">
            <a href="mon_chi_tiet.php?id=<?php echo $lq['id']; ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <img src="assets/img/<?php echo $anhLq; ?>" class="card-img-top" style="height:120px;object-fit:cover;">
                    <div class="card-body p-2">
                        <p class="small fw-bold mb-1" style="color:var(--dark);"><?php echo $lq['ten_mon']; ?></p>
                        <p class="small mb-0" style="color:var(--primary);"><?php echo number_format($lq['gia']); ?>đ</p>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

</div>

<script>
function doiSoLuong(delta) {
    const o = document.getElementById('so-luong-input');
    let val = parseInt(o.value) + delta;
    if (val < 1) val = 1;
    o.value = val;
}
</script>

<?php include 'includes/footer.php'; ?>