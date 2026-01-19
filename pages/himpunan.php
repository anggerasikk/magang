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
                    <p class="subtitle">Himpunan Peraturan Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Himpunan Peraturan</li>
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
                    <div class="document-card">
                        <div class="card-content">
                            <div class="text-center mb-4">
                                <div class="monografi-icon" style="width: 80px; height: 80px; background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <h2 class="section-title">Himpunan Peraturan</h2>
                                <p class="text-muted">
                                    Kumpulan kompilasi peraturan perundang-undangan yang telah dikelompokkan 
                                    berdasarkan tema atau bidang tertentu untuk memudahkan akses dan penelitian 
                                    hukum di Kota Surakarta.
                                </p>
                            </div>
                            
                            <!-- PENCARIAN -->
                            <div class="year-filter mb-4">
                                <h4 class="mb-4">Cari Himpunan Peraturan</h4>
                                <form class="search-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Masukkan kata kunci himpunan peraturan..." 
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
                            
                            <!-- STATISTIK -->
                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2">24</div>
                                            <div class="card-title">Total Himpunan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-primary">3</div>
                                            <div class="card-title">Tahun 2024</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-success">856</div>
                                            <div class="card-title">Total Peraturan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-warning">18</div>
                                            <div class="card-title">Bidang Tema</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- DAFTAR HIMPUNAN -->
                            <h3 class="section-title">Himpunan Peraturan Terbaru</h3>
                            
                            <div class="card-container">
                                <!-- Himpunan 1 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-building"></i>
                                            <p>PEMERINTAHAN</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-primary">142 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/GOV/01/2024</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Pemerintahan</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> Januari 2024
                                            </div>
                                            <div class="card-status bg-primary">
                                                PDF, DOC
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kumpulan peraturan terkait pemerintahan daerah, organisasi perangkat daerah, 
                                            tata laksana, dan pelayanan publik di Kota Surakarta.
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
                                
                                <!-- Himpunan 2 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-cash-coin"></i>
                                            <p>KEUANGAN</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-success">98 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/FIN/02/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Keuangan & APBD</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> Desember 2023
                                            </div>
                                            <div class="card-status bg-success">
                                                PDF, XLS
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kompilasi peraturan tentang pengelolaan keuangan daerah, APBD, pajak daerah, 
                                            retribusi, dan pengelolaan aset daerah.
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
                                
                                <!-- Himpunan 3 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-tree"></i>
                                            <p>LINGKUNGAN</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-info">76 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/ENV/03/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Lingkungan Hidup</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> November 2023
                                            </div>
                                            <div class="card-status bg-info">
                                                PDF
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kumpulan peraturan tentang pengelolaan lingkungan, AMDAL, pengendalian pencemaran, 
                                            pengelolaan sampah, dan ruang terbuka hijau.
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
                                
                                <!-- Himpunan 4 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-house"></i>
                                            <p>TATA RUANG</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-warning">64 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/SPA/04/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Tata Ruang & Bangunan</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> Oktober 2023
                                            </div>
                                            <div class="card-status bg-warning text-dark">
                                                PDF, DWG
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kompilasi peraturan tentang rencana tata ruang wilayah, IMB, bangunan gedung, 
                                            perumahan, dan kawasan permukiman.
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
                                
                                <!-- Himpunan 5 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-shop"></i>
                                            <p>PERDAGANGAN</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-primary">58 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/TRD/05/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Perdagangan & UMKM</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> September 2023
                                            </div>
                                            <div class="card-status bg-primary">
                                                PDF, DOC
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kumpulan peraturan tentang perdagangan, pasar tradisional, UMKM, industri kreatif, 
                                            dan perlindungan konsumen.
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
                                
                                <!-- Himpunan 6 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-heart-pulse"></i>
                                            <p>KESEHATAN</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-success">72 Peraturan</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">HP/HLT/06/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Himpunan Peraturan Bidang Kesehatan & Sosial</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> Agustus 2023
                                            </div>
                                            <div class="card-status bg-success">
                                                PDF
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Kompilasi peraturan tentang pelayanan kesehatan, jaminan sosial, perlindungan anak, 
                                            penyandang disabilitas, dan kesejahteraan sosial.
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
                            
                            <!-- KEUNGGULAN HIMPUNAN -->
                            <div class="year-filter mt-5">
                                <h4 class="section-title"><i class="bi bi-star text-primary me-2"></i>Keunggulan Himpunan Peraturan</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-search fs-1 text-primary"></i>
                                            </div>
                                            <h5>Mudah Dicari</h5>
                                            <p class="small">Dikelompokkan berdasarkan tema</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-clock-history fs-1 text-primary"></i>
                                            </div>
                                            <h5>Selalu Update</h5>
                                            <p class="small">Diperbarui secara berkala</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-download fs-1 text-primary"></i>
                                            </div>
                                            <h5>Download Lengkap</h5>
                                            <p class="small">Format lengkap dalam satu file</p>
                                        </div>
                                    </div>
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
                            <li><a href="?page=hasil-harmonisasi"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan" class="active"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER BIDANG -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-filter"></i> Filter Berdasarkan Bidang</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Pemerintahan
                                <span class="badge bg-primary rounded-pill">142</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Keuangan & APBD
                                <span class="badge bg-primary rounded-pill">98</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Lingkungan Hidup
                                <span class="badge bg-primary rounded-pill">76</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Tata Ruang & Bangunan
                                <span class="badge bg-primary rounded-pill">64</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Perdagangan & UMKM
                                <span class="badge bg-primary rounded-pill">58</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Kesehatan & Sosial
                                <span class="badge bg-primary rounded-pill">72</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD TERPOPULER -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Terpopuler</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Himpunan Lengkap 2023 (ZIP)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Himpunan Pemerintahan 2024 (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Himpunan Keuangan (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-excel"></i> Daftar Peraturan Lengkap (XLS)</a></li>
                        </ul>
                    </div>
                    
                    <!-- INFORMASI PENTING -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian</h6>
                            <p class="small mb-2">Himpunan peraturan ini:</p>
                            <ul class="small mb-0 ps-3">
                                <li>Diupdate setiap 6 bulan</li>
                                <li>Berisi peraturan yang masih berlaku</li>
                                <li>Sudah melalui proses verifikasi</li>
                                <li>Dapat dijadikan referensi hukum</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- BANTUAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-question-circle"></i> Bantuan & Panduan</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-search"></i> Cara Mencari Peraturan</a></li>
                            <li><a href="#"><i class="bi bi-download"></i> Panduan Download</a></li>
                            <li><a href="#"><i class="bi bi-printer"></i> Cetak Himpunan</a></li>
                            <li><a href="#"><i class="bi bi-envelope"></i> Permohonan Himpunan</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

<script>
    // Fungsi pencarian
    document.querySelector('.search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const keyword = this.querySelector('input[type="text"]').value;
        
        if (!keyword.trim()) {
            alert('Masukkan kata kunci pencarian!');
            return;
        }
        
        alert(`Mencari himpunan peraturan dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
    });
    
    // Fungsi filter
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const bidang = this.textContent.split(' ')[0];
            const count = this.querySelector('.badge').textContent;
            
            alert(`Memfilter himpunan peraturan bidang: ${bidang}\n\nJumlah peraturan: ${count} peraturan\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
        });
    });
    
    // Fungsi view peraturan
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const collectionTitle = this.closest('.document-card').querySelector('.card-title').textContent;
            
            alert(`Melihat daftar peraturan dalam himpunan: "${collectionTitle}"\n\nDalam implementasi real, akan membuka halaman daftar peraturan.`);
        });
    });
    
    // Fungsi download himpunan
    document.querySelectorAll('.btn-download').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const collectionTitle = this.closest('.document-card').querySelector('.card-title').textContent;
            const collectionCount = this.closest('.document-card').querySelector('.image-badge').textContent;
            
            alert(`Mengunduh himpunan peraturan: "${collectionTitle}"\n\n${collectionCount}\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
            
            // Simulasi download
            setTimeout(() => {
                alert('Download berhasil!');
            }, 1000);
        });
    });
    
    // Highlight menu aktif di sidebar
    document.querySelectorAll('.sidebar-menu ul li a').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
            link.parentElement.classList.add('active');
        }
    });
</script>

<?php include 'footer.php'; ?>