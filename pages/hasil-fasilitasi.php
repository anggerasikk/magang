<?php
$pageTitle = 'Hasil-Fasilitasi - JDIH DPRD Kota Surakarta';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        <?php include "style-keputusan-dprd.css"; ?>
    </style>
    
    <style>
        /* Additional styles for Hasil Fasilitasi */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 10px;
        }
        
        .status-selesai {
            background: #d4edda;
            color: #155724;
        }
        
        .status-proses {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-tunda {
            background: #f8d7da;
            color: #721c24;
        }
        
        .facilitator-info {
            background: #f8f9fa;
            border-left: 4px solid #13547a;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .facilitator-title {
            font-weight: 600;
            color: #13547a;
            margin-bottom: 5px;
        }
        
        .facilitator-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .detail-item i {
            color: #13547a;
        }
        
        .raperda-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                    
                    <!-- HEADER MONOGRAFI -->
                    <div class="monografi-container">
                        <div class="monografi-header">
                            <div class="monografi-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h2 class="section-title">Hasil Fasilitasi Raperda</h2>
                            <p class="monografi-description">
                                Dokumen hasil fasilitasi proses penyusunan Rancangan Peraturan Daerah (Raperda) 
                                yang mencakup notulensi rapat, catatan kesepakatan, rekomendasi perbaikan, 
                                dan laporan kemajuan proses legislasi.
                            </p>
                        </div>
                        
                        <!-- PENCARIAN -->
                        <div class="year-filter">
                            <h5>Cari Hasil Fasilitasi Raperda</h5>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci hasil fasilitasi..." 
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
                                    <div class="stats-number">92</div>
                                    <div class="stats-label">Total Fasilitasi</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">10</div>
                                    <div class="stats-label">Tahun 2024</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">68</div>
                                    <div class="stats-label">Selesai</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">14</div>
                                    <div class="stats-label">Dalam Proses</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- DAFTAR DOKUMEN -->
                        <h3 class="section-title">Hasil Fasilitasi Terbaru</h3>
                        
                        <div class="card-container">
                            <!-- Dokumen 1 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-gear"></i>
                                        <p>Hasil Fasilitasi</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-selesai">
                                            <i class="bi bi-check-circle"></i> SELESAI
                                        </span>
                                        <span class="raperda-link">
                                            <i class="bi bi-link-45deg"></i> Raperda Pariwisata
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Hasil Fasilitasi Rapat Penyusunan Raperda tentang Penyelenggaraan Pariwisata Berkelanjutan</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Dr. H. Bambang Sutrisno, SH., MH.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>22 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>09:00 - 15:00 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 24 Januari 2024</div>
                                        <div class="card-status">Halaman: 28</div>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-link-45deg"></i> Raperda
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 2 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-people"></i>
                                        <p>Konsultasi Publik</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-proses">
                                            <i class="bi bi-hourglass-split"></i> DALAM PROSES
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/02/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Laporan Fasilitasi Konsultasi Publik Raperda Pengelolaan Sampah Berbasis Masyarakat</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Prof. Dr. Sri Wahyuni, SH., MH.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>18 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>10:00 - 16:00 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 20 Januari 2024</div>
                                        <div class="card-status">Halaman: 32</div>
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
                            
                            <!-- Dokumen 3 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-tools"></i>
                                        <p>Rapat Teknis</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2023</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-selesai">
                                            <i class="bi bi-check-circle"></i> SELESAI
                                        </span>
                                        <span class="raperda-link">
                                            <i class="bi bi-link-45deg"></i> Raperda Digitalisasi
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/12/2023</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Hasil Fasilitasi Rapat Teknis Penyusunan Raperda Digitalisasi Pelayanan Publik</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Dr. Maya Indriati, S.Si., M.T.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>15 Desember 2023</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>13:00 - 18:00 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 17 Desember 2023</div>
                                        <div class="card-status">Halaman: 36</div>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-link-45deg"></i> Raperda
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 4 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-pause-circle"></i>
                                        <p>Fasilitasi Ditunda</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2023</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-tunda">
                                            <i class="bi bi-exclamation-triangle"></i> DITUNDA
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/11/2023</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Laporan Fasilitasi Pembahasan Raperda Penataan Pedagang Kaki Lima</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Drs. Agus Setiawan, M.Si.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>8 Desember 2023</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>09:30 - 14:00 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 10 Desember 2023</div>
                                        <div class="card-status">Halaman: 24</div>
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
                            
                            <!-- Dokumen 5 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-chat-dots"></i>
                                        <p>FGD / Workshop</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2023</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-selesai">
                                            <i class="bi bi-check-circle"></i> SELESAI
                                        </span>
                                        <span class="raperda-link">
                                            <i class="bi bi-link-45deg"></i> Raperda Transportasi
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/10/2023</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Hasil Fasilitasi FGD Penyusunan Raperda Transportasi Publik Terintegrasi</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Dr. Rudi Hartono, S.T., M.T.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>5 Desember 2023</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>08:00 - 17:00 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 7 Desember 2023</div>
                                        <div class="card-status">Halaman: 42</div>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-link-45deg"></i> Raperda
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 6 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-handshake"></i>
                                        <p>Mediasi Stakeholder</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2023</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-selesai">
                                            <i class="bi bi-check-circle"></i> SELESAI
                                        </span>
                                    </div>
                                    <div class="card-number">HF/DPRD/09/2023</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Hasil Fasilitasi Mediasi Stakeholder Raperda Kawasan Bebas Sampah Plastik</a>
                                    </h4>
                                    <div class="facilitator-info">
                                        <div class="facilitator-title">Fasilitator & Detail:</div>
                                        <div class="facilitator-details">
                                            <div class="detail-item">
                                                <i class="bi bi-person"></i>
                                                <span>Prof. Dr. Ahmad Fauzi, S.H., M.H.</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>1 Desember 2023</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>10:30 - 16:30 WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 3 Desember 2023</div>
                                        <div class="card-status">Halaman: 38</div>
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
                        </div>
                        
                        <!-- FILTER TAHUN -->
                        <div class="year-filter mt-4">
                            <h5>Filter Berdasarkan Tahun</h5>
                            <div class="year-buttons">
                                <button class="year-btn active">Semua</button>
                                <button class="year-btn">2024</button>
                                <button class="year-btn">2023</button>
                                <button class="year-btn">2022</button>
                                <button class="year-btn">2021</button>
                                <button class="year-btn">2020</button>
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
                        
                        <!-- JENIS FASILITASI -->
                        <div class="year-filter mt-5">
                            <h4 class="mb-3"><i class="bi bi-diagram-3 text-primary me-2"></i>Jenis Fasilitasi yang Dilakukan</h4>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-people fs-1 text-primary"></i>
                                        </div>
                                        <h5>Konsultasi Publik</h5>
                                        <p class="small">Fasilitasi partisipasi masyarakat</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-gear fs-1 text-primary"></i>
                                        </div>
                                        <h5>Rapat Teknis</h5>
                                        <p class="small">Pembahasan aspek teknis raperda</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-shuffle fs-1 text-primary"></i>
                                        </div>
                                        <h5>Harmonisasi</h5>
                                        <p class="small">Penyelarasan dengan peraturan lain</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-chat-dots fs-1 text-primary"></i>
                                        </div>
                                        <h5>FGD / Workshop</h5>
                                        <p class="small">Diskusi kelompok terfokus</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-handshake fs-1 text-primary"></i>
                                        </div>
                                        <h5>Mediasi Stakeholder</h5>
                                        <p class="small">Penyelesaian perbedaan pandangan</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="mb-3">
                                            <i class="bi bi-graph-up fs-1 text-primary"></i>
                                        </div>
                                        <h5>Evaluasi Implementasi</h5>
                                        <p class="small">Penilaian pelaksanaan peraturan</p>
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
                            <li class="active"><a href="?page=hasil-fasilitasi"><i class="bi bi-gear"></i> Hasil Fasilitasi Raperda</a></li>
                            <li><a href="?page=risalah"><i class="bi bi-chat-left-text"></i> Risalah</a></li>
                            <li><a href="?page=hasil-harmonisasi"><i class="bi bi-shuffle"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="?page=karya-ilmiah"><i class="bi bi-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="?page=himpunan"><i class="bi bi-folder"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                    
                    <!-- STATISTIK FASILITASI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-bar-chart"></i> Statistik Fasilitasi</h4>
                        <ul>
                            <li class="d-flex justify-content-between">
                                <span>Total Fasilitasi</span>
                                <span class="badge bg-primary rounded-pill">92</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tahun 2024</span>
                                <span class="badge bg-primary rounded-pill">10</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tahun 2023</span>
                                <span class="badge bg-primary rounded-pill">22</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Selesai</span>
                                <span class="badge bg-success rounded-pill">68</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Dalam Proses</span>
                                <span class="badge bg-warning rounded-pill">14</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- FILTER STATUS -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-activity"></i> Filter Status</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Selesai
                                </span>
                                <span class="badge bg-success rounded-pill">68</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-hourglass-split text-warning me-2"></i>
                                    Dalam Proses
                                </span>
                                <span class="badge bg-warning rounded-pill">14</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                    Ditunda
                                </span>
                                <span class="badge bg-danger rounded-pill">10</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER JENIS FASILITASI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-diagram-3"></i> Filter Jenis Fasilitasi</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Konsultasi Publik
                                <span class="badge bg-primary rounded-pill">32</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Rapat Teknis
                                <span class="badge bg-primary rounded-pill">28</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                FGD / Workshop
                                <span class="badge bg-primary rounded-pill">18</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Mediasi Stakeholder
                                <span class="badge bg-primary rounded-pill">14</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD TEMPLATE -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Template & Formulir</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Notulensi Rapat (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Form Konsultasi Publik (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-excel"></i> Form Daftar Hadir (XLS)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-text"></i> Template Laporan Fasilitasi (DOC)</a></li>
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
            
            alert(`Mencari hasil fasilitasi dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
        });
        
        // Fungsi filter tahun
        document.querySelectorAll('.year-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove active class from all buttons
                document.querySelectorAll('.year-btn').forEach(b => {
                    b.classList.remove('active');
                });
                // Add active class to clicked button
                this.classList.add('active');
                
                const year = this.textContent;
                alert(`Memfilter hasil fasilitasi tahun: ${year}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi filter status & jenis
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.closest('.sidebar-menu').querySelector('h4').textContent;
                const filterValue = this.querySelector('span').textContent.trim();
                const count = this.querySelector('.badge').textContent;
                
                alert(`Memfilter berdasarkan ${filterType}: ${filterValue}\n\nJumlah dokumen: ${count}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi download
        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-card').querySelector('.card-title a').textContent;
                
                alert(`Mengunduh hasil fasilitasi: "${docTitle}"\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
                
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
                
                if (this.querySelector('.bi-link-45deg')) {
                    alert(`Mengarahkan ke Raperda terkait hasil fasilitasi: "${docTitle}"\n\nDalam implementasi real, akan membuka halaman Raperda.`);
                } else {
                    alert(`Membuka preview hasil fasilitasi: "${docTitle}"\n\nPreview akan dibuka di tab baru.`);
                }
            });
        });
        
        // Highlight menu aktif di sidebar
        document.querySelectorAll('.sidebar-menu ul li a').forEach(link => {
            if (link.href === window.location.href) {
                link.parentElement.classList.add('active');
            }
        });
        
        // Fungsi aktifkan tahun button
        document.querySelector('.year-btn.active').addEventListener('click', function(e) {
            e.preventDefault();
        });
    </script>

</body>
</html>