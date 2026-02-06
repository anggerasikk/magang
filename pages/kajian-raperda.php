<?php
// ============================================
// KEAMANAN LEVEL 1: ERROR HANDLING
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1); // Aktifkan untuk debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// ============================================
// KEAMANAN LEVEL 2: SESSION SECURITY
// ============================================
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' => 'Strict',
    'use_only_cookies' => 1,
    'cookie_lifetime' => 3600,
    'gc_maxlifetime' => 3600
]);

// ============================================
// KEAMANAN LEVEL 3: SESSION FIXATION PROTECTION
// ============================================
if (empty($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
}

function validate_session() {
    if (!isset($_SESSION['ip_address']) || $_SESSION['ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
        session_destroy();
        return false;
    }
    
    if (!isset($_SESSION['user_agent']) || $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown')) {
        session_destroy();
        return false;
    }
    
    return true;
}

if (!validate_session()) {
    header('HTTP/1.1 403 Forbidden');
    die('Invalid session detected');
}

// ============================================
// KEAMANAN LEVEL 4: CSRF PROTECTION
// ============================================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ============================================
// KEAMANAN LEVEL 5: SECURITY HEADERS
// ============================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// CSP Header
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
       "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
       "img-src 'self' data: https:; " .
       "font-src 'self' https://cdn.jsdelivr.net; " .
       "connect-src 'self';";
header("Content-Security-Policy: " . $csp);

// ============================================
// KEAMANAN LEVEL 6: RATE LIMITING
// ============================================
function check_rate_limit($key, $limit = 10, $time_window = 60) {
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    
    $now = time();
    $window_start = $now - $time_window;
    
    // Hapus request lama
    $_SESSION['rate_limit'][$key] = array_filter(
        $_SESSION['rate_limit'][$key] ?? [],
        function($time) use ($window_start) {
            return $time > $window_start;
        }
    );
    
    // Cek limit
    if (count($_SESSION['rate_limit'][$key]) >= $limit) {
        error_log("Rate limit exceeded for $key from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        return false;
    }
    
    $_SESSION['rate_limit'][$key][] = $now;
    return true;
}

// ============================================
// KEAMANAN LEVEL 7: INPUT VALIDATION
// ============================================
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    return $input;
}

function safe_output($data) {
    if (is_array($data)) {
        return array_map('safe_output', $data);
    }
    
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
}

// ============================================
// KEAMANAN LEVEL 8: SECURITY LOGGING
// ============================================
function security_log($message, $level = 'INFO') {
    $log_file = __DIR__ . '/logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $request_uri = $_SERVER['REQUEST_URI'] ?? 'unknown';
    
    $log_entry = sprintf(
        "[%s] %s - IP: %s - UA: %s - URI: %s - %s\n",
        $timestamp,
        $level,
        $ip,
        substr($user_agent, 0, 100),
        $request_uri,
        $message
    );
    
    error_log($log_entry, 3, $log_file);
}

// ============================================
// KEAMANAN LEVEL 9: DATABASE CONNECTION
// ============================================
include_once "a_db.php";

// ============================================
// KONFIGURASI PATH UNTUK MONOGRAFI - DIPERBAIKI
// ============================================
// Path utama (sesuai dengan detail-monografi.php)
$monografi_pdf_path = "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/";
$monografi_images_path = "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/images/";

// Fallback paths jika path utama tidak ada
if (!is_dir($monografi_pdf_path)) {
    $possible_paths = [
        "C:/xampp/htdocs/jdihcilacap_pasang/uploads/monografi/pdf/",
        "C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/monografi/pdf/",
        $_SERVER['DOCUMENT_ROOT'] . "/uploads/monografi/pdf/",
        $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/monografi/pdf/",
    ];
    
    foreach ($possible_paths as $path) {
        if (is_dir($path)) {
            $monografi_pdf_path = $path;
            break;
        }
    }
}

if (!is_dir($monografi_images_path)) {
    $possible_paths = [
        "C:/xampp/htdocs/jdihcilacap_pasang/uploads/monografi/images/",
        "C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/monografi/images/",
        $_SERVER['DOCUMENT_ROOT'] . "/uploads/monografi/images/",
        $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/monografi/images/",
    ];
    
    foreach ($possible_paths as $path) {
        if (is_dir($path)) {
            $monografi_images_path = $path;
            break;
        }
    }
}

// Gambar default
$default_image_url = "panel_control/assets/img/default-monografi.png";

// Log paths untuk debugging
error_log("PDF Path: " . $monografi_pdf_path);
error_log("Images Path: " . $monografi_images_path);
error_log("Default Image: " . $default_image_url);

// ============================================
// FUNGSI BANTU SEDERHANA - DIPERBAIKI
// ============================================

// Fungsi: Cek apakah ada file PDF di database - DIPERBAIKI
function isPdfAvailableInDB($file_pdf_field) {
    // Cek apakah ada file PDF di database
    // Perbaikan: handle null value sebelum trim()
    if ($file_pdf_field === null || $file_pdf_field === '') {
        return false;
    }
    
    // Sekarang bisa aman melakukan trim()
    $trimmed_value = trim($file_pdf_field);
    return !empty($trimmed_value);
}

// Fungsi: Cari file PDF di server (sama seperti di detail-monografi.php) - DIPERBAIKI
function findPDFFile($filename, $id = null) {
    global $monografi_pdf_path;
    
    // Perbaikan: cek null terlebih dahulu
    if ($filename === null || empty($filename)) {
        return null;
    }
    
    // Sanitize filename
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    // Lokasi yang mungkin - UTAMA untuk monografi
    $possible_locations = [
        // Lokasi utama monografi
        $monografi_pdf_path . $clean_filename,
        
        // Format Windows
        str_replace('/', '\\', $monografi_pdf_path) . $clean_filename,
        
        // Document root relatif
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/' . $clean_filename,
    ];
    
    // Jika ada ID, coba cari berdasarkan ID
    if ($id) {
        $possible_locations[] = $monografi_pdf_path . $id . '.pdf';
        $possible_locations[] = $monografi_pdf_path . $id . '.PDF';
        $possible_locations[] = $monografi_pdf_path . 'monografi_' . $id . '.pdf';
    }
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            // Verifikasi ini file PDF
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if (stripos($mime_type, 'pdf') !== false) {
                return [
                    'path' => $location,
                    'size' => filesize($location),
                    'time' => filemtime($location),
                    'mime' => $mime_type,
                    'filename' => basename($location)
                ];
            }
        }
    }
    
    return null;
}

// Fungsi: Cari file gambar - DIPERBAIKI
function findImageFile($filename, $id = null) {
    global $monografi_images_path, $default_image_url;
    
    // Perbaikan: cek null terlebih dahulu
    if ($filename === null || empty($filename)) {
        return [
            'url' => $default_image_url,
            'found' => false
        ];
    }
    
    // Sanitize filename
    $filename = basename($filename);
    $clean_filename = $filename;
    
    // Lokasi yang mungkin
    $possible_locations = [
        $monografi_images_path . $clean_filename,
        str_replace('/', '\\', $monografi_images_path) . $clean_filename,
    ];
    
    // Jika ada ID, coba cari berdasarkan ID
    if ($id) {
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($extensions as $ext) {
            $possible_locations[] = $monografi_images_path . $id . '.' . $ext;
            $possible_locations[] = $monografi_images_path . 'thumbnail_' . $id . '.' . $ext;
        }
    }
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            $web_url = str_replace(
                $_SERVER['DOCUMENT_ROOT'], 
                '', 
                str_replace('\\', '/', $location)
            );
            
            if (strpos($web_url, '/') !== 0) {
                $web_url = '/' . $web_url;
            }
            
            return [
                'url' => $web_url . '?t=' . time(),
                'found' => true,
                'path' => $location
            ];
        }
    }
    
    return [
        'url' => $default_image_url,
        'found' => false
    ];
}

// Fungsi: Generate URL untuk file PDF
function generatePdfUrl($file_path) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $root = str_replace('\\', '/', $root);
    $file_path = str_replace('\\', '/', $file_path);
    
    // Jika file ada dalam DOCUMENT_ROOT
    if (strpos($file_path, $root) === 0) {
        $url_path = substr($file_path, strlen($root));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path . '?t=' . time();
    }
    
    // Untuk Laragon
    if (strpos($file_path, 'C:/laragon/www') === 0) {
        $url_path = substr($file_path, strlen('C:/laragon/www'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path . '?t=' . time();
    }
    
    // Fallback: return path as is
    return $file_path . '?t=' . time();
}

// ============================================
// PAGINATION SETTINGS
// ============================================
$items_per_page = 6; // Tampil 6 card per halaman
$current_page = isset($_GET['page_num']) && is_numeric($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($current_page < 1) $current_page = 1;

$offset = ($current_page - 1) * $items_per_page;

// ============================================
// KEAMANAN LEVEL 12: ID ENCRYPTION
// ============================================
function encode_id($id) {
    if (!is_numeric($id) || $id <= 0) {
        return null;
    }
    
    $timestamp = time();
    $random = bin2hex(random_bytes(8));
    $token = base64_encode("mono_{$id}_{$timestamp}_{$random}");
    
    // URL safe encoding
    $token = str_replace(['+', '/', '='], ['-', '_', ''], $token);
    
    return $token;
}

// ============================================
// KEAMANAN LEVEL 13: INPUT PROCESSING
// ============================================
$clean_get = [];
foreach ($_GET as $key => $value) {
    $clean_get[$key] = sanitize_input($value);
}

$debug_mode = isset($clean_get['debug']) ? (int)$clean_get['debug'] : 0;
$search_query = isset($clean_get['search']) ? $clean_get['search'] : '';

// Apply rate limiting untuk search
if (!empty($search_query)) {
    $rate_limit_key = 'search_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (!check_rate_limit($rate_limit_key, 15, 60)) {
        header('HTTP/1.1 429 Too Many Requests');
        die('Too many search requests. Please try again later.');
    }
}

// Log akses halaman
security_log("Page accessed: kajian-raperda.php | Search: " . substr($search_query, 0, 100) . " | Page: $current_page", "INFO");

// ============================================
// KEAMANAN LEVEL 14: DATABASE QUERY WITH PREPARED STATEMENTS & PAGINATION
// ============================================
try {
    // Hitung total data untuk pagination
    if (!empty($search_query)) {
        $search_param = "%" . $search_query . "%";
        $count_query = "SELECT COUNT(*) as total 
                       FROM monografi 
                       WHERE jenis_monografi LIKE '%Kajian%Raperda%' 
                       AND (
                         judul LIKE ? 
                         OR penulis LIKE ?
                         OR bidang_hukum LIKE ?
                         OR deskripsi LIKE ?
                       )";
        
        $count_stmt = mysqli_prepare($koneksi, $count_query);
        mysqli_stmt_bind_param($count_stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
        mysqli_stmt_execute($count_stmt);
        $count_result = mysqli_stmt_get_result($count_stmt);
        $count_row = mysqli_fetch_assoc($count_result);
        $total_dokumen = $count_row['total'];
        
        // Query dengan pagination
        $query = "SELECT 
                    id_monografi,
                    judul,
                    jenis_monografi,
                    tanggal_pembuatan,
                    tahun_terbit,
                    penulis,
                    penerbit,
                    isbn,
                    bidang_hukum,
                    jumlah_halaman,
                    deskripsi,
                    status,
                    bahasa,
                    file_pdf,
                    thumbnail,
                    created_at
                  FROM monografi 
                  WHERE jenis_monografi LIKE '%Kajian%Raperda%' 
                  AND (
                    judul LIKE ? 
                    OR penulis LIKE ?
                    OR bidang_hukum LIKE ?
                    OR deskripsi LIKE ?
                  )
                  ORDER BY tahun_terbit DESC, id_monografi DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssssii", $search_param, $search_param, $search_param, $search_param, $items_per_page, $offset);
        
    } else {
        // Hitung total data tanpa search
        $count_query = "SELECT COUNT(*) as total 
                       FROM monografi 
                       WHERE jenis_monografi LIKE '%Kajian%Raperda%'";
        $count_result = mysqli_query($koneksi, $count_query);
        $count_row = mysqli_fetch_assoc($count_result);
        $total_dokumen = $count_row['total'];
        
        // Query dengan pagination tanpa search
        $query = "SELECT 
                    id_monografi,
                    judul,
                    jenis_monografi,
                    tanggal_pembuatan,
                    tahun_terbit,
                    penulis,
                    penerbit,
                    isbn,
                    bidang_hukum,
                    jumlah_halaman,
                    deskripsi,
                    status,
                    bahasa,
                    file_pdf,
                    thumbnail,
                    created_at
                  FROM monografi 
                  WHERE jenis_monografi LIKE '%Kajian%Raperda%' 
                  ORDER BY tahun_terbit DESC, id_monografi DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ii", $items_per_page, $offset);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($koneksi));
    }
    
    // Hitung total halaman
    $total_pages = ceil($total_dokumen / $items_per_page);
    if ($current_page > $total_pages && $total_pages > 0) {
        $current_page = $total_pages;
        // Redirect ke halaman terakhir jika page number melebihi total
        header("Location: ?page=kajian-raperda&page_num=$current_page" . ($search_query ? "&search=" . urlencode($search_query) : ""));
        exit;
    }
    
} catch (Exception $e) {
    security_log("Database error: " . $e->getMessage(), "ERROR");
    
    if ($debug_mode) {
        die("Database error: " . safe_output($e->getMessage()));
    } else {
        die("System temporarily unavailable. Please try again later.");
    }
}

// ============================================
// KEAMANAN LEVEL 15: GET ADDITIONAL DATA
// ============================================
try {
    // Tahun untuk filter
    $years_query = "SELECT DISTINCT tahun_terbit 
                    FROM monografi 
                    WHERE jenis_monografi LIKE '%Kajian%Raperda%'
                    ORDER BY tahun_terbit DESC";
    $years_result = mysqli_query($koneksi, $years_query);
    $years = [];
    while ($row = mysqli_fetch_assoc($years_result)) {
        $year = (int)$row['tahun_terbit'];
        // Validate year range
        if ($year >= 1900 && $year <= 2100) {
            $years[] = $year;
        }
    }
    
} catch (Exception $e) {
    security_log("Additional data query error: " . $e->getMessage(), "ERROR");
}

// Latest documents
$latest_query = "SELECT id_monografi, judul, tahun_terbit, created_at 
                 FROM monografi 
                 WHERE jenis_monografi LIKE '%Kajian%Raperda%'
                 ORDER BY created_at DESC 
                 LIMIT 5";
$latest_result = mysqli_query($koneksi, $latest_query);

// ============================================
// HEADER TEMPLATE
// ============================================
$page_title = safe_output("Kajian Raperda DPRD - JDIH DPRD Kota Surakarta");
include "header.php";
?>

<!-- ===============================
     STYLE - DIPERBAIKI UNTUK GAMBAR DAN PAGINATION
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK KAJIAN RAPERDA */

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
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
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

/* TOMBOL DOWNLOAD - DIPERBAIKI */
.download-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #4db8ab 0%, #5eb3a8 100%);
    color: white !important;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    font-weight: 500;
    text-decoration: none !important;
    display: inline-block;
    text-align: center;
}

.download-btn:hover {
    background: linear-gradient(135deg, #5eb3a8 0%, #5eb3a8 100%);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    text-decoration: none !important;
}

.download-btn.disabled {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
    color: #e0e0e0 !important;
    cursor: not-allowed !important;
    pointer-events: auto !important;
    opacity: 0.7 !important;
    border: none !important;
}

.download-btn.disabled:hover {
    transform: none !important;
    box-shadow: none !important;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
}


/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
    
    .card-actions {
        flex-direction: column;
    }
    
    .card-btn {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Debug info */
.debug-info {
    font-size: 11px;
    border-left: 3px solid #dc3545;
    background: #f8f9fa;
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
}

/* Download Status - DIPERBAIKI */
.download-status {
    font-size: 12px;
    margin: 10px 0;
    padding: 8px 12px;
    border-radius: 4px;
    display: inline-block;
    font-weight: 500;
}
.download-status.available {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.download-status.unavailable {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.download-status i {
    margin-right: 5px;
}

/* Custom untuk Kajian Raperda */
.kajian-raperda-badge {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

/* Styling untuk info tambahan */
.card-extra-info {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
    font-size: 12px;
    color: #666;
}

.card-extra-info span {
    margin-right: 15px;
    display: inline-block;
    margin-bottom: 5px;
}

.card-extra-info i {
    margin-right: 5px;
}

/* Tombol aksi */
.card-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.card-btn {
    flex: 1;
    min-width: 120px;
    text-align: center;
}

/* Styling khusus untuk monografi */
.monografi-title {
    font-size: 1.1rem;
    line-height: 1.4;
}

/* Search Box */
.search-box {
    margin-bottom: 20px;
}

/* Year Filter */
.year-filter {
    margin-bottom: 20px;
}

.year-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.year-btn {
    padding: 8px 15px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.year-btn:hover {
    background: #e9ecef;
}

.year-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* ========== PAGINATION STYLES ========== */
.pagination-container {
    margin-top: 30px;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
    gap: 5px;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 8px 15px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    color: #007bff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    min-width: 40px;
    text-align: center;
}

.page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0056b3;
    text-decoration: none;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    font-weight: 600;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.6;
}

.page-item:not(.disabled):not(.active) .page-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Page Info */
.page-info {
    text-align: center;
    margin-top: 10px;
    color: #6c757d;
    font-size: 14px;
}

/* Responsive Pagination */
@media (max-width: 576px) {
    .page-link {
        padding: 6px 10px;
        min-width: 35px;
        font-size: 14px;
    }
    
    .pagination {
        gap: 3px;
    }
}

/* Document Counter */
.document-counter {
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
    color: #6c757d;
    padding: 8px 15px;
    background: #f8f9fa;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.document-counter strong {
    color: #495057;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">KAJIAN RANCANGAN PERATURAN DAERAH DPRD</h1>
        <p class="subtitle">Kajian Rancangan Peraturan Daerah Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item">Monografi Hukum</li>
                <li class="breadcrumb-item active">Kajian Raperda DPRD</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT - DIPERBAIKI UNTUK TAMPIL GAMBAR & DOWNLOAD
================================ -->
<section class="main-content">
<div class="container">
<div class="row">

<!-- ===== KONTEN UTAMA ===== -->
<div class="col-lg-8">

<?php if (isset($_SESSION['security_message'])): ?>
<div class="security-warning">
    <i class="bi bi-shield-check"></i> <?= safe_output($_SESSION['security_message']) ?>
</div>
<?php unset($_SESSION['security_message']); ?>
<?php endif; ?>

<!-- SEARCH BAR DENGAN CSRF TOKEN -->
<div class="search-box">
    <form method="GET" action="" class="d-flex gap-2" id="searchForm">
        <input type="hidden" name="csrf_token" value="<?= safe_output($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="page" value="kajian-raperda">
        <input type="hidden" name="page_num" value="1">
        <div class="flex-grow-1">
            <input type="text" 
                   name="search" 
                   class="form-control form-control-lg" 
                   placeholder="Cari berdasarkan judul, penulis, atau bidang hukum..." 
                   value="<?= safe_output($search_query) ?>"
                   aria-label="Search documents"
                   minlength="2"
                   maxlength="100">
        </div>
        <button type="submit" class="btn btn-primary btn-lg" id="searchButton">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($search_query) || $current_page > 1): ?>
            <a href="?page=kajian-raperda" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- FILTER TAHUN -->
<div class="year-filter">
    <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h5>
    <div class="year-buttons">
        <button class="year-btn active" data-year="semua">Semua</button>
        <?php foreach ($years as $year): ?>
            <button class="year-btn" data-year="<?= safe_output($year) ?>"><?= safe_output($year) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<?php if (!empty($search_query)): ?>
    <h3 class="section-title">Hasil Pencarian: "<?= safe_output($search_query) ?>"</h3>
<?php else: ?>
    <h3 class="section-title">Daftar Kajian Raperda DPRD</h3>
<?php endif; ?>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php 
$counter = 0;
while ($row = mysqli_fetch_assoc($result)):
    $counter++;
    $tanggal = date('d F Y', strtotime($row['tanggal_pembuatan']));
    $tahun_terbit = $row['tahun_terbit'];
    $id = $row['id_monografi'];
    
    // ============================================
    // CARI GAMBAR - MENGGUNAKAN FUNGSI BARU
    // ============================================
    $image_info = findImageFile($row['thumbnail'], $id);
    $image_url = $image_info['url'];
    $image_found = $image_info['found'];
    
    // ============================================
    // CEK FILE PDF - SISTEM BARU SEDERHANA
    // ============================================
    // 1. Cek apakah ada di database (KOLOM file_pdf)
    $pdf_exists_in_db = isPdfAvailableInDB($row['file_pdf']);
    
    // 2. Jika ada di database, cari file fisik
    $pdf_info = null;
    $pdf_found = false;
    $pdf_url = '#';
    $pdf_filename = '';
    
    if ($pdf_exists_in_db) {
        $pdf_info = findPDFFile($row['file_pdf'], $id);
        
        if ($pdf_info) {
            $pdf_found = true;
            $pdf_url = generatePdfUrl($pdf_info['path']);
            $pdf_filename = $pdf_info['filename'] ?? basename($row['file_pdf']);
        }
    }
    
    // Enkripsi ID untuk detail
    $encrypted_id = encode_id($id);
    $detail_url = "index.php?page=detail-monografi&token=" . urlencode($encrypted_id);
    
    // Judul untuk download filename
    $safe_title = preg_replace('/[^a-zA-Z0-9]/', '_', substr($row['judul'], 0, 50));
    $download_filename = $pdf_found ? $pdf_filename : ($safe_title . '.pdf');
?>
<div class="document-card" data-tahun="<?= safe_output($tahun_terbit); ?>" data-id="<?= safe_output($id); ?>">
    <!-- CARD IMAGE - DIPERBAIKI -->
    <div class="card-image" id="card-image-<?= $id ?>">
        <img src="<?= safe_output($image_url) ?>" 
             alt="<?= safe_output($row['judul']) ?>"
             loading="lazy"
             onerror="this.src='<?= safe_output($default_image_url) ?>'">
    </div>

    <!-- CARD CONTENT -->
    <div class="card-content">
        <div class="card-number">Tahun <?= safe_output($tahun_terbit) ?></div>
        <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>

        <div class="card-meta">
            <div class="card-date">
                <i class="bi bi-calendar"></i> <?= safe_output($tanggal) ?>
            </div>
            <?php if (!empty($row['status'])): ?>
                <div class="card-status"><?= safe_output($row['status']) ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($row['penulis']) || !empty($row['bidang_hukum'])): ?>
        <div class="card-extra-info">
            <?php if (!empty($row['penulis'])): ?>
                <span><i class="bi bi-person"></i> <?= safe_output($row['penulis']) ?></span>
            <?php endif; ?>
            <?php if (!empty($row['bidang_hukum'])): ?>
                <span><i class="bi bi-journal"></i> <?= safe_output($row['bidang_hukum']) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- STATUS FILE - BERDASARKAN DATABASE -->
        <div class="download-status <?= $pdf_exists_in_db ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $pdf_exists_in_db ? 'check-circle' : 'x-circle' ?>"></i>
            <?php if ($pdf_exists_in_db): ?>
                File PDF tersedia
                <?php if (!$pdf_found): ?>
                    <br><small style="font-size: 9px; color: #dc3545;">(File fisik tidak ditemukan)</small>
                <?php endif; ?>
            <?php else: ?>
                File PDF tidak tersedia
            <?php endif; ?>
        </div>

        <!-- CARD ACTIONS - BUTTON DOWNLOAD YANG JELAS -->
        <div class="card-actions">
            <a href="<?= safe_output($detail_url) ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
            
            <?php if ($pdf_exists_in_db && $pdf_found): ?>
                <!-- Button aktif - file ada di DB dan ditemukan fisiknya -->
                <a href="<?= safe_output($pdf_url) ?>" 
                   class="card-btn download-btn"
                   download="<?= safe_output($download_filename) ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   data-id="<?= $id ?>"
                   data-title="<?= safe_output(addslashes($row['judul'])) ?>"
                   onclick="return confirmDownload(this)">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php elseif ($pdf_exists_in_db && !$pdf_found): ?>
                <!-- Button disabled - file ada di DB tapi tidak ditemukan fisiknya -->
                <a href="#" 
                   class="card-btn download-btn disabled"
                   onclick="event.preventDefault(); showFileNotAccessibleAlert('<?= safe_output(addslashes($row['judul'])) ?>');"
                   title="File terdaftar tetapi tidak dapat diakses">
                    <i class="bi bi-exclamation-triangle"></i> Tidak Dapat Diakses
                </a>
            <?php else: ?>
                <!-- Button disabled - tidak ada file di database -->
                <a href="#" 
                   class="card-btn download-btn disabled"
                   onclick="event.preventDefault(); showNoFileAlert('<?= safe_output(addslashes($row['judul'])) ?>');"
                   title="Tidak ada file PDF yang terdaftar">
                    <i class="bi bi-x-circle"></i> Tidak Tersedia
                </a>
            <?php endif; ?>
        </div>
        
        <?php if ($debug_mode): ?>
        <div class="debug-info">
            <small>
                <strong>Debug Info (ID: <?= $id ?>):</strong><br>
                Database PDF: "<?= safe_output($row['file_pdf']) ?>"<br>
                Ada di Database: <?= $pdf_exists_in_db ? 'YA' : 'TIDAK' ?><br>
                Ditemukan Secara Fisik: <?= $pdf_found ? 'YA' : 'TIDAK' ?><br>
                PDF URL: <?= safe_output($pdf_url) ?><br>
                Download Filename: <?= safe_output($download_filename) ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
</div>

<!-- ============================================
     PAGINATION SECTION
================================ -->
<?php if ($total_pages > 1): ?>
<div class="pagination-container">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Previous Page Link -->
            <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="?page=kajian-raperda<?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?>&page_num=<?= $current_page - 1 ?>"
                   aria-label="Previous">
                    <i class="bi bi-chevron-left"></i> Prev
                </a>
            </li>
            
            <!-- First Page -->
            <?php if ($current_page > 3): ?>
                <li class="page-item">
                    <a class="page-link" 
                       href="?page=kajian-raperda<?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?>&page_num=1">
                        1
                    </a>
                </li>
                <?php if ($current_page > 4): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Page Numbers -->
            <?php 
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++): 
            ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="?page=kajian-raperda<?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?>&page_num=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <!-- Last Page -->
            <?php if ($current_page < $total_pages - 2): ?>
                <?php if ($current_page < $total_pages - 3): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" 
                       href="?page=kajian-raperda<?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?>&page_num=<?= $total_pages ?>">
                        <?= $total_pages ?>
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Next Page Link -->
            <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="?page=kajian-raperda<?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?>&page_num=<?= $current_page + 1 ?>"
                   aria-label="Next">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Page Info -->
<div class="page-info">
    Menampilkan <?= ($offset + 1) ?> - <?= min($offset + $items_per_page, $total_dokumen) ?> dari <?= $total_dokumen ?> dokumen
</div>
<?php endif; ?>

<?php else: ?>
<div class="alert alert-info mt-4">
    <i class="bi bi-info-circle"></i> Tidak ada data Kajian Raperda yang ditemukan.
</div>
<?php endif; ?>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">
    <!-- Menu Monografi Statistik -->
    <div class="sidebar-menu">
        <h4><i class="bi bi-collection"></i> Menu Monografi Hukum</h4>
        <ul>
            <li>
                <a href="?page=pokok-pikiran"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a>
            </li>
            <li class="<?= (basename($_SERVER['PHP_SELF']) == 'kajian-raperda.php' || $_GET['page'] == 'kajian-raperda') ? 'active' : '' ?>">
                <a href="?page=kajian-raperda">
                    <i class="bi bi-search"></i> Kajian Raperda
                </a>
            </li>
            <li>
                <a href="?page=naskah-akademik">
                    <i class="bi bi-journal-text"></i> Naskah Akademik
                </a>
            </li>
            <li>
                <a href="?page=dokumen-langka">
                    <i class="bi bi-file-earmark-pdf"></i> Dokumen Langka
                </a>
            </li>
            <li>
                <a href="?page=risalah">
                    <i class="bi bi-file-text"></i> Risalah
                </a>
            </li>
            <li>
                <a href="?page=hasil-fasilitasi">
                    <i class="bi bi-gear"></i> Hasil Fasilitasi
                </a>
            </li>
            <li>
                <a href="?page=hasil-harmonisasi">
                    <i class="bi bi-shuffle"></i> Hasil Harmonisasi
                </a>
            </li>
            <li>
                <a href="?page=karya-ilmiah">
                    <i class="bi bi-pencil"></i> Penulisan Karya Ilmiah
                </a>
            </li>
            <li>
                <a href="?page=himpunan">
                    <i class="bi bi-folder"></i> Himpunan Peraturan
                </a>
            </li>
        </ul>
    </div>

    <!-- Statistik -->
    <div class="sidebar-menu">
        <h4><i class="bi bi-bar-chart"></i> Statistik Kajian Raperda</h4>
        <ul>
            <li class="stat-item-main">
                <span class="stat-label">Total Dokumen</span>
                <span class="stat-value"><?= $total_dokumen ?></span>
            </li>
            <?php if (!empty($years)): ?>
                <?php 
                // Ambil 5 tahun terakhir untuk statistik
                $recent_years = array_slice($years, 0, 5);
                foreach ($recent_years as $year): 
                ?>
                <li class="stat-item">
                    <span class="stat-year"><?= $year ?></span>
                    <span class="stat-count"><?= count(array_filter($years, function($y) use ($year) { return $y == $year; })) ?> dokumen</span>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Dokumen Terbaru -->
    <div class="sidebar-menu">
        <h4><i class="bi bi-fire"></i> Dokumen Terbaru</h4>
        <ul>
        <?php if ($latest_result): while ($doc = mysqli_fetch_assoc($latest_result)): ?>
        <li>
            <a href="?page=kajian-raperda&id=<?= urlencode(encode_id($doc['id_monografi'])) ?>">
                <i class="bi bi-file-text"></i> <?= safe_output(substr($doc['judul'], 0, 50)) ?>
            </a>
            <small class="text-muted d-block"><?= safe_output($doc['tahun_terbit']) ?></small>
            <small class="text-muted"><?= date('d M Y', strtotime($doc['created_at'])) ?></small>
        </li>
        <?php endwhile; endif; ?>
        </ul>
    </div>

   
</div>

    <?php if ($debug_mode): ?>
    <div class="sidebar-menu">
        <h4><i class="bi bi-bug"></i> Debug Paths</h4>
        <div class="debug-info">
            <small>
                <strong>File Paths:</strong><br>
                PDF Path: <?= $monografi_pdf_path ?><br>
                Images Path: <?= $monografi_images_path ?><br>
                Default Image: <?= $default_image_url ?>
            </small>
        </div>
    </div>
    <?php endif; ?>
</div>

</div>
</div>
</section>

<?php include "footer.php"; ?>

<!-- ===============================
     JAVASCRIPT - DIPERBAIKI DENGAN PAGINATION
================================ -->
<script>
// ============================================
// FUNGSI KONFIRMASI DOWNLOAD
// ============================================
function confirmDownload(linkElement) {
    const title = linkElement.getAttribute('data-title');
    const id = linkElement.getAttribute('data-id');
    
    // Tampilkan konfirmasi sederhana
    if (confirm(`Anda akan mendownload file:\n\n"${title}"\n\nKlik OK untuk melanjutkan download.`)) {
        console.log('Download confirmed for ID:', id, 'Title:', title);
        
        // Track download (opsional)
        trackDownload(id, title);
        
        // Tambahkan timestamp untuk menghindari cache
        const currentHref = linkElement.getAttribute('href');
        if (currentHref && !currentHref.includes('?t=')) {
            const newHref = currentHref + (currentHref.includes('?') ? '&' : '?') + 't=' + Date.now();
            linkElement.setAttribute('href', newHref);
        }
        
        // Biarkan browser melanjutkan download
        return true;
    }
    
    // Batal download
    return false;
}

// ============================================
// FUNGSI ALERT UNTUK FILE TIDAK TERSEDIA
// ============================================
function showNoFileAlert(title) {
    alert(`File PDF untuk "${title}" tidak tersedia di database.`);
}

function showFileNotAccessibleAlert(title) {
    alert(`File PDF untuk "${title}" terdaftar di database tetapi tidak dapat diakses.\n\nSilakan hubungi administrator.`);
}

// ============================================
// FUNGSI UNTUK TRACKING DOWNLOAD (OPTIONAL)
// ============================================
function trackDownload(id, title) {
    // Kirim data ke server untuk tracking jika diperlukan
    const data = {
        action: 'download',
        document_id: id,
        document_title: title,
        timestamp: new Date().toISOString(),
        user_agent: navigator.userAgent,
        url: window.location.href
    };
    
    // Menggunakan Beacon API jika tersedia
    if (navigator.sendBeacon) {
        navigator.sendBeacon('/api/track-download.php', JSON.stringify(data));
    } else {
        // Fallback menggunakan fetch
        fetch('/api/track-download.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
            keepalive: true
        }).catch(err => {
            console.log('Download tracking failed:', err);
        });
    }
    
    console.log('Download tracked:', data);
}

// ============================================
// FILTER BERDASARKAN TAHUN
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Year filter
    const yearButtons = document.querySelectorAll('.year-btn');
    const documentCards = document.querySelectorAll('.document-card');
    
    if (yearButtons.length > 0 && documentCards.length > 0) {
        yearButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                yearButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const selectedYear = this.getAttribute('data-year');
                
                // Filter cards
                documentCards.forEach(card => {
                    const cardYear = card.getAttribute('data-tahun');
                    
                    if (selectedYear === 'semua' || cardYear === selectedYear) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // ============================================
    // VALIDASI FORM PENCARIAN
    // ============================================
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            const searchButton = document.getElementById('searchButton');
            
            if (searchInput && searchInput.value.trim().length < 2) {
                e.preventDefault();
                alert('Masukkan minimal 2 karakter untuk pencarian.');
                searchInput.focus();
                return false;
            }
            
            // Reset ke halaman 1 saat search
            const pageNumInput = this.querySelector('input[name="page_num"]');
            if (pageNumInput) {
                pageNumInput.value = '1';
            }
            
            // Disable button untuk prevent double submit
            if (searchButton) {
                searchButton.disabled = true;
                searchButton.innerHTML = '<i class="bi bi-hourglass"></i> Searching...';
                
                // Re-enable setelah 3 detik (fallback)
                setTimeout(() => {
                    searchButton.disabled = false;
                    searchButton.innerHTML = '<i class="bi bi-search"></i> Cari';
                }, 3000);
            }
        });
    }
    
    // ============================================
    // AUTO-HIDE ALERTS SETELAH 5 DETIK
    // ============================================
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        });
    }, 5000);
    
    // ============================================
    // HANDLE IMAGE ERRORS
    // ============================================
    document.querySelectorAll('.card-image img').forEach(img => {
        img.addEventListener('error', function() {
            // Ganti dengan gambar default jika gagal load
            const defaultImage = '<?= safe_output($default_image_url) ?>';
            if (this.src !== defaultImage) {
                this.src = defaultImage;
            }
        });
    });
});

// ============================================
// MONITOR DOWNLOAD SUCCESS (OPTIONAL)
// ============================================
let downloadStartTime = null;

window.addEventListener('blur', function() {
    // Jika window kehilangan fokus setelah klik download, anggap download dimulai
    if (document.activeElement && document.activeElement.classList.contains('download-btn') && 
        !document.activeElement.classList.contains('disabled')) {
        downloadStartTime = Date.now();
        console.log('Download window opened');
    }
});

window.addEventListener('focus', function() {
    // Jika window kembali fokus setelah beberapa detik, anggap download selesai
    if (downloadStartTime && (Date.now() - downloadStartTime > 2000)) {
        console.log('Download likely completed');
        downloadStartTime = null;
    }
});
</script>

<?php 
// ============================================
// KEAMANAN LEVEL 16: CLEANUP & FINAL SECURITY
// ============================================

// Log successful page access
security_log("Page loaded successfully | Total documents: $total_dokumen | Current page: $current_page | Search: " . ($search_query ?: 'none'), "INFO");

// Cleanup sensitive data
if (isset($result)) {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_free_result($result);
}

if (isset($count_stmt)) {
    mysqli_stmt_close($count_stmt);
}

if (isset($count_result)) {
    mysqli_free_result($count_result);
}

if (isset($years_result)) mysqli_free_result($years_result);

// Clear sensitive variables
unset(
    $query, $result, $stmt, $row, 
    $encrypted_id, $image_info, $pdf_info,
    $search_query, $clean_get, $search_param,
    $count_query, $count_result, $count_row,
    $count_stmt
);

// Close database connection dengan error handling
if (isset($koneksi)) {
    try {
        mysqli_close($koneksi);
    } catch (Exception $e) {
        security_log("Database close error: " . $e->getMessage(), "ERROR");
    }
}

// Clear session data jika tidak diperlukan
if (!isset($_SESSION['user_id'])) {
    unset($_SESSION['rate_limit']);
}

// Add security footer
echo "<!-- Security System: Active -->\n";
echo "<!-- PDF Path: " . htmlspecialchars($monografi_pdf_path) . " -->\n";
echo "<!-- Images Path: " . htmlspecialchars($monografi_images_path) . " -->\n";
echo "<!-- Default Image: " . htmlspecialchars($default_image_url) . " -->\n";
echo "<!-- Pagination: Page $current_page of $total_pages ($items_per_page per page) -->\n";
echo "<!-- Generated: " . date('Y-m-d H:i:s') . " -->\n";
?>