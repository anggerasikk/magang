<?php
// struktur-organisasi.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struktur Organisasi - JDIH DPRD Kota Surakarta</title>
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
            padding-top: 70px; /* Diperkecil untuk mengurangi celah */
            margin: 0;
        }
        
        /* HEADER SECTION - DIUBAH UNTUK MENGHILANGKAN CELAH */
        .page-header {
            background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
            padding: 30px 0 25px 0;
            margin-top: -10px; /* Mendorong ke atas */
            color: white;
            position: relative;
            top: -10px; /* Mengurangi posisi */
        }
        
        .page-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 8px;
        }
        
        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
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

        /* MAIN CONTENT - DIUBAH UNTUK MENGHILANGKAN CELAH */
        .main-content {
            padding: 20px 0 40px 0;
            background: #f0f8ff;
            margin-top: -5px; /* Mendorong konten lebih dekat ke header */
        }
        
        .content-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid #80d0c7;
        }

        /* CARD STYLING */
        .content-card {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .content-card h4 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 18px;
            font-size: 1.3rem;
        }
        
        .content-card p {
            color: var(--p-color);
            font-size: var(--p-font-size);
            line-height: 1.6;
        }

        /* IMAGE CONTAINER */
        .struktur-image-container {
            background: white;
            border-radius: var(--border-radius-medium);
            padding: 18px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }
        
        .struktur-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        /* INFO BOX */
        .info-box {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 22px;
            margin-bottom: 25px;
        }
        
        .info-box h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 12px;
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
            padding: 22px;
            margin-bottom: 25px;
        }
        
        .sidebar-menu h4 {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 18px;
            padding-bottom: 8px;
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
            margin-bottom: 10px;
        }
        
        .sidebar-menu ul li:last-child {
            margin-bottom: 0;
        }
        
        .sidebar-menu ul li a {
            color: var(--p-color);
            text-decoration: none;
            padding: 10px 12px;
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

        /* DOWNLOAD BUTTON */
        .download-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-large);
            padding: 14px 22px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .download-btn:hover {
            background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }

        /* PERBAIKAN UNTUK MENGHILANGKAN CELAH */
        .navbar-custom {
            margin-bottom: 0;
            border-bottom: none;
        }
        
        .site-footer {
            margin-top: 0;
            padding-top: 40px;
        }
        
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        
        .col-lg-8, .col-lg-4 {
            padding-left: 8px;
            padding-right: 8px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding-top: 60px; /* Diperkecil untuk mobile */
            }
            
            .page-header {
                padding: 20px 0 18px 0;
                margin-top: -8px;
                top: -8px;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .main-content {
                padding: 15px 0 30px 0;
            }
            
            .content-card,
            .sidebar-menu,
            .info-box {
                padding: 18px;
            }
            
            .struktur-image-container {
                padding: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
                margin-bottom: 5px;
            }
            
            .page-header {
                padding: 15px 0 12px 0;
            }
            
            .content-title {
                font-size: 1.5rem;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }
            
            .main-content {
                padding: 10px 0 25px 0;
            }
            
            .row {
                margin-left: -5px;
                margin-right: -5px;
            }
            
            .col-lg-8, .col-lg-4 {
                padding-left: 5px;
                padding-right: 5px;
            }
        }
    </style>
</head>

<body id="top">

    <!-- INCLUDE HEADER -->
    <?php include('header.php'); ?>

    <!-- HEADER SECTION -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-white">STRUKTUR ORGANISASI</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Struktur Organisasi</li>
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
                    <div class="content-card">
                        <h3 class="content-title">Susunan Organisasi JDIH DPRD Kota Surakarta</h3>
                        
                        <div class="struktur-image-container">
                            <img src="images/struktur/pengarah.jpg" 
                                 alt="Struktur Organisasi JDIH DPRD Kota Surakarta" 
                                 class="struktur-image">
                        </div>
                        
                        <div class="info-box">
                            <h4><i class="bi bi-info-circle"></i> Tentang Struktur Organisasi</h4>
                            <p>
                                Struktur organisasi JDIH DPRD Kota Surakarta dirancang untuk mendukung 
                                pengelolaan dokumentasi dan informasi hukum secara terintegrasi dan 
                                berkelanjutan. Setiap unit memiliki peran dan tanggung jawab yang jelas 
                                dalam mendukung fungsi JDIH sebagai pusat informasi hukum.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- INFO BOX -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                        <p class="mb-0">
                            Struktur ini berfungsi sebagai landasan organisasi dalam 
                            pengelolaan produk hukum, monografi, dan informasi lainnya 
                            yang terkait dengan tugas dan fungsi DPRD Kota Surakarta.
                        </p>
                    </div>

                    <!-- MENU TERKAIT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul>
                            <li>
                                <a href="?page=alur-pengelolaan">
                                    <i class="bi bi-diagram-3"></i>
                                    Alur Pengelolaan Produk Hukum
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
                            <li>
                                <a href="?page=keputusan-dprd">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Keputusan DPRD
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- DOWNLOAD BOX -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download</h4>
                        <p class="mb-3">Unduh gambar struktur organisasi dalam format high-resolution:</p>
                        <a href="images/struktur/pengarah.jpg" download class="download-btn">
                            <i class="bi bi-download"></i>
                            Download Struktur Organisasi
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- INCLUDE FOOTER -->
    <?php include('footer.php'); ?>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>

</body>
</html>