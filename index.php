<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$allowed = ['home', 'tentang', 'perpu', 'perpusekdprd', 'monografi', 'yurisprudensi', 'artikel-hukum', 'detail-artikel', 'informasi', 'kontaksaran', 'perpu_detail', 'detail-kegiatan', 'profil-jdih', 'struktur-organisasi', 'alur-pengelolaan', 'sop-pengelola', 'dasar-hukum', 'surat-keputusan', 'video-profile'];
$page = in_array($page, $allowed) ? $page : 'home';
include 'pages/' . $page . '.php';
?>