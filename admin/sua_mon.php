<?php
include '../includes/header.php';
include '../config/ketnoi.php';
include 'kiem_tra_quyen.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM mon_an WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mon = $stmt->get_result()->fetch_assoc();

if (!$mon) {
    header("Location: mon_an.php");
    exit;
}

$danh_sach_dm = $conn->query("SELECT * FROM danh_muc");
$anh_hien_tai = !empty($mon['hinh_anh']) ? $mon['hinh_anh'] : 'no-image.jpg';
?>

<div class="container my-4">
    <h2 class="fw-bold mb-4">✏️ Sửa món ăn</h2>

    <form action="xu_ly_mon_an.php" method="POST" enctype="multipart/form-data" class="card shadow-sm border-0 p-4">
        <input type="hidden" name="hanh_dong" value="sua">
        <input type="hidden" name="id" value="<?php echo $mon['id']; ?>">

        <label class="form-label fw-bold">Ảnh hiện tại</label>
        <div class="mb-3">
            <img src="../assets/img/<?php echo $anh_hien_tai; ?>" style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Đổi ảnh khác (bỏ trống nếu giữ ảnh cũ)</label>
            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tên món</label>
            <input type="text" name="ten_mon" class="form-control" value="<?php echo htmlspecialchars($mon['ten_mon']); ?>" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá bán (đ)</label>
                <input type="number" name="gia" class="form-control" value="<?php echo $mon['gia']; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá gốc (đ)</label>
                <input type="number" name="gia_goc" class="form-control" value="<?php echo $mon['gia_goc']; ?>" placeholder="Để trống nếu không giảm giá">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Giá vốn (đ)</label>
                <input type="number" name="gia_von" class="form-control" value="<?php echo $mon['gia_von']; ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea name="mo_ta" class="form-control"><?php echo htmlspecialchars($mon['mo_ta']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Danh mục</label>
            <select name="id_danh_muc" class="form-select" required>
                <?php while ($dm = $danh_sach_dm->fetch_assoc()): ?>
                    <option value="<?php echo $dm['id']; ?>" <?php if ($dm['id'] == $mon['id_danh_muc']) echo 'selected'; ?>>
                        <?php echo $dm['ten_danh_muc']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-brand">Lưu thay đổi</button>
    </form>

    <p class="mt-3"><a href="mon_an.php" class="btn btn-outline-secondary btn-sm">← Quay lại danh sách</a></p>
</div>

<?php include '../includes/footer.php'; ?>