<?php
/**
 * DETAIL KEPUTUSAN DPRD
 * File: detail_keputusan.php
 * Fungsi: Menampilkan detail dokumen keputusan DPRD
 */

session_start();
include_once "a_db.php";


/* ======================
   VALIDASI ID
====================== */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: index.php?page=keputusan-dprd");
    exit;
}

/* ======================
   AMBIL DATA (SEMUA JENIS)
====================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM uploads WHERE idData = ? LIMIT 1"
);

if (!$stmt) {
    die("Error: Gagal menyiapkan query database");
}

mysqli_stmt_bind_param($stmt, "i", $id);
$execute_success = mysqli_stmt_execute($stmt);

if (!$execute_success) {
    die("Error: Gagal mengeksekusi query: " . mysqli_error($koneksi));
}

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php?page=keputusan-dprd");
    exit;
}

if (mysqli_num_rows($result) == 0) {
    die("Error: Dokumen tidak ditemukan di database (ID: $id)");
}

$data = mysqli_fetch_assoc($result); // PERBAIKAN DI SINI
mysqli_stmt_close($stmt);

/* ======================
   FUNGSI BANTU
====================== */
function tgl($tgl) {
    return !empty($tgl) ? date('d F Y', strtotime($tgl)) : '-';
}

/* ======================
   PROSES PENCARIAN FILE
====================== */
$pdf_db = !empty($data['pdf_path']) ? $data['pdf_path'] : $data['fileDownload'];

if (!empty($pdf_db)) {
    $file_name_only = basename($pdf_db);
    // Simple file check - assuming file exists if path is set
    $file_found = true;
    $file_url = $pdf_db;
    $file_name_display = $file_name_only;
    $file_size_formatted = 'N/A'; // Could be enhanced to get actual file size
} else {
    $file_found = false;
    $file_url = '';
    $file_name_display = '';
    $file_size_formatted = '0 MB';
}

/* ======================
   BACK LINK DINAMIS
====================== */
$back_page  = 'keputusan-dprd';
$back_label = 'Keputusan DPRD';

switch ($data['jenis']) {
    case 'Keputusan_Pimpinan_DPRD':
        $back_page  = 'keputusan-pimpinan';
        $back_label = 'Keputusan Pimpinan DPRD';
        break;

    case 'Keputusan_Sekretariat_DPRD':
        $back_page  = 'keputusan-sekretaris';
        $back_label = 'Keputusan Sekretariat DPRD';
        break;
}

$page_title = $data['judul'] . " - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<div class="container py-4">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="keputusan-dprd.php">Keputusan DPRD</a></li>
            <li class="breadcrumb-item active">Detail Dokumen</li>
        </ol>
    </nav>

    <!-- BACK BUTTON -->
    <a href="keputusan-dprd.php" class="btn btn-light mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
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

                            <?php if($file_found): ?>
                            <tr>
                                <td>1</td>
                                <td>Dokumen Utama</td>
                                <td><?= htmlspecialchars($file_name_display) ?></td>
                                <td><?= $file_size_formatted ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-sm btn-primary">
                                        👁 Lihat
                                    </a>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada file lampiran</td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <!-- QUICK ACTIONS -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($file_found): ?>
                        <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" download="<?= htmlspecialchars($file_name_display) ?>" class="btn btn-success">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-download"></i> Download (Tidak Tersedia)
                        </button>
                        <?php endif; ?>
                        <a href="keputusan-dprd.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>

            <!-- HELP CARD -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-question-circle"></i> Bantuan</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">
                        <strong>Masalah download?</strong>
                    </p>
                    <ul class="small">
                        <li>Pastikan koneksi internet stabil</li>
                        <li>Browser mendukung PDF</li>
                        <li>Cek folder Downloads di komputer</li>
                    </ul>
                    <hr>
                    <p class="small mb-0">
                        <i class="fas fa-phone-alt"></i>
                        <strong>Bantuan Teknis:</strong><br>
                        Hubungi Administrator
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
mysqli_close($koneksi);
?>
