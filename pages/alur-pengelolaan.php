<?php
// alur-pengelolaan.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Alur Pengelolaan Produk Hukum - JDIH DPRD Kota Surakarta</title>
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
            border-radius: var(--border-radius-medium);
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
            color: var(--p-color);
            font-size: var(--p-font-size);
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
            border-radius: var(--border-radius-medium);
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
            border-radius: var(--border-radius-small);
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
            color: var(--p-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* DAFTAR PRODUK */
        .produk-list {
            background: white;
            border-radius: var(--border-radius-medium);
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
            border-radius: var(--border-radius-medium);
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
                        <a class="nav-link" href="?page=artikel">Artikel Hukum</a>
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
                    <h1 class="text-white">ALUR PENGELOLAAN PRODUK HUKUM</h1>
                    <p class="subtitle">JDIH Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Alur Pengelolaan</li>
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
                    
                    <!-- ALUR PENGELOLAAN -->
                    <h2 class="section-title">Alur Pengelolaan Produk Hukum</h2>
                    
                    <div class="alur-container">
                        <div class="alur-timeline"></div>
                        
                        <!-- Step 1 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">1</div>
                                <h4>Persiapan dan Pengumpulan</h4>
                                <p>Tim JDIH mengumpulkan seluruh produk hukum yang dihasilkan oleh DPRD Kota Surakarta, termasuk Peraturan Daerah, Keputusan DPRD, dan dokumen hukum lainnya.</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">2</div>
                                <h4>Verifikasi dan Validasi</h4>
                                <p>Produk hukum yang terkumpul diverifikasi keabsahan dan keasliannya. Tim melakukan validasi terhadap nomor, tanggal, dan substansi dokumen.</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">3</div>
                                <h4>Klasifikasi dan Katalogisasi</h4>
                                <p>Dokumen diklasifikasikan berdasarkan jenis, tahun, dan bidang hukum. Setiap dokumen diberi kode katalog untuk memudahkan pencarian dan pengelolaan.</p>
                            </div>
                        </div>
                        
                        <!-- Step 4 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">4</div>
                                <h4>Digitalisasi dan Penyimpanan</h4>
                                <p>Produk hukum didigitalisasi dalam format elektronik dan disimpan dalam database yang aman. Backup data dilakukan secara berkala.</p>
                            </div>
                        </div>
                        
                        <!-- Step 5 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">5</div>
                                <h4>Publikasi dan Diseminasi</h4>
                                <p>Produk hukum dipublikasikan melalui website JDIH dan media lainnya. Informasi disebarluaskan kepada masyarakat dan stakeholders terkait.</p>
                            </div>
                        </div>
                        
                        <!-- Step 6 -->
                        <div class="alur-step">
                            <div class="alur-card">
                                <div class="step-number">6</div>
                                <h4>Pemeliharaan dan Update</h4>
                                <p>Database JDIH dipelihara secara berkala. Produk hukum yang telah dicabut atau diubah diupdate statusnya dalam sistem.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- STATISTIK PRODUK HUKUM -->
                    <h2 class="section-title">Statistik Produk Hukum</h2>
                    
                    <div class="produk-stats">
                        <div class="info-box">
                            <h4><i class="bi bi-info-circle"></i> Total Produk Hukum Terkelola</h4>
                            <p>Hingga tahun 2025, JDIH DPRD Kota Surakarta telah mengelola 256 produk hukum yang terdiri dari berbagai jenis dan bidang.</p>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-year">2025</div>
                                <div class="stat-count">24</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2024</div>
                                <div class="stat-count">28</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2023</div>
                                <div class="stat-count">32</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-year">2022</div>
                                <div class="stat-count">35</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DAFTAR PRODUK HUKUM -->
                    <h2 class="section-title">Daftar Produk Hukum</h2>
                    
                    <div class="produk-list">
                        <div class="produk-item">
                            <div class="produk-title">Peraturan Daerah Kota Surakarta Nomor 1 Tahun 2025</div>
                            <div class="produk-year">2025</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Keputusan DPRD Kota Surakarta Nomor 12/DPRD/2025</div>
                            <div class="produk-year">2025</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Peraturan DPRD Kota Surakarta Nomor 2 Tahun 2024</div>
                            <div class="produk-year">2024</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Keputusan Pimpinan DPRD Nomor 05/PIMP/2024</div>
                            <div class="produk-year">2024</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Peraturan Daerah Kota Surakarta Nomor 8 Tahun 2023</div>
                            <div class="produk-year">2023</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Keputusan DPRD Kota Surakarta Nomor 20/DPRD/2023</div>
                            <div class="produk-year">2023</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Peraturan DPRD Kota Surakarta Nomor 3 Tahun 2022</div>
                            <div class="produk-year">2022</div>
                        </div>
                        <div class="produk-item">
                            <div class="produk-title">Keputusan Sekretariat DPRD Nomor 15/Sek/2022</div>
                            <div class="produk-year">2022</div>
                        </div>
                    </div>
                    
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- INFO BOX -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-info-circle"></i> Informasi Alur</h4>
                        <p class="mb-0">
                            Alur pengelolaan produk hukum ini dirancang untuk memastikan 
                            bahwa seluruh dokumen hukum yang dihasilkan DPRD Kota Surakarta 
                            dapat diakses, dikelola, dan dipelihara dengan baik.
                        </p>
                    </div>

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
                                <a href="?page=perda">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Peraturan Daerah
                                </a>
                            </li>
                            <li>
                                <a href="?page=keputusan-dprd">
                                    <i class="bi bi-file-earmark-check"></i>
                                    Keputusan DPRD
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- DOWNLOAD BOX -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download</h4>
                        <p class="mb-3">Download dokumen terkait alur pengelolaan:</p>
                        <a href="downloads/alur-pengelolaan.pdf" download class="btn custom-btn w-100 mb-2">
                            <i class="bi bi-file-earmark-pdf"></i>
                            PDF Alur Pengelolaan
                        </a>
                        <a href="downloads/prosedur-jdih.pdf" download class="btn custom-border-btn w-100">
                            <i class="bi bi-file-earmark-text"></i>
                            Prosedur Operasional
                        </a>
                    </div>
                    
                    <!-- INFO KONTAK -->
                    <div class="info-box">
                        <h4><i class="bi bi-telephone"></i> Butuh Bantuan?</h4>
                        <p class="mb-2">Untuk informasi lebih lanjut tentang alur pengelolaan produk hukum, silakan hubungi:</p>
                        <p class="mb-0">
                            <strong>Tim JDIH DPRD</strong><br>
                            Email: jdih@dprd-surakarta.go.id<br>
                            Telepon: (0271) 723456 ext. 123
                        </p>
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
                            <a href="?page=alur-pengelolaan" class="site-footer-link">Alur Pengelolaan</a>
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