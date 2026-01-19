    <style>
        <?php include "style-keputusan-dprd.css"; ?>
    </style>
    <?php include 'header.php'; ?>


    <!-- HEADER SECTION -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-white">MONOGRAFI HUKUM</h1>
                    <p class="subtitle">Hasil Harmonisasi Raperda Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Hasil Harmonisasi Raperda</li>
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
                    
                    <!-- HEADER MONOGRAFI -->
                    <div class="year-filter">
                        <h5 class="section-title">Hasil Harmonisasi Raperda</h5>
                        <p class="mb-3">
                            Dokumen hasil harmonisasi Rancangan Peraturan Daerah (Raperda) dengan peraturan 
                            perundang-undangan yang lebih tinggi dan peraturan daerah lainnya, termasuk 
                            analisis kesesuaian, catatan ketidaksesuaian, dan rekomendasi penyesuaian.
                        </p>
                        
                        <!-- PENCARIAN -->
                        <div class="search-monografi">
                            <h5 class="mb-3">Cari Hasil Harmonisasi Raperda</h5>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci hasil harmonisasi..." 
                                           aria-label="Search" aria-describedby="button-search">
                                    <button class="btn btn-view" type="submit" id="button-search">
                                        <i class="bi bi-search"></i> CARI
                                    </button>
                                </div>
                                <div class="text-end mt-2">
                                    <a href="#" class="adv-search-link text-decoration-none">
                                        <i class="bi bi-sliders"></i> Pencarian Lanjutan
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- STATISTIK -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2">78</div>
                                    <div class="card-title">Total Harmonisasi</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-primary">8</div>
                                    <div class="card-title">Tahun 2024</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-success">62</div>
                                    <div class="card-title">Telah Sesuai</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-warning">16</div>
                                    <div class="card-title">Perlu Revisi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FILTER TAHUN -->
                    <div class="year-filter mb-4">
                        <h5 class="section-title">Filter Berdasarkan Tahun</h5>
                        <div class="year-buttons">
                            <button class="year-btn active">Semua</button>
                            <button class="year-btn">2024</button>
                            <button class="year-btn">2023</button>
                            <button class="year-btn">2022</button>
                            <button class="year-btn">2021</button>
                            <button class="year-btn">2020</button>
                        </div>
                    </div>
                    
                    <!-- DAFTAR DOKUMEN -->
                    <h3 class="section-title">Hasil Harmonisasi Terbaru</h3>
                    
                    <div class="card-container">
                        <!-- Dokumen 1 -->
                        <div class="document-card">
                            <div class="card-image">
                                <div class="placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <p>HASIL HARMONISASI</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge">HARMONIS</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">HH/DPRD/01/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Hasil Harmonisasi Raperda Pariwisata Berkelanjutan dengan UU No. 10 Tahun 2009 tentang Kepariwisataan</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 25 Jan 2024
                                    </div>
                                    <div class="card-status">
                                        Harmonis
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Analisis harmonisasi Raperda Pariwisata Berkelanjutan dengan UU Kepariwisataan dan Perda terkait lainnya, hasil: 95% harmonis...
                                </p>
                                <div class="card-actions">
                                    <a href="#" class="card-btn btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="#" class="card-btn btn-download">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dokumen 2 -->
                        <div class="document-card">
                            <div class="card-image">
                                <div class="placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <p>HASIL HARMONISASI</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-warning">PERLU REVISI</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">HH/DPRD/02/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Analisis Harmonisasi Raperda Pengelolaan Sampah dengan PP No. 81 Tahun 2012 tentang Pengelolaan Sampah Rumah Tangga</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 20 Jan 2024
                                    </div>
                                    <div class="card-status bg-warning text-dark">
                                        Perlu Revisi
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Hasil harmonisasi menunjukkan 3 pasal perlu revisi untuk disesuaikan dengan ketentuan dalam PP Pengelolaan Sampah Rumah Tangga...
                                </p>
                                <div class="card-actions">
                                    <a href="#" class="card-btn btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="#" class="card-btn btn-download">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dokumen 3 -->
                        <div class="document-card">
                            <div class="card-image">
                                <div class="placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <p>HASIL HARMONISASI</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge">HARMONIS</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">HH/DPRD/12/2023</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Hasil Harmonisasi Raperda Digitalisasi Pelayanan Publik dengan UU No. 11 Tahun 2008 tentang ITE</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 18 Des 2023
                                    </div>
                                    <div class="card-status">
                                        Harmonis
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Analisis menunjukkan Raperda Digitalisasi Pelayanan Publik telah harmonis dengan UU ITE dan regulasi terkait lainnya...
                                </p>
                                <div class="card-actions">
                                    <a href="#" class="card-btn btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="#" class="card-btn btn-download">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dokumen 4 -->
                        <div class="document-card">
                            <div class="card-image">
                                <div class="placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <p>HASIL HARMONISASI</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-danger">KONFLIK HUKUM</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">HH/DPRD/11/2023</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Analisis Harmonisasi Raperda Penataan PKL dengan Perda No. 5 Tahun 2015 tentang Ketertiban Umum</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 10 Des 2023
                                    </div>
                                    <div class="card-status bg-danger">
                                        Konflik Hukum
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Ditemukan potensi konflik hukum dengan Perda Ketertiban Umum pada 2 pasal terkait kewenangan penertiban dan sanksi administratif...
                                </p>
                                <div class="card-actions">
                                    <a href="#" class="card-btn btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="#" class="card-btn btn-download">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                    <!-- TINGKAT HARMONISASI -->
                    <div class="year-filter mt-5">
                        <h4 class="section-title"><i class="bi bi-bar-chart text-primary me-2"></i>Tingkat Harmonisasi</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <span class="fs-1 fw-bold text-success">95%</span>
                                    </div>
                                    <h5>Tinggi</h5>
                                    <p class="small">Harmonis dengan semua regulasi</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <span class="fs-1 fw-bold text-warning">75%</span>
                                    </div>
                                    <h5>Sedang</h5>
                                    <p class="small">Perlu minor adjustment</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <span class="fs-1 fw-bold text-danger">50%</span>
                                    </div>
                                    <h5>Rendah</h5>
                                    <p class="small">Perlu revisi signifikan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU MONOGRAFI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-collection"></i> Menu Monografi Hukum</h4>
                        <ul>
                            <li><a href="?page=pokok-pikiran-dprd"><i class="bi bi-lightbulb"></i> Pokok Pikiran DPRD</a></li>
                            <li><a href="?page=kajian-raperda"><i class="bi bi-search"></i> Kajian Raperda</a></li>
                            <li><a href="?page=naskah-akademik"><i class="bi bi-journal-text"></i> Naskah Akademik</a></li>
                            <li><a href="?page=hasil-fasilitasi"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                            <li><a href="?page=risalah"><i class="bi bi-chat-text"></i> Risalah</a></li>
                            <li><a href="?page=hasil-harmonisasi" class="active"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER STATUS HARMONISASI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-activity"></i> Filter Berdasarkan Status</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-circle-fill text-success me-2"></i>
                                    Harmonis
                                </span>
                                <span class="badge bg-success rounded-pill">62</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-circle-fill text-warning me-2"></i>
                                    Perlu Revisi
                                </span>
                                <span class="badge bg-warning rounded-pill">12</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-circle-fill text-danger me-2"></i>
                                    Konflik Hukum
                                </span>
                                <span class="badge bg-danger rounded-pill">4</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER JENIS PERATURAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-journals"></i> Filter Berdasarkan Jenis</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Harmonisasi dengan UU
                                <span class="badge bg-primary rounded-pill">45</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Harmonisasi dengan PP
                                <span class="badge bg-primary rounded-pill">38</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Harmonisasi dengan Perda Lain
                                <span class="badge bg-primary rounded-pill">52</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Harmonisasi dengan Peraturan Walikota
                                <span class="badge bg-primary rounded-pill">28</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- PANDUAN HARMONISASI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-book"></i> Panduan Harmonisasi</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Pedoman Teknis Harmonisasi (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Analisis (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Metodologi (PPT)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Contoh Lengkap (ZIP)</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>