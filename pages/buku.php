
<?php
// buku.php - HALAMAN AWAL BUKU PERPUSTAKAAN DENGAN DOWNLOAD DIRECT
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' => 'Strict',
    'use_only_cookies' => 1
]);

include_once "a_db.php";

// ============================================
// NAMESPACED FUNCTIONS UNTUK BUKU
// ============================================

if (!function_exists('buku_sanitize_input')) {
    function buku_sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('buku_safe_output')) {
    function buku_safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('buku_encode_token')) {
    function buku_encode_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        return base64_encode('jdih_buku_' . $id . '_' . time());
    }
}

if (!function_exists('buku_find_file')) {
    function buku_find_file($filename, $id = null) {
        $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
        $base_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/buku/";
        
        if (!is_dir($base_path)) {
            $possible_paths = [
                $document_root . "/panel_control/uploads/buku/",
                $document_root . "/uploads/buku/",
                "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/buku/",
                "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/buku/"
            ];
            
            foreach ($possible_paths as $path) {
                if (is_dir($path)) {
                    $base_path = $path;
                    break;
                }
            }
        }
        
        if (!empty($filename)) {
            $clean_name = basename($filename);
            $test_path = $base_path . $clean_name;
            
            if (file_exists($test_path) && is_file($test_path) && is_readable($test_path)) {
                return $test_path;
            }
        }
        
        if ($id) {
            $extensions = ['pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX'];
            foreach ($extensions as $ext) {
                $test_path = $base_path . $id . '.' . $ext;
                if (file_exists($test_path)) return $test_path;
                
                $test_path = $base_path . 'buku_' . $id . '.' . $ext;
                if (file_exists($test_path)) return $test_path;
                
                $test_path = $base_path . 'file_' . $id . '.' . $ext;
                if (file_exists($test_path)) return $test_path;
            }
        }
        
        return null;
    }
}

if (!function_exists('buku_format_size')) {
    function buku_format_size($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}

if (!function_exists('buku_handle_download')) {
    function buku_handle_download($book_id) {
        global $koneksi;
        
        // Cek jika ini request download
        if (isset($_GET['download']) && $_GET['download'] == 'true' && isset($_GET['id']) && $_GET['id'] == $book_id) {
            
            // Query untuk mendapatkan data buku
            $query = "SELECT judul, file_buku_path FROM buku_perpustakaan WHERE id_buku = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $book_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $file_path = buku_find_file($row['file_buku_path'], $book_id);
                
                if ($file_path && file_exists($file_path)) {
                    // Dapatkan MIME type
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($finfo, $file_path);
                    finfo_close($finfo);
                    
                    // Generate safe filename
                    $safe_title = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $row['judul']);
                    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                    $filename = $safe_title . '.' . $extension;
                    
                    // Set headers untuk force download
                    header('Content-Description: File Transfer');
                    header('Content-Type: ' . $mime_type);
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file_path));
                    
                    // Clear buffers
                    ob_clean();
                    flush();
                    
                    // Read file
                    readfile($file_path);
                    
                    // Log download
                    error_log("BUKU DOWNLOAD: " . $row['judul'] . " | ID: " . $book_id . " | IP: " . $_SERVER['REMOTE_ADDR']);
                    
                    exit; // Stop execution setelah download
                }
            }
        }
        // Jika bukan request download, lanjutkan ke halaman normal
    }
}

// ============================================
// INPUT VALIDATION
// ============================================
$clean_get = [];
foreach ($_GET as $key => $value) {
    $clean_get[$key] = buku_sanitize_input($value);
}

$debug_mode = isset($clean_get['debug']) ? (int)$clean_get['debug'] : 0;
$search_query = isset($clean_get['search']) ? $clean_get['search'] : '';
$field_filter = isset($clean_get['field']) ? $clean_get['field'] : '';
$availability_filter = isset($clean_get['availability']) ? $clean_get['availability'] : '';
$year_filter = isset($clean_get['year']) ? $clean_get['year'] : '';
$download_id = isset($clean_get['download_id']) ? (int)$clean_get['download_id'] : 0;

// ============================================
// HANDLE DOWNLOAD REQUEST
// ============================================
if ($download_id > 0 && isset($_GET['action']) && $_GET['action'] == 'download') {
    buku_handle_download($download_id);
}

// ============================================
// DATABASE QUERY
// ============================================
try {
    $where_conditions = ["1=1"];
    
    if (!empty($search_query)) {
        $search_escaped = mysqli_real_escape_string($koneksi, $search_query);
        $where_conditions[] = "(judul LIKE '%$search_escaped%' 
                              OR pengarang LIKE '%$search_escaped%' 
                              OR penerbit LIKE '%$search_escaped%' 
                              OR subjek_topik LIKE '%$search_escaped%'
                              OR isbn LIKE '%$search_escaped%')";
    }
    
    if (!empty($field_filter) && $field_filter !== 'semua') {
        $field_escaped = mysqli_real_escape_string($koneksi, $field_filter);
        $where_conditions[] = "bidang_hukum = '$field_escaped'";
    }
    
    if (!empty($availability_filter) && $availability_filter !== 'semua') {
        $availability_escaped = mysqli_real_escape_string($koneksi, $availability_filter);
        $where_conditions[] = "status_ketersediaan = '$availability_escaped'";
    }
    
    if (!empty($year_filter) && $year_filter !== 'semua') {
        $year_escaped = mysqli_real_escape_string($koneksi, $year_filter);
        $where_conditions[] = "tahun_terbit = '$year_escaped'";
    }
    
    $where_clause = implode(" AND ", $where_conditions);
    
    // Query data
    $query = "SELECT 
                id_buku,
                judul,
                slug,
                pengarang,
                penerbit,
                tahun_terbit,
                isbn,
                subjek_topik,
                bidang_hukum,
                bahasa,
                sinopsis,
                sampul_path,
                file_buku_path,
                status_ketersediaan,
                jumlah_eksemplar,
                jumlah_tersedia,
                tanggal_masuk,
                created_at
              FROM buku_perpustakaan 
              WHERE $where_clause
              ORDER BY id_buku DESC 
              LIMIT 20";
    
    $result = mysqli_query($koneksi, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($koneksi));
    }
    
    $search_results_count = mysqli_num_rows($result);
    
} catch (Exception $e) {
    error_log("Database error in buku.php: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}

// ============================================
// GET ADDITIONAL DATA
// ============================================
$total_query = "SELECT COUNT(*) as total FROM buku_perpustakaan";
$total_result = mysqli_query($koneksi, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_buku = $total_data ? (int)$total_data['total'] : 0;

$years_query = "SELECT DISTINCT tahun_terbit 
                FROM buku_perpustakaan 
                WHERE tahun_terbit IS NOT NULL
                ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
if ($years_result) {
    while ($row = mysqli_fetch_assoc($years_result)) {
        $year = (int)$row['tahun_terbit'];
        if ($year >= 1900 && $year <= 2100) {
            $years[] = $year;
        }
    }
}

$fields_query = "SELECT DISTINCT bidang_hukum 
                 FROM buku_perpustakaan 
                 WHERE bidang_hukum IS NOT NULL AND bidang_hukum != ''
                 ORDER BY bidang_hukum ASC";
$fields_result = mysqli_query($koneksi, $fields_query);
$fields = [];
if ($fields_result) {
    while ($row = mysqli_fetch_assoc($fields_result)) {
        $fields[] = $row['bidang_hukum'];
    }
}

$availability_query = "SELECT DISTINCT status_ketersediaan 
                       FROM buku_perpustakaan 
                       WHERE status_ketersediaan IS NOT NULL
                       ORDER BY status_ketersediaan ASC";
$availability_result = mysqli_query($koneksi, $availability_query);
$availability_statuses = [];
if ($availability_result) {
    while ($row = mysqli_fetch_assoc($availability_result)) {
        $availability_statuses[] = $row['status_ketersediaan'];
    }
}

// ============================================
// SET HEADERS
// ============================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ============================================
// INCLUDE HEADER
// ============================================
$page_title = buku_safe_output("Buku Perpustakaan - JDIH DPRD Kota Surakarta");
include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* Additional styles for buku */
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

.status-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-tersedia {
    background: rgba(40, 167, 69, 0.9);
    color: white;
}

.status-dipinjam {
    background: rgba(255, 193, 7, 0.9);
    color: #212529;
}

.status-hilang {
    background: rgba(220, 53, 69, 0.9);
    color: white;
}

.status-rusak {
    background: rgba(108, 117, 125, 0.9);
    color: white;
}

.availability-info {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 13px;
}

.availability-info span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.advanced-filter {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    border: 1px solid #dee2e6;
}

.filter-group {
    margin-bottom: 15px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
    font-size: 14px;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 5px;
}

.filter-btn {
    padding: 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 20px;
    background: white;
    color: #495057;
    font-size: 13px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.filter-btn:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.filter-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.book-author {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.book-publisher {
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 10px;
}

.file-status {
    font-size: 11px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.file-status.available {
    color: #28a745;
}

.file-status.unavailable {
    color: #dc3545;
}

@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .advanced-filter {
        padding: 15px;
    }
}

.download-processing {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.download-spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">BUKU PERPUSTAKAAN</h1>
        <p class="subtitle">Koleksi Buku Perpustakaan JDIH DPRD Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active">Buku Perpustakaan</li>
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
    <form method="GET" action="" class="d-flex gap-2">
        <div class="flex-grow-1">
            <input type="text" 
                   name="search" 
                   class="form-control form-control-lg" 
                   placeholder="Cari berdasarkan judul, pengarang, penerbit, atau ISBN..." 
                   value="<?= buku_safe_output($search_query) ?>"
                   aria-label="Search books">
        </div>
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($search_query) || !empty($field_filter) || !empty($availability_filter) || !empty($year_filter)): ?>
            <a href="index.php?page=buku" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- ADVANCED FILTER -->
<div class="advanced-filter">
    <div class="row">
        <div class="col-md-4">
            <div class="filter-group">
                <label><i class="bi bi-tags"></i> Bidang Hukum</label>
                <div class="filter-buttons">
                    <button type="button" class="filter-btn <?= empty($field_filter) || $field_filter === 'semua' ? 'active' : '' ?>" 
                            data-filter="field" data-value="semua">Semua</button>
                    <?php foreach ($fields as $field): ?>
                        <button type="button" class="filter-btn <?= $field_filter === $field ? 'active' : '' ?>" 
                                data-filter="field" data-value="<?= buku_safe_output($field) ?>">
                            <?= buku_safe_output($field) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="filter-group">
                <label><i class="bi bi-check-circle"></i> Status Ketersediaan</label>
                <div class="filter-buttons">
                    <button type="button" class="filter-btn <?= empty($availability_filter) || $availability_filter === 'semua' ? 'active' : '' ?>" 
                            data-filter="availability" data-value="semua">Semua</button>
                    <?php foreach ($availability_statuses as $status): ?>
                        <?php 
                        $status_text = '';
                        switch($status) {
                            case 'tersedia': $status_text = 'Tersedia'; break;
                            case 'dipinjam': $status_text = 'Dipinjam'; break;
                            case 'hilang': $status_text = 'Hilang'; break;
                            case 'rusak': $status_text = 'Rusak'; break;
                            default: $status_text = ucfirst($status);
                        }
                        ?>
                        <button type="button" class="filter-btn <?= $availability_filter === $status ? 'active' : '' ?>" 
                                data-filter="availability" data-value="<?= buku_safe_output($status) ?>">
                            <?= buku_safe_output($status_text) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="filter-group">
                <label><i class="bi bi-calendar"></i> Tahun Terbit</label>
                <div class="filter-buttons">
                    <button type="button" class="filter-btn <?= empty($year_filter) || $year_filter === 'semua' ? 'active' : '' ?>" 
                            data-filter="year" data-value="semua">Semua</button>
                    <?php foreach ($years as $year): ?>
                        <button type="button" class="filter-btn <?= $year_filter === $year ? 'active' : '' ?>" 
                                data-filter="year" data-value="<?= buku_safe_output($year) ?>">
                            <?= buku_safe_output($year) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HASIL PENCARIAN -->
<?php if (!empty($search_query) || !empty($field_filter) || !empty($availability_filter) || !empty($year_filter)): ?>
    <h3 class="section-title">Hasil Pencarian 
        <?php if (!empty($search_query)): ?>
            untuk "<?= buku_safe_output($search_query) ?>"
        <?php endif; ?>
        (<?= $search_results_count ?> buku)
    </h3>
<?php else: ?>
    <h3 class="section-title">Koleksi Buku Perpustakaan JDIH</h3>
<?php endif; ?>

<!-- DOWNLOAD PROCESSING OVERLAY -->
<div class="download-processing" id="downloadProcessing">
    <div class="download-spinner"></div>
</div>

<?php if ($search_results_count > 0): ?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)): 
    // Format tanggal masuk
    $tanggal_masuk = !empty($row['tanggal_masuk']) ? date('d F Y', strtotime($row['tanggal_masuk'])) : '-';
    
    // Encode token untuk detail
    $encrypted_id = buku_encode_token($row['id_buku']);
    
    // Cari file
    $file_from_db = $row['file_buku_path'] ?? '';
    $file_found = false;
    $actual_file_path = null;
    
    if (!empty($file_from_db)) {
        $actual_file_path = buku_find_file($file_from_db, $row['id_buku']);
        if ($actual_file_path) {
            $file_found = true;
        }
    }
    
    // Status configuration
    $status_config = [
        'tersedia' => ['class' => 'status-tersedia', 'text' => 'Tersedia'],
        'dipinjam' => ['class' => 'status-dipinjam', 'text' => 'Dipinjam'],
        'hilang' => ['class' => 'status-hilang', 'text' => 'Hilang'],
        'rusak' => ['class' => 'status-rusak', 'text' => 'Rusak']
    ];
    $status = $row['status_ketersediaan'] ?? 'tersedia';
    $status_info = $status_config[$status] ?? ['class' => 'bg-info', 'text' => ucfirst($status)];
    
    // Detail URL
    $detail_url = "index.php?page=detail-buku&token=" . urlencode($encrypted_id);
    
    // Download URL langsung (tanpa redirect ke file terpisah)
    $download_url = "index.php?page=buku&action=download&download_id=" . $row['id_buku'] . "&t=" . time();
?>
<div class="document-card" data-tahun="<?= buku_safe_output($row['tahun_terbit']); ?>" 
     data-field="<?= buku_safe_output($row['bidang_hukum']); ?>" 
     data-status="<?= buku_safe_output($row['status_ketersediaan']); ?>">
    <div class="card-image">
        <?php if (!empty($row['sampul_path'])): 
            $cover_path = $row['sampul_path'];
            $cover_full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($cover_path, '/');
            if (file_exists($cover_full_path)):
        ?>
            <img src="<?= buku_safe_output('/' . ltrim($cover_path, '/')) ?>" 
                 alt="<?= buku_safe_output($row['judul']) ?>"
                 loading="lazy">
        <?php else: ?>
            <div class="placeholder">
                <i class="bi bi-book"></i>
                <p><?= buku_safe_output($row['judul']) ?></p>
            </div>
        <?php endif; else: ?>
            <div class="placeholder">
                <i class="bi bi-book"></i>
                <p><?= buku_safe_output($row['judul']) ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Status Badge -->
        <div class="status-badge <?= $status_info['class'] ?>">
            <?= $status_info['text'] ?>
        </div>
        
        <div class="image-overlay">
            <span class="image-badge"><?= buku_safe_output($row['bidang_hukum'] ?? 'Umum') ?></span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number">ISBN: <?= buku_safe_output($row['isbn'] ?? '-') ?></div>
        <h3 class="card-title"><?= buku_safe_output($row['judul']) ?></h3>
        
        <div class="book-author">
            <i class="bi bi-person"></i> <?= buku_safe_output($row['pengarang']) ?>
        </div>
        
        <div class="book-publisher">
            <i class="bi bi-building"></i> 
            <?= buku_safe_output($row['penerbit'] ?? '-') ?> 
            <?php if (!empty($row['tahun_terbit'])): ?>
                (<?= buku_safe_output($row['tahun_terbit']) ?>)
            <?php endif; ?>
        </div>

        <div class="card-meta">
            <div class="card-date">
                <i class="bi bi-calendar-plus"></i> Masuk: <?= buku_safe_output($tanggal_masuk) ?>
            </div>
            <?php if (!empty($row['bahasa'])): ?>
                <div class="card-status"><?= buku_safe_output($row['bahasa']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Availability Info -->
        <div class="availability-info">
            <span><i class="bi bi-journal"></i> Total: <?= buku_safe_output($row['jumlah_eksemplar'] ?? 0) ?></span>
            <span><i class="bi bi-check-circle"></i> Tersedia: <?= buku_safe_output($row['jumlah_tersedia'] ?? 0) ?></span>
        </div>

        <div class="file-status <?= $file_found ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $file_found ? 'File digital tersedia' : 'File digital tidak tersedia' ?>
        </div>

        <div class="card-actions">
            <a href="<?= buku_safe_output($detail_url) ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
            
            <?php if ($file_found): ?>
                <a href="<?= buku_safe_output($download_url) ?>" 
                   class="card-btn btn-download buku-download-btn" 
                   data-id="<?= $row['id_buku'] ?>"
                   data-title="<?= buku_safe_output($row['judul']) ?>"
                   data-filename="<?= buku_safe_output(basename($actual_file_path)) ?>">
                    <i class="bi bi-download"></i> Download
                </a>
            <?php else: ?>
                <a href="#" 
                   class="card-btn btn-download disabled" 
                   onclick="event.preventDefault(); buku_showFileNotFoundAlert('<?= addslashes(buku_safe_output($row['judul'])) ?>');"
                   title="File digital tidak tersedia">
                    <i class="bi bi-download"></i> Download
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-files"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada buku yang ditemukan.
    </p>
</div>
<?php endif; ?>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">
    <div class="sidebar-menu">
        <h4><i class="bi bi-list"></i> Menu Perpustakaan</h4>
        <ul>
            <li class="active"><a href="#"><i class="bi bi-book"></i> Buku Perpustakaan</a></li>
            <li><a href="?page=katalog"><i class="bi bi-card-checklist"></i> Katalog Buku</a></li>
            <li><a href="?page=peminjaman"><i class="bi bi-calendar-check"></i> Peminjaman</a></li>
            <li><a href="?page=anggota"><i class="bi bi-people"></i> Anggota Perpustakaan</a></li>
        </ul>
    </div>

    <div class="sidebar-menu">
        <h4><i class="bi bi-bar-chart"></i> Statistik Buku</h4>
        <ul>
            <li>Total: <strong><?= $total_buku ?> buku</strong></li>
            <li>Ditampilkan: <strong><?= $search_results_count ?> buku</strong></li>
        </ul>
    </div>
</div>
</div>
</div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// BUKU-SPECIFIC JAVASCRIPT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Filter button handling
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter');
            const filterValue = this.getAttribute('data-value');
            
            const url = new URL(window.location.href);
            url.searchParams.set(filterType, filterValue);
            window.location.href = url.toString();
        });
    });
    
    // Document card filtering
    function buku_applyFilters() {
        const selectedField = '<?= $field_filter ?>';
        const selectedStatus = '<?= $availability_filter ?>';
        const selectedYear = '<?= $year_filter ?>';
        
        document.querySelectorAll(".document-card").forEach(card => {
            let showCard = true;
            
            if (selectedField && selectedField !== 'semua') {
                const cardField = card.getAttribute('data-field');
                if (cardField !== selectedField) {
                    showCard = false;
                }
            }
            
            if (selectedStatus && selectedStatus !== 'semua') {
                const cardStatus = card.getAttribute('data-status');
                if (cardStatus !== selectedStatus) {
                    showCard = false;
                }
            }
            
            if (selectedYear && selectedYear !== 'semua') {
                const cardYear = card.getAttribute('data-tahun');
                if (cardYear !== selectedYear) {
                    showCard = false;
                }
            }
            
            card.style.display = showCard ? "flex" : "none";
        });
    }
    
    if ('<?= $field_filter ?>' || '<?= $availability_filter ?>' || '<?= $year_filter ?>') {
        buku_applyFilters();
    }
    
    // Download button handling - DENGAN AJAX/FETCH
    document.querySelectorAll('.buku-download-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const bookId = this.getAttribute('data-id');
            const bookTitle = this.getAttribute('data-title');
            const filename = this.getAttribute('data-filename');
            const downloadUrl = this.getAttribute('href');
            
            // Tampilkan loading spinner
            const processingOverlay = document.getElementById('downloadProcessing');
            if (processingOverlay) {
                processingOverlay.style.display = 'flex';
            }
            
            // Track click
            buku_trackDownload(bookId, bookTitle, filename);
            
            // Trigger download setelah delay kecil untuk UX
            setTimeout(() => {
                // Download langsung tanpa popup atau redirect ke halaman lain
                window.location.href = downloadUrl;
                
                // Sembunyikan loading setelah 2 detik
                setTimeout(() => {
                    if (processingOverlay) {
                        processingOverlay.style.display = 'none';
                    }
                }, 2000);
                
            }, 500);
        });
    });
    
    // Disabled download button handling
    document.querySelectorAll('.btn-download.disabled').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.document-card');
            const judul = card.querySelector('.card-title').textContent;
            buku_showFileNotFoundAlert(judul);
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
});

// Fungsi untuk menampilkan alert file tidak ditemukan
function buku_showFileNotFoundAlert(judul) {
    const safeJudul = judul.replace(/[<>]/g, '');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File digital untuk buku <strong>${safeJudul}</strong> tidak ditemukan di server.`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File digital untuk buku ${safeJudul} tidak ditemukan di server.`);
    }
}

// Fungsi untuk tracking download
function buku_trackDownload(bookId, bookTitle, filename) {
    console.log('BUKU DOWNLOAD INITIATED:', {
        id: bookId,
        title: bookTitle,
        filename: filename,
        timestamp: new Date().toISOString()
    });
    
    // Optional: Show notification
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Mempersiapkan Download',
            html: `Buku <strong>"${bookTitle}"</strong> sedang disiapkan...`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Optional: Send to analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'book_download_click', {
            'event_category': 'library',
            'event_label': bookTitle,
            'value': 1
        });
    }
    
    // Optional: Send to server log
    fetch('buku-ajax.php?action=track_download', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            book_id: bookId,
            book_title: bookTitle,
            filename: filename,
            ip: '<?= $_SERVER['REMOTE_ADDR'] ?? 'unknown' ?>',
            timestamp: new Date().toISOString()
        })
    }).catch(error => {
        console.error('Download tracking failed:', error);
    });
}

// Handle browser back button after download
window.addEventListener('pageshow', function(event) {
    // Sembunyikan loading overlay jika masih tampil
    const processingOverlay = document.getElementById('downloadProcessing');
    if (processingOverlay && processingOverlay.style.display === 'flex') {
        processingOverlay.style.display = 'none';
    }
});
</script>

<?php 
// Cleanup
if (isset($result) && is_object($result)) mysqli_free_result($result);
if (isset($total_result) && is_object($total_result)) mysqli_free_result($total_result);
if (isset($years_result) && is_object($years_result)) mysqli_free_result($years_result);
if (isset($fields_result) && is_object($fields_result)) mysqli_free_result($fields_result);
if (isset($availability_result) && is_object($availability_result)) mysqli_free_result($availability_result);

mysqli_close($koneksi);
?>
