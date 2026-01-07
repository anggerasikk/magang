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
?>
<h4 style='padding-top:15px'><p align="center"><font face="Courier New" color="#00FF00" size="4">Semua Data Ruang</font></p></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if ($_SESSION['level']=='user_admin' || $_SESSION['level']=='user_input'): ?>
                <a class='btn btn-primary' href='index.php?page=ruang&aksi=tambah'><i class='fa fa-plus'></i> Tambah Ruang</a>
            <?php endif; ?>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead class='alert-danger'>
                    <tr>
                        <th>No</th>               
                        <th>Nama Ruang</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $perihal = mysqli_query($koneksi, "SELECT * FROM ruang ORDER BY id_ruang DESC");
                $no = 1;
                while ($i = mysqli_fetch_array($perihal)){
                    echo "<tr class='gradeX'>
                            <td>$no</td>
                            <td>$i[nama_ruang]</td>
                            <td>$i[keterangan]</td>
                            <td style='width:130px' class='text-right'>
                                <a class='btn' href='index.php?page=ruang&aksi=detail&id=$i[id_ruang]' title='Detail'><i class='fa fa-folder-open'></i></a>";
                    
                    if ($_SESSION['level']=='user_admin' || $_SESSION['level']=='user_input'){ 
                        echo "<a class='btn' href='index.php?page=ruang&aksi=edit&id=$i[id_ruang]' title='Edit'><i class='fa fa-pencil-square-o'></i></a>";
                    }
                    
                    if ($_SESSION['level']=='user_admin'){ 
                        echo "<a class='btn' href='index.php?page=ruang&aksi=hapus&id=$i[id_ruang]' title='Hapus' onclick='return confirm(\"Yakin hapus?\")'><i class='fa fa-trash-o'></i></a>";
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
    mysqli_query($koneksi, "DELETE FROM ruang where id_ruang='$_GET[id]'");
    echo "<script>alert('Data Ruang Berhasil Di Hapus.'); location='ruang'</script>";

}elseif ($aksi == 'tambah'){ 
    if (isset($_POST['simpan'])){
        mysqli_query($koneksi, "INSERT INTO ruang (nama_ruang, keterangan) VALUES('$_POST[a]','$_POST[b]')");
        echo "<script>alert('Sukses Menambahkan Data Ruang.'); location='ruang'</script>";
    }
?>

<h4 style='padding-top:15px'></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Tambahkan Data Ruang</strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Ruang</label>
                    <div class="col-lg-6">
                        <input type="text" name="a" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Keterangan</label>
                    <div class="col-lg-8">
                        <textarea name='b' rows="5" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='simpan' class="btn btn-info">Simpan</button>                  
                        <a href="ruang" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php  
}elseif ($aksi == 'edit'){ 
    $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM ruang where id_ruang='$_GET[id]'"));
    if (isset($_POST['update'])){
        mysqli_query($koneksi, "UPDATE ruang SET nama_ruang='$_POST[a]', keterangan='$_POST[b]' where id_ruang='$_GET[id]'");
        echo "<script>alert('Sukses Update Data Ruang.'); location='index.php?page=ruang'</script>";
    }
?>

<h4 style='padding-top:15px'></h4>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Edit Data Ruang</strong></div>
        <div class="panel-body">
            <form action='' class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Ruang</label>
                    <div class="col-lg-6">
                        <input type="text" name="a" class="form-control" value="<?php echo $e['nama_ruang']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Keterangan</label>
                    <div class="col-lg-8">
                        <textarea name='b' rows="5" class="form-control"><?php echo $e['keterangan']; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 pull-right">    
                        <button type="submit" name='update' class="btn btn-info">Update</button>                  
                        <a href="ruang" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php   
}elseif ($aksi == 'detail'){  
    $in = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM ruang where id_ruang='$_GET[id]'"));
?>
<h4 style='padding-top:15px'></h4>
<div class='col-md-12'>
    <div class='panel panel-default'>
        <div class='panel-heading'><strong>Detail Data Ruang</strong></div>
        <div class='panel-body'>
            <div class='form-horizontal'>
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Nama Ruang</label>
                    <div class='col-lg-6'> <?php echo $in['nama_ruang']; ?> </div>
                </div>
                <div class='form-group'>
                    <label class='col-lg-2 control-label'>Keterangan</label>
                    <div class='col-lg-8'> <?php echo $in['keterangan']; ?> </div>
                </div>
                <div class='form-group'>
                    <div class='col-lg-9 pull-right'>
                        <a href="ruang" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p><small>&copy; Aplikasi Notulen Rapat</small></p> 
    </div> 
</footer>