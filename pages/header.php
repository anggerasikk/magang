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
        /* OVERRIDE UNTUK NAVBAR - LAYOUT LOGO KIRI */
        body {
            padding-top: 90px;
        }
        
        /* NAVBAR POSITIONING - SINGLE ROW LAYOUT */
        .navbar-custom {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }
        
        /* CONTAINER MENJADI ROW DENGAN SPASI PROPORSIONAL */
        .navbar-custom .container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            position: relative;
        }
        
        /* LOGO DI KIRI - UKURAN TETAP */
        .navbar-brand {
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
            height: 60px;
            flex-shrink: 0;
            margin-right: 20px;
        }
        
        .navbar-brand img {
            height: 100%;
            width: auto;
            max-width: 180px;
            object-fit: contain;
        }
        
        /* TOGGLER (Mobile only) */
        .navbar-toggler {
            padding: 3px 6px;
            border-width: 1px;
            display: none;
        }
        
        /* NAVBAR COLLAPSE - MENJADI FLEX CONTAINER UTAMA */
        .navbar-collapse {
            display: flex !important;
            flex: 1;
            justify-content: center;
            align-items: center;
        }
        
        /* NAV MENU LAYOUT - FLEXIBLE */
        .navbar-nav {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            padding: 0;
            max-width: 75%;
        }
        
        .navbar-nav .nav-item {
            margin: 0 2px;
            text-align: center;
        }
        
        /* NAV LINK SIZE - LEBIH KECIL UNTUK MUAT */
        .navbar-nav .nav-link {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 0.7rem;
            padding: 5px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        /* NAV LINK ACTIVE STATE */
        .navbar-nav .nav-link.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white !important;
        }
        
        /* DROPDOWN MENU SIZE */
        .dropdown-menu {
            min-width: 180px;
            padding: 5px 0;
            margin-top: 5px;
            font-size: 0.8rem;
        }
        
        .dropdown-item {
            font-size: 0.75rem;
            padding: 6px 12px;
        }
        
        /* DROPDOWN ITEM ACTIVE STATE */
        .dropdown-item.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
        }
        
        /* DROPDOWN ACTIVE STATE */
        .nav-link.dropdown-toggle.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white !important;
        }
        
        /* TOMBOL LOGIN - DI SEBELAH KANAN */
        .btn-login-container {
            flex-shrink: 0;
            margin-left: 15px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 8px 18px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.7rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 8px rgba(19, 84, 122, 0.2);
            white-space: nowrap;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0f4266 0%, #6bc0b7 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }
        
        .btn-login i {
            font-size: 0.8rem;
        }
        
        /* RESPONSIVE ADJUSTMENTS */
        @media (min-width: 1200px) {
            body {
                padding-top: 95px;
            }
            
            .navbar-brand {
                height: 65px;
                margin-right: 30px;
            }
            
            .navbar-brand img {
                max-width: 200px;
            }
            
            .navbar-nav .nav-link {
                font-size: 0.72rem;
                padding: 6px 9px;
            }
            
            .navbar-nav {
                max-width: 80%;
            }
            
            .navbar-nav .nav-item {
                margin: 0 3px;
            }
        }
        
        @media (min-width: 1400px) {
            body {
                padding-top: 100px;
            }
            
            .navbar-brand {
                height: 70px;
            }
            
            .navbar-brand img {
                max-width: 220px;
            }
            
            .navbar-nav .nav-link {
                font-size: 0.75rem;
                padding: 6px 10px;
            }
            
            .navbar-nav .nav-item {
                margin: 0 4px;
            }
            
            .navbar-nav {
                max-width: 82%;
            }
        }
        
        @media (min-width: 1600px) {
            .navbar-nav .nav-link {
                font-size: 0.8rem;
                padding: 7px 12px;
            }
            
            .navbar-nav {
                max-width: 85%;
            }
        }
        
        /* MOBILE MENU */
        @media (max-width: 991.98px) {
            body {
                padding-top: 80px;
            }
            
            .navbar-custom {
                padding: 8px 0;
            }
            
            /* TAMPILKAN TOGGLER DI MOBILE */
            .navbar-toggler {
                display: block;
                order: 2;
                margin-left: auto;
            }
            
            /* SEMBUNYIKAN MENU DI MOBILE */
            .navbar-collapse {
                display: none !important;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                z-index: 1000;
                padding: 15px;
                border-radius: 0 0 10px 10px;
            }
            
            .navbar-collapse.show {
                display: flex !important;
            }
            
            /* NAV MENU VERTICAL DI MOBILE */
            .navbar-nav {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                max-width: 100%;
                margin: 0;
            }
            
            .navbar-nav .nav-item {
                margin: 2px 0;
                width: 100%;
            }
            
            .navbar-nav .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
                text-align: left;
                width: 100%;
            }
            
            /* DROPDOWN DI MOBILE */
            .dropdown-menu {
                margin-top: 2px;
                margin-left: 20px;
                width: calc(100% - 40px);
                border: none;
                box-shadow: none;
            }
            
            .dropdown-item {
                font-size: 0.85rem;
                padding: 8px 25px;
            }
            
            /* LOGIN BUTTON DI MOBILE */
            .btn-login-container {
                width: 100%;
                margin: 15px 0 5px 0;
            }
            
            .btn-login {
                width: 100%;
                justify-content: center;
                padding: 10px 20px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 75px;
            }
            
            .navbar-brand {
                height: 50px;
            }
            
            .navbar-brand img {
                max-width: 160px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding-top: 70px;
            }
            
            .navbar-brand {
                height: 45px;
            }
            
            .navbar-brand img {
                max-width: 140px;
            }
        }
        
        @media (max-width: 400px) {
            body {
                padding-top: 65px;
            }
            
            .navbar-brand {
                height: 40px;
            }
            
            .navbar-brand img {
                max-width: 120px;
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

                    <!-- TOMBOL LOGIN - DI LUAR UL NAVBAR -->
                    <a href="http://localhost/jdihcilacap_pasang_2026/panel_control/index.php" 
                    class="btn-login" 
                    target="_blank">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </div>
            </div>
        </nav>