?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Artikel Hukum - JDIH DPRD Kota Surakarta</title>
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

        /* ARTICLE CARD */
        .article-container {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }
        
        .article-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .article-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 25px;
        }
        
        @media (max-width: 768px) {
            .article-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .article-card {
            background: #f8f9fa;
            border-radius: var(--border-radius-medium);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            text-decoration: none;
            display: block;
            border: 2px solid transparent;
            position: relative;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border-color: #80d0c7;
        }
        
        .article-category {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .article-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .article-card:hover .article-image {
            transform: scale(1.05);
        }
        
        .article-content {
            padding: 25px;
        }
        
        .article-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .article-excerpt {
            color: var(--p-color);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .meta-item i {
            color: #80d0c7;
        }
        
        .read-more {
            color: #13547a;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .read-more:hover {
            color: #80d0c7;
            gap: 10px;
        }

        /* FEATURED ARTICLE */
        .featured-article {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: var(--border-radius-medium);
            overflow: hidden;
            margin-bottom: 40px;
            position: relative;
            box-shadow: 0 10px 30px rgba(19, 84, 122, 0.2);
        }
        
        .featured-content {
            padding: 40px;
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .featured-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            backdrop-filter: blur(5px);
        }
        
        .featured-title {
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .featured-excerpt {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .featured-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .featured-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .featured-meta-item i {
            color: white;
        }

        /* ARTICLE DETAIL */
        .article-detail {
            background: white;
            border-radius: var(--border-radius-medium);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
        }
        
        .article-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .article-detail-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .article-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .article-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .article-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }
        
        .article-body p {
            margin-bottom: 20px;
        }
        
        .article-body h2 {
            color: #13547a;
            margin: 30px 0 20px 0;
            font-size: 1.8rem;
        }
        
        .article-body h3 {
            color: #13547a;
            margin: 25px 0 15px 0;
            font-size: 1.5rem;
        }
        
        .article-body blockquote {
            border-left: 4px solid #80d0c7;
            padding: 20px 30px;
            margin: 30px 0;
            background: #f0f8ff;
            border-radius: 0 var(--border-radius-small) var(--border-radius-small) 0;
            font-style: italic;
            color: #555;
        }
        
        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: var(--border-radius-small);
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .article-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        
        .article-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .tag {
            background: #f0f8ff;
            color: #13547a;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .tag:hover {
            background: #13547a;
            color: white;
        }
        
        .share-article {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .share-label {
            color: #13547a;
            font-weight: 600;
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

        /* CATEGORY CLOUD */
        .category-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .category-item {
            background: #f0f8ff;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            color: #13547a;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        
        .category-item:hover {
            background: #13547a;
            color: white;
            border-color: #13547a;
            transform: translateY(-2px);
        }

        /* POPULAR ARTICLES */
        .popular-article-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .popular-article-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .popular-article-item:first-child {
            padding-top: 0;
        }
        
        .popular-article-number {
            background: #f0f8ff;
            color: #13547a;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .popular-article-content h5 {
            color: #13547a;
            font-size: 0.95rem;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .popular-article-meta {
            font-size: 0.8rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 10px;
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
        
        .search-form .btn-search {
            background: #13547a;
            color: white;
            border: 2px solid #13547a;
        }
        
        .search-form .btn-search:hover {
            background: #0d3a58;
            border-color: #0d3a58;
        }

        /* NEWSLETTER */
        .newsletter-box {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-radius: var(--border-radius-medium);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .newsletter-box h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .newsletter-box p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .newsletter-box .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            margin-bottom: 15px;
        }
        
        .newsletter-box .btn-subscribe {
            background: white;
            color: #13547a;
            font-weight: 600;
            width: 100%;
        }
        
        .newsletter-box .btn-subscribe:hover {
            background: #f0f8ff;
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
            
            .article-container,
            .article-detail,
            .sidebar-menu,
            .search-box,
            .newsletter-box {
                padding: 20px;
            }
            
            .article-grid {
                gap: 20px;
            }
            
            .featured-content {
                padding: 30px;
            }
            
            .featured-title {
                font-size: 1.5rem;
            }
            
            .article-detail-title {
                font-size: 1.5rem;
            }
            
            .article-detail-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .featured-title {
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

                    <!-- ARTIKEL HUKUM - ACTIVE -->
                    <li class="nav-item active">
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
                    <h1 class="text-white">ARTIKEL HUKUM</h1>
                    <p class="subtitle">Kumpulan Artikel dan Publikasi Ilmiah Hukum dari DPRD Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Artikel Hukum</li>
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
                    
                    <!-- ARTIKEL UTAMA/TERBARU -->
                    <div class="featured-article">
                        <div class="featured-content">
                            <span class="featured-badge">Artikel Utama</span>
                            <h2 class="featured-title">Reformasi Legislasi Daerah: Strategi Peningkatan Kualitas Produk Hukum di Kota Surakarta</h2>
                            <p class="featured-excerpt">
                                Analisis komprehensif terhadap proses legislasi daerah dan strategi inovatif untuk meningkatkan kualitas produk hukum yang dihasilkan DPRD Kota Surakarta dalam mendukung pembangunan berkelanjutan.
                            </p>
                            <div class="featured-meta">
                                <div class="featured-meta-item">
                                    <i class="bi bi-person"></i>
                                    <span>Dr. H. Bambang Sutrisno, SH., MH.</span>
                                </div>
                                <div class="featured-meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>15 Januari 2024</span>
                                </div>
                                <div class="featured-meta-item">
                                    <i class="bi bi-eye"></i>
                                    <span>1.245x dibaca</span>
                                </div>
                            </div>
                            <a href="#artikel-detail" class="custom-btn custom-btn-bg-white">
                                <i class="bi bi-book"></i>
                                Baca Artikel Lengkap
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER KATEGORI -->
                    <div class="article-container">
                        <h3 class="section-title">Filter Artikel</h3>
                        <div class="category-cloud">
                            <a href="#" class="category-item active">Semua</a>
                            <a href="#" class="category-item">Legislasi</a>
                            <a href="#" class="category-item">Pengawasan</a>
                            <a href="#" class="category-item">Anggaran</a>
                            <a href="#" class="category-item">Pemerintahan</a>
                            <a href="#" class="category-item">Hukum Tata Negara</a>
                            <a href="#" class="category-item">Hukum Administrasi</a>
                            <a href="#" class="category-item">Hukum Perdata</a>
                            <a href="#" class="category-item">Hukum Pidana</a>
                            <a href="#" class="category-item">Kajian Hukum</a>
                        </div>
                    </div>
                    
                    <!-- DAFTAR ARTIKEL -->
                    <div class="article-container">
                        <h3 class="section-title">Artikel Terbaru</h3>
                        
                        <div class="article-grid">
                            <!-- Artikel 1 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Legislasi</span>
                                <img src="https://images.unsplash.com/photo-1589391886085-8b6b0ac72a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Proses Legislasi" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Optimalisasi Fungsi Pengawasan DPRD dalam Pelaksanaan APBD Kota Surakarta</h3>
                                    <p class="article-excerpt">
                                        Kajian mendalam tentang mekanisme pengawasan DPRD terhadap pelaksanaan Anggaran Pendapatan dan Belanja Daerah serta strategi peningkatan efektivitasnya.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Drs. Agus Setiawan, M.Si.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>10 Jan 2024</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 2 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Hukum Tata Negara</span>
                                <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Hukum Tata Negara" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Implikasi Otonomi Daerah terhadap Kewenangan Legislasi DPRD Kota Surakarta</h3>
                                    <p class="article-excerpt">
                                        Analisis yuridis tentang batas kewenangan legislasi DPRD dalam konteks otonomi daerah dan harmonisasi dengan peraturan perundang-undangan nasional.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Prof. Dr. Sri Wahyuni, SH., MH.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>5 Jan 2024</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 3 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Pengawasan</span>
                                <img src="https://images.unsplash.com/<!-- Artikel 4 -->
<a href="#artikel-detail" class="article-card">
    <span class="article-category">Anggaran</span>
    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
         alt="Pengelolaan Anggaran" 
         class="article-image">
    <div class="article-content">
        <h3 class="article-title">Transparansi dan Akuntabilitas dalam Pengelolaan Keuangan Daerah</h3>
        <p class="article-excerpt">
            Studi tentang implementasi prinsip transparansi dan akuntabilitas dalam pengelolaan keuangan daerah di Kota Surakarta serta peran DPRD.
        </p>
        <div class="article-meta">
            <div class="meta-item">
                <i class="bi bi-person"></i>
                <span>Ir. Budi Santoso, M.Acc.</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-calendar"></i>
                <span>28 Des 2023</span>
            </div>
        </div>
    </div>
</a>

<!-- Artikel 5 -->
<a href="#artikel-detail" class="article-card">
    <span class="article-category">Pemerintahan</span>
    <img src="https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
         alt="Tata Kelola Pemerintahan" 
         class="article-image">
    <div class="article-content">
        <h3 class="article-title">Inovasi Tata Kelola Pemerintahan Berbasis Digital di Era Society 5.0</h3>
        <p class="article-excerpt">
            Kajian tentang transformasi digital dalam tata kelola pemerintahan daerah dan implikasinya terhadap fungsi legislasi DPRD.
        </p>
        <div class="article-meta">
            <div class="meta-item">
                <i class="bi bi-person"></i>
                <span>Dr. Maya Indriati, S.Si., M.T.</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-calendar"></i>
                <span>20 Des 2023</span>
            </div>
        </div>
    </div>
</a>

<!-- Artikel 6 -->
<a href="#artikel-detail" class="article-card">
    <span class="article-category">Hukum Administrasi</span>
    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
         alt="Hukum Administrasi" 
         class="article-image">
    <div class="article-content">
        <h3 class="article-title">Perlindungan Hukum terhadap Pelayanan Publik di Kota Surakarta</h3>
        <p class="article-excerpt">
            Analisis hukum administrasi tentang mekanisme perlindungan hukum bagi masyarakat dalam penerimaan pelayanan publik.
        </p>
        <div class="article-meta">
            <div class="meta-item">
                <i class="bi bi-person"></i>
                <span>Hendra Wijaya, SH., LL.M.</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-calendar"></i>
                <span>15 Des 2023</span>
            </div>
        </div>
    </div>
</a>
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
                    </div>
                    
                    <!-- DETAIL ARTIKEL (Contoh) -->
                    <div id="artikel-detail" class="article-detail">
                        <div class="article-header">
                            <h1 class="article-detail-title">Reformasi Legislasi Daerah: Strategi Peningkatan Kualitas Produk Hukum di Kota Surakarta</h1>
                            <div class="article-detail-meta">
                                <div class="article-author">
                                    <div class="author-avatar">BS</div>
                                    <div>
                                        <div class="author-name">Dr. H. Bambang Sutrisno, SH., MH.</div>
                                        <div class="author-title">Ketua Komisi Hukum DPRD Kota Surakarta</div>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Dipublikasikan: 15 Januari 2024</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-eye"></i>
                                    <span>1.245x dibaca</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span>Waktu baca: 8 menit</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="article-body">
                            <img src="https://images.unsplash.com/photo-1589391886085-8b6b0ac72a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                                 alt="Reformasi Legislasi" 
                                 class="img-fluid">
                            <p>Dalam era otonomi daerah yang semakin berkembang, fungsi legislasi Dewan Perwakilan Rakyat Daerah (DPRD) memiliki peran strategis dalam pembentukan peraturan daerah yang berkualitas. Kota Surakarta sebagai kota budaya dan pendidikan telah menunjukkan komitmennya dalam melakukan reformasi legislasi untuk mendukung pembangunan berkelanjutan.</p>
                            
                            <h2>Latar Belakang Reformasi Legislasi</h2>
                            <p>Reformasi legislasi daerah menjadi kebutuhan mendesak seiring dengan kompleksitas permasalahan pembangunan daerah. Data menunjukkan bahwa dalam lima tahun terakhir, DPRD Kota Surakarta telah menghasilkan 45 peraturan daerah, dengan tingkat implementasi mencapai 85%. Namun, tantangan masih ditemui dalam harmonisasi dengan peraturan pusat dan efektivitas implementasi di lapangan.</p>
                            
                            <blockquote>
                                "Kualitas produk hukum daerah tidak hanya diukur dari aspek yuridis formal, tetapi juga dari aspek sosiologis dan filosofis dalam memenuhi kebutuhan masyarakat."
                            </blockquote>
                            
                            <h2>Strategi Peningkatan Kualitas</h2>
                            <p>Beberapa strategi yang telah diterapkan DPRD Kota Surakarta meliputi:</p>
                            <ol>
                                <li><strong>Penguatan Kapasitas Legislator:</strong> Program pelatihan dan sertifikasi anggota DPRD dalam teknik legislasi.</li>
                                <li><strong>Partisipasi Publik yang Inklusif:</strong> Menerapkan model konsultasi publik berbasis digital dan tatap muka.</li>
                                <li><strong>Kajian Akademis Mendalam:</strong> Setiap rancangan peraturan daerah wajib dilengkapi dengan naskah akademik yang komprehensif.</li>
                                <li><strong>Harmonisasi Vertikal dan Horizontal:</strong> Mekanisme pemeriksaan harmonisasi dengan peraturan di atasnya dan sejajar.</li>
                            </ol>
                            
                            <h2>Implementasi dan Hasil</h2>
                            <p>Implementasi reformasi legislasi telah menunjukkan hasil yang signifikan. Indeks Kualitas Peraturan Daerah (IKPD) Kota Surakarta meningkat dari 75,2 pada tahun 2020 menjadi 88,5 pada tahun 2023. Selain itu, tingkat partisipasi masyarakat dalam proses legislasi meningkat 40% melalui platform digital yang disediakan.</p>
                            
                            <h2>Rekomendasi dan Ke Depan</h2>
                            <p>Untuk terus meningkatkan kualitas legislasi, diperlukan:</p>
                            <ul>
                                <li>Penguatan sistem monitoring dan evaluasi implementasi peraturan daerah</li>
                                <li>Integrasi teknologi dalam seluruh proses legislasi</li>
                                <li>Kerjasama dengan akademisi dan praktisi hukum</li>
                                <li>Penganggaran yang memadai untuk kegiatan legislasi</li>
                            </ul>
                            
                            <p>Reformasi legislasi daerah merupakan proses berkelanjutan yang memerlukan komitmen semua pemangku kepentingan. DPRD Kota Surakarta bertekad untuk terus meningkatkan kualitas produk hukum dalam rangka mewujudkan tata kelola pemerintahan yang baik dan pembangunan berkelanjutan.</p>
                        </div>
                        
                        <div class="article-footer">
                            <div class="article-tags">
                                <a href="#" class="tag">#LegislasiDaerah</a>
                                <a href="#" class="tag">#ReformasiHukum</a>
                                <a href="#" class="tag">#DPRDSurakarta</a>
                                <a href="#" class="tag">#ProdukHukum</a>
                                <a href="#" class="tag">#TataKelolaPemerintahan</a>
                            </div>
                            
                            <div class="share-article">
                                <div class="share-label">Bagikan Artikel:</div>
                                <div class="share-buttons">
                                    <a href="#" class="share-btn facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="share-btn twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="#" class="share-btn whatsapp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="share-btn link" onclick="copyToClipboard()">
                                        <i class="bi bi-link-45deg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- PENCARIAN -->
                    <div class="search-box">
                        <h4>Cari Artikel</h4>
                        <form class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Kata kunci..." aria-label="Search">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="searchTitle">
                                <label class="form-check-label" for="searchTitle">
                                    Cari dalam judul saja
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="searchContent">
                                <label class="form-check-label" for="searchContent">
                                    Cari dalam konten artikel
                                </label>
                            </div>
                        </form>
                    </div>
                    
                    <!-- KATEGORI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-folder"></i> Kategori Artikel</h4>
                        <ul>
                            <li class="active"><a href="#"><i class="bi bi-chevron-right"></i> Semua Kategori</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Legislasi (45)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Pengawasan (32)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Anggaran (28)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Pemerintahan (24)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Tata Negara (19)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Administrasi (15)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Perdata (12)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Pidana (8)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Kajian Hukum (21)</a></li>
                        </ul>
                    </div>
                    
                    <!-- ARTIKEL POPULER -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-fire"></i> Artikel Terpopuler</h4>
                        <div class="popular-article-item">
                            <div class="popular-article-number">1</div>
                            <div class="popular-article-content">
                                <h5>Digitalisasi Pelayanan Legislasi di Era Society 5.0</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 2,543</span>
                                    <span><i class="bi bi-calendar"></i> 20 Nov 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">2</div>
                            <div class="popular-article-content">
                                <h5>Peran DPRD dalam Pengawasan Anggaran Responsif Gender</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,987</span>
                                    <span><i class="bi bi-calendar"></i> 5 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">3</div>
                            <div class="popular-article-content">
                                <h5>Harmonisasi Perda dengan UU Cipta Kerja</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,756</span>
                                    <span><i class="bi bi-calendar"></i> 12 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">4</div>
                            <div class="popular-article-content">
                                <h5>Strategi Pencegahan Korupsi di Lingkungan Pemerintah Daerah</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,543</span>
                                    <span><i class="bi bi-calendar"></i> 18 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">5</div>
                            <div class="popular-article-content">
                                <h5>Implementasi Prinsip Good Governance dalam Pembentukan Perda</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,432</span>
                                    <span><i class="bi bi-calendar"></i> 25 Des 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- NEWSLETTER -->
                    <div class="newsletter-box">
                        <h4>Berlangganan Newsletter</h4>
                        <p>Dapatkan artikel hukum terbaru langsung ke email Anda. Gratis!</p>
                        <form>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email Anda">
                            </div>
                            <div class="mb-3">
                                <select class="form-select">
                                    <option selected>Pilih Kategori Minat</option>
                                    <option value="1">Legislasi dan Perundangan</option>
                                    <option value="2">Pengawasan dan Anggaran</option>
                                    <option value="3">Hukum Tata Negara</option>
                                    <option value="4">Hukum Administrasi</option>
                                    <option value="5">Semua Kategori</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-subscribe">
                                <i class="bi bi-envelope"></i> Berlangganan
                            </button>
                        </form>
                    </div>
                    
                    <!-- DOWNLOAD -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Materi</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Panduan Teknik Legislasi (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Naskah Akademik (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Reformasi Legislasi (PPT)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Kumpulan Artikel Lengkap (ZIP)</a></li>
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
        // Fungsi untuk copy link artikel
        function copyToClipboard() {
            const currentUrl = window.location.href;
            navigator.clipboard.writeText(currentUrl).then(() => {
                alert('Link artikel telah disalin!');
            });
        }
        
        // Fungsi untuk filter kategori
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Hapus active class dari semua item
                document.querySelectorAll('.category-item').forEach(el => {
                    el.classList.remove('active');
                });
                
                // Tambah active class ke item yang diklik
                this.classList.add('active');
                
                // Simulasi filter artikel (dalam implementasi real, ini akan request ke server)
                const category = this.textContent.trim();
                alert(`Memfilter artikel berdasarkan kategori: ${category}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi pencarian
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const keyword = this.querySelector('input[type="text"]').value;
            const searchTitle = this.querySelector('#searchTitle').checked;
            const searchContent = this.querySelector('#searchContent').checked;
            
            if (!keyword.trim()) {
                alert('Masukkan kata kunci pencarian!');
                return;
            }
            
            alert(`Mencari artikel dengan kata kunci: "${keyword}"\n\nPencarian dalam: ${searchTitle ? 'Judul' : ''} ${searchContent ? 'Konten' : ''}\n\nFungsi pencarian akan bekerja dengan request AJAX ke server.`);
        });
        
        // Fungsi berlangganan newsletter
        document.querySelector('.newsletter-box form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const category = this.querySelector('select').value;
            
            if (!email) {
                alert('Masukkan alamat email!');
                return;
            }
            
            alert(`Terima kasih telah berlangganan newsletter!\n\nEmail: ${email}\nKategori minat: ${this.querySelector('select option:checked').textContent}\n\nKonfirmasi akan dikirim ke email Anda.`);
            this.reset();
        });
        
        // Smooth scroll untuk link artikel
        document.querySelectorAll('a[href^="#artikel-detail"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>
</html>