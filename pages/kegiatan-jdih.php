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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
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
            padding: 30px;
            margin-bottom: 50px;
        }
        
        .filter-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .filter-btn {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            color: #13547a;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .filter-btn:hover {
            background: #e9ecef;
            border-color: #13547a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(19, 84, 122, 0.3);
        }
        
        /* ACTIVITY CARDS */
        .activity-cards-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .activity-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .activity-card:hover .activity-image {
            transform: scale(1.05);
        }
        
        .activity-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .activity-meta {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .activity-date {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .activity-category {
            color: #80d0c7;
            font-weight: 600;
            font-size: 0.9rem;
            background: #f0f8ff;
            padding: 6px 16px;
            border-radius: 50px;
        }
        
        .activity-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.2rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .activity-excerpt {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.7;
            font-size: 0.95rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }
        
        .activity-stats {
            display: flex;
            align-items: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }
        
        .activity-stats i {
            margin-right: 5px;
            color: #13547a;
        }
        
        .activity-stats span {
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
        }
        
        .read-more-btn {
            background: transparent;
            border: 2px solid #13547a;
            color: #13547a;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }
        
        .read-more-btn:hover {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(19, 84, 122, 0.3);
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
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: #f8f9fa;
            border-color: #13547a;
            color: #13547a;
            transform: translateY(-2px);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-color: transparent;
            color: white;
        }
        
        .page-item.disabled .page-link {
            color: #ccc;
            border-color: #e0e0e0;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .activity-cards-wrapper {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 25px;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .activity-filter {
                padding: 20px;
            }
            
            .filter-title {
                font-size: 1.1rem;
            }
            
            .filter-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            
            .activity-cards-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 0.95rem;
            }
            
            .activity-content {
                padding: 20px;
            }
            
            .activity-title {
                font-size: 1.1rem;
            }
            
            .activity-excerpt {
                font-size: 0.9rem;
            }
            
            .activity-stats span {
                margin-right: 15px;
                font-size: 0.85rem;
            }
            
            .read-more-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                width: 100%;
            }
        }
    </style>
</head>

<body id="top">

    <?php include 'header.php'; ?>

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
                <h4 class="filter-title"><i class="bi bi-funnel me-2"></i>Filter Kegiatan</h4>
                <div class="filter-tags">
                    <button class="filter-btn active" data-filter="all">Semua Kegiatan</button>
                    <button class="filter-btn" data-filter="sosialisasi">Sosialisasi</button>
                    <button class="filter-btn" data-filter="pelatihan">Pelatihan</button>
                    <button class="filter-btn" data-filter="seminar">Seminar</button>
                    <button class="filter-btn" data-filter="workshop">Workshop</button>
                    <button class="filter-btn" data-filter="lainnya">Lainnya</button>
                </div>
            </div>
            
            <!-- SECTION TITLE -->
            <h2 class="section-title"><i class="bi bi-calendar-event me-2"></i>Kegiatan Terbaru</h2>
            
            <!-- ACTIVITY CARDS -->
            <div class="activity-cards-wrapper">
                <!-- Activity 1 -->
                <div class="activity-card" data-category="sosialisasi">
                    <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Sosialisasi JDIH" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 25 Nov 2024
                            </span>
                            <span class="activity-category">Sosialisasi</span>
                        </div>
                        <h3 class="activity-title">Sosialisasi JDIH Setwan Kota Surakarta Meriahkan CFD</h3>
                        <p class="activity-excerpt">
                            Kegiatan sosialisasi JDIH dalam rangka Car Free Day dengan berbagai aktivitas 
                            menarik termasuk senam zumba dan edukasi hukum untuk masyarakat.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 613</span>
                            <span><i class="bi bi-heart-fill"></i> 45</span>
                            <span><i class="bi bi-chat-fill"></i> 12</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=1" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
                
                <!-- Activity 2 -->
                <div class="activity-card" data-category="pelatihan">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Pelatihan Digital" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 10 Jan 2021
                            </span>
                            <span class="activity-category">Pelatihan</span>
                        </div>
                        <h3 class="activity-title">Perkuat Infrastruktur Digital, Biro Hukum Gelar Pelatihan</h3>
                        <p class="activity-excerpt">
                            Pelatihan intensif untuk meningkatkan kompetensi staf dalam mengelola 
                            infrastruktur digital JDIH secara efektif dan efisien.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 450</span>
                            <span><i class="bi bi-heart-fill"></i> 32</span>
                            <span><i class="bi bi-chat-fill"></i> 8</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=2" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
                
                <!-- Activity 3 -->
                <div class="activity-card" data-category="seminar">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Public Hearing" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 10 Jan 2021
                            </span>
                            <span class="activity-category">Seminar</span>
                        </div>
                        <h3 class="activity-title">Pansus Gelar Public Hearing terkait Perencanaan Pembangunan</h3>
                        <p class="activity-excerpt">
                            Public hearing yang membahas rencana pembangunan dan pengembangan 
                            sistem informasi hukum yang terintegrasi.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 890</span>
                            <span><i class="bi bi-heart-fill"></i> 67</span>
                            <span><i class="bi bi-chat-fill"></i> 23</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=3" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
                
                <!-- Activity 4 -->
                <div class="activity-card" data-category="workshop">
                    <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Workshop Hukum" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 8 Jan 2021
                            </span>
                            <span class="activity-category">Workshop</span>
                        </div>
                        <h3 class="activity-title">Workshop Penyusunan Produk Hukum Digital</h3>
                        <p class="activity-excerpt">
                            Workshop intensif mengenai teknik penyusunan dan publikasi produk hukum 
                            dalam format digital yang mudah diakses masyarakat.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 320</span>
                            <span><i class="bi bi-heart-fill"></i> 28</span>
                            <span><i class="bi bi-chat-fill"></i> 5</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=4" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
                
                <!-- Activity 5 -->
                <div class="activity-card" data-category="sosialisasi">
                    <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Edukasi Masyarakat" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 5 Jan 2021
                            </span>
                            <span class="activity-category">Sosialisasi</span>
                        </div>
                        <h3 class="activity-title">Edukasi Hukum untuk Masyarakat Umum</h3>
                        <p class="activity-excerpt">
                            Program edukasi hukum kepada masyarakat untuk meningkatkan pemahaman 
                            tentang hak dan kewajiban berdasarkan peraturan yang berlaku.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 560</span>
                            <span><i class="bi bi-heart-fill"></i> 41</span>
                            <span><i class="bi bi-chat-fill"></i> 15</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=5" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
                
                <!-- Activity 6 -->
                <div class="activity-card" data-category="lainnya">
                    <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Penandatanganan MOU" class="activity-image">
                    <div class="activity-content">
                        <div class="activity-meta">
                            <span class="activity-date">
                                <i class="bi bi-calendar3"></i> 2 Jan 2021
                            </span>
                            <span class="activity-category">Kerjasama</span>
                        </div>
                        <h3 class="activity-title">Penandatanganan MOU dengan Universitas Lokal</h3>
                        <p class="activity-excerpt">
                            Kerjasama strategis dengan universitas lokal untuk pengembangan 
                            sistem JDIH dan peningkatan kapasitas sumber daya manusia.
                        </p>
                        <div class="activity-stats">
                            <span><i class="bi bi-eye-fill"></i> 780</span>
                            <span><i class="bi bi-heart-fill"></i> 58</span>
                            <span><i class="bi bi-chat-fill"></i> 19</span>
                        </div>
                        <a href="?page=detail-kegiatan&id=6" class="read-more-btn">Baca Selengkapnya</a>
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

    <?php include 'footer.php'; ?>

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
            const activityCards = document.querySelectorAll('[data-category]');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Show/hide activity cards based on filter
                    activityCards.forEach(card => {
                        const cardParent = card.closest('.activity-card');
                        if (filterValue === 'all') {
                            cardParent.style.display = 'flex';
                        } else {
                            const category = card.getAttribute('data-category');
                            if (category === filterValue) {
                                cardParent.style.display = 'flex';
                            } else {
                                cardParent.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>