<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dokumen Langka - Koleksi Khusus - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        <?php include "style-keputusan-dprd.css"; ?>
    </style>
    
    <style>
        /* Additional styles for Dokumen Langka */
        .rare-document-card {
            border: 2px solid #ffd700;
            position: relative;
            overflow: hidden;
        }
        
        .rare-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #8b4513;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .archive-year {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.2rem;
            color: #8b4513;
            font-weight: 600;
            margin-bottom: 20px;
            border-left: 4px solid #8b4513;
            padding-left: 15px;
            background: #fffaf0;
            padding: 10px 15px;
            border-radius: 0 8px 8px 0;
        }
        
        .condition-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .condition-good {
            background: #e6fffa;
            color: #234e52;
        }
        
        .condition-fair {
            background: #fffaf0;
            color: #744210;
        }
        
        .condition-poor {
            background: #fff5f5;
            color: #9b2c2c;
        }
        
        .access-level {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .access-public {
            background: #f0fff4;
            color: #22543d;
        }
        
        .access-limited {
            background: #fffaf0;
            color: #744210;
        }
        
        .access-restricted {
            background: #fff5f5;
            color: #9b2c2c;
        }
        
        .document-provenance {
            background: #f8f9fa;
            border-left: 4px solid #8b4513;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .provenance-title {
            font-weight: 600;
            color: #8b4513;
            margin-bottom: 5px;
        }
        
        .digital-copy {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .original-copy {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR & HEADER -->
    <?php include "header.php"; ?>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <div class="row">
                
                <!-- KONTEN UTAMA -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    
                    <!-- HEADER DOKUMEN LANGKA -->
                    <div class="monografi-container">
                        <div class="monografi-header">
                            <div class="monografi-icon">
                                <i class="bi bi-archive"></i>
                            </div>
                            <h2 class="section-title">Dokumen Langka & Koleksi Khusus</h2>
                            <p class="monografi-description">
                                Koleksi dokumen langka dan bersejarah milik DPRD Kota Surakarta yang mencakup 
                                arsip sejarah, dokumen pendiri, naskah asli peraturan tempo dulu, dan koleksi 
                                khusus lainnya yang memiliki nilai historis tinggi.
                            </p>
                        </div>
                        
                        <!-- PENCARIAN -->
                        <div class="year-filter">
                            <h5>Cari Dokumen Langka</h5>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci dokumen langka..." 
                                           aria-label="Search" aria-describedby="button-search">
                                    <button class="btn btn-search" type="submit" id="button-search">
                                        <i class="bi bi-search"></i> CARI
                                    </button>
                                </div>
                                <div class="text-end mt-3">
                                    <a href="#" class="adv-search-link">
                                        <i class="bi bi-sliders"></i> Pencarian Lanjutan
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- STATISTIK -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">128</div>
                                    <div class="stats-label">Total Dokumen</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">45</div>
                                    <div class="stats-label">Era Kolonial</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">68</div>
                                    <div class="stats-label">Era Kemerdekaan</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">15</div>
                                    <div class="stats-label">Digitalisasi</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- KATEGORI ERA -->
                        <div class="year-filter">
                            <h5>Kategori Dokumen</h5>
                            <div class="year-buttons">
                                <button class="year-btn active">Semua</button>
                                <button class="year-btn">Era Kolonial</button>
                                <button class="year-btn">Era Kemerdekaan</button>
                                <button class="year-btn">Orde Baru</button>
                                <button class="year-btn">Reformasi</button>
                                <button class="year-btn">Naskah Asli</button>
                                <button class="year-btn">Peta Kuno</button>
                            </div>
                        </div>
                        
                        <!-- DOKUMEN ERA KOLONIAL -->
                        <h4 class="archive-year mt-5">Era Kolonial (1900-1945)</h4>
                        
                        <div class="card-container">
                            <!-- Dokumen 1 -->
                            <div class="document-card rare-document-card">
                                <div class="rare-badge">DOKUMEN LANGKA</div>
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-book"></i>
                                        <p>Arsip Kolonial</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">1925</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-number">AL/DPRD/KOL/1925/001</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Notulen Rapat Dewan Kota (Gemeenteraad) Surakarta Tahun 1925</a>
                                    </h4>
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="condition-badge condition-fair">
                                            <i class="bi bi-check-circle"></i> Kondisi: Cukup
                                        </span>
                                        <span class="access-level access-restricted">
                                            <i class="bi bi-lock"></i> Akses Terbatas
                                        </span>
                                        <span class="original-copy">
                                            <i class="bi bi-file-earmark"></i> Asli
                                        </span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Tahun: 1925</div>
                                        <div class="card-status">Bahasa: Belanda</div>
                                    </div>
                                    <div class="document-provenance">
                                        <div class="provenance-title">Provenansi:</div>
                                        <p>Dokumen asli dari arsip Dewan Kota (Gemeenteraad) Surakarta masa kolonial Belanda. Disimpan dalam kondisi khusus.</p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview Digital
                                        </a>
                                        <a href="#" class="card-btn btn-download disabled" onclick="return false;">
                                            <i class="bi bi-download"></i> Permohonan
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 2 -->
                            <div class="document-card rare-document-card">
                                <div class="rare-badge">DOKUMEN LANGKA</div>
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-map"></i>
                                        <p>Peta Kuno</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">1930</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-number">AL/DPRD/KOL/1930/002</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Peta Administrasi Kota Surakarta Tahun 1930</a>
                                    </h4>
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="condition-badge condition-good">
                                            <i class="bi bi-check-circle"></i> Kondisi: Baik
                                        </span>
                                        <span class="access-level access-limited">
                                            <i class="bi bi-shield-lock"></i> Akses Terbatas
                                        </span>
                                        <span class="original-copy">
                                            <i class="bi bi-file-earmark"></i> Asli
                                        </span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Tahun: 1930</div>
                                        <div class="card-status">Skala: 1:10.000</div>
                                    </div>
                                    <div class="document-provenance">
                                        <div class="provenance-title">Provenansi:</div>
                                        <p>Peta asli hasil survei topografi pemerintah kolonial Belanda. Menunjukkan batas administratif dan tata ruang kota pada era tersebut.</p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview Digital
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download Scan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- DOKUMEN ERA KEMERDEKAAN -->
                        <h4 class="archive-year mt-5">Era Kemerdekaan (1945-1965)</h4>
                        
                        <div class="card-container">
                            <!-- Dokumen 3 -->
                            <div class="document-card rare-document-card">
                                <div class="rare-badge">DOKUMEN LANGKA</div>
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <p>Naskah Asli</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">1950</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-number">AL/DPRD/KMD/1950/001</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Naskah Asli Peraturan Kota Surakarta No. 1 Tahun 1950 tentang Pembentukan DPRD</a>
                                    </h4>
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="condition-badge condition-fair">
                                            <i class="bi bi-check-circle"></i> Kondisi: Cukup
                                        </span>
                                        <span class="access-level access-public">
                                            <i class="bi bi-unlock"></i> Akses Terbuka
                                        </span>
                                        <span class="digital-copy">
                                            <i class="bi bi-file-earmark-pdf"></i> Digital
                                        </span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Tanggal: 15 Agustus 1950</div>
                                        <div class="card-status">Halaman: 24</div>
                                    </div>
                                    <div class="document-provenance">
                                        <div class="provenance-title">Provenansi:</div>
                                        <p>Dokumen pendirian DPRD Kota Surakarta pasca kemerdekaan. Ditandatangani oleh Wali Kota pertama dan anggota DPRD perintis.</p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 4 -->
                            <div class="document-card rare-document-card">
                                <div class="rare-badge">DOKUMEN LANGKA</div>
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-camera"></i>
                                        <p>Foto Bersejarah</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">1955</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-number">AL/DPRD/KMD/1955/001</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Album Foto Sidang Pertama DPRD Kota Surakarta 1955</a>
                                    </h4>
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="condition-badge condition-poor">
                                            <i class="bi bi-exclamation-triangle"></i> Kondisi: Rusak
                                        </span>
                                        <span class="access-level access-limited">
                                            <i class="bi bi-shield-lock"></i> Akses Terbatas
                                        </span>
                                        <span class="digital-copy">
                                            <i class="bi bi-file-image"></i> Digital
                                        </span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Tahun: 1955</div>
                                        <div class="card-status">Jumlah: 36 Foto</div>
                                    </div>
                                    <div class="document-provenance">
                                        <div class="provenance-title">Provenansi:</div>
                                        <p>Koleksi foto hitam putih dokumentasi sidang pertama DPRD hasil Pemilu 1955. Sudah dilakukan restorasi digital.</p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Gallery
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- FILTER TAHUN -->
                        <div class="year-filter mt-4">
                            <h5>Filter Berdasarkan Tahun</h5>
                            <div class="year-buttons">
                                <button class="year-btn active">Semua</button>
                                <button class="year-btn">1800-an</button>
                                <button class="year-btn">1900-1945</button>
                                <button class="year-btn">1945-1965</button>
                                <button class="year-btn">1966-1998</button>
                                <button class="year-btn">1999-Sekarang</button>
                            </div>
                        </div>
                        
                        <!-- PAGINATION -->
                        <div class="pagination-container">
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
                        
                        <!-- INFORMASI AKSES -->
                        <div class="year-filter mt-5">
                            <h4 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Akses Dokumen Langka</h4>
                            <div class="alert alert-warning" role="alert">
                                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Prosedur Khusus Akses Dokumen Langka</h5>
                                <p class="mb-2">Untuk mengakses dokumen langka dan koleksi khusus:</p>
                                <ol class="mb-0">
                                    <li>Pengunjung harus membuat permohonan tertulis minimal 3 hari sebelumnya</li>
                                    <li>Dokumen hanya dapat diakses di ruang baca khusus dengan pengawasan</li>
                                    <li>Dilarang memfoto atau menyalin dokumen tanpa izin tertulis</li>
                                    <li>Untuk penelitian akademik, wajib melampirkan proposal penelitian</li>
                                    <li>Dokumen kondisi rusak hanya tersedia dalam bentuk digital</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU KOLEKSI KHUSUS -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-archive"></i> Menu Koleksi Khusus</h4>
                        <ul>
                            <li class="active"><a href="?page=dokumen-langka"><i class="bi bi-gem"></i> Dokumen Langka</a></li>
                            <li><a href="?page=arsip-sejarah"><i class="bi bi-clock-history"></i> Arsip Sejarah</a></li>
                            <li><a href="?page=naskah-asli"><i class="bi bi-file-earmark"></i> Naskah Asli</a></li>
                            <li><a href="?page=foto-bersejarah"><i class="bi bi-camera"></i> Foto Bersejarah</a></li>
                            <li><a href="?page=peta-kuno"><i class="bi bi-map"></i> Peta Kuno</a></li>
                            <li><a href="?page=mikrofilm"><i class="bi bi-film"></i> Mikrofilm</a></li>
                            <li><a href="?page=koleksi-digital"><i class="bi bi-hdd"></i> Koleksi Digital</a></li>
                        </ul>
                    </div>
                    
                    <!-- STATISTIK KOLEKSI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-bar-chart"></i> Statistik Koleksi</h4>
                        <ul>
                            <li class="d-flex justify-content-between">
                                <span>Total Dokumen Langka</span>
                                <span class="badge bg-primary rounded-pill">128</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Era Kolonial</span>
                                <span class="badge bg-warning rounded-pill">45</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Era Kemerdekaan</span>
                                <span class="badge bg-success rounded-pill">68</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Telah Didigitalisasi</span>
                                <span class="badge bg-info rounded-pill">15</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Dalam Proses Restorasi</span>
                                <span class="badge bg-danger rounded-pill">8</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- KONDISI DOKUMEN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-clipboard-check"></i> Kondisi Dokumen</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Kondisi Baik
                                </span>
                                <span class="badge bg-success rounded-pill">42</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-warning me-2"></i>
                                    Kondisi Cukup
                                </span>
                                <span class="badge bg-warning rounded-pill">65</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                    Kondisi Rusak
                                </span>
                                <span class="badge bg-danger rounded-pill">21</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- TINGKAT AKSES -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-shield"></i> Tingkat Akses</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-unlock text-success me-2"></i>
                                    Akses Terbuka
                                </span>
                                <span class="badge bg-success rounded-pill">35</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-shield-lock text-warning me-2"></i>
                                    Akses Terbatas
                                </span>
                                <span class="badge bg-warning rounded-pill">72</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-lock text-danger me-2"></i>
                                    Akses Terbatas Ketat
                                </span>
                                <span class="badge bg-danger rounded-pill">21</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- PROGRAM DIGITALISASI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-camera-video"></i> Program Digitalisasi</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-check-circle"></i> Panduan Pengambilan Foto</a></li>
                            <li><a href="#"><i class="bi bi-check-circle"></i> Teknik Pemindaian Dokumen</a></li>
                            <li><a href="#"><i class="bi bi-check-circle"></i> Restorasi Digital</a></li>
                            <li><a href="#"><i class="bi bi-check-circle"></i> Metadata & Katalogisasi</a></li>
                            <li><a href="#"><i class="bi bi-check-circle"></i> Penyimpanan Digital</a></li>
                        </ul>
                    </div>
                    
                    <!-- BANTUAN PENELITIAN -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-question-circle"></i> Bantuan Penelitian</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-info-circle"></i> Cara Mengakses Dokumen</a></li>
                            <li><a href="#"><i class="bi bi-pencil"></i> Form Permohonan</a></li>
                            <li><a href="#"><i class="bi bi-calendar"></i> Jadwal Kunjungan</a></li>
                            <li><a href="#"><i class="bi bi-printer"></i> Aturan Pencetakan</a></li>
                            <li><a href="#"><i class="bi bi-envelope"></i> Konsultasi Penelitian</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <?php include "footer.php"; ?>

    <!-- JAVASCRIPT FILES -->
    <?php include "js-footer.php"; ?>
    
    <script>
        // Fungsi pencarian
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const keyword = this.querySelector('input[type="text"]').value;
            
            if (!keyword.trim()) {
                alert('Masukkan kata kunci pencarian!');
                return;
            }
            
            alert(`Mencari dokumen langka dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
        });
        
        // Fungsi filter kategori
        document.querySelectorAll('.year-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove active class from all buttons
                document.querySelectorAll('.year-btn').forEach(b => {
                    b.classList.remove('active');
                });
                // Add active class to clicked button
                this.classList.add('active');
                
                const category = this.textContent;
                alert(`Memfilter kategori: ${category}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi filter kondisi
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.closest('.sidebar-menu').querySelector('h4').textContent;
                const filterValue = this.querySelector('span').textContent.trim();
                const count = this.querySelector('.badge').textContent;
                
                alert(`Memfilter berdasarkan ${filterType}: ${filterValue}\n\nJumlah dokumen: ${count}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi download (khusus dokumen terbuka)
        document.querySelectorAll('.btn-download:not(.disabled)').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-card').querySelector('.card-title a').textContent;
                
                alert(`Mengunduh dokumen: "${docTitle}"\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
                
                // Simulasi download
                setTimeout(() => {
                    alert('Download berhasil!');
                }, 1000);
            });
        });
        
        // Fungsi preview
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-card').querySelector('.card-title a').textContent;
                
                alert(`Membuka preview dokumen: "${docTitle}"\n\nPreview akan dibuka di tab baru.`);
            });
        });
        
        // Fungsi untuk dokumen terbatas
        document.querySelectorAll('.btn-download.disabled').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-card').querySelector('.card-title a').textContent;
                
                alert(`Dokumen "${docTitle}" memerlukan permohonan khusus.\n\nSilakan klik "Bantuan Penelitian" di sidebar untuk informasi lebih lanjut.`);
            });
        });
        
        // Highlight menu aktif di sidebar
        document.querySelectorAll('.sidebar-menu ul li a').forEach(link => {
            if (link.href === window.location.href) {
                link.parentElement.classList.add('active');
            }
        });
        
        // Fungsi aktifkan kategori button
        document.querySelector('.year-btn.active').addEventListener('click', function(e) {
            e.preventDefault();
        });
        
        // Tampilkan modal informasi untuk dokumen langka
        document.querySelectorAll('.rare-badge').forEach(badge => {
            badge.addEventListener('click', function(e) {
                e.stopPropagation();
                alert('Dokumen Langka: Koleksi khusus yang dilindungi dan dijaga untuk kepentingan penelitian sejarah dan budaya.\n\nStatus: Dilindungi UU No. 43 Tahun 2009 tentang Kearsipan');
            });
        });
    </script>

</body>
</html>