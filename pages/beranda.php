<?php
// beranda.php
$pageTitle = 'Beranda - JDIH DPRD Kota Surakarta';
include 'header.php';

// Include file koneksi database
include 'a_db.php';

// Query untuk statistik
// Total Dokumen (tanpa kondisi display karena kolom tidak ada)
$query_total = "SELECT COUNT(*) as total FROM uploads";
$result_total = $koneksi->query($query_total);
$row_total = $result_total->fetch_assoc();
$total_dokumen = $row_total['total'];

// Dokumen Tahun Ini (berdasarkan tahun_pengundangan)
$current_year = date('Y');
$query_tahun_ini = "SELECT COUNT(*) as total FROM uploads WHERE tahun_pengundangan = '$current_year'";
$result_tahun_ini = $koneksi->query($query_tahun_ini);
$row_tahun_ini = $result_tahun_ini->fetch_assoc();
$dokumen_tahun_ini = $row_tahun_ini['total'];

// Dokumen Bulan Ini (berdasarkan uploaded_at)
$current_month = date('Y-m');
$query_bulan_ini = "SELECT COUNT(*) as total FROM uploads WHERE DATE_FORMAT(uploaded_at, '%Y-%m') = '$current_month'";
$result_bulan_ini = $koneksi->query($query_bulan_ini);
$row_bulan_ini = $result_bulan_ini->fetch_assoc();
$dokumen_bulan_ini = $row_bulan_ini['total'];

// Distribusi Jenis Dokumen berdasarkan data di tabel uploads
$query_jenis = "SELECT jenis, COUNT(*) as jumlah FROM uploads WHERE jenis IS NOT NULL AND jenis != '' GROUP BY jenis ORDER BY jumlah DESC";
$result_jenis = $koneksi->query($query_jenis);

$jenis_stats = [];
$jenis_labels = [];
$jenis_data = [];
$jenis_total = 0;

// Kategori dari gambar yang perlu di-mapping
$kategori_mapping = [
    'Keputusan_DPRD' => 'Keputusan DPRD',
    'Keputusan_Sekretaris_DPRD' => 'Keputusan Sekretaris DPRD',
    'Keputusan_Pimpinan_DPRD' => 'Keputusan Pimpinan DPRD',
    'Peraturan_DPRD' => 'Peraturan DPRD',
    'Peraturan_Daerah' => 'Peraturan Daerah'
];

// Kategori lainnya akan digabung sebagai "Lainnya"
$kategori_lainnya = [];

if ($result_jenis && $result_jenis->num_rows > 0) {
    while ($row = $result_jenis->fetch_assoc()) {
        $jenis_asli = $row['jenis'];
        $jumlah = $row['jumlah'];
        $jenis_total += $jumlah;
        
        // Mapping jenis berdasarkan data di database
        if (isset($kategori_mapping[$jenis_asli])) {
            $jenis_label = $kategori_mapping[$jenis_asli];
            if (!isset($jenis_stats[$jenis_label])) {
                $jenis_stats[$jenis_label] = 0;
            }
            $jenis_stats[$jenis_label] += $jumlah;
        } else {
            // Untuk jenis yang tidak ada di mapping, masukkan ke "Lainnya"
            $kategori_lainnya[] = ['nama' => $jenis_asli, 'jumlah' => $jumlah];
        }
    }
    
    // Tambahkan "Lainnya" jika ada
    if (!empty($kategori_lainnya)) {
        $total_lainnya = 0;
        foreach ($kategori_lainnya as $lainnya) {
            $total_lainnya += $lainnya['jumlah'];
        }
        $jenis_stats['Lainnya'] = $total_lainnya;
    }
    
    // Tambahkan English Legal Products (jika tidak ada di data, set 0)
    if (!isset($jenis_stats['English Legal Products'])) {
        $jenis_stats['English Legal Products'] = 0;
    }
    
    // Konversi ke array untuk chart
    foreach ($jenis_stats as $label => $jumlah) {
        $jenis_labels[] = $label;
        $jenis_data[] = $jumlah;
    }
}

// Tren 10 Tahun Terakhir berdasarkan tahun_pengundangan
$start_year = $current_year - 9;
$trend_labels = [];
$trend_data = [];

// Query untuk mendapatkan data per tahun
$query_trend = "SELECT tahun_pengundangan, COUNT(*) as jumlah 
                FROM uploads 
                WHERE tahun_pengundangan REGEXP '^[0-9]{4}$'
                GROUP BY tahun_pengundangan 
                HAVING tahun_pengundangan BETWEEN $start_year AND $current_year
                ORDER BY tahun_pengundangan";
$result_trend = $koneksi->query($query_trend);

$trend_data_db = [];
if ($result_trend && $result_trend->num_rows > 0) {
    while ($row = $result_trend->fetch_assoc()) {
        $trend_data_db[$row['tahun_pengundangan']] = $row['jumlah'];
    }
}

// Isi data untuk semua tahun (termasuk yang kosong)
for ($year = $start_year; $year <= $current_year; $year++) {
    $trend_labels[] = $year;
    $trend_data[] = isset($trend_data_db[$year]) ? $trend_data_db[$year] : 0;
}

// Status Dokumen
$query_status = "SELECT status, COUNT(*) as jumlah FROM uploads WHERE status IS NOT NULL AND status != '' GROUP BY status ORDER BY jumlah DESC";
$result_status = $koneksi->query($query_status);
$status_data = [];
if ($result_status && $result_status->num_rows > 0) {
    while ($row = $result_status->fetch_assoc()) {
        $status_data[] = $row;
    }
}

// Bidang Hukum
$query_bidang = "SELECT bidangHukum, COUNT(*) as jumlah FROM uploads WHERE bidangHukum IS NOT NULL AND bidangHukum != '' GROUP BY bidangHukum ORDER BY jumlah DESC LIMIT 5";
$result_bidang = $koneksi->query($query_bidang);
$bidang_data = [];
if ($result_bidang && $result_bidang->num_rows > 0) {
    while ($row = $result_bidang->fetch_assoc()) {
        $bidang_data[] = $row;
    }
}

// Tutup koneksi
// $koneksi->close();
?>
<style>
<?php include "beranda.css"; ?>

/* Statistics Section */
.stats-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #5b5fdc, #2ecc71);
}

.stats-title h2 {
    color: #2c3e50;
    font-weight: 700;
    margin-top: 10px;
}

.stats-title small {
    color: #5b5fdc;
    font-weight: 600;
    letter-spacing: 2px;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
    height: 100%;
    min-height: 350px;
    display: flex;
    flex-direction: column;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.stats-card h5 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.stats-card small {
    color: #666;
    font-size: 0.85rem;
    display: block;
    margin-bottom: 15px;
}

.stats-card .chart-container {
    flex: 1;
    position: relative;
    min-height: 250px;
}

.stats-card-small {
    background: white;
    border-radius: 12px;
    padding: 25px 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border-top: 4px solid #5b5fdc;
}

.stats-card-small:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-top-color: #2ecc71;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #5b5fdc;
    margin-bottom: 8px;
    line-height: 1;
}

.stats-label {
    color: #666;
    font-size: 0.9rem;
    font-weight: 500;
}

.btn-stats {
    background: linear-gradient(135deg, #5b5fdc 0%, #7f9cf5 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-stats:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(91, 95, 220, 0.3);
    color: white;
}

.stats-grid {
    margin-bottom: 30px;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        padding: 20px;
        min-height: 300px;
    }
    
    .stats-number {
        font-size: 2rem;
    }
    
    .stats-section {
        padding: 60px 0;
    }
    
    .stats-card .chart-container {
        min-height: 200px;
    }
}

/* Animation */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card-small {
    animation: countUp 0.6s ease-out;
}

/* Status and Field Lists */
.status-list, .bidang-list {
    margin-top: 15px;
}

.status-item, .bidang-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.status-item:hover, .bidang-item:hover {
    background-color: #f9f9f9;
    border-radius: 5px;
    padding-left: 10px;
    padding-right: 10px;
}

.status-item:last-child, .bidang-item:last-child {
    border-bottom: none;
}

.status-name, .bidang-name {
    flex: 1;
    color: #555;
    font-size: 0.9rem;
}

.status-count, .bidang-count {
    color: #5b5fdc;
    font-weight: 600;
    min-width: 80px;
    text-align: right;
    font-size: 0.9rem;
}

.bidang-rank {
    background: #5b5fdc;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.8rem;
    font-weight: bold;
}

.bidang-name {
    display: flex;
    align-items: center;
}

/* No Data State */
.text-center.py-3 {
    padding: 20px 0;
    color: #999;
    font-style: italic;
}
</style>

<!-- Hero Section with Video Background -->
<section class="hero-section">
    <!-- Video Background -->
    <div class="video-background">
        <div id="youtube-player"></div>
        <div class="video-overlay"></div>
    </div>
    
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="hero-title animate-fade-in">Selamat Datang di JDIH<br>Sekretariat DPRD Surakarta</h1>
                <p class="hero-subtitle animate-fade-in-delay">
                    Pusat Dokumentasi dan Informasi Hukum yang menyediakan akses mudah<br>
                    terhadap seluruh produk hukum DPRD Kota Surakarta
                </p>

                <!-- Search Box -->
                <div class="search-box-wrapper mt-4 animate-slide-up">
                    <div class="search-box-container">
                        <div class="search-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" class="search-input" placeholder="Cari produk hukum, peraturan, atau informasi...">
                        <button class="search-btn">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                    <div class="search-suggestions">
                        <span class="suggestion-label">Pencarian Populer:</span>
                        <span class="suggestion-tag">Perda</span>
                        <span class="suggestion-tag">Keputusan DPRD</span>
                        <span class="suggestion-tag">Peraturan</span>
                        <span class="suggestion-tag">Artikel Hukum</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="main-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-card">
                    <h2 class="section-title">Kata Pengantar</h2>
                    <div class="welcome-text">
                        <p>
                            Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                            hadir sebagai sarana penyebarluasan produk hukum secara online, sebagai media 
                            publikasi, serta menyediakan akses yang mudah dan cepat untuk informasi hukum. 
                            Dengan demikian, diharapkan jajaran sekretariat Dewan Perwakilan Rakyat Daerah 
                            Kota Surakarta, masyarakat kota Surakarta, dan semua pihak yang memerlukan 
                            dapat mengakses dan mendapatkan informasi hukum yang tepat dan akurat.
                        </p>
                        <div class="quote-box">
                            <i class="bi bi-quote quote-icon"></i>
                            <p class="quote-text">JDIH membawa hukum langsung dalam genggaman Anda</p>
                        </div>
                        
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-name">KINKIN SULTANIL HAKIM, SH, MM</p>
                            <p class="signature-title">Sekretaris DPRD Kota Surakarta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="container">
                <div class="text-center mb-5 stats-title">
                    <small>★ STATISTIK & DATA ★</small>
                    <h2>Statistik Produk Hukum</h2>
                    <p class="text-muted">Data real-time dari database JDIH DPRD Surakarta</p>
                </div>

                <!-- Quick Stats -->
                <div class="row g-4 mb-5 stats-grid">
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($total_dokumen); ?></div>
                            <div class="stats-label">Total Dokumen</div>
                            <small>Semua produk hukum yang tersedia</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($dokumen_tahun_ini); ?></div>
                            <div class="stats-label">Dokumen Tahun <?php echo $current_year; ?></div>
                            <small>Produk hukum tahun berjalan</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($dokumen_bulan_ini); ?></div>
                            <div class="stats-label">Dokumen Bulan Ini</div>
                            <small>Update terbaru sistem JDIH</small>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="stats-card">
                            <h5>Distribusi Jenis Dokumen</h5>
                            <small>Persentase berdasarkan kategori produk hukum</small>
                            <div class="chart-container">
                                <?php if (!empty($jenis_labels)): ?>
                                    <canvas id="pieProdukHukum"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <p class="text-muted">Data distribusi jenis belum tersedia</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="stats-card">
                            <h5>Tren Dokumen Per Tahun</h5>
                            <small>Perkembangan 10 tahun terakhir</small>
                            <div class="chart-container">
                                <?php if (!empty($trend_labels)): ?>
                                    <canvas id="barProdukHukum"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <p class="text-muted">Data tren belum tersedia</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row g-4 mt-4">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <h5>Status Dokumen</h5>
                            <?php if (!empty($status_data)): ?>
                                <div class="status-list">
                                    <?php foreach ($status_data as $status): ?>
                                        <div class="status-item">
                                            <span class="status-name"><?php echo htmlspecialchars($status['status']); ?></span>
                                            <span class="status-count"><?php echo number_format($status['jumlah']); ?> dokumen</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">Data status belum tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="stats-card">
                            <h5>Top 5 Bidang Hukum</h5>
                            <?php if (!empty($bidang_data)): ?>
                                <div class="bidang-list">
                                    <?php $counter = 1; ?>
                                    <?php foreach ($bidang_data as $bidang): ?>
                                        <div class="bidang-item">
                                            <span class="bidang-rank">#<?php echo $counter; ?></span>
                                            <span class="bidang-name"><?php echo htmlspecialchars($bidang['bidangHukum']); ?></span>
                                            <span class="bidang-count"><?php echo number_format($bidang['jumlah']); ?></span>
                                        </div>
                                        <?php $counter++; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">Data bidang hukum belum tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="index.php?page=statistik" class="btn btn-stats">
                        <i class="bi bi-graph-up-arrow me-2"></i>Lihat Statistik Lengkap
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Quick Links -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Akses Cepat</h2>
                <p class="section-subtitle">Jelajahi berbagai kategori informasi hukum</p>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-file-text card-icon"></i>
                    </div>
                    <h5>Keputusan DPRD</h5>
                    <p>Akses seluruh keputusan yang telah ditetapkan oleh DPRD Kota Surakarta</p>
                    <a href="?page=keputusan-dprd" class="btn-custom">
                        Lihat Keputusan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-journal-text card-icon"></i>
                    </div>
                    <h5>Peraturan DPRD</h5>
                    <p>Kumpulan peraturan yang berlaku di lingkungan DPRD Kota Surakarta</p>
                    <a href="?page=peraturan-dprd" class="btn-custom">
                        Lihat Peraturan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-info-circle card-icon"></i>
                    </div>
                    <h5>Informasi Hukum</h5>
                    <p>Sumber informasi hukum terkini dan terpercaya untuk masyarakat</p>
                    <a href="?page=informasi" class="btn-custom">
                        Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Latest News -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                <p class="section-subtitle">Update terkini seputar kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Pelatihan JDIH" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Pelatihan</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>15 Desember 2024</span>
                        <h5 class="news-title">Pelatihan Pengelolaan JDIH untuk Staf Sekretariat</h5>
                        <p class="news-excerpt">Pelatihan intensif untuk meningkatkan kompetensi pengelola JDIH dalam mengelola informasi hukum.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Sosialisasi Perda" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Sosialisasi</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>10 Desember 2024</span>
                        <h5 class="news-title">Sosialisasi Peraturan Daerah Terbaru</h5>
                        <p class="news-excerpt">Kegiatan sosialisasi peraturan daerah terbaru kepada masyarakat Kota Surakarta.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Penghargaan JDIH" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Penghargaan</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>5 Desember 2024</span>
                        <h5 class="news-title">JDIH DPRD Surakarta Raih Penghargaan Nasional</h5>
                        <p class="news-excerpt">JDIH DPRD Kota Surakarta menerima penghargaan sebagai sistem dokumentasi hukum terbaik tingkat nasional.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instagram Section -->
        <div class="row">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Aktivitas Terbaru di Instagram</h2>
                <p class="section-subtitle">Ikuti perkembangan terbaru kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <div class="col-12">
                <div class="instagram-section">
                    <div class="row justify-content-center g-4">
                        <!-- Instagram Post 1 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DS69KHrj-1o/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 2 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DTPydpoEulZ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 3 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DRorvdjj6EE/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instagram Follow Button -->
                    <div class="text-center mt-4">
                        <a href="https://www.instagram.com/setwansurakarta/" target="_blank" class="btn-instagram">
                            <i class="bi bi-instagram me-2"></i> Ikuti @setwansurakarta di Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- YouTube API Script -->
<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            videoId: 'Yx_5XjMMy9k',
            playerVars: {
                autoplay: 1,
                controls: 0,
                showinfo: 0,
                modestbranding: 1,
                loop: 1,
                playlist: 'Yx_5XjMMy9k',
                fs: 0,
                cc_load_policy: 0,
                iv_load_policy: 3,
                autohide: 1,
                mute: 1
            },
            events: {
                onReady: onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        event.target.mute();
        event.target.playVideo();
    }
</script>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari PHP
    const pieLabels = <?php echo !empty($jenis_labels) ? json_encode($jenis_labels) : '[]'; ?>;
    const pieData = <?php echo !empty($jenis_data) ? json_encode($jenis_data) : '[]'; ?>;
    const barLabels = <?php echo !empty($trend_labels) ? json_encode($trend_labels) : '[]'; ?>;
    const barData = <?php echo !empty($trend_data) ? json_encode($trend_data) : '[]'; ?>;

    // Hitung total untuk persentase
    const pieTotal = pieData.reduce((a, b) => a + b, 0);

    /* PIE CHART */
    const pieCtx = document.getElementById('pieProdukHukum');
    if (pieCtx && pieLabels.length > 0 && pieTotal > 0) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: [
                        '#5b5fdc', '#ff7a45', '#2ecc71', 
                        '#7f9cf5', '#a855f7', '#38bdf8',
                        '#f59e0b', '#ef4444', '#10b981',
                        '#6b7280', '#ec4899', '#8b5cf6'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        const value = data.datasets[0].data[i];
                                        const percentage = pieTotal > 0 ? Math.round((value / pieTotal) * 100) : 0;
                                        
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor,
                                            lineWidth: data.datasets[0].borderWidth,
                                            hidden: isNaN(data.datasets[0].data[i]) || chart.getDatasetMeta(0).data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = pieTotal > 0 ? Math.round((value / pieTotal) * 100) : 0;
                                return `${label}: ${value} dokumen (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    /* BAR CHART */
    const barCtx = document.getElementById('barProdukHukum');
    if (barCtx && barLabels.length > 0) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: barData,
                    backgroundColor: '#5b5fdc',
                    borderColor: '#4a4ec9',
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: '#2ecc71'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Dokumen',
                            font: {
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: barData.length > 0 ? Math.ceil(Math.max(...barData) / 10) : 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Dokumen: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Animasi counter untuk statistik
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 20);
    }

    // Animate counters on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statsElements = document.querySelectorAll('.stats-number');
                statsElements.forEach(el => {
                    const target = parseInt(el.textContent.replace(/,/g, ''));
                    if (!isNaN(target)) {
                        animateCounter(el, target);
                    }
                });
                observer.disconnect();
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.stats-grid');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>

<!-- Instagram Embed Script -->
<script async src="//www.instagram.com/embed.js"></script>

<?php include 'footer.php'; ?>