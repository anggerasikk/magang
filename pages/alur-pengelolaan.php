<?php
// alur-pengelolaan.php
$pageTitle = 'Alur Pengelolaan Produk Hukum - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

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
    .alur-image-container {
        background: white;
        border-radius: var(--border-radius-medium);
        padding: 18px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        text-align: center;
    }
    
    .alur-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
        margin: 0 auto;
        display: block;
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

    /* TAMBAHAN UNTUK DESKRIPSI GAMBAR */
    .image-caption {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        margin-top: 10px;
        font-style: italic;
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
        
        .alur-image-container {
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

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>ALUR PENGELOLAAN PRODUK HUKUM</h1>
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
                <div class="content-card">
                    <h3 class="content-title">Alur Pengelolaan Produk Hukum JDIH DPRD Kota Surakarta</h3>
                    
                    <div class="alur-image-container">
                        <img src="images/alur.jpg" 
                             alt="Alur Pengelolaan Produk Hukum JDIH DPRD Kota Surakarta" 
                             class="alur-image">
                        <div class="image-caption">
                            Diagram Alur Pengelolaan Produk Hukum JDIH DPRD Kota Surakarta
                        </div>
                    </div>
                    
                    <div class="info-box">
                        <h4><i class="bi bi-info-circle"></i> Tentang Alur Pengelolaan</h4>
                        <p>
                            Alur pengelolaan produk hukum di JDIH DPRD Kota Surakarta dirancang untuk memastikan 
                            bahwa seluruh dokumen hukum yang dihasilkan dapat dikelola secara sistematis, 
                            transparan, dan akuntabel. Setiap tahapan dalam alur ini dilakukan dengan mengikuti 
                            standar operasional prosedur yang telah ditetapkan.
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
                        Alur pengelolaan ini merupakan panduan baku dalam pengelolaan 
                        produk hukum, mulai dari penerimaan, verifikasi, katalogisasi, 
                        digitalisasi, hingga publikasi dan pemeliharaan dokumen.
                    </p>
                </div>

                <!-- MENU TERKAIT -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                    <ul>
                        <li>
                            <a href="struktur-organisasi.php">
                                <i class="bi bi-diagram-3"></i>
                                Struktur Organisasi
                            </a>
                        </li>
                        <li>
                            <a href="sop-pengelola.php">
                                <i class="bi bi-clipboard-check"></i>
                                SOP Pengelola JDIH
                            </a>
                        </li>
                        <li>
                            <a href="dasar-hukum.php">
                                <i class="bi bi-journal-text"></i>
                                Dasar Hukum JDIH
                            </a>
                        </li>
                        <li>
                            <a href="visi-misi.php">
                                <i class="bi bi-bullseye"></i>
                                Visi & Misi
                            </a>
                        </li>
                        <li>
                            <a href="keputusan-dprd.php">
                                <i class="bi bi-file-earmark-text"></i>
                                Keputusan DPRD
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- DOWNLOAD BOX -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Download</h4>
                    <p class="mb-3">Unduh gambar alur pengelolaan dalam format high-resolution:</p>
                    <a href="images/alur.jpg" download class="download-btn">
                        <i class="bi bi-download"></i>
                        Download Diagram Alur
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>