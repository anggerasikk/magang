<?php 
session_start();
include "a_db.php";

// CEK LOGIN DAN LEVEL
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == ''){
?>
<h4 style='padding-top:15px'><p align="center"><font face="Courier New" color="#000000" size="4">Data Keputusan DPRD</font></p></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                <a class='btn btn-primary' href='index.php?page=tambahprokum'><i class='fa fa-plus'></i> Tambah Keputusan DPRD</a>
            <?php endif; ?>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead class='alert-info'>
                    <tr>
                        <th>No</th>
                        <th>Tahun Pengundangan</th>
                        <th>Tanggal Pengundangan</th>
                        <th>Tanggal Penetapan</th>
                        <th>Jenis</th>
                        <th>No Peraturan</th>
                        <th>Judul</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                // AMAN: Gunakan prepared statement untuk mencegah SQL injection
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
                    echo "<tr class='gradeX'>
                            <td>$no</td>
                            <td>{$i['tahun_pengundangan']}</td>
                            <td>{$i['tanggal_pengundangan']}</td>
                            <td>{$i['tanggal_penetapan']}</td>
                            <td>{$i['jenis']}</td>
                            <td>{$i['noPeraturan']}</td>                            
                            <td>{$i['judul']}</td>
                            <td style='width:170px' class='text-right'>";
                    
                    // Tombol Lihat Detail untuk semua user
                    echo "<a class='btn btn-info btn-sm' href='index.php?page=kepdprd&aksi=detail&id={$i['idData']}' title='Lihat Detail'><i class='fa fa-folder-open'></i></a>";
                    
                    // Tombol Edit untuk admin dan user_input
                    if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'){ 
                        echo " <a class='btn btn-warning btn-sm' href='index.php?page=kepdprd&aksi=edit&id={$i['idData']}' title='Edit Data'><i class='fa fa-pencil-square-o'></i></a>";
                    }
                    
                    // Tombol Hapus hanya untuk admin
                    if ($_SESSION['level'] == 'user_admin'){ 
                        echo " <a class='btn btn-danger btn-sm' href='index.php?page=kepdprd&aksi=hapus&id={$i['idData']}' title='Hapus' onclick='return confirm(\"Yakin menghapus data?\");'><i class='fa fa-trash-o'></i></a>";
                    }
                    
                    // Tombol Export Word untuk semua
                    echo " <a class='btn btn-success btn-sm' href='index.php?page=kepdprd&aksi=eksport&id={$i['idData']}' title='Export Word'><i class='fa fa-file-word-o'></i></a>";
                    
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
<!-- /Basic Data Tables Example --> 

<?php 
} elseif ($aksi == 'hapus') { 
    // VALIDASI: Cek apakah user adalah admin
    if ($_SESSION['level'] != 'user_admin') {
        echo "<script>alert('Anda tidak memiliki akses untuk menghapus!');
              window.location='index.php?page=kepdprd'</script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $delete_query = "DELETE FROM uploads WHERE idData='$id'";
    
    if(mysqli_query($koneksi, $delete_query)) {
        echo "<script>alert('Data Dokumen Berhasil Dihapus.');
              window.location='index.php?page=kepdprd'</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
              window.location='index.php?page=kepdprd'</script>";
    }

} elseif ($aksi == 'edit') { 
    // VALIDASI: Cek akses edit
    if ($_SESSION['level'] != 'user_admin' && $_SESSION['level'] != 'user_input') {
        echo "<script>alert('Anda tidak memiliki akses untuk mengedit!');
              window.location='index.php?page=kepdprd'</script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Data tidak ditemukan!');
              window.location='index.php?page=kepdprd'</script>";
        exit();
    }
    
    $e = mysqli_fetch_assoc($result);
    
    if (isset($_POST['update'])){
        // SANITASI INPUT
        $a = mysqli_real_escape_string($koneksi, $_POST['a']);
        $b = mysqli_real_escape_string($koneksi, $_POST['b']);
        $c = mysqli_real_escape_string($koneksi, $_POST['c']);
        $d = mysqli_real_escape_string($koneksi, $_POST['d']);
        $e_field = mysqli_real_escape_string($koneksi, $_POST['e']);
        $f = mysqli_real_escape_string($koneksi, $_POST['f']);
        
        $update_query = "UPDATE uploads SET 
                        tahun_pengundangan = '$a',
                        tanggal_pengundangan = '$b',
                        tanggal_penetapan = '$c',
                        jenis = '$d',
                        noPeraturan = '$e_field',
                        judul = '$f'
                        WHERE idData='$id'";
        
        if(mysqli_query($koneksi, $update_query)) {
            echo "<script>alert('Sukses Update Data Keputusan DPRD.');
                  window.location='index.php?page=kepdprd'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
                  window.location='index.php?page=kepdprd'</script>";
        }
    }
?>

<h4 style='padding-top:15px'></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Edit Data Dokumen Keputusan DPRD</strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST" enctype='multipart/form-data'>      
                <div class="form-group">
                    <label class="col-lg-2 control-label">Tahun Pengundangan</label>
                    <div class="col-lg-4">
                        <input type="text" name="a" class="bg-focus form-control" value="<?php echo htmlspecialchars($e['tahun_pengundangan']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-2 control-label">Tanggal Pengundangan</label>
                    <div class="col-lg-4">
                        <input type="date" name="b" class="bg-focus form-control" value="<?php echo htmlspecialchars($e['tanggal_pengundangan']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-2 control-label">Tanggal Penetapan</label>
                    <div class="col-lg-4">
                        <input type="date" name="c" class="form-control" value="<?php echo htmlspecialchars($e['tanggal_penetapan']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-2 control-label">Jenis</label>
                    <div class="col-lg-4">
                        <input type="text" name="d" class="bg-focus form-control" value="<?php echo htmlspecialchars($e['jenis']); ?>" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nomor Peraturan</label>
                    <div class="col-lg-4">
                        <input type="text" name="e" class="bg-focus form-control" value="<?php echo htmlspecialchars($e['noPeraturan']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-2 control-label">Judul</label>
                    <div class="col-lg-8">
                        <textarea name="f" class="form-control" rows="3" required><?php echo htmlspecialchars($e['judul']); ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='update' class="btn btn-info">Update Data</button>                  
                        <a href="index.php?page=kepdprd" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php            
} elseif ($aksi == 'detail') {  
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM uploads WHERE idData='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Data tidak ditemukan!');
              window.location='index.php?page=kepdprd'</script>";
        exit();
    }
    
    $in = mysqli_fetch_assoc($result);
?>
<h4 style='padding-top:15px'></h4>
<!-- Basic Data Tables Example -->
<div class='col-md-12'>
    <div class='panel panel-default'>
        <div class='panel-heading'><strong>Detail Data Keputusan DPRD</strong></div>
        <div class='panel-body'>
            <div class='form-horizontal'>
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Tahun Pengundangan</label>
                    <div class='col-lg-9'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['tahun_pengundangan']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Tanggal Pengundangan</label>
                    <div class='col-lg-4'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['tanggal_pengundangan']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Tanggal Penetapan</label>
                    <div class='col-lg-4'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['tanggal_penetapan']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Jenis</label>
                    <div class='col-lg-4'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['jenis']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Nomor Peraturan</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['noPeraturan']); ?></div>
                    </div>
                </div>

                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Judul</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['judul']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Singkatan Jenis</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['singkatanJenis']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Tempat Terbit</label>
                    <div class='col-lg-4'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['tempatTerbit']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Sumber</label>
                    <div class='col-lg-4'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['sumber']); ?></div>
                    </div>
                </div>

                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Status</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['status']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Bahasa</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['bahasa']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Penerbit</label>
                    <div class='col-lg-8'>
                        <div class='form-control-static'><?php echo htmlspecialchars($in['penerbit']); ?></div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <div class='col-lg-9 pull-right'>
                        <a href="index.php?page=kepdprd" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
} elseif ($aksi == 'eksport') {  
    // LOGIC UNTUK EKSPORT WORD
    echo "<script>alert('Fitur export Word sedang dikembangkan!');
          window.location='index.php?page=kepdprd'</script>";
}
?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy; Aplikasi JDIH DPRD</font></p></small></p> 
    </div> 
</footer>