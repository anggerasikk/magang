<?php
// keputusan-pimpinan.php
session_start();
include_once "a_db.php";

/* ===============================
   QUERY DATA UTAMA
================================ */
$query = "SELECT * FROM uploads 
          WHERE jenis = 'Keputusan_Pimpinan_DPRD'
          ORDER BY idData DESC";
$result = mysqli_query($koneksi, $query);

/* ===============================
   TOTAL DOKUMEN
================================ */
$total_query = "SELECT COUNT(*) AS total 
                FROM uploads 
                WHERE jenis = 'Keputusan_Pimpinan_DPRD'";
$total_dokumen = mysqli_fetch_assoc(mysqli_query($koneksi, $total_query))['total'];

/* ===============================
   STATISTIK PER TAHUN
================================ */
$year_stats = mysqli_query($koneksi, "
    SELECT tahun_pengundangan, COUNT(*) jumlah
    FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    GROUP BY tahun_pengundangan
    ORDER BY tahun_pengundangan DESC
    LIMIT 5
");

/* ===============================
   DOKUMEN TERBARU
================================ */
$latest = mysqli_query($koneksi, "
    SELECT * FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    ORDER BY tanggal_pengundangan DESC
    LIMIT 5
");

/* ===============================
   FILTER TAHUN
================================ */
$years = [];
$year_q = mysqli_query($koneksi, "
    SELECT DISTINCT tahun_pengundangan
    FROM uploads
    WHERE jenis = 'Keputusan_Pimpinan_DPRD'
    ORDER BY tahun_pengundangan DESC
");
while ($y = mysqli_fetch_assoc($year_q)) {
    $years[] = $y['tahun_pengundangan'];
}

/* ===============================
   HEADER
================================ */
$page_title = "Keputusan Pimpinan DPRD - JDIH DPRD Kota Surakarta";
$page_description = "Kumpulan Keputusan Pimpinan DPRD Kota Surakarta";

include "header.php";
?>

<!-- CSS KHUSUS KEPUTUSAN -->
<style><?php include "style-keputusan-dprd.css"; ?></style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <h1 class="text-white">KEPUTUSAN PIMPINAN DPRD</h1>
        <p class="subtitle">Keputusan Pimpinan DPRD Kota Surakarta</p>
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

<h3 class="section-title">Daftar Keputusan Pimpinan DPRD</h3>

<?php if (mysqli_num_rows($result) > 0): ?>
<div class="card-container">
<?php while ($row = mysqli_fetch_assoc($result)):
    $tanggal = date('d F Y', strtotime($row['tanggal_penetapan']));
    $file = $row['pdf_path'] ?: $row['fileDownload'];
    $image = $row['image_path'];
    $detail = "index.php?page=detail-keputusan&id=".$row['idData'];
?>
<div class="document-card" data-tahun="<?= $row['tahun_pengundangan'] ?>">
    <div class="card-image">
        <?php if ($image && file_exists($image)): ?>
            <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
        <?php else: ?>
            <div class="placeholder">
                <i class="bi bi-file-earmark-ruled"></i>
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
                    <i class="bi bi-x-circle"></i> Tidak Tersedia
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
<li><a href="?page=perundangan"><i class="bi bi-grid"></i> Semua Kategori</a></li>
<li><a href="?page=keputusan-dprd"><i class="bi bi-file-earmark-medical"></i> Keputusan DPRD</a></li>
<li class="active"><a href="#"><i class="bi bi-file-earmark-ruled"></i> Keputusan Pimpinan DPRD</a></li>
<li><a href="?page=keputusan-sekretaris"><i class="bi bi-file-earmark-text"></i> Keputusan Sekretaris DPRD</a></li>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-bar-chart"></i> Statistik</h4>
<ul>
<li>Total: <strong><?= $total_dokumen ?> dokumen</strong></li>
<?php while ($s = mysqli_fetch_assoc($year_stats)): ?>
<li><?= $s['tahun_pengundangan'] ?> : <?= $s['jumlah'] ?></li>
<?php endwhile; ?>
</ul>
</div>

<div class="sidebar-menu">
<h4><i class="bi bi-fire"></i> Dokumen Terbaru</h4>
<ul>
<?php while ($l = mysqli_fetch_assoc($latest)): ?>
<li>
<a href="index.php?page=detail-keputusan&id=<?= $l['idData'] ?>">
<i class="bi bi-file-text"></i> <?= htmlspecialchars($l['noPeraturan']) ?>
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
