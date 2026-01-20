<?php
// risalah.php
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
          WHERE (jenis_monografi LIKE '%Risalah%' OR jenis_monografi LIKE '%risalah%')
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
                WHERE (jenis_monografi LIKE '%Risalah%' OR jenis_monografi LIKE '%risalah%')";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE (jenis_monografi LIKE '%Risalah%' OR jenis_monografi LIKE '%risalah%')
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
$popular_query = "
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Risalah%' OR jenis_monografi LIKE '%risalah%')
    ORDER BY created_at DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);
$popular_data = [];
while ($pop = mysqli_fetch_assoc($popular_result)) {
    $popular_data[] = $pop;
}

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Risalah%' OR jenis_monografi LIKE '%risalah%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Risalah Rapat DPRD - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan Risalah Rapat DPRD Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/* STYLE KHUSUS UNTUK RISALAH */
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

/* Badge untuk jenis rapat */
.meeting-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-right: 8px;
    margin-bottom: 5px;
}

.badge-pleno {
    background: #e6fffa;
    color: #234e52;
}

.badge-komisi {
    background: #fffaf0;
    color: #744210;
}

.badge-bamus {
    background: #f0fff4;
    color: #22543d;
}

.badge-pansus {
    background: #f0f4ff;
    color: #2d3748;
}

/* Badge untuk status */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 5px;
}

.status-final {
    background: #d4edda;
    color: #155724;
}

.status-draft {
    background: #fff3cd;
    color: #856404;
}

.status-correction {
    background: #f8d7da;
    color: #721c24;
}

/* Info meeting */
.meeting-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin: 10px 0;
}

.meeting-info-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.meeting-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    color: #6c757d;
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
        <h1 class="text-white">RISALAH RAPAT DPRD</h1>
        <p class="subtitle">Risalah Rapat DPRD Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Risalah Rapat</li>
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
                    <h5>Cari Risalah Rapat</h5>
                    <form class="search-form" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Masukkan kata kunci risalah..." 
                                   aria-label="Search" id="searchKeyword">
                            <button class="btn btn-search" type="submit" id="button-search">
                                <i class="bi bi-search"></i> CARI
                            </button>
                        </div>
                    </form>
                </div>

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

                <h3 class="section-title">Daftar Risalah Rapat DPRD Kota Surakarta</h3>

                <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="card-container" id="documentsContainer">
                <?php 
                $counter = 0;
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
                    
                    // Tentukan jenis rapat berdasarkan judul atau bidang hukum
                    $jenis_rapat = 'Paripurna';
                    $badge_class = 'badge-pleno';
                    if (!empty($row['bidang_hukum'])) {
                        $bidang = strtolower($row['bidang_hukum']);
                        if (strpos($bidang, 'komisi') !== false) {
                            $jenis_rapat = 'Komisi';
                            $badge_class = 'badge-komisi';
                        } elseif (strpos($bidang, 'bamus') !== false || strpos($bidang, 'musyawarah') !== false) {
                            $jenis_rapat = 'Bamus';
                            $badge_class = 'badge-bamus';
                        } elseif (strpos($bidang, 'pansus') !== false || strpos($bidang, 'khusus') !== false) {
                            $jenis_rapat = 'Pansus';
                            $badge_class = 'badge-pansus';
                        }
                    }
                    
                    // Tentukan status berdasarkan status field
                    $status_text = $row['status'] ?? 'Final';
                    $status_class = 'status-final';
                    if (strpos(strtolower($status_text), 'draft') !== false) {
                        $status_class = 'status-draft';
                    } elseif (strpos(strtolower($status_text), 'koreksi') !== false || strpos(strtolower($status_text), 'revisi') !== false) {
                        $status_class = 'status-correction';
                    }
                    
                    $singkatan = "RSL";
                    
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
                                <i class="bi bi-journal-text"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php else: ?>
                            <div class="placeholder">
                                <i class="bi bi-journal-text"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php endif; ?>
                        <div class="image-overlay">
                            <span class="image-badge"><?= htmlspecialchars($singkatan) ?></span>
                        </div>
                    </div>

                    <div class="card-content">
                        <!-- Badge Jenis Rapat dan Status -->
                        <div class="d-flex flex-wrap mb-3">
                            <span class="status-badge <?= $status_class ?>">
                                <i class="bi bi-<?= $status_class == 'status-final' ? 'check-circle' : ($status_class == 'status-draft' ? 'pencil' : 'exclamation-triangle') ?>"></i> 
                                <?= htmlspecialchars($status_text) ?>
                            </span>
                            <span class="meeting-badge <?= $badge_class ?>">
                                <i class="bi bi-<?= $jenis_rapat == 'Paripurna' ? 'people' : ($jenis_rapat == 'Komisi' ? 'person' : ($jenis_rapat == 'Bamus' ? 'gear' : 'search')) ?>"></i> 
                                <?= htmlspecialchars($jenis_rapat) ?>
                            </span>
                        </div>
                        
                        <div class="card-number">Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></div>
                        <h3 class="card-title monografi-title"><?= htmlspecialchars($row['judul']) ?></h3>

                        <!-- Info Meeting -->
                        <div class="meeting-info">
                            <div class="meeting-info-title">Detail Rapat:</div>
                            <div class="meeting-details">
                                <div class="detail-item">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= $tanggal ?></span>
                                </div>
                                <?php if (!empty($row['penulis'])): ?>
                                <div class="detail-item">
                                    <i class="bi bi-person"></i>
                                    <span>Notulis: <?= htmlspecialchars($row['penulis']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($row['jumlah_halaman'])): ?>
                                <div class="detail-item">
                                    <i class="bi bi-file-text"></i>
                                    <span><?= htmlspecialchars($row['jumlah_halaman']) ?> halaman</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

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
                    <i class="bi bi-journal-text"></i>
                    <h4>Tidak Ada Data</h4>
                    <p class="text-muted mb-0">
                        Tidak ada risalah rapat yang tersedia.
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
                    <h4><i class="bi bi-collection"></i> Menu Monografi Hukum</h4>
                    <ul>
                        <li><a href="pokok-pikiran-dprd.php"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
                        <li><a href="kajian-raperda.php"><i class="bi bi-search"></i> Kajian Raperda</a></li>
                        <li><a href="naskah-akademik.php"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
                        <li><a href="hasil-fasilitasi.php"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                        <li><a href="hasil-harmonisasi.php"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                        <li><a href="karya-ilmiah.php"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                        <li><a href="himpunan.php"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        <li class="active"><a href="#"><i class="bi bi-journal-text"></i> Risalah Rapat</a></li>
                    </ul>
                </div>

                <!-- Statistik -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> Statistik Risalah</h4>
                    <ul>
                        <li>Total: <strong><?= $total_dokumen ?> risalah</strong></li>
                        <?php foreach ($year_stats_data as $stat): ?>
                        <li>
                            <?= $stat['tahun_terbit'] ?>: <strong><?= $stat['jumlah'] ?></strong> risalah
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

                <!-- Risalah Terbaru -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Risalah Terbaru</h4>
                    <ul>
                    <?php foreach ($popular_data as $pop): ?>
                    <li>
                        <a href="index.php?page=detail-monografi&id=<?= $pop['id_monografi'] ?>">
                            <i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($pop['judul'], 0, 40)) ?>...
                        </a>
                        <small class="text-muted d-block">Tahun: <?= htmlspecialchars($pop['tahun_terbit']) ?></small>
                        <small class="text-muted"><?= date('d M Y', strtotime($pop['tanggal_pembuatan'])) ?></small>
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
    const cards = document.querySelectorAll('.document-card');
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
                    Tidak ada risalah yang sesuai dengan filter yang dipilih.
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
    
    document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-btn[data-year='semua']").classList.add("active");
    
    document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-filter-sidebar[data-year='semua']").classList.add("active");
    
    const cards = document.querySelectorAll('.document-card');
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
    
    const cards = document.querySelectorAll('.document-card');
    let foundCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const desc = card.querySelector('.meeting-info')?.textContent.toLowerCase() || '';
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
                    Tidak ada risalah yang sesuai dengan kata kunci "${keyword}".
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
            html: `File PDF untuk risalah <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk risalah "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
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