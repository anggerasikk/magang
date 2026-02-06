<?php
/**
 * DETAIL KEPUTUSAN DPRD
 * File: detail_keputusan.php
 * Fungsi: Menampilkan detail dokumen keputusan DPRD
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
// Database
include_once "a_db.php";

// Functions - PASTIKAN SAMA DENGAN KEPUTUSAN-DPRD.PHP
if (!file_exists('a_functions.php')) {
    // Fallback functions - HARUS SAMA PERSIS
    function decode_id($token) {
        if (empty($token) || !is_string($token)) {
            return 0;
        }
        
        // Decode base64
        $decoded = base64_decode($token);
        if (!$decoded) {
            // Coba decode dengan URL-safe
            $decoded = base64_decode(strtr($token, '-_', '+/'));
            if (!$decoded) {
                return 0;
            }
        }
        
        // Format: jdih_ID_timestamp (format dari keputusan-dprd.php)
        $parts = explode('_', $decoded);
        if (count($parts) >= 2 && $parts[0] === 'jdih') {
            return (int)$parts[1];
        }
        
        return 0;
    }
    
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
} else {
    include_once "a_functions.php";
    
    // Pastikan fungsi-fungsi ada
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
// KEAMANAN: GET & VALIDATE TOKEN/ID
// ============================================
$id = 0;

// Coba dari token terlebih dahulu
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = sanitize_input($_GET['token']);
    
    // DEBUG: Tampilkan token
    if (isset($_GET['debug'])) {
        echo "<!-- DEBUG: Received Token: $token -->";
    }
    
    $id = decode_id($token);
    
    if (isset($_GET['debug'])) {
        echo "<!-- DEBUG: Decoded ID: $id -->";
    }
    
    // Jika decode gagal, coba format lain
    if ($id <= 0) {
        // Coba decode sebagai base64 langsung
        $decoded = base64_decode($token);
        if ($decoded && is_numeric($decoded)) {
            $id = (int)$decoded;
        }
    }
} 
// Fallback: Coba dari ID langsung (untuk compatibility)
elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)sanitize_input($_GET['id']);
}

// ============================================
// VALIDASI ID
// ============================================
if ($id <= 0) {
    // Redirect ke halaman utama dengan error message
    header("Location: index.php?page=keputusan-dprd&error=invalid_token");
    exit;
}

// ============================================
// QUERY DATABASE DENGAN PREPARED STATEMENT
// ============================================
$query = "SELECT * FROM uploads WHERE idData = ? LIMIT 1";
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
    header("Location: index.php?page=keputusan-dprd&error=doc_not_found");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// FUNGSI BANTU - DIPERBAIKI
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

// Fungsi pencarian file yang lebih COMPREHENSIVE - DIPERBAIKI
function findPDFFile($filename, $id = null) {
    if (empty($filename)) {
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
            'C:/xampp/htdocs/jdihcilacap_pasang/control_panel/uploads/pdf/' . $clean_filename,
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
                    'C:/xampp/htdocs/jdihcilacap_pasang/control_panel/uploads/pdf/' . $test_filename,
                    
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

// Fungsi generate URL file - DIPERBAIKI
function generateFileUrl($file_path) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $root = str_replace('\\', '/', $root);
    $file_path = str_replace('\\', '/', $file_path);
    
    // Jika file ada dalam DOCUMENT_ROOT
    if (strpos($file_path, $root) === 0) {
        $url_path = substr($file_path, strlen($root));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    // Untuk path Laragon - case insensitive
    if (stripos($file_path, 'C:/laragon/www') === 0) {
        $url_path = substr($file_path, strlen('C:/laragon/www'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    // Untuk path XAMPP
    if (stripos($file_path, 'C:/xampp/htdocs') === 0) {
        $url_path = substr($file_path, strlen('C:/xampp/htdocs'));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    // Untuk path XAMPP 8
    if (stripos($file_path, 'C:/xampp8/htdocs') === 0) {
        $url_path = substr($file_path, strlen('C:/xampp8/htdocs'));
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
$file_from_db = !empty($data['pdf_path']) ? $data['pdf_path'] : $data['fileDownload'];

if (!empty($file_from_db)) {
    $file_info = findPDFFile($file_from_db, $data['idData']);
    
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
        }
    }
}

// ============================================
// BREADCRUMB & BACK LINK DINAMIS
// ============================================
$breadcrumb_page  = 'keputusan-dprd';
$breadcrumb_label = 'Keputusan_DPRD';

switch ($data['jenis']) {

    case 'Keputusan_Pimpinan_DPRD':
        $breadcrumb_page  = 'keputusan-pimpinan';
        $breadcrumb_label = 'Keputusan_Pimpinan_DPRD';
        break;

    case 'Keputusan_Sekretariat_DPRD':
        $breadcrumb_page  = 'keputusan-sekretariat';
        $breadcrumb_label = 'Keputusan_Sekretaris_DPRD';
        break;

    case 'Peraturan_DPRD':
        $breadcrumb_page  = 'peraturan-dprd';
        $breadcrumb_label = 'Peraturan_DPRD';
        break;

    case 'Peraturan_Daerah':
        $breadcrumb_page  = 'perda';
        $breadcrumb_label = 'Peraturan_Daerah';
        break;

    case 'English_Legal_Product':
        $breadcrumb_page  = 'english-legal';
        $breadcrumb_label = 'English_Legal_Product';
}

$back_page  = $breadcrumb_page;
$back_label = $breadcrumb_label;


// ============================================
// INCLUDE HEADER
// ============================================
$page_title = safe_output($data['judul']) . " - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=<?= $breadcrumb_page ?>"><?= $breadcrumb_label ?></a></li>
            <li class="breadcrumb-item active"><a href="">Detail Dokumen</a></li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="index.php?page=<?= $back_page ?>" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke <?= $back_label ?>
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <p>ID: <?= $id ?>, Token: <?= $_GET['token'] ?? 'N/A' ?></p>
        <?php if($file_found): ?>
        <p>File ditemukan: <?= htmlspecialchars($actual_file_path) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-9">
            <!-- HEADER -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt"></i> 
                        <?= safe_output($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary"><?= safe_output($data['noPeraturan']) ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Tanggal Penetapan: <?= tgl($data['tanggal_penetapan']) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if($file_found): ?>
                            <span class="badge bg-success fs-6 p-2">
                                <i class="fas fa-check-circle"></i> File Tersedia
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger fs-6 p-2">
                                <i class="fas fa-exclamation-circle"></i> File Tidak Ditemukan
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- METADATA -->
            <div class="card mb-4">
                <div class="card-header fw-bold">METADATA</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tr><td width="30%">Bahasa</td><td>Bahasa Indonesia</td></tr>
                        <tr><td>Tipe Dokumen</td><td><?= safe_output($data['singkatanJenis']) ?></td></tr>
                        <tr><td>Judul</td><td><?= safe_output($data['judul']) ?></td></tr>
                        <tr><td>Nomor Peraturan</td><td><?= safe_output($data['noPeraturan']) ?></td></tr>
                        <tr><td>Tanggal Penetapan</td><td><?= tgl($data['tanggal_penetapan']) ?></td></tr>
                        <tr><td>Tanggal Pengundangan</td><td><?= tgl($data['tanggal_pengundangan']) ?></td></tr>
                        <tr><td>Tahun</td><td><?= safe_output($data['tahun_pengundangan']) ?></td></tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <?php 
                                $status = $data['status'] ?? '-';
                                $badge_class = '';
                                if ($status == 'Berlaku') $badge_class = 'bg-success';
                                elseif ($status == 'Tidak Berlaku') $badge_class = 'bg-danger';
                                elseif ($status == 'Dicabut') $badge_class = 'bg-warning';
                                else $badge_class = 'bg-secondary';
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= safe_output($status) ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

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
                                <i class="fas fa-file-pdf text-danger" style="font-size: 4rem;"></i>
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
                                <i class="fas fa-exclamation-triangle fa-3x"></i>
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
                                        ID: <?= $data['idData'] ?>
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
        <div class="col-lg-3">
            <!-- QUICK ACTIONS -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
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
                        <a href="index.php?page=<?= $back_page ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua
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