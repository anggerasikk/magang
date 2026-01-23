<?php
session_start();

// =================== KONEKSI DATABASE ===================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "jdihdprd";

$koneksi = new mysqli($host, $user, $pass, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");

// =================== AMBIL DATA DARI DATABASE ===================
$query = "SELECT * FROM penghargaan ORDER BY tahun_penghargaan DESC, tanggal_diterima DESC";
$result = $koneksi->query($query);

$penghargaan_data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $penghargaan_data[] = $row;
    }
}

// Tutup koneksi setelah mengambil data
$koneksi->close();

// Set page title untuk header
$pageTitle = 'Penghargaan - JDIH DPRD Kota Surakarta';
include 'header.php';

// =================== FUNGSI UNTUK PATH GAMBAR ===================
function getPenghargaanImagePath($url_gambar) {
    // PATH SERVER (UNTUK CEK FILE)
    $server_base = $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/penghargaan/';
    
    // URL WEB (UNTUK IMG SRC)
    $web_base = '/jdihcilacap_pasang/wb_admin/uploads/penghargaan/';

    if (empty($url_gambar)) {
        return null;
    }

    // Jika sudah URL absolut
    if (preg_match('/^https?:\/\//i', $url_gambar)) {
        return $url_gambar;
    }

    // Ekstrak nama file dari path
    $filename = basename($url_gambar);
    
    // Coba beberapa kemungkinan format path
    $possible_filenames = [
        $filename,
        str_replace('\\', '/', basename($url_gambar)),
        basename(str_replace('\\', '/', $url_gambar))
    ];
    
    // Cek file di server
    foreach ($possible_filenames as $test_filename) {
        $server_path = $server_base . $test_filename;
        if (file_exists($server_path)) {
            return $web_base . $test_filename;
        }
    }
    
    // Jika file tidak ditemukan, cek apakah path sudah relatif
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $url_gambar)) {
        return $url_gambar;
    }

    return null;
}
?>

<style>
    /* VARIABEL CSS SESUAI HEADER.PHP - GRADIENT BIRU HIJAU */
    :root {
        --c: #13547a;       /* Biru tua */
        --d: #80d0c7;       /* Hijau muda */
        --bg: #f8f9fa;      /* Background light */
        --w: #fff;          /* Putih */
        --t: #212529;       /* Teks gelap */
        --b: #e9ecef;       /* Border light */
        --r: 14px;
        --s: 0 8px 24px rgba(0,0,0,.08);
    }

    * {
        font-family: 'Montserrat', sans-serif;
    }

    body {
        background: var(--bg);
    }

    /* PAGE HEADER - SESUAI HEADER.PHP */
    .page-header {
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: var(--w);
        padding: 80px 0 60px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .page-header h1 {
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 15px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .page-header .subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        font-family: 'Open Sans', sans-serif;
    }

    /* BREADCRUMB NAVIGATION */
    .breadcrumb-nav {
        background: var(--w);
        border-radius: var(--r);
        padding: 15px 25px;
        margin-bottom: 30px;
        box-shadow: var(--s);
    }
    
    .breadcrumb-nav a {
        color: var(--c);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .breadcrumb-nav a:hover {
        color: var(--d);
        text-decoration: underline;
    }
    
    .breadcrumb-nav .separator {
        margin: 0 10px;
        color: #94a3b8;
    }
    
    .breadcrumb-nav .current {
        color: var(--t);
        font-weight: 600;
    }

    /* STATISTICS SECTION */
    .stats-container {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        padding: 25px;
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
        text-align: center;
    }
    
    .stat-box {
        min-width: 180px;
        transition: all 0.3s ease;
        border-left: 4px solid var(--c);
    }
    
    .stat-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }
    
    .stat-number {
        font-size: 2.8rem;
        font-weight: bold;
        color: var(--c);
        margin-bottom: 10px;
        display: block;
        font-family: 'Montserrat', sans-serif;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
    }

    /* FILTER SECTION */
    .filter-section {
        background: var(--w);
        border-radius: var(--r);
        padding: 25px;
        margin-bottom: 40px;
        box-shadow: var(--s);
    }
    
    .filter-title {
        color: var(--c);
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.3rem;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-group label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #555;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .filter-input {
        padding: 12px 15px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
        font-family: 'Open Sans', sans-serif;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--c);
        box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.15);
    }

    /* AWARD CARD */
    .award-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }
    
    .award-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .award-image-container {
        height: 220px;
        position: relative;
        overflow: hidden;
    }
    
    .award-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .award-card:hover .award-image {
        transform: scale(1.05);
    }
    
    .award-year-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: var(--w);
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        font-family: 'Montserrat', sans-serif;
        backdrop-filter: blur(5px);
    }
    
    .award-content {
        padding: 20px;
    }
    
    .award-title {
        color: var(--c);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.2em;
        line-height: 1.4;
        font-family: 'Montserrat', sans-serif;
    }
    
    .award-category {
        display: inline-block;
        background: #eef2ff;
        color: var(--c);
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 15px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .award-description {
        color: var(--t);
        margin-bottom: 15px;
        font-size: 0.9em;
        line-height: 1.5;
        height: 65px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Open Sans', sans-serif;
    }
    
    .award-details {
        border-top: 1px solid #dee2e6;
        padding-top: 15px;
        margin-top: 15px;
    }
    
    .detail-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        font-family: 'Open Sans', sans-serif;
    }
    
    .detail-row i {
        color: var(--c);
        width: 18px;
        text-align: center;
    }

    /* NO DATA MESSAGE */
    .no-data {
        text-align: center;
        padding: 60px 30px;
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        margin: 30px 0;
        border: 1px solid #dee2e6;
    }
    
    .no-data i {
        font-size: 4rem;
        color: var(--c);
        margin-bottom: 25px;
        opacity: 0.8;
    }
    
    .no-data h4 {
        color: var(--c);
        margin-bottom: 15px;
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }
    
    .no-data p {
        color: var(--t);
        margin-bottom: 25px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
        font-family: 'Open Sans', sans-serif;
    }

    /* MODAL/ LIGHTBOX STYLES - IMPROVED FOR SCROLLING */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.95);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        padding: 20px;
        overflow-y: auto;
    }
    
    .lightbox-content {
        max-width: 800px;
        width: 95%;
        max-height: 90vh;
        position: relative;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: modalAppear 0.3s ease;
    }
    
    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .lightbox-image-container {
        flex-shrink: 0;
        height: 300px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .lightbox-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
    }
    
    .lightbox-body {
        flex: 1;
        padding: 25px;
        color: var(--t);
        overflow-y: auto;
        max-height: calc(90vh - 300px - 50px); /* Tinggi total - tinggi gambar - padding */
    }
    
    .lightbox-title {
        color: var(--c);
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.8rem;
        line-height: 1.3;
        font-family: 'Montserrat', sans-serif;
        padding-right: 40px; /* Ruang untuk tombol close */
    }
    
    .lightbox-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .lightbox-year {
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: var(--w);
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        font-family: 'Montserrat', sans-serif;
        white-space: nowrap;
    }
    
    .lightbox-category {
        background: #eef2ff;
        color: var(--c);
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        font-family: 'Montserrat', sans-serif;
        white-space: nowrap;
    }
    
    .lightbox-description {
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        line-height: 1.7;
        font-size: 1.05rem;
        font-family: 'Open Sans', sans-serif;
    }
    
    .lightbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }
    
    .lightbox-info-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--c);
        transition: transform 0.2s ease;
    }
    
    .lightbox-info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .lightbox-info-card h4 {
        color: var(--c);
        margin-bottom: 12px;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .lightbox-info-card p {
        color: #475569;
        font-size: 1.05rem;
        font-weight: 500;
        font-family: 'Open Sans', sans-serif;
        line-height: 1.5;
    }
    
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: var(--w);
        font-size: 28px;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.7);
        transition: all 0.3s;
        z-index: 10;
        border: none;
    }
    
    .lightbox-close:hover {
        background: rgba(0,0,0,0.9);
        transform: rotate(90deg);
    }
    
    /* Scrollbar styling untuk lightbox */
    .lightbox-body::-webkit-scrollbar {
        width: 8px;
    }
    
    .lightbox-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .lightbox-body::-webkit-scrollbar-thumb {
        background: var(--c);
        border-radius: 10px;
    }
    
    .lightbox-body::-webkit-scrollbar-thumb:hover {
        background: var(--d);
    }

    /* PAGINATION */
    .pagination {
        justify-content: center;
        margin-top: 40px;
    }
    
    .page-item .page-link {
        color: var(--c);
        border: 2px solid #dee2e6;
        margin: 0 5px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
        padding: 8px 18px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        border-color: transparent;
        color: var(--w);
    }
    
    .page-item .page-link:hover {
        background: var(--bg);
        border-color: var(--c);
        color: var(--c);
        text-decoration: none;
    }
    
    .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
        background-color: var(--w);
        border-color: #dee2e6;
    }

    .page-info {
        text-align: center;
        color: var(--t);
        margin-top: 15px;
        font-size: 0.95rem;
        font-family: 'Open Sans', sans-serif;
    }

    /* BACK TO TOP BUTTON */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        transition: all 0.3s ease;
        z-index: 100;
        border: none;
    }
    
    .back-to-top:hover {
        background: linear-gradient(135deg, #0f4266 0%, #6bc0b7 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(19, 84, 122, 0.4);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .stats-container {
            padding: 20px;
            gap: 15px;
        }
        
        .stat-box {
            min-width: 140px;
        }
        
        .stat-number {
            font-size: 2.2rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .lightbox-content {
            width: 98%;
            max-height: 95vh;
        }
        
        .lightbox-image-container {
            height: 250px;
        }
        
        .lightbox-body {
            max-height: calc(95vh - 250px - 50px);
        }
        
        .lightbox-grid {
            grid-template-columns: 1fr;
        }
        
        .lightbox-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header .subtitle {
            font-size: 1rem;
        }
        
        .breadcrumb-nav {
            padding: 12px 15px;
            font-size: 0.9rem;
        }
        
        .stat-box {
            min-width: 120px;
            padding: 15px;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .lightbox-body {
            padding: 20px;
        }
        
        .lightbox-image-container {
            height: 200px;
        }
        
        .lightbox-body {
            max-height: calc(95vh - 200px - 40px);
        }
        
        .lightbox-description {
            padding: 15px;
            font-size: 1rem;
        }
        
        .lightbox-meta {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
    }
    
    @media (max-width: 400px) {
        .lightbox-image-container {
            height: 180px;
            padding: 15px;
        }
        
        .lightbox-body {
            padding: 15px;
            max-height: calc(95vh - 180px - 30px);
        }
        
        .lightbox-title {
            font-size: 1.3rem;
        }
        
        .lightbox-close {
            top: 10px;
            right: 10px;
            width: 35px;
            height: 35px;
            font-size: 24px;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Penghargaan</h1>
                <p class="subtitle">
                    Kumpulan penghargaan dan prestasi yang telah diraih oleh JDIH DPRD Kota Surakarta
                </p>
            </div>
        </div>
    </div>
</section>

<!-- BREADCRUMB NAVIGATION -->
<div class="container">
    <div class="breadcrumb-nav">
        <a href="index.php">Beranda</a>
        <span class="separator">/</span>
        <a href="?page=penghargaan">Informasi</a>
        <span class="separator">/</span>
        <span class="current">Penghargaan</span>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <!-- STATISTICS -->
    <div class="stats-container">
        <div class="stat-box">
            <span class="stat-number" id="totalCount"><?php echo count($penghargaan_data); ?></span>
            <span class="stat-label">Total Penghargaan</span>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo date('Y'); ?></span>
            <span class="stat-label">Tahun Ini</span>
        </div>
        <?php
        // Hitung kategori unik
        $kategori_unik = [];
        foreach ($penghargaan_data as $data) {
            if (!empty($data['kategori_penghargaan']) && !in_array($data['kategori_penghargaan'], $kategori_unik)) {
                $kategori_unik[] = $data['kategori_penghargaan'];
            }
        }
        ?>
        <div class="stat-box">
            <span class="stat-number"><?php echo count($kategori_unik); ?></span>
            <span class="stat-label">Kategori</span>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <div class="filter-title">
            <i class="bi bi-filter"></i> Filter Penghargaan
        </div>
        <div class="filter-grid">
            <div class="filter-group">
                <label for="filterYear"><i class="bi bi-calendar"></i> Tahun</label>
                <select id="filterYear" class="filter-input">
                    <option value="">Semua Tahun</option>
                    <?php
                    // Ambil tahun unik dari data
                    $tahun_unik = [];
                    foreach ($penghargaan_data as $data) {
                        if (!empty($data['tahun_penghargaan']) && !in_array($data['tahun_penghargaan'], $tahun_unik)) {
                            $tahun_unik[] = $data['tahun_penghargaan'];
                        }
                    }
                    rsort($tahun_unik); // Urutkan dari tahun terbaru
                    
                    foreach ($tahun_unik as $tahun) {
                        echo '<option value="' . $tahun . '">' . $tahun . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filterCategory"><i class="bi bi-tags"></i> Kategori</label>
                <select id="filterCategory" class="filter-input">
                    <option value="">Semua Kategori</option>
                    <?php
                    foreach ($kategori_unik as $kategori) {
                        echo '<option value="' . htmlspecialchars($kategori) . '">' . htmlspecialchars($kategori) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="searchInput"><i class="bi bi-search"></i> Cari</label>
                <input type="text" id="searchInput" class="filter-input" placeholder="Cari berdasarkan nama, penerima, atau deskripsi...">
            </div>
        </div>
    </div>

    <!-- AWARDS GALLERY -->
    <div class="row" id="awardsGallery">
        <?php if (empty($penghargaan_data)): ?>
            <div class="col-12">
                <div class="no-data">
                    <i class="bi bi-trophy"></i>
                    <h4>Tidak Ada Data Penghargaan</h4>
                    <p>Belum ada data penghargaan yang tersedia.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($penghargaan_data as $index => $award): ?>
                <?php
                // Tentukan path gambar yang akan digunakan
                $image_path = !empty($award['thumbnail_path']) ? $award['thumbnail_path'] : $award['foto_penghargaan_path'];
                
                // Dapatkan path gambar yang benar
                $url_gambar = getPenghargaanImagePath($image_path);
                
                // Potong deskripsi jika terlalu panjang
                $short_desc = !empty($award['deskripsi']) ? 
                    (strlen($award['deskripsi']) > 150 ? substr($award['deskripsi'], 0, 150) . '...' : $award['deskripsi']) : 
                    'Tidak ada deskripsi';
                ?>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="award-card" data-year="<?php echo $award['tahun_penghargaan'] ?? ''; ?>" 
                         data-category="<?php echo htmlspecialchars($award['kategori_penghargaan'] ?? ''); ?>"
                         data-index="<?php echo $index; ?>">
                        
                        <div class="award-image-container">
                            <?php if ($url_gambar): ?>
                                <img src="<?php echo htmlspecialchars($url_gambar); ?>" 
                                     alt="<?php echo htmlspecialchars($award['nama_penghargaan']); ?>" 
                                     class="award-image"
                                     onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80'">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                     alt="Penghargaan" 
                                     class="award-image">
                            <?php endif; ?>
                            
                            <?php if (!empty($award['tahun_penghargaan'])): ?>
                                <div class="award-year-badge"><?php echo $award['tahun_penghargaan']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="award-content">
                            <h3 class="award-title"><?php echo htmlspecialchars($award['nama_penghargaan']); ?></h3>
                            
                            <?php if (!empty($award['kategori_penghargaan'])): ?>
                                <span class="award-category"><?php echo htmlspecialchars($award['kategori_penghargaan']); ?></span>
                            <?php endif; ?>
                            
                            <p class="award-description"><?php echo htmlspecialchars($short_desc); ?></p>
                            
                            <div class="award-details">
                                <?php if (!empty($award['penerima_penghargaan'])): ?>
                                    <div class="detail-row">
                                        <i class="bi bi-person-badge"></i>
                                        <span><strong>Penerima:</strong> <?php echo htmlspecialchars($award['penerima_penghargaan']); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($award['instansi_pemberi'])): ?>
                                    <div class="detail-row">
                                        <i class="bi bi-building"></i>
                                        <span><strong>Pemberi:</strong> <?php echo htmlspecialchars($award['instansi_pemberi']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL/LIGHTBOX -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">&times;</button>
        
        <div class="lightbox-image-container">
            <img id="lightbox-img" class="lightbox-img" src="" alt="">
        </div>
        
        <div class="lightbox-body">
            <h3 id="lightbox-title" class="lightbox-title"></h3>
            
            <div class="lightbox-meta">
                <span id="lightbox-year" class="lightbox-year"></span>
                <span id="lightbox-category" class="lightbox-category"></span>
            </div>
            
            <div class="lightbox-description" id="lightbox-description"></div>
            
            <div class="lightbox-grid" id="lightbox-details"></div>
        </div>
    </div>
</div>

<!-- BACK TO TOP BUTTON -->
<button class="back-to-top" id="backToTop">
    <i class="bi bi-chevron-up"></i>
</button>

<script>
// Data dari PHP
const awardsData = <?php echo json_encode($penghargaan_data); ?>;

// Fungsi untuk mendapatkan path gambar untuk lightbox
function getLightboxImagePath(path) {
    if (!path) return 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    
    // Jika sudah URL absolut
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }
    
    // Jika path relatif
    if (path.startsWith('/')) {
        return path;
    }
    
    // Coba ekstrak nama file
    const filename = path.split(/[\\/]/).pop();
    return '/jdihcilacap_pasang/wb_admin/uploads/penghargaan/' + filename;
}

// Fungsi untuk filter penghargaan
function filterAwards() {
    const yearValue = document.getElementById('filterYear').value;
    const categoryValue = document.getElementById('filterCategory').value;
    const searchValue = document.getElementById('searchInput').value.toLowerCase().trim();
    const awardCards = document.querySelectorAll('.award-card');
    const totalCountElement = document.getElementById('totalCount');
    
    let visibleCount = 0;
    
    awardCards.forEach(card => {
        const year = card.getAttribute('data-year');
        const category = card.getAttribute('data-category');
        const index = parseInt(card.getAttribute('data-index'));
        const award = awardsData[index];
        
        let visible = true;
        
        // Filter tahun
        if (yearValue && year !== yearValue) {
            visible = false;
        }
        
        // Filter kategori
        if (categoryValue && category !== categoryValue) {
            visible = false;
        }
        
        // Filter pencarian
        if (searchValue) {
            const searchText = `
                ${award.nama_penghargaan || ''}
                ${award.deskripsi || ''}
                ${award.penerima_penghargaan || ''}
                ${award.instansi_pemberi || ''}
                ${award.kategori_penghargaan || ''}
            `.toLowerCase();
            
            if (!searchText.includes(searchValue)) {
                visible = false;
            }
        }
        
        // Tampilkan/sembunyikan card
        card.parentElement.style.display = visible ? 'block' : 'none';
        if (visible) visibleCount++;
    });
    
    // Update total count
    totalCountElement.textContent = visibleCount;
    
    // Tampilkan pesan jika tidak ada data
    const gallery = document.getElementById('awardsGallery');
    const existingNoData = gallery.querySelector('.no-data');
    
    if (visibleCount === 0 && !existingNoData) {
        const noDataDiv = document.createElement('div');
        noDataDiv.className = 'col-12';
        noDataDiv.innerHTML = `
            <div class="no-data">
                <i class="bi bi-search"></i>
                <h4>Tidak Ada Hasil</h4>
                <p>Tidak ada penghargaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        `;
        gallery.appendChild(noDataDiv);
    } else if (visibleCount > 0 && existingNoData) {
        existingNoData.parentElement.remove();
    }
}

// Fungsi untuk membuka lightbox
function openLightbox(index) {
    if (awardsData.length === 0) return;
    
    const award = awardsData[index];
    
    // Tentukan path gambar untuk modal
    const imagePath = award.foto_penghargaan_path || award.thumbnail_path || '';
    const modalImagePath = getLightboxImagePath(imagePath);
    
    // Format tanggal jika ada
    let tanggalFormatted = '';
    if (award.tanggal_diterima) {
        const date = new Date(award.tanggal_diterima);
        tanggalFormatted = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    // Format tanggal dibuat
    let createdFormatted = '';
    if (award.created_at) {
        const date = new Date(award.created_at);
        createdFormatted = date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Set gambar
    const imgElement = document.getElementById('lightbox-img');
    imgElement.src = modalImagePath;
    imgElement.alt = award.nama_penghargaan || 'Penghargaan';
    imgElement.onerror = function() {
        this.src = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    };
    
    // Set judul dan deskripsi
    document.getElementById('lightbox-title').textContent = award.nama_penghargaan;
    document.getElementById('lightbox-description').textContent = award.deskripsi || 'Tidak ada deskripsi tersedia.';
    
    // Set meta data
    const yearSpan = document.getElementById('lightbox-year');
    const categorySpan = document.getElementById('lightbox-category');
    
    if (award.tahun_penghargaan) {
        yearSpan.textContent = award.tahun_penghargaan;
        yearSpan.style.display = 'inline-block';
    } else {
        yearSpan.style.display = 'none';
    }
    
    if (award.kategori_penghargaan) {
        categorySpan.textContent = award.kategori_penghargaan;
        categorySpan.style.display = 'inline-block';
    } else {
        categorySpan.style.display = 'none';
    }
    
    // Set detail info
    const detailsContainer = document.getElementById('lightbox-details');
    let detailsHTML = '';
    
    if (award.penerima_penghargaan) {
        detailsHTML += `
            <div class="lightbox-info-card">
                <h4><i class="bi bi-person-badge"></i> Penerima Penghargaan</h4>
                <p>${award.penerima_penghargaan}</p>
            </div>
        `;
    }
    
    if (award.instansi_pemberi) {
        detailsHTML += `
            <div class="lightbox-info-card">
                <h4><i class="bi bi-building"></i> Instansi Pemberi</h4>
                <p>${award.instansi_pemberi}</p>
            </div>
        `;
    }
    
    if (tanggalFormatted) {
        detailsHTML += `
            <div class="lightbox-info-card">
                <h4><i class="bi bi-calendar-check"></i> Tanggal Diterima</h4>
                <p>${tanggalFormatted}</p>
            </div>
        `;
    }
    
    if (createdFormatted) {
        detailsHTML += `
            <div class="lightbox-info-card">
                <h4><i class="bi bi-clock"></i> Ditambahkan Pada</h4>
                <p>${createdFormatted}</p>
            </div>
        `;
    }
    
    
    detailsContainer.innerHTML = detailsHTML;
    
    // Tampilkan lightbox
    const lightbox = document.getElementById('lightbox');
    lightbox.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Scroll ke atas konten lightbox
    const lightboxBody = document.querySelector('.lightbox-body');
    if (lightboxBody) {
        lightboxBody.scrollTop = 0;
    }
}

// Fungsi untuk menutup lightbox
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Setup event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup filter
    document.getElementById('filterYear').addEventListener('change', filterAwards);
    document.getElementById('filterCategory').addEventListener('change', filterAwards);
    document.getElementById('searchInput').addEventListener('input', filterAwards);
    
    // Setup klik pada award card untuk membuka lightbox
    const awardCards = document.querySelectorAll('.award-card');
    
    awardCards.forEach(card => {
        card.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            openLightbox(index);
        });
    });
    
    // Setup tombol close lightbox
    document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
    
    // Close lightbox ketika klik di luar konten
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
    
    // Close lightbox dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
    
    // Back to top button
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'flex';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Inisialisasi filter
    filterAwards();
});
</script>

<?php 
include 'footer.php'; 
?>