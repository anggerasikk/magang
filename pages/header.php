<?php
// header.php

// Mendapatkan halaman aktif
$currentPage = basename($_SERVER['PHP_SELF']);
$currentQuery = isset($_GET['page']) ? $_GET['page'] : '';

// Fungsi untuk menentukan menu aktif
function isActiveMenu($page, $currentPage, $currentQuery, $targetPage = '', $targetQuery = '') {
    if ($targetPage && $currentPage === $targetPage) {
        return 'active';
    }
    if ($targetQuery && $currentQuery === $targetQuery) {
        return 'active';
    }
    return '';
}

// Fungsi untuk highlight dropdown jika ada submenu yang aktif
function isDropdownActive($pages, $currentQuery) {
    foreach ($pages as $page) {
        if ($currentQuery === $page) {
            return 'active';
        }
    }
    return '';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'JDIH DPRD Kota Surakarta'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
    
    <!-- NAVBAR CSS -->
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        /* OVERRIDE UNTUK NAVBAR - HANYA LAYOUT SAJA */
        body {
            padding-top: 80px;
        }
        
        /* NAVBAR POSITIONING - TETAP WARNA ASLI */
        .navbar-custom {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }
        
        /* LOGO STYLING UNTUK UKURAN 398x136px */
        .navbar-brand {
            display: flex;
            align-items: center;
            padding: 0;
            margin-right: 30px;
            height: 60px; /* Tinggi container logo */
        }
        
        .navbar-brand img {
            height: 100%; /* Tinggi mengikuti container */
            width: auto; /* Lebar otomatis proporsional */
            max-width: 200px; /* Maksimal lebar untuk desktop */
            object-fit: contain; /* Agar tidak terdistorsi */
            transition: all 0.3s ease;
        }
        
        /* Penyesuaian untuk berbagai ukuran layar */
        @media (min-width: 992px) {
            .navbar-brand {
                height: 55px; /* Sedikit lebih kecil di desktop */
            }
            
            .navbar-brand img {
                max-width: 180px;
            }
            
            .navbar-brand {
                margin-right: 20px;
            }
        }
        
        @media (min-width: 1200px) {
            .navbar-brand {
                height: 60px;
            }
            
            .navbar-brand img {
                max-width: 200px;
            }
        }
        
        @media (min-width: 1400px) {
            .navbar-brand {
                height: 65px; /* Sedikit lebih besar di layar lebar */
            }
            
            .navbar-brand img {
                max-width: 220px;
            }
        }
        
        /* MOBILE - Logo lebih kecil */
        @media (max-width: 991.98px) {
            .navbar-brand {
                height: 50px;
                margin-right: 15px;
            }
            
            .navbar-brand img {
                max-width: 160px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                height: 45px;
            }
            
            .navbar-brand img {
                max-width: 140px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                height: 40px;
            }
            
            .navbar-brand img {
                max-width: 120px;
            }
        }
        
        @media (max-width: 400px) {
            .navbar-brand {
                height: 35px;
            }
            
            .navbar-brand img {
                max-width: 100px;
            }
        }
        
        /* NAV MENU LAYOUT - DI TENGAH */
        .navbar-nav {
            flex-wrap: nowrap;
            justify-content: center;
            width: 100%;
        }
        
        .navbar-nav .nav-item {
            margin: 0 2px;
            text-align: center;
            min-width: max-content;
        }
        
        /* NAV LINK SIZE - DIKECILKAN AGAR MUAT */
        .navbar-nav .nav-link {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 6px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        /* DROPDOWN MENU SIZE - DIKECILKAN */
        .dropdown-menu {
            min-width: 200px;
            padding: 5px 0;
            margin-top: 5px;
        }
        
        .dropdown-item {
            font-size: 0.8rem;
            padding: 6px 15px;
        }
        
        /* RESPONSIVE ADJUSTMENTS UNTUK MENU */
        @media (min-width: 992px) {
            .navbar-nav .nav-link {
                font-size: 0.7rem;
                padding: 5px 7px;
            }
            
            .navbar-nav .nav-item {
                margin: 0 1px;
            }
        }
        
        @media (min-width: 1200px) {
            .navbar-nav .nav-link {
                font-size: 0.75rem;
                padding: 6px 8px;
            }
            
            .navbar-nav .nav-item {
                margin: 0 2px;
            }
        }
        
        @media (min-width: 1400px) {
            .navbar-nav .nav-link {
                font-size: 0.8rem;
                padding: 7px 10px;
            }
            
            .navbar-nav .nav-item {
                margin: 0 3px;
            }
        }
        
        /* MOBILE MENU */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: inherit;
                padding: 15px;
                border-radius: 8px;
                margin-top: 10px;
            }
            
            .navbar-nav {
                align-items: flex-start;
            }
            
            .navbar-nav .nav-item {
                margin: 3px 0;
                width: 100%;
            }
            
            .navbar-nav .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
                text-align: left;
            }
            
            .dropdown-menu {
                margin-top: 2px;
                margin-left: 15px;
                width: calc(100% - 30px);
            }
            
            .dropdown-item {
                font-size: 0.85rem;
                padding: 8px 20px;
            }
        }
        
        /* TOGGLER BUTTON - TETAP WARNA ASLI */
        .navbar-toggler {
            padding: 3px 6px;
            border-width: 1px;
        }
                /* STYLE UNTUK HALAMAN ALUR PENGELOLAAN */
        /* HEADER SECTION */
        .page-header {
            background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
            padding: 40px 0 30px 0;
            margin-top: 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .page-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .page-header .subtitle {
            font-size: 1.1rem;
            color: white;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }
        
        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 2;
        }
        
        .page-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .page-header .breadcrumb-item.active {
            color: white;
        }
        
        .page-header .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.8);
        }

        /* MAIN CONTENT */
        .main-content {
            padding: 50px 0 60px 0;
            background: #f0f8ff;
        }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #13547a, #80d0c7);
        }

        /* ALUR PENGELOLAAN CARDS */
        .alur-container {
            position: relative;
            margin-bottom: 50px;
        }
        
        .alur-timeline {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #13547a, #80d0c7);
            transform: translateX(-50%);
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .alur-timeline {
                left: 30px;
            }
        }
        
        .alur-step {
            position: relative;
            margin-bottom: 40px;
            z-index: 2;
        }
        
        .alur-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .alur-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .step-number {
            position: absolute;
            top: -15px;
            left: -15px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            box-shadow: 0 3px 10px rgba(19, 84, 122, 0.3);
            z-index: 3;
        }
        
        .alur-card h4 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.2rem;
            padding-right: 30px;
        }
        
        .alur-card p {
            color: #555;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 0;
        }
        
        /* ALTERNATING LAYOUT */
        .alur-step:nth-child(odd) .alur-card {
            margin-left: 50%;
            padding-left: 40px;
        }
        
        .alur-step:nth-child(even) .alur-card {
            margin-right: 50%;
            padding-right: 40px;
        }
        
        @media (max-width: 768px) {
            .alur-step:nth-child(odd) .alur-card,
            .alur-step:nth-child(even) .alur-card {
                margin-left: 60px;
                margin-right: 20px;
                padding-left: 40px;
                padding-right: 20px;
            }
            
            .step-number {
                left: -25px;
            }
        }

        /* DAFTAR PRODUK HUKUM */
        .produk-stats {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            background: #e9ecef;
            transform: translateY(-3px);
        }
        
        .stat-year {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: #13547a;
            margin-bottom: 5px;
        }
        
        .stat-count {
            font-size: 2.5rem;
            font-weight: 700;
            color: #80d0c7;
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #555;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* DAFTAR PRODUK */
        .produk-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }
        
        .produk-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .produk-item:hover {
            background: #f8f9fa;
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 5px;
        }
        
        .produk-item:last-child {
            border-bottom: none;
        }
        
        .produk-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            flex: 1;
        }
        
        .produk-year {
            color: #80d0c7;
            font-weight: 700;
            margin-left: 20px;
        }
        
        /* INFO BOX */
        .info-box {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .info-box h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .info-box h4 i {
            margin-right: 10px;
        }
        
        .info-box p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* SIDEBAR MENU */
        .sidebar-menu {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .sidebar-menu h4 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu h4 i {
            color: #80d0c7;
            margin-right: 10px;
        }
        
        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu ul li {
            margin-bottom: 12px;
        }
        
        .sidebar-menu ul li:last-child {
            margin-bottom: 0;
        }
        
        .sidebar-menu ul li a {
            color: #555;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .sidebar-menu ul li a:hover {
            background: #f0f8ff;
            color: #13547a;
            transform: translateX(5px);
        }
        
        .sidebar-menu ul li a i {
            color: #80d0c7;
            margin-right: 10px;
            font-size: 0.9rem;
        }

        /* BUTTON STYLES */
        .custom-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .custom-btn:hover {
            background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }
        
        .custom-border-btn {
            background: transparent;
            color: #13547a;
            border: 2px solid #13547a;
            border-radius: 50px;
            padding: 10px 25px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .custom-border-btn:hover {
            background: #13547a;
            color: white;
            transform: translateY(-2px);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 30px 0 20px 0;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .main-content {
                padding: 30px 0 40px 0;
            }
            
            .section-title {
                font-size: 1.5rem;
                margin-bottom: 30px;
            }
            
            .alur-card,
            .produk-stats,
            .produk-list,
            .sidebar-menu,
            .info-box {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .produk-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .produk-year {
                margin-left: 0;
                align-self: flex-end;
            }
            
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <!-- BRAND DENGAN LOGO 398x136px -->
            <a class="navbar-brand" href="index.php">
                <!-- Ganti path sesuai lokasi file logo Anda -->
                <img src="images/logo-jdih.png" 
                     alt="JDIH DPRD Kota Surakarta" 
                     title="JDIH DPRD Kota Surakarta"
                     width="398" 
                     height="136">
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU - DI TENGAH -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <!-- BERANDA -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" 
                           href="index.php">
                            Beranda
                        </a>
                    </li>

                    <!-- TENTANG KAMI -->
                    <?php 
                    $tentangPages = ['Profil-JDIH', 'struktur-organisasi', 'alur-pengelolaan', 'sop-pengelola', 'dasar-hukum', 'surat-keputusan', 'video-profile'];
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo isDropdownActive($tentangPages, $currentQuery); ?>" 
                           href="#" 
                           id="dropdownTentangKami"
                           role="button" 
                           data-bs-toggle="dropdown">
                            Tentang Kami
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo $currentQuery === 'Profil-JDIH' ? 'active' : ''; ?>" 
                                   href="?page=Profil-JDIH">Profil JDIH</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'struktur-organisasi' ? 'active' : ''; ?>" 
                                   href="?page=struktur-organisasi">Struktur Organisasi</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'alur-pengelolaan' ? 'active' : ''; ?>" 
                                   href="?page=alur-pengelolaan">Alur Pengelolaan</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'sop-pengelola' ? 'active' : ''; ?>" 
                                   href="?page=sop-pengelola">SOP Pengelola</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'dasar-hukum' ? 'active' : ''; ?>" 
                                   href="?page=dasar-hukum">Dasar Hukum</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'surat-keputusan' ? 'active' : ''; ?>" 
                                   href="?page=surat-keputusan">Surat Keputusan</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'video-profile' ? 'active' : ''; ?>" 
                                   href="?page=video-profile">Video Profile</a></li>
                        </ul>
                    </li>

                    <!-- PERUNDANG-UNDANGAN -->
                    <?php 
                    $perundanganPages = ['keputusan-dprd', 'keputusan-pimpinan', 'keputusan-sekretariat', 'peraturan-dprd', 'perda', 'english-legal'];
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo isDropdownActive($perundanganPages, $currentQuery); ?>" 
                           href="#" 
                           id="dropdownPerundangan"
                           role="button" 
                           data-bs-toggle="dropdown">
                            Perundang-undangan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo $currentQuery === 'keputusan-dprd' ? 'active' : ''; ?>" 
                                   href="?page=keputusan-dprd">Keputusan DPRD</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'keputusan-pimpinan' ? 'active' : ''; ?>" 
                                   href="?page=keputusan-pimpinan">Keputusan Pimpinan</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'keputusan-sekretariat' ? 'active' : ''; ?>" 
                                   href="?page=keputusan-sekretariat">Keputusan Sekretariat</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'peraturan-dprd' ? 'active' : ''; ?>" 
                                   href="?page=peraturan-dprd">Peraturan DPRD</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'perda' ? 'active' : ''; ?>" 
                                   href="?page=perda">Peraturan Daerah</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'english-legal' ? 'active' : ''; ?>" 
                                   href="?page=english-legal">English Legal Product</a></li>
                        </ul>
                    </li>

                    <!-- MONOGRAFI HUKUM -->
                    <?php 
                    $monografiPages = ['pokok-pikiran', 'kajian-raperda', 'naskah-akademik', 'dokumen-langka', 'risalah', 'hasil-fasilitasi', 'karya-ilmiah', 'himpunan'];
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo isDropdownActive($monografiPages, $currentQuery); ?>" 
                           href="#" 
                           id="dropdownMonografi"
                           role="button" 
                           data-bs-toggle="dropdown">
                            Monografi Hukum
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo $currentQuery === 'pokok-pikiran' ? 'active' : ''; ?>" 
                                   href="?page=pokok-pikiran">Pokok Pikiran</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'kajian-raperda' ? 'active' : ''; ?>" 
                                   href="?page=kajian-raperda">Kajian Raperda</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'naskah-akademik' ? 'active' : ''; ?>" 
                                   href="?page=naskah-akademik">Naskah Akademik</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'dokumen-langka' ? 'active' : ''; ?>" 
                                   href="?page=dokumen-langka">Dokumen Langka</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'risalah' ? 'active' : ''; ?>" 
                                   href="?page=risalah">Risalah</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'hasil-fasilitasi' ? 'active' : ''; ?>" 
                                   href="?page=hasil-fasilitasi">Hasil Fasilitasi</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'hasil-harmonisasi' ? 'active' : ''; ?>" 
                                   href="?page=hasil-harmonisasi">Hasil Harmonisasi</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'karya-ilmiah' ? 'active' : ''; ?>" 
                                   href="?page=karya-ilmiah">Penulisan Karya Ilmiah</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'himpunan' ? 'active' : ''; ?>" 
                                   href="?page=himpunan">Himpunan Peraturan</a></li>
                        </ul>
                    </li>

                    <!-- ARTIKEL HUKUM -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentQuery === 'artikel-hukum' ? 'active' : ''; ?>" 
                           href="?page=artikel-hukum">
                            Artikel Hukum
                        </a>
                    </li>

                    <!-- YURISPRUDENSI -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentQuery === 'yurisprudensi' ? 'active' : ''; ?>" 
                           href="?page=yurisprudensi">
                            Yurisprudensi
                        </a>
                    </li>

                    <!-- INFORMASI -->
                    <?php 
                    $informasiPages = ['kegiatan', 'infografis', 'galeri', 'penghargaan', 'buku'];
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo isDropdownActive($informasiPages, $currentQuery); ?>" 
                           href="#" 
                           id="dropdownInformasi"
                           role="button" 
                           data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo $currentQuery === 'kegiatan' ? 'active' : ''; ?>" 
                                   href="?page=kegiatan">Kegiatan JDIH</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'infografis' ? 'active' : ''; ?>" 
                                   href="?page=infografis">Infografis & Videografis</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'galeri' ? 'active' : ''; ?>" 
                                   href="?page=galeri">Galeri Foto</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'penghargaan' ? 'active' : ''; ?>" 
                                   href="?page=penghargaan">Penghargaan</a></li>
                            <li><a class="dropdown-item <?php echo $currentQuery === 'buku' ? 'active' : ''; ?>" 
                                   href="?page=buku">Koleksi Buku</a></li>
                        </ul>
                    </li>

                    <!-- KONTAK KAMI -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentQuery === 'kontak-kami' ? 'active' : ''; ?>" 
                           href="?page=kontak-kami">
                            Kontak Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>