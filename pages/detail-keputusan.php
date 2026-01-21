<?php
/**
 * DETAIL YURISPRUDENSI
 * File: detail-yurisprudensi.php
 * Fungsi: Menampilkan detail lengkap putusan yurisprudensi
 * Desain: Mirip dengan detail_keputusan.php
 */

session_start();

// ============================================
// KONEKSI DATABASE
// ============================================
$host = "localhost";
$username = "root";
$password = "";
$database = "jdihdprd";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");

// ============================================
// VALIDASI ID
// ============================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: yurisprudensi.php");
    exit;
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

function findDocumentFile($data) {
    $file_fields = ['lampiran_path', 'fileDownload', 'file_path', 'fileUpload', 'file_name', 'pdf_path'];
    
    foreach ($file_fields as $field) {
        if (!empty($data[$field]) && trim($data[$field]) !== '') {
            $path = trim($data[$field]);
            $path = preg_replace('/\?.*$/', '', $path);
            return $path;
        }
    }
    return null;
}

function findFileOnServer($filename) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    $base_dir = dirname(__DIR__);
    
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    $filename_variations = [$filename, $clean_filename];
    
    $no_spaces = str_replace([' ', '(', ')', '[', ']'], ['_', '', '', '', ''], $clean_filename);
    if ($no_spaces !== $clean_filename) {
        $filename_variations[] = $no_spaces;
    }
    
    $possible_locations = [];
    
    $base_dirs = [
        $root,
        $base_dir,
        $current_dir,
        'C:/xampp/htdocs',
        'C:/xampp/htdocs/jdihcilacap_pasang',
        '/var/www/html'
    ];
    
    $sub_dirs = [
        '/uploads/yurisprudensi/pdf',
        '/uploads/yurisprudensi',
        '/uploads/pdf',
        '/uploads',
        '/wb_admin/uploads/yurisprudensi/pdf',
        '/wb_admin/uploads/yurisprudensi',
        '/wb_admin/uploads/pdf',
        '/wb_admin/uploads',
        '/admin/uploads/yurisprudensi/pdf',
        '/admin/uploads/yurisprudensi',
        '/admin/uploads/pdf',
        '/admin/uploads',
        '/jdih/uploads/yurisprudensi/pdf',
        '/jdih/uploads/yurisprudensi',
        '/jdih/uploads/pdf',
        '/jdih/uploads'
    ];
    
    foreach ($base_dirs as $base) {
        foreach ($sub_dirs as $sub) {
            foreach ($filename_variations as $variation) {
                $possible_locations[] = $base . $sub . '/' . $variation;
                
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $possible_locations[] = str_replace('/', '\\', $base . $sub . '\\' . $variation);
                }
            }
        }
    }
    
    $special_locations = [
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/yurisprudensi/pdf/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/yurisprudensi/' . $filename,
        'C:/xampp/htdocs/uploads/yurisprudensi/pdf/' . $filename,
        'C:/xampp/htdocs/uploads/yurisprudensi/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/pdf/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/' . $filename,
        
        '/var/www/html/jdihcilacap_pasang/uploads/yurisprudensi/pdf/' . $filename,
        '/var/www/html/jdihcilacap_pasang/uploads/yurisprudensi/' . $filename,
        '/var/www/html/uploads/yurisprudensi/pdf/' . $filename,
        '/var/www/html/uploads/yurisprudensi/' . $filename,
        '/var/www/html/jdihcilacap_pasang/uploads/pdf/' . $filename,
        '/var/www/html/jdihcilacap_pasang/uploads/' . $filename,
        
        $current_dir . '/uploads/yurisprudensi/pdf/' . $filename,
        $current_dir . '/uploads/yurisprudensi/' . $filename,
        $current_dir . '/uploads/pdf/' . $filename,
        $current_dir . '/uploads/' . $filename,
        $current_dir . '/../uploads/yurisprudensi/pdf/' . $filename,
        $current_dir . '/../uploads/yurisprudensi/' . $filename,
        $current_dir . '/../uploads/pdf/' . $filename,
        $current_dir . '/../uploads/' . $filename,
    ];
    
    $possible_locations = array_merge($possible_locations, $special_locations);
    $possible_locations = array_unique($possible_locations);
    
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if (stripos($mime_type, 'pdf') !== false || stripos($mime_type, 'application/pdf') !== false) {
                return [
                    'path' => $location,
                    'size' => filesize($location),
                    'time' => filemtime($location),
                    'mime' => $mime_type
                ];
            }
        }
    }
    
    return findSimilarFile($filename, 'yurisprudensi');
}

function findSimilarFile($filename, $type = 'yurisprudensi') {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $search_dirs = [
        $root . '/uploads/' . $type,
        $root . '/uploads/' . $type . '/pdf',
        $root . '/wb_admin/uploads/' . $type,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/' . $type,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/' . $type . '/pdf'
    ];
    
    $clean_search = preg_replace('/[^a-z0-9]/i', '', strtolower($filename));
    
    foreach ($search_dirs as $dir) {
        if (!is_dir($dir)) continue;
        
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $clean_file = preg_replace('/[^a-z0-9]/i', '', strtolower($file));
            
            if ($clean_search === $clean_file) {
                $full_path = $dir . '/' . $file;
                if (is_readable($full_path)) {
                    return [
                        'path' => $full_path,
                        'size' => filesize($full_path),
                        'time' => filemtime($full_path),
                        'mime' => mime_content_type($full_path)
                    ];
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

function generateFileUrl($file_path) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $root = str_replace('\\', '/', $root);
    $file_path = str_replace('\\', '/', $file_path);
    
    if (strpos($file_path, $root) === 0) {
        $url_path = substr($file_path, strlen($root));
        return $url_path;
    }
    
    if (strpos($file_path, 'C:/xampp/htdocs') === 0) {
        $url_path = substr($file_path, strlen('C:/xampp/htdocs'));
        return $url_path;
    }
    
    if (strpos($file_path, '/var/www/html') === 0) {
        $url_path = substr($file_path, strlen('/var/www/html'));
        return $url_path;
    }
    
    return $file_path;
}

// ============================================
// AMBIL DATA YURISPRUDENSI
// ============================================
$query = "SELECT 
            id_yurisprudensi,
            tipe_dokumen,
            judul,
            slug,
            teu_badan,
            nomor_putusan,
            jenis_peradilan,
            singkatan_jenis_peradilan,
            tempat_peradilan,
            tanggal_dibacakan,
            sumber,
            subjek,
            status_putusan,
            bahasa,
            bidang_hukum_jenis_perkara,
            lokasi,
            cover_path,
            lampiran_path,
            tahun_putusan,
            kata_kunci,
            deskripsi,
            created_at,
            updated_at
          FROM yurisprudensi 
          WHERE id_yurisprudensi = ? 
          LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query);
if (!$stmt) {
    die("Error: Gagal menyiapkan query database");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: yurisprudensi.php");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// PROSES PENCARIAN FILE
// ============================================
$debug_mode = isset($_GET['debug']);
$file_found = false;
$file_info = null;
$file_url = '';
$file_size_formatted = '0 MB';
$file_name_display = '';

$file_path_db = findDocumentFile($data);

if (!empty($file_path_db)) {
    $file_name_only = basename($file_path_db);
    $file_info = findFileOnServer($file_name_only);
    
    if ($file_info) {
        $file_found = true;
        $actual_file_path = $file_info['path'];
        $file_size_formatted = formatFileSize($file_info['size']);
        $file_name_display = basename($actual_file_path);
        
        $file_url = generateFileUrl($actual_file_path);
        $file_url .= '?t=' . time();
    }
}

// ============================================
// SET PAGE HEADER
// ============================================
$page_title = $data['judul'] . " - Yurisprudensi JDIH DPRD Kota Surakarta";
include "header.php";
?>

<!-- ============================================
     STYLE KHUSUS (Disesuaikan dengan detail_keputusan)
============================================ -->
<style>
/* Style mengikuti detail_keputusan.php */
.table-detail td {
    padding: 8px 12px;
    vertical-align: top;
}

.table-detail tr:nth-child(even) {
    background-color: #f8f9fa;
}

.badge-status {
    font-size: 0.85em;
    padding: 5px 10px;
}

.file-icon-large {
    font-size: 4rem;
    color: #dc3545;
}

.btn-download-custom {
    padding: 10px 20px;
    font-weight: 600;
}

.preview-modal iframe {
    width: 100%;
    height: 600px;
    border: none;
}

.sidebar-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.sidebar-card .card-header {
    padding: 12px 15px;
    font-weight: 600;
}

.sidebar-card .card-body {
    padding: 15px;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 10px 0;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 768px) {
    .file-icon-large {
        font-size: 3rem;
        margin-bottom: 10px;
    }
    
    .btn-download-custom {
        width: 100%;
        margin-bottom: 10px;
    }
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
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Yurisprudensi
    </a>

    <!-- DEBUG INFO -->
    <?php if($debug_mode): ?>
    <div class="alert alert-info mb-4">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Database Path</th><td><code><?= htmlspecialchars($file_path_db ?? 'NULL') ?></code></td></tr>
                    <tr><th>Filename</th><td><code><?= htmlspecialchars($file_name_only ?? 'N/A') ?></code></td></tr>
                    <tr><th>File Found</th><td><?= $file_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Document Root</th><td><code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></td></tr>
                    <tr><th>Current Dir</th><td><code><?= __DIR__ ?></code></td></tr>
                    <tr><th>Putusan ID</th><td><?= $id ?></td></tr>
                </table>
            </div>
        </div>
        <?php if($file_found): ?>
        <table class="table table-sm table-bordered mt-2">
            <tr><th>Actual Path</th><td><code><?= htmlspecialchars($file_info['path']) ?></code></td></tr>
            <tr><th>File URL</th><td><code><?= htmlspecialchars($file_url) ?></code></td></tr>
            <tr><th>File Size</th><td><?= $file_size_formatted ?></td></tr>
            <tr><th>MIME Type</th><td><?= $file_info['mime'] ?></td></tr>
        </table>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- KONTEN UTAMA -->
        <div class="col-lg-9">
            <!-- HEADER PUTUSAN -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-gavel"></i> 
                        <?= htmlspecialchars($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary"><?= htmlspecialchars($data['nomor_putusan']) ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Tanggal Dibacakan: <?= tgl($data['tanggal_dibacakan']) ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-balance-scale"></i> 
                                Jenis Peradilan: <?= htmlspecialchars($data['jenis_peradilan']) ?>
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

            <!-- ============================================
                 METADATA PUTUSAN
            ============================================ -->
            <div class="card mb-4">
                <div class="card-header fw-bold">METADATA PUTUSAN</div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-detail mb-0">
                        <tr>
                            <td width="35%"><strong>Jenis Peradilan</strong></td>
                            <td><?= htmlspecialchars($data['jenis_peradilan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Singkatan Jenis Peradilan</strong></td>
                            <td><?= htmlspecialchars($data['singkatan_jenis_peradilan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tempat Peradilan</strong></td>
                            <td><?= htmlspecialchars($data['tempat_peradilan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Putusan</strong></td>
                            <td><?= htmlspecialchars($data['nomor_putusan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Dibacakan</strong></td>
                            <td><?= tgl($data['tanggal_dibacakan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tahun Putusan</strong></td>
                            <td><?= htmlspecialchars($data['tahun_putusan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Bidang Hukum/Jenis Perkara</strong></td>
                            <td><?= htmlspecialchars($data['bidang_hukum_jenis_perkara']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Subjek</strong></td>
                            <td><?= htmlspecialchars($data['subjek']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status Putusan</strong></td>
                            <td>
                                <?php 
                                $status = $data['status_putusan'] ?? '-';
                                $badge_class = 'bg-secondary';
                                if (stripos($status, 'Tetap') !== false) $badge_class = 'bg-success';
                                elseif (stripos($status, 'Kasasi') !== false) $badge_class = 'bg-warning';
                                elseif (stripos($status, 'Peninjauan') !== false) $badge_class = 'bg-info';
                                elseif (stripos($status, 'Provisionil') !== false || stripos($status, 'Sementara') !== false) $badge_class = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge_class ?> badge-status"><?= $status ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Bahasa</strong></td>
                            <td><?= htmlspecialchars($data['bahasa']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>TEU Badan</strong></td>
                            <td><?= htmlspecialchars($data['teu_badan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Lokasi</strong></td>
                            <td><?= htmlspecialchars($data['lokasi']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sumber</strong></td>
                            <td><?= htmlspecialchars($data['sumber']) ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Dokumen</strong></td>
                            <td><?= htmlspecialchars($data['tipe_dokumen']) ?></td>
                        </tr>
                        <?php if(!empty($data['kata_kunci'])): ?>
                        <tr>
                            <td><strong>Kata Kunci</strong></td>
                            <td>
                                <?php 
                                $keywords = explode(',', $data['kata_kunci']);
                                foreach($keywords as $keyword): 
                                    $keyword = trim($keyword);
                                    if(!empty($keyword)):
                                ?>
                                <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars($keyword) ?></span>
                                <?php endif; endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- ============================================
                 DESKRIPSI PUTUSAN
            ============================================ -->
            <?php if(!empty($data['deskripsi'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">DESKRIPSI PUTUSAN</div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ============================================
                 DOKUMEN DIGITAL
            ============================================ -->
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
                            <h6><?= htmlspecialchars($file_name_display) ?></h6>
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
                                <a href="<?= htmlspecialchars($file_url) ?>" 
                                   target="_blank" 
                                   download="<?= htmlspecialchars($file_name_display) ?>" 
                                   class="btn btn-success btn-lg btn-download-custom">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= htmlspecialchars($file_url) ?>" 
                                       target="_blank" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-info" 
                                            onclick="showPreview()">
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
                                <p class="mb-2">Dokumen PDF tidak tersedia di server.</p>
                                <hr>
                                <div class="mb-3">
                                    <strong>Informasi Debug:</strong><br>
                                    <small class="text-muted">
                                        Path database: <code><?= htmlspecialchars($file_path_db ?? 'NULL') ?></code><br>
                                        Nama file: <code><?= htmlspecialchars($file_name_only ?? 'N/A') ?></code>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="?id=<?= $id ?>&debug=1" class="btn btn-warning btn-sm">
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

        <!-- ============================================
             SIDEBAR
        ============================================ -->
        <div class="col-lg-3">
            <!-- QUICK ACTIONS -->
            <div class="card sidebar-card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($file_found): ?>
                        <a href="<?= htmlspecialchars($file_url) ?>" 
                           target="_blank" 
                           download="<?= htmlspecialchars($file_name_display) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= htmlspecialchars($file_url) ?>" 
                           target="_blank" 
                           class="btn btn-primary">
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
                        <a href="yurisprudensi.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="admin/edit-yurisprudensi.php?id=<?= $id ?>" class="btn btn-outline-warning">
                            <i class="fas fa-edit"></i> Edit Putusan
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- FILE INFO -->
            <?php if($file_found): ?>
            <div class="card sidebar-card mb-3 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Informasi File</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file"></i> Nama File</span>
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($file_name_display) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-weight-hanging"></i> Ukuran</span>
                            <span class="badge bg-primary"><?= $file_size_formatted ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar"></i> Diupdate</span>
                            <span class="badge bg-light text-dark"><?= date('d/m/Y', $file_info['time']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf"></i> Format</span>
                            <span class="badge bg-danger">PDF</span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- INFORMASI PERADILAN -->
            <div class="card sidebar-card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-balance-scale"></i> Informasi Peradilan</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="mb-2">
                            <i class="fas fa-building fa-2x text-primary"></i>
                        </div>
                        <h5 class="mb-1"><?= htmlspecialchars($data['tempat_peradilan']) ?></h5>
                        <p class="text-muted mb-2"><?= htmlspecialchars($data['jenis_peradilan']) ?></p>
                    </div>
                    <div class="small">
                        <p class="mb-1">
                            <i class="fas fa-hashtag me-2"></i>
                            <strong>Singkatan:</strong> <?= htmlspecialchars($data['singkatan_jenis_peradilan']) ?>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-book me-2"></i>
                            <strong>Bidang:</strong> <?= htmlspecialchars($data['bidang_hukum_jenis_perkara']) ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <strong>Tahun:</strong> <?= htmlspecialchars($data['tahun_putusan']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- STATUS PUTUSAN -->
            <div class="card sidebar-card mb-3 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Status Putusan</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <?php
                        $status_icon = 'check-circle';
                        $status_color = 'success';
                        $status_text = 'Tetap';
                        if (stripos($data['status_putusan'], 'Kasasi') !== false) {
                            $status_icon = 'arrow-repeat';
                            $status_color = 'warning';
                            $status_text = 'Kasasi';
                        } elseif (stripos($data['status_putusan'], 'Peninjauan') !== false) {
                            $status_icon = 'search';
                            $status_color = 'info';
                            $status_text = 'Peninjauan';
                        } elseif (stripos($data['status_putusan'], 'Provisionil') !== false || stripos($data['status_putusan'], 'Sementara') !== false) {
                            $status_icon = 'clock-history';
                            $status_color = 'danger';
                            $status_text = 'Sementara';
                        }
                        ?>
                        <div class="mb-3">
                            <i class="fas fa-<?= $status_icon ?> fa-3x text-<?= $status_color ?>"></i>
                        </div>
                        <h5 class="text-<?= $status_color ?>"><?= htmlspecialchars($data['status_putusan']) ?></h5>
                        <p class="text-muted small mb-0">Status hukum putusan ini adalah <?= $status_text ?></p>
                    </div>
                </div>
            </div>

            <!-- HELP CARD -->
            <div class="card sidebar-card shadow-sm">
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

    <!-- ============================================
         PREVIEW MODAL
    ============================================ -->
    <?php if($file_found): ?>
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
                <div class="modal-body p-0 preview-modal" style="background: #525659;">
                    <iframe 
                        id="pdfViewer"
                        src="<?= htmlspecialchars($file_url) ?>#view=FitH&toolbar=1&navpanes=0" 
                        width="100%" 
                        height="600"
                        frameborder="0">
                    </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-expand"></i> Buka Fullscreen
                    </a>
                    <a href="<?= htmlspecialchars($file_url) ?>" 
                       target="_blank" 
                       download="<?= htmlspecialchars($file_name_display) ?>" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ============================================
     SCRIPTS
============================================ -->
<?php if($file_found): ?>
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

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + P untuk print
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
    // Ctrl/Cmd + D untuk download
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        const downloadBtn = document.querySelector('.btn-download-custom');
        if(downloadBtn) downloadBtn.click();
    }
});
</script>
<?php endif; ?>

<?php
// ============================================
// FOOTER
// ============================================
include "footer.php";

// ============================================
// CLOSE DATABASE CONNECTION
// ============================================
mysqli_close($koneksi);
?>