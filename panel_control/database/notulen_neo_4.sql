-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 09:56 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notulen_neo_4`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `id` int(11) NOT NULL,
  `queries` varchar(300) NOT NULL,
  `predikat` varchar(100) NOT NULL,
  `replies` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chatbot`
--

INSERT INTO `chatbot` (`id`, `queries`, `predikat`, `replies`) VALUES
(1, 'siapa kamu', 'siapa nama kamu', 'hallo teman saya robot jdih'),
(3, 'selamat pagi', 'selamat siang dan selamat sore', 'iya salam'),
(4, 'hai woi haloo helo halo hello hai', 'heh hehe hihi', 'hehehehe'),
(18, 'bagaimana cara unduh jdih versi android', 'unduh jdih versi android', 'anda dapat klik melalui kolom tautan paling bawah yang berada di website jdih surakarta'),
(20, 'berapa jumlah peraturan perundang-undangan jdih surakarta', 'peraturan perundang-undangan jdih surakarta', 'berjumlah 114  '),
(22, 'dimana layanan perpustakaan ', 'layanan perpustakaan ', 'ada di kolom atas bertuliskan layanan perpustakaan'),
(23, 'apa sih jdih itu?', 'maksudnya jdih', 'wadah pendayagunaan bersama atas dokumen hukum'),
(25, 'apakah memiliki akun sosial media', 'akun sosial media jdih surakarta', 'tentunya ada kami memiliki akun instagram dan youtube'),
(26, 'kasih tau dong testimoni jdih dprd kota surakarta', 'testimoni jdih', 'selalu uptoddate, cepat, akurat dalam menyampaikan kegiatan penyusunan produk hukum'),
(27, 'apakah jdih selalu update berita nya?', 'update berita jdih', 'yapp, pastinya jdih selalu meng update berita terbaru nya, langsung aja cek di https://jdih.setwan.surakarta.go.id/berita/index'),
(28, 'apa itu fungsi dengan aplikasi jdih surakarta', 'fungsi aplikasi jdih surakarta', 'untuk mengoptimalkan proses publikasi produk hukum DPRD Kota Surakarta'),
(29, 'apa nama akun instagramnya ', 'nama akun instagram', '@setwansurakarta'),
(30, 'dimana dapat mencari dokumen keputusan dprd di jdih?', 'dokumen keputusan dprd', 'dapat di akses di link tersebut https://jdih.setwan.surakarta.go.id/dokumen/peraturan'),
(31, 'berapa dasar hukum yang tertera di jdih surakarta', 'jumlah dasar hukum jdih', 'ada 3 dasar hukum'),
(32, 'apasih sekilas fungsi tentang jdih itu?', 'sekilas fungsi jdih ', 'wadah pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan, serta merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.'),
(33, 'dimana bisa mengunjungi laman ulasan jdih dprd?', 'laman ulasan jdih dprd', 'https://ulas.surakarta.go.id/'),
(34, 'bagaimana cara agar aspirasi dapat diterima dengan cepat?', '', 'kamu bisa langsung menyampaikan aspirasi dengan cepat melalui https://ulas.surakarta.go.id/'),
(35, 'apasih sekilas sejarah dari jdih itu?', 'sekilas sejarah jdih', 'sejarah jdih bisa dibuka di link https://jdih.setwan.surakarta.go.id/sejarah jdih'),
(36, 'apakah visi jdih?', 'visi jdih', '\"Masyarakat memperoleh kepastian hukum\"'),
(37, 'jdih apakah punya misi', 'misi jdih', 'misi jdih \"Mewujudkan peraturan Perundang-Undangan yang berkualitas\"'),
(38, 'apakah jdih punya dasar hukum?', 'dasar hukum jdih', 'tentunya ada bisa diakses di https://jdih.setwan.surakarta.go.id/'),
(39, 'dimana letak berita jdih?', 'berita jdih', 'berita jdih bisa diakses https://jdih.setwan.surakarta.go.id/'),
(40, 'bagaimana cara mencari artikel atau majalah umum jdih?', 'artikel atau majalah jdih', 'ada dikolom atas pencarian'),
(41, 'bagaimana cara login layanan perpustakaan', 'login layanan perpustakaan', 'login menggunakan username dan password'),
(42, 'apakah jdih punya koleksi dokumen?', 'koleksi dokumen jdih', 'pastinya ada bisa diaksees di https://jdih.setwan.surakarta.go.id/'),
(43, 'jdih mengeluarkan berapa modul', 'modul jdih', 'modulnya ada 5'),
(44, 'berapa faktor penyebab lemahnya dukungan dokumentasi hukum', 'faktor penyebab', 'ada 4 faktor'),
(45, 'apa hasil dari dilaksanakannya lokakarya', 'hasil lokakarya', 'menunjuk BPHN sebagai Pusat Jaringan dan diberi tugas sebagai penyelenggara latihan pembinaan tenaga'),
(46, 'tahun berapa dilakukan lokakarya', 'tahun lokakarya', 'tahun 1978'),
(47, 'tahun berapa terbit Kebijakan Nasional terkait pelaksanaan JDIHN', 'tahun terbit kebijakan nasional', 'tahun 1999'),
(48, 'apa sih isi keputusan presiden nomor 91 tahun 1999', 'Keputusan Presiden Nomor 91 Tahun 1999', 'Keputusan Presiden Nomor 91 Tahun 1999 tentang Jaringan Dokumentasi dan Informasi Hukum Nasional dalam Lembaran Negara No. 135.'),
(49, 'apa isi Peraturan Presiden Nomor 33 Tahun 2012', 'Peraturan Presiden Nomor 33 Tahun 2012', 'Isinya tentang Jaringan Dokumentasi dan Informasi Hukum Nasional yang salah satu tugasnya adalah melakukan pembinaan, pengembangan dan monitoring pada anggota JDIHN'),
(50, 'bagaimana struktur organisasi jdih', 'struktur organisasi', 'bisa liat disini https://jdih.setwan.surakarta.go.id/site/sto'),
(51, 'monografi jdih', 'monografi', 'bisa liat disini https://jdih.setwan.surakarta.go.id/dokumen/monografi'),
(52, 'pedoman wajib jdih', 'pedoman jdih', 'Peraturan Menteri Hukum dan HAM Nomor 8 Tahun 2019 Tentang Standar Pengelolaan Dokumen dan Informasi Hukum'),
(53, ' berapa Standar Pengelolaan Dokumen ', 'Standar Pengelolaan Dokumen ', 'ada 3'),
(54, 'apa kepanjangan jdih', 'kepanjangan jdih', 'JARINGAN DOKUMENTASI DAN INFORMASI HUKUM'),
(55, 'siapa ketua dprd surakarta', 'ketua dprd surakarta', 'Budi Prasetyo, S.Sos'),
(56, 'siapa wakil ketua dprd surakarta', 'wakil ketua dprd surakarta', 'wakil 1 : H. SUGENG RIYANTO, S.S\r\nwakil 2 : Drs. TAUFIQURAHMAN\r\nwakil 3 : Drs. ACHMAD SAPARI MM\r\n'),
(57, 'apa tata tertib dprd surakarta', 'tata tertib dprd', 'bisa diliat disini https://dprd.surakarta.go.id/wp-content/uploads/2022/03/TataTertibDPRDKotaSurakarta.pdf'),
(58, 'siapa ketua badan kehormatan', 'ketua badan kehormatan', 'Ketua YULIANTO INDRATMOKO\r\nWkil Ketua H. MUHAMMAD AL AMIN, S.E\r\n'),
(59, 'apa aja pengumuman jdih surakarta', 'pengumuman jdih surakarta', 'silakan cek disini\r\nhttps://jdih.surakarta.go.id/pawartos.php?YmVyaXRhX2lkPTImbWVudTE9SW5mb3JtYXNpJm1lbnUyPURhZnRhciBQZW5ndW11bWFu'),
(60, 'siapa aja anggota jdih surakarta', 'anggota jdih surakarta', 'silakan cek disini https://jdih.surakarta.go.id/unit.php?YmVyaXRhX2lkPTcmbWVudTE9QmVyYW5kYSZtZW51Mj1Bbmdnb3RhIEpESUg='),
(61, 'ada berapa sub bidang di jdih', 'sub bidang jdih', 'ada 6 sub bidang'),
(62, 'hai apa kabarmu', 'kabar', 'hai,ada yang bisa kami bantu'),
(63, 'tolong saya', 'tolong', 'ada yang bisa saya bantu'),
(64, 'lagi ngapain', 'ngapain', 'lagi balas chat kamu'),
(65, 'yang benar', 'benar', 'Y'),
(66, 'kamu brengsek', 'brengsek', 'tolong berbicara dengan baik\r\nada yang bisa kami bantu'),
(67, 'kamu anjing', 'anjing', 'tolong berbicara dengan sopan ada yang bisa kami bantu'),
(68, 'apakah kamu baik-baik saja?', 'baik', 'tentu baik baik saja hehehe\r\n\r\n'),
(69, 'terima kasih', 'makasih', 'sama sama ada yang mau ditanyakan lagi'),
(70, 'masa?', 'masak,mosok', 'coba dengan bahasa lain'),
(71, 'boong', 'bohong,ngapusi,mang eak', 'coba tanyakan hal lain'),
(72, 'apa yang anda pikirkan', 'pikiran anda', 'selamat datang di jdih ada yang bisa kami bantu'),
(73, 'hallo robot', 'hallo robot', 'robot disini '),
(74, 'sekarang kamu dimana', 'lokasi, posisi', 'jl.adisucipto no.143A, karangasem,kec.laweyan, kota surakarta, Jawa tengah'),
(75, 'kata kata hari ini', 'kata kata', 'tidak ada kata gagal hidup ini,kecuali saat kamu menyerah menghadapi cobaan'),
(76, 'apa profil dprd surakarta', 'profil dprd', 'DPRD menjadi lembaga yang berwenang dalam melakukan tugas legislasi atau persetujuan terhadap aturan daerah dan pengawasan penggunaan anggaran pemerintah daerah'),
(77, 'apa fungsi dprd kota', 'fungsi dprd', 'DPRD memiliki tiga fungsi, yaitu : Legislasi, berkaitan dengan pembentukan peraturan daerah. Anggaran, Kewenangan dalam hal anggaran daerah(APBD) Pengawasan, Kewenangan mengontrol pelaksanaan perda dan peraturan lainnya serta kebijakan pemerintah daerah.'),
(78, 'jam operasional dprd surakarta', 'jam', '07.30-16.30'),
(79, 'sebelah mana kantor dprd surakarta', 'sebelah mana', 'jl.adisucipto no.143A, karangasem,kec.laweyan, kota surakarta, Jawa tengah'),
(80, 'aplikasi apa saja yang berjalan di jdih', 'aplikasi yang tertaut', 'SIMDA KEUANGAN, SIMDA BARANG, WEBSITE DPRD, APLIKASI PERPUSTAKAAN, APLIKASI ARSIP, APLIKASI SIMLEG, APLIKASI SIMARDI, APLIKASI PEMBUATAN A2'),
(81, 'siapa yang menciptakanmu', 'cipta', 'mr.danang'),
(82, 'iya ada', 'ada', 'apakah itu?'),
(83, 'apa motto dprd surakarta', 'motto dprd', '\"mulat sarira angrasa wani\"\r\n'),
(84, 'apa slogan dprd surakarta', 'slogan dprd', '\"the spirit of java\"'),
(85, 'ada berapa komisi 1 bidang pemerintahan', 'komisi 1', 'ada 13'),
(86, 'di dprd ada berapa komisi?', 'komisi', 'ada empat'),
(87, 'ada berapa komisi 2 bidang perekonomian', 'komisi 2', 'ada 11'),
(88, 'ada berapa komisi 3 bidang pembangunan', 'komisi 3', 'ada 9'),
(89, 'ada berapa komisi 4 bidang kesejahteraan rakyat', 'komisi 4', 'ada 10'),
(90, 'siapa ketua fraksi partai demokrasi indonesia perjuangan', 'ketua fraksi PDIP', 'YF. Sukasno, S.H'),
(91, 'Apa peran DPRD dalam sebuah pemerintahan', 'peran dprd', 'pembentukan peraturan daerah, anggaran dan pengawasan'),
(92, 'siapa ketua fraksi partai keadilan sejahtera', 'ketua fraksi PKS', 'H ASIH SUNJOTO PUTRO, S.Si\r\n'),
(93, 'mengapa dprd perlu mengawasi kepala daerah', 'mengawasi', 'agar tidak terjadi kesewenang-wenangan dalam menjalankan tugasnya dalam rangka mensejahterakan rakyat'),
(94, 'apa fungsi dprd terkait keuangan daerah', 'fungsi keuangan daerah', 'untuk menetapkan kebijakan keuangan'),
(95, 'apakah dprd merupakan lembaga legislatif', 'lembaga legislatif', 'dijelaskan dprd memiliki hak penuh sebagai lembaga legislatif'),
(96, 'siapa yang membuat peraturan daerah', 'peraturan daerah', 'dibuat oleh kepala daerah setelah mendapat persetujuan bersama dprd'),
(97, 'apa kepanjangan dprd', 'kepanjangan dprd', 'dewan perwakilan rakyat daerah'),
(98, 'apa kewajiban anggota jdih', 'kewajiban anggota jdih', 'Melakukan Pengelolaan Dokumentasi dan Informasi Hukum dengan menyediakan sarana dan prasarana, serta sumber daya manusia, dan anggaran '),
(99, 'mengapa anggota jdih harus memiliki website jdih', 'website jdih ', ' setiap anggota JDIHN dapat mempublikasikan Dokumen Hukum yang diterbitkannya, serta melalui website JDIH pula masyarakat maupun pencari informasi hukum dapat memperoleh Dokumen Hukum yang diterbitkan oleh instansi Anggota JDIHN tersebut'),
(100, 'Apa Tugas Anggota Jaringan Dokumentasi Dan Informasi Hukum Nasional?', 'Apa Tugas Anggota Jaringan Dokumentasi', 'Melakukan Pengelolaan Dokumentasi dan Informasi Hukum yang Diterbitkan oleh Instansinya.'),
(101, 'siapa yang menetapkan peraturan dprd', 'peraturan dprd', 'ditetapkan oleh pimpinan dprd'),
(102, 'bagaimana pengambilan keputusan dprd', 'keptusan dprd', 'pengambilan keputusan dalam rapat dprd pada dasarnya diusahakan sejauh mungkin dengan musyawarah'),
(103, 'Bagaimana cara mengunduh dokumen hukum?', 'mengunduh dokumen hukum', 'Pengunjung masuk ke tautan detail dokumen produk yang dibutuhkan, lalu tekan tombol Salinan.'),
(104, 'Bagaimana jika dokumen hukum tidak dapat ditemukan?', 'dokumen hukum tidak ditemukan', 'Pengunjung dapat menghubungi kami melalui alamat email dan mengisi kotak saran dan pertanyaan yang tertera dihalaman Hubungi Kami.'),
(105, 'Bagaimana cara mencari dokumen hukum?', 'mencari dokumen hukum', 'pencarian dokumen hukum dapat dilakukan dengan nomor/judul/kata kunci pada kolom pencarian atau melalui pencarian lanjutan (filter) dengan menggunakan indikator yang telah disediakan.'),
(106, 'Bagaimana memberikan saran dan pertanyaan seputar dokumen hukum yang di laman ini?', 'memberikan saran dokumen hukum', 'Pengunjung dapat mengakses menu Kontak dan mengisi kotak saran dan pertanyaan yang tersedia.'),
(107, 'apakah dprd surakarta sabtu tetap buka', 'dprd surakarta sabtu tetap buka', 'maaf jika hari sabtu kami tutup, silahkan berkunjung lagi dihari senin-jumat'),
(108, 'jam buka hari jumat dprd surakarta', 'jam buka hari jumat', '7.30-11.00'),
(109, 'jam berapa dprd surakarta buka?', 'jam buka dprd', '07.30'),
(110, 'kamu siapa', 'siapa', 'saya robot yang akan menjawab pertanyaanmu tentang dprd'),
(112, 'sayang', 'yang,beb', 'ya ada robot disini\r\napakah ada yang bisa dibantu'),
(113, 'apa nama akun instagram dprd surakarta', 'instagram dprd surakarta', '@dprd.kota.surakarta'),
(114, 'apakah dprd kota surakarta menerima anak magang', 'magang,penelitian,PI', 'sangat menerima,lebih lengkapnya langsung datang saja ke kantor dprd surakarta'),
(115, 'berapa sih gaji anggota dan pimpinan DPRD ', 'gaji anggota dan pimpinan  DPRD', 'sekitar 4juta sampai 5juta'),
(116, 'bagaimana ulasan lokasi tempat dprd surakarta', 'lokasi', 'lokasinya sejuk,aman,nyaman,luas,bersih,banyak pepohonan,dan rapi'),
(117, 'tahun berapa gedung dprd surakarta dibangun', 'gedung dprd', 'tahun 1931-1932'),
(118, 'kantor DPRD surakarta berada diprovinsi mana?', 'provinsi dprd surakarta', 'Provinsi Jawa Tengah'),
(119, 'kamu robot apaan', 'robot apaan', 'kenalin nih,aku robot jdih DPRD surakarta'),
(120, 'kepada siapa jika ingin mengunjuingi kantor dprd surakarta?', 'kepada siapa', 'silahkan menuju pos satpam yang berada di gerbang masuk kantor dprd surakarta'),
(121, 'dimanakah pelaksanaan rapat yang diadakan di kantor dprd surakarta?', 'pelaksanaan rapat', 'rapat diselenggarakan di gedung Graha Paripurna'),
(123, 'apakah kantor dprd surakarta dibuka untuk umum', 'dibuka, umum', 'bagi seluruh tamu wajib melapor terlebih dahulu pada pos satpam sebelum memasuki gedung dprd surakarta'),
(124, 'siapa jdih', '', 'jdih adalah robot pimtar yang bisa membantu kamu'),
(125, '', '', 'hai juga'),
(126, '', 'mau tanya', 'tanya apa'),
(127, '', '', 'robot pintar'),
(128, 'lokasi', '', 'Jl. Adi Sucipto No. 143 A\r\nSurakarta, Jawa Tengah - INDONESIA'),
(129, '', '', 'iya pagi juga '),
(130, '', 'minta tolong', 'tolong apa'),
(131, '', '', 'berita adalah informasi atau peristiwa terbaru'),
(132, 'kontak', '', 'telp : (0271) 712461, fax : (0271) 717620\r\nEmail : setwan.surakarta@gmail.com'),
(133, '', '', 'iya siang juga'),
(134, '', 'kamu tau gak', 'tau apa?'),
(136, 'web site', '', 'https://jdih.setwan.surakarta.go.id/'),
(137, '', '', 'waalaikumsalam'),
(138, '', 'lagi apa', 'sedang menunggu kamu'),
(139, '', '', 'surakarta adalah solo'),
(140, 'kapan merdeka', '', '17 agustus 1945'),
(141, '', '', 'sama'),
(142, '', 'kamu siapa', 'kepo'),
(143, '', '', 'https://jdih.setwan.surakarta.go.id/site/sekilas-sejarah'),
(144, 'instagram', '', '@setwansurakarta'),
(145, '', '', 'tanya aku aja'),
(146, '', 'bangsa', 'indonesia'),
(147, '', '', 'Chatbot adalah robot virtual yang dapat menirukan percakapan layaknya manusia.'),
(148, 'pinter', '', 'belajar makanya emm'),
(149, '', '', 'sekitar 2 tahun'),
(151, '', '', 'pancasila adalah dasar negara RI'),
(152, 'dasar negara', '', 'PANCASILA'),
(154, '', '', 'Dewan Perwakilan Rakyat Daerah provinsi (DPRD provinsi) adalah lembaga perwakilan rakyat daerah yang berkedudukan sebagai unsur penyelenggara pemerintahan daerah provinsi'),
(155, '', '', 'Dewan Perwakilan Rakyat Daerah kabupaten/kota (DPRD kabupaten/kota) adalah lembaga perwakilan rakyat daerah yang berkedudukan sebagai unsur penyelenggara pemerintahan daerah kabupaten/kota'),
(156, '', '', 'bisa dilihat melalui https://id.wikipedia.org/wiki/Berkas:Lambang_DPRD_Generik.png'),
(157, '', '', 'Fraksi adalah wadah berhimpun anggota DPRD provinsi untuk mengoptimalkan pelaksanaan fungsi, wewenang dan tugas DPRD provinsi, serta hak dan kewajiban anggota DPRD provinsi');

-- --------------------------------------------------------

--
-- Table structure for table `fraksi`
--

CREATE TABLE `fraksi` (
  `id_fraksi` int(11) NOT NULL,
  `nama_fraksi` varchar(200) NOT NULL,
  `anggota` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notulen`
--

CREATE TABLE `notulen` (
  `id_notulen` int(11) NOT NULL,
  `no_notulen` varchar(30) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(10) NOT NULL,
  `tempat` varchar(50) NOT NULL,
  `jenis_rapat` varchar(200) NOT NULL,
  `rapat_ke` varchar(20) NOT NULL,
  `masa_sidang` varchar(20) NOT NULL,
  `tahun_sidang` varchar(10) NOT NULL,
  `sifat_rapat` varchar(100) NOT NULL,
  `agenda` varchar(200) NOT NULL,
  `pimpinan_rapat` varchar(50) NOT NULL,
  `wakil_ketua_1` varchar(100) NOT NULL,
  `wakil_ketua_2` varchar(100) NOT NULL,
  `sekretaris_dprd` varchar(50) NOT NULL,
  `pencatat` varchar(400) NOT NULL,
  `pihak_1` varchar(100) NOT NULL,
  `pihak_2` varchar(100) NOT NULL,
  `pihak_3` varchar(100) NOT NULL,
  `hadir` varchar(200) NOT NULL,
  `fraksi_golkar` varchar(500) NOT NULL,
  `fraksi_pdip` varchar(500) NOT NULL,
  `fraksi_pkb` varchar(500) NOT NULL,
  `fraksi_gerindra` varchar(500) NOT NULL,
  `fraksi_pksperindo` varchar(500) NOT NULL,
  `fraksi_demonasdem` varchar(500) NOT NULL,
  `jalan_rapat` text NOT NULL,
  `hasil_rapat` text NOT NULL,
  `kesimpulan` text NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notulen`
--

INSERT INTO `notulen` (`id_notulen`, `no_notulen`, `hari`, `tanggal`, `jam`, `tempat`, `jenis_rapat`, `rapat_ke`, `masa_sidang`, `tahun_sidang`, `sifat_rapat`, `agenda`, `pimpinan_rapat`, `wakil_ketua_1`, `wakil_ketua_2`, `sekretaris_dprd`, `pencatat`, `pihak_1`, `pihak_2`, `pihak_3`, `hadir`, `fraksi_golkar`, `fraksi_pdip`, `fraksi_pkb`, `fraksi_gerindra`, `fraksi_pksperindo`, `fraksi_demonasdem`, `jalan_rapat`, `hasil_rapat`, `kesimpulan`, `id_user`) VALUES
(1, '01 /Komisi II-DPRD/I/2016', 'Jumat', '2017-06-30', '08:00', 'ruang_paripurna', 'rapat_banggar', '', '', '', '', 'Renja Tahun 2016', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'SUPRIYANTO, SH - Sekretaris Komisi II', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya <b>bidang drainase dan pengelolaan persampahan </b>terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 1),
(3, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_kepanitiaan', 'rapat_pansus', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(4, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_bapemperda', 'rapat_komisi', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(5, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_banggar', 'rapat_banmus', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(6, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_banggar', 'rapat_bk', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(7, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_banggar', 'rapat_public_hearing', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(8, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_banggar', 'rapat_paripurna', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(9, '02 /Komisi II-DPRD/I/2017', 'Senin', '2017-07-01', '08:00', 'ruang_paripurna', 'rapat_lainnya', '', '', '', '', 'Renja Tahun 2017', 'YF. SUKASNO, SH - Ketua Komisi II', '', '', 'Testing', '', '', '', '', '', '', '', '', '', '', '', 'Pimpinan membuka Rapat, menyampaikan maksud dan tujuan diadakannya rapat yaitu : Renja 2016', '1. Untuk mensinkronkan Prohram kegiatan khususnya bidang drainase dan pengelolaan persampahan terkait dengan penghargaan Adipura maka komisi II akan melaksanakan konsultasi ke Kementerian Pekerjaan Umum dan Perumahan Rakyat RI dan Kementerian Lingkungan Hidup RI pada tanggal 14 s/d 16 Januari 2016.<br>2. Pada tanggal 6 Januari 2016 jam 11.00 WIB untuk diagendakan rapat internal komisi II dengan agenda rencana kerja tahun 2016 secara menyeluruh.', '', 5),
(10, '44', 'Selasa', '2023-09-27', '3432', 'ruang_paripurna', 'rapat_komisi', '', '', '', '', '234', '324', '', '', '324', '', '', '', '', '', '', '', '', '', '', '', '2342314', '_NAMA PEMBICARA:&gt;&gt;danang&nbsp; 10:17:42&gt;&gt;&gt;Halo tes Perkenalkan nama saya Danang saya anggota Komisi 1 yang membidangi bidang pemerintahan dan ekonomi saya selaku ketua komisi akan membuka Rapat ini dan semoga Nanti rapat bisa berjalan dengan lancar kan_NAMA PEMBICARA:&gt;&gt;febri&nbsp; 10:18:19&gt;&gt;&gt;nama saya Febri saya anggota Komisi 2 tugas saya di komisi 2 sebagai ketua adalah memastikan semua anggota Saya bekerja dengan baik', '', 1),
(11, '24234', 'Senin', '2023-12-03', '432', 'ruang_paripurna', 'rapat_lainnya', '', '', '', '', 'awdef', 'tiko', '', '', 'senja', '', '', '', '', '', '', '', '', '', '', '', 'xcxvcxv', 'xcvcxvx', 'cxvcxvxv', 1),
(12, '', 'Senin', '2023-12-03', 'waedsad', 'ruang_paripurna', 'rapat_banggar', '', '', '', '', 'sadsad', 'koko.se', '', '', 'asdsad', '', '', '', '', '', '', '', '', '', '', '', 'sad', 'asdsa', 'sadsad', 1),
(13, '55', 'Senin', '2023-12-08', 'erew', 'ruang_banggar', 'rapat_banggar', '', '', '', '', 'werewrwe', 'koko.se', 'dgdfg', 'dfgdfg', 'ika eka. se', '', 'dfgdfg', 'dfgdf', 'dfgdfg', '', '', '', '', '', '', '', 'erewr', 'werewr', 'ewrwer', 1),
(14, 'erter', 'Senin', '2025-01-06', '5345', 'ruang_paripurna', 'Banggar', '', '', '', '', 'dfghfh', 'joko', 'eko', 'dika', '', '', 'kikik', 'kisno', 'selfi', '', '', '', '', '', '', '', 'ghjghjg', 'ghjghj', 'ghjghjh', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pejabat`
--

CREATE TABLE `pejabat` (
  `id_pejabat` int(11) NOT NULL,
  `pimpinan_rapat` varchar(30) NOT NULL,
  `wakil_ketua_1` varchar(30) NOT NULL,
  `wakil_ketua_2` varchar(200) NOT NULL,
  `sekretaris_dprd` varchar(100) NOT NULL,
  `notulis` varchar(200) NOT NULL,
  `pihak_1` varchar(100) NOT NULL,
  `pihak_2` varchar(100) NOT NULL,
  `pihak_3` varchar(100) NOT NULL,
  `pencatat` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pejabat`
--

INSERT INTO `pejabat` (`id_pejabat`, `pimpinan_rapat`, `wakil_ketua_1`, `wakil_ketua_2`, `sekretaris_dprd`, `notulis`, `pihak_1`, `pihak_2`, `pihak_3`, `pencatat`) VALUES
(6, 'joko', 'eko', 'dika', '', 'vika', 'kikik', 'kisno', 'selfi', '');

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id_organisasi` int(50) NOT NULL,
  `nama_badan` varchar(100) NOT NULL,
  `nama_1` varchar(100) NOT NULL,
  `nama_2` varchar(100) NOT NULL,
  `nama_3` varchar(100) NOT NULL,
  `nama_4` varchar(100) NOT NULL,
  `nama_5` varchar(100) NOT NULL,
  `nama_6` varchar(100) NOT NULL,
  `nama_7` varchar(100) NOT NULL,
  `nama_8` varchar(100) NOT NULL,
  `nama_9` varchar(100) NOT NULL,
  `nama_10` varchar(100) NOT NULL,
  `nama_11` varchar(100) NOT NULL,
  `nama_12` varchar(100) NOT NULL,
  `nama_13` varchar(100) NOT NULL,
  `nama_14` varchar(100) NOT NULL,
  `nama_15` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id_organisasi`, `nama_badan`, `nama_1`, `nama_2`, `nama_3`, `nama_4`, `nama_5`, `nama_6`, `nama_7`, `nama_8`, `nama_9`, `nama_10`, `nama_11`, `nama_12`, `nama_13`, `nama_14`, `nama_15`) VALUES
(1, 'Badan _Anggaran', 'eko suyanto', 'jeki', 'joko', 'siska', 'deni', 'meme', 'siska', 'soso', 'sisi', 'heti', 'meka', 'sugiyatni', 'bobi', 'komeng', 'wika'),
(2, 'Badan_Musyawarah', ' ika', 'eny', 'rama', 'joshua', 'endi', 'kiki', 'niken', 'risky', 'senja', 'yulia', 'alfian', 'ipin', 'vika', 'ita', 'gilang'),
(3, 'Badan_Kehormatan', 'aninda', 'yudi', 'satrio', 'ari angga', 'tiko', 'aya', 'nardi', 'agus', 'andika', 'nila', 'elga', 'mega', 'ucup', 'irwanto', 'gina');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpmu_user`
--

INSERT INTO `phpmu_user` (`id_user`, `username`, `password`, `nama_lengkap`, `alamat_email`, `no_telpon`, `alamat_lengkap`, `level`, `status`, `waktu_daftar`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin@gmail.com', '0812345678', 'Jl raya gentan baki sukoharjo', 'user_admin', 'Y', '2015-06-03 00:00:00'),
(3, 'dewi', 'ed1d859c50262701d92e5cbf39652792', 'Dewi Safitri', 'dewi.safitri@gmail.com', '087305450112', 'Linggar Jati, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-03 00:00:00'),
(4, 'udin', '6bec9c852847242e384a4d5ac0962ba0', 'Udin Sedunia', 'udin.sedunia@gmail.com', '081267771390', 'Ulak Karang, Padang, Sumatera Barat', 'user_biasa', 'Y', '2015-06-04 08:03:18'),
(5, 'amel', 'da0e22de18e3fbe1e96bdc882b912ea4', 'Amel Amelia', 'amel.amelia@gmail.com', '0812345678', 'Jl raya solo jawa tengah', 'user_input', 'Y', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `id_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(50) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`id_ruang`, `nama_ruang`, `keterangan`) VALUES
(4, 'ruang_paripurna', 'untuk rapat besar'),
(6, 'ruang_banggar', 'ruang khsuus banggar'),
(7, 'ruang_kepanitiaan', 'umum'),
(8, 'ruang_bapemperda', 'untuk rapat Bapemperda'),
(9, 'ruang umum', 'umum');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fraksi`
--
ALTER TABLE `fraksi`
  ADD PRIMARY KEY (`id_fraksi`);

--
-- Indexes for table `notulen`
--
ALTER TABLE `notulen`
  ADD PRIMARY KEY (`id_notulen`);

--
-- Indexes for table `pejabat`
--
ALTER TABLE `pejabat`
  ADD PRIMARY KEY (`id_pejabat`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_organisasi`);

--
-- Indexes for table `phpmu_user`
--
ALTER TABLE `phpmu_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id_ruang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `fraksi`
--
ALTER TABLE `fraksi`
  MODIFY `id_fraksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notulen`
--
ALTER TABLE `notulen`
  MODIFY `id_notulen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pejabat`
--
ALTER TABLE `pejabat`
  MODIFY `id_pejabat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_organisasi` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phpmu_user`
--
ALTER TABLE `phpmu_user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
