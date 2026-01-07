<?php
include 'wb_admin/a_db.php';

$doc = isset($_GET['doc']) ? base64_decode($_GET['doc']) : 0;
$id = intval($doc);

$sql = "SELECT * FROM uploads WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
  echo "Dokumen tidak ditemukan.";
  exit;
}

$data = mysqli_fetch_assoc($result);
$pdf_name = htmlspecialchars($data['pdf_name']);
$pdf_path = 'wb_admin/' . $data['pdf_path'];
$image_path = 'wb_admin/' . $data['image_path'];
$tanggal = date('Y-m-d H:i:s', strtotime($data['uploaded_at']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Dokumen - <?= $pdf_name ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .preview-img {
      max-width: 200px;
      max-height: 200px;
      object-fit: contain;
    }
    iframe {
      width: 100%;
      height: 600px;
      border: 1px solid #ccc;
      margin-top: 20px;
    }
  </style>
</head>
<body class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4">Detail Dokumen</h2>
    <div class="card shadow mx-auto" style="max-width: 600px;">
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th>Nama File</th>
            <td><?= $pdf_name ?></td>
          </tr>
          <tr>
            <th>Tanggal Upload</th>
            <td><?= $tanggal ?></td>
          </tr>
          <tr>
            <th>Gambar</th>
            <td><img src="<?= $image_path ?>" class="preview-img"></td>
          </tr>
          <tr>
            <th>File Dokumen</th>
            <td><a href="<?= $pdf_path ?>" class="btn btn-primary" target="_blank">📥 Download PDF</a></td>
          </tr>
        </table>
      </div>
    </div>

    <h4 class="mt-5">Preview Dokumen</h4>
    <iframe src="<?= $pdf_path ?>"></iframe>

    <div class="text-center mt-4">
      <a href="a_view.php" class="btn btn-secondary">← Kembali ke Galeri</a>
    </div>
  </div>
</body>
</html>
