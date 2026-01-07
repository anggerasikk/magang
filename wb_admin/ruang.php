<?php 
    if ($_GET[aksi]==''){
?>
        <h4 style='padding-top:15px'><p align="justify"><font face="Courier New" color="#00FF00" size="4">Semua Data Ruang</font></p></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php if ($_SESSION[level]=='user_admin'){ ?>
                        <a class='btn btn-primary' href='index.php?page=ruang&aksi=tambah'><i class='fa fa-plus'></i> Tambah Ruang</a>
                       
                    <?php }elseif ($_SESSION[level]=='user_input'){ ?>
                        <a class='btn btn-primary' href='index.php?page=ruang&aksi=tambah'><i class='fa fa-plus'></i> Tambah Ruang</a>
                    <?php } ?>
                </div>

                <div class="panel-body">
                 <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead  class='alert-danger'>
                    <tr>
                        <th>No</th>               
                        <th>Nama Ruang</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $perihal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ruang ORDER BY id_ruang DESC");
                        $no = 1;
                        while ($i = mysqli_fetch_array($perihal)){
                            echo "<tr class='gradeX'>
                                    <td>$no</td>
                                    <td>$i[nama_ruang]</td>
                                    <td>$i[keterangan]</td>";
                                        if ($_SESSION[level]=='user_admin'){ 
                                            echo "<td style='width:130px' class='text-right'>
                                                <a class='btn' href='index.php?page=ruang&aksi=detail&id=$i[id_ruang]' title='Lihat Detail Surat ini'><i class='fa fa-folder-open'></i></a>
                                                <a class='btn' href='index.php?page=ruang&aksi=edit&id=$i[id_ruang]' title='Edit Data Surat ini'><i class='fa fa-pencil-square-o'></i></a>
                                                <a class='btn' href='index.php?page=ruang&aksi=hapus&id=$i[id_ruang]' title='Hapus Surat ini'><i class='fa fa-trash-o'></i></a>";
                                        }elseif ($_SESSION[level]=='user_input'){ 
                                            echo "<td style='width:90px' class='text-right'>
                                                <a class='btn' href='index.php?page=ruang&aksi=detail&id=$i[id_ruang]' title='Lihat Detail Surat ini'><i class='fa fa-folder-open'></i></a>
                                                <a class='btn' href='index.php?page=ruang&aksi=edit&id=$i[id_ruang]' title='Edit Data Surat ini'><i class='fa fa-pencil-square-o'></i></a>";
                                        }else{
                                            echo "<td style='width:45px' class='text-right'>
                                                <a class='btn' href='index.php?page=ruang&aksi=detail&id=$i[id_ruang]' title='Lihat Detail Surat ini'><i class='fa fa-folder-open'></i></a>";
                                        }
                                    echo "</td>
                                 </tr>";
                            $no++;
                        }
                    ?>

                    </tbody>
                    </table>
                </div>
            </div>
            </div>
            <!-- /Basic Data Tables Example --> 

<?php 
}elseif ($_GET[aksi]=='hapus'){ 
    mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM ruang where id_ruang='$_GET[id]'");
    echo "<script>window.alert('Data Ruang Berhasil Di Hapus.');
                                window.location='ruang'</script>";

}elseif ($_GET[aksi]=='tambah'){ 
    if (isset($_POST[simpan])){
                        mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ruang (nama_ruang, keterangan)           
                                        VALUES('$_POST[a]','$_POST[b]')");
                                       
                        echo "<script>window.alert('Sukses Menambahkan Data Ruang .');
                                window.location='ruang'</script>";
    }
?>

                <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Tambahkan Data Ruang</strong></div>
                <div class="panel-body">
                    <form action='' class="form-horizontal" method="POST" data-validate="parsley" enctype='multipart/form-data'>      


                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nama Ruang</label>
                            <div class="col-lg-6">
                            <input type="text" name="a" placeholder="Masukkan Nama Ruang" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 control-label">Keterangan</label>
                            <div class="col-lg-8">
                            <textarea placeholder="Masukkan Keterangan" name='b' rows="5" class="form-control" data-trigger="keyup"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-9 pull-right">    
                            <button type="submit" name='simpan' class="btn btn-info">Simpan Data</button>                  
                            <button type="reset" class="btn btn-default">Reset</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>

<?php  
}elseif ($_GET[aksi]=='edit'){ 
    $e = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ruang where id_ruang='$_GET[id]'"));
        if (isset($_POST[update])){
                        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ruang SET         nama_ruang  = '$_POST[a]',
                                                              keterangan    = '$_POST[b]' where id_ruang='$_GET[id]'");
                                       
                        echo "<script>window.alert('Sukses Update Data Ruang .');
                                window.location='ruang'</script>";
        }
?>

                <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Edit Data Perihal</strong></div>
                <div class="panel-body">
                    <form action='' class="form-horizontal" method="POST" data-validate="parsley" enctype='multipart/form-data'>      

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nama Ruang</label>
                            <div class="col-lg-6">
                            <input type="text" name="a" placeholder="Masukkan Nama Ruang" data-required="true" class="bg-focus form-control" value="<?php echo $e[nama_ruang]; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 control-label">Keterangan</label>
                            <div class="col-lg-8">
                            <textarea placeholder="Masukkan Keterangan" name='b' rows="5" class="form-control" data-trigger="keyup"><?php echo $e[keterangan]; ?></textarea>
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
<?php   
}elseif ($_GET[aksi]=='detail'){  
    $in = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ruang where id_ruang='$_GET[id]'"));
    echo "<h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class='col-md-12'>
            <div class='panel panel-default'>
            <div class='panel-heading'><strong>Detail Data Ruang</strong></div>
                <div class='panel-body'>
                    <form action='' class='form-horizontal' data-validate='parsley' enctype='multipart/form-data'>

                        

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Nama Ruang</label>
                            <div class='col-lg-6'>
                                $in[nama_ruang]
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Keterangan</label>
                            <div class='col-lg-8'>
                                 $in[keterangan] <br><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>";
} 
?>

             <footer id="footer"> 
                <div class="text-center clearfix">
                    <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy Aplikasi Notulen Rapat</font></p></small></p> 
                </div> 
            </footer> 