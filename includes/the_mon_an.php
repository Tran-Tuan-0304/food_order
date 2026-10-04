<?php
$anh = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
$het_hang = isset($mon['con_hang']) && $mon['con_hang'] == 0;
?>
<div class="col">
    <div class="card h-100 shadow-sm border-0">
        <div class="position-relative">
            <a href="mon_chi_tiet.php?id=<?php echo $mon['id']; ?>">
                <img src="assets/img/<?php echo $anh; ?>" class="card-img-top"
                     style="aspect-ratio:4/3;object-fit:cover;width:100%;<?php echo $het_hang ? 'filter:grayscale(70%);opacity:0.6;' : ''; ?>">
            </a>
            <?php if ($het_hang): ?>
                <span class="badge bg-dark position-absolute top-50 start-50 translate-middle fs-6 px-3 py-2">Hết hàng</span>
            <?php endif; ?>
        </div>
        <div class="card-body d-flex flex-column">
            <h6 class="card-title fw-bold">
                <a href="mon_chi_tiet.php?id=<?php echo $mon['id']; ?>" class="text-decoration-none" style="color:var(--dark);"><?php echo $mon['ten_mon']; ?></a>
            </h6>
            <p class="card-text text-muted small flex-grow-1"><?php echo $mon['mo_ta']; ?></p>
            <?php if (!empty($mon['gia_goc']) && $mon['gia_goc'] > $mon['gia']):
                $phantram = round((1 - $mon['gia'] / $mon['gia_goc']) * 100);
            ?>
            <div class="mb-2">
                <span class="text-muted text-decoration-line-through small me-2"><?php echo number_format($mon['gia_goc']); ?>đ</span>
                <span class="badge badge-giam">-<?php echo $phantram; ?>%</span>
            </div>
            <?php endif; ?>
            <p class="fw-bold fs-5 mb-2" style="color:var(--primary);"><?php echo number_format($mon['gia']); ?>đ</p>
            <?php if ($het_hang): ?>
                <button type="button" class="btn btn-secondary w-100" disabled>Hết hàng</button>
            <?php else: ?>
                <button type="button" class="btn btn-brand w-100 btn-them-gio" data-id="<?php echo $mon['id']; ?>">Thêm vào giỏ</button>
            <?php endif; ?>
        </div>
    </div>
</div>