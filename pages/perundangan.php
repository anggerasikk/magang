<?php
// perundangan.php - Controller untuk semua jenis peraturan
session_start();
include_once "a_db.php";

// Dapatkan jenis peraturan dari parameter URL
$jenis_peraturan = isset($_GET['jenis']) ? $_GET['jenis'] : 'keputusan-dprd';

// Mapping parameter URL ke nilai database
$jenis_mapping = [
    'keputusan-dprd' => 'Keputusan_DPRD',
    'keputusan-sekretaris' => 'Keputusan_Sekretaris_DPRD',
    'keputusan-pimpinan' => 'Keputusan_Pimpinan_DPRD',
    'peraturan-dprd' => 'Peraturan_DPRD',
    'peraturan-daerah' => 'Peraturan_Daerah',
    'english-legal' => 'English_Legal_Product'
];

// Default ke Keputusan DPRD jika tidak valid
$jenis_database = isset($jenis_mapping[$jenis_peraturan]) ? 
    $jenis_mapping[$jenis_peraturan] : 'Keputusan_DPRD';

// Mapping untuk informasi halaman
$info_halaman = [
    'keputusan-dprd' => [
        'title' => 'Keputusan DPRD',
        'subtitle' => 'Keputusan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'description' => 'Kumpulan Keputusan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'icon' => 'bi-file-earmark-medical',
        'info_title' => 'Definisi Keputusan DPRD',
        'info_content' => 'Keputusan DPRD adalah keputusan yang ditetapkan oleh DPRD dalam rangka pelaksanaan tugas dan wewenangnya berdasarkan peraturan perundang-undangan.',
        'info_title2' => 'Proses Pengambilan',
        'info_content2' => 'Keputusan DPRD diambil melalui rapat paripurna yang dihadiri oleh seluruh anggota DPRD dan disahkan melalui mekanisme voting atau musyawarah mufakat.',
        'breadcrumb' => 'Keputusan DPRD',
        'url_detail' => 'detail-peraturan'
    ],
    'keputusan-sekretaris' => [
        'title' => 'Keputusan Sekretaris DPRD',
        'subtitle' => 'Keputusan Sekretaris Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'description' => 'Kumpulan Keputusan Sekretaris Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'icon' => 'bi-file-earmark-text',
        'info_title' => 'Definisi Keputusan Sekretaris',
        'info_content' => 'Keputusan Sekretaris DPRD adalah keputusan yang ditetapkan oleh Sekretaris DPRD dalam rangka penyelenggaraan administrasi, tata usaha, dan pelayanan di lingkungan Sekretariat DPRD.',
        'info_title2' => 'Ruang Lingkup',
        'info_content2' => 'Meliputi pengaturan administrasi kepegawaian, pengelolaan aset, tata laksana pelayanan publik, dan penyelenggaraan tugas teknis administrasi di lingkungan Sekretariat DPRD.',
        'breadcrumb' => 'Keputusan Sekretaris DPRD',
        'url_detail' => 'detail-peraturan'
    ],
    'keputusan-pimpinan' => [
        'title' => 'Keputusan Pimpinan DPRD',
        'subtitle' => 'Keputusan Pimpinan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'description' => 'Kumpulan Keputusan Pimpinan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'icon' => 'bi-file-earmark-ruled',
        'info_title' => 'Definisi Keputusan Pimpinan',
        'info_content' => 'Keputusan Pimpinan DPRD adalah keputusan yang ditetapkan oleh Pimpinan DPRD dalam rangka penyelenggaraan tugas pimpinan dan pengambilan keputusan strategis.',
        'info_title2' => 'Fungsi',
        'info_content2' => 'Berfungsi sebagai panduan operasional dan pengambilan keputusan internal di lingkungan pimpinan DPRD.',
        'breadcrumb' => 'Keputusan Pimpinan DPRD',
        'url_detail' => 'detail-peraturan'
    ],
    'peraturan-dprd' => [
        'title' => 'Peraturan DPRD',
        'subtitle' => 'Peraturan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'description' => 'Kumpulan Peraturan Dewan Perwakilan Rakyat Daerah Kota Surakarta',
        'icon' => 'bi-journal-text',
        'info_title' => 'Definisi Peraturan DPRD',
        'info_content' => 'Peraturan DPRD adalah peraturan yang ditetapkan oleh DPRD untuk mengatur penyelenggaraan pemerintahan daerah dan pelaksanaan tugas DPRD.',
        'info_title2' => 'Bentuk dan Kedudukan',
        'info_content2' => 'Merupakan produk hukum yang mengikat secara internal di lingkungan DPRD dan memiliki kekuatan hukum tertentu.',
        'breadcrumb' => 'Peraturan DPRD',
        'url_detail' => 'detail-peraturan'
    ],
    'peraturan-daerah' => [
        'title' => 'Peraturan Daerah',
        'subtitle' => 'Peraturan Daerah Kota Surakarta',
        'description' => 'Kumpulan Peraturan Daerah Kota Surakarta',
        'icon' => 'bi-journal-bookmark',
        'info_title' => 'Definisi Peraturan Daerah',
        'info_content' => 'Peraturan Daerah adalah peraturan perundang-undangan yang dibentuk oleh DPRD dengan persetujuan bersama Kepala Daerah.',
        'info_title2' => 'Kedudukan Hukum',
        'info_content2' => 'Merupakan peraturan daerah yang berlaku di wilayah Kota Surakarta dan memiliki kekuatan hukum mengikat.',
        'breadcrumb' => 'Peraturan Daerah',
        'url_detail' => 'detail-peraturan'
    ],
    'english-legal' => [
        'title' => 'English Legal Product',
        'subtitle' => 'English Legal Product DPRD Kota Surakarta',
        'description' => 'Kumpulan Produk Hukum Bahasa Inggris DPRD Kota Surakarta',
        'icon' => 'bi-translate',
        'info_title' => 'Definisi English Legal Product',
        'info_content' => 'Produk hukum yang diterjemahkan ke dalam bahasa Inggris untuk keperluan internasional dan publikasi global.',
        'info_title2' => 'Tujuan',
        'info_content2' => 'Mempermudah akses informasi hukum bagi masyarakat internasional dan meningkatkan transparansi pemerintahan.',
        'breadcrumb' => 'English Legal Product',
        'url_detail' => 'detail-peraturan'
    ]
];

// Ambil data untuk halaman yang aktif
$halaman_data = isset($info_halaman[$jenis_peraturan]) ? 
    $info_halaman[$jenis_peraturan] : $info_halaman['keputusan-dprd'];

// Query data dari database berdasarkan jenis
$query = "SELECT * FROM uploads WHERE jenis = '$jenis_database' ORDER BY idData DESC";
$result = mysqli_query($koneksi, $query);

// Hitung total dokumen
$total_query = "SELECT COUNT(*) as total FROM uploads WHERE jenis = '$jenis_database'";
$total_result = mysqli_query($koneksi, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_dokumen = $total_data['total'];

// Query untuk statistik per tahun
$year_stats_query = "
    SELECT tahun_pengundangan, COUNT(*) as jumlah 
    FROM uploads 
    WHERE jenis = '$jenis_database'
    GROUP BY tahun_pengundangan 
    ORDER BY tahun_pengundangan DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// Query dokumen terbaru
$recent_query = "
    SELECT * FROM uploads 
    WHERE jenis = '$jenis_database'
    ORDER BY tanggal_pengundangan DESC 
    LIMIT 5";
$recent_result = mysqli_query($koneksi, $recent_query);

// Ambil tahun-tahun unik untuk filter
$years_query = "
    SELECT DISTINCT tahun_pengundangan 
    FROM uploads 
    WHERE jenis = '$jenis_database'
    ORDER BY tahun_pengundangan DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($year_row = mysqli_fetch_assoc($years_result)) {
    $years[] = $year_row['tahun_pengundangan'];
}

// Set variabel untuk header
$page_title = $halaman_data['title'] . " - JDIH DPRD Kota Surakarta";
$page_description = $halaman_data['description'];

// Include header
include "header.php";
?>

<style>
    /* OVERRIDE UNTUK HALAMAN INI SAJA */
    body {
        padding-top: 80px;
        background: #f8f9fa;
    }
    
    /* MAIN CONTENT */
    .main-content {
        padding: 50px 0 60px 0;
        background: #f8f9fa;
    }
    
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 3px;
        background: linear-gradient(to right, #13547a, #80d0c7);
    }

    /* CARD STYLES */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .document-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .card-image {
        height: 200px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #13547a, #80d0c7);
    }
    
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .document-card:hover .card-image img {
        transform: scale(1.05);
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
        display: flex;
        align-items: flex-end;
        padding: 20px;
    }
    
    .image-badge {
        background: rgba(255, 255, 255, 0.9);
        color: #13547a;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .card-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .card-number {
        color: #13547a;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 10px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .card-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 1.05rem;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
        display: -webkit-box;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }
    
    .card-date {
        color: #718096;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    
    .card-date i {
        margin-right: 5px;
    }
    
    .card-status {
        background: #e6fffa;
        color: #234e52;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .card-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .card-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-view {
        background: #13547a;
        color: white;
        border: none;
    }
    
    .btn-view:hover {
        background: #0d3c5e;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-download {
        background: #f0f0f0;
        color: #13547a;
        border: 1px solid #ddd;
    }
    
    .btn-download:hover {
        background: #13547a;
        color: white;
        transform: translateY(-2px);
    }

    /* YEAR FILTER */
    .year-filter {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .year-filter h5 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .year-filter h5 i {
        color: #80d0c7;
        margin-right: 10px;
    }
    
    .year-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .year-btn {
        padding: 8px 20px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        color: #13547a;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .year-btn:hover, .year-btn.active {
        background: #13547a;
        color: white;
        border-color: #13547a;
        transform: translateY(-2px);
    }

    /* SIDEBAR MENU */
    .sidebar-menu {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .sidebar-menu h4 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
    }
    
    .sidebar-menu h4 i {
        color: #80d0c7;
        margin-right: 10px;
    }
    
    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-menu ul li {
        margin-bottom: 12px;
    }
    
    .sidebar-menu ul li:last-child {
        margin-bottom: 0;
    }
    
    .sidebar-menu ul li a {
        color: #4a5568;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .sidebar-menu ul li.active a {
        background: #f0f8ff;
        color: #13547a;
        font-weight: 600;
    }
    
    .sidebar-menu ul li a:hover {
        background: #f0f8ff;
        color: #13547a;
        transform: translateX(5px);
    }
    
    .sidebar-menu ul li a i {
        color: #80d0c7;
        margin-right: 10px;
        font-size: 0.9rem;
    }

    /* PAGINATION */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
    }
    
    .page-item .page-link {
        border: none;
        background: white;
        color: #13547a;
        border-radius: 8px;
        padding: 10px 18px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .page-item.active .page-link {
        background: #13547a;
        color: white;
        box-shadow: 0 3px 10px rgba(19, 84, 122, 0.3);
    }
    
    .page-item .page-link:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* NO DATA */
    .no-data {
        background: white;
        border-radius: 12px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .no-data-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .card-container {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }
    
    @media (max-width: 992px) {
        .card-container {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        body {
            padding-top: 70px;
        }
        
        .page-header {
            padding: 30px 0 20px 0;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .main-content {
            padding: 30px 0 40px 0;
        }
        
        .card-container {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .card-content {
            padding: 20px;
        }
        
        .year-filter,
        .sidebar-menu {
            padding: 20px;
        }
        
        .year-buttons {
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
        
        .card-container {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            flex-direction: column;
        }
        
        .card-btn {
            width: 100%;
        }
    }
</style>

<!-- HEADER SECTION -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white"><?php echo $halaman_data['title']; ?></h1>
                <p class="subtitle"><?php echo $halaman_data['subtitle']; ?></p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $halaman_data['breadcrumb']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <div class="row">
            
            <!-- KONTEN UTAMA -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                
                <!-- FILTER TAHUN -->
                <div class="year-filter">
                    <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h5>
                    <div class="year-buttons">
                        <button type="button" class="year-btn active" data-year="semua">Semua</button>

                        <?php foreach($years as $year): ?>
                            <button 
                                type="button" 
                                class="year-btn" 
                                data-year="<?php echo $year; ?>">
                                <?php echo $year; ?>
                            </button>
                        <?php endforeach; ?>

                    </div>
                </div>
                
                <!-- DAFTAR PERATURAN DALAM BENTUK CARD -->
                <div class="document-container">
                    <h3 class="section-title">Daftar <?php echo $halaman_data['title']; ?> Kota Surakarta</h3>
                    
                    <?php if(mysqli_num_rows($result) > 0): ?>

                    <div class="card-container">
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)):
                            // Format tanggal
                            $tanggal_penetapan = !empty($row['tanggal_penetapan']) ? 
                                date('d F Y', strtotime($row['tanggal_penetapan'])) : 'Tanggal tidak tersedia';
                            
                            // Tentukan file untuk download
                            $file_path = !empty($row['pdf_path']) ? $row['pdf_path'] : 
                                (!empty($row['fileDownload']) ? $row['fileDownload'] : '');
                            
                            // Tentukan image untuk cover
                            $image_path = !empty($row['image_path']) ? $row['image_path'] : '';
                            
                            // URL untuk detail
                            $detail_url = "index.php?page=" . $halaman_data['url_detail'] . 
                                         "&jenis=" . $jenis_peraturan . 
                                         "&id=" . $row['idData'];

                        ?>
                        <div class="document-card" data-tahun="<?php echo $row['tahun_pengundangan']; ?>">
                            <!-- CARD IMAGE -->
                            <div class="card-image">
                                <?php if(!empty($image_path) && file_exists($image_path)): ?>
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                                <?php else: ?>
                                    <!-- Placeholder jika tidak ada gambar -->
                                    <div style="width:100%; height:100%; background:linear-gradient(135deg, #13547a, #80d0c7); display:flex; align-items:center; justify-content:center;">
                                        <div style="text-align:center; color:white; padding:20px;">
                                            <i class="bi <?php echo $halaman_data['icon']; ?>" style="font-size:3rem; opacity:0.7;"></i>
                                            <p style="margin-top:10px; font-weight:500;"><?php echo htmlspecialchars($row['singkatanJenis'] ?: $halaman_data['breadcrumb']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="image-overlay">
                                    <span class="image-badge"><?php echo htmlspecialchars($row['singkatanJenis'] ?: $halaman_data['breadcrumb']); ?></span>
                                </div>
                            </div>
                            
                            <!-- CARD CONTENT -->
                            <div class="card-content">
                                <div class="card-number">
                                    <?php echo htmlspecialchars($row['noPeraturan']); ?>
                                </div>
                                
                                <h3 class="card-title">
                                    <?php echo htmlspecialchars($row['judul']); ?>
                                </h3>
                                
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i>
                                        <?php echo $tanggal_penetapan; ?>
                                    </div>
                                    <?php if(!empty($row['status'])): ?>
                                    <div class="card-status">
                                        <?php echo $row['status']; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- ACTION BUTTONS -->
                                <div class="card-actions">
                                    <a href="<?php echo $detail_url; ?>" class="card-btn btn-view">
                                        <i class="bi bi-eye"></i>
                                        Lihat Detail
                                    </a>
                                    <?php if(!empty($file_path) && file_exists($file_path)): ?>
                                    <a href="<?php echo $file_path; ?>" download class="card-btn btn-download">
                                        <i class="bi bi-download"></i>
                                        Download
                                    </a>
                                    <?php else: ?>
                                    <button class="card-btn btn-download" disabled title="File tidak tersedia">
                                        <i class="bi bi-exclamation-circle"></i>
                                        Tidak Tersedia
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $no++;
                        endwhile; 
                        ?>
                    </div>
                    
                    <!-- PAGINATION -->
                    <div class="pagination-container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <?php if($total_dokumen > 12): ?>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <?php endif; ?>
                                <?php if($total_dokumen > 24): ?>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <?php endif; ?>
                                <li class="page-item <?php echo ($total_dokumen <= 12) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                    <?php else: ?>
                    <div class="no-data">
                        <div class="no-data-icon">
                            <i class="bi bi-files"></i>
                        </div>
                        <h4 class="mb-3">Tidak Ada Data</h4>
                        <p class="text-muted mb-0">
                            Tidak ada <?php echo strtolower($halaman_data['title']); ?> yang tersedia.
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- INFORMASI -->
                <div class="document-container">
                    <h3 class="section-title">Informasi <?php echo $halaman_data['title']; ?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-info-circle text-primary"></i> <?php echo $halaman_data['info_title']; ?></h5>
                                <p><?php echo $halaman_data['info_content']; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-journal-check text-primary"></i> <?php echo $halaman_data['info_title2']; ?></h5>
                                <p><?php echo $halaman_data['info_content2']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- MENU PERUNDANGAN -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-list"></i> Menu Perundangan</h4>
                    <ul>
                        <li>
                            <a href="../index.php?page=perundangan">
                                <i class="bi bi-grid"></i>
                                Semua Kategori
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'keputusan-dprd' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=keputusan-dprd">
                                <i class="bi bi-file-earmark-medical"></i>
                                Keputusan DPRD
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'keputusan-sekretaris' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=keputusan-sekretaris">
                                <i class="bi bi-file-earmark-text"></i>
                                Keputusan Sekretaris DPRD
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'keputusan-pimpinan' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=keputusan-pimpinan">
                                <i class="bi bi-file-earmark-ruled"></i>
                                Keputusan Pimpinan DPRD
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'peraturan-dprd' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=peraturan-dprd">
                                <i class="bi bi-journal-text"></i>
                                Peraturan DPRD
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'peraturan-daerah' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=peraturan-daerah">
                                <i class="bi bi-journal-bookmark"></i>
                                Peraturan Daerah
                            </a>
                        </li>
                        <li class="<?php echo $jenis_peraturan == 'english-legal' ? 'active' : ''; ?>">
                            <a href="perundangan.php?jenis=english-legal">
                                <i class="bi bi-translate"></i>
                                English Legal Product
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- STATISTIK -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> Statistik <?php echo $halaman_data['breadcrumb']; ?></h4>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Total:</span>
                            <span class="fw-bold text-primary"><?php echo $total_dokumen; ?> dokumen</span>
                        </li>
                        <?php 
                        mysqli_data_seek($year_stats_result, 0);
                        while($stat = mysqli_fetch_assoc($year_stats_result)): 
                        ?>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Tahun <?php echo $stat['tahun_pengundangan']; ?>:</span>
                            <span class="fw-bold"><?php echo $stat['jumlah']; ?></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- DOKUMEN TERBARU -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Dokumen Terbaru</h4>
                    <ul>
                        <?php 
                        // Reset dan ambil data
                        mysqli_data_seek($recent_result, 0);
                        $recent_items = [];
                        while($row = mysqli_fetch_assoc($recent_result)) {
                            $recent_items[] = $row;
                        }
                        
                        // Tampilkan maksimal 5 item
                        $display_count = min(5, count($recent_items));
                        
                        for($i = 0; $i < $display_count; $i++):
                            $pop = $recent_items[$i];
                        ?>
                        <li>
                            <a href="index.php?page=<?php echo $halaman_data['url_detail']; ?>&jenis=<?php echo $jenis_peraturan; ?>&id=<?php echo $pop['idData']; ?>">
                                <i class="bi bi-file-text"></i>
                                <?php echo htmlspecialchars($pop['noPeraturan']); ?>
                                <span class="badge bg-primary ms-2"><?php echo ($i + 1); ?>#</span>
                            </a>
                        </li>
                        <?php 
                        endfor; 
                        
                        if($display_count == 0):
                        ?>
                        <li class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Belum ada dokumen
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include "footer.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".year-btn");
    const cards = document.querySelectorAll(".document-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            const year = this.dataset.year;

            cards.forEach(card => {
                if (year === "semua" || card.dataset.tahun === year) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
});
</script>

<?php 
// Tutup koneksi database
mysqli_close($koneksi);
?>