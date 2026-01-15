<?php
// peraturan-dprd.php
session_start();
include_once "a_db.php";

/* ===============================
   QUERY DATA UTAMA
================================ */
$query = "SELECT * FROM uploads 
          WHERE jenis = 'Peraturan_DPRD' 
          ORDER BY idData DESC";
$result = mysqli_query($koneksi, $query);

/* ===============================
   TOTAL DOKUMEN
================================ */
$total_query = "SELECT COUNT(*) as total 
                FROM uploads 
                WHERE jenis = 'Peraturan_DPRD'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

/* ===============================
   STATISTIK PER TAHUN
================================ */
$year_stats_query = "
    SELECT tahun_pengundangan, COUNT(*) as jumlah
    FROM uploads
    WHERE jenis = 'Peraturan_DPRD'
    GROUP BY tahun_pengundangan
    ORDER BY tahun_pengundangan DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

/* ===============================
   DOKUMEN TERBARU
================================ */
$popular_query = "
    SELECT * FROM uploads
    WHERE jenis = 'Peraturan_DPRD'
    ORDER BY tanggal_pengundangan DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

/* ===============================
   TAHUN FILTER
================================ */
$years_query = "
    SELECT DISTINCT tahun_pengundangan
    FROM uploads
    WHERE jenis = 'Peraturan_DPRD'
    ORDER BY tahun_pengundangan DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_pengundangan'];
}

/* ===============================
   HEADER
================================ */
$page_title = "Peraturan DPRD - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan Peraturan DPRD Kota Surakarta";

include "header.php";
?>

<!-- ===============================
     STYLE (SAMA PERSIS DPRD)
================================ -->
<style>
<?php include "style-keputusan-dprd.css"; ?>
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">PERATURAN DPRD</h1>
        <p class="subtitle">Peraturan DPRD Kota Surakarta</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active">Peraturan DPRD</li>
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

<h3 class="section-title">Daftar Peraturan DPRD</h3>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):

    $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
    $file = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
    $image = !empty($row['image_path']) ? $row['image_path'] : '';
    // LINK DETAIL DISAMAKAN
    $detail = "index.php?page=detail-keputusan&id=" . $row['idData'];
?>

<div class="document-card" data-tahun="<?= $row['tahun_pengundangan']; ?>">
    <div class="card-image">
        <?php if ($image && file_exists($image)): ?>
            <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
        <?php else: ?>
            <div class="placeholder">
                <i class="bi bi-file-earmark-text"></i>
                <p><?= htmlspecialchars($row['singkatanJenis']) ?></p>
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
            <?php if ($row['status']): ?>
                <div class="card-status"><?= $row['status'] ?></div>
            <?php endif; ?>
        </div>

        <div class="card-actions">
            <a href="<?= $detail ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
            <?php if ($file && file_exists($file)): ?>
                <a href="<?= $file ?>" download class="card-btn btn-download">
                    <i class="bi bi-download"></i> Download
                </a>
            <?php else: ?>
                <button class="card-btn btn-download" disabled>
                    <i class="bi bi-exclamation-circle"></i> Tidak Tersedia
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endwhile; ?>
</div>
<?php else: ?>
<div class="no-data">
    <i class="bi bi-files"></i>
    <h4>Tidak Ada Data</h4>
</div>
<?php endif; ?>

</div>

<!-- ===== SIDEBAR ===== -->
<div class="col-lg-4">

<div class="sidebar-menu">
<h4><i class="bi bi-list"></i> Menu Perundangan</h4>
<ul>
<li><a href="index.php?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
<li><a href="keputusan-dprd.php"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
<li class="active"><a href="#"><i class="bi bi-journal-text"></i> Peraturan DPRD</a></li>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik</h4>
<ul>
<li>Total: <strong><?= $total_dokumen ?> dokumen</strong></li>
<?php while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= $stat['tahun_pengundangan'] ?> : <?= $stat['jumlah'] ?></li>
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
document.querySelectorAll(".year-btn").forEach(btn => {
    btn.onclick = () => {
        document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const y = btn.dataset.year;
        document.querySelectorAll(".document-card").forEach(c => {
            c.style.display = (y === "semua" || c.dataset.tahun === y) ? "flex" : "none";
        });
    };
});
</script>

<?php mysqli_close($koneksi); ?>
