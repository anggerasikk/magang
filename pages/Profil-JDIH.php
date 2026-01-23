<?php
// profil-jdih.php
$pageTitle = "Profil JDIH - Sekretariat DPRD Kota Surakarta";
include('header.php');
?>

<style>
    body {
        padding-top: 80px; 
    }
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
        right: 0;
        bottom: 0;
        background: url('images/logo.png') no-repeat center right 20px;
        background-size: 150px;
        opacity: 0.1;
        z-index: 1;
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
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
        color: white;
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
    .profile-card {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 40px;
        margin-bottom: 40px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .profile-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, rgba(19, 84, 122, 0.05) 0%, rgba(128, 208, 199, 0.05) 100%);
        border-radius: 50%;
        z-index: 1;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        gap: 30px;
        position: relative;
        z-index: 2;
    }
    .profile-logo {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 20px rgba(19, 84, 122, 0.25);
        overflow: hidden;
        background: white;
        padding: 15px;
        border: 4px solid #13547a;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .profile-logo:hover {
        transform: rotate(5deg) scale(1.05);
        box-shadow: 0 10px 25px rgba(19, 84, 122, 0.35);
    }
    
    .profile-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .profile-logo::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        background: linear-gradient(135deg, #13547a, #80d0c7);
        border-radius: 50%;
        z-index: -1;
        opacity: 0.1;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.05); opacity: 0.15; }
        100% { transform: scale(1); opacity: 0.1; }
    }
    
    .profile-info h2 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.8rem;
    }
    
    .profile-info p {
        color: var(--p-color);
        font-size: 1.1rem;
        margin-bottom: 5px;
    }
    
    .profile-info .organization {
        color: #80d0c7;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .profile-content h3 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 20px;
        font-size: 1.3rem;
        position: relative;
        padding-left: 15px;
    }
    
    .profile-content h3::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        bottom: 5px;
        width: 4px;
        background: linear-gradient(to bottom, #13547a, #80d0c7);
        border-radius: 2px;
    }
    
    .profile-content p {
        color: var(--p-color);
        font-size: var(--p-font-size);
        line-height: 1.8;
        margin-bottom: 20px;
        text-align: justify;
    }
    
    .highlight-box {
        background: linear-gradient(135deg, rgba(19, 84, 122, 0.1) 0%, rgba(128, 208, 199, 0.1) 100%);
        border-left: 4px solid #13547a;
        padding: 25px;
        margin: 30px 0;
        border-radius: 0 var(--border-radius-small) var(--border-radius-small) 0;
        position: relative;
        overflow: hidden;
    }
    
    .highlight-box::before {
        content: '"';
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 4rem;
        color: rgba(19, 84, 122, 0.1);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 1;
    }
    
    .highlight-box p {
        margin: 0;
        font-style: italic;
        color: #13547a;
        font-weight: 500;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }
    .stats-container {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .stats-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #13547a, #80d0c7);
    }
    
    .stats-title {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .stats-title i {
        color: #80d0c7;
        font-size: 1.5rem;
        margin-right: 15px;
    }
    
    .stats-title h3 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 1.3rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 20px 15px;
        background: #f8f9fa;
        border-radius: var(--border-radius-small);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(to right, #13547a, #80d0c7);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .stat-item:hover::before {
        transform: scaleX(1);
    }
    
    .stat-item:hover {
        background: #e9ecef;
        transform: translateY(-5px);
    }
    
    .stat-year {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: #13547a;
        margin-bottom: 5px;
    }
    
    .stat-count {
        font-size: 2rem;
        font-weight: 700;
        color: #80d0c7;
        line-height: 1;
        margin-bottom: 5px;
    }
    
    .stat-count span {
        font-size: 1rem;
        color: #666;
    }
    
    .stat-label {
        color: var(--p-color);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .news-container {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
    }
    
    .news-list {
        margin-top: 20px;
    }
    
    .news-item {
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: all 0.3s ease;
    }
    
    .news-item:hover {
        background: #f8f9fa;
        padding-left: 15px;
        padding-right: 15px;
        border-radius: 5px;
    }
    
    .news-item:last-child {
        border-bottom: none;
    }
    
    .news-date {
        min-width: 100px;
        text-align: center;
        background: #f8f9fa;
        padding: 10px;
        border-radius: var(--border-radius-small);
        transition: all 0.3s ease;
    }
    
    .news-item:hover .news-date {
        background: #13547a;
        color: white;
    }
    
    .news-date .day {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
        color: #13547a;
        display: block;
        transition: color 0.3s ease;
    }
    
    .news-item:hover .news-date .day {
        color: white;
    }
    
    .news-date .month {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.3s ease;
    }
    
    .news-item:hover .news-date .month {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .news-content h4 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 10px;
        line-height: 1.4;
        transition: color 0.3s ease;
    }
    
    .news-item:hover .news-content h4 {
        color: #80d0c7;
    }
    
    .news-content p {
        color: var(--p-color);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }
    .sidebar-menu {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #13547a, #80d0c7);
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
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-menu ul li a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #80d0c7;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .sidebar-menu ul li a:hover::before {
        transform: scaleY(1);
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
    .contact-info {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-radius: var(--border-radius-medium);
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .contact-info::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: url('images/logo.png') no-repeat center;
        background-size: contain;
        opacity: 0.1;
        z-index: 1;
    }
    
    .contact-info > * {
        position: relative;
        z-index: 2;
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
        display: flex;
        align-items: center;
    }
    
    .contact-detail h5 i {
        margin-right: 8px;
        font-size: 0.9rem;
        color: #80d0c7;
    }
    
    .contact-detail p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        line-height: 1.6;
        font-size: 0.95rem;
        padding-left: 24px;
    }
    
    .contact-detail a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }
    
    .contact-detail a:hover {
        color: white;
        text-decoration: underline;
        transform: translateX(5px);
    }
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
        position: relative;
        overflow: hidden;
    }
    
    .portal-link-item:last-child {
        margin-bottom: 0;
    }
    
    .portal-link-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #13547a;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .portal-link-item:hover::before {
        transform: scaleY(1);
    }
    
    .portal-link-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .portal-link-item i {
        color: #80d0c7;
        margin-right: 10px;
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }
    
    .portal-link-item:hover i {
        transform: rotate(15deg);
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
    .custom-btn {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: var(--border-radius-small);
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    
    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        color: white;
    }
    
    .custom-border-btn {
        background: transparent;
        color: #13547a;
        border: 2px solid #13547a;
        padding: 12px 20px;
        border-radius: var(--border-radius-small);
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    
    .custom-border-btn:hover {
        background: #13547a;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    @media (max-width: 768px) {
        body {
            padding-top: 70px;
        }
        
        .page-header {
            padding: 30px 0 20px 0;
        }
        
        .page-header::before {
            background-size: 100px;
            opacity: 0.08;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .main-content {
            padding: 30px 0 40px 0;
        }
        
        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        
        .profile-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
        
        .profile-card,
        .stats-container,
        .news-container,
        .sidebar-menu,
        .contact-info,
        .portal-links {
            padding: 20px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .news-item {
            flex-direction: column;
            gap: 15px;
        }
        
        .news-date {
            min-width: auto;
            width: 100px;
            align-self: center;
        }
        
        .contact-info::before {
            width: 80px;
            height: 80px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header::before {
            background-size: 80px;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .profile-content h3 {
            font-size: 1.2rem;
        }
        
        .profile-logo {
            width: 100px;
            height: 100px;
            border-width: 3px;
        }
    }
</style>

<!-- HEADER SECTION -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white">PROFIL JDIH</h1>
                <p class="subtitle">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil JDIH</li>
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
                
                <!-- PROFILE JDIH -->
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-logo">
                            <img src="images/logo.png" alt="Logo JDIH DPRD Kota Surakarta" title="Logo JDIH DPRD Kota Surakarta">
                        </div>
                        <div class="profile-info">
                            <h2>JDIH DPRD Kota Surakarta</h2>
                            <p><strong>Jaringan Dokumentasi dan Informasi Hukum</strong></p>
                            <p class="organization">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                            <p class="text-muted"><small><i class="bi bi-geo-alt"></i> Kota Surakarta, Jawa Tengah</small></p>
                        </div>
                    </div>
                    
                    <div class="profile-content">
                        <h3>Tentang JDIH</h3>
                        <p>
                            Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                            merupakan unit kerja yang bertanggung jawab dalam pengelolaan, pendokumentasian, 
                            dan penyebarluasan informasi hukum yang dihasilkan oleh DPRD Kota Surakarta.
                        </p>
                        
                        <div class="highlight-box">
                            <p>
                                "JDIH membawa hukum langsung dalam genggaman Anda! Sebagai sarana penyerbarluasan 
                                Produk Hukum berbasis Online untuk publikasi dan akses cepat ke informasi hukum."
                            </p>
                        </div>
                        
                        <p>
                            Dibentuk berdasarkan kebutuhan akan sistem pengelolaan dokumen hukum yang terintegrasi 
                            dan mudah diakses oleh masyarakat, akademisi, penegak hukum, dan stakeholders lainnya. 
                            JDIH DPRD Kota Surakarta berkomitmen untuk menyediakan informasi hukum yang akurat, 
                            terkini, dan mudah diakses.
                        </p>
                        
                        <h3>Visi</h3>
                        <p>
                            Menjadi pusat informasi hukum yang terpercaya, terintegrasi, dan mudah diakses 
                            bagi seluruh masyarakat Kota Surakarta dan stakeholders terkait.
                        </p>
                        
                        <h3>Misi</h3>
                        <p>
                            1. Mengumpulkan dan mendokumentasikan seluruh produk hukum DPRD Kota Surakarta<br>
                            2. Mengelola database hukum yang akurat dan terupdate<br>
                            3. Menyebarluaskan informasi hukum kepada masyarakat secara mudah dan cepat<br>
                            4. Memberikan pelayanan informasi hukum yang profesional<br>
                            5. Mendorong transparansi dan akuntabilitas dalam proses legislasi
                        </p>
                        
                        <h3>Fungsi dan Tugas</h3>
                        <p>
                            JDIH DPRD Kota Surakarta memiliki fungsi utama sebagai pusat pengelolaan informasi 
                            hukum yang meliputi: pengumpulan, pengolahan, penyimpanan, dan penyebarluasan 
                            informasi hukum. Tugas utama meliputi dokumentasi produk hukum, pembuatan database, 
                            pelayanan informasi, dan publikasi produk hukum melalui berbagai media.
                        </p>
                        
                        <h3>Sejarah Singkat</h3>
                        <p>
                            JDIH DPRD Kota Surakarta secara resmi dibentuk pada tahun 2014 melalui Surat Keputusan 
                            Sekretaris DPRD Kota Surakarta. Sejak itu, JDIH terus berkembang dengan mengadopsi 
                            teknologi informasi untuk meningkatkan pelayanan. Pada tahun 2019, diluncurkan website 
                            JDIH untuk memudahkan akses publik terhadap informasi hukum.
                        </p>
                    </div>
                </div>
                
                <!-- STATISTIK PRODUK HUKUM -->
                <div class="stats-container">
                    <div class="stats-title">
                        <i class="bi bi-bar-chart"></i>
                        <h3>Statistik Produk Hukum Terkelola</h3>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-year">2025</div>
                            <div class="stat-count">79</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2024</div>
                            <div class="stat-count">68</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2023</div>
                            <div class="stat-count">89</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2022</div>
                            <div class="stat-count">103</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2021</div>
                            <div class="stat-count">22</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2020</div>
                            <div class="stat-count">52</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">2019</div>
                            <div class="stat-count">18</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-year">Total</div>
                            <div class="stat-count">456</div>
                            <div class="stat-label">Produk Hukum</div>
                        </div>
                    </div>
                </div>
                
                <!-- BERITA TERBARU -->
                <div class="news-container">
                    <h3 class="section-title">Berita Terbaru</h3>
                    
                    <div class="news-list">
                        <div class="news-item">
                            <div class="news-date">
                                <span class="day">10</span>
                                <span class="month">Jan 2024</span>
                            </div>
                            <div class="news-content">
                                <h4>Peluncuran Website JDIH Versi 3.0</h4>
                                <p>JDIH DPRD Kota Surakarta meluncurkan website terbaru dengan fitur yang lebih lengkap dan user-friendly untuk memudahkan akses informasi hukum.</p>
                            </div>
                        </div>
                        
                        <div class="news-item">
                            <div class="news-date">
                                <span class="day">15</span>
                                <span class="month">Des 2023</span>
                            </div>
                            <div class="news-content">
                                <h4>Workshop Pengelolaan Dokumen Hukum Digital</h4>
                                <p>Tim JDIH mengadakan workshop tentang pengelolaan dokumen hukum digital untuk meningkatkan kapasitas SDM dalam pengelolaan informasi hukum.</p>
                            </div>
                        </div>
                        
                        <div class="news-item">
                            <div class="news-date">
                                <span class="day">20</span>
                                <span class="month">Nov 2023</span>
                            </div>
                            <div class="news-content">
                                <h4>MoU dengan Universitas Sebelas Maret</h4>
                                <p>JDIH DPRD Kota Surakarta menandatangani MoU dengan UNS untuk kerjasama dalam penelitian dan pengembangan sistem informasi hukum.</p>
                            </div>
                        </div>
                        
                        <div class="news-item">
                            <div class="news-date">
                                <span class="day">05</span>
                                <span class="month">Okt 2023</span>
                            </div>
                            <div class="news-content">
                                <h4>Penghargaan JDIH Terbaik Tingkat Provinsi</h4>
                                <p>JDIH DPRD Kota Surakarta menerima penghargaan sebagai JDIH terbaik tingkat Provinsi Jawa Tengah tahun 2023.</p>
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
                            <a href="struktur-organisasi.php">
                                <i class="bi bi-diagram-3"></i>
                                Struktur Organisasi
                            </a>
                        </li>
                        <li>
                            <a href="alur-pengelolaan.php">
                                <i class="bi bi-diagram-2"></i>
                                Alur Pengelolaan
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
                    </ul>
                </div>

                <!-- CONTACT INFO -->
                <div class="contact-info">
                    <h4><i class="bi bi-telephone"></i> Kontak Informasi</h4>
                    
                    <div class="contact-detail">
                        <h5><i class="bi bi-geo-alt"></i> Alamat</h5>
                        <p>
                            Sekretariat DPRD Kota Surakarta<br>
                            Jl. Jenderal Sudirman No. 2<br>
                            Kecamatan Pasar Kliwon<br>
                            Kota Surakarta, Jawa Tengah 57111
                        </p>
                    </div>
                    
                    <div class="contact-detail">
                        <h5><i class="bi bi-telephone"></i> Telepon</h5>
                        <p>
                            <a href="tel:+62271723456">(0271) 723456</a><br>
                            Ext. 123 (JDIH)
                        </p>
                    </div>
                    
                    <div class="contact-detail">
                        <h5><i class="bi bi-envelope"></i> Email</h5>
                        <p>
                            <a href="mailto:jdih@dprd-surakarta.go.id">jdih@dprd-surakarta.go.id</a>
                        </p>
                    </div>
                    
                    <div class="contact-detail">
                        <h5><i class="bi bi-clock"></i> Jam Operasional</h5>
                        <p>
                            Senin - Jumat: 08:00 - 16:00 WIB<br>
                            Sabtu - Minggu: Tutup
                        </p>
                    </div>
                </div>

                <!-- PORTAL LINKS -->
                <div class="portal-links">
                    <h4><i class="bi bi-link"></i> Link Portal</h4>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>DPRD Kota Surakarta</span>
                        <a href="https://dprd-surakarta.go.id" target="_blank"></a>
                    </div>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>Pemerintah Kota Surakarta</span>
                        <a href="https://surakarta.go.id" target="_blank"></a>
                    </div>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>JDIH Nasional</span>
                        <a href="https://jdih.kemenkumham.go.id" target="_blank"></a>
                    </div>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>JDIH Provinsi Jawa Tengah</span>
                        <a href="https://jdih.jatengprov.go.id" target="_blank"></a>
                    </div>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>Sistem Informasi Hukum</span>
                        <a href="https://peraturan.go.id" target="_blank"></a>
                    </div>
                    
                    <div class="portal-link-item">
                        <i class="bi bi-globe"></i>
                        <span>Mahkamah Konstitusi</span>
                        <a href="https://mkri.id" target="_blank"></a>
                    </div>
                </div>
                
                <!-- DOWNLOAD -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Download</h4>
                    <p class="mb-3">Download dokumen profil JDIH:</p>
                    <a href="downloads/profil-jdih.pdf" download class="btn custom-btn w-100 mb-2">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Profil JDIH (PDF)
                    </a>
                    <a href="downloads/brosur-jdih.pdf" download class="btn custom-border-btn w-100">
                        <i class="bi bi-file-earmark-text"></i>
                        Brosur JDIH
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include('footer.php'); ?>