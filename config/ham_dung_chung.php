<?php
// Cấu hình phí vận chuyển - đổi 2 con số này ở đây nếu muốn thay đổi chính sách ship
define('PHI_SHIP_MAC_DINH', 15000);      // Phí ship mặc định
define('DON_TOI_THIEU_FREESHIP', 150000); // Đơn từ mức này trở lên được miễn phí ship

// Hàm tính phí ship dựa theo tạm tính (tiền món ăn, CHƯA trừ giảm giá)
function tinhPhiShip($tam_tinh) {
    if ($tam_tinh >= DON_TOI_THIEU_FREESHIP) {
        return 0;
    }
    return PHI_SHIP_MAC_DINH;
}
?>