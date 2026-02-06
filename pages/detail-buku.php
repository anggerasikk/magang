[file name]: detail-buku.php
[file content begin]
<?php
// detail-buku.php - DETAIL BUKU DENGAN DOWNLOAD DIRECT
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

session_start();
include_once "a_db.php";

// ============================================
// NAMESPACED FUNCTIONS UNTUK DETAIL BUKU
// ============================================

if (!function_exists('detail_buku_decode_token')) {
    function detail_buku_decode_token($token) {
        if (empty($token)) return 0;
        
        $token = urldecode($token);
        $decoded = base64_decode($token);
        
        if (!$decoded) return 0;
        
        $parts = explode('_', $decoded);
        
        if (count($parts) >= 3 && $parts[0] === 'jdih' && $parts[1] === 'buku') {
            $id = (int)$parts[2];
            return $id > 0 ? $id : 0;
        }
        
        return 0;
    }
}

if (!function_exists('detail_buku_find_file')) {
    function detail_buku_find_file($filename, $id = null) {
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

if (!function_exists('detail_buku_path_to_url')) {
    function detail_buku_path_to_url($physical_path) {
        $doc_root = $_SERVER['DOCUMENT_ROOT'];
        $doc_root = str_replace('\\', '/', $doc_root);
        $physical_path = str_replace('\\', '/', $physical_path);
        
        if (strpos($physical_path, $doc_root) === 0) {
            $url_path = substr($physical_path, strlen($doc_root));
            if (strpos($url_path, '/') !== 0) {
                $url_path = '/' . $url_path;
            }
            return $url_path;
        }
        
        return null;
    }
}

if (!function_exists('detail_buku_format_size')) {
    function detail_buku_format_size($bytes) {
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

if (!function_exists('detail_buku_tgl')) {
    function detail_buku_tgl($tgl) {
        if (empty($tgl) || $tgl == '0000-00-00' || $tgl === null) return '-';

        $bulan = array(
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        );

        $english_date = date('d F Y', strtotime((string)$tgl));
        return str_replace(array_keys($bulan), array_values($bulan), $english_date);
    }
}

if (!function_exists('detail_buku_handle_download')) {
    function detail_buku_handle_download($book_id) {
        global $koneksi;
        
        // Cek jika ini request download
        if (isset($_GET['action']) && $_GET['action'] == 'download' && $_GET['book_id'] == $book_id) {
            
            // Query untuk mendapatkan data buku
            $query = "SELECT judul, file_buku_path FROM buku_perpustakaan WHERE id_buku = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $book_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $file_path = detail_buku_find_file($row['file_buku_path'], $book_id);
                
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
                    error_log("DETAIL BUKU DOWNLOAD: " . $row['judul'] . " | ID: " . $book_id . " | IP: " . $_SERVER['REMOTE_ADDR']);
                    
                    exit; // Stop execution setelah download
                }
            }
        }
        // Jika bukan request download, lanjutkan ke halaman normal
    }
}

// ============================================
// AMBIL DAN VALIDASI TOKEN
// ============================================
$id = 0;
$debug_mode = isset($_GET['debug']) ? (int)$_GET['debug'] : 0;

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    $id = detail_buku_decode_token($token);
    
    if ($debug_mode) {
        error_log("Token: " . $token);
        error_log("Decoded ID: " . $id);
    }
}

if ($id <= 0) {
    header("Location: buku.php?error=invalid_token");
    exit;
}

// ============================================
// HANDLE DOWNLOAD REQUEST
// ============================================
detail_buku_handle_download($id);

// ============================================
// QUERY DATABASE
// ============================================
$query = "SELECT * FROM buku_perpustakaan WHERE id_buku = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Error: Gagal menyiapkan query database");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: buku.php?error=doc_not_found");
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ============================================
// CEK FILE PDF
// ============================================
$pdf_file = $data['file_buku_path'] ?? '';
$actual_pdf_path = detail_buku_find_file($pdf_file, $data['id_buku']);
$file_found = ($actual_pdf_path && file_exists($actual_pdf_path));
$file_size = $file_found ? detail_buku_format_size(filesize($actual_pdf_path)) : '0 MB';
$file_name_display = $file_found ? basename($actual_pdf_path) : '';
$file_time = $file_found ? filemtime($actual_pdf_path) : 0;

// URL untuk view dan download
if ($file_found) {
    $pdf_url = detail_buku_path_to_url($actual_pdf_path);
    if ($pdf_url) {
        // URL untuk view (preview only)
        $view_url = $pdf_url . '?t=' . time();
        
        // URL untuk download langsung dari halaman ini
        $download_url = "detail-buku.php?token=" . urlencode($_GET['token']) . "&action=download&book_id=" . $id . "&t=" . time();
    }
}

// Status configuration
$status_config = [
    'tersedia' => ['badge' => 'bg-success', 'text' => 'Tersedia'],
    'dipinjam' => ['badge' => 'bg-warning', 'text' => 'Dipinjam'],
    'hilang' => ['badge' => 'bg-danger', 'text' => 'Hilang'],
    'rusak' => ['badge' => 'bg-secondary', 'text' => 'Rusak']
];
$status = $data['status_ketersediaan'] ?? 'tersedia';
$status_info = $status_config[$status] ?? ['badge' => 'bg-info', 'text' => ucfirst($status)];

$page_title = htmlspecialchars($data['judul']) . " - Buku Perpustakaan";
include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* Additional styles for detail page */
.pdf-viewer-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    height: 700px;
}

.pdf-viewer {
    width: 100%;
    height: 100%;
    border: none;
}

.file-info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.metadata-table {
    width: 100%;
    border-collapse: collapse;
}

.metadata-table td {
    padding: 8px 12px;
    border-bottom: 1px solid #dee2e6;
}

.metadata-table tr:last-child td {
    border-bottom: none;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
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

.book-cover-container {
    text-align: center;
    margin-bottom: 20px;
}

.book-cover {
    max-width: 200px;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.no-pdf-message {
    text-align: center;
    padding: 40px 20px;
}

.no-pdf-icon {
    font-size: 64px;
    color: #dc3545;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .pdf-viewer-container {
        height: 500px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="buku.php">Buku Perpustakaan</a></li>
            <li class="breadcrumb-item active">Detail Buku</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="buku.php" class="btn btn-light mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Buku Perpustakaan
    </a>

    <!-- DOWNLOAD PROCESSING OVERLAY -->
    <div class="download-processing" id="downloadProcessing">
        <div class="download-spinner"></div>
    </div>

    <?php if($debug_mode): ?>
    <div class="alert alert-info mb-4">
        <h5><i class="bi bi-bug"></i> Debug Information</h5>
        <p><strong>Token:</strong> <?= htmlspecialchars($_GET['token'] ?? '') ?></p>
        <p><strong>ID:</strong> <?= $id ?></p>
        <p><strong>PDF Path:</strong> <?= htmlspecialchars($pdf_file ?? 'NULL') ?></p>
        <p><strong>Actual Path:</strong> <?= htmlspecialchars($actual_pdf_path ?? 'NULL') ?></p>
        <p><strong>File Found:</strong> <?= $file_found ? 'Ya' : 'Tidak' ?></p>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- HEADER BUKU -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-book"></i> 
                        <?= htmlspecialchars($data['judul']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">
                                <i class="bi bi-person"></i> <?= htmlspecialchars($data['pengarang']) ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-calendar"></i> 
                                Tahun Terbit: <?= htmlspecialchars($data['tahun_terbit'] ?? '-') ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if($file_found): ?>
                            <span class="badge bg-success fs-6 p-2">
                                <i class="bi bi-check-circle"></i> File Tersedia
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger fs-6 p-2">
                                <i class="bi bi-exclamation-circle"></i> File Tidak Ditemukan
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- METADATA -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Metadata Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="metadata-table">
                                <tr>
                                    <td width="40%"><strong>Bahasa</strong></td>
                                    <td><?= htmlspecialchars($data['bahasa'] ?? 'Indonesia') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Judul</strong></td>
                                    <td><?= htmlspecialchars($data['judul']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Pengarang</strong></td>
                                    <td><?= htmlspecialchars($data['pengarang']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Penerbit</strong></td>
                                    <td><?= htmlspecialchars($data['penerbit'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tahun Terbit</strong></td>
                                    <td><?= htmlspecialchars($data['tahun_terbit'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>ISBN</strong></td>
                                    <td><?= htmlspecialchars($data['isbn'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="metadata-table">
                                <tr>
                                    <td width="40%"><strong>Bidang Hukum</strong></td>
                                    <td><?= htmlspecialchars($data['bidang_hukum'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td><span class="badge <?= $status_info['badge'] ?>"><?= $status_info['text'] ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Subjek/Topik</strong></td>
                                    <td><?= htmlspecialchars($data['subjek_topik'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Panggil</strong></td>
                                    <td><?= htmlspecialchars($data['nomor_panggil'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi Rak</strong></td>
                                    <td><?= htmlspecialchars($data['lokasi_rak'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Masuk</strong></td>
                                    <td><?= detail_buku_tgl($data['tanggal_masuk']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SINOPSIS -->
            <?php if(!empty($data['sinopsis'])): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-chat-text"></i> Sinopsis</h5>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($data['sinopsis'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- FILE DIGITAL -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> File Digital Buku</h5>
                </div>
                <div class="card-body">
                    <?php if($file_found): ?>
                    <div class="file-info-card">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <div class="col-md-7">
                                <h6><?= htmlspecialchars($file_name_display) ?></h6>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-hdd"></i> Ukuran: <?= $file_size ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-calendar"></i> 
                                    Diupdate: <?= date('d/m/Y H:i', $file_time) ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <div class="action-buttons">
                                    <a href="<?= htmlspecialchars($view_url) ?>" target="_blank" class="btn btn-primary">
                                        <i class="bi bi-eye"></i> Lihat di Tab Baru
                                    </a>
                                    <a href="<?= htmlspecialchars($download_url) ?>" 
                                       class="btn btn-success detail-download-btn"
                                       data-book-id="<?= $id ?>"
                                       data-book-title="<?= htmlspecialchars($data['judul']) ?>"
                                       data-filename="<?= htmlspecialchars($file_name_display) ?>">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PDF VIEWER (PREVIEW ONLY) -->
                    <div class="pdf-viewer-container mt-4">
                        <iframe id="pdfViewer" src="<?= htmlspecialchars($view_url) ?>" 
                                class="pdf-viewer" 
                                type="application/pdf"
                                title="Preview Buku: <?= htmlspecialchars($data['judul']) ?>">
                        </iframe>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Catatan:</strong> File di atas hanya preview. Klik tombol <strong>"Download"</strong> untuk menyimpan file ke komputer Anda.
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger no-pdf-message">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-exclamation-triangle-fill no-pdf-icon"></i>
                            <div>
                                <h5 class="alert-heading">File Tidak Ditemukan!</h5>
                                <p class="mb-0">File digital buku tidak tersedia di server.</p>
                                <p class="small mt-2">Silakan hubungi admin perpustakaan untuk informasi lebih lanjut.</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- BOOK COVER -->
            <?php 
            $cover_path = $data['sampul_path'] ?? '';
            $cover_found = false;
            $cover_url = '';
            
            if (!empty($cover_path)) {
                $cover_full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($cover_path, '/');
                if (file_exists($cover_full_path)) {
                    $cover_found = true;
                    $cover_url = '/' . ltrim($cover_path, '/');
                }
            }
            ?>
            
            <?php if($cover_found): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-image"></i> Sampul Buku</h6>
                </div>
                <div class="card-body text-center">
                    <img src="<?= htmlspecialchars($cover_url) ?>" 
                         alt="Sampul <?= htmlspecialchars($data['judul']) ?>" 
                         class="book-cover img-fluid mb-3">
                    <p class="small text-muted mb-0">Cover buku mungkin berbeda dengan versi fisik</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- QUICK ACTIONS -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($file_found): ?>
                        <a href="<?= htmlspecialchars($view_url) ?>" target="_blank" class="btn btn-primary">
                            <i class="bi bi-eye"></i> Lihat di Tab Baru
                        </a>
                        <a href="<?= htmlspecialchars($download_url) ?>" 
                           class="btn btn-success detail-download-btn"
                           data-book-id="<?= $id ?>"
                           data-book-title="<?= htmlspecialchars($data['judul']) ?>"
                           data-filename="<?= htmlspecialchars($file_name_display) ?>">
                            <i class="bi bi-download"></i> Download File
                        </a>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="bi bi-eye"></i> Lihat (Tidak Tersedia)
                        </button>
                        <button class="btn btn-secondary" disabled>
                            <i class="bi bi-download"></i> Download (Tidak Tersedia)
                        </button>
                        <?php endif; ?>
                        <a href="buku.php" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> Lihat Semua Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- FILE INFO -->
            <?php if($file_found): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-file-text"></i> Informasi File</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-file-earmark"></i> Nama File</span>
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($file_name_display) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-hdd"></i> Ukuran</span>
                            <span class="badge bg-primary"><?= $file_size ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-calendar"></i> Diupdate</span>
                            <span class="badge bg-light text-dark"><?= date('d/m/Y', $file_time) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-filetype-pdf"></i> Format</span>
                            <span class="badge bg-danger"><?= strtoupper(pathinfo($actual_pdf_path, PATHINFO_EXTENSION)) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- KETERSEDIAAN -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-archive"></i> Ketersediaan</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Eksemplar</span>
                            <span class="badge bg-primary"><?= htmlspecialchars($data['jumlah_eksemplar'] ?? 0) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tersedia</span>
                            <span class="badge bg-success"><?= htmlspecialchars($data['jumlah_tersedia'] ?? 0) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Dipinjam</span>
                            <span class="badge bg-danger"><?= htmlspecialchars(($data['jumlah_eksemplar'] ?? 0) - ($data['jumlah_tersedia'] ?? 0)) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Status</span>
                            <span class="badge <?= $status_info['badge'] ?>"><?= $status_info['text'] ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// DETAIL BUKU JAVASCRIPT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Auto adjust PDF viewer height
    function detail_adjustViewerHeight() {
        const viewer = document.getElementById('pdfViewer');
        if (viewer) {
            const windowHeight = window.innerHeight;
            const viewerTop = viewer.getBoundingClientRect().top;
            const padding = 50;
            
            const newHeight = windowHeight - viewerTop - padding;
            viewer.style.height = Math.max(500, newHeight) + 'px';
        }
    }
    
    window.addEventListener('resize', detail_adjustViewerHeight);
    detail_adjustViewerHeight();
    
    // Handle download button clicks
    document.querySelectorAll('.detail-download-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const bookId = this.getAttribute('data-book-id');
            const bookTitle = this.getAttribute('data-book-title');
            const filename = this.getAttribute('data-filename');
            const downloadUrl = this.getAttribute('href');
            
            // Tampilkan loading spinner
            const processingOverlay = document.getElementById('downloadProcessing');
            if (processingOverlay) {
                processingOverlay.style.display = 'flex';
            }
            
            // Track download
            detail_trackDownload(bookId, bookTitle, filename);
            
            // Trigger download setelah delay untuk UX
            setTimeout(() => {
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
    
    // Handle view in new tab
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        link.addEventListener('click', function(e) {
            console.log('Opening PDF in new tab:', this.href);
            // Optional: Track view events
            if (typeof gtag !== 'undefined') {
                gtag('event', 'pdf_preview_view', {
                    'event_category': 'book_detail',
                    'event_label': '<?= htmlspecialchars($data['judul']) ?>'
                });
            }
        });
    });
    
    // Handle PDF viewer untuk mencegah auto-download
    const pdfViewer = document.getElementById('pdfViewer');
    if (pdfViewer) {
        pdfViewer.addEventListener('load', function() {
            console.log('PDF preview loaded successfully');
        });
        
        pdfViewer.addEventListener('error', function() {
            console.warn('PDF preview might trigger download');
            // Show alternative message
            this.style.display = 'none';
            const container = this.parentElement;
            const errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-warning mt-3';
            errorMsg.innerHTML = `
                <i class="bi bi-exclamation-triangle"></i>
                <strong>PDF tidak dapat ditampilkan:</strong> 
                Browser Anda tidak mendukung preview PDF. 
                Silakan klik tombol "Lihat di Tab Baru" atau "Download".
            `;
            container.parentElement.insertBefore(errorMsg, container.nextSibling);
        });
    }
});

// Fungsi untuk tracking download di detail page
function detail_trackDownload(bookId, bookTitle, filename) {
    console.log('DETAIL BOOK DOWNLOAD:', {
        id: bookId,
        title: bookTitle,
        filename: filename,
        timestamp: new Date().toISOString()
    });
    
    // Show notification
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Memulai Download',
            html: `Buku <strong>"${bookTitle}"</strong> sedang didownload...`,
            timer: 2000,
            showConfirmButton: false
        });
    }
    
    // Analytics tracking
    if (typeof gtag !== 'undefined') {
        gtag('event', 'detail_book_download', {
            'event_category': 'library',
            'event_label': bookTitle,
            'value': 1
        });
    }
    
    // Optional: Send to server log
    const formData = new FormData();
    formData.append('book_id', bookId);
    formData.append('book_title', bookTitle);
    formData.append('action', 'track_download');
    
    fetch('detail-buku-ajax.php', {
        method: 'POST',
        body: formData
    }).catch(error => {
        console.error('Download tracking failed:', error);
    });
}

// Handle browser back button after download
window.addEventListener('pageshow', function(event) {
    const processingOverlay = document.getElementById('downloadProcessing');
    if (processingOverlay && processingOverlay.style.display === 'flex') {
        processingOverlay.style.display = 'none';
    }
});
</script>

<?php
include "footer.php";
mysqli_close($koneksi);
?>
[file content end]