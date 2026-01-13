<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Penulisan Karya Ilmiah - Monografi Hukum - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
    
    <style>
        /* OVERRIDE UNTUK HALAMAN MONOGRAFI */
        body {
            padding-top: 80px;
        }
        
        /* HEADER SECTION -->
        .page-header {
            background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
            padding: 40px 0 30px 0;
            margin-top: 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.1;
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

        /* MONOGRAFI CONTENT */
        .monografi-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
        }
        
        .monografi-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f8ff;
        }
        
        .monografi-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .monografi-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        /* SEARCH BOX -->
        .search-monografi {
            background: #f8f9fa;
            border-radius: var(--border-radius-medium);
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        
        .search-form .input-group {
            margin-bottom: 20px;
        }
        
        .search-form input {
            border: 2px solid #13547a;
            border-right: none;
            height: 50px;
            font-size: 1rem;
        }
        
        .search-form input:focus {
            box-shadow: 0 0 0 0.2rem rgba(19, 84, 122, 0.25);
        }
        
        .search-form .btn-search {
            background: #13547a;
            color: white;
            border: 2px solid #13547a;
            height: 50px;
            padding: 0 25px;
            font-weight: 600;
        }
        
        .search-form .btn-search:hover {
            background: #0d3a58;
            border-color: #0d3a58;
        }
        
        .adv-search-link {
            color: #13547a;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .adv-search-link:hover {
            color: #80d0c7;
            gap: 8px;
        }

        /* DOCUMENT LIST */
        .document-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .document-item {
            background: white;
            border-radius: var(--border-radius-small);
            padding: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        
        .document-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: #80d0c7;
        }
        
        .document-icon {
            width: 60px;
            height: 60px;
            background: #f0f8ff;
            border-radius: var(--border-radius-small);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #13547a;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .document-content {
            flex: 1;
        }
        
        .document-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .document-title a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .document-title a:hover {
            color: #80d0c7;
        }
        
        .document-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .meta-item i {
            color: #80d0c7;
        }
        
        .document-excerpt {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .document-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-download {
            background: #13547a;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: var(--border-radius-small);
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-download:hover {
            background: #0d3a58;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-preview {
            background: #f0f8ff;
            color: #13547a;
            border: 1px solid #13547a;
            padding: 8px 15px;
            border-radius: var(--border-radius-small);
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-preview:hover {
            background: #13547a;
            color: white;
            transform: translateY(-2px);
        }

        /* TYPE BADGE */
        .type-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .type-jurnal {
            background: #d4edda;
            color: #155724;
        }
        
        .type-skripsi {
            background: #cce5ff;
            color: #004085;
        }
        
        .type-tesis {
            background: #fff3cd;
            color: #856404;
        }
        
        .type-artikel {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* PAGINATION */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .pagination {
            display: flex;
            gap: 5px;
        }
        
        .page-item .page-link {
            border: none;
            background: #f8f9fa;
            color: #13547a;
            border-radius: var(--border-radius-small);
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .page-item.active .page-link {
            background: #13547a;
            color: white;
        }
        
        .page-item .page-link:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }

        /* SIDEBAR */
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
        
        .sidebar-menu ul li.active a {
            background: #f0f8ff;
            color: #13547a;
            font-weight: 600;
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

        /* STATS BOX */
        .stats-box {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
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
            
            .monografi-container {
                padding: 30px;
            }
            
            .document-item {
                flex-direction: column;
                gap: 15px;
            }
            
            .document-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .document-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .document-actions {
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .monografi-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .search-form input,
            .search-form .btn-search {
                height: 45px;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR -->
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

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownTentangKami"
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
                            <li><a class="dropdown-item" href="?page=visi-misi">Visi & Misi</a></li>
                            <li><a class="dropdown-item" href="?page=video-profil">Video Profil JDIH</a></li>
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
                            <li><a class="dropdown-item" href="?page=keputusan-sekretaris">Keputusan Sekretaris DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=peraturan-dprd">Peraturan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=perda">Peraturan Daerah</a></li>
                            <li><a class="dropdown-item" href="?page=english-legal">English Legal Product</a></li>
                        </ul>
                    </li>

                    <!-- MONOGRAFI -->
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMonografi"
                            role="button" data-bs-toggle="dropdown">
                            Monografi Hukum
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=pokok-pikiran-dprd">Pokok Pikiran DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=kajian-raperda">Kajian Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=naskah-akademik">Naskah Akademik</a></li>
                            <li><a class="dropdown-item" href="?page=hasil-fasilitasi">Hasil Fasilitasi Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=risalah">Risalah</a></li>
                            <li><a class="dropdown-item" href="?page=hasil-harmonisasi">Hasil Harmonisasi Raperda</a></li>
                            <li><a class="dropdown-item active" href="?page=karya-ilmiah">Penulisan Karya Ilmiah</a></li>
                            <li><a class="dropdown-item" href="?page=himpunan">Himpunan Peraturan</a></li>
                        </ul>
                    </li>

                    <!-- ARTIKEL HUKUM -->
                    <li class="nav-item">
                        <a class="nav-link" href="?page=artikel">Artikel Hukum</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?page=yurisprudensi">Yurisprudensi</a></li>

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
                    <h1 class="text-white">MONOGRAFI HUKUM</h1>
                    <p class="subtitle">Penulisan Karya Ilmiah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Penulisan Karya Ilmiah</li>
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
                    
                    <!-- HEADER MONOGRAFI -->
                    <div class="monografi-container">
                        <div class="monografi-header">
                            <div class="monografi-icon">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <h2 class="section-title">Penulisan Karya Ilmiah</h2>
                            <p class="monografi-description">
                                Kumpulan karya ilmiah yang dihasilkan oleh anggota DPRD, staf ahli, akademisi, 
                                dan peneliti terkait dengan aspek hukum, legislasi, dan pemerintahan daerah 
                                Kota Surakarta.
                            </p>
                        </div>
                        
                        <!-- PENCARIAN -->
                        <div class="search-monografi">
                            <h4 class="mb-4">Cari Karya Ilmiah</h4>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci karya ilmiah..." 
                                           aria-label="Search" aria-describedby="button-search">
                                    <button class="btn btn-search" type="submit" id="button-search">
                                        <i class="bi bi-search"></i> CARI
                                    </button>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="adv-search-link">
                                        <i class="bi bi-sliders"></i> Pencarian Lanjutan
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- STATISTIK -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">156</div>
                                    <div class="stats-label">Total Karya</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">12</div>
                                    <div class="stats-label">Tahun 2024</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">45</div>
                                    <div class="stats-label">Jurnal Nasional</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">68</div>
                                    <div class="stats-label">Skripsi & Tesis</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- DAFTAR DOKUMEN -->
                        <h3 class="section-title">Karya Ilmiah Terbaru</h3>
                        
                        <div class="document-list">
                            <!-- Dokumen 1 -->
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="document-content">
                                    <span class="type-badge type-jurnal">JURNAL NASIONAL</span>
                                    <h3 class="document-title">
                                        <a href="#">Analisis Efektivitas Pengawasan DPRD Kota Surakarta terhadap Pelaksanaan APBD 2023</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 30 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Penulis: Dr. H. Bambang Sutrisno, SH., MH.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-building"></i>
                                            <span>Jurnal: Jurnal Legislasi Indonesia</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Pengawasan Anggaran</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Penelitian tentang efektivitas mekanisme pengawasan DPRD terhadap pelaksanaan APBD dengan pendekatan mixed methods dan analisis regresi...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-link-45deg"></i> Lihat di Google Scholar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 2 -->
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="document-content">
                                    <span class="type-badge type-tesis">TESIS</span>
                                    <h3 class="document-title">
                                        <a href="#">Implementasi Prinsip Good Governance dalam Proses Legislasi di DPRD Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 25 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Penulis: Sri Mulyani, S.H., M.H.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-building"></i>
                                            <span>Universitas: Universitas Sebelas Maret</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Hukum Tata Negara</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Tesis magister yang mengkaji implementasi prinsip transparansi, akuntabilitas, dan partisipasi dalam proses pembentukan peraturan daerah...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 3 -->
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="document-content">
                                    <span class="type-badge type-skripsi">SKRIPSI</span>
                                    <h3 class="document-title">
                                        <a href="#">Peran DPRD dalam Perlindungan Hak-hak Penyandang Disabilitas di Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 20 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Penulis: Andi Pratama, S.H.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-building"></i>
                                            <span>Universitas: Universitas Gadjah Mada</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Hukum Hak Asasi Manusia</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Skripsi sarjana yang menganalisis peran legislatif dalam mengimplementasikan konvensi hak penyandang disabilitas melalui produk hukum daerah...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 4 -->
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="document-content">
                                    <span class="type-badge type-artikel">ARTIKEL ILMIAH</span>
                                    <h3 class="document-title">
                                        <a href="#">Digitalisasi Proses Legislasi: Peluang dan Tantangan bagi DPRD Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 15 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Penulis: Dr. Maya Indriati, S.Si., M.T.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-building"></i>
                                            <span>Konferensi: ICGSS 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Teknologi Informasi & Hukum</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Artikel ilmiah yang membahas implementasi teknologi dalam proses legislasi dan dampaknya terhadap efisiensi dan partisipasi publik...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-link-45deg"></i> Lihat di ResearchGate
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PAGINATION -->
                        <div class="pagination-container">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        
                        <!-- KATEGORI KARYA ILMIAH -->
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="mb-3"><i class="bi bi-diagram-3 text-primary me-2"></i>Kategori Karya Ilmiah</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-journal fs-1 text-primary"></i>
                                        </div>
                                        <h5>Jurnal</h5>
                                        <p class="small">Artikel jurnal nasional & internasional</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-book fs-1 text-primary"></i>
                                        </div>
                                        <h5>Skripsi</h5>
                                        <p class="small">Karya ilmiah strata 1</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-journals fs-1 text-primary"></i>
                                        </div>
                                        <h5>Tesis</h5>
                                        <p class="small">Karya ilmiah strata 2</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                                        </div>
                                        <h5>Artikel</h5>
                                        <p class="small">Makalah konferensi & prosiding</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU MONOGRAFI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-collection"></i> Menu Monografi Hukum</h4>
                        <ul>
                            <li><a href="?page=pokok-pikiran-dprd"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
                            <li><a href="?page=kajian-raperda"><i class="bi bi-search"></i> Kajian Raperda</a></li>
                            <li><a href="?page=naskah-akademik"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
                            <li><a href="?page=hasil-fasilitasi"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                            <li><a href="?page=hasil-harmonisasi"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah" class="active"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER TAHUN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-filter"></i> Filter Berdasarkan Tahun</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2024
                                <span class="badge bg-primary rounded-pill">12</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2023
                                <span class="badge bg-primary rounded-pill">28</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2022
                                <span class="badge bg-primary rounded-pill">32</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2021
                                <span class="badge bg-primary rounded-pill">40</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2020
                                <span class="badge bg-primary rounded-pill">36</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER JENIS KARYA -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-diagram-3"></i> Filter Berdasarkan Jenis</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <span class="type-badge type-jurnal me-2"></span>
                                    Jurnal
                                </span>
                                <span class="badge bg-success rounded-pill">45</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <span class="type-badge type-skripsi me-2"></span>
                                    Skripsi
                                </span>
                                <span class="badge bg-info rounded-pill">52</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <span class="type-badge type-tesis me-2"></span>
                                    Tesis
                                </span>
                                <span class="badge bg-warning rounded-pill">16</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <span class="type-badge type-artikel me-2"></span>
                                    Artikel Ilmiah
                                </span>
                                <span class="badge bg-primary rounded-pill">43</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER INSTITUSI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-building"></i> Filter Berdasarkan Institusi</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Sebelas Maret
                                <span class="badge bg-primary rounded-pill">68</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Gadjah Mada
                                <span class="badge bg-primary rounded-pill">32</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Indonesia
                                <span class="badge bg-primary rounded-pill">18</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                DPRD Kota Surakarta
                                <span class="badge bg-primary rounded-pill">24</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Lembaga Penelitian Lain
                                <span class="badge bg-primary rounded-pill">14</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- PANDUAN PENULISAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-book"></i> Panduan & Template</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Penulisan (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Pedoman Sitasi (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Metodologi (PPT)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Contoh Karya Lengkap (ZIP)</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                
                <!-- LOGO DAN DESKRIPSI -->
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="site-footer-logo">
                        <a href="index.html">
                            <img src="images/logo-footer.png" alt="logo" class="img-fluid">
                        </a>
                    </div>
                    <p class="text-white">
                        Jaringan Dokumentasi dan Informasi Hukum (JDIH) DPRD Kota Surakarta 
                        merupakan sistem informasi hukum yang menyediakan akses terhadap 
                        produk hukum dan dokumentasi legislasi daerah.
                    </p>
                </div>

                <!-- LINK CEPAT -->
                <div class="col-lg-2 col-md-6 col-12 mx-auto mb-4">
                    <h6 class="site-footer-title">Menu Cepat</h6>
                    <ul class="footer-menu">
                        <li><a href="index.php" class="site-footer-link">Beranda</a></li>
                        <li><a href="?page=profil-jdih" class="site-footer-link">Profil JDIH</a></li>
                        <li><a href="?page=perda" class="site-footer-link">Peraturan Daerah</a></li>
                        <li><a href="?page=artikel" class="site-footer-link">Artikel Hukum</a></li>
                        <li><a href="?page=kontak" class="site-footer-link">Kontak Kami</a></li>
                    </ul>
                </div>

                <!-- KONTAK -->
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <h6 class="site-footer-title">Kontak Kami</h6>
                    <p class="text-white d-flex mb-2">
                        <i class="bi-geo-alt me-2"></i>
                        Jl. Jend. Sudirman No. 2, Kec. Banjarsari, Kota Surakarta, Jawa Tengah 57113
                    </p>
                    <p class="text-white d-flex mb-2">
                        <i class="bi-telephone me-2"></i>
                        <a href="tel:+62271234567" class="site-footer-link">(0271) 234567</a>
                    </p>
                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:jdih@dprd-surakarta.go.id" class="site-footer-link">jdih@dprd-surakarta.go.id</a>
                    </p>
                </div>

                <!-- MEDIA SOSIAL -->
                <div class="col-lg-3 col-md-6 col-12">
                    <h6 class="site-footer-title">Media Sosial</h6>
                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi-facebook"></a></li>
                        <li><a href="#" class="social-icon-link bi-twitter"></a></li>
                        <li><a href="#" class="social-icon-link bi-instagram"></a></li>
                        <li><a href="#" class="social-icon-link bi-youtube"></a></li>
                        <li><a href="#" class="social-icon-link bi-whatsapp"></a></li>
                    </ul>
                    <p class="copyright-text mt-3">Copyright © 2024 JDIH DPRD Kota Surakarta</p>
                </div>

            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        // Fungsi pencarian
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const keyword = this.querySelector('input[type="text"]').value;
            
            if (!keyword.trim()) {
                alert('Masukkan kata kunci pencarian!');
                return;
            }
            
            alert(`Mencari karya ilmiah dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
        });
        
        // Fungsi filter
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.closest('.sidebar-menu').querySelector('h4').textContent;
                const filterValue = this.textContent.trim().split('\n')[0];
                
                alert(`Memfilter berdasarkan ${filterType}: ${filterValue}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi download
        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-item').querySelector('.document-title a').textContent;
                
                alert(`Mengunduh karya ilmiah: "${docTitle}"\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
                
                // Simulasi download
                setTimeout(() => {
                    alert('Download berhasil!');
                }, 1000);
            });
        });
        
        // Fungsi preview
        document.querySelectorAll('.btn-preview').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-item').querySelector('.document-title a').textContent;
                
                if (this.querySelector('.bi-link-45deg')) {
                    alert(`Mengarahkan ke platform akademik untuk karya: "${docTitle}"\n\nDalam implementasi real, akan membuka halaman di Google Scholar/ResearchGate.`);
                } else {
                    alert(`Membuka preview karya ilmiah: "${docTitle}"\n\nPreview akan dibuka di tab baru.`);
                }
            });
        });
    </script>

</body>
</html>