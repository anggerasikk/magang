<?php
// detail-kegiatan.php
require_once 'a_db.php'; // Include koneksi database

// ============================================
// PERBAIKAN 1: Tambahkan base_url
// ============================================
$base_url = "http://localhost/jdihcilacap_pasang";

// Ambil ID artikel dari parameter GET
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// ============================================
// PERBAIKAN 2: Perbaiki query (status konsisten)
// ============================================
$query = "SELECT * FROM artikel_kegiatan WHERE id_artikel = $id_artikel AND status = 'publik'";
$result = $koneksi->query($query);

// Debug: Cek apakah query berhasil
if (!$result) {
    echo "Error: " . $koneksi->error;
    exit();
}

// Jika artikel tidak ditemukan
if ($result->num_rows == 0) {
    // Tampilkan pesan error atau redirect
    echo "<script>alert('Artikel tidak ditemukan!'); window.location.href='index.php?page=kegiatan';</script>";
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

// ============================================
// PERBAIKAN 3: Fungsi untuk handle gambar
// ============================================
function getImageUrl($path, $base_url, $default_image = null) {
    if (empty($path)) {
        return $default_image ?: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    }
    
    // Jika sudah URL lengkap
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Jika path relatif (dimulai dengan uploads/)
    if (strpos($path, 'uploads/') === 0 || strpos($path, '/uploads/') === 0) {
        // Bersihkan path
        $clean_path = ltrim($path, '/');
        
        // Cek apakah file ada di server
        $server_path = $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/' . $clean_path;
        
        if (file_exists($server_path)) {
            return $base_url . '/' . $clean_path;
        }
        
        // Coba berbagai kemungkinan path
        $possible_paths = [
            $_SERVER['DOCUMENT_ROOT'] . '/' . $clean_path,
            $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/' . $clean_path,
            $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/uploads/' . basename($clean_path),
            dirname(__FILE__) . '/' . $clean_path
        ];
        
        foreach ($possible_paths as $possible_path) {
            if (file_exists($possible_path)) {
                $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $possible_path);
                return $base_url . $relative_path;
            }
        }
    }
    
    // Default fallback
    return $default_image ?: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
}

// Gunakan fungsi untuk mendapatkan URL gambar
$thumbnail_url = getImageUrl($artikel['thumbnail_path'], $base_url);

$pageTitle = $artikel['judul'] . ' - JDIH DPRD Kota Surakarta';
$meta_description = !empty($artikel['meta_description']) ? $artikel['meta_description'] : substr(strip_tags($artikel['konten_artikel']), 0, 160);
$meta_keywords = !empty($artikel['meta_keywords']) ? $artikel['meta_keywords'] : 'kegiatan jdih, dprd surakarta, hukum';

// Debug output untuk memeriksa data
/*
echo "<!-- DEBUG: Artikel ID: " . $id_artikel . " -->";
echo "<!-- DEBUG: Thumbnail Path: " . $artikel['thumbnail_path'] . " -->";
echo "<!-- DEBUG: Thumbnail URL: " . $thumbnail_url . " -->";
echo "<!-- DEBUG: Judul: " . $artikel['judul'] . " -->";
*/

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
    <meta property="og:image" content="' . htmlspecialchars($thumbnail_url) . '">
    <meta property="og:url" content="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">
    <meta property="og:type" content="article">
    <title>', $header_content);

echo $header_content;
?>

<!-- ... Kode CSS tetap sama ... -->

<!-- BREADCRUMB NAVIGATION -->
<nav class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=kegiatan">Kegiatan</a></li>
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
            <?php if (!empty($thumbnail_url)): ?>
                <img src="<?php echo htmlspecialchars($thumbnail_url); ?>" 
                     alt="<?php echo htmlspecialchars($artikel['judul']); ?>" 
                     class="article-thumbnail"
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';">
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
                                <a href="index.php?page=kegiatan&filter=<?php echo urlencode(strtolower($trimmed_tag)); ?>" 
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
                    <?php while ($related = $related_result->fetch_assoc()): 
                        $related_thumbnail = getImageUrl($related['thumbnail_path'], $base_url);
                    ?>
                        <a href="index.php?page=detail-jdih&id=<?php echo $related['id_artikel']; ?>" 
                           class="related-card">
                            <img src="<?php echo htmlspecialchars($related_thumbnail); ?>" 
                                 alt="<?php echo htmlspecialchars($related['judul']); ?>" 
                                 class="related-image"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';">
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
            <a href="index.php?page=kegiatan" class="btn-back">
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
</script>

<?php 
// Tutup koneksi database
if (isset($koneksi)) {
    $koneksi->close();
}

include 'footer.php';
?>