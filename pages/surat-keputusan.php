<?php
// surat-keputusan.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keputusan - JDIH DPRD Kota Surakarta</title>
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

        /* CONTENT CARD */
        .content-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
        }
        
        .decree-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .decree-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .decree-subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        /* DECREE DOCUMENT */
        .decree-document {
            background: #f0f8ff;
            border-radius: var(--border-radius-medium);
            padding: 40px;
            margin: 40px 0;
            text-align: center;
            border: 2px dashed #80d0c7;
        }
        
        .document-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
        }
        
        .document-title {
            color: #13547a;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .document-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }
        
        .meta-item i {
            color: #80d0c7;
        }
        
        .btn-download-primary {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: var(--border-radius-medium);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-size: 1.1rem;
        }
        
        .btn-download-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(19, 84, 122, 0.3);
            color: white;
        }

        /* DECREE LIST */
        .decree-list {
            margin-top: 50px;
        }
        
        .decree-item {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .decree-item:hover {
            border-color: #80d0c7;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .decree-info {
            flex: 1;
            min-width: 300px;
        }
        
        .decree-number {
            color: #13547a;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .decree-description {
            color: #666;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        
        .decree-details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #888;
            font-size: 0.9rem;
        }
        
        .decree-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-download-secondary {
            background: #13547a;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: var(--border-radius-small);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-download-secondary:hover {
            background: #0d3a58;
            color: white;
        }
        
        .btn-preview {
            background: transparent;
            color: #13547a;
            border: 2px solid #13547a;
            padding: 8px 20px;
            border-radius: var(--border-radius-small);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-preview:hover {
            background: #13547a;
            color: white;
        }

        /* TEAM SECTION */
        .team-section {
            margin-top: 50px;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .team-card {
            background: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }
        
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .team-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .team-name {
            color: #13547a;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .team-position {
            color: #80d0c7;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .team-qualification {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .team-contact {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .contact-link {
            color: #13547a;
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        
        .contact-link:hover {
            color: #80d0c7;
        }

        /* INFO BOX */
        .info-box {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: var(--border-radius-medium);
            padding: 30px;
            margin: 40px 0;
            color: white;
        }
        
        .info-box h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-box p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .info-box ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .info-box li {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }
        
        .info-box li:last-child {
            margin-bottom: 0;
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
        
        .quick-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .quick-links li {
            margin-bottom: 12px;
        }
        
        .quick-links li:last-child {
            margin-bottom: 0;
        }
        
        .quick-links a {
            color: var(--p-color);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: var(--border-radius-small);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .quick-links li.active a {
            background: #f0f8ff;
            color: #13547a;
            font-weight: 600;
        }
        
        .quick-links a:hover {
            background: #f0f8ff;
            color: #13547a;
            transform: translateX(5px);
        }
        
        .quick-links a i {
            color: #80d0c7;
            font-size: 0.9rem;
        }

        /* STATS CARD */
        .stats-card {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stats-card h4 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        /* CONTACT INFO */
        .contact-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .contact-card h4 {
            color: #13547a;
            margin-bottom: 20px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .contact-card h4 i {
            color: #80d0c7;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
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

        /* SEARCH BOX */
        .search-box {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .search-form .input-group {
            margin-bottom: 15px;
        }
        
        .search-form input {
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        
        .search-form input:focus {
            border-color: #80d0c7;
            box-shadow: none;
        }
        
        .btn-search {
            background: #13547a;
            color: white;
            border: 2px solid #13547a;
        }
        
        .btn-search:hover {
            background: #0d3a58;
            border-color: #0d3a58;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .decree-title {
                font-size: 1.8rem;
            }
            
            .content-card {
                padding: 30px;
            }
            
            .team-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
            
            .decree-document {
                padding: 30px 20px;
            }
            
            .decree-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .decree-info {
                min-width: 100%;
            }
            
            .decree-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .document-meta {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }
        
        @media (max-width: 576px) {
            .decree-title {
                font-size: 1.5rem;
            }
            
            .content-card {
                padding: 20px;
            }
            
            .document-title {
                font-size: 1.3rem;
            }
            
            .btn-download-primary {
                padding: 10px 25px;
                font-size: 1rem;
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

                    <li class="nav-item dropdown active">
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
                            <li class="active"><a class="dropdown-item" href="?page=surat-keputusan">Surat Keputusan Pengelola JDIH Sekretariat DPRD Kota Surakarta</a></li>
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
                    <h1 class="text-white">SURAT KEPUTUSAN PENGELOLA JDIH</h1>
                    <p class="subtitle">Surat Keputusan Resmi Pengelola Jaringan Dokumentasi dan Informasi Hukum Sekretariat DPRD Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Surat Keputusan</li>
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
                    
                    <!-- SEARCH BOX -->
                    <div class="search-box">
                        <h4>Cari Surat Keputusan</h4>
                        <form class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari berdasarkan nomor, tahun, atau judul SK...">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="year2025">
                                    <label class="form-check-label" for="year2025">
                                        Tahun 2025
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="year2024">
                                    <label class="form-check-label" for="year2024">
                                        Tahun 2024
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="year2023">
                                    <label class="form-check-label" for="year2023">
                                        Tahun 2023
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- CONTENT CARD -->
                    <div class="content-card">
                        <div class="decree-header">
                            <h1 class="decree-title">Surat Keputusan Pengelola JDIH</h1>
                            <h2 class="decree-subtitle">Sekretariat DPRD Kota Surakarta</h2>
                            <p class="text-muted">Dokumen resmi penunjukan dan pengangkatan pengelola JDIH Sekretariat DPRD Kota Surakarta</p>
                        </div>
                        
                        <!-- MAIN DECREE DOCUMENT -->
                        <div class="decree-document">
                            <div class="document-icon">
                                <i class="bi bi-file-earmark-medical"></i>
                            </div>
                            <h3 class="document-title">Surat Keputusan Tim Teknis Pengelola JDIH Sekretariat Dewan DPRD Surakarta Tahun 2025</h3>
                            
                            <div class="document-meta">
                                <div class="meta-item">
                                    <i class="bi bi-hash"></i>
                                    <span>Nomor: 001/SK-SETWAN/JDIH/DPRD-SKA/2025</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Tanggal: 2 Januari 2025</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-file-earmark"></i>
                                    <span>Ukuran: 1.59 MB</span>
                                </div>
                            </div>
                            
                            <p class="mb-4">Dokumen resmi penetapan Tim Teknis Pengelola JDIH untuk periode tahun 2025 yang ditandatangani oleh Sekretaris DPRD Kota Surakarta.</p>
                            
                            <a href="#" class="btn-download-primary">
                                <i class="bi bi-download"></i>
                                DOWNLOAD
                            </a>
                        </div>
                        
                        <!-- DECREE LIST -->
                        <div class="decree-list">
                            <h3 class="section-title">Daftar Surat Keputusan Lainnya</h3>
                            
                            <!-- SK 1 -->
                            <div class="decree-item">
                                <div class="decree-info">
                                    <div class="decree-number">SK No. 045/SK-SETWAN/JDIH/DPRD-SKA/2024</div>
                                    <p class="decree-description">Surat Keputusan Penunjukan Koordinator Teknis Pengelola JDIH Sekretariat DPRD Kota Surakarta</p>
                                    <div class="decree-details">
                                        <div class="detail-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>15 Desember 2024</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-file-earmark"></i>
                                            <span>892 KB</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-eye"></i>
                                            <span>156x diunduh</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="decree-actions">
                                    <a href="#" class="btn-preview">
                                        <i class="bi bi-eye"></i>
                                        Preview
                                    </a>
                                    <a href="#" class="btn-download-secondary">
                                        <i class="bi bi-download"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                            
                            <!-- SK 2 -->
                            <div class="decree-item">
                                <div class="decree-info">
                                    <div class="decree-number">SK No. 028/SK-SETWAN/JDIH/DPRD-SKA/2023</div>
                                    <p class="decree-description">Surat Keputusan Pembentukan Tim Implementasi Sistem JDIH Digital Sekretariat DPRD Kota Surakarta</p>
                                    <div class="decree-details">
                                        <div class="detail-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>3 Juli 2023</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-file-earmark"></i>
                                            <span>1.24 MB</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-eye"></i>
                                            <span>98x diunduh</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="decree-actions">
                                    <a href="#" class="btn-preview">
                                        <i class="bi bi-eye"></i>
                                        Preview
                                    </a>
                                    <a href="#" class="btn-download-secondary">
                                        <i class="bi bi-download"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                            
                            <!-- SK 3 -->
                            <div class="decree-item">
                                <div class="decree-info">
                                    <div class="decree-number">SK No. 012/SK-SETWAN/JDIH/DPRD-SKA/2022</div>
                                    <p class="decree-description">Surat Keputusan Pengangkatan Anggota Pengelola JDIH Sekretariat DPRD Kota Surakarta Periode 2022-2024</p>
                                    <div class="decree-details">
                                        <div class="detail-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>10 Maret 2022</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-file-earmark"></i>
                                            <span>756 KB</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="bi bi-eye"></i>
                                            <span>87x diunduh</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="decree-actions">
                                    <a href="#" class="btn-preview">
                                        <i class="bi bi-eye"></i>
                                        Preview
                                    </a>
                                    <a href="#" class="btn-download-secondary">
                                        <i class="bi bi-download"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TEAM SECTION -->
                        <div class="team-section">
                            <h3 class="section-title">Tim Pengelola JDIH 2025</h3>
                            <p class="mb-4">Berdasarkan Surat Keputusan No. 001/SK-SETWAN/JDIH/DPRD-SKA/2025, berikut adalah susunan Tim Teknis Pengelola JDIH:</p>
                            
                            <div class="team-grid">
                                <!-- Anggota 1 -->
                                <div class="team-card">
                                    <div class="team-avatar">BS</div>
                                    <h4 class="team-name">Budi Santoso, S.H., M.Hum.</h4>
                                    <p class="team-position">Koordinator Tim</p>
                                    <p class="team-qualification">Pegawai Negeri Sipil Ahli Pertama</p>
                                    <div class="team-contact">
                                        <a href="mailto:budi.santoso@dprd-surakarta.go.id" class="contact-link">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                        <a href="tel:+628123456789" class="contact-link">
                                            <i class="bi bi-telephone"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Anggota 2 -->
                                <div class="team-card">
                                    <div class="team-avatar">SW</div>
                                    <h4 class="team-name">Sri Wahyuni, S.H.</h4>
                                    <p class="team-position">Anggota Bidang Dokumentasi</p>
                                    <p class="team-qualification">Pegawai Negeri Sipil Ahli Pertama</p>
                                    <div class="team-contact">
                                        <a href="mailto:sri.wahyuni@dprd-surakarta.go.id" class="contact-link">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                        <a href="tel:+628123456788" class="contact-link">
                                            <i class="bi bi-telephone"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Anggota 3 -->
                                <div class="team-card">
                                    <div class="team-avatar">AR</div>
                                    <h4 class="team-name">Agus Riyanto, S.Kom.</h4>
                                    <p class="team-position">Anggota Bidang Teknologi Informasi</p>
                                    <p class="team-qualification">Pegawai Negeri Sipil Ahli Pertama</p>
                                    <div class="team-contact">
                                        <a href="mailto:agus.riyanto@dprd-surakarta.go.id" class="contact-link">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                        <a href="tel:+628123456787" class="contact-link">
                                            <i class="bi bi-telephone"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- INFO BOX -->
                        <div class="info-box">
                            <h3><i class="bi bi-info-circle me-2"></i> Informasi Penting</h3>
                            <p>Surat Keputusan Pengelola JDIH merupakan dokumen resmi yang memiliki kekuatan hukum dalam menetapkan:</p>
                            <ul>
                                <li>Struktur organisasi pengelola JDIH</li>
                                <li>Tugas, fungsi, dan tanggung jawab masing-masing anggota</li>
                                <li>Periode masa kerja tim pengelola</li>
                                <li>Wewenang dan kewajiban dalam pengelolaan sistem</li>
                            </ul>
                            <p class="mt-3">Surat Keputusan ini dapat dijadikan sebagai acuan dan referensi resmi dalam kegiatan pengelolaan JDIH Sekretariat DPRD Kota Surakarta.</p>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- STATS CARD -->
                    <div class="stats-card">
                        <h4><i class="bi bi-file-earmark-medical me-2"></i> Statistik Surat Keputusan</h4>
                        <div class="stat-number">15</div>
                        <div class="stat-label">Total SK Aktif</div>
                        <div class="stat-number">2025</div>
                        <div class="stat-label">SK Terbaru</div>
                    </div>
                    
                    <!-- QUICK LINKS -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul class="quick-links">
                            <li><a href="?page=profil-jdih"><i class="bi bi-house-door"></i> Profil JDIH</a></li>
                            <li><a href="?page=struktur-organisasi"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                            <li><a href="?page=dasar-hukum"><i class="bi bi-file-earmark-text"></i> Dasar Hukum</a></li>
                            <li class="active"><a href="?page=surat-keputusan"><i class="bi bi-file-earmark-medical"></i> Surat Keputusan</a></li>
                            <li><a href="?page=sop-pengelola"><i class="bi bi-list-check"></i> SOP Pengelola</a></li>
                            <li><a href="?page=alur-pengelolaan"><i class="bi bi-arrow-repeat"></i> Alur Pengelolaan</a></li>
                            <li><a href="?page=visi-misi"><i class="bi bi-bullseye"></i> Visi & Misi</a></li>
                        </ul>
                    </div>
                    
                    <!-- DOWNLOAD POPULAR -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Paling Sering Diunduh</h4>
                        <ul class="quick-links">
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div>
                                        <div>SK Tim Teknis 2025</div>
                                        <small class="text-muted">Diunduh 245 kali</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div>
                                        <div>SK Koordinator 2024</div>
                                        <small class="text-muted">Diunduh 187 kali</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div>
                                        <div>SK Implementasi Digital 2023</div>
                                        <small class="text-muted">Diunduh 156 kali</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div>
                                        <div>SK Anggota 2022</div>
                                        <small class="text-muted">Diunduh 132 kali</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- CONTACT INFO -->
                    <div class="contact-card">
                        <h4><i class="bi bi-info-circle"></i> Informasi Kontak</h4>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Koordinator JDIH</h5>
                                <p>Budi Santoso, SH., M.Hum.</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Telepon</h5>
                                <p><a href="tel:+62271234567">(0271) 234567</a> ext. 123</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email</h5>
                                <p><a href="mailto:jdih@dprd-surakarta.go.id">jdih@dprd-surakarta.go.id</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Jam Konsultasi</h5>
                                <p>Senin - Kamis: 09.00 - 15.00<br>
                                Jumat: 09.00 - 11.30</p>
                            </div>
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
        // Fungsi untuk download utama
        document.querySelector('.btn-download-primary').addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
                const docTitle = document.querySelector('.document-title').textContent;
                const docSize = '1.59 MB';
                
                if (confirm(`Unduh dokumen: ${docTitle}\n\nUkuran file: ${docSize}\n\nKlik OK untuk melanjutkan.`)) {
                    alert('Download akan dimulai...\n\nDalam implementasi nyata, file PDF akan didownload ke perangkat Anda.');
                }
            }
        });
        
        // Fungsi untuk download secondary
        document.querySelectorAll('.btn-download-secondary').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                    const docTitle = this.closest('.decree-item').querySelector('.decree-number').textContent;
                    const docSize = this.closest('.decree-item').querySelector('.decree-details .detail-item:nth-child(2) span').textContent;
                    
                    if (confirm(`Unduh dokumen: ${docTitle}\n\nUkuran file: ${docSize}\n\nKlik OK untuk melanjutkan.`)) {
                        alert('Download akan dimulai...\n\nDalam implementasi nyata, file PDF akan didownload ke perangkat Anda.');
                    }
                }
            });
        });
        
        // Fungsi untuk preview
        document.querySelectorAll('.btn-preview').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.decree-item').querySelector('.decree-number').textContent;
                
                alert(`Membuka preview dokumen: ${docTitle}\n\nDalam implementasi nyata, ini akan membuka preview PDF di jendela baru.`);
            });
        });
        
        // Fungsi untuk pencarian
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const searchQuery = this.querySelector('input[type="text"]').value;
            const yearFilters = Array.from(this.querySelectorAll('input[type="checkbox"]:checked'))
                .map(cb => cb.nextElementSibling.textContent.trim())
                .join(', ');
            
            if (!searchQuery.trim() && !yearFilters) {
                alert('Masukkan kata kunci pencarian atau pilih filter tahun!');
                return;
            }
            
            alert(`Mencari Surat Keputusan dengan:\nKata kunci: "${searchQuery}"\nFilter tahun: ${yearFilters || 'Tidak ada'}\n\nDalam implementasi nyata, ini akan melakukan filter pada daftar dokumen.`);
        });
        
        // Fungsi untuk menu dropdown aktif
        document.addEventListener('DOMContentLoaded', function() {
            const tentangKamiDropdown = document.querySelector('#dropdownTentangKami');
            if (tentangKamiDropdown) {
                tentangKamiDropdown.classList.add('active');
            }
        });
        
        // Fungsi untuk kontak email
        document.querySelectorAll('a[href^="mailto:"]').forEach(link => {
            link.addEventListener('click', function() {
                const email = this.getAttribute('href').replace('mailto:', '');
                console.log(`Membuka email ke: ${email}`);
            });
        });
        
        // Fungsi untuk kontak telepon
        document.querySelectorAll('a[href^="tel:"]').forEach(link => {
            link.addEventListener('click', function() {
                const phone = this.getAttribute('href').replace('tel:', '');
                console.log(`Menghubungi: ${phone}`);
            });
        });
    </script>

</body>
</html>