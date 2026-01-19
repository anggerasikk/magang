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
                    <p class="subtitle">Yurisprudensi Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Monografi Hukum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Yurisprudensi</li>
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
            <!-- Ganti div monografi-icon dengan img logo -->
            <div class="monografi-icon" style="width: 120px; height: 120px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <img src="images/logo.png" alt="Logo JDIH" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h2 class="section-title">Yurisprudensi</h2>
            <p class="text-muted">
                Kumpulan putusan pengadilan yang menjadi acuan dalam penegakan hukum 
                dan pembentukan yurisprudensi di Kota Surakarta.
            </p>
        </div>
        
        <!-- ... rest of the content ... -->
    </div>
</div>
                            
                            <!-- PENCARIAN -->
                            <div class="year-filter mb-4">
                                <h4 class="mb-4">Cari Putusan Pengadilan</h4>
                                <form class="search-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nomor perkara, pokok perkara, atau kata kunci..." 
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
                                
                                <!-- FILTER -->
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <select class="form-control filter-select">
                                            <option value="">Semua Jenis Perkara</option>
                                            <option value="pidana">Pidana</option>
                                            <option value="perdata">Perdata</option>
                                            <option value="tata_usaha">Tata Usaha Negara</option>
                                            <option value="mk">Mahkamah Konstitusi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control filter-select">
                                            <option value="">Semua Tingkat Pengadilan</option>
                                            <option value="ptun">PTUN</option>
                                            <option value="pn">Pengadilan Negeri</option>
                                            <option value="pt">Pengadilan Tinggi</option>
                                            <option value="ma">Mahkamah Agung</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control filter-select">
                                            <option value="">Semua Tahun</option>
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- STATISTIK -->
                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2">124</div>
                                            <div class="card-title">Total Putusan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-primary">18</div>
                                            <div class="card-title">Tahun 2024</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-success">42</div>
                                            <div class="card-title">Perdata</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="document-card">
                                        <div class="card-content text-center">
                                            <div class="card-number fs-2 text-warning">36</div>
                                            <div class="card-title">TUN</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- DAFTAR PUTUSAN -->
                            <h3 class="section-title">Putusan Terbaru</h3>
                            
                            <div class="card-container">
                                <!-- Putusan 1 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-house"></i>
                                            <p>TATA USAHA NEGARA</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-primary">PTUN SURAKARTA</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">123/PID/PTUN/SKA/2024</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Sengketa Tata Usaha Negara tentang IMB</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> 15 November 2024
                                            </div>
                                            <div class="card-status bg-primary">
                                                Tata Usaha Negara
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Putusan tentang sengketa Izin Mendirikan Bangunan (IMB) antara pemohon 
                                            dengan Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surakarta.
                                        </p>
                                        <div class="card-actions">
                                            <a href="#" class="card-btn btn-view">
                                                <i class="bi bi-eye"></i> Baca Putusan
                                            </a>
                                            <a href="#" class="card-btn btn-download">
                                                <i class="bi bi-download"></i> Unduh PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Putusan 2 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-tree"></i>
                                            <p>PERDATA</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-success">PN SURAKARTA</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">456/PDT/PN/SKA/2024</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Sengketa Perdata tentang Tanah</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> 5 November 2024
                                            </div>
                                            <div class="card-status bg-success">
                                                Perdata
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Penyelesaian sengketa kepemilikan tanah antara pihak keluarga dengan 
                                            pengembang perumahan di wilayah Kota Surakarta.
                                        </p>
                                        <div class="card-actions">
                                            <a href="#" class="card-btn btn-view">
                                                <i class="bi bi-eye"></i> Baca Putusan
                                            </a>
                                            <a href="#" class="card-btn btn-download">
                                                <i class="bi bi-download"></i> Unduh PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Putusan 3 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-cash-coin"></i>
                                            <p>PIDANA</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-warning">PT JAWA TENGAH</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">789/PID/PT/SKA/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Perkara Pidana Korupsi APBD</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> 20 Agustus 2023
                                            </div>
                                            <div class="card-status bg-warning text-dark">
                                                Pidana Khusus
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Perkara tindak pidana korupsi dalam pengelolaan Anggaran Pendapatan 
                                            dan Belanja Daerah (APBD) Kota Surakarta.
                                        </p>
                                        <div class="card-actions">
                                            <a href="#" class="card-btn btn-view">
                                                <i class="bi bi-eye"></i> Baca Putusan
                                            </a>
                                            <a href="#" class="card-btn btn-download">
                                                <i class="bi bi-download"></i> Unduh PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Putusan 4 -->
                                <div class="document-card">
                                    <div class="card-image">
                                        <div class="placeholder">
                                            <i class="bi bi-building"></i>
                                            <p>TATA USAHA NEGARA</p>
                                        </div>
                                        <div class="image-overlay">
                                            <span class="image-badge bg-info">PTUN SURAKARTA</span>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-number">321/PID/PTUN/SKA/2023</div>
                                        <h3 class="card-title">
                                            <a href="#" class="text-decoration-none text-dark">Sengketa Perizinan Usaha</a>
                                        </h3>
                                        <div class="card-meta">
                                            <div class="card-date">
                                                <i class="bi bi-calendar"></i> 12 Juli 2023
                                            </div>
                                            <div class="card-status bg-info">
                                                Tata Usaha Negara
                                            </div>
                                        </div>
                                        <p class="mt-2 small text-muted">
                                            Putusan mengenai penolakan izin usaha perdagangan dan pertimbangan 
                                            yuridis tentang kepatutan administrasi.
                                        </p>
                                        <div class="card-actions">
                                            <a href="#" class="card-btn btn-view">
                                                <i class="bi bi-eye"></i> Baca Putusan
                                            </a>
                                            <a href="#" class="card-btn btn-download">
                                                <i class="bi bi-download"></i> Unduh PDF
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
                            
                            <!-- KEUNGGULAN YURISPRUDENSI -->
                            <div class="year-filter mt-5">
                                <h4 class="section-title"><i class="bi bi-award text-primary me-2"></i>Manfaat Yurisprudensi</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-lightbulb fs-1 text-primary"></i>
                                            </div>
                                            <h5>Kepastian Hukum</h5>
                                            <p class="small">Memberikan pedoman dalam penegakan hukum</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-book fs-1 text-primary"></i>
                                            </div>
                                            <h5>Pembelajaran</h5>
                                            <p class="small">Referensi bagi praktisi hukum dan akademisi</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3">
                                            <div class="mb-3">
                                                <i class="bi bi-shield-check fs-1 text-primary"></i>
                                            </div>
                                            <h5>Perlindungan</h5>
                                            <p class="small">Melindungi hak-hak masyarakat melalui putusan</p>
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
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                            <li><a href="?page=yurisprudensi" class="active"><i class="bi bi-gavel"></i> Yurisprudensi</a></li>
                        </ul>
                    </div>
                    
                    <!-- FILTER JENIS PERKARA -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-filter"></i> Filter Berdasarkan Jenis</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-house text-primary me-2"></i>
                                    Tata Usaha Negara
                                </span>
                                <span class="badge bg-primary rounded-pill">36</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-tree text-success me-2"></i>
                                    Perdata
                                </span>
                                <span class="badge bg-success rounded-pill">42</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-cash-coin text-warning me-2"></i>
                                    Pidana
                                </span>
                                <span class="badge bg-warning rounded-pill">28</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-shield-check text-info me-2"></i>
                                    Mahkamah Konstitusi
                                </span>
                                <span class="badge bg-info rounded-pill">18</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD TERPOPULER -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Terpopuler</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Kompilasi Putusan 2023 (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Putusan TUN Lengkap (ZIP)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Analisis Yurisprudensi (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-excel"></i> Database Putusan (XLS)</a></li>
                        </ul>
                    </div>
                    
                    <!-- INFORMASI PENTING -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian</h6>
                            <p class="small mb-2">Yurisprudensi ini:</p>
                            <ul class="small mb-0 ps-3">
                                <li>Putusan yang sudah memiliki kekuatan hukum tetap</li>
                                <li>Dapat dijadikan acuan dalam penegakan hukum</li>
                                <li>Sudah melalui proses anonimisasi data</li>
                                <li>Untuk kepentingan akademik dan penelitian</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- BANTUAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-question-circle"></i> Bantuan & Panduan</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-search"></i> Cara Mencari Putusan</a></li>
                            <li><a href="#"><i class="bi bi-download"></i> Panduan Download</a></li>
                            <li><a href="#"><i class="bi bi-book"></i> Memahami Putusan Pengadilan</a></li>
                            <li><a href="#"><i class="bi bi-envelope"></i> Permohonan Putusan</a></li>
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
        
        alert(`Mencari putusan dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
    });
    
    // Fungsi filter select
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            const filterName = this.previousElementSibling ? this.previousElementSibling.textContent : this.parentElement.previousElementSibling.textContent;
            const filterValue = this.value;
            
            if (filterValue) {
                console.log(`Filter diterapkan: ${filterName} = ${filterValue}`);
                // Implement filter logic here
            }
        });
    });
    
    // Fungsi filter sidebar
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const jenis = this.querySelector('span:first-child').textContent.trim();
            const count = this.querySelector('.badge').textContent;
            
            alert(`Memfilter putusan jenis: ${jenis}\n\nJumlah putusan: ${count} putusan\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
        });
    });
    
    // Fungsi view putusan
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const caseTitle = this.closest('.document-card').querySelector('.card-title').textContent;
            const caseNumber = this.closest('.document-card').querySelector('.card-number').textContent;
            
            alert(`Membaca putusan: "${caseTitle}"\n\nNomor: ${caseNumber}\n\nDalam implementasi real, akan membuka halaman detail putusan.`);
        });
    });
    
    // Fungsi download putusan
    document.querySelectorAll('.btn-download').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const caseTitle = this.closest('.document-card').querySelector('.card-title').textContent;
            
            alert(`Mengunduh putusan: "${caseTitle}"\n\nDokumen PDF akan mulai diunduh dalam beberapa detik.`);
            
            // Simulasi download
            setTimeout(() => {
                alert('Download berhasil!');
            }, 1000);
        });
    });
    
    // Highlight menu aktif di sidebar
    document.querySelectorAll('.sidebar-menu ul li a').forEach(link => {
        if (link.href === window.location.href || link.getAttribute('href').includes('yurisprudensi')) {
            link.classList.add('active');
            link.parentElement.classList.add('active');
        }
    });
</script>

<?php include 'footer.php'; ?>