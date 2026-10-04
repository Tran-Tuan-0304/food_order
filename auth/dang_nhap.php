<?php include '../includes/header.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h3 class="text-center mb-4" style="color:var(--primary);">🔑 Đăng nhập</h3>

          <?php if (isset($_GET['loi'])): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($_GET['loi']); ?></div>
          <?php endif; ?>

          <form action="xu_ly_dang_nhap.php" method="POST">
            <!-- Ghi nhớ trang khách đang đứng trước khi bị yêu cầu đăng nhập, để quay lại đúng chỗ sau khi đăng nhập xong -->
            <input type="hidden" name="quay_lai" value="<?php echo isset($_GET['quay_lai']) ? htmlspecialchars($_GET['quay_lai']) : ''; ?>">

            <div class="mb-3">
              <label class="form-label fw-bold">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Mật khẩu</label>
              <input type="password" name="mat_khau" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-brand w-100 py-2 fw-bold">Đăng nhập</button>
          </form>

          <p class="text-center mt-3 mb-0">
            Chưa có tài khoản?
            <a href="dang_ky.php<?php echo isset($_GET['quay_lai']) ? '?quay_lai='.urlencode($_GET['quay_lai']) : ''; ?>" class="fw-bold" style="color:var(--primary);">Đăng ký ngay</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>