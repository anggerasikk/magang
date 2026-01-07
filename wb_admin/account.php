<?php 
session_start();
include "a_db.php";

// CEK LOGIN
if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == ''){ 
    // AMAN: Gunakan prepared statement untuk mencegah SQL injection
    $user_id = mysqli_real_escape_string($koneksi, $_SESSION['login']);
    $query = "SELECT * FROM phpmu_user WHERE id_user='$user_id'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Data user tidak ditemukan!');
              window.location='index.php'</script>";
        exit();
    }
    
    $e = mysqli_fetch_assoc($result);
    
    // PROSES UPDATE DATA
    if (isset($_POST['update'])){
        // SANITASI INPUT
        $username = mysqli_real_escape_string($koneksi, $_POST['a']);
        $password = $_POST['b'];
        $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['c']);
        $alamat_email = mysqli_real_escape_string($koneksi, $_POST['d']);
        $no_telpon = mysqli_real_escape_string($koneksi, $_POST['e']);
        $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['f']);
        
        // Validasi email
        if (!filter_var($alamat_email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid!');
                  window.location='account'</script>";
            exit();
        }
        
        // Jika password tidak kosong, update dengan password baru
        if ($password != '') {
            $passw = md5($password); // WARNING: MD5 sudah tidak aman, sebaiknya gunakan password_hash()
            $update_query = "UPDATE phpmu_user SET 
                            username = '$username',
                            password = '$passw',
                            nama_lengkap = '$nama_lengkap',
                            alamat_email = '$alamat_email',
                            no_telpon = '$no_telpon',
                            alamat_lengkap = '$alamat_lengkap' 
                            WHERE id_user='$user_id'";
        } else {
            // Jika password kosong, jangan update password
            $update_query = "UPDATE phpmu_user SET 
                            username = '$username',
                            nama_lengkap = '$nama_lengkap',
                            alamat_email = '$alamat_email',
                            no_telpon = '$no_telpon',
                            alamat_lengkap = '$alamat_lengkap' 
                            WHERE id_user='$user_id'";
        }
        
        if(mysqli_query($koneksi, $update_query)) {
            // Update session username jika berubah
            $_SESSION['username'] = $username;
            $_SESSION['name_lengkap'] = $nama_lengkap;
            
            echo "<script>alert('Sukses Update Data Profile anda.');
                  window.location='index.php?page=account'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');
                  window.location='index.php?page=account'</script>";
        }
    }
    
    // AMBIL DATA LAGI UNTUK DITAMPILKAN
    $query2 = "SELECT * FROM phpmu_user WHERE id_user='$user_id'";
    $result2 = mysqli_query($koneksi, $query2);
    $ee = mysqli_fetch_assoc($result2);
?>

<h4 style='padding-top:15px'></h4>
<!-- Basic Data Tables Example -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Edit Informasi Data Login anda 
                (<?php echo "Level : <b style='color:red; text-transform:capitalize'>" . htmlspecialchars($ee['level']) . "</b>"; ?>)
            </strong>
        </div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST" enctype='multipart/form-data'>      
                <div class="form-group">
                    <label class="col-lg-2 control-label">Username</label>
                    <div class="col-lg-4">
                        <input type="text" name="a" class="bg-focus form-control" 
                               value="<?php echo htmlspecialchars($e['username']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-4">
                        <input type="password" name="b" class="bg-focus form-control" 
                               placeholder="Masukkan password baru">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Lengkap</label>
                    <div class="col-lg-6">
                        <input type="text" name="c" class="bg-focus form-control" 
                               value="<?php echo htmlspecialchars($e['nama_lengkap']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Email</label>
                    <div class="col-lg-6">
                        <input type="email" name="d" class="bg-focus form-control" 
                               value="<?php echo htmlspecialchars($e['alamat_email']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">No Telepon</label>
                    <div class="col-lg-3">
                        <input type="text" name="e" class="form-control" 
                               value="<?php echo htmlspecialchars($e['no_telpon']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Alamat Lengkap</label>
                    <div class="col-lg-8">
                        <textarea name='f' rows="5" class="form-control" required><?php echo htmlspecialchars($e['alamat_lengkap']); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='update' class="btn btn-info">Update Data</button>                  
                        <button type="reset" class="btn btn-default">Reset</button>
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