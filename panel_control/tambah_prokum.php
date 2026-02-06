<?php 
    // Perbaikan: Tambah pengecekan isset() untuk $_GET['aksi']
    if (!isset($_GET['aksi']) || $_GET['aksi'] == ''){
?>
        <h4 style='padding-top:15px'><p align="center"><font face="Courier New" color="#000000" size="4">Semua Data Produk Hukum</font></p></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
                
           <iframe width= "560" height= "315" src= "http://localhost/jdihcilacap_pasang_2026/panel_control/tambah_prokum/a_tambah_prokum.php"></iframe>.
               
            </div>
            </div>
            <!-- /Basic Data Tables Example --> 
<?php 
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus'){ 
    // Tambah pengecekan isset() untuk $_GET['id']
    if (isset($_GET['id'])) {
        mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM notulen where id_notulen='".mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['id'])."'");
        echo "<script>window.alert('Data Notulen Berhasil Di Hapus.');
                                window.location='notulen'</script>";
    } else {
        echo "<script>window.alert('ID tidak valid.');
                                window.location='notulen'</script>";
    }

} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'tambah'){ 
    if (isset($_POST['simpan'])){
        // Escape semua input untuk keamanan
        $a = isset($_POST['a']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['a']) : '';
        $b = isset($_POST['b']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['b']) : '';
        $c = isset($_POST['c']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['c']) : '';
        $d = isset($_POST['d']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['d']) : '';
        $e = isset($_POST['e']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['e']) : '';
        $f = isset($_POST['f']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['f']) : '';
        $g = isset($_POST['g']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['g']) : '';
        $h = isset($_POST['h']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['h']) : '';
        $i = isset($_POST['i']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['i']) : '';
        $j = isset($_POST['j']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['j']) : '';
        $k = isset($_POST['k']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['k']) : '';
        $l = isset($_POST['l']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['l']) : '';
        $m = isset($_POST['m']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['m']) : '';
        $n = isset($_POST['n']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['n']) : '';
        $o = isset($_POST['o']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['o']) : '';
        $p = isset($_POST['p']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['p']) : '';
        $q = isset($_POST['q']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['q']) : '';
        $r = isset($_POST['r']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['r']) : '';
        $s = isset($_POST['s']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['s']) : '';
        $t = isset($_POST['t']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['t']) : '';
        $u = isset($_POST['u']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['u']) : '';
        $v = isset($_POST['v']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['v']) : '';
        $w = isset($_POST['w']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['w']) : '';
        $x = isset($_POST['x']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['x']) : '';
        $y = isset($_POST['y']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['y']) : '';
        $z = isset($_POST['z']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['z']) : '';
        $zz = isset($_POST['zz']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['zz']) : '';
        
        $id_user = isset($_SESSION['login']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_SESSION['login']) : '';
            
        mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO notulen (no_notulen, hari, tanggal, jam, tempat, jenis_rapat, rapat_ke, masa_sidang, tahun_sidang, sifat_rapat, agenda, pimpinan_rapat, wakil_ketua_1, wakil_ketua_2, sekretaris_dprd, pencatat, pihak_1, pihak_2, pihak_3, hadir, fraksi_golkar, fraksi_pdip, fraksi_pkb, fraksi_gerindra, fraksi_pksperindo, fraksi_demonasdem, hasil_kegiatan_rapat, id_user)           
                                        VALUES('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k', '$l', '$m', '$n', '$o', '$p', '$q', '$r', '$s', '$t', '$u', '$v', '$w', '$x', '$y', '$z', '$zz', '$id_user')") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
                                       
        echo "<script>window.alert('Sukses Menambahkan Data Notulen .');
                window.location='notulen'</script>";
                
    }
?>

                <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Tambahkan Data Notulen</strong></div>
                <div class="panel-body">
                    <form action='upload.php' class="form-horizontal" method="POST" enctype="multipart/form-data">      

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nomor</label>
                            <div class="col-lg-4">
                            <input type="text" name="a" placeholder="" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
                        
						 <div class="form-group">
                            <label class="col-lg-2 control-label">Judul</label>
                            <div class="col-lg-2">
                            <input type="text" name="b" placeholder="" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
                        <div class="form-group">
                        <label class="col-lg-2 control-label">Jenis</label>
                        <div class="col-lg-4">
                          <select name="c" class="form-control">
                            <option value=''>- Pilih -</option>
                           <option value='Senin'>Senin</option>
                           <option value='Selasa'>Selasa</option>
                           <option value='Rabu'>Rabu</option>
                           <option value='Kamis'>Kamis</option>
                           <option value='Jumat'>Jum'at</option>
                           <option value='Sabtu'>Sabtu</option>
                           <option value='Minggu'>Minggu</option>
                          </select>
                        </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal</label>
                            <div class="col-lg-8">
                            <input type="text" class="combodate form-control" data-format="YYYY-MM-DD" data-template="D  MMM  YYYY" name="d" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">file</label>
                            <div class="col-lg-8">
                            <input type="file" name="document" accept="application/pdf" placeholder="" data-required="true" class="form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">image</label>
                            <div class="col-lg-8">
                            <input type="text" name="f" placeholder="" data-required="true" class="form-control">
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
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit'){ 
    // Tambah pengecekan isset() untuk $_GET['id']
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['id']);
        $result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM notulen where id_notulen='$id'");
        
        if (mysqli_num_rows($result) > 0) {
            $e = mysqli_fetch_array($result);

            if (isset($_POST['update'])){
                // Escape semua input untuk keamanan
                $a = isset($_POST['a']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['a']) : '';
                $b = isset($_POST['b']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['b']) : '';
                $c = isset($_POST['c']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['c']) : '';
                $d = isset($_POST['d']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['d']) : '';
                $e_input = isset($_POST['e']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['e']) : '';
                $f = isset($_POST['f']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['f']) : '';
                $g = isset($_POST['g']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['g']) : '';
                $h = isset($_POST['h']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['h']) : '';
                $i = isset($_POST['i']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['i']) : '';
                $j = isset($_POST['j']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['j']) : '';
                $k = isset($_POST['k']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['k']) : '';
                $l = isset($_POST['l']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['l']) : '';
            
                mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE notulen SET no_notulen = '$a',
                                                    hari = '$b',
                                                    tanggal = '$c',
                                                    jam = '$d',
                                                    tempat = '$e_input',
                                                    jenis_rapat = '$f',
                                                    rapat_ke = '$g',
                                                    masa_sidang = '$h',
                                                    tahun_sidang = '$i',
                                                    sifat_rapat = '$j',
                                                    agenda = '$k',
                                                    pimpinan_rapat = '$l' where id_notulen='$id'");
                                       
                echo "<script>window.alert('Sukses Update Data Notulen .');
                        window.location='notulen'</script>";
            }
?>

                <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Edit Data Notulen</strong></div>
                <div class="panel-body">
                    <form action='' class="form-horizontal" method="POST" data-validate="parsley" enctype='multipart/form-data'>      

                        <div class="form-group">
                            <label class="col-lg-2 control-label">No Notulen</label>
                            <div class="col-lg-4">
                            <input type="text" name="a" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo isset($e['no_notulen']) ? htmlspecialchars($e['no_notulen']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Hari</label>
                            <div class="col-lg-4">
                            <input type="text" name="b" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo isset($e['hari']) ? htmlspecialchars($e['hari']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal</label>
                            <div class="col-lg-8">
                            <input type="text" class="combodate form-control" data-format="YYYY-MM-DD" data-template="D  MMM  YYYY" name="c" value="<?php echo isset($e['tanggal']) && !empty($e['tanggal']) ? $e['tanggal'] : date("Y-m-d"); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jam</label>
                            <div class="col-lg-2">
                            <input type="text" name="d" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo isset($e['jam']) ? htmlspecialchars($e['jam']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                        <label class="col-lg-2 control-label">Tempat</label>
                        <div class="col-lg-4">
                          <select name="e" class="form-control">
                            <option value=''>- Pilih -</option>
                            <?php 
                                $ruang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ruang ORDER BY id_ruang");
                                while ($p = mysqli_fetch_array($ruang)){
                                    if (isset($e['tempat']) && $e['tempat'] == $p['nama_ruang']){
                                        echo "<option value='".htmlspecialchars($p['nama_ruang'])."' selected>".htmlspecialchars($p['nama_ruang'])."</option>";
                                    }else{
                                        echo "<option value='".htmlspecialchars($p['nama_ruang'])."'>".htmlspecialchars($p['nama_ruang'])."</option>";
                                    }
                                }
                            ?>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jenis Rapat</label>
                            <div class="col-lg-8">
                            <input type="text" name="f" placeholder="" data-required="true" class="form-control" value="<?php echo isset($e['jenis_rapat']) ? htmlspecialchars($e['jenis_rapat']) : ''; ?>">
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Rapat Ke</label>
                            <div class="col-lg-8">
                            <input type="text" name="g" placeholder="" data-required="true" class="form-control" value="<?php echo isset($e['rapat_ke']) ? htmlspecialchars($e['rapat_ke']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Masa Sidang</label>
                            <div class="col-lg-4">
                            <input type="text" name="h" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo isset($e['masa_sidang']) ? htmlspecialchars($e['masa_sidang']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tahun Sidang</label>
                            <div class="col-lg-4">
                            <input type="text" name="i" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo isset($e['tahun_sidang']) ? htmlspecialchars($e['tahun_sidang']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Sifat Rapat</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='j' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo isset($e['sifat_rapat']) ? htmlspecialchars($e['sifat_rapat']) : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Agenda</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='k' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo isset($e['agenda']) ? htmlspecialchars($e['agenda']) : ''; ?></textarea>
                            </div>
                        </div>
						
						  <div class="form-group">
                            <label class="col-lg-2 control-label">Pimpinan Rapat</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='l' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo isset($e['pimpinan_rapat']) ? htmlspecialchars($e['pimpinan_rapat']) : ''; ?></textarea>
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
        } else {
            echo "<script>window.alert('Data tidak ditemukan.');
                    window.location='notulen'</script>";
        }
    } else {
        echo "<script>window.alert('ID tidak valid.');
                window.location='notulen'</script>";
    }
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'detail'){  
    // Tambah pengecekan isset() untuk $_GET['id']
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['id']);
        $result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from notulen where id_notulen='$id'");
        
        if (mysqli_num_rows($result) > 0) {
            $in = mysqli_fetch_array($result);
            
            // Fungsi tgl_indo
            if (!function_exists('tgl_indo')) {
                function tgl_indo($tanggal) {
                    if(empty($tanggal)) return '-';
                    $bulan = array (
                        1 => 'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    );
                    $pecahkan = explode('-', $tanggal);
                    
                    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
                }
            }
            
            echo "<h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class='col-md-12'>
            <div class='panel panel-default'>
            <div class='panel-heading'><strong>Detail Data Notulen</strong></div>
                <div class='panel-body'>
                    <form action='' class='form-horizontal' data-validate='parsley' enctype='multipart/form-data'>
                        <div class='form-group'>
                        

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>No Notulen</label>
                            <div class='col-lg-9'>
                            ".(isset($in['no_notulen']) ? htmlspecialchars($in['no_notulen']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Hari</label>
                            <div class='col-lg-4'>
                            ".(isset($in['hari']) ? htmlspecialchars($in['hari']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tanggal</label>
                            <div class='col-lg-8'>
                            ".tgl_indo(isset($in['tanggal']) ? $in['tanggal'] : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Jam</label>
                            <div class='col-lg-2'>
                            ".(isset($in['jam']) ? htmlspecialchars($in['jam']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tempat</label>
                            <div class='col-lg-8'>
                             ".(isset($in['tempat']) ? htmlspecialchars($in['tempat']) : '')."
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Jenis Rapat</label>
                            <div class='col-lg-8'>
                             ".(isset($in['jenis_rapat']) ? htmlspecialchars($in['jenis_rapat']) : '')."
                            </div>
                        </div>
                
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Rapat Ke</label>
                            <div class='col-lg-8'>
                             ".(isset($in['rapat_ke']) ? htmlspecialchars($in['rapat_ke']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Masa Sidang</label>
                            <div class='col-lg-4'>
                            ".(isset($in['masa_sidang']) ? htmlspecialchars($in['masa_sidang']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tahun Sidang</label>
                            <div class='col-lg-4'>
                            ".(isset($in['tahun_sidang']) ? htmlspecialchars($in['tahun_sidang']) : '')."
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Sifat Rapat</label>
                            <div class='col-lg-8'>
                            ".(isset($in['sifat_rapat']) ? htmlspecialchars($in['sifat_rapat']) : '')."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Agenda</label>
                            <div class='col-lg-8'>
                            ".(isset($in['agenda']) ? htmlspecialchars($in['agenda']) : '')."
                            </div>
                        </div>
						
						<div class='form-group'>
                            <label class='col-lg-2 control-label'>Pimpinan Rapat</label>
                            <div class='col-lg-8'>
                            ".(isset($in['pimpinan_rapat']) ? htmlspecialchars($in['pimpinan_rapat']) : '')."
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>";
        } else {
            echo "<script>window.alert('Data tidak ditemukan.');
                    window.location='notulen'</script>";
        }
    } else {
        echo "<script>window.alert('ID tidak valid.');
                window.location='notulen'</script>";
    }
} else {
    echo "<script>window.alert('Aksi tidak valid.');
            window.location='notulen'</script>";
}
?>

             <footer id="footer"> 
                <div class="text-center clearfix">
                    <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy Aplikasi JDIH DPRD</font></p></small></p> 
                </div> 
            </footer> 