<?php
// penghargaan.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Penghargaan - JDIH DPRD Kota Surakarta</title>
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
        /* OVERRIDE UNTUK HALAMAN PENGHARGAAN */
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
        
        /* TIMELINE */
        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background: linear-gradient(to bottom, #13547a, #80d0c7);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item:nth-child(odd) {
            left: 0;
        }
        
        .timeline-item:nth-child(even) {
            left: 50%;
        }
        
        .timeline-content {
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        
        .timeline-item:nth-child(odd) .timeline-content {
            margin-right: 30px;
        }
        
        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 30px;
        }
        
        .timeline-content::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: #13547a;
            border: 4px solid white;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .timeline-item:nth-child(odd) .timeline-content::after {
            right: -40px;
        }
        
        .timeline-item:nth-child(even) .timeline-content::after {
            left: -40px;
        }
        
        .award-year {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .award-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        
        .award-description {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .award-issuer {
            color: #80d0c7;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        /* AWARD STATS */
        .stats-cards {
            margin-bottom: 60px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border-top: 5px solid #13547a;
        }
        
        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .stats-icon {
            font-size: 3rem;
            color: #13547a;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            color: #13547a;
            margin-bottom: 10px;
        }
        
        .stats-label {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item:nth-child(even) {
                left: 0;
            }
            
            .timeline-content {
                margin: 0 !important;
            }
            
            .timeline-content::after {
                left: -40px !important;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .timeline-content {
                padding: 20px;
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
                    <h1 class="page-title">Penghargaan</h1>
                    <p class="page-subtitle">
                        Prestasi dan penghargaan yang diraih oleh JDIH DPRD Kota Surakarta
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- AWARD STATS -->
            <div class="stats-cards">
                <div class="row">
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="stats-number">15</div>
                            <div class="stats-label">Penghargaan Nasional</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="bi bi-award"></i>
                            </div>
                            <div class="stats-number">8</div>
                            <div class="stats-label">Penghargaan Regional</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="stats-number">5</div>
                            <div class="stats-label">Sertifikasi</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="stats-number">3</div>
                            <div class="stats-label">Akreditasi</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TIMELINE -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">Linimasa Penghargaan</h2>
                </div>
            </div>
            
            <div class="timeline">
                <!-- Award 1 -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="award-year">2024</span>
                        <h3 class="award-title">Juara 1 JDIH Terbaik Tingkat Nasional</h3>
                        <p class="award-description">
                            JDIH DPRD Kota Surakarta meraih penghargaan sebagai Jaringan Dokumentasi 
                            dan Informasi Hukum terbaik se-Indonesia dalam ajang Penghargaan JDIH 
                            Nasional 2024 yang diselenggarakan oleh Kementerian Hukum dan HAM.
                        </p>
                        <p class="award-issuer">
                            <i class="bi bi-building me-2"></i>Kementerian Hukum dan HAM RI
                        </p>
                    </div>
                </div>
                
                <!-- Award 2 -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="award-year">2023</span>
                        <h3 class="award-title">Penghargaan Inovasi Pelayanan Publik</h3>
                        <p class="award-description">
                            Sistem JDIH DPRD Surakarta diakui sebagai inovasi pelayanan publik 
                            terbaik dalam bidang informasi hukum oleh Kementerian Pendayagunaan 
                            Aparatur Negara dan Reformasi Birokrasi.
                        </p>
                        <p class="award-issuer">
                            <i class="bi bi-building me-2"></i>Kementerian PANRB
                        </p>
                    </div>
                </div>
                
                <!-- Award 3 -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="award-year">2022</span>
                        <h3 class="award-title">Sertifikasi ISO 9001:2015</h3>
                        <p class="award-description">
                            Unit JDIH DPRD Kota Surakarta mendapatkan sertifikasi ISO 9001:2015 
                            untuk Sistem Manajemen Mutu dalam pengelolaan informasi dan dokumentasi hukum.
                        </p>
                        <p class="award-issuer">
                            <i class="bi bi-award me-2"></i>Badan Sertifikasi Internasional
                        </p>
                    </div>
                </div>
                
                <!-- Award 4 -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="award-year">2021</span>
                        <h3 class="award-title">Website Terbaik DPRD Se-Jateng</h3>
                        <p class="award-description">
                            Website JDIH DPRD Kota Surakarta dinobatkan sebagai website terbaik 
                            tingkat DPRD se-Jawa Tengah dalam kompetisi yang diselenggarakan 
                            oleh Asosiasi DPRD Kota Seluruh Indonesia.
                        </p>
                        <p class="award-issuer">
                            <i class="bi bi-building me-2"></i>ADKASI Jawa Tengah
                        </p>
                    </div>
                </div>
                
                <!-- Award 5 -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="award-year">2020</span>
                        <h3 class="award-title">Penghargaan Transparansi Informasi</h3>
                        <p class="award-description">
                            JDIH DPRD Surakarta menerima penghargaan dalam kategori transparansi 
                            informasi publik dari Komisi Informasi Provinsi Jawa Tengah.
                        </p>
                        <p class="award-issuer">
                            <i class="bi bi-building me-2"></i>Komisi Informasi Jateng
                        </p>
                    </div>
                </div>
            </div>
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
            // Navbar scroll effect
            const navbar = document.querySelector('.navbar-custom');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Timeline animation
            const timelineItems = document.querySelectorAll('.timeline-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateX(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            timelineItems.forEach((item, index) => {
                item.style.opacity = '0';
                if (index % 2 === 0) {
                    item.style.transform = 'translateX(-50px)';
                } else {
                    item.style.transform = 'translateX(50px)';
                }
                item.style.transition = 'all 0.5s ease';
                observer.observe(item);
            });
        });
    </script>

</body>
</html>