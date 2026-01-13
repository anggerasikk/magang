<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Naskah Akademik - Monografi Hukum - JDIH DPRD Kota Surakarta</title>
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
        
        /* HEADER SECTION */
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
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

        /* SEARCH BOX */
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
                            <li><a class="dropdown-item active" href="?page=naskah-akademik">Naskah Akademik</a></li>
                            <li><a class="dropdown-item" href="?page=hasil-fasilitasi">Hasil Fasilitasi Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=hasil-harmonisasi">Hasil Harmonisasi Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=karya-ilmiah">Penulisan Karya Ilmiah</a></li>
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
                    <p class="subtitle">Naskah Akademik Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Naskah Akademik</li>
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
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h2 class="section-title">Naskah Akademik</h2>
                            <p class="monografi-description">
                                Kumpulan naskah akademik yang merupakan hasil penelitian dan kajian mendalam 
                                sebagai landasan ilmiah dalam penyusunan Rancangan Peraturan Daerah (Raperda). 
                                Naskah akademik memuat analisis yuridis, filosofis, sosiologis, dan ekonomis.
                            </p>
                        </div>
                        
                        <!-- PENCARIAN -->
                        <div class="search-monografi">
                            <h4 class="mb-4">Cari Naskah Akademik</h4>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci naskah akademik..." 
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
                                    <div class="stats-number">74</div>
                                    <div class="stats-label">Total Naskah</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">6</div>
                                    <div class="stats-label">Tahun 2024</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">58</div>
                                    <div class="stats-label">Menjadi Perda</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">16</div>
                                    <div class="stats-label">Dalam Proses</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- DAFTAR DOKUMEN -->
                        <h3 class="section-title">Naskah Akademik Terbaru</h3>
                        
                        <div class="document-list">
                            <!-- Dokumen 1 -->
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="document-content">
                                    <h3 class="document-title">
                                        <a href="#">Naskah Akademik Raperda tentang Pelestarian dan Pengembangan Batik Surakarta sebagai Warisan Budaya</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 25 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Nomor: NA/DPRD/01/2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Tim Penyusun: Pusat Kajian Hukum UNS</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Kebudayaan</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Naskah akademik yang mengkaji pengaturan pelestarian batik Surakarta sebagai warisan budaya tak benda dengan pendekatan ekonomi kreatif dan perlindungan hukum...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-link-45deg"></i> Lihat Raperda
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
                                    <h3 class="document-title">
                                        <a href="#">Naskah Akademik Pengaturan Sistem Transportasi Publik Terintegrasi di Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 18 Januari 2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Nomor: NA/DPRD/02/2024</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Tim Penyusun: Fakultas Teknik UNS</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Transportasi</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Kajian akademis tentang pengaturan sistem transportasi publik terintegrasi yang mencakup aspek teknis, ekonomi, sosial, dan lingkungan untuk mendukung smart mobility...
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
                                    <h3 class="document-title">
                                        <a href="#">Naskah Akademik Pengelolaan Kawasan Bebas Sampah Plastik di Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 12 Desember 2023</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Nomor: NA/DPRD/12/2023</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Tim Penyusun: Fakultas Hukum UNS</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Lingkungan Hidup</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Analisis komprehensif tentang pengaturan kawasan bebas sampah plastik dengan pendekatan circular economy dan partisipasi masyarakat dalam pengelolaan sampah...
                                    </p>
                                    <div class="document-actions">
                                        <a href="#" class="btn-download">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="btn-preview">
                                            <i class="bi bi-link-45deg"></i> Lihat Raperda
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
                                    <h3 class="document-title">
                                        <a href="#">Naskah Akademik Perlindungan dan Pemberdayaan Penyandang Disabilitas di Kota Surakarta</a>
                                    </h3>
                                    <div class="document-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>Tanggal: 5 Desember 2023</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-journal-text"></i>
                                            <span>Nomor: NA/DPRD/11/2023</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Tim Penyusun: Lembaga Kajian Sosial</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <span>Bidang: Sosial & Kesejahteraan</span>
                                        </div>
                                    </div>
                                    <p class="document-excerpt">
                                        Kajian mendalam tentang pengaturan hak-hak penyandang disabilitas meliputi aksesibilitas, kesempatan kerja, pendidikan, dan partisipasi dalam pembangunan...
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
                        
                        <!-- STRUKTUR NASKAH AKADEMIK -->
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="mb-3"><i class="bi bi-list-ol text-primary me-2"></i>Struktur Naskah Akademik</h4>
                            <div class="accordion" id="accordionStruktur">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            <i class="bi bi-1-circle me-2"></i>Pendahuluan
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Latar belakang, rumusan masalah, tujuan, manfaat, dan metodologi penelitian.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <i class="bi bi-2-circle me-2"></i>Kajian Teoritis
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Kajian literatur, kerangka konseptual, dan landasan teori yang relevan.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            <i class="bi bi-3-circle me-2"></i>Analisis Permasalahan
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Identifikasi dan analisis permasalahan hukum yang diatur dalam raperda.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            <i class="bi bi-4-circle me-2"></i>Rekomendasi Kebijakan
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Rekomendasi kebijakan dan alternatif penyelesaian masalah.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                            <i class="bi bi-5-circle me-2"></i>Kesimpulan & Saran
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Kesimpulan penelitian dan saran implementasi.</p>
                                        </div>
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
                            <li><a href="?page=naskah-akademik" class="active"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
                            <li><a href="?page=hasil-fasilitasi"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                            <li><a href="?page=hasil-harmonisasi"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER TAHUN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-filter"></i> Filter Berdasarkan Tahun</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2024
                                <span class="badge bg-primary rounded-pill">6</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2023
                                <span class="badge bg-primary rounded-pill">14</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2022
                                <span class="badge bg-primary rounded-pill">12</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2021
                                <span class="badge bg-primary rounded-pill">16</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                2020
                                <span class="badge bg-primary rounded-pill">10</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER INSTITUSI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-building"></i> Filter Berdasarkan Institusi</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Sebelas Maret
                                <span class="badge bg-primary rounded-pill">28</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Badan Legislasi DPRD
                                <span class="badge bg-primary rounded-pill">18</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Lembaga Kajian Hukum
                                <span class="badge bg-primary rounded-pill">12</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Konsultan Hukum
                                <span class="badge bg-primary rounded-pill">8</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Institusi Lainnya
                                <span class="badge bg-primary rounded-pill">8</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD PANDUAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Panduan Penyusunan</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Naskah Akademik (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Pedoman Penyusunan (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Contoh Lengkap (ZIP)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Metodologi (PPT)</a></li>
                        </ul>
                    </div>
                    
                    <!-- TAHAPAN PENYUSUNAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-diagram-3"></i> Tahapan Penyusunan</h4>
                        <div class="small">
                            <ol class="ps-3">
                                <li class="mb-2">Permohonan penyusunan</li>
                                <li class="mb-2">Penetapan tim penyusun</li>
                                <li class="mb-2">Penelitian dan kajian</li>
                                <li class="mb-2">Penyusunan draft</li>
                                <li class="mb-2">Review dan revisi</li>
                                <li class="mb-2">Finalisasi dan pengesahan</li>
                            </ol>
                        </div>
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
            
            alert(`Mencari naskah akademik dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
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
                
                alert(`Mengunduh naskah akademik: "${docTitle}"\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
                
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
                    alert(`Mengarahkan ke Raperda terkait naskah akademik: "${docTitle}"\n\nDalam implementasi real, akan membuka halaman Raperda.`);
                } else {
                    alert(`Membuka preview naskah akademik: "${docTitle}"\n\nPreview akan dibuka di tab baru.`);
                }
            });
        });
        
        // Accordion functionality
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (this.classList.contains('collapsed')) {
                    icon.className = 'bi bi-chevron-down me-2';
                } else {
                    icon.className = 'bi bi-chevron-up me-2';
                }
            });
        });
    </script>

</body>
</html>