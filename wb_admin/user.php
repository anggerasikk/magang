<?php 
session_start();
include "a_db.php";

// CEK LOGIN DAN LEVEL
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

// Hanya admin yang bisa akses manajemen user
if($_SESSION['level'] != 'user_admin') {
    echo "<script>alert('Anda tidak memiliki akses!');
          window.location='index.php'</script>";
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$stat = isset($_GET['stat']) ? $_GET['stat'] : '3'; // Default ke admin

// SET STATUS DAN LEVEL BERDASARKAN GET['stat']
if ($stat == '1'){
    $status = 'User Biasa';
    $level = 'user_biasa';
}elseif ($stat == '2'){
    $status = 'User Input';
    $level = 'user_input';
}else{
    $status = 'User Admin';
    $level = 'user_admin';
}

if ($aksi == ''){
?>
<h4 style='padding-top:15px'>Semua Data User <?php echo htmlspecialchars($status); ?></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a class='btn btn-primary' href='index.php?page=user&stat=<?php echo $stat; ?>&aksi=tambah'>
                <i class='fa fa-plus'></i> Tambah <?php echo htmlspecialchars($status); ?>
            </a>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead class='alert-info'>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat Email</th>
                        <th>No Telepon</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $query = "SELECT * FROM phpmu_user WHERE level='$level' ORDER BY id_user DESC";
                $inbox = mysqli_query($koneksi, $query);
                
                if(!$inbox) {
                    die("Query error: " . mysqli_error($koneksi));
                }
                
                $no = 1;
                while ($i = mysqli_fetch_assoc($inbox)){
                    $stat_user = $i['status'] == 'Y' 
                        ? '<span style="color:green">Aktif</span>' 
                        : '<span style="color:red">Non Aktif</span>';
                    
                    echo "<tr class='gradeX'>
                            <td>$no</td>
                            <td>" . htmlspecialchars($i['username']) . "</td>
                            <td>" . htmlspecialchars($i['nama_lengkap']) . "</td>
                            <td>" . htmlspecialchars($i['alamat_email']) . "</td>
                            <td>" . htmlspecialchars($i['no_telpon']) . "</td>
                            <td>$stat_user</td>
                            <td style='width:150px' class='text-right'>";
                    
                    echo "<a class='btn btn-warning btn-sm' href='index.php?page=user&stat=$stat&aksi=edit&id={$i['id_user']}' title='Edit'>
                            <i class='fa fa-pencil-square-o'></i>
                          </a>";
                    
                    echo "<a class='btn btn-danger btn-sm' href='index.php?page=user&stat=$stat&aksi=hapus&id={$i['id_user']}' 
                            title='Hapus' onclick=\"return confirm('Yakin menghapus user ini?');\">
                            <i class='fa fa-trash-o'></i>
                          </a>";
                    
                    $akt_status = $i['status'] == 'Y' ? 'N' : 'Y';
                    $akt_text = $i['status'] == 'Y' ? 'Nonaktifkan' : 'Aktifkan';
                    
                    echo "<a class='btn btn-info btn-sm' href='index.php?page=user&stat=$stat&aksi=aktif&id={$i['id_user']}&akt=$akt_status' 
                            title='$akt_text User'>
                            <i class='fa fa-user'></i>
                          </a>";
                    
                    echo "</td></tr>";
                    $no++;
                }
                
                if($no == 1) {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
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
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Cek apakah mencoba menghapus diri sendiri
    if($id == $_SESSION['login']) {
        echo "<script>alert('Tidak dapat menghapus akun sendiri!');
              window.location='index.php?page=user&stat=$stat'</script>";
        exit();
    }
    
    $delete_query = "DELETE FROM phpmu_user WHERE id_user='$id'";
    
    if(mysqli_query($koneksi, $delete_query)) {
        echo "<script>alert('Data User Berhasil Dihapus.');
              window.location='index.php?page=user&stat=$stat'</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
              window.location='index.php?page=user&stat=$stat'</script>";
    }

} elseif ($aksi == 'aktif') { 
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $akt = mysqli_real_escape_string($koneksi, $_GET['akt']);
    
    // Cek apakah mencoba nonaktifkan diri sendiri
    if($id == $_SESSION['login'] && $akt == 'N') {
        echo "<script>alert('Tidak dapat menonaktifkan akun sendiri!');
              window.location='index.php?page=user&stat=$stat'</script>";
        exit();
    }
    
    $aktiv = $akt == 'Y' ? 'Aktifkan' : 'Non Aktifkan';
    
    $update_query = "UPDATE phpmu_user SET status='$akt' WHERE id_user='$id'";
    
    if(mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Data User Berhasil Di $aktiv.');
              window.location='index.php?page=user&stat=$stat'</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
              window.location='index.php?page=user&stat=$stat'</script>";
    }

} elseif ($aksi == 'tambah') { 
    if (isset($_POST['simpan'])){
        // VALIDASI DAN SANITASI INPUT
        $username = mysqli_real_escape_string($koneksi, $_POST['a']);
        $password = md5($_POST['b']); // WARNING: MD5 sudah tidak aman
        $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['c']);
        $alamat_email = mysqli_real_escape_string($koneksi, $_POST['d']);
        $no_telpon = mysqli_real_escape_string($koneksi, $_POST['e']);
        $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['f']);
        
        // Validasi email
        if (!filter_var($alamat_email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid!');
                  window.history.back();</script>";
            exit();
        }
        
        // Cek apakah username sudah ada
        $check_query = "SELECT id_user FROM phpmu_user WHERE username='$username'";
        $check_result = mysqli_query($koneksi, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Username sudah digunakan!');
                  window.history.back();</script>";
            exit();
        }
        
        // Insert user baru
        $insert_query = "INSERT INTO phpmu_user (username, password, nama_lengkap, alamat_email, no_telpon, alamat_lengkap, level, status, waktu_daftar)           
                         VALUES('$username','$password','$nama_lengkap','$alamat_email','$no_telpon','$alamat_lengkap','$level','Y', NOW())";
        
        if(mysqli_query($koneksi, $insert_query)) {
            echo "<script>alert('Sukses Menambahkan Data $status.');
                  window.location='index.php?page=user&stat=$stat'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
                  window.history.back();</script>";
        }
    }
?>

<h4 style='padding-top:15px'></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Tambahkan Data <?php echo htmlspecialchars($status); ?></strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Username</label>
                    <div class="col-lg-4">
                        <input type="text" name="a" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-4">
                        <input type="password" name="b" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Lengkap</label>
                    <div class="col-lg-6">
                        <input type="text" name="c" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Email</label>
                    <div class="col-lg-6">
                        <input type="email" name="d" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">No Telepon</label>
                    <div class="col-lg-3">
                        <input type="text" name="e" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Lengkap</label>
                    <div class="col-lg-8">
                        <textarea name='f' rows="5" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='simpan' class="btn btn-info">Simpan Data</button>                  
                        <a href="index.php?page=user&stat=<?php echo $stat; ?>" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
} elseif ($aksi == 'edit') { 
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM phpmu_user WHERE id_user='$id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Data tidak ditemukan!');
              window.location='index.php?page=user&stat=$stat'</script>";
        exit();
    }
    
    $e = mysqli_fetch_assoc($result);
    
    if (isset($_POST['update'])){
        // VALIDASI DAN SANITASI
        $username = mysqli_real_escape_string($koneksi, $_POST['a']);
        $password = $_POST['b'];
        $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['c']);
        $alamat_email = mysqli_real_escape_string($koneksi, $_POST['d']);
        $no_telpon = mysqli_real_escape_string($koneksi, $_POST['e']);
        $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['f']);
        
        // Validasi email
        if (!filter_var($alamat_email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid!');
                  window.history.back();</script>";
            exit();
        }
        
        // Cek username unik (kecuali untuk user yang sama)
        $check_query = "SELECT id_user FROM phpmu_user WHERE username='$username' AND id_user != '$id'";
        $check_result = mysqli_query($koneksi, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Username sudah digunakan oleh user lain!');
                  window.history.back();</script>";
            exit();
        }
        
        // Update query
        if (empty($password)) {
            $update_query = "UPDATE phpmu_user SET 
                            username = '$username',
                            nama_lengkap = '$nama_lengkap',
                            alamat_email = '$alamat_email',
                            no_telpon = '$no_telpon',
                            alamat_lengkap = '$alamat_lengkap' 
                            WHERE id_user='$id'";
        } else {
            $password_hash = md5($password);
            $update_query = "UPDATE phpmu_user SET 
                            username = '$username',
                            password = '$password_hash',
                            nama_lengkap = '$nama_lengkap',
                            alamat_email = '$alamat_email',
                            no_telpon = '$no_telpon',
                            alamat_lengkap = '$alamat_lengkap' 
                            WHERE id_user='$id'";
        }
        
        if(mysqli_query($koneksi, $update_query)) {
            echo "<script>alert('Sukses Update Data $status.');
                  window.location='index.php?page=user&stat=$stat'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
                  window.history.back();</script>";
        }
    }
?>

<h4 style='padding-top:15px'></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Edit Data <?php echo htmlspecialchars($status); ?></strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Username</label>
                    <div class="col-lg-4">
                        <input type="text" name="a" class="form-control" value="<?php echo htmlspecialchars($e['username']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-4">
                        <input type="password" name="b" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Lengkap</label>
                    <div class="col-lg-6">
                        <input type="text" name="c" class="form-control" value="<?php echo htmlspecialchars($e['nama_lengkap']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Email</label>
                    <div class="col-lg-6">
                        <input type="email" name="d" class="form-control" value="<?php echo htmlspecialchars($e['alamat_email']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">No Telepon</label>
                    <div class="col-lg-3">
                        <input type="text" name="e" class="form-control" value="<?php echo htmlspecialchars($e['no_telpon']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Lengkap</label>
                    <div class="col-lg-8">
                        <textarea name='f' rows="5" class="form-control"><?php echo htmlspecialchars($e['alamat_lengkap']); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='update' class="btn btn-info">Update Data</button>                  
                        <a href="index.php?page=user&stat=<?php echo $stat; ?>" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p><small>&copy; Aplikasi JDIH DPRD</small></p> 
    </div> 
</footer>