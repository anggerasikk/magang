<?php
// grafis.php

require_once 'a_db.php'; // Pastikan file koneksi database ada
include "header.php";
// Query data dari database
$sql = "SELECT * FROM grafis WHERE status = 'aktif' ORDER BY tanggal_dibuat DESC";
$result = $koneksi->query($sql);

// Pisahkan data berdasarkan tipe
$videografis = [];
$infografis = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['tipe'] == 'videografis') {
            $videografis[] = $row;
        } else {
            $infografis[] = $row;
        }
    }
}
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
                        
    
    <!-- NAVBAR CSS -->

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
            margin-bottom: px;
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
        
        .infographic-description {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .infographic-date {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .infographic-date i {
            margin-right: 8px;
        }
        
        /* VIDEO GALLERY */
        .video-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
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
            transition: transform 0.3s;
        }
        
        .video-card:hover .video-thumbnail img {
            transform: scale(1.05);
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
            cursor: pointer;
            text-decoration: none;
            z-index: 2;
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
            line-height: 1.4;
        }
        
        .video-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .video-meta {
            color: #888;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* MODAL STYLES */
        .modal-video {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .modal-video {
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .modal-video {
                height: 200px;
            }
        }
        
        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #888;
            margin-bottom: 10px;
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
                        <?php if (count($infografis) > 0): ?>
                            <?php foreach ($infografis as $item): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="infographic-card">
                                    <?php if (!empty($item['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['judul']); ?>" 
                                             class="infographic-image">
                                    <?php else: ?>
                                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                             alt="Default Thumbnail" class="infographic-image">
                                    <?php endif; ?>
                                    <div class="infographic-content">
                                        <span class="infographic-category">Infografis</span>
                                        <h3 class="infographic-title"><?php echo htmlspecialchars($item['judul']); ?></h3>
                                        <?php if (!empty($item['deskripsi'])): ?>
                                            <p class="infographic-description"><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                                        <?php endif; ?>
                                        <div class="infographic-date">
                                            <i class="bi bi-calendar"></i>
                                            <?php echo date('d F Y', strtotime($item['tanggal_dibuat'])); ?>
                                        </div>
                                        <?php if (!empty($item['sumber'])): ?>
                                            <a href="<?php echo htmlspecialchars($item['sumber']); ?>" 
                                               target="_blank" 
                                               class="download-btn">
                                                <i class="bi bi-download me-2"></i>Lihat Infografis
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-bar-chart"></i>
                                    <h3>Belum ada infografis tersedia</h3>
                                    <p>Infografis akan segera ditambahkan</p>
                                </div>
                            </div>
                        <?php endif; ?>
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
                        <?php if (count($videografis) > 0): ?>
                            <?php foreach ($videografis as $item): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="video-card">
                                    <div class="video-thumbnail">
                                        <?php if (!empty($item['thumbnail'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['judul']); ?>">
                                        <?php else: ?>
                                            <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab2d6?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                                 alt="Default Thumbnail">
                                        <?php endif; ?>
                                        <a href="#" class="video-play-btn" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#videoModal<?php echo $item['id']; ?>">
                                            <i class="bi bi-play-fill"></i>
                                        </a>
                                    </div>
                                    <div class="video-content">
                                        <h4 class="video-title"><?php echo htmlspecialchars($item['judul']); ?></h4>
                                        <?php if (!empty($item['deskripsi'])): ?>
                                            <p class="video-description"><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                                        <?php endif; ?>
                                        <div class="video-meta">
                                            <span><i class="bi bi-calendar"></i> <?php echo date('d M Y', strtotime($item['tanggal_dibuat'])); ?></span>
                                            <span>Videografis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-camera-video"></i>
                                    <h3>Belum ada videografis tersedia</h3>
                                    <p>Video akan segera ditambahkan</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO MODALS -->
    <?php foreach ($videografis as $item): ?>
    <div class="modal fade" id="videoModal<?php echo $item['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($item['judul']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <?php
                        // Extract YouTube video ID from URL
                        $youtubeUrl = $item['sumber'];
                        if (strpos($youtubeUrl, 'youtube.com') !== false || strpos($youtubeUrl, 'youtu.be') !== false) {
                            // If it's already an embed URL, use it directly
                            if (strpos($youtubeUrl, 'embed') !== false) {
                                $embedUrl = $youtubeUrl;
                            } else {
                                // Convert regular YouTube URL to embed URL
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
                                    $videoId = $matches[1];
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                } else {
                                    $embedUrl = $youtubeUrl;
                                }
                            }
                        } else {
                            $embedUrl = $youtubeUrl;
                        }
                        ?>
                        <iframe src="<?php echo htmlspecialchars($embedUrl); ?>" 
                                title="<?php echo htmlspecialchars($item['judul']); ?>" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                class="modal-video"></iframe>
                    </div>
                    <?php if (!empty($item['deskripsi'])): ?>
                        <div class="mt-3">
                            <p><strong>Deskripsi:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($item['deskripsi'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- FOOTER SAMA DENGAN BERANDA -->
    <!-- ... (salin footer dari beranda.php) ... -->

    <!-- JAVASCRIPT FILES -->

    
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
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
            
            // Close modal when clicking outside
            const videoModals = document.querySelectorAll('.modal');
            videoModals.forEach(function(modal) {
                modal.addEventListener('show.bs.modal', function() {
                    // Pause all videos when opening a new modal
                    const iframes = document.querySelectorAll('.modal-video');
                    iframes.forEach(function(iframe) {
                        iframe.src = iframe.src; // Reload iframe to stop video
                    });
                });
            });
        });
    </script>

</body>
</html>

<?php
include "footer.php";
// Tutup koneksi database
$koneksi->close();
?>