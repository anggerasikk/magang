<?php 

// index.php (ROOT)
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($uri === 'sys-auth-9f3a') {
    require 'http://localhost/jdihcilacap_pasang_2026/panel_control/index.php';
    exit;
}

session_start();
error_reporting(0);
include "koneksi.php";
    if ($_SESSION['login']==''){
        include "login.php";
    }else{
        include "content.php";
    }
?> 

