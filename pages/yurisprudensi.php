<?php
// yurisprudensi.php
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
$base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/";
$yurisprudensi_pdf_path = "wb_admin/uploads/yurisprudensi/";
$yurisprudensi_images_path = $_SERVER['DOCUMENT_ROOT'] . "/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/";
$web_yurisprudensi_images_path = "/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/";


$web_yurisprudensi_pdf_path = "/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/";
$web_yurisprudensi_images_path = "/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/";

// ============================================
// FUNGSI BANTU UNTUK FILE
// ============================================

// Fungsi untuk menemukan file PDF di server
function findYurisprudensiPDFFile($filename, $id = null) {
    global $yurisprudensi_pdf_path;
    
    if (empty($filename)) {
        return null;
    }
    
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    $possible_locations = [
        $yurisprudensi_pdf_path . $clean_filename,
        str_replace('/', '\\', $yurisprudensi_pdf_path) . $clean_filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/yurisprudensi/' . $clean_filename,
    ];
    
    if ($id) {
        $possible_locations[] = $yurisprudensi_pdf_path . $id . '.pdf';
        $possible_locations[] = $yurisprudensi_pdf_path . $id . '.PDF';
        $possible_locations[] = $yurisprudensi_pdf_path . 'yurisprudensi_' . $id . '.pdf';
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

// Fungsi untuk menemukan gambar cover
function findYurisprudensiImageFile($filename, $id = null) {
    global $yurisprudensi_images_path;

    $exts = ['jpg','jpeg','png','webp','gif'];

    if (!empty($filename)) {
        $filename = basename($filename);
        if (file_exists($yurisprudensi_images_path . $filename)) {
            return $filename;
        }
    }

    if ($id) {
        foreach ($exts as $ext) {
            $variants = [
                $id . '.' . $ext,
                'cover_' . $id . '.' . $ext,
                'yurisprudensi_' . $id . '.' . $ext,
            ];

            foreach ($variants as $file) {
                if (file_exists($yurisprudensi_images_path . $file)) {
                    return $file;
                }
            }
        }
    }

    return null;
}


// ============================================
// VARIABEL PENCARIAN DAN FILTER
// ============================================
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$jenis_peradilan = isset($_GET['jenis_peradilan']) ? trim($_GET['jenis_peradilan']) : '';
$tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';
$bidang_hukum = isset($_GET['bidang_hukum']) ? trim($_GET['bidang_hukum']) : '';

// ============================================
// QUERY DATA UTAMA DENGAN FILTER
// ============================================
$where_conditions = [];
$params = [];
$param_types = "";

// Filter jenis peradilan
if (!empty($jenis_peradilan)) {
    $where_conditions[] = "jenis_peradilan = ?";
    $params[] = $jenis_peradilan;
    $param_types .= "s";
}

// Filter tahun
if (!empty($tahun) && $tahun !== 'semua') {
    $where_conditions[] = "tahun_putusan = ?";
    $params[] = $tahun;
    $param_types .= "s";
}

// Filter bidang hukum
if (!empty($bidang_hukum)) {
    $where_conditions[] = "bidang_hukum_jenis_perkara = ?";
    $params[] = $bidang_hukum;
    $param_types .= "s";
}

// Pencarian
if (!empty($search)) {
    $where_conditions[] = "(judul LIKE ? OR nomor_putusan LIKE ? OR kata_kunci LIKE ? OR deskripsi LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $param_types .= "ssss";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query untuk data utama
$query = "SELECT 
            id_yurisprudensi,
            tipe_dokumen,
            judul,
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
            created_at
          FROM yurisprudensi 
          $where_clause
          ORDER BY tanggal_dibacakan DESC, created_at DESC
          LIMIT 12";

// Eksekusi query
if (!empty($params)) {
    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = false;
    }
} else {
    $result = mysqli_query($koneksi, $query);
}

// ============================================
// TOTAL PUTUSAN
// ============================================
$total_query = "SELECT COUNT(*) as total FROM yurisprudensi";
$total_result = mysqli_query($koneksi, $total_query);
$total_putusan = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_putusan, COUNT(*) as jumlah
    FROM yurisprudensi
    GROUP BY tahun_putusan
    ORDER BY tahun_putusan DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// JENIS PERADILAN
// ============================================
$jenis_peradilan_query = "
    SELECT DISTINCT jenis_peradilan, COUNT(*) as jumlah
    FROM yurisprudensi
    WHERE jenis_peradilan IS NOT NULL AND jenis_peradilan != ''
    GROUP BY jenis_peradilan
    ORDER BY jumlah DESC";
$jenis_peradilan_result = mysqli_query($koneksi, $jenis_peradilan_query);

// ============================================
// BIDANG HUKUM
// ============================================
$bidang_hukum_query = "
    SELECT DISTINCT bidang_hukum_jenis_perkara, COUNT(*) as jumlah
    FROM yurisprudensi
    WHERE bidang_hukum_jenis_perkara IS NOT NULL AND bidang_hukum_jenis_perkara != ''
    GROUP BY bidang_hukum_jenis_perkara
    ORDER BY jumlah DESC
    LIMIT 10";
$bidang_hukum_result = mysqli_query($koneksi, $bidang_hukum_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_putusan
    FROM yurisprudensi
    WHERE tahun_putusan IS NOT NULL
    ORDER BY tahun_putusan DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
if ($years_result) {
    while ($row = mysqli_fetch_assoc($years_result)) {
        $years[] = $row['tahun_putusan'];
    }
}

// ============================================
// PUTUSAN TERBARU
// ============================================
$terbaru_query = "
    SELECT id_yurisprudensi, judul, nomor_putusan, tanggal_dibacakan, jenis_peradilan
    FROM yurisprudensi
    ORDER BY tanggal_dibacakan DESC
    LIMIT 5";
$terbaru_result = mysqli_query($koneksi, $terbaru_query);

// ============================================
// HEADER
// ============================================
$page_title = "Yurisprudensi - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan putusan pengadilan yang menjadi acuan dalam penegakan hukum di Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>

/

</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white">YURISPRUDENSI</h1>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Yurisprudensi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT
================================ -->
<section class="main-content">
    <div class="container">
        <div class="row">
            
            <!-- KONTEN UTAMA -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                
                <!-- HEADER YURISPRUDENSI -->
                <div class="header-yurisprudensi">
                    <div class="header-content">
                        <div class="monografi-icon">
                            <img src="images/logo.png" alt="Logo JDIH">
                        </div>
                        <h2 class="section-title">Yurisprudensi</h2>
                        <p class="lead mb-4">
                            Kumpulan putusan pengadilan yang menjadi acuan dalam penegakan hukum 
                            dan pembentukan yurisprudensi di Kota Surakarta.
                        </p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?= $total_putusan ?></div>
                                    <div class="stats-label">Total Putusan</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <?php 
                                $tahun_ini = date('Y');
                                $query_tahun = "SELECT COUNT(*) as total FROM yurisprudensi WHERE tahun_putusan = ?";
                                $stmt_tahun = mysqli_prepare($koneksi, $query_tahun);
                                mysqli_stmt_bind_param($stmt_tahun, "s", $tahun_ini);
                                mysqli_stmt_execute($stmt_tahun);
                                $result_tahun = mysqli_stmt_get_result($stmt_tahun);
                                $tahun_ini_total = mysqli_fetch_assoc($result_tahun)['total'] ?? 0;
                                ?>
                                <div class="stats-card">
                                    <div class="stats-number text-primary"><?= $tahun_ini_total ?></div>
                                    <div class="stats-label">Tahun <?= $tahun_ini ?></div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <?php 
                                $query_perdata = "SELECT COUNT(*) as total FROM yurisprudensi WHERE bidang_hukum_jenis_perkara LIKE '%perdata%'";
                                $result_perdata = mysqli_query($koneksi, $query_perdata);
                                $perdata_total = mysqli_fetch_assoc($result_perdata)['total'] ?? 0;
                                ?>
                                <div class="stats-card">
                                    <div class="stats-number text-success"><?= $perdata_total ?></div>
                                    <div class="stats-label">Perdata</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            
                <!-- PENCARIAN -->
                <div class="year-filter mb-4">
                    <h4 class="mb-4">Cari Putusan Pengadilan</h4>
                    <form method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari nomor perkara, pokok perkara, atau kata kunci..." 
                                   value="<?= htmlspecialchars($search) ?>"
                                   aria-label="Search">
                            <button class="btn btn-view" type="submit">
                                <i class="bi bi-search"></i> CARI
                            </button>
                        </div>
                        <div class="text-end mt-2">
                            <a href="#" class="adv-search-link text-decoration-none" onclick="toggleAdvancedSearch()">
                                <i class="bi bi-sliders"></i> Pencarian Lanjutan
                            </a>
                        </div>
                        
                        <!-- FILTER LANJUTAN -->
                        <div id="advancedSearch" style="display: none; margin-top: 15px;">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <select class="form-control filter-select" name="jenis_peradilan">
                                        <option value="">Semua Jenis Peradilan</option>
                                        <option value="Peradilan Umum" <?= $jenis_peradilan == 'Peradilan Umum' ? 'selected' : '' ?>>Peradilan Umum</option>
                                        <option value="Peradilan Agama" <?= $jenis_peradilan == 'Peradilan Agama' ? 'selected' : '' ?>>Peradilan Agama</option>
                                        <option value="Peradilan Tata Usaha Negara" <?= $jenis_peradilan == 'Peradilan Tata Usaha Negara' ? 'selected' : '' ?>>Tata Usaha Negara</option>
                                        <option value="Mahkamah Konstitusi" <?= $jenis_peradilan == 'Mahkamah Konstitusi' ? 'selected' : '' ?>>Mahkamah Konstitusi</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <select class="form-control filter-select" name="bidang_hukum">
                                        <option value="">Semua Bidang Hukum</option>
                                        <?php 
                                        mysqli_data_seek($bidang_hukum_result, 0);
                                        while ($bh = mysqli_fetch_assoc($bidang_hukum_result)): 
                                        ?>
                                            <option value="<?= htmlspecialchars($bh['bidang_hukum_jenis_perkara']) ?>" 
                                                <?= $bidang_hukum == $bh['bidang_hukum_jenis_perkara'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($bh['bidang_hukum_jenis_perkara']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <select class="form-control filter-select" name="tahun">
                                        <option value="">Semua Tahun</option>
                                        <?php foreach ($years as $year): ?>
                                            <option value="<?= $year ?>" <?= $tahun == $year ? 'selected' : '' ?>>
                                                <?= $year ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                            
                <!-- STATISTIK -->
                <div class="row mb-4">
                    <?php 
                    mysqli_data_seek($year_stats_result, 0);
                    $counter = 0;
                    while ($stat = mysqli_fetch_assoc($year_stats_result)): 
                        $counter++;
                        $colors = ['text-primary', 'text-success', 'text-warning', 'text-info'];
                        $color = $colors[$counter % count($colors)] ?? 'text-primary';
                    ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-number <?= $color ?>"><?= $stat['jumlah'] ?></div>
                            <div class="stats-label">Tahun <?= $stat['tahun_putusan'] ?></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                            
                <!-- DAFTAR PUTUSAN -->
                <h3 class="section-title">Putusan Terbaru</h3>
                
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="card-container">
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $tanggal = date('d F Y', strtotime($row['tanggal_dibacakan']));
                        
                        // Cari file PDF
                        $file_from_db = $row['lampiran_path'];
                        $file_found = false;
                        $actual_file_path = null;
                        $download_url = '#';
                        
                        if (!empty($file_from_db)) {
                            $actual_file_path = findYurisprudensiPDFFile($file_from_db, $row['id_yurisprudensi']);
                            if ($actual_file_path) {
                                $file_found = true;
                                $image_found = false;
$image_url = '';

$cover_file = findYurisprudensiImageFile($row['cover_path'], $row['id_yurisprudensi']);

if ($cover_file) {
    $image_found = true;
    $image_url = $web_yurisprudensi_images_path . $cover_file;
}

                            }
                        }
                        
                        // Cari gambar cover
                        $image_url = '';
                        $image_found = false;
                        
                        if (!empty($row['cover_path'])) {
                            $actual_image_path = findYurisprudensiImageFile($row['cover_path'], $row['id_yurisprudensi']);
                            if ($actual_image_path) {
                                $image_found = true;
                                $image_found = false;
$image_url = '';

$cover_file = findYurisprudensiImageFile($row['cover_path'], $row['id_yurisprudensi']);

if ($cover_file) {
    $image_found = true;
    $image_url = $web_yurisprudensi_images_path . $cover_file;
}

                            }
                        } else {
                            $actual_image_path = findYurisprudensiImageFile(null, $row['id_yurisprudensi']);
                            if ($actual_image_path) {
                                $image_found = true;
                                $image_found = false;
$image_url = '';

$cover_file = findYurisprudensiImageFile($row['cover_path'], $row['id_yurisprudensi']);

if ($cover_file) {
    $image_found = true;
    $image_url = $web_yurisprudensi_images_path . $cover_file;
}

                            }
                        }
                        
                        if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
                            $image_url = '/' . $image_url;
                        }
                        
                        // Tentukan badge berdasarkan jenis peradilan
                        $badge_class = 'badge-tun';
                        if (stripos($row['jenis_peradilan'], 'Perdata') !== false) {
                            $badge_class = 'badge-perdata';
                        } elseif (stripos($row['jenis_peradilan'], 'Pidana') !== false) {
                            $badge_class = 'badge-pidana';
                        } elseif (stripos($row['jenis_peradilan'], 'Konstitusi') !== false) {
                            $badge_class = 'badge-mk';
                        }
                        
                        // Tentukan status putusan
                        $status_class = 'status-tetap';
                        if (stripos($row['status_putusan'], 'Kasasi') !== false) {
                            $status_class = 'status-kasasi';
                        } elseif (stripos($row['status_putusan'], 'Peninjauan') !== false) {
                            $status_class = 'status-peninjauan';
                        }
                        
                        $detail = "index.php?page=detail-yurisprudensi&id=" . $row['id_yurisprudensi'];
                    ?>
                    
                    <div class="document-card">
                        <div class="card-image">
                            <?php if ($image_found && !empty($image_url)): ?>
                                <img src="<?= htmlspecialchars($image_url) ?>" 
                                     alt="<?= htmlspecialchars($row['judul']) ?>"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
                                <div class="placeholder" style="display: none;">
                                    <i class="bi bi-gavel"></i>
                                    <p><?= htmlspecialchars($row['singkatan_jenis_peradilan']) ?><br>
                                    <small><?= htmlspecialchars($row['tahun_putusan']) ?></small></p>
                                </div>
                            <?php else: ?>
                                <div class="placeholder">
                                    <i class="bi bi-gavel"></i>
                                    <p><?= htmlspecialchars($row['singkatan_jenis_peradilan']) ?><br>
                                    <small><?= htmlspecialchars($row['tahun_putusan']) ?></small></p>
                                </div>
                            <?php endif; ?>
                            <div class="image-overlay">
                                <span class="image-badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($row['singkatan_jenis_peradilan']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-number"><?= htmlspecialchars($row['nomor_putusan']) ?></div>
                            <h3 class="card-title">
                                <a href="<?= $detail ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </a>
                            </h3>
                            
                            <div class="putusan-info">
                                <div class="info-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Dibacakan: <?= $tanggal ?></span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-building"></i>
                                    <span><?= htmlspecialchars($row['tempat_peradilan']) ?></span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-tags"></i>
                                    <span><?= htmlspecialchars($row['bidang_hukum_jenis_perkara']) ?></span>
                                </div>
                            </div>
                            
                            <div class="status-putusan <?= $status_class ?>">
                                <i class="bi bi-check-circle"></i>
                                <?= htmlspecialchars($row['status_putusan']) ?>
                            </div>
                            
                            <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                                <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                                <?= $file_found ? 'File Putusan tersedia' : 'File Putusan tidak tersedia' ?>
                            </div>

                            <div class="card-actions">
                                <a href="<?= $detail ?>" class="card-btn btn-view">
                                    <i class="bi bi-eye"></i> Baca Putusan
                                </a>
                                
                                <?php if ($file_found): ?>
                                    <a href="<?= htmlspecialchars($download_url) ?>" 
                                       class="card-btn btn-download" 
                                       download>
                                        <i class="bi bi-download"></i> Unduh PDF
                                    </a>
                                <?php else: ?>
                                    <a href="#" 
                                       class="card-btn btn-download disabled" 
                                       onclick="event.preventDefault(); alert('File putusan tidak tersedia untuk diunduh.');">
                                        <i class="bi bi-download"></i> Unduh PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php endwhile; ?>
                </div>
                
                <!-- PAGINATION -->
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
                
                <?php else: ?>
                <div class="no-data">
                    <i class="bi bi-gavel"></i>
                    <h4>Tidak Ada Data</h4>
                    <p class="text-muted mb-0">
                        <?php 
                        if (!empty($search)) {
                            echo 'Tidak ada putusan yang sesuai dengan pencarian "' . htmlspecialchars($search) . '".';
                        } else {
                            echo 'Belum ada putusan yang tersedia.';
                        }
                        ?>
                    </p>
                    <?php if (!empty($search)): ?>
                    <a href="yurisprudensi.php" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                            
                <!-- KEUNGGULAN YURISPRUDENSI -->
                <div class="year-filter mt-5">
                    <h4 class="section-title"><i class="bi bi-award text-primary me-2"></i>Manfaat Yurisprudensi</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="manfaat-item">
                                <div class="mb-3">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <h5>Kepastian Hukum</h5>
                                <p class="small">Memberikan pedoman dalam penegakan hukum</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="manfaat-item">
                                <div class="mb-3">
                                    <i class="bi bi-book"></i>
                                </div>
                                <h5>Pembelajaran</h5>
                                <p class="small">Referensi bagi praktisi hukum dan akademisi</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="manfaat-item">
                                <div class="mb-3">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5>Perlindungan</h5>
                                <p class="small">Melindungi hak-hak masyarakat melalui putusan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- MENU MONOGRAFI -->
                <div class="sidebar-menu">
                    
                </div>
                
                <!-- FILTER JENIS PERADILAN -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-filter"></i> Filter Berdasarkan Jenis</h4>
                    <div class="list-group">
                        <?php 
                        mysqli_data_seek($jenis_peradilan_result, 0);
                        $colors = ['primary', 'success', 'warning', 'info'];
                        $color_index = 0;
                        while ($jp = mysqli_fetch_assoc($jenis_peradilan_result)): 
                            $color = $colors[$color_index % count($colors)];
                            $color_index++;
                        ?>
                        <a href="?jenis_peradilan=<?= urlencode($jp['jenis_peradilan']) ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $jenis_peradilan == $jp['jenis_peradilan'] ? 'active' : '' ?>">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-house text-<?= $color ?> me-2"></i>
                                <?= htmlspecialchars($jp['jenis_peradilan']) ?>
                            </span>
                            <span class="badge bg-<?= $color ?> rounded-pill"><?= $jp['jumlah'] ?></span>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <!-- PUTUSAN TERBARU -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Putusan Terbaru</h4>
                    <ul>
                    <?php 
                    mysqli_data_seek($terbaru_result, 0);
                    while ($terbaru = mysqli_fetch_assoc($terbaru_result)): 
                    ?>
                        <li>
                            <a href="detail-yurisprudensi.php?id=<?= $terbaru['id_yurisprudensi'] ?>">
                                <i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($terbaru['nomor_putusan'], 0, 20)) ?>...
                            </a>
                            <small class="text-muted d-block"><?= htmlspecialchars(substr($terbaru['judul'], 0, 30)) ?>...</small>
                            <small class="text-muted"><?= date('d M Y', strtotime($terbaru['tanggal_dibacakan'])) ?></small>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>
                
                <!-- DOWNLOAD TERPOPULER -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Download Terpopuler</h4>
                    <ul>
                        <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Kompilasi Putusan 2023 (PDF)</a></li>
                        <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Putusan TUN Lengkap (ZIP)</a></li>
                        <li><a href="#"><i class="bi bi-file-earmark-word"></i> Analisis Yurisprudensi (DOC)</a></li>
                        <li><a href="#"><i class="bi bi-file-earmark-excel"></i> Database Putusan (XLS)</a></li>
                    </ul>
                </div>
                
                <!-- INFORMASI PENTING -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian</h6>
                        <p class="small mb-2">Yurisprudensi ini:</p>
                        <ul class="small mb-0 ps-3">
                            <li>Putusan yang sudah memiliki kekuatan hukum tetap</li>
                            <li>Dapat dijadikan acuan dalam penegakan hukum</li>
                            <li>Sudah melalui proses anonimisasi data</li>
                            <li>Untuk kepentingan akademik dan penelitian</li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>

<script>
// Fungsi untuk toggle pencarian lanjutan
function toggleAdvancedSearch() {
    const advancedSearch = document.getElementById('advancedSearch');
    if (advancedSearch.style.display === 'none') {
        advancedSearch.style.display = 'block';
    } else {
        advancedSearch.style.display = 'none';
    }
    return false;
}

// Fungsi filter tahun
document.querySelectorAll(".year-btn").forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        const selectedYear = this.dataset.year;
        
        if (selectedYear === "semua") {
            window.location.href = "yurisprudensi.php";
        } else {
            window.location.href = "yurisprudensi.php?tahun=" + selectedYear;
        }
    });
});

// Fungsi filter select
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        const form = this.closest('form');
        if (form) {
            form.submit();
        }
    });
});

// Highlight menu aktif
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    
    sidebarLinks.forEach(link => {
        if (link.href === currentUrl || link.href.includes('yurisprudensi')) {
            link.classList.add('active');
            link.parentElement.classList.add('active');
        }
    });
});

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Tambahkan style untuk notification
const notificationStyle = document.createElement('style');
notificationStyle.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(100%);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        min-width: 300px;
        border-left: 4px solid #3498db;
    }
    
    .notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .notification.success {
        border-left-color: #27ae60;
    }
    
    .notification.warning {
        border-left-color: #e74c3c;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-content i {
        font-size: 1.2rem;
    }
    
    .notification-content span {
        color: #333;
        font-weight: 500;
    }
`;
document.head.appendChild(notificationStyle);
</script>

<?php 
// Tutup statement dan koneksi database
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
if (isset($stmt_tahun)) {
    mysqli_stmt_close($stmt_tahun);
}

mysqli_close($koneksi);

include 'footer.php'; 
?>