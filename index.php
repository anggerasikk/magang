<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$allowed = ['home', 'tentang', 'perpu', 'perpusekdprd', 'monografi', 'yurisprudensi', 'artikel-hukum', 'informasi', 'kontaksaran', 'perpu_detail', 'detail-kegiatan']; // Tambahkan 'detail-kegiatan'
$page = in_array($page, $allowed) ? $page : 'home';
include 'pages/' . $page . '.php';
?>