<?php
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
    
    function sanitize($input) {
        if (is_array($input)) {
            return array_map('sanitize', $input);
        }
        // Handle null values untuk PHP 8.1+
        if ($input === null) {
            return '';
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    function safe_output($data) {
        // Handle null values untuk PHP 8.1+
        if ($data === null) {
            return '';
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    function validate_year($year) {
        if ($year === null || $year === '') {
            return null;
        }
        $year = (int)$year;
        return ($year >= 1900 && $year <= 2100) ? $year : null;
    }
} else {
    include_once "a_functions.php";
    
    // Override fungsi safe_output dari a_functions.php untuk handle null
    if (function_exists('safe_output')) {
        // Ganti dengan fungsi yang sudah diupdate
        function safe_output($data) {
            // Handle null values untuk PHP 8.1+
            if ($data === null) {
                return '';
            }
            // Handle array
            if (is_array($data)) {
                return array_map('safe_output', $data);
            }
            // Handle non-string values
            if (!is_string($data)) {
                $data = (string)$data;
            }
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
}

// Fungsi sanitize_input alias untuk sanitize (untuk kompatibilitas)
if (!function_exists('sanitize_input')) {
    function sanitize_input($input) {
        return sanitize($input);
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

// ============================================
// KEAMANAN LEVEL 5: KONEKSI DATABASE DENGAN ERROR HANDLING
// ============================================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "jdihdprd"; // Diubah sesuai database Anda

try {
    $koneksi = new mysqli($host, $user, $pass, $dbname);
    
    if ($koneksi->connect_error) {
        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
    }
    
    $koneksi->set_charset("utf8mb4");
    
    // =================== AMBIL DATA DARI DATABASE ===================
    $query = "SELECT * FROM penghargaan ORDER BY tahun_penghargaan DESC, tanggal_diterima DESC";
    $result = $koneksi->query($query);
    
    if (!$result) {
        throw new Exception("Query gagal: " . $koneksi->error);
    }
    
    $penghargaan_data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Sanitize data dari database - handle null values
            $sanitized_row = [];
            foreach ($row as $key => $value) {
                // Handle null values sebelum safe_output
                $sanitized_row[$key] = $value === null ? '' : safe_output($value);
            }
            $penghargaan_data[] = $sanitized_row;
        }
    }
    
    // Tutup result set
    $result->close();
    
} catch (Exception $e) {
    error_log("Database error in penghargaan.php: " . $e->getMessage());
    $penghargaan_data = [];
    
    // Jangan tampilkan error detail ke user
    if ($debug_mode) {
        die("Database error: " . $e->getMessage());
    }
}

// ============================================
// KEAMANAN LEVEL 6: PATH CONFIGURATION & VALIDATION - DIPERBAIKI
// ============================================
// Tentukan lokasi gambar yang benar
$correct_upload_path = '/jdihcilacap_pasang_2026/panel_control/uploads/';
$correct_image_folder = $correct_upload_path . 'penghargaan/';

// Base URL untuk aplikasi
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . $correct_upload_path;
$image_base_url = 'http://' . $_SERVER['HTTP_HOST'] . $correct_image_folder;

// Path fisik di server
$server_document_root = $_SERVER['DOCUMENT_ROOT'];
$correct_physical_path = $server_document_root . $correct_upload_path;
$correct_physical_image_path = $server_document_root . $correct_image_folder;

// ============================================
// KEAMANAN LEVEL 7: FUNGSI UNTUK MENDAPATKAN GAMBAR - DIPERBAIKI LAGI
// ============================================
/**
 * Fungsi untuk mendapatkan path gambar dari database
 * Mencari gambar di lokasi yang benar: http://localhost/jdihcilacap_pasang_2026/panel_control/uploads/penghargaan/
 */
function getImageUrl($db_path, $id = null, $type = 'thumbnail') {
    global $image_base_url, $correct_physical_image_path, $server_document_root;
    
    // Jika path database kosong, return null
    if (empty($db_path) || $db_path === '') {
        return null;
    }
    
    // Normalize path
    $db_path = str_replace('\\', '/', $db_path);
    
    // STEP 1: Jika path sudah merupakan URL lengkap
    if (preg_match('/^https?:\/\//i', $db_path)) {
        return $db_path;
    }
    
    // STEP 2: Ekstrak filename dari path database
    $filename = basename($db_path);
    
    // STEP 3: Coba lokasi yang benar: /penghargaan/filename
    $correct_location = $correct_physical_image_path . $filename;
    
    if (file_exists($correct_location) && is_file($correct_location)) {
        return $image_base_url . $filename;
    }
    
    // STEP 4: Coba berbagai pola berdasarkan ID jika ada
    if ($id) {
        // Patterns untuk mencari file berdasarkan ID
        $patterns = [];
        
        if ($type === 'thumbnail') {
            $patterns = [
                'thumbnail_' . $id,
                'thumb_' . $id,
                'small_' . $id,
                'img_' . $id . '_thumb',
                'penghargaan_' . $id . '_thumbnail',
            ];
        } else {
            $patterns = [
                'foto_' . $id,
                'image_' . $id,
                'detail_' . $id,
                'penghargaan_' . $id . '_detail',
                'penghargaan_' . $id,
                'award_' . $id,
            ];
        }
        
        // Tambahkan ID saja
        $patterns[] = (string)$id;
        
        // Ekstensi yang dicari
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP'];
        
        // Cari semua kombinasi
        foreach ($patterns as $pattern) {
            foreach ($extensions as $ext) {
                $test_filename = $pattern . '.' . $ext;
                $test_path = $correct_physical_image_path . $test_filename;
                
                if (file_exists($test_path) && is_file($test_path)) {
                    return $image_base_url . $test_filename;
                }
            }
        }
    }
    
    // STEP 5: Coba cari file dengan nama yang sama di lokasi lain
    $search_paths = [
        // Lokasi yang benar
        $correct_physical_image_path . $filename,
        
        // Lokasi alternatif di dalam uploads
        $server_document_root . '/jdihcilacap_pasang_2026/panel_control/uploads/' . $filename,
        $server_document_root . '/jdihcilacap_pasang_2026/uploads/' . $filename,
        $server_document_root . '/panel_control/uploads/penghargaan/' . $filename,
        $server_document_root . '/uploads/penghargaan/' . $filename,
        $server_document_root . '/penghargaan/' . $filename,
        
        // Path absolut umum
        $server_document_root . '/' . $filename,
        
        // Lokasi XAMPP
        'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/penghargaan/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/penghargaan/' . $filename,
        
        // Lokasi Laragon
        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/penghargaan/' . $filename,
        'C:/laragon/www/jdihcilacap_pasang_2026/uploads/penghargaan/' . $filename,
    ];
    
    foreach ($search_paths as $search_path) {
        if (file_exists($search_path) && is_file($search_path)) {
            // Convert to URL
            $relative_path = str_replace($server_document_root, '', $search_path);
            $relative_path = str_replace('\\', '/', $relative_path);
            
            if (strpos($relative_path, '/') !== 0) {
                $relative_path = '/' . $relative_path;
            }
            
            return 'http://' . $_SERVER['HTTP_HOST'] . $relative_path;
        }
    }
    
    // STEP 6: HANYA return null jika BENAR-BENAR tidak ditemukan
    return null;
}

// ============================================
// KEAMANAN LEVEL 8: SET SECURITY HEADERS
// ============================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ============================================
// INCLUDE HEADER TEMPLATE DENGAN TITLE YANG AMAN
// ============================================
$page_title = safe_output("Penghargaan - JDIH DPRD Kota Surakarta");
include 'header.php';

// Hitung statistik dengan validasi
$tahun_unik = [];
$kategori_unik = [];
$current_year = date('Y');

foreach ($penghargaan_data as $data) {
    // Validasi tahun - handle null/empty values
    if (!empty($data['tahun_penghargaan']) && $data['tahun_penghargaan'] !== '') {
        $year = validate_year($data['tahun_penghargaan']);
        if ($year && !in_array($year, $tahun_unik)) {
            $tahun_unik[] = $year;
        }
    }
    
    // Validasi kategori - handle null/empty values
    if (!empty($data['kategori_penghargaan']) && is_string($data['kategori_penghargaan']) && $data['kategori_penghargaan'] !== '') {
        $kategori = trim($data['kategori_penghargaan']);
        if (!in_array($kategori, $kategori_unik)) {
            $kategori_unik[] = $kategori;
        }
    }
}

// Urutkan tahun dari terbaru
rsort($tahun_unik);

// Siapkan data untuk JavaScript dengan kedua gambar
foreach ($penghargaan_data as &$award) {
    // Untuk card: thumbnail_path - HANYA ambil dari database
    $award['thumbnail_url'] = getImageUrl($award['thumbnail_path'] ?? '', $award['id_penghargaan'] ?? null, 'thumbnail');
    
    // Untuk detail: foto_penghargaan_path - HANYA ambil dari database
    $award['detail_image_url'] = getImageUrl($award['foto_penghargaan_path'] ?? '', $award['id_penghargaan'] ?? null, 'foto');
    
    // Debug info
    if ($debug_mode) {
        echo "<!-- DEBUG: ID: " . ($award['id_penghargaan'] ?? 'N/A') . " -->";
        echo "<!-- DEBUG: Thumbnail DB Path: " . ($award['thumbnail_path'] ?? 'N/A') . " -->";
        echo "<!-- DEBUG: Thumbnail URL Found: " . ($award['thumbnail_url'] ?? 'NULL - akan pakai placeholder') . " -->";
        echo "<!-- DEBUG: Detail DB Path: " . ($award['foto_penghargaan_path'] ?? 'N/A') . " -->";
        echo "<!-- DEBUG: Detail URL Found: " . ($award['detail_image_url'] ?? 'NULL - akan pakai placeholder') . " -->";
    }
}

unset($award); // Hapus reference
?>

<style>
<?php 
// Include style utama dari file CSS yang diupload
include "style-keputusan-dprd.css";
?>

/* PENAMBAHAN STYLE KHUSUS UNTUK HALAMAN PENGHARGAAN */

/* VARIABEL CSS SESUAI HEADER.PHP - GRADIENT BIRU HIJAU */
:root {
    --c: #13547a;       /* Biru tua */
    --d: #80d0c7;       /* Hijau muda */
    --bg: #f8f9fa;      /* Background light */
    --w: #fff;          /* Putih */
    --t: #212529;       /* Teks gelap */
    --b: #e9ecef;       /* Border light */
    --r: 14px;
    --s: 0 8px 24px rgba(0,0,0,.08);
}

body {
    background: var(--bg);
}

/* PAGE HEADER */
.page-header {
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: var(--w);
    padding: 80px 0 60px;
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.page-header .subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
    font-family: 'Open Sans', sans-serif;
}

/* BREADCRUMB NAVIGATION */
.breadcrumb-nav {
    background: var(--w);
    border-radius: var(--r);
    padding: 15px 25px;
    margin-bottom: 30px;
    box-shadow: var(--s);
}

.breadcrumb-nav a {
    color: var(--c);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.breadcrumb-nav a:hover {
    color: var(--d);
    text-decoration: underline;
}

.breadcrumb-nav .separator {
    margin: 0 10px;
    color: #94a3b8;
}

.breadcrumb-nav .current {
    color: var(--t);
    font-weight: 600;
}

/* STATISTICS SECTION */
.stats-container {
    background: var(--w);
    border-radius: var(--r);
    box-shadow: var(--s);
    padding: 25px;
    margin-bottom: 40px;
    display: flex;
    justify-content: center;
    gap: 25px;
    flex-wrap: wrap;
    text-align: center;
}

.stat-box {
    min-width: 180px;
    transition: all 0.3s ease;
    border-left: 4px solid var(--c);
    padding: 20px;
}

.stat-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
}

.stat-number {
    font-size: 2.8rem;
    font-weight: bold;
    color: var(--c);
    margin-bottom: 10px;
    display: block;
    font-family: 'Montserrat', sans-serif;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
}

/* FILTER SECTION */
.filter-section {
    background: var(--w);
    border-radius: var(--r);
    padding: 25px;
    margin-bottom: 40px;
    box-shadow: var(--s);
}

.filter-title {
    color: var(--c);
    font-weight: 700;
    margin-bottom: 20px;
    font-size: 1.3rem;
    font-family: 'Montserrat', sans-serif;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #555;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Montserrat', sans-serif;
}

.filter-input {
    padding: 12px 15px;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    font-family: 'Open Sans', sans-serif;
}

.filter-input:focus {
    outline: none;
    border-color: var(--c);
    box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.15);
}

/* AWARD CARD */
.award-card {
    background: var(--w);
    border-radius: var(--r);
    box-shadow: var(--s);
    margin-bottom: 30px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
    border: 1px solid #dee2e6;
    cursor: pointer;
}

.award-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.award-image-container {
    height: 220px;
    position: relative;
    overflow: hidden;
}

.award-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.award-card:hover .award-image {
    transform: scale(1.05);
}

/* Style untuk image placeholder */
.award-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: white;
    text-align: center;
    padding: 20px;
    transition: transform 0.5s ease;
}

.award-card:hover .award-image-placeholder {
    transform: scale(1.05);
}

.award-image-placeholder i {
    font-size: 3rem;
    margin-bottom: 15px;
}

.award-image-placeholder span {
    font-size: 0.9rem;
    opacity: 0.9;
}

.award-year-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: var(--w);
    padding: 6px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: 'Montserrat', sans-serif;
    backdrop-filter: blur(5px);
}

.award-content {
    padding: 20px;
}

.award-title {
    color: var(--c);
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.2em;
    line-height: 1.4;
    font-family: 'Montserrat', sans-serif;
}

.award-category {
    display: inline-block;
    background: #eef2ff;
    color: var(--c);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.award-description {
    color: var(--t);
    margin-bottom: 15px;
    font-size: 0.9em;
    line-height: 1.5;
    height: 65px;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Open Sans', sans-serif;
}

.award-details {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
    margin-top: 15px;
}

.detail-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    font-family: 'Open Sans', sans-serif;
}

.detail-row i {
    color: var(--c);
    width: 18px;
    text-align: center;
}

/* NO DATA MESSAGE */
.no-data {
    text-align: center;
    padding: 60px 30px;
    background: var(--w);
    border-radius: var(--r);
    box-shadow: var(--s);
    margin: 30px 0;
    border: 1px solid #dee2e6;
}

.no-data i {
    font-size: 4rem;
    color: var(--c);
    margin-bottom: 25px;
    opacity: 0.8;
}

.no-data h4 {
    color: var(--c);
    margin-bottom: 15px;
    font-size: 1.5rem;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
}

.no-data p {
    color: var(--t);
    margin-bottom: 25px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
    font-family: 'Open Sans', sans-serif;
}

/* MODAL/ LIGHTBOX STYLES */
.lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.95);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow-y: auto;
}

.lightbox-content {
    max-width: 800px;
    width: 95%;
    max-height: 90vh;
    position: relative;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: modalAppear 0.3s ease;
}

@keyframes modalAppear {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.lightbox-image-container {
    flex-shrink: 0;
    height: 400px; /* Lebih tinggi untuk gambar detail */
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
}

.lightbox-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
}

.lightbox-body {
    flex: 1;
    padding: 25px;
    color: var(--t);
    overflow-y: auto;
    max-height: calc(90vh - 400px - 50px);
}

.lightbox-title {
    color: var(--c);
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.8rem;
    line-height: 1.3;
    font-family: 'Montserrat', sans-serif;
    padding-right: 40px;
}

.lightbox-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.lightbox-year {
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: var(--w);
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}

.lightbox-category {
    background: #eef2ff;
    color: var(--c);
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}

.lightbox-description {
    background: #f8fafc;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    line-height: 1.7;
    font-size: 1.05rem;
    font-family: 'Open Sans', sans-serif;
}

.lightbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.lightbox-info-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    border-left: 4px solid var(--c);
    transition: transform 0.2s ease;
}

.lightbox-info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.lightbox-info-card h4 {
    color: var(--c);
    margin-bottom: 12px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Montserrat', sans-serif;
}

.lightbox-info-card p {
    color: #475569;
    font-size: 1.05rem;
    font-weight: 500;
    font-family: 'Open Sans', sans-serif;
    line-height: 1.5;
}

.lightbox-close {
    position: absolute;
    top: 20px;
    right: 20px;
    color: var(--w);
    font-size: 28px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0,0,0,0.7);
    transition: all 0.3s;
    z-index: 10;
    border: none;
}

.lightbox-close:hover {
    background: rgba(0,0,0,0.9);
    transform: rotate(90deg);
}

/* Scrollbar styling untuk lightbox */
.lightbox-body::-webkit-scrollbar {
    width: 8px;
}

.lightbox-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.lightbox-body::-webkit-scrollbar-thumb {
    background: var(--c);
    border-radius: 10px;
}

.lightbox-body::-webkit-scrollbar-thumb:hover {
    background: var(--d);
}

/* PAGINATION */
.pagination {
    justify-content: center;
    margin-top: 40px;
}

.page-item .page-link {
    color: var(--c);
    border: 2px solid #dee2e6;
    margin: 0 5px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s;
    padding: 8px 18px;
    font-family: 'Montserrat', sans-serif;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    border-color: transparent;
    color: var(--w);
}

.page-item .page-link:hover {
    background: var(--bg);
    border-color: var(--c);
    color: var(--c);
    text-decoration: none;
}

.page-item.disabled .page-link {
    color: #adb5bd;
    pointer-events: none;
    background-color: var(--w);
    border-color: #dee2e6;
}

.page-info {
    text-align: center;
    color: var(--t);
    margin-top: 15px;
    font-size: 0.95rem;
    font-family: 'Open Sans', sans-serif;
}

/* BACK TO TOP BUTTON */
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    transition: all 0.3s ease;
    z-index: 100;
    border: none;
}

.back-to-top:hover {
    background: linear-gradient(135deg, #0f4266 0%, #6bc0b7 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(19, 84, 122, 0.4);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .page-header {
        padding: 60px 0 40px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .stats-container {
        padding: 20px;
        gap: 15px;
    }
    
    .stat-box {
        min-width: 140px;
    }
    
    .stat-number {
        font-size: 2.2rem;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .lightbox-content {
        width: 98%;
        max-height: 95vh;
    }
    
    .lightbox-image-container {
        height: 300px; /* Lebih kecil di mobile */
    }
    
    .lightbox-body {
        max-height: calc(95vh - 300px - 50px);
    }
    
    .lightbox-grid {
        grid-template-columns: 1fr;
    }
    
    .lightbox-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .page-header .subtitle {
        font-size: 1rem;
    }
    
    .breadcrumb-nav {
        padding: 12px 15px;
        font-size: 0.9rem;
    }
    
    .stat-box {
        min-width: 120px;
        padding: 15px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .lightbox-body {
        padding: 20px;
    }
    
    .lightbox-image-container {
        height: 250px;
    }
    
    .lightbox-body {
        max-height: calc(95vh - 250px - 40px);
    }
    
    .lightbox-description {
        padding: 15px;
        font-size: 1rem;
    }
    
    .lightbox-meta {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}

@media (max-width: 400px) {
    .lightbox-image-container {
        height: 200px;
        padding: 15px;
    }
    
    .lightbox-body {
        padding: 15px;
        max-height: calc(95vh - 200px - 30px);
    }
    
    .lightbox-title {
        font-size: 1.3rem;
    }
    
    .lightbox-close {
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        font-size: 24px;
    }
}
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Penghargaan</h1>
                <p class="subtitle">
                    Kumpulan penghargaan dan prestasi yang telah diraih oleh JDIH DPRD Kota Surakarta
                </p>
            </div>
        </div>
    </div>
</section>

<!-- BREADCRUMB NAVIGATION -->
<div class="container">
    <div class="breadcrumb-nav">
        <a href="index.php">Beranda</a>
        <span class="separator">/</span>
        <a href="?page=informasi">Informasi</a>
        <span class="separator">/</span>
        <span class="current">Penghargaan</span>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <!-- STATISTICS -->
    <div class="stats-container">
        <div class="stat-box">
            <span class="stat-number" id="totalCount"><?php echo count($penghargaan_data); ?></span>
            <span class="stat-label">Total Penghargaan</span>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo validate_year($current_year) ?: date('Y'); ?></span>
            <span class="stat-label">Tahun Ini</span>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo count($kategori_unik); ?></span>
            <span class="stat-label">Kategori</span>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <div class="filter-title">
            <i class="bi bi-filter"></i> Filter Penghargaan
        </div>
        <div class="filter-grid">
            <div class="filter-group">
                <label for="filterYear"><i class="bi bi-calendar"></i> Tahun</label>
                <select id="filterYear" class="filter-input">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahun_unik as $tahun): 
                        if (validate_year($tahun)): ?>
                        <option value="<?php echo safe_output($tahun); ?>"><?php echo safe_output($tahun); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filterCategory"><i class="bi bi-tags"></i> Kategori</label>
                <select id="filterCategory" class="filter-input">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_unik as $kategori): ?>
                        <option value="<?php echo safe_output($kategori); ?>"><?php echo safe_output($kategori); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="searchInput"><i class="bi bi-search"></i> Cari</label>
                <input type="text" id="searchInput" class="filter-input" placeholder="Cari berdasarkan nama, penerima, atau deskripsi...">
            </div>
        </div>
    </div>

    <!-- AWARDS GALLERY -->
    <div class="row" id="awardsGallery">
        <?php if (empty($penghargaan_data)): ?>
            <div class="col-12">
                <div class="no-data">
                    <i class="bi bi-trophy"></i>
                    <h4>Tidak Ada Data Penghargaan</h4>
                    <p>Belum ada data penghargaan yang tersedia.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($penghargaan_data as $index => $award): ?>
                <?php
                // Potong deskripsi dengan aman
                $short_desc = '';
                if (!empty($award['deskripsi']) && $award['deskripsi'] !== '') {
                    $desc = strip_tags($award['deskripsi']);
                    $short_desc = strlen($desc) > 150 ? substr($desc, 0, 150) . '...' : $desc;
                }
                ?>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="award-card" 
                         data-year="<?php echo !empty($award['tahun_penghargaan']) && validate_year($award['tahun_penghargaan']) ? safe_output($award['tahun_penghargaan']) : ''; ?>" 
                         data-category="<?php echo !empty($award['kategori_penghargaan']) ? safe_output($award['kategori_penghargaan']) : ''; ?>"
                         data-index="<?php echo $index; ?>">
                        
                        <div class="award-image-container">
                            <?php if (!empty($award['thumbnail_url'])): ?>
                                <!-- CARD: Gunakan thumbnail dari lokasi yang benar -->
                                <img src="<?php echo safe_output($award['thumbnail_url']); ?>" 
                                     alt="<?php echo !empty($award['nama_penghargaan']) ? safe_output($award['nama_penghargaan']) : 'Penghargaan'; ?>" 
                                     class="award-image"
                                     loading="lazy"
                                     data-src="<?php echo safe_output($award['thumbnail_url']); ?>"
                                     data-index="<?php echo $index; ?>">
                            <?php else: ?>
                                <!-- HANYA gunakan placeholder jika tidak ditemukan di database -->
                                <div class="award-image-placeholder" data-index="<?php echo $index; ?>">
                                    <i class="bi bi-trophy"></i>
                                    <span>Gambar Penghargaan</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($award['tahun_penghargaan']) && validate_year($award['tahun_penghargaan'])): ?>
                                <div class="award-year-badge"><?php echo safe_output($award['tahun_penghargaan']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="award-content">
                            <h3 class="award-title"><?php echo !empty($award['nama_penghargaan']) ? safe_output($award['nama_penghargaan']) : 'Tanpa Nama'; ?></h3>
                            
                            <?php if (!empty($award['kategori_penghargaan']) && $award['kategori_penghargaan'] !== ''): ?>
                                <span class="award-category"><?php echo safe_output($award['kategori_penghargaan']); ?></span>
                            <?php endif; ?>
                            
                            <p class="award-description"><?php echo !empty($short_desc) ? safe_output($short_desc) : 'Tidak ada deskripsi'; ?></p>
                            
                            <div class="award-details">
                                <?php if (!empty($award['penerima_penghargaan']) && $award['penerima_penghargaan'] !== ''): ?>
                                    <div class="detail-row">
                                        <i class="bi bi-person-badge"></i>
                                        <span><strong>Penerima:</strong> <?php echo safe_output($award['penerima_penghargaan']); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($award['instansi_pemberi']) && $award['instansi_pemberi'] !== ''): ?>
                                    <div class="detail-row">
                                        <i class="bi bi-building"></i>
                                        <span><strong>Pemberi:</strong> <?php echo safe_output($award['instansi_pemberi']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL/LIGHTBOX -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">&times;</button>
        
        <div class="lightbox-image-container" id="lightbox-image-container">
            <!-- DETAIL: Gambar akan dimuat oleh JavaScript -->
            <img id="lightbox-img" class="lightbox-img" src="" alt="" style="display: none;">
        </div>
        
        <div class="lightbox-body">
            <h3 id="lightbox-title" class="lightbox-title"></h3>
            
            <div class="lightbox-meta" id="lightbox-meta">
                <span id="lightbox-year" class="lightbox-year"></span>
                <span id="lightbox-category" class="lightbox-category"></span>
            </div>
            
            <div class="lightbox-description" id="lightbox-description"></div>
            
            <div class="lightbox-grid" id="lightbox-details"></div>
        </div>
    </div>
</div>

<!-- BACK TO TOP BUTTON -->
<button class="back-to-top" id="backToTop">
    <i class="bi bi-chevron-up"></i>
</button>

<script>
// Data dari PHP dengan kedua URL gambar
const awardsData = <?php echo json_encode($penghargaan_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

// Base URL untuk gambar
const baseImageUrl = 'http://<?php echo $_SERVER["HTTP_HOST"]; ?>/jdihcilacap_pasang_2026/panel_control/uploads/penghargaan/';

console.log("Base image URL:", baseImageUrl);
console.log("Awards data loaded:", awardsData);

// Fungsi untuk mencari gambar alternatif berdasarkan ID dan nama
function findAlternativeImage(award, type) {
    const id = award.id_penghargaan;
    const awardName = award.nama_penghargaan || '';
    
    if (!id) return null;
    
    // Patterns untuk mencari file
    const patterns = [];
    
    if (type === 'thumbnail') {
        patterns.push(
            `thumbnail_${id}`,
            `thumb_${id}`,
            `small_${id}`,
            `img_${id}_thumb`,
            `penghargaan_${id}_thumbnail`,
            `thumb_${awardName.toLowerCase().replace(/\s+/g, '_')}`,
            `thumbnail_${awardName.toLowerCase().replace(/\s+/g, '_')}`
        );
    } else {
        patterns.push(
            `foto_${id}`,
            `image_${id}`,
            `detail_${id}`,
            `penghargaan_${id}_detail`,
            `penghargaan_${id}`,
            `award_${id}`,
            `foto_${awardName.toLowerCase().replace(/\s+/g, '_')}`,
            `image_${awardName.toLowerCase().replace(/\s+/g, '_')}`
        );
    }
    
    // Tambahkan ID saja
    patterns.push(String(id));
    
    // Ekstensi yang dicari
    const extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    
    // Coba semua kombinasi
    for (const pattern of patterns) {
        for (const ext of extensions) {
            const testUrl = `${baseImageUrl}${pattern}.${ext}`;
            console.log(`Trying alternative: ${testUrl}`);
            
            // Buat tes async untuk mengecek apakah file ada
            const img = new Image();
            img.src = testUrl;
            
            // Jika gambar berhasil dimuat, return URL
            if (img.complete || img.width > 0) {
                return testUrl;
            }
        }
    }
    
    return null;
}

// Fungsi helper untuk menampilkan placeholder gambar di lightbox
function showImagePlaceholder(container, title) {
    // JANGAN timpa seluruh container, hanya tambahkan div placeholder
    const placeholderDiv = document.createElement('div');
    placeholderDiv.style.cssText = 'width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg, #13547a 0%, #80d0c7 100%);color:white;text-align:center;padding:20px;';
    placeholderDiv.innerHTML = `
        <i class="bi bi-trophy" style="font-size:4rem;margin-bottom:20px;"></i>
        <h4 style="color:white;margin-bottom:10px;">${sanitizeHTML(title)}</h4>
        <p style="opacity:0.9;">Gambar tidak tersedia</p>
    `;
    
    // Sembunyikan gambar dan tambahkan placeholder
    const imgElement = document.getElementById('lightbox-img');
    if (imgElement) {
        imgElement.style.display = 'none';
    }
    container.appendChild(placeholderDiv);
}

// Fungsi untuk filter penghargaan
function filterAwards() {
    const yearValue = document.getElementById('filterYear').value;
    const categoryValue = document.getElementById('filterCategory').value;
    const searchValue = document.getElementById('searchInput').value.toLowerCase().trim();
    const awardCards = document.querySelectorAll('.award-card');
    const totalCountElement = document.getElementById('totalCount');
    
    // Validasi input
    if (yearValue && !/^\d{4}$/.test(yearValue)) {
        console.error('Invalid year format:', yearValue);
        return;
    }
    
    if (searchValue && searchValue.length < 2) {
        return; // Minimal 2 karakter untuk pencarian
    }
    
    let visibleCount = 0;
    
    awardCards.forEach(card => {
        const year = card.getAttribute('data-year');
        const category = card.getAttribute('data-category');
        const index = parseInt(card.getAttribute('data-index'));
        const award = awardsData[index];
        
        let visible = true;
        
        // Filter tahun
        if (yearValue && year !== yearValue) {
            visible = false;
        }
        
        // Filter kategori
        if (categoryValue && category !== categoryValue) {
            visible = false;
        }
        
        // Filter pencarian
        if (searchValue) {
            const searchText = `
                ${award.nama_penghargaan || ''}
                ${award.deskripsi || ''}
                ${award.penerima_penghargaan || ''}
                ${award.instansi_pemberi || ''}
                ${award.kategori_penghargaan || ''}
            `.toLowerCase();
            
            if (!searchText.includes(searchValue)) {
                visible = false;
            }
        }
        
        // Tampilkan/sembunyikan card
        card.parentElement.style.display = visible ? 'block' : 'none';
        if (visible) visibleCount++;
    });
    
    // Update total count
    if (totalCountElement) {
        totalCountElement.textContent = visibleCount;
    }
    
    // Tampilkan pesan jika tidak ada data
    const gallery = document.getElementById('awardsGallery');
    const existingNoData = gallery.querySelector('.no-data');
    
    if (visibleCount === 0 && !existingNoData) {
        const noDataDiv = document.createElement('div');
        noDataDiv.className = 'col-12';
        noDataDiv.innerHTML = `
            <div class="no-data">
                <i class="bi bi-search"></i>
                <h4>Tidak Ada Hasil</h4>
                <p>Tidak ada penghargaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        `;
        gallery.appendChild(noDataDiv);
    } else if (visibleCount > 0 && existingNoData) {
        existingNoData.parentElement.remove();
    }
}

// Fungsi untuk membuka lightbox dengan gambar detail - DIPERBAIKI
function openLightbox(index) {
    if (!awardsData || !awardsData[index]) {
        console.error('Invalid award index:', index);
        return;
    }
    
    const award = awardsData[index];
    
    // Validasi dan sanitasi data
    const awardName = award.nama_penghargaan || 'Tanpa Nama';
    const awardYear = award.tahun_penghargaan || '';
    const awardCategory = award.kategori_penghargaan || '';
    const awardDescription = award.deskripsi || 'Tidak ada deskripsi tersedia.';
    
    // Set gambar DETAIL
    const imgElement = document.getElementById('lightbox-img');
    const imageContainer = document.getElementById('lightbox-image-container');
    
    // Bersihkan placeholder sebelumnya (jika ada)
    const existingPlaceholder = imageContainer.querySelector('div');
    if (existingPlaceholder && existingPlaceholder !== imgElement) {
        existingPlaceholder.remove();
    }
    
    // Reset tampilan gambar
    imgElement.style.display = 'block';
    imgElement.alt = sanitizeHTML(awardName);
    
    // STEP 1: Prioritaskan detail_image_url dari database
    if (award.detail_image_url) {
        console.log('Using detail image from database:', award.detail_image_url);
        // Gunakan gambar dari database
        imgElement.src = award.detail_image_url;
        
        // Setup error handling untuk gambar dari database
        imgElement.onerror = function() {
            console.log('Detail image from DB failed, trying thumbnail...');
            // Coba thumbnail sebagai fallback
            if (award.thumbnail_url) {
                this.src = award.thumbnail_url;
                this.onerror = function() {
                    console.log('Thumbnail also failed, showing placeholder...');
                    showImagePlaceholder(imageContainer, awardName);
                };
            } else {
                showImagePlaceholder(imageContainer, awardName);
            }
        };
    } 
    // STEP 2: Jika tidak ada detail, coba thumbnail dari database
    else if (award.thumbnail_url) {
        console.log('Using thumbnail from database:', award.thumbnail_url);
        // Gunakan thumbnail dari database
        imgElement.src = award.thumbnail_url;
        
        // Setup error handling
        imgElement.onerror = function() {
            console.log('Thumbnail image failed, trying to find alternative...');
            
            // Coba cari gambar alternatif
            const alternativeUrl = findAlternativeImage(award, 'detail');
            if (alternativeUrl) {
                console.log('Found alternative image:', alternativeUrl);
                this.src = alternativeUrl;
                this.onerror = function() {
                    console.log('Alternative image also failed, showing placeholder...');
                    showImagePlaceholder(imageContainer, awardName);
                };
            } else {
                showImagePlaceholder(imageContainer, awardName);
            }
        };
    }
    // STEP 3: Coba cari gambar alternatif
    else {
        console.log('No image found in database, trying to find alternative...');
        
        // Cari gambar alternatif
        const alternativeUrl = findAlternativeImage(award, 'detail') || findAlternativeImage(award, 'thumbnail');
        
        if (alternativeUrl) {
            console.log('Found alternative image:', alternativeUrl);
            imgElement.src = alternativeUrl;
            
            imgElement.onerror = function() {
                console.log('Alternative image failed, showing placeholder...');
                showImagePlaceholder(imageContainer, awardName);
            };
        } else {
            console.log('No alternative image found, showing placeholder...');
            imgElement.style.display = 'none';
            showImagePlaceholder(imageContainer, awardName);
        }
    }
    
    // Set judul dan deskripsi - PERBAIKAN: JANGAN timpa elemen yang ada
    const titleElement = document.getElementById('lightbox-title');
    const descriptionElement = document.getElementById('lightbox-description');
    
    if (titleElement) {
        titleElement.textContent = awardName;
    }
    
    if (descriptionElement) {
        descriptionElement.textContent = awardDescription;
    }
    
    // Set meta data
    const yearSpan = document.getElementById('lightbox-year');
    const categorySpan = document.getElementById('lightbox-category');
    
    if (yearSpan) {
        if (awardYear) {
            yearSpan.textContent = awardYear;
            yearSpan.style.display = 'inline-block';
        } else {
            yearSpan.style.display = 'none';
        }
    }
    
    if (categorySpan) {
        if (awardCategory) {
            categorySpan.textContent = awardCategory;
            categorySpan.style.display = 'inline-block';
        } else {
            categorySpan.style.display = 'none';
        }
    }
    
    // Set detail info
    const detailsContainer = document.getElementById('lightbox-details');
    let detailsHTML = '';
    
    // Fungsi helper untuk membuat card info
    function createInfoCard(title, content, icon = 'info-circle') {
        if (!content || content === '') return '';
        return `
            <div class="lightbox-info-card">
                <h4><i class="bi bi-${icon}"></i> ${sanitizeHTML(title)}</h4>
                <p>${sanitizeHTML(content)}</p>
            </div>
        `;
    }
    
    // Tambahkan semua card info
    detailsHTML += createInfoCard('Penerima Penghargaan', award.penerima_penghargaan, 'person-badge');
    detailsHTML += createInfoCard('Instansi Pemberi', award.instansi_pemberi, 'building');
    
    if (award.tanggal_diterima && award.tanggal_diterima !== '') {
        try {
            const date = new Date(award.tanggal_diterima);
            if (!isNaN(date.getTime())) {
                const formattedDate = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                detailsHTML += createInfoCard('Tanggal Diterima', formattedDate, 'calendar-check');
            }
        } catch (e) {
            console.log('Error formatting date:', e);
        }
    }
    
    if (award.created_at && award.created_at !== '') {
        try {
            const date = new Date(award.created_at);
            if (!isNaN(date.getTime())) {
                const formattedDate = date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                detailsHTML += createInfoCard('Ditambahkan Pada', formattedDate, 'clock');
            }
        } catch (e) {
            console.log('Error formatting created_at date:', e);
        }
    }
    
    if (detailsContainer) {
        detailsContainer.innerHTML = detailsHTML || '<p>Tidak ada informasi tambahan</p>';
    }
    
    // Tampilkan lightbox
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    // Scroll ke atas konten lightbox
    const lightboxBody = document.querySelector('.lightbox-body');
    if (lightboxBody) {
        lightboxBody.scrollTop = 0;
    }
}

// Fungsi untuk menutup lightbox
function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset gambar untuk next time
        const imgElement = document.getElementById('lightbox-img');
        if (imgElement) {
            imgElement.src = '';
            imgElement.style.display = 'none';
        }
        
        // Hapus placeholder jika ada
        const imageContainer = document.getElementById('lightbox-image-container');
        const placeholder = imageContainer.querySelector('div');
        if (placeholder && placeholder.id !== 'lightbox-img') {
            placeholder.remove();
        }
    }
}

// Fungsi sanitasi HTML sederhana untuk client-side
function sanitizeHTML(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Setup event listeners dengan validasi
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded");
    
    // Setup filter dengan debounce untuk pencarian
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterAwards, 300);
        });
    }
    
    // Setup filter events
    const filterYear = document.getElementById('filterYear');
    if (filterYear) {
        filterYear.addEventListener('change', filterAwards);
    }
    
    const filterCategory = document.getElementById('filterCategory');
    if (filterCategory) {
        filterCategory.addEventListener('change', filterAwards);
    }
    
    // Setup klik pada award card untuk membuka lightbox
    // Event delegation untuk menangani klik pada card dan placeholder
    document.getElementById('awardsGallery').addEventListener('click', function(e) {
        let target = e.target;
        
        // Cari elemen award-card terdekat
        let card = target.closest('.award-card');
        if (!card) {
            // Mungkin klik pada placeholder
            const placeholder = target.closest('.award-image-placeholder');
            if (placeholder) {
                card = placeholder.closest('.award-card');
            }
        }
        
        if (card) {
            const index = parseInt(card.getAttribute('data-index'));
            console.log("Card clicked, index:", index);
            if (!isNaN(index) && index >= 0 && index < awardsData.length) {
                openLightbox(index);
            } else {
                console.error("Invalid index:", index);
            }
        }
    });
    
    // Setup tombol close lightbox
    const closeBtn = document.getElementById('lightboxClose');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeLightbox);
    }
    
    // Close lightbox ketika klik di luar konten
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
    }
    
    // Close lightbox dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
    
    // Back to top button
    const backToTopBtn = document.getElementById('backToTop');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Image error handling untuk thumbnail di card
    document.querySelectorAll('.award-image').forEach(img => {
        img.addEventListener('error', function() {
            console.warn('Thumbnail image failed to load:', this.src);
            
            // Coba cari gambar alternatif
            const index = this.getAttribute('data-index');
            const award = awardsData[index];
            
            if (award) {
                const alternativeUrl = findAlternativeImage(award, 'thumbnail');
                if (alternativeUrl) {
                    console.log('Found alternative thumbnail:', alternativeUrl);
                    this.src = alternativeUrl;
                    return;
                }
            }
            
            // Ganti dengan placeholder
            const container = this.parentElement;
            const awardName = award ? award.nama_penghargaan || 'Penghargaan' : 'Penghargaan';
            
            // Jangan timpa badge tahun jika ada
            const yearBadge = container.querySelector('.award-year-badge');
            const badgeHTML = yearBadge ? yearBadge.outerHTML : '';
            
            container.innerHTML = `
                <div class="award-image-placeholder" data-index="${index}">
                    <i class="bi bi-trophy"></i>
                    <span>${sanitizeHTML(awardName)}</span>
                </div>
                ${badgeHTML}
            `;
        });
    });
    
    // Inisialisasi filter
    filterAwards();
    
    // Test: Coba buka console untuk debugging
    console.log("Event listeners setup complete");
});
</script>

<?php 
// ============================================
// KEAMANAN LEVEL 9: CLEANUP & LOGGING
// ============================================

// Log page access dengan sanitasi
$access_log = sprintf(
    "[%s] IP: %s | Page: penghargaan.php | User-Agent: %s | Results: %d | Image Path: %s\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 100),
    count($penghargaan_data),
    $image_base_url
);
error_log($access_log);

// Cleanup resources
if (isset($koneksi) && $koneksi instanceof mysqli) {
    $koneksi->close();
}

// Clear sensitive variables
unset(
    $koneksi, $result, $penghargaan_data,
    $tahun_unik, $kategori_unik, 
    $correct_upload_path, $correct_image_folder
);

include 'footer.php'; 
?>