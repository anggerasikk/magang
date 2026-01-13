<?php
// buku.php
$pageTitle = 'Koleksi Buku - JDIH DPRD Kota Surakarta';
include 'header.php';
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
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Judul buku, penulis, atau kata kunci...">
                <button class="search-btn">
                    <i class="bi bi-search me-2"></i>CARI
                </button>
            </div>
        </div>
        
        <div class="row">
            <!-- FILTER SIDEBAR -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h5 class="filter-title">Filter Koleksi</h5>
                    
                    <div class="filter-category">
                        <h6>Kategori Buku</h6>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox" checked> Hukum Tata Negara
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Hukum Administrasi
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Hukum Pidana
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Hukum Perdata
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Hukum Internasional
                            </label>
                        </div>
                    </div>
                    
                    <div class="filter-category">
                        <h6>Tahun Terbit</h6>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> 2020 - 2024
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> 2015 - 2019
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> 2010 - 2014
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Sebelum 2010
                            </label>
                        </div>
                    </div>
                    
                    <div class="filter-category">
                        <h6>Status</h6>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox" checked> Tersedia
                            </label>
                        </div>
                        <div class="filter-option">
                            <label>
                                <input type="checkbox"> Dipinjam
                            </label>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary w-100 mt-3" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border: none;">
                        Terapkan Filter
                    </button>
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
                        <h2 class="section-title">Koleksi Buku Terbaru</h2>
                    </div>
                </div>
                
                <div class="book-grid">
                    <!-- Book 1 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Tata Negara" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Tata Negara</span>
                            <h4 class="book-title">Pengantar Ilmu Hukum Tata Negara Indonesia</h4>
                            <p class="book-author">Prof. Dr. Jimly Asshiddiqie, SH.</p>
                            <div class="book-meta">
                                <span class="book-year">2023</span>
                                <span class="book-status status-available">Tersedia</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=1" class="btn-detail">Detail</a>
                                <a href="?page=peminjaman&id=1" class="btn-borrow">Pinjam</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book 2 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1531346688376-ab6275c4725e?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Administrasi" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Administrasi</span>
                            <h4 class="book-title">Hukum Administrasi Negara di Indonesia</h4>
                            <p class="book-author">Dr. Indroharto, SH., M.Hum.</p>
                            <div class="book-meta">
                                <span class="book-year">2022</span>
                                <span class="book-status status-borrowed">Dipinjam</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=2" class="btn-detail">Detail</a>
                                <button class="btn-borrow" disabled>Dipinjam</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book 3 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Pidana" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Pidana</span>
                            <h4 class="book-title">KUHAP dan Teori Penyidikan Modern</h4>
                            <p class="book-author">Andi Hamzah, SH.</p>
                            <div class="book-meta">
                                <span class="book-year">2023</span>
                                <span class="book-status status-available">Tersedia</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=3" class="btn-detail">Detail</a>
                                <a href="?page=peminjaman&id=3" class="btn-borrow">Pinjam</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book 4 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Perdata" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Perdata</span>
                            <h4 class="book-title">Hukum Perdata Internasional</h4>
                            <p class="book-author">Prof. Dr. Sudargo Gautama</p>
                            <div class="book-meta">
                                <span class="book-year">2021</span>
                                <span class="book-status status-available">Tersedia</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=4" class="btn-detail">Detail</a>
                                <a href="?page=peminjaman&id=4" class="btn-borrow">Pinjam</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book 5 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Internasional" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Internasional</span>
                            <h4 class="book-title">Hukum Internasional Publik</h4>
                            <p class="book-author">Prof. Dr. Hikmahanto Juwana, S.H., LL.M.</p>
                            <div class="book-meta">
                                <span class="book-year">2023</span>
                                <span class="book-status status-available">Tersedia</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=5" class="btn-detail">Detail</a>
                                <a href="?page=peminjaman&id=5" class="btn-borrow">Pinjam</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book 6 -->
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Hukum Lingkungan" class="book-cover">
                        <div class="book-content">
                            <span class="book-category">Hukum Lingkungan</span>
                            <h4 class="book-title">Hukum Lingkungan dan Pengelolaan Sumber Daya Alam</h4>
                            <p class="book-author">Dr. Takdir Rahmadi, SH., LL.M.</p>
                            <div class="book-meta">
                                <span class="book-year">2022</span>
                                <span class="book-status status-borrowed">Dipinjam</span>
                            </div>
                            <div class="book-actions">
                                <a href="?page=detail-buku&id=6" class="btn-detail">Detail</a>
                                <button class="btn-borrow" disabled>Dipinjam</button>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                if (query) {
                    alert('Mencari buku: ' + query);
                    // Implement actual search logic here
                    // window.location.href = '?page=search-buku&q=' + encodeURIComponent(query);
                } else {
                    alert('Silakan masukkan kata kunci pencarian');
                }
            }
        }
        
        // Filter functionality
        const filterBtn = document.querySelector('.filter-sidebar .btn-primary');
        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                alert('Filter diterapkan!');
                // Implement filter logic here
            });
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
    });
</script>

<?php include 'footer.php'; ?>