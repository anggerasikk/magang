<?php
// galeri.php - FRONTEND untuk pengunjung
$pageTitle = 'Galeri Foto - JDIH DPRD Kota Surakarta';
include 'header.php';

require_once 'a_db.php';

// Set base URL untuk gambar
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/jdihcilacap_pasang/';
$upload_base_path = 'C:/laragon/www/jdihcilacap_pasang/wb_admin/uploads/galeri/';

// Ambil parameter filter dari URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$current_page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
if ($current_page < 1) $current_page = 1;

$per_page = 9;
$offset = ($current_page - 1) * $per_page;

// Fungsi untuk membuat URL dengan parameter yang benar
function buildUrl($params = []) {
    $base_params = ['page' => 'galeri'];
    $all_params = array_merge($base_params, $params);
    return '?' . http_build_query($all_params);
}

// Fungsi untuk mendapatkan path gambar yang benar
function getImagePath($url_gambar) {
    // PATH SERVER (UNTUK CEK FILE)
    $server_base = $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/galeri/';
    
    // URL WEB (UNTUK IMG SRC)
    $web_base = '/jdihcilacap_pasang/wb_admin/uploads/galeri/';

    if (empty($url_gambar)) {
        return null;
    }

    // Jika sudah URL absolut
    if (preg_match('/^https?:\/\//i', $url_gambar)) {
        return $url_gambar;
    }

    $filename = basename($url_gambar);
    $server_path = $server_base . $filename;

    if (file_exists($server_path)) {
        return $web_base . $filename;
    }

    return null;
}

// Deklarasi variabel
$album_info = null;
$item_type = 'album';
$total_data = 0;
$items_data = [];

if ($filter === 'all') {
    // MODE ALBUM
    $query_items = "
        SELECT * FROM galeri_foto
        WHERE tipe = 'album'
        ORDER BY is_cover DESC, urutan, tanggal_dibuat DESC
        LIMIT $per_page OFFSET $offset
    ";

    $query_count = "
        SELECT COUNT(*) as total 
        FROM galeri_foto 
        WHERE tipe = 'album'
    ";

    $item_type = 'album';

} else {
    // MODE FOTO DALAM ALBUM
    $album_id = (int)$filter;

    // Ambil informasi album terlebih dahulu
    $query_album_info = "SELECT * FROM galeri_foto WHERE id = $album_id AND tipe = 'album'";
    $result_album_info = mysqli_query($koneksi, $query_album_info);
    if ($result_album_info && mysqli_num_rows($result_album_info) > 0) {
        $album_info = mysqli_fetch_assoc($result_album_info);
    }

    $query_items = "
        SELECT f.*, a.judul AS album_judul
        FROM galeri_foto f
        JOIN galeri_foto a ON f.parent_id = a.id
        WHERE f.tipe = 'foto' AND a.id = $album_id
        ORDER BY f.urutan, f.tanggal_dibuat DESC
        LIMIT $per_page OFFSET $offset
    ";

    $query_count = "
        SELECT COUNT(*) as total
        FROM galeri_foto
        WHERE tipe = 'foto' AND parent_id = $album_id
    ";

    $item_type = 'foto';
}

// Eksekusi query items
$result_items = mysqli_query($koneksi, $query_items);
if (!$result_items) {
    die("Query error: " . mysqli_error($koneksi));
}

// Hitung total data
$result_count = mysqli_query($koneksi, $query_count);
if ($result_count) {
    $total_data_row = mysqli_fetch_assoc($result_count);
    $total_data = $total_data_row['total'];
} else {
    $total_data = 0;
}

$total_pages = ceil($total_data / $per_page);

// Query untuk mengambil semua album untuk filter dropdown
$query_all_albums = "SELECT * FROM galeri_foto WHERE tipe = 'album' ORDER BY urutan";
$result_all_albums = mysqli_query($koneksi, $query_all_albums);

// Reset pointer result albums untuk digunakan lagi nanti
if ($result_all_albums) {
    mysqli_data_seek($result_all_albums, 0);
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

    /* ALBUM HEADER */
    .album-header {
        background: var(--w);
        border-radius: var(--r);
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: var(--s);
        text-align: center;
    }
    
    .album-header h2 {
        color: var(--c);
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.8rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .album-header p {
        color: var(--t);
        max-width: 800px;
        margin: 0 auto 20px;
        line-height: 1.6;
        font-family: 'Open Sans', sans-serif;
    }
    
    .album-header .album-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 25px;
    }
    
    .album-info-item {
        display: flex;
        align-items: center;
        color: var(--c);
        font-weight: 600;
        font-size: 1.1rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .album-info-item i {
        margin-right: 10px;
        font-size: 1.3rem;
    }

    /* BACK BUTTON - SESUAI HEADER.PHP */
    .back-to-albums {
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        border: none;
        color: var(--w);
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        margin-top: 20px;
        font-size: 1rem;
        font-family: 'Montserrat', sans-serif;
        box-shadow: 0 3px 8px rgba(19, 84, 122, 0.2);
    }
    
    .back-to-albums:hover {
        background: linear-gradient(135deg, #0f4266 0%, #6bc0b7 100%);
        color: var(--w);
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    
    .back-to-albums i {
        margin-right: 10px;
    }

    /* FILTER SECTION */
    .gallery-filter {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        padding: 25px;
        margin-bottom: 40px;
    }
    
    .filter-title {
        color: var(--c);
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.3rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .filter-btn {
        background: var(--bg);
        border: 2px solid #dee2e6;
        color: var(--t);
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 0.95rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: var(--w);
        border-color: transparent;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    /* ALBUM CARD */
    .album-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: 1px solid #dee2e6;
    }
    
    .album-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .album-cover {
        height: 250px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .album-info {
        padding: 20px;
    }
    
    .album-title {
        color: var(--c);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.2em;
        line-height: 1.4;
        font-family: 'Montserrat', sans-serif;
    }
    
    .album-desc {
        color: var(--t);
        margin-bottom: 15px;
        font-size: 0.95em;
        line-height: 1.5;
        height: 70px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Open Sans', sans-serif;
    }
    
    .album-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    
    .album-date {
        color: #6c757d;
        font-size: 0.85em;
        font-family: 'Open Sans', sans-serif;
    }
    
    .album-date i {
        margin-right: 5px;
    }
    
    .album-count {
        color: var(--c);
        font-weight: 600;
        font-size: 0.95em;
        font-family: 'Montserrat', sans-serif;
    }
    
    .album-count i {
        margin-right: 5px;
    }
    
    .badge-cover {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
        color: var(--w);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
        font-family: 'Montserrat', sans-serif;
    }

    /* FOTO CARD */
    .foto-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: 1px solid #dee2e6;
    }
    
    .foto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .foto-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.5s;
    }
    
    .foto-card:hover .foto-img {
        transform: scale(1.05);
    }
    
    .foto-info {
        padding: 20px;
    }
    
    .foto-title {
        color: var(--c);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.1em;
        line-height: 1.4;
        font-family: 'Montserrat', sans-serif;
    }
    
    .foto-desc {
        color: var(--t);
        margin-bottom: 15px;
        font-size: 0.9em;
        line-height: 1.5;
        height: 65px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Open Sans', sans-serif;
    }
    
    .foto-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    
    .foto-date {
        color: #6c757d;
        font-size: 0.85em;
        font-family: 'Open Sans', sans-serif;
    }
    
    .foto-date i {
        margin-right: 5px;
    }
    
    .foto-album {
        color: var(--c);
        font-weight: 600;
        font-size: 0.85em;
        font-family: 'Montserrat', sans-serif;
    }
    
    .foto-album i {
        margin-right: 5px;
    }

    /* LIGHTBOX STYLES */
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
    }
    
    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
    }
    
    .lightbox-img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 10px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    
    .lightbox-info {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 0 0 10px 10px;
        padding: 25px;
        margin-top: -5px;
        color: var(--t);
    }
    
    .lightbox-title {
        color: var(--c);
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.3rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .lightbox-desc {
        color: var(--t);
        margin-bottom: 15px;
        line-height: 1.6;
        font-family: 'Open Sans', sans-serif;
    }
    
    .lightbox-meta {
        display: flex;
        justify-content: space-between;
        color: #6c757d;
        font-size: 0.9rem;
        font-family: 'Open Sans', sans-serif;
    }
    
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: var(--w);
        font-size: 40px;
        cursor: pointer;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.5);
        transition: background 0.3s;
    }
    
    .lightbox-close:hover {
        background: rgba(0,0,0,0.8);
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: var(--w);
        font-size: 50px;
        cursor: pointer;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.5);
        transition: background 0.3s;
    }
    
    .lightbox-nav:hover {
        background: rgba(0,0,0,0.8);
    }
    
    .lightbox-prev {
        left: 20px;
    }
    
    .lightbox-next {
        right: 20px;
    }

    /* PAGINATION - SESUAI HEADER.PHP */
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

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .album-header {
            padding: 20px;
        }
        
        .album-header .album-info {
            flex-direction: column;
            gap: 15px;
        }
        
        .filter-tags {
            justify-content: center;
        }
        
        .lightbox-nav {
            width: 50px;
            height: 50px;
            font-size: 40px;
        }
        
        .lightbox-content {
            max-width: 95%;
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
        
        .filter-btn {
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        
        .pagination .page-link {
            padding: 6px 15px;
            font-size: 0.9rem;
            margin: 0 3px;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Galeri Foto</h1>
                <p class="subtitle">
                    Dokumentasi visual kegiatan dan acara JDIH DPRD Kota Surakarta
                </p>
            </div>
        </div>
    </div>
</section>

<!-- BREADCRUMB NAVIGATION -->
<div class="container">
    <div class="breadcrumb-nav">
        <?php if ($filter == 'all'): ?>
            <span class="current">Semua Album</span>
        <?php else: ?>
            <a href="<?php echo buildUrl(['filter' => 'all', 'pg' => 1]); ?>">Semua Album</a>
            <span class="separator">/</span>
            <span class="current"><?php echo htmlspecialchars($album_info['judul'] ?? 'Album'); ?></span>
        <?php endif; ?>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <?php if ($filter != 'all' && isset($album_info)): ?>
        <!-- ALBUM HEADER (hanya tampil saat melihat foto dalam album) -->
        <div class="album-header">
            <h2><?php echo htmlspecialchars($album_info['judul']); ?></h2>
            <?php if (!empty($album_info['deskripsi'])): ?>
                <p><?php echo htmlspecialchars($album_info['deskripsi']); ?></p>
            <?php endif; ?>
            
            <div class="album-info">
                <div class="album-info-item">
                    <i class="bi bi-images"></i>
                    <span><?php echo $total_data; ?> Foto</span>
                </div>
                <div class="album-info-item">
                    <i class="bi bi-calendar"></i>
                    <span><?php echo date('d F Y', strtotime($album_info['tanggal_dibuat'])); ?></span>
                </div>
            </div>
            
            <a href="<?php echo buildUrl(['filter' => 'all', 'pg' => 1]); ?>" class="back-to-albums">
                <i class="bi bi-arrow-left"></i> Kembali ke Semua Album
            </a>
        </div>
    <?php endif; ?>
    
    <!-- FILTER SECTION (hanya tampil saat melihat semua album) -->
    <?php if ($filter == 'all'): ?>
        <div class="gallery-filter">
            <h4 class="filter-title">Pilih Album</h4>
            <div class="filter-tags">
                <a href="<?php echo buildUrl(['filter' => 'all', 'pg' => 1]); ?>" class="filter-btn active">
                    Semua Album
                </a>
                <?php 
                if ($result_all_albums && mysqli_num_rows($result_all_albums) > 0):
                    while ($album = mysqli_fetch_assoc($result_all_albums)): 
                ?>
                    <a href="<?php echo buildUrl(['filter' => $album['id'], 'pg' => 1]); ?>" class="filter-btn">
                        <?php echo htmlspecialchars($album['judul']); ?>
                    </a>
                <?php 
                    endwhile;
                else:
                ?>
                    <span class="text-muted">Belum ada album</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- GALLERY GRID -->
    <div class="row">
        <?php if ($result_items && mysqli_num_rows($result_items) > 0): ?>
            <?php 
            $counter = 0;
            $items_data = []; // Untuk menyimpan data untuk lightbox
            
            while ($item = mysqli_fetch_assoc($result_items)): 
                $counter++;
                $tanggal = date('d F Y', strtotime($item['tanggal_dibuat']));
                
                // Tentukan apakah ini album atau foto
                $is_album = ($item_type == 'album');
                
                // Dapatkan path gambar yang benar
                $url_gambar = getImagePath($item['url_gambar']);
                
                // Untuk foto, simpan data untuk lightbox
                if (!$is_album) {
                    $items_data[] = [
                        'id' => $counter,
                        'judul' => htmlspecialchars($item['judul']),
                        'deskripsi' => htmlspecialchars($item['deskripsi']),
                        'url_gambar' => $url_gambar,
                        'tanggal' => $tanggal,
                        'album_judul' => isset($item['album_judul']) ? htmlspecialchars($item['album_judul']) : ''
                    ];
                }
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <?php if ($is_album): ?>
                    <?php 
                    // Hitung jumlah foto dalam album
                    $album_id = $item['id'];
                    $query_foto_count = "SELECT COUNT(*) as total FROM galeri_foto WHERE parent_id = $album_id";
                    $result_foto_count = mysqli_query($koneksi, $query_foto_count);
                    $foto_count_data = $result_foto_count ? mysqli_fetch_assoc($result_foto_count) : ['total' => 0];
                    $foto_count = $foto_count_data['total'];
                    ?>
                    
                    <!-- ALBUM CARD -->
                    <div class="album-card" onclick="window.location.href='<?php echo buildUrl(['filter' => $item['id'], 'pg' => 1]); ?>'" style="cursor: pointer;">
                        <div class="album-cover" style="background-image: url('<?php echo htmlspecialchars($url_gambar); ?>')">
                            <?php if ($item['is_cover'] == 1): ?>
                                <span class="badge-cover">Album Utama</span>
                            <?php endif; ?>
                        </div>
                        <div class="album-info">
                            <h3 class="album-title"><?php echo htmlspecialchars($item['judul']); ?></h3>
                            <p class="album-desc"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 120) . (strlen($item['deskripsi']) > 120 ? '...' : '')); ?></p>
                            <div class="album-meta">
                                <div class="album-date">
                                    <i class="bi bi-calendar"></i> <?php echo $tanggal; ?>
                                </div>
                                <div class="album-count">
                                    <i class="bi bi-images"></i> <?php echo $foto_count; ?> Foto
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    
                    <!-- FOTO CARD -->
                    <div class="foto-card">
                        <img src="<?php echo htmlspecialchars($url_gambar); ?>" 
                             alt="<?php echo htmlspecialchars($item['judul']); ?>"
                             class="foto-img"
                             onclick="openLightbox(<?php echo $counter - 1; ?>)"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80'">
                        <div class="foto-info">
                            <h4 class="foto-title"><?php echo htmlspecialchars($item['judul']); ?></h4>
                            <p class="foto-desc"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 100) . (strlen($item['deskripsi']) > 100 ? '...' : '')); ?></p>
                            <div class="foto-meta">
                                <div class="foto-date">
                                    <i class="bi bi-calendar"></i> <?php echo $tanggal; ?>
                                </div>
                                <?php if (isset($item['album_judul'])): ?>
                                    <div class="foto-album">
                                        <i class="bi bi-folder"></i> <?php echo htmlspecialchars($item['album_judul']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </div>
            
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="no-data">
                    <?php if ($filter == 'all'): ?>
                        <i class="bi bi-images"></i>
                        <h4>Tidak ada album ditemukan</h4>
                        <p>Belum ada album yang dibuat. Silakan kembali lagi nanti.</p>
                    <?php else: ?>
                        <i class="bi bi-image"></i>
                        <h4>Tidak ada foto dalam album ini</h4>
                        <p>Belum ada foto yang diunggah untuk album ini.</p>
                        <a href="<?php echo buildUrl(['filter' => 'all', 'pg' => 1]); ?>" class="back-to-albums">
                            <i class="bi bi-arrow-left"></i> Kembali ke Semua Album
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Previous Button -->
                <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" 
                       href="<?php echo buildUrl(['filter' => $filter, 'pg' => max(1, $current_page - 1)]); ?>" 
                       aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                
                <!-- Page Numbers -->
                <?php 
                // Tampilkan maksimal 5 halaman di sekitar halaman aktif
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $start_page + 4);
                if ($end_page - $start_page < 4) {
                    $start_page = max(1, $end_page - 4);
                }
                
                for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo buildUrl(['filter' => $filter, 'pg' => $i]); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <!-- Next Button -->
                <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" 
                       href="<?php echo buildUrl(['filter' => $filter, 'pg' => min($total_pages, $current_page + 1)]); ?>" 
                       aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Page Info -->
        <div class="page-info">
            <?php 
            $start_item = ($current_page - 1) * $per_page + 1;
            $end_item = min($current_page * $per_page, $total_data);
            ?>
            Menampilkan <?php echo $start_item; ?> - <?php echo $end_item; ?> dari <?php echo $total_data; ?> 
            <?php echo ($filter == 'all') ? 'album' : 'foto'; ?>
        </div>
    <?php endif; ?>
</div>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <div class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)">
            <i class="bi bi-chevron-left"></i>
        </div>
        <div class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)">
            <i class="bi bi-chevron-right"></i>
        </div>
        <img id="lightbox-img" class="lightbox-img" src="">
        <div class="lightbox-info">
            <h3 id="lightbox-title" class="lightbox-title"></h3>
            <p id="lightbox-desc" class="lightbox-desc"></p>
            <div class="lightbox-meta">
                <span id="lightbox-date"></span>
                <span id="lightbox-album"></span>
            </div>
        </div>
    </div>
</div>

<script>
// Data untuk lightbox
const itemsData = <?php echo json_encode($items_data); ?>;
let currentLightboxIndex = 0;

// Fungsi untuk membuka lightbox
function openLightbox(index) {
    if (itemsData.length === 0) return;
    
    currentLightboxIndex = index;
    const item = itemsData[currentLightboxIndex];
    
    document.getElementById('lightbox-img').src = item.url_gambar;
    document.getElementById('lightbox-title').textContent = item.judul;
    document.getElementById('lightbox-desc').textContent = item.deskripsi;
    document.getElementById('lightbox-date').innerHTML = `<i class="bi bi-calendar"></i> ${item.tanggal}`;
    document.getElementById('lightbox-album').innerHTML = item.album_judul ? `<i class="bi bi-folder"></i> ${item.album_judul}` : '';
    
    document.getElementById('lightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Mencegah scroll
}

// Fungsi untuk menutup lightbox
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk mengganti gambar di lightbox
function changeLightboxImage(direction) {
    currentLightboxIndex += direction;
    
    // Loop around
    if (currentLightboxIndex < 0) {
        currentLightboxIndex = itemsData.length - 1;
    } else if (currentLightboxIndex >= itemsData.length) {
        currentLightboxIndex = 0;
    }
    
    openLightbox(currentLightboxIndex);
}

// Fungsi untuk navigasi keyboard
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    
    if (lightbox.style.display === 'flex') {
        e.preventDefault();
        
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                changeLightboxImage(-1);
                break;
            case 'ArrowRight':
                changeLightboxImage(1);
                break;
        }
    }
});

// Fungsi untuk swipe di mobile
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('lightbox').addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('lightbox').addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    
    if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe kiri
        changeLightboxImage(1);
    } else if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe kanan
        changeLightboxImage(-1);
    }
}

// Close lightbox ketika klik di luar gambar
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});
</script>

<?php 
// Tutup koneksi database
mysqli_close($koneksi);
include 'footer.php'; 
?>