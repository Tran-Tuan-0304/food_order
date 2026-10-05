-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 05, 2026 lúc 03:56 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `food_order_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `hinh_anh` varchar(255) NOT NULL,
  `tieu_de` varchar(200) DEFAULT NULL,
  `thu_tu` int(11) NOT NULL DEFAULT 0,
  `kich_hoat` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banner`
--

INSERT INTO `banner` (`id`, `hinh_anh`, `tieu_de`, `thu_tu`, `kich_hoat`) VALUES
(3, '1790781645_1.jpg', '', 0, 1),
(4, '1790782013_2.jpg', '', 1, 1),
(5, '1790782122_3.jpg', '', 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_phi`
--

CREATE TABLE `chi_phi` (
  `id` int(11) NOT NULL,
  `ten_chi_phi` varchar(200) NOT NULL,
  `so_tien` decimal(10,0) NOT NULL,
  `ngay` date NOT NULL,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(11) NOT NULL,
  `id_don_hang` int(11) DEFAULT NULL,
  `id_mon` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`id`, `id_don_hang`, `id_mon`, `so_luong`, `don_gia`) VALUES
(1, 1, 1, 1, 25000),
(2, 1, 2, 1, 30000),
(3, 1, 5, 1, 20000),
(4, 2, 4, 2, 32000),
(5, 3, 2, 1, 30000),
(6, 3, 4, 2, 32000),
(7, 4, 6, 1, 28000),
(8, 4, 2, 1, 30000),
(9, 4, 1, 1, 25000),
(10, 4, 12, 1, 38000),
(11, 4, 14, 1, 18000),
(12, 5, 2, 1, 30000),
(13, 5, 14, 1, 18000),
(14, 5, 19, 1, 42000),
(15, 5, 24, 1, 40000),
(16, 5, 30, 1, 18000),
(17, 6, 3, 1, 35000),
(18, 7, 2, 2, 30000),
(19, 7, 4, 2, 32000),
(20, 7, 10, 2, 40000),
(21, 8, 1, 1, 25000),
(22, 9, 2, 1, 30000),
(23, 10, 7, 1, 30000),
(24, 11, 1, 2, 25000),
(25, 11, 10, 3, 40000),
(26, 12, 1, 1, 25000),
(27, 12, 10, 1, 40000),
(28, 13, 1, 1, 25000),
(29, 14, 1, 1, 25000),
(30, 15, 27, 1, 20000),
(31, 15, 19, 1, 42000),
(32, 15, 18, 1, 45000),
(33, 15, 1, 1, 25000),
(34, 15, 29, 1, 22000),
(35, 16, 5, 1, 20000),
(36, 16, 11, 1, 35000),
(37, 16, 26, 1, 85000),
(38, 16, 4, 1, 32000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten_danh_muc`) VALUES
(1, 'Trà sữa'),
(2, 'Cơm'),
(3, 'Đồ ăn vặt'),
(4, 'Bún - Phở'),
(5, 'Burger - Pizza'),
(6, 'Nước uống'),
(7, 'Tráng miệng'),
(8, 'Món chay'),
(9, 'Lẩu - Nướng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `ten_khach` varchar(100) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `tong_tien` decimal(10,0) NOT NULL,
  `trang_thai` enum('cho_xac_nhan','da_nhan','dang_lam','dang_giao','hoan_thanh','da_huy') NOT NULL DEFAULT 'cho_xac_nhan',
  `id_ma_giam_gia` int(11) DEFAULT NULL,
  `tien_giam` decimal(10,0) NOT NULL DEFAULT 0,
  `phi_ship` decimal(10,0) NOT NULL DEFAULT 0,
  `phuong_thuc_tt` enum('cod','chuyen_khoan') NOT NULL DEFAULT 'cod',
  `trang_thai_tt` enum('chua_thanh_toan','da_thanh_toan') NOT NULL DEFAULT 'chua_thanh_toan',
  `ten_shipper` varchar(100) DEFAULT NULL,
  `sdt_shipper` varchar(15) DEFAULT NULL,
  `dia_chi_giao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_nguoi_dung`, `ten_khach`, `sdt`, `ngay_dat`, `tong_tien`, `trang_thai`, `id_ma_giam_gia`, `tien_giam`, `phi_ship`, `phuong_thuc_tt`, `trang_thai_tt`, `ten_shipper`, `sdt_shipper`, `dia_chi_giao`) VALUES
(1, NULL, 'Tran Tuan', '09116316', '2026-08-19 22:04:46', 75000, 'hoan_thanh', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'suygaiscb'),
(2, 2, 'Tran Tuan', '0123456', '2026-08-20 12:59:53', 64000, 'hoan_thanh', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'êfwefwf'),
(3, 2, 'Tran Tuan', '0123456', '2026-08-20 13:01:28', 94000, 'hoan_thanh', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'êfwefwf'),
(4, 2, 'Tran Tuan', '0123456', '2026-08-22 20:59:18', 139000, 'hoan_thanh', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'êfwefwf'),
(5, 2, 'Tran Tuan', '0123456', '2026-08-23 16:40:48', 148000, 'cho_xac_nhan', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, '12 Trần Hưng Đạo'),
(6, NULL, 'đạt', '123456', '2026-09-19 09:22:25', 35000, 'cho_xac_nhan', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'trường đại hk kinh công'),
(7, 3, 'Tran Quoc Tuan', '0948337015', '2026-09-19 10:15:51', 204000, 'hoan_thanh', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, 'nhà của hoàng anh'),
(8, 2, 'Tran Tuan', '0123456', '2026-09-19 12:21:33', 25000, 'dang_giao', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, '12 Trần Hưng Đạo'),
(9, NULL, 'Tran Tuan', '0123456', '2026-09-19 12:26:31', 30000, 'dang_giao', NULL, 0, 0, 'cod', 'chua_thanh_toan', 'Hải', '0897263578', 'ini'),
(10, 2, 'Tran Tuan', '0123456', '2026-09-19 12:34:00', 30000, 'da_huy', NULL, 0, 0, 'cod', 'chua_thanh_toan', NULL, NULL, '12 Trần Hưng Đạo'),
(11, 3, 'Tran Quoc Tuan', '0948337015', '2026-09-30 16:44:26', 150000, 'cho_xac_nhan', 3, 20000, 0, 'chuyen_khoan', 'da_thanh_toan', NULL, NULL, 'nhà của hoàng anh'),
(12, 2, 'Tran Tuan', '0123456', '2026-09-30 16:46:02', 73500, 'da_huy', 1, 6500, 15000, 'cod', 'chua_thanh_toan', NULL, NULL, '12 Trần Hưng Đạo'),
(13, 2, 'Tran Tuan', '0123456', '2026-09-30 16:49:12', 40000, 'hoan_thanh', NULL, 0, 15000, 'cod', 'chua_thanh_toan', 'hải', '0927536763', '12 Trần Hưng Đạo'),
(14, 3, 'Tran Quoc Tuan', '0948337015', '2026-09-30 21:42:32', 40000, 'hoan_thanh', NULL, 0, 15000, 'cod', 'chua_thanh_toan', NULL, NULL, 'nhà của hoàng anh'),
(15, 2, 'Tran Tuan', '0123456', '2026-10-01 13:46:52', 134000, 'hoan_thanh', 3, 20000, 0, 'chuyen_khoan', 'da_thanh_toan', 'Công', '037446519', '12 Trần Hưng Đạo'),
(16, 2, 'Tran Tuan', '0123456', '2026-10-02 09:01:19', 152000, 'hoan_thanh', 3, 20000, 0, 'chuyen_khoan', 'da_thanh_toan', 'Khải', '012345678', '12 Trần Hưng Đạo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_don_hang`
--

CREATE TABLE `lich_su_don_hang` (
  `id` int(11) NOT NULL,
  `id_don_hang` int(11) NOT NULL,
  `trang_thai` varchar(30) NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `thoi_gian` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_don_hang`
--

INSERT INTO `lich_su_don_hang` (`id`, `id_don_hang`, `trang_thai`, `ghi_chu`, `thoi_gian`) VALUES
(1, 11, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-09-30 16:44:26'),
(2, 11, 'da_nhan', 'Quán đã nhận đơn của bạn', '2026-09-30 16:45:23'),
(3, 11, 'cho_xac_nhan', 'Đơn hàng đang chờ quán xác nhận', '2026-09-30 16:45:25'),
(4, 12, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-09-30 16:46:02'),
(5, 12, 'da_huy', 'Khách hàng đã hủy đơn hàng', '2026-09-30 16:46:21'),
(6, 9, 'dang_giao', 'Đơn hàng đang được giao bởi shipper Hải (0897263578)', '2026-09-30 16:47:39'),
(7, 13, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-09-30 16:49:12'),
(8, 13, 'dang_giao', 'Đơn hàng đang được giao bởi shipper hải (0927536763)', '2026-09-30 16:49:45'),
(9, 13, 'hoan_thanh', 'Đơn hàng đã giao thành công', '2026-09-30 20:09:48'),
(10, 14, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-09-30 21:42:32'),
(11, 14, 'hoan_thanh', 'Đơn hàng đã giao thành công', '2026-09-30 21:53:27'),
(12, 15, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-10-01 13:46:52'),
(13, 15, 'da_nhan', 'Quán đã nhận đơn của bạn', '2026-10-01 13:51:14'),
(14, 15, 'dang_lam', 'Quán đang chuẩn bị món ăn', '2026-10-01 13:51:22'),
(15, 15, 'dang_giao', 'Đơn hàng đang được giao bởi shipper Công (037446519)', '2026-10-01 13:51:37'),
(16, 15, 'hoan_thanh', 'Đơn hàng đã giao thành công', '2026-10-01 13:51:42'),
(17, 16, 'cho_xac_nhan', 'Đơn hàng đã được đặt, đang chờ quán xác nhận', '2026-10-02 09:01:19'),
(18, 16, 'da_nhan', 'Quán đã nhận đơn của bạn', '2026-10-02 09:03:42'),
(19, 16, 'dang_lam', 'Quán đang chuẩn bị món ăn', '2026-10-02 09:03:48'),
(20, 16, 'dang_giao', 'Đơn hàng đang được giao bởi shipper Khải (012345678)', '2026-10-02 09:04:14'),
(21, 16, 'hoan_thanh', 'Đơn hàng đã giao thành công', '2026-10-02 09:04:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ma_giam_gia`
--

CREATE TABLE `ma_giam_gia` (
  `id` int(11) NOT NULL,
  `ma` varchar(30) NOT NULL,
  `loai` enum('phan_tram','tien_mat') NOT NULL,
  `gia_tri` decimal(10,0) NOT NULL,
  `don_toi_thieu` decimal(10,0) NOT NULL DEFAULT 0,
  `giam_toi_da` decimal(10,0) DEFAULT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 100,
  `han_dung` date DEFAULT NULL,
  `kich_hoat` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ma_giam_gia`
--

INSERT INTO `ma_giam_gia` (`id`, `ma`, `loai`, `gia_tri`, `don_toi_thieu`, `giam_toi_da`, `so_luong`, `han_dung`, `kich_hoat`) VALUES
(1, 'NGON10', 'phan_tram', 10, 50000, 30000, 99, '2026-12-31', 1),
(2, 'FREESHIP', 'tien_mat', 15000, 0, NULL, 200, '2026-12-31', 1),
(3, 'GIAM20K', 'tien_mat', 20000, 100000, NULL, 47, '2026-12-31', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mon_an`
--

CREATE TABLE `mon_an` (
  `id` int(11) NOT NULL,
  `ten_mon` varchar(150) NOT NULL,
  `gia` decimal(10,0) NOT NULL,
  `gia_goc` decimal(10,0) DEFAULT NULL,
  `gia_von` decimal(10,0) NOT NULL DEFAULT 0,
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0,
  `mo_ta` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `id_danh_muc` int(11) DEFAULT NULL,
  `con_hang` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `mon_an`
--

INSERT INTO `mon_an` (`id`, `ten_mon`, `gia`, `gia_goc`, `gia_von`, `noi_bat`, `mo_ta`, `hinh_anh`, `id_danh_muc`, `con_hang`) VALUES
(1, 'Trà sữa trân châu', 25000, 35000, 15000, 0, 'Trà sữa truyền thống, trân châu đen', '1787408114_no-image.jpg', 1, 1),
(2, 'Trà sữa matcha', 30000, NULL, 18000, 0, 'Vị matcha Nhật Bản đậm đà', '1787408131_ts matcha.jpg', 1, 1),
(3, 'Cơm gà xối mỡ', 35000, NULL, 21000, 0, 'Cơm nóng kèm gà chiên giòn', '1787218432_Com-Ga-Xoi-Mo-01.jpg', 2, 1),
(4, 'Cơm sườn nướng', 32000, NULL, 19200, 0, 'Sườn nướng mật ong thơm lừng', '1787218459_com suon nuong.jpg', 2, 1),
(5, 'Khoai tây chiên', 20000, NULL, 12000, 0, 'Khoai tây chiên giòn rụm', '1787218515_khoai-tay-chien.jpg', 3, 1),
(6, 'Trà sữa Oolong', 28000, NULL, 16800, 0, 'Vị trà Oolong thanh mát, ít ngọt', '1787218390_ts oolong.jpg', 1, 1),
(7, 'Trà sữa khoai môn', 30000, NULL, 18000, 0, 'Béo thơm vị khoai môn tự nhiên', '1787218399_ts khoai mon.jpg', 1, 1),
(8, 'Hồng trà sữa', 25000, NULL, 15000, 0, 'Hồng trà đậm vị, topping trân châu trắng', '1787218408_hong-tra-sua.jpg', 1, 1),
(9, 'Trà sữa socola', 32000, NULL, 19200, 0, 'Vị socola đậm đà, phủ kem tươi', '1787218419_tra-sua-chocolate.jpg', 1, 1),
(10, 'Cơm tấm sườn bì chả', 40000, NULL, 24000, 1, 'Cơm tấm truyền thống đầy đủ topping', '1787218469_com suon bi cha.jpg', 2, 1),
(11, 'Cơm chiên dương châu', 35000, NULL, 21000, 0, 'Cơm chiên trứng, tôm, lạp xưởng, rau củ', '1787218478_cơm chiên dương châu.jpg', 2, 1),
(12, 'Cơm gà xé Hải Nam', 38000, NULL, 22800, 0, 'Gà luộc xé, nước chấm gừng đặc trưng', '1787218490_com ga hai nam.jpg', 2, 1),
(13, 'Cơm cá kho tộ', 36000, NULL, 21600, 0, 'Cá kho tộ đậm đà, ăn kèm canh chua', '1787218500_com ca kho tộ.jpg', 2, 1),
(14, 'Xúc xích chiên', 18000, NULL, 10800, 0, 'Xúc xích chiên giòn, ăn kèm tương ớt', '1787218532_xuc-xich-chien.jpg', 3, 1),
(15, 'Bánh tráng trộn', 22000, NULL, 13200, 0, 'Bánh tráng trộn khô bò, rau răm, đậu phộng', '1787218544_banh trang tron.jpg', 3, 1),
(16, 'Nem chua rán', 25000, NULL, 15000, 0, 'Nem chua rán giòn, chấm tương ớt', '1787218551_nem chua rán.jpg', 3, 1),
(17, 'Cá viên chiên', 20000, NULL, 12000, 0, 'Cá viên chiên giòn kèm nước chấm', '1787218560_ca vien chien.jpg', 3, 1),
(18, 'Phở bò tái', 45000, NULL, 27000, 0, 'Phở bò truyền thống, nước dùng ninh xương', '1787218573_pho bo tai.jpg', 4, 1),
(19, 'Bún chả Hà Nội', 42000, NULL, 25200, 0, 'Chả nướng thơm, nước chấm chua ngọt', '1787218581_bun cha hn.jpg', 4, 1),
(20, 'Bún bò Huế', 45000, NULL, 27000, 0, 'Vị cay đặc trưng, đầy đủ giò heo, chả', '1787218591_bun bo hue.jpg', 4, 1),
(21, 'Bún riêu cua', 38000, NULL, 22800, 0, 'Riêu cua đồng, cà chua, đậu phụ', '1787218601_bun rieu cua.jpg', 4, 1),
(22, 'Phở gà', 40000, NULL, 24000, 0, 'Nước dùng thanh ngọt từ gà ta', '1787218613_pho ga.jpg', 4, 1),
(23, 'Burger bò phô mai', 45000, NULL, 27000, 0, 'Bò Úc, phô mai tan chảy, rau tươi', '1787218637_burger bo pho mai.jpg', 5, 1),
(24, 'Burger gà giòn', 40000, NULL, 24000, 0, 'Gà chiên giòn, sốt mayonnaise', '1787218649_ga gion.jpg', 5, 1),
(25, 'Pizza hải sản', 89000, NULL, 53400, 0, 'Tôm, mực, phô mai Mozzarella', '1787218662_pizza hai san.jpg', 5, 1),
(26, 'Pizza Pepperoni', 85000, NULL, 51000, 0, 'Xúc xích Pepperoni cay nhẹ', '1787218672_pizza pepperoni.jpg', 5, 1),
(27, 'Nước cam ép', 20000, NULL, 12000, 0, 'Cam tươi vắt nguyên chất', '1787218687_nuoc cam ep.jpg', 6, 1),
(28, 'Sinh tố bơ', 25000, NULL, 15000, 0, 'Bơ sáp béo ngậy, sữa đặc', '1787218697_sinh to bo.jpg', 6, 1),
(29, 'Cà phê sữa đá', 22000, NULL, 13200, 0, 'Cà phê phin truyền thống Việt Nam', '1787218708_cf sữa đa.jpg', 6, 1),
(30, 'Nước chanh dây', 18000, NULL, 10800, 0, 'Chua ngọt, giải khát', '1787218719_chanh-day-3.jpg', 6, 1),
(31, 'Trà đào cam sả', 25000, NULL, 15000, 0, 'Trà đào thơm mát kết hợp cam sả', '1787218728_tra-dao-cam-sa.jpg', 6, 1),
(32, 'Bánh flan', 15000, NULL, 9000, 0, 'Bánh flan caramen mềm mịn', '1787218744_flan.jpg', 7, 1),
(33, 'Chè khúc bạch', 20000, NULL, 12000, 0, 'Khúc bạch, hạnh nhân, nhãn', '1787218755_che khuc bach.jpg', 7, 1),
(34, 'Kem dừa', 22000, NULL, 13200, 0, 'Kem tươi trong trái dừa xiêm', '1787218767_kem-dua.jpg', 7, 1),
(35, 'Bánh su kem', 18000, NULL, 10800, 0, 'Vỏ giòn, nhân kem sữa thơm béo', '1787218777_su kem.jpg', 7, 1),
(36, 'Cơm chay thập cẩm', 35000, NULL, 21000, 0, 'Đậu hũ, nấm, rau củ theo mùa', '1787218797_Cơm chay thập cẩm.jpg', 8, 1),
(37, 'Bún chay', 32000, NULL, 19200, 0, 'Nước dùng nấm, đậu hũ, rau', '1787218811_bun chay.jpg', 8, 1),
(38, 'Gỏi cuốn chay', 28000, NULL, 16800, 0, 'Cuốn tươi mát với đậu hũ, bún, rau', '1787218822_goi cuon chay.jpg', 8, 1),
(39, 'Lẩu thái hải sản (2 người)', 150000, NULL, 90000, 0, 'Vị chua cay đặc trưng Thái Lan', '1787218835_lau-thai-hai-san.jpg', 9, 1),
(40, 'Set nướng BBQ (2 người)', 180000, NULL, 108000, 0, 'Thịt bò, gà, hải sản nướng than hoa', '1787218845_set nuong.jpg', 9, 1),
(41, 'Lẩu gà lá é', 140000, NULL, 84000, 0, 'Đặc sản Đà Lạt, vị lá é đặc trưng', '1787218857_lẩu gà lá é.jpg', 9, 1),
(42, 'Lẩu cá tầm Đà Lạt', 250000, NULL, 150000, 0, 'Nước lẩu ngọt thanh, cá tầm chắc thịt', '1787407595_lau-ca-tam-da-lat.jpg', 9, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `vai_tro` enum('khach_hang','admin') DEFAULT 'khach_hang',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `sdt`, `dia_chi`, `vai_tro`, `ngay_tao`) VALUES
(2, 'Tran Tuan', 't206@gmail.com', '$2y$10$F/gVOl6Uij9QlFtWXNa1/uVaGS2ndsEUoTJhhswsmaEWWix//eL5i', '0123456', '12 Trần Hưng Đạo', 'khach_hang', '2026-08-20 12:32:27'),
(3, 'Tran Quoc Tuan', 'trantuan206@gmail.com', '$2y$10$FIr3WzohrzMIlyFvtIm9b.DcHMf8QvEbeZOGfm.j6Q9QisnIkoYY6', '0948337015', 'nhà của hoàng anh', 'admin', '2026-08-23 10:08:16');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chi_phi`
--
ALTER TABLE `chi_phi`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_don_hang` (`id_don_hang`),
  ADD KEY `id_mon` (`id_mon`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`),
  ADD KEY `fk_donhang_magiamgia` (`id_ma_giam_gia`);

--
-- Chỉ mục cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_don_hang` (`id_don_hang`);

--
-- Chỉ mục cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma` (`ma`);

--
-- Chỉ mục cho bảng `mon_an`
--
ALTER TABLE `mon_an`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_danh_muc` (`id_danh_muc`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `chi_phi`
--
ALTER TABLE `chi_phi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `mon_an`
--
ALTER TABLE `mon_an`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`id_mon`) REFERENCES `mon_an` (`id`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `fk_donhang_magiamgia` FOREIGN KEY (`id_ma_giam_gia`) REFERENCES `ma_giam_gia` (`id`);

--
-- Các ràng buộc cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  ADD CONSTRAINT `lich_su_don_hang_ibfk_1` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`);

--
-- Các ràng buộc cho bảng `mon_an`
--
ALTER TABLE `mon_an`
  ADD CONSTRAINT `mon_an_ibfk_1` FOREIGN KEY (`id_danh_muc`) REFERENCES `danh_muc` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
