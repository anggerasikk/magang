<?php
// beranda.php
$pageTitle = 'Beranda - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title">Selamat Datang di JDIH<br>Sekretariat DPRD Surakarta</h1>
                    <p class="hero-subtitle">
                        Pusat Dokumentasi dan Informasi Hukum yang menyediakan akses mudah
                        terhadap seluruh produk hukum DPRD Kota Surakarta
                    </p>

                    <!-- SEARCH BOX -->
                    <div class="search-box mt-5">
                        <input type="text" class="search-input" placeholder="Cari produk hukum, peraturan, atau informasi...">
                        <button class="search-btn">
                            <i class="bi bi-search me-2"></i>CARI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- WELCOME SECTION -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">Kata Pengantar</h2>
                    <div class="welcome-text">
                        <p>
                            Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                            hadir sebagai sarana penyebarluasan produk hukum secara online, sebagai media 
                            publikasi, serta menyediakan akses yang mudah dan cepat untuk informasi hukum. 
                            Dengan demikian, diharapkan jajaran sekretariat Dewan Perwakilan Rakyat Daerah 
                            Kota Surakarta, masyarakat kota Surakarta, dan semua pihak yang memerlukan 
                            dapat mengakses dan mendapatkan informasi hukum yang tepat dan akurat.
                        </p>
                        <p>
                            <strong>"JDIH membawa hukum langsung dalam genggaman Anda"</strong>
                        </p>
                        
                        <div class="signature-box">
                            <p class="signature-name">KINKIN SULTANIL HAKIM, SH, MM</p>
                            <p class="signature-title">SEKRETARIS DPRD KOTA SURAKARTA</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- STATISTICS -->
            <div class="stats-section rounded-3">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-4 mb-md-0">
                            <div class="stat-item">
                                <div class="stat-number">1,250+</div>
                                <div class="stat-label">Produk Hukum</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4 mb-md-0">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Peraturan Daerah</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">750+</div>
                                <div class="stat-label">Keputusan DPRD</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">100+</div>
                                <div class="stat-label">Artikel Hukum</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- QUICK LINKS -->
            <div class="row mt-5">
                <div class="col-12 mb-5">
                    <h2 class="section-title">Akses Cepat</h2>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-file-text"></i>
                        <h5>Keputusan DPRD</h5>
                        <p>Akses seluruh keputusan yang telah ditetapkan oleh DPRD Kota Surakarta</p>
                        <a href="?page=keputusan-dprd" class="btn custom-btn">Lihat Keputusan</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-journal-text"></i>
                        <h5>Peraturan DPRD</h5>
                        <p>Kumpulan peraturan yang berlaku di lingkungan DPRD Kota Surakarta</p>
                        <a href="?page=peraturan-dprd" class="btn custom-btn">Lihat Peraturan</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="custom-block">
                        <i class="bi bi-info-circle"></i>
                        <h5>Informasi Hukum</h5>
                        <p>Sumber informasi hukum terkini dan terpercaya untuk masyarakat</p>
                        <a href="?page=informasi" class="btn custom-btn">Selengkapnya</a>
                    </div>
                </div>
            </div>
            
            <!-- LATEST NEWS -->
            <div class="row mt-5">
                <div class="col-12 mb-5">
                    <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Pelatihan JDIH" class="news-image">
                        <div class="news-content">
                            <span class="news-date">15 Desember 2024</span>
                            <h5 class="news-title">Pelatihan Pengelolaan JDIH untuk Staf Sekretariat</h5>
                            <p class="news-excerpt">Pelatihan intensif untuk meningkatkan kompetensi pengelola JDIH dalam mengelola informasi hukum.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Sosialisasi Perda" class="news-image">
                        <div class="news-content">
                            <span class="news-date">10 Desember 2024</span>
                            <h5 class="news-title">Sosialisasi Peraturan Daerah Terbaru</h5>
                            <p class="news-excerpt">Kegiatan sosialisasi peraturan daerah terbaru kepada masyarakat Kota Surakarta.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Penghargaan JDIH" class="news-image">
                        <div class="news-content">
                            <span class="news-date">5 Desember 2024</span>
                            <h5 class="news-title">JDIH DPRD Surakarta Raih Penghargaan Nasional</h5>
                            <p class="news-excerpt">JDIH DPRD Kota Surakarta menerima penghargaan sebagai sistem dokumentasi hukum terbaik tingkat nasional.</p>
                            <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>