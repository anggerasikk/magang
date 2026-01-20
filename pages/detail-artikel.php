<?php
/**
 * DETAIL ARTIKEL HUKUM
 * File: detail-artikel.php
 * Fungsi: Menampilkan detail artikel hukum dari DPRD Kota Surakarta
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
$artikel_pdf_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/";
$artikel_images_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/artikel/images/";

$web_artikel_pdf_path = "/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/";
$web_artikel_images_path = "/jdihcilacap_pasang/wb_admin/uploads/artikel/images/";

/* ======================
   VALIDASI ID
====================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: artikel-hukum.php");
    exit;
}

/* ======================
   AMBIL DATA ARTIKEL
====================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT 
        id_artikel,
        judul,
        jenis_artikel,
        tanggal_terbit,
        tahun_terbit,
        penulis,
        jurnal,
        issn,
        volume,
        issue,
        halaman,
        bidang_hukum,
        keywords,
        abstrak,
        status,
        bahasa,
        file_pdf,
        thumbnail,
        created_at,
        updated_at,
        id_user
     FROM artikel_hukum 
     WHERE id_artikel = ? AND status = 'published' 
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
    header("Location: artikel-hukum.php");
    exit;
}

if (mysqli_num_rows($result) == 0) {
    die("Error: Artikel tidak ditemukan di database (ID: $id)");
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

// Fungsi untuk menemukan file PDF di server
function findArtikelPDFFile($filename, $id = null) {
    global $artikel_pdf_path;
    
    if (empty($filename) && empty($id)) {
        return null;
    }
    
    if (!empty($filename)) {
        $filename = basename($filename);
        $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
        
        $possible_locations = [
            $artikel_pdf_path . $clean_filename,
            str_replace('/', '\\', $artikel_pdf_path) . $clean_filename,
            $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/' . $clean_filename,
        ];
        
        foreach ($possible_locations as $location) {
            if (file_exists($location) && is_file($location) && is_readable($location)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $location);
                finfo_close($finfo);
                
                if (stripos($mime_type, 'pdf') !== false) {
                    return $location;
                }
            }
        }
    }
    
    // Coba dengan ID
    if ($id) {
        $possible_filenames = [
            $id . '.pdf',
            $id . '.PDF',
            'artikel_' . $id . '.pdf',
            'artikel_' . $id . '.PDF',
            'ARTIKEL_' . $id . '.pdf',
        ];
        
        foreach ($possible_filenames as $filename) {
            $location = $artikel_pdf_path . $filename;
            if (file_exists($location) && is_file($location) && is_readable($location)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $location);
                finfo_close($finfo);
                
                if (stripos($mime_type, 'pdf') !== false) {
                    return $location;
                }
            }
        }
    }
    
    return null;
}

// Fungsi untuk menemukan gambar thumbnail
function findArtikelImageFile($filename, $id = null) {
    global $artikel_images_path;
    
    if (empty($filename) && $id) {
        $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP', 'GIF'];
        foreach ($image_extensions as $ext) {
            $test_filenames = [
                $id . '.' . $ext,
                'artikel_' . $id . '.' . $ext,
                'ARTIKEL_' . $id . '.' . $ext,
                'thumbnail_' . $id . '.' . $ext,
                'cover_' . $id . '.' . $ext,
            ];
            
            foreach ($test_filenames as $test_filename) {
                $test_path = $artikel_images_path . $test_filename;
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
        $artikel_images_path . $filename,
        str_replace('/', '\\', $artikel_images_path) . $filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/artikel/images/' . $filename,
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

$file_path_db = $data['file_pdf'] ?? '';

if (!empty($file_path_db)) {
    $actual_file_path = findArtikelPDFFile($file_path_db, $data['id_artikel']);
    
    if ($actual_file_path && file_exists($actual_file_path)) {
        $file_found = true;
        $file_info = [
            'path' => $actual_file_path,
            'size' => filesize($actual_file_path),
            'time' => filemtime($actual_file_path),
            'mime' => mime_content_type($actual_file_path)
        ];
        
        $file_size_formatted = formatFileSize($file_info['size']);
        $file_name_display = basename($actual_file_path);
        
        // Generate URL untuk browser
        $file_url = generateFileUrl($actual_file_path);
        
        // Tambahkan parameter timestamp untuk menghindari cache
        $file_url .= '?t=' . time();
    }
}

/* ======================
   PROSES PENCARIAN GAMBAR
====================== */
$image_found = false;
$image_url = '';
$thumbnail_path = $data['thumbnail'] ?? '';

if (!empty($thumbnail_path)) {
    $actual_image_path = findArtikelImageFile($thumbnail_path, $data['id_artikel']);
} else {
    $actual_image_path = findArtikelImageFile(null, $data['id_artikel']);
}

if ($actual_image_path && file_exists($actual_image_path)) {
    $image_found = true;
    $image_url = generateFileUrl($actual_image_path);
    
    // Pastikan URL dimulai dengan slash
    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
        $image_url = '/' . $image_url;
    }
}

$page_title = $data['judul'] . " - Artikel Hukum - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="artikel-hukum.php">Artikel Hukum</a></li>
            <li class="breadcrumb-item active">Detail Artikel</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="artikel-hukum.php" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Artikel Hukum
    </a>

    <?php if($debug_mode): ?>
    <div class="alert alert-info">
        <h5><i class="fas fa-bug"></i> Debug Information</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Database PDF Path</th><td><code><?= htmlspecialchars($file_path_db ?? 'NULL') ?></code></td></tr>
                    <tr><th>Database Thumbnail</th><td><code><?= htmlspecialchars($thumbnail_path ?? 'NULL') ?></code></td></tr>
                    <tr><th>PDF Found</th><td><?= $file_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                    <tr><th>Image Found</th><td><?= $image_found ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th>Document Root</th><td><code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></td></tr>
                    <tr><th>PDF Path</th><td><code><?= $artikel_pdf_path ?></code></td></tr>
                    <tr><th>Image Path</th><td><code><?= $artikel_images_path ?></code></td></tr>
                </table>
            </div>
        </div>
        <?php if($file_found): ?>
        <table class="table table-sm table-bordered mt-2">
            <tr><th>Actual PDF Path</th><td><code><?= htmlspecialchars($file_info['path']) ?></code></td></tr>
            <tr><th>File URL</th><td><code><?= htmlspecialchars($file_url) ?></code></td></tr>
            <tr><th>File Size</th><td><?= $file_size_formatted ?></td></tr>
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
                            <h5 class="text-primary">
                                <i class="fas fa-tag"></i> 
                                <?= htmlspecialchars($data['jenis_artikel']) ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                Tanggal Terbit: <?= tgl($data['tanggal_terbit']) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if($file_found): ?>
                            <span class="badge bg-success fs-6 p-2">
                                <i class="fas fa-check-circle"></i> PDF Tersedia
                            </span>
                            <?php else: ?>
                            <span class="badge bg-warning fs-6 p-2">
                                <i class="fas fa-exclamation-circle"></i> PDF Tidak Tersedia
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THUMBNAIL & INFO UTAMA -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- THUMBNAIL -->
                        <div class="col-md-3">
                            <?php if($image_found && !empty($image_url)): ?>
                            <div class="text-center">
                                <img src="<?= htmlspecialchars($image_url) ?>" 
                                     alt="<?= htmlspecialchars($data['judul']) ?>"
                                     class="img-fluid rounded shadow"
                                     style="max-height: 250px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22250%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f8f9fa%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22Arial%22%20font-size%3D%2214%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%20fill%3D%22%236c757d%22%3EArtikel%20Hukum%3C%2Ftext%3E%3C%2Fsvg%3E'">
                                <p class="text-muted small mt-2">Cover Artikel</p>
                            </div>
                            <?php else: ?>
                            <div class="text-center">
                                <div class="placeholder-image rounded" style="width: 200px; height: 250px; background: #f8f9fa; display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="fas fa-book fa-4x text-secondary mb-3"></i>
                                    <p class="text-muted small">Tidak ada gambar</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- INFO UTAMA -->
                        <div class="col-md-9">
                            <h5 class="border-bottom pb-2">Informasi Artikel</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-user-edit"></i> Penulis:</strong><br>
                                    <?= htmlspecialchars($data['penulis'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-newspaper"></i> Jurnal:</strong><br>
                                    <?= htmlspecialchars($data['jurnal'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-barcode"></i> ISSN:</strong><br>
                                    <?= htmlspecialchars($data['issn'] ?: '-') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-calendar-alt"></i> Tahun Terbit:</strong><br>
                                    <?= htmlspecialchars($data['tahun_terbit'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-language"></i> Bahasa:</strong><br>
                                    <?= htmlspecialchars($data['bahasa'] ?: '-') ?></p>
                                    
                                    <p><strong><i class="fas fa-tags"></i> Bidang Hukum:</strong><br>
                                    <?= htmlspecialchars($data['bidang_hukum'] ?: '-') ?></p>
                                </div>
                            </div>
                            
                            <p><strong><i class="fas fa-key"></i> Keywords:</strong><br>
                            <span class="text-muted"><?= htmlspecialchars($data['keywords'] ?: '-') ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================
                 METADATA DETAIL
            ====================== -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-info-circle"></i> METADATA DETAIL
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tr><td width="30%">ID Artikel</td><td><?= htmlspecialchars($data['id_artikel']) ?></td></tr>
                        <tr><td>Jenis Artikel</td><td><?= htmlspecialchars($data['jenis_artikel']) ?></td></tr>
                        <tr><td>Judul</td><td><?= htmlspecialchars($data['judul']) ?></td></tr>
                        <tr><td>Penulis</td><td><?= htmlspecialchars($data['penulis']) ?></td></tr>
                        <tr><td>Jurnal</td><td><?= htmlspecialchars($data['jurnal'] ?: '-') ?></td></tr>
                        <tr><td>ISSN</td><td><?= htmlspecialchars($data['issn'] ?: '-') ?></td></tr>
                        <tr><td>Volume</td><td><?= htmlspecialchars($data['volume'] ?: '-') ?></td></tr>
                        <tr><td>Issue</td><td><?= htmlspecialchars($data['issue'] ?: '-') ?></td></tr>
                        <tr><td>Halaman</td><td><?= htmlspecialchars($data['halaman'] ?: '-') ?></td></tr>
                        <tr><td>Tanggal Terbit</td><td><?= tgl($data['tanggal_terbit']) ?></td></tr>
                        <tr><td>Tahun Terbit</td><td><?= htmlspecialchars($data['tahun_terbit']) ?></td></tr>
                        <tr><td>Bidang Hukum</td><td><?= htmlspecialchars($data['bidang_hukum'] ?: '-') ?></td></tr>
                        <tr><td>Keywords</td><td><?= htmlspecialchars($data['keywords'] ?: '-') ?></td></tr>
                        <tr><td>Bahasa</td><td><?= htmlspecialchars($data['bahasa'] ?: '-') ?></td></tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <?php 
                                $status = $data['status'] ?? '-';
                                $badge_class = '';
                                if ($status == 'published') $badge_class = 'bg-success';
                                elseif ($status == 'draft') $badge_class = 'bg-warning';
                                elseif ($status == 'archived') $badge_class = 'bg-secondary';
                                else $badge_class = 'bg-info';
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                            </td>
                        </tr>
                        <tr><td>Dibuat Pada</td><td><?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></td></tr>
                        <tr><td>Diupdate Pada</td><td><?= date('d/m/Y H:i', strtotime($data['updated_at'])) ?></td></tr>
                    </table>
                </div>
            </div>

            <!-- ======================
                 ABSTRAK
            ====================== -->
            <?php if(!empty($data['abstrak'])): ?>
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="fas fa-file-alt"></i> ABSTRAK
                </div>
                <div class="card-body">
                    <div class="abstract-content">
                        <?= nl2br(htmlspecialchars($data['abstrak'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ======================
                 DOKUMEN DIGITAL
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
                                <a href="<?= htmlspecialchars($file_url) ?>" 
                                   target="_blank" 
                                   download="<?= htmlspecialchars($file_name_display) ?>" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Download PDF
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
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-3x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">File PDF Tidak Ditemukan!</h5>
                                <p class="mb-2">Dokumen PDF artikel ini tidak tersedia di server.</p>
                                <hr>
                                <div class="mb-3">
                                    <strong>Informasi:</strong><br>
                                    <small class="text-muted">
                                        Path database: <code><?= htmlspecialchars($file_path_db ?: 'NULL') ?></code><br>
                                        Artikel ID: <code><?= htmlspecialchars($data['id_artikel']) ?></code>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="?id=<?= $id ?>&debug=1" class="btn btn-warning btn-sm">
                                        <i class="fas fa-bug"></i> Debug Mode
                                    </a>
                                    <a href="artikel-hukum.php" class="btn btn-primary btn-sm">
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
                        <a href="artikel-hukum.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua Artikel
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
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- ARTICLE INFO -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Info Artikel</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hashtag"></i> ID</span>
                            <span class="badge bg-dark"><?= htmlspecialchars($data['id_artikel']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-tag"></i> Jenis</span>
                            <span class="badge bg-info"><?= htmlspecialchars($data['jenis_artikel']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar"></i> Tahun</span>
                            <span class="badge bg-warning"><?= htmlspecialchars($data['tahun_terbit']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-language"></i> Bahasa</span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($data['bahasa']) ?></span>
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
                        Hubungi Administrator Sistem
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

// Track download
document.querySelectorAll('a[download]').forEach(link => {
    link.addEventListener('click', function() {
        console.log('Download started:', this.href);
    });
});
</script>
<?php endif; ?>

<?php
include "footer.php";
mysqli_close($koneksi);
?>