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

// Ambil surat keputusan utama (hanya satu)
$mainDecree = $decrees[0];
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
            color : #ffff;
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
                            <h3 class="document-title">
                                <?php echo htmlspecialchars($mainDecree['title']); ?>
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
                                    DOWNLOAD SURAT KEPUTUSAN
                                </a>
                                <button type="button" 
                                        class="btn-preview-primary"
                                        onclick="openPreview('<?php echo getPreviewUrl($mainDecree['file_id']); ?>', '<?php echo htmlspecialchars(addslashes($mainDecree['title'])); ?>')">
                                    <i class="bi bi-eye"></i>
                                    PREVIEW SURAT
                                </button>
                            </div>
                        </div>              <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU TERKAIT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'Profil-JDIH') !== false) ? 'active' : '' ?>">
                                <a href="?page=profil-jdih">
                                    <i class="bi bi-building"></i>
                                    Profil JDIH
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'struktur-organisasi') !== false) ? 'active' : '' ?>">
                                <a href="?page=struktur-organisasi">
                                    <i class="bi bi-diagram-3"></i>
                                    Struktur Organisasi
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'alur-pengelolaan') !== false) ? 'active' : '' ?>">
                                <a href="?page=alur-pengelolaan">
                                    <i class="bi bi-diagram-2"></i>
                                    Alur Pengelolaan
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'sop-pengelola') !== false) ? 'active' : '' ?>">
                                <a href="?page=sop-pengelola">
                                    <i class="bi bi-clipboard-check"></i>
                                    SOP Pengelola
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'dasar-hukum') !== false) ? 'active' : '' ?>">
                                <a href="?page=dasar-hukum">
                                    <i class="bi bi-journal-text"></i>
                                    Dasar Hukum
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'surat-keputusan') !== false) ? 'active' : '' ?>">
                                <a href="?page=surat-keputusan">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    Surat Keputusan
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'video-profile') !== false) ? 'active' : '' ?>">
                                <a href="?page=video-profile">
                                    <i class="bi bi-play-circle"></i>
                                    Video Profile
                                </a>
                            </li>
                        </ul>
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
        
        // Reset preview iframe saat modal ditutup
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('previewModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('previewIframe').src = '';
            });
        });
    </script>

</body>
</html>