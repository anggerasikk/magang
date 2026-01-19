<?php 
session_start();
include "a_db.php";

// CEK LOGIN DAN LEVEL
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// ================================================
// HALAMAN UTAMA (LIST DATA)
// ================================================
if ($aksi == ''){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data English Legal - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
    <!-- Tambahkan DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* Styling khusus untuk tabel */
        .dataTables_wrapper {
            padding: 10px;
        }
        .img-preview {
            max-width: 50px;
            max-height: 50px;
            border-radius: 3px;
            object-fit: cover;
        }
        .btn-action {
            margin: 2px;
            padding: 3px 8px;
        }
        .badge {
            font-size: 85%;
        }
        .table > tbody > tr > td {
            vertical-align: middle;
        }
        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h4 style='padding-top:15px; text-align:center;'>
            <font face="Courier New" color="#000000" size="4">
                Data English Legal
            </font>
        </h4>
        
        <!-- Basic Data Tables Example -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                        <a class='btn btn-primary' href='index.php?page=tambahprokum&jenis=English_Legal'>
                            <i class='fa fa-plus'></i> Tambah English Legal
                        </a>
                    <?php endif; ?>
                    <div class="pull-right">
                        <span class="badge bg-info">Total: 
                            <?php 
                            $count_query = "SELECT COUNT(*) as total FROM uploads WHERE jenis = 'English_Legal'";
                            $count_result = mysqli_query($koneksi, $count_query);
                            $count_data = mysqli_fetch_assoc($count_result);
                            echo $count_data['total'];
                            ?>
                        </span>
                    </div>
                </div>

                <div class="panel-body">
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead class='alert-info'>
                            <tr>
                                <th width="50">No</th>
                                <th width="100">Tahun</th>
                                <th>No Peraturan</th>
                                <th>Judul</th>
                                <th width="120">Status</th>
                                <th width="80">Gambar</th>
                                <th width="80">PDF</th>
                                <th width="180" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        // Query data berdasarkan level user
                        if ($_SESSION['level'] == 'user_admin'){
                            $query = "SELECT * FROM uploads WHERE jenis = 'English_Legal' ORDER BY idData DESC";
                        } else {
                            $user_id = mysqli_real_escape_string($koneksi, $_SESSION['login']);
                            $query = "SELECT * FROM uploads WHERE id_user = '$user_id' AND jenis = 'English_Legal' ORDER BY idData DESC";
                        }
                        
                        $prokum = mysqli_query($koneksi, $query);
                        
                        if(!$prokum) {
                            die("Query error: " . mysqli_error($koneksi));
                        }
                        
                        $no = 1;
                        while ($i = mysqli_fetch_assoc($prokum)){
                            // Cek file gambar
                            $gambar = "-";
                            if (!empty($i['image_path']) && file_exists($i['image_path'])) {
                                $gambar = "<img src='{$i['image_path']}' class='img-preview'>";
                            }
                            
                            // Cek file PDF
                            $pdf_file = "-";
                            if (!empty($i['pdf_path']) && file_exists($i['pdf_path'])) {
                                $pdf_file = "<a href='{$i['pdf_path']}' target='_blank' class='btn btn-xs btn-info'>
                                                <i class='fa fa-file-pdf-o'></i>
                                             </a>";
                            }
                            
                            // Status badge warna
                            $status_badge = "";
                            if ($i['status'] == 'Berlaku') {
                                $status_badge = "<span class='label label-success'>{$i['status']}</span>";
                            } elseif ($i['status'] == 'Tidak Berlaku') {
                                $status_badge = "<span class='label label-danger'>{$i['status']}</span>";
                            } else {
                                $status_badge = "<span class='label label-warning'>{$i['status']}</span>";
                            }
                            
                            echo "<tr>
                                    <td>$no</td>
                                    <td>{$i['tahun_pengundangan']}</td>
                                    <td>{$i['noPeraturan']}</td>                            
                                    <td>{$i['judul']}</td>
                                    <td>$status_badge</td>
                                    <td class='text-center'>$gambar</td>
                                    <td class='text-center'>$pdf_file</td>
                                    <td class='text-center'>";
                            
                            // Tombol Lihat Detail
                            echo "<a class='btn btn-info btn-xs btn-action' href='index.php?page=englishlegal&aksi=detail&id={$i['idData']}' title='Lihat Detail'>
                                    <i class='fa fa-folder-open'></i>
                                  </a>";
                            
                            // Tombol Edit
                            if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'){ 
                                echo "<a class='btn btn-warning btn-xs btn-action' href='index.php?page=englishlegal&aksi=edit&id={$i['idData']}' title='Edit Data'>
                                        <i class='fa fa-pencil-square-o'></i>
                                      </a>";
                            }
                            
                            // Tombol Hapus hanya untuk admin
                            if ($_SESSION['level'] == 'user_admin'){ 
                                echo "<a class='btn btn-danger btn-xs btn-action' href='index.php?page=englishlegal&aksi=hapus&id={$i['idData']}' 
                                      title='Hapus' onclick='return confirm(\"Yakin menghapus data?\");'>
                                        <i class='fa fa-trash-o'></i>
                                      </a>";
                            }
                            
                            // Tombol Export Word
                            echo "<a class='btn btn-success btn-xs btn-action' href='index.php?page=englishlegal&aksi=eksport&id={$i['idData']}' title='Export Word'>
                                    <i class='fa fa-file-word-o'></i>
                                  </a>";
                            
                            echo "</td></tr>";
                            $no++;
                        }
                        
                        if($no == 1) {
                            echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari total _MAX_ data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikut",
                    "previous": "Sebelum"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": [5, 6, 7] }, // Nonaktifkan sorting untuk kolom Gambar, PDF, Action
                { "searchable": false, "targets": [5, 6, 7] } // Nonaktifkan search untuk kolom Gambar, PDF, Action
            ]
        });
    });
    </script>
</body>
</html>

<?php 
// ================================================
// AKSI HAPUS DATA
// ================================================
} elseif ($aksi == 'hapus') { 
    // VALIDASI: Cek apakah user adalah admin
    if ($_SESSION['level'] != 'user_admin') {
        echo "<script>
                alert('Anda tidak memiliki akses untuk menghapus!');
                window.location='index.php?page=englishlegal'
              </script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data untuk hapus file fisik
    $query = "SELECT image_path, pdf_path FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Hapus file gambar jika ada
    if (!empty($data['image_path']) && file_exists($data['image_path'])) {
        unlink($data['image_path']);
    }
    
    // Hapus file PDF jika ada
    if (!empty($data['pdf_path']) && file_exists($data['pdf_path'])) {
        unlink($data['pdf_path']);
    }
    
    // Hapus data dari database
    $delete_query = "DELETE FROM uploads WHERE idData='$id'";
    
    if(mysqli_query($koneksi, $delete_query)) {
        echo "<script>
                alert('Data Dokumen Berhasil Dihapus.');
                window.location='index.php?page=englishlegal'
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($koneksi) . "');
                window.location='index.php?page=englishlegal'
              </script>";
    }

// ================================================
// AKSI EDIT DATA (FORM EDIT)
// ================================================
} elseif ($aksi == 'edit') { 
    // VALIDASI: Cek akses edit
    if ($_SESSION['level'] != 'user_admin' && $_SESSION['level'] != 'user_input') {
        echo "<script>
                alert('Anda tidak memiliki akses untuk mengedit!');
                window.location='index.php?page=englishlegal'
              </script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location='index.php?page=englishlegal'
              </script>";
        exit();
    }
    
    $data = mysqli_fetch_assoc($result);
    
    // Proses update data
    if (isset($_POST['update'])){
        // Sanitasi semua input
        $tahun_pengundangan = mysqli_real_escape_string($koneksi, $_POST['tahun_pengundangan']);
        $tanggal_pengundangan = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengundangan']);
        $tanggal_penetapan = mysqli_real_escape_string($koneksi, $_POST['tanggal_penetapan']);
        $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis']);
        $noPeraturan = mysqli_real_escape_string($koneksi, $_POST['noPeraturan']);
        $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
        $singkatanJenis = mysqli_real_escape_string($koneksi, $_POST['singkatanJenis']);
        $tempatTerbit = mysqli_real_escape_string($koneksi, $_POST['tempatTerbit']);
        $sumber = mysqli_real_escape_string($koneksi, $_POST['sumber']);
        $status = mysqli_real_escape_string($koneksi, $_POST['status']);
        $bahasa = mysqli_real_escape_string($koneksi, $_POST['bahasa']);
        $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
        $subjek = mysqli_real_escape_string($koneksi, $_POST['subjek']);
        $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
        $bidangHukum = mysqli_real_escape_string($koneksi, $_POST['bidangHukum']);
        $nomorIndukBuku = mysqli_real_escape_string($koneksi, $_POST['nomorIndukBuku']);
        $urlDetailPeraturan = mysqli_real_escape_string($koneksi, $_POST['urlDetailPeraturan']);
        $teuBadan = mysqli_real_escape_string($koneksi, $_POST['teuBadan']);
        $deskripsiFisik = mysqli_real_escape_string($koneksi, $_POST['deskripsiFisik']);
        $operasi = mysqli_real_escape_string($koneksi, $_POST['operasi']);
        $display = mysqli_real_escape_string($koneksi, $_POST['display']);
        $noPanggil = mysqli_real_escape_string($koneksi, $_POST['noPanggil']);
        $urlDownload = mysqli_real_escape_string($koneksi, $_POST['urlDownload']);
        
        // Handle upload gambar baru
        $image_name = $data['image_name'];
        $image_path = $data['image_path'];
        
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
            // Generate unique filename
            $image_name = "img_" . uniqid() . ".png";
            $image_path = "uploads/images/" . $image_name;
            
            // Hapus gambar lama jika ada
            if (!empty($data['image_path']) && file_exists($data['image_path'])) {
                unlink($data['image_path']);
            }
            
            // Upload gambar baru
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }
        
        // Handle upload PDF baru
        $fileDownload = $data['fileDownload'];
        $pdf_path = $data['pdf_path'];
        
        if (!empty($_FILES['pdf_file']['name']) && $_FILES['pdf_file']['error'] == 0) {
            $fileDownload = basename($_FILES['pdf_file']['name']);
            $pdf_path = "uploads/pdf/" . $fileDownload;
            
            // Hapus PDF lama jika ada
            if (!empty($data['pdf_path']) && file_exists($data['pdf_path'])) {
                unlink($data['pdf_path']);
            }
            
            // Upload PDF baru
            move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf_path);
        }
        
        // Query update
        $update_query = "UPDATE uploads SET 
                        tahun_pengundangan = '$tahun_pengundangan',
                        tanggal_pengundangan = '$tanggal_pengundangan',
                        tanggal_penetapan = '$tanggal_penetapan',
                        jenis = '$jenis',
                        noPeraturan = '$noPeraturan',
                        judul = '$judul',
                        singkatanJenis = '$singkatanJenis',
                        tempatTerbit = '$tempatTerbit',
                        sumber = '$sumber',
                        status = '$status',
                        bahasa = '$bahasa',
                        penerbit = '$penerbit',
                        subjek = '$subjek',
                        isbn = '$isbn',
                        bidangHukum = '$bidangHukum',
                        nomorIndukBuku = '$nomorIndukBuku',
                        urlDetailPeraturan = '$urlDetailPeraturan',
                        teuBadan = '$teuBadan',
                        deskripsiFisik = '$deskripsiFisik',
                        operasi = '$operasi',
                        display = '$display',
                        noPanggil = '$noPanggil',
                        urlDownload = '$urlDownload',
                        fileDownload = '$fileDownload',
                        pdf_path = '$pdf_path',
                        image_name = '$image_name',
                        image_path = '$image_path'
                        WHERE idData='$id'";
        
        if(mysqli_query($koneksi, $update_query)) {
            echo "<script>
                    alert('Sukses Update Data English Legal.');
                    window.location='index.php?page=englishlegal'
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . mysqli_error($koneksi) . "');
                    window.location='index.php?page=englishlegal'
                  </script>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
    <style>
        .form-horizontal .control-label {
            text-align: left !important;
            padding-top: 7px;
        }
        .form-horizontal .form-group {
            margin-left: 0;
            margin-right: 0;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .img-preview-large {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #337ab7;
            color: #337ab7;
        }
        .alert-box {
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .alert-info {
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            color: #31708f;
        }
        .alert-warning {
            background-color: #fcf8e3;
            border: 1px solid #faebcc;
            color: #8a6d3b;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .btn-custom {
            margin: 5px;
            min-width: 120px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h4 style='padding-top:15px'></h4>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> Edit Data English Legal
                <span class="pull-right">
                    ID Dokumen: <strong>#<?php echo $data['idData']; ?></strong>
                </span>
            </div>
            
            <div class="panel-body">
                <form action='' method="POST" enctype='multipart/form-data' onsubmit="return validateForm()">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <h4>Informasi Dasar</h4>
                            <div class="form-group">
                                <label class="control-label required">Tahun Pengundangan</label>
                                <input type="text" name="tahun_pengundangan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['tahun_pengundangan']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label required">Tanggal Pengundangan</label>
                                <input type="date" name="tanggal_pengundangan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['tanggal_pengundangan']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label required">Tanggal Penetapan</label>
                                <input type="date" name="tanggal_penetapan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['tanggal_penetapan']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Jenis Dokumen</label>
                                <input type="text" class="form-control" value="English Legal" readonly>
                                <input type="hidden" name="jenis" value="English_Legal">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label required">No. Peraturan</label>
                                <input type="text" name="noPeraturan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['noPeraturan']); ?>" required>
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <h4>&nbsp;</h4>
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="Berlaku" <?php echo ($data['status'] == 'Berlaku') ? 'selected' : ''; ?>>Berlaku</option>
                                    <option value="Tidak Berlaku" <?php echo ($data['status'] == 'Tidak Berlaku') ? 'selected' : ''; ?>>Tidak Berlaku</option>
                                    <option value="Dicabut" <?php echo ($data['status'] == 'Dicabut') ? 'selected' : ''; ?>>Dicabut</option>
                                    <option value="Diganti" <?php echo ($data['status'] == 'Diganti') ? 'selected' : ''; ?>>Diganti</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Bahasa</label>
                                <select name="bahasa" class="form-control">
                                    <option value="Indonesia" <?php echo ($data['bahasa'] == 'Indonesia') ? 'selected' : ''; ?>>Indonesia</option>
                                    <option value="Inggris" <?php echo ($data['bahasa'] == 'Inggris') ? 'selected' : ''; ?>>Inggris</option>
                                    <option value="Daerah" <?php echo ($data['bahasa'] == 'Daerah') ? 'selected' : ''; ?>>Daerah</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Tempat Terbit</label>
                                <input type="text" name="tempatTerbit" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['tempatTerbit']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Sumber</label>
                                <input type="text" name="sumber" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['sumber']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['penerbit']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Judul -->
                    <div class="form-group">
                        <label class="control-label required">Judul Dokumen</label>
                        <textarea name="judul" class="form-control" rows="3" required><?php echo htmlspecialchars($data['judul']); ?></textarea>
                    </div>
                    
                    <!-- Metadata -->
                    <h4>Metadata</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Singkatan Jenis</label>
                                <input type="text" name="singkatanJenis" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['singkatanJenis']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Subjek</label>
                                <input type="text" name="subjek" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['subjek']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">ISBN</label>
                                <input type="text" name="isbn" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['isbn']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Bidang Hukum</label>
                                <input type="text" name="bidangHukum" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['bidangHukum']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Nomor Induk Buku</label>
                                <input type="text" name="nomorIndukBuku" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['nomorIndukBuku']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Teu Badan</label>
                                <input type="text" name="teuBadan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['teuBadan']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Deskripsi Fisik</label>
                                <input type="text" name="deskripsiFisik" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['deskripsiFisik']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">No Panggil</label>
                                <input type="text" name="noPanggil" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['noPanggil']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Operasi</label>
                                <input type="text" name="operasi" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['operasi']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Display</label>
                                <select name="display" class="form-control">
                                    <option value="Ya" <?php echo ($data['display'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                    <option value="Tidak" <?php echo ($data['display'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Dokumen -->
                    <h4>File Dokumen</h4>
                    <div class="row">
                        <!-- Gambar Cover -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Gambar Cover Saat Ini</label>
                                <?php if (!empty($data['image_path']) && file_exists($data['image_path'])): ?>
                                    <div class="text-center">
                                        <img src="<?php echo $data['image_path']; ?>" class="img-preview-large">
                                        <p class="file-info">
                                            <i class="fa fa-file-image-o"></i> 
                                            <?php echo basename($data['image_path']); ?>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="alert-box alert-warning">
                                        <i class="fa fa-image"></i> Tidak ada gambar cover
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Ubah Gambar Cover</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div class="file-info">
                                    <i class="fa fa-info-circle"></i> Ukuran maks: 2MB. Format: JPG, PNG, GIF
                                </div>
                            </div>
                        </div>
                        
                        <!-- File PDF -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">File PDF Saat Ini</label>
                                <?php if (!empty($data['pdf_path']) && file_exists($data['pdf_path'])): ?>
                                    <div class="alert-box alert-info">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                        <h4>File PDF Tersedia</h4>
                                        <p><strong><?php echo basename($data['fileDownload']); ?></strong></p>
                                        <p>
                                            <a href="<?php echo $data['pdf_path']; ?>" target="_blank" class="btn btn-xs btn-info">
                                                <i class="fa fa-eye"></i> Lihat PDF
                                            </a>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="alert-box alert-warning">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                        <p>Tidak ada file PDF</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Ubah File PDF</label>
                                <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                                <div class="file-info">
                                    <i class="fa fa-info-circle"></i> Ukuran maks: 10MB. Format: PDF
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- URL -->
                    <h4>URL dan Informasi Lainnya</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">URL Download</label>
                                <input type="text" name="urlDownload" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['urlDownload']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">URL Detail Peraturan</label>
                                <input type="text" name="urlDetailPeraturan" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['urlDetailPeraturan']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="alert-box alert-info">
                                <h4><i class="fa fa-info-circle"></i> Informasi Sistem</h4>
                                <p><strong>ID Dokumen:</strong> <?php echo $data['idData']; ?></p>
                                <p><strong>Terakhir diupdate:</strong> <?php echo $data['uploaded_at']; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TOMBOL ACTION -->
                    <div class="text-center" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                        <button type="reset" class="btn btn-default btn-custom">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                        <a href="index.php?page=englishlegal" class="btn btn-default btn-custom">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" name="update" class="btn btn-primary btn-custom">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    // Validasi field wajib
    var tahun = document.querySelector('input[name="tahun_pengundangan"]').value;
    var noPeraturan = document.querySelector('input[name="noPeraturan"]').value;
    var judul = document.querySelector('textarea[name="judul"]').value;
    
    if (!tahun.trim() || !noPeraturan.trim() || !judul.trim()) {
        alert('Harap isi semua field yang wajib (bertanda *)!');
        return false;
    }
    
    // Validasi tanggal
    var tglPengundangan = document.querySelector('input[name="tanggal_pengundangan"]').value;
    var tglPenetapan = document.querySelector('input[name="tanggal_penetapan"]').value;
    
    if (tglPengundangan && tglPenetapan) {
        if (new Date(tglPenetapan) > new Date(tglPengundangan)) {
            if (!confirm('Tanggal Penetapan lebih baru dari Tanggal Pengundangan. Lanjutkan?')) {
                return false;
            }
        }
    }
    
    // Validasi ukuran file gambar
    var imageInput = document.querySelector('input[name="image"]');
    if (imageInput.files[0]) {
        var fileSize = imageInput.files[0].size / 1024 / 1024; // MB
        if (fileSize > 2) {
            alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
            imageInput.value = '';
            return false;
        }
    }
    
    // Validasi ukuran file PDF
    var pdfInput = document.querySelector('input[name="pdf_file"]');
    if (pdfInput.files[0]) {
        var fileSize = pdfInput.files[0].size / 1024 / 1024; // MB
        if (fileSize > 10) {
            alert('Ukuran PDF terlalu besar! Maksimal 10MB.');
            pdfInput.value = '';
            return false;
        }
        
        // Validasi ekstensi PDF
        var fileName = pdfInput.files[0].name;
        var ext = fileName.split('.').pop().toLowerCase();
        if (ext !== 'pdf') {
            alert('Hanya file PDF yang diizinkan!');
            pdfInput.value = '';
            return false;
        }
    }
    
    return true;
}
</script>
</body>
</html>

<?php            

// ================================================
// AKSI DETAIL DATA (VIEW DETAIL)
// ================================================
} elseif ($aksi == 'detail') {  
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location='index.php?page=englishlegal'
              </script>";
        exit();
    }
    
    $data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Data - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
    <style>
        .detail-panel {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
        .detail-header {
            background: #337ab7;
            color: white;
            padding: 15px 20px;
            border-radius: 5px 5px 0 0;
            font-size: 18px;
        }
        .detail-section {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .detail-section:last-child {
            border-bottom: none;
        }
        .detail-section-title {
            font-size: 16px;
            font-weight: bold;
            color: #337ab7;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .detail-item {
            margin-bottom: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 3px;
            border-left: 3px solid #337ab7;
        }
        .detail-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #666;
            word-wrap: break-word;
        }
        .badge-detail {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .badge-success { background: #5cb85c; color: white; }
        .badge-danger { background: #d9534f; color: white; }
        .badge-warning { background: #f0ad4e; color: white; }
        .img-detail {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .file-preview {
            text-align: center;
            padding: 15px;
        }
        .action-buttons {
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 0 0 5px 5px;
            border-top: 1px solid #eee;
        }
        .btn-detail {
            margin: 0 5px;
            min-width: 100px;
        }
        .no-data {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h4 style='padding-top:15px'></h4>
    
    <div class="detail-panel">
        <div class="detail-header">
            <i class="fa fa-file-text"></i> Detail Data English Legal
            <span class="pull-right">
                ID: <strong>#<?php echo $data['idData']; ?></strong>
            </span>
        </div>
        
        <div class="detail-section">
            <div class="detail-section-title">Informasi Dasar</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Tahun Pengundangan:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['tahun_pengundangan']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Pengundangan:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['tanggal_pengundangan']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Penetapan:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['tanggal_penetapan']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Jenis Dokumen:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['jenis']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">No. Peraturan:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['noPeraturan']); ?></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <?php 
                            $status_class = '';
                            if ($data['status'] == 'Berlaku') $status_class = 'badge-success';
                            elseif ($data['status'] == 'Tidak Berlaku') $status_class = 'badge-danger';
                            else $status_class = 'badge-warning';
                            ?>
                            <span class="badge-detail <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($data['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Bahasa:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['bahasa']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Tempat Terbit:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['tempatTerbit']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Sumber:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['sumber']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Penerbit:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['penerbit']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Judul Dokumen:</div>
                <div class="detail-value"><?php echo htmlspecialchars($data['judul']); ?></div>
            </div>
        </div>
        
        <div class="detail-section">
            <div class="detail-section-title">File Dokumen</div>
            
            <div class="row">
                <!-- Gambar Cover -->
                <div class="col-md-6">
                    <div class="file-preview">
                        <h4><i class="fa fa-image"></i> Gambar Cover</h4>
                        <?php if (!empty($data['image_path']) && file_exists($data['image_path'])): ?>
                            <img src="<?php echo $data['image_path']; ?>" class="img-detail">
                            <p class="mt-3">
                                <strong>Nama File:</strong> <?php echo htmlspecialchars($data['image_name']); ?><br>
                                <strong>Path:</strong> <?php echo htmlspecialchars($data['image_path']); ?>
                            </p>
                            <a href="<?php echo $data['image_path']; ?>" target="_blank" class="btn btn-info">
                                <i class="fa fa-external-link"></i> Lihat Full Size
                            </a>
                        <?php else: ?>
                            <div class="detail-item">
                                <i class="fa fa-image"></i>
                                <p class="no-data">Tidak ada gambar cover</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- File PDF -->
                <div class="col-md-6">
                    <div class="file-preview">
                        <h4><i class="fa fa-file-pdf-o"></i> File PDF</h4>
                        <?php if (!empty($data['pdf_path']) && file_exists($data['pdf_path'])): ?>
                            <div class="detail-item">
                                <i class="fa fa-file-pdf-o"></i>
                                <h4>File PDF Tersedia</h4>
                                <p><strong><?php echo htmlspecialchars($data['fileDownload']); ?></strong></p>
                                <p>
                                    <strong>Ukuran:</strong> <?php echo round(filesize($data['pdf_path']) / 1024, 2); ?> KB<br>
                                    <strong>Path:</strong> <?php echo htmlspecialchars($data['pdf_path']); ?>
                                </p>
                                <div class="mt-3">
                                    <a href="<?php echo $data['pdf_path']; ?>" target="_blank" class="btn btn-info">
                                        <i class="fa fa-eye"></i> Lihat PDF
                                    </a>
                                    <a href="<?php echo $data['pdf_path']; ?>" download class="btn btn-success">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="detail-item">
                                <i class="fa fa-file-pdf-o"></i>
                                <p class="no-data">Tidak ada file PDF</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="detail-section">
            <div class="detail-section-title">Informasi Lainnya</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">URL Download:</div>
                        <div class="detail-value">
                            <?php if (!empty($data['urlDownload'])): ?>
                                <a href="<?php echo htmlspecialchars($data['urlDownload']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($data['urlDownload']); ?>
                                </a>
                            <?php else: ?>
                                <span class="no-data">Tidak tersedia</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">URL Detail Peraturan:</div>
                        <div class="detail-value">
                            <?php if (!empty($data['urlDetailPeraturan'])): ?>
                                <a href="<?php echo htmlspecialchars($data['urlDetailPeraturan']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($data['urlDetailPeraturan']); ?>
                                </a>
                            <?php else: ?>
                                <span class="no-data">Tidak tersedia</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Terakhir diupdate:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['uploaded_at']); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- TOMBOL ACTION -->
        <div class="action-buttons">
            <a href="index.php?page=englishlegal" class="btn btn-default btn-detail">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                <a href="index.php?page=englishlegal&aksi=edit&id=<?php echo $id; ?>" class="btn btn-warning btn-detail">
                    <i class="fa fa-edit"></i> Edit Data
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['level'] == 'user_admin'): ?>
                <a href="index.php?page=englishlegal&aksi=hapus&id=<?php echo $id; ?>" 
                   class="btn btn-danger btn-detail"
                   onclick="return confirm('Yakin menghapus data ini?')">
                    <i class="fa fa-trash"></i> Hapus
                </a>
            <?php endif; ?>
            <a href="index.php?page=englishlegal&aksi=eksport&id=<?php echo $id; ?>" class="btn btn-success btn-detail">
                <i class="fa fa-file-word-o"></i> Export Word
            </a>
        </div>
    </div>
</div>
</body>
</html>

<?php 

// ================================================
// AKSI EKSPORT (FITUR EKSPORT WORD)
// ================================================
} elseif ($aksi == 'eksport') {  
    // LOGIC UNTUK EKSPORT WORD
    echo "<script>
            alert('Fitur export Word sedang dikembangan!');
            window.location='index.php?page=englishlegal'
          </script>";
}
?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p>
            <small>
                <font face="Courier New" color="#F0F8FF" size="4">
                    &copy; Aplikasi JDIH DPRD
                </font>
            </small>
        </p> 
    </div> 
</footer>