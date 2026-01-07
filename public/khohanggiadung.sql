-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th1 07, 2026 lúc 12:05 PM
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
  `donGia` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`maDH`,`maHH`),
  KEY `fk_ctpdh_hh` (`maHH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_phieudathang`
--

INSERT INTO `ct_phieudathang` (`maDH`, `maHH`, `soLuong`, `donGia`) VALUES
('PD1767511942', 'HH001', 20, 10640000.00);

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
('DM02', 'Nồi cơm điện');

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
  `maNCC` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thuongHieu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moTa` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maDanhMuc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loaiHang` enum('LO','SERIAL') COLLATE utf8mb4_unicode_ci DEFAULT 'LO',
  `heSoChiemCho` int DEFAULT '1',
  PRIMARY KEY (`maHH`),
  KEY `fk_hanghoa_dvt` (`maDVT`),
  KEY `fk_hanghoa_ncc` (`maNCC`),
  KEY `fk_hanghoa_danhmuc` (`maDanhMuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hanghoa`
--

INSERT INTO `hanghoa` (`maHH`, `tenHH`, `maDVT`, `maNCC`, `thuongHieu`, `model`, `moTa`, `maDanhMuc`, `loaiHang`, `heSoChiemCho`) VALUES
('1', '1', 'DVT01', 'NCC1', '1', '1', '1', 'DM01', 'LO', 1),
('HH001', 'Tủ lạnh aqua inverter', 'DVT01', 'NCC1', 'AQUA', 'AQR-B390MA', 'Ngăn đá dưới - 2 cánh\r\nDung tích tổng: 350 lít\r\nDung tích sử dụng: 324 lít - 3 - 4 người\r\nDung tích ngăn đá: 91 lít\r\nDung tích ngăn lạnh: 199 lít\r\nDung tích ngăn chuyển đổi: 34 lít\r\nChất liệu cửa tủ lạnh: Mặt thép\r\nChất liệu khay ngăn lạnh: Kính chịu lực\r\nChất liệu ống dẫn gas, dàn lạnh: Ống dẫn gas', 'DM01', 'SERIAL', 1),
('HH003', 'Nồi cơm điện toshiba', 'DVT01', 'NCC1', 'toshiba', 'rc-10jfmvn', '', 'DM02', 'SERIAL', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('NCC1', 'Cuckoo', '180 cao lỗ p. chánh hưng tp.hcm', '123', 'Cuckoo@group.com', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieubh`
--

DROP TABLE IF EXISTS `phieubh`;
CREATE TABLE IF NOT EXISTS `phieubh` (
  `maBH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayNhan` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngayTra` datetime DEFAULT NULL,
  `moTaLoi` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangThai` int DEFAULT '0',
  `maND` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maBH`),
  KEY `fk_bh_serial` (`serial`),
  KEY `fk_bh_nd` (`maND`)
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
('PD1767511942', '2026-01-04 07:32:22', 'NCC1', 0, 'ND01');

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
-- Cấu trúc bảng cho bảng `phieuxuat_serial`
--

DROP TABLE IF EXISTS `phieuxuat_serial`;
CREATE TABLE IF NOT EXISTS `phieuxuat_serial` (
  `maPX` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`maPX`,`serial`),
  KEY `fk_pxs_serial` (`serial`)
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
  `trangThai` enum('TRONG','DAY') COLLATE utf8mb4_unicode_ci DEFAULT 'TRONG' COMMENT 'Trạng thái kệ',
  `sucChuaToiDa` int DEFAULT '100' COMMENT 'Số lượng tối đa có thể chứa',
  PRIMARY KEY (`maViTri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vitri`
--

INSERT INTO `vitri` (`maViTri`, `day`, `ke`, `o`, `trangThai`, `sucChuaToiDa`) VALUES
('A-1-1', 'A', '1', '1', 'TRONG', 100);

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
  ADD CONSTRAINT `fk_hanghoa_dvt` FOREIGN KEY (`maDVT`) REFERENCES `donvitinh` (`maDVT`),
  ADD CONSTRAINT `fk_hanghoa_ncc` FOREIGN KEY (`maNCC`) REFERENCES `nhacungcap` (`maNCC`);

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
  ADD CONSTRAINT `fk_bh_nd` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `fk_bh_serial` FOREIGN KEY (`serial`) REFERENCES `hanghoa_serial` (`serial`);

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
-- Các ràng buộc cho bảng `phieuxuat_serial`
--
ALTER TABLE `phieuxuat_serial`
  ADD CONSTRAINT `fk_pxs_px` FOREIGN KEY (`maPX`) REFERENCES `phieuxuat` (`maPX`),
  ADD CONSTRAINT `fk_pxs_serial` FOREIGN KEY (`serial`) REFERENCES `hanghoa_serial` (`serial`);

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
