<?php
// hasil-harmonisasi.php
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
// Untuk Hasil Harmonisasi Peraturan
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
          WHERE jenis_monografi LIKE '%Harmonisasi%' OR 
                jenis_monografi LIKE '%harmonisasi%' OR
                judul LIKE '%Harmonisasi%' OR
                judul LIKE '%harmonisasi%'
          ORDER BY tahun_terbit DESC, id_monografi DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// ============================================
// TOTAL DOKUMEN
// ============================================
$total_query = "SELECT COUNT(*) as total 
                FROM monografi 
                WHERE jenis_monografi LIKE '%Harmonisasi%' OR 
                      jenis_monografi LIKE '%harmonisasi%' OR
                      judul LIKE '%Harmonisasi%' OR
                      judul LIKE '%harmonisasi%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Harmonisasi%' OR 
          jenis_monografi LIKE '%harmonisasi%' OR
          judul LIKE '%Harmonisasi%' OR
          judul LIKE '%harmonisasi%'
    GROUP BY tahun_terbit
    ORDER BY tahun_terbit DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// DOKUMEN TERBARU
// ============================================
$recent_query = "
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit
    FROM monografi
    WHERE jenis_monografi LIKE '%Harmonisasi%' OR 
          jenis_monografi LIKE '%harmonisasi%' OR
          judul LIKE '%Harmonisasi%' OR
          judul LIKE '%harmonisasi%'
    ORDER BY created_at DESC
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);

// ============================================
// JENIS DOKUMEN STATS
// ============================================
$jenis_stats_query = "
    SELECT jenis_monografi, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Harmonisasi%' OR 
          jenis_monografi LIKE '%harmonisasi%' OR
          judul LIKE '%Harmonisasi%' OR
          judul LIKE '%harmonisasi%'
    GROUP BY jenis_monografi
    ORDER BY jumlah DESC
    LIMIT 5";
$jenis_stats_result = mysqli_query($koneksi, $jenis_stats_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Harmonisasi%' OR 
           jenis_monografi LIKE '%harmonisasi%' OR
           judul LIKE '%Harmonisasi%' OR
           judul LIKE '%harmonisasi%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Hasil Harmonisasi Peraturan - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan hasil harmonisasi peraturan dan perundang-undangan";

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

/* STYLE KHUSUS UNTUK HASIL HARMONISASI */
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">HASIL HARMONISASI PERATURAN</h1>
        <p class="subtitle">Kumpulan hasil harmonisasi peraturan dan perundang-undangan</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Hasil Harmonisasi</li>
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

<!-- HEADER HASIL HARMONISASI -->
<div class="monografi-container">
    <div class="monografi-header">
        <div class="monografi-icon">
            <i class="bi bi-shuffle"></i>
        </div>
        <h2 class="section-title">Hasil Harmonisasi Peraturan</h2>
        <p class="monografi-description">
            Dokumentasi hasil harmonisasi peraturan dan perundang-undangan untuk menjamin 
            konsistensi, kepastian hukum, dan keselarasan antar peraturan yang berlaku 
            di wilayah Kota Surakarta.
        </p>
    </div>
    
    <!-- PENCARIAN -->
    <div class="year-filter">
        <h5>Cari Hasil Harmonisasi</h5>
        <form class="search-form" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Masukkan kata kunci harmonisasi..." 
                       aria-label="Search" id="searchKeyword">
                <button class="btn btn-search" type="submit" id="button-search">
                    <i class="bi bi-search"></i> CARI
                </button>
            </div>
        </form>
    </div>
    
    <!-- STATISTIK -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number"><?= $total_dokumen ?></div>
                <div class="stats-label">Total Dokumen</div>
            </div>
        </div>
        <?php
        // Hitung berdasarkan status (simulasi)
        $selesai_query = "SELECT COUNT(*) as total FROM monografi 
                         WHERE (jenis_monografi LIKE '%Harmonisasi%' OR 
                                jenis_monografi LIKE '%harmonisasi%' OR
                                judul LIKE '%Harmonisasi%' OR
                                judul LIKE '%harmonisasi%')
                         AND tahun_terbit >= YEAR(CURDATE()) - 2";
        $selesai_result = mysqli_query($koneksi, $selesai_query);
        $selesai_count = mysqli_fetch_assoc($selesai_result)['total'];
        
        $proses_query = "SELECT COUNT(*) as total FROM monografi 
                        WHERE (jenis_monografi LIKE '%Harmonisasi%' OR 
                               jenis_monografi LIKE '%harmonisasi%' OR
                               judul LIKE '%Harmonisasi%' OR
                               judul LIKE '%harmonisasi%')
                        AND tahun_terbit BETWEEN YEAR(CURDATE()) - 5 AND YEAR(CURDATE()) - 3";
        $proses_result = mysqli_query($koneksi, $proses_query);
        $proses_count = mysqli_fetch_assoc($proses_result)['total'];
        
        $rencana_query = "SELECT COUNT(*) as total FROM monografi 
                         WHERE (jenis_monografi LIKE '%Harmonisasi%' OR 
                                jenis_monografi LIKE '%harmonisasi%' OR
                                judul LIKE '%Harmonisasi%' OR
                                judul LIKE '%harmonisasi%')
                         AND tahun_terbit < YEAR(CURDATE()) - 5";
        $rencana_result = mysqli_query($koneksi, $rencana_query);
        $rencana_count = mysqli_fetch_assoc($rencana_result)['total'];
        ?>
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number"><?= $selesai_count ?></div>
                <div class="stats-label">Selesai Diharmonisasikan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number"><?= $proses_count ?></div>
                <div class="stats-label">Dalam Proses</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number"><?= $rencana_count ?></div>
                <div class="stats-label">Rencana Harmonisasi</div>
            </div>
        </div>
    </div>
    
    <!-- FILTER STATUS -->
    <div class="year-filter">
        <h5>Filter Berdasarkan Status</h5>
        <div class="year-buttons">
            <button class="harmonisasi-filter-btn active" data-status="semua">Semua</button>
            <button class="harmonisasi-filter-btn" data-status="selesai">Selesai</button>
            <button class="harmonisasi-filter-btn" data-status="proses">Dalam Proses</button>
            <button class="harmonisasi-filter-btn" data-status="rencana">Rencana</button>
        </div>
    </div>

    <h3 class="section-title mt-4">Daftar Hasil Harmonisasi Peraturan</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="card-container" id="documentsContainer">
    <?php 
    $counter = 0;
    mysqli_data_seek($result, 0); // Reset pointer
    
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
        
        if (!empty($file_from_db)) {
            $actual_file_path = findPDFFile($file_from_db, $row['id_monografi']);
            if ($actual_file_path) {
                $file_found = true;
                // URL untuk download langsung
                $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_file_path);
                $download_url = $relative_path . '?t=' . time();
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
        
        // Tentukan status harmonisasi berdasarkan tahun
        $tahun = $row['tahun_terbit'];
        $status = '';
        $status_class = '';
        $status_text = '';
        $status_icon = '';
        
        if ($tahun >= date('Y') - 2) {
            $status = 'selesai';
            $status_class = 'status-selesai';
            $status_text = 'Selesai Diharmonisasikan';
            $status_icon = 'bi bi-check-circle';
        } elseif ($tahun >= date('Y') - 5) {
            $status = 'proses';
            $status_class = 'status-proses';
            $status_text = 'Dalam Proses';
            $status_icon = 'bi bi-arrow-repeat';
        } else {
            $status = 'rencana';
            $status_class = 'status-rencana';
            $status_text = 'Rencana Harmonisasi';
            $status_icon = 'bi bi-calendar-check';
        }
        
        // Tentukan jenis harmonisasi
        $jenis_harmonisasi = 'Harmonisasi Peraturan';
        if (strpos($row['judul'], 'UUD') !== false) {
            $jenis_harmonisasi = 'Harmonisasi dengan UUD 1945';
        } elseif (strpos($row['judul'], 'UU') !== false) {
            $jenis_harmonisasi = 'Harmonisasi dengan UU';
        } elseif (strpos($row['judul'], 'Perda') !== false) {
            $jenis_harmonisasi = 'Harmonisasi Perda';
        } elseif (strpos($row['judul'], 'Walikota') !== false || strpos($row['judul'], 'Bupati') !== false) {
            $jenis_harmonisasi = 'Harmonisasi Peraturan Walikota/Bupati';
        }
        
        // URL untuk detail
        $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
        
        // Buat singkatan
        $singkatan = "HMN";
        if (!empty($row['jenis_monografi'])) {
            $jenis = $row['jenis_monografi'];
            if (strpos($jenis, 'Harmonisasi') !== false) {
                $singkatan = "HMN";
            }
        }
    ?>

    <div class="document-card harmonisasi-card" data-status="<?= $status ?>" data-tahun="<?= $tahun ?>" data-id="<?= $row['id_monografi']; ?>">
        <div class="harmonisasi-badge">HASIL HARMONISASI</div>
        <div class="card-image">
            <?php if ($image_found && !empty($image_url)): ?>
                <img src="<?= htmlspecialchars($image_url) ?>" 
                     alt="<?= htmlspecialchars($row['judul']) ?>"
                     loading="lazy"
                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
                <div class="placeholder" style="display: none;">
                    <i class="bi bi-shuffle"></i>
                    <p><?= htmlspecialchars($singkatan) ?><br>
                    <small>Tahun <?= htmlspecialchars($tahun) ?></small></p>
                </div>
            <?php else: ?>
                <div class="placeholder">
                    <i class="bi bi-shuffle"></i>
                    <p><?= htmlspecialchars($singkatan) ?><br>
                    <small>Tahun <?= htmlspecialchars($tahun) ?></small></p>
                </div>
            <?php endif; ?>
            <div class="image-overlay">
                <span class="image-badge"><?= htmlspecialchars($tahun) ?></span>
            </div>
        </div>

        <div class="card-content">
            <div class="card-number"><?= htmlspecialchars($row['jenis_monografi']) ?></div>
            <h4 class="card-title">
                <a href="<?= $detail ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['judul']) ?></a>
            </h4>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="harmonisasi-status <?= $status_class ?>">
                    <i class="<?= $status_icon ?>"></i> <?= $status_text ?>
                </span>
                <span class="harmonisasi-type">
                    <i class="bi bi-tags"></i> <?= $jenis_harmonisasi ?>
                </span>
            </div>

            <div class="card-meta">
                <div class="card-date">
                    <i class="bi bi-calendar"></i> <?= $tanggal ?>
                </div>
                <div class="card-status">Tahun: <?= htmlspecialchars($tahun) ?></div>
            </div>

            <?php if (!empty($row['penulis']) || !empty($row['bidang_hukum']) || !empty($row['jumlah_halaman'])): ?>
            <div class="card-extra-info">
                <?php if (!empty($row['penulis'])): ?>
                <span><i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?></span>
                <?php endif; ?>
                <?php if (!empty($row['bidang_hukum'])): ?>
                <span><i class="bi bi-journal"></i> <?= htmlspecialchars($row['bidang_hukum']) ?></span>
                <?php endif; ?>
                <?php if (!empty($row['jumlah_halaman'])): ?>
                <span><i class="bi bi-file-text"></i> <?= htmlspecialchars($row['jumlah_halaman']) ?> halaman</span>
                <?php endif; ?>
                <?php if (!empty($row['bahasa'])): ?>
                <span><i class="bi bi-translate"></i> <?= htmlspecialchars($row['bahasa']) ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($row['deskripsi'])): ?>
            <div class="legal-info">
                <div class="legal-title">Ringkasan Harmonisasi:</div>
                <p><?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 200))) ?><?= strlen($row['deskripsi']) > 200 ? '...' : '' ?></p>
            </div>
            <?php endif; ?>

            <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
            </div>

            <div class="card-actions">
                <!-- TOMBOL LIHAT DETAIL -->
                <a href="<?= $detail ?>" class="card-btn btn-view">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                
                <!-- TOMBOL DOWNLOAD -->
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
        </div>
    </div>

    <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="no-data">
        <i class="bi bi-shuffle"></i>
        <h4>Tidak Ada Data</h4>
        <p class="text-muted mb-0">
            Tidak ada hasil harmonisasi yang tersedia di database.
        </p>
        <?php 
        // Debug: Tampilkan jenis monografi yang ada
        $debug_query = "SELECT DISTINCT jenis_monografi FROM monografi WHERE jenis_monografi LIKE '%Harmonisasi%' LIMIT 10";
        $debug_result = mysqli_query($koneksi, $debug_query);
        if (mysqli_num_rows($debug_result) > 0): ?>
        <div class="alert alert-info mt-3">
            <small>Contoh jenis monografi harmonisasi yang tersedia:<br>
            <?php while ($debug_row = mysqli_fetch_assoc($debug_result)): ?>
                <?= htmlspecialchars($debug_row['jenis_monografi']) ?><br>
            <?php endwhile; ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- INFORMASI HARMONISASI -->
    <div class="year-filter mt-5">
        <h4 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Proses Harmonisasi</h4>
        <div class="alert alert-info" role="alert">
            <h5 class="alert-heading"><i class="bi bi-shuffle me-2"></i>Tahapan Harmonisasi Peraturan</h5>
            <p class="mb-2">Proses harmonisasi peraturan meliputi:</p>
            <ol class="mb-0">
                <li>Identifikasi peraturan yang saling bertentangan</li>
                <li>Analisis kesesuaian dengan peraturan yang lebih tinggi</li>
                <li>Penyusunan rekomendasi harmonisasi</li>
                <li>Koordinasi dengan instansi terkait</li>
                <li>Penetapan hasil harmonisasi</li>
                <li>Implementasi dan monitoring</li>
            </ol>
        </div>
    </div>
</div>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<!-- Reset result pointer untuk sidebar -->
<?php
mysqli_data_seek($year_stats_result, 0);
mysqli_data_seek($recent_result, 0);
mysqli_data_seek($jenis_stats_result, 0);
?>

<div class="sidebar-menu">
<h4><i class="bi bi-shuffle"></i> Menu Harmonisasi</h4>
<ul>
<li><a href="index.php?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
<li><a href="keputusan-dprd.php"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
<li><a href="keputusan-pimpinan.php"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
<li><a href="keputusan-sekretariat.php"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris DPRD</a></li>
<li><a href="peraturan-dprd.php"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
<li><a href="perda.php"><i class="bi bi-journal-bookmark"></i> Peraturan Daerah</a></li>
<li><a href="pokok-pikiran-dprd.php"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
<li><a href="dokumen-langka.php"><i class="bi bi-gem"></i> Dokumen Langka</a></li>
<li class="active"><a href="#"><i class="bi bi-shuffle"></i> Hasil Harmonisasi</a></li>
<li><a href="index.php?page=english-legal"><i class="bi bi-translate"></i> English Legal Products</a></li>
</ul>
</div>

<!-- STATISTIK HARMONISASI -->
<div class="sidebar-menu">
    <h4><i class="bi bi-bar-chart"></i> Statistik Harmonisasi</h4>
    <ul>
        <li class="d-flex justify-content-between">
            <span>Total Dokumen</span>
            <span class="badge bg-primary rounded-pill"><?= $total_dokumen ?></span>
        </li>
        <li class="d-flex justify-content-between">
            <span>Selesai Diharmonisasikan</span>
            <span class="badge bg-success rounded-pill"><?= $selesai_count ?></span>
        </li>
        <li class="d-flex justify-content-between">
            <span>Dalam Proses</span>
            <span class="badge bg-warning rounded-pill"><?= $proses_count ?></span>
        </li>
        <li class="d-flex justify-content-between">
            <span>Rencana Harmonisasi</span>
            <span class="badge bg-info rounded-pill"><?= $rencana_count ?></span>
        </li>
    </ul>
</div>

<!-- JENIS HARMONISASI -->
<div class="sidebar-menu">
    <h4><i class="bi bi-tags"></i> Jenis Harmonisasi</h4>
    <ul>
    <?php while ($jenis = mysqli_fetch_assoc($jenis_stats_result)): ?>
        <li class="d-flex justify-content-between">
            <span><?= htmlspecialchars(substr($jenis['jenis_monografi'], 0, 30)) ?>...</span>
            <span class="badge bg-secondary rounded-pill"><?= $jenis['jumlah'] ?></span>
        </li>
    <?php endwhile; ?>
    </ul>
</div>

<!-- FILTER TAHUN -->
<div class="sidebar-menu">
    <h4><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h4>
    <div class="year-buttons-sidebar">
        <button class="year-btn year-filter-sidebar active" data-year="semua">Semua</button>
        <?php foreach ($years as $year): ?>
            <button class="year-btn year-filter-sidebar" data-year="<?= $year ?>"><?= $year ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- DOKUMEN TERBARU -->
<div class="sidebar-menu">
    <h4><i class="bi bi-clock-history"></i> Hasil Terbaru</h4>
    <ul>
    <?php while ($recent = mysqli_fetch_assoc($recent_result)): ?>
    <li>
        <a href="index.php?page=detail-monografi&id=<?= $recent['id_monografi'] ?>">
            <i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($recent['judul'], 0, 40)) ?>...
        </a>
        <small class="text-muted d-block">Tahun: <?= htmlspecialchars($recent['tahun_terbit']) ?></small>
        <small class="text-muted"><?= date('d M Y', strtotime($recent['tanggal_pembuatan'])) ?></small>
    </li>
    <?php endwhile; ?>
    </ul>
</div>

<!-- INFORMASI HUKUM -->
<div class="sidebar-menu">
    <h4><i class="bi bi-info-circle"></i> Informasi Hukum</h4>
    <ul>
        <li><a href="#" onclick="showDasarHukum()"><i class="bi bi-book"></i> Dasar Hukum</a></li>
        <li><a href="#" onclick="showProsedur()"><i class="bi bi-clipboard-check"></i> Prosedur Harmonisasi</a></li>
        <li><a href="#" onclick="showTimeline()"><i class="bi bi-calendar-week"></i> Timeline Proses</a></li>
        <li><a href="#" onclick="showKontak()"><i class="bi bi-telephone"></i> Kontak Konsultasi</a></li>
    </ul>
</div>

</div>
</div>
</div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// FILTER BERDASARKAN STATUS
// ============================================
document.querySelectorAll(".harmonisasi-filter-btn").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".harmonisasi-filter-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedStatus = btn.dataset.status;
        
        filterDocuments(selectedStatus, currentYear);
    });
});

// ============================================
// FILTER BERDASARKAN TAHUN (SIDEBAR)
// ============================================
let currentYear = "semua";
let currentStatus = "semua";

document.querySelectorAll(".year-filter-sidebar").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        currentYear = btn.dataset.year;
        
        filterDocuments(currentStatus, currentYear);
    });
});

// ============================================
// FUNGSI FILTER DOKUMEN
// ============================================
function filterDocuments(status, year) {
    const cards = document.querySelectorAll('.harmonisasi-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardStatus = card.dataset.status;
        const cardYear = card.dataset.tahun;
        
        const statusMatch = status === "semua" || cardStatus === status;
        const yearMatch = year === "semua" || cardYear === year;
        
        if (statusMatch && yearMatch) {
            card.style.display = "flex";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });
    
    // Update counter
    if (visibleCount === 0) {
        document.getElementById('documentsContainer').innerHTML = `
            <div class="no-data">
                <i class="bi bi-search"></i>
                <h4>Tidak Ditemukan</h4>
                <p class="text-muted mb-0">
                    Tidak ada hasil harmonisasi yang sesuai dengan filter yang dipilih.
                </p>
                <button class="btn btn-primary mt-3" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                </button>
            </div>
        `;
    }
}

// ============================================
// RESET FILTER
// ============================================
function resetFilters() {
    currentStatus = "semua";
    currentYear = "semua";
    
    document.querySelectorAll(".harmonisasi-filter-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(".harmonisasi-filter-btn[data-status='semua']").classList.add("active");
    
    document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-filter-sidebar[data-year='semua']").classList.add("active");
    
    const cards = document.querySelectorAll('.harmonisasi-card');
    cards.forEach(card => {
        card.style.display = "flex";
    });
}

// ============================================
// FUNGSI PENCARIAN
// ============================================
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const keyword = document.getElementById('searchKeyword').value.toLowerCase().trim();
    
    if (!keyword) {
        alert('Masukkan kata kunci pencarian!');
        return;
    }
    
    const cards = document.querySelectorAll('.harmonisasi-card');
    let foundCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title a').textContent.toLowerCase();
        const desc = card.querySelector('.legal-info p')?.textContent.toLowerCase() || '';
        const judul = card.querySelector('.card-number').textContent.toLowerCase();
        
        if (title.includes(keyword) || desc.includes(keyword) || judul.includes(keyword)) {
            card.style.display = "flex";
            foundCount++;
            
            // Highlight the keyword
            highlightText(card, keyword);
        } else {
            card.style.display = "none";
        }
    });
    
    if (foundCount === 0) {
        document.getElementById('documentsContainer').innerHTML = `
            <div class="no-data">
                <i class="bi bi-search"></i>
                <h4>Tidak Ditemukan</h4>
                <p class="text-muted mb-0">
                    Tidak ada hasil harmonisasi yang sesuai dengan kata kunci "${keyword}".
                </p>
                <button class="btn btn-primary mt-3" onclick="resetSearch()">
                    <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua
                </button>
            </div>
        `;
    }
    
    // Show search results info
    if (foundCount > 0) {
        showSearchResultsInfo(keyword, foundCount);
    }
});

function highlightText(card, keyword) {
    const elements = card.querySelectorAll('.card-title a, .legal-info p');
    
    elements.forEach(element => {
        const originalText = element.textContent;
        const regex = new RegExp(`(${keyword})`, 'gi');
        const highlightedText = originalText.replace(regex, '<mark>$1</mark>');
        
        if (originalText !== highlightedText) {
            element.innerHTML = highlightedText;
        }
    });
}

function resetSearch() {
    document.getElementById('searchKeyword').value = '';
    resetFilters();
}

function showSearchResultsInfo(keyword, count) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Hasil Pencarian',
            html: `Ditemukan <strong>${count}</strong> hasil harmonisasi dengan kata kunci "<strong>${keyword}</strong>"`,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        console.log(`Found ${count} harmonization results for "${keyword}"`);
    }
}

// ============================================
// FUNGSI UNTUK NOTIFIKASI
// ============================================
function showFileNotFoundAlert(judul) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk hasil harmonisasi <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk hasil harmonisasi "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
    }
}

function showDownloadStarted(filename) {
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
// INFORMASI HUKUM FUNCTIONS
// ============================================
function showDasarHukum() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Dasar Hukum Harmonisasi',
            html: `
                <div class="text-start">
                    <h6>Landasan Hukum:</h6>
                    <ol>
                        <li>Undang-Undang Nomor 12 Tahun 2011 tentang Pembentukan Peraturan Perundang-undangan</li>
                        <li>Undang-Undang Nomor 15 Tahun 2019 tentang Perubahan atas UU No. 12/2011</li>
                        <li>Peraturan Pemerintah No. 87 Tahun 2014 tentang Peraturan Pelaksanaan UU No. 12/2011</li>
                        <li>Peraturan Menteri Hukum dan HAM No. 33 Tahun 2018 tentang Harmonisasi Peraturan</li>
                        <li>Peraturan Daerah tentang Tata Cara Penyusunan Peraturan Daerah</li>
                    </ol>
                    <hr>
                    <p><small>* Semua proses harmonisasi mengacu pada peraturan perundang-undangan yang berlaku</small></p>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 700
        });
    }
}

function showProsedur() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Prosedur Harmonisasi',
            html: `
                <div class="text-start">
                    <h6>Tahapan Proses:</h6>
                    <ol>
                        <li><strong>Identifikasi:</strong> Pencarian peraturan yang bertentangan</li>
                        <li><strong>Analisis:</strong> Kajian hukum mendalam</li>
                        <li><strong>Koordinasi:</strong> Konsultasi dengan instansi terkait</li>
                        <li><strong>Perumusan:</strong> Penyusunan rekomendasi harmonisasi</li>
                        <li><strong>Validasi:</strong> Pemeriksaan oleh ahli hukum</li>
                        <li><strong>Penetapan:</strong> Pengesahan hasil harmonisasi</li>
                        <li><strong>Implementasi:</strong> Penerapan hasil harmonisasi</li>
                        <li><strong>Monitoring:</strong> Pemantauan dan evaluasi</li>
                    </ol>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 600
        });
    }
}

function showTimeline() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Timeline Proses Harmonisasi',
            html: `
                <div class="text-start">
                    <h6>Rata-rata Waktu Proses:</h6>
                    <ul>
                        <li>Identifikasi: 2-4 minggu</li>
                        <li>Analisis hukum: 4-8 minggu</li>
                        <li>Koordinasi: 4-6 minggu</li>
                        <li>Perumusan: 2-4 minggu</li>
                        <li>Validasi: 2-3 minggu</li>
                        <li>Penetapan: 1-2 minggu</li>
                    </ul>
                    <hr>
                    <p><strong>Total waktu rata-rata:</strong> 15-27 minggu</p>
                    <p><small>* Timeline dapat berubah tergantung kompleksitas kasus</small></p>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 500
        });
    }
}

function showKontak() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Kontak Konsultasi',
            html: `
                <div class="text-start">
                    <h6>Tim Harmonisasi Peraturan:</h6>
                    <p><strong>Kepala Bagian:</strong> Dr. H. Ahmad Syarifuddin, S.H., M.H.<br>
                    <i class="bi bi-telephone"></i> (0271) 123456 ext. 101<br>
                    <i class="bi bi-envelope"></i> harmonisasi@dprd-surakarta.go.id</p>
                    
                    <p><strong>Sekretaris Tim:</strong> Dra. Sri Wahyuni, M.H.<br>
                    <i class="bi bi-telephone"></i> (0271) 123456 ext. 102</p>
                    
                    <p><strong>Alamat:</strong><br>
                    Gedung DPRD Kota Surakarta<br>
                    Jl. Jenderal Sudirman No. 2, Surakarta<br>
                    Jawa Tengah 57126</p>
                    
                    <p><strong>Jam Kerja:</strong><br>
                    Senin - Kamis: 08.00 - 16.00<br>
                    Jumat: 08.00 - 11.30</p>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 600
        });
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
            
            console.log('Download clicked:', {
                id: id,
                filename: filename,
                href: this.href
            });
        });
    });
    
    // Untuk tombol download yang disabled
    const disabledButtons = document.querySelectorAll('.btn-download.disabled');
    disabledButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.harmonisasi-card');
            const judul = card.querySelector('.card-title a').textContent;
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
// HARMONISASI BADGE CLICK
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.harmonisasi-badge').forEach(badge => {
        badge.addEventListener('click', function(e) {
            e.stopPropagation();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hasil Harmonisasi Peraturan',
                    html: `
                        <div class="text-start">
                            <p>Dokumen hasil harmonisasi peraturan untuk menjamin keselarasan dan kepastian hukum.</p>
                            <p><strong>Tujuan Harmonisasi:</strong></p>
                            <ul>
                                <li>Menghilangkan konflik atau pertentangan antar peraturan</li>
                                <li>Menjamin konsistensi hierarki peraturan</li>
                                <li>Meningkatkan kepastian hukum bagi masyarakat</li>
                                <li>Memperkuat sistem hukum nasional yang terpadu</li>
                                <li>Mendorong penegakan hukum yang efektif</li>
                            </ul>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Mengerti'
                });
            } else {
                alert('Hasil Harmonisasi Peraturan: Dokumen hasil harmonisasi untuk menjamin keselarasan dan kepastian hukum antar peraturan yang berlaku.');
            }
        });
    });
});
</script>

<?php 
mysqli_close($koneksi); 
?>