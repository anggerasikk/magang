<?php
// profil-jdih.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil JDIH - Sekretariat DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES SAMA DENGAN HOME -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">

    <style>
        /* OVERRIDE UNTUK HALAMAN INI SAJA */
        body {
            padding-top: 80px; /* Untuk fixed navbar */
        }
        
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
            opacity: 0.9;
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
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #13547a, #80d0c7);
        }

        /* PROFILE CARD */
        .profile-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            gap: 30px;
        }
        
        .profile-logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }
        
        .profile-info h2 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .profile-info p {
            color: var(--p-color);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .profile-content h3 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .profile-content p {
            color: var(--p-color);
            font-size: var(--p-font-size);
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: justify;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, rgba(19, 84, 122, 0.1) 0%, rgba(128, 208, 199, 0.1) 100%);
            border-left: 4px solid #13547a;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 var(--border-radius-small) var(--border-radius-small) 0;
        }
        
        .highlight-box p {
            margin: 0;
            font-style: italic;
            color: #13547a;
            font-weight: 500;
        }

        /* STATISTIK PRODUK HUKUM */
        .stats-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .stats-title {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .stats-title i {
            color: #80d0c7;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .stats-title h3 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1.3rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px 15px;
            background: #f8f9fa;
            border-radius: var(--border-radius-small);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-item:hover {
            background: #e9ecef;
            transform: translateY(-5px);
        }
        
        .stat-year {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            color: #13547a;
            margin-bottom: 5px;
        }
        
        .stat-count {
            font-size: 2rem;
            font-weight: 700;
            color: #80d0c7;
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .stat-count span {
            font-size: 1rem;
            color: #666;
        }
        
        .stat-label {
            color: var(--p-color);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* BERITA TERBARU */
        .news-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .news-list {
            margin-top: 20px;
        }
        
        .news-item {
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            gap: 20px;
            align-items: flex-start;
            transition: all 0.3s ease;
        }
        
        .news-item:hover {
            background: #f8f9fa;
            padding-left: 15px;
            padding-right: 15px;
            border-radius: 5px;
        }
        
        .news-item:last-child {
            border-bottom: none;
        }
        
        .news-date {
            min-width: 100px;
            text-align: center;
            background: #f8f9fa;
            padding: 10px;
            border-radius: var(--border-radius-small);
        }
        
        .news-date .day {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #13547a;
            display: block;
        }
        
        .news-date .month {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .news-content h4 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .news-content p {
            color: var(--p-color);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        /* SIDEBAR MENU */
        .sidebar-menu {
            background: white;
            border-radius: var(--border-radius-medium);
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
            color: var(--p-color);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: var(--border-radius-small);
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

        /* CONTACT INFO */
        .contact-info {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .contact-info h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .contact-info h4 i {
            margin-right: 10px;
        }
        
        .contact-detail {
            margin-bottom: 20px;
        }
        
        .contact-detail:last-child {
            margin-bottom: 0;
        }
        
        .contact-detail h5 {
            color: white;
            font-size: 1rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .contact-detail p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        .contact-detail a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .contact-detail a:hover {
            color: white;
            text-decoration: underline;
        }

        /* PORTAL LINKS */
        .portal-links {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }
        
        .portal-link-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: var(--border-radius-small);
            margin-bottom: 10px;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        
        .portal-link-item:last-child {
            margin-bottom: 0;
        }
        
        .portal-link-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .portal-link-item i {
            color: #80d0c7;
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .portal-link-item span {
            color: #13547a;
            font-weight: 500;
            flex: 1;
        }
        
        .portal-link-item a {
            color: var(--p-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
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
            
            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .profile-logo {
                width: 100px;
                height: 100px;
                font-size: 1.5rem;
            }
            
            .profile-card,
            .stats-container,
            .news-container,
            .sidebar-menu,
            .contact-info,
            .portal-links {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .news-item {
                flex-direction: column;
                gap: 15px;
            }
            
            .news-date {
                min-width: auto;
                width: 100px;
                align-self: center;
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
            
            .profile-content h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR SAMA DENGAN HOME -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <!-- BRAND -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <span class="fw-bold">JDIH DPRD</span>
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU SAMA DENGAN HOME -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdownTentangKami"
                            role="button" data-bs-toggle="dropdown">
                            Tentang Kami
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=profil-jdih">Profil JDIH</a></li>
                            <li><a class="dropdown-item" href="?page=struktur-organisasi">Struktur Organisasi</a></li>
                            <li><a class="dropdown-item" href="?page=alur-pengelolaan">Alur Pengelolaan Produk Hukum</a></li>
                            <li><a class="dropdown-item" href="?page=sop-pengelola">SOP Pengelola JDIH DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=dasar-hukum">Dasar Hukum JDIH Sekretariat DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=surat-keputusan">Surat Keputusan Pengelola JDIH Sekretariat DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=video-profile">Video Profile JDIH</a></li>
                        </ul>
                    </li>

                    <!-- PERUNDANGAN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownPerundangan"
                            role="button" data-bs-toggle="dropdown">
                            Perundang-undangan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=keputusan-dprd">Keputusan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=keputusan-pimpinan">Keputusan Pimpinan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=keputusan-sekretariat">Keputusan Sekretariat DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=peraturan-dprd">Peraturan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=perda">Peraturan Daerah</a></li>
                            <li><a class="dropdown-item" href="?page=english-legal">English Legal Product</a></li>
                        </ul>
                    </li>

                    <!-- MONOGRAFI -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMonografi"
                            role="button" data-bs-toggle="dropdown">
                            Monografi Hukum
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=kajian-raperda">Kajian Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=naskah-akademik">Naskah Akademik</a></li>
                            <li><a class="dropdown-item" href="?page=dokumen-langka">Dokumen Langka</a></li>
                            <li><a class="dropdown-item" href="?page=risalah">Risalah</a></li>
                            <li><a class="dropdown-item" href="?page=fasilitasi">Hasil Fasilitasi Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=karya-ilmiah">Penulisan Karya Ilmiah</a></li>
                            <li><a class="dropdown-item" href="?page=himpunan">Himpunan Peraturan</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?page=artikel-hukum">Artikel Hukum</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?page=yurisprudensi">Yurisprudensi</a>
                    </li>

                    <!-- INFORMASI -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownInformasi"
                            role="button" data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=kegiatan">Kegiatan JDIH</a></li>
                            <li><a class="dropdown-item" href="?page=infografis">Infografis & Videografis</a></li>
                            <li><a class="dropdown-item" href="?page=galeri">Galeri Foto</a></li>
                            <li><a class="dropdown-item" href="?page=penghargaan">Penghargaan</a></li>
                            <li><a class="dropdown-item" href="?page=buku">Koleksi Buku</a></li>
                        </ul>
                    </li>

                    <!-- KONTAK -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownKontak"
                            role="button" data-bs-toggle="dropdown">
                            Kontak & Saran
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=kontak">Form Kontak</a></li>
                            <li><a class="dropdown-item" href="?page=saran">Saran & Masukan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-white">PROFIL JDIH</h1>
                    <p class="subtitle">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profil JDIH</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <div class="row">
                
                <!-- KONTEN UTAMA -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    
                    <!-- PROFILE JDIH -->
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-logo">
                                JDIH
                            </div>
                            <div class="profile-info">
                                <h2>JDIH DPRD Kota Surakarta</h2>
                                <p><strong>Jaringan Dokumentasi dan Informasi Hukum</strong></p>
                                <p>Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                            </div>
                        </div>
                        
                        <div class="profile-content">
                            <h3>Tentang JDIH</h3>
                            <p>
                                Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                                merupakan unit kerja yang bertanggung jawab dalam pengelolaan, pendokumentasian, 
                                dan penyebarluasan informasi hukum yang dihasilkan oleh DPRD Kota Surakarta.
                            </p>
                            
                            <div class="highlight-box">
                                <p>
                                    "JDIH membawa hukum langsung dalam genggaman Anda! Sebagai sarana penyerbarluasan 
                                    Produk Hukum berbasis Online untuk publikasi dan akses cepat ke informasi hukum."
                                </p>
                            </div>
                            
                            <p>
                                Dibentuk berdasarkan kebutuhan akan sistem pengelolaan dokumen hukum yang terintegrasi 
                                dan mudah diakses oleh masyarakat, akademisi, penegak hukum, dan stakeholders lainnya. 
                                JDIH DPRD Kota Surakarta berkomitmen untuk menyediakan informasi hukum yang akurat, 
                                terkini, dan mudah diakses.
                            </p>
                            
                            <h3>Visi</h3>
                            <p>
                                Menjadi pusat informasi hukum yang terpercaya, terintegrasi, dan mudah diakses 
                                bagi seluruh masyarakat Kota Surakarta dan stakeholders terkait.
                            </p>
                            
                            <h3>Misi</h3>
                            <p>
                                1. Mengumpulkan dan mendokumentasikan seluruh produk hukum DPRD Kota Surakarta<br>
                                2. Mengelola database hukum yang akurat dan terupdate<br>
                                3. Menyebarluaskan informasi hukum kepada masyarakat secara mudah dan cepat<br>
                                4. Memberikan pelayanan informasi hukum yang profesional<br>
                                5. Mendorong transparansi dan akuntabilitas dalam proses legislasi
                            </p>
                            
                            <h3>Fungsi dan Tugas</h3>
                            <p>
                                JDIH DPRD Kota Surakarta memiliki fungsi utama sebagai pusat pengelolaan informasi 
                                hukum yang meliputi: pengumpulan, pengolahan, penyimpanan, dan penyebarluasan 
                                informasi hukum. Tugas utama meliputi dokumentasi produk hukum, pembuatan database, 
                                pelayanan informasi, dan publikasi produk hukum melalui berbagai media.
                            </p>
                            
                            <h3>Sejarah Singkat</h3>
                            <p>
                                JDIH DPRD Kota Surakarta secara resmi dibentuk pada tahun 2014 melalui Surat Keputusan 
                                Sekretaris DPRD Kota Surakarta. Sejak itu, JDIH terus berkembang dengan mengadopsi 
                                teknologi informasi untuk meningkatkan pelayanan. Pada tahun 2019, diluncurkan website 
                                JDIH untuk memudahkan akses publik terhadap informasi hukum.
                            </p>
                        </div>
                    </div>
                    
                    <!-- STATISTIK PRODUK HUKUM -->
                    <div class="stats-container">
                        <div class="stats-title">
                            <i class="bi bi-bar-chart"></i>
                            <h3>Statistik Produk Hukum Terkelola</h3>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-year">2025</div>
                                <div class="stat-count">79</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2024</div>
                                <div class="stat-count">68</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2023</div>
                                <div class="stat-count">89</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2022</div>
                                <div class="stat-count">103</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2021</div>
                                <div class="stat-count">22</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2020</div>
                                <div class="stat-count">52</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2019</div>
                                <div class="stat-count">18</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">Total</div>
                                <div class="stat-count">456</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- BERITA TERBARU -->
                    <div class="news-container">
                        <h3 class="section-title">Berita Terbaru</h3>
                        
                        <div class="news-list">
                            <div class="news-item">
                                <div class="news-date">
                                    <span class="day">10</span>
                                    <span class="month">Jan 2024</span>
                                </div>
                                <div class="news-content">
                                    <h4>Peluncuran Website JDIH Versi 3.0</h4>
                                    <p>JDIH DPRD Kota Surakarta meluncurkan website terbaru dengan fitur yang lebih lengkap dan user-friendly untuk memudahkan akses informasi hukum.</p>
                                </div>
                            </div>
                            
                            <div class="news-item">
                                <div class="news-date">
                                    <span class="day">15</span>
                                    <span class="month">Des 2023</span>
                                </div>
                                <div class="news-content">
                                    <h4>Workshop Pengelolaan Dokumen Hukum Digital</h4>
                                    <p>Tim JDIH mengadakan workshop tentang pengelolaan dokumen hukum digital untuk meningkatkan kapasitas SDM dalam pengelolaan informasi hukum.</p>
                                </div>
                            </div>
                            
                            <div class="news-item">
                                <div class="news-date">
                                    <span class="day">20</span>
                                    <span class="month">Nov 2023</span>
                                </div>
                                <div class="news-content">
                                    <h4>MoU dengan Universitas Sebelas Maret</h4>
                                    <p>JDIH DPRD Kota Surakarta menandatangani MoU dengan UNS untuk kerjasama dalam penelitian dan pengembangan sistem informasi hukum.</p>
                                </div>
                            </div>
                            
                            <div class="news-item">
                                <div class="news-date">
                                    <span class="day">05</span>
                                    <span class="month">Okt 2023</span>
                                </div>
                                <div class="news-content">
                                    <h4>Penghargaan JDIH Terbaik Tingkat Provinsi</h4>
                                    <p>JDIH DPRD Kota Surakarta menerima penghargaan sebagai JDIH terbaik tingkat Provinsi Jawa Tengah tahun 2023.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU TERKAIT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul>
                            <li>
                                <a href="?page=struktur-organisasi">
                                    <i class="bi bi-diagram-3"></i>
                                    Struktur Organisasi
                                </a>
                            </li>
                            <li>
                                <a href="?page=alur-pengelolaan">
                                    <i class="bi bi-diagram-2"></i>
                                    Alur Pengelolaan
                                </a>
                            </li>
                            <li>
                                <a href="?page=sop-pengelola">
                                    <i class="bi bi-clipboard-check"></i>
                                    SOP Pengelola JDIH
                                </a>
                            </li>
                            <li>
                                <a href="?page=dasar-hukum">
                                    <i class="bi bi-journal-text"></i>
                                    Dasar Hukum JDIH
                                </a>
                            </li>
                            <li>
                                <a href="?page=visi-misi">
                                    <i class="bi bi-bullseye"></i>
                                    Visi & Misi
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- CONTACT INFO -->
                    <div class="contact-info">
                        <h4><i class="bi bi-telephone"></i> Kontak Informasi</h4>
                        
                        <div class="contact-detail">
                            <h5>Alamat</h5>
                            <p>
                                Sekretariat DPRD Kota Surakarta<br>
                                Jl. Jenderal Sudirman No. 2<br>
                                Kecamatan Pasar Kliwon<br>
                                Kota Surakarta, Jawa Tengah 57111
                            </p>
                        </div>
                        
                        <div class="contact-detail">
                            <h5>Telepon</h5>
                            <p>
                                <a href="tel:+62271723456">(0271) 723456</a><br>
                                Ext. 123 (JDIH)
                            </p>
                        </div>
                        
                        <div class="contact-detail">
                            <h5>Email</h5>
                            <p>
                                <a href="mailto:jdih@dprd-surakarta.go.id">jdih@dprd-surakarta.go.id</a>
                            </p>
                        </div>
                        
                        <div class="contact-detail">
                            <h5>Jam Operasional</h5>
                            <p>
                                Senin - Jumat: 08:00 - 16:00 WIB<br>
                                Sabtu - Minggu: Tutup
                            </p>
                        </div>
                    </div>

                    <!-- PORTAL LINKS -->
                    <div class="portal-links">
                        <h4><i class="bi bi-link"></i> Link Portal</h4>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>DPRD Kota Surakarta</span>
                            <a href="https://dprd-surakarta.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>Pemerintah Kota Surakarta</span>
                            <a href="https://surakarta.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>JDIH Nasional</span>
                            <a href="https://jdih.kemenkumham.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>JDIH Provinsi Jawa Tengah</span>
                            <a href="https://jdih.jatengprov.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>Sistem Informasi Hukum</span>
                            <a href="https://peraturan.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>Mahkamah Konstitusi</span>
                            <a href="https://mkri.id" target="_blank"></a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download</h4>
                        <p class="mb-3">Download dokumen profil JDIH:</p>
                        <a href="downloads/profil-jdih.pdf" download class="btn custom-btn w-100 mb-2">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Profil JDIH (PDF)
                        </a>
                        <a href="downloads/brosur-jdih.pdf" download class="btn custom-border-btn w-100">
                            <i class="bi bi-file-earmark-text"></i>
                            Brosur JDIH
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER SAMA DENGAN TEMPLATE -->
    <footer class="site-footer section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-12 mb-4 pb-2">
                    <a class="navbar-brand mb-2" href="index.php">
                        <i class="bi-back"></i>
                        <span>JDIH DPRD</span>
                    </a>
                </div>

                <div class="col-lg-3 col-md-4 col-6">
                    <h6 class="site-footer-title mb-3">Tautan Cepat</h6>

                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="index.php" class="site-footer-link">Beranda</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="?page=profil-jdih" class="site-footer-link">Profil JDIH</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="?page=perda" class="site-footer-link">Peraturan Daerah</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="?page=kontak" class="site-footer-link">Kontak</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-6 mb-4 mb-lg-0">
                    <h6 class="site-footer-title mb-3">Kontak</h6>

                    <p class="text-white d-flex mb-1">
                        <a href="tel:+62271723456" class="site-footer-link">
                            (0271) 723456
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <a href="mailto:jdih@dprd-surakarta.go.id" class="site-footer-link">
                            jdih@dprd-surakarta.go.id
                        </a>
                    </p>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0 ms-auto">
                    <p class="copyright-text mt-lg-5 mt-4">
                        Copyright © 2025 JDIH DPRD Kota Surakarta. All rights reserved.
                        <br><br>
                        <a rel="nofollow" href="https://dprd-surakarta.go.id" target="_blank">DPRD Kota Surakarta</a>
                    </p>
                </div>

            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES SAMA DENGAN HOME -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>

</body>
</html>