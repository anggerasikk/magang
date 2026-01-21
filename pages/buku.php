<?php
// buku.php
require_once 'a_db.php'; // Include koneksi database

$pageTitle = 'Koleksi Buku - JDIH DPRD Kota Surakarta';
include 'header.php';

// Query untuk mengambil data buku
$query = "SELECT * FROM buku_perpustakaan ORDER BY created_at DESC LIMIT 12";
$result = $koneksi->query($query);

// Handle search
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $koneksi->real_escape_string($_GET['search']);
    $search_query = " WHERE judul LIKE '%$search%' OR pengarang LIKE '%$search%' OR subjek_topik LIKE '%$search%'";
}

// Handle filters
$filter_conditions = [];
if (isset($_GET['bidang_hukum']) && !empty($_GET['bidang_hukum'])) {
    $bidang_hukum = $koneksi->real_escape_string($_GET['bidang_hukum']);
    $filter_conditions[] = "bidang_hukum = '$bidang_hukum'";
}

if (isset($_GET['tahun']) && !empty($_GET['tahun'])) {
    $tahun = $koneksi->real_escape_string($_GET['tahun']);
    $filter_conditions[] = "tahun_terbit = '$tahun'";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $koneksi->real_escape_string($_GET['status']);
    if ($status == 'tersedia') {
        $filter_conditions[] = "status_ketersediaan = 'tersedia' AND jumlah_tersedia > 0";
    } elseif ($status == 'dipinjam') {
        $filter_conditions[] = "status_ketersediaan = 'dipinjam' OR jumlah_tersedia = 0";
    }
}

// Build final query
$where_clause = "";
if (!empty($search_query)) {
    $where_clause = $search_query;
}

if (!empty($filter_conditions)) {
    if (empty($where_clause)) {
        $where_clause = " WHERE " . implode(" AND ", $filter_conditions);
    } else {
        $where_clause .= " AND " . implode(" AND ", $filter_conditions);
    }
}

$final_query = "SELECT * FROM buku_perpustakaan" . $where_clause . " ORDER BY created_at DESC";
$final_result = $koneksi->query($final_query);

// Get unique years and bidang_hukum for filters
$years_query = "SELECT DISTINCT tahun_terbit FROM buku_perpustakaan ORDER BY tahun_terbit DESC";
$years_result = $koneksi->query($years_query);

$bidang_query = "SELECT DISTINCT bidang_hukum FROM buku_perpustakaan WHERE bidang_hukum IS NOT NULL AND bidang_hukum != ''";
$bidang_result = $koneksi->query($bidang_query);
?>

<style>
    /* CSS KHUSUS UNTUK HALAMAN BUKU */
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
    
    /* SEARCH SECTION */
    .search-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
    }
    
    .search-box {
        display: flex;
        gap: 10px;
    }
    
    .search-input {
        flex: 1;
        height: 50px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 0 20px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #13547a;
        box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.1);
    }
    
    .search-btn {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0 30px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    
    /* FILTER SIDEBAR */
    .filter-sidebar {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .filter-title {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .filter-category {
        margin-bottom: 25px;
    }
    
    .filter-category h6 {
        color: #13547a;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .filter-option {
        margin-bottom: 10px;
    }
    
    .filter-option label {
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    
    .filter-option input {
        margin-right: 10px;
    }
    
    /* BOOK GRID */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 30px;
    }
    
    .book-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .book-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .book-cover {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .book-content {
        padding: 20px;
    }
    
    .book-category {
        display: inline-block;
        background: #f0f8ff;
        color: #13547a;
        padding: 3px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .book-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        font-size: 1.1rem;
        line-height: 1.4;
        margin-bottom: 10px;
        height: 60px;
        overflow: hidden;
    }
    
    .book-author {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .book-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .book-year {
        color: #80d0c7;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .book-status {
        padding: 3px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-available {
        background: #d4edda;
        color: #155724;
    }
    
    .status-borrowed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .book-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-detail, .btn-borrow {
        flex: 1;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-detail {
        background: #f8f9fa;
        border: 2px solid #13547a;
        color: #13547a;
    }
    
    .btn-detail:hover {
        background: #13547a;
        color: white;
    }
    
    .btn-borrow {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        border: 2px solid transparent;
        color: white;
    }
    
    .btn-borrow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    
    .btn-borrow:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    /* PAGINATION */
    .pagination {
        justify-content: center;
        margin-top: 50px;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2.2rem;
        }
        
        .search-box {
            flex-direction: column;
        }
        
        .search-btn {
            width: 100%;
            height: 50px;
        }
        
        .book-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
        
        .book-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #80d0c7;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        color: #13547a;
        margin-bottom: 15px;
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Koleksi Buku Perpustakaan</h1>
                <p class="subtitle">
                    Koleksi lengkap buku hukum dan literatur pendukung di Perpustakaan JDIH DPRD Kota Surakarta
                </p>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <!-- SEARCH SECTION -->
        <div class="search-section">
            <h4 class="mb-4" style="color: #13547a;">Cari Buku</h4>
            <form method="GET" action="" class="search-box">
                <input type="hidden" name="page" value="buku">
                <input type="text" name="search" class="search-input" 
                       placeholder="Judul buku, penulis, atau kata kunci..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="search-btn">
                    <i class="bi bi-search me-2"></i>CARI
                </button>
            </form>
        </div>
        
        <div class="row">
            <!-- FILTER SIDEBAR -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h5 class="filter-title">Filter Koleksi</h5>
                    <form method="GET" action="" id="filterForm">
                        <input type="hidden" name="page" value="buku">
                        <?php if(isset($_GET['search'])): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                        <?php endif; ?>
                        
                        <div class="filter-category">
                            <h6>Bidang Hukum</h6>
                            <div class="filter-option">
                                <label>
                                    <input type="radio" name="bidang_hukum" value="" 
                                           <?php echo !isset($_GET['bidang_hukum']) ? 'checked' : ''; ?>
                                           onchange="document.getElementById('filterForm').submit()">
                                    Semua Bidang
                                </label>
                            </div>
                            <?php if($bidang_result && $bidang_result->num_rows > 0): ?>
                                <?php while($bidang = $bidang_result->fetch_assoc()): ?>
                                    <?php if(!empty($bidang['bidang_hukum'])): ?>
                                        <div class="filter-option">
                                            <label>
                                                <input type="radio" name="bidang_hukum" value="<?php echo $bidang['bidang_hukum']; ?>"
                                                       <?php echo (isset($_GET['bidang_hukum']) && $_GET['bidang_hukum'] == $bidang['bidang_hukum']) ? 'checked' : ''; ?>
                                                       onchange="document.getElementById('filterForm').submit()">
                                                <?php echo $bidang['bidang_hukum']; ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                                <?php $bidang_result->data_seek(0); ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="filter-category">
                            <h6>Tahun Terbit</h6>
                            <div class="filter-option">
                                <label>
                                    <input type="radio" name="tahun" value="" 
                                           <?php echo !isset($_GET['tahun']) ? 'checked' : ''; ?>
                                           onchange="document.getElementById('filterForm').submit()">
                                    Semua Tahun
                                </label>
                            </div>
                            <?php if($years_result && $years_result->num_rows > 0): ?>
                                <?php while($year = $years_result->fetch_assoc()): ?>
                                    <?php if(!empty($year['tahun_terbit'])): ?>
                                        <div class="filter-option">
                                            <label>
                                                <input type="radio" name="tahun" value="<?php echo $year['tahun_terbit']; ?>"
                                                       <?php echo (isset($_GET['tahun']) && $_GET['tahun'] == $year['tahun_terbit']) ? 'checked' : ''; ?>
                                                       onchange="document.getElementById('filterForm').submit()">
                                                <?php echo $year['tahun_terbit']; ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                                <?php $years_result->data_seek(0); ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="filter-category">
                            <h6>Status</h6>
                            <div class="filter-option">
                                <label>
                                    <input type="radio" name="status" value="" 
                                           <?php echo !isset($_GET['status']) ? 'checked' : ''; ?>
                                           onchange="document.getElementById('filterForm').submit()">
                                    Semua Status
                                </label>
                            </div>
                            <div class="filter-option">
                                <label>
                                    <input type="radio" name="status" value="tersedia"
                                           <?php echo (isset($_GET['status']) && $_GET['status'] == 'tersedia') ? 'checked' : ''; ?>
                                           onchange="document.getElementById('filterForm').submit()">
                                    Tersedia
                                </label>
                            </div>
                            <div class="filter-option">
                                <label>
                                    <input type="radio" name="status" value="dipinjam"
                                           <?php echo (isset($_GET['status']) && $_GET['status'] == 'dipinjam') ? 'checked' : ''; ?>
                                           onchange="document.getElementById('filterForm').submit()">
                                    Dipinjam
                                </label>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-secondary w-100 mt-3" onclick="resetFilters()">
                            Reset Filter
                        </button>
                    </form>
                </div>
                
                <!-- LIBRARY INFO -->
                <div class="filter-sidebar">
                    <h5 class="filter-title">Informasi Perpustakaan</h5>
                    <p style="color: #666; font-size: 0.95rem;">
                        <strong>Jam Operasional:</strong><br>
                        Senin - Jumat: 08.00 - 16.00 WIB
                    </p>
                    <p style="color: #666; font-size: 0.95rem;">
                        <strong>Syarat Peminjaman:</strong><br>
                        Kartu anggota perpustakaan dan KTP
                    </p>
                    <p style="color: #666; font-size: 0.95rem;">
                        <strong>Masa Pinjam:</strong><br>
                        7 hari kerja (dapat diperpanjang)
                    </p>
                </div>
            </div>
            
            <!-- BOOK COLLECTION -->
            <div class="col-lg-9">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="section-title">Koleksi Buku 
                            <?php if(isset($_GET['search'])): ?>
                                <small class="text-muted">Hasil pencarian: "<?php echo htmlspecialchars($_GET['search']); ?>"</small>
                            <?php endif; ?>
                        </h2>
                    </div>
                </div>
                
                <?php if($final_result && $final_result->num_rows > 0): ?>
                    <div class="book-grid">
                        <?php while($buku = $final_result->fetch_assoc()): ?>
                            <?php
                            // Tentukan status ketersediaan
                            $status = ($buku['status_ketersediaan'] == 'tersedia' && $buku['jumlah_tersedia'] > 0) ? 'tersedia' : 'dipinjam';
                            $status_class = ($status == 'tersedia') ? 'status-available' : 'status-borrowed';
                            $status_text = ($status == 'tersedia') ? 'Tersedia' : 'Dipinjam';
                            
                            // Gambar sampul (gunakan default jika tidak ada)
                            $cover_image = !empty($buku['sampul_path']) && file_exists($buku['sampul_path']) 
                                ? $buku['sampul_path'] 
                                : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                            
                            // Truncate judul jika terlalu panjang
                            $judul = strlen($buku['judul']) > 60 ? substr($buku['judul'], 0, 60) . '...' : $buku['judul'];
                            ?>
                            
                            <div class="book-card">
                                <img src="<?php echo $cover_image; ?>" 
                                     alt="<?php echo htmlspecialchars($buku['judul']); ?>" 
                                     class="book-cover">
                                <div class="book-content">
                                    <?php if(!empty($buku['bidang_hukum'])): ?>
                                        <span class="book-category"><?php echo $buku['bidang_hukum']; ?></span>
                                    <?php endif; ?>
                                    
                                    <h4 class="book-title" title="<?php echo htmlspecialchars($buku['judul']); ?>">
                                        <?php echo htmlspecialchars($judul); ?>
                                    </h4>
                                    
                                    <?php if(!empty($buku['pengarang'])): ?>
                                        <p class="book-author"><?php echo htmlspecialchars($buku['pengarang']); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="book-meta">
                                        <?php if(!empty($buku['tahun_terbit'])): ?>
                                            <span class="book-year"><?php echo $buku['tahun_terbit']; ?></span>
                                        <?php endif; ?>
                                        <span class="book-status <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="book-actions">
                                        <a href="?page=detail-buku&id=<?php echo $buku['id_buku']; ?>" class="btn-detail">Detail</a>
                                        
                                        <?php if($status == 'tersedia'): ?>
                                            <a href="?page=peminjaman&id=<?php echo $buku['id_buku']; ?>" class="btn-borrow">Pinjam</a>
                                        <?php else: ?>
                                            <button class="btn-borrow" disabled>Dipinjam</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <!-- Empty state -->
                    <div class="empty-state">
                        <i class="bi bi-book"></i>
                        <h4>Buku Tidak Ditemukan</h4>
                        <p>Tidak ada buku yang sesuai dengan kriteria pencarian/filter Anda.</p>
                        <a href="?page=buku" class="btn btn-primary" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border: none;">
                            Lihat Semua Buku
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- PAGINATION -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
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
        </div>
    </div>
</section>

<script>
    // JavaScript khusus untuk halaman buku
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchBtn = document.querySelector('.search-btn');
        const searchInput = document.querySelector('.search-input');
        
        if (searchBtn && searchInput) {
            searchBtn.addEventListener('click', function() {
                performSearch();
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                const query = searchInput.value.trim();
                if (!query) {
                    alert('Silakan masukkan kata kunci pencarian');
                    return false;
                }
                return true;
            }
        }
        
        // Book card hover effect
        const bookCards = document.querySelectorAll('.book-card');
        bookCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Reset filters function
        window.resetFilters = function() {
            window.location.href = '?page=buku';
        }
    });
</script>

<?php 
// Tutup koneksi database
if(isset($koneksi)) {
    $koneksi->close();
}
include 'footer.php'; 
?>