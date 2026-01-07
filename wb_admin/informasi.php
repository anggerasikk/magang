<?php 
session_start();
include "a_db.php";

// CEK LOGIN
if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// FUNGSI KONVERSI
function konversiJenis($jenis) {
    $jenis_array = [
        'Keputusan_DPRD' => 'Keputusan DPRD',
        'Keputusan_Sekretaris_DPRD' => 'Keputusan Sekretaris DPRD',
        'Keputusan_Pimpinan_DPRD' => 'Keputusan Pimpinan DPRD',
        'Peraturan_DPRD' => 'Peraturan DPRD'
    ];
    return $jenis_array[$jenis] ?? $jenis;
}

function konversiTanggal($tanggal) {
    return date('d-m-Y', strtotime($tanggal));
}

function formatStatus($status) {
    switch($status) {
        case 'Berlaku': return '<span class="label label-success">Berlaku</span>';
        case 'Tidak Berlaku': return '<span class="label label-danger">Tidak Berlaku</span>';
        case 'Perubahan': return '<span class="label label-warning">Perubahan</span>';
        default: return '<span class="label label-default">' . $status . '</span>';
    }
}

if ($aksi == ''){
?>
<h4 style='padding-top:15px'><p align="center"><font face="Courier New" color="#00FF00" size="4">Semua Data Informasi</font></p></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if ($_SESSION['level']=='user_admin' || $_SESSION['level']=='user_input'): ?>
                <a class='btn btn-primary' href='index.php?page=informasi&aksi=tambah'><i class='fa fa-plus'></i> Tambah Informasi</a>
            <?php endif; ?>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead class='alert-danger'>
                    <tr>
                        <th>No</th>               
                        <th>Tanggal Tayang</th>
                        <th>Jenis</th>
                        <th>Judul</th>
                        <th>Sumber</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $query = "SELECT * FROM informasi ORDER BY idData DESC";
                $result = mysqli_query($koneksi, $query);
                $no = 1;
                while ($data = mysqli_fetch_array($result)){
                    echo "<tr class='gradeX'>
                            <td>$no</td>
                            <td>" . konversiTanggal($data['tanggal_tayang']) . "</td>
                            <td>" . konversiJenis($data['jenis']) . "</td>
                            <td>" . htmlspecialchars($data['judul']) . "</td>
                            <td>" . htmlspecialchars($data['sumber']) . "</td>
                            <td>" . formatStatus($data['status']) . "</td>
                            <td>";
                    
                    // Tampilkan gambar jika ada
                    if(!empty($data['image_name'])) {
                        echo "<a href='" . htmlspecialchars($data['image_path']) . "' target='_blank' title='Lihat Dokumen'>📄</a>";
                    } else {
                        echo "-";
                    }
                    
                    echo "</td>
                            <td style='width:150px' class='text-right'>
                                <a class='btn' href='index.php?page=informasi&aksi=detail&id=$data[idData]' title='Detail'><i class='fa fa-folder-open'></i></a>";
                    
                    if ($_SESSION['level']=='user_admin' || $_SESSION['level']=='user_input'){ 
                        echo "<a class='btn' href='index.php?page=informasi&aksi=edit&id=$data[idData]' title='Edit'><i class='fa fa-pencil-square-o'></i></a>";
                    }
                    
                    if ($_SESSION['level']=='user_admin'){ 
                        echo "<a class='btn' href='index.php?page=informasi&aksi=hapus&id=$data[idData]' title='Hapus' onclick='return confirm(\"Yakin hapus?\")'><i class='fa fa-trash-o'></i></a>";
                    }
                    
                    echo "</td></tr>";
                    $no++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
}elseif ($aksi == 'hapus'){ 
    // Hapus file gambar jika ada
    $query = mysqli_query($koneksi, "SELECT image_path FROM informasi WHERE idData='$_GET[id]'");
    $data = mysqli_fetch_array($query);
    
    if(!empty($data['image_path']) && file_exists($data['image_path'])) {
        unlink($data['image_path']);
    }
    
    // Hapus data dari database
    mysqli_query($koneksi, "DELETE FROM informasi WHERE idData='$_GET[id]'");
    echo "<script>alert('Data Informasi Berhasil Dihapus.'); location='index.php?page=informasi'</script>";

}elseif ($aksi == 'tambah'){ 
    if (isset($_POST['simpan'])){
        $tanggal_tayang = $_POST['tanggal_tayang'];
        $jenis = $_POST['jenis'];
        $judul = $_POST['judul'];
        $sumber = $_POST['sumber'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsiinformasi'];
        
        // Handle upload file
        $image_name = '';
        $image_path = '';
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/images/";
            if(!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_filename = 'img_' . uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            // Validasi file
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
            if(in_array($file_ext, $allowed_types)) {
                if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_name = $new_filename;
                    $image_path = $target_file;
                }
            }
        }
        
        $query = "INSERT INTO informasi (tanggal_tayang, jenis, judul, sumber, status, deskripsiinformasi, image_name, image_path, uploaded_at) 
                  VALUES ('$tanggal_tayang', '$jenis', '$judul', '$sumber', '$status', '$deskripsi', '$image_name', '$image_path', NOW())";
        
        mysqli_query($koneksi, $query);
        echo "<script>alert('Sukses Menambahkan Data Informasi.'); location='index.php?page=informasi'</script>";
    }
?>

<h4 style='padding-top:15px'></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Tambahkan Data Informasi</strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Tanggal Tayang</label>
                    <div class="col-lg-6">
                        <input type="date" name="tanggal_tayang" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Jenis</label>
                    <div class="col-lg-6">
                        <select name="jenis" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Peraturan_DPRD">Peraturan DPRD</option>
                            <option value="Keputusan_DPRD">Keputusan DPRD</option>
                            <option value="Keputusan_Pimpinan_DPRD">Keputusan Pimpinan DPRD</option>
                            <option value="Keputusan_Sekretaris_DPRD">Keputusan Sekretaris DPRD</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Judul</label>
                    <div class="col-lg-10">
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Sumber</label>
                    <div class="col-lg-6">
                        <input type="text" name="sumber" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Status</label>
                    <div class="col-lg-6">
                        <select name="status" class="form-control">
                            <option value="Berlaku">Berlaku</option>
                            <option value="Tidak Berlaku">Tidak Berlaku</option>
                            <option value="Perubahan">Perubahan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Deskripsi</label>
                    <div class="col-lg-10">
                        <textarea name="deskripsiinformasi" rows="5" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Upload Dokumen</label>
                    <div class="col-lg-6">
                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                        <small class="text-muted">Format: JPG, PNG, GIF, PDF, DOC, DOCX</small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">    
                        <button type="submit" name='simpan' class="btn btn-info">Simpan</button>                  
                        <a href="index.php?page=informasi" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php  
}elseif ($aksi == 'edit'){ 
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "SELECT * FROM informasi WHERE idData='$id'");
    $data = mysqli_fetch_array($query);
    
    if (isset($_POST['update'])){
        $tanggal_tayang = $_POST['tanggal_tayang'];
        $jenis = $_POST['jenis'];
        $judul = $_POST['judul'];
        $sumber = $_POST['sumber'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsiinformasi'];
        
        // Handle update file
        $image_name = $data['image_name'];
        $image_path = $data['image_path'];
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Hapus file lama jika ada
            if(!empty($data['image_path']) && file_exists($data['image_path'])) {
                unlink($data['image_path']);
            }
            
            $target_dir = "uploads/images/";
            if(!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_filename = 'img_' . uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
            if(in_array($file_ext, $allowed_types)) {
                if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_name = $new_filename;
                    $image_path = $target_file;
                }
            }
        }
        
        $query = "UPDATE informasi SET 
                  tanggal_tayang='$tanggal_tayang', 
                  jenis='$jenis', 
                  judul='$judul', 
                  sumber='$sumber', 
                  status='$status', 
                  deskripsiinformasi='$deskripsi',
                  image_name='$image_name',
                  image_path='$image_path',
                  uploaded_at=NOW()
                  WHERE idData='$id'";
        
        mysqli_query($koneksi, $query);
        echo "<script>alert('Sukses Update Data Informasi.'); location='index.php?page=informasi'</script>";
    }
?>

<h4 style='padding-top:15px'></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Edit Data Informasi</strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Tanggal Tayang</label>
                    <div class="col-lg-6">
                        <input type="date" name="tanggal_tayang" class="form-control" value="<?php echo $data['tanggal_tayang']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Jenis</label>
                    <div class="col-lg-6">
                        <select name="jenis" class="form-control" required>
                            <option value="Peraturan_DPRD" <?php echo $data['jenis']=='Peraturan_DPRD'?'selected':''; ?>>Peraturan DPRD</option>
                            <option value="Keputusan_DPRD" <?php echo $data['jenis']=='Keputusan_DPRD'?'selected':''; ?>>Keputusan DPRD</option>
                            <option value="Keputusan_Pimpinan_DPRD" <?php echo $data['jenis']=='Keputusan_Pimpinan_DPRD'?'selected':''; ?>>Keputusan Pimpinan DPRD</option>
                            <option value="Keputusan_Sekretaris_DPRD" <?php echo $data['jenis']=='Keputusan_Sekretaris_DPRD'?'selected':''; ?>>Keputusan Sekretaris DPRD</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Judul</label>
                    <div class="col-lg-10">
                        <input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Sumber</label>
                    <div class="col-lg-6">
                        <input type="text" name="sumber" class="form-control" value="<?php echo htmlspecialchars($data['sumber']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Status</label>
                    <div class="col-lg-6">
                        <select name="status" class="form-control">
                            <option value="Berlaku" <?php echo $data['status']=='Berlaku'?'selected':''; ?>>Berlaku</option>
                            <option value="Tidak Berlaku" <?php echo $data['status']=='Tidak Berlaku'?'selected':''; ?>>Tidak Berlaku</option>
                            <option value="Perubahan" <?php echo $data['status']=='Perubahan'?'selected':''; ?>>Perubahan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Deskripsi</label>
                    <div class="col-lg-10">
                        <textarea name="deskripsiinformasi" rows="5" class="form-control"><?php echo htmlspecialchars($data['deskripsiinformasi']); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">File Saat Ini</label>
                    <div class="col-lg-6">
                        <?php if(!empty($data['image_name'])): ?>
                            <p><a href="<?php echo htmlspecialchars($data['image_path']); ?>" target="_blank"><?php echo htmlspecialchars($data['image_name']); ?></a></p>
                            <label><input type="checkbox" name="hapus_file" value="1"> Hapus file ini</label>
                        <?php else: ?>
                            <p>Tidak ada file</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Ubah File</label>
                    <div class="col-lg-6">
                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">    
                        <button type="submit" name='update' class="btn btn-info">Update</button>                  
                        <a href="index.php?page=informasi" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php   
}elseif ($aksi == 'detail'){  
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "SELECT * FROM informasi WHERE idData='$id'");
    $data = mysqli_fetch_array($query);
?>
<h4 style='padding-top:15px'></h4>
<div class='col-md-12'>
    <div class='panel panel-default'>
        <div class='panel-heading'><strong>Detail Data Informasi</strong></div>
        <div class='panel-body'>
            <div class='form-horizontal'>
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Tanggal Tayang</label>
                    <div class='col-lg-6'> <?php echo konversiTanggal($data['tanggal_tayang']); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Jenis</label>
                    <div class='col-lg-6'> <?php echo konversiJenis($data['jenis']); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Judul</label>
                    <div class='col-lg-10'> <?php echo htmlspecialchars($data['judul']); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Sumber</label>
                    <div class='col-lg-6'> <?php echo htmlspecialchars($data['sumber']); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Status</label>
                    <div class='col-lg-6'> <?php echo formatStatus($data['status']); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Deskripsi</label>
                    <div class='col-lg-10'> <?php echo nl2br(htmlspecialchars($data['deskripsiinformasi'])); ?> </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Dokumen</label>
                    <div class='col-lg-6'>
                        <?php if(!empty($data['image_name'])): ?>
                            <a href="<?php echo htmlspecialchars($data['image_path']); ?>" target="_blank" class="btn btn-primary">
                                <i class="fa fa-download"></i> Download <?php echo htmlspecialchars($data['image_name']); ?>
                            </a>
                        <?php else: ?>
                            Tidak ada dokumen
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Uploaded At</label>
                    <div class='col-lg-6'> <?php echo date('d-m-Y H:i:s', strtotime($data['uploaded_at'])); ?> </div>
                </div>
                
                <div class='form-group'>
                    <div class='col-lg-10 col-lg-offset-2'>
                        <a href="index.php?page=informasi" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p><small>&copy; Aplikasi JDIH DPRD</small></p> 
    </div> 
</footer>