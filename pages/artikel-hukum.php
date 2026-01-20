<?php
// artikel-hukum.php
session_start();

// ============================================
// KONEKSI DATABASE
// ============================================
$host = "localhost";
$username = "root";
$password = "";
$database = "jdihdprd";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");

// ============================================
// KONFIGURASI PATH
// ============================================
$base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/";
$artikel_pdf_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/";
$artikel_images_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/artikel/images/";

$web_artikel_pdf_path = "/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/";
$web_artikel_images_path = "/jdihcilacap_pasang/wb_admin/uploads/artikel/images/";

// ============================================
// FUNGSI BANTU UNTUK FILE
// ============================================

// Fungsi untuk menemukan file PDF di server
function findArtikelPDFFile($filename, $id = null) {
    global $artikel_pdf_path;
    
    if (empty($filename)) {
        return null;
    }
    
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    $possible_locations = [
        $artikel_pdf_path . $clean_filename,
        str_replace('/', '\\', $artikel_pdf_path) . $clean_filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/artikel/pdf/' . $clean_filename,
    ];
    
    if ($id) {
        $possible_locations[] = $artikel_pdf_path . $id . '.pdf';
        $possible_locations[] = $artikel_pdf_path . $id . '.PDF';
        $possible_locations[] = $artikel_pdf_path . 'artikel_' . $id . '.pdf';
    }
    
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if (stripos($mime_type, 'pdf') !== false) {
                return $location;
            }
        }
    }
    
    return null;
}

// Fungsi untuk menemukan gambar thumbnail
function findArtikelImageFile($filename, $id = null) {
    global $artikel_images_path;
    
    if (empty($filename) && $id) {
        $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($image_extensions as $ext) {
            $test_filename = $id . '.' . $ext;
            $test_path = $artikel_images_path . $test_filename;
            
            if (file_exists($test_path)) {
                return $test_path;
            }
            
            $test_filename2 = 'artikel_' . $id . '.' . $ext;
            $test_path2 = $artikel_images_path . $test_filename2;
            
            if (file_exists($test_path2)) {
                return $test_path2;
            }
        }
        return null;
    }
    
    if (empty($filename)) {
        return null;
    }
    
    $filename = basename($filename);
    
    $possible_locations = [
        $artikel_images_path . $filename,
        str_replace('/', '\\', $artikel_images_path) . $filename,
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/artikel/images/' . $filename,
    ];
    
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            return $location;
        }
    }
    
    return null;
}

// ============================================
// QUERY DATA UTAMA
// ============================================
$query = "SELECT 
            id_artikel,
            judul,
            jenis_artikel,
            tanggal_terbit,
            tahun_terbit,
            penulis,
            jurnal,
            issn,
            volume,
            issue,
            halaman,
            bidang_hukum,
            keywords,
            abstrak,
            status,
            bahasa,
            file_pdf,
            thumbnail,
            created_at,
            updated_at,
            id_user
          FROM artikel_hukum 
          WHERE status = 'published' 
          ORDER BY tanggal_terbit DESC, created_at DESC
          LIMIT 12";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// ============================================
// TOTAL ARTIKEL
// ============================================
$total_query = "SELECT COUNT(*) as total 
                FROM artikel_hukum 
                WHERE status = 'published'";
$total_result = mysqli_query($koneksi, $total_query);
$total_artikel = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_terbit, COUNT(*) as jumlah
    FROM artikel_hukum
    WHERE status = 'published'
    GROUP BY tahun_terbit
    ORDER BY tahun_terbit DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// ARTIKEL TERBARU
// ============================================
$popular_query = "
    SELECT id_artikel, judul, tanggal_terbit, tahun_terbit
    FROM artikel_hukum
    WHERE status = 'published'
    ORDER BY created_at DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

// ============================================
// KATEGORI (bidang_hukum)
// ============================================
$kategori_query = "
    SELECT DISTINCT bidang_hukum, COUNT(*) as jumlah
    FROM artikel_hukum
    WHERE status = 'published' AND bidang_hukum IS NOT NULL AND bidang_hukum != ''
    GROUP BY bidang_hukum
    ORDER BY jumlah DESC";
$kategori_result = mysqli_query($koneksi, $kategori_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_terbit
    FROM artikel_hukum
    WHERE status = 'published' AND tahun_terbit IS NOT NULL
    ORDER BY tahun_terbit DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_terbit'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Artikel Hukum - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan artikel dan publikasi ilmiah hukum dari DPRD Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">ARTIKEL HUKUM</h1>
        <p class="subtitle">Kumpulan Artikel dan Publikasi Ilmiah Hukum dari DPRD Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Artikel Hukum</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT
================================ -->
<section class="main-content">
    <div class="container">
        <div class="row">
            
            <!-- ===== KONTEN UTAMA ===== -->
            <div class="col-lg-8">
                
                <!-- FILTER TAHUN -->
                <div class="year-filter">
                    <h5><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h5>
                    <div class="year-buttons">
                        <button class="year-btn active" data-year="semua">Semua</button>
                        <?php foreach ($years as $year): ?>
                            <button class="year-btn" data-year="<?= $year ?>"><?= $year ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <h3 class="section-title">Daftar Artikel Hukum</h3>

                <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="card-container">
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $tanggal = date('d F Y', strtotime($row['tanggal_terbit']));
                    
                    // Cari file PDF
                    $file_from_db = $row['file_pdf'];
                    $file_found = false;
                    $actual_file_path = null;
                    $download_url = '#';
                    
                    if (!empty($file_from_db)) {
                        $actual_file_path = findArtikelPDFFile($file_from_db, $row['id_artikel']);
                        if ($actual_file_path) {
                            $file_found = true;
                            $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_file_path);
                            $download_url = $relative_path . '?t=' . time();
                        }
                    }
                    
                    // Cari gambar/thumbnail
                    $image_url = '';
                    $image_found = false;
                    
                    if (!empty($row['thumbnail'])) {
                        $actual_image_path = findArtikelImageFile($row['thumbnail'], $row['id_artikel']);
                        if ($actual_image_path) {
                            $image_found = true;
                            $relative_image_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_image_path);
                            $image_url = $relative_image_path;
                        }
                    } else {
                        $actual_image_path = findArtikelImageFile(null, $row['id_artikel']);
                        if ($actual_image_path) {
                            $image_found = true;
                            $relative_image_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_image_path);
                            $image_url = $relative_image_path;
                        }
                    }
                    
                    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url) && $image_url[0] !== '/') {
                        $image_url = '/' . $image_url;
                    }
                    
                    // Tentukan jenis artikel
                    $jenis_artikel = $row['jenis_artikel'] ?? 'Artikel';
                    $singkatan = substr($jenis_artikel, 0, 3);
                    
                    $detail = "detail-artikel.php?id=" . $row['id_artikel'];
                ?>
                
                <div class="document-card" data-tahun="<?= $row['tahun_terbit']; ?>" data-id="<?= $row['id_artikel']; ?>">
                    <div class="card-image">
                        <?php if ($image_found && !empty($image_url)): ?>
                            <img src="<?= htmlspecialchars($image_url) ?>" 
                                 alt="<?= htmlspecialchars($row['judul']) ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
                            <div class="placeholder" style="display: none;">
                                <i class="bi bi-journal-text"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php else: ?>
                            <div class="placeholder">
                                <i class="bi bi-journal-text"></i>
                                <p><?= htmlspecialchars($singkatan) ?><br>
                                <small>Tahun <?= htmlspecialchars($row['tahun_terbit']) ?></small></p>
                            </div>
                        <?php endif; ?>
                        <div class="image-overlay">
                            <span class="image-badge"><?= htmlspecialchars($singkatan) ?></span>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-number"><?= htmlspecialchars($row['tahun_terbit']) ?> - <?= htmlspecialchars($row['bidang_hukum']) ?></div>
                        <h3 class="card-title"><?= htmlspecialchars($row['judul']) ?></h3>

                        <div class="card-meta">
                            <div class="card-date">
                                <i class="bi bi-calendar"></i> <?= $tanggal ?>
                            </div>
                            <div class="card-status"><?= htmlspecialchars($row['jenis_artikel']) ?></div>
                        </div>

                        <p class="text-muted mb-3" style="font-size: 0.9rem;">
                            <i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?>
                        </p>

                        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
                            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
                            <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
                        </div>

                        <div class="card-actions">
                            <button class="card-btn btn-view"
        onclick="window.location.href='<?= $detail ?>'">
    <i class="bi bi-eye"></i> Lihat Detail
</button>

                            
                            <!-- TOMBOL DOWNLOAD -->
                            <?php if ($file_found): ?>
                                <a href="<?= htmlspecialchars($download_url) ?>" 
                                   class="card-btn btn-download download-btn" 
                                   data-id="<?= $row['id_artikel']; ?>"
                                   data-filename="<?= htmlspecialchars(basename($actual_file_path)) ?>"
                                   download>
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            <?php else: ?>
                                <a href="#" 
                                   class="card-btn btn-download disabled" 
                                   onclick="event.preventDefault(); showFileNotFoundAlert('<?= htmlspecialchars($row['judul']) ?>');"
                                   title="File PDF tidak tersedia">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="no-data">
                    <i class="bi bi-journal-text"></i>
                    <h4>Tidak Ada Data</h4>
                    <p class="text-muted mb-0">
                        Tidak ada artikel hukum yang tersedia.
                    </p>
                </div>
                <?php endif; ?>

                <!-- PAGINATION -->
                <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="pagination-container mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
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
                <?php endif; ?>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="col-lg-4">
                
                <!-- Menu Artikel -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-collection"></i> Kategori Artikel</h4>
                    <ul>
                        <?php 
                        mysqli_data_seek($kategori_result, 0);
                        while ($kat = mysqli_fetch_assoc($kategori_result)): 
                        ?>
                            <li>
                                <a href="?kategori=<?= urlencode($kat['bidang_hukum']) ?>">
                                    <i class="bi bi-folder"></i> <?= htmlspecialchars($kat['bidang_hukum']) ?>
                                    <span class="badge bg-secondary float-end"><?= $kat['jumlah'] ?></span>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- Statistik -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> Statistik Artikel</h4>
                    <ul>
                        <li>Total: <strong><?= $total_artikel ?> artikel</strong></li>
                        <?php 
                        mysqli_data_seek($year_stats_result, 0);
                        while ($stat = mysqli_fetch_assoc($year_stats_result)): 
                        ?>
                            <li>
                                <?= $stat['tahun_terbit'] ?>: <strong><?= $stat['jumlah'] ?></strong> artikel
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- Filter Tahun -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-calendar"></i> Filter Berdasarkan Tahun</h4>
                    <div class="year-buttons-sidebar">
                        <button class="year-btn year-filter-sidebar active" data-year="semua">Semua</button>
                        <?php foreach ($years as $year): ?>
                            <button class="year-btn year-filter-sidebar" data-year="<?= $year ?>"><?= $year ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Artikel Terbaru -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-clock-history"></i> Artikel Terbaru</h4>
                    <ul>
                    <?php 
                    mysqli_data_seek($popular_result, 0);
                    while ($pop = mysqli_fetch_assoc($popular_result)): 
                    ?>
                        <li>
                            <a href="detail-artikel.php?id=<?= $pop['id_artikel'] ?>">
                                <i class="bi bi-file-text"></i> <?= htmlspecialchars(substr($pop['judul'], 0, 40)) ?>...
                            </a>
                            <small class="text-muted d-block">Tahun: <?= htmlspecialchars($pop['tahun_terbit']) ?></small>
                            <small class="text-muted"><?= date('d M Y', strtotime($pop['tanggal_terbit'])) ?></small>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>

                <!-- Pencarian -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-search"></i> Cari Artikel</h4>
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Kata kunci...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// FILTER BERDASARKAN TAHUN
// ============================================
document.querySelectorAll(".year-btn").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedYear = btn.dataset.year;
        
        document.querySelectorAll(".document-card").forEach(card => {
            if (selectedYear === "semua" || card.dataset.tahun === selectedYear) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    });
});

// ============================================
// FILTER BERDASARKAN TAHUN (SIDEBAR)
// ============================================
document.querySelectorAll(".year-filter-sidebar").forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll(".year-filter-sidebar").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const selectedYear = btn.dataset.year;
        
        document.querySelectorAll(".year-btn").forEach(b => {
            if (b.dataset.year === selectedYear) {
                b.click();
            }
        });
    });
});

// ============================================
// FUNGSI UNTUK NOTIFIKASI
// ============================================
function showFileNotFoundAlert(judul) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk artikel <strong>${judul}</strong> tidak ditemukan di server.<br><br>
                  <small>Silakan hubungi administrator untuk melaporkan masalah ini.</small>`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk artikel "${judul}" tidak ditemukan di server.\n\nSilakan hubungi administrator untuk melaporkan masalah ini.`);
    }
}

function showDownloadStarted(filename) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Download Dimulai',
            html: `File <strong>${filename}</strong> sedang didownload...<br><br>
                  <small>Jika download tidak dimulai, coba klik kanan dan "Save link as..."</small>`,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        console.log(`Download started: ${filename}`);
    }
}

// ============================================
// HANDLING DOWNLOAD BUTTON
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.download-btn:not(.disabled)');
    
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.getAttribute('data-id');
            const filename = this.getAttribute('data-filename');
            
            showDownloadStarted(filename);
            
            console.log('Download clicked:', {
                id: id,
                filename: filename,
                href: this.href
            });
        });
    });
    
    const disabledButtons = document.querySelectorAll('.btn-download.disabled');
    disabledButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.document-card');
            const judul = card.querySelector('.card-title').textContent;
            showFileNotFoundAlert(judul);
        });
    });
    
    // ============================================
    // ERROR HANDLING UNTUK GAMBAR
    // ============================================
    const images = document.querySelectorAll('.card-image img');
    
    images.forEach(img => {
        if (img.complete && img.naturalHeight === 0) {
            const placeholder = img.parentElement.querySelector('.placeholder');
            if (placeholder) {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
        
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const placeholder = this.parentElement.querySelector('.placeholder');
            if (placeholder) {
                placeholder.style.display = 'flex';
            }
        });
    });
});

// ============================================
// PENCARIAN ARTIKEL
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const searchForms = document.querySelectorAll('form[method="GET"]');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && !searchInput.value.trim()) {
                e.preventDefault();
                alert('Masukkan kata kunci pencarian!');
            }
        });
    });
});

// ============================================
// FILTER KATEGORI
// ============================================
function filterByCategory(category) {
    window.location.href = '?kategori=' + encodeURIComponent(category);
}
</script>

<?php 
// Tutup koneksi database
mysqli_close($koneksi); 
?>