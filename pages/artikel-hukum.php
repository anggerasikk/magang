<?php
// artikel-hukum.php - VERSION FIXED

// ============================================
// KEAMANAN LEVEL 1: ERROR HANDLING
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// ============================================
// KEAMANAN LEVEL 2: SESSION SECURITY
// ============================================
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' => 'Strict',
    'use_only_cookies' => 1
]);

// ============================================
// KEAMANAN LEVEL 3: INCLUDE REQUIRED FILES
// ============================================
// Check and include a_db.php
include_once "a_db.php";

// Check and include a_functions.php
if (!file_exists('a_functions.php')) {
    error_log("CRITICAL: a_functions.php not found in " . __DIR__);
    
    // Fallback functions if a_functions.php doesn't exist
    function encode_id($id) { 
        return base64_encode('jdih_' . $id . '_' . time());
    }
    function decode_id($token) {
        $decoded = base64_decode($token);
        if (!$decoded) return 0;
        $parts = explode('_', $decoded);
        return isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : 0;
    }
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
} else {
    include_once "a_functions.php";
}

// ============================================
// KEAMANAN LEVEL 4: INPUT VALIDATION & SANITIZATION
// ============================================
// Sanitize all GET parameters
$clean_get = [];
foreach ($_GET as $key => $value) {
    $clean_get[$key] = sanitize_input($value);
}

// Extract sanitized parameters
$debug_mode = isset($clean_get['debug']) ? (int)$clean_get['debug'] : 0;
$page = isset($clean_get['page']) ? $clean_get['page'] : '';
$year_filter = isset($clean_get['year']) ? $clean_get['year'] : '';
$search_query = isset($clean_get['search']) ? $clean_get['search'] : '';
$type_filter = isset($clean_get['type']) ? $clean_get['type'] : '';

// ============================================
// KEAMANAN LEVEL 5: PATH CONFIGURATION & VALIDATION - DIPERBAIKI
// ============================================

// DEBUG: Tampilkan informasi path untuk troubleshooting
if ($debug_mode) {
    error_log("DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT']);
    error_log("SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME']);
    error_log("__DIR__: " . __DIR__);
}

// PATH KONFIGURASI - MENGGUNAKAN $_SERVER['DOCUMENT_ROOT']
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// Base paths - SELALU GUNAKAN document_root
$base_upload_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/";
$web_image_path = "/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/images/";
$pdf_base_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/pdf/";

// Fallback jika path tidak ditemukan
if (!is_dir($base_upload_path)) {
    // Coba alternatif paths
    $possible_paths = [
        $document_root . "/panel_control/uploads/artikel_hukum/",
        $document_root . "/uploads/artikel_hukum/",
        dirname(__DIR__) . "/panel_control/uploads/artikel_hukum/",
        "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/",
        "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/"
    ];
    
    foreach ($possible_paths as $possible_path) {
        if (is_dir($possible_path)) {
            $base_upload_path = $possible_path;
            $pdf_base_path = dirname($possible_path) . "/pdf/";
            if (!is_dir($pdf_base_path)) {
                $pdf_base_path = $possible_path . "pdf/";
            }
            break;
        }
    }
}

// Pastikan folder ada
if (!is_dir($pdf_base_path)) {
    @mkdir($pdf_base_path, 0755, true);
}

// Log path untuk debugging
if ($debug_mode) {
    error_log("Document Root: " . $document_root);
    error_log("Base Upload Path: " . $base_upload_path);
    error_log("Web Image Path: " . $web_image_path);
    error_log("PDF Base Path: " . $pdf_base_path);
    error_log("PDF Dir exists: " . (is_dir($pdf_base_path) ? 'YES' : 'NO'));
    error_log("PDF Dir readable: " . (is_readable($pdf_base_path) ? 'YES' : 'NO'));
    
    // List files in PDF directory
    if (is_dir($pdf_base_path)) {
        $files = scandir($pdf_base_path);
        error_log("Files in PDF dir: " . implode(", ", array_filter($files, function($file) {
            return !in_array($file, ['.', '..']) && pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
        })));
    }
}

// Validate paths to prevent directory traversal
function validate_file_path($path, $allowed_base) {
    // Normalize paths
    $real_path = realpath($path);
    $real_allowed = realpath($allowed_base);
    
    if ($real_path === false || $real_allowed === false) {
        error_log("Path validation failed: " . $path . " | " . $allowed_base);
        return false;
    }
    
    // Check if path is within allowed directory
    return strpos($real_path, $real_allowed) === 0;
}

// ============================================
// KEAMANAN LEVEL 6: SECURE FILE FUNCTIONS - DIPERBAIKI
// ============================================

// Fungsi untuk menemukan file PDF artikel - VERSION FIXED
function findArtikelPDF($filename, $id = null) {
    global $pdf_base_path, $debug_mode;
    
    if ($debug_mode) {
        error_log("=== findArtikelPDF CALLED ===");
        error_log("Input filename: {$filename}");
        error_log("Input ID: {$id}");
        error_log("PDF base path: {$pdf_base_path}");
    }
    
    if (empty($filename) || !is_string($filename)) {
        if ($debug_mode) error_log("Filename empty or not string");
        return null;
    }
    
    // Check if directory exists and is readable
    if (!is_dir($pdf_base_path) || !is_readable($pdf_base_path)) {
        if ($debug_mode) error_log("PDF directory not accessible: {$pdf_base_path}");
        return null;
    }
    
    // Sanitize filename
    $filename = basename($filename);
    $clean_filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $clean_filename);
    
    if ($debug_mode) error_log("Cleaned filename: {$clean_filename}");
    
    // Validate filename length
    if (strlen($clean_filename) > 255) {
        if ($debug_mode) error_log("Filename too long");
        return null;
    }
    
    // Possible locations with security validation
    $possible_locations = [];
    
    // Base location - filename from database
    $base_location = $pdf_base_path . $clean_filename;
    if (validate_file_path($base_location, $pdf_base_path)) {
        $possible_locations[] = $base_location;
        if ($debug_mode) error_log("Added base location: {$base_location}");
    }
    
    // Original filename
    $original_location = $pdf_base_path . $filename;
    if (validate_file_path($original_location, $pdf_base_path)) {
        $possible_locations[] = $original_location;
        if ($debug_mode) error_log("Added original location: {$original_location}");
    }
    
    // If ID provided, try ID-based naming
    if ($id && is_numeric($id)) {
        $id_locations = [
            $pdf_base_path . $id . '.pdf',
            $pdf_base_path . $id . '.PDF',
            $pdf_base_path . 'artikel_' . $id . '.pdf',
            $pdf_base_path . 'ARTIKEL_' . $id . '.pdf',
        ];
        
        foreach ($id_locations as $location) {
            if (validate_file_path($location, $pdf_base_path)) {
                $possible_locations[] = $location;
                if ($debug_mode) error_log("Added ID location: {$location}");
            }
        }
    }
    
    // Remove duplicates
    $possible_locations = array_unique($possible_locations);
    
    if ($debug_mode) {
        error_log("Total possible locations: " . count($possible_locations));
        foreach ($possible_locations as $loc) {
            error_log("Checking: {$loc}");
        }
    }
    
    // Check each location
    foreach ($possible_locations as $location) {
        if ($debug_mode) {
            error_log("Testing location: {$location}");
            error_log("File exists: " . (file_exists($location) ? 'YES' : 'NO'));
        }
        
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            if ($debug_mode) {
                error_log("File is readable");
                error_log("File size: " . filesize($location));
            }
            
            // Verify it's actually a PDF file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if ($debug_mode) error_log("MIME type: {$mime_type}");
            
            // Strict MIME type validation
            $allowed_mimes = ['application/pdf', 'application/x-pdf'];
            if (in_array($mime_type, $allowed_mimes, true)) {
                if ($debug_mode) error_log("PDF FOUND: {$location}");
                return $location;
            } else {
                if ($debug_mode) error_log("Invalid MIME type");
            }
        }
    }
    
    if ($debug_mode) error_log("PDF NOT FOUND");
    return null;
}

// Fungsi untuk menemukan thumbnail artikel - SIMPLIFIED VERSION
function findArtikelImage($image_name, $id = null) {
    global $base_upload_path, $web_image_path;
    
    // Declare debug_mode as global to fix the error
    global $debug_mode;
    
    $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    
    if ($debug_mode) {
        error_log("=== findArtikelImage CALLED ===");
        error_log("Image name: {$image_name}");
        error_log("ID: {$id}");
    }
    
    // 1. Coba path yang paling mungkin
    $possible_paths = [];
    
    // Jika ada nama file dari database
    if (!empty($image_name)) {
        $clean_name = basename($image_name);
        
        // Lokasi utama
        $main_path = $base_upload_path . 'images/' . $clean_name;
        $possible_paths[] = $main_path;
        
        // Alternatif lokasi
        $possible_paths = array_merge($possible_paths, [
            $base_upload_path . $clean_name,
            $document_root . $web_image_path . $clean_name,
            $document_root . '/uploads/artikel_hukum/images/' . $clean_name,
            $document_root . '/panel_control/uploads/artikel_hukum/images/' . $clean_name,
        ]);
    }
    
    // 2. Coba berdasarkan ID
    if ($id) {
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($extensions as $ext) {
            $possible_paths[] = $base_upload_path . 'images/' . $id . '.' . $ext;
            $possible_paths[] = $base_upload_path . $id . '.' . $ext;
            $possible_paths[] = $document_root . $web_image_path . $id . '.' . $ext;
        }
    }
    
    // 3. Cek setiap lokasi
    foreach ($possible_paths as $path) {
        if ($debug_mode) error_log("Checking image path: {$path}");
        
        if (file_exists($path) && is_file($path) && is_readable($path)) {
            // Validasi gambar
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $path);
            finfo_close($finfo);
            
            if (strpos($mime_type, 'image/') === 0) {
                // Convert to URL
                $url = convert_path_to_url($path);
                if ($url) {
                    if ($debug_mode) error_log("Image FOUND: {$url}");
                    return [
                        'path' => $path,
                        'url' => $url,
                        'source' => 'server'
                    ];
                }
            }
        }
    }
    
    // 4. FALLBACK: Gambar default
    if ($debug_mode) error_log("No image found, using default");
    return [
        'path' => '',
        'url' => '/jdihcilacap_pasang_2026/panel_control/uploads/images/ARTIKEL HUKUM.png',
        'source' => 'fallback'
    ];
}

// Fungsi konversi path fisik ke URL - DIPERBAIKI
function convert_path_to_url($physical_path) {
    // Declare debug_mode as global to fix the error
    global $debug_mode;
    
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $physical_path = str_replace('\\', '/', $physical_path);
    
    if ($debug_mode) {
        error_log("convert_path_to_url called");
        error_log("Physical path: {$physical_path}");
        error_log("Doc root: {$doc_root}");
    }
    
    // Jika path ada dalam document root
    if (strpos($physical_path, $doc_root) === 0) {
        $url_path = substr($physical_path, strlen($doc_root));
        // Pastikan diawali dengan slash
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        
        if ($debug_mode) error_log("Converted to URL: {$url_path}");
        return $url_path;
    }
    
    // Cek jika path menggunakan XAMPP/Laragon format
    $patterns = [
        'C:/xampp/htdocs/' => '',
        'C:/laragon/www/' => '',
        '/var/www/html/' => '',
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        if (strpos($physical_path, $pattern) === 0) {
            $url_path = substr($physical_path, strlen($pattern));
            if (strpos($url_path, '/') !== 0) {
                $url_path = '/' . $url_path;
            }
            
            if ($debug_mode) error_log("Converted via pattern: {$url_path}");
            return $url_path;
        }
    }
    
    if ($debug_mode) error_log("Could not convert path to URL");
    return null;
}

// ============================================
// KEAMANAN LEVEL 7: DATABASE QUERY - DIPERBAIKI
// ============================================
try {
    // Build base query
    $where_conditions = ["UPPER(status) = 'PUBLISHED'"];
    
    // Add search condition
    if (!empty($search_query)) {
        $search_escaped = mysqli_real_escape_string($koneksi, $search_query);
        $search_conditions = [
            "judul LIKE '%$search_escaped%'",
            "keywords LIKE '%$search_escaped%'",
            "abstrak LIKE '%$search_escaped%'",
            "penulis LIKE '%$search_escaped%'",
            "jurnal LIKE '%$search_escaped%'"
        ];
        $where_conditions[] = "(" . implode(" OR ", $search_conditions) . ")";
    }
    
    // Add year filter
    if (!empty($year_filter) && is_numeric($year_filter)) {
        $where_conditions[] = "tahun_terbit = " . (int)$year_filter;
    }
    
    // Add type filter
    if (!empty($type_filter) && $type_filter != 'semua') {
        $type_escaped = mysqli_real_escape_string($koneksi, $type_filter);
        $where_conditions[] = "jenis_artikel = '$type_escaped'";
    }
    
    // Build final query
    $where_clause = implode(" AND ", $where_conditions);
    $query = "SELECT * FROM artikel_hukum WHERE $where_clause ORDER BY tahun_terbit DESC, tanggal_terbit DESC";
    
    if ($debug_mode) {
        error_log("SQL Query: " . $query);
    }
    
    $result = mysqli_query($koneksi, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($koneksi));
    }
    
    // Get total search results
    $search_results_count = mysqli_num_rows($result);
    
    if ($debug_mode) {
        error_log("Search results count: " . $search_results_count);
    }
    
} catch (Exception $e) {
    error_log("Database error in artikel-hukum.php: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}

// ============================================
// KEAMANAN LEVEL 8: GET ADDITIONAL DATA
// ============================================

// Total documents
$total_query = "SELECT COUNT(*) as total FROM artikel_hukum WHERE UPPER(status) = 'PUBLISHED'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = $total_result ? (int)mysqli_fetch_assoc($total_result)['total'] : 0;

// Year statistics
$year_stats_query = "SELECT tahun_terbit, COUNT(*) as jumlah 
                     FROM artikel_hukum 
                     WHERE UPPER(status) = 'PUBLISHED'
                     GROUP BY tahun_terbit 
                     ORDER BY tahun_terbit DESC 
                     LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// Popular articles
$popular_query = "SELECT id_artikel, judul, penulis, tahun_terbit 
                  FROM artikel_hukum 
                  WHERE UPPER(status) = 'PUBLISHED'
                  ORDER BY created_at DESC 
                  LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

// Year filter
$years_query = "SELECT DISTINCT tahun_terbit 
                FROM artikel_hukum 
                WHERE UPPER(status) = 'PUBLISHED'
                ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $year = (int)$row['tahun_terbit'];
    if ($year >= 1900 && $year <= 2100) {
        $years[] = $year;
    }
}

// Get all article types for filter
$types_query = "SELECT DISTINCT jenis_artikel 
                FROM artikel_hukum 
                WHERE UPPER(status) = 'PUBLISHED' 
                AND jenis_artikel IS NOT NULL 
                AND jenis_artikel != '' 
                ORDER BY jenis_artikel";
$types_result = mysqli_query($koneksi, $types_query);
$article_types = [];
while ($row = mysqli_fetch_assoc($types_result)) {
    $article_types[] = $row['jenis_artikel'];
}

// ============================================
// KEAMANAN LEVEL 9: SET SECURITY HEADERS
// ============================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ============================================
// INCLUDE HEADER TEMPLATE
// ============================================
$page_title = safe_output("Artikel Hukum - JDIH DPRD Kota Surakarta");
include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK ARTIKEL HUKUM */
.card-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-image:hover img {
    transform: scale(1.05);
}

.placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    text-align: center;
}

.placeholder i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.9;
}

.placeholder p {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
    line-height: 1.4;
}

.image-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.image-badge {
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* TOMBOL DOWNLOAD */
.download-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.download-btn.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* Article specific styles */
.article-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
}

.article-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #6c757d;
}

.article-meta-item i {
    font-size: 14px;
}

.abstract-preview {
    font-size: 14px;
    line-height: 1.5;
    color: #555;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.keywords {
    margin-bottom: 15px;
}

.keyword-tag {
    display: inline-block;
    background: #e9ecef;
    color: #495057;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    margin-right: 5px;
    margin-bottom: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
    
    .article-meta {
        flex-direction: column;
        gap: 5px;
    }
}

/* Debug info */
.debug-info {
    font-size: 11px;
    border-left: 3px solid #dc3545;
    padding: 10px;
    background: #f8f9fa;
    margin-top: 10px;
}

/* Download Status */
.download-status {
    font-size: 11px;
    margin-top: 5px;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
}
.download-status.available {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.download-status.unavailable {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Type filter */
.type-filter {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.type-filter h5 {
    margin-bottom: 10px;
    color: #2c3e50;
}

.type-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.type-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
}

.type-btn:hover {
    background: #e9ecef;
}

.type-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* Image debug */
.img-debug {
    position: absolute;
    top: 5px;
    left: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 10px;
    z-index: 10;
}
</style>


<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">ARTIKEL HUKUM</h1>
        <p class="subtitle">Kumpulan Artikel Ilmiah Bidang Hukum</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=artikel-hukum">Publikasi</a></li>
                <li class="breadcrumb-item active">Artikel Hukum</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT
================================ -->
<section class="main-content">
<div class="container">
<div class="row">

<!-- ===== KONTEN UTAMA ===== -->
<div class="col-lg-8">

<!-- SEARCH BAR -->
<div class="search-box">
    <form method="GET" action="?" class="d-flex gap-2">
        <input type="hidden" name="page" value="artikel-hukum">
        <div class="flex-grow-1">
            <input type="text" 
                   name="search" 
                   class="form-control form-control-lg" 
                   placeholder="Cari artikel berdasarkan judul, penulis, kata kunci, atau abstrak..." 
                   value="<?= safe_output($search_query) ?>"
                   aria-label="Search articles">
        </div>
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($search_query) || !empty($year_filter) || !empty($type_filter)): ?>
            <a href="?page=artikel-hukum" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- FILTER TYPE -->
<div class="type-filter">
    <h5><i class="bi bi-filter"></i> Filter Berdasarkan Jenis Artikel</h5>
    <div class="type-buttons">
        <button class="type-btn active" data-type="semua">Semua Jenis</button>
        <?php foreach ($article_types as $type): ?>
            <button class="type-btn" data-type="<?= safe_output($type) ?>"><?= safe_output($type) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- FILTER TAHUN -->
<div class="year-filter">
    <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun Terbit</h5>
    <div class="year-buttons">
        <button class="year-btn active" data-year="semua">Semua Tahun</button>
        <?php foreach ($years as $year): ?>
            <button class="year-btn" data-year="<?= safe_output($year) ?>"><?= safe_output($year) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- HASIL SEARCH / LIST ARTIKEL -->
<?php if (!empty($search_query)): ?>
    <?php if ($search_results_count > 0): ?>
        <?php
        // Store search results for display
        $search_results = [];
        mysqli_data_seek($result, 0);
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
        ?>
        <h3 class="section-title">Hasil Pencarian: "<?= safe_output($search_query) ?>" (<?= $search_results_count ?> artikel)</h3>
        <div class="card-container">
            <?php foreach ($search_results as $row): 
                $tanggal = date('d F Y', strtotime($row['tanggal_terbit']));
                
                // ENKRIPSI ID
                $id = (int)$row['id_artikel'];
                $encrypted_id = encode_id($id);
                if (!$encrypted_id) {
                    $encrypted_id = base64_encode('jdih_artikel_' . $id . '_' . time());
                }
                
                // CARI FILE PDF
                $file_found = false;
                $actual_file_path = null;
                $download_url = '#';
                
                if (!empty($row['file_pdf'])) {
                    $actual_file_path = findArtikelPDF($row['file_pdf'], $row['id_artikel']);
                    if ($actual_file_path && file_exists($actual_file_path)) {
                        $file_found = true;
                        
                        // Convert to URL dengan benar
                        $normalized_path = str_replace('\\', '/', $actual_file_path);
                        $normalized_docroot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
                        
                        $relative_path = str_replace($normalized_docroot, '', $normalized_path);
                        if (strpos($relative_path, '/') !== 0) {
                            $relative_path = '/' . $relative_path;
                        }
                        
                        $download_url = $relative_path . '?download=1&t=' . time();
                        
                        if ($debug_mode) {
                            error_log("PDF Found for ID {$row['id_artikel']}: {$actual_file_path}");
                            error_log("Download URL: {$download_url}");
                        }
                    } else {
                        if ($debug_mode) {
                            error_log("PDF NOT Found for ID {$row['id_artikel']}: {$row['file_pdf']}");
                        }
                    }
                }
                
                // CARI THUMBNAIL
                $image_info = findArtikelImage($row['thumbnail'], $row['id_artikel']);
                $image_url = $image_info['url'] ?? '';
                $image_found = !empty($image_url);
                
                // DETAIL URL
                $detail = "index.php?page=detail-artikel&token=" . urlencode($encrypted_id);
                
                // Parse keywords
                $keywords = !empty($row['keywords']) ? explode(',', $row['keywords']) : [];
            ?>
            <div class="document-card" data-tahun="<?= safe_output($row['tahun_terbit']); ?>" data-jenis="<?= safe_output($row['jenis_artikel']); ?>">
                <div class="card-image">
                    <?php if ($image_found): ?>
                        <?php if ($debug_mode): ?>
                            <div class="img-debug">✓</div>
                        <?php endif; ?>
                        <img src="<?= safe_output($image_url) ?>" 
                             alt="<?= safe_output($row['judul']) ?>"
                             loading="lazy"
                             onerror="handleImageError(this)"
                             data-src="<?= safe_output($image_url) ?>">
                    <?php else: ?>
                        <?php if ($debug_mode): ?>
                            <div class="img-debug">✗</div>
                        <?php endif; ?>
                        <div class="placeholder">
                            <i class="bi bi-journal-text"></i>
                            <p>Artikel Hukum<br>
                            <small><?= safe_output($row['jenis_artikel']) ?></small></p>
                        </div>
                    <?php endif; ?>
                    <div class="image-overlay">
                        <span class="image-badge"><?= safe_output($row['jenis_artikel']) ?></span>
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['jurnal']) ?> - Vol. <?= safe_output($row['volume']) ?>, No. <?= safe_output($row['issue']) ?></div>
                    <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>
                    
                    <div class="article-meta">
                        <div class="article-meta-item">
                            <i class="bi bi-person"></i>
                            <span><?= safe_output($row['penulis']) ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="bi bi-calendar"></i>
                            <span><?= safe_output($tanggal) ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="bi bi-translate"></i>
                            <span><?= safe_output($row['bahasa']) ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="bi bi-tag"></i>
                            <span><?= safe_output($row['bidang_hukum']) ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($row['abstrak'])): ?>
                    <div class="abstract-preview">
                        <?= safe_output(substr($row['abstrak'], 0, 200)) ?>...
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($keywords)): ?>
                    <div class="keywords">
                        <?php foreach ($keywords as $keyword): ?>
                            <span class="keyword-tag"><?= safe_output(trim($keyword)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                        <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                        <?= $file_found ? 'Full Text PDF tersedia' : 'Full Text tidak tersedia' ?>
                    </div>

                    <div class="card-actions">
                        <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Baca Abstrak
                        </a>
                        
                        <?php if ($file_found): ?>
                            <a href="<?= safe_output($download_url) ?>" 
                               class="card-btn btn-download download-btn" 
                               data-id="<?= safe_output($row['id_artikel']); ?>"
                               data-filename="<?= safe_output(basename($actual_file_path)) ?>"
                               download="<?= safe_output(basename($actual_file_path)) ?>">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        <?php else: ?>
                            <button class="card-btn btn-download disabled" 
                                    onclick="showFileNotFoundAlert('<?= addslashes(safe_output($row['judul'])) ?>')"
                                    title="File PDF tidak tersedia">
                                <i class="bi bi-download"></i> Download PDF
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($debug_mode): ?>
                    <div class="debug-info">
                        <small>
                            <strong>Debug Info:</strong><br>
                            ID: <?= safe_output($row['id_artikel']) ?><br>
                            Thumbnail: <?= safe_output($row['thumbnail']) ?><br>
                            Thumbnail Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                            PDF File: <?= safe_output($row['file_pdf']) ?><br>
                            PDF Found: <?= $file_found ? 'Ya' : 'Tidak' ?><br>
                            PDF Path: <?= safe_output($actual_file_path) ?><br>
                            Download URL: <?= safe_output($download_url) ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-info-circle"></i> Tidak ada artikel yang sesuai dengan pencarian "<strong><?= safe_output($search_query) ?></strong>".
        </div>
        <!-- Tampilkan semua artikel jika tidak ada hasil pencarian -->
        <?php 
        $all_query = "SELECT * FROM artikel_hukum WHERE UPPER(status) = 'PUBLISHED' ORDER BY tahun_terbit DESC, tanggal_terbit DESC";
        $all_result = mysqli_query($koneksi, $all_query);
        ?>
        <h3 class="section-title mt-4">Semua Artikel Hukum</h3>
        <div class="card-container">
            <?php while ($row = mysqli_fetch_assoc($all_result)):
                $id = (int)$row['id_artikel'];
                $encrypted_id = encode_id($id);
                $image_info = findArtikelImage($row['thumbnail'], $row['id_artikel']);
                $image_url = $image_info['url'] ?? '';
                $image_found = !empty($image_url);
            ?>
            <div class="document-card">
                <div class="card-image">
                    <?php if ($image_found): ?>
                        <img src="<?= safe_output($image_url) ?>" alt="<?= safe_output($row['judul']) ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="bi bi-journal-text"></i>
                            <p><?= safe_output($row['jenis_artikel']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['jurnal']) ?></div>
                    <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>
                    <div class="card-actions">
                        <a href="?page=detail-artikel&token=<?= urlencode($encrypted_id) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Baca Abstrak
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
<?php else: // Tampilkan filter dan daftar normal jika tidak ada search ?>

<h3 class="section-title">Daftar Artikel Hukum (<?= $total_dokumen ?> artikel)</h3>

<?php if (mysqli_num_rows($result) > 0): 
    mysqli_data_seek($result, 0);
?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):
    $tanggal = date('d F Y', strtotime($row['tanggal_terbit']));
    
    // ENKRIPSI ID
    $id = (int)$row['id_artikel'];
    $encrypted_id = encode_id($id);
    if (!$encrypted_id) {
        $encrypted_id = base64_encode('jdih_artikel_' . $id . '_' . time());
    }
    
    // CARI FILE PDF
    $file_found = false;
    $actual_file_path = null;
    $download_url = '#';
    
    if (!empty($row['file_pdf'])) {
        $actual_file_path = findArtikelPDF($row['file_pdf'], $row['id_artikel']);
        if ($actual_file_path && file_exists($actual_file_path)) {
            $file_found = true;
            
            // Convert to URL dengan benar
            $normalized_path = str_replace('\\', '/', $actual_file_path);
            $normalized_docroot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
            
            $relative_path = str_replace($normalized_docroot, '', $normalized_path);
            if (strpos($relative_path, '/') !== 0) {
                $relative_path = '/' . $relative_path;
            }
            
            $download_url = $relative_path . '?download=1&t=' . time();
        }
    }
    
    // CARI THUMBNAIL
    $image_info = findArtikelImage($row['thumbnail'], $row['id_artikel']);
    $image_url = $image_info['url'] ?? '';
    $image_found = !empty($image_url);
    
    // DETAIL URL
    $detail = "index.php?page=detail-artikel&token=" . urlencode($encrypted_id);
    
    // Parse keywords
    $keywords = !empty($row['keywords']) ? explode(',', $row['keywords']) : [];
?>

<div class="document-card" data-tahun="<?= safe_output($row['tahun_terbit']); ?>" data-jenis="<?= safe_output($row['jenis_artikel']); ?>">
    <div class="card-image">
        <?php if ($image_found): ?>
            <?php if ($debug_mode): ?>
                <div class="img-debug">✓</div>
            <?php endif; ?>
            <img src="<?= safe_output($image_url) ?>" 
                 alt="<?= safe_output($row['judul']) ?>"
                 loading="lazy"
                 onerror="handleImageError(this)"
                 data-src="<?= safe_output($image_url) ?>">
        <?php else: ?>
            <?php if ($debug_mode): ?>
                <div class="img-debug">✗</div>
            <?php endif; ?>
            <div class="placeholder">
                <i class="bi bi-journal-text"></i>
                <p>Artikel Hukum<br>
                <small><?= safe_output($row['jenis_artikel']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge"><?= safe_output($row['jenis_artikel']) ?></span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number">
            <?php if (!empty($row['jurnal'])): ?>
                <?= safe_output($row['jurnal']) ?>
                <?php if (!empty($row['volume']) || !empty($row['issue'])): ?>
                    - Vol. <?= safe_output($row['volume']) ?>, No. <?= safe_output($row['issue']) ?>
                <?php endif; ?>
                <?php if (!empty($row['issn'])): ?>
                    <br><small>ISSN: <?= safe_output($row['issn']) ?></small>
                <?php endif; ?>
            <?php else: ?>
                Artikel Ilmiah
            <?php endif; ?>
        </div>
        
        <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>

        <div class="article-meta">
            <div class="article-meta-item">
                <i class="bi bi-person"></i>
                <span><?= safe_output($row['penulis']) ?></span>
            </div>
            <div class="article-meta-item">
                <i class="bi bi-calendar"></i>
                <span><?= safe_output($tanggal) ?></span>
            </div>
            <div class="article-meta-item">
                <i class="bi bi-translate"></i>
                <span><?= safe_output($row['bahasa']) ?></span>
            </div>
            <div class="article-meta-item">
                <i class="bi bi-tag"></i>
                <span><?= safe_output($row['bidang_hukum']) ?></span>
            </div>
        </div>
        
        <?php if (!empty($row['abstrak'])): ?>
        <div class="abstract-preview">
            <?= safe_output(substr($row['abstrak'], 0, 200)) ?>...
        </div>
        <?php endif; ?>
        
        <?php if (!empty($keywords)): ?>
        <div class="keywords">
            <?php foreach (array_slice($keywords, 0, 5) as $keyword): ?>
                <span class="keyword-tag"><?= safe_output(trim($keyword)) ?></span>
            <?php endforeach; ?>
            <?php if (count($keywords) > 5): ?>
                <span class="keyword-tag">+<?= count($keywords) - 5 ?> lainnya</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $file_found ? 'Full Text PDF tersedia' : 'Full Text tidak tersedia' ?>
        </div>

        <div class="card-actions">
            <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Baca Abstrak
            </a>
            
            <?php if ($file_found): ?>
                <a href="<?= safe_output($download_url) ?>" 
                   class="card-btn btn-download download-btn" 
                   data-id="<?= safe_output($row['id_artikel']); ?>"
                   data-filename="<?= safe_output(basename($actual_file_path)) ?>"
                   download="<?= safe_output(basename($actual_file_path)) ?>">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php else: ?>
                <button class="card-btn btn-download disabled" 
                        onclick="showFileNotFoundAlert('<?= addslashes(safe_output($row['judul'])) ?>')"
                        title="File PDF tidak tersedia">
                    <i class="bi bi-download"></i> Download PDF
                </button>
            <?php endif; ?>
        </div>
        
        <?php if ($debug_mode): ?>
        <div class="debug-info">
            <small>
                <strong>Debug Info:</strong><br>
                ID: <?= safe_output($row['id_artikel']) ?><br>
                Thumbnail: <?= safe_output($row['thumbnail']) ?><br>
                Thumbnail Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                PDF File: <?= safe_output($row['file_pdf']) ?><br>
                PDF Found: <?= $file_found ? 'Ya' : 'Tidak' ?><br>
                PDF Path: <?= safe_output($actual_file_path) ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-journal-text"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada artikel hukum yang tersedia.
    </p>
</div>
<?php endif; ?>
<?php endif; ?>
</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<?php
// Reset result pointers for sidebar
if ($year_stats_result) mysqli_data_seek($year_stats_result, 0);
if ($popular_result) mysqli_data_seek($popular_result, 0);
?>

<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik Artikel</h4>
<ul>
<li>Total: <strong><?= safe_output($total_dokumen) ?> artikel</strong></li>
<?php if ($year_stats_result): while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= safe_output($stat['tahun_terbit']) ?> : <strong><?= safe_output($stat['jumlah']) ?></strong> artikel</li>
<?php endwhile; endif; ?>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Artikel Terbaru</h4>
<ul>
<?php if ($popular_result): while ($pop = mysqli_fetch_assoc($popular_result)): 
    $pop_id = (int)$pop['id_artikel'];
    $pop_token = encode_id($pop_id);
    if (!$pop_token) {
        $pop_token = base64_encode('jdih_artikel_' . $pop_id . '_' . time());
    }
?>
<li>
<a href="index.php?page=detail-artikel&token=<?= urlencode($pop_token) ?>">
<i class="bi bi-file-text"></i> <?= safe_output(substr($pop['judul'], 0, 40)) ?>...
</a>
<small class="text-muted d-block"><?= safe_output($pop['penulis']) ?></small>
<small class="text-muted"><?= safe_output($pop['tahun_terbit']) ?></small>
</li>
<?php endwhile; endif; ?>
</ul>
</div>

<!-- DEBUG PANEL -->
<?php if ($debug_mode): ?>
<div class="sidebar-menu">
    <h4><i class="bi bi-bug"></i> Debug Info</h4>
    <div class="debug-info">
        <small>
            <strong>Server Info:</strong><br>
            Document Root: <?= $_SERVER['DOCUMENT_ROOT'] ?><br>
            Base Upload Path: <?= $base_upload_path ?><br>
            PDF Base Path: <?= $pdf_base_path ?><br>
            Total Results: <?= $search_results_count ?><br>
            Search Query: <?= safe_output($search_query) ?>
        </small>
    </div>
</div>
<?php endif; ?>

</div>
</div>
</div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// CLIENT-SIDE VALIDATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Artikel Hukum page loaded');
    
    // Image error handling function
    window.handleImageError = function(img) {
        console.error('Image failed to load:', img.src);
        img.style.display = 'none';
        const cardImage = img.parentElement;
        const placeholder = cardImage.querySelector('.placeholder');
        if (placeholder) {
            placeholder.style.display = 'flex';
        }
    };
    
    // Year filter
    document.querySelectorAll(".year-btn").forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedYear = btn.getAttribute('data-year');
            
            if (selectedYear !== 'semua') {
                const yearNum = parseInt(selectedYear);
                if (isNaN(yearNum) || yearNum < 1900 || yearNum > 2100) {
                    console.error('Invalid year:', selectedYear);
                    return;
                }
            }
            
            document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            
            updateFilterURL('year', selectedYear);
            filterArticles();
        });
    });
    
    // Type filter
    document.querySelectorAll(".type-btn").forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedType = btn.getAttribute('data-type');
            
            document.querySelectorAll(".type-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            
            updateFilterURL('type', selectedType);
            filterArticles();
        });
    });
    
    // Download button handling
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const filename = this.getAttribute('data-filename');
            console.log('Download started:', filename);
            showDownloadStarted(filename);
        });
    });
    
    // Disabled download button handling
    document.querySelectorAll('.btn-download.disabled').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const card = this.closest('.document-card');
            const title = card.querySelector('.card-title').textContent;
            showFileNotFoundAlert(title);
        });
    });
    
    // Pre-check images
    document.querySelectorAll('.card-image img').forEach(img => {
        if (img.complete && img.naturalHeight === 0) {
            handleImageError(img);
        }
    });
    
    // Search form validation
    const searchForm = document.querySelector('.search-box form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim().length < 2 && searchInput.value.trim() !== '') {
                alert('Masukkan minimal 2 karakter untuk pencarian');
                e.preventDefault();
                return false;
            }
        });
    }
    
    // Debug: Log semua gambar
    <?php if (isset($debug_mode) && $debug_mode): ?>
    console.log('=== IMAGE DEBUG INFO ===');
    document.querySelectorAll('.card-image img').forEach((img, index) => {
        console.log(`Image ${index + 1}: src="${img.src}", data-src="${img.getAttribute('data-src')}"`);
    });
    <?php endif; ?>
});

// Fungsi untuk filter artikel
function filterArticles() {
    const selectedYear = document.querySelector('.year-btn.active').getAttribute('data-year');
    const selectedType = document.querySelector('.type-btn.active').getAttribute('data-type');
    
    let visibleCount = 0;
    const totalCount = document.querySelectorAll('.document-card').length;
    
    document.querySelectorAll(".document-card").forEach(card => {
        const cardYear = card.getAttribute('data-tahun');
        const cardType = card.getAttribute('data-jenis');
        
        let showCard = true;
        
        if (selectedYear !== 'semua' && cardYear !== selectedYear) {
            showCard = false;
        }
        
        if (selectedType !== 'semua' && cardType !== selectedType) {
            showCard = false;
        }
        
        if (showCard) {
            card.style.display = "flex";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });
    
    // Update judul section
    const sectionTitle = document.querySelector('.section-title');
    if (sectionTitle) {
        if (selectedYear !== 'semua' || selectedType !== 'semua') {
            let filterText = '';
            if (selectedYear !== 'semua') filterText += ` Tahun ${selectedYear}`;
            if (selectedType !== 'semua') filterText += ` ${selectedType}`;
            sectionTitle.textContent = `Artikel Hukum${filterText} (${visibleCount} dari ${totalCount} artikel)`;
        } else {
            sectionTitle.textContent = `Daftar Artikel Hukum (${totalCount} artikel)`;
        }
    }
}

// Fungsi untuk update URL dengan filter
function updateFilterURL(param, value) {
    const url = new URL(window.location);
    
    if (value === 'semua') {
        url.searchParams.delete(param);
    } else {
        url.searchParams.set(param, value);
    }
    
    // Update URL tanpa reload page
    window.history.pushState({}, '', url);
}

// Fungsi untuk alert file tidak ditemukan
function showFileNotFoundAlert(title) {
    const safeTitle = title.substring(0, 100).replace(/[<>]/g, '');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk artikel <strong>${safeTitle}</strong> tidak ditemukan di server.`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk artikel "${safeTitle}" tidak ditemukan di server.`);
    }
}

// Fungsi untuk notifikasi download dimulai
function showDownloadStarted(filename) {
    const safeFilename = filename.substring(0, 50).replace(/[<>]/g, '');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Download Dimulai',
            html: `File <strong>${safeFilename}</strong> sedang didownload...`,
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// Fungsi untuk retry loading gambar
function retryImageLoad(imgElement) {
    const src = imgElement.getAttribute('data-src');
    if (src) {
        const newSrc = src + (src.includes('?') ? '&' : '?') + 'retry=' + Date.now();
        imgElement.src = newSrc;
        console.log('Retrying image load:', newSrc);
    }
}
</script>

<?php 
// ============================================
// CLEANUP & LOGGING
// ============================================

// Log page access
$access_log = sprintf(
    "[%s] IP: %s | Page: artikel-hukum.php | Search: %s | Results: %d\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    $search_query ?? 'none',
    $search_results_count ?? 0
);
error_log($access_log);

// Cleanup resources
if (isset($result)) mysqli_free_result($result);
if (isset($total_result)) mysqli_free_result($total_result);
if (isset($year_stats_result)) mysqli_free_result($year_stats_result);
if (isset($popular_result)) mysqli_free_result($popular_result);
if (isset($years_result)) mysqli_free_result($years_result);
if (isset($types_result)) mysqli_free_result($types_result);
if (isset($all_result)) mysqli_free_result($all_result);

// Close database connection
if (isset($koneksi)) mysqli_close($koneksi);

// Clear sensitive variables
unset(
    $query, $result, $row, 
    $encrypted_id, $actual_file_path, $koneksi,
    $search_results, $search_escaped
);?>