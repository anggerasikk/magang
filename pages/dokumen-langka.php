<?php
// dokumen-langka.php
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
          WHERE jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%'
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
                WHERE jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%'
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
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit
    FROM monografi
    WHERE jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%'
    ORDER BY created_at DESC
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);
$recent_data = [];
while ($pop = mysqli_fetch_assoc($recent_result)) {
    $recent_data[] = $pop;
}

// ============================================
// JENIS DOKUMEN STATS (untuk kondisi)
// ============================================
$jenis_stats_query = "
    SELECT 
        CASE 
            WHEN tahun_terbit < 1945 THEN 'Era Kolonial'
            WHEN tahun_terbit BETWEEN 1945 AND 1965 THEN 'Era Kemerdekaan'
            WHEN tahun_terbit BETWEEN 1966 AND 1998 THEN 'Orde Baru'
            WHEN tahun_terbit >= 1999 THEN 'Reformasi'
            ELSE 'Lainnya'
        END as era,
        COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%'
    GROUP BY 
        CASE 
            WHEN tahun_terbit < 1945 THEN 'Era Kolonial'
            WHEN tahun_terbit BETWEEN 1945 AND 1965 THEN 'Era Kemerdekaan'
            WHEN tahun_terbit BETWEEN 1966 AND 1998 THEN 'Orde Baru'
            WHEN tahun_terbit >= 1999 THEN 'Reformasi'
            ELSE 'Lainnya'
        END
    ORDER BY 
        CASE era
            WHEN 'Era Kolonial' THEN 1
            WHEN 'Era Kemerdekaan' THEN 2
            WHEN 'Orde Baru' THEN 3
            WHEN 'Reformasi' THEN 4
            ELSE 5
        END";
$jenis_stats_result = mysqli_query($koneksi, $jenis_stats_query);
$jenis_stats_data = [];
while ($era = mysqli_fetch_assoc($jenis_stats_result)) {
    $jenis_stats_data[$era['era']] = $era['jumlah'];
}

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Langka%' OR jenis_monografi LIKE '%langka%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Dokumen Langka & Koleksi Khusus - JDIH DPRD Kota Surakarta";
$page_description = "Koleksi dokumen langka dan bersejarah milik DPRD Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK DOKUMEN LANGKA */
.rare-document-card {
    border: 2px solid #ffd700;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.rare-document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
}

.rare-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #8b4513;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    z-index: 2;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.condition-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.condition-good {
    background: #e6fffa;
    color: #234e52;
}

.condition-fair {
    background: #fffaf0;
    color: #744210;
}

.condition-poor {
    background: #fff5f5;
    color: #9b2c2c;
}

.access-level {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.access-public {
    background: #f0fff4;
    color: #22543d;
}

.access-limited {
    background: #fffaf0;
    color: #744210;
}

.access-restricted {
    background: #fff5f5;
    color: #9b2c2c;
}

.document-provenance {
    background: #f8f9fa;
    border-left: 4px solid #8b4513;
    padding: 15px;
    border-radius: 0 8px 8px 0;
    margin-top: 15px;
    font-size: 0.9rem;
}

.provenance-title {
    font-weight: 600;
    color: #8b4513;
    margin-bottom: 5px;
}

.digital-copy {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.original-copy {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
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
    background: linear-gradient(135deg, #8b4513 0%, #d2691e 100%);
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

/* Stats boxes */
.stats-box {
    background: white;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    color: #8b4513;
}

.stats-label {
    font-size: 0.9rem;
    color: #666;
    margin-top: 5px;
}

/* List group items */
.list-group-item {
    border: none;
    padding: 10px 15px;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
    .card-image {
        height: 180px;
    }
    
    .document-card {
        flex-direction: column;
    }
    
    .stats-box {
        margin-bottom: 15px;
    }
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">DOKUMEN LANGKA & KOLEKSI KHUSUS</h1>
        <p class="subtitle">Koleksi dokumen langka dan bersejarah DPRD Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Dokumen Langka</li>
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
                    <h5>Cari Dokumen Langka</h5>
                    <form class="search-form" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Masukkan kata kunci dokumen langka..." 
                                   aria-label="Search" id="searchKeyword">
                            <button class="btn btn-search" type="submit" id="button-search">
                                <i class="bi bi-search"></i> CARI
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KATEGORI ERA -->
                <div class="year-filter">
                    <h5>Filter Berdasarkan Era</h5>
                    <div class="year-buttons">
                        <button class="year-btn era-filter active" data-era="semua">Semua</button>
                        <button class="year-btn era-filter" data-era="kolonial">Era Kolonial</button>
                        <button class="year-btn era-filter" data-era="kemerdekaan">Era Kemerdekaan</button>
                        <button class="year-btn era-filter" data-era="orde-baru">Orde Baru</button>
                        <button class="year-btn era-filter" data-era="reformasi">Reformasi</button>
                    </div>
                </div>

                <h3 class="section-title mt-4">Daftar Dokumen Langka DPRD Kota Surakarta</h3>

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
                    
                    // Tentukan era berdasarkan tahun
                    $tahun = $row['tahun_terbit'];
                    $era = '';
                    if ($tahun < 1945) {
                        $era = 'kolonial';
                        $era_text = 'Era Kolonial';
                    } elseif ($tahun >= 1945 && $tahun <= 1965) {
                        $era = 'kemerdekaan';
                        $era_text = 'Era Kemerdekaan';
                    } elseif ($tahun >= 1966 && $tahun <= 1998) {
                        $era = 'orde-baru';
                        $era_text = 'Orde Baru';
                    } elseif ($tahun >= 1999) {
                        $era = 'reformasi';
                        $era_text = 'Era Reformasi';
                    } else {
                        $era = 'lainnya';
                        $era_text = 'Lainnya';
                    }
                    
                    // Tentukan kondisi dokumen
                    $kondisi_class = "condition-fair";
                    $kondisi_text = "Kondisi Cukup";
                    $kondisi_icon = "bi bi-check-circle";
                    if ($tahun >= 1990) {
                        $kondisi_class = "condition-good";
                        $kondisi_text = "Kondisi Baik";
                    } elseif ($tahun < 1950) {
                        $kondisi_class = "condition-poor";
                        $kondisi_text = "Kondisi Rusak";
                        $kondisi_icon = "bi bi-exclamation-triangle";
                    }
                    
                    // Tentukan tingkat akses
                    $akses_class = "access-limited";
                    $akses_text = "Akses Terbatas";
                    $akses_icon = "bi bi-shield-lock";
                    if ($file_found) {
                        $akses_class = "access-public";
                        $akses_text = "Akses Terbuka";
                        $akses_icon = "bi bi-unlock";
                    } elseif ($tahun < 1945) {
                        $akses_class = "access-restricted";
                        $akses_text = "Akses Terbatas Ketat";
                        $akses_icon = "bi bi-lock";
                    }
                    
                    // Tentukan apakah digital atau asli
                    $copy_badge = $file_found ? 'digital-copy' : 'original-copy';
                    $copy_text = $file_found ? 'Digital' : 'Asli';
                    $copy_icon = $file_found ? 'bi bi-file-earmark-pdf' : 'bi bi-file-earmark';
                    
                    // URL untuk detail
                    $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
                ?>

                <div class="document-card rare-document-card" data-era="<?= $era ?>" data-tahun="<?= $tahun ?>" data-id="<?= $row['id_monografi']; ?>">
                    <div class="rare-badge">DOKUMEN LANGKA</div>
                    <div class="card-image">
                        <?php if ($image_found && !empty($image_url)): ?>
                            <img src="<?= htmlspecialchars($image_url) ?>" 
                                 alt="<?= htmlspecialchars($row['judul']) ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
                            <div class="placeholder" style="display: none;">
                                <i class="bi bi-archive"></i>
                                <p>Dokumen Langka<br>
                                <small>Tahun <?= htmlspecialchars($tahun) ?></small></p>
                            </div>
                        <?php else: ?>
                            <div class="placeholder">
                                <i class="bi bi-archive"></i>
                                <p>Dokumen Langka<br>
                                <small>Tahun <?= htmlspecialchars($tahun) ?></small></p>
                            </div>
                        <?php endif; ?>
                        <div class="image-overlay">
                            <span class="image-badge"><?= htmlspecialchars($tahun) ?></span>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-number"><?= htmlspecialchars($row['jenis_monografi']) ?> - <?= htmlspecialchars($tahun) ?></div>
                        <h4 class="card-title">
                            <a href="<?= $detail ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['judul']) ?></a>
                        </h4>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="condition-badge <?= $kondisi_class ?>">
                                <i class="<?= $kondisi_icon ?>"></i> <?= $kondisi_text ?>
                            </span>
                            <span class="access-level <?= $akses_class ?>">
                                <i class="<?= $akses_icon ?>"></i> <?= $akses_text ?>
                            </span>
                            <span class="<?= $copy_badge ?>">
                                <i class="<?= $copy_icon ?>"></i> <?= $copy_text ?>
                            </span>
                        </div>

                        <div class="card-meta">
                            <div class="card-date">
                                <i class="bi bi-calendar"></i> <?= $tanggal ?>
                            </div>
                            <div class="card-status"><?= htmlspecialchars($era_text) ?></div>
                        </div>

                        <?php if (!empty($row['penulis']) || !empty($row['penerbit']) || !empty($row['bidang_hukum'])): ?>
                        <div class="card-extra-info">
                            <?php if (!empty($row['penulis'])): ?>
                            <span><i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($row['penerbit'])): ?>
                            <span><i class="bi bi-building"></i> <?= htmlspecialchars($row['penerbit']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($row['bidang_hukum'])): ?>
                            <span><i class="bi bi-journal"></i> <?= htmlspecialchars($row['bidang_hukum']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($row['jumlah_halaman'])): ?>
                            <span><i class="bi bi-file-text"></i> <?= htmlspecialchars($row['jumlah_halaman']) ?> halaman</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($row['deskripsi'])): ?>
                        <div class="document-provenance">
                            <div class="provenance-title">Deskripsi:</div>
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
                            <?php if ($file_found && $akses_class == 'access-public'): ?>
                                <!-- Tombol Download Langsung -->
                                <a href="<?= htmlspecialchars($download_url) ?>" 
                                   class="card-btn btn-download download-btn" 
                                   data-id="<?= $row['id_monografi']; ?>"
                                   data-filename="<?= htmlspecialchars(basename($actual_file_path)) ?>"
                                   download>
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            <?php elseif ($file_found && $akses_class == 'access-limited'): ?>
                                <a href="#" 
                                   class="card-btn btn-download disabled" 
                                   onclick="event.preventDefault(); showAccessRestrictedAlert('<?= htmlspecialchars($row['judul']) ?>', 'terbatas');"
                                   title="Dokumen dengan akses terbatas">
                                    <i class="bi bi-download"></i> Permohonan
                                </a>
                            <?php elseif ($akses_class == 'access-restricted'): ?>
                                <a href="#" 
                                   class="card-btn btn-download disabled" 
                                   onclick="event.preventDefault(); showAccessRestrictedAlert('<?= htmlspecialchars($row['judul']) ?>', 'terbatas ketat');"
                                   title="Dokumen dengan akses terbatas ketat">
                                    <i class="bi bi-download"></i> Permohonan
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
                    <i class="bi bi-archive"></i>
                    <h4>Tidak Ada Data</h4>
                    <p class="text-muted mb-0">
                        Tidak ada dokumen langka yang tersedia di database.
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
                <!-- Menu Koleksi Khusus -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-archive"></i> Menu Koleksi Khusus</h4>
                    <ul>
                        <li><a href="index.php?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
                        <li><a href="keputusan-dprd.php"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
                        <li><a href="keputusan-pimpinan.php"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
                        <li><a href="keputusan-sekretariat.php"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris DPRD</a></li>
                        <li><a href="peraturan-dprd.php"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
                        <li><a href="perda.php"><i class="bi bi-journal-bookmark"></i> Peraturan Daerah</a></li>
                        <li><a href="pokok-pikiran-dprd.php"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
                        <li class="active"><a href="#"><i class="bi bi-gem"></i> Dokumen Langka</a></li>
                        <li><a href="index.php?page=english-legal"><i class="bi bi-translate"></i> English Legal Products</a></li>
                    </ul>
                </div>

                <!-- Statistik Koleksi -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> Statistik Koleksi</h4>
                    <ul>
                        <li class="d-flex justify-content-between">
                            <span>Total Dokumen Langka</span>
                            <span class="badge bg-primary rounded-pill"><?= $total_dokumen ?></span>
                        </li>
                        <?php foreach ($jenis_stats_data as $era_name => $count): ?>
                        <li class="d-flex justify-content-between">
                            <span><?= htmlspecialchars($era_name) ?></span>
                            <span class="badge bg-warning rounded-pill"><?= $count ?></span>
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

                <!-- Dokumen Terbaru -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Dokumen Terbaru</h4>
                    <ul>
                    <?php foreach ($recent_data as $recent): ?>
                    <li>
                        <a href="index.php?page=detail-monografi&id=<?= $recent['id_monografi'] ?>">
                            <i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($recent['judul'], 0, 40)) ?>...
                        </a>
                        <small class="text-muted d-block">Tahun: <?= htmlspecialchars($recent['tahun_terbit']) ?></small>
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
// FILTER BERDASARKAN ERA
// ============================================
document.querySelectorAll(".era-filter").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".era-filter").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedEra = btn.dataset.era;
        
        filterDocuments(selectedEra, currentYear);
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
        
        filterDocuments(currentEra, currentYear);
    });
});

// ============================================
// FUNGSI FILTER DOKUMEN
// ============================================
let currentEra = "semua";

function filterDocuments(era, year) {
    const cards = document.querySelectorAll('.rare-document-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardEra = card.dataset.era;
        const cardYear = card.dataset.tahun;
        
        const eraMatch = era === "semua" || cardEra === era;
        const yearMatch = year === "semua" || cardYear === year;
        
        if (eraMatch && yearMatch) {
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
                    Tidak ada dokumen yang sesuai dengan filter yang dipilih.
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
    currentEra = "semua";
    currentYear = "semua";
    
    document.querySelectorAll(".era-filter").forEach(b => b.classList.remove("active"));
    document.querySelector(".era-filter[data-era='semua']").classList.add("active");
    
    document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-filter-sidebar[data-year='semua']").classList.add("active");
    
    const cards = document.querySelectorAll('.rare-document-card');
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
    
    const cards = document.querySelectorAll('.rare-document-card');
    let foundCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title a').textContent.toLowerCase();
        const desc = card.querySelector('.document-provenance p')?.textContent.toLowerCase() || '';
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
                    Tidak ada dokumen yang sesuai dengan kata kunci "${keyword}".
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
            html: `File PDF untuk dokumen <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk dokumen "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
    }
}

function showAccessRestrictedAlert(judul, tingkat) {
    const tingkatText = tingkat === 'terbatas ketat' ? 'terbatas ketat' : 'terbatas';
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Akses ' + tingkatText.toUpperCase(),
            html: `Dokumen <strong>${judul}</strong> memiliki akses ${tingkatText}.<br><br>
                  <small>Silakan hubungi petugas arsip untuk mengajukan permohonan akses.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`Dokumen "${judul}" memiliki akses ${tingkatText}.\n\nSilakan hubungi petugas arsip untuk mengajukan permohonan akses.`);
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
            const card = this.closest('.rare-document-card');
            const judul = card.querySelector('.card-title a').textContent;
            
            const aksesElement = card.querySelector('.access-level');
            if (aksesElement) {
                const aksesText = aksesElement.textContent;
                if (aksesText.includes('Terbatas Ketat')) {
                    showAccessRestrictedAlert(judul, 'terbatas ketat');
                } else if (aksesText.includes('Terbatas')) {
                    showAccessRestrictedAlert(judul, 'terbatas');
                } else {
                    showFileNotFoundAlert(judul);
                }
            } else {
                showFileNotFoundAlert(judul);
            }
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