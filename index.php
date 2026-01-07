<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$allowed = ['home', 'tentang', 'perpu', 'perpusekdprd', 'monografi', 'yurisprudensi', 'artikelhukum', 'informasi', 'kontaksaran', 'perpu_detail'];
$page = in_array($page, $allowed) ? $page : 'home';
include 'pages/' . $page . '.php';
?>
