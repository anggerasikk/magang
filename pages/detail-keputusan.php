<?php
session_start();
include_once "a_db.php";

/* ======================
   VALIDASI ID
====================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location:keputusan-dprd.php");
    exit;
}

/* ======================
   AMBIL DATA
====================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM uploads 
     WHERE idData = ? 
     AND jenis = 'Keputusan_DPRD'
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: keputusan-dprd.php");
    exit;
}

$data = mysqli_fetch_assoc($result);

/* ======================
   FORMAT TANGGAL
====================== */
function tgl($tgl){
    return !empty($tgl) ? date('d F Y', strtotime($tgl)) : '-';
}

/* ======================
   FILE PATH HANDLING
====================== */
/*
 DB            : wb_admin/uploads/pdf/file.pdf
 FILE SERVER   : /home/.../public_html/wb_admin/uploads/pdf/file.pdf
 FILE PUBLIC   : ../wb_admin/uploads/pdf/file.pdf
*/

$pdf_db = !empty($data['pdf_path']) ? $data['pdf_path'] : $data['fileDownload'];

$pdf_public = '';
$pdf_server = '';
$pdf_exists = false;
$pdf_size   = '-';

if (!empty($pdf_db)) {
    $pdf_public = '../' . ltrim($pdf_db, '/');
    $pdf_server = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($pdf_db, '/');

    if (file_exists($pdf_server)) {
        $pdf_exists = true;
        $pdf_size = round(filesize($pdf_server) / 1024 / 1024, 2) . ' MB';
    }
}

$page_title = $data['judul'] . " - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">

<a href="index.php?page=keputusan-dprd" class="btn btn-light mb-3">
    ← Kembali ke Daftar Keputusan DPRD
</a>

<div class="row">
<div class="col-md-9">

<!-- ======================
     METADATA
====================== -->
<div class="card mb-4">
<div class="card-header fw-bold">METADATA</div>
<div class="card-body p-0">

<table class="table table-bordered mb-0">
<tr><td width="30%">Bahasa</td><td>Bahasa Indonesia</td></tr>
<tr><td>Tipe Dokumen</td><td><?= htmlspecialchars($data['singkatanJenis']) ?></td></tr>
<tr><td>Judul</td><td><?= htmlspecialchars($data['judul']) ?></td></tr>
<tr><td>Nomor Peraturan</td><td><?= htmlspecialchars($data['noPeraturan']) ?></td></tr>
<tr><td>Tanggal Penetapan</td><td><?= tgl($data['tanggal_penetapan']) ?></td></tr>
<tr><td>Tanggal Pengundangan</td><td><?= tgl($data['tanggal_pengundangan']) ?></td></tr>
<tr><td>Tahun</td><td><?= htmlspecialchars($data['tahun_pengundangan']) ?></td></tr>
<tr>
    <td>Bidang Hukum</td>
    <td><?= htmlspecialchars($data['bidang_hukum'] ?? '-') ?></td>
</tr>

<tr><td>Status</td><td><?= htmlspecialchars($data['status']) ?: '-' ?></td></tr>
<tr><td>Sumber</td><td><?= htmlspecialchars($data['sumber']) ?: '-' ?></td></tr>
<tr><td>Jumlah Dilihat</td><td><?= (int)$data['views'] ?></td></tr>
</table>

</div>
</div>

<!-- ======================
     URAIAN SINGKAT
====================== -->
<?php if(!empty($data['abstrak']) || !empty($data['keterangan'])): ?>
<div class="card mb-4">
<div class="card-header fw-bold">URAIAN SINGKAT</div>
<div class="card-body">
<?= nl2br(htmlspecialchars($data['abstrak'] ?: $data['keterangan'])) ?>
</div>
</div>
<?php endif; ?>

<!-- ======================
     LAMPIRAN DOKUMEN
====================== -->
<div class="card mb-4">
<div class="card-header fw-bold">📎 LAMPIRAN DOKUMEN</div>
<div class="card-body p-0">

<table class="table table-striped mb-0">
<thead>
<tr>
    <th width="5%">#</th>
    <th width="20%">Tipe</th>
    <th>Nama File</th>
    <th width="15%">Ukuran</th>
    <th width="20%">Aksi</th>
</tr>
</thead>
<tbody>

<?php if($pdf_exists): ?>
<tr>
    <td>1</td>
    <td>Dokumen Utama</td>
    <td><?= basename($pdf_db) ?></td>
    <td><?= $pdf_size ?></td>
    <td>
        <a href="<?= $pdf_public ?>" target="_blank" class="btn btn-sm btn-primary">
            👁 Lihat
        </a>
        <a href="../download.php?id=<?= $id ?>" class="btn btn-sm btn-success">
            ⬇ Download
        </a>
    </td>
</tr>
<?php else: ?>
<tr>
    <td colspan="5" class="text-center text-danger">
        File PDF tidak tersedia
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>

<!-- ======================
     PREVIEW PDF (OPSIONAL)
====================== -->
<?php if($pdf_exists): ?>
<div class="card mb-4">
<div class="card-header fw-bold">📖 Pratinjau Dokumen</div>
<div class="card-body p-0">
<iframe 
    src="<?= $pdf_public ?>" 
    width="100%" 
    height="700"
    style="border:1px solid #ccc">
</iframe>
</div>
</div>
<?php endif; ?>

</div>
</div>
</div>

<?php
include "footer.php";
mysqli_close($koneksi);
?>
