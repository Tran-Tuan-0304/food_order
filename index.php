<?php
include 'config/ketnoi.php';
include 'includes/header.php';

$tu_khoa = isset($_GET['tim']) ? trim($_GET['tim']) : '';

$ket_qua_danhmuc = $conn->query("SELECT * FROM danh_muc");
$danh_sach_danh_muc = [];
while ($row = $ket_qua_danhmuc->fetch_assoc()) { $danh_sach_danh_muc[] = $row; }

$ds_banner = $conn->query("SELECT * FROM banner WHERE kich_hoat = 1 ORDER BY thu_tu ASC");
$banners = [];
while ($b = $ds_banner->fetch_assoc()) { $banners[] = $b; }

$icon_danh_muc = [
    'Trà sữa' => '🧋', 'Cơm' => '🍚', 'Đồ ăn vặt' => '🍟',
    'Bún - Phở' => '🍜', 'Burger - Pizza' => '🍔', 'Nước uống' => '🥤',
    'Tráng miệng' => '🍰', 'Món chay' => '🥗', 'Lẩu - Nướng' => '🍢',
];

// ===== Xác định khung giờ hiện tại để gợi ý món phù hợp =====
$gio_hien_tai = (int) date('H');
if ($gio_hien_tai >= 5 && $gio_hien_tai < 10) {
    $khung_gio = 'sang'; $tieu_de_khung_gio = '☀️ Gợi ý bữa sáng cho bạn';
} elseif ($gio_hien_tai >= 10 && $gio_hien_tai < 14) {
    $khung_gio = 'trua'; $tieu_de_khung_gio = '🌤️ Gợi ý bữa trưa cho bạn';
} elseif ($gio_hien_tai >= 14 && $gio_hien_tai < 22) {
    $khung_gio = 'toi'; $tieu_de_khung_gio = '🌙 Gợi ý bữa tối cho bạn';
} else {
    $khung_gio = 'toi'; $tieu_de_khung_gio = '🌃 Đói bụng đêm khuya? Thử ngay';
}

// Mỗi danh mục phù hợp với (các) khung giờ nào - chỉnh sửa mảng này nếu muốn đổi logic
$map_khung_gio = [
    'Bún - Phở'      => ['sang', 'trua'],
    'Cơm'            => ['trua', 'toi'],
    'Trà sữa'        => ['sang', 'trua', 'toi'],
    'Đồ ăn vặt'      => ['toi'],
    'Burger - Pizza' => ['trua', 'toi'],
    'Nước uống'      => ['sang', 'trua', 'toi'],
    'Tráng miệng'    => ['trua', 'toi'],
    'Món chay'       => ['trua', 'toi'],
    'Lẩu - Nướng'    => ['toi'],
];
$id_danh_muc_phu_hop = [];
foreach ($danh_sach_danh_muc as $dm) {
    $slots = $map_khung_gio[$dm['ten_danh_muc']] ?? ['sang', 'trua', 'toi'];
    if (in_array($khung_gio, $slots)) {
        $id_danh_muc_phu_hop[] = $dm['id'];
    }
}
?>

<?php if (count($banners) > 0): ?>
<div id="bannerThat" class="carousel slide mt-3" data-bs-ride="carousel" data-bs-interval="2500">
    <div class="carousel-indicators">
        <?php foreach ($banners as $i => $b): ?>
            <button type="button" data-bs-target="#bannerThat" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo $i==0?'active':''; ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner rounded" style="max-width:1000px;margin:0 auto;">
        <?php foreach ($banners as $i => $b): ?>
        <div class="carousel-item <?php echo $i==0?'active':''; ?>">
            <img src="assets/img/<?php echo $b['hinh_anh']; ?>" class="d-block w-100" style="height:220px;object-fit:cover;">
            <?php if (!empty($b['tieu_de'])): ?>
            <div class="carousel-caption d-none d-md-block" style="background:rgba(0,0,0,0.35);border-radius:8px;">
                <h5><?php echo htmlspecialchars($b['tieu_de']); ?></h5>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($banners) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerThat" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerThat" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="banner" id="banner">
    <div class="banner-slide active"><h2>🔥 Giảm 20% đơn đầu tiên</h2><p>Nhập mã FIRST20 khi đặt hàng</p></div>
    <div class="banner-slide"><h2>🍜 Món mới ra mắt</h2><p>Thử ngay Lẩu Thái hải sản hôm nay</p></div>
    <div class="banner-dots"><span class="dot active"></span><span class="dot"></span></div>
</div>
<?php endif; ?>

<div class="container my-4">

    <?php if ($tu_khoa == ''): ?>

        <!-- ========== KHỐI 1: DANH MỤC MÓN ĂN ========== -->
        <h4 class="fw-bold mb-3">Danh mục món ăn</h4>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <?php $idx = 0; foreach ($danh_sach_danh_muc as $dm):
                $idx++;
                $icon = $icon_danh_muc[$dm['ten_danh_muc']] ?? '🍽️';
                $mau = ['var(--primary)','var(--secondary)','var(--accent)'][$idx % 3];
            ?>
            <div class="col">
                <a href="danh_muc.php?id=<?php echo $dm['id']; ?>" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-body py-4">
                            <div class="fs-1 mb-2"><?php echo $icon; ?></div>
                            <h6 class="fw-bold mb-0" style="color:<?php echo $mau; ?>;"><?php echo $dm['ten_danh_muc']; ?></h6>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ========== KHỐI 2: GỢI Ý THEO KHUNG GIỜ ========== -->
        <?php if (count($id_danh_muc_phu_hop) > 0):
            $placeholders = implode(',', array_fill(0, count($id_danh_muc_phu_hop), '?'));
            $types = str_repeat('i', count($id_danh_muc_phu_hop));
            $sql_kg = "SELECT * FROM mon_an WHERE id_danh_muc IN ($placeholders) ORDER BY RAND() LIMIT 8";
            $stmt_kg = $conn->prepare($sql_kg);
            $stmt_kg->bind_param($types, ...$id_danh_muc_phu_hop);
            $stmt_kg->execute();
            $ds_khung_gio = $stmt_kg->get_result();
            if ($ds_khung_gio->num_rows > 0):
        ?>
        <h4 class="fw-bold mb-3"><?php echo $tieu_de_khung_gio; ?></h4>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <?php while ($mon = $ds_khung_gio->fetch_assoc()) { include 'includes/the_mon_an.php'; } ?>
        </div>
        <?php endif; endif; ?>

        <!-- ========== KHỐI 3: TOP MÓN BÁN CHẠY (tính thật từ dữ liệu đơn hàng) ========== -->
        <?php
        $ds_banchay = $conn->query("
            SELECT mon_an.*, SUM(chi_tiet_don_hang.so_luong) as tong_ban
            FROM mon_an
            JOIN chi_tiet_don_hang ON chi_tiet_don_hang.id_mon = mon_an.id
            JOIN don_hang ON chi_tiet_don_hang.id_don_hang = don_hang.id
            WHERE don_hang.trang_thai != 'da_huy'
            GROUP BY mon_an.id
            ORDER BY tong_ban DESC
            LIMIT 8
        ");
        if ($ds_banchay->num_rows > 0):
        ?>
        <h4 class="fw-bold mb-3">🏆 Top món bán chạy</h4>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <?php while ($mon = $ds_banchay->fetch_assoc()) { include 'includes/the_mon_an.php'; } ?>
        </div>
        <?php endif; ?>

        <!-- ========== KHỐI 4: GIẢM GIÁ SÂU ========== -->
        <?php
        $ds_giam_gia = $conn->query("
            SELECT * FROM mon_an
            WHERE gia_goc IS NOT NULL AND gia_goc > gia
            ORDER BY (1 - gia/gia_goc) DESC
            LIMIT 8
        ");
        if ($ds_giam_gia->num_rows > 0):
        ?>
        <div class="d-flex align-items-center gap-2 mb-3">
            <h4 class="fw-bold mb-0">🔥 Giảm giá sâu</h4>
            <span class="badge" style="background:var(--accent);color:var(--dark);">Số lượng có hạn</span>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <?php while ($mon = $ds_giam_gia->fetch_assoc()) { include 'includes/the_mon_an.php'; } ?>
        </div>
        <?php endif; ?>

        <!-- ========== KHỐI 5: MÓN ĐẶC SẮC ========== -->
        <?php
        $ds_noi_bat = $conn->query("SELECT * FROM mon_an WHERE noi_bat = 1 ORDER BY id DESC LIMIT 8");
        if ($ds_noi_bat->num_rows > 0):
        ?>
        <h4 class="fw-bold mb-3">⭐ Món đặc sắc</h4>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <?php while ($mon = $ds_noi_bat->fetch_assoc()) { include 'includes/the_mon_an.php'; } ?>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- CÓ tìm kiếm -->
        <?php
        $co_ket_qua = false;
        foreach ($danh_sach_danh_muc as $danhmuc) {
            $stmt = $conn->prepare("SELECT * FROM mon_an WHERE id_danh_muc = ? AND ten_mon LIKE ?");
            $tu_khoa_like = "%" . $tu_khoa . "%";
            $stmt->bind_param("is", $danhmuc['id'], $tu_khoa_like);
            $stmt->execute();
            $ket_qua_mon = $stmt->get_result();
            if ($ket_qua_mon->num_rows == 0) continue;
            $co_ket_qua = true;

            echo "<h5 class='fw-bold mt-4 mb-3'>" . $danhmuc['ten_danh_muc'] . "</h5>";
            echo "<div class='row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-4'>";
            while ($mon = $ket_qua_mon->fetch_assoc()) {
                include 'includes/the_mon_an.php';
            }
            echo "</div>";
        }
        if (!$co_ket_qua) {
            echo "<p class='text-muted'>Không tìm thấy món nào phù hợp với '" . htmlspecialchars($tu_khoa) . "'.</p>";
        }
        ?>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>