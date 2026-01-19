<?php include 'header.php'; ?>
    <style>
        <?php include "style-keputusan-dprd.css"; ?>
    </style>


    <!-- HEADER SECTION -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-white">MONOGRAFI HUKUM</h1>
                    <p class="subtitle">Penulisan Karya Ilmiah Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Penulisan Karya Ilmiah</li>
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
                        <h5 class="section-title">Penulisan Karya Ilmiah</h5>
                        <p class="mb-3">
                            Kumpulan karya ilmiah yang dihasilkan oleh anggota DPRD, staf ahli, akademisi, 
                            dan peneliti terkait dengan aspek hukum, legislasi, dan pemerintahan daerah 
                            Kota Surakarta.
                        </p>
                        
                        <!-- PENCARIAN -->
                        <div class="search-monografi">
                            <h5 class="mb-3">Cari Karya Ilmiah</h5>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci karya ilmiah..." 
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
                                    <div class="card-number fs-2">156</div>
                                    <div class="card-title">Total Karya</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-primary">12</div>
                                    <div class="card-title">Tahun 2024</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-success">45</div>
                                    <div class="card-title">Jurnal Nasional</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="document-card">
                                <div class="card-content text-center">
                                    <div class="card-number fs-2 text-warning">68</div>
                                    <div class="card-title">Skripsi & Tesis</div>
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
                    <h3 class="section-title">Karya Ilmiah Terbaru</h3>
                    
                    <div class="card-container">
                        <!-- Dokumen 1 -->
                        <div class="document-card">
                            <div class="card-image">
                                <div class="placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <p>JURNAL NASIONAL</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-success">JURNAL</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">JJL/01/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Analisis Efektivitas Pengawasan DPRD Kota Surakarta terhadap Pelaksanaan APBD 2023</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 30 Jan 2024
                                    </div>
                                    <div class="card-status bg-success">
                                        Jurnal Nasional
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Penelitian tentang efektivitas mekanisme pengawasan DPRD terhadap pelaksanaan APBD dengan pendekatan mixed methods dan analisis regresi...
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
                                    <p>TESIS</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-warning">TESIS</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">TES/UNS/02/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Implementasi Prinsip Good Governance dalam Proses Legislasi di DPRD Kota Surakarta</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 25 Jan 2024
                                    </div>
                                    <div class="card-status bg-warning text-dark">
                                        Tesis
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Tesis magister yang mengkaji implementasi prinsip transparansi, akuntabilitas, dan partisipasi dalam proses pembentukan peraturan daerah...
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
                                    <p>SKRIPSI</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-info">SKRIPSI</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">SKR/UGM/03/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Peran DPRD dalam Perlindungan Hak-hak Penyandang Disabilitas di Kota Surakarta</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 20 Jan 2024
                                    </div>
                                    <div class="card-status bg-info">
                                        Skripsi
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Skripsi sarjana yang menganalisis peran legislatif dalam mengimplementasikan konvensi hak penyandang disabilitas melalui produk hukum daerah...
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
                                    <p>ARTIKEL ILMIAH</p>
                                </div>
                                <div class="image-overlay">
                                    <span class="image-badge bg-primary">ARTIKEL</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-number">ART/ICGSS/04/2024</div>
                                <h3 class="card-title">
                                    <a href="#" class="text-decoration-none text-dark">Digitalisasi Proses Legislasi: Peluang dan Tantangan bagi DPRD Kota Surakarta</a>
                                </h3>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i class="bi bi-calendar"></i> 15 Jan 2024
                                    </div>
                                    <div class="card-status bg-primary">
                                        Artikel Ilmiah
                                    </div>
                                </div>
                                <p class="mt-2 small text-muted">
                                    Artikel ilmiah yang membahas implementasi teknologi dalam proses legislasi dan dampaknya terhadap efisiensi dan partisipasi publik...
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
                    
                    <!-- KATEGORI KARYA ILMIAH -->
                    <div class="year-filter mt-5">
                        <h4 class="section-title"><i class="bi bi-diagram-3 text-primary me-2"></i>Kategori Karya Ilmiah</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <i class="bi bi-journal fs-1 text-primary"></i>
                                    </div>
                                    <h5>Jurnal</h5>
                                    <p class="small">Artikel jurnal nasional & internasional</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <i class="bi bi-book fs-1 text-primary"></i>
                                    </div>
                                    <h5>Skripsi</h5>
                                    <p class="small">Karya ilmiah strata 1</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <i class="bi bi-journals fs-1 text-primary"></i>
                                    </div>
                                    <h5>Tesis</h5>
                                    <p class="small">Karya ilmiah strata 2</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <div class="mb-3">
                                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                                    </div>
                                    <h5>Artikel</h5>
                                    <p class="small">Makalah konferensi & prosiding</p>
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
                            <li><a href="?page=hasil-harmonisasi"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah" class="active"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER JENIS KARYA -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-diagram-3"></i> Filter Berdasarkan Jenis</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-journal text-success me-2"></i>
                                    Jurnal
                                </span>
                                <span class="badge bg-success rounded-pill">45</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-book text-info me-2"></i>
                                    Skripsi
                                </span>
                                <span class="badge bg-info rounded-pill">52</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-journals text-warning me-2"></i>
                                    Tesis
                                </span>
                                <span class="badge bg-warning rounded-pill">16</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                    Artikel Ilmiah
                                </span>
                                <span class="badge bg-primary rounded-pill">43</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER INSTITUSI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-building"></i> Filter Berdasarkan Institusi</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Sebelas Maret
                                <span class="badge bg-primary rounded-pill">68</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Gadjah Mada
                                <span class="badge bg-primary rounded-pill">32</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Universitas Indonesia
                                <span class="badge bg-primary rounded-pill">18</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                DPRD Kota Surakarta
                                <span class="badge bg-primary rounded-pill">24</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Lembaga Penelitian Lain
                                <span class="badge bg-primary rounded-pill">14</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- PANDUAN PENULISAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-book"></i> Panduan & Template</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Penulisan (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Pedoman Sitasi (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Metodologi (PPT)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Contoh Karya Lengkap (ZIP)</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>