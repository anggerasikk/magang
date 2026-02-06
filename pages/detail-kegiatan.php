<?php
// detail-kegiatan.php - VERSI DIPERBAIKI DENGAN DEBUG GAMBAR

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

// ============================================
// ERROR REPORTING UNTUK DEBUG
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'a_db.php'; // Include koneksi database
require_once 'a_functions.php'; // Include encryption functions

// ============================================
// CSRF TOKEN
// ============================================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ============================================
// HELPER FUNCTIONS
// ============================================

// ============================================
// FUNGSI UNTUK MENDAPATKAN GAMBAR - VERSI DIPERBAIKI
// ============================================
function getArticleImage($image_path, $article_id = 0) {
    // Log untuk debugging
    error_log("FUNCTION getArticleImage called");
    error_log("Input image_path: " . $image_path);
    error_log("Input article_id: " . $article_id);
    
    // Default image
    $default_image = 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80';
    
    // Jika path kosong atau null
    if (empty($image_path) || $image_path == 'NULL' || trim($image_path) == '') {
        error_log("Path is empty, using default");
        return $default_image;
    }
    
    // Tampilkan raw path untuk debug
    error_log("Raw path from DB: " . bin2hex($image_path));
    
    // Clean path
    $clean_path = trim($image_path);
    error_log("Cleaned path: " . $clean_path);
    
    // Jika sudah URL lengkap
    if (strpos($clean_path, 'http://') === 0 || strpos($clean_path, 'https://') === 0) {
        error_log("Path is full URL: " . $clean_path);
        return $clean_path;
    }
    
    // Ambil nama file
    $filename = basename($clean_path);
    error_log("Filename extracted: " . $filename);
    
    // Cek apakah file memiliki ekstensi yang valid
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    
    if (!in_array($extension, $valid_extensions)) {
        error_log("Invalid file extension: " . $extension);
        $filename = ''; // Reset filename untuk pencarian alternatif
    }
    
    // ============================================
    // CARI FILE DI SERVER - MULTI-PATH CHECK
    // ============================================
    
    // Dapatkan document root
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    error_log("Document root: " . $doc_root);
    
    // Dapatkan current directory
    $current_dir = __DIR__;
    error_log("Current directory: " . $current_dir);
    
    // Dapatkan project root (2 level up dari pages/)
    $project_root = dirname(dirname(__DIR__));
    error_log("Project root: " . $project_root);
    
    // List semua kemungkinan lokasi
    $possible_locations = [];
    
    // 1. Path berdasarkan filename dari database
    if (!empty($filename)) {
        $possible_locations = array_merge($possible_locations, [
            // Laragon structure
            'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/' . $filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/uploads/' . $filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/' . $clean_path,
            
            // XAMPP structure
            'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $filename,
            'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel/' . $filename,
            'C:/xampp/htdocs/jdihcilacap_pasang_2026/' . $clean_path,
            
            // XAMPP 8 structure
            'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $filename,
            'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/artikel/' . $filename,
            
            // Document root based
            $doc_root . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $filename,
            $doc_root . '/jdihcilacap_pasang_2026/uploads/artikel/' . $filename,
            $doc_root . '/panel_control/uploads/artikel/' . $filename,
            $doc_root . '/uploads/artikel/' . $filename,
            $doc_root . '/uploads/' . $filename,
            $doc_root . '/' . $clean_path,
            
            // Project root based
            $project_root . '/panel_control/uploads/artikel/' . $filename,
            $project_root . '/uploads/artikel/' . $filename,
            $project_root . '/' . $clean_path,
            
            // Current directory based
            $current_dir . '/../panel_control/uploads/artikel/' . $filename,
            $current_dir . '/../uploads/artikel/' . $filename,
        ]);
    }
    
    // 2. Cari berdasarkan ID artikel (jika filename tidak ditemukan atau tidak valid)
    if ($article_id > 0) {
        $id_patterns = [
            'artikel_' . $article_id,
            'kegiatan_' . $article_id,
            'article_' . $article_id,
            (string)$article_id,
        ];
        
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        foreach ($id_patterns as $pattern) {
            foreach ($extensions as $ext) {
                $test_filename = $pattern . '.' . $ext;
                
                $possible_locations = array_merge($possible_locations, [
                    // Laragon
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $test_filename,
                    'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/' . $test_filename,
                    
                    // XAMPP
                    'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $test_filename,
                    
                    // XAMPP 8
                    'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $test_filename,
                    
                    // Document root
                    $doc_root . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/' . $test_filename,
                    $doc_root . '/panel_control/uploads/artikel/' . $test_filename,
                    
                    // Project root
                    $project_root . '/panel_control/uploads/artikel/' . $test_filename,
                ]);
            }
        }
    }
    
    // 3. Normalize semua path (ganti backslash dengan forward slash)
    $possible_locations = array_map(function($path) {
        return str_replace('\\', '/', $path);
    }, $possible_locations);
    
    // 4. Hapus duplikat
    $possible_locations = array_unique($possible_locations);
    
    // 5. Cek setiap lokasi
    error_log("Checking " . count($possible_locations) . " possible locations...");
    
    foreach ($possible_locations as $index => $location) {
        error_log("Checking location " . ($index + 1) . ": " . $location);
        
        if (file_exists($location)) {
            error_log("FOUND FILE at: " . $location);
            error_log("File size: " . filesize($location) . " bytes");
            
            // Konversi ke URL web
            $web_path = '';
            
            if (strpos($location, $doc_root) === 0) {
                $web_path = str_replace($doc_root, '', $location);
                error_log("Converted (doc_root): " . $doc_root . " -> " . $web_path);
            }
            elseif (strpos($location, 'C:/laragon/www/') === 0) {
                $web_path = str_replace('C:/laragon/www', '', $location);
                error_log("Converted (laragon): C:/laragon/www -> " . $web_path);
            }
            elseif (strpos($location, 'C:/xampp/htdocs/') === 0) {
                $web_path = str_replace('C:/xampp/htdocs', '', $location);
                error_log("Converted (xampp): C:/xampp/htdocs -> " . $web_path);
            }
            elseif (strpos($location, 'C:/xampp8/htdocs/') === 0) {
                $web_path = str_replace('C:/xampp8/htdocs', '', $location);
                error_log("Converted (xampp8): C:/xampp8/htdocs -> " . $web_path);
            }
            else {
                $web_path = $location;
                error_log("Using original path: " . $web_path);
            }
            
            // Pastikan path dimulai dengan slash
            $web_path = str_replace('\\', '/', $web_path);
            if (strpos($web_path, '/') !== 0) {
                $web_path = '/' . $web_path;
            }
            
            error_log("Final web path: " . $web_path);
            return $web_path;
        }
    }
    
    // 6. Jika tidak ditemukan di mana pun
    error_log("File NOT FOUND in any location, using default image");
    
    // Coba satu pendekatan terakhir: cari file apa saja di folder artikel
    $test_dirs = [
        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
        $doc_root . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
        $project_root . '/panel_control/uploads/artikel/',
    ];
    
    foreach ($test_dirs as $test_dir) {
        if (is_dir($test_dir)) {
            error_log("Checking directory: " . $test_dir);
            $files = scandir($test_dir);
            if ($files) {
                error_log("Files in directory (" . count($files) . "): " . implode(', ', array_slice($files, 0, 10)));
            }
        }
    }
    
    return $default_image;
}

// ============================================
// VALIDASI INPUT
// ============================================
$id_artikel = 0;
$token = '';

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
    // Validasi token format
    if (strlen($token) >= 10 && preg_match('/^[a-zA-Z0-9_\-\+=]+$/', $token)) {
        $id_artikel = decode_id($token);
        error_log("Token decoded. ID Artikel: " . $id_artikel);
    } else {
        error_log("Invalid token format: " . $token);
    }
}

// ============================================
// VALIDASI TOKEN DAN ID
// ============================================
if ($id_artikel <= 0 || !is_numeric($id_artikel)) {
    http_response_code(404);
    die("<div style='text-align:center; padding:50px; font-family:Arial, sans-serif;'>
            <h2 style='color:#dc3545;'>Artikel tidak ditemukan!</h2>
            <p style='margin:20px 0; color:#666;'>Token tidak valid atau artikel telah dihapus.</p>
            <a href='?page=kegiatan' 
               style='display:inline-block; 
                      margin-top:20px; 
                      padding:12px 30px; 
                      background:linear-gradient(135deg, #13547a 0%, #80d0c7 100%); 
                      color:white; 
                      text-decoration:none; 
                      border-radius:25px;
                      font-weight:bold;
                      transition:all 0.3s ease;'
               onmouseover='this.style.transform=\"translateY(-2px)\";'
               onmouseout='this.style.transform=\"translateY(0)\";'>
                Kembali ke Daftar Kegiatan
            </a>
        </div>");
}

// ============================================
// QUERY DATABASE DENGAN PREPARED STATEMENT
// ============================================
$query = "SELECT * FROM artikel_kegiatan WHERE id_artikel = ? AND status = 'publik'";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    error_log("Prepare statement error: " . $koneksi->error);
    die("<div style='text-align:center; padding:50px;'>
            <h2 style='color:#dc3545;'>Terjadi Kesalahan Sistem</h2>
            <p style='margin:20px 0;'>Silakan coba lagi nanti.</p>
        </div>");
}

$stmt->bind_param('i', $id_artikel);

if (!$stmt->execute()) {
    error_log("Query execution error: " . $stmt->error);
    $stmt->close();
    die("<div style='text-align:center; padding:50px;'>
            <h2 style='color:#dc3545;'>Terjadi Kesalahan</h2>
            <p style='margin:20px 0;'>Gagal mengambil data artikel.</p>
        </div>");
}

$result = $stmt->get_result();

// Jika artikel tidak ditemukan
if ($result->num_rows == 0) {
    $stmt->close();
    http_response_code(404);
    die("<div style='text-align:center; padding:50px;'>
            <h2 style='color:#dc3545;'>Artikel tidak ditemukan!</h2>
            <p style='margin:20px 0;'>Artikel yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href='?page=kegiatan' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#13547a; color:white; text-decoration:none; border-radius:5px;'>
                Kembali ke Daftar Kegiatan
            </a>
        </div>");
}

$artikel = $result->fetch_assoc();
$stmt->close();

error_log("=== ARTICLE DATA FROM DATABASE ===");
error_log("Article ID: " . $id_artikel);
error_log("Title: " . $artikel['judul']);
error_log("Thumbnail Path from DB: " . $artikel['thumbnail_path']);
error_log("Thumbnail Path Type: " . gettype($artikel['thumbnail_path']));
error_log("Thumbnail Path Length: " . strlen($artikel['thumbnail_path']));
error_log("==================================");

// ============================================
// UPDATE JUMLAH VIEWS
// ============================================
$update_views = "UPDATE artikel_kegiatan SET views = views + 1 WHERE id_artikel = ?";
$stmt_views = $koneksi->prepare($update_views);
if ($stmt_views) {
    $stmt_views->bind_param('i', $id_artikel);
    $stmt_views->execute();
    $stmt_views->close();
}

// ============================================
// AMBIL ARTIKEL TERKAIT
// ============================================
$related_articles = [];
if (!empty($artikel['meta_keywords'])) {
    $keywords = explode(',', $artikel['meta_keywords']);
    $main_keyword = trim($keywords[0]);
    
    if (!empty($main_keyword)) {
        $related_query = "SELECT id_artikel, judul, thumbnail_path, tanggal_publikasi, views 
                         FROM artikel_kegiatan 
                         WHERE id_artikel != ? 
                         AND status = 'publik'
                         AND (meta_keywords LIKE CONCAT('%', ?, '%') OR judul LIKE CONCAT('%', ?, '%'))
                         ORDER BY tanggal_publikasi DESC 
                         LIMIT 3";
        
        $stmt_related = $koneksi->prepare($related_query);
        if ($stmt_related) {
            $stmt_related->bind_param('iss', $id_artikel, $main_keyword, $main_keyword);
            $stmt_related->execute();
            $related_result = $stmt_related->get_result();
            
            while ($row = $related_result->fetch_assoc()) {
                $related_articles[] = $row;
            }
            $stmt_related->close();
        }
    }
}

// ============================================
// DAPATKAN URL GAMBAR
// ============================================
$thumbnail_url = getArticleImage($artikel['thumbnail_path'], $id_artikel);

// Debug informasi
error_log("=== FINAL IMAGE URL ===");
error_log("Generated Thumbnail URL: " . $thumbnail_url);
error_log("=========================");

// ============================================
// SET PAGE METADATA
// ============================================
$pageTitle = safe_output($artikel['judul']) . ' - JDIH DPRD Kota Surakarta';
$meta_description = !empty($artikel['meta_description']) ? 
    safe_output($artikel['meta_description']) : 
    safe_output(substr(strip_tags($artikel['konten_artikel']), 0, 160));
$meta_keywords = !empty($artikel['meta_keywords']) ? 
    safe_output($artikel['meta_keywords']) : 
    'kegiatan jdih, dprd surakarta, hukum, berita hukum';

// Include header
include 'header.php';

// ============================================
// DEBUG PANEL (Hanya tampil jika ?debug=1)
// ============================================
if (isset($_GET['debug']) && $_GET['debug'] == 1) {
    echo '<div style="background:#f8f9fa; border:2px solid #dc3545; padding:20px; margin:20px 0; border-radius:10px; font-family:monospace; font-size:14px;">';
    echo '<h3 style="color:#dc3545; margin-top:0;">DEBUG INFORMATION</h3>';
    
    echo '<h4>Database Information:</h4>';
    echo '<p><strong>Article ID:</strong> ' . $id_artikel . '</p>';
    echo '<p><strong>Title:</strong> ' . safe_output($artikel['judul']) . '</p>';
    echo '<p><strong>Thumbnail Path in DB:</strong> ' . safe_output($artikel['thumbnail_path']) . '</p>';
    echo '<p><strong>Thumbnail Path (hex):</strong> ' . bin2hex($artikel['thumbnail_path']) . '</p>';
    echo '<p><strong>Path Length:</strong> ' . strlen($artikel['thumbnail_path']) . '</p>';
    
    echo '<h4>Server Information:</h4>';
    echo '<p><strong>Document Root:</strong> ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';
    echo '<p><strong>Current Dir:</strong> ' . __DIR__ . '</p>';
    echo '<p><strong>Project Root:</strong> ' . dirname(dirname(__DIR__)) . '</p>';
    
    echo '<h4>Image Information:</h4>';
    echo '<p><strong>Final Image URL:</strong> ' . $thumbnail_url . '</p>';
    
    // Cek apakah file ada
    if (strpos($thumbnail_url, 'http') !== 0) {
        $full_path = $_SERVER['DOCUMENT_ROOT'] . $thumbnail_url;
        echo '<p><strong>File Exists:</strong> ' . (file_exists($full_path) ? 'YES ✓' : 'NO ✗') . '</p>';
        echo '<p><strong>Full Server Path:</strong> ' . $full_path . '</p>';
        
        if (file_exists($full_path)) {
            echo '<p><strong>File Size:</strong> ' . filesize($full_path) . ' bytes</p>';
            echo '<p><strong>File Type:</strong> ' . mime_content_type($full_path) . '</p>';
        }
    }
    
    // Cek beberapa direktori umum
    echo '<h4>Directory Check:</h4>';
    $dirs_to_check = [
        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
        dirname(dirname(__DIR__)) . '/panel_control/uploads/artikel/',
    ];
    
    foreach ($dirs_to_check as $dir) {
        if (is_dir($dir)) {
            echo '<p style="color:green;">✓ Directory exists: ' . $dir . '</p>';
            $files = scandir($dir);
            if ($files) {
                echo '<p>Files (' . count($files) . '): ' . implode(', ', array_slice($files, 0, 20)) . '</p>';
            }
        } else {
            echo '<p style="color:red;">✗ Directory NOT found: ' . $dir . '</p>';
        }
    }
    
    echo '</div>';
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo safe_output($artikel['judul']); ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:image" content="<?php echo safe_output($thumbnail_url, 'attr'); ?>">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="JDIH DPRD Kota Surakarta">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo safe_output($artikel['judul']); ?>">
    <meta name="twitter:description" content="<?php echo $meta_description; ?>">
    <meta name="twitter:image" content="<?php echo safe_output($thumbnail_url, 'attr'); ?>">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary-color: #13547a;
            --secondary-color: #80d0c7;
            --accent-color: #ff7e5f;
            --light-color: #f0f8ff;
            --dark-color: #1a3c5c;
            --text-color: #333;
            --text-light: #666;
            --border-color: #e0e0e0;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        /* ===== BASE STYLES ===== */
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.7;
            color: var(--text-color);
            background: var(--light-color);
            padding-top: 70px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* ===== BREADCRUMB ===== */
        .breadcrumb-nav {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }
        
        .breadcrumb {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .breadcrumb-item {
            font-size: 0.9rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .breadcrumb-item a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        .breadcrumb-item.active {
            color: var(--text-light);
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            color: #999;
            margin-right: 10px;
        }
        
        /* ===== ARTICLE HEADER ===== */
        .article-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 0 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .article-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0V100H1000V0C800,50,500,80,0,0Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }
        
        .article-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }
        
        @media (max-width: 768px) {
            .article-title {
                font-size: 1.8rem;
            }
        }
        
        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            position: relative;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 25px;
            backdrop-filter: blur(5px);
        }
        
        .meta-item i {
            font-size: 1.1rem;
        }
        
        .article-category {
            background: var(--accent-color);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ===== ARTICLE CONTENT ===== */
        .article-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }
        
        .article-thumbnail {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
            transition: var(--transition);
        }
        
        @media (max-width: 768px) {
            .article-thumbnail {
                height: 300px;
            }
        }
        
        .article-body {
            padding: 40px;
        }
        
        @media (max-width: 768px) {
            .article-body {
                padding: 25px;
            }
        }
        
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .article-content h2 {
            font-size: 1.8rem;
            margin-top: 2.5rem;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            margin-top: 2rem;
            color: var(--dark-color);
        }
        
        .article-content ul, .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }
        
        .article-content li {
            margin-bottom: 0.5rem;
        }
        
        .article-content blockquote {
            border-left: 4px solid var(--accent-color);
            padding-left: 20px;
            margin: 2rem 0;
            font-style: italic;
            color: var(--text-light);
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0 10px 10px 0;
        }
        
        /* ===== ARTICLE FOOTER ===== */
        .article-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid var(--border-color);
        }
        
        .article-tags {
            margin-bottom: 30px;
        }
        
        .tag {
            display: inline-block;
            background: var(--light-color);
            color: var(--primary-color);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 0 5px 10px 0;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }
        
        .tag:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(19, 84, 122, 0.2);
            text-decoration: none;
        }
        
        .share-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .share-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        
        .share-btn:hover {
            transform: translateY(-3px) scale(1.1);
            text-decoration: none;
        }
        
        .share-fb {
            background: #3b5998;
        }
        
        .share-twitter {
            background: #1da1f2;
        }
        
        .share-wa {
            background: #25d366;
        }
        
        .share-link {
            background: var(--primary-color);
        }
        
        /* ===== AUTHOR SECTION ===== */
        .author-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: var(--shadow);
        }
        
        .author-info {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        @media (max-width: 576px) {
            .author-info {
                flex-direction: column;
                text-align: center;
            }
        }
        
        .author-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--secondary-color);
        }
        
        .author-details h4 {
            margin-bottom: 5px;
            color: var(--primary-color);
        }
        
        .author-details p {
            color: var(--text-light);
            margin-bottom: 5px;
            font-size: 0.95rem;
        }
        
        /* ===== RELATED ARTICLES ===== */
        .related-section {
            margin-bottom: 50px;
        }
        
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--secondary-color);
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: var(--accent-color);
        }
        
        .related-articles {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        @media (max-width: 768px) {
            .related-articles {
                grid-template-columns: 1fr;
            }
        }
        
        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            display: block;
        }
        
        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }
        
        .related-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .related-card:hover .related-image {
            transform: scale(1.05);
        }
        
        .related-content {
            padding: 20px;
        }
        
        .related-title {
            font-size: 1.1rem;
            line-height: 1.4;
            margin-bottom: 10px;
            color: var(--primary-color);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .related-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        .related-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* ===== BACK BUTTON ===== */
        .back-button {
            text-align: center;
            margin: 40px 0;
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        
        .btn-back:hover {
            background: transparent;
            color: var(--primary-color);
            border-color: var(--primary-color);
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        /* ===== TOAST NOTIFICATION ===== */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease;
            border-left: 4px solid var(--primary-color);
            max-width: 350px;
        }
        
        .toast-notification.success {
            border-left-color: #28a745;
        }
        
        .toast-notification.error {
            border-left-color: #dc3545;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .toast-content i {
            font-size: 1.2rem;
        }
        
        .toast-content i.bi-check-circle-fill {
            color: #28a745;
        }
        
        .toast-content i.bi-exclamation-circle-fill {
            color: #dc3545;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        /* ===== LOADING STATE ===== */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* ===== ACCESSIBILITY ===== */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* ===== PRINT STYLES ===== */
        @media print {
            .breadcrumb-nav,
            .share-buttons,
            .back-button {
                display: none;
            }
            
            .article-header {
                background: none !important;
                color: #000 !important;
                padding: 0;
            }
            
            .article-title {
                color: #000 !important;
            }
            
            .article-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body id="top">


<!-- ARTICLE HEADER -->
<section class="article-header">
    <div class="container">
        <h1 class="article-title"><?php echo safe_output($artikel['judul']); ?></h1>
        
        <div class="article-meta">
            <div class="meta-item">
                <i class="bi bi-calendar3"></i>
                <span><?php echo date('d F Y', strtotime($artikel['tanggal_publikasi'])); ?></span>
            </div>
            
            <div class="meta-item">
                <i class="bi bi-person-fill"></i>
                <span><?php echo !empty($artikel['nama_penulis']) ? safe_output($artikel['nama_penulis']) : 'Admin JDIH'; ?></span>
            </div>
            
            <div class="meta-item">
                <i class="bi bi-eye-fill"></i>
                <span><?php echo number_format($artikel['views'] + 1); ?> dilihat</span>
            </div>
            
            <?php if (!empty($artikel['meta_keywords'])): ?>
                <?php 
                $keywords = explode(',', $artikel['meta_keywords']);
                $main_category = trim($keywords[0]);
                if (!empty($main_category)): 
                ?>
                    <div class="article-category"><?php echo ucfirst(safe_output($main_category)); ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- BREADCRUMB NAVIGATION -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=kegiatan">Kegiatan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo safe_output($artikel['judul']); ?></li>
        </ol>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="main-content">
    <div class="container">
        <article class="article-container">
            <!-- ARTICLE THUMBNAIL -->
            <?php if (!empty($thumbnail_url)): ?>
                <img src="<?php echo safe_output($thumbnail_url, 'attr'); ?>" 
                     alt="<?php echo safe_output($artikel['judul'], 'attr'); ?>" 
                     class="article-thumbnail"
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80';"
                     loading="lazy">
            <?php endif; ?>
            
            <!-- ARTICLE CONTENT -->
            <div class="article-body">
                <div class="article-content">
                    <?php 
                    // Tampilkan konten artikel
                    echo $artikel['konten_artikel'];
                    ?>
                </div>
                
                <!-- ARTICLE FOOTER -->
                <footer class="article-footer">
                    <!-- TAGS -->
                    <?php if (!empty($artikel['meta_keywords'])): ?>
                        <div class="article-tags">
                            <strong class="me-2" style="color: var(--primary-color);">Tags:</strong>
                            <?php 
                            $tags = explode(',', $artikel['meta_keywords']);
                            foreach ($tags as $tag):
                                $trimmed_tag = trim($tag);
                                if (!empty($trimmed_tag)):
                            ?>
                                <a href="index.php?page=kegiatan&filter=<?php echo urlencode(strtolower(safe_output($trimmed_tag))); ?>" 
                                   class="tag">
                                    <?php echo safe_output($trimmed_tag); ?>
                                </a>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SHARE BUTTONS -->
                    <div class="share-buttons">
                        <strong class="me-3 align-self-center" style="color: var(--primary-color);">Bagikan:</strong>
                        <?php
                        $current_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        $share_title = urlencode(safe_output($artikel['judul']));
                        ?>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($current_url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="share-btn share-fb" 
                           title="Share on Facebook"
                           aria-label="Share on Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo urlencode($current_url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="share-btn share-twitter" 
                           title="Share on Twitter"
                           aria-label="Share on Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . urlencode($current_url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="share-btn share-wa" 
                           title="Share on WhatsApp"
                           aria-label="Share on WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <button class="share-btn share-link" onclick="copyToClipboard()" title="Copy link" aria-label="Copy link">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </footer>
            </div>
        </article>
        
        <!-- AUTHOR SECTION -->
        <section class="author-section" aria-labelledby="author-title">
            <h2 id="author-title" class="sr-only">Informasi Penulis</h2>
            <div class="author-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode(!empty($artikel['nama_penulis']) ? $artikel['nama_penulis'] : 'Admin JDIH'); ?>&background=13547a&color=fff&size=100" 
                     alt="<?php echo !empty($artikel['nama_penulis']) ? safe_output($artikel['nama_penulis'], 'attr') : 'Admin JDIH'; ?>" 
                     class="author-avatar">
                <div class="author-details">
                    <h3><?php echo !empty($artikel['nama_penulis']) ? safe_output($artikel['nama_penulis']) : 'Admin JDIH'; ?></h3>
                    <p>Penulis Artikel</p>
                    <p class="mt-2">
                        Artikel ini dipublikasikan pada <?php echo date('d F Y', strtotime($artikel['tanggal_publikasi'])); ?>. 
                        <?php if (!empty($artikel['tanggal_update'])): ?>
                            Terakhir diperbarui: <?php echo date('d F Y', strtotime($artikel['tanggal_update'])); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </section>
        
        <!-- RELATED ARTICLES -->
        <?php if (!empty($related_articles)): ?>
            <section class="related-section" aria-labelledby="related-title">
                <h2 id="related-title" class="section-title">Artikel Terkait</h2>
                <div class="related-articles">
                    <?php foreach ($related_articles as $related): 
                        $related_thumbnail = getArticleImage($related['thumbnail_path'], $related['id_artikel']);
                        $related_token = encode_id($related['id_artikel']);
                    ?>
                        <a href="index.php?page=detail-kegiatan&token=<?php echo urlencode($related_token); ?>" 
                           class="related-card">
                            <img src="<?php echo safe_output($related_thumbnail, 'attr'); ?>" 
                                 alt="<?php echo safe_output($related['judul'], 'attr'); ?>" 
                                 class="related-image"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';"
                                 loading="lazy">
                            <div class="related-content">
                                <h3 class="related-title"><?php echo safe_output($related['judul']); ?></h3>
                                <div class="related-meta">
                                    <span><i class="bi bi-calendar3"></i> <?php echo date('d M Y', strtotime($related['tanggal_publikasi'])); ?></span>
                                    <span><i class="bi bi-eye-fill"></i> <?php echo number_format($related['views']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- BACK BUTTON -->
        <div class="back-button">
            <a href="index.php?page=kegiatan" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kegiatan
            </a>
        </div>
    </div>
</main>

<!-- JAVASCRIPT -->
<script>
    // CSRF Token untuk AJAX requests
    const csrfToken = '<?php echo $_SESSION["csrf_token"] ?? ""; ?>';
    
    // Copy to clipboard function
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            showToast('Link berhasil disalin ke clipboard!', 'success');
            
            // Update button temporarily
            const button = document.querySelector('.share-link');
            const originalHTML = button.innerHTML;
            const originalBg = button.style.background;
            
            button.innerHTML = '<i class="bi bi-check"></i>';
            button.style.background = '#28a745';
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.style.background = originalBg;
            }, 2000);
            
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            showToast('Gagal menyalin link. Silakan salin manual.', 'error');
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 4000);
    }
    
    // Image lazy loading
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => {
                if (!img.complete) {
                    img.dataset.src = img.src;
                    imageObserver.observe(img);
                }
            });
        }
        
        // Add loading state to share buttons
        const shareButtons = document.querySelectorAll('.share-btn');
        shareButtons.forEach(btn => {
            if (btn.tagName === 'A') {
                btn.addEventListener('click', function(e) {
                    // Small delay to show loading (if needed)
                    setTimeout(() => {
                        window.open(this.href, '_blank');
                    }, 100);
                });
            }
        });
        
        // Image error handling
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                console.warn('Image failed to load:', this.src);
                if (!this.src.includes('unsplash.com')) {
                    this.src = 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80';
                }
            });
        });
    });
    
    // Print functionality
    function printArticle() {
        window.print();
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + P to print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printArticle();
        }
        
        // Escape to close toast
        if (e.key === 'Escape') {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(toast => toast.remove());
        }
        
        // B to go back
        if (e.key === 'b' && !e.ctrlKey && !e.metaKey && !e.altKey) {
            const backButton = document.querySelector('.btn-back');
            if (backButton) {
                backButton.click();
            }
        }
    });
    
    // Add data-src attribute for lazy loading
    document.querySelectorAll('.article-thumbnail, .related-image').forEach(img => {
        if (!img.hasAttribute('data-src')) {
            img.setAttribute('data-src', img.src);
        }
    });
</script>

<?php 
// Tutup koneksi database dengan aman
if (isset($koneksi)) {
    $koneksi->close();
}

include 'footer.php';
?>