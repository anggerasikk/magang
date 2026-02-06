<?php
/**
 * DETAIL MONOGRAFI DPRD
 * File: detail-monografi.php
 * Fungsi: Menampilkan detail dokumen monografi DPRD
 */

// ============================================
// KEAMANAN: ERROR HANDLING
// ============================================
error_reporting(0);
ini_set('display_errors', 0);

// ============================================
// KEAMANAN: SESSION START
// ============================================
session_start();

// ============================================
// KEAMANAN: INCLUDE REQUIRED FILES
// ============================================
include_once "a_db.php";

// Functions
if (!file_exists('a_functions.php')) {
    // Fallback functions
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
} else {
    include_once "a_functions.php";
    
    if (!function_exists('safe_output')) {
        function safe_output($data) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
    
    if (!function_exists('sanitize_input')) {
        function sanitize_input($input) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
    }
}

// ============================================
// KEAMANAN: VALIDASI INPUT
// ============================================
$id = 0;

// Coba dari token
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = sanitize_input($_GET['token']);
    
    // Decode token (sesuai dengan format di list page)
    if (function_exists('decode_id')) {
        $id = decode_id($token);
    } else {
        // Fallback decode
        $decoded = base64_decode($token);
        if ($decoded) {
            $parts = explode('_', $decoded);
            if (count($parts) >= 2 && $parts[0] === 'mono') {
                $id = (int)$parts[1];
            }
        }
    }
} 
// Fallback: ID langsung
elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)sanitize_input($_GET['id']);
}

// ============================================
// VALIDASI ID
// ============================================
if ($id <= 0) {
    header("Location: index.php?page=perundangan");
    exit;
}

// ============================================
// QUERY DATABASE DENGAN PREPARED STATEMENT
// ============================================
$query = "SELECT * FROM monografi WHERE id_monografi = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    error_log("Database prepare error: " . mysqli_error($koneksi));
    die("Database error. Please try again later.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: index.php?page=perundangan");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// TENTUKAN JENIS MONOGRAFI UNTUK NAVIGASI
// ============================================
$jenis_monografi = $data['jenis_monografi'] ?? '';
$back_url = 'index.php?page=perundangan'; // default
$back_label = 'Perundangan'; // default
$breadcrumb_label = 'Detail Dokumen'; // default
$page_title_suffix = ' - JDIH DPRD Kota Surakarta';

// Tentukan halaman kembali berdasarkan jenis monografi
if (stripos($jenis_monografi, 'Risalah') !== false) {
    $back_url = 'index.php?page=risalah';
    $back_label = 'Risalah';
    $breadcrumb_label = 'Risalah';
    $page_title_suffix = ' - Risalah';
}elseif (stripos($jenis_monografi, 'Pokok Pikiran') !== false) {
    $back_url = 'index.php?page=pokok-pikiran';
    $back_label = 'Pokok Pikiran';
    $breadcrumb_label = 'Pokok Pikiran';
    $page_title_suffix = ' - Pokok Pikiran';
} elseif (stripos($jenis_monografi, 'Naskah Akademik') !== false) {
    $back_url = 'index.php?page=naskah-akademik';
    $back_label = 'Naskah Akademik';
    $breadcrumb_label = 'Naskah Akademik';
    $page_title_suffix = ' - Naskah Akademik';
} elseif (stripos($jenis_monografi, 'Dokumen Langka') !== false) {
    $back_url = 'index.php?page=dokumen-langka';
    $back_label = 'Dokumen Langka';
    $breadcrumb_label = 'Dokumen Langka';
    $page_title_suffix = ' - Dokumen Langka';
}elseif (stripos($jenis_monografi, 'Hasil Harmonisasi') !== false) {
    $back_url = 'index.php?page=hasil-harmonisasi';
    $back_label = 'Hasil Harmonisasi';
    $breadcrumb_label = 'Hasil Harmonisasi';
    $page_title_suffix = ' - Hasil Harmonisasi';
}elseif (stripos($jenis_monografi, 'Hasil Fasilitasi') !== false) {
    $back_url = 'index.php?page=hasil-fasilitasi';
    $back_label = 'Hasil Fasilitasi';
    $breadcrumb_label = 'Hasil Fasilitasi';
    $page_title_suffix = ' - Hasil Fasilitasi';
}elseif (stripos($jenis_monografi, 'Penulisan Karya Ilmiah') !== false) {
    $back_url = 'index.php?page=karya-ilmiah';
    $back_label = 'Penulisan Karya Ilmiah';
    $breadcrumb_label = 'Penulisan Karya Ilmiah';
    $page_title_suffix = ' - Penulisan Karya Ilmiah';
}elseif (stripos($jenis_monografi, 'Himpunan Peraturan') !== false || stripos($jenis_monografi, 'Himpunan') !== false) {
    $back_url = 'index.php?page=himpunan';
    $back_label = 'Himpunan Peraturan';
    $breadcrumb_label = 'Himpunan Peraturan';
    $page_title_suffix = ' - Himpunan Peraturan';
} elseif (stripos($jenis_monografi, 'Kajian Raperda') !== false || stripos($jenis_monografi, 'raperda') !== false) {
    $back_url = 'index.php?page=kajian-raperda';
    $back_label = 'Kajian Raperda';
    $breadcrumb_label = 'Kajian Raperda';
    $page_title_suffix = ' - Kajian Raperda';
}

// ============================================
// FUNGSI BANTU
// ============================================
function tgl($tgl) {
    if (empty($tgl) || $tgl == '0000-00-00') return '-';
    
    $bulan = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    );
    
    $english_date = date('d F Y', strtotime($tgl));
    return str_replace(array_keys($bulan), array_values($bulan), $english_date);
}

// ============================================
// KONFIGURASI PATH UNTUK MONOGRAFI
// ============================================
$monografi_pdf_path = "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/";
$monografi_images_path = "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/images/";

// Fungsi pencarian file PDF di server - DIPERBAIKI UNTUK HANDLE NULL
function findPDFFile($filename, $id = null) {
    global $monografi_pdf_path;
    
    // Perbaikan: cek null atau empty dengan aman
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
                    'mime' => $mime_type
                ];
            }
        }
    }
    
    return null;
}

// Fungsi format ukuran file
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Fungsi generate URL file
function generateFileUrl($file_path) {
    // Perbaikan: cek null terlebih dahulu
    if ($file_path === null || empty($file_path)) {
        return '#';
    }
    
    $root = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $root = str_replace('\\', '/', $root);
    $file_path = str_replace('\\', '/', $file_path);
    
    // Jika file ada dalam DOCUMENT_ROOT
    if ($root && strpos($file_path, $root) === 0) {
        $url_path = substr($file_path, strlen($root));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    // Untuk Laragon
    if (strpos($file_path, 'C:/laragon/www') === 0) {
        $url_path = substr($file_path, strlen('C:/laragon/www'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    // Fallback: return path as is
    return $file_path;
}

// ============================================
// PROSES PENCARIAN FILE
// ============================================
$debug_mode = isset($_GET['debug']);
$file_found = false;
$file_info = null;
$file_url = '';
$file_size_formatted = '0 MB';
$file_name_display = '';

// Cari file dari database
$file_from_db = $data['file_pdf'];

if (!empty($file_from_db)) {
    $file_info = findPDFFile($file_from_db, $data['id_monografi']);
    
    if ($file_info) {
        $file_found = true;
        $actual_file_path = $file_info['path'];
        $file_size_formatted = formatFileSize($file_info['size']);
        $file_name_display = basename($actual_file_path);
        
        // Generate URL untuk browser
        $file_url = generateFileUrl($actual_file_path);
        
        // Tambahkan parameter timestamp untuk menghindari cache
        $file_url .= '?t=' . time();
        
        if ($debug_mode) {
            echo "<!-- DEBUG: File found at: $actual_file_path -->";
            echo "<!-- DEBUG: File URL: $file_url -->";
            echo "<!-- DEBUG: Jenis Monografi: $jenis_monografi -->";
            echo "<!-- DEBUG: Back URL: $back_url -->";
        }
    }
}

// ============================================
// INCLUDE HEADER
// ============================================
$page_title = safe_output($data['judul']) . $page_title_suffix;
include "header.php";
?>

<!-- ===============================
     STYLE DETAIL
================================ -->
<style>
/* ========== GLOBAL ========== */
.container {
    max-width: 1200px;
}

.card {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    padding: 15px 20px;
}

.card-body {
    padding: 20px;
}

/* ========== DOCUMENT HEADER ========== */
.document-header {
    background: linear-gradient(135deg, #5eb3a8 0%, #2575fc 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.document-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 10px;
}

.document-subtitle {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 0;
}

.document-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 15px;
    border-radius: 20px;
    display: inline-block;
    margin-top: 10px;
    font-size: 14px;
}

/* ========== METADATA TABLE ========== */
.metadata-table {
    width: 100%;
    border-collapse: collapse;
}

.metadata-table tr {
    border-bottom: 1px solid #dee2e6;
}

.metadata-table tr:last-child {
    border-bottom: none;
}

.metadata-table td {
    padding: 12px 15px;
    vertical-align: top;
}

.metadata-table td:first-child {
    width: 30%;
    font-weight: 600;
    background-color: #f8f9fa;
}

/* ========== FILE INFO ========== */
.file-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.file-icon-large {
    font-size: 48px;
    color: #dc3545;
    margin-bottom: 15px;
}

.download-btn-lg {
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
}

.btn-group .btn {
    padding: 10px 20px;
}

/* ========== BADGES ========== */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #212529;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-secondary {
    background-color: #6c757d !important;
}

/* ========== SIDEBAR ========== */
.sidebar-menu .card {
    margin-bottom: 20px;
}

.list-group-item {
    border: none;
    padding: 12px 15px;
    border-bottom: 1px solid #f8f9fa;
}

.list-group-item:last-child {
    border-bottom: none;
}

/* ========== PREVIEW MODAL ========== */
.modal-xl {
    max-width: 95%;
}

.modal-header {
    background: linear-gradient(135deg, #5eb3a8 0%, #2575fc 100%);
    color: white;
}

.modal-body {
    padding: 0;
    background: #525659;
}

.btn-close-white {
    filter: invert(1);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .document-header {
        padding: 20px;
    }
    
    .document-title {
        font-size: 20px;
    }
    
    .metadata-table td:first-child {
        width: 40%;
    }
    
    .download-btn-lg {
        padding: 10px 20px;
        font-size: 14px;
    }
}

/* ========== BREADCRUMB ========== */
.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 20px;
}

.breadcrumb-item a {
    color: #5eb3a8;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #6c757d;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}

/* ========== BUTTONS ========== */
.btn-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #212529;
}

.btn-light:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.btn-outline-primary {
    color: #5eb3a8;
    border-color: #5eb3a8;
}

.btn-outline-primary:hover {
    background-color: #5eb3a8;
    border-color: #5eb3a8;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

/* ========== ALERTS ========== */
.alert {
    border-radius: 8px;
    padding: 15px 20px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

/* ========== TYPE BADGE ========== */
.type-badge {
    background: rgba(255, 255, 255, 0.3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-left: 10px;
}
</style>

<!-- ===============================
     MAIN CONTENT
================================ -->
<div class="container py-4">
    <!-- BREADCRUMB - DIPERBAIKI SESUAI JENIS MONOGRAFI -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
            <li class="breadcrumb-item"><a href="<?= safe_output($back_url) ?>"><?= safe_output($breadcrumb_label) ?></a></li>
            <li class="breadcrumb-item active">Detail Dokumen</li>
        </ol>
    </nav>

    <!-- BACK BUTTON - DIPERBAIKI SESUAI JENIS MONOGRAFI -->
    <a href="<?= safe_output($back_url) ?>" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke <?= safe_output($back_label) ?>
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <p>ID: <?= $id ?>, Jenis: <?= safe_output($jenis_monografi) ?></p>
        <p>File from DB: <?= safe_output($file_from_db) ?></p>
        <p>Back URL: <?= safe_output($back_url) ?>, Back Label: <?= safe_output($back_label) ?></p>
        <?php if($file_found): ?>
        <p>File ditemukan: <?= safe_output($actual_file_path) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- DOCUMENT HEADER - DITAMBAHKAN JENIS MONOGRAFI -->
    <div class="document-header">
        <h1 class="document-title"><?= safe_output($data['judul']) ?>
            <span class="type-badge"><?= safe_output($jenis_monografi) ?></span>
        </h1>
        <p class="document-subtitle">
            <i class="fas fa-calendar"></i> Tahun <?= safe_output($data['tahun_terbit']) ?>
            <?php if(!empty($data['penulis'])): ?>
            | <i class="fas fa-user"></i> <?= safe_output($data['penulis']) ?>
            <?php endif; ?>
            <?php if(!empty($data['bidang_hukum'])): ?>
            | <i class="fas fa-gavel"></i> <?= safe_output($data['bidang_hukum']) ?>
            <?php endif; ?>
        </p>
        <span class="document-badge">
            <i class="fas fa-book"></i> <?= safe_output($jenis_monografi) ?>
        </span>
    </div>

    <div class="row">
        <!-- MAIN CONTENT -->
        <div class="col-lg-8">
            <!-- METADATA -->
            <div class="card mb-4">
                <div class="card-header fw-bold">INFORMASI DOKUMEN</div>
                <div class="card-body p-0">
                    <table class="metadata-table">
                        <tr>
                            <td>Judul</td>
                            <td><?= safe_output($data['judul']) ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Dokumen</td>
                            <td>
                                <?= safe_output($data['jenis_monografi']) ?>
                                <?php if($jenis_monografi !== $data['jenis_monografi']): ?>
                                <br><small class="text-muted">(Kategori: <?= safe_output($jenis_monografi) ?>)</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Penulis</td>
                            <td><?= safe_output($data['penulis'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Penerbit</td>
                            <td><?= safe_output($data['penerbit'] ?? '-') ?></td>
                        </tr>
                        <?php if(!empty($data['isbn'])): ?>
                        <tr>
                            <td>ISBN</td>
                            <td><?= safe_output($data['isbn']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Tanggal Pembuatan</td>
                            <td><?= tgl($data['tanggal_pembuatan']) ?></td>
                        </tr>
                        <tr>
                            <td>Tahun Terbit</td>
                            <td><?= safe_output($data['tahun_terbit']) ?></td>
                        </tr>
                        <tr>
                            <td>Bidang Hukum</td>
                            <td><?= safe_output($data['bidang_hukum'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Halaman</td>
                            <td><?= safe_output($data['jumlah_halaman'] ?? '-') ?> halaman</td>
                        </tr>
                        <tr>
                            <td>Bahasa</td>
                            <td><?= safe_output($data['bahasa'] ?? 'Indonesia') ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <?php 
                                $status = $data['status'] ?? '-';
                                $badge_class = '';
                                if ($status == 'Tersedia') $badge_class = 'bg-success';
                                elseif ($status == 'Tidak Tersedia') $badge_class = 'bg-danger';
                                elseif ($status == 'Arsip') $badge_class = 'bg-warning';
                                else $badge_class = 'bg-secondary';
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= safe_output($status) ?></span>
                            </td>
                        </tr>
                        <?php if(!empty($data['created_at'])): ?>
                        <tr>
                            <td>Dibuat Tanggal</td>
                            <td><?= tgl($data['created_at']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- DESKRIPSI -->
            <?php if(!empty($data['deskripsi'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">DESKRIPSI</div>
                <div class="card-body">
                    <?= nl2br(safe_output($data['deskripsi'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- DOKUMEN DIGITAL -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-pdf"></i> DOKUMEN DIGITAL
                </div>
                <div class="card-body">
                    <?php if($file_found): ?>
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="file-icon-large">
                                <i class="fas fa-file-pdf text-danger"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><?= safe_output($file_name_display) ?></h6>
                            <p class="text-muted mb-1">
                                <i class="fas fa-hdd"></i> Ukuran: <?= $file_size_formatted ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Diupdate: <?= date('d/m/Y H:i', $file_info['time']) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2">
                                <!-- Tombol Download -->
                                <a href="<?= safe_output($file_url) ?>" 
                                   class="btn btn-success btn-lg"
                                   download="<?= safe_output($file_name_display) ?>">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= safe_output($file_url) ?>" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka
                                    </a>
                                    <?php if($file_found): ?>
                                    <button type="button" class="btn btn-outline-info" onclick="showPreview()">
                                        <i class="fas fa-search"></i> Pratinjau
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">File Tidak Ditemukan!</h5>
                                <p class="mb-2">Dokumen PDF tidak tersedia di server.</p>
                                <?php if($debug_mode): ?>
                                <hr>
                                <div class="mb-3">
                                    <strong>Debug Info:</strong><br>
                                    <small class="text-muted">
                                        File from DB: <?= safe_output($file_from_db) ?><br>
                                        ID: <?= $data['id_monografi'] ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- QUICK ACTIONS - DIPERBAIKI LINK KEMBALI -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($file_found): ?>
                        <a href="<?= safe_output($file_url) ?>" 
                           class="btn btn-success"
                           download="<?= safe_output($file_name_display) ?>">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= safe_output($file_url) ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <button onclick="showPreview()" class="btn btn-info">
                            <i class="fas fa-search-plus"></i> Pratinjau
                        </button>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-download"></i> Download (Tidak Tersedia)
                        </button>
                        <?php endif; ?>
                        <a href="<?= safe_output($back_url) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua <?= safe_output($back_label) ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- FILE INFO -->
            <?php if($file_found): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Informasi File</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file"></i> Nama File</span>
                            <span class="badge bg-light text-dark"><?= safe_output($file_name_display) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-weight-hanging"></i> Ukuran</span>
                            <span class="badge bg-primary"><?= $file_size_formatted ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar"></i> Diupdate</span>
                            <span class="badge bg-light text-dark"><?= date('d/m/Y', $file_info['time']) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- CATEGORY INFO -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="fas fa-folder"></i> Kategori Dokumen</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Jenis</span>
                            <span class="badge bg-info"><?= safe_output($jenis_monografi) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Kategori</span>
                            <span class="badge bg-secondary"><?= safe_output($back_label) ?></span>
                        </li>
                        <li class="list-group-item">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Dokumen ini termasuk dalam kategori <?= safe_output($back_label) ?>
                            </small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- BOOK INFO KHUSUS MONOGRAFI -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Buku</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if(!empty($data['penulis'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Penulis</span>
                            <span class="badge bg-info"><?= safe_output($data['penulis']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['penerbit'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Penerbit</span>
                            <span class="badge bg-secondary"><?= safe_output($data['penerbit']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['jumlah_halaman'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Halaman</span>
                            <span class="badge bg-primary"><?= safe_output($data['jumlah_halaman']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['bahasa'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Bahasa</span>
                            <span class="badge bg-dark"><?= safe_output($data['bahasa']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['isbn'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ISBN</span>
                            <span class="badge bg-warning"><?= safe_output($data['isbn']) ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- HELP CARD -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-question-circle"></i> Bantuan</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">
                        <strong>Masalah download?</strong>
                    </p>
                    <ul class="small">
                        <li>Pastikan koneksi internet stabil</li>
                        <li>Browser mendukung PDF</li>
                        <li>Cek folder Downloads di komputer</li>
                    </ul>
                    <hr>
                    <p class="small mb-0">
                        <i class="fas fa-phone-alt"></i>
                        <strong>Bantuan Teknis:</strong><br>
                        Hubungi Administrator
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PREVIEW MODAL -->
    <?php if($file_found): ?>
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf"></i> 
                        Pratinjau: <?= safe_output(substr($data['judul'], 0, 50)) ?>...
                        <small class="type-badge"><?= safe_output($jenis_monografi) ?></small>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="background: #525659;">
                    <iframe 
                        id="pdfViewer"
                        src="<?= safe_output($file_url) ?>#view=FitH&toolbar=1&navpanes=0" 
                        width="100%" 
                        height="600"
                        frameborder="0"
                        style="border: none;">
                    </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <a href="<?= safe_output($file_url) ?>" 
                       class="btn btn-success"
                       download="<?= safe_output($file_name_display) ?>">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function showPreview() {
        var modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }
    
    document.getElementById('previewModal')?.addEventListener('shown.bs.modal', function () {
        var iframe = document.getElementById('pdfViewer');
        if (iframe) {
            iframe.src = iframe.src;
        }
    });
    </script>
    <?php endif; ?>
</div>

<?php
include "footer.php";
mysqli_close($koneksi);
?>