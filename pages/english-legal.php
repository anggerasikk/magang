<?php
// english-legal.php
session_start();
include_once "a_db.php";

$pageTitle = 'English Legal Products - JDIH DPRD Kota Surakarta';

/* ===============================
   QUERY DATA UTAMA
================================ */
$query = "SELECT * FROM uploads 
          WHERE jenis = 'English_Legal' 
          ORDER BY idData DESC";
$result = mysqli_query($koneksi, $query);

/* ===============================
   TOTAL DOKUMEN
================================ */
$total_query = "SELECT COUNT(*) as total 
                FROM uploads 
                WHERE jenis = 'English_Legal'";
$total_result = mysqli_query($koneksi, $total_query);
$total_dokumen = mysqli_fetch_assoc($total_result)['total'];

/* ===============================
   STATISTIK PER TAHUN
================================ */
$year_stats_query = "
    SELECT tahun_pengundangan, COUNT(*) as jumlah
    FROM uploads
    WHERE jenis = 'English_Legal'
    GROUP BY tahun_pengundangan
    ORDER BY tahun_pengundangan DESC
    LIMIT 5";
$year_stats_result = mysqli_query($koneksi, $year_stats_query);

/* ===============================
   DOKUMEN TERBARU
================================ */
$popular_query = "
    SELECT * FROM uploads
    WHERE jenis = 'English_Legal'
    ORDER BY tanggal_pengundangan DESC
    LIMIT 5";
$popular_result = mysqli_query($koneksi, $popular_query);

/* ===============================
   TAHUN FILTER
================================ */
$years_query = "
    SELECT DISTINCT tahun_pengundangan
    FROM uploads
    WHERE jenis = 'English_Legal'
    ORDER BY tahun_pengundangan DESC";
$years_result = mysqli_query($koneksi, $years_query);

$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['tahun_pengundangan'];
}

include 'header.php';
?>

<style>
<?php include "style-keputusan-dprd.css"; ?>
</style>

<!-- HEADER SECTION -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">ENGLISH LEGAL PRODUCTS</h1>
        <p class="subtitle">English Translation of Legal Products from DPRD Surakarta City</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="?page=perundangan">Perundang-Undangan</a></li>
                <li class="breadcrumb-item active" aria-current="page">English Legal Products</li>
            </ol>
        </nav>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
<div class="container">
<div class="row">

<!-- KONTEN UTAMA -->
<div class="col-lg-8">

<!-- FILTER TAHUN -->
<div class="year-filter">
    <h5><i class="bi bi-calendar"></i> Filter by Year</h5>
    <div class="year-buttons">
        <button class="year-btn active" data-year="semua">All</button>
        <?php foreach ($years as $year): ?>
            <button class="year-btn" data-year="<?= $year ?>"><?= $year ?></button>
        <?php endforeach; ?>
    </div>
</div>

<h3 class="section-title">List of English Legal Products</h3>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):
    $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
    $file = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
    $detail = "index.php?page=detail-keputusan&id=" . $row['idData'];
?>
<div class="document-card" data-tahun="<?= $row['tahun_pengundangan']; ?>">
    <div class="card-content">
        <div class="card-number"><?= htmlspecialchars($row['noPeraturan']) ?></div>
        <h3 class="card-title"><?= htmlspecialchars($row['judul']) ?></h3>
        <div class="card-meta">
            <div class="card-date"><i class="bi bi-calendar"></i> <?= $tanggal ?></div>
        </div>
        <div class="card-actions">
            <a href="<?= $detail ?>" class="card-btn btn-view">
                <i class="bi bi-eye"></i> View
            </a>
            <?php if ($file && file_exists($file)): ?>
            <a href="<?= $file ?>" download class="card-btn btn-download">
                <i class="bi bi-download"></i> Download
            </a>
            <?php else: ?>
            <button class="card-btn btn-download" disabled>
                <i class="bi bi-exclamation-circle"></i> Not Available
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
    <h4>No Data Available</h4>
</div>
<?php endif; ?>

</div>

<!-- SIDEBAR -->
<div class="col-lg-4">

<!-- MENU PERUNDANGAN -->
<div class="sidebar-menu">
<h4><i class="bi bi-list"></i> Legislation Menu</h4>
<ul>
<li><a href="?page=perundangan"><i class="bi bi-grid"></i> All Categories</a></li>
<li><a href="?page=keputusan-dprd"><i class="bi bi-file-earmark-medical"></i> DPRD Decisions</a></li>
<li><a href="?page=keputusan-sekretaris"><i class="bi bi-file-earmark-text"></i> Secretary Decisions</a></li>
<li><a href="?page=keputusan-pimpinan"><i class="bi bi-file-earmark-ruled"></i> Leadership Decisions</a></li>
<li><a href="?page=peraturan-dprd"><i class="bi bi-journal-text"></i> DPRD Regulations</a></li>
<li><a href="?page=perda"><i class="bi bi-journal-bookmark"></i> Regional Regulations</a></li>
<li class="active"><a href="#"><i class="bi bi-translate"></i> English Legal Products</a></li>
</ul>
</div>

<!-- STATISTIK -->
<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistics</h4>
<ul>
<li>Total: <strong><?= $total_dokumen ?> documents</strong></li>
<?php while ($stat = mysqli_fetch_assoc($year_stats_result)): ?>
<li><?= $stat['tahun_pengundangan'] ?> : <?= $stat['jumlah'] ?> documents</li>
<?php endwhile; ?>
</ul>
</div>

<!-- DOKUMEN TERBARU -->
<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Popular Documents</h4>
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
<?php include 'footer.php'; ?>
