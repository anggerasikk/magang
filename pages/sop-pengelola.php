<?php
// sop-pengelola.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SOP Pengelola JDIH - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
    
    <style>
        /* OVERRIDE UNTUK HALAMAN INI SAJA */
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
            background: #f8f9fa;
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
        
        .section-title.no-line::after {
            display: none;
        }

        /* CONTENT CARD */
        .content-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
        }
        
        .sop-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .sop-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .sop-subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .sop-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }
        
        .meta-item i {
            color: #80d0c7;
            font-size: 1.1rem;
        }

        /* SOP CONTENT */
        .sop-section {
            margin-bottom: 40px;
        }
        
        .sop-section-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .sop-item {
            margin-bottom: 25px;
            padding-left: 20px;
            position: relative;
        }
        
        .sop-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            width: 8px;
            height: 8px;
            background: #80d0c7;
            border-radius: 50%;
        }
        
        .sop-item h4 {
            color: #13547a;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .sop-process {
            background: #f0f8ff;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #13547a;
        }
        
        .process-step {
            display: flex;
            margin-bottom: 20px;
        }
        
        .process-step:last-child {
            margin-bottom: 0;
        }
        
        .step-number {
            background: #13547a;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .step-content h5 {
            color: #13547a;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .responsibility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .responsibility-item {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius-medium);
            padding: 20px;
            transition: all 0.3s;
        }
        
        .responsibility-item:hover {
            border-color: #80d0c7;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .responsibility-item h5 {
            color: #13547a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .responsibility-item h5 i {
            color: #80d0c7;
        }

        /* DOCUMENT SECTION */
        .document-card {
            background: white;
            border-radius: var(--border-radius-medium);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        .document-list {
            list-style: none;
            padding: 0;
        }
        
        .document-item {
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .document-item:last-child {
            border-bottom: none;
        }
        
        .document-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .document-icon {
            width: 50px;
            height: 50px;
            background: #f0f8ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #13547a;
            font-size: 1.5rem;
        }
        
        .document-details h5 {
            color: #13547a;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .document-meta {
            font-size: 0.9rem;
            color: #666;
        }
        
        .document-actions .btn {
            padding: 8px 20px;
            font-size: 0.9rem;
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
        
        .news-sidebar {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .news-sidebar h4 {
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .news-list {
            list-style: none;
            padding: 0;
        }
        
        .news-item {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .news-item:last-child {
            border-bottom: none;
        }
        
        .news-item a {
            color: white;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }
        
        .news-item a:hover {
            color: #f0f8ff;
            transform: translateX(5px);
        }
        
        .news-title {
            font-weight: 500;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .news-date {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .law-year-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .law-year-card h4 {
            color: #13547a;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .year-badge {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 15px 30px;
            border-radius: var(--border-radius-medium);
            font-size: 1.5rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .law-year-card p {
            color: #666;
            margin-bottom: 20px;
        }

        /* CONTACT INFO */
        .contact-info-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .contact-info-card h4 {
            color: #13547a;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            background: #f0f8ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #13547a;
            flex-shrink: 0;
        }
        
        .contact-details h5 {
            color: #13547a;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .contact-details p {
            color: #666;
            margin: 0;
        }
        
        .contact-details a {
            color: #13547a;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-details a:hover {
            color: #80d0c7;
        }

        /* DOWNLOAD SECTION */
        .download-section {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: var(--border-radius-medium);
            padding: 40px;
            margin: 50px 0;
            text-align: center;
            color: white;
        }
        
        .download-section h3 {
            color: white;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .download-section p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .download-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .btn-download {
            background: white;
            color: #13547a;
            border: none;
            padding: 12px 30px;
            border-radius: var(--border-radius-medium);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        
        .btn-download:hover {
            background: #f0f8ff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #13547a;
        }
        
        .btn-outline-download {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 12px 30px;
            border-radius: var(--border-radius-medium);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        
        .btn-outline-download:hover {
            background: white;
            color: #13547a;
            transform: translateY(-3px);
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sop-title {
                font-size: 1.8rem;
            }
            
            .content-card {
                padding: 30px;
            }
            
            .responsibility-grid {
                grid-template-columns: 1fr;
            }
        }
        
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
            
            .sop-meta {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .document-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .download-buttons {
                flex-direction: column;
            }
            
            .download-section {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .sop-title {
                font-size: 1.5rem;
            }
            
            .content-card {
                padding: 20px;
            }
            
            .sop-section-title {
                font-size: 1.3rem;
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
                            <li class="active"><a class="dropdown-item" href="?page=sop-pengelola">SOP Pengelola JDIH DPRD Kota Surakarta</a></li>
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

                    <!-- ARTIKEL HUKUM -->
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
                    <h1 class="text-white">SOP PENGELOLA JDIH</h1>
                    <p class="subtitle">Standar Operasional Prosedur Pengelola Jaringan Dokumentasi dan Informasi Hukum DPRD Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">SOP Pengelola JDIH</li>
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
                    
                    <!-- SOP CONTENT -->
                    <div class="content-card">
                        <div class="sop-header">
                            <h1 class="sop-title">STANDAR OPERASIONAL PROSEDUR (SOP)</h1>
                            <h2 class="sop-subtitle">PENGELOLA JARINGAN DOKUMENTASI DAN INFORMASI HUKUM (JDIH)</h2>
                            <p class="text-muted">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                            
                            <div class="sop-meta">
                                <div class="meta-item">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Nomor: 001/SOP-JDIH/DPRD-SKA/2024</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Tanggal Berlaku: 1 Januari 2024</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Revisi: 01</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BAB 1: PENDAHULUAN -->
                        <div class="sop-section">
                            <h3 class="sop-section-title">BAB I: PENDAHULUAN</h3>
                            
                            <div class="sop-item">
                                <h4>1.1 Latar Belakang</h4>
                                <p>Dalam rangka meningkatkan efektivitas dan efisiensi pengelolaan produk hukum di lingkungan DPRD Kota Surakarta, diperlukan suatu sistem pengelolaan dokumentasi dan informasi hukum yang terstandarisasi. SOP ini disusun untuk memberikan panduan operasional bagi pengelola JDIH dalam melaksanakan tugas dan fungsinya.</p>
                            </div>
                            
                            <div class="sop-item">
                                <h4>1.2 Tujuan</h4>
                                <p>1. Menjamin konsistensi dan kualitas pengelolaan produk hukum.<br>
                                2. Meningkatkan akurasi dan kecepatan akses informasi hukum.<br>
                                3. Menjaga keamanan dan keutuhan dokumen hukum.<br>
                                4. Mempermudah monitoring dan evaluasi pengelolaan JDIH.</p>
                            </div>
                            
                            <div class="sop-item">
                                <h4>1.3 Ruang Lingkup</h4>
                                <p>SOP ini berlaku untuk seluruh pengelola JDIH DPRD Kota Surakarta dalam melakukan kegiatan dokumentasi, pengelolaan, dan penyebarluasan produk hukum.</p>
                            </div>
                        </div>
                        
                        <!-- BAB 2: PROSEDUR PENGELOLAAN -->
                        <div class="sop-section">
                            <h3 class="sop-section-title">BAB II: PROSEDUR PENGELOLAAN DOKUMEN HUKUM</h3>
                            
                            <div class="sop-process">
                                <h4>Alur Proses Pengelolaan Dokumen Hukum</h4>
                                
                                <div class="process-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h5>Penerimaan Dokumen</h5>
                                        <p>Dokumen hukum diterima dari unit kerja terkait, diverifikasi kelengkapan dan keabsahannya.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h5>Registrasi dan Klasifikasi</h5>
                                        <p>Dokumen didaftarkan dalam sistem, diberi nomor registrasi, dan diklasifikasikan berdasarkan jenis dan kategori.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h5>Pencatatan dan Indeks</h5>
                                        <p>Dokumen dicatat dalam database JDIH dan dibuat indeks untuk memudahkan pencarian.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step">
                                    <div class="step-number">4</div>
                                    <div class="step-content">
                                        <h5>Digitalisasi</h5>
                                        <p>Dokumen fisik dipindai dan dikonversi ke format digital dengan standar kualitas yang ditetapkan.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step">
                                    <div class="step-number">5</div>
                                    <div class="step-content">
                                        <h5>Penyimpanan dan Arsip</h5>
                                        <p>Dokumen disimpan secara fisik dan digital dengan sistem pengarsipan yang terstruktur.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step">
                                    <div class="step-number">6</div>
                                    <div class="step-content">
                                        <h5>Diseminasi Informasi</h5>
                                        <p>Informasi hukum disebarluaskan melalui website, media sosial, dan layanan informasi lainnya.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BAB 3: TANGGUNG JAWAB -->
                        <div class="sop-section">
                            <h3 class="sop-section-title">BAB III: TANGGUNG JAWAB DAN WEWENANG</h3>
                            
                            <div class="responsibility-grid">
                                <div class="responsibility-item">
                                    <h5><i class="bi bi-person-badge"></i> Koordinator JDIH</h5>
                                    <p>Mengkoordinasi seluruh kegiatan JDIH, menyusun program kerja, dan bertanggung jawab atas kualitas layanan.</p>
                                </div>
                                
                                <div class="responsibility-item">
                                    <h5><i class="bi bi-file-earmark-text"></i> Admin Dokumen</h5>
                                    <p>Melakukan penerimaan, registrasi, dan pengarsipan dokumen hukum sesuai prosedur.</p>
                                </div>
                                
                                <div class="responsibility-item">
                                    <h5><i class="bi bi-laptop"></i> Teknisi Sistem</h5>
                                    <p>Mengelola sistem informasi JDIH, melakukan digitalisasi, dan menjaga keamanan data.</p>
                                </div>
                                
                                <div class="responsibility-item">
                                    <h5><i class="bi bi-headset"></i> Petugas Layanan</h5>
                                    <p>Melayani permintaan informasi, membantu pencarian dokumen, dan memberikan bantuan teknis.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BAB 4: STANDAR KUALITAS -->
                        <div class="sop-section">
                            <h3 class="sop-section-title">BAB IV: STANDAR KUALITAS DAN MONITORING</h3>
                            
                            <div class="sop-item">
                                <h4>4.1 Standar Kualitas Dokumen Digital</h4>
                                <p>1. Resolusi pindaian minimal 300 DPI<br>
                                2. Format file PDF/A untuk arsip permanen<br>
                                3. OCR (Optical Character Recognition) untuk dokumen teks<br>
                                4. Metadata lengkap untuk setiap dokumen</p>
                            </div>
                            
                            <div class="sop-item">
                                <h4>4.2 Waktu Respon Layanan</h4>
                                <p>1. Permintaan informasi sederhana: Maksimal 15 menit<br>
                                2. Permintaan dokumen kompleks: Maksimal 2 jam kerja<br>
                                3. Permintaan via email: Maksimal 24 jam<br>
                                4. Pengaduan masyarakat: Maksimal 3 hari kerja</p>
                            </div>
                            
                            <div class="sop-item">
                                <h4>4.3 Monitoring dan Evaluasi</h4>
                                <p>1. Dilakukan monitoring harian oleh koordinator<br>
                                2. Evaluasi bulanan terhadap kinerja sistem<br>
                                3. Audit kualitas semesteran<br>
                                4. Survei kepuasan pengguna triwulanan</p>
                            </div>
                        </div>
                        
                        <!-- BAB 5: DOKUMEN TERKAIT -->
                        <div class="sop-section">
                            <h3 class="sop-section-title">BAB V: DOKUMEN TERKAIT DAN REFERENSI</h3>
                            
                            <div class="sop-item">
                                <h4>5.1 Dokumen Pendukung</h4>
                                <p>1. Peraturan Menteri Dalam Negeri tentang JDIH<br>
                                2. Peraturan Daerah tentang Organisasi DPRD<br>
                                3. SK Kepala Sekretariat DPRD tentang Pembentukan JDIH<br>
                                4. Panduan Teknis Pengelolaan JDIH</p>
                            </div>
                            
                            <div class="sop-item">
                                <h4>5.2 Formulir yang Digunakan</h4>
                                <p>1. Formulir Penerimaan Dokumen<br>
                                2. Formulir Peminjaman Arsip<br>
                                3. Formulir Permintaan Informasi<br>
                                4. Formulir Laporan Kegiatan</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD SECTION -->
                    <div class="download-section">
                        <h3>Unduh SOP Lengkap</h3>
                        <p>Dapatkan versi lengkap SOP Pengelola JDIH dalam format PDF untuk referensi dan implementasi lebih lanjut.</p>
                        <div class="download-buttons">
                            <a href="#" class="btn-download">
                                <i class="bi bi-download"></i>
                                Download SOP (PDF)
                            </a>
                            <a href="#" class="btn-outline-download">
                                <i class="bi bi-printer"></i>
                                Cetak Dokumen
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOKUMEN TERKAIT -->
                    <div class="document-card">
                        <h3 class="section-title">Dokumen Terkait</h3>
                        <div class="document-list">
                            <div class="document-item">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </div>
                                    <div class="document-details">
                                        <h5>SK Pembentukan Tim JDIH DPRD Kota Surakarta</h5>
                                        <div class="document-meta">
                                            <span>Nomor: 001/SK-SETWAN/DPRD-SKA/2024</span>
                                            <span>•</span>
                                            <span>PDF • 2.4 MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            
                            <div class="document-item">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-word"></i>
                                    </div>
                                    <div class="document-details">
                                        <h5>Format Laporan Bulanan Pengelola JDIH</h5>
                                        <div class="document-meta">
                                            <span>Template Administratif</span>
                                            <span>•</span>
                                            <span>DOCX • 1.2 MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            
                            <div class="document-item">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-spreadsheet"></i>
                                    </div>
                                    <div class="document-details">
                                        <h5>Daftar Inventaris Dokumen Hukum 2024</h5>
                                        <div class="document-meta">
                                            <span>Update Per Januari 2024</span>
                                            <span>•</span>
                                            <span>XLSX • 3.1 MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- BERITA TERBARU -->
                    <div class="news-sidebar">
                        <h4><i class="bi bi-newspaper"></i> Berita Terbaru</h4>
                        <ul class="news-list">
                            <li class="news-item">
                                <a href="#">
                                    <div class="news-title">Sosialisasi JDIH Setwan Kota Surakarta kepada Masyarakat</div>
                                    <div class="news-date">
                                        <i class="bi bi-calendar"></i>
                                        <span>Jan 10, 2021</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="news-item">
                                <a href="#">
                                    <div class="news-title">Perkuat Infrastruktur Digital, Biro Hukum Gelar Pelatihan JDIH</div>
                                    <div class="news-date">
                                        <i class="bi bi-calendar"></i>
                                        <span>Jan 10, 2021</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="news-item">
                                <a href="#">
                                    <div class="news-title">Pansus Gelar Public Hearing terkait Penetapan Perda</div>
                                    <div class="news-date">
                                        <i class="bi bi-calendar"></i>
                                        <span>Jan 10, 2021</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="news-item">
                                <a href="#">
                                    <div class="news-title">Public Hearing Raporda Penyelonggaraan Keuangan Daerah</div>
                                    <div class="news-date">
                                        <i class="bi bi-calendar"></i>
                                        <span>Jan 10, 2021</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- TAHUN PRODUK HUKUM -->
                    <div class="law-year-card">
                        <h4>Tahun Produk Hukum</h4>
                        <div class="year-badge">2024</div>
                        <p>Produk hukum yang diterbitkan tahun ini meliputi Perda, Keputusan DPRD, dan regulasi lainnya.</p>
                        <a href="?page=perda" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                            Lihat Produk Hukum
                        </a>
                    </div>
                    
                    <!-- INFORMASI KONTAK -->
                    <div class="contact-info-card">
                        <h4><i class="bi bi-info-circle"></i> Informasi Kontak</h4>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Alamat Kantor</h5>
                                <p>Jl. Jenderal Sudirman No. 2<br>
                                Kecamatan Banjarsari<br>
                                Kota Surakarta 57113</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Telepon</h5>
                                <p><a href="tel:+62271234567">(0271) 234567</a><br>
                                <a href="tel:+62271234568">(0271) 234568</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email</h5>
                                <p><a href="mailto:jdih@dprd-surakarta.go.id">jdih@dprd-surakarta.go.id</a><br>
                                <a href="mailto:sekretariat@dprd-surakarta.go.id">sekretariat@dprd-surakarta.go.id</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Jam Operasional</h5>
                                <p>Senin - Kamis: 08.00 - 16.00<br>
                                Jumat: 08.00 - 11.30<br>
                                Sabtu - Minggu: Tutup</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- MENU TERKAIT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link"></i> Menu Terkait</h4>
                        <ul>
                            <li><a href="?page=profil-jdih"><i class="bi bi-chevron-right"></i> Profil JDIH</a></li>
                            <li class="active"><a href="?page=sop-pengelola"><i class="bi bi-chevron-right"></i> SOP Pengelola</a></li>
                            <li><a href="?page=alur-pengelolaan"><i class="bi bi-chevron-right"></i> Alur Pengelolaan</a></li>
                            <li><a href="?page=dasar-hukum"><i class="bi bi-chevron-right"></i> Dasar Hukum</a></li>
                            <li><a href="?page=surat-keputusan"><i class="bi bi-chevron-right"></i> Surat Keputusan</a></li>
                            <li><a href="?page=struktur-organisasi"><i class="bi bi-chevron-right"></i> Struktur Organisasi</a></li>
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
                        <a href="index.php">
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
        // Fungsi untuk download dokumen
        document.querySelectorAll('.btn-download, .btn-outline-download').forEach(button => {
            button.addEventListener('click', function(e) {
                if(this.getAttribute('href') === '#') {
                    e.preventDefault();
                    alert('Fungsi download akan bekerja dalam implementasi nyata. File akan didownload ke perangkat Anda.');
                }
            });
        });
        
        // Fungsi untuk navigasi berita
        document.querySelectorAll('.news-item a').forEach(item => {
            item.addEventListener('click', function(e) {
                if(this.getAttribute('href') === '#') {
                    e.preventDefault();
                    const title = this.querySelector('.news-title').textContent;
                    alert(`Membuka berita: "${title}"\n\nDalam implementasi nyata, ini akan mengarah ke halaman berita detail.`);
                }
            });
        });
        
        // Fungsi untuk toggle dropdown aktif
        document.addEventListener('DOMContentLoaded', function() {
            // Set menu Tentang Kami sebagai aktif
            const tentangKamiDropdown = document.querySelector('#dropdownTentangKami');
            if(tentangKamiDropdown) {
                tentangKamiDropdown.classList.add('active');
            }
        });
        
        // Fungsi untuk print dokumen
        document.querySelector('.btn-outline-download[href="#"]').addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Apakah Anda ingin mencetak dokumen SOP ini?')) {
                window.print();
            }
        });
    </script>

</body>
</html>