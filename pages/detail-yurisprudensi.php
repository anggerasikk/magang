<?php
// detail-yurisprudensi.php - VERSION FIXED WITH ENCRYPTED TOKEN

// ============================================
// KEAMANAN: ERROR HANDLING
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// ============================================
// KEAMANAN: SESSION START
// ============================================
session_start();

// ============================================
// KEAMANAN: INCLUDE REQUIRED FILES
// ============================================
// Database
include_once "a_db.php";

// Functions - SAMA dengan yang ada di yurisprudensi.php
if (!function_exists('decode_yurisprudensi_token')) {
    function decode_yurisprudensi_token($token) {
        if (empty($token)) return 0;
        
        // Decode URL encoding dulu
        $token = urldecode($token);
        
        // Decode base64
        $decoded = base64_decode($token);
        
        if (!$decoded) {
            return 0;
        }
        
        // Format: 'jdih_yuri_<id>_<timestamp>'
        $parts = explode('_', $decoded);
        
        if (count($parts) >= 4 && $parts[0] === 'jdih' && $parts[1] === 'yuri') {
            $id = (int)$parts[2];
            return $id > 0 ? $id : 0;
        }
        
        return 0;
    }
}

// ============================================
// GET & VALIDATE TOKEN/ID - HANYA TOKEN SAJA
// ============================================
$id = 0;
$debug_mode = isset($_GET['debug']);

// HANYA TERIMA TOKEN, TIDAK TERIMA ID LANGSUNG
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    
    if ($debug_mode) {
        error_log("Token received: " . $token);
        echo "<!-- DEBUG: Token received: $token -->\n";
    }
    
    $id = decode_yurisprudensi_token($token);
    
    if ($debug_mode) {
        error_log("Decoded ID from token: " . $id);
        echo "<!-- DEBUG: Decoded ID: $id -->\n";
    }
    
    // Jika decode gagal, coba langsung decode base64
    if ($id <= 0) {
        $decoded = base64_decode(urldecode($token));
        if ($debug_mode) {
            error_log("Direct decode: " . $decoded);
        }
        
        if ($decoded && strpos($decoded, 'jdih_yuri_') === 0) {
            $parts = explode('_', $decoded);
            if (isset($parts[2]) && is_numeric($parts[2])) {
                $id = (int)$parts[2];
                if ($debug_mode) {
                    error_log("Extracted ID from decoded string: " . $id);
                }
            }
        }
    }
}

// Validasi ID
if ($id <= 0) {
    if ($debug_mode) {
        echo "<!-- ERROR: Invalid token -->\n";
        echo "<script>alert('Token tidak valid atau sudah kadaluarsa');</script>";
    }
    
    // Redirect ke halaman yurisprudensi dengan error message
    header("Location: yurisprudensi.php?error=invalid_token");
    exit;
}

// ============================================
// FUNGSI BANTU UNTUK FILE
// ============================================
function findYurisprudensiPDF($filename, $id = null) {
    $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $base_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/";
    
    // Fallback paths
    if (!is_dir($base_path)) {
        $possible_paths = [
            $document_root . "/panel_control/uploads/yurisprudensi/",
            $document_root . "/uploads/yurisprudensi/",
            dirname(__DIR__) . "/panel_control/uploads/yurisprudensi/",
            "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/",
            "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/",
            "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/"
        ];
        
        foreach ($possible_paths as $path) {
            if (is_dir($path)) {
                $base_path = $path;
                break;
            }
        }
    }
    
    $debug_mode = isset($GLOBALS['debug_mode']) && $GLOBALS['debug_mode'];
    
    if ($debug_mode) {
        error_log("Searching PDF for filename: $filename, ID: $id");
        error_log("Base path: $base_path");
    }
    
    // Cari berdasarkan filename dari database
    if (!empty($filename)) {
        $clean_name = basename($filename);
        $test_path = $base_path . $clean_name;
        
        if ($debug_mode) error_log("Testing path: $test_path");
        
        if (file_exists($test_path) && is_file($test_path) && is_readable($test_path)) {
            $mime = mime_content_type($test_path);
            if (stripos($mime, 'pdf') !== false) {
                return $test_path;
            }
        }
    }
    
    // Cari berdasarkan ID
    if ($id) {
        $patterns = [
            $id . '.pdf',
            $id . '.PDF',
            'yurisprudensi_' . $id . '.pdf',
            'YURISPRUDENSI_' . $id . '.pdf',
            'putusan_' . $id . '.pdf',
            'PUTUSAN_' . $id . '.pdf',
        ];
        
        foreach ($patterns as $pattern) {
            $test_path = $base_path . $pattern;
            if ($debug_mode) error_log("Testing ID path: $test_path");
            
            if (file_exists($test_path) && is_file($test_path) && is_readable($test_path)) {
                $mime = mime_content_type($test_path);
                if (stripos($mime, 'pdf') !== false) {
                    return $test_path;
                }
            }
        }
    }
    
    return null;
}

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

function path_to_url($physical_path) {
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $physical_path = str_replace('\\', '/', $physical_path);
    
    if (strpos($physical_path, $doc_root) === 0) {
        $url_path = substr($physical_path, strlen($doc_root));
        if (strpos($url_path, '/') !== 0) {
            $url_path = '/' . $url_path;
        }
        return $url_path;
    }
    
    return $physical_path;
}

function safe_date_format($date_string) {
    // Check for null, empty, or invalid date formats
    if (empty($date_string) || $date_string === null || $date_string === '0000-00-00 00:00:00' || $date_string === '0000-00-00') {
        return '-';
    }

    // Try to parse the date
    $timestamp = strtotime($date_string);

    // Check if strtotime failed (returns false for invalid dates)
    if ($timestamp === false) {
        return '-';
    }

    // Format the date
    return date('d/m/Y H:i', $timestamp);
}

function tgl($tgl) {
    if (empty($tgl) || $tgl == '0000-00-00' || $tgl === null) return '-';

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

    $english_date = date('d F Y', strtotime((string)$tgl));
    return str_replace(array_keys($bulan), array_values($bulan), $english_date);
}

// ============================================
// QUERY DATABASE
// ============================================
$query = "SELECT * FROM yurisprudensi WHERE id_yurisprudensi = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Error: Gagal menyiapkan query database");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    
    if ($debug_mode) {
        echo "<!-- ERROR: No yurisprudensi found with ID: $id -->\n";
    }
    
    header("Location: yurisprudensi.php?error=doc_not_found");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// CEK FILE PDF
// ============================================
$pdf_file = $data['lampiran_path'] ?? '';
$actual_pdf_path = findYurisprudensiPDF($pdf_file, $data['id_yurisprudensi']);
$pdf_found = ($actual_pdf_path && file_exists($actual_pdf_path));
$pdf_url = $pdf_found ? path_to_url($actual_pdf_path) . '?t=' . time() : '';
$file_size = $pdf_found ? formatFileSize(filesize($actual_pdf_path)) : '0 MB';

// ============================================
// TAMPILKAN HALAMAN
// ============================================
$page_title = htmlspecialchars($data['judul']) . " - Yurisprudensi JDIH DPRD";
include "header.php";
?>

<!-- STYLE UPDATE UNTUK SERAGAM -->
<style>
/* Style utama dari detail-keputusan */
.card-header.fw-bold {
    background-color: #f8f9fa !important;
    color: #495057 !important;
    border-bottom: 1px solid #dee2e6 !important;
    font-size: 1.1rem !important;
}

.table-bordered td:first-child {
    font-weight: 600;
    background-color: #f8f9fa;
    width: 30%;
}

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}

.btn-group .btn {
    padding: 0.375rem 0.75rem;
}

.file-icon-large i {
    font-size: 4rem;
}

.pdf-preview {
    width: 100%;
    height: 600px;
    border: none;
    background: #525659;
}

.modal-xl {
    max-width: 1200px;
}

/* Style khusus untuk yurisprudensi */
.keyword-badge {
    display: inline-block;
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    margin-right: 5px;
    margin-bottom: 5px;
    font-size: 12px;
}

.abstract-content {
    line-height: 1.8;
    font-size: 16px;
}

/* Shadow untuk card seperti di detail-keputusan */
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}

/* Warna konsisten */
.bg-primary {
    background-color: #0d6efd !important;
}

.bg-success {
    background-color: #198754 !important;
}

.bg-info {
    background-color: #0dcaf0 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

/* Button styles konsisten */
.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}
</style>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="yurisprudensi.php">Yurisprudensi</a></li>
            <li class="breadcrumb-item active">Detail Putusan</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="yurisprudensi.php" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Yurisprudensi
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info mb-4">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>ID Putusan</th><td><?= $id ?></td></tr>
                    <tr><th>Token</th><td><?= htmlspecialchars($_GET['token'] ?? 'N/A') ?></td></tr>
                    <tr><th>PDF Found</th><td><?= $pdf_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Document Root</th><td><code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></td></tr>
                    <tr><th>Current Dir</th><td><code><?= __DIR__ ?></code></td></tr>
                </table>
            </div>
        </div>
        <?php if($pdf_found): ?>
        <table class="table table-sm table-bordered mt-2">
            <tr><th>Actual Path</th><td><code><?= htmlspecialchars($actual_pdf_path) ?></code></td></tr>
            <tr><th>File URL</th><td><code><?= htmlspecialchars($pdf_url) ?></code></td></tr>
            <tr><th>File Size</th><td><?= $file_size ?></td></tr>
        </table>
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
                        <?= htmlspecialchars($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Informasi Putusan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-hashtag"></i> Nomor Putusan:</strong><br>
                                    <?= htmlspecialchars($data['nomor_putusan'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-building"></i> Tempat Peradilan:</strong><br>
                                    <?= htmlspecialchars($data['tempat_peradilan'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-calendar"></i> Tanggal Dibacakan:</strong><br>
                                    <?= tgl($data['tanggal_dibacakan']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-scale-balanced"></i> Jenis Peradilan:</strong><br>
                                    <?= htmlspecialchars($data['jenis_peradilan'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-tag"></i> Status Putusan:</strong><br>
                                    <?= htmlspecialchars($data['status_putusan'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-gavel"></i> Bidang Hukum:</strong><br>
                                    <?= htmlspecialchars($data['bidang_hukum_jenis_perkara'] ?? '-') ?></p>
                                </div>
                            </div>
                            
                            <?php if(!empty($data['kata_kunci'])): ?>
                            <div class="mt-3">
                                <strong><i class="fas fa-tags"></i> Kata Kunci:</strong><br>
                                <?php 
                                $keywords = explode(',', $data['kata_kunci']);
                                foreach ($keywords as $keyword): ?>
                                    <span class="keyword-badge"><?= htmlspecialchars(trim($keyword)) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if($pdf_found): ?>
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

            <!-- DESKRIPSI -->
            <?php if(!empty($data['deskripsi'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-text"></i> DESKRIPSI PUTUSAN
                </div>
                <div class="card-body">
                    <div class="abstract-content">
                        <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ======================
                 METADATA DETAIL
            ====================== -->
            <div class="card mb-4">
                <div class="card-header fw-bold">METADATA DETAIL</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <td width="30%">ID Putusan</td>
                            <td><?= htmlspecialchars($data['id_yurisprudensi']) ?></td>
                        </tr>
                        <tr>
                            <td>Tipe Dokumen</td>
                            <td><?= htmlspecialchars($data['tipe_dokumen'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Singkatan Jenis Peradilan</td>
                            <td><?= htmlspecialchars($data['singkatan_jenis_peradilan'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Sumber</td>
                            <td><?= htmlspecialchars($data['sumber'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Subjek</td>
                            <td><?= htmlspecialchars($data['subjek'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Bahasa</td>
                            <td><?= htmlspecialchars($data['bahasa'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Tahun Putusan</td>
                            <td><?= htmlspecialchars($data['tahun_putusan'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>TEU Badan</td>
                            <td><?= htmlspecialchars($data['teu_badan'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td><?= htmlspecialchars($data['lokasi'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Dibuat Pada</td>
                            <td><?= safe_date_format($data['created_at']) ?></td>
                        </tr>
                        <tr>
                            <td>Diupdate Pada</td>
                            <td><?= safe_date_format($data['updated_at']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ======================
                 DOKUMEN DIGITAL
            ====================== -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-pdf"></i> DOKUMEN DIGITAL
                </div>
                <div class="card-body">
                    <?php if($pdf_found): ?>
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="file-icon-large">
                                <i class="fas fa-file-pdf text-danger" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><?= htmlspecialchars(basename($actual_pdf_path)) ?></h6>
                            <p class="text-muted mb-1">
                                <i class="fas fa-hdd"></i> Ukuran: <?= $file_size ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Diupdate: <?= date('d/m/Y H:i', filemtime($actual_pdf_path)) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2">
                                <!-- Tombol Download dengan atribut download -->
                                <a href="<?= htmlspecialchars($pdf_url) ?>" 
                                   target="_blank" 
                                   download="<?= htmlspecialchars(basename($actual_pdf_path)) ?>" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= htmlspecialchars($pdf_url) ?>" 
                                       target="_blank"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#previewModal">
                                        <i class="fas fa-search"></i> Pratinjau
                                    </button>
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
                                <p class="mb-2">Dokumen PDF tidak tersedia untuk putusan ini.</p>
                                <hr>
                                <div class="mb-3">
                                    <strong>Informasi Debug:</strong><br>
                                    <small class="text-muted">
                                        Nama file database: <code><?= htmlspecialchars($pdf_file) ?></code><br>
                                        ID Putusan: <code><?= $data['id_yurisprudensi'] ?></code>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="?token=<?= urlencode($_GET['token'] ?? '') ?>&debug=1" class="btn btn-warning btn-sm">
                                        <i class="fas fa-bug"></i> Debug Mode
                                    </a>
                                </div>
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
                        <?php if($pdf_found): ?>
                        <!-- Tombol Download di sidebar -->
                        <a href="<?= htmlspecialchars($pdf_url) ?>" 
                           target="_blank" 
                           download="<?= htmlspecialchars(basename($actual_pdf_path)) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= htmlspecialchars($pdf_url) ?>" 
                           target="_blank"
                           class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <button type="button" 
                                class="btn btn-info" 
                                data-bs-toggle="modal" 
                                data-bs-target="#previewModal">
                            <i class="fas fa-search-plus"></i> Pratinjau
                        </button>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-download"></i> Download (Tidak Tersedia)
                        </button>
                        <?php endif; ?>
                        <a href="yurisprudensi.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>

            <!-- FILE INFO -->
            <?php if($pdf_found): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Informasi File</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file"></i> Nama File</span>
                            <span class="badge bg-light text-dark"><?= htmlspecialchars(basename($actual_pdf_path)) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-weight-hanging"></i> Ukuran</span>
                            <span class="badge bg-primary"><?= $file_size ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar"></i> Diupdate</span>
                            <span class="badge bg-light text-dark"><?= date('d/m/Y', filemtime($actual_pdf_path)) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf"></i> Format</span>
                            <span class="badge bg-danger">PDF</span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- INFO PERADILAN -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-scale-balanced"></i> Info Peradilan</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ID</span>
                            <span class="badge bg-dark"><?= htmlspecialchars($data['id_yurisprudensi']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tahun</span>
                            <span class="badge bg-warning"><?= htmlspecialchars($data['tahun_putusan']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Status</span>
                            <span class="badge bg-info"><?= htmlspecialchars($data['status_putusan'] ?? '-') ?></span>
                        </li>
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
    <?php if($pdf_found): ?>
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf"></i> 
                        Pratinjau: <?= htmlspecialchars(substr($data['judul'], 0, 50)) ?>...
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="background: #525659;">
                    <iframe 
                        id="pdfViewer"
                        src="<?= htmlspecialchars($pdf_url) ?>#view=FitH&toolbar=1&navpanes=0" 
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
                    <a href="<?= htmlspecialchars($pdf_url) ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-expand"></i> Buka Fullscreen
                    </a>
                    <!-- Tombol Download di modal -->
                    <a href="<?= htmlspecialchars($pdf_url) ?>" 
                       target="_blank" 
                       download="<?= htmlspecialchars(basename($actual_pdf_path)) ?>" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if($pdf_found): ?>
<script>
// Refresh iframe saat modal dibuka
document.getElementById('previewModal')?.addEventListener('shown.bs.modal', function () {
    var iframe = document.getElementById('pdfViewer');
    if (iframe) {
        iframe.src = iframe.src;
    }
});
</script>
<?php endif; ?>

<?php
include "footer.php";
mysqli_close($koneksi);
?>