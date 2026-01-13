<?php
// kegiatan-jdih.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kegiatan JDIH - JDIH DPRD Kota Surakarta</title>
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
        /* OVERRIDE UNTUK HALAMAN KEGIATAN */
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
        
        /* ACTIVITY FILTER */
        .activity-filter {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 40px;
        }
        
        .filter-btn {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            color: #13547a;
            padding: 10px 25px;
            border-radius: 50px;
            margin-right: 10px;
            margin-bottom: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
        }
        
        /* ACTIVITY GRID */
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
        }
        
        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .activity-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .activity-content {
            padding: 25px;
        }
        
        .activity-meta {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .activity-date {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-right: 15px;
        }
        
        .activity-category {
            color: #80d0c7;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .activity-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.3rem;
            line-height: 1.4;
        }
        
        .activity-excerpt {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .activity-stats {
            display: flex;
            align-items: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .activity-stats i {
            margin-right: 5px;
        }
        
        .activity-stats span {
            margin-right: 20px;
        }
        
        .read-more-btn {
            background: transparent;
            border: 2px solid #13547a;
            color: #13547a;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        
        .read-more-btn:hover {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
        }
        
        /* PAGINATION */
        .pagination {
            justify-content: center;
            margin-top: 50px;
        }
        
        .page-link {
            color: #13547a;
            border: 2px solid #e0e0e0;
            margin: 0 5px;
            border-radius: 10px;
            font-weight: 600;
            min-width: 45px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .page-link:hover {
            background: #f8f9fa;
            border-color: #13547a;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-color: transparent;
            color: white;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .activity-filter {
                padding: 20px;
            }
            
            .filter-btn {
                padding: 8px 20px;
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
            
            .activity-content {
                padding: 20px;
            }
            
            .activity-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR SAMA DENGAN BERANDA -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <!-- BRAND -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <span class="fw-bold">JDIH DPRD</span>
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>

                    <!-- Menu lainnya sama seperti beranda -->
                    <!-- ... (salin seluruh menu dari beranda.php) ... -->
                    
                    <!-- INFORMASI -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownInformasi"
                            role="button" data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="kegiatan-jdih.php">Kegiatan JDIH</a></li>
                            <li><a class="dropdown-item" href="infografis.php">Infografis & Videografis</a></li>
                            <li><a class="dropdown-item" href="galeri.php">Galeri Foto</a></li>
                            <li><a class="dropdown-item" href="penghargaan.php">Penghargaan</a></li>
                            <li><a class="dropdown-item" href="buku.php">Koleksi Buku</a></li>
                        </ul>
                    </li>

                    <!-- Menu lainnya ... -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title">Kegiatan JDIH</h1>
                    <p class="page-subtitle">
                        Dokumentasi kegiatan dan aktivitas Jaringan Dokumentasi dan Informasi Hukum 
                        Sekretariat DPRD Kota Surakarta
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- FILTER SECTION -->
            <div class="activity-filter">
                <h4 class="mb-4" style="color: #13547a;">Filter Kegiatan</h4>
                <div class="filter-tags">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="sosialisasi">Sosialisasi</button>
                    <button class="filter-btn" data-filter="pelatihan">Pelatihan</button>
                    <button class="filter-btn" data-filter="seminar">Seminar</button>
                    <button class="filter-btn" data-filter="workshop">Workshop</button>
                    <button class="filter-btn" data-filter="lainnya">Lainnya</button>
                </div>
            </div>
            
            <!-- ACTIVITY LIST -->
            <div class="row">
                <div class="col-12 mb-5">
                    <h2 class="section-title">Kegiatan Terbaru</h2>
                </div>
                
                <!-- Activity 1 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="sosialisasi">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Sosialisasi JDIH" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">25 Nov 2024</span>
                                <span class="activity-category">Sosialisasi</span>
                            </div>
                            <h3 class="activity-title">Sosialisasi JDIH Setwan Kota Surakarta Meriahkan CFD</h3>
                            <p class="activity-excerpt">
                                Kegiatan sosialisasi JDIH dalam rangka Car Free Day dengan berbagai aktivitas 
                                menarik termasuk senam zumba dan edukasi hukum.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 613</span>
                                <span><i class="bi bi-heart"></i> 45</span>
                                <span><i class="bi bi-chat"></i> 12</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=1" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 2 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="pelatihan">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Pelatihan Digital" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">10 Jan 2021</span>
                                <span class="activity-category">Pelatihan</span>
                            </div>
                            <h3 class="activity-title">Perkuat Infrastruktur Digital, Biro Hukum Gelar Pelatihan</h3>
                            <p class="activity-excerpt">
                                Pelatihan intensif untuk meningkatkan kompetensi staf dalam mengelola 
                                infrastruktur digital JDIH secara efektif dan efisien.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 450</span>
                                <span><i class="bi bi-heart"></i> 32</span>
                                <span><i class="bi bi-chat"></i> 8</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=2" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 3 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="seminar">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Public Hearing" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">10 Jan 2021</span>
                                <span class="activity-category">Seminar</span>
                            </div>
                            <h3 class="activity-title">Pansus Gelar Public Hearing terkait Perencanaan Pembangunan</h3>
                            <p class="activity-excerpt">
                                Public hearing yang membahas rencana pembangunan dan pengembangan 
                                sistem informasi hukum yang terintegrasi.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 890</span>
                                <span><i class="bi bi-heart"></i> 67</span>
                                <span><i class="bi bi-chat"></i> 23</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=3" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 4 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="workshop">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Workshop Hukum" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">8 Jan 2021</span>
                                <span class="activity-category">Workshop</span>
                            </div>
                            <h3 class="activity-title">Workshop Penyusunan Produk Hukum Digital</h3>
                            <p class="activity-excerpt">
                                Workshop intensif mengenai teknik penyusunan dan publikasi produk hukum 
                                dalam format digital yang mudah diakses masyarakat.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 320</span>
                                <span><i class="bi bi-heart"></i> 28</span>
                                <span><i class="bi bi-chat"></i> 5</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=4" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 5 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="sosialisasi">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Edukasi Masyarakat" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">5 Jan 2021</span>
                                <span class="activity-category">Sosialisasi</span>
                            </div>
                            <h3 class="activity-title">Edukasi Hukum untuk Masyarakat Umum</h3>
                            <p class="activity-excerpt">
                                Program edukasi hukum kepada masyarakat untuk meningkatkan pemahaman 
                                tentang hak dan kewajiban berdasarkan peraturan yang berlaku.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 560</span>
                                <span><i class="bi bi-heart"></i> 41</span>
                                <span><i class="bi bi-chat"></i> 15</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=5" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 6 -->
                <div class="col-lg-4 col-md-6 mb-4" data-category="lainnya">
                    <div class="activity-card">
                        <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Penandatanganan MOU" class="activity-image">
                        <div class="activity-content">
                            <div class="activity-meta">
                                <span class="activity-date">2 Jan 2021</span>
                                <span class="activity-category">Kerjasama</span>
                            </div>
                            <h3 class="activity-title">Penandatanganan MOU dengan Universitas Lokal</h3>
                            <p class="activity-excerpt">
                                Kerjasama strategis dengan universitas lokal untuk pengembangan 
                                sistem JDIH dan peningkatan kapasitas sumber daya manusia.
                            </p>
                            <div class="activity-stats">
                                <span><i class="bi bi-eye"></i> 780</span>
                                <span><i class="bi bi-heart"></i> 58</span>
                                <span><i class="bi bi-chat"></i> 19</span>
                            </div>
                            <a href="?page=detail-kegiatan&id=6" class="read-more-btn">Baca Selengkapnya</a>
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
    <footer class="site-footer section-padding">
        <div class="container">
            <!-- ... (salin footer dari beranda.php) ... -->
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        // Filter functionality for activities
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const activityCards = document.querySelectorAll('.activity-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Show/hide activity cards based on filter
                    activityCards.forEach(card => {
                        if (filterValue === 'all') {
                            card.style.display = 'block';
                        } else {
                            const category = card.getAttribute('data-category');
                            if (category === filterValue) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
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