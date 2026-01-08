<?php
// video-profil.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Video Profil - JDIH DPRD Kota Surakarta</title>
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

        /* VIDEO CONTAINER */
        .video-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }
        
        .video-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: var(--border-radius-medium);
            margin-bottom: 25px;
            background: #000;
        }
        
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-info {
            padding: 20px;
            background: #f8f9fa;
            border-radius: var(--border-radius-small);
            margin-top: 20px;
        }
        
        .video-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .video-description {
            color: var(--p-color);
            font-size: var(--p-font-size);
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .video-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.95rem;
        }
        
        .meta-item i {
            color: #80d0c7;
        }

        /* VIDEO LIST */
        .video-list-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .video-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }
        
        @media (max-width: 768px) {
            .video-list {
                grid-template-columns: 1fr;
            }
        }
        
        .video-card {
            background: #f8f9fa;
            border-radius: var(--border-radius-small);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            cursor: pointer;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .video-thumbnail {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            overflow: hidden;
        }
        
        .video-thumbnail img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .video-card:hover .video-thumbnail img {
            transform: scale(1.05);
        }
        
        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(19, 84, 122, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .video-card:hover .play-overlay {
            opacity: 1;
        }
        
        .play-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #13547a;
            font-size: 1.5rem;
        }
        
        .video-card-content {
            padding: 20px;
        }
        
        .video-card-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .video-card-desc {
            color: var(--p-color);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .video-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #666;
        }
        
        .video-card-duration {
            background: #13547a;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: 500;
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

        /* SOCIAL SHARE */
        .social-share {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #f0f0f0;
        }
        
        .share-label {
            color: #13547a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .share-buttons {
            display: flex;
            gap: 10px;
        }
        
        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .share-btn:hover {
            transform: translateY(-3px);
        }
        
        .share-btn.facebook {
            background: #3b5998;
        }
        
        .share-btn.twitter {
            background: #1da1f2;
        }
        
        .share-btn.whatsapp {
            background: #25d366;
        }
        
        .share-btn.link {
            background: #13547a;
        }

        /* MODAL STYLES */
        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .video-modal.active {
            display: flex;
        }
        
        .modal-content {
            width: 90%;
            max-width: 900px;
            position: relative;
        }
        
        .modal-video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
        }
        
        .modal-video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .close-modal {
            position: absolute;
            top: -40px;
            right: 0;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close-modal:hover {
            color: #80d0c7;
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
            
            .video-container,
            .video-list-container,
            .sidebar-menu,
            .contact-info,
            .portal-links {
                padding: 20px;
            }
            
            .video-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .social-share {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .modal-content {
                width: 95%;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .video-title {
                font-size: 1.3rem;
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
                        <a class="nav-link" href="../index.php">Beranda</a>
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
                    <h1 class="text-white">VIDEO PROFIL JDIH</h1>
                    <p class="subtitle">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Video Profil</li>
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
                    
                    <!-- VIDEO UTAMA -->
                    <div class="video-container">
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/rko4gjE72Gc" 
                                    title="Video Profil JDIH DPRD Kota Surakarta" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                        
                        <h2 class="video-title">JDIH DPRD Kota Surakarta - Video Profil</h2>
                        
                        <div class="video-description">
                            <p>
                                Video profil ini memperkenalkan Jaringan Dokumentasi dan Informasi Hukum (JDIH) 
                                Sekretariat DPRD Kota Surakarta. Dalam video ini, Anda akan melihat:
                            </p>
                            <ul>
                                <li>Visi dan misi JDIH DPRD Kota Surakarta</li>
                                <li>Fasilitas dan infrastruktur yang dimiliki</li>
                                <li>Proses pengelolaan produk hukum</li>
                                <li>Pelayanan yang diberikan kepada masyarakat</li>
                                <li>Testimoni dari pengguna layanan JDIH</li>
                            </ul>
                            <p>
                                Video ini diharapkan dapat memberikan gambaran yang komprehensif tentang 
                                peran dan fungsi JDIH dalam mendukung transparansi dan aksesibilitas 
                                informasi hukum di Kota Surakarta.
                            </p>
                        </div>
                        
                        <div class="video-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar"></i>
                                <span>Dipublikasikan: 15 Januari 2024</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>Durasi: 5:42 menit</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-eye"></i>
                                <span>Ditonton: 1.245 kali</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-youtube"></i>
                                <span>YouTube</span>
                            </div>
                        </div>
                        
                        <!-- SOCIAL SHARE -->
                        <div class="social-share">
                            <div class="share-label">Bagikan video ini:</div>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=https://youtu.be/rko4gjE72Gc" 
                                   target="_blank" 
                                   class="share-btn facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=https://youtu.be/rko4gjE72Gc&text=Video%20Profil%20JDIH%20DPRD%20Kota%20Surakarta" 
                                   target="_blank" 
                                   class="share-btn twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text=Tonton%20Video%20Profil%20JDIH%20DPRD%20Kota%20Surakarta%3A%20https%3A%2F%2Fyoutu.be%2Frko4gjE72Gc" 
                                   target="_blank" 
                                   class="share-btn whatsapp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="#" 
                                   onclick="navigator.clipboard.writeText('https://youtu.be/rko4gjE72Gc'); alert('Link video disalin!');" 
                                   class="share-btn link">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- VIDEO LAINNYA -->
                    <div class="video-list-container">
                        <h3 class="section-title">Video Lainnya</h3>
                        
                        <div class="video-list">
                            <!-- Video 2 - Tutorial -->
                            <div class="video-card" data-video-id="-aAbLqOKyd8" data-video-title="Tutorial Penggunaan Website JDIH">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/-aAbLqOKyd8/maxresdefault.jpg" alt="Tutorial Penggunaan Website JDIH">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">Tutorial Penggunaan Website JDIH</h4>
                                    <p class="video-card-desc">Panduan lengkap cara menggunakan website JDIH DPRD Kota Surakarta untuk mencari dan mengakses produk hukum.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 7:30</span>
                                        <span class="video-card-duration">Tutorial</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video 3 - Proses Legislasi -->
                            <div class="video-card" data-video-id="B5XrqNA9FJ8" data-video-title="Proses Legislasi di DPRD Surakarta">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/B5XrqNA9FJ8/maxresdefault.jpg" alt="Proses Legislasi di DPRD Surakarta">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">Proses Legislasi di DPRD Surakarta</h4>
                                    <p class="video-card-desc">Proses pembentukan peraturan daerah mulai dari pengajuan hingga pengesahan. Pelajari tahapan-tahapannya.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 9:15</span>
                                        <span class="video-card-duration">Edukasi</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video 4 - Wawancara -->
                            <div class="video-card" data-video-id="IQ9IijAz8Ow" data-video-title="Wawancara dengan Kepala JDIH">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/IQ9IijAz8Ow/maxresdefault.jpg" alt="Wawancara dengan Kepala JDIH">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">Wawancara dengan Kepala JDIH</h4>
                                    <p class="video-card-desc">Wawancara eksklusif dengan Kepala JDIH DPRD Kota Surakarta tentang tantangan dan prestasi.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 12:45</span>
                                        <span class="video-card-duration">Wawancara</span>
                                    </div>
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
                                <a href="?page=profil-jdih">
                                    <i class="bi bi-person-badge"></i>
                                    Profil JDIH
                                </a>
                            </li>
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
                                <a href="?page=visi-misi">
                                    <i class="bi bi-bullseye"></i>
                                    Visi & Misi
                                </a>
                            </li>
                            <li>
                                <a href="?page=galeri">
                                    <i class="bi bi-images"></i>
                                    Galeri Foto
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
                            <h5>YouTube Channel</h5>
                            <p>
                                <a href="https://youtube.com/@jdihdprdsurakarta" target="_blank">
                                    JDIH DPRD Surakarta
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- PORTAL LINKS -->
                    <div class="portal-links">
                        <h4><i class="bi bi-link"></i> Link Portal</h4>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-youtube"></i>
                            <span>YouTube JDIH</span>
                            <a href="https://youtube.com/@jdihdprdsurakarta" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>Website DPRD</span>
                            <a href="https://dprd-surakarta.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>Pemkot Surakarta</span>
                            <a href="https://surakarta.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-globe"></i>
                            <span>JDIH Nasional</span>
                            <a href="https://jdih.kemenkumham.go.id" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-instagram"></i>
                            <span>Instagram JDIH</span>
                            <a href="https://instagram.com/jdih_surakarta" target="_blank"></a>
                        </div>
                        
                        <div class="portal-link-item">
                            <i class="bi bi-facebook"></i>
                            <span>Facebook JDIH</span>
                            <a href="https://facebook.com/jdihsurakarta" target="_blank"></a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD VIDEO -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Video</h4>
                        <p class="mb-3">Unduh video profil untuk presentasi atau arsip:</p>
                        <a href="downloads/video-profil-jdih.mp4" download class="btn custom-btn w-100 mb-2">
                            <i class="bi bi-file-earmark-play"></i>
                            Download MP4 (1080p)
                        </a>
                        <a href="downloads/transkrip-video.pdf" download class="btn custom-border-btn w-100">
                            <i class="bi bi-file-earmark-text"></i>
                            Download Transkrip
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- VIDEO MODAL -->
    <div class="video-modal" id="videoModal">
        <div class="modal-content">
            <button class="close-modal" id="closeModal">&times;</button>
            <div class="modal-video-wrapper">
                <iframe id="modalVideoPlayer" 
                        src="" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

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
                            <a href="?page=video-profil" class="site-footer-link">Video Profil</a>
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

    <script>
        // Script untuk video player dan interaksi
        $(document).ready(function() {
            // Update view count (simulasi)
            function updateViewCount() {
                const viewCount = document.querySelector('.meta-item:nth-child(3) span');
                if (viewCount) {
                    const currentViews = parseInt(viewCount.textContent.replace('Ditonton: ', '').replace(' kali', ''));
                    viewCount.textContent = `Ditonton: ${currentViews + 1} kali`;
                }
            }
            
            // Simulate view count update after 3 seconds
            setTimeout(updateViewCount, 3000);
            
            // Video card click handler
            $('.video-card').on('click', function(e) {
                const videoId = $(this).data('video-id');
                const videoTitle = $(this).data('video-title');
                
                if (videoId) {
                    openVideoModal(videoId, videoTitle);
                }
            });
            
            // Modal functionality
            const modal = $('#videoModal');
            const modalPlayer = $('#modalVideoPlayer');
            const closeBtn = $('#closeModal');
            
            function openVideoModal(videoId, videoTitle) {
                modalPlayer.attr('src', `https://www.youtube.com/embed/${videoId}?autoplay=1`);
                modal.addClass('active');
                $('body').css('overflow', 'hidden');
                
                // Update page title in modal if needed
                document.title = `${videoTitle} - JDIH DPRD`;
            }
            
            function closeVideoModal() {
                modal.removeClass('active');
                modalPlayer.attr('src', '');
                $('body').css('overflow', 'auto');
                document.title = 'Video Profil - JDIH DPRD Kota Surakarta';
            }
            
            // Close modal events
            closeBtn.on('click', closeVideoModal);
            
            modal.on('click', function(e) {
                if (e.target === this) {
                    closeVideoModal();
                }
            });
            
            // Close with ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && modal.hasClass('active')) {
                    closeVideoModal();
                }
            });
            
            // Copy link confirmation
            $('.share-btn.link').on('click', function(e) {
                e.preventDefault();
                const button = $(this);
                const originalHTML = button.html();
                
                navigator.clipboard.writeText('https://youtu.be/rko4gjE72Gc').then(() => {
                    button.html('<i class="bi bi-check"></i>');
                    button.css('background', '#25d366');
                    
                    setTimeout(function() {
                        button.html(originalHTML);
                        button.css('background', '#13547a');
                    }, 2000);
                });
            });
            
            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = this.hash;
                var $target = $(target);
                
                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top - 80
                }, 900, 'swing', function() {
                    window.location.hash = target;
                });
            });
        });
    </script>

</body>
</html>