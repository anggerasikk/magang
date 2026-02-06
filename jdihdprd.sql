-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2026 at 06:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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
-- Table structure for table `artikel_hukum`
--

CREATE TABLE `artikel_hukum` (
  `id_artikel` int(11) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `jenis_artikel` enum('Artikel Jurnal','Artikel Konferensi','Artikel Review','Artikel Penelitian','Artikel Populer','Kertas Kerja') NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `penulis` varchar(200) NOT NULL,
  `jurnal` varchar(200) DEFAULT NULL,
  `issn` varchar(50) DEFAULT NULL,
  `volume` varchar(20) DEFAULT NULL,
  `issue` varchar(20) DEFAULT NULL,
  `halaman` varchar(50) DEFAULT NULL,
  `bidang_hukum` varchar(100) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `abstrak` text NOT NULL,
  `status` enum('Published','Unpublished','Draft','Under Review') DEFAULT 'Draft',
  `bahasa` enum('Indonesia','Inggris','Arab','Lainnya') DEFAULT 'Indonesia',
  `file_pdf` varchar(500) DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `id_user` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel_hukum`
--

INSERT INTO `artikel_hukum` (`id_artikel`, `judul`, `jenis_artikel`, `tanggal_terbit`, `tahun_terbit`, `penulis`, `jurnal`, `issn`, `volume`, `issue`, `halaman`, `bidang_hukum`, `keywords`, `abstrak`, `status`, `bahasa`, `file_pdf`, `thumbnail`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'Analisis Hukum Pidana Terhadap Tindak Pidana Korupsi di Indonesia', 'Artikel Jurnal', '2023-05-15', '2023', 'Dr. Budi Santoso, S.H., M.H.', 'Jurnal Hukum Pidana', '1234-5678', '12', '3', '45-60', 'Hukum Pidana', 'korupsi, hukum pidana, tindak pidana', 'Artikel ini menganalisis perkembangan hukum pidana terkait tindak pidana korupsi di Indonesia dalam dekade terakhir. Fokus penelitian adalah pada efektivitas penerapan undang-undang anti korupsi dan kendala dalam penegakan hukum.', 'Published', 'Indonesia', NULL, NULL, 'admin', '2026-01-19 15:26:14', NULL),
(2, 'Perlindungan Hukum Terhadap Konsumen dalam Transaksi E-Commerce', 'Artikel Penelitian', '2023-08-20', '2023', 'Siti Aminah, S.H., LL.M.', 'Jurnal Hukum Bisnis', '8765-4321', '8', '2', '120-135', 'Hukum Bisnis', 'e-commerce, konsumen, perlindungan hukum', 'Penelitian ini mengkaji perlindungan hukum bagi konsumen dalam transaksi elektronik di platform e-commerce. Hasil penelitian menunjukkan masih adanya celah hukum yang perlu diperbaiki untuk memberikan perlindungan maksimal.', 'Published', 'Indonesia', NULL, NULL, 'admin', '2026-01-19 15:26:14', NULL),
(3, 'Implikasi Hukum Internasional dalam Penyelesaian Sengketa Perbatasan', 'Artikel Konferensi', '2023-10-10', '2023', 'Prof. Dr. Ahmad Wijaya, S.H., M.A.', 'Prosiding Konferensi Hukum Internasional', NULL, NULL, NULL, '78-92', 'Hukum Internasional', 'hukum internasional, sengketa, perbatasan', 'Artikel ini membahas implikasi hukum internasional dalam penyelesaian sengketa perbatasan antar negara dengan studi kasus di wilayah Asia Tenggara.', 'Under Review', 'Indonesia', NULL, NULL, 'admin', '2026-01-19 15:26:14', NULL),
(4, 'Tinjauan Yuridis Terhadap Pelaksanaan Restorative Justice dalam Sistem Peradilan Pidana', 'Artikel Review', '2023-03-25', '2023', 'Dewi Kartika, S.H., M.Hum.', 'Jurnal Hukum dan Masyarakat', '3456-7890', '15', '1', '30-45', 'Hukum Pidana', 'restorative justice, peradilan pidana, keadilan', 'Review ini mengkaji konsep restorative justice dalam sistem peradilan pidana dan implementasinya di Indonesia. Ditemukan bahwa penerapan restorative justice masih belum optimal.', 'Published', 'Indonesia', NULL, NULL, 'admin', '2026-01-19 15:26:14', NULL),
(5, 'artikel 1', 'Artikel Konferensi', '2026-01-19', '2026', 'Badan Legislasi', '', '', '', '', '', 'Hukum Administrasi Negara', '', '\"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"', 'Published', 'Indonesia', 'uploads/artikel_hukum/pdf/artikel_1768811298_Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'uploads/artikel_hukum/images/thumbnail_1768811298_ChatGPT Image Jan 15, 2026, 04_20_08 PM.png', '1', '2026-01-19 15:28:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galeri_foto`
--

CREATE TABLE `galeri_foto` (
  `id` int(11) NOT NULL,
  `tipe` enum('album','foto') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `url_gambar` varchar(500) NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_cover` tinyint(1) DEFAULT 0,
  `tanggal_dibuat` timestamp NULL DEFAULT current_timestamp(),
  `tanggal_diperbarui` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri_foto`
--

INSERT INTO `galeri_foto` (`id`, `tipe`, `parent_id`, `judul`, `deskripsi`, `url_gambar`, `urutan`, `is_cover`, `tanggal_dibuat`, `tanggal_diperbarui`) VALUES
(1, 'album', NULL, 'gambar 1', 'gambar kegiatan 1', 'uploads/galeri/1768893852_ChatGPT Image Jan 15, 2026, 04_20_08 PM.png', 0, 1, '2026-01-20 07:24:12', '2026-01-20 07:24:12'),
(2, 'foto', 1, 'pembukaan', 'pembukaan', 'uploads/galeri/1768893911_keputusan_dprd.png', 0, 0, '2026-01-20 07:25:11', '2026-01-20 07:25:11'),
(3, 'album', NULL, 'coba', 'coba semoga bisa tampil okey', 'uploads/galeri/1768970481_pexels-amir-462394.jpg', 0, 1, '2026-01-21 04:41:21', '2026-01-21 04:41:21'),
(4, 'foto', 3, 'ini coba 1', 'ini coba 1', 'uploads/galeri/1768970481_0_pexels-liza-lova-1414718-2739295.jpg', 1, 0, '2026-01-21 04:41:21', '2026-01-21 04:41:21'),
(5, 'foto', 3, 'ini coba 2', 'ya dicoba 2', 'uploads/galeri/1768970481_1_pexels-pixabay-162500.jpg', 2, 0, '2026-01-21 04:41:21', '2026-01-21 04:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `grafis`
--

CREATE TABLE `grafis` (
  `id` int(11) NOT NULL,
  `tipe` enum('videografis','infografis') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `sumber` varchar(500) NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT current_timestamp(),
  `tanggal_diperbarui` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monografi`
--

CREATE TABLE `monografi` (
  `id_monografi` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jenis_monografi` varchar(100) NOT NULL,
  `tanggal_pembuatan` date NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `penulis` varchar(200) DEFAULT NULL,
  `penerbit` varchar(200) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `bidang_hukum` varchar(100) DEFAULT NULL,
  `jumlah_halaman` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Berlaku','Tidak Berlaku','Draft') DEFAULT 'Berlaku',
  `bahasa` varchar(50) DEFAULT 'Indonesia',
  `file_pdf` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monografi`
--

INSERT INTO `monografi` (`id_monografi`, `judul`, `jenis_monografi`, `tanggal_pembuatan`, `tahun_terbit`, `penulis`, `penerbit`, `isbn`, `bidang_hukum`, `jumlah_halaman`, `deskripsi`, `status`, `bahasa`, `file_pdf`, `thumbnail`, `created_at`, `updated_at`, `id_user`) VALUES
(1, 'Pokok Pikiran Pembangunan Daerah 2024', 'Pokok Pikiran', '2024-01-15', '2024', 'Tim DPRD', 'DPRD Kabupaten', NULL, 'Pemerintahan Daerah', NULL, 'Dokumen pokok pikiran DPRD tahun 2024', 'Berlaku', 'Indonesia', NULL, NULL, '2026-01-19 04:38:12', NULL, NULL),
(2, 'Kajian Raperda APBD 2025', 'Kajian Raperda', '2024-06-20', '2024', 'Badan Legislasi', 'DPRD Kabupaten', '1234567890', 'Keuangan Daerah', 0, 'Kajian akademik Raperda APBD tahun 2025', 'Berlaku', 'Indonesia', NULL, NULL, '2026-01-19 04:38:12', '2026-01-19 06:24:26', NULL),
(3, 'Naskah Akademik Perda Pendidikan', 'Naskah Akademik', '2023-11-10', '2023', 'Tim Ahli', 'DPRD Kabupaten', NULL, 'Pendidikan', NULL, 'Naskah akademik peraturan daerah tentang pendidikan', 'Berlaku', 'Indonesia', NULL, NULL, '2026-01-19 04:38:12', NULL, NULL),
(5, 'Risalah Rapat Paripurna ke-15', 'Risalah', '2024-03-05', '2024', 'Sekretariat DPRD', 'DPRD Kabupaten', NULL, 'Tata Tertib', NULL, 'Risalah rapat paripurna ke-15 tahun 2024', 'Berlaku', 'Indonesia', NULL, NULL, '2026-01-19 04:38:12', NULL, NULL),
(6, 'Memahami untuk Membasmi : Panduan untuk Memahami Tindak Pidana Korupsi', 'Himpunan Peraturan', '2026-01-19', '2026', '', '', '', '', 0, '', 'Berlaku', 'Indonesia', 'uploads/monografi/pdf/monografi_1768803915_Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'uploads/monografi/images/thumbnail_1768803915_ChatGPT Image Jan 15, 2026, 04_20_08 PM.png', '2026-01-19 06:25:15', NULL, '1'),
(7, 'pokok pikiran 1', 'Pokok Pikiran', '2026-01-19', '2026', '', '', '', '', 0, '', 'Berlaku', 'Indonesia', 'uploads/monografi/pdf/monografi_1768806431_Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'uploads/monografi/images/thumbnail_1768806431_ChatGPT Image Jan 15, 2026, 04_20_08 PM.png', '2026-01-19 07:07:11', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `phpmu_user`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `phpmu_user`
--

INSERT INTO `phpmu_user` (`id_user`, `username`, `password`, `nama_lengkap`, `alamat_email`, `no_telpon`, `alamat_lengkap`, `level`, `status`, `waktu_daftar`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin@gmail.com', '0812345678', 'Jl raya gentan baki sukoharjo', 'user_admin', 'Y', '2015-06-03 00:00:00'),
(3, 'dewi', 'ed1d859c50262701d92e5cbf39652792', 'Dewi Safitri', 'dewi.safitri@gmail.com', '087305450112', 'Linggar Jati, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-03 00:00:00'),
(4, 'udin', '6bec9c852847242e384a4d5ac0962ba0', 'Udin Sedunia', 'udin.sedunia@gmail.com', '081267771390', 'Ulak Karang, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-04 08:03:18'),
(5, 'amel', 'da0e22de18e3fbe1e96bdc882b912ea4', 'Amel Amelia', 'amel.amelia@gmail.com', '0812345678', 'Jl raya solo jawa tengah', 'user_input', 'Y', '0000-00-00 00:00:00'),
(7, 'teta', 'd9c4b80884a6623851c5b6cd6a0a001a', 'teta', 'teta@gmail.com', '00000000000', '', 'user_input', 'Y', '2026-01-07 11:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `produkdprd`
--

CREATE TABLE `produkdprd` (
  `id_produk` int(11) NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `file` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `idData` int(11) NOT NULL,
  `jenis_dokumen` varchar(50) DEFAULT 'produk_hukum',
  `tahun_pengundangan` varchar(20) NOT NULL,
  `tanggal_pengundangan` varchar(30) NOT NULL,
  `tanggal_penetapan` varchar(30) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `jenis_monografi` varchar(100) DEFAULT NULL,
  `noPeraturan` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`idData`, `jenis_dokumen`, `tahun_pengundangan`, `tanggal_pengundangan`, `tanggal_penetapan`, `jenis`, `jenis_monografi`, `noPeraturan`, `judul`, `deskripsi`, `singkatanJenis`, `tempatTerbit`, `sumber`, `status`, `bahasa`, `fileDownload`, `pdf_path`, `urlDownload`, `operasi`, `display`, `noPanggil`, `penerbit`, `subjek`, `isbn`, `bidangHukum`, `nomorIndukBuku`, `urlDetailPeraturan`, `teuBadan`, `deskripsiFisik`, `image_name`, `image_path`, `uploaded_at`) VALUES
(20, 'produk_hukum', 'gggg', '2025-06-24', '2025-06-24', '', NULL, '', '', NULL, '', '', '', '', '', 'pdf_685a543e84564.pdf', 'uploads/pdf/pdf_685a543e84564.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a543e8456b.png', 'uploads/images/img_685a543e8456b.png', '2025-06-24 14:31:10'),
(21, 'produk_hukum', '2026', '2025-06-07', '2025-06-06', 'Keputusan_DPRD', NULL, '2312 ', 'peraturan inovasi', NULL, '', '', '', '', '', 'pdf_685a55dcbea63.pdf', 'uploads/pdf/pdf_685a55dcbea63.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a55dcbea6d.png', 'uploads/images/img_685a55dcbea6d.png', '2025-06-24 14:38:04'),
(22, 'produk_hukum', '2026', '2025-06-24', '2025-06-24', 'Keputusan_DPRD', NULL, '2026', 'oke banget', NULL, '', '', '', '', '', 'pdf_685a569166b71.pdf', 'uploads/pdf/pdf_685a569166b71.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a569166b79.png', 'uploads/images/img_685a569166b79.png', '2025-06-24 14:41:05'),
(23, 'produk_hukum', '54353', '2025-06-06', '2025-07-22', 'Keputusan_Sekretaris_DPRD', NULL, '45345100000', ' industri', NULL, '', '', '', '', '', 'pdf_685a57901b928.pdf', 'uploads/pdf/pdf_685a57901b928.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685a57901b930.png', 'uploads/images/img_685a57901b930.png', '2025-06-24 14:45:20'),
(24, 'produk_hukum', '2024', '2025-06-25', '2025-06-25', 'Keputusan_DPRD', NULL, '2000', 'ertert', NULL, 'dfvhc', 'cgbcbcvbcb', '', '', '', 'pdf_685b78eea68e7.pdf', 'uploads/pdf/pdf_685b78eea68e7.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685b78eea68f7.png', 'uploads/images/img_685b78eea68f7.png', '2025-06-25 11:19:58'),
(25, 'produk_hukum', 'gdg', '2025-06-07', '2025-07-22', 'Keputusan_Sekretaris_DPRD', NULL, '53455555555', 'cvbcb dfghdf dfhdf dgh', NULL, ' drdfgdg', 'dgdgdg', 'dfgdfg', 'Berlaku', 'Indonesia', 'pdf_685b926cd7dd9.pdf', 'uploads/pdf/pdf_685b926cd7dd9.pdf', '', '', '', '', '', '', '', '', '', '', '', '', 'img_685b926cd7ddf.png', 'uploads/images/img_685b926cd7ddf.png', '2025-06-25 13:08:44'),
(26, 'produk_hukum', '2025', '2024-06-07', '2022-01-08', 'Peraturan_DPRD', NULL, '223/d.r/20/2025', 'peraturan dprd tentang pelaksnaan pokok pikran DPRD', NULL, 'perDPRD', 'surakarta', 'surakarta', 'Berlaku', 'Indonesia', 'pdf_685ba25f55b01.pdf', 'uploads/pdf/pdf_685ba25f55b01.pdf', '', '-', '-', '23.perdprd', 'DPRD kota Surakarta', '-', '231312', 'pemerintahan', '424234', '2w1eqwe', 'DPRD Kota Surakarta', 'Pokok Pikiran DPRD', 'img_685ba25f55d50.png', 'uploads/images/img_685ba25f55d50.png', '2025-06-25 14:16:47'),
(27, 'produk_hukum', '2022', '2025-06-26', '2025-06-26', 'Keputusan_Pimpinan_DPRD', NULL, 'qweqe', ' okwee e e ee e ', NULL, 'dcasdfa', 'sdfsdf', 'sdfsdf', 'Berlaku', 'Indonesia', 'pdf_685cca8a787e7.pdf', 'uploads/pdf/pdf_685cca8a787e7.pdf', '', 'qrwer', 'werwe', 'werwer', 'werw', 'werw', 'werwe', 'werwe', 'werw', 'wer', 'wer', 'werwer', 'img_685cca8a787f0.png', 'uploads/images/img_685cca8a787f0.png', '2025-06-26 11:20:26'),
(29, 'produk_hukum', 'sdfgdg', '2025-06-26', '2025-06-26', 'Keputusan_DPRD', NULL, 'dfgdf', 'dfgdf', NULL, 'dfg', 'fg', '', 'Berlaku', 'Indonesia', '2020kepimdprd33316632.pdf.pdf', 'uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'dfgdfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dfg', 'dgf', 'dfg', 'dfg', 'img_685cedd22ab04.png', 'uploads/images/img_685cedd22ab04.png', '2025-06-26 13:50:58'),
(32, 'produk_hukum', '2025', '2025-07-22', '2025-07-22', 'Keputusan_Sekretaris_DPRD', NULL, '65/66/555', 'bagus banget', NULL, 'bg', 'solo', 'solo', 'Berlaku', 'Indonesia', '2020kepimdprd33316632.pdf.pdf', 'uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'ok', 'ok', '788', 'solo', 'solo', 'solo', 'solo', '5656', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', 'solo', 'bagus', 'img_687f10ddbb75b.png', 'uploads/images/img_687f10ddbb75b.png', '2025-07-22 11:17:33'),
(33, 'produk_hukum', '2025', '2026-01-07', '2026-01-07', 'Keputusan_Sekretaris_DPRD', NULL, '55555', '88888', NULL, '55', '55', '55', 'Berlaku', 'Indonesia', 'albata.pdf', 'uploads/pdf/albata.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/albata.pdf', '55', '55', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_695dd726df479.png', 'uploads/images/img_695dd726df479.png', '2026-01-07 10:46:46'),
(34, 'produk_hukum', '2026', '2026-01-13', '2026-01-13', 'Keputusan_DPRD', NULL, '1234', 'peraturan baru aja', NULL, 'pba', 'surakarta', 'dprd', 'Berlaku', 'Indonesia', 'albata.pdf', 'uploads/pdf/albata.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/albata.pdf', 'aa', 'aa', '1234', 'jdih', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_6966093c594d8.png', 'uploads/images/img_6966093c594d8.png', '2026-01-13 15:58:36'),
(35, 'produk_hukum', '2026', '2026-01-14', '2026-01-14', 'Peraturan_DPRD', NULL, '0987', 'peraturan dprd 12', NULL, '', '', '', 'Berlaku', 'Indonesia', 'scp__6._LENA_SATLITA.pdf', 'uploads/pdf/scp__6._LENA_SATLITA.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/scp__6._LENA_SATLITA.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_69675c11b27e7.png', 'uploads/images/img_69675c11b27e7.png', '2026-01-14 16:04:17'),
(36, 'produk_hukum', '2026', '2026-01-15', '2026-01-15', 'Peraturan_Daerah', NULL, '9999', 'perda terbaru', NULL, 'perda', 'surakarta', '', '', '', '702.pdf', 'uploads/pdf/702.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/702.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_6968a76a40485.png', 'uploads/images/img_6968a76a40485.png', '2026-01-15 15:38:02'),
(37, 'produk_hukum', '2026', '2026-01-15', '2026-01-15', 'English_Legal_Product', NULL, '7777777', '', NULL, '', '', '', 'Berlaku', 'Inggris', 'Contoh_Form_Nilai_Ekskul_S1_2025.pdf', 'uploads/pdf/Contoh_Form_Nilai_Ekskul_S1_2025.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/Contoh_Form_Nilai_Ekskul_S1_2025.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_6968a7a1d39d7.png', 'uploads/images/img_6968a7a1d39d7.png', '2026-01-15 15:38:57'),
(38, 'produk_hukum', '2025', '2026-01-15', '2026-01-15', '', NULL, '333333', '333333', NULL, '33333', '3333', '', '', 'Inggris', '2020kepimdprd33316632.pdf.pdf', 'uploads/pdf/2020kepimdprd33316632.pdf.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/2020kepimdprd33316632.pdf.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_6968b26d9c831.png', 'uploads/images/img_6968b26d9c831.png', '2026-01-15 16:25:01'),
(39, 'produk_hukum', '2026', '2026-01-19', '2026-01-19', 'Keputusan_DPRD', NULL, '', '', NULL, '', '', '', 'Berlaku', 'Indonesia', 'Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'uploads/pdf/Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_696db1e2f3da0.png', 'uploads/images/img_696db1e2f3da0.png', '2026-01-19 11:24:03'),
(40, 'produk_hukum', '2026', '2026-01-23', '2026-01-23', 'English_Legal_Product', NULL, '55555', 'Lorem ipsum dolor sit amet', NULL, '', '', '', 'Berlaku', 'Inggris', 'albata__2_.pdf', 'uploads/pdf/albata__2_.pdf', 'http://localhost/jdihcilacap/panel_control/uploads/pdf/albata__2_.pdf', '', '', '', '', '', '', '', '', 'http://localhost/jdihcilacap/perpu_detail?doc=<?=  ?>', '', '', 'img_6972e5ed32eaf.jpg', 'uploads/images/img_6972e5ed32eaf.jpg', '2026-01-23 10:07:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel_hukum`
--
ALTER TABLE `artikel_hukum`
  ADD PRIMARY KEY (`id_artikel`),
  ADD KEY `idx_judul` (`judul`(255)),
  ADD KEY `idx_tahun` (`tahun_terbit`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_bidang_hukum` (`bidang_hukum`);

--
-- Indexes for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `grafis`
--
ALTER TABLE `grafis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monografi`
--
ALTER TABLE `monografi`
  ADD PRIMARY KEY (`id_monografi`);

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
-- AUTO_INCREMENT for table `artikel_hukum`
--
ALTER TABLE `artikel_hukum`
  MODIFY `id_artikel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grafis`
--
ALTER TABLE `grafis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monografi`
--
ALTER TABLE `monografi`
  MODIFY `id_monografi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
