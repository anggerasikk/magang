<?php
// yurisprudensi.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Yurisprudensi - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
    
    <!-- NAVBAR CSS -->
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        /* OVERRIDE UNTUK HALAMAN YURISPRUDENSI */
        body {
            padding-top: 80px;
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
        
        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
        }
        
        .page-subtitle {
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
        
        /* SEARCH AND FILTER */
        .search-filter-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .search-container {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .search-input {
            flex: 1;
            height: 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0 20px;
            font-size: 1rem;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0 30px;
            font-weight: 600;
        }
        
        .filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-select {
            flex: 1;
            min-width: 200px;
            height: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0 15px;
            color: #13547a;
            font-weight: 500;
        }
        
        /* CASES GRID */
        .cases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .case-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .case-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .case-header {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .case-number {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .case-year {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
        }
        
        .case-content {
            padding: 25px;
        }
        
        .case-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            font-size: 1.2rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .case-details {
            margin-bottom: 20px;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .detail-label {
            width: 120px;
            color: #666;
            font-weight: 500;
        }
        
        .detail-value {
            flex: 1;
            color: #333;
        }
        
        .case-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #555;
        }
        
        .case-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view, .btn-download {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-view {
            background: #f8f9fa;
            border: 2px solid #13547a;
            color: #13547a;
        }
        
        .btn-view:hover {
            background: #13547a;
            color: white;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border: 2px solid transparent;
            color: white;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }
        
        /* PAGINATION */
        .pagination {
            justify-content: center;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .search-btn {
                width: 100%;
                height: 50px;
            }
            
            .cases-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-select {
                min-width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .case-content {
                padding: 20px;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR SAMA DENGAN BERANDA -->
    <!-- ... (salin navbar dari beranda.php) ... -->

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title">Yurisprudensi</h1>
                    <p class="page-subtitle">
                        Kumpulan putusan pengadilan yang menjadi acuan dalam penegakan hukum
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- SEARCH AND FILTER -->
            <div class="search-filter-section">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Cari putusan pengadilan, nomor perkara, atau kata kunci...">
                    <button class="search-btn">
                        <i class="bi bi-search me-2"></i>CARI
                    </button>
                </div>
                
                <div class="filter-options">
                    <select class="filter-select">
                        <option value="">Semua Jenis Perkara</option>
                        <option value="pidana">Pidana</option>
                        <option value="perdata">Perdata</option>
                        <option value="tata_usaha">Tata Usaha Negara</option>
                        <option value="mahkamah_konstitusi">Mahkamah Konstitusi</option>
                    </select>
                    
                    <select class="filter-select">
                        <option value="">Semua Tingkat Pengadilan</option>
                        <option value="ptun">Pengadilan Tata Usaha Negara</option>
                        <option value="pn">Pengadilan Negeri</option>
                        <option value="pt">Pengadilan Tinggi</option>
                        <option value="ma">Mahkamah Agung</option>
                        <option value="mk">Mahkamah Konstitusi</option>
                    </select>
                    
                    <select class="filter-select">
                        <option value="">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                </div>
            </div>
            
            <!-- CASES LIST -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">Putusan Terbaru</h2>
                </div>
            </div>
            
            <div class="cases-grid">
                <!-- Case 1 -->
                <div class="case-card">
                    <div class="case-header">
                        <span class="case-number">Putusan No. 123/PID/PTUN/SKA/2024</span>
                        <span class="case-year">2024</span>
                    </div>
                    <div class="case-content">
                        <h3 class="case-title">Sengketa Tata Usaha Negara tentang IMB</h3>
                        
                        <div class="case-details">
                            <div class="detail-item">
                                <span class="detail-label">Pengadilan:</span>
                                <span class="detail-value">PTUN Surakarta</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jenis:</span>
                                <span class="detail-value">Tata Usaha Negara</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal:</span>
                                <span class="detail-value">15 November 2024</span>
                            </div>
                        </div>
                        
                        <div class="case-summary">
                            Putusan tentang sengketa Izin Mendirikan Bangunan (IMB) antara pemohon 
                            dengan Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surakarta.
                        </div>
                        
                        <div class="case-actions">
                            <a href="?page=detail-putusan&id=1" class="btn-view">Baca Putusan</a>
                            <a href="#" class="btn-download">
                                <i class="bi bi-download me-2"></i>PDF
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Case 2 -->
                <div class="case-card">
                    <div class="case-header">
                        <span class="case-number">Putusan No. 456/PDT/PN/SKA/2024</span>
                        <span class="case-year">2024</span>
                    </div>
                    <div class="case-content">
                        <h3 class="case-title">Sengketa Perdata tentang Tanah</h3>
                        
                        <div class="case-details">
                            <div class="detail-item">
                                <span class="detail-label">Pengadilan:</span>
                                <span class="detail-value">PN Surakarta</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jenis:</span>
                                <span class="detail-value">Perdata</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal:</span>
                                <span class="detail-value">5 November 2024</span>
                            </div>
                        </div>
                        
                        <div class="case-summary">
                            Penyelesaian sengketa kepemilikan tanah antara pihak keluarga dengan 
                            pengembang perumahan di wilayah Kota Surakarta.
                        </div>
                        
                        <div class="case-actions">
                            <a href="?page=detail-putusan&id=2" class="btn-view">Baca Putusan</a>
                            <a href="#" class="btn-download">
                                <i class="bi bi-download me-2"></i>PDF
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Case 3 -->
                <div class="case-card">
                    <div class="case-header">
                        <span class="case-number">Putusan No. 789/PID/PT/SKA/2023</span>
                        <span class="case-year">2023</span>
                    </div>
                    <div class="case-content">
                        <h3 class="case-title">Perkara Pidana Korupsi APBD</h3>
                        
                        <div class="case-details">
                            <div class="detail-item">
                                <span class="detail-label">Pengadilan:</span>
                                <span class="detail-value">PT Jawa Tengah</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jenis:</span>
                                <span class="detail-value">Pidana Khusus</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal:</span>
                                <span class="detail-value">20 Agustus 2023</span>
                            </div>
                        </div>
                        
                        <div class="case-summary">
                            Perkara tindak pidana korupsi dalam pengelolaan Anggaran Pendapatan 
                            dan Belanja Daerah (APBD) Kota Surakarta.
                        </div>
                        
                        <div class="case-actions">
                            <a href="?page=detail-putusan&id=3" class="btn-view">Baca Putusan</a>
                            <a href="#" class="btn-download">
                                <i class="bi bi-download me-2"></i>PDF
                            </a>
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
    </section>

    <!-- FOOTER SAMA DENGAN BERANDA -->
    <!-- ... (salin footer dari beranda.php) ... -->

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchBtn = document.querySelector('.search-btn');
            const searchInput = document.querySelector('.search-input');
            
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
                    alert('Mencari yurisprudensi: ' + query);
                    // Implement actual search logic here
                } else {
                    alert('Silakan masukkan kata kunci pencarian');
                }
            }
            
            // Filter functionality
            const filterSelects = document.querySelectorAll('.filter-select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    applyFilters();
                });
            });
            
            function applyFilters() {
                // Implement filter logic here
                console.log('Filters applied');
            }
            
            // Navbar scroll effect
            const navbar = document.querySelector('.navbar-custom');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        });
    </script>

</body>
</html>