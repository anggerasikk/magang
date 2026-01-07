<?php
include 'wb_admin/a_db.php';

$sql = "SELECT * FROM uploads ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
  die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Galeri Dokumen Horizontal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      background-color: #eee;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-light">

  <div class="container py-5">
    <h2 class="text-center text-primary mb-4">Galeri Dokumen Horizontal</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 justify-content-center">

      <?php
      while ($row = mysqli_fetch_assoc($result)) {
        $filePdf = htmlspecialchars($row['pdf_name']);
        $fileUrl = 'wb_admin/' . $row['pdf_path'];
        $imageUrl = 'wb_admin/' . $row['image_path'];
        $tanggal = date('d M Y, H:i', strtotime($row['uploaded_at']));
        $encodedId = base64_encode($row['id']);

        echo '
        <div class="col">
          <div class="card text-center p-2 h-100">
            <img src="' . $imageUrl . '" alt="Thumbnail" class="rounded">
            <div class="card-body">
              <h6 class="card-title text-truncate">
                <a href="a_detail?doc=' . $encodedId . '" class="text-decoration-none">' . $filePdf . '</a>
              </h6>
              <p class="card-text text-muted small">' . $tanggal . '</p>
              <a href="' . $fileUrl . '" target="_blank" class="btn btn-outline-primary btn-sm">Lihat PDF</a>
            </div>
          </div>
        </div>';
      }
      ?>

    </div>

    <div class="text-center mt-4">
      <button class="btn btn-primary">1</button>
    </div>

    <footer class="text-center mt-5 text-muted">
      <small>&copy; 2025 - Aplikasi Dokumen</small>
    </footer>
  </div>

</body>
</html>
