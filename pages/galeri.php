<?php
// galeri.php - VERSI SIMPLE DENGAN SEARCH & TANPA SIDEBAR SEPERTI GRAFIS

// ============================================
// KONFIGURASI KEAMANAN (tersembunyi)
// ============================================

// 1. Error Handling dan Logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_error.log');

function log_security_event($event, $details = '') {
    $log_file = __DIR__ . '/logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $log_entry = "[{$timestamp}] [IP: {$ip}] {$event}";
    if (!empty($details)) {
        $log_entry .= " - Details: {$details}";
    }
    $log_entry .= PHP_EOL;
    
    if (!file_exists(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    log_security_event("PHP_ERROR", "{$errstr} in {$errfile} on line {$errline}");
    return true;
});

set_exception_handler(function($exception) {
    log_security_event("EXCEPTION", $exception->getMessage());
    die("<div style='padding:20px;text-align:center;color:#721c24;background-color:#f8d7da;border:1px solid #f5c6cb;border-radius:5px;margin:20px;'>Terjadi kesalahan sistem. Silakan hubungi administrator.</div>");
});

// 2. Pengamanan Session
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true,
    'use_only_cookies' => true
]);

if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// 3. Validasi dan Sanitasi Input GET
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    
    $input = urldecode($input);
    $input = str_replace(chr(0), '', $input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $input = trim($input);
    
    return $input;
}

function validate_search_query($query) {
    if (strlen($query) > 100) {
        return false;
    }
    
    if (!preg_match('/^[a-zA-Z0-9\s\-_,.()@:;\'"\/]+$/u', $query)) {
        return false;
    }
    
    return true;
}

function validate_tab_parameter($tab) {
    $allowed_tabs = ['album', 'foto'];
    return in_array($tab, $allowed_tabs);
}

function validate_page_parameter($page) {
    $allowed_pages = ['galeri'];
    return in_array($page, $allowed_pages);
}

function validate_album_id($id) {
    return is_numeric($id) && $id >= 0 && $id <= 9999999999;
}

// Proses input GET
$search_query_raw = isset($_GET['search']) ? $_GET['search'] : '';
$tab_raw = isset($_GET['tab']) ? $_GET['tab'] : 'album';
$page_raw = isset($_GET['page']) ? $_GET['page'] : '';
$album_id_raw = isset($_GET['album']) ? $_GET['album'] : '';

$search_query_raw = sanitize_input($search_query_raw);
$tab_raw = sanitize_input($tab_raw);
$page_raw = sanitize_input($page_raw);
$album_id_raw = sanitize_input($album_id_raw);

if (!validate_page_parameter($page_raw)) {
    header('HTTP/1.0 400 Bad Request');
    die("Invalid request");
}

if (!validate_tab_parameter($tab_raw)) {
    $tab_raw = 'album';
}

$search_query = '';
if (!empty($search_query_raw)) {
    if (validate_search_query($search_query_raw)) {
        $search_query = $search_query_raw;
    }
}

$tab = $tab_raw;
$album_id = 0;
if (!empty($album_id_raw) && validate_album_id($album_id_raw)) {
    $album_id = (int)$album_id_raw;
}

// 4. INCLUDE KONEKSI DATABASE
try {
    require_once 'a_db.php';
    
    if (!$koneksi || !mysqli_ping($koneksi)) {
        throw new Exception("Database connection failed");
    }
    
    mysqli_set_charset($koneksi, 'utf8mb4');
    
} catch (Exception $e) {
    die("<div style='padding:20px;text-align:center;color:#856404;background-color:#fff3cd;border:1px solid #ffeaa7;border-radius:5px;margin:20px;'>System maintenance in progress. Please try again later.</div>");
}

// ============================================
// HANDLE SEARCH QUERY dengan Prepared Statements
// ============================================
$current_mode = 'album';
$galeri_data = [];
$total_album = 0;
$total_foto = 0;
$search_results_count = 0;
$album_info = null;

try {
    if ($album_id > 0) {
        // Mode: Melihat foto dalam album tertentu dengan prepared statement
        $stmt = mysqli_prepare($koneksi, 
            "SELECT * FROM galeri_foto 
             WHERE tipe = 'foto' 
             AND parent_id = ?
             ORDER BY urutan, tanggal_dibuat DESC");
        
        mysqli_stmt_bind_param($stmt, 'i', $album_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $current_mode = 'foto';
        
        // Ambil info album dengan prepared statement
        $stmt_album = mysqli_prepare($koneksi, 
            "SELECT * FROM galeri_foto WHERE id = ? AND tipe = 'album'");
        mysqli_stmt_bind_param($stmt_album, 'i', $album_id);
        mysqli_stmt_execute($stmt_album);
        $album_result = mysqli_stmt_get_result($stmt_album);
        $album_info = $album_result ? mysqli_fetch_assoc($album_result) : null;
        mysqli_stmt_close($stmt_album);
        
    } else {
        // Mode: Melihat semua album
        if (!empty($search_query)) {
            $search_term = "%{$search_query}%";
            $stmt = mysqli_prepare($koneksi, 
                "SELECT * FROM galeri_foto 
                 WHERE tipe = 'album'
                 AND (judul LIKE ? OR deskripsi LIKE ?)
                 ORDER BY is_cover DESC, urutan, tanggal_dibuat DESC");
            
            mysqli_stmt_bind_param($stmt, 'ss', $search_term, $search_term);
        } else {
            $stmt = mysqli_prepare($koneksi, 
                "SELECT * FROM galeri_foto 
                 WHERE tipe = 'album'
                 ORDER BY is_cover DESC, urutan, tanggal_dibuat DESC");
        }
        
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
    }
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $galeri_data[] = $row;
            
            if ($current_mode == 'album') {
                $total_album++;
            } else {
                $total_foto++;
            }
        }
        $search_results_count = mysqli_num_rows($result);
        mysqli_data_seek($result, 0);
    }
    
    if (isset($stmt) && is_object($stmt)) {
        mysqli_stmt_close($stmt);
    }
    
} catch (Exception $e) {
    log_security_event("DATABASE_ERROR", $e->getMessage());
    $galeri_data = [];
}

// Query untuk menghitung total foto dalam album dengan prepared statement
if ($current_mode == 'album' && !empty($galeri_data)) {
    foreach ($galeri_data as &$album) {
        $album_id = $album['id'];
        $stmt_count = mysqli_prepare($koneksi, 
            "SELECT COUNT(*) as total FROM galeri_foto WHERE tipe = 'foto' AND parent_id = ?");
        
        if ($stmt_count) {
            mysqli_stmt_bind_param($stmt_count, 'i', $album_id);
            mysqli_stmt_execute($stmt_count);
            $count_result = mysqli_stmt_get_result($stmt_count);
            $count_data = $count_result ? mysqli_fetch_assoc($count_result) : ['total' => 0];
            $album['foto_count'] = $count_data['total'];
            $total_foto += $count_data['total'];
            mysqli_stmt_close($stmt_count);
        }
    }
    unset($album);
}

// Fungsi untuk mendapatkan path gambar yang benar dengan validasi keamanan

// Fungsi untuk mendapatkan path gambar yang benar
function getImagePath($url_gambar) {
    if (empty($url_gambar)) {
        return 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    }

    // Jika sudah URL absolut
    if (preg_match('/^https?:\/\//i', $url_gambar)) {
        return $url_gambar;
    }

    // Hanya ambil nama file
    $filename = basename($url_gambar);
    
    // Path untuk web
    $web_path = 'http://localhost/jdihcilacap_pasang_2026/panel_control/uploads/galeri/' . $filename;
    
    // Path fisik di server untuk pengecekan
    $physical_path = $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/galeri/' . $filename;
    
    // Cek apakah file benar-benar ada di server
    if (file_exists($physical_path)) {
        return $web_path;
    } else {
        // Jika tidak ada, return gambar default
        return 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    }
}



// ============================================
// INCLUDE HEADER (TATALETAK ASLI)
// ============================================
$page_title = "Galeri Foto - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<style>
<?php 
// Include style utama dari file CSS
include "style-keputusan-dprd.css";
?>

/* STYLE KHUSUS UNTUK HALAMAN GALERI */
body {
    background: var(--bg-gray);
}

/* Search Section */
.search-section {
    background: var(--bg-white);
    padding: 25px;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
}

.search-wrapper {
    max-width: 600px;
    margin: 0 auto;
}

.search-input-group {
    display: flex;
    gap: 10px;
}

.search-input {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid var(--border-light);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-main);
    box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.15);
}

.search-btn {
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    color: var(--bg-white);
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-main));
    box-shadow: 0 6px 16px rgba(19, 84, 122, 0.35);
}

.reset-btn {
    background: var(--bg-white);
    color: var(--text-medium);
    border: 2px solid var(--border-light);
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reset-btn:hover {
    background: var(--bg-gray);
    border-color: var(--text-light);
}

.search-results-info {
    text-align: center;
    margin-top: 15px;
    color: var(--text-medium);
    font-size: 0.9rem;
}

.search-results-info .badge {
    margin-left: 5px;
}

/* Navigation Breadcrumb */
.galeri-nav {
    background: var(--bg-white);
    padding: 15px 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-sm);
}

.nav-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.nav-link {
    color: var(--primary-main);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.nav-link:hover {
    color: var(--secondary-main);
    text-decoration: underline;
}

.nav-separator {
    color: var(--text-light);
}

.nav-current {
    color: var(--text-dark);
    font-weight: 600;
}

/* Back Button */
.back-button {
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    color: var(--bg-white);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.back-button:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-main));
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(19, 84, 122, 0.35);
    color: var(--bg-white);
    text-decoration: none;
}

/* Album Header */
.album-header {
    background: var(--bg-white);
    padding: 30px;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
    text-align: center;
}

.album-header h2 {
    color: var(--primary-main);
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.8rem;
}

.album-header p {
    color: var(--text-medium);
    max-width: 800px;
    margin: 0 auto 20px;
    line-height: 1.6;
}

.album-stats {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--primary-main);
    font-weight: 600;
}

.stat-item i {
    font-size: 1.2rem;
}

/* Card untuk galeri */
.galeri-card {
    background: var(--bg-white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 30px;
    border: 1px solid transparent;
}

.galeri-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--secondary-light);
}

.galeri-image {
    height: 250px;
    position: relative;
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    overflow: hidden;
}

.galeri-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.galeri-card:hover .galeri-image img {
    transform: scale(1.1) rotate(2deg);
}

/* Placeholder untuk galeri */
.galeri-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--bg-white);
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    position: relative;
}

.galeri-placeholder i {
    font-size: 3.5rem;
    margin-bottom: 12px;
    opacity: 0.9;
}

.galeri-placeholder p {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0;
    opacity: 0.95;
}

/* Image overlay badge */
.image-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 2;
}

.image-badge {
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-main);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Galeri content */
.galeri-content {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.galeri-title {
    font-weight: 600;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 15px;
    color: var(--text-dark);
    display: -webkit-box;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.2em;
}

.galeri-description {
    font-size: 0.9rem;
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

/* Galeri meta */
.galeri-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.galeri-date {
    font-size: 0.85rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}

.galeri-date i {
    color: var(--secondary-main);
}

.galeri-count {
    background: linear-gradient(135deg, #e6fffa, #b2f5ea);
    color: var(--primary-dark);
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Empty state */
.empty-galeri {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-white);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    margin: 30px 0;
}

.empty-galeri i {
    font-size: 4rem;
    color: var(--secondary-light);
    margin-bottom: 20px;
    opacity: 0.6;
}

.empty-galeri h4 {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-galeri p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Lightbox styles */
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
}

.lightbox-content {
    max-width: 90%;
    max-height: 90%;
    position: relative;
}

.lightbox-img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 10px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
}

.lightbox-info {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 0 0 10px 10px;
    padding: 25px;
    margin-top: -5px;
    color: var(--text-dark);
}

.lightbox-title {
    color: var(--primary-main);
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.lightbox-desc {
    color: var(--text-medium);
    margin-bottom: 15px;
    line-height: 1.6;
}

.lightbox-meta {
    display: flex;
    justify-content: space-between;
    color: var(--text-light);
    font-size: 0.9rem;
}

.lightbox-close {
    position: absolute;
    top: 20px;
    right: 20px;
    color: white;
    font-size: 40px;
    cursor: pointer;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    transition: background 0.3s;
}

.lightbox-close:hover {
    background: rgba(0,0,0,0.8);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    font-size: 50px;
    cursor: pointer;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    transition: background 0.3s;
}

.lightbox-nav:hover {
    background: rgba(0,0,0,0.8);
}

.lightbox-prev {
    left: 20px;
}

.lightbox-next {
    right: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .search-input-group {
        flex-direction: column;
    }
    
    .search-input,
    .search-btn,
    .reset-btn {
        width: 100%;
    }
    
    .album-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .galeri-card {
        margin-bottom: 20px;
    }
    
    .lightbox-nav {
        width: 50px;
        height: 50px;
        font-size: 40px;
    }
    
    .lightbox-content {
        max-width: 95%;
    }
}

@media (max-width: 576px) {
    .galeri-content {
        padding: 20px;
    }
    
    .album-header {
        padding: 20px;
    }
    
    .album-header h2 {
        font-size: 1.5rem;
    }
    
    .lightbox-info {
        padding: 15px;
    }
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Galeri Foto</h1>
                <p class="subtitle">Dokumentasi visual kegiatan dan acara JDIH DPRD Kota Surakarta</p>
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mt-4">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Galeri Foto</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT - FULL WIDTH
================================ -->
<section class="main-content">
    <div class="container">
        <!-- SEARCH SECTION -->
        <div class="search-section">
            <div class="search-wrapper">
                <form method="GET" action="" class="search-form">
                    <input type="hidden" name="page" value="galeri">
                    <?php if ($album_id > 0): ?>
                        <input type="hidden" name="album" value="<?php echo htmlspecialchars($album_id, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                    <?php endif; ?>
                    <div class="search-input-group">
                        <input type="text" 
                               name="search" 
                               class="search-input" 
                               placeholder="Cari album atau foto..." 
                               value="<?php echo htmlspecialchars($search_query, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <?php if (!empty($search_query)): ?>
                            <a href="?page=galeri<?php echo $album_id > 0 ? '&album=' . htmlspecialchars($album_id, ENT_QUOTES | ENT_HTML5, 'UTF-8') : ''; ?>" class="reset-btn">
                                <i class="bi bi-x"></i> Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
                
                <?php if (!empty($search_query)): ?>
                    <div class="search-results-info">
                        Hasil pencarian untuk: <strong>"<?php echo htmlspecialchars($search_query, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"</strong>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($search_results_count, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?> hasil ditemukan</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- NAVIGATION BREADCRUMB -->
        <div class="galeri-nav">
            <div class="nav-breadcrumb">
                <a href="?page=galeri" class="nav-link">
                    <i class="bi bi-images"></i> Semua Album
                </a>
                
                <?php if ($album_id > 0 && isset($album_info)): ?>
                    <span class="nav-separator">/</span>
                    <span class="nav-current">
                        <i class="bi bi-folder"></i> <?php echo htmlspecialchars($album_info['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ($album_id > 0): ?>
                <div class="mt-3">
                    <a href="?page=galeri" class="back-button">
                        <i class="bi bi-arrow-left"></i> Kembali ke Semua Album
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ALBUM HEADER (jika sedang melihat album) -->
        <?php if ($album_id > 0 && isset($album_info)): ?>
            <div class="album-header">
                <h2><?php echo htmlspecialchars($album_info['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h2>
                <?php if (!empty($album_info['deskripsi'])): ?>
                    <p><?php echo htmlspecialchars($album_info['deskripsi'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                <?php endif; ?>
                
                <div class="album-stats">
                    <div class="stat-item">
                        <i class="bi bi-images"></i>
                        <span><?php echo htmlspecialchars($total_foto, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?> Foto</span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-calendar"></i>
                        <span><?php echo isset($album_info['tanggal_dibuat']) ? htmlspecialchars(date('d F Y', strtotime($album_info['tanggal_dibuat'])), ENT_QUOTES | ENT_HTML5, 'UTF-8') : ''; ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- GALLERY GRID -->
        <div class="card-container">
            <?php if (!empty($galeri_data)): ?>
                <?php 
                $items_data = []; // Untuk menyimpan data lightbox
                $counter = 0;
                
                foreach ($galeri_data as $item): 
                    $tanggal = isset($item['tanggal_dibuat']) ? date('d F Y', strtotime($item['tanggal_dibuat'])) : '';
                    $url_gambar = getImagePath($item['url_gambar'] ?? '');
                    
                    if ($current_mode == 'album'): 
                        // MODE: TAMPILKAN ALBUM
                        $foto_count = isset($item['foto_count']) ? $item['foto_count'] : 0;
                ?>
                
                <?php
                $albumToken = bin2hex(random_bytes(8));
                $_SESSION['album_tokens'][$albumToken] = $item['id'];
                ?>

                <div class="galeri-card"
                    onclick="window.location.href='?page=galeri&album=<?php echo htmlspecialchars($albumToken, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>'">

                    <div class="galeri-image">
                        <?php if (!empty($url_gambar)): ?>
                            <img src="<?php echo $url_gambar; ?>" 
                                 alt="<?php echo htmlspecialchars($item['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'galeri-placeholder\'><i class=\'bi bi-images\'></i><p>Album Foto</p></div>';">
                        <?php else: ?>
                            <div class="galeri-placeholder">
                                <i class="bi bi-images"></i>
                                <p>Album Foto</p>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($item['is_cover']) && $item['is_cover'] == 1): ?>
                            <div class="image-overlay">
                                <span class="image-badge">Album Utama</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="galeri-content">
                        <h3 class="galeri-title"><?php echo htmlspecialchars($item['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h3>
                        
                        <?php if (!empty($item['deskripsi'])): ?>
                            <p class="galeri-description"><?php echo htmlspecialchars($item['deskripsi'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                        <?php endif; ?>
                        
                        <div class="galeri-meta">
                            <div class="galeri-date">
                                <i class="bi bi-calendar"></i> <?php echo htmlspecialchars($tanggal, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                            </div>
                            <span class="galeri-count">
                                <i class="bi bi-images"></i> <?php echo htmlspecialchars($foto_count, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?> Foto
                            </span>
                        </div>
                    </div>
                </div>
                
                <?php else: // MODE: TAMPILKAN FOTO DALAM ALBUM 
                    $counter++;
                    $items_data[] = [
                        'id' => $counter,
                        'judul' => htmlspecialchars($item['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'deskripsi' => htmlspecialchars($item['deskripsi'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'url_gambar' => $url_gambar,
                        'tanggal' => htmlspecialchars($tanggal, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'album_judul' => isset($album_info['judul']) ? htmlspecialchars($album_info['judul'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : ''
                    ];
                ?>
                
                <div class="galeri-card">
                    <div class="galeri-image">
                        <?php if (!empty($url_gambar)): ?>
                            <img src="<?php echo $url_gambar; ?>" 
                                 alt="<?php echo htmlspecialchars($item['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"
                                 loading="lazy"
                                 onclick="openLightbox(<?php echo $counter - 1; ?>)"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'galeri-placeholder\'><i class=\'bi bi-image\'></i><p>Foto</p></div>';">
                        <?php else: ?>
                            <div class="galeri-placeholder">
                                <i class="bi bi-image"></i>
                                <p>Foto</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="galeri-content">
                        <h3 class="galeri-title"><?php echo htmlspecialchars($item['judul'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h3>
                        
                        <?php if (!empty($item['deskripsi'])): ?>
                            <p class="galeri-description"><?php echo htmlspecialchars($item['deskripsi'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                        <?php endif; ?>
                        
                        <div class="galeri-meta">
                            <div class="galeri-date">
                                <i class="bi bi-calendar"></i> <?php echo htmlspecialchars($tanggal, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php endif; endforeach; ?>
                
            <?php else: // Tidak ada data ?>
            
                <div class="empty-galeri">
                    <?php if ($album_id > 0): ?>
                        <i class="bi bi-image"></i>
                        <h4>Tidak ada foto dalam album ini</h4>
                        <p>Belum ada foto yang diunggah untuk album ini.</p>
                    <?php else: ?>
                        <i class="bi bi-images"></i>
                        <h4>Belum ada album tersedia</h4>
                        <p>Album akan segera ditambahkan</p>
                    <?php endif; ?>
                </div>
                
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <div class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)">
        <i class="bi bi-chevron-left"></i>
    </div>
    <div class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)">
        <i class="bi bi-chevron-right"></i>
    </div>
    <div class="lightbox-content">
        <img id="lightbox-img" class="lightbox-img" src="" alt="">
        <div class="lightbox-info">
            <h3 id="lightbox-title" class="lightbox-title"></h3>
            <p id="lightbox-desc" class="lightbox-desc"></p>
            <div class="lightbox-meta">
                <span id="lightbox-date"></span>
                <span id="lightbox-album"></span>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
// ============================================
// JAVASCRIPT FUNCTIONS UNTUK LIGHTBOX
// ============================================
const itemsData = <?php echo isset($items_data) ? json_encode($items_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : '[]'; ?>;
let currentLightboxIndex = 0;

// Fungsi untuk membuka lightbox
function openLightbox(index) {
    if (itemsData.length === 0) return;
    
    // Validasi index
    if (index < 0 || index >= itemsData.length) return;
    
    currentLightboxIndex = index;
    const item = itemsData[currentLightboxIndex];
    
    // Validasi URL gambar
    function isValidImageUrl(url) {
        return url && url.startsWith('http') && (url.endsWith('.jpg') || url.endsWith('.jpeg') || url.endsWith('.png') || url.endsWith('.gif') || url.endsWith('.webp') || url.includes('unsplash'));
    }
    
    // Preload gambar sebelum ditampilkan
    const img = new Image();
    img.onload = function() {
        document.getElementById('lightbox-img').src = item.url_gambar;
    };
    img.onerror = function() {
        document.getElementById('lightbox-img').src = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    };
    
    // Set src dengan validasi
    if (isValidImageUrl(item.url_gambar)) {
        img.src = item.url_gambar;
    } else {
        img.src = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    }
    
    // Sanitasi output untuk mencegah XSS
    function sanitizeHTML(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }
    
    document.getElementById('lightbox-title').textContent = sanitizeHTML(item.judul);
    document.getElementById('lightbox-desc').textContent = sanitizeHTML(item.deskripsi);
    document.getElementById('lightbox-date').innerHTML = `<i class="bi bi-calendar"></i> ${sanitizeHTML(item.tanggal)}`;
    document.getElementById('lightbox-album').innerHTML = item.album_judul ? `<i class="bi bi-folder"></i> ${sanitizeHTML(item.album_judul)}` : '';
    
    document.getElementById('lightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup lightbox
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk mengganti gambar di lightbox
function changeLightboxImage(direction) {
    currentLightboxIndex += direction;
    
    // Loop around
    if (currentLightboxIndex < 0) {
        currentLightboxIndex = itemsData.length - 1;
    } else if (currentLightboxIndex >= itemsData.length) {
        currentLightboxIndex = 0;
    }
    
    openLightbox(currentLightboxIndex);
}

// Fungsi untuk navigasi keyboard
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    
    if (lightbox.style.display === 'flex') {
        e.preventDefault();
        
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                changeLightboxImage(-1);
                break;
            case 'ArrowRight':
                changeLightboxImage(1);
                break;
        }
    }
});

// Close lightbox ketika klik di luar gambar
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Fungsi untuk swipe di mobile
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('lightbox').addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('lightbox').addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    
    if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe kiri
        changeLightboxImage(1);
    } else if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe kanan
        changeLightboxImage(-1);
    }
}

// Search form validation
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput) {
                const value = searchInput.value.trim();
                if (value.length < 2 && value.length > 0) {
                    alert('Masukkan minimal 2 karakter untuk pencarian');
                    e.preventDefault();
                    return false;
                }
                
                // Validasi input untuk mencegah XSS
                if (/[<>]/g.test(value)) {
                    alert('Input mengandung karakter yang tidak diizinkan');
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
    
    // Preload gambar untuk performa yang lebih baik
    if (itemsData && itemsData.length > 0) {
        itemsData.forEach(function(item) {
            if (item.url_gambar) {
                const img = new Image();
                img.src = item.url_gambar;
            }
        });
    }
    
    // Error handling untuk gambar
    document.querySelectorAll('.galeri-image img').forEach(function(img) {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const placeholder = this.closest('.galeri-image').querySelector('.galeri-placeholder');
            if (!placeholder) {
                const newPlaceholder = document.createElement('div');
                newPlaceholder.className = 'galeri-placeholder';
                newPlaceholder.innerHTML = '<i class="bi bi-image"></i><p>Foto</p>';
                this.parentElement.appendChild(newPlaceholder);
            }
        });
    });
});

// Rate limiting untuk lightbox (mencegah spam klik)
let lastLightboxClick = 0;
const LIGHTBOX_CLICK_DELAY = 300; // 300ms delay

function throttleLightbox(func) {
    const now = Date.now();
    if (now - lastLightboxClick > LIGHTBOX_CLICK_DELAY) {
        lastLightboxClick = now;
        func();
    }
}

// Update event listeners untuk throttle
document.querySelectorAll('[onclick*="openLightbox"]').forEach(function(element) {
    const originalOnClick = element.getAttribute('onclick');
    element.removeAttribute('onclick');
    element.addEventListener('click', function() {
        throttleLightbox(function() {
            eval(originalOnClick);
        });
    });
});
</script>

<?php 
// ============================================
// CLEANUP DENGAN ERROR HANDLING
// ============================================
try {
    // Cleanup resources
    if (isset($result)) {
        mysqli_free_result($result);
    }
    if (isset($album_result)) {
        mysqli_free_result($album_result);
    }
    if (isset($count_result)) {
        mysqli_free_result($count_result);
    }
    
    // Close database connection
    if (isset($koneksi)) {
        mysqli_close($koneksi);
    }
} catch (Exception $e) {
    log_security_event("CLEANUP_ERROR", $e->getMessage());
}
?>