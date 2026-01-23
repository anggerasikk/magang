<?php
// surat-keputusan.php
$pageTitle = 'Surat Keputusan Pengelola JDIH - JDIH DPRD Kota Surakarta';
include 'header.php';

// Data surat keputusan dengan URL Google Drive
$decrees = [
    [
        'id' => 1,
        'number' => '001/SK-SETWAN/JDIH/DPRD-SKA/2025',
        'title' => 'Surat Keputusan Tim Teknis Pengelola JDIH Sekretariat Dewan DPRD Surakarta Tahun 2025',
        'description' => 'Dokumen resmi penetapan Tim Teknis Pengelola JDIH untuk periode tahun 2025 yang ditandatangani oleh Sekretaris DPRD Kota Surakarta.',
        'date' => '2 Januari 2025',
        'year' => 2025,
        'size' => '1.59 MB',
        'downloads' => 245,
        'file_id' => '1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie'
    ],
    [
        'id' => 2,
        'number' => '045/SK-SETWAN/JDIH/DPRD-SKA/2024',
        'title' => 'Surat Keputusan Penunjukan Koordinator Teknis Pengelola JDIH Sekretariat DPRD Kota Surakarta',
        'description' => 'Penunjukan resmi koordinator teknis untuk pengelolaan JDIH tahun 2024.',
        'date' => '15 Desember 2024',
        'year' => 2024,
        'size' => '892 KB',
        'downloads' => 187,
        'file_id' => '1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie'
    ],
    [
        'id' => 3,
        'number' => '028/SK-SETWAN/JDIH/DPRD-SKA/2023',
        'title' => 'Surat Keputusan Pembentukan Tim Implementasi Sistem JDIH Digital Sekretariat DPRD Kota Surakarta',
        'description' => 'Pembentukan tim khusus untuk implementasi sistem JDIH digital.',
        'date' => '3 Juli 2023',
        'year' => 2023,
        'size' => '1.24 MB',
        'downloads' => 156,
        'file_id' => '1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie'
    ],
    [
        'id' => 4,
        'number' => '012/SK-SETWAN/JDIH/DPRD-SKA/2022',
        'title' => 'Surat Keputusan Pengangkatan Anggota Pengelola JDIH Sekretariat DPRD Kota Surakarta Periode 2022-2024',
        'description' => 'Pengangkatan anggota pengelola JDIH untuk periode 2022-2024.',
        'date' => '10 Maret 2022',
        'year' => 2022,
        'size' => '756 KB',
        'downloads' => 132,
        'file_id' => '1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie'
    ]
];

// Fungsi untuk mendapatkan URL download dari Google Drive
function getDownloadUrl($fileId) {
    return "https://drive.google.com/uc?export=download&id=" . $fileId;
}

// Fungsi untuk mendapatkan URL preview dari Google Drive
function getPreviewUrl($fileId) {
    return "https://drive.google.com/file/d/" . $fileId . "/preview";
}

// Proses pencarian
$searchResults = $decrees;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($searchQuery) {
    $searchResults = array_filter($decrees, function($decree) use ($searchQuery) {
        $search = strtolower($searchQuery);
        $title = strtolower($decree['title']);
        $number = strtolower($decree['number']);
        $year = (string)$decree['year'];
        
        return strpos($title, $search) !== false || 
               strpos($number, $search) !== false || 
               strpos($year, $search) !== false;
    });
}

// Filter berdasarkan tahun
$selectedYears = [];
if (isset($_GET['year2025'])) $selectedYears[] = 2025;
if (isset($_GET['year2024'])) $selectedYears[] = 2024;
if (isset($_GET['year2023'])) $selectedYears[] = 2023;
if (isset($_GET['year2022'])) $selectedYears[] = 2022;

if (!empty($selectedYears)) {
    $searchResults = array_filter($searchResults, function($decree) use ($selectedYears) {
        return in_array($decree['year'], $selectedYears);
    });
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    
    
    <style>
        /* STYLES UNTUK HALAMAN INI SAJA */
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
        
        .btn-action-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .btn-preview-primary {
            background: #28a745;
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
        
        .btn-preview-primary:hover {
            background: #218838;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
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
        
        /* SEARCH HIGHLIGHT */
        .search-highlight {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        /* RESULTS INFO */
        .results-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 20px 0;
            border-left: 4px solid #13547a;
        }
        
        .results-info p {
            margin: 0;
            color: #13547a;
            font-weight: 500;
        }
        
        .results-info .highlight {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
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
        
        /* PREVIEW MODAL */
        .preview-modal .modal-dialog {
            max-width: 90%;
            height: 90vh;
            margin: 5vh auto;
        }
        
        .preview-modal .modal-content {
            height: 100%;
        }
        
        .preview-modal .modal-body {
            padding: 0;
            flex: 1;
        }
        
        .preview-iframe {
            width: 100%;
            height: 100%;
            border: none;
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
            
            .btn-action-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-download-primary, .btn-preview-primary {
                width: 100%;
                justify-content: center;
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
            
            .btn-download-primary, .btn-preview-primary {
                padding: 10px 25px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body id="top">

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
                        <form method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari berdasarkan nomor, tahun, atau judul SK..."
                                       value="<?php echo htmlspecialchars($searchQuery); ?>">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <?php if ($searchQuery || !empty($selectedYears)): ?>
                                <a href="surat-keputusan.php" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                                <?php endif; ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="year2025" id="year2025" 
                                           <?php echo in_array(2025, $selectedYears) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="year2025">
                                        Tahun 2025
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="year2024" id="year2024"
                                           <?php echo in_array(2024, $selectedYears) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="year2024">
                                        Tahun 2024
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="year2023" id="year2023"
                                           <?php echo in_array(2023, $selectedYears) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="year2023">
                                        Tahun 2023
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="year2022" id="year2022"
                                           <?php echo in_array(2022, $selectedYears) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="year2022">
                                        Tahun 2022
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- RESULTS INFO -->
                    <?php if ($searchQuery || !empty($selectedYears)): ?>
                    <div class="results-info">
                        <p>
                            <i class="bi bi-info-circle me-2"></i>
                            Menampilkan <span class="highlight"><?php echo count($searchResults); ?></span> dari <?php echo count($decrees); ?> surat keputusan
                            <?php if ($searchQuery): ?>
                                untuk pencarian "<span class="highlight"><?php echo htmlspecialchars($searchQuery); ?></span>"
                            <?php endif; ?>
                            <?php if (!empty($selectedYears)): ?>
                                <?php echo $searchQuery ? 'dan' : 'untuk'; ?> tahun 
                                <span class="highlight"><?php echo implode(', ', $selectedYears); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- CONTENT CARD -->
                    <div class="content-card">
                        <div class="decree-header">
                            <h1 class="decree-title">Surat Keputusan Pengelola JDIH</h1>
                            <h2 class="decree-subtitle">Sekretariat DPRD Kota Surakarta</h2>
                            <p class="text-muted">Dokumen resmi penunjukan dan pengangkatan pengelola JDIH Sekretariat DPRD Kota Surakarta</p>
                        </div>
                        
                        <!-- MAIN DECREE DOCUMENT -->
                        <?php if (count($searchResults) > 0): ?>
                            <?php $mainDecree = $searchResults[array_key_first($searchResults)]; ?>
                            <div class="decree-document">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </div>
                                <h3 class="document-title">
                                    <?php 
                                    $displayTitle = htmlspecialchars($mainDecree['title']);
                                    if ($searchQuery) {
                                        $pattern = '/(' . preg_quote($searchQuery, '/') . ')/i';
                                        $displayTitle = preg_replace($pattern, '<span class="search-highlight">$1</span>', $displayTitle);
                                    }
                                    echo $displayTitle;
                                    ?>
                                </h3>
                                
                                <div class="document-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-hash"></i>
                                        <span>Nomor: <?php echo htmlspecialchars($mainDecree['number']); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>Tanggal: <?php echo htmlspecialchars($mainDecree['date']); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-file-earmark"></i>
                                        <span>Ukuran: <?php echo $mainDecree['size']; ?></span>
                                    </div>
                                </div>
                                
                                <p class="mb-4"><?php echo htmlspecialchars($mainDecree['description']); ?></p>
                                
                                <div class="btn-action-group">
                                    <a href="<?php echo getDownloadUrl($mainDecree['file_id']); ?>" 
                                       class="btn-download-primary" target="_blank"
                                       onclick="trackDownload(<?php echo $mainDecree['id']; ?>)">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                    <button type="button" 
                                            class="btn-preview-primary"
                                            onclick="openPreview('<?php echo getPreviewUrl($mainDecree['file_id']); ?>', '<?php echo htmlspecialchars(addslashes($mainDecree['title'])); ?>')">
                                        <i class="bi bi-eye"></i>
                                        PREVIEW
                                    </button>
                                </div>
                            </div>
                            
                            <!-- DECREE LIST -->
                            <?php if (count($searchResults) > 1): ?>
                            <div class="decree-list">
                                <h3 class="section-title">Daftar Surat Keputusan Lainnya</h3>
                                
                                <?php foreach (array_slice($searchResults, 1) as $decree): ?>
                                <?php
                                $displayTitle = htmlspecialchars($decree['title']);
                                if ($searchQuery) {
                                    $pattern = '/(' . preg_quote($searchQuery, '/') . ')/i';
                                    $displayTitle = preg_replace($pattern, '<span class="search-highlight">$1</span>', $displayTitle);
                                }
                                ?>
                                <div class="decree-item">
                                    <div class="decree-info">
                                        <div class="decree-number">
                                            <?php 
                                            $displayNumber = htmlspecialchars($decree['number']);
                                            if ($searchQuery) {
                                                $displayNumber = preg_replace($pattern, '<span class="search-highlight">$1</span>', $displayNumber);
                                            }
                                            echo $displayNumber;
                                            ?>
                                        </div>
                                        <p class="decree-description"><?php echo $displayTitle; ?></p>
                                        <div class="decree-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span><?php echo htmlspecialchars($decree['date']); ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-file-earmark"></i>
                                                <span><?php echo $decree['size']; ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-eye"></i>
                                                <span><?php echo $decree['downloads']; ?>x diunduh</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="decree-actions">
                                        <button type="button" 
                                                class="btn-preview"
                                                onclick="openPreview('<?php echo getPreviewUrl($decree['file_id']); ?>', '<?php echo htmlspecialchars(addslashes($decree['title'])); ?>')">
                                            <i class="bi bi-eye"></i>
                                            Preview
                                        </button>
                                        <a href="<?php echo getDownloadUrl($decree['file_id']); ?>" 
                                           class="btn-download-secondary" target="_blank"
                                           onclick="trackDownload(<?php echo $decree['id']; ?>)">
                                            <i class="bi bi-download"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-x" style="font-size: 4rem; color: #ccc;"></i>
                                <h4 class="mt-3">Tidak ada surat keputusan ditemukan</h4>
                                <p class="text-muted">Coba gunakan kata kunci pencarian yang berbeda atau pilih filter tahun lain</p>
                                <a href="surat-keputusan.php" class="btn btn-primary mt-3">
                                    <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua
                                </a>
                            </div>
                        <?php endif; ?>
                        
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
                        <div class="stat-number"><?php echo count($decrees); ?></div>
                        <div class="stat-label">Total SK Aktif</div>
                        <div class="stat-number"><?php echo max(array_column($decrees, 'year')); ?></div>
                        <div class="stat-label">SK Terbaru</div>
                    </div>
                    
                    <!-- QUICK LINKS -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul class="quick-links">
                            <li><a href="profil-jdih.php"><i class="bi bi-house-door"></i> Profil JDIH</a></li>
                            <li><a href="struktur-organisasi.php"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                            <li><a href="dasar-hukum.php"><i class="bi bi-file-earmark-text"></i> Dasar Hukum</a></li>
                            <li class="active"><a href="surat-keputusan.php"><i class="bi bi-file-earmark-medical"></i> Surat Keputusan</a></li>
                            <li><a href="sop-pengelola.php"><i class="bi bi-list-check"></i> SOP Pengelola</a></li>
                            <li><a href="alur-pengelolaan.php"><i class="bi bi-arrow-repeat"></i> Alur Pengelolaan</a></li>
                            <li><a href="visi-misi.php"><i class="bi bi-bullseye"></i> Visi & Misi</a></li>
                        </ul>
                    </div>
                    
                    <!-- DOWNLOAD POPULAR -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Paling Sering Diunduh</h4>
                        <ul class="quick-links">
                            <?php 
                            // Sort by downloads descending
                            usort($decrees, function($a, $b) {
                                return $b['downloads'] - $a['downloads'];
                            });
                            
                            // Take top 4
                            $topDownloads = array_slice($decrees, 0, 4);
                            foreach ($topDownloads as $decree): 
                            ?>
                            <li>
                                <a href="<?php echo getDownloadUrl($decree['file_id']); ?>" target="_blank" 
                                   onclick="trackDownload(<?php echo $decree['id']; ?>)">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div>
                                        <div><?php echo htmlspecialchars(substr($decree['title'], 0, 40)); ?>...</div>
                                        <small class="text-muted">Diunduh <?php echo $decree['downloads']; ?> kali</small>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
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

    <!-- PREVIEW MODAL -->
    <div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Surat Keputusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe class="preview-iframe" id="previewIframe" src=""></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="downloadPreviewBtn" class="btn btn-primary" target="_blank">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        // Fungsi untuk membuka preview
        function openPreview(url, title) {
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            const iframe = document.getElementById('previewIframe');
            const modalTitle = document.getElementById('previewModalLabel');
            const downloadBtn = document.getElementById('downloadPreviewBtn');
            
            iframe.src = url;
            modalTitle.textContent = title;
            
            // Set download link (konversi dari preview URL ke download URL)
            const downloadUrl = url.replace('/preview', '').replace('/file/d/', '/uc?export=download&id=');
            downloadBtn.href = downloadUrl;
            
            modal.show();
        }
        
        // Fungsi untuk tracking download
        function trackDownload(docId) {
            // Simulasi tracking download
            console.log('Download tracked for document ID:', docId);
            
            // Dalam implementasi nyata, ini akan mengirim data ke server
            // fetch('track-download.php?doc_id=' + docId);
            
            // Tampilkan notifikasi
            showNotification('Download surat keputusan dimulai...');
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message) {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = 'notification alert alert-success alert-dismissible fade show';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            notification.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Hapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Highlight pencarian di hasil
        document.addEventListener('DOMContentLoaded', function() {
            const searchQuery = "<?php echo addslashes($searchQuery); ?>";
            if (searchQuery && searchQuery.trim() !== '') {
                // Tambahkan animasi untuk hasil yang di-highlight
                const highlights = document.querySelectorAll('.search-highlight');
                highlights.forEach(highlight => {
                    highlight.style.animation = 'pulse 2s infinite';
                });
                
                // Tambahkan style untuk animasi
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes pulse {
                        0% { background-color: #fff3cd; }
                        50% { background-color: #ffeaa7; }
                        100% { background-color: #fff3cd; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            // Reset preview iframe saat modal ditutup
            document.getElementById('previewModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('previewIframe').src = '';
            });
            
            // Auto-submit checkbox filter saat dicentang
            document.querySelectorAll('.search-form input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Submit form dengan delay kecil
                    setTimeout(() => {
                        this.closest('form').submit();
                    }, 300);
                });
            });
        });
    </script>

</body>
</html>