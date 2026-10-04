<?php
include '../includes/header.php';
include '../config/ketnoi.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$don = $stmt->get_result()->fetch_assoc();
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h3 class="text-center mb-3">✅ Đặt hàng thành công!</h3>
          <p>Mã đơn hàng: <strong>#<?php echo $don['id']; ?></strong></p>
          <p>Khách hàng: <?php echo $don['ten_khach']; ?></p>
          <p>Phí vận chuyển: <?php echo $don['phi_ship'] == 0 ? 'Miễn phí' : number_format($don['phi_ship']).'đ'; ?></p>
          <p>Tổng tiền: <strong class="fs-5" style="color:var(--primary);"><?php echo number_format($don['tong_tien']); ?>đ</strong></p>
          <p>Trạng thái đơn: Chờ quán xác nhận</p>

          <?php if ($don['phuong_thuc_tt'] == 'chuyen_khoan'): ?>
          <hr>
          <div class="text-center">
              <h5 class="fw-bold mb-3">🏦 Quét mã để chuyển khoản</h5>
              <?php
              // Nội dung mã hóa trong QR - chỉ mang tính minh họa cho đồ án, không phải QR ngân hàng thật
              $noi_dung_qr = "NGAN HANG: VIETNAM DEMO BANK\n"
                           . "SO TAI KHOAN: 0123456789\n"
                           . "CHU TAI KHOAN: NGON MOI NGAY\n"
                           . "SO TIEN: " . number_format($don['tong_tien']) . "d\n"
                           . "NOI DUNG: DH" . $don['id'];
              $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($noi_dung_qr);
              ?>
              <img src="<?php echo $qr_url; ?>" alt="Mã QR chuyển khoản" class="img-fluid mb-3" style="max-width:220px;">
              <div class="text-start bg-light rounded p-3 small">
                  <p class="mb-1"><strong>Ngân hàng:</strong> Vietnam Demo Bank</p>
                  <p class="mb-1"><strong>Số tài khoản:</strong> 0123456789</p>
                  <p class="mb-1"><strong>Chủ tài khoản:</strong> NGON MOI NGAY</p>
                  <p class="mb-1"><strong>Số tiền:</strong> <?php echo number_format($don['tong_tien']); ?>đ</p>
                  <p class="mb-0"><strong>Nội dung CK:</strong> DH<?php echo $don['id']; ?></p>
              </div>
              <p class="text-muted small mt-2">
                  Sau khi chuyển khoản, đơn hàng của bạn sẽ được quán xác nhận trong ít phút.
                  <em>(Đây là mã QR minh họa cho đồ án, không phải tài khoản ngân hàng thật.)</em>
              </p>
          </div>
          <?php endif; ?>

          <a href="../index.php" class="btn btn-brand w-100 mt-3">Tiếp tục mua sắm</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>