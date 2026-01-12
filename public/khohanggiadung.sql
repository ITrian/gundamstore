-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th1 12, 2026 lúc 06:55 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `khohanggiadung`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieudathang`
--

DROP TABLE IF EXISTS `ct_phieudathang`;
CREATE TABLE IF NOT EXISTS `ct_phieudathang` (
  `maDH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuong` int NOT NULL,
  `soLuongDaNhap` int DEFAULT '0',
  `donGia` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`maDH`,`maHH`),
  KEY `fk_ctpdh_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_phieudathang`
--

INSERT INTO `ct_phieudathang` (`maDH`, `maHH`, `soLuong`, `soLuongDaNhap`, `donGia`) VALUES
('PD1768186155', 'HH001', 2, 2, 7000000.00),
('PD1768186155', 'HH002', 3, 3, 445000.00),
('PD1768186155', 'HH003', 5, 5, 600000.00),
('PD1768194143', 'HH003', 1, 3, 0.00),
('PD1768194229', 'HH002', 3, 4, 7000000.00),
('PD1768200131', 'HH002', 5, 8, 750000.00),
('PD1768200131', 'HH003', 3, 5, 500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieukiemke`
--

DROP TABLE IF EXISTS `ct_phieukiemke`;
CREATE TABLE IF NOT EXISTS `ct_phieukiemke` (
  `maKK` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuongThucTe` int NOT NULL,
  PRIMARY KEY (`maKK`,`maHH`),
  KEY `fk_ctkk_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieunhap`
--

DROP TABLE IF EXISTS `ct_phieunhap`;
CREATE TABLE IF NOT EXISTS `ct_phieunhap` (
  `maPN` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuong` int NOT NULL,
  `donGia` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`maPN`,`maHH`),
  KEY `fk_ctpn_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_phieunhap`
--

INSERT INTO `ct_phieunhap` (`maPN`, `maHH`, `soLuong`, `donGia`) VALUES
('PN-260112-001', 'HH001', 1, 7000000.00),
('PN-260112-001', 'HH002', 3, 445000.00),
('PN-260112-001', 'HH003', 4, 600000.00),
('PN-260112-002', 'HH001', 1, 7000000.00),
('PN-260112-002', 'HH002', 1, 445000.00),
('PN-260112-002', 'HH003', 3, 600000.00),
('PN-260112-003', 'HH001', 1, 7000000.00),
('PN-260112-003', 'HH002', 3, 445000.00),
('PN-260112-003', 'HH003', 2, 600000.00),
('PN-260112-004', 'HH001', 1, 7000000.00),
('PN-260112-004', 'HH003', 3, 600000.00),
('PN-260112-005', 'HH001', 1, 7000000.00),
('PN-260112-005', 'HH002', 3, 445000.00),
('PN-260112-005', 'HH003', 2, 600000.00),
('PN-260112-006', 'HH001', 1, 7000000.00),
('PN-260112-006', 'HH003', 3, 600000.00),
('PN-260112-007', 'HH003', 3, 50.00),
('PN-260112-008', 'HH002', 4, 7000000.00),
('PN-260112-009', 'HH002', 3, 750000.00),
('PN-260112-009', 'HH003', 2, 500000.00),
('PN-260112-010', 'HH002', 5, 750000.00),
('PN-260112-010', 'HH003', 3, 500000.00),
('PN-260112-011', 'HH001', 4, 100000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieuxuat`
--

DROP TABLE IF EXISTS `ct_phieuxuat`;
CREATE TABLE IF NOT EXISTS `ct_phieuxuat` (
  `maPX` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuong` int NOT NULL,
  `donGia` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`maPX`,`maHH`),
  KEY `fk_ctpx_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieuxuat_lo`
--

DROP TABLE IF EXISTS `ct_phieuxuat_lo`;
CREATE TABLE IF NOT EXISTS `ct_phieuxuat_lo` (
  `maPX` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `maLo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `maViTri` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuong` int NOT NULL,
  PRIMARY KEY (`maPX`,`maHH`,`maLo`,`maViTri`),
  KEY `fk_ctpx_lo_lo` (`maLo`),
  KEY `fk_ctpx_lo_vt` (`maViTri`),
  KEY `fk_ctpx_lo_px` (`maPX`),
  KEY `fk_ctpx_lo_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieuxuat_serial`
--

DROP TABLE IF EXISTS `ct_phieuxuat_serial`;
CREATE TABLE IF NOT EXISTS `ct_phieuxuat_serial` (
  `maPX` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maPX`,`maHH`,`serial`),
  KEY `fk_ctpx_ser_px` (`maPX`),
  KEY `fk_ctpx_ser_hh` (`maHH`),
  KEY `fk_ctpx_ser_serial` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

DROP TABLE IF EXISTS `danhmuc`;
CREATE TABLE IF NOT EXISTS `danhmuc` (
  `maDanhMuc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenDanhMuc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maDanhMuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`maDanhMuc`, `tenDanhMuc`) VALUES
('DM01', 'Tủ Lạnh'),
('DM02', 'Nồi cơm điện'),
('DM03', 'Quạt điện');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donvitinh`
--

DROP TABLE IF EXISTS `donvitinh`;
CREATE TABLE IF NOT EXISTS `donvitinh` (
  `maDVT` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenDVT` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maDVT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donvitinh`
--

INSERT INTO `donvitinh` (`maDVT`, `tenDVT`) VALUES
('DVT01', 'Cái'),
('DVT02', 'Chiếc'),
('DVT03', 'Bộ'),
('DVT04', 'Hộp'),
('DVT05', 'Thùng'),
('DVT06', 'Cặp'),
('DVT07', 'Cuộn'),
('DVT08', 'Mét'),
('DVT09', 'Vỉ'),
('DVT10', 'Lốc'),
('DVT11', 'Túi'),
('DVT12', 'Bao');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hanghoa`
--

DROP TABLE IF EXISTS `hanghoa`;
CREATE TABLE IF NOT EXISTS `hanghoa` (
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenHH` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maDVT` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thuongHieu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moTa` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maDanhMuc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loaiHang` enum('LO','SERIAL') COLLATE utf8mb4_unicode_ci DEFAULT 'LO',
  `heSoChiemCho` int DEFAULT '1',
  `thoiGianBaoHanh` int DEFAULT '12' COMMENT 'Thời gian bảo hành cho khách (Tháng)',
  PRIMARY KEY (`maHH`),
  KEY `fk_hanghoa_dvt` (`maDVT`),
  KEY `fk_hanghoa_danhmuc` (`maDanhMuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hanghoa`
--

INSERT INTO `hanghoa` (`maHH`, `tenHH`, `maDVT`, `thuongHieu`, `model`, `moTa`, `maDanhMuc`, `loaiHang`, `heSoChiemCho`, `thoiGianBaoHanh`) VALUES
('HH001', 'Tủ lạnh aqua inverter', 'DVT01', 'AQUA', 'AQR-B390MA', 'Ngăn đá dưới - 2 cánh\r\nDung tích tổng: 350 lít\r\nDung tích sử dụng: 324 lít - 3 - 4 người\r\nDung tích ngăn đá: 91 lít\r\nDung tích ngăn lạnh: 199 lít\r\nDung tích ngăn chuyển đổi: 34 lít\r\nChất liệu cửa tủ lạnh: Mặt thép\r\nChất liệu khay ngăn lạnh: Kính chịu lực\r\nChất liệu ống dẫn gas, dàn lạnh: Ống dẫn gas', 'DM01', 'SERIAL', 50, 12),
('HH002', 'Quạt bàn Senko', 'DVT01', 'Senko', 'B1612 47W', 'Màu xanh dương', 'DM03', 'LO', 10, 12),
('HH003', 'Nồi cơm điện toshiba', 'DVT01', 'toshiba', 'rc-10jfmvn', '', 'DM02', 'SERIAL', 20, 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hanghoa_serial`
--

DROP TABLE IF EXISTS `hanghoa_serial`;
CREATE TABLE IF NOT EXISTS `hanghoa_serial` (
  `serial` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maLo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` int DEFAULT '1',
  `maViTri` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`serial`),
  KEY `fk_hhs_lo` (`maLo`),
  KEY `fk_hhs_vitri` (`maViTri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hanghoa_serial`
--

INSERT INTO `hanghoa_serial` (`serial`, `maLo`, `trangThai`, `maViTri`) VALUES
('SCAN-1768189270439-431', 'LO202601120001', 1, 'A-1-1'),
('SCAN-1768189272950-575', 'LO202601120003', 1, 'A-1-1'),
('SCAN-1768189273228-573', 'LO202601120003', 1, 'A-1-2'),
('SCAN-1768189273558-584', 'LO202601120003', 1, 'A-1-2'),
('SCAN-1768189273866-42', 'LO202601120003', 1, 'A-1-2'),
('SCAN-1768190869151-188', 'LO202601120004', 1, 'A-1-3'),
('SCAN-1768190871146-997', 'LO202601120006', 1, 'A-1-2'),
('SCAN-1768190871465-525', 'LO202601120006', 1, 'A-1-3'),
('SCAN-1768190871743-801', 'LO202601120006', 1, 'A-1-3'),
('SCAN-1768191857075-326', 'LO202601120007', 1, 'A-1-1'),
('SCAN-1768191859413-637', 'LO202601120009', 1, 'A-1-1'),
('SCAN-1768191860188-742', 'LO202601120009', 1, 'A-1-1'),
('SCAN-1768192047305-573', 'LO202601120010', 1, 'A-1-1'),
('SCAN-1768192049064-577', 'LO202601120011', 1, 'A-1-1'),
('SCAN-1768192049387-291', 'LO202601120011', 1, 'A-1-1'),
('SCAN-1768192049890-899', 'LO202601120011', 1, 'A-1-1'),
('SCAN-1768193937875-223', 'LO202601120012', 1, 'A-1-1'),
('SCAN-1768193939747-368', 'LO202601120014', 1, 'A-1-1'),
('SCAN-1768193940124-686', 'LO202601120014', 1, 'A-1-1'),
('SCAN-1768194023675-525', 'LO202601120015', 1, 'A-1-1'),
('SCAN-1768194025444-913', 'LO202601120016', 1, 'A-1-1'),
('SCAN-1768194025792-964', 'LO202601120016', 1, 'A-1-1'),
('SCAN-1768194026090-16', 'LO202601120016', 1, 'A-1-1'),
('SCAN-1768194173383-856', 'LO202601120017', 1, 'A-1-1'),
('SCAN-1768194173724-785', 'LO202601120017', 1, 'A-1-1'),
('SCAN-1768194174047-727', 'LO202601120017', 1, 'A-1-1'),
('SCAN-1768200295940-917', 'LO202601120020', 1, 'A-1-1'),
('SCAN-1768200296237-82', 'LO202601120020', 1, 'A-1-1'),
('SCAN-1768200434576-755', 'LO202601120022', 1, 'A-1-1'),
('SCAN-1768200434923-954', 'LO202601120022', 1, 'A-1-1'),
('SCAN-1768200435323-182', 'LO202601120022', 1, 'A-1-1'),
('SCAN-1768200491548-637', 'LO202601120023', 1, 'A-1-1'),
('SCAN-1768200491846-953', 'LO202601120023', 1, 'A-1-1'),
('SCAN-1768200492379-828', 'LO202601120023', 1, 'A-1-1'),
('SCAN-1768200493078-88', 'LO202601120023', 1, 'A-1-4');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `maKH` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenKH` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diaChi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` tinyint DEFAULT '1',
  PRIMARY KEY (`maKH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`maKH`, `tenKH`, `diaChi`, `sdt`, `email`, `trangThai`) VALUES
('KH00001', 'Tô Nhật Hào', '180 cao lỗ p. chánh hưng tp.hcm', '0813956301', '123@gmail.com', 1),
('KH00002', '1', '1', '1', '123@gmail.com', 1),
('KH00003', 'Nguyễn Đình Trí', 'nhà bè', '0123456789', '123@gmail.com', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lohang`
--

DROP TABLE IF EXISTS `lohang`;
CREATE TABLE IF NOT EXISTS `lohang` (
  `maLo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maPN` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuongNhap` int NOT NULL,
  `ngayNhap` date NOT NULL,
  `hanBaoHanh` date DEFAULT NULL,
  PRIMARY KEY (`maLo`),
  KEY `fk_lo_pn` (`maPN`),
  KEY `fk_lo_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lohang`
--

INSERT INTO `lohang` (`maLo`, `maPN`, `maHH`, `soLuongNhap`, `ngayNhap`, `hanBaoHanh`) VALUES
('LO202601120001', 'PN-260112-001', 'HH001', 1, '2026-01-12', '2026-01-15'),
('LO202601120002', 'PN-260112-001', 'HH002', 3, '2026-01-12', '2026-01-15'),
('LO202601120003', 'PN-260112-001', 'HH003', 4, '2026-01-12', '2026-01-15'),
('LO202601120004', 'PN-260112-002', 'HH001', 1, '2026-01-12', '2026-01-27'),
('LO202601120005', 'PN-260112-002', 'HH002', 1, '2026-01-12', '2026-01-27'),
('LO202601120006', 'PN-260112-002', 'HH003', 3, '2026-01-12', '2026-01-27'),
('LO202601120007', 'PN-260112-003', 'HH001', 1, '2026-01-12', '2026-02-03'),
('LO202601120008', 'PN-260112-003', 'HH002', 3, '2026-01-12', '2026-02-03'),
('LO202601120009', 'PN-260112-003', 'HH003', 2, '2026-01-12', '2026-02-03'),
('LO202601120010', 'PN-260112-004', 'HH001', 1, '2026-01-12', '2026-01-30'),
('LO202601120011', 'PN-260112-004', 'HH003', 3, '2026-01-12', '2026-01-30'),
('LO202601120012', 'PN-260112-005', 'HH001', 1, '2026-01-12', '2026-01-29'),
('LO202601120013', 'PN-260112-005', 'HH002', 3, '2026-01-12', '2026-01-29'),
('LO202601120014', 'PN-260112-005', 'HH003', 2, '2026-01-12', '2026-01-29'),
('LO202601120015', 'PN-260112-006', 'HH001', 1, '2026-01-12', '2026-01-31'),
('LO202601120016', 'PN-260112-006', 'HH003', 3, '2026-01-12', '2026-01-31'),
('LO202601120017', 'PN-260112-007', 'HH003', 3, '2026-01-12', '2026-01-28'),
('LO202601120018', 'PN-260112-008', 'HH002', 4, '2026-01-12', '2026-01-29'),
('LO202601120019', 'PN-260112-009', 'HH002', 3, '2026-01-12', '2027-12-12'),
('LO202601120020', 'PN-260112-009', 'HH003', 2, '2026-01-12', '2027-10-12'),
('LO202601120021', 'PN-260112-010', 'HH002', 5, '2026-01-12', '2026-01-31'),
('LO202601120022', 'PN-260112-010', 'HH003', 3, '2026-01-12', '2026-01-31'),
('LO202601120023', 'PN-260112-011', 'HH001', 4, '2026-01-12', '2026-01-16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lo_hang_vi_tri`
--

DROP TABLE IF EXISTS `lo_hang_vi_tri`;
CREATE TABLE IF NOT EXISTS `lo_hang_vi_tri` (
  `maLVT` int NOT NULL AUTO_INCREMENT,
  `maLo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maViTri` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soLuong` int NOT NULL,
  PRIMARY KEY (`maLVT`),
  KEY `fk_lvt_lo` (`maLo`),
  KEY `fk_lvt_vitri` (`maViTri`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lo_hang_vi_tri`
--

INSERT INTO `lo_hang_vi_tri` (`maLVT`, `maLo`, `maViTri`, `soLuong`) VALUES
(47, 'LO202601120001', 'A-1-1', 1),
(48, 'LO202601120002', 'A-1-1', 3),
(49, 'LO202601120003', 'A-1-1', 1),
(50, 'LO202601120003', 'A-1-2', 3),
(51, 'LO202601120004', 'A-1-3', 1),
(52, 'LO202601120005', 'A-1-2', 1),
(53, 'LO202601120006', 'A-1-2', 1),
(54, 'LO202601120006', 'A-1-3', 2),
(55, 'LO202601120007', 'A-1-1', 1),
(56, 'LO202601120008', 'A-1-1', 3),
(57, 'LO202601120009', 'A-1-1', 2),
(58, 'LO202601120010', 'A-1-1', 1),
(59, 'LO202601120011', 'A-1-1', 3),
(60, 'LO202601120012', 'A-1-1', 1),
(61, 'LO202601120013', 'A-1-1', 3),
(62, 'LO202601120014', 'A-1-1', 2),
(63, 'LO202601120015', 'A-1-1', 1),
(64, 'LO202601120016', 'A-1-1', 3),
(65, 'LO202601120017', 'A-1-1', 3),
(66, 'LO202601120018', 'A-1-1', 4),
(67, 'LO202601120019', 'A-1-1', 3),
(68, 'LO202601120020', 'A-1-1', 2),
(69, 'LO202601120021', 'A-1-1', 5),
(70, 'LO202601120022', 'A-1-1', 3),
(71, 'LO202601120023', 'A-1-1', 3),
(72, 'LO202601120023', 'A-1-4', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

DROP TABLE IF EXISTS `nguoidung`;
CREATE TABLE IF NOT EXISTS `nguoidung` (
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenND` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taiKhoan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matKhau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maVaiTro` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maND`),
  UNIQUE KEY `taiKhoan` (`taiKhoan`),
  KEY `fk_nguoidung_vaitro` (`maVaiTro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`maND`, `tenND`, `email`, `sdt`, `taiKhoan`, `matKhau`, `maVaiTro`) VALUES
('ND01', 'Admin', 'admin@gmail.com', '0987654321', 'admin', '$2y$10$OFuUSY8zdBRIZ5LO1fTglOnanDX8UxCWT5Vko5EEkALk/VakvTyq6', 'VT_ADMIN'),
('ND02', 'Tô Nhật Hào', 'nhathao0910@gmail.com', '0123456789', 'nhathao0910', '$2y$10$OFuUSY8zdBRIZ5LO1fTglOnanDX8UxCWT5Vko5EEkALk/VakvTyq6', 'VT_KHO');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhacungcap`
--

DROP TABLE IF EXISTS `nhacungcap`;
CREATE TABLE IF NOT EXISTS `nhacungcap` (
  `maNCC` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenNCC` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diaChi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` tinyint DEFAULT '1',
  PRIMARY KEY (`maNCC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhacungcap`
--

INSERT INTO `nhacungcap` (`maNCC`, `tenNCC`, `diaChi`, `sdt`, `email`, `trangThai`) VALUES
('NCC002', '1', '1', '1', '123@gmail.com', 1),
('NCC1', 'Cuckoo', '180 cao lỗ p. chánh hưng tp.hcm', '123', 'Cuckoo@group.com', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieubh`
--

DROP TABLE IF EXISTS `phieubh`;
CREATE TABLE IF NOT EXISTS `phieubh` (
  `maBH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngayNhan` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngayTra` datetime DEFAULT NULL,
  `moTaLoi` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` int DEFAULT '0',
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maBH`),
  KEY `fk_bh_serial` (`serial`),
  KEY `fk_bh_nd` (`maND`),
  KEY `fk_bh_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieudathang`
--

DROP TABLE IF EXISTS `phieudathang`;
CREATE TABLE IF NOT EXISTS `phieudathang` (
  `maDH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayDatHang` datetime DEFAULT CURRENT_TIMESTAMP,
  `maNCC` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trangThai` int DEFAULT '0',
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maDH`),
  KEY `fk_pdh_ncc` (`maNCC`),
  KEY `fk_pdh_nd` (`maND`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieudathang`
--

INSERT INTO `phieudathang` (`maDH`, `ngayDatHang`, `maNCC`, `trangThai`, `maND`) VALUES
('PD1768186155', '2026-01-12 02:49:15', 'NCC1', 2, 'ND01'),
('PD1768193548', '2026-01-12 04:52:28', 'NCC002', 0, 'ND01'),
('PD1768194143', '2026-01-12 05:02:23', 'NCC002', 2, 'ND01'),
('PD1768194229', '2026-01-12 05:03:49', 'NCC1', 2, 'ND01'),
('PD1768200131', '2026-01-12 06:42:11', 'NCC1', 2, 'ND01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieukiemke`
--

DROP TABLE IF EXISTS `phieukiemke`;
CREATE TABLE IF NOT EXISTS `phieukiemke` (
  `maKK` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayKK` datetime DEFAULT CURRENT_TIMESTAMP,
  `ghiChu` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maKK`),
  KEY `fk_pkk_nd` (`maND`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieunhap`
--

DROP TABLE IF EXISTS `phieunhap`;
CREATE TABLE IF NOT EXISTS `phieunhap` (
  `maPN` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayNhap` datetime DEFAULT CURRENT_TIMESTAMP,
  `maNCC` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ghiChu` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maPN`),
  KEY `fk_pn_ncc` (`maNCC`),
  KEY `fk_pn_nd` (`maND`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieunhap`
--

INSERT INTO `phieunhap` (`maPN`, `ngayNhap`, `maNCC`, `ghiChu`, `maND`) VALUES
('PN-260112-001', '2026-01-12 10:41:15', 'NCC1', '', 'ND01'),
('PN-260112-002', '2026-01-12 11:08:25', 'NCC1', '', 'ND01'),
('PN-260112-003', '2026-01-12 11:24:31', 'NCC1', '', 'ND01'),
('PN-260112-004', '2026-01-12 11:27:31', 'NCC1', '', 'ND01'),
('PN-260112-005', '2026-01-12 11:59:23', 'NCC1', '', 'ND01'),
('PN-260112-006', '2026-01-12 12:00:55', 'NCC1', 'nhập bổ sung', 'ND01'),
('PN-260112-007', '2026-01-12 12:03:06', 'NCC002', '', 'ND01'),
('PN-260112-008', '2026-01-12 12:08:14', 'NCC1', '', 'ND01'),
('PN-260112-009', '2026-01-12 13:46:09', 'NCC1', 'nhập đợt 1', 'ND01'),
('PN-260112-010', '2026-01-12 13:47:19', 'NCC1', 'nhập bổ sung', 'ND01'),
('PN-260112-011', '2026-01-12 13:48:20', 'NCC1', '', 'ND01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieuxuat`
--

DROP TABLE IF EXISTS `phieuxuat`;
CREATE TABLE IF NOT EXISTS `phieuxuat` (
  `maPX` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayXuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `maKH` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ghiChu` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maNDXuat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maPX`),
  KEY `fk_px_kh` (`maKH`),
  KEY `fk_px_nd` (`maNDXuat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quyen`
--

DROP TABLE IF EXISTS `quyen`;
CREATE TABLE IF NOT EXISTS `quyen` (
  `maQuyen` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenQuyen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moTa` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maQuyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quyen`
--

INSERT INTO `quyen` (`maQuyen`, `tenQuyen`, `moTa`) VALUES
('Q_BAOCAO', 'Xem báo cáo', 'Quyền xem báo cáo tồn kho, doanh thu'),
('Q_HETHONG', 'Quản trị hệ thống', 'Quyền quản lý người dùng và phân quyền'),
('Q_NHAP_KHO', 'Quản lý nhập kho', 'Quyền tạo và duyệt phiếu nhập'),
('Q_QL_HANG', 'Quản lý hàng hóa', 'Quyền thêm, sửa, xóa hàng hóa'),
('Q_XEM_HANG', 'Xem hàng hóa', 'Quyền xem danh sách và chi tiết hàng hóa'),
('Q_XUAT_KHO', 'Quản lý xuất kho', 'Quyền tạo và duyệt phiếu xuất');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quyen_vaitro`
--

DROP TABLE IF EXISTS `quyen_vaitro`;
CREATE TABLE IF NOT EXISTS `quyen_vaitro` (
  `maVaiTro` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maQuyen` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maVaiTro`,`maQuyen`),
  KEY `fk_qvt_quyen` (`maQuyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quyen_vaitro`
--

INSERT INTO `quyen_vaitro` (`maVaiTro`, `maQuyen`) VALUES
('VT_ADMIN', 'Q_BAOCAO'),
('VT_ADMIN', 'Q_HETHONG'),
('VT_ADMIN', 'Q_NHAP_KHO'),
('VT_KHO', 'Q_NHAP_KHO'),
('VT_ADMIN', 'Q_QL_HANG'),
('VT_ADMIN', 'Q_XEM_HANG'),
('VT_KHO', 'Q_XEM_HANG'),
('VT_ADMIN', 'Q_XUAT_KHO'),
('VT_KHO', 'Q_XUAT_KHO');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro`
--

DROP TABLE IF EXISTS `vaitro`;
CREATE TABLE IF NOT EXISTS `vaitro` (
  `maVaiTro` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenVaiTro` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moTa` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maVaiTro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro`
--

INSERT INTO `vaitro` (`maVaiTro`, `tenVaiTro`, `moTa`) VALUES
('VT_ADMIN', 'Quản trị viên', 'Có toàn quyền truy cập hệ thống'),
('VT_KHO', 'Nhân viên kho', 'Chỉ có quyền nhập xuất và xem hàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vitri`
--

DROP TABLE IF EXISTS `vitri`;
CREATE TABLE IF NOT EXISTS `vitri` (
  `maViTri` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ke` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `o` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` enum('TRONG','DAY') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'TRONG',
  `sucChuaToiDa` int DEFAULT '100',
  PRIMARY KEY (`maViTri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vitri`
--

INSERT INTO `vitri` (`maViTri`, `day`, `ke`, `o`, `trangThai`, `sucChuaToiDa`) VALUES
('A-1-1', 'A', '1', '1', 'TRONG', 1000),
('A-1-2', 'A', '1', '2', 'TRONG', 100),
('A-1-3', 'A', '1', '3', 'TRONG', 100),
('A-1-4', 'A', '1', '4', 'TRONG', 100),
('A-1-5', 'A', '1', '5', 'TRONG', 100);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ct_phieudathang`
--
ALTER TABLE `ct_phieudathang`
  ADD CONSTRAINT `fk_ctpdh_dh` FOREIGN KEY (`maDH`) REFERENCES `phieudathang` (`maDH`),
  ADD CONSTRAINT `fk_ctpdh_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`);

--
-- Các ràng buộc cho bảng `ct_phieukiemke`
--
ALTER TABLE `ct_phieukiemke`
  ADD CONSTRAINT `fk_ctkk_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`),
  ADD CONSTRAINT `fk_ctkk_kk` FOREIGN KEY (`maKK`) REFERENCES `phieukiemke` (`maKK`);

--
-- Các ràng buộc cho bảng `ct_phieunhap`
--
ALTER TABLE `ct_phieunhap`
  ADD CONSTRAINT `fk_ctpn_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`),
  ADD CONSTRAINT `fk_ctpn_pn` FOREIGN KEY (`maPN`) REFERENCES `phieunhap` (`maPN`);

--
-- Các ràng buộc cho bảng `ct_phieuxuat`
--
ALTER TABLE `ct_phieuxuat`
  ADD CONSTRAINT `fk_ctpx_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`),
  ADD CONSTRAINT `fk_ctpx_px` FOREIGN KEY (`maPX`) REFERENCES `phieuxuat` (`maPX`);

--
-- Các ràng buộc cho bảng `hanghoa`
--
ALTER TABLE `hanghoa`
  ADD CONSTRAINT `fk_hanghoa_danhmuc` FOREIGN KEY (`maDanhMuc`) REFERENCES `danhmuc` (`maDanhMuc`),
  ADD CONSTRAINT `fk_hanghoa_dvt` FOREIGN KEY (`maDVT`) REFERENCES `donvitinh` (`maDVT`);

--
-- Các ràng buộc cho bảng `hanghoa_serial`
--
ALTER TABLE `hanghoa_serial`
  ADD CONSTRAINT `fk_hhs_lo` FOREIGN KEY (`maLo`) REFERENCES `lohang` (`maLo`),
  ADD CONSTRAINT `fk_hhs_vitri` FOREIGN KEY (`maViTri`) REFERENCES `vitri` (`maViTri`);

--
-- Các ràng buộc cho bảng `lohang`
--
ALTER TABLE `lohang`
  ADD CONSTRAINT `fk_lo_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`),
  ADD CONSTRAINT `fk_lo_pn` FOREIGN KEY (`maPN`) REFERENCES `phieunhap` (`maPN`);

--
-- Các ràng buộc cho bảng `lo_hang_vi_tri`
--
ALTER TABLE `lo_hang_vi_tri`
  ADD CONSTRAINT `fk_lvt_lo` FOREIGN KEY (`maLo`) REFERENCES `lohang` (`maLo`),
  ADD CONSTRAINT `fk_lvt_vitri` FOREIGN KEY (`maViTri`) REFERENCES `vitri` (`maViTri`);

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `fk_nguoidung_vaitro` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);

--
-- Các ràng buộc cho bảng `phieubh`
--
ALTER TABLE `phieubh`
  ADD CONSTRAINT `fk_bh_hh` FOREIGN KEY (`maHH`) REFERENCES `hanghoa` (`maHH`),
  ADD CONSTRAINT `fk_bh_nd` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `phieudathang`
--
ALTER TABLE `phieudathang`
  ADD CONSTRAINT `fk_pdh_ncc` FOREIGN KEY (`maNCC`) REFERENCES `nhacungcap` (`maNCC`),
  ADD CONSTRAINT `fk_pdh_nd` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `phieukiemke`
--
ALTER TABLE `phieukiemke`
  ADD CONSTRAINT `fk_pkk_nd` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_pn_ncc` FOREIGN KEY (`maNCC`) REFERENCES `nhacungcap` (`maNCC`),
  ADD CONSTRAINT `fk_pn_nd` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `phieuxuat`
--
ALTER TABLE `phieuxuat`
  ADD CONSTRAINT `fk_px_kh` FOREIGN KEY (`maKH`) REFERENCES `khachhang` (`maKH`),
  ADD CONSTRAINT `fk_px_nd` FOREIGN KEY (`maNDXuat`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `quyen_vaitro`
--
ALTER TABLE `quyen_vaitro`
  ADD CONSTRAINT `fk_qvt_quyen` FOREIGN KEY (`maQuyen`) REFERENCES `quyen` (`maQuyen`),
  ADD CONSTRAINT `fk_qvt_vaitro` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
