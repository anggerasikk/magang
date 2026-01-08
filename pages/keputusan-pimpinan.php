<?php
// keputusan-pimpinan.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Keputusan Pimpinan DPRD - JDIH DPRD Kota Surakarta</title>
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

        /* DOCUMENT LIST */
        .document-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .document-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .document-table th {
            background: #f8f9fa;
            color: #13547a;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            font-family: 'Montserrat', sans-serif;
        }
        
        .document-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: var(--p-color);
        }
        
        .document-table tr:hover {
            background: #f0f8ff;
        }
        
        .document-number {
            color: #13547a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .document-title {
            font-weight: 500;
        }
        
        .document-date {
            color: #666;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        .document-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            color: #13547a;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background: #13547a;
            color: white;
            transform: translateY(-2px);
        }

        /* YEAR FILTER */
        .year-filter {
            background: #f8f9fa;
            border-radius: var(--border-radius-medium);
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .year-filter h5 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .year-filter h5 i {
            color: #80d0c7;
            margin-right: 10px;
        }
        
        .year-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .year-btn {
            padding: 8px 20px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius-small);
            color: #13547a;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .year-btn:hover, .year-btn.active {
            background: #13547a;
            color: white;
            border-color: #13547a;
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
            
            .document-container,
            .sidebar-menu {
                padding: 20px;
            }
            
            .document-table {
                display: block;
                overflow-x: auto;
            }
            
            .document-actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn {
                width: 32px;
                height: 32px;
            }
            
            .year-buttons {
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
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

                    <!-- PERUNDANGAN - ACTIVE -->
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownPerundangan"
                            role="button" data-bs-toggle="dropdown">
                            Perundang-undangan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=keputusan-dprd">Keputusan DPRD</a></li>
                            <li><a class="dropdown-item active" href="?page=keputusan-pimpinan">Keputusan Pimpinan DPRD</a></li>
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
                    <h1 class="text-white">KEPUTUSAN PIMPINAN DPRD</h1>
                    <p class="subtitle">Keputusan Pimpinan Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Keputusan Pimpinan DPRD</li>
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
                    
                    <!-- FILTER TAHUN -->
                    <div class="year-filter">
                        <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h5>
                        <div class="year-buttons">
                            <a href="#" class="year-btn active">Semua</a>
                            <a href="#" class="year-btn">2024</a>
                            <a href="#" class="year-btn">2023</a>
                            <a href="#" class="year-btn">2022</a>
                            <a href="#" class="year-btn">2021</a>
                            <a href="#" class="year-btn">2020</a>
                            <a href="#" class="year-btn">2019</a>
                            <a href="#" class="year-btn">2018</a>
                            <a href="#" class="year-btn">Lainnya</a>
                        </div>
                    </div>
                    
                    <!-- DAFTAR KEPUTUSAN PIMPINAN DPRD -->
                    <div class="document-container">
                        <h3 class="section-title">Daftar Keputusan Pimpinan DPRD Kota Surakarta</h3>
                        
                        <div class="table-responsive">
                            <table class="document-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Nomor & Tahun</th>
                                        <th style="width: 50%;">Tentang</th>
                                        <th style="width: 15%;">Tanggal</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="document-number">23/PIMP/DPRD/2024</td>
                                        <td class="document-title">Penetapan Agenda Sidang Paripurna DPRD Kota Surakarta Bulan Juli 2024</td>
                                        <td class="document-date">5 Maret 2024</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">18/PIMP/DPRD/2024</td>
                                        <td class="document-title">Penetapan Panitia Khusus Pembahasan Rancangan Peraturan Daerah tentang Pengelolaan Sampah</td>
                                        <td class="document-date">20 Februari 2024</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">15/PIMP/DPRD/2024</td>
                                        <td class="document-title">Penjadwalan Kunjungan Kerja Komisi-Komisi DPRD ke Kelurahan di Wilayah Kota Surakarta</td>
                                        <td class="document-date">10 Januari 2024</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">28/PIMP/DPRD/2023</td>
                                        <td class="document-title">Penetapan Jadwal Reses Anggota DPRD Kota Surakarta Periode 2023-2024</td>
                                        <td class="document-date">15 Desember 2023</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">22/PIMP/DPRD/2023</td>
                                        <td class="document-title">Penetapan Tim Kerja Penyusunan Panduan Pengawasan DPRD terhadap Pelaksanaan APBD</td>
                                        <td class="document-date">10 Oktober 2023</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">17/PIMP/DPRD/2023</td>
                                        <td class="document-title">Penetapan Mekanisme Konsultasi antara Pimpinan DPRD dengan Pemerintah Daerah</td>
                                        <td class="document-date">5 Agustus 2023</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">32/PIMP/DPRD/2022</td>
                                        <td class="document-title">Penetapan Tata Cara Penerimaan Kunjungan Masyarakat di DPRD Kota Surakarta</td>
                                        <td class="document-date">20 November 2022</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="document-number">20/PIMP/DPRD/2022</td>
                                        <td class="document-title">Penetapan Prosedur Pengajuan Hak Interpelasi dan Hak Angket oleh Anggota DPRD</td>
                                        <td class="document-date">5 Juli 2022</td>
                                        <td class="document-actions">
                                            <a href="#" class="action-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="action-btn" title="Unduh">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
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
                    </div>
                    
                    <!-- INFORMASI -->
                    <div class="document-container">
                        <h3 class="section-title">Informasi Keputusan Pimpinan DPRD</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h5><i class="bi bi-info-circle text-primary"></i> Definisi</h5>
                                    <p>Keputusan Pimpinan DPRD adalah keputusan yang ditetapkan oleh Pimpinan DPRD dalam rangka pelaksanaan tugas pimpinan dewan, pengaturan tata tertib, dan pengelolaan kegiatan DPRD.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h5><i class="bi bi-journal-check text-primary"></i> Ruang Lingkup</h5>
                                    <p>Meliputi pengaturan agenda sidang, pembentukan panitia khusus, penjadwalan kegiatan, dan pengaturan mekanisme kerja antara DPRD dengan pemerintah daerah.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU PERUNDANGAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-list"></i> Menu Perundangan</h4>
                        <ul>
                            <li>
                                <a href="?page=perundangan">
                                    <i class="bi bi-grid"></i>
                                    Semua Kategori
                                </a>
                            </li>
                            <li>
                                <a href="?page=keputusan-dprd">
                                    <i class="bi bi-file-earmark-medical"></i>
                                    Keputusan DPRD
                                </a>
                            </li>
                            <li>
                                <a href="?page=keputusan-sekretaris">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Keputusan Sekretaris DPRD
                                </a>
                            </li>
                            <li class="active">
                                <a href="?page=keputusan-pimpinan">
                                    <i class="bi bi-file-earmark-ruled"></i>
                                    Keputusan Pimpinan DPRD
                                </a>
                            </li>
                            <li>
                                <a href="?page=peraturan-dprd">
                                    <i class="bi bi-journal-text"></i>
                                    Peraturan DPRD
                                </a>
                            </li>
                            <li>
                                <a href="?page=perda">
                                    <i class="bi bi-journal-bookmark"></i>
                                    Peraturan Daerah
                                </a>
                            </li>
                            <li>
                                <a href="?page=english-legal">
                                    <i class="bi bi-translate"></i>
                                    English Legal Products
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- STATISTIK -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-bar-chart"></i> Statistik Keputusan Pimpinan</h4>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between mb-2">
                                <span>Total Keputusan:</span>
                                <span class="fw-bold">156 dokumen</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>Tahun 2024:</span>
                                <span class="fw-bold">8 dokumen</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>Tahun 2023:</span>
                                <span class="fw-bold">22 dokumen</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>Tahun 2022:</span>
                                <span class="fw-bold">28 dokumen</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tahun 2021:</span>
                                <span class="fw-bold">30 dokumen</span>
                            </li>
                        </ul>
                    </div>

                    <!-- DOKUMEN TERPOPULER -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-fire"></i> Dokumen Populer</h4>
                        <ul>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-text"></i>
                                    Keputusan No. 23/2024
                                    <span class="badge bg-primary ms-2">432x</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-text"></i>
                                    Keputusan No. 18/2024
                                    <span class="badge bg-primary ms-2">398x</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-text"></i>
                                    Keputusan No. 28/2023
                                    <span class="badge bg-primary ms-2">376x</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-text"></i>
                                    Keputusan No. 22/2023
                                    <span class="badge bg-primary ms-2">354x</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-file-text"></i>
                                    Keputusan No. 32/2022
                                    <span class="badge bg-primary ms-2">321x</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- DOWNLOAD -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download</h4>
                        <p class="mb-3">Unduh kumpulan keputusan Pimpinan DPRD dalam format terkompresi:</p>
                        <a href="downloads/keputusan-pimpinan-2024.zip" class="btn custom-btn w-100 mb-2">
                            <i class="bi bi-file-earmark-zip"></i>
                            Keputusan 2024 (ZIP)
                        </a>
                        <a href="downloads/keputusan-pimpinan-2020-2023.zip" class="btn custom-border-btn w-100">
                            <i class="bi bi-file-earmark-zip"></i>
                            Keputusan 2020-2023 (ZIP)
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
                            <a href="?page=perundangan" class="site-footer-link">Perundangan</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="?page=keputusan-pimpinan" class="site-footer-link">Keputusan Pimpinan</a>
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
        // Script untuk halaman keputusan pimpinan DPRD
        $(document).ready(function() {
            // Filter tahun
            $('.year-btn').on('click', function(e) {
                e.preventDefault();
                $('.year-btn').removeClass('active');
                $(this).addClass('active');
                
                const tahun = $(this).text();
                if (tahun === 'Semua') {
                    $('.document-table tbody tr').show();
                } else {
                    $('.document-table tbody tr').each(function() {
                        const docNumber = $(this).find('.document-number').text();
                        if (docNumber.includes(tahun)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
            
            // Action button functionality
            $('.action-btn').on('click', function(e) {
                e.preventDefault();
                const action = $(this).attr('title');
                const docNumber = $(this).closest('tr').find('.document-number').text();
                
                if (action === 'Lihat') {
                    alert(`Membuka dokumen: ${docNumber}\n\nFitur preview dokumen akan ditampilkan di sini.`);
                } else if (action === 'Unduh') {
                    alert(`Mengunduh dokumen: ${docNumber}\n\nDownload akan dimulai secara otomatis.`);
                }
            });
            
            // Pagination interaction
            $('.page-link').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled')) {
                    $('.page-item').removeClass('active');
                    $(this).parent().addClass('active');
                    
                    const pageText = $(this).text();
                    if (!isNaN(pageText)) {
                        console.log(`Navigasi ke halaman: ${pageText}`);
                    }
                }
            });
        });
    </script>

</body>
</html>