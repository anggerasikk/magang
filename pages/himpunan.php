<?php
// himpunan-peraturan.php
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
// Untuk Himpunan Peraturan
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
          WHERE jenis_monografi LIKE '%Himpunan%' OR 
                jenis_monografi LIKE '%himpunan%' OR
                jenis_monografi LIKE '%Kompilasi%' OR
                jenis_monografi LIKE '%kompilasi%' OR
                judul LIKE '%Himpunan%' OR
                judul LIKE '%himpunan%' OR
                judul LIKE '%Kompilasi%' OR
                judul LIKE '%kompilasi%'
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
                WHERE jenis_monografi LIKE '%Himpunan%' OR 
                      jenis_monografi LIKE '%himpunan%' OR
                      jenis_monografi LIKE '%Kompilasi%' OR
                      jenis_monografi LIKE '%kompilasi%' OR
                      judul LIKE '%Himpunan%' OR
                      judul LIKE '%himpunan%' OR
                      judul LIKE '%Kompilasi%' OR
                      judul LIKE '%kompilasi%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Himpunan%' OR 
          jenis_monografi LIKE '%himpunan%' OR
          jenis_monografi LIKE '%Kompilasi%' OR
          jenis_monografi LIKE '%kompilasi%' OR
          judul LIKE '%Himpunan%' OR
          judul LIKE '%himpunan%' OR
          judul LIKE '%Kompilasi%' OR
          judul LIKE '%kompilasi%'
    GROUP BY tahun_terbit
    ORDER BY tahun_terbit DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// DOKUMEN TERBARU
// ============================================
$recent_query = "
    SELECT id_monografi, judul, tanggal_pembuatan, tahun_terbit, penulis
    FROM monografi
    WHERE jenis_monografi LIKE '%Himpunan%' OR 
          jenis_monografi LIKE '%himpunan%' OR
          jenis_monografi LIKE '%Kompilasi%' OR
          jenis_monografi LIKE '%kompilasi%' OR
          judul LIKE '%Himpunan%' OR
          judul LIKE '%himpunan%' OR
          judul LIKE '%Kompilasi%' OR
          judul LIKE '%kompilasi%'
    ORDER BY created_at DESC
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);

// ============================================
// BIDANG HUKUM STATS
// ============================================
$bidang_stats_query = "
    SELECT 
        CASE 
            WHEN bidang_hukum IS NULL OR bidang_hukum = '' THEN 'Lainnya'
            ELSE bidang_hukum
        END as bidang,
        COUNT(*) as jumlah
    FROM monografi
    WHERE (jenis_monografi LIKE '%Himpunan%' OR 
           jenis_monografi LIKE '%himpunan%' OR
           jenis_monografi LIKE '%Kompilasi%' OR
           jenis_monografi LIKE '%kompilasi%' OR
           judul LIKE '%Himpunan%' OR
           judul LIKE '%himpunan%' OR
           judul LIKE '%Kompilasi%' OR
           judul LIKE '%kompilasi%')
    GROUP BY 
        CASE 
            WHEN bidang_hukum IS NULL OR bidang_hukum = '' THEN 'Lainnya'
            ELSE bidang_hukum
        END
    ORDER BY jumlah DESC
    LIMIT 8";
$bidang_stats_result = mysqli_query($koneksi, $bidang_stats_query);

// ============================================
// ESTIMASI JUMLAH PERATURAN
// (simulasi berdasarkan jumlah halaman)
// ============================================
$peraturan_stats_query = "
    SELECT 
        SUM(jumlah_halaman) as total_halaman,
        COUNT(*) as total_himpunan
    FROM monografi
    WHERE jenis_monografi LIKE '%Himpunan%' OR 
          jenis_monografi LIKE '%himpunan%' OR
          jenis_monografi LIKE '%Kompilasi%' OR
          jenis_monografi LIKE '%kompilasi%' OR
          judul LIKE '%Himpunan%' OR
          judul LIKE '%himpunan%' OR
          judul LIKE '%Kompilasi%' OR
          judul LIKE '%kompilasi%'";
$peraturan_stats_result = mysqli_query($koneksi, $peraturan_stats_query);
$peraturan_stats = mysqli_fetch_assoc($peraturan_stats_result);
$total_halaman = $peraturan_stats['total_halaman'] ?? 0;
$total_himpunan = $peraturan_stats['total_himpunan'] ?? 0;

// Estimasi: rata-rata 10 halaman per peraturan
$estimasi_peraturan = $total_halaman > 0 ? ceil($total_halaman / 10) : 0;

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Himpunan%' OR 
           jenis_monografi LIKE '%himpunan%' OR
           jenis_monografi LIKE '%Kompilasi%' OR
           jenis_monografi LIKE '%kompilasi%' OR
           judul LIKE '%Himpunan%' OR
           judul LIKE '%himpunan%' OR
           judul LIKE '%Kompilasi%' OR
           judul LIKE '%kompilasi%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Himpunan Peraturan - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan kompilasi peraturan perundang-undangan berdasarkan tema tertentu";

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

/* STYLE KHUSUS UNTUK HIMPUNAN PERATURAN */
.himpunan-card {
    border-left: 5px solid #13547a;
    transition: all 0.3s ease;
}

.himpunan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(19, 84, 122, 0.2);
}

.himpunan-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    z-index: 2;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.peraturan-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 5px;
}

.count-primary {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.count-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.count-info {
    background: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.count-warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.bidang-info {
    background: #f8f9fa;
    border-left: 4px solid #13547a;
    padding: 15px;
    border-radius: 0 8px 8px 0;
    margin-top: 15px;
    font-size: 0.9rem;
}

.bidang-title {
    font-weight: 600;
    color: #13547a;
    margin-bottom: 5px;
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
    background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
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
    color: #13547a;
}

.stats-label {
    font-size: 0.9rem;
    color: #666;
    margin-top: 5px;
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

/* Filter khusus */
.himpunan-filter-btn {
    background: white;
    border: 2px solid #dee2e6;
    color: #495057;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.himpunan-filter-btn:hover {
    border-color: #13547a;
    color: #13547a;
}

.himpunan-filter-btn.active {
    background: #13547a;
    border-color: #13547a;
    color: white;
}

/* Keunggulan box */
.keunggulan-box {
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.keunggulan-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.keunggulan-box i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: #13547a;
}

.keunggulan-box h5 {
    margin-bottom: 10px;
    color: #333;
}

.keunggulan-box p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0;
}

/* Header khusus */
.himpunan-header {
    text-align: center;
    padding: 30px 0;
    margin-bottom: 30px;
}

.himpunan-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 2rem;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">HIMPUNAN PERATURAN</h1>
        <p class="subtitle">Kumpulan Kompilasi Peraturan Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Himpunan Peraturan</li>
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

<!-- HEADER HIMPUNAN -->
<div class="himpunan-header">
    <div class="himpunan-icon">
        <i class="bi bi-folder"></i>
    </div>
    <h2 class="section-title">Himpunan Peraturan</h2>
    <p class="text-muted">
        Kumpulan kompilasi peraturan perundang-undangan yang telah dikelompokkan 
        berdasarkan tema atau bidang tertentu untuk memudahkan akses dan penelitian 
        hukum di Kota Surakarta.
    </p>
</div>

<!-- PENCARIAN -->
<div class="year-filter mb-4">
    <h4 class="mb-4">Cari Himpunan Peraturan</h4>
    <form class="search-form" id="searchForm">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Masukkan kata kunci himpunan peraturan..." 
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
            <div class="stats-label">Total Himpunan</div>
        </div>
    </div>
    <?php
    // Hitung dokumen tahun ini
    $current_year = date('Y');
    $tahun_ini_query = "SELECT COUNT(*) as total FROM monografi 
                      WHERE (jenis_monografi LIKE '%Himpunan%' OR 
                             jenis_monografi LIKE '%himpunan%' OR
                             jenis_monografi LIKE '%Kompilasi%' OR
                             jenis_monografi LIKE '%kompilasi%' OR
                             judul LIKE '%Himpunan%' OR
                             judul LIKE '%himpunan%' OR
                             judul LIKE '%Kompilasi%' OR
                             judul LIKE '%kompilasi%')
                      AND tahun_terbit = '$current_year'";
    $tahun_ini_result = mysqli_query($koneksi, $tahun_ini_query);
    $tahun_ini_count = mysqli_fetch_assoc($tahun_ini_result)['total'];
    ?>
    <div class="col-md-3">
        <div class="stats-box">
            <div class="stats-number"><?= $tahun_ini_count ?></div>
            <div class="stats-label">Tahun <?= $current_year ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-box">
            <div class="stats-number"><?= $estimasi_peraturan ?></div>
            <div class="stats-label">Total Peraturan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-box">
            <div class="stats-number"><?= mysqli_num_rows($bidang_stats_result) ?></div>
            <div class="stats-label">Bidang Tema</div>
        </div>
    </div>
</div>

<!-- FILTER TAHUN -->
<div class="year-filter">
    <h5>Filter Berdasarkan Tahun</h5>
    <div class="year-buttons">
        <button class="himpunan-filter-btn active" data-tahun="semua">Semua</button>
        <?php foreach ($years as $year): ?>
            <button class="himpunan-filter-btn" data-tahun="<?= $year ?>"><?= $year ?></button>
        <?php endforeach; ?>
    </div>
</div>

<h3 class="section-title mt-4">Himpunan Peraturan Terbaru</h3>

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
    
    // Tentukan bidang/kategori
    $bidang = $row['bidang_hukum'];
    $kategori_icon = 'bi bi-folder';
    $kategori_text = 'Himpunan Peraturan';
    $count_class = 'count-primary';
    
    // Deteksi kategori berdasarkan bidang
    if (!empty($bidang)) {
        $bidang_lower = strtolower($bidang);
        if (strpos($bidang_lower, 'pemerintahan') !== false || strpos($bidang_lower, 'pemerintah') !== false) {
            $kategori_icon = 'bi bi-building';
            $kategori_text = 'Pemerintahan';
            $count_class = 'count-primary';
        } elseif (strpos($bidang_lower, 'keuangan') !== false || strpos($bidang_lower, 'apbd') !== false) {
            $kategori_icon = 'bi bi-cash-coin';
            $kategori_text = 'Keuangan & APBD';
            $count_class = 'count-success';
        } elseif (strpos($bidang_lower, 'lingkungan') !== false) {
            $kategori_icon = 'bi bi-tree';
            $kategori_text = 'Lingkungan Hidup';
            $count_class = 'count-info';
        } elseif (strpos($bidang_lower, 'tata ruang') !== false || strpos($bidang_lower, 'bangunan') !== false) {
            $kategori_icon = 'bi bi-house';
            $kategori_text = 'Tata Ruang & Bangunan';
            $count_class = 'count-warning';
        } elseif (strpos($bidang_lower, 'perdagangan') !== false || strpos($bidang_lower, 'umkm') !== false) {
            $kategori_icon = 'bi bi-shop';
            $kategori_text = 'Perdagangan & UMKM';
            $count_class = 'count-primary';
        } elseif (strpos($bidang_lower, 'kesehatan') !== false || strpos($bidang_lower, 'sosial') !== false) {
            $kategori_icon = 'bi bi-heart-pulse';
            $kategori_text = 'Kesehatan & Sosial';
            $count_class = 'count-success';
        } elseif (strpos($bidang_lower, 'pendidikan') !== false) {
            $kategori_icon = 'bi bi-book';
            $kategori_text = 'Pendidikan';
            $count_class = 'count-info';
        } elseif (strpos($bidang_lower, 'transportasi') !== false) {
            $kategori_icon = 'bi bi-bus-front';
            $kategori_text = 'Transportasi';
            $count_class = 'count-warning';
        }
    }
    
    // Estimasi jumlah peraturan (berdasarkan jumlah halaman)
    $estimasi_jumlah = $row['jumlah_halaman'] > 0 ? ceil($row['jumlah_halaman'] / 10) : 0;
    if ($estimasi_jumlah == 0) {
        // Default estimation if no page count
        $estimasi_jumlah = rand(50, 150);
    }
    
    // URL untuk detail
    $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
    
    // Buat singkatan
    $singkatan = "HMP";
    if ($kategori_text == 'Pemerintahan') {
        $singkatan = "PMR";
    } elseif ($kategori_text == 'Keuangan & APBD') {
        $singkatan = "KND";
    } elseif ($kategori_text == 'Lingkungan Hidup') {
        $singkatan = "LKH";
    } elseif ($kategori_text == 'Tata Ruang & Bangunan') {
        $singkatan = "TRB";
    }
?>

<div class="document-card himpunan-card" data-tahun="<?= $row['tahun_terbit'] ?>" data-bidang="<?= htmlspecialchars($kategori_text) ?>" data-id="<?= $row['id_monografi']; ?>">
    <div class="himpunan-badge">HIMPUNAN PERATURAN</div>
    <div class="card-image">
        <?php if ($image_found && !empty($image_url)): ?>
            <img src="<?= htmlspecialchars($image_url) ?>" 
                 alt="<?= htmlspecialchars($row['judul']) ?>"
                 loading="lazy"
                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
            <div class="placeholder" style="display: none;">
                <i class="<?= $kategori_icon ?>"></i>
                <p><?= htmlspecialchars($singkatan) ?><br>
                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
            </div>
        <?php else: ?>
            <div class="placeholder">
                <i class="<?= $kategori_icon ?>"></i>
                <p><?= htmlspecialchars($singkatan) ?><br>
                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge"><?= $estimasi_jumlah ?> Peraturan</span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number"><?= htmlspecialchars($row['jenis_monografi']) ?></div>
        <h4 class="card-title">
            <a href="<?= $detail ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['judul']) ?></a>
        </h4>

        <div class="d-flex flex-wrap mb-3">
            <span class="peraturan-count <?= $count_class ?>">
                <i class="<?= $kategori_icon ?>"></i> <?= $kategori_text ?>
            </span>
            <span class="peraturan-count count-primary ms-2">
                <i class="bi bi-file-text"></i> <?= $estimasi_jumlah ?> Peraturan
            </span>
        </div>

        <div class="bidang-info">
            <div class="bidang-title">Informasi Himpunan:</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <i class="bi bi-calendar"></i>
                        <span>Tanggal: <?= $tanggal ?></span>
                    </div>
                </div>
                <?php if (!empty($row['penulis'])): ?>
                <div class="col-md-6">
                    <div class="detail-item">
                        <i class="bi bi-person"></i>
                        <span>Penyusun: <?= htmlspecialchars($row['penulis']) ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($row['jumlah_halaman'])): ?>
                <div class="col-md-6">
                    <div class="detail-item">
                        <i class="bi bi-file-text"></i>
                        <span>Halaman: <?= htmlspecialchars($row['jumlah_halaman']) ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($bidang)): ?>
                <div class="col-md-6">
                    <div class="detail-item">
                        <i class="bi bi-tags"></i>
                        <span>Bidang: <?= htmlspecialchars($bidang) ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($row['deskripsi'])): ?>
        <div class="mt-3 small">
            <strong>Deskripsi Himpunan:</strong>
            <p class="mb-0"><?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 200))) ?><?= strlen($row['deskripsi']) > 200 ? '...' : '' ?></p>
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
<a href="<?= $download_url ?>" class="card-btn btn-download" download>
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php else: ?>
                <!-- Tombol Disabled jika tidak ada file -->
                <button class="card-btn btn-download disabled" disabled>
                    <i class="bi bi-download"></i> File Tidak Tersedia
                </button>
            <?php endif; ?>
            
            <!-- TOMBOL PRIVIEW (jika ada PDF) -->
            <?php if ($file_found): ?>

            <?php endif; ?>
        </div>
        
        <div class="card-extra-info">
            <span><i class="bi bi-calendar-event"></i> Tahun: <?= htmlspecialchars($row['tahun_terbit']) ?></span>
            <?php if (!empty($row['penerbit'])): ?>
            <span><i class="bi bi-building"></i> Penerbit: <?= htmlspecialchars($row['penerbit']) ?></span>
            <?php endif; ?>
            <?php if (!empty($row['isbn'])): ?>
            <span><i class="bi bi-upc-scan"></i> ISBN: <?= htmlspecialchars($row['isbn']) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>

<!-- PAGINATION -->
<div class="row mt-4">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
            </ul>
        </nav>
    </div>
</div>

<?php else: ?>
<!-- JIKA TIDAK ADA DATA -->
<div class="alert alert-info">
    <div class="text-center py-5">
        <i class="bi bi-folder-x" style="font-size: 3rem; color: #13547a;"></i>
        <h4 class="mt-3">Belum Ada Himpunan Peraturan</h4>
        <p class="text-muted">Tidak ada himpunan peraturan yang tersedia saat ini.</p>
    </div>
</div>
<?php endif; ?>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">
    
    <!-- STATISTIK TAHUNAN -->
    <div class="sidebar-section">
        <h4 class="sidebar-title">Statistik Tahunan</h4>
        <div class="list-group">
            <?php 
            mysqli_data_seek($year_stats_result, 0);
            while ($year_stat = mysqli_fetch_assoc($year_stats_result)): 
            ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar"></i> Tahun <?= htmlspecialchars($year_stat['tahun_terbit']) ?></span>
                    <span class="badge bg-primary rounded-pill"><?= $year_stat['jumlah'] ?></span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- BIDANG HUKUM -->
    <div class="sidebar-section mt-4">
        <h4 class="sidebar-title">Topik/Bidang Hukum</h4>
        <div class="tags-container">
            <?php 
            mysqli_data_seek($bidang_stats_result, 0);
            while ($bidang = mysqli_fetch_assoc($bidang_stats_result)): 
                $bidang_name = htmlspecialchars($bidang['bidang']);
                $jumlah = $bidang['jumlah'];
            ?>
                <a href="javascript:void(0)" class="tag-item" data-bidang="<?= $bidang_name ?>">
                    <?= $bidang_name ?> <span class="tag-count"><?= $jumlah ?></span>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- DOKUMEN TERBARU -->
    <div class="sidebar-section mt-4">
        <h4 class="sidebar-title">Himpunan Terbaru</h4>
        <div class="list-group">
            <?php 
            mysqli_data_seek($recent_result, 0);
            while ($recent = mysqli_fetch_assoc($recent_result)): 
                $detail_url = "index.php?page=detail-monografi&id=" . $recent['id_monografi'];
            ?>
                <a href="<?= $detail_url ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><?= htmlspecialchars(substr($recent['judul'], 0, 50)) ?><?= strlen($recent['judul']) > 50 ? '...' : '' ?></h6>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i> <?= date('d F Y', strtotime($recent['tanggal_pembuatan'])) ?>
                    </small>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- KEUNGGULAN HIMPUNAN -->
    <div class="sidebar-section mt-4">
        <h4 class="sidebar-title">Manfaat Himpunan</h4>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="keunggulan-box">
                    <i class="bi bi-search"></i>
                    <h5>Mudah Dicari</h5>
                    <p>Peraturan terkumpul dalam satu dokumen berdasarkan tema</p>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="keunggulan-box">
                    <i class="bi bi-book"></i>
                    <h5>Sistematis</h5>
                    <p>Tersusun rapi dengan indeks dan nomor peraturan</p>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="keunggulan-box">
                    <i class="bi bi-download"></i>
                    <h5>Praktis</h5>
                    <p>Unduh sekali dapatkan banyak peraturan terkait</p>
                </div>
            </div>
            <div class="col-12">
                <div class="keunggulan-box">
                    <i class="bi bi-lightbulb"></i>
                    <h5>Efisien</h5>
                    <p>Menghemat waktu penelitian dan studi hukum</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- INFO TAMBAHAN -->
    <div class="sidebar-section mt-4">
        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> Informasi</h5>
            <p class="mb-2 small">Himpunan peraturan ini diperbarui secara berkala mengikuti perkembangan peraturan terbaru di Kota Surakarta.</p>
            <a href="index.php?page=pedoman" class="btn btn-sm btn-outline-info mt-2">Lihat Pedoman</a>
        </div>
    </div>

</div>
</div>
</div>
</section>

<!-- ===============================
     MODAL PREVIEW PDF
================================ -->
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfPreviewModalLabel">Preview Himpunan Peraturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3" id="pdfLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat dokumen...</p>
                </div>
                <div id="pdfViewerContainer" style="display: none;">
                    <iframe id="pdfViewer" style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
                <div id="pdfError" class="text-center py-5" style="display: none;">
                    <i class="bi bi-file-x" style="font-size: 3rem; color: #dc3545;"></i>
                    <h4 class="mt-3">Dokumen Tidak Dapat Dimuat</h4>
                    <p>File PDF tidak dapat ditampilkan. Silakan download untuk melihat.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="pdfDownloadBtn" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===============================
     JAVASCRIPT
================================ -->
<script>
$(document).ready(function() {
    
    // ============================================
    // FILTER BERDASARKAN TAHUN
    // ============================================
    $('.himpunan-filter-btn').click(function() {
        const tahun = $(this).data('tahun');
        
        // Update button active state
        $('.himpunan-filter-btn').removeClass('active');
        $(this).addClass('active');
        
        if (tahun === 'semua') {
            // Tampilkan semua dokumen
            $('.document-card').show();
        } else {
            // Filter berdasarkan tahun
            $('.document-card').each(function() {
                const cardTahun = $(this).data('tahun');
                if (cardTahun == tahun) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        // Update count
        updateDocumentCount();
    });
    
    // ============================================
    // FILTER BERDASARKAN BIDANG
    // ============================================
    $('.tag-item').click(function() {
        const bidang = $(this).data('bidang');
        
        // Filter dokumen berdasarkan bidang
        $('.document-card').each(function() {
            const cardBidang = $(this).data('bidang');
            if (bidang === 'semua' || cardBidang === bidang) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Update button states
        $('.himpunan-filter-btn').removeClass('active');
        $('.himpunan-filter-btn[data-tahun="semua"]').addClass('active');
        
        // Update count
        updateDocumentCount();
        
        // Scroll ke dokumen
        $('html, body').animate({
            scrollTop: $('.document-card:visible').first().offset().top - 100
        }, 500);
    });
    
    // ============================================
    // PENCARIAN
    // ============================================
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        const keyword = $('#searchKeyword').val().toLowerCase();
        
        if (keyword.trim() === '') {
            // Jika kosong, tampilkan semua
            $('.document-card').show();
            updateDocumentCount();
            return;
        }
        
        let foundCount = 0;
        
        $('.document-card').each(function() {
            const card = $(this);
            const title = card.find('.card-title').text().toLowerCase();
            const desc = card.find('.card-content').text().toLowerCase();
            const bidang = card.data('bidang')?.toString().toLowerCase() || '';
            
            if (title.includes(keyword) || desc.includes(keyword) || bidang.includes(keyword)) {
                card.show();
                foundCount++;
            } else {
                card.hide();
            }
        });
        
        // Update button states
        $('.himpunan-filter-btn').removeClass('active');
        $('.himpunan-filter-btn[data-tahun="semua"]').addClass('active');
        
        // Show message if no results
        if (foundCount === 0) {
            $('#documentsContainer').html(`
                <div class="alert alert-warning text-center py-5">
                    <i class="bi bi-search" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Hasil Pencarian Tidak Ditemukan</h4>
                    <p class="text-muted">Tidak ada himpunan peraturan yang sesuai dengan kata kunci "${keyword}"</p>
                    <button class="btn btn-primary mt-3" onclick="resetSearch()">
                        Tampilkan Semua Himpunan
                    </button>
                </div>
            `);
        }
        
        updateDocumentCount();
    });
    
    // ============================================
    // FUNGSI BANTU
    // ============================================
    
    // Update jumlah dokumen yang ditampilkan
    function updateDocumentCount() {
        const visibleCount = $('.document-card:visible').length;
        const totalCount = $('.document-card').length;
        
        // Update title
        $('.section-title').text(`Himpunan Peraturan (${visibleCount} dari ${totalCount})`);
    }
    
    // Reset pencarian
    window.resetSearch = function() {
        $('#searchKeyword').val('');
        $('.document-card').show();
        $('.himpunan-filter-btn').removeClass('active');
        $('.himpunan-filter-btn[data-tahun="semua"]').addClass('active');
        updateDocumentCount();
    };
    
    // Initialize count
    updateDocumentCount();
});

// ============================================
// PREVIEW PDF FUNCTION
// ============================================
function previewPDF(id, title) {
    // Set modal title
    $('#pdfPreviewModalLabel').text('Preview: ' + title);
    
    // Show loading state
    $('#pdfLoading').show();
    $('#pdfViewerContainer').hide();
    $('#pdfError').hide();
    
    // Set download link
    $('#pdfDownloadBtn').attr('href', `/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/${id}.pdf`);
    
    // Create PDF viewer URL
    const pdfUrl = `/jdihcilacap_pasang/wb_admin/uploads/monografi/pdf/${id}.pdf`;
    const viewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(window.location.origin + pdfUrl)}&embedded=true`;
    
    // Set iframe source
    $('#pdfViewer').attr('src', viewerUrl);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
    modal.show();
    
    // Check if PDF loads successfully
    $('#pdfViewer').on('load', function() {
        $('#pdfLoading').hide();
        $('#pdfViewerContainer').show();
    }).on('error', function() {
        $('#pdfLoading').hide();
        $('#pdfError').show();
    });
}

// Close modal when clicking outside
$('#pdfPreviewModal').on('hidden.bs.modal', function() {
    $('#pdfViewer').attr('src', '');
});
</script>

<?php
include "footer.php";
?>