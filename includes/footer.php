<footer class="text-center py-4 text-muted border-top mt-5">
<p class="mb-2">
    <a href="/food_order/contact.php" class="text-decoration-none text-muted">📞 Liên hệ</a>
</p>
</footer>

<button id="btn-len-dau" title="Lên đầu trang">↑</button>

<!-- Bootstrap JS (cần cho menu ☰ hoạt động) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

<script>
// Nút lên đầu trang
const btnLenDau = document.getElementById('btn-len-dau');
window.addEventListener('scroll', function() {
    btnLenDau.style.display = window.scrollY > 300 ? 'flex' : 'none';
});
btnLenDau.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Banner tự chạy (nếu trang có banner)
let slideHienTai = 0;
const slides = document.querySelectorAll('.banner-slide');
const dots = document.querySelectorAll('.dot');
function chuyenSlide() {
    if (slides.length === 0) return;
    slides[slideHienTai].classList.remove('active');
    dots[slideHienTai].classList.remove('active');
    slideHienTai = (slideHienTai + 1) % slides.length;
    slides[slideHienTai].classList.add('active');
    dots[slideHienTai].classList.add('active');
}
if (slides.length > 0) setInterval(chuyenSlide, 4000);

// AJAX thêm giỏ hàng - cập nhật cả 2 badge
const daDangNhap = <?php echo isset($_SESSION['dang_nhap']) ? 'true' : 'false'; ?>;

document.querySelectorAll('.btn-them-gio').forEach(function(nut) {
    nut.addEventListener('click', function() {
        if (!daDangNhap) {
            const trangHienTai = window.location.pathname + window.location.search;
            window.location.href = '/food_order/auth/dang_nhap.php'
                + '?quay_lai=' + encodeURIComponent(trangHienTai)
                + '&loi=' + encodeURIComponent('Vui lòng đăng nhập để thêm món vào giỏ hàng');
            return;
        }

        const idMon = this.dataset.id;
        const nutBamHienTai = this;
        let soLuong = 1;
        if (this.dataset.qtyInput) {
            soLuong = parseInt(document.getElementById(this.dataset.qtyInput).value) || 1;
        }

        fetch('donhang/ajax_them_gio_hang.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + idMon + '&so_luong=' + soLuong
        })
        .then(response => response.json())
        .then(data => {
            if (data.thanh_cong) {
                const badge1 = document.getElementById('badge-gio-hang');
                badge1.textContent = data.so_luong_gio;
                badge1.style.display = 'inline-block';

                const badge2 = document.getElementById('badge-gio-hang-noi');
                badge2.textContent = data.so_luong_gio;
                badge2.style.display = 'flex';

                const chuCu = nutBamHienTai.textContent;
                nutBamHienTai.textContent = 'Đã thêm ✓';
                setTimeout(function() { nutBamHienTai.textContent = chuCu; }, 1000);
            }
        })
        .catch(error => console.error('Lỗi khi thêm vào giỏ:', error));
    });
});

// Tự động quay về trang chủ khi xóa hết chữ trong ô tìm kiếm
const oTimKiem = document.getElementById('o-tim-kiem');
if (oTimKiem) {
    const gtriBanDau = oTimKiem.value;
    oTimKiem.addEventListener('input', function() {
        if (this.value.trim() === '' && gtriBanDau !== '') {
            window.location.href = '/food_order/index.php';
        }
    });
}
</script>
</body>
</html>