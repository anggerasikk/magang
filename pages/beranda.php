<?php
// beranda.php
$pageTitle = 'Beranda - JDIH DPRD Kota Surakarta';
include 'header.php';
?>
<style>
<?php include "beranda.css"; ?>
</style>
<!-- Hero Section with Video Background -->
<section class="hero-section">
    <!-- Video Background -->
    <div class="video-background">
        <div id="youtube-player"></div>
        <div class="video-overlay"></div>
    </div>
    
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="hero-title animate-fade-in">Selamat Datang di JDIH<br>Sekretariat DPRD Surakarta</h1>
                <p class="hero-subtitle animate-fade-in-delay">
                    Pusat Dokumentasi dan Informasi Hukum yang menyediakan akses mudah<br>
                    terhadap seluruh produk hukum DPRD Kota Surakarta
                </p>

                <!-- Search Box -->
                <div class="search-box-wrapper mt-4 animate-slide-up">
                    <div class="search-box-container">
                        <div class="search-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" class="search-input" placeholder="Cari produk hukum, peraturan, atau informasi...">
                        <button class="search-btn">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                    <div class="search-suggestions">
                        <span class="suggestion-label">Pencarian Populer:</span>
                        <span class="suggestion-tag">Perda</span>
                        <span class="suggestion-tag">Keputusan DPRD</span>
                        <span class="suggestion-tag">Peraturan</span>
                        <span class="suggestion-tag">Artikel Hukum</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Main Content -->
<section class="main-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-card">
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
                        <div class="quote-box">
                            <i class="bi bi-quote quote-icon"></i>
                            <p class="quote-text">JDIH membawa hukum langsung dalam genggaman Anda</p>
                        </div>
                        
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-name">KINKIN SULTANIL HAKIM, SH, MM</p>
                            <p class="signature-title">Sekretaris DPRD Kota Surakarta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Akses Cepat</h2>
                <p class="section-subtitle">Jelajahi berbagai kategori informasi hukum</p>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-file-text card-icon"></i>
                    </div>
                    <h5>Keputusan DPRD</h5>
                    <p>Akses seluruh keputusan yang telah ditetapkan oleh DPRD Kota Surakarta</p>
                    <a href="?page=keputusan-dprd" class="btn-custom">
                        Lihat Keputusan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-journal-text card-icon"></i>
                    </div>
                    <h5>Peraturan DPRD</h5>
                    <p>Kumpulan peraturan yang berlaku di lingkungan DPRD Kota Surakarta</p>
                    <a href="?page=peraturan-dprd" class="btn-custom">
                        Lihat Peraturan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-info-circle card-icon"></i>
                    </div>
                    <h5>Informasi Hukum</h5>
                    <p>Sumber informasi hukum terkini dan terpercaya untuk masyarakat</p>
                    <a href="?page=informasi" class="btn-custom">
                        Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Latest News -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                <p class="section-subtitle">Update terkini seputar kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Pelatihan JDIH" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Pelatihan</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>15 Desember 2024</span>
                        <h5 class="news-title">Pelatihan Pengelolaan JDIH untuk Staf Sekretariat</h5>
                        <p class="news-excerpt">Pelatihan intensif untuk meningkatkan kompetensi pengelola JDIH dalam mengelola informasi hukum.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Sosialisasi Perda" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Sosialisasi</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>10 Desember 2024</span>
                        <h5 class="news-title">Sosialisasi Peraturan Daerah Terbaru</h5>
                        <p class="news-excerpt">Kegiatan sosialisasi peraturan daerah terbaru kepada masyarakat Kota Surakarta.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                             alt="Penghargaan JDIH" class="news-image">
                        <div class="news-overlay">
                            <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i>Penghargaan</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <span class="news-date"><i class="bi bi-calendar3 me-2"></i>5 Desember 2024</span>
                        <h5 class="news-title">JDIH DPRD Surakarta Raih Penghargaan Nasional</h5>
                        <p class="news-excerpt">JDIH DPRD Kota Surakarta menerima penghargaan sebagai sistem dokumentasi hukum terbaik tingkat nasional.</p>
                        <a href="#" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instagram Section -->
        <div class="row">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Aktivitas Terbaru di Instagram</h2>
                <p class="section-subtitle">Ikuti perkembangan terbaru kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <div class="col-12">
                <div class="instagram-section">
                    <div class="row justify-content-center g-4">
                        <!-- Instagram Post 1 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DS69KHrj-1o/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 2 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DTPydpoEulZ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 3 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DRorvdjj6EE/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instagram Follow Button -->
                    <div class="text-center mt-4">
                        <a href="https://www.instagram.com/setwansurakarta/" target="_blank" class="btn-instagram">
                            <i class="bi bi-instagram me-2"></i> Ikuti @setwansurakarta di Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="stats-section">
    <div class="container">
        <div class="text-center mb-5 stats-title">
            <small>★ STATISTIK & DATA ★</small>
            <h2>Statistik Produk Hukum</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="stats-card">
                    <h5>Persentase Produk Hukum</h5>
                    <canvas id="pieProdukHukum"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="stats-card">
                    <h5>Statistik Dokumen</h5>
                    <small>Tren 10 Tahun Terakhir</small>
                    <canvas id="barProdukHukum"></canvas>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="index.php?page=statistik" class="btn btn-stats">
                Data Statistik Produk JDIH
            </a>
        </div>
    </div>
</section>


<!-- YouTube API Script -->
<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            videoId: 'Yx_5XjMMy9k',
            playerVars: {
                autoplay: 1,
                controls: 0,
                showinfo: 0,
                modestbranding: 1,
                loop: 1,
                playlist: 'Yx_5XjMMy9k',
                fs: 0,
                cc_load_policy: 0,
                iv_load_policy: 3,
                autohide: 1,
                mute: 1
            },
            events: {
                onReady: onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        event.target.mute();
        event.target.playVideo();
    }
    
    // Navbar scroll effect - hapus atau komentari kode ini
    /*
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-custom');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    */
</script>

<!-- Instagram Embed Script -->
<script async src="//www.instagram.com/embed.js"></script>
<!-- Chart.js (WAJIB ADA) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* PIE CHART */
    const pieCtx = document.getElementById('pieProdukHukum');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: [
                    'Keputusan DPRD',
                    'Keputusan Sekretaris DPRD',
                    'Keputusan Pimpinan DPRD',
                    'Peraturan Daerah',
                    'Peraturan DPRD',
                    'English Legal Products'
                ],
                datasets: [{
                    data: [55.1, 17.7, 6.0, 16.6, 1.8, 2.9],
                    backgroundColor: [
                        '#5b5fdc',
                        '#ff7a45',
                        '#2ecc71',
                        '#7f9cf5',
                        '#a855f7',
                        '#38bdf8'
                    ]
                }]
            }
        });
    }

    /* BAR CHART */
    const barCtx = document.getElementById('barProdukHukum');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['2017','2018','2019','2020','2021','2022','2023','2024','2025'],
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: [5, 12, 18, 50, 22, 110, 85, 62, 78],
                    backgroundColor: '#c6ff4d'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

});
</script>


<?php include 'footer.php'; ?>