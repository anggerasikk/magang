<?php
// infografis.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Infografis & Videografis - JDIH DPRD Kota Surakarta</title>
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
        /* OVERRIDE UNTUK HALAMAN INFOGRAFIS */
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
        
        /* TABS */
        .nav-tabs {
            border: none;
            justify-content: center;
            margin-bottom: 40px;
        }
        
        .nav-tabs .nav-link {
            border: 2px solid #e0e0e0;
            color: #13547a;
            padding: 12px 30px;
            border-radius: 50px;
            margin: 0 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: #13547a;
            background: #f8f9fa;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
        }
        
        /* INFOGRAFIS GRID */
        .infographic-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
        }
        
        .infographic-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .infographic-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .infographic-content {
            padding: 25px;
        }
        
        .infographic-category {
            display: inline-block;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .infographic-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.2rem;
            line-height: 1.4;
        }
        
        .infographic-stats {
            display: flex;
            align-items: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .infographic-stats i {
            margin-right: 5px;
        }
        
        .infographic-stats span {
            margin-right: 20px;
        }
        
        .download-btn {
            background: transparent;
            border: 2px solid #13547a;
            color: #13547a;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            width: 100%;
            justify-content: center;
        }
        
        .download-btn:hover {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
        }
        
        /* VIDEO GALLERY */
        .video-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .video-thumbnail {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        
        .video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(19, 84, 122, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: all 0.3s;
        }
        
        .video-play-btn:hover {
            background: rgba(19, 84, 122, 1);
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .video-content {
            padding: 20px;
        }
        
        .video-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .video-meta {
            color: #888;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .nav-tabs .nav-link {
                padding: 10px 20px;
                margin: 0 5px 10px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .infographic-content,
            .video-content {
                padding: 15px;
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
                    <h1 class="page-title">Infografis & Videografis</h1>
                    <p class="page-subtitle">
                        Informasi hukum dalam bentuk visual yang menarik dan mudah dipahami
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- TABS -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="infografis-tab" data-bs-toggle="tab" 
                            data-bs-target="#infografis" type="button" role="tab">
                        <i class="bi bi-bar-chart me-2"></i>Infografis
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="videografis-tab" data-bs-toggle="tab" 
                            data-bs-target="#videografis" type="button" role="tab">
                        <i class="bi bi-camera-video me-2"></i>Videografis
                    </button>
                </li>
            </ul>
            
            <!-- TAB CONTENT -->
            <div class="tab-content" id="myTabContent">
                <!-- INFOGRAFIS TAB -->
                <div class="tab-pane fade show active" id="infografis" role="tabpanel">
                    <div class="row mb-5">
                        <div class="col-12">
                            <h2 class="section-title">Koleksi Infografis</h2>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Infografis 1 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="infographic-card">
                                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                     alt="Proses Legislasi" class="infographic-image">
                                <div class="infographic-content">
                                    <span class="infographic-category">Proses Legislasi</span>
                                    <h3 class="infographic-title">Alur Penyusunan Peraturan Daerah</h3>
                                    <p>Visualisasi tahapan penyusunan Perda dari inisiasi hingga pengesahan</p>
                                    <div class="infographic-stats">
                                        <span><i class="bi bi-download"></i> 1.2K</span>
                                        <span><i class="bi bi-eye"></i> 4.5K</span>
                                    </div>
                                    <a href="#" class="download-btn">
                                        <i class="bi bi-download me-2"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Infografis 2 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="infographic-card">
                                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                     alt="Statistik Hukum" class="infographic-image">
                                <div class="infographic-content">
                                    <span class="infographic-category">Statistik</span>
                                    <h3 class="infographic-title">Statistik Produk Hukum 2024</h3>
                                    <p>Data perkembangan jumlah produk hukum DPRD Kota Surakarta</p>
                                    <div class="infographic-stats">
                                        <span><i class="bi bi-download"></i> 890</span>
                                        <span><i class="bi bi-eye"></i> 3.2K</span>
                                    </div>
                                    <a href="#" class="download-btn">
                                        <i class="bi bi-download me-2"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Infografis 3 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="infographic-card">
                                <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                     alt="Struktur DPRD" class="infographic-image">
                                <div class="infographic-content">
                                    <span class="infographic-category">Organisasi</span>
                                    <h3 class="infographic-title">Struktur Organisasi DPRD</h3>
                                    <p>Struktur lengkap organisasi Dewan Perwakilan Rakyat Daerah</p>
                                    <div class="infographic-stats">
                                        <span><i class="bi bi-download"></i> 1.5K</span>
                                        <span><i class="bi bi-eye"></i> 5.1K</span>
                                    </div>
                                    <a href="#" class="download-btn">
                                        <i class="bi bi-download me-2"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- VIDEOGRAFIS TAB -->
                <div class="tab-pane fade" id="videografis" role="tabpanel">
                    <div class="row mb-5">
                        <div class="col-12">
                            <h2 class="section-title">Koleksi Videografis</h2>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Video 1 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="video-card">
                                <div class="video-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab2d6?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                         alt="Video Profile JDIH">
                                    <a href="#" class="video-play-btn" data-bs-toggle="modal" data-bs-target="#videoModal1">
                                        <i class="bi bi-play-fill"></i>
                                    </a>
                                </div>
                                <div class="video-content">
                                    <h4 class="video-title">Video Profile JDIH DPRD Surakarta</h4>
                                    <div class="video-meta">
                                        <span><i class="bi bi-clock"></i> 5:30</span>
                                        <span><i class="bi bi-eye"></i> 2.3K</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video 2 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="video-card">
                                <div class="video-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                         alt="Tutorial Penggunaan">
                                    <a href="#" class="video-play-btn" data-bs-toggle="modal" data-bs-target="#videoModal2">
                                        <i class="bi bi-play-fill"></i>
                                    </a>
                                </div>
                                <div class="video-content">
                                    <h4 class="video-title">Tutorial Penggunaan Website JDIH</h4>
                                    <div class="video-meta">
                                        <span><i class="bi bi-clock"></i> 8:15</span>
                                        <span><i class="bi bi-eye"></i> 1.8K</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video 3 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="video-card">
                                <div class="video-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1589903308904-1010c2294adc?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                         alt="Proses Legislasi">
                                    <a href="#" class="video-play-btn" data-bs-toggle="modal" data-bs-target="#videoModal3">
                                        <i class="bi bi-play-fill"></i>
                                    </a>
                                </div>
                                <div class="video-content">
                                    <h4 class="video-title">Proses Legislasi di DPRD Surakarta</h4>
                                    <div class="video-meta">
                                        <span><i class="bi bi-clock"></i> 12:45</span>
                                        <span><i class="bi bi-eye"></i> 3.1K</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO MODALS -->
    <div class="modal fade" id="videoModal1" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Video Profile JDIH DPRD Surakarta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                                title="Video Profile" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER SAMA DENGAN BERANDA -->
    <!-- ... (salin footer dari beranda.php) ... -->

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'));
            triggerTabList.forEach(function (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
            
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