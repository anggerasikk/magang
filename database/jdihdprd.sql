-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 22 Jul 2025 pada 07.18
-- Versi Server: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jdihdprd`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasi`
--

CREATE TABLE `informasi` (
  `idData` int(11) NOT NULL,
  `tanggal_tayang` varchar(30) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `sumber` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `deskripsiinformasi` varchar(4000) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `informasi`
--

INSERT INTO `informasi` (`idData`, `tanggal_tayang`, `jenis`, `judul`, `sumber`, `status`, `deskripsiinformasi`, `image_name`, `image_path`, `uploaded_at`) VALUES
(20, '2025-06-24', '', '', '', '', '', 'img_685a543e8456b.png', 'uploads/images/img_685a543e8456b.png', '2025-06-24 14:31:10'),
(21, '2025-06-06', 'Keputusan_DPRD', 'peraturan inovasi', '', '', '', 'img_685a55dcbea6d.png', 'uploads/images/img_685a55dcbea6d.png', '2025-06-24 14:38:04'),
(22, '2025-06-24', 'Keputusan_DPRD', 'oke banget', '', '', '', 'img_685a569166b79.png', 'uploads/images/img_685a569166b79.png', '2025-06-24 14:41:05'),
(23, '2025-07-22', 'Keputusan_Sekretaris_DPRD', ' industri', '', '', '', 'img_685a57901b930.png', 'uploads/images/img_685a57901b930.png', '2025-06-24 14:45:20'),
(24, '2025-06-25', 'Keputusan_DPRD', 'ertert', '', '', '', 'img_685b78eea68f7.png', 'uploads/images/img_685b78eea68f7.png', '2025-06-25 11:19:58'),
(25, '2025-07-22', 'Keputusan_Sekretaris_DPRD', 'cvbcb dfghdf dfhdf dgh', 'dfgdfg', 'Berlaku', '', 'img_685b926cd7ddf.png', 'uploads/images/img_685b926cd7ddf.png', '2025-06-25 13:08:44'),
(26, '2022-01-08', 'Peraturan_DPRD', 'peraturan dprd tentang pelaksnaan pokok pikran DPRD', 'surakarta', 'Berlaku', 'Pokok Pikiran DPRD', 'img_685ba25f55d50.png', 'uploads/images/img_685ba25f55d50.png', '2025-06-25 14:16:47'),
(27, '2025-06-26', 'Keputusan_Pimpinan_DPRD', ' okwee e e ee e ', 'sdfsdf', 'Berlaku', 'werwer', 'img_685cca8a787f0.png', 'uploads/images/img_685cca8a787f0.png', '2025-06-26 11:20:26'),
(29, '2025-06-26', 'Keputusan_DPRD', 'dfgdf', '', 'Berlaku', 'dfg', 'img_685cedd22ab04.png', 'uploads/images/img_685cedd22ab04.png', '2025-06-26 13:50:58'),
(32, '2025-07-22', 'Keputusan_Sekretaris_DPRD', 'bagus banget', 'solo', 'Berlaku', 'bagus', 'img_687f10ddbb75b.png', 'uploads/images/img_687f10ddbb75b.png', '2025-07-22 11:17:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `phpmu_user`
--

CREATE TABLE `phpmu_user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `alamat_email` varchar(150) NOT NULL,
  `no_telpon` varchar(15) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'biasa',
  `status` enum('Y','N') NOT NULL DEFAULT 'N',
  `waktu_daftar` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `phpmu_user`
--

INSERT INTO `phpmu_user` (`id_user`, `username`, `password`, `nama_lengkap`, `alamat_email`, `no_telpon`, `alamat_lengkap`, `level`, `status`, `waktu_daftar`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin@gmail.com', '0812345678', 'Jl raya gentan baki sukoharjo', 'user_admin', 'Y', '2015-06-03 00:00:00'),
(3, 'dewi', 'ed1d859c50262701d92e5cbf39652792', 'Dewi Safitri', 'dewi.safitri@gmail.com', '087305450112', 'Linggar Jati, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-03 00:00:00'),
(4, 'udin', '6bec9c852847242e384a4d5ac0962ba0', 'Udin Sedunia', 'udin.sedunia@gmail.com', '081267771390', 'Ulak Karang, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-04 08:03:18'),
(5, 'amel', 'da0e22de18e3fbe1e96bdc882b912ea4', 'Amel Amelia', 'amel.amelia@gmail.com', '0812345678', 'Jl raya solo jawa tengah', 'user_input', 'Y', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produkdprd`
--

CREATE TABLE `produkdprd` (
  `id_produk` int(11) NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `file` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `uploads`
--

CREATE TABLE `uploads` (
  `idData` int(11) NOT NULL,
  `tahun_pengundangan` varchar(20) NOT NULL,
  `tanggal_pengundangan` varchar(30) NOT NULL,
  `tanggal_penetapan` varchar(30) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `noPeraturan` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `singkatanJenis` varchar(150) NOT NULL,
  `tempatTerbit` varchar(100) NOT NULL,
  `sumber` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `bahasa` varchar(100) NOT NULL,
  `fileDownload` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `urlDownload` varchar(300) NOT NULL,
  `operasi` varchar(50) NOT NULL,
  `display` varchar(30) NOT NULL,
  `noPanggil` varchar(80) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `subjek` varchar(100) NOT NULL,
  `isbn` varchar(100) NOT NULL,
  `bidangHukum` varchar(100) NOT NULL,
  `nomorIndukBuku` varchar(100) NOT NULL,
  `urlDetailPeraturan` varchar(300) NOT NULL,
  `teuBadan` varchar(100) NOT NULL,
  `deskripsiFisik` varchar(100) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `uploads`
--

INSERT INTO `uploads` (`idData`, `tahun_pengundangan`, `tanggal_pengundangan`, `tanggal_penetapan`, `jenis`, `noPeraturan`, `judul`, `singkatanJenis`, `tempatTerbit`, `sumber`, `status`, `bahasa`, `fileDownload`, `pdf_path`, `urlDownload`, `operasi`, `display`, `noPanggil`, `penerbit`, `subjek`, `isbn`, `bidangHukum`, `nomorIndukBuku`, `urlDetailPeraturan`, `teuBadan`, `deskripsiFisik`, `image_name`, `image_path`, `uploaded_at`) VALUES
(20, 'gggg', '2025-06-24', '2025-06-24', '', '', '', '', '', '', '', '', 'pdf_685a543e84564.pdf', 'uploads/pdf/pdf_685a543e84564.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a543e8456b.png', 'uploads/images/img_685a543e8456b.png', '2025-06-24 14:31:10'),
(21, '2026', '2025-06-07', '2025-06-06', 'Keputusan_DPRD', '2312 ', 'peraturan inovasi', '', '', '', '', '', 'pdf_685a55dcbea63.pdf', 'uploads/pdf/pdf_685a55dcbea63.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a55dcbea6d.png', 'uploads/images/img_685a55dcbea6d.png', '2025-06-24 14:38:04'),
(22, 'sdadas', '2025-06-24', '2025-06-24', 'Keputusan_DPRD', 'asda', 'oke banget', '', '', '', '', '', 'pdf_685a569166b71.pdf', 'uploads/pdf/pdf_685a569166b71.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a569166b79.png', 'uploads/images/img_685a569166b79.png', '2025-06-24 14:41:05'),
(23, '54353', '2025-06-06', '2025-07-22', 'Keputusan_Sekretaris_DPRD', '45345100000', ' industri', '', '', '', '', '', 'pdf_685a57901b928.pdf', 'uploads/pdf/pdf_685a57901b928.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a57901b930.png', 'uploads/images/img_685a57901b930.png', '2025-06-24 14:45:20'),
(24, 'xcdg', '2025-06-25', '2025-06-25', 'Keputusan_DPRD', 'tert', 'ertert', 'dfvhc', 'cgbcbcvbcb', '', '', '', 'pdf_685b78eea68e7.pdf', 'uploads/pdf/pdf_685b78eea68e7.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685b78eea68f7.png', 'uploads/images/img_685b78eea68f7.png', '2025-06-25 11:19:58'),
(25, 'gdg', '2025-06-07', '2025-07-22', 'Keputusan_Sekretaris_DPRD', '53455555555', 'cvbcb dfghdf dfhdf dgh', ' drdfgdg', 'dgdgdg', 'dfgdfg', 'Berlaku', 'Indonesia', 'pdf_685b926cd7dd9.pdf', 'uploads/pdf/pdf_685b926cd7dd9.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685b926cd7ddf.png', 'uploads/images/img_685b926cd7ddf.png', '2025-06-25 13:08:44'),
(26, '2025', '2024-06-07', '2022-01-08', 'Peraturan_DPRD', '223/d.r/20/2025', 'peraturan dprd tentang pelaksnaan pokok pikran DPRD', 'perDPRD', 'surakarta', 'surakarta', 'Berlaku', 'Indonesia', 'pdf_685ba25f55b01.pdf', 'uploads/pdf/pdf_685ba25f55b01.pdf', '', '-', '-', '23.perdprd', 'DPRD kota Surakarta', '-', '231312', 'pemerintahan', '424234', '2w1eqwe', 'DPRD Kota Surakarta', 'Pokok Pikiran DPRD', 'img_685ba25f55d50.png', 'uploads/images/img_685ba25f55d50.png', '2025-06-25 14:16:47'),
(27, '2022', '2025-06-26', '2025-06-26', 'Keputusan_Pimpinan_DPRD', 'qweqe', ' okwee e e ee e ', 'dcasdfa', 'sdfsdf', 'sdfsdf', 'Berlaku', 'Indonesia', 'pdf_685cca8a787e7.pdf', 'uploads/pdf/pdf_685cca8a787e7.pdf', '', 'qrwer', 'werwe', 'werwer', 'werw', 'werw', 'werwe', 'werwe', 'werw', 'wer', 'wer', 'werwer', 'img_685cca8a787f0.png', 'uploads/images/img_685cca8a787f0.png', '2025-06-26 11:20:26'),
(29, 'sdfgdg', '2025-06-26', '2025-06-26', 'Keputusan_DPRD', 'dfgdf', 'dfgdf', 'dfg', 'fg', '', 'Berlaku', 'Indonesia', '2020kepimdprd33316632.pdf.pdf', 'uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'http://localhost/jdihcilacap/wb_admin/uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'dfgdfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dgf', 'dfg', 'dfg', 'img_685cedd22ab04.png', 'uploads/images/img_685cedd22ab04.png', '2025-06-26 13:50:58'),
(32, '2025', '2025-07-22', '2025-07-22', 'Keputusan_Sekretaris_DPRD', '65/66/555', 'bagus banget', 'bg', 'solo', 'solo', 'Berlaku', 'Indonesia', '2020kepimdprd33316632.pdf.pdf', 'uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'http://localhost/jdihcilacap/wb_admin/uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'ok', 'ok', '788', 'solo', 'solo', 'solo', 'solo', '5656', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', 'solo', 'bagus', 'img_687f10ddbb75b.png', 'uploads/images/img_687f10ddbb75b.png', '2025-07-22 11:17:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`idData`);

--
-- Indexes for table `phpmu_user`
--
ALTER TABLE `phpmu_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `produkdprd`
--
ALTER TABLE `produkdprd`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`idData`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `idData` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `phpmu_user`
--
ALTER TABLE `phpmu_user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `produkdprd`
--
ALTER TABLE `produkdprd`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `idData` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
