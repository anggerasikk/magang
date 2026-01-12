<?php
// beranda.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Beranda - JDIH DPRD Kota Surakarta</title>
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
        /* OVERRIDE UNTUK HALAMAN BERANDA */
        body {
            padding-top: 80px; /* Untuk fixed navbar */
        }
        
        /* HERO SECTION */
        .hero-section {
            background: linear-gradient(rgba(19, 84, 122, 0.85), rgba(19, 84, 122, 0.85)), 
                        url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }
        
        .hero-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }
        
        /* SEARCH BOX */
        .search-box {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-input {
            height: 60px;
            border-radius: 50px;
            border: none;
            padding: 15px 25px;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }
        
        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            background: linear-gradient(135deg, #80d0c7 0%, #13547a 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            background: linear-gradient(135deg, #6bc0b7 0%, #104366 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.4);
        }
        
        /* MAIN CONTENT */
        .main-content {
            padding: 60px 0;
            background: #f0f8ff;
        }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #13547a;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #80d0c7;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: #ff7e5f;
        }
        
        /* WELCOME SECTION */
        .welcome-text {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 30px;
            border-left: 5px solid #80d0c7;
        }
        
        .welcome-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
        }
        
        .signature-box {
            text-align: right;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        
        .signature-name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .signature-title {
            color: #80d0c7;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        /* QUICK LINKS */
        .custom-block {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .custom-block:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: #80d0c7;
        }
        
        .custom-block i {
            color: #13547a;
            font-size: 3.5rem;
            margin-bottom: 20px;
            display: block;
        }
        
        .custom-block h5 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .custom-block p {
            color: #717275;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .custom-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .custom-btn:hover {
            background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(19, 84, 122, 0.3);
        }
        
        /* STATISTICS SECTION */
        .stats-section {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 60px 0;
            margin: 60px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* NEWS SECTION */
        .news-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .news-content {
            padding: 25px;
        }
        
        .news-date {
            color: #80d0c7;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
        }
        
        .news-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .news-excerpt {
            color: #717275;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .read-more {
            color: #13547a;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .read-more:hover {
            color: #80d0c7;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .hero-title {
                font-size: 2.2rem;
            }
            
            .search-input {
                height: 50px;
                font-size: 1rem;
            }
            
            .search-btn {
                padding: 12px 25px;
                top: 4px;
                right: 4px;
            }
            
            .main-content {
                padding: 40px 0;
            }
            
            .welcome-text {
                padding: 25px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR SAMA DENGAN STRUKTUR ORGANISASI -->
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
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownTentangKami"
                            role="button" data-bs-toggle="dropdown">
                            Tentang Kami
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=Profil-JDIH">Profil JDIH</a></li>
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

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title">Selamat Datang di JDIH<br>Sekretariat DPRD Surakarta</h1>
                    <p class="hero-subtitle">
                        Pusat Dokumentasi dan Informasi Hukum yang menyediakan akses mudah 
                        terhadap seluruh produk hukum DPRD Kota Surakarta
                    </p>
                    
                    <!-- SEARCH BOX -->
                    <div class="search-box mt-5">
                        <input type="text" class="search-input" placeholder="Cari produk hukum, peraturan, atau informasi...">
                        <button class="search-btn">
                            <i class="bi bi-search me-2"></i>CARI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- WELCOME SECTION -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">Kata Pengantar</h2>
                    <div class="welcome-text">
                        <p>
                            Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                            hadir sebagai sarana penyebarluasan produk hukum secara online, sebagai media 
                            publikasi, serta menyediakan akses yang mudah dan cepat untuk informasi hukum. 
                            Dengan demikian, diharapkan jajaran sekretariat Dewan Perwakilan Rakyat Daerah 
                            Kota Surakarta, masyarakat kota Surakarta, dan semua pihak yang memerlukan 
                            dapat mengakses dan mendapatkan informasi hukum yang tepat dan akurat.
                        </p>
                        <p>
                            <strong>"JDIH membawa hukum langsung dalam genggaman Anda"</strong>
                        </p>
                        
                        <div class="signature-box">
                            <p class="signature-name">KINKIN SULTANIL HAKIM, SH, MM</p>
                            <p class="signature-title">SEKRETARIS DPRD KOTA SURAKARTA</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- STATISTICS -->
            <div class="stats-section rounded-3">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-4 mb-md-0">
                            <div class="stat-item">
                                <div class="stat-number">1,250+</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4 mb-md-0">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Peraturan Daerah</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">750+</div>
                                <div class="stat-label">Keputusan DPRD</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">100+</div>
                                <div class="stat-label">Artikel Hukum</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- QUICK LINKS -->
            <div class="row mt-5">
                <div class="col-12 mb-5">
                    <h2 class="section-title">Akses Cepat</h2>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-file-text"></i>
                        <h5>Keputusan DPRD</h5>
                        <p>Akses seluruh keputusan yang telah ditetapkan oleh DPRD Kota Surakarta</p>
                        <a href="?page=keputusan-dprd" class="btn custom-btn">Lihat Keputusan</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-journal-text"></i>
                        <h5>Peraturan DPRD</h5>
                        <p>Kumpulan peraturan yang berlaku di lingkungan DPRD Kota Surakarta</p>
                        <a href="?page=peraturan-dprd" class="btn custom-btn">Lihat Peraturan</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-info-circle"></i>
                        <h5>Informasi Hukum</h5>
                        <p>Sumber informasi hukum terkini dan terpercaya untuk masyarakat</p>
                        <a href="?page=informasi" class="btn custom-btn">Selengkapnya</a>
                    </div>
                </div>
            </div>
            
            <!-- LATEST NEWS -->
            <div class="row mt-5">
                <div class="col-12 mb-5">
                    <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Pelatihan JDIH" class="news-image">
                        <div class="news-content">
                            <span class="news-date">15 Desember 2024</span>
                            <h5 class="news-title">Pelatihan Pengelolaan JDIH untuk Staf Sekretariat</h5>
                            <p class="news-excerpt">Pelatihan intensif untuk meningkatkan kompetensi pengelola JDIH dalam mengelola informasi hukum.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Sosialisasi Perda" class="news-image">
                        <div class="news-content">
                            <span class="news-date">10 Desember 2024</span>
                            <h5 class="news-title">Sosialisasi Peraturan Daerah Terbaru</h5>
                            <p class="news-excerpt">Kegiatan sosialisasi peraturan daerah terbaru kepada masyarakat Kota Surakarta.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Penghargaan JDIH" class="news-image">
                        <div class="news-content">
                            <span class="news-date">5 Desember 2024</span>
                            <h5 class="news-title">JDIH DPRD Surakarta Raih Penghargaan Nasional</h5>
                            <p class="news-excerpt">JDIH DPRD Kota Surakarta menerima penghargaan sebagai sistem dokumentasi hukum terbaik tingkat nasional.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
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
                        <span>JDIH DPRD</span>
                    </a>
                    <p class="text-white mt-3">
                        Jaringan Dokumentasi dan Informasi Hukum<br>
                        Dewan Perwakilan Rakyat Daerah<br>
                        Kota Surakarta
                    </p>
                </div>

                <div class="col-lg-3 col-md-4 col-6">
                    <h6 class="site-footer-title mb-3">Menu Utama</h6>
                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="index.php" class="site-footer-link">Beranda</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=perundangan" class="site-footer-link">Perundangan</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=keputusan-sekretaris" class="site-footer-link">Keputusan Sekretaris</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=kontak" class="site-footer-link">Kontak</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-6">
                    <h6 class="site-footer-title mb-3">Informasi</h6>
                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="?page=tentang" class="site-footer-link">Tentang Kami</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=kontak" class="site-footer-link">Kontak</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Kebijakan Privasi</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0 ms-auto">
                    <h6 class="site-footer-title mb-3">Kontak Kami</h6>
                    <p class="text-white mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        Sekretariat DPRD Kota Surakarta<br>
                        <span style="margin-left: 24px;">Jl. Jenderal Sudirman No. 2, Surakarta</span>
                    </p>
                    <p class="text-white mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        jdih@dprd-surakarta.go.id
                    </p>
                    <p class="text-white mb-4">
                        <i class="bi bi-telephone me-2"></i>
                        (0271) 123456
                    </p>
                    
                    <div class="copyright">
                        <p class="mt-4">
                            &copy; <?php echo date('Y'); ?> JDIH Sekretariat DPRD Kota Surakarta.<br>
                            Hak Cipta Dilindungi.
                        </p>
                    </div>
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
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.querySelector('.search-btn');
            const searchInput = document.querySelector('.search-input');
            
            searchBtn.addEventListener('click', function() {
                performSearch();
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                const query = searchInput.value.trim();
                if (query) {
                    // Redirect to search results page with query parameter
                    window.location.href = '?page=search&q=' + encodeURIComponent(query);
                } else {
                    alert('Silakan masukkan kata kunci pencarian');
                }
            }
            
            // Navbar scroll effect
            const navbar = document.querySelector('.navbar-custom');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Active nav link
            const currentPage = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>
</html>