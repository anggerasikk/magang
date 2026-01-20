<?php
// karya-ilmiah.php
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
// Untuk Karya Ilmiah
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
          WHERE jenis_monografi LIKE '%Karya_Ilmiah%' OR 
                jenis_monografi LIKE '%karya_ilmiah%' OR
                jenis_monografi LIKE '%Jurnal%' OR
                jenis_monografi LIKE '%jurnal%' OR
                jenis_monografi LIKE '%Skripsi%' OR
                jenis_monografi LIKE '%skripsi%' OR
                jenis_monografi LIKE '%Tesis%' OR
                jenis_monografi LIKE '%tesis%' OR
                jenis_monografi LIKE '%Artikel%' OR
                jenis_monografi LIKE '%artikel%' OR
                judul LIKE '%Karya Ilmiah%' OR
                judul LIKE '%Jurnal%' OR
                judul LIKE '%Skripsi%' OR
                judul LIKE '%Tesis%' OR
                judul LIKE '%Artikel%'
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
                WHERE jenis_monografi LIKE '%Karya_Ilmiah%' OR 
                      jenis_monografi LIKE '%karya_ilmiah%' OR
                      jenis_monografi LIKE '%Jurnal%' OR
                      jenis_monografi LIKE '%jurnal%' OR
                      jenis_monografi LIKE '%Skripsi%' OR
                      jenis_monografi LIKE '%skripsi%' OR
                      jenis_monografi LIKE '%Tesis%' OR
                      jenis_monografi LIKE '%tesis%' OR
                      jenis_monografi LIKE '%Artikel%' OR
                      jenis_monografi LIKE '%artikel%' OR
                      judul LIKE '%Karya Ilmiah%' OR
                      judul LIKE '%Jurnal%' OR
                      judul LIKE '%Skripsi%' OR
                      judul LIKE '%Tesis%' OR
                      judul LIKE '%Artikel%'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Karya_Ilmiah%' OR 
          jenis_monografi LIKE '%karya_ilmiah%' OR
          jenis_monografi LIKE '%Jurnal%' OR
          jenis_monografi LIKE '%jurnal%' OR
          jenis_monografi LIKE '%Skripsi%' OR
          jenis_monografi LIKE '%skripsi%' OR
          jenis_monografi LIKE '%Tesis%' OR
          jenis_monografi LIKE '%tesis%' OR
          jenis_monografi LIKE '%Artikel%' OR
          jenis_monografi LIKE '%artikel%' OR
          judul LIKE '%Karya Ilmiah%' OR
          judul LIKE '%Jurnal%' OR
          judul LIKE '%Skripsi%' OR
          judul LIKE '%Tesis%' OR
          judul LIKE '%Artikel%'
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
    WHERE jenis_monografi LIKE '%Karya_Ilmiah%' OR 
          jenis_monografi LIKE '%karya_ilmiah%' OR
          jenis_monografi LIKE '%Jurnal%' OR
          jenis_monografi LIKE '%jurnal%' OR
          jenis_monografi LIKE '%Skripsi%' OR
          jenis_monografi LIKE '%skripsi%' OR
          jenis_monografi LIKE '%Tesis%' OR
          jenis_monografi LIKE '%tesis%' OR
          jenis_monografi LIKE '%Artikel%' OR
          jenis_monografi LIKE '%artikel%' OR
          judul LIKE '%Karya Ilmiah%' OR
          judul LIKE '%Jurnal%' OR
          judul LIKE '%Skripsi%' OR
          judul LIKE '%Tesis%' OR
          judul LIKE '%Artikel%'
    ORDER BY created_at DESC
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);

// ============================================
// JENIS KARYA STATS
// ============================================
$jenis_stats_query = "
    SELECT 
        CASE 
            WHEN jenis_monografi LIKE '%Jurnal%' OR jenis_monografi LIKE '%jurnal%' OR judul LIKE '%Jurnal%' THEN 'Jurnal'
            WHEN jenis_monografi LIKE '%Skripsi%' OR jenis_monografi LIKE '%skripsi%' OR judul LIKE '%Skripsi%' THEN 'Skripsi'
            WHEN jenis_monografi LIKE '%Tesis%' OR jenis_monografi LIKE '%tesis%' OR judul LIKE '%Tesis%' THEN 'Tesis'
            WHEN jenis_monografi LIKE '%Artikel%' OR jenis_monografi LIKE '%artikel%' OR judul LIKE '%Artikel%' THEN 'Artikel Ilmiah'
            ELSE 'Karya Ilmiah Lainnya'
        END as jenis_karya,
        COUNT(*) as jumlah
    FROM monografi
    WHERE jenis_monografi LIKE '%Karya_Ilmiah%' OR 
          jenis_monografi LIKE '%karya_ilmiah%' OR
          jenis_monografi LIKE '%Jurnal%' OR
          jenis_monografi LIKE '%jurnal%' OR
          jenis_monografi LIKE '%Skripsi%' OR
          jenis_monografi LIKE '%skripsi%' OR
          jenis_monografi LIKE '%Tesis%' OR
          jenis_monografi LIKE '%tesis%' OR
          jenis_monografi LIKE '%Artikel%' OR
          jenis_monografi LIKE '%artikel%' OR
          judul LIKE '%Karya Ilmiah%' OR
          judul LIKE '%Jurnal%' OR
          judul LIKE '%Skripsi%' OR
          judul LIKE '%Tesis%' OR
          judul LIKE '%Artikel%'
    GROUP BY 
        CASE 
            WHEN jenis_monografi LIKE '%Jurnal%' OR jenis_monografi LIKE '%jurnal%' OR judul LIKE '%Jurnal%' THEN 'Jurnal'
            WHEN jenis_monografi LIKE '%Skripsi%' OR jenis_monografi LIKE '%skripsi%' OR judul LIKE '%Skripsi%' THEN 'Skripsi'
            WHEN jenis_monografi LIKE '%Tesis%' OR jenis_monografi LIKE '%tesis%' OR judul LIKE '%Tesis%' THEN 'Tesis'
            WHEN jenis_monografi LIKE '%Artikel%' OR jenis_monografi LIKE '%artikel%' OR judul LIKE '%Artikel%' THEN 'Artikel Ilmiah'
            ELSE 'Karya Ilmiah Lainnya'
        END
    ORDER BY jumlah DESC";
$jenis_stats_result = mysqli_query($koneksi, $jenis_stats_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM monografi
    WHERE (jenis_monografi LIKE '%Karya_Ilmiah%' OR 
           jenis_monografi LIKE '%karya_ilmiah%' OR
           jenis_monografi LIKE '%Jurnal%' OR
           jenis_monografi LIKE '%jurnal%' OR
           jenis_monografi LIKE '%Skripsi%' OR
           jenis_monografi LIKE '%skripsi%' OR
           jenis_monografi LIKE '%Tesis%' OR
           jenis_monografi LIKE '%tesis%' OR
           jenis_monografi LIKE '%Artikel%' OR
           jenis_monografi LIKE '%artikel%' OR
           judul LIKE '%Karya Ilmiah%' OR
           judul LIKE '%Jurnal%' OR
           judul LIKE '%Skripsi%' OR
           judul LIKE '%Tesis%' OR
           judul LIKE '%Artikel%')
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// PENULIS STATS
// ============================================
$penulis_stats_query = "
    SELECT penulis, COUNT(*) as jumlah
    FROM monografi
    WHERE (jenis_monografi LIKE '%Karya_Ilmiah%' OR 
           jenis_monografi LIKE '%karya_ilmiah%' OR
           jenis_monografi LIKE '%Jurnal%' OR
           jenis_monografi LIKE '%jurnal%' OR
           jenis_monografi LIKE '%Skripsi%' OR
           jenis_monografi LIKE '%skripsi%' OR
           jenis_monografi LIKE '%Tesis%' OR
           jenis_monografi LIKE '%tesis%' OR
           jenis_monografi LIKE '%Artikel%' OR
           jenis_monografi LIKE '%artikel%' OR
           judul LIKE '%Karya Ilmiah%' OR
           judul LIKE '%Jurnal%' OR
           judul LIKE '%Skripsi%' OR
           judul LIKE '%Tesis%' OR
           judul LIKE '%Artikel%')
    AND penulis IS NOT NULL AND penulis != ''
    GROUP BY penulis
    ORDER BY jumlah DESC
    LIMIT 10";
$penulis_stats_result = mysqli_query($koneksi, $penulis_stats_query);

// ============================================
// HEADER
// ============================================
$page_title = "Penulisan Karya Ilmiah - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan karya ilmiah terkait hukum dan legislasi";

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

/* STYLE KHUSUS UNTUK KARYA ILMIAH */
.karya-ilmiah-card {
    border-left: 5px solid #007bff;
    transition: all 0.3s ease;
}

.karya-ilmiah-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.2);
}

.karya-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #007bff, #6610f2);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    z-index: 2;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.karya-type {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.type-jurnal {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.type-skripsi {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.type-tesis {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.type-artikel {
    background: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.abstrak-info {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    border-radius: 0 8px 8px 0;
    margin-top: 15px;
    font-size: 0.9rem;
}

.abstrak-title {
    font-weight: 600;
    color: #007bff;
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
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
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
    color: #007bff;
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
.karya-filter-btn {
    background: white;
    border: 2px solid #dee2e6;
    color: #495057;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.karya-filter-btn:hover {
    border-color: #007bff;
    color: #007bff;
}

.karya-filter-btn.active {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

/* Kategori karya */
.kategori-box {
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.kategori-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.kategori-box i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: #007bff;
}

.kategori-box h5 {
    margin-bottom: 10px;
    color: #333;
}

.kategori-box p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0;
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">PENULISAN KARYA ILMIAH</h1>
        <p class="subtitle">Karya Ilmiah Hukum dan Legislasi Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Penulisan Karya Ilmiah</li>
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

<!-- HEADER KARYA ILMIAH -->
<div class="monografi-container">
    <div class="monografi-header">
        <div class="monografi-icon">
            <i class="bi bi-pencil"></i>
        </div>
        <h2 class="section-title">Penulisan Karya Ilmiah</h2>
        <p class="monografi-description">
            Kumpulan karya ilmiah yang dihasilkan oleh anggota DPRD, staf ahli, akademisi, 
            dan peneliti terkait dengan aspek hukum, legislasi, dan pemerintahan daerah 
            Kota Surakarta.
        </p>
    </div>
    
    <!-- PENCARIAN -->
    <div class="year-filter">
        <h5>Cari Karya Ilmiah</h5>
        <form class="search-form" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Masukkan kata kunci karya ilmiah..." 
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
                <div class="stats-label">Total Karya</div>
            </div>
        </div>
        <?php
        mysqli_data_seek($jenis_stats_result, 0);
        $jenis_counts = [];
        $counter = 0;
        while ($jenis = mysqli_fetch_assoc($jenis_stats_result)) {
            if ($counter < 3) { // Ambil 3 jenis pertama
                $jenis_counts[$jenis['jenis_karya']] = $jenis['jumlah'];
                $counter++;
            }
        }
        
        // Hitung dokumen tahun ini
        $current_year = date('Y');
        $tahun_ini_query = "SELECT COUNT(*) as total FROM monografi 
                          WHERE (jenis_monografi LIKE '%Karya_Ilmiah%' OR 
                                 jenis_monografi LIKE '%karya_ilmiah%' OR
                                 jenis_monografi LIKE '%Jurnal%' OR
                                 jenis_monografi LIKE '%jurnal%' OR
                                 jenis_monografi LIKE '%Skripsi%' OR
                                 jenis_monografi LIKE '%skripsi%' OR
                                 jenis_monografi LIKE '%Tesis%' OR
                                 jenis_monografi LIKE '%tesis%' OR
                                 jenis_monografi LIKE '%Artikel%' OR
                                 jenis_monografi LIKE '%artikel%' OR
                                 judul LIKE '%Karya Ilmiah%' OR
                                 judul LIKE '%Jurnal%' OR
                                 judul LIKE '%Skripsi%' OR
                                 judul LIKE '%Tesis%' OR
                                 judul LIKE '%Artikel%')
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
                <div class="stats-number"><?= isset($jenis_counts['Jurnal']) ? $jenis_counts['Jurnal'] : 0 ?></div>
                <div class="stats-label">Jurnal Nasional</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number">
                    <?php 
                    $skripsi_tesis = (isset($jenis_counts['Skripsi']) ? $jenis_counts['Skripsi'] : 0) + 
                                    (isset($jenis_counts['Tesis']) ? $jenis_counts['Tesis'] : 0);
                    echo $skripsi_tesis;
                    ?>
                </div>
                <div class="stats-label">Skripsi & Tesis</div>
            </div>
        </div>
    </div>
    
    <!-- FILTER JENIS KARYA -->
    <div class="year-filter">
        <h5>Filter Berdasarkan Jenis Karya</h5>
        <div class="year-buttons">
            <button class="karya-filter-btn active" data-jenis="semua">Semua</button>
            <button class="karya-filter-btn" data-jenis="jurnal">Jurnal</button>
            <button class="karya-filter-btn" data-jenis="skripsi">Skripsi</button>
            <button class="karya-filter-btn" data-jenis="tesis">Tesis</button>
            <button class="karya-filter-btn" data-jenis="artikel">Artikel</button>
        </div>
    </div>

    <h3 class="section-title mt-4">Daftar Karya Ilmiah Terbaru</h3>

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
        
        // Tentukan jenis karya
        $judul = strtolower($row['judul']);
        $jenis = strtolower($row['jenis_monografi']);
        $karya_jenis = '';
        $karya_class = '';
        $karya_text = '';
        
        if (strpos($jenis, 'jurnal') !== false || strpos($judul, 'jurnal') !== false) {
            $karya_jenis = 'jurnal';
            $karya_class = 'type-jurnal';
            $karya_text = 'Jurnal Nasional';
        } elseif (strpos($jenis, 'skripsi') !== false || strpos($judul, 'skripsi') !== false) {
            $karya_jenis = 'skripsi';
            $karya_class = 'type-skripsi';
            $karya_text = 'Skripsi';
        } elseif (strpos($jenis, 'tesis') !== false || strpos($judul, 'tesis') !== false) {
            $karya_jenis = 'tesis';
            $karya_class = 'type-tesis';
            $karya_text = 'Tesis';
        } elseif (strpos($jenis, 'artikel') !== false || strpos($judul, 'artikel') !== false) {
            $karya_jenis = 'artikel';
            $karya_class = 'type-artikel';
            $karya_text = 'Artikel Ilmiah';
        } else {
            $karya_jenis = 'lainnya';
            $karya_class = 'type-artikel';
            $karya_text = 'Karya Ilmiah';
        }
        
        // Tentukan icon berdasarkan jenis
        $icon_class = "bi bi-file-earmark-text";
        if ($karya_jenis == 'jurnal') {
            $icon_class = "bi bi-journal";
        } elseif ($karya_jenis == 'skripsi') {
            $icon_class = "bi bi-book";
        } elseif ($karya_jenis == 'tesis') {
            $icon_class = "bi bi-journals";
        }
        
        // URL untuk detail
        $detail = "index.php?page=detail-monografi&id=" . $row['id_monografi'];
        
        // Buat singkatan
        $singkatan = "KRY";
        if ($karya_jenis == 'jurnal') {
            $singkatan = "JRN";
        } elseif ($karya_jenis == 'skripsi') {
            $singkatan = "SKR";
        } elseif ($karya_jenis == 'tesis') {
            $singkatan = "TES";
        } elseif ($karya_jenis == 'artikel') {
            $singkatan = "ART";
        }
    ?>

    <div class="document-card karya-ilmiah-card" data-jenis="<?= $karya_jenis ?>" data-tahun="<?= $row['tahun_terbit'] ?>" data-id="<?= $row['id_monografi']; ?>">
        <div class="karya-badge">KARYA ILMIAH</div>
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
            <div class="card-number"><?= htmlspecialchars($row['jenis_monografi']) ?></div>
            <h4 class="card-title">
                <a href="<?= $detail ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['judul']) ?></a>
            </h4>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="karya-type <?= $karya_class ?>">
                    <i class="<?= $icon_class ?>"></i> <?= $karya_text ?>
                </span>
                <span class="harmonisasi-type">
                    <i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?>
                </span>
            </div>

            <div class="card-meta">
                <div class="card-date">
                    <i class="bi bi-calendar"></i> <?= $tanggal ?>
                </div>
                <div class="card-status"><?= htmlspecialchars($row['tahun_terbit']) ?></div>
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
                <?php if (!empty($row['penerbit'])): ?>
                <span><i class="bi bi-building"></i> <?= htmlspecialchars($row['penerbit']) ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($row['deskripsi'])): ?>
            <div class="abstrak-info">
                <div class="abstrak-title">Abstrak:</div>
                <p><?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 250))) ?><?= strlen($row['deskripsi']) > 250 ? '...' : '' ?></p>
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
        <i class="bi bi-pencil"></i>
        <h4>Tidak Ada Data</h4>
        <p class="text-muted mb-0">
            Tidak ada karya ilmiah yang tersedia di database.
        </p>
        <?php 
        // Debug: Tampilkan jenis monografi yang ada
        $debug_query = "SELECT DISTINCT jenis_monografi FROM monografi WHERE jenis_monografi LIKE '%Karya%' OR jenis_monografi LIKE '%Jurnal%' OR jenis_monografi LIKE '%Skripsi%' OR jenis_monografi LIKE '%Tesis%' OR jenis_monografi LIKE '%Artikel%' LIMIT 10";
        $debug_result = mysqli_query($koneksi, $debug_query);
        if (mysqli_num_rows($debug_result) > 0): ?>
        <div class="alert alert-info mt-3">
            <small>Contoh jenis monografi karya ilmiah yang tersedia:<br>
            <?php while ($debug_row = mysqli_fetch_assoc($debug_result)): ?>
                <?= htmlspecialchars($debug_row['jenis_monografi']) ?><br>
            <?php endwhile; ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- KATEGORI KARYA ILMIAH -->
    <div class="year-filter mt-5">
        <h4 class="section-title mb-4"><i class="bi bi-diagram-3 text-primary me-2"></i>Kategori Karya Ilmiah</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="kategori-box">
                    <div class="mb-3">
                        <i class="bi bi-journal fs-1 text-primary"></i>
                    </div>
                    <h5>Jurnal</h5>
                    <p class="small">Artikel jurnal nasional & internasional</p>
                    <div class="mt-2">
                        <span class="badge bg-primary"><?= isset($jenis_counts['Jurnal']) ? $jenis_counts['Jurnal'] : 0 ?> karya</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="kategori-box">
                    <div class="mb-3">
                        <i class="bi bi-book fs-1 text-primary"></i>
                    </div>
                    <h5>Skripsi</h5>
                    <p class="small">Karya ilmiah strata 1 (S1)</p>
                    <div class="mt-2">
                        <span class="badge bg-primary"><?= isset($jenis_counts['Skripsi']) ? $jenis_counts['Skripsi'] : 0 ?> karya</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="kategori-box">
                    <div class="mb-3">
                        <i class="bi bi-journals fs-1 text-primary"></i>
                    </div>
                    <h5>Tesis</h5>
                    <p class="small">Karya ilmiah strata 2 (S2)</p>
                    <div class="mt-2">
                        <span class="badge bg-primary"><?= isset($jenis_counts['Tesis']) ? $jenis_counts['Tesis'] : 0 ?> karya</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="kategori-box">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                    </div>
                    <h5>Artikel</h5>
                    <p class="small">Makalah konferensi & prosiding</p>
                    <div class="mt-2">
                        <span class="badge bg-primary"><?= isset($jenis_counts['Artikel Ilmiah']) ? $jenis_counts['Artikel Ilmiah'] : 0 ?> karya</span>
                    </div>
                </div>
            </div>
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
mysqli_data_seek($penulis_stats_result, 0);
?>

<div class="sidebar-menu">
<h4><i class="bi bi-pencil"></i> Menu Monografi Hukum</h4>
<ul>
<li><a href="pokok-pikiran-dprd.php"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
<li><a href="kajian-raperda.php"><i class="bi bi-search"></i> Kajian Raperda</a></li>
<li><a href="naskah-akademik.php"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
<li><a href="hasil-fasilitasi.php"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
<li><a href="risalah.php"><i class="bi bi-chat-text"></i> Risalah</a></li>
<li><a href="hasil-harmonisasi.php"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
<li class="active"><a href="#"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
<li><a href="himpunan-peraturan.php"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
</ul>
</div>

<!-- STATISTIK KARYA -->
<div class="sidebar-menu">
    <h4><i class="bi bi-bar-chart"></i> Statistik Karya Ilmiah</h4>
    <ul>
        <li class="d-flex justify-content-between">
            <span>Total Karya Ilmiah</span>
            <span class="badge bg-primary rounded-pill"><?= $total_dokumen ?></span>
        </li>
        <?php while ($jenis = mysqli_fetch_assoc($jenis_stats_result)): 
            $badge_class = 'bg-primary';
            if ($jenis['jenis_karya'] == 'Jurnal') $badge_class = 'bg-success';
            elseif ($jenis['jenis_karya'] == 'Skripsi') $badge_class = 'bg-info';
            elseif ($jenis['jenis_karya'] == 'Tesis') $badge_class = 'bg-warning';
            elseif ($jenis['jenis_karya'] == 'Artikel Ilmiah') $badge_class = 'bg-secondary';
        ?>
        <li class="d-flex justify-content-between">
            <span><?= htmlspecialchars($jenis['jenis_karya']) ?></span>
            <span class="badge <?= $badge_class ?> rounded-pill"><?= $jenis['jumlah'] ?></span>
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

<!-- PENULIS TERBANYAK -->
<div class="sidebar-menu">
    <h4><i class="bi bi-person"></i> Penulis Terbanyak</h4>
    <div class="list-group">
        <?php while ($penulis = mysqli_fetch_assoc($penulis_stats_result)): ?>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span class="text-truncate" style="max-width: 150px;"><?= htmlspecialchars($penulis['penulis']) ?></span>
            <span class="badge bg-primary rounded-pill"><?= $penulis['jumlah'] ?></span>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<!-- KARYA TERBARU -->
<div class="sidebar-menu">
    <h4><i class="bi bi-clock-history"></i> Karya Terbaru</h4>
    <ul>
    <?php while ($recent = mysqli_fetch_assoc($recent_result)): 
        // Tentukan icon berdasarkan jenis
        $judul_lower = strtolower($recent['judul']);
        $recent_icon = "bi bi-file-earmark-text";
        if (strpos($judul_lower, 'jurnal') !== false) $recent_icon = "bi bi-journal";
        elseif (strpos($judul_lower, 'skripsi') !== false) $recent_icon = "bi bi-book";
        elseif (strpos($judul_lower, 'tesis') !== false) $recent_icon = "bi bi-journals";
    ?>
    <li>
        <a href="index.php?page=detail-monografi&id=<?= $recent['id_monografi'] ?>">
            <i class="<?= $recent_icon ?>"></i> <?= htmlspecialchars(substr($recent['judul'], 0, 40)) ?>...
        </a>
        <small class="text-muted d-block">Oleh: <?= htmlspecialchars($recent['penulis']) ?></small>
        <small class="text-muted">Tahun: <?= htmlspecialchars($recent['tahun_terbit']) ?></small>
    </li>
    <?php endwhile; ?>
    </ul>
</div>

<!-- PANDUAN PENULISAN -->
<div class="sidebar-menu">
    <h4><i class="bi bi-book"></i> Panduan & Template</h4>
    <ul>
        <li><a href="#" onclick="showTemplate()"><i class="bi bi-file-earmark-word"></i> Template Penulisan (DOC)</a></li>
        <li><a href="#" onclick="showPedoman()"><i class="bi bi-file-earmark-pdf"></i> Pedoman Sitasi (PDF)</a></li>
        <li><a href="#" onclick="showMetodologi()"><i class="bi bi-file-earmark-ppt"></i> Presentasi Metodologi (PPT)</a></li>
        <li><a href="#" onclick="showContoh()"><i class="bi bi-file-earmark-zip"></i> Contoh Karya Lengkap (ZIP)</a></li>
    </ul>
</div>

</div>
</div>
</div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// FILTER BERDASARKAN JENIS KARYA
// ============================================
document.querySelectorAll(".karya-filter-btn").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".karya-filter-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedJenis = btn.dataset.jenis;
        
        filterDocuments(selectedJenis, currentYear);
    });
});

// ============================================
// FILTER BERDASARKAN TAHUN (SIDEBAR)
// ============================================
let currentYear = "semua";
let currentJenis = "semua";

document.querySelectorAll(".year-filter-sidebar").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        currentYear = btn.dataset.year;
        
        filterDocuments(currentJenis, currentYear);
    });
});

// ============================================
// FUNGSI FILTER DOKUMEN
// ============================================
function filterDocuments(jenis, year) {
    const cards = document.querySelectorAll('.karya-ilmiah-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardJenis = card.dataset.jenis;
        const cardYear = card.dataset.tahun;
        
        const jenisMatch = jenis === "semua" || cardJenis === jenis;
        const yearMatch = year === "semua" || cardYear === year;
        
        if (jenisMatch && yearMatch) {
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
                    Tidak ada karya ilmiah yang sesuai dengan filter yang dipilih.
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
    currentJenis = "semua";
    currentYear = "semua";
    
    document.querySelectorAll(".karya-filter-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(".karya-filter-btn[data-jenis='semua']").classList.add("active");
    
    document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
    document.querySelector(".year-filter-sidebar[data-year='semua']").classList.add("active");
    
    const cards = document.querySelectorAll('.karya-ilmiah-card');
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
    
    const cards = document.querySelectorAll('.karya-ilmiah-card');
    let foundCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title a').textContent.toLowerCase();
        const desc = card.querySelector('.abstrak-info p')?.textContent.toLowerCase() || '';
        const penulis = card.querySelector('.harmonisasi-type')?.textContent.toLowerCase() || '';
        
        if (title.includes(keyword) || desc.includes(keyword) || penulis.includes(keyword)) {
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
                    Tidak ada karya ilmiah yang sesuai dengan kata kunci "${keyword}".
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
    const elements = card.querySelectorAll('.card-title a, .abstrak-info p');
    
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
            html: `Ditemukan <strong>${count}</strong> karya ilmiah dengan kata kunci "<strong>${keyword}</strong>"`,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        console.log(`Found ${count} academic works for "${keyword}"`);
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
            html: `File PDF untuk karya ilmiah <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk karya ilmiah "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
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
// PANDUAN PENULISAN FUNCTIONS
// ============================================
function showTemplate() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Template Penulisan',
            html: `
                <div class="text-start">
                    <h6>Template yang tersedia:</h6>
                    <ul>
                        <li><strong>Template Jurnal:</strong> Format IMRaD dengan style APA 7th</li>
                        <li><strong>Template Skripsi:</strong> Format standar perguruan tinggi</li>
                        <li><strong>Template Tesis:</strong> Format penelitian kualitatif/kuantitatif</li>
                        <li><strong>Template Artikel:</strong> Format konferensi nasional/internasional</li>
                    </ul>
                    <hr>
                    <p class="mb-2">Silakan download template yang sesuai:</p>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="window.open('templates/jurnal_template.docx', '_blank')">
                            <i class="bi bi-download"></i> Download Template
                        </button>
                    </div>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 600
        });
    }
}

function showPedoman() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Pedoman Sitasi dan Referensi',
            html: `
                <div class="text-start">
                    <h6>Pedoman yang tersedia:</h6>
                    <ol>
                        <li>Pedoman Sitasi APA Style 7th Edition</li>
                        <li>Pedoman Sitasi Chicago Style 17th Edition</li>
                        <li>Pedoman Sitasi Harvard Style</li>
                        <li>Pedoman Manajemen Referensi dengan Mendeley</li>
                        <li>Pedoman Manajemen Referensi dengan Zotero</li>
                    </ol>
                    <hr>
                    <p class="mb-2">Download pedoman sitasi:</p>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="window.open('pedoman/sitasi_apa7.pdf', '_blank')">
                            <i class="bi bi-download"></i> Download Pedoman
                        </button>
                    </div>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 600
        });
    }
}

function showMetodologi() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Presentasi Metodologi Penelitian',
            html: `
                <div class="text-start">
                    <h6>Materi metodologi penelitian:</h6>
                    <ul>
                        <li>Metodologi Penelitian Hukum Normatif</li>
                        <li>Metodologi Penelitian Hukum Empiris</li>
                        <li>Teknik Pengumpulan Data dan Analisis</li>
                        <li>Etika Penelitian dan Plagiarisme</li>
                        <li>Teknik Penulisan dan Penyuntingan</li>
                    </ul>
                    <hr>
                    <p class="mb-2">Download materi presentasi:</p>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="window.open('materi/metodologi_penelitian.ppt', '_blank')">
                            <i class="bi bi-download"></i> Download Materi
                        </button>
                    </div>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: 600
        });
    }
}

function showContoh() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Contoh Karya Ilmiah Lengkap',
            html: `
                <div class="text-start">
                    <h6>Contoh karya ilmiah lengkap:</h6>
                    <ul>
                        <li>Contoh Jurnal Hukum Terpublikasi (PDF)</li>
                        <li>Contoh Skripsi Hukum Berpredikat Cumlaude</li>
                        <li>Contoh Tesis Hukum dengan Metode Campuran</li>
                        <li>Contoh Artikel Konferensi Internasional</li>
                        <li>Contoh Proposal Penelitian yang Didanai</li>
                    </ul>
                    <hr>
                    <p class="mb-2">Download contoh karya ilmiah:</p>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="window.open('contoh/contoh_karya_ilmiah.zip', '_blank')">
                            <i class="bi bi-download"></i> Download Contoh
                        </button>
                    </div>
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
            const card = this.closest('.karya-ilmiah-card');
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
// KARYA ILMIAH BADGE CLICK
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.karya-badge').forEach(badge => {
        badge.addEventListener('click', function(e) {
            e.stopPropagation();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Karya Ilmiah',
                    html: `
                        <div class="text-start">
                            <p>Karya ilmiah hasil penelitian tentang hukum dan legislasi di Kota Surakarta.</p>
                            <p><strong>Tujuan Publikasi:</strong></p>
                            <ul>
                                <li>Mengembangkan ilmu pengetahuan hukum</li>
                                <li>Memberikan kontribusi pada pembuatan kebijakan</li>
                                <li>Mendorong penelitian ilmiah di bidang hukum</li>
                                <li>Meningkatkan kualitas produk legislasi</li>
                                <li>Sebagai referensi akademik dan praktisi</li>
                            </ul>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Mengerti'
                });
            } else {
                alert('Karya Ilmiah: Hasil penelitian tentang hukum dan legislasi yang berkontribusi pada pengembangan ilmu pengetahuan dan kebijakan publik.');
            }
        });
    });
});
</script>

<?php 
mysqli_close($koneksi); 
?>