<?php
// pokok-pikiran-dprd.php
session_start();
include_once "a_db.php";

// ============================================
// KONFIGURASI PATH YANG BENAR UNTUK MONOGRAFI
// ============================================
$base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/";
$monografi_pdf_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/";
$monografi_images_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/monografi/images/";

$web_monografi_pdf_path = "/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/";
$web_monografi_images_path = "/jdihcilacap_pasang/wb_admin/uploads/monografi/images/";

// ============================================
// FUNGSI BANTU UNTUK FILE
// ============================================

// Fungsi untuk menemukan file PDF di server
function findPDFFile($filename, $id = null) {
    global $monografi_pdf_path;
    
    if (empty($filename)) {
        return null;
    }
    
    // Bersihkan nama file
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    // Lokasi yang mungkin - UTAMA untuk monografi
    $possible_locations = [
        // Lokasi utama monografi
        $monografi_pdf_path . $clean_filename,
        
        // Format Windows
        str_replace('/', '\\', $monografi_pdf_path) . $clean_filename,
        
        // Document root relatif
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/' . $clean_filename,
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
                return $location;
            }
        }
    }
    
    return null;
}

// Fungsi untuk menemukan gambar
function findImageFile($filename, $id = null) {
    global $monografi_images_path;
    
    if (empty($filename)) {
        // Coba cari berdasarkan ID
        if ($id) {
            $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            foreach ($image_extensions as $ext) {
                $test_filename = $id . '.' . $ext;
                $test_path = $monografi_images_path . $test_filename;
                
                if (file_exists($test_path)) {
                    return $test_path;
                }
                
                // Coba dengan prefix
                $test_filename2 = 'monografi_' . $id . '.' . $ext;
                $test_path2 = $monografi_images_path . $test_filename2;
                
                if (file_exists($test_path2)) {
                    return $test_path2;
                }
            }
        }
        return null;
    }
    
    // Bersihkan nama file
    $filename = basename($filename);
    
    // Lokasi yang mungkin - UTAMA untuk monografi
    $possible_locations = [
        // Lokasi utama monografi
        $monografi_images_path . $filename,
        
        // Format Windows
        str_replace('/', '\\', $monografi_images_path) . $filename,
        
        // Document root relatif
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/monografi/images/' . $filename,
    ];
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            return $location;
        }
    }
    
    return null;
}

// ============================================
// QUERY DATA UTAMA - DARI TABEL MONOGRAFI
// Sesuai dengan struktur kolom yang diberikan
// ============================================
$query = "SELECT 
            id_monografi,
            judul,
            jenis_monografi,
            tanggal_pembuatan,
            tahun_terbit,
            penulis,
            penerbit,
            isbn,
            bidang_hukum,
            jumlah_halaman,
            deskripsi,
            status,
            bahasa,
            file_pdf,
            thumbnail,
            created_at,
            updated_at,
            id_user
          FROM monografi 
          WHERE jenis_monografi LIKE '%Pokok_Pikiran%' 
          ORDER BY id_monografi DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// ============================================
// TOTAL DOKUMEN
// ============================================
$total_query = "SELECT COUNT(*) as total 
                FROM monografi 
                WHERE jenis_monografi LIKE '%Pokok_Pikiran%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Pokok_Pikiran%'
    GROUP BY tahun_terbit
    ORDER BY tahun_terbit DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// DOKUMEN TERBARU
// ============================================
$popular_query = "
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit
    FROM monografi
    WHERE jenis_monografi LIKE '%Pokok_Pikiran%'
    ORDER BY created_at DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE jenis_monografi LIKE '%Pokok_Pikiran%'
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Pokok Pikiran DPRD - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan Pokok Pikiran Dewan Perwakilan Rakyat Daerah Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php 
// Include CSS dasar
include "style-keputusan-dprd.css"; 
?>

/* STYLE KHUSUS UNTUK GAMBAR */
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

/* TOMBOL DOWNLOAD */
.download-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.download-btn.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
}

/* Debug info */
.debug-info {
    font-size: 11px;
    border-left: 3px solid #dc3545;
    background: #f8f9fa;
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
}

/* Download Status */
.download-status {
    font-size: 11px;
    margin-top: 5px;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
}
.download-status.available {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.download-status.unavailable {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Custom untuk Pokok Pikiran */
.pokok-pikiran-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Styling untuk info tambahan */
.card-extra-info {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
    font-size: 12px;
    color: #666;
}

.card-extra-info span {
    margin-right: 15px;
    display: inline-block;
    margin-bottom: 5px;
}

.card-extra-info i {
    margin-right: 5px;
}

/* Tombol aksi */
.card-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.card-btn {
    flex: 1;
    min-width: 120px;
    text-align: center;
}

.force-download:hover {
    background-color: #218838;
    color: white;
}

/* Styling khusus untuk monografi */
.monografi-title {
    font-size: 1.1rem;
    line-height: 1.4;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">POKOK PIKIRAN DPRD</h1>
        <p class="subtitle">Pokok Pikiran Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Pokok Pikiran DPRD</li>
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

<!-- FILTER TAHUN -->
<div class="year-filter">
    <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h5>
    <div class="year-buttons">
        <button class="year-btn active" data-year="semua">Semua</button>
        <?php foreach ($years as $year): ?>
            <button class="year-btn" data-year="<?= $year ?>"><?= $year ?></button>
        <?php endforeach; ?>
    </div>
</div>

<h3 class="section-title">Daftar Pokok Pikiran DPRD Kota Surakarta</h3>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php 
$counter = 0;
while ($row = mysqli_fetch_assoc($result)):
    $counter++;
    $tanggal = date('d F Y', strtotime($row['tanggal_pembuatan']));
    
    // ============================================
    // CARI FILE PDF
    // ============================================
    $file_from_db = $row['file_pdf'];
    $file_found = false;
    $actual_file_path = null;
    $download_url = '#';
    $force_download_url = '#';
    
    if (!empty($file_from_db)) {
        $actual_file_path = findPDFFile($file_from_db, $row['id_monografi']);
        if ($actual_file_path) {
            $file_found = true;
            // URL untuk download langsung
            $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_file_path);
            $download_url = $relative_path . '?t=' . time(); // Tambah timestamp untuk cache busting
            
            // URL untuk force download
            $force_download_url = "force_download.php?file=" . urlencode($relative_path) . "&id=" . $row['id_monografi'] . "&type=monografi";
        }
    }
    
    // ============================================
    // CARI GAMBAR/THUMBNAIL
    // ============================================
    $image_url = '';
    $image_found = false;
    $actual_image_path = null;
    
    if (!empty($row['thumbnail'])) {
        $actual_image_path = findImageFile($row['thumbnail'], $row['id_monografi']);
        if ($actual_image_path) {
            $image_found = true;
            $relative_image_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_image_path);
            $image_url = $relative_image_path;
        }
    } else {
        // Jika tidak ada thumbnail di database, cari berdasarkan ID
        $actual_image_path = findImageFile(null, $row['id_monografi']);
        if ($actual_image_path) {
            $image_found = true;
            $relative_image_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_image_path);
            $image_url = $relative_image_path;
        }
    }
    
    // Pastikan URL gambar dimulai dengan /
    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
        $image_url = '/' . $image_url;
    }
    
    // Buat singkatan jenis
    $singkatan = "PKP";
    if (!empty($row['jenis_monografi'])) {
        $jenis = $row['jenis_monografi'];
        if (strpos($jenis, 'Pokok_Pikiran') !== false) {
            $singkatan = "PKP";
        }
    }
    
    // ============================================
    // URL UNTUK DETAIL - DIPERBAIKI
    // ============================================
    $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
?>

<div class="document-card" data-tahun="<?= $row['tahun_terbit']; ?>" data-id="<?= $row['id_monografi']; ?>">
    <div class="card-image">
        <?php if ($image_found && !empty($image_url)): ?>
            <img src="<?= htmlspecialchars($image_url) ?>" 
                 alt="<?= htmlspecialchars($row['judul']) ?>"
                 loading="lazy"
                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
            <div class="placeholder" style="display: none;">
                <i class="bi bi-lightbulb"></i>
                <p><?= htmlspecialchars($singkatan) ?><br>
                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
            </div>
        <?php else: ?>
            <div class="placeholder pokok-pikiran-badge">
                <i class="bi bi-lightbulb"></i>
                <p><?= htmlspecialchars($singkatan) ?><br>
                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge"><?= htmlspecialchars($singkatan) ?></span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number">Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></div>
        <h3 class="card-title monografi-title"><?= htmlspecialchars($row['judul']) ?></h3>

        <div class="card-meta">
            <div class="card-date">
                <i class="bi bi-calendar"></i> <?= $tanggal ?>
            </div>
            <?php if (!empty($row['status'])): ?>
                <div class="card-status"><?= htmlspecialchars($row['status']) ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($row['penulis'])): ?>
        <div class="card-extra-info">
            <span><i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?></span>
            <?php if (!empty($row['penerbit'])): ?>
            <span><i class="bi bi-building"></i> <?= htmlspecialchars($row['penerbit']) ?></span>
            <?php endif; ?>
            <?php if (!empty($row['jumlah_halaman'])): ?>
            <span><i class="bi bi-file-text"></i> <?= htmlspecialchars($row['jumlah_halaman']) ?> halaman</span>
            <?php endif; ?>
            <?php if (!empty($row['bidang_hukum'])): ?>
            <span><i class="bi bi-journal"></i> <?= htmlspecialchars($row['bidang_hukum']) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
            <?php if ($file_found): ?>
                <br><small><?= htmlspecialchars(basename($actual_file_path)) ?></small>
            <?php endif; ?>
        </div>

        <div class="card-actions">
            <!-- TOMBOL LIHAT DETAIL - DIPERBAIKI -->
            <a href="<?= $detail ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
            
            <!-- TOMBOL DOWNLOAD YANG DIPERBAIKI -->
            <?php if ($file_found): ?>
                <!-- Tombol Download Langsung -->
                <a href="<?= htmlspecialchars($download_url) ?>" 
                   class="card-btn btn-download download-btn" 
                   data-id="<?= $row['id_monografi']; ?>"
                   data-filename="<?= htmlspecialchars(basename($actual_file_path)) ?>"
                   download>
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php else: ?>
                <a href="#" 
                   class="card-btn btn-download disabled" 
                   onclick="event.preventDefault(); showFileNotFoundAlert('<?= htmlspecialchars($row['judul']) ?>');"
                   title="File PDF tidak tersedia">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php endif; ?>
        </div>
        
        <!-- DEBUG INFO -->
        <?php if (isset($_GET['debug'])): ?>
        <div class="debug-info mt-2 p-2 bg-light rounded">
            <small class="text-muted">
                <strong>Debug Info (Dokumen #<?= $counter ?>):</strong><br>
                ID: <?= $row['id_monografi'] ?><br>
                Jenis: <?= htmlspecialchars($row['jenis_monografi']) ?><br>
                File PDF dari DB: <?= htmlspecialchars($file_from_db) ?><br>
                File Found: <?= $file_found ? 'Ya' : 'Tidak' ?><br>
                Actual PDF Path: <?= htmlspecialchars($actual_file_path) ?><br>
                Download URL: <?= htmlspecialchars($download_url) ?><br>
                Thumbnail dari DB: <?= htmlspecialchars($row['thumbnail']) ?><br>
                Image Found: <?= $image_found ? 'Ya' : 'Tidak' ?><br>
                Actual Image Path: <?= htmlspecialchars($actual_image_path) ?><br>
                Image URL: <?= htmlspecialchars($image_url) ?><br>
                Detail URL: <?= htmlspecialchars($detail) ?><br>
                PDF Folder: <?= $monografi_pdf_path ?><br>
                Images Folder: <?= $monografi_images_path ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-files"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada pokok pikiran DPRD yang tersedia.
    </p>
</div>
<?php endif; ?>

<!-- SOLUSI ALTERNATIF JIKA DOWNLOAD MASIH TIDAK BERFUNGSI -->
<div class="alert alert-info mt-4">
    <h5><i class="bi bi-info-circle"></i> Panduan Download</h5>
    <p class="mb-2">Jika mengalami masalah download:</p>
    <ol class="mb-0">
        <li>Coba tombol <strong>"Download PDF"</strong> terlebih dahulu</li>
        <li>Jika tidak berhasil, gunakan tombol <strong>"Force Download"</strong></li>
        <li>Klik kanan pada tombol download dan pilih "Save link as..."</li>
        <li>Gunakan halaman detail dokumen untuk download</li>
        <li>Hubungi administrator jika masalah berlanjut</li>
    </ol>
</div>

<!-- INFO PATH -->
<?php if (isset($_GET['debug'])): ?>
<div class="alert alert-warning mt-3">
    <h5><i class="bi bi-folder"></i> Informasi Path Server</h5>
    <ul class="mb-0">
        <li>PDF Folder (monografi): <code><?= $monografi_pdf_path ?></code></li>
        <li>Images Folder (monografi): <code><?= $monografi_images_path ?></code></li>
        <li>Document Root: <code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></li>
        <li>File PDF dari DB contoh: <code><?= htmlspecialchars($file_from_db ?? 'Tidak ada contoh') ?></code></li>
    </ul>
</div>
<?php endif; ?>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<!-- Reset result pointer untuk sidebar -->
<?php
mysqli_data_seek($year_stats_result, 0);
mysqli_data_seek($popular_result, 0);
?>

<div class="sidebar-menu">
<h4><i class="bi bi-list"></i> Menu Perundangan</h4>
<ul>
<li><a href="index.php?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
<li><a href="keputusan-dprd.php"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
<li><a href="keputusan-pimpinan.php"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
<li><a href="keputusan-sekretariat.php"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris DPRD</a></li>
<li><a href="peraturan-dprd.php"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
<li><a href="perda.php"><i class="bi bi-journal-bookmark"></i> Peraturan Daerah</a></li>
<li class="active"><a href="#"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
<li><a href="index.php?page=english-legal"><i class="bi bi-translate"></i> English Legal Products</a></li>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik</h4>
<ul>
<li>Total: <strong><?= $total_dokumen ?> dokumen</strong></li>
<?php while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= $stat['tahun_terbit'] ?> : <strong><?= $stat['jumlah'] ?></strong> dokumen</li>
<?php endwhile; ?>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Dokumen Terbaru</h4>
<ul>
<?php while ($pop = mysqli_fetch_assoc($popular_result)): ?>
<li>
<!-- DIPERBAIKI JUGI DI SIDEBAR -->
<a href="detail-monografi.php?id=<?= $pop['id_monografi'] ?>">
<i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($pop['judul'], 0, 50)) ?>...
</a>
<small class="text-muted d-block">Tahun: <?= htmlspecialchars($pop['tahun_terbit']) ?></small>
<small class="text-muted"><?= date('d M Y', strtotime($pop['tanggal_pembuatan'])) ?></small>
</li>
<?php endwhile; ?>
</ul>
</div>

<!-- LINK CEK DOWNLOAD -->
<div class="sidebar-menu bg-light">
<h4><i class="bi bi-download"></i> Test Download</h4>
<p class="mb-2">Test apakah file PDF bisa diakses:</p>
<ul>
<li><a href="<?= $monografi_pdf_path ?>" target="_blank"><i class="bi bi-folder"></i> Lihat Folder PDF</a></li>
<li><a href="test_download.php" target="_blank"><i class="bi bi-gear"></i> Test Download Script</a></li>
<li><a href="force_download.php?test=1" target="_blank"><i class="bi bi-download"></i> Test Force Download</a></li>
</ul>
</div>

</div>
</div>
</div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// FILTER BERDASARKAN TAHUN
// ============================================
document.querySelectorAll(".year-btn").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedYear = btn.dataset.year;
        
        document.querySelectorAll(".document-card").forEach(card => {
            if (selectedYear === "semua" || card.dataset.tahun === selectedYear) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    });
});

// ============================================
// FUNGSI UNTUK NOTIFIKASI
// ============================================
function showFileNotFoundAlert(judul) {
    // Gunakan SweetAlert jika ada, atau alert biasa
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk dokumen <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk dokumen "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
    }
}

function showDownloadStarted(filename) {
    // Gunakan SweetAlert jika ada, atau alert biasa
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Download Dimulai',
            html: `File <strong>${filename}</strong> sedang didownload...<br><br>
                  <small>Jika download tidak dimulai, coba klik kanan dan "Save link as..."</small>`,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        console.log(`Download started: ${filename}`);
        alert(`Download file "${filename}" sedang dimulai.`);
    }
}

// ============================================
// HANDLING DOWNLOAD BUTTON
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Untuk tombol download yang aktif
    const downloadButtons = document.querySelectorAll('.download-btn:not(.disabled)');
    
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.getAttribute('data-id');
            const filename = this.getAttribute('data-filename');
            
            // Tampilkan notifikasi
            showDownloadStarted(filename);
            
            // Optional: Log ke console untuk debugging
            console.log('Download clicked:', {
                id: id,
                filename: filename,
                href: this.href
            });
            
            // Biarkan link bekerja secara normal
        });
    });
    
    // Untuk tombol force download
    const forceButtons = document.querySelectorAll('.force-download');
    forceButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.getAttribute('data-id');
            console.log('Force download clicked for ID:', id);
            // Biarkan link bekerja secara normal
        });
    });
    
    // Untuk tombol download yang disabled
    const disabledButtons = document.querySelectorAll('.btn-download.disabled');
    disabledButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.document-card');
            const judul = card.querySelector('.card-title').textContent;
            showFileNotFoundAlert(judul);
        });
    });
    
    // ============================================
    // ERROR HANDLING UNTUK GAMBAR
    // ============================================
    const images = document.querySelectorAll('.card-image img');
    
    images.forEach(img => {
        // Cek jika gambar sudah error
        if (img.complete && img.naturalHeight === 0) {
            const placeholder = img.parentElement.querySelector('.placeholder');
            if (placeholder) {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
        
        // Event listener untuk error gambar
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const placeholder = this.parentElement.querySelector('.placeholder');
            if (placeholder) {
                placeholder.style.display = 'flex';
            }
        });
    });
});

// ============================================
// FUNGSI ALTERNATIF DOWNLOAD
// ============================================
function downloadViaBlob(url, filename) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            console.error('Download error:', error);
            alert('Gagal mendownload file. Silakan coba tombol Force Download.');
        });
}

// ============================================
// TEST DOWNLOAD FUNCTION
// ============================================
function testDownload(id) {
    const testUrl = `test_download.php?id=${id}`;
    window.open(testUrl, '_blank');
}

// ============================================
// DEBUG FUNCTIONS
// ============================================
function showPathInfo() {
    alert('PDF Path: <?= $monografi_pdf_path ?>\nImages Path: <?= $monografi_images_path ?>');
}

// ============================================
// DEBUG DETAIL LINK
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const detailLinks = document.querySelectorAll('.btn-view');
    
    detailLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            console.log('Detail link clicked:', this.href);
            
            // Optional: Validasi link
            if (!this.href || this.href === '#') {
                e.preventDefault();
                alert('Link detail tidak valid!');
                return false;
            }
            
            // Optional: Buka di tab baru untuk testing (Shift+click)
            if (e.shiftKey || e.ctrlKey || e.metaKey) {
                // Biarkan browser menangani shortcut
                return true;
            }
            
            // Biarkan link bekerja normal
            return true;
        });
    });
});
</script>

<?php 
// ============================================
// DEBUG INFO TAMBAHAN
// ============================================
if (isset($_GET['debug'])) {
    echo "<!-- ========== DEBUG INFO ========== -->\n";
    echo "<!-- PHP Version: " . PHP_VERSION . " -->\n";
    echo "<!-- Script: " . $_SERVER['PHP_SELF'] . " -->\n";
    echo "<!-- Document Root: " . $_SERVER['DOCUMENT_ROOT'] . " -->\n";
    echo "<!-- Monografi PDF Path: " . $monografi_pdf_path . " -->\n";
    echo "<!-- Monografi Images Path: " . $monografi_images_path . " -->\n";
    
    // Cek apakah folder PDF ada dan bisa diakses
    if (is_dir($monografi_pdf_path)) {
        echo "<!-- PDF folder exists -->\n";
        
        // Coba akses beberapa file
        $test_files = glob($monografi_pdf_path . "*.pdf");
        echo "<!-- Found " . count($test_files) . " PDF files in monografi folder -->\n";
        
        // Tampilkan 5 file pertama
        $sample_count = 0;
        foreach ($test_files as $file) {
            if ($sample_count < 5) {
                echo "<!-- Sample PDF: " . basename($file) . " -->\n";
                $sample_count++;
            }
        }
    } else {
        echo "<!-- WARNING: Monografi PDF folder NOT found -->\n";
    }
    
    // Cek apakah folder images ada
    if (is_dir($monografi_images_path)) {
        echo "<!-- Images folder exists -->\n";
        $image_files = glob($monografi_images_path . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
        echo "<!-- Found " . count($image_files) . " image files -->\n";
    }
    
    echo "<!-- ========== END DEBUG INFO ========== -->\n";
}

mysqli_close($koneksi); 
?>