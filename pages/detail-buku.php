<?php
/**
 * DETAIL BUKU PERPUSTAKAAN
 * File: detail-buku.php
 * Fungsi: Menampilkan detail buku perpustakaan
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
// KONFIGURASI PATH
// ============================================
$base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/";
$buku_files_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/buku/files/";
$buku_images_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/buku/images/";

$web_buku_files_path = "/jdihcilacap_pasang/wb_admin/uploads/buku/files/";
$web_buku_images_path = "/jdihcilacap_pasang/wb_admin/uploads/buku/images/";

/* ======================
   VALIDASI ID
====================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: buku.php");
    exit;
}

/* ======================
   AMBIL DATA BUKU
====================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT 
        id_buku,
        judul,
        slug,
        pengarang,
        penerbit,
        tahun_terbit,
        deskripsi_fisik,
        isbn,
        kota_terbit,
        subjek_topik,
        bidang_hukum,
        bahasa,
        tipe_dokumen,
        nomor_panggil,
        nomor_induk,
        lokasi_rak,
        sinopsis,
        sampul_path,
        file_buku_path,
        status_ketersediaan,
        jumlah_eksemplar,
        jumlah_tersedia,
        tanggal_masuk,
        id_user,
        created_at,
        updated_at
     FROM buku_perpustakaan 
     WHERE id_buku = ?
     LIMIT 1"
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
    header("Location: buku.php");
    exit;
}

if (mysqli_num_rows($result) == 0) {
    die("Error: Buku tidak ditemukan di database (ID: $id)");
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* ======================
   FUNGSI BANTU
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

// Fungsi untuk menemukan file buku
function findBukuFile($filename, $id = null, $type = 'pdf') {
    global $buku_files_path;
    
    if (empty($filename) && empty($id)) {
        return null;
    }
    
    if (!empty($filename)) {
        $filename = basename($filename);
        
        $possible_locations = [
            $buku_files_path . $filename,
            str_replace('/', '\\', $buku_files_path) . $filename,
            $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/buku/files/' . $filename,
        ];
        
        foreach ($possible_locations as $location) {
            if (file_exists($location) && is_file($location) && is_readable($location)) {
                return $location;
            }
        }
    }
    
    // Coba dengan ID
    if ($id) {
        $extensions = $type === 'pdf' ? ['pdf', 'PDF'] : ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP', 'GIF'];
        
        foreach ($extensions as $ext) {
            $possible_filenames = [
                $id . '.' . $ext,
                'buku_' . $id . '.' . $ext,
                'BUKU_' . $id . '.' . $ext,
            ];
            
            foreach ($possible_filenames as $filename) {
                $location = $buku_files_path . $filename;
                if (file_exists($location) && is_file($location) && is_readable($location)) {
                    return $location;
                }
            }
        }
    }
    
    return null;
}

// Fungsi untuk menemukan gambar sampul
function findSampulImageFile($filename, $id = null) {
    global $buku_images_path;
    
    if (empty($filename) && $id) {
        $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP', 'GIF'];
        foreach ($image_extensions as $ext) {
            $test_filenames = [
                $id . '.' . $ext,
                'buku_' . $id . '.' . $ext,
                'BUKU_' . $id . '.' . $ext,
                'sampul_' . $id . '.' . $ext,
                'cover_' . $id . '.' . $ext,
                'thumbnail_' . $id . '.' . $ext,
            ];
            
            foreach ($test_filenames as $test_filename) {
                $test_path = $buku_images_path . $test_filename;
                if (file_exists($test_path)) {
                    return $test_path;
                }
            }
        }
        return null;
    }
    
    if (empty($filename)) {
        return null;
    }
    
    $filename = basename($filename);
    
    $possible_locations = [
        $buku_images_path . $filename,
        str_replace('/', '\\', $buku_images_path) . $filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/buku/images/' . $filename,
        $_SERVER['DOCUMENT_ROOT'] . $filename, // Jika path relatif
    ];
    
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            return $location;
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
    
    // Jika sudah berupa URL
    if (preg_match('/^https?:\/\//', $file_path)) {
        return $file_path;
    }
    
    // Jika path relatif
    if (!empty($file_path) && $file_path[0] !== '/') {
        return '/' . $file_path;
    }
    
    return $file_path;
}

// Fungsi untuk mendapatkan ekstensi file
function getFileExtension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

// Fungsi untuk mendapatkan icon berdasarkan tipe file
function getFileIcon($filename) {
    $ext = strtolower(getFileExtension($filename));
    
    $icons = [
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word',
        'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel',
        'xlsx' => 'fa-file-excel',
        'ppt' => 'fa-file-powerpoint',
        'pptx' => 'fa-file-powerpoint',
        'txt' => 'fa-file-alt',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
        'gif' => 'fa-file-image',
        'zip' => 'fa-file-archive',
        'rar' => 'fa-file-archive',
        'epub' => 'fa-book',
        'mobi' => 'fa-book',
    ];
    
    return $icons[$ext] ?? 'fa-file';
}

/* ======================
   PROSES PENCARIAN FILE
====================== */
$debug_mode = isset($_GET['debug']);

// Cari file buku
$file_found = false;
$file_info = null;
$file_url = '';
$file_size_formatted = '0 MB';
$file_name_display = '';
$file_icon = 'fa-file';

$file_path_db = $data['file_buku_path'] ?? '';

if (!empty($file_path_db)) {
    $actual_file_path = findBukuFile($file_path_db, $data['id_buku'], getFileExtension($file_path_db));
    
    if ($actual_file_path && file_exists($actual_file_path)) {
        $file_found = true;
        $file_info = [
            'path' => $actual_file_path,
            'size' => filesize($actual_file_path),
            'time' => filemtime($actual_file_path),
            'mime' => mime_content_type($actual_file_path),
            'extension' => getFileExtension($actual_file_path)
        ];
        
        $file_size_formatted = formatFileSize($file_info['size']);
        $file_name_display = basename($actual_file_path);
        $file_icon = getFileIcon($actual_file_path);
        
        // Generate URL untuk browser
        $file_url = generateFileUrl($actual_file_path);
        
        // Tambahkan parameter timestamp untuk menghindari cache
        $file_url .= '?t=' . time();
    }
}

// Cari gambar sampul
$image_found = false;
$image_url = '';
$sampul_path = $data['sampul_path'] ?? '';

if (!empty($sampul_path)) {
    $actual_image_path = findSampulImageFile($sampul_path, $data['id_buku']);
} else {
    $actual_image_path = findSampulImageFile(null, $data['id_buku']);
}

if ($actual_image_path && file_exists($actual_image_path)) {
    $image_found = true;
    $image_url = generateFileUrl($actual_image_path);
    
    // Pastikan URL dimulai dengan slash
    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
        $image_url = '/' . $image_url;
    }
}

// Status ketersediaan
$status_class = '';
$status_text = '';
switch ($data['status_ketersediaan']) {
    case 'tersedia':
        $status_class = 'bg-success';
        $status_text = 'Tersedia';
        break;
    case 'dipinjam':
        $status_class = 'bg-warning';
        $status_text = 'Dipinjam';
        break;
    case 'hilang':
        $status_class = 'bg-danger';
        $status_text = 'Hilang';
        break;
    case 'rusak':
        $status_class = 'bg-secondary';
        $status_text = 'Rusak';
        break;
    default:
        $status_class = 'bg-info';
        $status_text = $data['status_ketersediaan'];
}

$page_title = $data['judul'] . " - Buku Perpustakaan - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="buku.php">Koleksi Buku</a></li>
            <li class="breadcrumb-item active">Detail Buku</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="buku.php" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Koleksi Buku
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Database File Path</th><td><code><?= htmlspecialchars($file_path_db ?? 'NULL') ?></code></td></tr>
                    <tr><th>Database Sampul Path</th><td><code><?= htmlspecialchars($sampul_path ?? 'NULL') ?></code></td></tr>
                    <tr><th>File Found</th><td><?= $file_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                    <tr><th>Image Found</th><td><?= $image_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Document Root</th><td><code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></td></tr>
                    <tr><th>Files Path</th><td><code><?= $buku_files_path ?></code></td></tr>
                    <tr><th>Images Path</th><td><code><?= $buku_images_path ?></code></td></tr>
                </table>
            </div>
        </div>
        <?php if($file_found): ?>
        <table class="table table-sm table-bordered mt-2">
            <tr><th>Actual File Path</th><td><code><?= htmlspecialchars($file_info['path']) ?></code></td></tr>
            <tr><th>File URL</th><td><code><?= htmlspecialchars($file_url) ?></code></td></tr>
            <tr><th>File Size</th><td><?= $file_size_formatted ?></td></tr>
            <tr><th>File Type</th><td><?= $file_info['mime'] ?></td></tr>
        </table>
        <?php endif; ?>
        <?php if($image_found): ?>
        <table class="table table-sm table-bordered mt-2">
            <tr><th>Actual Image Path</th><td><code><?= htmlspecialchars($actual_image_path) ?></code></td></tr>
            <tr><th>Image URL</th><td><code><?= htmlspecialchars($image_url) ?></code></td></tr>
        </table>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-9">
            <!-- HEADER BUKU -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-book"></i> 
                            <?= htmlspecialchars($data['judul']) ?>
                        </h4>
                        <div>
                            <span class="badge <?= $status_class ?> fs-6 p-2">
                                <i class="fas fa-circle"></i> <?= $status_text ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-muted mb-0">
                                <i class="fas fa-hashtag"></i> 
                                ID Buku: <strong><?= htmlspecialchars($data['id_buku']) ?></strong>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-barcode"></i> 
                                ISBN: <strong><?= htmlspecialchars($data['isbn'] ?: '-') ?></strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if($file_found): ?>
                            <span class="badge bg-success fs-6 p-2">
                                <i class="fas fa-check-circle"></i> File Digital Tersedia
                            </span>
                            <?php else: ?>
                            <span class="badge bg-warning fs-6 p-2">
                                <i class="fas fa-exclamation-circle"></i> File Digital Tidak Tersedia
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SAMPUL & INFO UTAMA -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- SAMPUL BUKU -->
                        <div class="col-md-3">
                            <?php if($image_found && !empty($image_url)): ?>
                            <div class="text-center">
                                <img src="<?= htmlspecialchars($image_url) ?>" 
                                     alt="<?= htmlspecialchars($data['judul']) ?>"
                                     class="img-fluid rounded shadow"
                                     style="max-height: 300px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f8f9fa%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22Arial%22%20font-size%3D%2214%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%20fill%3D%22%236c757d%22%3ECover%20Buku%3C%2Ftext%3E%3C%2Fsvg%3E'">
                                <p class="text-muted small mt-2">Sampul Buku</p>
                            </div>
                            <?php else: ?>
                            <div class="text-center">
                                <div class="placeholder-image rounded" style="width: 200px; height: 300px; background: #f8f9fa; display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="fas fa-book fa-4x text-secondary mb-3"></i>
                                    <p class="text-muted small">Tidak ada sampul</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- INFO UTAMA BUKU -->
                        <div class="col-md-9">
                            <h5 class="border-bottom pb-2">Informasi Bibliografis</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-user-edit"></i> Pengarang:</strong><br>
                                    <?= htmlspecialchars($data['pengarang'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-building"></i> Penerbit:</strong><br>
                                    <?= htmlspecialchars($data['penerbit'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-city"></i> Kota Terbit:</strong><br>
                                    <?= htmlspecialchars($data['kota_terbit'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-calendar-alt"></i> Tahun Terbit:</strong><br>
                                    <?= htmlspecialchars($data['tahun_terbit'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-file-alt"></i> Deskripsi Fisik:</strong><br>
                                    <?= htmlspecialchars($data['deskripsi_fisik'] ?: '-') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-language"></i> Bahasa:</strong><br>
                                    <?= htmlspecialchars($data['bahasa'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-tag"></i> Tipe Dokumen:</strong><br>
                                    <?= htmlspecialchars($data['tipe_dokumen'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-tags"></i> Subjek/Topik:</strong><br>
                                    <?= htmlspecialchars($data['subjek_topik'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-gavel"></i> Bidang Hukum:</strong><br>
                                    <?= htmlspecialchars($data['bidang_hukum'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-barcode"></i> ISBN:</strong><br>
                                    <?= htmlspecialchars($data['isbn'] ?: '-') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFO KOLEKSI PERPUSTAKAAN -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-university"></i> INFORMASI KOLEKSI PERPUSTAKAAN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-hashtag"></i> Nomor Panggil:</strong><br>
                            <span class="badge bg-dark fs-6"><?= htmlspecialchars($data['nomor_panggil'] ?: '-') ?></span></p>
                            
                            <p><strong><i class="fas fa-fingerprint"></i> Nomor Induk:</strong><br>
                            <code><?= htmlspecialchars($data['nomor_induk'] ?: '-') ?></code></p>
                            
                            <p><strong><i class="fas fa-archive"></i> Lokasi Rak:</strong><br>
                            <span class="badge bg-info"><?= htmlspecialchars($data['lokasi_rak'] ?: '-') ?></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-copy"></i> Jumlah Eksemplar:</strong><br>
                            <span class="badge bg-primary fs-5"><?= htmlspecialchars($data['jumlah_eksemplar'] ?: '0') ?></span></p>
                            
                            <p><strong><i class="fas fa-check-circle"></i> Jumlah Tersedia:</strong><br>
                            <span class="badge bg-success fs-5"><?= htmlspecialchars($data['jumlah_tersedia'] ?: '0') ?></span></p>
                            
                            <p><strong><i class="fas fa-calendar-day"></i> Tanggal Masuk:</strong><br>
                            <?= tgl($data['tanggal_masuk']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================
                 SINOPSIS
            ====================== -->
            <?php if(!empty($data['sinopsis'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-alt"></i> SINOPSIS
                </div>
                <div class="card-body">
                    <div class="sinopsis-content">
                        <?= nl2br(htmlspecialchars($data['sinopsis'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ======================
                 FILE DIGITAL
            ====================== -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file"></i> FILE DIGITAL BUKU
                </div>
                <div class="card-body">
                    <?php if($file_found): ?>
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="file-icon-large">
                                <i class="fas <?= $file_icon ?> text-danger" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><?= htmlspecialchars($file_name_display) ?></h6>
                            <p class="text-muted mb-1">
                                <i class="fas fa-hdd"></i> Ukuran: <?= $file_size_formatted ?>
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-file"></i> Tipe: <?= strtoupper($file_info['extension']) ?> (<?= $file_info['mime'] ?>)
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Diupdate: <?= date('d/m/Y H:i', $file_info['time']) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2">
                                <!-- Tombol Download dengan atribut download -->
                                <a href="<?= htmlspecialchars($file_url) ?>" 
                                   target="_blank" 
                                   download="<?= htmlspecialchars($file_name_display) ?>" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                                <div class="btn-group" role="group">
                                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka
                                    </a>
                                    <?php if($file_info['extension'] == 'pdf'): ?>
                                    <button type="button" class="btn btn-outline-info" onclick="showPreview()">
                                        <i class="fas fa-search"></i> Pratinjau
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-3x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">File Digital Tidak Ditemukan!</h5>
                                <p class="mb-2">File digital buku ini tidak tersedia di server.</p>
                                <hr>
                                <div class="mb-3">
                                    <strong>Informasi:</strong><br>
                                    <small class="text-muted">
                                        Path database: <code><?= htmlspecialchars($file_path_db ?: 'NULL') ?></code><br>
                                        Buku ID: <code><?= htmlspecialchars($data['id_buku']) ?></code>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="?id=<?= $id ?>&debug=1" class="btn btn-warning btn-sm">
                                        <i class="fas fa-bug"></i> Debug Mode
                                    </a>
                                    <a href="buku.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
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
                        <?php if($file_found): ?>
                        <!-- Tombol Download di sidebar -->
                        <a href="<?= htmlspecialchars($file_url) ?>" 
                           target="_blank" 
                           download="<?= htmlspecialchars($file_name_display) ?>" 
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Download File
                        </a>
                        <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <?php if($file_info['extension'] == 'pdf'): ?>
                        <button onclick="showPreview()" class="btn btn-info">
                            <i class="fas fa-search-plus"></i> Pratinjau
                        </button>
                        <?php endif; ?>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-download"></i> File Tidak Tersedia
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-warning" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak Katalog
                        </button>
                        <a href="buku.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua Buku
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
                            <span><i class="fas fa-file-code"></i> Format</span>
                            <span class="badge bg-dark"><?= strtoupper($file_info['extension']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar"></i> Diupdate</span>
                            <span class="badge bg-light text-dark"><?= date('d/m/Y', $file_info['time']) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- STATUS BUKU -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header <?= $status_class ?> text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Status Koleksi</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hashtag"></i> ID Buku</span>
                            <span class="badge bg-dark"><?= htmlspecialchars($data['id_buku']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-copy"></i> Total Eksemplar</span>
                            <span class="badge bg-primary"><?= htmlspecialchars($data['jumlah_eksemplar']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check-circle"></i> Tersedia</span>
                            <span class="badge bg-success"><?= htmlspecialchars($data['jumlah_tersedia']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-archive"></i> Lokasi</span>
                            <span class="badge bg-info"><?= htmlspecialchars($data['lokasi_rak'] ?: '-') ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- BIBLIOGRAFI INFO -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-book"></i> Info Bibliografi</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <small><i class="fas fa-calendar"></i> <strong>Tahun:</strong> <?= htmlspecialchars($data['tahun_terbit']) ?></small>
                        </li>
                        <li class="list-group-item">
                            <small><i class="fas fa-language"></i> <strong>Bahasa:</strong> <?= htmlspecialchars($data['bahasa']) ?></small>
                        </li>
                        <li class="list-group-item">
                            <small><i class="fas fa-tag"></i> <strong>Tipe:</strong> <?= htmlspecialchars($data['tipe_dokumen']) ?></small>
                        </li>
                        <li class="list-group-item">
                            <small><i class="fas fa-barcode"></i> <strong>ISBN:</strong> <?= htmlspecialchars($data['isbn'] ?: '-') ?></small>
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
                        <strong>Status Ketersediaan:</strong>
                    </p>
                    <ul class="small">
                        <li><span class="badge bg-success">●</span> Tersedia</li>
                        <li><span class="badge bg-warning">●</span> Dipinjam</li>
                        <li><span class="badge bg-danger">●</span> Hilang</li>
                        <li><span class="badge bg-secondary">●</span> Rusak</li>
                    </ul>
                    <hr>
                    <p class="small mb-0">
                        <i class="fas fa-phone-alt"></i>
                        <strong>Peminjaman:</strong><br>
                        Hubungi Petugas Perpustakaan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PREVIEW MODAL (jika file PDF) -->
    <?php if($file_found && $file_info['extension'] == 'pdf'): ?>
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

<?php if($file_found && $file_info['extension'] == 'pdf'): ?>
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

// Track download
document.querySelectorAll('a[download]').forEach(link => {
    link.addEventListener('click', function() {
        console.log('Download started:', this.href);
    });
});
</script>
<?php endif; ?>

<style>
/* Style untuk sinopsis */
.sinopsis-content {
    font-family: 'Georgia', serif;
    color: #333;
    line-height: 1.6;
    text-align: justify;
}

.sinopsis-content p {
    margin-bottom: 1rem;
}

/* Badge untuk status */
.badge-status {
    font-size: 0.9rem;
    padding: 0.4rem 0.8rem;
}

/* Print styles untuk katalog */
@media print {
    .container {
        width: 100%;
        max-width: 100%;
    }
    
    .sidebar, 
    .btn, 
    .breadcrumb, 
    .card-header:not(.bg-primary),
    .modal,
    .alert {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .sinopsis-content {
        font-size: 11pt;
    }
    
    /* Header untuk cetakan */
    .card-header.bg-primary {
        background: #fff !important;
        color: #000 !important;
        border-bottom: 2px solid #000;
    }
    
    /* Hide download buttons saat print */
    a[download], 
    button {
        display: none !important;
    }
}
</style>

<?php
include "footer.php";
mysqli_close($koneksi);
?>