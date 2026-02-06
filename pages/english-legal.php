<?php
// peraturan-daerah.php - DENGAN INTEGRASI a_functions.php LENGKAP + SEARCH BAR


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


// ============================================
// KEAMANAN LEVEL 5: PATH CONFIGURATION & VALIDATION - DIPERBAIKI
// ============================================


// DEBUG: Tampilkan informasi path untuk troubleshooting
if ($debug_mode) {
    error_log("DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT']);
    error_log("SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME']);
    error_log("__DIR__: " . __DIR__);
}


// PATH KONFIGURASI - SESUAIKAN DENGAN STRUKTUR ANDA
$base_dir = dirname(__DIR__); // Root project


// Tentukan path berdasarkan environment
if (strpos($_SERVER['DOCUMENT_ROOT'], 'laragon') !== false) {
    // Laragon environment
    $base_upload_path = $base_dir . "/panel_control/uploads/";
    $web_image_path = "/jdihcilacap_pasang_2026/panel_control/uploads/images/";
    $pdf_base_path = $base_dir . "/panel_control/uploads/pdf/";
} else {
    // XAMPP environment
    $base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/";
    $web_image_path = "/jdihcilacap_pasang/panel_control/uploads/images/";
    $pdf_base_path = "C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/pdf/";
}


// Pastikan folder ada
if (!is_dir($base_upload_path)) {
    error_log("WARNING: Base upload path tidak ditemukan: " . $base_upload_path);
    // Coba alternatif
    $base_upload_path = $base_dir . "/uploads/";
    $web_image_path = "/jdihcilacap_pasang_2026/uploads/images/";
    $pdf_base_path = $base_dir . "/uploads/pdf/";
}


// Log path untuk debugging
if ($debug_mode) {
    error_log("Base Upload Path: " . $base_upload_path);
    error_log("Web Image Path: " . $web_image_path);
    error_log("PDF Base Path: " . $pdf_base_path);
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


// Fungsi untuk menemukan file PDF di server dengan validasi keamanan
function findPDFFile($filename, $id = null) {
    // Deklarasi variabel global yang diperlukan
    global $pdf_base_path, $web_image_path;
   
    if (empty($filename) || !is_string($filename)) {
        return null;
    }
   
    // Sanitize filename
    $filename = basename($filename);
    $clean_filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $clean_filename);
   
    // Validate filename length
    if (strlen($clean_filename) > 255) {
        return null;
    }
   
    // Dapatkan document root dan path setup
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    $project_root = dirname(dirname(__DIR__));
   
    // List semua kemungkinan lokasi
    $possible_locations = [];
   
    // 1. Cek dari nama file asli
    if (!empty($clean_filename)) {
        $possible_locations = array_merge($possible_locations, [
            // Laragon structure
            'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $clean_filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/' . $clean_filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/uploads/pdf/' . $clean_filename,
            'C:/laragon/www/jdihcilacap_pasang_2026/uploads/' . $clean_filename,
           
            // XAMPP structure
            'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $clean_filename,
            'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/pdf/' . $clean_filename,
            'C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/pdf/' . $clean_filename,
           
            // XAMPP 8 structure
            'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $clean_filename,
            'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/pdf/' . $clean_filename,
           
            // Document root based
            $doc_root . '/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $clean_filename,
            $doc_root . '/jdihcilacap_pasang_2026/uploads/pdf/' . $clean_filename,
            $doc_root . '/panel_control/uploads/pdf/' . $clean_filename,
            $doc_root . '/uploads/pdf/' . $clean_filename,
            $doc_root . '/uploads/' . $clean_filename,
           
            // Project root based
            $project_root . '/panel_control/uploads/pdf/' . $clean_filename,
            $project_root . '/uploads/pdf/' . $clean_filename,
           
            // Base path
            $pdf_base_path . $clean_filename,
            dirname($web_image_path) . '/pdf/' . $clean_filename,
        ]);
    }
   
    // 2. Cari berdasarkan ID artikel (jika filename tidak ditemukan atau tidak valid)
    if ($id && is_numeric($id)) {
        $id_patterns = [
            'pdf_' . $id,
            'keputusan_' . $id,
            'document_' . $id,
            (string)$id,
        ];
       
        $extensions = ['pdf', 'PDF'];
       
        foreach ($id_patterns as $pattern) {
            foreach ($extensions as $ext) {
                $test_filename = $pattern . '.' . $ext;
               
                $possible_locations = array_merge($possible_locations, [
                    // Laragon
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $test_filename,
                    'C:/laragon/www/jdihcilacap_pasang_2026/uploads/pdf/' . $test_filename,
                   
                    // XAMPP
                    'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $test_filename,
                    'C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/pdf/' . $test_filename,
                   
                    // XAMPP 8
                    'C:/xampp8/htdocs/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $test_filename,
                   
                    // Document root
                    $doc_root . '/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $test_filename,
                    $doc_root . '/panel_control/uploads/pdf/' . $test_filename,
                   
                    // Project root
                    $project_root . '/panel_control/uploads/pdf/' . $test_filename,
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
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            // Verify it's actually a PDF file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
           
            // Strict MIME type validation
            $allowed_mimes = ['application/pdf', 'application/x-pdf'];
            if (in_array($mime_type, $allowed_mimes, true)) {
                return $location;
            }
        }
    }
   
    return null;
}


// FUNGSI BARU UNTUK MENCARI GAMBAR
function findImageFile($image_name, $image_path = '', $id = null) {
    // Deklarasi variabel global yang diperlukan
    global $base_upload_path, $web_image_path;
   
    $possible_urls = [];
    $possible_paths = [];
   
    // 1. Cek dari image_path di database
    if (!empty($image_path) && file_exists($image_path)) {
        $real_path = realpath($image_path);
        if ($real_path) {
            // Konversi ke URL
            $url = convert_path_to_url($real_path);
            if ($url) {
                return [
                    'path' => $real_path,
                    'url' => $url,
                    'source' => 'database_path'
                ];
            }
        }
    }
   
    // 2. Coba dari image_name + base paths
    if (!empty($image_name)) {
        // Bersihkan nama file
        $clean_name = basename($image_name);
       
        // Lokasi yang mungkin di server
        $server_paths = [
            $base_upload_path . 'images/' . $clean_name,
            $base_upload_path . $clean_name,
            dirname($base_upload_path) . '/images/' . $clean_name,
            $_SERVER['DOCUMENT_ROOT'] . $web_image_path . $clean_name,
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/' . $clean_name,
            $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/images/' . $clean_name,
            $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $clean_name,
        ];
       
        // Tambahkan lokasi berdasarkan ID
        if ($id) {
            $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            foreach ($extensions as $ext) {
                $server_paths[] = $base_upload_path . 'images/' . $id . '.' . $ext;
                $server_paths[] = $base_upload_path . $id . '.' . $ext;
                $server_paths[] = $_SERVER['DOCUMENT_ROOT'] . $web_image_path . $id . '.' . $ext;
            }
        }
       
        // Cek setiap lokasi
        foreach ($server_paths as $server_path) {
            if (file_exists($server_path) && is_file($server_path) && is_readable($server_path)) {
                // Validasi bahwa ini file gambar
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $server_path);
                finfo_close($finfo);
               
                if (strpos($mime_type, 'image/') === 0) {
                    $url = convert_path_to_url($server_path);
                    if ($url) {
                        return [
                            'path' => $server_path,
                            'url' => $url,
                            'source' => 'server_search'
                        ];
                    }
                }
            }
        }
    }
   
    // 3. Coba URL langsung
    if (!empty($image_name)) {
        $web_urls = [
            $web_image_path . $image_name,
            '/uploads/images/' . $image_name,
            '/panel_control/uploads/images/' . $image_name,
            '/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $image_name,
        ];
       
        foreach ($web_urls as $web_url) {
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $web_url;
            if (file_exists($full_path)) {
                return [
                    'path' => $full_path,
                    'url' => $web_url,
                    'source' => 'web_url'
                ];
            }
        }
    }
   
    return null;
}


// Fungsi konversi path fisik ke URL - DIPERBAIKI
function convert_path_to_url($physical_path) {
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    $doc_root = str_replace('\\', '/', $doc_root);
    $physical_path = str_replace('\\', '/', $physical_path);
   
    // Jika path ada dalam document root
    if (strpos($physical_path, $doc_root) === 0) {
        $url_path = substr($physical_path, strlen($doc_root));
        // Pastikan diawali dengan slash
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
   
    // Untuk path Laragon - case insensitive
    if (stripos($physical_path, 'C:/laragon/www') === 0) {
        $url_path = substr($physical_path, strlen('C:/laragon/www'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
   
    // Untuk path XAMPP
    if (stripos($physical_path, 'C:/xampp/htdocs') === 0) {
        $url_path = substr($physical_path, strlen('C:/xampp/htdocs'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
   
    // Untuk path XAMPP 8
    if (stripos($physical_path, 'C:/xampp8/htdocs') === 0) {
        $url_path = substr($physical_path, strlen('C:/xampp8/htdocs'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
   
    return null;
}


// ============================================
// KEAMANAN LEVEL 7: DATABASE QUERY WITH ERROR HANDLING
// ============================================
try {
    // Build query based on search
    if (!empty($search_query)) {
        // Secure search with prepared statement pattern
        $search_escaped = mysqli_real_escape_string($koneksi, $search_query);
        $query = "SELECT
                    idData,
                    tahun_pengundangan,
                    tanggal_pengundangan,
                    tanggal_penetapan,
                    jenis,
                    noPeraturan,
                    judul,
                    singkatanJenis,
                    status,
                    fileDownload,
                    pdf_path,
                    image_name,
                    image_path,
                    uploaded_at,
                    subjek
                  FROM uploads
                  WHERE jenis = 'English_Legal_Product'
                  AND (
                    judul LIKE '%$search_escaped%'
                    OR noPeraturan LIKE '%$search_escaped%'
                    OR subjek LIKE '%$search_escaped%'
                  )
                  ORDER BY idData DESC";
    } else {
        $query = "SELECT
                    idData,
                    tahun_pengundangan,
                    tanggal_pengundangan,
                    tanggal_penetapan,
                    jenis,
                    noPeraturan,
                    judul,
                    singkatanJenis,
                    status,
                    fileDownload,
                    pdf_path,
                    image_name,
                    image_path,
                    uploaded_at
                  FROM uploads
                  WHERE jenis = 'English_Legal_Product'
                  ORDER BY idData DESC";
    }
   
    $result = mysqli_query($koneksi, $query);
   
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($koneksi));
    }
   
    // Get total search results
    $search_results_count = mysqli_num_rows($result);
   
} catch (Exception $e) {
    error_log("Database error in: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}


// ============================================
// KEAMANAN LEVEL 8: GET ADDITIONAL DATA
// ============================================


// Total documents
$total_query = "SELECT COUNT(*) as total FROM uploads WHERE jenis = 'English_Legal_Product'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = $total_result ? (int)mysqli_fetch_assoc($total_result)['total'] : 0;


// Year statistics
$year_stats_query = "SELECT tahun_pengundangan, COUNT(*) as jumlah
                     FROM uploads
                     WHERE jenis = 'English_Legal_Product'
                     GROUP BY tahun_pengundangan
                     ORDER BY tahun_pengundangan DESC
                     LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);


// Popular documents
$popular_query = "SELECT idData, noPeraturan, judul, tanggal_penetapan
                  FROM uploads
                  WHERE jenis = 'English_Legal_Product'
                  ORDER BY tanggal_pengundangan DESC
                  LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);


// Year filter
$years_query = "SELECT DISTINCT tahun_pengundangan
                FROM uploads
                WHERE jenis = 'English_Legal_Product'
                ORDER BY tahun_pengundangan DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $year = (int)$row['tahun_pengundangan'];
    // Validate year range
    if ($year >= 1900 && $year <= 2100) {
        $years[] = $year;
    }
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
$page_title = safe_output("English Legal Product - JDIH DPRD Kota Surakarta");
include "header.php";
?>


<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>


/* STYLE KHUSUS UNTUK GAMBAR */
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


/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
   
    .document-card {
        flex-direction: column;
    }
}


/* Debug info */
.debug-info {
    font-size: 11px;
    border-left: 3px solid #dc3545;
}


/* Download Status */
.download-status {
    font-size: 11px;
    margin-top: 5px;
}
.download-status.available {
    color: #28a745;
}
.download-status.unavailable {
    color: #dc3545;
}
</style>




<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">ENGLISH LEGAL PRODUCT </h1>
        <p class="subtitle">English Legal Product Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">English Legal Product</li>
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
        <input type="hidden" name="page" value="english-legal">
        <div class="flex-grow-1">
            <input type="text"
                   name="search"
                   class="form-control form-control-lg"
                   placeholder="Cari berdasarkan nomor, judul, atau deskripsi..."
                   value="<?= safe_output($search_query) ?>"
                   aria-label="Search documents">
        </div>
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($search_query)): ?>
            <a href="?page=english-legal" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>


<!-- HASIL SEARCH -->
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
        <h3 class="section-title">Hasil Pencarian: "<?= safe_output($search_query) ?>" (<?= $search_results_count ?> dokumen)</h3>
        <div class="card-container">
            <?php foreach ($search_results as $row):
                $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
               
                // ============================================
                // KEAMANAN: ENKRIPSI ID UNTUK URL
                // ============================================
                $id = (int)$row['idData'];
                $encrypted_id = encode_id($id);
               
                // Fallback jika encode gagal
                if (!$encrypted_id) {
                    $encrypted_id = base64_encode('jdih_' . $id . '_' . time());
                }
               
                // ============================================
                // CARI FILE PDF DENGAN VALIDASI
                // ============================================
                $file_from_db = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
                $file_found = false;
                $actual_file_path = null;
                $download_url = '#';
               
                if (!empty($file_from_db)) {
                    $actual_file_path = findPDFFile($file_from_db, $row['idData']);
                    if ($actual_file_path) {
                        $file_found = true;
                        // Konversi path fisik ke URL dengan lebih fleksibel
                        $relative_path = '';
                        $doc_root = $_SERVER['DOCUMENT_ROOT'];
                        $actual_file_path_normalized = str_replace('\\', '/', $actual_file_path);
                       
                        if (strpos($actual_file_path_normalized, $doc_root) === 0) {
                            $relative_path = str_replace($doc_root, '', $actual_file_path_normalized);
                        }
                        elseif (stripos($actual_file_path_normalized, 'C:/laragon/www') === 0) {
                            $relative_path = str_replace('C:/laragon/www', '', $actual_file_path_normalized);
                        }
                        elseif (stripos($actual_file_path_normalized, 'C:/xampp/htdocs') === 0) {
                            $relative_path = str_replace('C:/xampp/htdocs', '', $actual_file_path_normalized);
                        }
                        elseif (stripos($actual_file_path_normalized, 'C:/xampp8/htdocs') === 0) {
                            $relative_path = str_replace('C:/xampp8/htdocs', '', $actual_file_path_normalized);
                        }
                       
                        if (strpos($relative_path, '/') !== 0) {
                            $relative_path = '/' . $relative_path;
                        }
                        $download_url = $relative_path . '?t=' . time();
                    }
                }
               
                // ============================================
                // CARI GAMBAR DENGAN FUNGSI BARU
                // ============================================
                $image_info = findImageFile($row['image_name'], $row['image_path'], $row['idData']);
                $image_url = '';
                $image_found = false;
               
                if ($image_info) {
                    $image_url = $image_info['url'];
                    $image_found = true;
                   
                    if ($debug_mode) {
                        error_log("Image found for ID {$row['idData']}: " . $image_url . " (Source: " . $image_info['source'] . ")");
                    }
                } else {
                    if ($debug_mode) {
                        error_log("Image NOT found for ID {$row['idData']}: name={$row['image_name']}, path={$row['image_path']}");
                    }
                }
               
                // ============================================
                // GENERATE DETAIL URL DENGAN TOKEN
                // ============================================
                $detail = "index.php?page=detail-keputusan&token=" . urlencode($encrypted_id);
            ?>
            <div class="document-card" data-tahun="<?= safe_output($row['tahun_pengundangan']); ?>" data-id="<?= safe_output($row['idData']); ?>">
                <div class="card-image">
                    <?php if ($image_found && !empty($image_url)): ?>
                        <?php if ($debug_mode): ?>
                            <div class="img-debug">Found</div>
                        <?php endif; ?>
                        <img src="<?= safe_output($image_url) ?>"
                             alt="<?= safe_output($row['judul']) ?>"
                             loading="lazy"
                             onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';"
                             data-src="<?= safe_output($image_url) ?>">
                        <div class="placeholder" style="display: none;">
                            <i class="bi bi-file-earmark-medical"></i>
                            <p><?= safe_output($row['singkatanJenis']) ?><br>
                            <small><?= safe_output($row['noPeraturan']) ?></small></p>
                        </div>
                    <?php else: ?>
                        <?php if ($debug_mode): ?>
                            <div class="img-debug">Not Found</div>
                        <?php endif; ?>
                        <div class="placeholder">
                            <i class="bi bi-file-earmark-medical"></i>
                            <p><?= safe_output($row['singkatanJenis']) ?><br>
                            <small><?= safe_output($row['noPeraturan']) ?></small></p>
                        </div>
                    <?php endif; ?>
                    <div class="image-overlay">
                        <span class="image-badge"><?= safe_output($row['singkatanJenis']) ?></span>
                    </div>
                </div>


                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['noPeraturan']) ?></div>
                    <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>


                    <div class="card-meta">
                        <div class="card-date">
                            <i class="bi bi-calendar"></i> <?= safe_output($tanggal) ?>
                        </div>
                        <?php if (!empty($row['status'])): ?>
                            <div class="card-status"><?= safe_output($row['status']) ?></div>
                        <?php endif; ?>
                    </div>


                    <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                        <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                        <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
                    </div>


                    <div class="card-actions">
                        <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                       
                        <?php if ($file_found): ?>
                            <a href="<?= safe_output($download_url) ?>"
                               class="card-btn btn-download download-btn"
                               data-id="<?= safe_output($row['idData']); ?>"
                               data-filename="<?= safe_output(basename($actual_file_path)) ?>"
                               download>
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        <?php else: ?>
                            <a href="#"
                               class="card-btn btn-download disabled"
                               onclick="event.preventDefault(); showFileNotFoundAlert('<?= addslashes(safe_output($row['noPeraturan'])) ?>');"
                               title="File PDF tidak tersedia">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        <?php endif; ?>
                    </div>
                   
                    <?php if ($debug_mode): ?>
                    <div class="debug-info">
                        <small class="text-muted">
                            <strong>Debug Info:</strong><br>
                            ID: <?= safe_output($row['idData']) ?><br>
                            Image Name: <?= safe_output($row['image_name']) ?><br>
                            Image Path DB: <?= safe_output($row['image_path']) ?><br>
                            Image Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                            Image URL: <?= safe_output($image_url) ?><br>
                            Image Source: <?= isset($image_info['source']) ? $image_info['source'] : 'N/A' ?><br>
                            File Found: <?= $file_found ? 'Ya' : 'Tidak' ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-info-circle"></i> Tidak ada dokumen yang sesuai dengan pencarian "<strong><?= safe_output($search_query) ?></strong>".
        </div>
        <!-- Tampilkan semua dokumen jika tidak ada hasil pencarian -->
        <?php
        // Reset query untuk menampilkan semua dokumen
        $all_query = "SELECT * FROM uploads WHERE jenis = 'English_Legal_Product' ORDER BY idData DESC";
        $all_result = mysqli_query($koneksi, $all_query);
        ?>
        <h3 class="section-title mt-4">Semua English Legal Product Kota Surakarta</h3>
        <div class="card-container">
            <?php while ($row = mysqli_fetch_assoc($all_result)):
                // Proses inline
                $id = (int)$row['idData'];
                $encrypted_id = encode_id($id);
                $image_info = findImageFile($row['image_name'], $row['image_path'], $row['idData']);
                $image_url = $image_info ? $image_info['url'] : '';
                $image_found = $image_info ? true : false;
            ?>
            <div class="document-card">
                <div class="card-image">
                    <?php if ($image_found): ?>
                        <img src="<?= safe_output($image_url) ?>" alt="<?= safe_output($row['judul']) ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="bi bi-file-earmark-medical"></i>
                            <p><?= safe_output($row['singkatanJenis']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div class="card-number"><?= safe_output($row['noPeraturan']) ?></div>
                    <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>
                    <div class="card-actions">
                        <a href="?page=detail-keputusan&token=<?= urlencode($encrypted_id) ?>" class="card-btn btn-view">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
<?php else: // Tampilkan filter dan daftar normal jika tidak ada search ?>


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


<h3 class="section-title">Daftar English Legal Product Kota Surakarta</h3>


<?php if (mysqli_num_rows($result) > 0):
    // Reset pointer untuk display normal
    mysqli_data_seek($result, 0);
?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):


    $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
   
    // ============================================
    // KEAMANAN: ENKRIPSI ID UNTUK URL
    // ============================================
    $id = (int)$row['idData'];
    $encrypted_id = encode_id($id);
   
    // Fallback jika encode gagal
    if (!$encrypted_id) {
        $encrypted_id = base64_encode('jdih_' . $id . '_' . time());
    }
   
    // ============================================
    // CARI FILE PDF DENGAN VALIDASI
    // ============================================
    $file_from_db = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
    $file_found = false;
    $actual_file_path = null;
    $download_url = '#';
   
    if (!empty($file_from_db)) {
        $actual_file_path = findPDFFile($file_from_db, $row['idData']);
        if ($actual_file_path) {
            $file_found = true;
            // Konversi path fisik ke URL dengan lebih fleksibel
            $relative_path = '';
            $doc_root = $_SERVER['DOCUMENT_ROOT'];
            $actual_file_path_normalized = str_replace('\\', '/', $actual_file_path);
           
            if (strpos($actual_file_path_normalized, $doc_root) === 0) {
                $relative_path = str_replace($doc_root, '', $actual_file_path_normalized);
            }
            elseif (stripos($actual_file_path_normalized, 'C:/laragon/www') === 0) {
                $relative_path = str_replace('C:/laragon/www', '', $actual_file_path_normalized);
            }
            elseif (stripos($actual_file_path_normalized, 'C:/xampp/htdocs') === 0) {
                $relative_path = str_replace('C:/xampp/htdocs', '', $actual_file_path_normalized);
            }
            elseif (stripos($actual_file_path_normalized, 'C:/xampp8/htdocs') === 0) {
                $relative_path = str_replace('C:/xampp8/htdocs', '', $actual_file_path_normalized);
            }
            if (strpos($relative_path, '/') !== 0) {
                $relative_path = '/' . $relative_path;
            }
            $download_url = $relative_path . '?t=' . time();
        }
    }
   
    // ============================================
    // CARI GAMBAR DENGAN FUNGSI BARU
    // ============================================
    $image_info = findImageFile($row['image_name'], $row['image_path'], $row['idData']);
    $image_url = '';
    $image_found = false;
   
    if ($image_info) {
        $image_url = $image_info['url'];
        $image_found = true;
    }
   
    // ============================================
    // GENERATE DETAIL URL DENGAN TOKEN
    // ============================================
    $detail = "index.php?page=detail-keputusan&token=" . urlencode($encrypted_id);
?>


<div class="document-card" data-tahun="<?= safe_output($row['tahun_pengundangan']); ?>" data-id="<?= safe_output($row['idData']); ?>">
    <div class="card-image">
        <?php if ($image_found && !empty($image_url)): ?>
            <?php if ($debug_mode): ?>
                <div class="img-debug">✓</div>
            <?php endif; ?>
            <img src="<?= safe_output($image_url) ?>"
                 alt="<?= safe_output($row['judul']) ?>"
                 loading="lazy"
                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';"
                 data-src="<?= safe_output($image_url) ?>">
            <div class="placeholder" style="display: none;">
                <i class="bi bi-file-earmark-medical"></i>
                <p><?= safe_output($row['singkatanJenis']) ?><br>
                <small><?= safe_output($row['noPeraturan']) ?></small></p>
            </div>
        <?php else: ?>
            <?php if ($debug_mode): ?>
                <div class="img-debug">✗</div>
            <?php endif; ?>
            <div class="placeholder">
                <i class="bi bi-file-earmark-medical"></i>
                <p><?= safe_output($row['singkatanJenis']) ?><br>
                <small><?= safe_output($row['noPeraturan']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge"><?= safe_output($row['singkatanJenis']) ?></span>
        </div>
    </div>


    <div class="card-content">
        <div class="card-number"><?= safe_output($row['noPeraturan']) ?></div>
        <h3 class="card-title"><?= safe_output($row['judul']) ?></h3>


        <div class="card-meta">
            <div class="card-date">
                <i class="bi bi-calendar"></i> <?= safe_output($tanggal) ?>
            </div>
            <?php if (!empty($row['status'])): ?>
                <div class="card-status"><?= safe_output($row['status']) ?></div>
            <?php endif; ?>
        </div>


        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
        </div>


        <div class="card-actions">
            <a href="<?= safe_output($detail) ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
           
            <?php if ($file_found): ?>
                <a href="<?= safe_output($download_url) ?>"
                   class="card-btn btn-download download-btn"
                   data-id="<?= safe_output($row['idData']); ?>"
                   data-filename="<?= safe_output(basename($actual_file_path)) ?>"
                   download>
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php else: ?>
                <a href="#"
                   class="card-btn btn-download disabled"
                   onclick="event.preventDefault(); showFileNotFoundAlert('<?= addslashes(safe_output($row['noPeraturan'])) ?>');"
                   title="File PDF tidak tersedia">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php endif; ?>
        </div>
       
        <?php if ($debug_mode): ?>
        <div class="debug-info">
            <small class="text-muted">
                <strong>Debug Info:</strong><br>
                ID: <?= safe_output($row['idData']) ?><br>
                Image Name: <?= safe_output($row['image_name']) ?><br>
                Image Path DB: <?= safe_output($row['image_path']) ?><br>
                Image Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                Image URL: <?= safe_output($image_url) ?><br>
                <?php if ($image_info): ?>
                Image Source: <?= $image_info['source'] ?><br>
                Image Physical Path: <?= $image_info['path'] ?><br>
                <?php endif; ?>
                File Found: <?= $file_found ? 'Ya' : 'Tidak' ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>


<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-files"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada English Legal Product yang tersedia.
    </p>
</div>
<?php endif; ?>
<?php endif; // End empty search_query check ?>
</div>


<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">


<?php
// Reset result pointers for sidebar
if ($year_stats_result) mysqli_data_seek($year_stats_result, 0);
if ($popular_result) mysqli_data_seek($popular_result, 0);
?>


<div class="sidebar-menu">
<h4><i class="bi bi-list"></i> Menu Perundangan</h4>
<ul>
<li><a href="?page=keputusan-sekretariat"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris</a></li>
<li><a href="?page=keputusan-pimpinan"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
<li><a href="?page=keputusan-dprd"><i class="bi bi-translate"></i> Keputusan DPRD</a></li>
<li><a href="?page=peraturan-dprd"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
<li><a href="?page=perda"><i class="bi bi-journal-bookmark"></i> Peraturan Daerah</a></li>
<li class="active"><a href="#"><i class="bi bi-file-earmark-medical"></i> English Legal Product</a></li>
</ul>
</div>


<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik</h4>
<ul>
<li>Total: <strong><?= safe_output($total_dokumen) ?> dokumen</strong></li>
<?php if ($year_stats_result): while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= safe_output($stat['tahun_pengundangan']) ?> : <strong><?= safe_output($stat['jumlah']) ?></strong> dokumen</li>
<?php endwhile; endif; ?>
</ul>
</div>


<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Dokumen Terbaru</h4>
<ul>
<?php if ($popular_result): while ($pop = mysqli_fetch_assoc($popular_result)):
    $pop_id = (int)$pop['idData'];
    $pop_token = encode_id($pop_id);
    if (!$pop_token) {
        $pop_token = base64_encode('jdih_' . $pop_id . '_' . time());
    }
?>
<li>
<a href="index.php?page=detail-keputusan&token=<?= urlencode($pop_token) ?>">
<i class="bi bi-file-text"></i> <?= safe_output($pop['noPeraturan']) ?>
</a>
<small class="text-muted d-block"><?= safe_output($pop['judul']) ?></small>
<small class="text-muted"><?= date('d M Y', strtotime($pop['tanggal_penetapan'])) ?></small>
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
            Script: <?= $_SERVER['PHP_SELF'] ?><br>
            Base Upload Path: <?= $base_upload_path ?><br>
            Web Image Path: <?= $web_image_path ?><br>
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
    // Year filter
    document.querySelectorAll(".year-btn").forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedYear = btn.getAttribute('data-year');
           
            // Validate year input
            if (selectedYear !== 'semua') {
                const yearNum = parseInt(selectedYear);
                if (isNaN(yearNum) || yearNum < 1900 || yearNum > 2100) {
                    console.error('Invalid year:', selectedYear);
                    return;
                }
            }
           
            document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
           
            document.querySelectorAll(".document-card").forEach(card => {
                if (selectedYear === "semua" || card.getAttribute('data-tahun') === selectedYear) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
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
   
    // Disabled button handling
    document.querySelectorAll('.btn-download.disabled').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.document-card');
            const noPeraturan = card.querySelector('.card-number').textContent;
            showFileNotFoundAlert(noPeraturan);
        });
    });
   
    // Image error handling
    document.querySelectorAll('.card-image img').forEach(img => {
        // Check if image already failed to load
        if (img.complete && img.naturalHeight === 0) {
            const placeholder = img.parentElement.querySelector('.placeholder');
            if (placeholder) {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
       
        // Add error event listener
        img.addEventListener('error', function() {
            console.error('Image failed to load:', this.src);
            this.style.display = 'none';
            const placeholder = this.parentElement.querySelector('.placeholder');
            if (placeholder) {
                placeholder.style.display = 'flex';
            }
        });
       
        // Success loading
        img.addEventListener('load', function() {
            console.log('Image loaded successfully:', this.src);
        });
    });
   
    // Search form validation
    const searchForm = document.querySelector('.search-box form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim().length < 2) {
                alert('Masukkan minimal 2 karakter untuk pencarian');
                e.preventDefault();
                return false;
            }
        });
    }
   
    // Debug: Log semua gambar
    <?php if ($debug_mode): ?>
    console.log('=== IMAGE DEBUG INFO ===');
    document.querySelectorAll('.card-image img').forEach(img => {
        console.log('Image src:', img.src, '| Data src:', img.getAttribute('data-src'));
    });
    <?php endif; ?>
});


function showFileNotFoundAlert(noPeraturan) {
    const safeNoPeraturan = noPeraturan.replace(/[<>]/g, '');
   
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk dokumen <strong>${safeNoPeraturan}</strong> tidak ditemukan di server.`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk dokumen ${safeNoPeraturan} tidak ditemukan di server.`);
    }
}


function showDownloadStarted(filename) {
    const safeFilename = filename.replace(/[<>]/g, '');
   
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Download Dimulai',
            html: `File <strong>${safeFilename}</strong> sedang didownload...`,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        console.log(`Download started: ${safeFilename}`);
    }
}


// Fungsi untuk retry loading gambar
function retryImageLoad(imgElement) {
    const src = imgElement.getAttribute('data-src');
    if (src) {
        imgElement.src = src + '?retry=' + Date.now();
    }
}
</script>


<?php
// ============================================
// KEAMANAN LEVEL 10: CLEANUP & LOGGING
// ============================================


// Log page access
$access_log = sprintf(
    "[%s] IP: %s | Page: english-legal.php | Search: %s | Results: %d\n",
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
if (isset($all_result)) mysqli_free_result($all_result);


// Close database connection
if (isset($koneksi)) mysqli_close($koneksi);


// Clear sensitive variables
unset(
    $query, $result, $row,
    $encrypted_id, $file_from_db,
    $actual_file_path, $koneksi,
    $search_results, $search_escaped
);
?>

