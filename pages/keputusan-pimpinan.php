<?php
// keputusan-pimpinan.php
session_start();
include_once "a_db.php";

// ============================================
// KONFIGURASI PATH (SESUAIKAN DENGAN STRUKTUR ANDA)
// ============================================
$base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/";
$web_image_path = "/jdihcilacap_pasang/wb_admin/uploads/images/";
$pdf_base_path = "C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/pdf/";

// ============================================
// FUNGSI BANTU UNTUK FILE
// ============================================

// Fungsi untuk menemukan file PDF di server
function findPDFFile($filename, $id = null) {
    global $pdf_base_path;
    
    if (empty($filename)) {
        return null;
    }
    
    // Bersihkan nama file
    $filename = basename($filename);
    $clean_filename = preg_replace('/(\.pdf)+$/i', '.pdf', $filename);
    
    // Lokasi yang mungkin
    $possible_locations = [
        // Lokasi utama Anda
        $pdf_base_path . $clean_filename,
        
        // Format Windows
        str_replace('/', '\\', $pdf_base_path) . $clean_filename,
        
        // Document root relatif
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/pdf/' . $clean_filename,
        
        // Alternatif lokasi
        'C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/' . $clean_filename,
        $_SERVER['DOCUMENT_ROOT'] . '/uploads/pdf/' . $clean_filename,
        $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $clean_filename,
    ];
    
    // Jika ada ID, coba cari berdasarkan ID
    if ($id) {
        $possible_locations[] = $pdf_base_path . $id . '.pdf';
        $possible_locations[] = $pdf_base_path . $id . '.PDF';
        $possible_locations[] = $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/pdf/' . $id . '.pdf';
    }
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            // Verifikasi ini file PDF
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

// ============================================
// QUERY DATA UTAMA
// ============================================
$query = "SELECT 
            idData, 
            tahun_pengundangan,
            tanggal_pengundangan,
            tanggal_penetapan,
            jenis,
            noPeraturan,
            judul,
            singkatanJenis,
            status,
            fileDownload,
            pdf_path,
            image_name,
            image_path,
            uploaded_at
          FROM uploads 
          WHERE jenis = 'Keputusan_Pimpinan_DPRD' 
          ORDER BY idData DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// ============================================
// TOTAL DOKUMEN
// ============================================
$total_query = "SELECT COUNT(*) as total 
                FROM uploads 
                WHERE jenis = 'Keputusan_Pimpinan_DPRD'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

// ============================================
// STATISTIK PER TAHUN
// ============================================
$year_stats_query = "
    SELECT tahun_pengundangan, COUNT(*) as jumlah
    FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    GROUP BY tahun_pengundangan
    ORDER BY tahun_pengundangan DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

// ============================================
// DOKUMEN TERBARU
// ============================================
$popular_query = "
    SELECT idData, noPeraturan, judul, tanggal_penetapan
    FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    ORDER BY tanggal_pengundangan DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

// ============================================
// TAHUN FILTER
// ============================================
$years_query = "
    SELECT DISTINCT tahun_pengundangan
    FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    ORDER BY tahun_pengundangan DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_pengundangan'];
}

// ============================================
// HEADER
// ============================================
$page_title = "Keputusan Pimpinan DPRD - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan Keputusan Pimpinan Dewan Perwakilan Rakyat Daerah Kota Surakarta";

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
        <h1 class="text-white">KEPUTUSAN PIMPINAN DPRD</h1>
        <p class="subtitle">Keputusan Pimpinan Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Keputusan Pimpinan DPRD</li>
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

<h3 class="section-title">Daftar Keputusan Pimpinan DPRD Kota Surakarta</h3>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):

    $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
    
    // ============================================
    // CARI FILE PDF
    // ============================================
    $file_from_db = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
    $file_found = false;
    $actual_file_path = null;
    $download_url = '#';
    
    if (!empty($file_from_db)) {
        $actual_file_path = findPDFFile($file_from_db, $row['idData']);
        if ($actual_file_path) {
            $file_found = true;
            // URL untuk download langsung (tanpa force_download.php dulu)
            $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $actual_file_path);
            $download_url = $relative_path . '?t=' . time(); // Tambah timestamp untuk cache busting
        }
    }
    
    // ============================================
    // CARI GAMBAR
    // ============================================
    $image_url = '';
    $image_found = false;
    
    // 1. Pertama, cek dari kolom image_path di database
    if (!empty($row['image_path']) && file_exists($row['image_path'])) {
        $image_url = $row['image_path'];
        $image_found = true;
    }
    // 2. Jika tidak, coba dari image_name + base path
    elseif (!empty($row['image_name'])) {
        $possible_paths = [
            $base_upload_path . 'images/' . $row['image_name'],
            $base_upload_path . $row['image_name'],
            'uploads/images/' . $row['image_name'],
            'uploads/' . $row['image_name']
        ];
        
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $image_url = str_replace('C:/xampp/htdocs', '', $path);
                $image_found = true;
                break;
            }
        }
        
        if (!$image_found) {
            $web_test_path = $web_image_path . $row['image_name'];
            $full_test_path = $_SERVER['DOCUMENT_ROOT'] . $web_test_path;
            if (file_exists($full_test_path)) {
                $image_url = $web_test_path;
                $image_found = true;
            }
        }
    }
    // 3. Jika masih tidak ada, coba cari berdasarkan ID
    if (!$image_found) {
        $image_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        foreach ($image_extensions as $ext) {
            $test_filename = $row['idData'] . '.' . $ext;
            $test_path = $base_upload_path . 'images/' . $test_filename;
            
            if (file_exists($test_path)) {
                $image_url = $web_image_path . $test_filename;
                $image_found = true;
                break;
            }
        }
    }
    
    if (!empty($image_url) && !preg_match('/^https?:\/\//', $image_url)) {
        if (strpos($image_url, '/') !== 0) {
            $image_url = '/' . $image_url;
        }
    }
    
    $detail = "index.php?page=detail-keputusan&id=" . $row['idData'];
?>

<div class="document-card" data-tahun="<?= $row['tahun_pengundangan']; ?>" data-id="<?= $row['idData']; ?>">
    <div class="card-image">
        <?php if ($image_found && !empty($image_url)): ?>
            <img src="<?= htmlspecialchars($image_url) ?>" 
                 alt="<?= htmlspecialchars($row['judul']) ?>"
                 loading="lazy"
                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.querySelector('.placeholder').style.display='flex';">
            <div class="placeholder" style="display: none;">
                <i class="bi bi-file-earmark-ruled"></i>
                <p><?= htmlspecialchars($row['singkatanJenis']) ?><br>
                <small><?= htmlspecialchars($row['noPeraturan']) ?></small></p>
            </div>
        <?php else: ?>
            <div class="placeholder">
                <i class="bi bi-file-earmark-ruled"></i>
                <p><?= htmlspecialchars($row['singkatanJenis']) ?><br>
                <small><?= htmlspecialchars($row['noPeraturan']) ?></small></p>
            </div>
        <?php endif; ?>
        <div class="image-overlay">
            <span class="image-badge"><?= htmlspecialchars($row['singkatanJenis']) ?></span>
        </div>
    </div>

    <div class="card-content">
        <div class="card-number"><?= htmlspecialchars($row['noPeraturan']) ?></div>
        <h3 class="card-title"><?= htmlspecialchars($row['judul']) ?></h3>

        <div class="card-meta">
            <div class="card-date">
                <i class="bi bi-calendar"></i> <?= $tanggal ?>
            </div>
            <?php if (!empty($row['status'])): ?>
                <div class="card-status"><?= htmlspecialchars($row['status']) ?></div>
            <?php endif; ?>
        </div>

        <div class="download-status <?= $file_found ? 'available' : 'unavailable' ?>">
            <i class="bi bi-<?= $file_found ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $file_found ? 'File PDF tersedia' : 'File PDF tidak tersedia' ?>
        </div>

        <div class="card-actions">
            <a href="<?= $detail ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
            
            <!-- TOMBOL DOWNLOAD YANG DIPERBAIKI -->
            <?php if ($file_found): ?>
                <a href="<?= htmlspecialchars($download_url) ?>" 
                   class="card-btn btn-download download-btn" 
                   data-id="<?= $row['idData']; ?>"
                   data-filename="<?= basename($actual_file_path) ?>"
                   download>
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php else: ?>
                <a href="#" 
                   class="card-btn btn-download disabled" 
                   onclick="event.preventDefault(); showFileNotFoundAlert('<?= htmlspecialchars($row['noPeraturan']) ?>');"
                   title="File PDF tidak tersedia">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_GET['debug'])): ?>
        <div class="debug-info mt-2 p-2 bg-light rounded">
            <small class="text-muted">
                <strong>Debug Info:</strong><br>
                ID: <?= $row['idData'] ?><br>
                File from DB: <?= htmlspecialchars($file_from_db) ?><br>
                File Found: <?= $file_found ? 'Ya' : 'Tidak' ?><br>
                Actual Path: <?= htmlspecialchars($actual_file_path) ?><br>
                Download URL: <?= htmlspecialchars($download_url) ?><br>
                Server Root: <?= $_SERVER['DOCUMENT_ROOT'] ?>
            </small>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-files"></i>
    <h4>Tidak Ada Data</h4>
    <p class="text-muted mb-0">
        Tidak ada keputusan Pimpinan DPRD yang tersedia.
    </p>
</div>
<?php endif; ?>

<!-- SOLUSI ALTERNATIF JIKA DOWNLOAD MASIH TIDAK BERFUNGSI -->

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<!-- Reset result pointer untuk sidebar -->
<?php
mysqli_data_seek($year_stats_result, 0);
mysqli_data_seek($popular_result, 0);
?>

<div class="sidebar-menu">
<h4><i class="bi bi-list"></i> Menu Perundangan</h4>
<ul>
<li><a href="index.php?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
<li><a href="keputusan-dprd.php"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
<li class="active"><a href="#"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
<li><a href="keputusan-sekretariat.php"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris DPRD</a></li>
<li><a href="index.php?page=peraturan-dprd"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
<li><a href="index.php?page=perda"><i class="bi bi-journal-bookmark"></i> Peraturan Daerah</a></li>
<li><a href="index.php?page=english-legal"><i class="bi bi-translate"></i> English Legal Products</a></li>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik</h4>
<ul>
<li>Total: <strong><?= $total_dokumen ?> dokumen</strong></li>
<?php while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= $stat['tahun_pengundangan'] ?> : <strong><?= $stat['jumlah'] ?></strong> dokumen</li>
<?php endwhile; ?>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Dokumen Terbaru</h4>
<ul>
<?php while ($pop = mysqli_fetch_assoc($popular_result)): ?>
<li>
<a href="index.php?page=detail-keputusan&id=<?= $pop['idData'] ?>">
<i class="bi bi-file-text"></i> <?= htmlspecialchars($pop['noPeraturan']) ?>
</a>
<small class="text-muted d-block"><?= htmlspecialchars($pop['judul']) ?></small>
<small class="text-muted"><?= date('d M Y', strtotime($pop['tanggal_penetapan'])) ?></small>
</li>
<?php endwhile; ?>
</ul>
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
// FUNGSI UNTUK NOTIFIKASI
// ============================================
function showFileNotFoundAlert(noPeraturan) {
    // Gunakan SweetAlert jika ada, atau alert biasa
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'File Tidak Tersedia',
            html: `File PDF untuk dokumen <strong>${noPeraturan}</strong> tidak ditemukan di server.`,
            confirmButtonText: 'Mengerti'
        });
    } else {
        alert(`File PDF untuk dokumen ${noPeraturan} tidak ditemukan di server.`);
    }
}

function showDownloadStarted(filename) {
    // Gunakan SweetAlert jika ada, atau alert biasa
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Download Dimulai',
            html: `File <strong>${filename}</strong> sedang didownload...`,
            timer: 2000,
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
    // Untuk tombol download yang aktif
    const downloadButtons = document.querySelectorAll('.download-btn:not(.disabled)');
    
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.getAttribute('data-id');
            const filename = this.getAttribute('data-filename');
            
            // Tampilkan notifikasi
            showDownloadStarted(filename);
            
            // Optional: Log ke console untuk debugging
            console.log('Download clicked:', {
                id: id,
                filename: filename,
                href: this.href
            });
        });
    });
    
    // Untuk tombol download yang disabled
    const disabledButtons = document.querySelectorAll('.btn-download.disabled');
    disabledButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.document-card');
            const noPeraturan = card.querySelector('.card-number').textContent;
            showFileNotFoundAlert(noPeraturan);
        });
    });
    
    // ============================================
    // ERROR HANDLING UNTUK GAMBAR
    // ============================================
    const images = document.querySelectorAll('.card-image img');
    
    images.forEach(img => {
        // Cek jika gambar sudah error
        if (img.complete && img.naturalHeight === 0) {
            const placeholder = img.parentElement.querySelector('.placeholder');
            if (placeholder) {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
        
        // Event listener untuk error gambar
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
// FUNGSI ALTERNATIF DOWNLOAD
// ============================================
function downloadViaBlob(url, filename) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            console.error('Download error:', error);
            alert('Gagal mendownload file. Silakan coba lagi.');
        });
}

// ============================================
// TEST DOWNLOAD FUNCTION
// ============================================
function testDownload(id) {
    const testUrl = `test_download.php?id=${id}`;
    window.open(testUrl, '_blank');
}
</script>

<?php 
// ============================================
// DEBUG INFO TAMBAHAN
// ============================================
if (isset($_GET['debug'])) {
    echo "<!-- ========== DEBUG INFO ========== -->\n";
    echo "<!-- PHP Version: " . PHP_VERSION . " -->\n";
    echo "<!-- Script: " . $_SERVER['PHP_SELF'] . " -->\n";
    echo "<!-- Document Root: " . $_SERVER['DOCUMENT_ROOT'] . " -->\n";
    echo "<!-- PDF Base Path: " . $pdf_base_path . " -->\n";
    
    // Cek apakah folder PDF ada dan bisa diakses
    if (is_dir($pdf_base_path)) {
        echo "<!-- PDF folder exists -->\n";
        
        // Coba akses beberapa file
        $test_files = glob($pdf_base_path . "*.pdf");
        echo "<!-- Found " . count($test_files) . " PDF files -->\n";
        
        // Tampilkan 3 file pertama
        $sample_count = 0;
        foreach ($test_files as $file) {
            if ($sample_count < 3) {
                echo "<!-- Sample PDF: " . basename($file) . " -->\n";
                $sample_count++;
            }
        }
    } else {
        echo "<!-- WARNING: PDF folder NOT found -->\n";
    }
    
    echo "<!-- ========== END DEBUG INFO ========== -->\n";
}

mysqli_close($koneksi); 
?>