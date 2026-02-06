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
    <title>Data Keputusan DPRD - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <div class="container-fluid">
        <h4 style='padding-top:15px; text-align:center;'>
            <font face="Courier New" color="#000000" size="4">
                Data Keputusan DPRD
            </font>
        </h4>
        
        <!-- Basic Data Tables Example -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                        <a class='btn btn-primary' href='index.php?page=tambahprokum&jenis=Keputusan_DPRD'>
                            <i class='fa fa-plus'></i> Tambah Keputusan DPRD
                        </a>
                    <?php endif; ?>
                    <div class="pull-right">
                        <span class="badge bg-info">Total: 
                            <?php 
                            $count_query = "SELECT COUNT(*) as total FROM uploads WHERE jenis = 'Keputusan_DPRD'";
                            $count_result = mysqli_query($koneksi, $count_query);
                            $count_data = mysqli_fetch_assoc($count_result);
                            echo $count_data['total'];
                            ?>
                        </span>
                    </div>
                </div>

                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead class='alert-info'>
                            <tr>
                                <th width="50">No</th>
                                <th width="100">Tahun</th>
                                <th>No Peraturan</th>
                                <th>Judul</th>
                                <th width="100">Status</th>
                                <th width="80">Gambar</th>
                                <th width="80">PDF</th>
                                <th width="200" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        // Query data berdasarkan level user
                        if ($_SESSION['level'] == 'user_admin'){
                            $query = "SELECT * FROM uploads WHERE jenis = 'Keputusan_DPRD' ORDER BY idData DESC";
                        } else {
                            $user_id = mysqli_real_escape_string($koneksi, $_SESSION['login']);
                            $query = "SELECT * FROM uploads WHERE id_user = '$user_id' AND jenis = 'Keputusan_DPRD' ORDER BY idData DESC";
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
                            echo "<a class='btn btn-info btn-xs btn-action' href='index.php?page=kepdprd&aksi=detail&id={$i['idData']}' title='Lihat Detail'>
                                    <i class='fa fa-folder-open'></i>
                                  </a>";
                            
                            // Tombol Edit
                            if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'){ 
                                echo "<a class='btn btn-warning btn-xs btn-action' href='index.php?page=kepdprd&aksi=edit&id={$i['idData']}' title='Edit Data'>
                                        <i class='fa fa-pencil-square-o'></i>
                                      </a>";
                            }
                            
                            // Tombol Hapus hanya untuk admin
                            if ($_SESSION['level'] == 'user_admin'){ 
                                echo "<a class='btn btn-danger btn-xs btn-action' href='index.php?page=kepdprd&aksi=hapus&id={$i['idData']}' 
                                      title='Hapus' onclick='return confirm(\"Yakin menghapus data?\");'>
                                        <i class='fa fa-trash-o'></i>
                                      </a>";
                            }
                            
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
                window.location='index.php?page=kepdprd'
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
                window.location='index.php?page=kepdprd'
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($koneksi) . "');
                window.location='index.php?page=kepdprd'
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
                window.location='index.php?page=kepdprd'
              </script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location='index.php?page=kepdprd'
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
            $fileDownload = $_FILES['pdf_file']['name'];
            $pdf_path = "uploads/pdf/" . basename($fileDownload);
            
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
                    alert('Sukses Update Data Keputusan DPRD.');
                    window.location='index.php?page=kepdprd'
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . mysqli_error($koneksi) . "');
                    window.location='index.php?page=kepdprd'
                  </script>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
<div class="container-fluid">
    <h4 style='padding-top:15px'></h4>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> Edit Data Keputusan DPRD
                <span class="pull-right">
                    ID Dokumen: <strong>#<?php echo $data['idData']; ?></strong>
                </span>
            </div>
            
            <div class="panel-body">
                <form action='' method="POST" enctype='multipart/form-data'>
                    <!-- TABS NAVIGASI -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">
                                <i class="fa fa-info-circle"></i> Informasi Dasar
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">
                                <i class="fa fa-tags"></i> Metadata
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">
                                <i class="fa fa-file"></i> File Dokumen
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">
                                <i class="fa fa-link"></i> URL & Lainnya
                            </a>
                        </li>
                    </ul>
                    
                    <br>
                    
                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        
                        <!-- TAB 1: INFORMASI DASAR -->
                        <div role="tabpanel" class="tab-pane active" id="tab1">
                            <div class="section-title">Informasi Dasar Dokumen</div>
                            
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label required">Tahun Pengundangan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="tahun_pengundangan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['tahun_pengundangan']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label required">Tanggal Pengundangan</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tanggal_pengundangan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['tanggal_pengundangan']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label required">Tanggal Penetapan</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tanggal_penetapan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['tanggal_penetapan']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Jenis Dokumen</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="Keputusan DPRD" readonly style="background-color: #f5f5f5;">
                                            <input type="hidden" name="jenis" value="Keputusan_DPRD">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label required">No. Peraturan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="noPeraturan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['noPeraturan']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="status" class="form-control">
                                                <option value="Berlaku" <?php echo ($data['status'] == 'Berlaku') ? 'selected' : ''; ?>>Berlaku</option>
                                                <option value="Tidak Berlaku" <?php echo ($data['status'] == 'Tidak Berlaku') ? 'selected' : ''; ?>>Tidak Berlaku</option>
                                                <option value="Dicabut" <?php echo ($data['status'] == 'Dicabut') ? 'selected' : ''; ?>>Dicabut</option>
                                                <option value="Diganti" <?php echo ($data['status'] == 'Diganti') ? 'selected' : ''; ?>>Diganti</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bahasa</label>
                                        <div class="col-sm-8">
                                            <select name="bahasa" class="form-control">
                                                <option value="Indonesia" <?php echo ($data['bahasa'] == 'Indonesia') ? 'selected' : ''; ?>>Indonesia</option>
                                                <option value="Inggris" <?php echo ($data['bahasa'] == 'Inggris') ? 'selected' : ''; ?>>Inggris</option>
                                                <option value="Daerah" <?php echo ($data['bahasa'] == 'Daerah') ? 'selected' : ''; ?>>Daerah</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tempat Terbit</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="tempatTerbit" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['tempatTerbit']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Sumber</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sumber" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['sumber']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Penerbit</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="penerbit" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['penerbit']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Judul (Full Width) -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required">Judul Dokumen</label>
                                        <div class="col-sm-10">
                                            <textarea name="judul" class="form-control" rows="3" required><?php echo htmlspecialchars($data['judul']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TAB 2: METADATA -->
                        <div role="tabpanel" class="tab-pane" id="tab2">
                            <div class="section-title">Metadata Dokumen</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Singkatan Jenis</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="singkatanJenis" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['singkatanJenis']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Subjek</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="subjek" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['subjek']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ISBN</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="isbn" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['isbn']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bidang Hukum</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="bidangHukum" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['bidangHukum']); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nomor Induk Buku</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nomorIndukBuku" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['nomorIndukBuku']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Teu Badan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="teuBadan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['teuBadan']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Deskripsi Fisik</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="deskripsiFisik" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['deskripsiFisik']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Operasi</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="operasi" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['operasi']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Panggil</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="noPanggil" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['noPanggil']); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Display</label>
                                        <div class="col-sm-8">
                                            <select name="display" class="form-control">
                                                <option value="Ya" <?php echo ($data['display'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                                <option value="Tidak" <?php echo ($data['display'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TAB 3: FILE DOKUMEN -->
                        <div role="tabpanel" class="tab-pane" id="tab3">
                            <div class="section-title">File dan Dokumen</div>
                            
                            <div class="row">
                                <!-- Gambar Cover -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Gambar Cover Saat Ini</label>
                                        <div class="col-sm-12 text-center">
                                            <?php if (!empty($data['image_path']) && file_exists($data['image_path'])): ?>
                                                <img src="<?php echo $data['image_path']; ?>" class="img-preview-large">
                                                <div class="file-info">
                                                    <i class="fa fa-file-image-o"></i> 
                                                    <?php echo basename($data['image_path']); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert-box alert-warning">
                                                    <i class="fa fa-image"></i> Tidak ada gambar cover
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Ubah Gambar Cover</label>
                                        <div class="col-sm-12">
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            <div class="file-info">
                                                <i class="fa fa-info-circle"></i> Ukuran maks: 2MB. Format: JPG, PNG, GIF
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- File PDF -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">File PDF Saat Ini</label>
                                        <div class="col-sm-12 text-center">
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
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Ubah File PDF</label>
                                        <div class="col-sm-12">
                                            <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                                            <div class="file-info">
                                                <i class="fa fa-info-circle"></i> Ukuran maks: 10MB. Format: PDF
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TAB 4: URL & LAINNYA -->
                        <div role="tabpanel" class="tab-pane" id="tab4">
                            <div class="section-title">URL dan Informasi Lainnya</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">URL Download</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="urlDownload" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['urlDownload']); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">URL Detail Peraturan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="urlDetailPeraturan" class="form-control" 
                                                   value="<?php echo htmlspecialchars($data['urlDetailPeraturan']); ?>">
                                        </div>
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
                        </div>
                    </div>
                    
                    <!-- TOMBOL ACTION -->
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button type="reset" class="btn btn-default btn-custom">
                                        <i class="fa fa-refresh"></i> Reset
                                    </button>
                                    <a href="index.php?page=kepdprd" class="btn btn-default btn-custom">
                                        <i class="fa fa-times"></i> Batal
                                    </a>
                                    <button type="submit" name="update" class="btn btn-primary btn-custom">
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validasi form sebelum submit
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        // Validasi field wajib
        var tahun = document.querySelector('input[name="tahun_pengundangan"]').value;
        var noPeraturan = document.querySelector('input[name="noPeraturan"]').value;
        var judul = document.querySelector('textarea[name="judul"]').value;
        
        if (!tahun.trim() || !noPeraturan.trim() || !judul.trim()) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib (bertanda *)!');
            return false;
        }
        
        // Validasi tanggal
        var tglPengundangan = document.querySelector('input[name="tanggal_pengundangan"]').value;
        var tglPenetapan = document.querySelector('input[name="tanggal_penetapan"]').value;
        
        if (tglPengundangan && tglPenetapan) {
            if (new Date(tglPenetapan) > new Date(tglPengundangan)) {
                if (!confirm('Tanggal Penetapan lebih baru dari Tanggal Pengundangan. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
        }
        
        // Validasi ukuran file gambar
        var imageInput = document.querySelector('input[name="image"]');
        if (imageInput.files[0]) {
            var fileSize = imageInput.files[0].size / 1024 / 1024; // MB
            if (fileSize > 2) {
                e.preventDefault();
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
                e.preventDefault();
                alert('Ukuran PDF terlalu besar! Maksimal 10MB.');
                pdfInput.value = '';
                return false;
            }
            
            // Validasi ekstensi PDF
            var fileName = pdfInput.files[0].name;
            var ext = fileName.split('.').pop().toLowerCase();
            if (ext !== 'pdf') {
                e.preventDefault();
                alert('Hanya file PDF yang diizinkan!');
                pdfInput.value = '';
                return false;
            }
        }
        
        return true;
    });
});
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
                window.location='index.php?page=kepdprd'
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
</head>
<body>
<div class="container-fluid">
    <h4 style='padding-top:15px'></h4>
    
    <div class="detail-panel">
        <div class="detail-header">
            <i class="fa fa-file-text"></i> Detail Data Keputusan DPRD
            <span class="pull-right">
                ID: <strong>#<?php echo $data['idData']; ?></strong>
            </span>
        </div>
        
        <!-- TABS NAVIGASI -->
        <ul class="nav nav-tabs" role="tablist" style="margin: 0; padding: 0 20px; background: #f8f9fa;">
            <li role="presentation" class="active">
                <a href="#detail-tab1" aria-controls="detail-tab1" role="tab" data-toggle="tab">
                    <i class="fa fa-info"></i> Informasi
                </a>
            </li>
            <li role="presentation">
                <a href="#detail-tab2" aria-controls="detail-tab2" role="tab" data-toggle="tab">
                    <i class="fa fa-file"></i> Dokumen
                </a>
            </li>
            <li role="presentation">
                <a href="#detail-tab3" aria-controls="detail-tab3" role="tab" data-toggle="tab">
                    <i class="fa fa-cog"></i> Sistem
                </a>
            </li>
        </ul>
        
        <!-- TAB CONTENT -->
        <div class="tab-content">
            <!-- TAB 1: INFORMASI -->
            <div role="tabpanel" class="tab-pane active" id="detail-tab1">
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
                    <div class="detail-section-title">Metadata</div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Singkatan Jenis:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['singkatanJenis']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Subjek:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['subjek']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">ISBN:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['isbn']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Bidang Hukum:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['bidangHukum']); ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Nomor Induk Buku:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['nomorIndukBuku']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Teu Badan:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['teuBadan']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Deskripsi Fisik:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['deskripsiFisik']); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">No Panggil:</div>
                                <div class="detail-value"><?php echo htmlspecialchars($data['noPanggil']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TAB 2: DOKUMEN -->
            <div role="tabpanel" class="tab-pane" id="detail-tab2">
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
                                    <div class="alert alert-warning">
                                        <i class="fa fa-image fa-3x"></i>
                                        <p class="mt-2">Tidak ada gambar cover</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- File PDF -->
                        <div class="col-md-6">
                            <div class="file-preview">
                                <h4><i class="fa fa-file-pdf-o"></i> File PDF</h4>
                                <?php if (!empty($data['pdf_path']) && file_exists($data['pdf_path'])): ?>
                                    <div class="alert alert-info">
                                        <i class="fa fa-file-pdf-o fa-4x"></i>
                                        <h4>File PDF Tersedia</h4>
                                        <p><strong><?php echo htmlspecialchars($data['fileDownload']); ?></strong></p>
                                        <p>
                                            <strong>Ukuran:</strong> <?php echo round(filesize($data['pdf_path']) / 1024, 2); ?> KB<br>
                                            <strong>Path:</strong> <?php echo htmlspecialchars($data['pdf_path']); ?>
                                        </p>
                                        <div class="btn-group">
                                            <a href="<?php echo $data['pdf_path']; ?>" target="_blank" class="btn btn-info">
                                                <i class="fa fa-eye"></i> Lihat PDF
                                            </a>
                                            <a href="<?php echo $data['pdf_path']; ?>" download class="btn btn-success">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                        <p class="mt-2">Tidak ada file PDF</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <div class="detail-section-title">URL Terkait</div>
                        
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
                </div>
            </div>
            
            <!-- TAB 3: SISTEM -->
            <div role="tabpanel" class="tab-pane" id="detail-tab3">
                <div class="detail-section">
                    <div class="detail-section-title">Informasi Sistem</div>
                    
                    <div class="detail-item">
                        <div class="detail-label">ID Data:</div>
                        <div class="detail-value"><?php echo $data['idData']; ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Operasi:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['operasi']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Display:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['display']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Waktu Unggah:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['uploaded_at']); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- TOMBOL ACTION -->
        <div class="action-buttons">
            <a href="index.php?page=kepdprd" class="btn btn-default btn-detail">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                <a href="index.php?page=kepdprd&aksi=edit&id=<?php echo $id; ?>" class="btn btn-warning btn-detail">
                    <i class="fa fa-edit"></i> Edit Data
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['level'] == 'user_admin'): ?>
                <a href="index.php?page=kepdprd&aksi=hapus&id=<?php echo $id; ?>" 
                   class="btn btn-danger btn-detail"
                   onclick="return confirm('Yakin menghapus data ini?')">
                    <i class="fa fa-trash"></i> Hapus
                </a>
            <?php endif; ?>

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
            alert('Fitur export Word sedang dikembangkan!');
            window.location='index.php?page=kepdprd'
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