<?php
include 'a_db.php';

$tahun_pengundangan = $_POST['tahun_pengundangan'];
$tanggal_pengundangan = $_POST['tanggal_pengundangan'];
$tanggal_penetapan = $_POST['tanggal_penetapan'];
$jenis = $_POST['jenis'];
$noPeraturan = $_POST['noPeraturan'];
$judul = $_POST['judul'];
$singkatanJenis = $_POST['singkatanJenis'];
$tempatTerbit = $_POST['tempatTerbit'];
$sumber = $_POST['sumber'];
$status = $_POST['status'];
$bahasa = $_POST['bahasa'];
$operasi = $_POST['operasi'];
$display = $_POST['display'];
$noPanggil = $_POST['noPanggil'];
$penerbit = $_POST['penerbit'];
$subjek = $_POST['subjek'];
$isbn = $_POST['isbn'];
$bidangHukum = $_POST['bidangHukum'];
$nomorIndukBuku = $_POST['nomorIndukBuku'];

$teuBadan = $_POST['teuBadan'];
$deskripsiFisik = $_POST['deskripsiFisik'];

$pdf_dir = "uploads/pdf/";
$image_dir = "uploads/images/";
$maxSize = 2 * 1024 * 1024; // Max 2MB

if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0777, true);
if (!is_dir($image_dir)) mkdir($image_dir, 0777, true);

// Validasi ukuran file
if ($_FILES['pdf']['size'] > $maxSize || $_FILES['image']['size'] > $maxSize) {
    echo "Ukuran file terlalu besar. Maksimal 2MB.";
    exit;
}

// Validasi MIME
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$pdf_mime = finfo_file($finfo, $_FILES['pdf']['tmp_name']);
$image_mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
finfo_close($finfo);

if ($pdf_mime !== 'application/pdf') {
    echo "File bukan PDF valid.";
    exit;
}

$allowed_images = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($image_mime, $allowed_images)) {
    echo "File gambar tidak valid (hanya JPG/PNG/GIF).";
    exit;
}

// Gunakan nama file asli untuk PDF
$pdf_name = basename($_FILES['pdf']['name']);
$pdf_name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $pdf_name); // Sanitize
$pdf_path = $pdf_dir . $pdf_name;

$base_url = "http://localhost/jdihcilacap/wb_admin/uploads/pdf/";
$urlDownload = $base_url . $pdf_name;

$detail = "perpu_detail?doc=<?= $encodedId ?>";
$base_detail_url = "http://localhost/jdihcilacap/";
$urlDetailPeraturan = $base_detail_url . $detail;

// Gunakan nama unik untuk gambar
$image_ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
$image_name = uniqid("img_") . "." . $image_ext;
$image_path = $image_dir . $image_name;

// Simpan file
if (
    move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_path) &&
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path)
) {
    // Simpan ke DB
    $stmt = $koneksi->prepare("INSERT INTO uploads (
        tahun_pengundangan, tanggal_pengundangan, tanggal_penetapan, jenis, noPeraturan, judul, singkatanJenis, tempatTerbit, sumber, status, bahasa, operasi, display, noPanggil, penerbit, subjek, isbn, bidangHukum, nomorIndukBuku, urlDetailPeraturan, teuBadan, deskripsiFisik,
        fileDownload, pdf_path, urlDownload, image_name, image_path, uploaded_at
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
    )");

    $stmt->bind_param("sssssssssssssssssssssssssss",
        $tahun_pengundangan, $tanggal_pengundangan, $tanggal_penetapan, $jenis, $noPeraturan, $judul, $singkatanJenis, $tempatTerbit, $sumber, $status, $bahasa, $operasi, $display, $noPanggil, $penerbit, $subjek, $isbn, $bidangHukum, $nomorIndukBuku, $urlDetailPeraturan, $teuBadan, $deskripsiFisik,
        $pdf_name, $pdf_path, $urlDownload, $image_name, $image_path
    );

    $stmt->execute();

    echo "<script>window.alert('Sukses Menambahkan Data Produk Hukum.'); window.location='index.php?page=tambahprokum'</script>";
} else {
    echo "Gagal upload file.";
}
?>
