<?php
// hasil-fasilitasi.php
session_start();
include_once "a_db.php";

// ============================================
// KONFIGURASI PATH
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
    
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    $possible_locations = [
        $monografi_pdf_path . $clean_filename,
        str_replace('/', '\\', $monografi_pdf_path) . $clean_filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/' . $clean_filename,
    ];
    
    if ($id) {
        $possible_locations[] = $monografi_pdf_path . $id . '.pdf';
        $possible_locations[] = $monografi_pdf_path . $id . '.PDF';
        $possible_locations[] = $monografi_pdf_path . 'monografi_' . $id . '.pdf';
    }
    
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
    
    return null;
}

// Fungsi untuk menemukan gambar
function findImageFile($filename, $id = null) {
    global $monografi_images_path;
    
    if (empty($filename) && $id) {
        $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($image_extensions as $ext) {
            $test_filename = $id . '.' . $ext;
            $test_path = $monografi_images_path . $test_filename;
            
            if (file_exists($test_path)) {
                return $test_path;
            }
            
            $test_filename2 = 'monografi_' . $id . '.' . $ext;
            $test_path2 = $monografi_images_path . $test_filename2;
            
            if (file_exists($test_path2)) {
                return $test_path2;
            }
        }
        return null;
    }
    
    if (empty($filename)) {
        return null;
    }
    
    $filename = basename($filename);
    
    $possible_locations = [
        $monografi_images_path . $filename,
        str_replace('/', '\\', $monografi_images_path) . $filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/monografi/images/' . $filename,
    ];
    
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            return $location;
        }
    }
    
    return null;
}

// ============================================
// QUERY DATA UTAMA
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
          WHERE jenis_monografi LIKE '%Fasilitasi%' OR 
                jenis_monografi LIKE '%fasilitasi%' OR
                judul LIKE '%Fasilitasi%' OR
                judul LIKE '%fasilitasi%' OR
                jenis_monografi LIKE '%Konsultasi_Publik%' OR
                jenis_monografi LIKE '%Rapat_Teknis%' OR
                jenis_monografi LIKE '%FGD%' OR
                jenis_monografi LIKE '%Mediasi%'
          ORDER BY tahun_terbit DESC, created_at DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// ============================================
// TOTAL DOKUMEN
// ============================================
$total_query = "SELECT COUNT(*) as total 
                FROM monografi 
                WHERE jenis_monografi LIKE '%Fasilitasi%' OR 
                      jenis_monografi LIKE '%fasilitasi%' OR
                      judul LIKE '%Fasilitasi%' OR
                      judul LIKE '%fasilitasi%' OR
                      jenis_monografi LIKE '%Konsultasi_Publik%' OR
                      jenis_monografi LIKE '%Rapat_Teknis%' OR
                      jenis_monografi LIKE '%FGD%' OR
                      jenis_monografi LIKE '%Mediasi%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Fasilitasi%' OR 
          jenis_monografi LIKE '%fasilitasi%' OR
          judul LIKE '%Fasilitasi%' OR
          judul LIKE '%fasilitasi%' OR
          jenis_monografi LIKE '%Konsultasi_Publik%' OR
          jenis_monografi LIKE '%Rapat_Teknis%' OR
          jenis_monografi LIKE '%FGD%' OR
          jenis_monografi LIKE '%Mediasi%'
    GROUP BY tahun_terbit
    ORDER BY tahun_terbit DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);
$year_stats_data = [];
while ($stat = mysqli_fetch_assoc($year_stats_result)) {
    $year_stats_data[] = $stat;
}

// ============================================
// DOKUMEN TERBARU
// ============================================
$recent_query = "
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit, penulis
    FROM monografi
    WHERE jenis_monografi LIKE '%Fasilitasi%' OR 
          jenis_monografi LIKE '%fasilitasi%' OR
          judul LIKE '%Fasilitasi%' OR
          judul LIKE '%fasilitasi%' OR
          jenis_monografi LIKE '%Konsultasi_Publik%' OR
          jenis_monografi LIKE '%Rapat_Teknis%' OR
          jenis_monografi LIKE '%FGD%' OR
          jenis_monografi LIKE '%Mediasi%'
    ORDER BY created_at DESC
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);
$recent_data = [];
while ($pop = mysqli_fetch_assoc($recent_result)) {
    $recent_data[] = $pop;
}

// ============================================
// JENIS FASILITASI STATS
// ============================================
$jenis_stats_query = "
    SELECT 
        CASE 
            WHEN jenis_monografi LIKE '%Konsultasi_Publik%' OR judul LIKE '%Konsultasi Publik%' THEN 'Konsultasi Publik'
            WHEN jenis_monografi LIKE '%Rapat_Teknis%' OR judul LIKE '%Rapat Teknis%' THEN 'Rapat Teknis'
            WHEN jenis_monografi LIKE '%FGD%' OR judul LIKE '%FGD%' OR judul LIKE '%Workshop%' THEN 'FGD / Workshop'
            WHEN jenis_monografi LIKE '%Mediasi%' OR judul LIKE '%Mediasi%' THEN 'Mediasi Stakeholder'
            WHEN jenis_monografi LIKE '%Harmonisasi%' THEN 'Harmonisasi'
            ELSE 'Fasilitasi Lainnya'
        END as jenis_fasilitasi,
        COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Fasilitasi%' OR 
          jenis_monografi LIKE '%fasilitasi%' OR
          judul LIKE '%Fasilitasi%' OR
          judul LIKE '%fasilitasi%' OR
          jenis_monografi LIKE '%Konsultasi_Publik%' OR
          jenis_monografi LIKE '%Rapat_Teknis%' OR
          jenis_monografi LIKE '%FGD%' OR
          jenis_monografi LIKE '%Mediasi%'
    GROUP BY 
        CASE 
            WHEN jenis_monografi LIKE '%Konsultasi_Publik%' OR judul LIKE '%Konsultasi Publik%' THEN 'Konsultasi Publik'
            WHEN jenis_monografi LIKE '%Rapat_Teknis%' OR judul LIKE '%Rapat Teknis%' THEN 'Rapat Teknis'
            WHEN jenis_monografi LIKE '%FGD%' OR judul LIKE '%FGD%' OR judul LIKE '%Workshop%' THEN 'FGD / Workshop'
            WHEN jenis_monografi LIKE '%Mediasi%' OR judul LIKE '%Mediasi%' THEN 'Mediasi Stakeholder'
            WHEN jenis_monografi LIKE '%Harmonisasi%' THEN 'Harmonisasi'
            ELSE 'Fasilitasi Lainnya'
        END
    ORDER BY jumlah DESC";
$jenis_stats_result = mysqli_query($koneksi, $jenis_stats_query);
$jenis_stats_data = [];
while ($jenis = mysqli_fetch_assoc($jenis_stats_result)) {
    $jenis_stats_data[$jenis['jenis_fasilitasi']] = $jenis['jumlah'];
}

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Fasilitasi%' OR 
           jenis_monografi LIKE '%fasilitasi%' OR
           judul LIKE '%Fasilitasi%' OR
           judul LIKE '%fasilitasi%' OR
           jenis_monografi LIKE '%Konsultasi_Publik%' OR
           jenis_monografi LIKE '%Rapat_Teknis%' OR
           jenis_monografi LIKE '%FGD%' OR
           jenis_monografi LIKE '%Mediasi%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Hasil Fasilitasi Raperda - JDIH DPRD Kota Surakarta";
$page_description = "Dokumen hasil fasilitasi penyusunan Rancangan Peraturan Daerah";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK HASIL FASILITASI */
.fasilitasi-card {
    border-left: 5px solid #17a2b8;
    transition: all 0.3s ease;
}

.fasilitasi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(23, 162, 184, 0.2);
}

.fasilitasi-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    z-index: 2;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-right: 10px;
    margin-bottom: 5px;
}

.status-selesai {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-proses {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-tunda {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.raperda-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.facilitator-info {
    background: #f8f9fa;
    border-left: 4px solid #17a2b8;
    padding: 15px;
    border-radius: 0 8px 8px 0;
    margin-top: 15px;
    font-size: 0.9rem;
}

.facilitator-title {
    font-weight: 600;
    color: #17a2b8;
    margin-bottom: 10px;
}

.facilitator-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.detail-item i {
    color: #17a2b8;
    width: 16px;
}

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
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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

/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
    
    .facilitator-details {
        flex-direction: column;
        gap: 8px;
    }
}

/* Filter khusus */
.fasilitasi-filter-btn {
    background: white;
    border: 2px solid #dee2e6;
    color: #495057;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.fasilitasi-filter-btn:hover {
    border-color: #17a2b8;
    color: #17a2b8;
}

.fasilitasi-filter-btn.active {
    background: #17a2b8;
    border-color: #17a2b8;
    color: white;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">HASIL FASILITASI RAPERDA</h1>
        <p class="subtitle">Dokumen Fasilitasi Penyusunan Rancangan Peraturan Daerah</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Hasil Fasilitasi Raperda</li>
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
                <!-- PENCARIAN -->
                <div class="year-filter">
                    <h5>Cari Hasil Fasilitasi Raperda</h5>
                    <form class="search-form" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Masukkan kata kunci hasil fasilitasi..." 
                                   aria-label="Search" id="searchKeyword">
                            <button class="btn btn-search" type="submit" id="button-search">
                                <i class="bi bi-search"></i> CARI
                            </button>
                        </div>
                    </form>
                </div>

                <!-- FILTER TAHUN -->
                <div class="year-filter">
                    <h5>Filter Berdasarkan Tahun</h5>
                    <div class="year-buttons">
                        <button class="fasilitasi-filter-btn active" data-tahun="semua">Semua</button>
                        <?php foreach ($years as $year): ?>
                            <button class="fasilitasi-filter-btn" data-tahun="<?= $year ?>"><?= $year ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <h3 class="section-title mt-4">Hasil Fasilitasi Terbaru</h3>

                <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="card-container" id="documentsContainer">
                <?php 
                $counter = 0;
                mysqli_data_seek($result, 0);
                
                while ($row = mysqli_fetch_assoc($result)):
                    $counter++;
                    $tanggal = date('d F Y', strtotime($row['tanggal_pembuatan']));
                    
                    // Cari file PDF
                    $file_from_db = $row['file_pdf'];
                    $file_found = false;
                    $actual_file_path = null;
                    $download_url = '#';
                    
                    if (!empty($file_from_db)) {
                        $actual_file_path = findPDFFile($file_from_db, $row['id_monografi']);
                        if ($actual_file_path) {
                            $file_found = true;
                            $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_file_path);
                            $download_url = $relative_path . '?t=' . time();
                        }
                    }
                    
                    // Cari gambar/thumbnail
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
                    
                    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
                        $image_url = '/' . $image_url;
                    }
                    
                    // Tentukan status fasilitasi
                    $status = strtolower($row['status']);
                    $status_class = '';
                    $status_text = '';
                    $status_icon = '';
                    
                    if (strpos($status, 'selesai') !== false || strpos($status, 'approved') !== false || strpos($status, 'done') !== false) {
                        $status_class = 'status-selesai';
                        $status_text = 'SELESAI';
                        $status_icon = 'bi bi-check-circle';
                    } elseif (strpos($status, 'proses') !== false || strpos($status, 'pending') !== false || strpos($status, 'in_progress') !== false) {
                        $status_class = 'status-proses';
                        $status_text = 'DALAM PROSES';
                        $status_icon = 'bi bi-hourglass-split';
                    } elseif (strpos($status, 'tunda') !== false || strpos($status, 'delayed') !== false || strpos($status, 'postponed') !== false) {
                        $status_class = 'status-tunda';
                        $status_text = 'DITUNDA';
                        $status_icon = 'bi bi-exclamation-triangle';
                    } else {
                        $status_class = 'status-proses';
                        $status_text = 'DALAM PROSES';
                        $status_icon = 'bi bi-hourglass-split';
                    }
                    
                    // Tentukan jenis fasilitasi
                    $judul = strtolower($row['judul']);
                    $jenis = strtolower($row['jenis_monografi']);
                    $fasilitasi_jenis = '';
                    $icon_class = 'bi bi-gear';
                    
                    if (strpos($jenis, 'konsultasi_publik') !== false || strpos($judul, 'konsultasi publik') !== false) {
                        $fasilitasi_jenis = 'Konsultasi Publik';
                        $icon_class = 'bi bi-people';
                    } elseif (strpos($jenis, 'rapat_teknis') !== false || strpos($judul, 'rapat teknis') !== false) {
                        $fasilitasi_jenis = 'Rapat Teknis';
                        $icon_class = 'bi bi-tools';
                    } elseif (strpos($jenis, 'fgd') !== false || strpos($judul, 'fgd') !== false || strpos($judul, 'workshop') !== false) {
                        $fasilitasi_jenis = 'FGD / Workshop';
                        $icon_class = 'bi bi-chat-dots';
                    } elseif (strpos($jenis, 'mediasi') !== false || strpos($judul, 'mediasi') !== false) {
                        $fasilitasi_jenis = 'Mediasi Stakeholder';
                        $icon_class = 'bi bi-handshake';
                    } elseif (strpos($jenis, 'harmonisasi') !== false) {
                        $fasilitasi_jenis = 'Harmonisasi';
                        $icon_class = 'bi bi-shuffle';
                    } else {
                        $fasilitasi_jenis = 'Hasil Fasilitasi';
                        $icon_class = 'bi bi-gear';
                    }
                    
                    // Cek apakah terkait raperda
                    $terkait_raperda = false;
                    $raperda_text = '';
                    if (strpos($judul, 'raperda') !== false || strpos($judul, 'rancangan peraturan daerah') !== false) {
                        $terkait_raperda = true;
                        $words = explode(' ', $row['judul']);
                        foreach ($words as $word) {
                            if (strpos(strtolower($word), 'raperda') !== false || strpos(strtolower($word), 'peraturan daerah') !== false) {
                                $raperda_text = $word;
                                break;
                            }
                        }
                    }
                    
                    // URL untuk detail
                    $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
                    
                    $singkatan = "FAS";
                    if ($fasilitasi_jenis == 'Konsultasi Publik') {
                        $singkatan = "KPL";
                    } elseif ($fasilitasi_jenis == 'Rapat Teknis') {
                        $singkatan = "RPT";
                    } elseif ($fasilitasi_jenis == 'FGD / Workshop') {
                        $singkatan = "FGD";
                    } elseif ($fasilitasi_jenis == 'Mediasi Stakeholder') {
                        $singkatan = "MED";
                    }
                ?>

                <div class="document-card fasilitasi-card" data-tahun="<?= $row['tahun_terbit'] ?>" data-id="<?= $row['id_monografi']; ?>">
                    <div class="fasilitasi-badge">HASIL FASILITASI</div>
                    <div class="card-image">
                        <?php if ($image_found && !empty($image_url)): ?>
                            <img src="<?= htmlspecialchars($image_url) ?>" 
                                 alt="<?= htmlspecialchars($row['judul']) ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
                            <div class="placeholder" style="display: none;">
                                <i class="<?= $icon_class ?>"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php else: ?>
                            <div class="placeholder">
                                <i class="<?= $icon_class ?>"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php endif; ?>
                        <div class="image-overlay">
                            <span class="image-badge"><?= htmlspecialchars($row['tahun_terbit']) ?></span>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="d-flex flex-wrap mb-3">
                            <span class="status-badge <?= $status_class ?>">
                                <i class="<?= $status_icon ?>"></i> <?= $status_text ?>
                            </span>
                            <?php if ($terkait_raperda && !empty($raperda_text)): ?>
                            <span class="raperda-link">
                                <i class="bi bi-link-45deg"></i> <?= htmlspecialchars($raperda_text) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-number"><?= htmlspecialchars($row['jenis_monografi']) ?></div>
                        <h4 class="card-title">
                            <a href="<?= $detail ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['judul']) ?></a>
                        </h4>

                        <div class="facilitator-info">
                            <div class="facilitator-title">Informasi Fasilitasi:</div>
                            <div class="facilitator-details">
                                <?php if (!empty($row['penulis'])): ?>
                                <div class="detail-item">
                                    <i class="bi bi-person"></i>
                                    <span>Fasilitator: <?= htmlspecialchars($row['penulis']) ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="detail-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Tanggal: <?= $tanggal ?></span>
                                </div>
                                <?php if (!empty($row['jumlah_halaman'])): ?>
                                <div class="detail-item">
                                    <i class="bi bi-file-text"></i>
                                    <span>Halaman: <?= htmlspecialchars($row['jumlah_halaman']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($row['deskripsi'])): ?>
                        <div class="mt-3 small">
                            <strong>Ringkasan:</strong>
                            <p class="mb-0"><?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 150))) ?><?= strlen($row['deskripsi']) > 150 ? '...' : '' ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                            <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
                        </div>

                        <div class="card-actions">
                            <a href="<?= $detail ?>" class="card-btn btn-view">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                            
                            <?php if ($file_found): ?>
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
                    <i class="bi bi-gear"></i>
                    <h4>Tidak Ada Data</h4>
                    <p class="text-muted mb-0">
                        Tidak ada hasil fasilitasi yang tersedia di database.
                    </p>
                </div>
                <?php endif; ?>

                <!-- PAGINATION -->
                <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="pagination-container mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="col-lg-4">
                <!-- Menu Monografi Hukum -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-gear"></i> Menu Monografi Hukum</h4>
                    <ul>
                        <li><a href="pokok-pikiran-dprd.php"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
                        <li><a href="kajian-raperda.php"><i class="bi bi-search"></i> Kajian Raperda</a></li>
                        <li><a href="naskah-akademik.php"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
                        <li class="active"><a href="#"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                        <li><a href="risalah.php"><i class="bi bi-chat-text"></i> Risalah</a></li>
                        <li><a href="hasil-harmonisasi.php"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                        <li><a href="karya-ilmiah.php"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                        <li><a href="himpunan-peraturan.php"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                    </ul>
                </div>

                <!-- Statistik Fasilitasi -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> Statistik Fasilitasi</h4>
                    <ul>
                        <li class="d-flex justify-content-between">
                            <span>Total Fasilitasi</span>
                            <span class="badge bg-primary rounded-pill"><?= $total_dokumen ?></span>
                        </li>
                        <?php foreach ($year_stats_data as $year): ?>
                        <li class="d-flex justify-content-between">
                            <span>Tahun <?= $year['tahun_terbit'] ?></span>
                            <span class="badge bg-primary rounded-pill"><?= $year['jumlah'] ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Filter Tahun -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h4>
                    <div class="year-buttons-sidebar">
                        <button class="year-btn year-filter-sidebar active" data-year="semua">Semua</button>
                        <?php foreach ($years as $year): ?>
                            <button class="year-btn year-filter-sidebar" data-year="<?= $year ?>"><?= $year ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Jenis Fasilitasi -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-diagram-3"></i> Jenis Fasilitasi</h4>
                    <ul>
                        <?php foreach ($jenis_stats_data as $jenis_name => $count): ?>
                        <li class="d-flex justify-content-between">
                            <span><?= htmlspecialchars($jenis_name) ?></span>
                            <span class="badge bg-primary rounded-pill"><?= $count ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Fasilitasi Terbaru -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Fasilitasi Terbaru</h4>
                    <ul>
                    <?php foreach ($recent_data as $recent): ?>
                    <li>
                        <a href="index.php?page=detail-monografi&id=<?= $recent['id_monografi'] ?>">
                            <i class="bi bi-gear"></i> <?= htmlspecialchars(substr($recent['judul'], 0, 40)) ?>...
                        </a>
                        <small class="text-muted d-block">Oleh: <?= htmlspecialchars($recent['penulis']) ?></small>
                        <small class="text-muted"><?= date('d M Y', strtotime($recent['tanggal_pembuatan'])) ?></small>
                    </li>
                    <?php endforeach; ?>
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
document.querySelectorAll(".fasilitasi-filter-btn").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".fasilitasi-filter-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedYear = btn.dataset.tahun;
        
        filterDocuments(selectedYear);
    });
});

// ============================================
// FILTER BERDASARKAN TAHUN (SIDEBAR)
// ============================================
let currentYear = "semua";

document.querySelectorAll(".year-filter-sidebar").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        currentYear = btn.dataset.year;
        
        filterDocuments(currentYear);
    });
});

// ============================================
// FUNGSI FILTER DOKUMEN
// ============================================
function filterDocuments(year) {
    const cards = document.querySelectorAll('.fasilitasi-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardYear = card.dataset.tahun;
        
        const yearMatch = year === "semua" || cardYear === year;
        
        if (yearMatch) {
            card.style.display = "flex";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });
    
    if (visibleCount === 0) {
        document.getElementById('documentsContainer').innerHTML = `
            <div class="no-data">
                <i class="bi bi-search"></i>
                <h4>Tidak Ditemukan</h4>
                <p class="text-muted mb-0">
                    Tidak ada hasil fasilitasi yang sesuai dengan filter yang dipilih.
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
    currentYear = "semua";
    
    document.querySelectorAll(".fasilitasi-filter-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(".fasilitasi-filter-btn[data-tahun='semua']").classList.add("active");
    
    document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-filter-sidebar[data-year='semua']").classList.add("active");
    
    const cards = document.querySelectorAll('.fasilitasi-card');
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
    
    const cards = document.querySelectorAll('.fasilitasi-card');
    let foundCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title a').textContent.toLowerCase();
        const desc = card.querySelector('.small p')?.textContent.toLowerCase() || '';
        const judul = card.querySelector('.card-number').textContent.toLowerCase();
        
        if (title.includes(keyword) || desc.includes(keyword) || judul.includes(keyword)) {
            card.style.display = "flex";
            foundCount++;
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
                    Tidak ada hasil fasilitasi yang sesuai dengan kata kunci "${keyword}".
                </p>
                <button class="btn btn-primary mt-3" onclick="resetSearch()">
                    <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua
                </button>
            </div>
        `;
    }
});

function resetSearch() {
    document.getElementById('searchKeyword').value = '';
    resetFilters();
}

// ============================================
// FUNGSI UNTUK NOTIFIKASI
// ============================================
function showFileNotFoundAlert(judul) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk hasil fasilitasi <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk hasil fasilitasi "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
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
    }
}

// ============================================
// HANDLING DOWNLOAD BUTTON
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.download-btn:not(.disabled)');
    
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.getAttribute('data-id');
            const filename = this.getAttribute('data-filename');
            
            showDownloadStarted(filename);
            
            console.log('Download clicked:', {
                id: id,
                filename: filename,
                href: this.href
            });
        });
    });
    
    const disabledButtons = document.querySelectorAll('.btn-download.disabled');
    disabledButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.fasilitasi-card');
            const judul = card.querySelector('.card-title a').textContent;
            showFileNotFoundAlert(judul);
        });
    });
    
    // ============================================
    // ERROR HANDLING UNTUK GAMBAR
    // ============================================
    const images = document.querySelectorAll('.card-image img');
    
    images.forEach(img => {
        if (img.complete && img.naturalHeight === 0) {
            const placeholder = img.parentElement.querySelector('.placeholder');
            if (placeholder) {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
        
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const placeholder = this.parentElement.querySelector('.placeholder');
            if (placeholder) {
                placeholder.style.display = 'flex';
            }
        });
    });
});
</script>

<?php mysqli_close($koneksi); ?>