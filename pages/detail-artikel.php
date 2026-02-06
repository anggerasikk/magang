<?php
/**
 * DETAIL ARTIKEL HUKUM - VERSION FIXED
 * File: detail-artikel.php
 */

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
if (!isset($koneksi) || !$koneksi) {
    include_once "a_db.php";
}

// Functions - Gunakan fungsi dari artikel-hukum.php
if (!function_exists('sanitize_input')) {
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('safe_output')) {
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

// ============================================
// FUNGSI DECODE ID YANG KOMPATIBEL
// ============================================
function decode_article_token($token) {
    // Decode base64
    $decoded = base64_decode($token);
    
    if (!$decoded) {
        return 0;
    }
    
    // Format dari artikel-hukum.php: 'jdih_<id>_<timestamp>'
    $parts = explode('_', $decoded);
    
    if (count($parts) >= 3 && $parts[0] === 'jdih') {
        $id = (int)$parts[1];
        return $id > 0 ? $id : 0;
    }
    
    // Coba format alternatif
    if (is_numeric($decoded)) {
        return (int)$decoded;
    }
    
    return 0;
}

// ============================================
// GET & VALIDATE TOKEN/ID
// ============================================
$id = 0;
$debug_mode = isset($_GET['debug']);

// Cek token dari URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $raw_token = $_GET['token'];
    $token = urldecode($raw_token); // Decode URL encoding dulu
    
    if ($debug_mode) {
        error_log("Token received: " . $raw_token);
        error_log("Token after urldecode: " . $token);
    }
    
    // Coba decode dengan fungsi yang kompatibel
    $id = decode_article_token($token);
    
    if ($debug_mode) {
        error_log("Decoded ID: " . $id);
    }
    
    // Jika masih 0, coba langsung decode base64
    if ($id <= 0) {
        $direct_decode = base64_decode($token);
        if ($debug_mode) {
            error_log("Direct base64 decode: " . $direct_decode);
        }
        
        // Cek jika hasil decode adalah format jdih_X_timestamp
        if ($direct_decode && strpos($direct_decode, 'jdih_') === 0) {
            $parts = explode('_', $direct_decode);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $id = (int)$parts[1];
            }
        }
    }
}

// Debug output
if ($debug_mode) {
    echo "<!-- DEBUG: Final ID: $id -->\n";
}

// Validasi ID
if ($id <= 0) {
    error_log("Invalid ID or token: " . ($_GET['token'] ?? 'none'));
    header("Location: index.php?page=artikel-hukum&error=invalid_token");
    exit;
}

// ============================================
// FUNGSI BANTU FILE - UPDATE DENGAN PATH YANG SAMA
// ============================================

// Gunakan path yang sama dengan artikel-hukum.php
function get_article_base_path() {
    $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $base_path = $document_root . "/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/";
    
    // Fallback paths
    if (!is_dir($base_path)) {
        $possible_paths = [
            $document_root . "/panel_control/uploads/artikel_hukum/",
            $document_root . "/uploads/artikel_hukum/",
            dirname(__DIR__) . "/panel_control/uploads/artikel_hukum/",
            "C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/",
            "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/"
        ];
        
        foreach ($possible_paths as $path) {
            if (is_dir($path)) {
                $base_path = $path;
                break;
            }
        }
    }
    
    return $base_path;
}

// Fungsi untuk menemukan file PDF
function find_article_pdf($filename, $id = null) {
    $base_path = get_article_base_path();
    $pdf_path = $base_path . "pdf/";
    
    if (!is_dir($pdf_path)) {
        @mkdir($pdf_path, 0755, true);
    }
    
    if ($debug_mode = (isset($GLOBALS['debug_mode']) && $GLOBALS['debug_mode'])) {
        error_log("Searching PDF for filename: $filename, ID: $id");
        error_log("PDF path: $pdf_path");
    }
    
    // Cari berdasarkan filename dari database
    if (!empty($filename)) {
        $clean_name = basename($filename);
        $test_path = $pdf_path . $clean_name;
        
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
            'artikel_' . $id . '.pdf',
            'ARTIKEL_' . $id . '.pdf',
            'artikel_' . $id . '.PDF',
        ];
        
        foreach ($patterns as $pattern) {
            $test_path = $pdf_path . $pattern;
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

// Fungsi untuk menemukan gambar
function find_article_image($filename, $id = null) {
    $base_path = get_article_base_path();
    $image_path = $base_path . "images/";
    
    if (!is_dir($image_path)) {
        @mkdir($image_path, 0755, true);
    }
    
    // Cari berdasarkan filename
    if (!empty($filename)) {
        $clean_name = basename($filename);
        $test_path = $image_path . $clean_name;
        
        if (file_exists($test_path) && is_file($test_path) && is_readable($test_path)) {
            $mime = mime_content_type($test_path);
            if (strpos($mime, 'image/') === 0) {
                return $test_path;
            }
        }
    }
    
    // Cari berdasarkan ID
    if ($id) {
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP', 'GIF'];
        foreach ($extensions as $ext) {
            $patterns = [
                $id . '.' . $ext,
                'artikel_' . $id . '.' . $ext,
                'ARTIKEL_' . $id . '.' . $ext,
                'thumbnail_' . $id . '.' . $ext,
                'cover_' . $id . '.' . $ext,
            ];
            
            foreach ($patterns as $pattern) {
                $test_path = $image_path . $pattern;
                if (file_exists($test_path) && is_file($test_path) && is_readable($test_path)) {
                    return $test_path;
                }
            }
        }
    }
    
    // Default image
    $default_path = get_article_base_path() . "../../images/ARTIKEL HUKUM.png";
    if (file_exists($default_path)) {
        return $default_path;
    }
    
    return null;
}

// Fungsi format ukuran file
function format_file_size($bytes) {
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

// Fungsi konversi path ke URL
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

// ============================================
// QUERY DATABASE
// ============================================
$query = "SELECT * FROM artikel_hukum WHERE id_artikel = ? AND UPPER(status) = 'PUBLISHED' LIMIT 1";
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
    
    if ($debug_mode) {
        echo "<!-- DEBUG: No article found with ID: $id -->";
    }
    
    header("Location: index.php?page=artikel-hukum&error=doc_not_found");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// CEK FILE PDF DAN GAMBAR
// ============================================
$pdf_file = $data['file_pdf'] ?? '';
$thumbnail_file = $data['thumbnail'] ?? '';

// Cari file PDF
$actual_pdf_path = find_article_pdf($pdf_file, $data['id_artikel']);
$pdf_found = ($actual_pdf_path && file_exists($actual_pdf_path));
$pdf_url = $pdf_found ? path_to_url($actual_pdf_path) . '?t=' . time() : '';
$file_size = $pdf_found ? format_file_size(filesize($actual_pdf_path)) : '0 MB';

// Cari gambar
$actual_image_path = find_article_image($thumbnail_file, $data['id_artikel']);
$image_found = ($actual_image_path && file_exists($actual_image_path));
$image_url = $image_found ? path_to_url($actual_image_path) : '';

// ============================================
// TAMPILKAN HALAMAN
// ============================================
$page_title = safe_output($data['judul']) . " - JDIH DPRD Kota Surakarta";
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

/* Style khusus untuk artikel */
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
            <li class="breadcrumb-item"><a href="index.php?page=artikel-hukum">Artikel Hukum</a></li>
            <li class="breadcrumb-item active">Detail Artikel</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="index.php?page=artikel-hukum" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info mb-4">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>ID Artikel</th><td><?= $id ?></td></tr>
                    <tr><th>Token</th><td><?= $_GET['token'] ?? 'N/A' ?></td></tr>
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
                        <?= safe_output($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Informasi Artikel Hukum</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-user"></i> Penulis:</strong><br>
                                    <?= safe_output($data['penulis'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-book"></i> Jurnal:</strong><br>
                                    <?= safe_output($data['jurnal'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-calendar"></i> Tahun Terbit:</strong><br>
                                    <?= safe_output($data['tahun_terbit'] ?? '-') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-tag"></i> Jenis Artikel:</strong><br>
                                    <?= safe_output($data['jenis_artikel'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-language"></i> Bahasa:</strong><br>
                                    <?= safe_output($data['bahasa'] ?? '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-gavel"></i> Bidang Hukum:</strong><br>
                                    <?= safe_output($data['bidang_hukum'] ?? '-') ?></p>
                                </div>
                            </div>
                            
                            <?php if(!empty($data['keywords'])): ?>
                            <div class="mt-3">
                                <strong><i class="fas fa-tags"></i> Keywords:</strong><br>
                                <?php 
                                $keywords = explode(',', $data['keywords']);
                                foreach ($keywords as $keyword): ?>
                                    <span class="keyword-badge"><?= safe_output(trim($keyword)) ?></span>
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

            <!-- GAMBAR THUMBNAIL -->
            <?php if($image_found): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-image"></i> THUMBNAIL
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="<?= safe_output($image_url) ?>" 
                             alt="<?= safe_output($data['judul']) ?>"
                             class="img-fluid rounded"
                             style="max-height: 300px;">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ABSTRAK -->
            <?php if(!empty($data['abstrak'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-text"></i> ABSTRAK
                </div>
                <div class="card-body">
                    <div class="abstract-content">
                        <?= nl2br(safe_output($data['abstrak'])) ?>
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
                            <td width="30%">ID Artikel</td>
                            <td><?= safe_output($data['id_artikel']) ?></td>
                        </tr>
                        <tr>
                            <td>ISSN</td>
                            <td><?= safe_output($data['issn'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Volume</td>
                            <td><?= safe_output($data['volume'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Issue</td>
                            <td><?= safe_output($data['issue'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Halaman</td>
                            <td><?= safe_output($data['halaman'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Terbit</td>
                            <td><?= !empty($data['tanggal_terbit']) ? date('d F Y', strtotime($data['tanggal_terbit'])) : '-' ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <?php 
                                $status = $data['status'] ?? '-';
                                $badge_class = '';
                                if ($status == 'Published' || $status == 'PUBLISHED') $badge_class = 'bg-success';
                                elseif ($status == 'Draft') $badge_class = 'bg-warning';
                                elseif ($status == 'Archived') $badge_class = 'bg-secondary';
                                else $badge_class = 'bg-info';
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= safe_output(ucfirst($status)) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dibuat Pada</td>
                            <td><?= !empty($data['created_at']) ? date('d/m/Y H:i', strtotime($data['created_at'])) : '-' ?></td>
                        </tr>
                        <tr>
                            <td>Diupdate Pada</td>
                            <td><?= !empty($data['updated_at']) ? date('d/m/Y H:i', strtotime($data['updated_at'])) : '-' ?></td>
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
                            <h6><?= safe_output(basename($actual_pdf_path)) ?></h6>
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
                                <a href="<?= safe_output($pdf_url) ?>" 
                                   target="_blank" 
                                   download="<?= safe_output(basename($actual_pdf_path)) ?>" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= safe_output($pdf_url) ?>" 
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
                                <p class="mb-2">Dokumen PDF tidak tersedia untuk artikel ini.</p>
                                <hr>
                                <div class="mb-3">
                                    <strong>Informasi Debug:</strong><br>
                                    <small class="text-muted">
                                        Nama file database: <code><?= htmlspecialchars($pdf_file) ?></code><br>
                                        ID Artikel: <code><?= $data['id_artikel'] ?></code>
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
                        <a href="<?= safe_output($pdf_url) ?>" 
                           target="_blank" 
                           download="<?= safe_output(basename($actual_pdf_path)) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= safe_output($pdf_url) ?>" 
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
                        <a href="index.php?page=artikel-hukum" class="btn btn-outline-secondary">
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
                            <span class="badge bg-light text-dark"><?= safe_output(basename($actual_pdf_path)) ?></span>
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

            <!-- INFO ARTIKEL -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Info Artikel</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ID</span>
                            <span class="badge bg-dark"><?= safe_output($data['id_artikel']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tahun</span>
                            <span class="badge bg-warning"><?= safe_output($data['tahun_terbit']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Status</span>
                            <span class="badge bg-<?= strtolower($data['status']) == 'published' ? 'success' : 'warning' ?>">
                                <?= safe_output(ucfirst($data['status'])) ?>
                            </span>
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
                        Pratinjau: <?= safe_output(substr($data['judul'], 0, 50)) ?>...
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="background: #525659;">
                    <iframe 
                        id="pdfViewer"
                        src="<?= safe_output($pdf_url) ?>#view=FitH&toolbar=1&navpanes=0" 
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
                    <a href="<?= safe_output($pdf_url) ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-expand"></i> Buka Fullscreen
                    </a>
                    <!-- Tombol Download di modal -->
                    <a href="<?= safe_output($pdf_url) ?>" 
                       target="_blank" 
                       download="<?= safe_output(basename($actual_pdf_path)) ?>" 
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
?>