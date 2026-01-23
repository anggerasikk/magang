<?php
// kegiatan.php
require_once 'a_db.php'; // Include koneksi database

// Tambahkan base URL di atas
$base_url = "http://localhost/jdihcilacap_pasang";
$pageTitle = 'Kegiatan JDIH - JDIH DPRD Kota Surakarta';
include 'header.php';

// Query untuk mengambil data artikel kegiatan
$status = 'publik'; // Hanya ambil artikel yang sudah dipublish
$query = "SELECT * FROM artikel_kegiatan WHERE status = '$status' ORDER BY tanggal_publikasi DESC LIMIT 6";
$result = $koneksi->query($query);

// Handle filter berdasarkan kategori (ambil dari kata kunci atau judul)
$current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
if ($current_filter != 'all') {
    $filter_query = " AND (meta_keywords LIKE '%$current_filter%' OR judul LIKE '%$current_filter%')";
    $query = "SELECT * FROM artikel_kegiatan WHERE status = '$status' $filter_query ORDER BY tanggal_publikasi DESC";
    $result = $koneksi->query($query);
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
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
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover {
            background: #e9ecef;
            border-color: #13547a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #13547a;
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
        
        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #80d0c7;
            margin-bottom: 20px;
        }
        
        .empty-state h4 {
            color: #13547a;
            margin-bottom: 15px;
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
                    <a href="?page=kegiatan" class="filter-btn <?php echo ($current_filter == 'all') ? 'active' : ''; ?>">
                        Semua Kegiatan
                    </a>
                    <a href="?page=kegiatan&filter=sosialisasi" class="filter-btn <?php echo ($current_filter == 'sosialisasi') ? 'active' : ''; ?>">
                        Sosialisasi
                    </a>
                    <a href="?page=kegiatan&filter=pelatihan" class="filter-btn <?php echo ($current_filter == 'pelatihan') ? 'active' : ''; ?>">
                        Pelatihan
                    </a>
                    <a href="?page=kegiatan&filter=seminar" class="filter-btn <?php echo ($current_filter == 'seminar') ? 'active' : ''; ?>">
                        Seminar
                    </a>
                    <a href="?page=kegiatan&filter=workshop" class="filter-btn <?php echo ($current_filter == 'workshop') ? 'active' : ''; ?>">
                        Workshop
                    </a>
                    <a href="?page=kegiatan&filter=lainnya" class="filter-btn <?php echo ($current_filter == 'lainnya') ? 'active' : ''; ?>">
                        Lainnya
                    </a>
                </div>
            </div>
            
            <!-- SECTION TITLE -->
            <h2 class="section-title"><i class="bi bi-calendar-event me-2"></i>
                <?php 
                if ($current_filter == 'all') {
                    echo 'Kegiatan Terbaru';
                } else {
                    echo 'Kegiatan ' . ucfirst($current_filter);
                }
                ?>
            </h2>
            
            <!-- ACTIVITY CARDS -->
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="activity-cards-wrapper">
                    <?php while ($artikel = $result->fetch_assoc()): ?>
                        <?php
                        // Ekstrak kategori dari meta_keywords atau judul
                        $categories = ['sosialisasi', 'pelatihan', 'seminar', 'workshop'];
                        $article_category = 'lainnya';
                        
                        if (!empty($artikel['meta_keywords'])) {
                            $keywords = strtolower($artikel['meta_keywords']);
                            foreach ($categories as $cat) {
                                if (strpos($keywords, $cat) !== false) {
                                    $article_category = $cat;
                                    break;
                                }
                            }
                        }
                        
                        // Format tanggal
                        $tanggal = date('d M Y', strtotime($artikel['tanggal_publikasi']));
                        
                        // Ambil excerpt dari konten
                        $excerpt = strip_tags($artikel['konten_artikel']);
                        if (strlen($excerpt) > 150) {
                            $excerpt = substr($excerpt, 0, 150) . '...';
                        }
                        
                        // Thumbnail image
                        $thumbnail = !empty($artikel['thumbnail_path']) && file_exists($artikel['thumbnail_path'])
                            ? $artikel['thumbnail_path']
                            : 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
                        
                        // Penulis
                        $penulis = !empty($artikel['nama_penulis']) ? $artikel['nama_penulis'] : 'Admin JDIH';
                        ?>
                        
                        <div class="activity-card" data-category="<?php echo $article_category; ?>">
                            <img src="<?php echo $thumbnail; ?>" 
                                 alt="<?php echo htmlspecialchars($artikel['judul']); ?>" 
                                 class="activity-image">
                            <div class="activity-content">
                                <div class="activity-meta">
                                    <span class="activity-date">
                                        <i class="bi bi-calendar3"></i> <?php echo $tanggal; ?>
                                    </span>
                                    <span class="activity-category">
                                        <?php echo ucfirst($article_category); ?>
                                    </span>
                                </div>
                                <h3 class="activity-title" title="<?php echo htmlspecialchars($artikel['judul']); ?>">
                                    <?php echo htmlspecialchars($artikel['judul']); ?>
                                </h3>
                                <p class="activity-excerpt">
                                    <?php echo htmlspecialchars($excerpt); ?>
                                </p>
                                <div class="activity-stats">
                                    <span title="Dilihat"><i class="bi bi-eye-fill"></i> <?php echo $artikel['views']; ?></span>
                                    <span title="Penulis"><i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($penulis); ?></span>
                                </div>
                                <!-- PERBAIKAN: Link mengarah ke detail-kegiatan.php dalam folder yang sama -->
                                <a href="index.php?page=detail-kegiatan.php?id=<?php echo $artikel['id_artikel']; ?>" class="read-more-btn">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Empty state -->
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h4>Tidak Ada Kegiatan</h4>
                    <p>Belum ada kegiatan yang tersedia untuk kategori ini.</p>
                    <a href="?page=kegiatan" class="btn btn-primary mt-3" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border: none;">
                        Lihat Semua Kegiatan
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </section>

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
            
            // Filter dengan JavaScript untuk UX yang lebih baik
            filterButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Hanya untuk JavaScript filtering (opsional)
                    const filterValue = this.getAttribute('href').split('filter=')[1];
                    const isAll = this.textContent.trim() === 'Semua Kegiatan';
                    
                    if (isAll) filterValue = 'all';
                    
                    activityCards.forEach(card => {
                        const cardParent = card.closest('.activity-card');
                        if (filterValue === 'all' || isAll) {
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
            
            // Hover effect untuk kartu kegiatan
            const cards = document.querySelectorAll('.activity-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>

</body>
</html>

<?php 
// Tutup koneksi database
if (isset($koneksi)) {
    $koneksi->close();
}
?>