<?php
// struktur-organisasi.php
$pageTitle = 'Struktur Organisasi - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

<style>
    /* RESET UNTUK HALAMAN INI */
    .struktur-page {
        margin: 0;
        padding: 0;
        width: 100%;
    }
    
    /* HEADER SECTION - DIPERBAIKI */
    .page-header {
        background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
        padding: 40px 0 30px 0;
        margin: 0;
        color: white;
        position: relative;
        width: 100%;
    }
    
    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 10px;
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

    /* MAIN CONTENT - DIPERBAIKI */
    .main-content {
        padding: 40px 0 60px 0;
        background: #f0f8ff;
        margin: 0;
        width: 100%;
    }
    
    .content-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #80d0c7;
    }

    /* CARD STYLING */
    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
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
        margin-bottom: 20px;
        font-size: 1.3rem;
    }
    
    .content-card p {
        color: #555;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* IMAGE CONTAINER */
    .struktur-image-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        text-align: center;
    }
    
    .struktur-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        margin: 0 auto;
        display: block;
    }

    /* INFO BOX */
    .info-box {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-radius: 10px;
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
        border-radius: 10px;
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
        margin-bottom: 10px;
    }
    
    .sidebar-menu ul li:last-child {
        margin-bottom: 0;
    }
    
    .sidebar-menu ul li a {
        color: #555;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 6px;
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
        border-radius: 8px;
        padding: 15px 25px;
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

    /* TAMBAHAN UNTUK DESKRIPSI GAMBAR */
    .image-caption {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        margin-top: 15px;
        font-style: italic;
    }

    /* PERBAIKAN LINK MENU TERKAIT */
    .sidebar-menu ul li a[href*="?page="] {
        color: #555;
    }
    
    .sidebar-menu ul li a[href*="?page="]:hover {
        color: #13547a;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 30px 0 25px 0;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .main-content {
            padding: 30px 0 50px 0;
        }
        
        .content-card,
        .sidebar-menu,
        .info-box {
            padding: 20px;
        }
        
        .struktur-image-container {
            padding: 15px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        
        .page-header {
            padding: 25px 0 20px 0;
        }
        
        .content-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            padding-bottom: 12px;
        }
        
        .main-content {
            padding: 25px 0 40px 0;
        }
        
        .content-card,
        .sidebar-menu,
        .info-box {
            padding: 18px;
        }
    }
</style>

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
                        <div class="image-caption">
                            Struktur Organisasi JDIH DPRD Kota Surakarta
                        </div>
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
                            <a href="alur-pengelolaan.php">
                                <i class="bi bi-diagram-3"></i>
                                Alur Pengelolaan Produk Hukum
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

<?php include 'footer.php'; ?>