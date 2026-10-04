<?php
include 'config/ketnoi.php';
include 'includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM danh_muc WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$danhmuc = $stmt->get_result()->fetch_assoc();

if (!$danhmuc) {
    header("Location: index.php");
    exit;
}

$stmt2 = $conn->prepare("SELECT * FROM mon_an WHERE id_danh_muc = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$ds_mon = $stmt2->get_result();
?>

<div class="container my-4">

    <nav class="mb-3">
        <a href="index.php" class="text-decoration-none text-muted">Trang chủ</a>
        <span class="text-muted"> / </span>
        <span class="fw-bold"><?php echo $danhmuc['ten_danh_muc']; ?></span>
    </nav>

    <h2 class="fw-bold mb-4"><?php echo $danhmuc['ten_danh_muc']; ?></h2>

    <?php if ($ds_mon->num_rows == 0): ?>
        <p class="text-muted">Danh mục này hiện chưa có món ăn nào.</p>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php while ($mon = $ds_mon->fetch_assoc()): ?>
                <?php include 'includes/the_mon_an.php'; ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>