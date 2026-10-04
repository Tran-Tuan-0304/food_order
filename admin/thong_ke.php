<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$hom_nay = $conn->query("
    SELECT SUM(tong_tien) as tong, COUNT(*) as so_don 
    FROM don_hang 
    WHERE DATE(ngay_dat) = CURDATE() AND trang_thai != 'da_huy'
")->fetch_assoc();

$thang_nay = $conn->query("
    SELECT SUM(tong_tien) as tong, COUNT(*) as so_don 
    FROM don_hang 
    WHERE MONTH(ngay_dat) = MONTH(CURDATE()) AND YEAR(ngay_dat) = YEAR(CURDATE()) AND trang_thai != 'da_huy'
")->fetch_assoc();

$sql_7ngay = "
    SELECT DATE(ngay_dat) as ngay, SUM(tong_tien) as tong 
    FROM don_hang 
    WHERE ngay_dat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND trang_thai != 'da_huy'
    GROUP BY DATE(ngay_dat)
    ORDER BY ngay ASC
";
$ket_qua_7ngay = $conn->query($sql_7ngay);
$du_lieu_7ngay = [];
while ($row = $ket_qua_7ngay->fetch_assoc()) {
    $du_lieu_7ngay[$row['ngay']] = (float) $row['tong'];
}

// Chuẩn bị 2 mảng song song cho Chart.js: nhãn ngày + số tiền tương ứng
$nhan_ngay = [];
$so_tien_ngay = [];
for ($i = 6; $i >= 0; $i--) {
    $ngay = date('Y-m-d', strtotime("-$i days"));
    $nhan_ngay[] = date('d/m', strtotime($ngay));
    $so_tien_ngay[] = $du_lieu_7ngay[$ngay] ?? 0;
}

$top_mon = $conn->query("
    SELECT mon_an.ten_mon, mon_an.hinh_anh, SUM(chi_tiet_don_hang.so_luong) as tong_ban
    FROM chi_tiet_don_hang
    JOIN mon_an ON chi_tiet_don_hang.id_mon = mon_an.id
    JOIN don_hang ON chi_tiet_don_hang.id_don_hang = don_hang.id
    WHERE don_hang.trang_thai != 'da_huy'
    GROUP BY mon_an.id
    ORDER BY tong_ban DESC
    LIMIT 5
");
$top_mon_list = [];
while ($row = $top_mon->fetch_assoc()) { $top_mon_list[] = $row; }
$ban_cao_nhat = count($top_mon_list) > 0 ? $top_mon_list[0]['tong_ban'] : 1;

// Báo cáo lợi nhuận
$loi_nhuan = $conn->query("
    SELECT COALESCE(SUM(tong_tien - phi_ship), 0) as doanh_thu
    FROM don_hang
    WHERE trang_thai = 'hoan_thanh'
      AND MONTH(ngay_dat) = MONTH(CURDATE()) AND YEAR(ngay_dat) = YEAR(CURDATE())
")->fetch_assoc();
$gia_von_ban_ra = $conn->query("
    SELECT COALESCE(SUM(chi_tiet_don_hang.so_luong * mon_an.gia_von), 0) as tong
    FROM chi_tiet_don_hang
    JOIN don_hang ON chi_tiet_don_hang.id_don_hang = don_hang.id
    JOIN mon_an ON chi_tiet_don_hang.id_mon = mon_an.id
    WHERE don_hang.trang_thai = 'hoan_thanh'
      AND MONTH(don_hang.ngay_dat) = MONTH(CURDATE()) AND YEAR(don_hang.ngay_dat) = YEAR(CURDATE())
")->fetch_assoc();
$chi_phi_thang = $conn->query("
    SELECT COALESCE(SUM(so_tien), 0) as tong FROM chi_phi
    WHERE MONTH(ngay) = MONTH(CURDATE()) AND YEAR(ngay) = YEAR(CURDATE())
")->fetch_assoc();
$lai_gop = $loi_nhuan['doanh_thu'] - $gia_von_ban_ra['tong'];
$lai_rong = $lai_gop - $chi_phi_thang['tong'];
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">📊 Thống kê doanh thu</h2>

    <!-- 2 Ô TỔNG QUAN -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="fs-1">📅</div>
                    <div>
                        <div class="text-muted small">Doanh thu hôm nay</div>
                        <div class="fs-3 fw-bold" style="color:var(--primary);"><?php echo number_format($hom_nay['tong'] ?? 0); ?>đ</div>
                        <div class="text-muted small"><?php echo $hom_nay['so_don'] ?? 0; ?> đơn hàng</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="fs-1">🗓️</div>
                    <div>
                        <div class="text-muted small">Doanh thu tháng này</div>
                        <div class="fs-3 fw-bold" style="color:var(--primary);"><?php echo number_format($thang_nay['tong'] ?? 0); ?>đ</div>
                        <div class="text-muted small"><?php echo $thang_nay['so_don'] ?? 0; ?> đơn hàng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- BIỂU ĐỒ 7 NGÀY -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">📈 Doanh thu 7 ngày gần nhất</h5>
                    <canvas id="bieuDo7Ngay" height="220"></canvas>
                </div>
            </div>
        </div>

        <!-- TOP 5 MÓN BÁN CHẠY -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">🏆 Top 5 món bán chạy</h5>

                    <?php if (count($top_mon_list) == 0): ?>
                        <p class="text-muted small">Chưa có dữ liệu bán hàng.</p>
                    <?php else: ?>
                        <?php
                        $huy_hieu = ['🥇', '🥈', '🥉', '4', '5'];
                        foreach ($top_mon_list as $i => $mon):
                            $anh = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
                            $phan_tram_thanh = round($mon['tong_ban'] / $ban_cao_nhat * 100);
                        ?>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="fs-5 fw-bold text-center" style="width:28px;"><?php echo $huy_hieu[$i]; ?></div>
                            <img src="../assets/img/<?php echo $anh; ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?php echo htmlspecialchars($mon['ten_mon']); ?></div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar" style="width:<?php echo $phan_tram_thanh; ?>%; background:var(--primary);"></div>
                                </div>
                            </div>
                            <div class="fw-bold small text-nowrap"><?php echo $mon['tong_ban']; ?> đã bán</div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- BÁO CÁO LỢI NHUẬN -->
    <h3 class="fw-bold mt-5 mb-3">💰 Báo cáo lợi nhuận tháng này</h3>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <table class="table mb-0">
                <tr>
                    <td>Doanh thu (đơn hoàn thành, đã trừ phí ship)</td>
                    <td class="text-end fw-bold"><?php echo number_format($loi_nhuan['doanh_thu']); ?>đ</td>
                </tr>
                <tr>
                    <td>Giá vốn hàng đã bán</td>
                    <td class="text-end text-danger">- <?php echo number_format($gia_von_ban_ra['tong']); ?>đ</td>
                </tr>
                <tr class="table-light">
                    <td class="fw-bold">Lãi gộp</td>
                    <td class="text-end fw-bold"><?php echo number_format($lai_gop); ?>đ</td>
                </tr>
                <tr>
                    <td>Chi phí khác (nguyên liệu, điện nước, lương...)</td>
                    <td class="text-end text-danger">- <?php echo number_format($chi_phi_thang['tong']); ?>đ</td>
                </tr>
                <tr class="table-light">
                    <td class="fw-bold fs-5">Lãi ròng</td>
                    <td class="text-end fw-bold fs-5" style="color:<?php echo $lai_rong >= 0 ? 'var(--secondary)' : 'var(--primary)'; ?>;">
                        <?php echo number_format($lai_rong); ?>đ
                    </td>
                </tr>
            </table>
            <p class="text-muted small mt-3 mb-0">
                * Giá vốn được tính theo giá vốn hiện tại của từng món trong kho, mang tính ước lượng gần đúng.
                Bạn có thể vào <a href="mon_an.php">Quản lý món ăn</a> để cập nhật giá vốn chính xác hơn cho từng món.
            </p>
            <a href="chi_phi.php" class="btn btn-outline-secondary btn-sm mt-2">Quản lý chi phí khác</a>
        </div>
    </div>
</div>

<!-- Chart.js - thư viện vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('bieuDo7Ngay');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($nhan_ngay); ?>,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: <?php echo json_encode($so_tien_ngay); ?>,
            backgroundColor: '#FF4D3D',
            borderRadius: 8,
            maxBarThickness: 50
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) { return value.toLocaleString('vi-VN'); }
                }
            }
        }
    }
});
</script>

<?php include '../includes/footer.php'; ?>