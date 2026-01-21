<?php
// galeri.php
$pageTitle = 'Galeri Foto - JDIH DPRD Kota Surakarta';
include 'header.php';

require_once 'a_db.php';

// Query untuk mengambil album (parent_id = NULL dan tipe = 'album')
$query_album = "SELECT * FROM galeri_foto WHERE tipe = 'album' ORDER BY urutan";
$result_album = mysqli_query($koneksi, $query_album);

// Ambil parameter filter dari URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$per_page = 9;
$offset = ($current_page - 1) * $per_page;

// Query untuk mengambil foto berdasarkan filter
if ($filter == 'all') {
    // Ambil SEMUA item: album cover DAN foto
    $query_foto = "SELECT 
                    gf.*,
                    parent.judul as album_judul,
                    CASE 
                        WHEN gf.tipe = 'album' THEN 'album'
                        ELSE 'foto'
                    END as item_type
                   FROM galeri_foto gf
                   LEFT JOIN galeri_foto parent ON gf.parent_id = parent.id
                   WHERE (gf.tipe = 'foto' OR (gf.tipe = 'album' AND gf.is_cover = 1))
                   ORDER BY 
                     CASE WHEN gf.tipe = 'album' THEN 1 ELSE 2 END,
                     gf.tanggal_dibuat DESC 
                   LIMIT $per_page OFFSET $offset";
    
    $query_count = "SELECT COUNT(*) as total 
                    FROM galeri_foto 
                    WHERE tipe = 'foto' OR (tipe = 'album' AND is_cover = 1)";
} else {
    // Ambil hanya foto dalam album tertentu
    $filter = (int)$filter;
    $query_foto = "SELECT f.*, a.judul as album_judul, 'foto' as item_type
                   FROM galeri_foto f 
                   LEFT JOIN galeri_foto a ON f.parent_id = a.id 
                   WHERE f.tipe = 'foto' AND a.id = $filter
                   ORDER BY f.tanggal_dibuat DESC 
                   LIMIT $per_page OFFSET $offset";
    $query_count = "SELECT COUNT(*) as total FROM galeri_foto f 
                    LEFT JOIN galeri_foto a ON f.parent_id = a.id 
                    WHERE f.tipe = 'foto' AND a.id = $filter";
}

$result_foto = mysqli_query($koneksi, $query_foto);
$result_count = mysqli_query($koneksi, $query_count);

if ($result_count) {
    $total_data_row = mysqli_fetch_assoc($result_count);
    $total_data = $total_data_row['total'];
} else {
    $total_data = 0;
}

$total_pages = ceil($total_data / $per_page);

// Ambil semua foto untuk modal (tanpa pagination untuk modal)
$query_all_fotos = "SELECT f.*, a.judul as album_judul 
                    FROM galeri_foto f 
                    LEFT JOIN galeri_foto a ON f.parent_id = a.id 
                    WHERE f.tipe = 'foto' 
                    ORDER BY f.tanggal_dibuat DESC";
$result_all_fotos = mysqli_query($koneksi, $query_all_fotos);
$all_fotos = [];
if ($result_all_fotos) {
    while ($foto = mysqli_fetch_assoc($result_all_fotos)) {
        $all_fotos[] = $foto;
    }
}

// Reset pointer result album untuk digunakan lagi nanti
mysqli_data_seek($result_album, 0);
?>

<style>
    /* CSS KHUSUS UNTUK HALAMAN GALERI */
    body {
        background: #f0f8ff;
    }
    
    /* PAGE HEADER */
    .page-header {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        padding: 80px 0 60px;
        text-align: center;
        margin-bottom: 60px;
    }
    
    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.8rem;
        margin-bottom: 20px;
    }
    
    .page-header .subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* MAIN CONTENT */
    .main-content {
        padding: 40px 0 80px;
    }
    
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #13547a;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #80d0c7;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100px;
        height: 3px;
        background: #ff7e5f;
    }
    
    /* GALLERY FILTER */
    .gallery-filter {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        color: #13547a;
        padding: 10px 25px;
        border-radius: 50px;
        margin-right: 10px;
        margin-bottom: 10px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-color: transparent;
        text-decoration: none;
    }
    
    /* GALLERY GRID */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        height: 300px;
    }
    
    .gallery-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(19, 84, 122, 0.9), transparent);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 25px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .gallery-title {
        color: white;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    
    .gallery-date {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    
    .gallery-date i {
        margin-right: 5px;
    }
    
    /* BADGE untuk jenis item */
    .item-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
    }
    
    .item-badge.album {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
    }
    
    .item-badge.foto {
        background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
    }
    
    /* MODAL STYLES */
    .modal-content {
        background: transparent;
        border: none;
    }
    
    .modal-body {
        padding: 0;
        text-align: center;
    }
    
    .modal-body img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .modal-body .text-white {
        background: rgba(0, 0, 0, 0.7);
        padding: 15px;
        border-radius: 0 0 10px 10px;
        margin-top: -4px;
    }
    
    /* PAGINATION */
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }
    
    .page-link {
        color: #13547a;
        border: 2px solid #e0e0e0;
        margin: 0 5px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .page-item.active .page-link {
        background: #13547a;
        border-color: #13547a;
        color: white;
    }
    
    .page-link:hover {
        background: #f0f8ff;
        border-color: #13547a;
        color: #13547a;
        text-decoration: none;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    /* NO DATA MESSAGE */
    .no-data {
        text-align: center;
        padding: 50px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .no-data i {
        font-size: 3rem;
        color: #80d0c7;
        margin-bottom: 20px;
    }
    
    .no-data h4 {
        color: #13547a;
        margin-bottom: 15px;
    }
    
    .no-data p {
        color: #666;
        margin-bottom: 20px;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2.2rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header .subtitle {
            font-size: 1rem;
        }
        
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
            margin-right: 5px;
            margin-bottom: 8px;
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

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <!-- FILTER SECTION -->
        <div class="gallery-filter">
            <h4 class="mb-4" style="color: #13547a;">Filter Album</h4>
            <div class="filter-tags">
                <a href="?filter=all&page=1" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
                    Semua
                </a>
                <?php 
                if ($result_album && mysqli_num_rows($result_album) > 0):
                    while ($album = mysqli_fetch_assoc($result_album)): 
                ?>
                    <a href="?filter=<?php echo $album['id']; ?>&page=1" 
                       class="filter-btn <?php echo $filter == $album['id'] ? 'active' : ''; ?>">
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
        
        <!-- GALLERY GRID -->
        <div class="gallery-grid">
            <?php if ($result_foto && mysqli_num_rows($result_foto) > 0): ?>
                <?php while ($item = mysqli_fetch_assoc($result_foto)): 
                    $tanggal = date('d F Y', strtotime($item['tanggal_dibuat']));
                    
                    // Tentukan apakah ini album atau foto
                    $is_album = ($item['item_type'] ?? ($item['tipe'] ?? '')) == 'album';
                    
                    // Perbaiki path gambar - gambar ada di wb_admin/uploads/galeri/
                    $url_gambar = $item['url_gambar'];
                    
                    // Jika path tidak dimulai dengan http, tambahkan path ke wb_admin/uploads/galeri/
                    if (!preg_match('/^http/', $url_gambar)) {
                        // Hapus 'uploads/galeri/' dari database karena path di database salah
                        // Path di database: 'uploads/galeri/namafile.jpg'
                        // Path sebenarnya: 'wb_admin/uploads/galeri/namafile.jpg'
                        
                        // Ekstrak nama file saja
                        $filename = basename($url_gambar);
                        
                        // Buat path baru yang benar
                        $correct_path = '../wb_admin/uploads/galeri/' . $filename;
                        
                        // Cek apakah file ada di lokasi yang benar
                        if (file_exists($correct_path)) {
                            $url_gambar = $correct_path;
                        } else {
                            // Coba alternatif: langsung dari path database (tapi dengan ../wb_admin/)
                            $alternate_path = '../wb_admin/' . $url_gambar;
                            if (file_exists($alternate_path)) {
                                $url_gambar = $alternate_path;
                            } else {
                                // Fallback ke gambar placeholder
                                $url_gambar = $is_album ? 
                                    'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80' :
                                    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                            }
                        }
                    }
                    
                    // Tentukan modal target
                    if ($is_album) {
                        // Untuk album, klik akan filter album tersebut
                        $modal_target = '';
                        $onclick = "window.location.href='?filter=" . $item['id'] . "&page=1'";
                        $cursor_style = 'cursor: pointer;';
                    } else {
                        // Untuk foto, buka modal
                        $modal_target = 'data-bs-toggle="modal" data-bs-target="#imageModal' . $item['id'] . '"';
                        $onclick = '';
                        $cursor_style = '';
                    }
                ?>
                    <div class="gallery-item" 
                         data-category="<?php echo $item['parent_id']; ?>" 
                         <?php echo $modal_target; ?>
                         data-foto-id="<?php echo $item['id']; ?>"
                         onclick="<?php echo $onclick; ?>"
                         style="<?php echo $cursor_style; ?>">
                        
                        <!-- Badge untuk jenis item -->
                        <div class="item-badge <?php echo $is_album ? 'album' : 'foto'; ?>">
                            <?php echo $is_album ? 'ALBUM' : 'FOTO'; ?>
                        </div>
                        
                        <img src="<?php echo htmlspecialchars($url_gambar); ?>" 
                             alt="<?php echo htmlspecialchars($item['judul']); ?>"
                             onerror="this.onerror=null; this.src='<?php echo $is_album ? 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80' : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80'; ?>'">
                        <div class="gallery-overlay">
                            <h4 class="gallery-title"><?php echo htmlspecialchars($item['judul']); ?></h4>
                            <?php if (!$is_album && !empty($item['album_judul'])): ?>
                                <div class="gallery-category mb-2" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                                    <i class="bi bi-folder"></i> <?php echo htmlspecialchars($item['album_judul']); ?>
                                </div>
                            <?php elseif ($is_album): ?>
                                <div class="gallery-category mb-2" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                                    <i class="bi bi-images"></i> Album Foto
                                </div>
                            <?php endif; ?>
                            <div class="gallery-date">
                                <i class="bi bi-calendar"></i> <?php echo $tanggal; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-data">
                        <i class="bi bi-images"></i>
                        <h4>Tidak ada foto ditemukan</h4>
                        <p>Belum ada foto yang diunggah untuk album ini.</p>
                        <a href="?filter=all&page=1" class="filter-btn">Lihat Semua Foto</a>
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
                           href="?filter=<?php echo htmlspecialchars($filter); ?>&page=<?php echo max(1, $current_page - 1); ?>" 
                           aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
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
                            <a class="page-link" href="?filter=<?php echo htmlspecialchars($filter); ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Next Button -->
                    <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" 
                           href="?filter=<?php echo htmlspecialchars($filter); ?>&page=<?php echo min($total_pages, $current_page + 1); ?>" 
                           aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Page Info -->
            <div class="text-center text-muted mt-2">
                <?php 
                $start_item = ($current_page - 1) * $per_page + 1;
                $end_item = min($current_page * $per_page, $total_data);
                ?>
                Menampilkan <?php echo $start_item; ?> - <?php echo $end_item; ?> dari <?php echo $total_data; ?> item
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- IMAGE MODALS (hanya untuk foto, bukan album) -->
<?php if (!empty($all_fotos)): ?>
    <?php foreach ($all_fotos as $foto): 
        $tanggal = date('d F Y', strtotime($foto['tanggal_dibuat']));
        // Perbaiki path gambar untuk modal juga
        $url_gambar_modal = $foto['url_gambar'];
        
        if (!preg_match('/^http/', $url_gambar_modal)) {
            // Ekstrak nama file saja
            $filename = basename($url_gambar_modal);
            $correct_path = '../wb_admin/uploads/galeri/' . $filename;
            
            if (file_exists($correct_path)) {
                $url_gambar_modal = $correct_path;
            } else {
                $alternate_path = '../wb_admin/' . $url_gambar_modal;
                if (file_exists($alternate_path)) {
                    $url_gambar_modal = $alternate_path;
                } else {
                    $url_gambar_modal = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80';
                }
            }
        }
    ?>
        <div class="modal fade" id="imageModal<?php echo $foto['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="<?php echo htmlspecialchars($url_gambar_modal); ?>" 
                             alt="<?php echo htmlspecialchars($foto['judul']); ?>" 
                             class="img-fluid"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'">
                        <div class="mt-3 text-white">
                            <h5><?php echo htmlspecialchars($foto['judul']); ?></h5>
                            <?php if (!empty($foto['deskripsi'])): ?>
                                <p><?php echo htmlspecialchars($foto['deskripsi']); ?></p>
                            <?php endif; ?>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small><i class="bi bi-calendar"></i> <?php echo $tanggal; ?></small>
                                </div>
                                <?php if (!empty($foto['album_judul'])): ?>
                                    <div class="col-md-6 text-md-end">
                                        <small><i class="bi bi-folder"></i> <?php echo htmlspecialchars($foto['album_judul']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    // JavaScript khusus untuk halaman galeri
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery item click animation
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                // Hanya beri animasi jika bukan album (album sudah ada onclick sendiri)
                if (!this.querySelector('.item-badge.album')) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                }
            });
        });
        
        // Modal close animation
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                this.style.opacity = '0';
                setTimeout(() => {
                    this.style.opacity = '1';
                    this.style.transition = 'opacity 0.3s ease';
                }, 10);
            });
            
            modal.addEventListener('hide.bs.modal', function() {
                this.style.opacity = '0';
                this.style.transition = 'opacity 0.3s ease';
            });
        });
        
        // Keyboard navigation for gallery
        document.addEventListener('keydown', function(e) {
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) {
                if (e.key === 'Escape') {
                    const modalInstance = bootstrap.Modal.getInstance(activeModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
                
                // Navigate between photos using arrow keys
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const currentModalId = activeModal.id;
                    const currentId = parseInt(currentModalId.replace('imageModal', ''));
                    const fotoIds = <?php echo json_encode(array_column($all_fotos, 'id')); ?>;
                    const currentIndex = fotoIds.indexOf(currentId);
                    
                    let nextIndex;
                    if (e.key === 'ArrowLeft') {
                        nextIndex = currentIndex > 0 ? currentIndex - 1 : fotoIds.length - 1;
                    } else {
                        nextIndex = currentIndex < fotoIds.length - 1 ? currentIndex + 1 : 0;
                    }
                    
                    // Close current modal and open next one
                    const currentModalInstance = bootstrap.Modal.getInstance(activeModal);
                    if (currentModalInstance) {
                        currentModalInstance.hide();
                    }
                    
                    setTimeout(() => {
                        const nextModalId = 'imageModal' + fotoIds[nextIndex];
                        const nextModalElement = document.getElementById(nextModalId);
                        if (nextModalElement) {
                            const nextModal = new bootstrap.Modal(nextModalElement);
                            nextModal.show();
                        }
                    }, 300);
                }
            }
        });
        
        // Handle image loading errors
        const images = document.querySelectorAll('.gallery-item img, .modal-body img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                // Cek parent element untuk menentukan jenis
                const parent = this.closest('.gallery-item');
                const isAlbum = parent ? parent.querySelector('.item-badge.album') : false;
                
                if (this.classList.contains('img-fluid')) {
                    // Modal image error
                    this.src = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80';
                } else {
                    // Thumbnail image error
                    this.src = isAlbum ? 
                        'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80' :
                        'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                }
            });
        });
    });
</script>

<?php 
// Tutup koneksi database
mysqli_close($koneksi);
include 'footer.php'; 
?>