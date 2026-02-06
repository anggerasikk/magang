<?php
// yurisprudensi.php - VERSION SECURITY FIXED

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
        return base64_encode('jdih_yuri_' . $id . '_' . time());
    }
    function decode_id($token) {
        $decoded = base64_decode($token);
        if (!$decoded) return 0;
        $parts = explode('_', $decoded);
        return isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : 0;
    }
    function encode_yurisprudensi_id($id) {
        return base64_encode('jdih_yuri_' . $id . '_' . time());
    }
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
} else {
    include_once "a_functions.php";

    // Add fallback for yurisprudensi encoding if not exists
    if (!function_exists('encode_yurisprudensi_id')) {
        function encode_yurisprudensi_id($id) {
            return base64_encode('jdih_yuri_' . $id . '_' . time());
        }
    }
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
$year_filter = isset($clean_get['tahun']) ? $clean_get['tahun'] : '';
$search_query = isset($clean_get['search']) ? $clean_get['search'] : '';
$jenis_peradilan = isset($clean_get['jenis_peradilan']) ? $clean_get['jenis_peradilan'] : '';
$bidang_hukum = isset($clean_get['bidang_hukum']) ? $clean_get['bidang_hukum'] : '';

// ============================================
// KEAMANAN LEVEL 5: PATH CONFIGURATION & VALIDATION
// ============================================

// DEBUG: Tampilkan informasi path untuk troubleshooting
if ($debug_mode) {
    error_log("DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT']);
    error_log("SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME']);
    error_log("__DIR__: " . __DIR__);
}

// PATH KONFIGURASI - MENGGUNAKAN $_SERVER['DOCUMENT_ROOT']
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// Base paths - SESUAI PERMINTAAN
$base_upload_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/";
$pdf_base_path = $base_upload_path; // PDF di folder yang sama
$web_yurisprudensi_path = "/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/";

// Fallback jika path tidak ditemukan
if (!is_dir($base_upload_path)) {
    // Coba alternatif paths
    $possible_paths = [
        $document_root . "/panel_control/uploads/yurisprudensi/",
        $document_root . "/uploads/yurisprudensi/",
        dirname(__DIR__) . "/panel_control/uploads/yurisprudensi/",
        "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/",
        "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/",
        "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/" // Original path
    ];
    
    foreach ($possible_paths as $possible_path) {
        if (is_dir($possible_path)) {
            $base_upload_path = $possible_path;
            $pdf_base_path = $possible_path;
            break;
        }
    }
}

// Default image path sesuai permintaan
$default_image_path = "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/images/YURISPRUDENSI.png";
$web_default_image_path = "/jdihcilacap_pasang_2026/panel_control/uploads/images/YURISPRUDENSI.png";

// Pastikan folder ada
if (!is_dir($pdf_base_path)) {
    @mkdir($pdf_base_path, 0755, true);
}

// Log path untuk debugging
if ($debug_mode) {
    error_log("Document Root: " . $document_root);
    error_log("Base Upload Path: " . $base_upload_path);
    error_log("PDF Base Path: " . $pdf_base_path);
    error_log("Web Path: " . $web_yurisprudensi_path);
    error_log("Default Image Path: " . $default_image_path);
    
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
// KEAMANAN LEVEL 6: SECURE FILE FUNCTIONS
// ============================================

// Fungsi untuk menemukan file PDF yurisprudensi
function findYurisprudensiPDF($filename, $id = null) {
    global $pdf_base_path, $debug_mode;
    
    if ($debug_mode) {
        error_log("=== findYurisprudensiPDF CALLED ===");
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
            $pdf_base_path . 'yurisprudensi_' . $id . '.pdf',
            $pdf_base_path . 'putusan_' . $id . '.pdf',
            $pdf_base_path . 'YURISPRUDENSI_' . $id . '.pdf',
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

// Fungsi untuk menemukan gambar cover yurisprudensi
function findYurisprudensiImage($image_name, $id = null) {
    global $base_upload_path, $web_yurisprudensi_path, $default_image_path, $web_default_image_path;
    
    // Declare debug_mode as global
    global $debug_mode;
    
    $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    
    if ($debug_mode) {
        error_log("=== findYurisprudensiImage CALLED ===");
        error_log("Image name: {$image_name}");
        error_log("ID: {$id}");
    }
    
    // 1. Coba path yang paling mungkin
    $possible_paths = [];
    
    // Jika ada nama file dari database
    if (!empty($image_name)) {
        $clean_name = basename($image_name);
        
        // Lokasi utama
        $main_path = $base_upload_path . $clean_name;
        if (validate_file_path($main_path, $base_upload_path)) {
            $possible_paths[] = $main_path;
            if ($debug_mode) error_log("Added main path: {$main_path}");
        }
        
        // Alternatif lokasi
        $alt_paths = [
            $document_root . $web_yurisprudensi_path . $clean_name,
            $document_root . '/uploads/yurisprudensi/' . $clean_name,
            $document_root . '/panel_control/uploads/yurisprudensi/' . $clean_name,
        ];
        
        foreach ($alt_paths as $path) {
            if (validate_file_path($path, $document_root)) {
                $possible_paths[] = $path;
                if ($debug_mode) error_log("Added alt path: {$path}");
            }
        }
    }
    
    // 2. Coba berdasarkan ID
    if ($id) {
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($extensions as $ext) {
            $id_paths = [
                $base_upload_path . $id . '.' . $ext,
                $base_upload_path . 'cover_' . $id . '.' . $ext,
                $base_upload_path . 'yurisprudensi_' . $id . '.' . $ext,
                $document_root . $web_yurisprudensi_path . $id . '.' . $ext,
            ];
            
            foreach ($id_paths as $path) {
                if (validate_file_path($path, $base_upload_path) || validate_file_path($path, $document_root)) {
                    $possible_paths[] = $path;
                    if ($debug_mode) error_log("Added ID path: {$path}");
                }
            }
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
    
    // 4. FALLBACK: Gambar default sesuai permintaan
    if (file_exists($default_image_path)) {
        if ($debug_mode) error_log("Using default image");
        return [
            'path' => $default_image_path,
            'url' => $web_default_image_path,
            'source' => 'fallback'
        ];
    }
    
    // 5. Ultimate fallback
    if ($debug_mode) error_log("No image found");
    return [
        'path' => '',
        'url' => '/panel_control/uploads/images/YURISPRUDENSI.png',
        'source' => 'ultimate_fallback'
    ];
}

// Fungsi konversi path fisik ke URL
function convert_path_to_url($physical_path) {
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
    $where_conditions = ["1=1"]; // Default condition
    
    // Add search condition
    if (!empty($search_query)) {
        $search_escaped = mysqli_real_escape_string($koneksi, $search_query);
        $search_conditions = [
            "judul LIKE '%$search_escaped%'",
            "nomor_putusan LIKE '%$search_escaped%'",
            "kata_kunci LIKE '%$search_escaped%'",
            "deskripsi LIKE '%$search_escaped%'",
            "subjek LIKE '%$search_escaped%'",
            "bidang_hukum_jenis_perkara LIKE '%$search_escaped%'"
        ];
        $where_conditions[] = "(" . implode(" OR ", $search_conditions) . ")";
    }
    
    // Add year filter
    if (!empty($year_filter) && $year_filter !== 'semua' && is_numeric($year_filter)) {
        $year_filter = (int)$year_filter;
        if ($year_filter >= 1900 && $year_filter <= 2100) {
            $where_conditions[] = "tahun_putusan = " . $year_filter;
        }
    }
    
    // Add jenis peradilan filter
    if (!empty($jenis_peradilan) && $jenis_peradilan !== 'semua') {
        $jenis_escaped = mysqli_real_escape_string($koneksi, $jenis_peradilan);
        $where_conditions[] = "jenis_peradilan = '$jenis_escaped'";
    }
    
    // Add bidang hukum filter
    if (!empty($bidang_hukum) && $bidang_hukum !== 'semua') {
        $bidang_escaped = mysqli_real_escape_string($koneksi, $bidang_hukum);
        $where_conditions[] = "bidang_hukum_jenis_perkara = '$bidang_escaped'";
    }
    
    // Build final query
    $where_clause = implode(" AND ", $where_conditions);
    $query = "SELECT * FROM yurisprudensi WHERE $where_clause ORDER BY tanggal_dibacakan DESC, tahun_putusan DESC, created_at DESC LIMIT 12";
    
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
    error_log("Database error in yurisprudensi.php: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}

// ============================================
// KEAMANAN LEVEL 8: GET ADDITIONAL DATA
// ============================================

// Total documents
$total_query = "SELECT COUNT(*) as total FROM yurisprudensi";
$total_result = mysqli_query($koneksi, $total_query);
$total_putusan = $total_result ? (int)mysqli_fetch_assoc($total_result)['total'] : 0;

// Year statistics
$year_stats_query = "SELECT tahun_putusan, COUNT(*) as jumlah 
                     FROM yurisprudensi 
                     GROUP BY tahun_putusan 
                     ORDER BY tahun_putusan DESC 
                     LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// Popular articles (putusan terbaru)
$terbaru_query = "SELECT id_yurisprudensi, judul, nomor_putusan, tanggal_dibacakan, jenis_peradilan 
                  FROM yurisprudensi 
                  ORDER BY tanggal_dibacakan DESC 
                  LIMIT 5";
$terbaru_result = mysqli_query($koneksi, $terbaru_query);

// Year filter
$years_query = "SELECT DISTINCT tahun_putusan 
                FROM yurisprudensi 
                WHERE tahun_putusan IS NOT NULL
                ORDER BY tahun_putusan DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $year = (int)$row['tahun_putusan'];
    if ($year >= 1900 && $year <= 2100) {
        $years[] = $year;
    }
}

// Get all jenis peradilan for filter
$jenis_peradilan_query = "SELECT DISTINCT jenis_peradilan 
                          FROM yurisprudensi 
                          WHERE jenis_peradilan IS NOT NULL 
                          AND jenis_peradilan != '' 
                          ORDER BY jenis_peradilan";
$jenis_peradilan_result = mysqli_query($koneksi, $jenis_peradilan_query);
$jenis_peradilan_list = [];
while ($row = mysqli_fetch_assoc($jenis_peradilan_result)) {
    $jenis_peradilan_list[] = $row['jenis_peradilan'];
}

// Get bidang hukum for filter
$bidang_hukum_query = "SELECT DISTINCT bidang_hukum_jenis_perkara 
                       FROM yurisprudensi 
                       WHERE bidang_hukum_jenis_perkara IS NOT NULL 
                       AND bidang_hukum_jenis_perkara != '' 
                       ORDER BY bidang_hukum_jenis_perkara";
$bidang_hukum_result = mysqli_query($koneksi, $bidang_hukum_query);
$bidang_hukum_list = [];
while ($row = mysqli_fetch_assoc($bidang_hukum_result)) {
    $bidang_hukum_list[] = $row['bidang_hukum_jenis_perkara'];
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
$page_title = safe_output("Yurisprudensi - JDIH DPRD Kota Surakarta");
$page_description = safe_output("Kumpulan putusan pengadilan yang menjadi acuan dalam penegakan hukum di Kota Surakarta");
include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK YURISPRUDENSI */
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

/* Badge warna berdasarkan jenis peradilan */
.badge-perdata {
    background: rgba(52, 152, 219, 0.9) !important;
}
.badge-pidana {
    background: rgba(231, 76, 60, 0.9) !important;
}
.badge-tun {
    background: rgba(46, 204, 113, 0.9) !important;
}
.badge-mk {
    background: rgba(155, 89, 182, 0.9) !important;
}
.badge-agama {
    background: rgba(241, 196, 15, 0.9) !important;
}

/* Status putusan */
.status-putusan {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 10px;
}
.status-tetap {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.status-kasasi {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}
.status-peninjauan {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Putusan info */
.putusan-info {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #6c757d;
}

.info-item i {
    font-size: 14px;
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

/* Deskripsi preview */
.deskripsi-preview {
    font-size: 14px;
    line-height: 1.5;
    color: #555;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Keywords */
.kata-kunci {
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

/* Filter section */
.adv-search-link {
    color: #3498db;
    font-size: 14px;
}
.adv-search-link:hover {
    color: #2980b9;
    text-decoration: underline !important;
}

/* Stats card */
.stats-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}
.stats-card:hover {
    transform: translateY(-5px);
}
.stats-number {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}
.stats-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Manfaat item */
.manfaat-item {
    text-align: center;
    padding: 20px;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}
.manfaat-item:hover {
    background: #e9ecef;
    transform: translateY(-5px);
}
.manfaat-item i {
    font-size: 36px;
    color: #3498db;
    margin-bottom: 15px;
}
.manfaat-item h5 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #2c3e50;
}
.manfaat-item p {
    font-size: 13px;
    color: #7f8c8d;
}

/* Header yurisprudensi */
.header-yurisprudensi {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}
.monografi-icon {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}
.monografi-icon img {
    width: 40px;
    height: 40px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
    
    .putusan-info {
        flex-direction: column;
        gap: 5px;
    }
    
    .header-yurisprudensi {
        padding: 20px;
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

/* Filter buttons */
.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.filter-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: #e9ecef;
}

.filter-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">YURISPRUDENSI</h1>
        <p class="subtitle">Kumpulan Putusan Pengadilan yang Menjadi Acuan Hukum</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                <li class="breadcrumb-item active">Yurisprudensi</li>
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
<div class="search-box mb-4">
    <form method="GET" action="?" class="d-flex gap-2">
        <input type="hidden" name="page" value="yurisprudensi">
        <div class="flex-grow-1">
            <input type="text" 
                   name="search" 
                   class="form-control form-control-lg" 
                   placeholder="Cari putusan berdasarkan nomor perkara, pokok perkara, atau kata kunci..." 
                   value="<?= safe_output($search_query) ?>"
                   aria-label="Search putusan">
        </div>
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($search_query) || !empty($year_filter) || !empty($jenis_peradilan) || !empty($bidang_hukum)): ?>
            <a href="?page=yurisprudensi" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- FILTER SECTION -->
<div class="year-filter mb-4">
    <h5><i class="bi bi-filter"></i> Filter Berdasarkan</h5>
    
    <!-- Tahun Filter -->
    <div class="filter-section mb-3">
        <h6 class="mb-2">Tahun Putusan:</h6>
        <div class="filter-buttons">
            <button class="filter-btn <?= empty($year_filter) || $year_filter === 'semua' ? 'active' : '' ?>" 
                    data-filter="tahun" data-value="semua">Semua Tahun</button>
            <?php foreach ($years as $year): ?>
                <button class="filter-btn <?= $year_filter == $year ? 'active' : '' ?>" 
                        data-filter="tahun" data-value="<?= safe_output($year) ?>"><?= safe_output($year) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Jenis Peradilan Filter -->
    <?php if (!empty($jenis_peradilan_list)): ?>
    <div class="filter-section mb-3">
        <h6 class="mb-2">Jenis Peradilan:</h6>
        <div class="filter-buttons">
            <button class="filter-btn <?= empty($jenis_peradilan) || $jenis_peradilan === 'semua' ? 'active' : '' ?>" 
                    data-filter="jenis_peradilan" data-value="semua">Semua Jenis</button>
            <?php foreach ($jenis_peradilan_list as $jenis): ?>
                <button class="filter-btn <?= $jenis_peradilan == $jenis ? 'active' : '' ?>" 
                        data-filter="jenis_peradilan" data-value="<?= safe_output($jenis) ?>"><?= safe_output($jenis) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Bidang Hukum Filter -->
    <?php if (!empty($bidang_hukum_list)): ?>
    <div class="filter-section mb-3">
        <h6 class="mb-2">Bidang Hukum:</h6>
        <div class="filter-buttons">
            <button class="filter-btn <?= empty($bidang_hukum) || $bidang_hukum === 'semua' ? 'active' : '' ?>" 
                    data-filter="bidang_hukum" data-value="semua">Semua Bidang</button>
            <?php foreach ($bidang_hukum_list as $bidang): ?>
                <button class="filter-btn <?= $bidang_hukum == $bidang ? 'active' : '' ?>" 
                        data-filter="bidang_hukum" data-value="<?= safe_output($bidang) ?>"><?= safe_output($bidang) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- HASIL SEARCH / LIST YURISPRUDENSI -->
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
        <h3 class="section-title">Hasil Pencarian: "<?= safe_output($search_query) ?>" (<?= $search_results_count ?> putusan)</h3>
        <div class="card-container">
            <?php foreach ($search_results as $row): 
                $tanggal = date('d F Y', strtotime($row['tanggal_dibacakan']));
                
                // GUNAKAN ID LANGSUNG - tidak perlu enkripsi
                $id = (int)$row['id_yurisprudensi'];
                
                // CARI FILE PDF
                $file_found = false;
                $actual_file_path = null;
                $download_url = '#';
                
                if (!empty($row['lampiran_path'])) {
                    $actual_file_path = findYurisprudensiPDF($row['lampiran_path'], $row['id_yurisprudensi']);
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
                            error_log("PDF Found for ID {$row['id_yurisprudensi']}: {$actual_file_path}");
                            error_log("Download URL: {$download_url}");
                        }
                    } else {
                        if ($debug_mode) {
                            error_log("PDF NOT Found for ID {$row['id_yurisprudensi']}: {$row['lampiran_path']}");
                        }
                    }
                }
                
                // CARI GAMBAR COVER
                $image_info = findYurisprudensiImage($row['cover_path'], $row['id_yurisprudensi']);
                $image_url = $image_info['url'] ?? '';
                $image_found = !empty($image_url);
                
                // DETAIL URL - GUNAKAN TOKEN TERENKRIPSI
                $encrypted_id = encode_yurisprudensi_id($id);
                $detail = "index.php?page=detail-yurisprudensi&token=" . urlencode($encrypted_id);
                
                // Parse keywords
                $keywords = !empty($row['kata_kunci']) ? explode(',', $row['kata_kunci']) : [];
                
                // Tentukan badge berdasarkan jenis peradilan
                $badge_class = 'badge-tun';
                if (stripos($row['jenis_peradilan'], 'Perdata') !== false) {
                    $badge_class = 'badge-perdata';
                } elseif (stripos($row['jenis_peradilan'], 'Pidana') !== false) {
                    $badge_class = 'badge-pidana';
                } elseif (stripos($row['jenis_peradilan'], 'Konstitusi') !== false) {
                    $badge_class = 'badge-mk';
                } elseif (stripos($row['jenis_peradilan'], 'Agama') !== false) {
                    $badge_class = 'badge-agama';
                }
                
                // Tentukan status putusan
                $status_class = 'status-tetap';
                if (stripos($row['status_putusan'], 'Kasasi') !== false) {
                    $status_class = 'status-kasasi';
                } elseif (stripos($row['status_putusan'], 'Peninjauan') !== false) {
                    $status_class = 'status-peninjauan';
                }
            ?>
            <div class="document-card" 
                 data-tahun="<?= safe_output($row['tahun_putusan']); ?>" 
                 data-jenis="<?= safe_output($row['jenis_peradilan']); ?>"
                 data-bidang="<?= safe_output($row['bidang_hukum_jenis_perkara']); ?>">
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
                            <i class="bi bi-gavel"></i>
                            <p><?= safe_output($row['singkatan_jenis_peradilan'] ?? $row['jenis_peradilan']) ?><br>
                            <small><?= safe_output($row['tahun_putusan']) ?></small></p>
                        </div>
                    <?php endif; ?>
                    <div class="image-overlay">
                        <span class="image-badge <?= $badge_class ?>">
                            <?= safe_output($row['singkatan_jenis_peradilan'] ?? substr($row['jenis_peradilan'], 0, 3)) ?>
                        </span>
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['nomor_putusan']) ?></div>
                    <h3 class="card-title">
                        <a href="<?= safe_output($detail) ?>" class="text-decoration-none text-dark">
                            <?= safe_output($row['judul']) ?>
                        </a>
                    </h3>
                    
                    <div class="putusan-info">
                        <div class="info-item">
                            <i class="bi bi-calendar"></i>
                            <span>Dibacakan: <?= safe_output($tanggal) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-building"></i>
                            <span><?= safe_output($row['tempat_peradilan']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-tags"></i>
                            <span><?= safe_output($row['bidang_hukum_jenis_perkara']) ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($row['status_putusan'])): ?>
                    <div class="status-putusan <?= $status_class ?>">
                        <i class="bi bi-check-circle"></i>
                        <?= safe_output($row['status_putusan']) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($row['deskripsi'])): ?>
                    <div class="deskripsi-preview">
                        <?= safe_output(substr($row['deskripsi'], 0, 200)) ?>...
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($keywords)): ?>
                    <div class="kata-kunci">
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
                        <!-- PERBAIKAN DI SINI: Link ke detail-yurisprudensi.php dengan token terenkripsi -->
                        <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Baca Putusan
                        </a>
                        
                        <?php if ($file_found): ?>
                            <a href="<?= safe_output($download_url) ?>" 
                               class="card-btn btn-download download-btn" 
                               data-id="<?= safe_output($row['id_yurisprudensi']); ?>"
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
                            ID: <?= safe_output($row['id_yurisprudensi']) ?><br>
                            Detail URL: <?= safe_output($detail) ?><br>
                            Cover: <?= safe_output($row['cover_path']) ?><br>
                            Cover Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                            PDF File: <?= safe_output($row['lampiran_path']) ?><br>
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
            <i class="bi bi-info-circle"></i> Tidak ada putusan yang sesuai dengan pencarian "<strong><?= safe_output($search_query) ?></strong>".
        </div>
        <!-- Tampilkan semua putusan jika tidak ada hasil pencarian -->
        <?php 
        $all_query = "SELECT * FROM yurisprudensi ORDER BY tanggal_dibacakan DESC, tahun_putusan DESC LIMIT 12";
        $all_result = mysqli_query($koneksi, $all_query);
        ?>
        <h3 class="section-title mt-4">Semua Putusan Yurisprudensi</h3>
        <div class="card-container">
            <?php while ($row = mysqli_fetch_assoc($all_result)):
                $id = (int)$row['id_yurisprudensi'];
                $image_info = findYurisprudensiImage($row['cover_path'], $row['id_yurisprudensi']);
                $image_url = $image_info['url'] ?? '';
                $image_found = !empty($image_url);
            ?>
            <div class="document-card">
                <div class="card-image">
                    <?php if ($image_found): ?>
                        <img src="<?= safe_output($image_url) ?>" alt="<?= safe_output($row['judul']) ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="bi bi-gavel"></i>
                            <p><?= safe_output($row['singkatan_jenis_peradilan'] ?? $row['jenis_peradilan']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['nomor_putusan']) ?></div>
                    <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>
                    <div class="card-actions">
                        <!-- PERBAIKAN DI SINI: Link ke detail-yurisprudensi.php dengan token terenkripsi -->
                        <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Baca Putusan
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
<?php else: // Tampilkan filter dan daftar normal jika tidak ada search ?>

<h3 class="section-title">Daftar Putusan Yurisprudensi (<?= $search_results_count ?> putusan)</h3>

<?php if (mysqli_num_rows($result) > 0): 
    mysqli_data_seek($result, 0);
?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):
    $tanggal = date('d F Y', strtotime($row['tanggal_dibacakan']));
    
    // GUNAKAN ID LANGSUNG - tidak perlu enkripsi
    $id = (int)$row['id_yurisprudensi'];
    
    // CARI FILE PDF
    $file_found = false;
    $actual_file_path = null;
    $download_url = '#';
    
    if (!empty($row['lampiran_path'])) {
        $actual_file_path = findYurisprudensiPDF($row['lampiran_path'], $row['id_yurisprudensi']);
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
    
    // CARI GAMBAR COVER
    $image_info = findYurisprudensiImage($row['cover_path'], $row['id_yurisprudensi']);
    $image_url = $image_info['url'] ?? '';
    $image_found = !empty($image_url);
    
                // DETAIL URL - GUNAKAN TOKEN TERENKRIPSI
                $encrypted_id = encode_yurisprudensi_id($id);
                $detail = "detail-yurisprudensi.php?token=" . urlencode($encrypted_id);
    
    // Parse keywords
    $keywords = !empty($row['kata_kunci']) ? explode(',', $row['kata_kunci']) : [];
    
    // Tentukan badge berdasarkan jenis peradilan
    $badge_class = 'badge-tun';
    if (stripos($row['jenis_peradilan'], 'Perdata') !== false) {
        $badge_class = 'badge-perdata';
    } elseif (stripos($row['jenis_peradilan'], 'Pidana') !== false) {
        $badge_class = 'badge-pidana';
    } elseif (stripos($row['jenis_peradilan'], 'Konstitusi') !== false) {
        $badge_class = 'badge-mk';
    } elseif (stripos($row['jenis_peradilan'], 'Agama') !== false) {
        $badge_class = 'badge-agama';
    }
    
    // Tentukan status putusan
    $status_class = 'status-tetap';
    if (stripos($row['status_putusan'], 'Kasasi') !== false) {
        $status_class = 'status-kasasi';
    } elseif (stripos($row['status_putusan'], 'Peninjauan') !== false) {
        $status_class = 'status-peninjauan';
    }
?>

<div class="document-card" 
     data-tahun="<?= safe_output($row['tahun_putusan']); ?>" 
     data-jenis="<?= safe_output($row['jenis_peradilan']); ?>"
     data-bidang="<?= safe_output($row['bidang_hukum_jenis_perkara']); ?>">
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
                <i class="bi bi-gavel"></i>
                <p><?= safe_output($row['singkatan_jenis_peradilan'] ?? $row['jenis_peradilan']) ?><br>
                <small><?= safe_output($row['tahun_putusan']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge <?= $badge_class ?>">
                <?= safe_output($row['singkatan_jenis_peradilan'] ?? substr($row['jenis_peradilan'], 0, 3)) ?>
            </span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number"><?= safe_output($row['nomor_putusan']) ?></div>
        <h3 class="card-title">
            <!-- PERBAIKAN DI SINI: Link ke detail-yurisprudensi.php dengan token terenkripsi -->
            <a href="index.php?page=detail-yurisprudensi&token=<?= urlencode($encrypted_id) ?>" class="text-decoration-none text-dark">
                <?= safe_output($row['judul']) ?>
            </a>
        </h3>
        
        <div class="putusan-info">
            <div class="info-item">
                <i class="bi bi-calendar"></i>
                <span>Dibacakan: <?= safe_output($tanggal) ?></span>
            </div>
            <div class="info-item">
                <i class="bi bi-building"></i>
                <span><?= safe_output($row['tempat_peradilan']) ?></span>
            </div>
            <div class="info-item">
                <i class="bi bi-tags"></i>
                <span><?= safe_output($row['bidang_hukum_jenis_perkara']) ?></span>
            </div>
        </div>
        
        <?php if (!empty($row['status_putusan'])): ?>
        <div class="status-putusan <?= $status_class ?>">
            <i class="bi bi-check-circle"></i>
            <?= safe_output($row['status_putusan']) ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($row['deskripsi'])): ?>
        <div class="deskripsi-preview">
            <?= safe_output(substr($row['deskripsi'], 0, 200)) ?>...
        </div>
        <?php endif; ?>
        
        <?php if (!empty($keywords)): ?>
        <div class="kata-kunci">
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
                        <!-- PERBAIKAN DI SINI: Link ke detail-yurisprudensi.php dengan token terenkripsi -->
                        <a href="index.php?page=detail-yurisprudensi&token=<?= urlencode($encrypted_id) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Baca Putusan
                        </a>
            
            <?php if ($file_found): ?>
                <a href="<?= safe_output($download_url) ?>" 
                   class="card-btn btn-download download-btn" 
                   data-id="<?= safe_output($row['id_yurisprudensi']); ?>"
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
                ID: <?= safe_output($row['id_yurisprudensi']) ?><br>
                Detail URL: <?= safe_output($detail) ?><br>
                Cover: <?= safe_output($row['cover_path']) ?><br>
                Cover Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                PDF File: <?= safe_output($row['lampiran_path']) ?><br>
                PDF Found: <?= $file_found ? 'Ya' : 'Tidak' ?><br>
                PDF Path: <?= safe_output($actual_file_path) ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>
</div>

<!-- PAGINATION -->
<div class="pagination-container mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<?php else: ?>
<div class="no-data">
    <i class="bi bi-gavel"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada putusan yurisprudensi yang tersedia.
    </p>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- MANFAAT YURISPRUDENSI -->
<div class="year-filter mt-5">
    <h4 class="section-title"><i class="bi bi-award text-primary me-2"></i>Manfaat Yurisprudensi</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="manfaat-item">
                <div class="mb-3">
                    <i class="bi bi-lightbulb"></i>
                </div>
                <h5>Kepastian Hukum</h5>
                <p class="small">Memberikan pedoman dalam penegakan hukum</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="manfaat-item">
                <div class="mb-3">
                    <i class="bi bi-book"></i>
                </div>
                <h5>Pembelajaran</h5>
                <p class="small">Referensi bagi praktisi hukum dan akademisi</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="manfaat-item">
                <div class="mb-3">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5>Perlindungan</h5>
                <p class="small">Melindungi hak-hak masyarakat melalui putusan</p>
            </div>
        </div>
    </div>
</div>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<?php
// Reset result pointers for sidebar
if ($year_stats_result) mysqli_data_seek($year_stats_result, 0);
if ($terbaru_result) mysqli_data_seek($terbaru_result, 0);
?>
<!-- STATISTIK TAHUN -->
<div class="sidebar-menu">
    <h4><i class="bi bi-bar-chart"></i> Statistik Putusan</h4>
    <ul>
        <li>Total: <strong><?= safe_output($total_putusan) ?> putusan</strong></li>
        <?php if ($year_stats_result): 
            $counter = 0;
            while ($stat = mysqli_fetch_assoc($year_stats_result)): 
                $counter++;
                $colors = ['text-primary', 'text-success', 'text-warning', 'text-info', 'text-danger'];
                $color = $colors[$counter % count($colors)] ?? 'text-primary';
        ?>
        <li><?= safe_output($stat['tahun_putusan']) ?> : <strong class="<?= $color ?>"><?= safe_output($stat['jumlah']) ?></strong> putusan</li>
        <?php endwhile; endif; ?>
    </ul>
</div>

<!-- PUTUSAN TERBARU -->
<div class="sidebar-menu">
    <h4><i class="bi bi-clock-history"></i> Putusan Terbaru</h4>
    <ul>
    <?php if ($terbaru_result): while ($terbaru = mysqli_fetch_assoc($terbaru_result)): 
        $terbaru_id = (int)$terbaru['id_yurisprudensi'];
    ?>
    <li>
        <!-- PERBAIKAN DI SINI: Link ke detail-yurisprudensi.php dengan token terenkripsi -->
        <a href="detail-yurisprudensi.php?token=<?= urlencode(encode_yurisprudensi_id($terbaru_id)) ?>">
            <i class="bi bi-file-text"></i> <?= safe_output(substr($terbaru['nomor_putusan'], 0, 20)) ?>...
        </a>
        <small class="text-muted d-block"><?= safe_output(substr($terbaru['judul'], 0, 30)) ?>...</small>
        <small class="text-muted"><?= date('d M Y', strtotime($terbaru['tanggal_dibacakan'])) ?></small>
    </li>
    <?php endwhile; endif; ?>
    </ul>
</div>


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
    console.log('Yurisprudensi page loaded');
    
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
    
    // Filter buttons
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter');
            const filterValue = this.getAttribute('data-value');
            
            // Validate input
            if (filterValue === 'semua') {
                // Remove filter
                updateFilterURL(filterType, '');
            } else {
                // Validate year
                if (filterType === 'tahun' && filterValue !== 'semua') {
                    const yearNum = parseInt(filterValue);
                    if (isNaN(yearNum) || yearNum < 1900 || yearNum > 2100) {
                        console.error('Invalid year:', filterValue);
                        return;
                    }
                }
                
                updateFilterURL(filterType, filterValue);
            }
            
            // Update active state
            document.querySelectorAll(`.filter-btn[data-filter="${filterType}"]`).forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // Submit form or reload page
            window.location.href = getFilteredURL();
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
    
    // Initialize active filter buttons based on URL
    initializeActiveFilters();
    
    // Debug: Log semua gambar
    <?php if (isset($debug_mode) && $debug_mode): ?>
    console.log('=== IMAGE DEBUG INFO ===');
    document.querySelectorAll('.card-image img').forEach((img, index) => {
        console.log(`Image ${index + 1}: src="${img.src}", data-src="${img.getAttribute('data-src')}"`);
    });
    <?php endif; ?>
});

// Fungsi untuk update URL dengan filter
function updateFilterURL(param, value) {
    const url = new URL(window.location);
    
    if (value === '' || value === 'semua') {
        url.searchParams.delete(param);
    } else {
        url.searchParams.set(param, value);
    }
    
    return url.toString();
}

// Fungsi untuk mendapatkan URL dengan semua filter
function getFilteredURL() {
    let url = new URL(window.location);
    
    // Keep page parameter
    if (!url.searchParams.has('page')) {
        url.searchParams.set('page', 'yurisprudensi');
    }
    
    // Keep search parameter
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value.trim()) {
        url.searchParams.set('search', searchInput.value.trim());
    }
    
    // Get active filters
    document.querySelectorAll('.filter-btn.active').forEach(btn => {
        const filterType = btn.getAttribute('data-filter');
        const filterValue = btn.getAttribute('data-value');
        
        if (filterValue !== 'semua') {
            url.searchParams.set(filterType, filterValue);
        } else {
            url.searchParams.delete(filterType);
        }
    });
    
    return url.toString();
}

// Fungsi untuk menginisialisasi filter aktif berdasarkan URL
function initializeActiveFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Set tahun filter
    const tahun = urlParams.get('tahun');
    document.querySelectorAll('.filter-btn[data-filter="tahun"]').forEach(btn => {
        if ((!tahun && btn.getAttribute('data-value') === 'semua') || 
            btn.getAttribute('data-value') === tahun) {
            btn.classList.add('active');
        }
    });
    
    // Set jenis peradilan filter
    const jenis = urlParams.get('jenis_peradilan');
    document.querySelectorAll('.filter-btn[data-filter="jenis_peradilan"]').forEach(btn => {
        if ((!jenis && btn.getAttribute('data-value') === 'semua') || 
            btn.getAttribute('data-value') === jenis) {
            btn.classList.add('active');
        }
    });
    
    // Set bidang hukum filter
    const bidang = urlParams.get('bidang_hukum');
    document.querySelectorAll('.filter-btn[data-filter="bidang_hukum"]').forEach(btn => {
        if ((!bidang && btn.getAttribute('data-value') === 'semua') || 
            btn.getAttribute('data-value') === bidang) {
            btn.classList.add('active');
        }
    });
}

// Fungsi untuk alert file tidak ditemukan
function showFileNotFoundAlert(title) {
    const safeTitle = title.substring(0, 100).replace(/[<>]/g, '');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk putusan <strong>${safeTitle}</strong> tidak ditemukan di server.`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk putusan "${safeTitle}" tidak ditemukan di server.`);
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

// Fungsi untuk toggle pencarian lanjutan
function toggleAdvancedSearch() {
    const advancedSearch = document.getElementById('advancedSearch');
    if (advancedSearch.style.display === 'none') {
        advancedSearch.style.display = 'block';
    } else {
        advancedSearch.style.display = 'none';
    }
    return false;
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
    "[%s] IP: %s | Page: yurisprudensi.php | Search: %s | Results: %d\n",
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
if (isset($terbaru_result)) mysqli_free_result($terbaru_result);
if (isset($years_result)) mysqli_free_result($years_result);
if (isset($jenis_peradilan_result)) mysqli_free_result($jenis_peradilan_result);
if (isset($bidang_hukum_result)) mysqli_free_result($bidang_hukum_result);
if (isset($all_result)) mysqli_free_result($all_result);
if (isset($result_tahun)) mysqli_free_result($result_tahun);
if (isset($result_perdata)) mysqli_free_result($result_perdata);

// Close database connection
if (isset($koneksi)) mysqli_close($koneksi);

// Clear sensitive variables
unset(
    $query, $result, $row, 
    $actual_file_path, $koneksi,
    $search_results, $search_escaped
);
?>