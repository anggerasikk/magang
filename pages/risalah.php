<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Risalah - Monografi Hukum - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        <?php include "style-keputusan-dprd.css"; ?>
    </style>
    
    <style>
        /* Additional styles for Risalah */
        .meeting-badge {
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
        
        .badge-pleno {
            background: #cce5ff;
            color: #004085;
        }
        
        .badge-komisi {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-bamus {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-pansus {
            background: #e2d9f3;
            color: #3d2c5a;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-final {
            background: #d4edda;
            color: #155724;
        }
        
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-correction {
            background: #f8d7da;
            color: #721c24;
        }
        
        .meeting-info {
            background: #f8f9fa;
            border-left: 4px solid #13547a;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .meeting-info-title {
            font-weight: 600;
            color: #13547a;
            margin-bottom: 5px;
        }
        
        .meeting-details {
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
        
        .video-badge {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
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
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h2 class="section-title">Risalah Rapat DPRD</h2>
                            <p class="monografi-description">
                                Kumpulan dokumen resmi risalah rapat DPRD Kota Surakarta yang berisi notulensi lengkap 
                                dari berbagai jenis rapat, termasuk rapat paripurna, rapat komisi, rapat panitia khusus, 
                                dan rapat Badan Musyawarah (Bamus).
                            </p>
                        </div>
                        
                        <!-- PENCARIAN -->
                        <div class="year-filter">
                            <h5>Cari Risalah Rapat</h5>
                            <form class="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci risalah rapat..." 
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
                                    <div class="stats-number">342</div>
                                    <div class="stats-label">Total Risalah</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">18</div>
                                    <div class="stats-label">Tahun 2024</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">256</div>
                                    <div class="stats-label">Risalah Final</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-box">
                                    <div class="stats-number">4</div>
                                    <div class="stats-label">Jenis Rapat</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- DAFTAR DOKUMEN -->
                        <h3 class="section-title">Risalah Terbaru</h3>
                        
                        <div class="card-container">
                            <!-- Dokumen 1 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-mic"></i>
                                        <p>Rapat Paripurna</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-final">
                                            <i class="bi bi-check-circle"></i> FINAL
                                        </span>
                                        <span class="meeting-badge badge-pleno">
                                            <i class="bi bi-people"></i> PARIPURNA
                                        </span>
                                    </div>
                                    <div class="card-number">RR/DPRD/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Paripurna DPRD Kota Surakarta Masa Persidangan I Tahun 2024</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>25 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>09:00 - 15:30 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>45 Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 27 Januari 2024</div>
                                        <div class="card-status">Halaman: 48</div>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-camera-video"></i> Video
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 2 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-person-badge"></i>
                                        <p>Rapat Komisi</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-final">
                                            <i class="bi bi-check-circle"></i> FINAL
                                        </span>
                                        <span class="meeting-badge badge-komisi">
                                            <i class="bi bi-person"></i> KOMISI A
                                        </span>
                                    </div>
                                    <div class="card-number">RR/KOM-A/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Komisi A DPRD tentang Pembahasan Raperda Pelestarian Batik</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>22 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>10:00 - 17:00 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>12 Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 24 Januari 2024</div>
                                        <div class="card-status">Halaman: 36</div>
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
                                        <i class="bi bi-gear"></i>
                                        <p>Rapat Bamus</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-draft">
                                            <i class="bi bi-pencil"></i> DRAFT
                                        </span>
                                        <span class="meeting-badge badge-bamus">
                                            <i class="bi bi-gear"></i> BAMUS
                                        </span>
                                    </div>
                                    <div class="card-number">RR/BAMUS/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Badan Musyawarah DPRD tentang Penetapan Agenda Sidang</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>20 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>14:00 - 16:30 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>Pimpinan + Fraksi</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 21 Januari 2024</div>
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
                            
                            <!-- Dokumen 4 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-search"></i>
                                        <p>Rapat Pansus</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-correction">
                                            <i class="bi bi-exclamation-triangle"></i> KOREKSI
                                        </span>
                                        <span class="meeting-badge badge-pansus">
                                            <i class="bi bi-search"></i> PANSUS
                                        </span>
                                    </div>
                                    <div class="card-number">RR/PANSUS/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Panitia Khusus tentang Pengawasan Pelaksanaan APBD 2023</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>18 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>09:30 - 18:00 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>15 Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 19 Januari 2024</div>
                                        <div class="card-status">Halaman: 56</div>
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
                                        <i class="bi bi-people"></i>
                                        <p>Rapat Komisi</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2024</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-final">
                                            <i class="bi bi-check-circle"></i> FINAL
                                        </span>
                                        <span class="meeting-badge badge-komisi">
                                            <i class="bi bi-person"></i> KOMISI B
                                        </span>
                                        <span class="video-badge">
                                            <i class="bi bi-camera-video"></i> ADA VIDEO
                                        </span>
                                    </div>
                                    <div class="card-number">RR/KOM-B/01/2024</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Komisi B tentang Pembahasan Kebijakan Ekonomi Daerah</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>17 Januari 2024</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>13:00 - 17:30 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>11 Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 18 Januari 2024</div>
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
                                            <i class="bi bi-camera-video"></i> Video
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dokumen 6 -->
                            <div class="document-card">
                                <div class="card-image">
                                    <div class="placeholder">
                                        <i class="bi bi-mic"></i>
                                        <p>Rapat Paripurna</p>
                                    </div>
                                    <div class="image-overlay">
                                        <span class="image-badge">2023</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="d-flex flex-wrap mb-3">
                                        <span class="status-badge status-final">
                                            <i class="bi bi-check-circle"></i> FINAL
                                        </span>
                                        <span class="meeting-badge badge-pleno">
                                            <i class="bi bi-people"></i> PARIPURNA
                                        </span>
                                        <span class="video-badge">
                                            <i class="bi bi-camera-video"></i> ADA VIDEO
                                        </span>
                                    </div>
                                    <div class="card-number">RR/DPRD/12/2023</div>
                                    <h4 class="card-title">
                                        <a href="#" class="text-decoration-none text-dark">Risalah Rapat Paripurna Penutupan Masa Sidang Tahun 2023</a>
                                    </h4>
                                    <div class="meeting-info">
                                        <div class="meeting-info-title">Detail Rapat:</div>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>28 Desember 2023</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock"></i>
                                                <span>09:00 - 14:00 WIB</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people"></i>
                                                <span>45 Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <div class="card-date">Upload: 30 Desember 2023</div>
                                        <div class="card-status">Halaman: 52</div>
                                    </div>
                                    <div class="card-actions">
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        <a href="#" class="card-btn btn-download">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="#" class="card-btn btn-view">
                                            <i class="bi bi-camera-video"></i> Video
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
                        
                        <!-- STRUKTUR RISALAH -->
                        <div class="year-filter mt-5">
                            <h4 class="mb-3"><i class="bi bi-list-ol text-primary me-2"></i>Struktur Risalah Rapat</h4>
                            <div class="accordion" id="accordionStruktur">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            <i class="bi bi-1-circle me-2"></i>Kepala Risalah
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Informasi dasar rapat: jenis rapat, tanggal, waktu, tempat, pimpinan rapat, dan daftar hadir.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <i class="bi bi-2-circle me-2"></i>Acara Rapat
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Agenda rapat yang dibahas secara berurutan dengan nomor acara.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            <i class="bi bi-3-circle me-2"></i>Pembahasan & Diskusi
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Transkrip pembahasan tiap agenda, termasuk pandangan fraksi, tanggapan, dan diskusi.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            <i class="bi bi-4-circle me-2"></i>Keputusan & Kesepakatan
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Keputusan yang diambil, voting (jika ada), dan kesepakatan yang dicapai.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                            <i class="bi bi-5-circle me-2"></i>Penutup
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionStruktur">
                                        <div class="accordion-body">
                                            <p>Waktu penutupan, agenda berikutnya, dan penandatanganan pimpinan rapat.</p>
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
                            <li class="active"><a href="?page=risalah"><i class="bi bi-journal-text"></i> Risalah</a></li>
                        </ul>
                    </div>
                    
                    <!-- STATISTIK RISALAH -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-bar-chart"></i> Statistik Risalah</h4>
                        <ul>
                            <li class="d-flex justify-content-between">
                                <span>Total Risalah</span>
                                <span class="badge bg-primary rounded-pill">342</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tahun 2024</span>
                                <span class="badge bg-primary rounded-pill">18</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tahun 2023</span>
                                <span class="badge bg-primary rounded-pill">156</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Risalah Final</span>
                                <span class="badge bg-success rounded-pill">256</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Dengan Video</span>
                                <span class="badge bg-danger rounded-pill">48</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- FILTER JENIS RAPAT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-diagram-3"></i> Filter Jenis Rapat</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-people me-2" style="color: #004085;"></i>
                                    Rapat Paripurna
                                </span>
                                <span class="badge bg-primary rounded-pill">86</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-person me-2" style="color: #0c5460;"></i>
                                    Rapat Komisi
                                </span>
                                <span class="badge bg-primary rounded-pill">168</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-gear me-2" style="color: #155724;"></i>
                                    Rapat Bamus
                                </span>
                                <span class="badge bg-primary rounded-pill">64</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-search me-2" style="color: #3d2c5a;"></i>
                                    Rapat Pansus
                                </span>
                                <span class="badge bg-primary rounded-pill">24</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER STATUS -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-activity"></i> Filter Status</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Final
                                </span>
                                <span class="badge bg-success rounded-pill">256</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                    Draft
                                </span>
                                <span class="badge bg-warning rounded-pill">68</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                    Perlu Koreksi
                                </span>
                                <span class="badge bg-danger rounded-pill">18</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD PERIODE -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Per Periode</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Risalah 2023 Lengkap (ZIP)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Risalah Paripurna 2023 (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Risalah (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-excel"></i> Daftar Risalah 2019-2023 (XLS)</a></li>
                        </ul>
                    </div>
                    
                    <!-- INFORMASI PENTING -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-clock-history me-2"></i>Proses Risalah</h6>
                            <p class="small mb-2">Alur penyusunan risalah:</p>
                            <ol class="small mb-0 ps-3">
                                <li>Perekaman dan notulensi rapat</li>
                                <li>Penyusunan draft risalah</li>
                                <li>Review oleh pimpinan rapat</li>
                                <li>Koreksi dan finalisasi</li>
                                <li>Pengesahan dan publikasi</li>
                            </ol>
                        </div>
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
            
            alert(`Mencari risalah rapat dengan kata kunci: "${keyword}"\n\nHasil pencarian akan ditampilkan di bawah.`);
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
                alert(`Memfilter risalah tahun: ${year}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi filter jenis rapat
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.closest('.sidebar-menu').querySelector('h4').textContent;
                const filterValue = this.querySelector('span').textContent.trim();
                const count = this.querySelector('.badge').textContent;
                
                alert(`Memfilter berdasarkan ${filterType}: ${filterValue}\n\nJumlah risalah: ${count}\n\nFungsi filter akan bekerja dengan request AJAX ke server.`);
            });
        });
        
        // Fungsi download
        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const docTitle = this.closest('.document-card').querySelector('.card-title a').textContent;
                
                alert(`Mengunduh risalah: "${docTitle}"\n\nDokumen akan mulai diunduh dalam beberapa detik.`);
                
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
                
                if (this.querySelector('.bi-camera-video')) {
                    alert(`Membuka rekaman video rapat: "${docTitle}"\n\nDalam implementasi real, akan membuka player video.`);
                } else {
                    alert(`Membuka preview risalah: "${docTitle}"\n\nPreview akan dibuka di tab baru.`);
                }
            });
        });
        
        // Accordion functionality
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (this.classList.contains('collapsed')) {
                    icon.className = 'bi bi-chevron-down me-2';
                } else {
                    icon.className = 'bi bi-chevron-up me-2';
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