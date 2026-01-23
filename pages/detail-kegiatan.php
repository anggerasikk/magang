<?php
// detail-kegiatan.php
require_once 'a_db.php'; // Include koneksi database

// Ambil ID artikel dari parameter GET
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Query untuk mengambil data artikel berdasarkan ID
$query = "SELECT * FROM artikel_kegiatan WHERE id_artikel = $id_artikel AND status = 'publik'";
$result = $koneksi->query($query);

// Jika artikel tidak ditemukan
if (!$result || $result->num_rows == 0) {
    // Redirect atau tampilkan error
    header('Location: ?page=kegiatan');
    exit();
}

$artikel = $result->fetch_assoc();

// Update jumlah views
$update_views = "UPDATE artikel_kegiatan SET views = views + 1 WHERE id_artikel = $id_artikel";
$koneksi->query($update_views);

// Ambil artikel terkait (kategori yang sama)
$kategori = $artikel['meta_keywords'];
$related_query = "SELECT id_artikel, judul, slug, thumbnail_path, tanggal_publikasi, views 
                  FROM artikel_kegiatan 
                  WHERE id_artikel != $id_artikel 
                  AND status = 'publik'
                  AND (meta_keywords LIKE '%$kategori%' OR judul LIKE '%$kategori%')
                  ORDER BY tanggal_publikasi DESC 
                  LIMIT 3";
$related_result = $koneksi->query($related_query);

$pageTitle = $artikel['judul'] . ' - JDIH DPRD Kota Surakarta';
$meta_description = !empty($artikel['meta_description']) ? $artikel['meta_description'] : substr(strip_tags($artikel['konten_artikel']), 0, 160);
$meta_keywords = !empty($artikel['meta_keywords']) ? $artikel['meta_keywords'] : 'kegiatan jdih, dprd surakarta, hukum';

// Include header dengan meta tags khusus
ob_start();
include 'header.php';
$header_content = ob_get_contents();
ob_end_clean();

// Ganti title dan tambah meta tags
$header_content = str_replace('<title>', '<!-- Dynamic Meta Tags -->
    <meta name="description" content="' . htmlspecialchars($meta_description) . '">
    <meta name="keywords" content="' . htmlspecialchars($meta_keywords) . '">
    <meta property="og:title" content="' . htmlspecialchars($artikel['judul']) . '">
    <meta property="og:description" content="' . htmlspecialchars($meta_description) . '">
    <meta property="og:image" content="' . htmlspecialchars($artikel['thumbnail_path']) . '">
    <meta property="og:url" content="' . $_SERVER['REQUEST_URI'] . '">
    <meta property="og:type" content="article">
    <title>', $header_content);

echo $header_content;
?>

<style>
    /* STYLE KHUSUS HALAMAN DETAIL */
    body {
        padding-top: 80px;
        background: #f8f9fa;
    }
    
    /* BREADCRUMB */
    .breadcrumb-nav {
        background: white;
        padding: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }
    
    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 0;
    }
    
    .breadcrumb-item a {
        color: #13547a;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #80d0c7;
        font-weight: 600;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #666;
    }
    
    /* ARTICLE HEADER */
    .article-header {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        padding: 80px 0 40px;
        text-align: center;
        margin-bottom: 50px;
        border-radius: 15px 15px 0 0;
    }
    
    .article-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 20px;
        line-height: 1.3;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    
    .article-meta {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        opacity: 0.9;
    }
    
    .meta-item i {
        font-size: 1.1rem;
    }
    
    .article-category {
        background: rgba(255,255,255,0.2);
        padding: 6px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
    }
    
    /* MAIN CONTENT */
    .main-content {
        padding: 0 0 80px;
    }
    
    .article-container {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 60px;
    }
    
    /* ARTICLE THUMBNAIL */
    .article-thumbnail {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        margin-bottom: 40px;
    }
    
    /* ARTICLE CONTENT */
    .article-body {
        padding: 0 50px 50px;
    }
    
    @media (max-width: 768px) {
        .article-body {
            padding: 0 20px 30px;
        }
    }
    
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }
    
    .article-content h2 {
        font-family: 'Montserrat', sans-serif;
        color: #13547a;
        margin: 40px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .article-content h3 {
        color: #80d0c7;
        margin: 30px 0 15px;
    }
    
    .article-content p {
        margin-bottom: 25px;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 30px 0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .article-content blockquote {
        border-left: 4px solid #13547a;
        padding-left: 20px;
        margin: 30px 0;
        font-style: italic;
        color: #666;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 0 10px 10px 0;
    }
    
    .article-content ul, .article-content ol {
        margin: 20px 0;
        padding-left: 30px;
    }
    
    .article-content li {
        margin-bottom: 10px;
    }
    
    /* ARTICLE FOOTER */
    .article-footer {
        border-top: 2px solid #f0f0f0;
        padding-top: 30px;
        margin-top: 50px;
    }
    
    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 30px;
    }
    
    .tag {
        background: #f0f8ff;
        color: #13547a;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .tag:hover {
        background: #13547a;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }
    
    .share-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
    }
    
    .share-fb {
        background: #3b5998;
    }
    
    .share-twitter {
        background: #1da1f2;
    }
    
    .share-wa {
        background: #25d366;
    }
    
    .share-link {
        background: #13547a;
    }
    
    /* RELATED ARTICLES */
    .related-section {
        margin-top: 80px;
    }
    
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #13547a;
        margin-bottom: 40px;
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
    
    .related-articles {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .related-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }
    
    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }
    
    .related-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .related-content {
        padding: 20px;
    }
    
    .related-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        margin-bottom: 10px;
        font-size: 1.1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 0.9rem;
    }
    
    /* AUTHOR SECTION */
    .author-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-top: 60px;
    }
    
    .author-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    @media (max-width: 576px) {
        .author-info {
            flex-direction: column;
            text-align: center;
        }
    }
    
    .author-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #80d0c7;
    }
    
    .author-details h4 {
        color: #13547a;
        margin-bottom: 5px;
    }
    
    .author-details p {
        color: #666;
        margin-bottom: 0;
    }
    
    /* BACK BUTTON */
    .back-button {
        margin-top: 40px;
        text-align: center;
    }
    
    .btn-back {
        background: transparent;
        border: 2px solid #13547a;
        color: #13547a;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-back:hover {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-color: transparent;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    
    /* RESPONSIVE */
    @media (max-width: 992px) {
        .article-title {
            font-size: 2rem;
        }
        
        .related-articles {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        body {
            padding-top: 70px;
        }
        
        .article-header {
            padding: 60px 0 30px;
        }
        
        .article-title {
            font-size: 1.8rem;
        }
        
        .article-meta {
            flex-direction: column;
            gap: 10px;
        }
        
        .article-body {
            padding: 0 20px 30px;
        }
        
        .article-content {
            font-size: 1rem;
        }
        
        .related-articles {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 576px) {
        .article-title {
            font-size: 1.5rem;
        }
        
        .article-thumbnail {
            max-height: 300px;
        }
        
        .share-buttons {
            justify-content: center;
        }
    }
</style>

<!-- BREADCRUMB NAVIGATION -->
<nav class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?page=home">Beranda</a></li>
            <li class="breadcrumb-item"><a href="?page=kegiatan">Kegiatan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($artikel['judul']); ?></li>
        </ol>
    </div>
</nav>

<!-- ARTICLE HEADER -->
<section class="article-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="article-title"><?php echo htmlspecialchars($artikel['judul']); ?></h1>
                
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('d F Y', strtotime($artikel['tanggal_publikasi'])); ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <i class="bi bi-person-fill"></i>
                        <span><?php echo !empty($artikel['nama_penulis']) ? htmlspecialchars($artikel['nama_penulis']) : 'Admin JDIH'; ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <i class="bi bi-eye-fill"></i>
                        <span><?php echo number_format($artikel['views'] + 1); ?> dilihat</span>
                    </div>
                    
                    <?php if (!empty($artikel['meta_keywords'])): ?>
                        <?php 
                        $keywords = explode(',', $artikel['meta_keywords']);
                        $main_category = trim($keywords[0]);
                        if (!empty($main_category)): 
                        ?>
                            <div class="article-category"><?php echo ucfirst($main_category); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <div class="article-container">
            <!-- ARTICLE THUMBNAIL -->
            <?php if (!empty($artikel['thumbnail_path'])): ?>
                <img src="<?php echo htmlspecialchars($artikel['thumbnail_path']); ?>" 
                     alt="<?php echo htmlspecialchars($artikel['judul']); ?>" 
                     class="article-thumbnail">
            <?php endif; ?>
            
            <!-- ARTICLE CONTENT -->
            <div class="article-body">
                <div class="article-content">
                    <?php 
                    // Tampilkan konten artikel
                    echo $artikel['konten_artikel'];
                    ?>
                </div>
                
                <!-- ARTICLE FOOTER -->
                <div class="article-footer">
                    <!-- TAGS -->
                    <?php if (!empty($artikel['meta_keywords'])): ?>
                        <div class="article-tags">
                            <strong class="me-2" style="color: #13547a;">Tags:</strong>
                            <?php 
                            $tags = explode(',', $artikel['meta_keywords']);
                            foreach ($tags as $tag):
                                $trimmed_tag = trim($tag);
                                if (!empty($trimmed_tag)):
                            ?>
                                <a href="?page=kegiatan&filter=<?php echo urlencode(strtolower($trimmed_tag)); ?>" 
                                   class="tag">
                                    <?php echo htmlspecialchars($trimmed_tag); ?>
                                </a>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SHARE BUTTONS -->
                    <div class="share-buttons">
                        <strong class="me-3 align-self-center" style="color: #13547a;">Bagikan:</strong>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="share-btn share-fb" title="Share on Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($artikel['judul'] . ' - ' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="share-btn share-twitter" title="Share on Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($artikel['judul'] . ' ' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="share-btn share-wa" title="Share on WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <button class="share-btn share-link" onclick="copyToClipboard()" title="Copy link">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- AUTHOR SECTION -->
        <div class="author-section">
            <div class="author-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode(!empty($artikel['nama_penulis']) ? $artikel['nama_penulis'] : 'Admin JDIH'); ?>&background=13547a&color=fff&size=100" 
                     alt="<?php echo !empty($artikel['nama_penulis']) ? htmlspecialchars($artikel['nama_penulis']) : 'Admin JDIH'; ?>" 
                     class="author-avatar">
                <div class="author-details">
                    <h4><?php echo !empty($artikel['nama_penulis']) ? htmlspecialchars($artikel['nama_penulis']) : 'Admin JDIH'; ?></h4>
                    <p>Penulis Artikel</p>
                    <p class="mt-2">Artikel ini dipublikasikan pada <?php echo date('d F Y', strtotime($artikel['tanggal_publikasi'])); ?>. Terakhir diperbarui: <?php echo !empty($artikel['tanggal_update']) ? date('d F Y', strtotime($artikel['tanggal_update'])) : '-'; ?></p>
                </div>
            </div>
        </div>
        
        <!-- RELATED ARTICLES -->
        <?php if ($related_result && $related_result->num_rows > 0): ?>
            <div class="related-section">
                <h2 class="section-title">Artikel Terkait</h2>
                <div class="related-articles">
                    <?php while ($related = $related_result->fetch_assoc()): ?>
                        <a href="?page=detail-kegiatan&id=<?php echo $related['id_artikel']; ?>&slug=<?php echo $related['slug']; ?>" 
                           class="related-card">
                            <?php if (!empty($related['thumbnail_path'])): ?>
                                <img src="<?php echo htmlspecialchars($related['thumbnail_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($related['judul']); ?>" 
                                     class="related-image">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                     alt="<?php echo htmlspecialchars($related['judul']); ?>" 
                                     class="related-image">
                            <?php endif; ?>
                            <div class="related-content">
                                <h3 class="related-title"><?php echo htmlspecialchars($related['judul']); ?></h3>
                                <div class="related-meta">
                                    <span><i class="bi bi-calendar3"></i> <?php echo date('d M Y', strtotime($related['tanggal_publikasi'])); ?></span>
                                    <span><i class="bi bi-eye-fill"></i> <?php echo number_format($related['views']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- BACK BUTTON -->
        <div class="back-button">
            <a href="?page=kegiatan" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- JAVASCRIPT -->
<script>
    // Copy to clipboard function
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            // Show success message
            const button = document.querySelector('.share-link');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i>';
            button.style.background = '#28a745';
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.style.background = '#13547a';
            }, 2000);
            
            // Toast notification
            showToast('Link berhasil disalin!');
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            showToast('Gagal menyalin link', 'error');
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Add styles for toast
        if (!document.querySelector('#toast-styles')) {
            const style = document.createElement('style');
            style.id = 'toast-styles';
            style.innerHTML = `
                .toast-notification {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: white;
                    padding: 15px 20px;
                    border-radius: 10px;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                    border-left: 4px solid #28a745;
                }
                .toast-notification.error {
                    border-left-color: #dc3545;
                }
                .toast-notification i {
                    font-size: 1.2rem;
                }
                .toast-notification.success i {
                    color: #28a745;
                }
                .toast-notification.error i {
                    color: #dc3545;
                }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
    
    // Add slideOut animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Image zoom functionality
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.article-content img');
        images.forEach(img => {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', function() {
                if (this.classList.contains('zoomed')) {
                    this.classList.remove('zoomed');
                    this.style.cursor = 'zoom-in';
                } else {
                    this.classList.add('zoomed');
                    this.style.cursor = 'zoom-out';
                }
            });
        });
        
        // Add styles for zoom
        const zoomStyle = document.createElement('style');
        zoomStyle.innerHTML = `
            .article-content img.zoomed {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(1.2);
                z-index: 10000;
                max-width: 90%;
                max-height: 90%;
                box-shadow: 0 0 0 1000px rgba(0,0,0,0.8);
                cursor: zoom-out;
            }
        `;
        document.head.appendChild(zoomStyle);
    });
</script>

<?php 
// Tutup koneksi database
if (isset($koneksi)) {
    $koneksi->close();
}

include 'footer.php';
?>