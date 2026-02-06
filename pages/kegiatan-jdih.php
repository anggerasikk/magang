<?php
// kegiatan.php

// ============================================
// SESSION SECURITY
// ============================================
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true
]);

// ============================================
// SECURITY HEADERS
// ============================================
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once 'a_db.php'; // Include koneksi database
require_once 'a_functions.php'; // Include encryption functions

// ============================================
// HELPER FUNCTIONS TAMBAHAN (HANYA YANG BELUM ADA)
// ============================================

// Cek apakah fungsi safe_include sudah ada di a_functions.php
if (!function_exists('safe_include')) {
    function safe_include($file_path) {
        $allowed_files = ['a_db.php', 'a_functions.php', 'header.php', 'footer.php'];
        $filename = basename($file_path);
        
        if (in_array($filename, $allowed_files, true)) {
            $full_path = __DIR__ . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($full_path) && is_file($full_path)) {
                return $full_path;
            }
        }
        return false;
    }
}

// Cek apakah fungsi validate_image_path sudah ada
if (!function_exists('validate_image_path')) {
    function validate_image_path($path) {
        if (empty($path)) return false;
        
        // Bersihkan path dari directory traversal
        $clean_path = str_replace(['../', './', '..\\', '.\\'], '', $path);
        $clean_path = basename($clean_path);
        
        // Validasi ekstensi file
        $extension = strtolower(pathinfo($clean_path, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        
        if (!in_array($extension, $allowed_extensions, true)) {
            return false;
        }
        
        // Validasi nama file
        $filename = pathinfo($clean_path, PATHINFO_FILENAME);
        if (!preg_match('/^[a-zA-Z0-9_\-\s\.]+$/', $filename)) {
            return false;
        }
        
        return $clean_path;
    }
}

// Cek apakah fungsi safe_output sudah ada, jika tidak gunakan yang default
if (!function_exists('safe_output')) {
    // Fallback function jika tidak ada di a_functions.php
    function safe_output($string, $context = 'html') {
        if ($string === null || $string === '') {
            return '';
        }
        
        $string = (string) $string;
        
        if ($context === 'attr') {
            // Untuk atribut HTML
            return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        } else {
            // Untuk konten HTML biasa
            return htmlspecialchars($string, ENT_NOQUOTES | ENT_HTML5, 'UTF-8', false);
        }
    }
}

// ============================================
// VALIDASI INPUT DAN PARAMETER
// ============================================
$current_filter = 'all';
if (isset($_GET['filter'])) {
    $filter = trim($_GET['filter']);
    $allowed_filters = ['all', 'sosialisasi', 'pelatihan', 'seminar', 'workshop', 'lainnya'];
    
    if (in_array($filter, $allowed_filters, true)) {
        $current_filter = $filter;
    }
}

// ============================================
// CSRF TOKEN (JIKA BELUM ADA DI SESSION)
// ============================================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ============================================
// QUERY DENGAN PREPARED STATEMENTS
// ============================================
$status = 'publik';

if ($current_filter === 'all') {
    // Query tanpa filter
    $query = "SELECT * FROM artikel_kegiatan WHERE status = ? ORDER BY tanggal_publikasi DESC LIMIT 6";
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        error_log("Prepare statement error: " . $koneksi->error);
        die("Terjadi kesalahan sistem.");
    }
    $stmt->bind_param('s', $status);
} else {
    // Query dengan filter
    $query = "SELECT * FROM artikel_kegiatan WHERE status = ? 
              AND (meta_keywords LIKE CONCAT('%', ?, '%') OR judul LIKE CONCAT('%', ?, '%')) 
              ORDER BY tanggal_publikasi DESC";
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        error_log("Prepare statement error: " . $koneksi->error);
        die("Terjadi kesalahan sistem.");
    }
    $stmt->bind_param('sss', $status, $current_filter, $current_filter);
}

if (!$stmt->execute()) {
    error_log("Query execution error: " . $stmt->error);
    die("Terjadi kesalahan saat mengambil data.");
}

$result = $stmt->get_result();

// Update base URL untuk struktur baru
$base_url = "http://localhost/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026";
$pageTitle = 'Berita JDIH - JDIH DPRD Kota Surakarta';

// Include header
include 'header.php';

?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo safe_output($pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
    
    <style>
        /* OVERRIDE UNTUK HALAMAN KEGIATAN */
        body {
            padding-top: 80px;
            background: #f0f8ff;
        }
        
        /* PAGE HEADER */
        .page-header {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            margin-bottom: 60px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 700px;
            color: #ffff;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* MAIN CONTENT */
        .main-content {
            padding: 40px 0 80px;
        }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #13547a;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #80d0c7;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: #ff7e5f;
        }
        
        /* ACTIVITY FILTER */
        .activity-filter {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 50px;
        }
        
        .filter-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .filter-btn {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            color: #13547a;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover {
            background: #e9ecef;
            border-color: #13547a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #13547a;
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(19, 84, 122, 0.3);
        }
        
        /* ACTIVITY CARDS */
        .activity-cards-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .activity-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .activity-card:hover .activity-image {
            transform: scale(1.05);
        }
        
        .activity-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .activity-meta {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .activity-date {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .activity-category {
            color: #80d0c7;
            font-weight: 600;
            font-size: 0.9rem;
            background: #f0f8ff;
            padding: 6px 16px;
            border-radius: 50px;
        }
        
        .activity-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.2rem;
            line-height: 1.4;
            display: -webkit-box;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .activity-excerpt {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.7;
            font-size: 0.95rem;
            display: -webkit-box;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }
        
        .activity-stats {
            display: flex;
            align-items: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }
        
        .activity-stats i {
            margin-right: 5px;
            color: #13547a;
        }
        
        .activity-stats span {
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
        }
        
        .read-more-btn {
            background: transparent;
            border: 2px solid #13547a;
            color: #13547a;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }
        
        .read-more-btn:hover {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(19, 84, 122, 0.3);
        }
        
        /* PAGINATION */
        .pagination {
            justify-content: center;
            margin-top: 50px;
        }
        
        .page-link {
            color: #13547a;
            border: 2px solid #e0e0e0;
            margin: 0 5px;
            border-radius: 10px;
            font-weight: 600;
            min-width: 45px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: #f8f9fa;
            border-color: #13547a;
            color: #13547a;
            transform: translateY(-2px);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-color: transparent;
            color: white;
        }
        
        .page-item.disabled .page-link {
            color: #ccc;
            border-color: #e0e0e0;
        }
        
        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #80d0c7;
            margin-bottom: 20px;
        }
        
        .empty-state h4 {
            color: #13547a;
            margin-bottom: 15px;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .activity-cards-wrapper {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 25px;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .activity-filter {
                padding: 20px;
            }
            
            .filter-title {
                font-size: 1.1rem;
            }
            
            .filter-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            
            .activity-cards-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 0.95rem;
            }
            
            .activity-content {
                padding: 20px;
            }
            
            .activity-title {
                font-size: 1.1rem;
            }
            
            .activity-excerpt {
                font-size: 0.9rem;
            }
            
            .activity-stats span {
                margin-right: 15px;
                font-size: 0.85rem;
            }
            
            .read-more-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                width: 100%;
            }
        }
    </style>
</head>

<body id="top">

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title">Berita JDIH</h1>
                    <p class="page-subtitle">
                        Dokumentasi berita dan aktivitas Jaringan Dokumentasi dan Informasi Hukum 
                        Sekretariat DPRD Kota Surakarta
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- FILTER SECTION -->
            <div class="activity-filter">
                <h4 class="filter-title"><i class="bi bi-funnel me-2"></i>Filter Berita</h4>
                <div class="filter-tags">
                    <a href="?page=kegiatan" class="filter-btn <?php echo ($current_filter == 'all') ? 'active' : ''; ?>">
                        Semua Berita
                    </a>
                    <a href="?page=kegiatan&filter=sosialisasi" class="filter-btn <?php echo ($current_filter == 'sosialisasi') ? 'active' : ''; ?>">
                        Sosialisasi
                    </a>
                    <a href="?page=kegiatan&filter=pelatihan" class="filter-btn <?php echo ($current_filter == 'pelatihan') ? 'active' : ''; ?>">
                        Pelatihan
                    </a>
                    <a href="?page=kegiatan&filter=seminar" class="filter-btn <?php echo ($current_filter == 'seminar') ? 'active' : ''; ?>">
                        Seminar
                    </a>
                    <a href="?page=kegiatan&filter=workshop" class="filter-btn <?php echo ($current_filter == 'workshop') ? 'active' : ''; ?>">
                        Workshop
                    </a>
                    <a href="?page=kegiatan&filter=lainnya" class="filter-btn <?php echo ($current_filter == 'lainnya') ? 'active' : ''; ?>">
                        Lainnya
                    </a>
                </div>
            </div>
            
            <!-- SECTION TITLE -->
            <h2 class="section-title"><i class="bi bi-calendar-event me-2"></i>
                <?php 
                if ($current_filter == 'all') {
                    echo 'Berita Terbaru';
                } else {
                    echo 'Kegiatan ' . ucfirst(safe_output($current_filter));
                }
                ?>
            </h2>
            
            <!-- KEGIATAN CARDS -->
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="activity-cards-wrapper">
                    <?php while ($artikel = $result->fetch_assoc()): ?>
                        <?php
                        // Ekstrak kategori dari meta_keywords atau judul
                        $categories = ['sosialisasi', 'pelatihan', 'seminar', 'workshop'];
                        $article_category = 'lainnya';
                        
                        if (!empty($artikel['meta_keywords'])) {
                            $keywords = strtolower($artikel['meta_keywords']);
                            foreach ($categories as $cat) {
                                if (strpos($keywords, $cat) !== false) {
                                    $article_category = $cat;
                                    break;
                                }
                            }
                        }
                        
                        // Format tanggal
                        $tanggal = date('d M Y', strtotime($artikel['tanggal_publikasi']));
                        
                        // Ambil excerpt dari konten dengan sanitasi
                        $excerpt = strip_tags($artikel['konten_artikel']);
                        if (strlen($excerpt) > 150) {
                            $excerpt = substr($excerpt, 0, 150) . '...';
                        }
                        
                        // ====================================
                        // PENANGANAN GAMBAR YANG AMAN
                        // ====================================
                        
                        // Default placeholder image
                        $default_image = 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                        
                        // Inisialisasi variabel untuk gambar
                        $thumbnail = $default_image;
                        
                        // Cek apakah thumbnail_path ada dan valid
                        if (!empty($artikel['thumbnail_path'])) {
                            $thumbnail_path = trim($artikel['thumbnail_path']);
                            
                            // Jika path adalah URL lengkap
                            if (filter_var($thumbnail_path, FILTER_VALIDATE_URL)) {
                                $thumbnail = $thumbnail_path;
                            } else {
                                // Validasi path lokal
                                $validated_filename = validate_image_path($thumbnail_path);
                                
                                if ($validated_filename !== false) {
                                    // Lokasi yang diizinkan (whitelist approach) - SAMA DENGAN detail-kegiatan.php
                                    $allowed_dirs = [
                                        // Laragon structure
                                        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                        'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/',
                                        'C:/laragon/www/jdihcilacap_pasang_2026/uploads/',
                                        
                                        // XAMPP structure
                                        'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                        'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel/',
                                        
                                        // XAMPP 8 structure
                                        'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                        'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/artikel/',
                                        
                                        // Document root based
                                        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel/',
                                        $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel/',
                                        $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel/',
                                        $_SERVER['DOCUMENT_ROOT'] . '/uploads/',
                                    ];
                                    
                                    foreach ($allowed_dirs as $base_dir) {
                                        $full_path = $base_dir . $validated_filename;
                                        if (file_exists($full_path) && is_file($full_path)) {
                                            // Verifikasi path
                                            $real_path = realpath($full_path);
                                            $real_base = realpath($base_dir);
                                            
                                            if ($real_path && $real_base && strpos($real_path, $real_base) === 0) {
                                                // Verifikasi ini benar-benar file gambar
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $mime_type = finfo_file($finfo, $real_path);
                                                finfo_close($finfo);
                                                
                                                if (strpos($mime_type, 'image/') === 0) {
                                                    // Buat URL web - Konversi path dengan lebih fleksibel
                                                    $web_path = '';
                                                    
                                                    if (strpos($real_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                                                        $web_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_path);
                                                    }
                                                    elseif (strpos($real_path, 'C:\\laragon\\www') === 0 || strpos($real_path, 'C:/laragon/www') === 0) {
                                                        $web_path = str_replace(['C:\\laragon\\www', 'C:/laragon/www'], '', $real_path);
                                                    }
                                                    elseif (strpos($real_path, 'C:\\xampp\\htdocs') === 0 || strpos($real_path, 'C:/xampp/htdocs') === 0) {
                                                        $web_path = str_replace(['C:\\xampp\\htdocs', 'C:/xampp/htdocs'], '', $real_path);
                                                    }
                                                    elseif (strpos($real_path, 'C:\\xampp8\\htdocs') === 0 || strpos($real_path, 'C:/xampp8/htdocs') === 0) {
                                                        $web_path = str_replace(['C:\\xampp8\\htdocs', 'C:/xampp8/htdocs'], '', $real_path);
                                                    }
                                                    
                                                    // Normalize path
                                                    $web_path = str_replace('\\', '/', $web_path);
                                                    if (strpos($web_path, '/') !== 0) {
                                                        $web_path = '/' . $web_path;
                                                    }
                                                    
                                                    $thumbnail = $web_path;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Fallback: Cari gambar berdasarkan ID artikel
                        if ($thumbnail === $default_image && isset($artikel['id_artikel'])) {
                            // Validasi ID artikel
                            if (is_numeric($artikel['id_artikel'])) {
                                $possible_image_files = [
                                    'artikel_' . $artikel['id_artikel'] . '.jpg',
                                    'kegiatan_' . $artikel['id_artikel'] . '.jpg',
                                    'article_' . $artikel['id_artikel'] . '.jpg',
                                    $artikel['id_artikel'] . '.jpg',
                                    'artikel_' . $artikel['id_artikel'] . '.png',
                                    'kegiatan_' . $artikel['id_artikel'] . '.png',
                                    'article_' . $artikel['id_artikel'] . '.png',
                                    $artikel['id_artikel'] . '.png',
                                    'artikel_' . $artikel['id_artikel'] . '.jpeg',
                                    'kegiatan_' . $artikel['id_artikel'] . '.jpeg',
                                    'article_' . $artikel['id_artikel'] . '.jpeg',
                                    $artikel['id_artikel'] . '.jpeg',
                                ];
                                
                                $base_dirs = [
                                    // Laragon
                                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                    'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/',
                                    
                                    // XAMPP
                                    'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                    'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel/',
                                    
                                    // XAMPP 8
                                    'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                    'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/artikel/',
                                    
                                    // Document root
                                    $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                                    $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel/',
                                    $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel/',
                                    $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel/',
                                ];
                                
                                foreach ($base_dirs as $base_dir) {
                                    foreach ($possible_image_files as $image_file) {
                                        $check_path = $base_dir . $image_file;
                                        if (file_exists($check_path)) {
                                            $real_path = realpath($check_path);
                                            $real_base = realpath($base_dir);
                                            
                                            if ($real_path && $real_base && strpos($real_path, $real_base) === 0) {
                                                // Konversi path dengan lebih fleksibel
                                                $web_path = '';
                                                
                                                if (strpos($real_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                                                    $web_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_path);
                                                }
                                                elseif (strpos($real_path, 'C:\\laragon\\www') === 0 || strpos($real_path, 'C:/laragon/www') === 0) {
                                                    $web_path = str_replace(['C:\\laragon\\www', 'C:/laragon/www'], '', $real_path);
                                                }
                                                elseif (strpos($real_path, 'C:\\xampp\\htdocs') === 0 || strpos($real_path, 'C:/xampp/htdocs') === 0) {
                                                    $web_path = str_replace(['C:\\xampp\\htdocs', 'C:/xampp/htdocs'], '', $real_path);
                                                }
                                                elseif (strpos($real_path, 'C:\\xampp8\\htdocs') === 0 || strpos($real_path, 'C:/xampp8/htdocs') === 0) {
                                                    $web_path = str_replace(['C:\\xampp8\\htdocs', 'C:/xampp8/htdocs'], '', $real_path);
                                                }
                                                
                                                // Normalize path
                                                $web_path = str_replace('\\', '/', $web_path);
                                                if (strpos($web_path, '/') !== 0) {
                                                    $web_path = '/' . $web_path;
                                                }
                                                
                                                $thumbnail = $web_path;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Buat link detail dengan enkripsi token
                        $encrypted_token = encode_id($artikel['id_artikel']);
                        $detail_link = "?page=detail-kegiatan&token=" . urlencode($encrypted_token);
                        ?>
                        <div class="activity-card" data-category="<?php echo safe_output($article_category, 'attr'); ?>">
                            <img src="<?php echo safe_output($thumbnail, 'attr'); ?>" 
                                alt="<?php echo safe_output($artikel['judul'], 'attr'); ?>" 
                                class="activity-image"
                                onerror="this.onerror=null; this.src='<?php echo safe_output($default_image, 'attr'); ?>';">
                            <div class="activity-content">
                                <div class="activity-meta">
                                    <span class="activity-date">
                                        <i class="bi bi-calendar3"></i> <?php echo safe_output($tanggal); ?>
                                    </span>
                                    <span class="activity-category">
                                        <?php echo safe_output(ucfirst($article_category)); ?>
                                    </span>
                                </div>
                                <h3 class="activity-title" title="<?php echo safe_output($artikel['judul'], 'attr'); ?>">
                                    <?php echo safe_output($artikel['judul']); ?>
                                </h3>
                                <p class="activity-excerpt">
                                    <?php echo safe_output($excerpt); ?>
                                </p>
                                <div class="activity-stats">
                                    <span title="Dilihat"><i class="bi bi-eye-fill"></i> <?php echo safe_output($artikel['views']); ?></span>
                                    <span title="Penulis"><i class="bi bi-person-fill"></i> 
                                        <?php 
                                        echo !empty($artikel['nama_penulis']) 
                                            ? safe_output($artikel['nama_penulis']) 
                                            : 'Admin JDIH'; 
                                        ?>
                                    </span>
                                </div>
                                <!-- Link ke halaman detail-kegiatan dengan ID -->
                                <a href="<?php echo safe_output($detail_link, 'attr'); ?>" class="read-more-btn">
                                    <i class="bi bi-eye"></i> Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Empty state -->
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h4>Tidak Ada Berita</h4>
                    <p>Belum ada berita yang tersedia untuk kategori ini.</p>
                    <a href="?page=kegiatan" class="btn btn-primary mt-3" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border: none;">
                        Lihat Semua Berita
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </section>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        // Simple initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Activity page loaded');
            
            // Debug: Log semua gambar
            const images = document.querySelectorAll('.activity-image');
            images.forEach((img, index) => {
                img.addEventListener('error', function() {
                    console.warn(`Image ${index} failed to load:`, this.src);
                    // Fallback ke default image
                    this.src = 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                });
            });
            
            // CSRF Protection untuk form (jika ada)
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?php echo $_SESSION["csrf_token"] ?? ""; ?>';
                form.appendChild(csrfInput);
            });
        });
    </script>

</body>
</html>

<?php 
// Tutup koneksi database dengan aman
if (isset($stmt)) {
    $stmt->close();
}

if (isset($koneksi)) {
    $koneksi->close();
}
?>