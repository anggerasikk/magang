<?php
/**
 * DETAIL MONOGRAFI DPRD
 * File: detail-monografi.php
 * Fungsi: Menampilkan detail dokumen monografi DPRD
 * Struktur mengikuti detail-keputusan.php yang sudah berhasil
 */

session_start();
include_once "a_db.php";

/* ======================
   VALIDASI ID
====================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: pokok-pikiran-dprd.php");
    exit;
}

/* ======================
   KONFIGURASI PATH UNTUK MONOGRAFI
====================== */
$monografi_pdf_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/";
$monografi_images_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/monografi/images/";

/* ======================
   AMBIL DATA DARI TABEL MONOGRAFI
====================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM monografi WHERE id_monografi = ? LIMIT 1"
);

if (!$stmt) {
    die("Error: Gagal menyiapkan query database");
}

mysqli_stmt_bind_param($stmt, "i", $id);
$execute_success = mysqli_stmt_execute($stmt);

if (!$execute_success) {
    die("Error: Gagal mengeksekusi query: " . mysqli_error($koneksi));
}

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: pokok-pikiran-dprd.php");
    exit;
}

if (mysqli_num_rows($result) == 0) {
    die("Error: Dokumen tidak ditemukan di database (ID: $id)");
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* ======================
   FUNGSI BANTU (SAMA DENGAN DETAIL-KEPUTUSAN)
====================== */
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

// Cari file PDF di database untuk monografi
function findDocumentFile($data) {
    $file_fields = ['file_pdf', 'pdf_path', 'file_path', 'fileDownload'];
    
    foreach ($file_fields as $field) {
        if (!empty($data[$field]) && trim($data[$field]) !== '') {
            $path = trim($data[$field]);
            // Hapus parameter query string jika ada
            $path = preg_replace('/\?.*$/', '', $path);
            return $path;
        }
    }
    return null;
}

// Fungsi pencarian file PDF monografi di server
function findFileOnServer($filename) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    $base_dir = dirname(__DIR__);
    
    // Bersihkan nama file
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    // Daftar semua variasi nama file
    $filename_variations = [$filename, $clean_filename];
    
    // Tambahkan variasi tanpa spasi/karakter khusus
    $no_spaces = str_replace([' ', '(', ')', '[', ']'], ['_', '', '', '', ''], $clean_filename);
    if ($no_spaces !== $clean_filename) {
        $filename_variations[] = $no_spaces;
    }
    
    // Lokasi yang mungkin (semua kombinasi)
    $possible_locations = [];
    
    // Daftar base directories
    $base_dirs = [
        $root,
        $base_dir,
        $current_dir,
        'C:/xampp/htdocs',
        'C:/xampp/htdocs/jdihcilacap_pasang',
        '/var/www/html'
    ];
    
    // Daftar subdirectories KHUSUS MONOGRAFI
    $sub_dirs = [
        '/wb_admin/uploads/monografi/pdf',
        '/uploads/monografi/pdf',
        '/monografi/pdf',
        '/wb_admin/uploads/pdf',
        '/uploads/pdf',
        '/pdf'
    ];
    
    // Buat semua kombinasi
    foreach ($base_dirs as $base) {
        foreach ($sub_dirs as $sub) {
            foreach ($filename_variations as $variation) {
                $possible_locations[] = $base . $sub . '/' . $variation;
                
                // Tambahkan dengan backslash untuk Windows
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $possible_locations[] = str_replace('/', '\\', $base . $sub . '\\' . $variation);
                }
            }
        }
    }
    
    // Tambahkan lokasi khusus monografi
    $special_locations = [
        // Absolute paths untuk monografi
        'C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/monografi/pdf/' . $filename,
        'C:/xampp/htdocs/monografi/pdf/' . $filename,
        
        // Linux paths
        '/var/www/html/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/' . $filename,
        '/var/www/html/jdihcilacap_pasang/uploads/monografi/pdf/' . $filename,
        '/var/www/html/monografi/pdf/' . $filename,
        
        // Current project
        $current_dir . '/uploads/monografi/pdf/' . $filename,
        $current_dir . '/../uploads/monografi/pdf/' . $filename,
        $current_dir . '/wb_admin/uploads/monografi/pdf/' . $filename,
    ];
    
    $possible_locations = array_merge($possible_locations, $special_locations);
    
    // Hilangkan duplikat
    $possible_locations = array_unique($possible_locations);
    
    // Cek setiap lokasi
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            // Verifikasi bahwa ini file PDF
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if (strpos($mime_type, 'pdf') !== false || strpos($mime_type, 'application/pdf') !== false) {
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
    $root = $_SERVER['DOCUMENT_ROOT'];
    $root = str_replace('\\', '/', $root);
    $file_path = str_replace('\\', '/', $file_path);
    
    // Jika file ada dalam DOCUMENT_ROOT
    if (strpos($file_path, $root) === 0) {
        $url_path = substr($file_path, strlen($root));
        return $url_path;
    }
    
    // Untuk XAMPP absolute path
    if (strpos($file_path, 'C:/xampp/htdocs') === 0) {
        $url_path = substr($file_path, strlen('C:/xampp/htdocs'));
        return $url_path;
    }
    
    // Untuk Linux absolute path
    if (strpos($file_path, '/var/www/html') === 0) {
        $url_path = substr($file_path, strlen('/var/www/html'));
        return $url_path;
    }
    
    // Jika tidak bisa dikonversi, return path relatif
    return $file_path;
}

/* ======================
   PROSES PENCARIAN FILE
====================== */
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
        
        // Generate URL untuk browser
        $file_url = generateFileUrl($actual_file_path);
        
        // Tambahkan parameter timestamp untuk menghindari cache
        $file_url .= '?t=' . time();
    }
}

/* ======================
   BACK LINK DINAMIS
====================== */
$back_page  = 'pokok-pikiran-dprd';
$back_label = 'Pokok Pikiran DPRD';

// Tentukan jenis dokumen untuk back link
$jenis = $data['jenis_monografi'] ?? '';
if (strpos($jenis, 'Pokok_Pikiran') !== false) {
    $back_page  = 'pokok-pikiran-dprd';
    $back_label = 'Pokok Pikiran DPRD';
}

$page_title = $data['judul'] . " - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $back_page ?>.php"><?= $back_label ?></a></li>
            <li class="breadcrumb-item active">Detail Dokumen</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="<?= $back_page ?>.php" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke <?= $back_label ?>
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info">
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
                    <tr><th>Server OS</th><td><?= PHP_OS ?></td></tr>
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
        <div class="col-lg-9">
            <!-- HEADER -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-book"></i> 
                        <?= htmlspecialchars($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">Tahun <?= htmlspecialchars($data['tahun_terbit']) ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Tanggal Pembuatan: <?= tgl($data['tanggal_pembuatan']) ?>
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

            <!-- ======================
                 METADATA (SAMA DENGAN DETAIL-KEPUTUSAN)
            ====================== -->
            <div class="card mb-4">
                <div class="card-header fw-bold">METADATA</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <!-- SESUAI DENGAN GAMBAR YANG ANDA BERIKAN -->
                        <tr><td width="30%">Bahasa</td><td><?= htmlspecialchars($data['bahasa'] ?? 'Indonesia') ?></td></tr>
                        <tr><td>Tipe Dokumen</td><td><?= htmlspecialchars($data['jenis_monografi'] ?? '-') ?></td></tr>
                        <tr><td>Judul</td><td><?= htmlspecialchars($data['judul'] ?? '-') ?></td></tr>
                        
                        <!-- Untuk monografi, tidak ada "Nomor Peraturan", ganti dengan ISBN atau Penulis -->
                        <?php if(!empty($data['isbn'])): ?>
                        <tr><td>ISBN</td><td><?= htmlspecialchars($data['isbn']) ?></td></tr>
                        <?php else: ?>
                        <tr><td>Penulis</td><td><?= htmlspecialchars($data['penulis'] ?? '-') ?></td></tr>
                        <?php endif; ?>
                        
                        <!-- Tanggal Penetapan ganti dengan Tanggal Pembuatan -->
                        <tr><td>Tanggal Pembuatan</td><td><?= tgl($data['tanggal_pembuatan']) ?></td></tr>
                        
                        <!-- Tanggal Pengundangan ganti dengan Tahun Terbit -->
                        <tr><td>Tahun Terbit</td><td><?= htmlspecialchars($data['tahun_terbit'] ?? '-') ?></td></tr>
                        
                        <!-- Tahun ganti dengan Penerbit -->
                        <tr><td>Penerbit</td><td><?= htmlspecialchars($data['penerbit'] ?? '-') ?></td></tr>
                        
                        <!-- Bidang Hukum -->
                        <tr><td>Bidang Hukum</td><td><?= htmlspecialchars($data['bidang_hukum'] ?? '-') ?></td></tr>
                        
                        <!-- Status -->
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
                                <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                            </td>
                        </tr>
                        
                        <!-- Sumber ganti dengan Jumlah Halaman -->
                        <tr><td>Jumlah Halaman</td><td><?= htmlspecialchars($data['jumlah_halaman'] ?? '-') ?> halaman</td></tr>
                        
                        <!-- Info tambahan untuk monografi -->
                        <?php if(!empty($data['created_at'])): ?>
                        <tr><td>Dibuat Tanggal</td><td><?= tgl($data['created_at']) ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- ======================
                 DESKRIPSI/ABSTRAK
            ====================== -->
            <?php if(!empty($data['deskripsi'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">DESKRIPSI</div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ======================
                 DOKUMEN DIGITAL (SAMA DENGAN DETAIL-KEPUTUSAN)
            ====================== -->
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
                                <!-- Tombol Download dengan atribut download -->
                                <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" download="<?= htmlspecialchars($file_name_display) ?>" class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka
                                    </a>
                                    <button type="button" class="btn btn-outline-info" onclick="showPreview()">
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

        <!-- SIDEBAR (SAMA DENGAN DETAIL-KEPUTUSAN) -->
        <div class="col-lg-3">
            <!-- QUICK ACTIONS -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($file_found): ?>
                        <!-- Tombol Download di sidebar -->
                        <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" download="<?= htmlspecialchars($file_name_display) ?>" class="btn btn-success">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-primary">
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
                        <a href="<?= $back_page ?>.php" class="btn btn-outline-secondary">
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
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-book"></i> Jenis</span>
                            <span class="badge bg-warning">Monografi</span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

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
                            <span class="badge bg-info"><?= htmlspecialchars($data['penulis']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['penerbit'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Penerbit</span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($data['penerbit']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['jumlah_halaman'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Halaman</span>
                            <span class="badge bg-primary"><?= htmlspecialchars($data['jumlah_halaman']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['bahasa'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Bahasa</span>
                            <span class="badge bg-dark"><?= htmlspecialchars($data['bahasa']) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['isbn'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ISBN</span>
                            <span class="badge bg-warning"><?= htmlspecialchars($data['isbn']) ?></span>
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

    <!-- PREVIEW MODAL (SAMA DENGAN DETAIL-KEPUTUSAN) -->
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
                <div class="modal-body p-0" style="background: #525659;">
                    <iframe 
                        id="pdfViewer"
                        src="<?= htmlspecialchars($file_url) ?>#view=FitH&toolbar=1&navpanes=0" 
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
                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-expand"></i> Buka Fullscreen
                    </a>
                    <!-- Tombol Download di modal -->
                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" download="<?= htmlspecialchars($file_name_display) ?>" class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if($file_found): ?>
<script>
function showPreview() {
    var modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

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