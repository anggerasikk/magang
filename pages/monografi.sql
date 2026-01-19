-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 06:49 AM
-- Server version: 12.1.2-MariaDB-log
-- PHP Version: 8.3.26

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
(6, 'Memahami untuk Membasmi : Panduan untuk Memahami Tindak Pidana Korupsi', 'Himpunan Peraturan', '2026-01-19', '2026', '', '', '', '', 0, '', 'Berlaku', 'Indonesia', 'uploads/monografi/pdf/monografi_1768803915_Ringkasan_Bab_5_Klasifikasi_Makhluk_Hidup_Part_1.pdf', 'uploads/monografi/images/thumbnail_1768803915_ChatGPT Image Jan 15, 2026, 04_20_08 PM.png', '2026-01-19 06:25:15', NULL, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monografi`
--
ALTER TABLE `monografi`
  ADD PRIMARY KEY (`id_monografi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monografi`
--
ALTER TABLE `monografi`
  MODIFY `id_monografi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
