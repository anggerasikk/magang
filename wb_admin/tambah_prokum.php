<?php 
    if ($_GET[aksi]==''){
?>
        <h4 style='padding-top:15px'><p align="center"><font face="Courier New" color="#000000" size="4">Semua Data Produk Hukum</font></p></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
                
           <iframe width= "560" height= "315" src= "http://localhost/jdihcilacap/wb_admin/tambah_prokum/a_tambah_prokum.php"></iframe>.
               
            </div>
            </div>
            <!-- /Basic Data Tables Example --> 
<?php 
}elseif ($_GET[aksi]=='hapus'){ 
    mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM notulen where id_notulen='$_GET[id]'");
    echo "<script>window.alert('Data Notulen Berhasil Di Hapus.');
                                window.location='notulen'</script>";

}elseif ($_GET[aksi]=='tambah'){ 
    if (isset($_POST[simpan])){
            
                        mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO notulen (no_notulen, hari, tanggal, jam, tempat, jenis_rapat, rapat_ke, masa_sidang, tahun_sidang, sifat_rapat, agenda, pimpinan_rapat, wakil_ketua_1, wakil_ketua_2, sekretaris_dprd, pencatat, pihak_1, pihak_2, pihak_3, hadir, fraksi_golkar, fraksi_pdip, fraksi_pkb, fraksi_gerindra, fraksi_pksperindo, fraksi_demonasdem, hasil_kegiatan_rapat, id_user)           
                                        VALUES('$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_POST[h]','$_POST[i]','$_POST[j]','$_POST[k]', '$_POST[l]', '$_POST[m]', '$_POST[n]', '$_POST[o]', '$_POST[p]', '$_POST[q]', '$_POST[r]', '$_POST[s]', '$_POST[t]', '$_POST[u]', '$_POST[v]', '$_POST[w]', '$_POST[x]', '$_POST[y]', '$_POST[z]', '$_POST[zz]', '$_SESSION[login]')") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
                                       
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
}elseif ($_GET[aksi]=='edit'){ 
    $e = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM notulen where id_notulen='$_GET[id]'"));

    if (isset($_POST[update])){
            
                        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE notulen SET no_notulen     = '$_POST[a]',
                                                        hari         = '$_POST[b]',
                                                        tanggal         = '$_POST[c]',
                                                        jam             = '$_POST[d]',
                                                        tempat         = '$_POST[e]',
                                                        jenis_rapat         = '$_POST[f]',
                                                        rapat_ke         = '$_POST[g]',
                                                        masa_sidang          = '$_POST[h]',
                                                        tahun_sidang     = '$_POST[i]',
                                                        sifat_rapat     = '$_POST[j]',
														agenda     = '$_POST[k]',
                                                        pimpinan_rapat     = '$_POST[l]'        where id_notulen='$_GET[id]'");
                                       
                        echo "<script>window.alert('Sukses Update Data Notulen .');
                                window.location='notulen'</script>";
                
    }
?>

                <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Edit Data Surat Masuk</strong></div>
                <div class="panel-body">
                    <form action='' class="form-horizontal" method="POST" data-validate="parsley" enctype='multipart/form-data'>      

                        <div class="form-group">
                            <label class="col-lg-2 control-label">No Notulen</label>
                            <div class="col-lg-4">
                            <input type="text" name="a" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo $e[no_notulen]; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Hari</label>
                            <div class="col-lg-4">
                            <input type="text" name="b" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo $e[hari]; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal</label>
                            <div class="col-lg-8">
                            <input type="text" class="combodate form-control" data-format="YYYY-MM-DD" data-template="D  MMM  YYYY" name="c" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jam</label>
                            <div class="col-lg-2">
                            <input type="text" name="d" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo $e[jam]; ?>">
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
                                    if ($e['tempat']==$p['nama_ruang']){
                                        echo "<option value='$p[nama_ruang]' selected>$p[nama_ruang]</option>";
                                    }else{
                                        echo "<option value='$p[nama_ruang]'> $p[nama_ruang]</option>";
                                    }
                                }
                            ?>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jenis_Rapat</label>
                            <div class="col-lg-8">
                            <input type="text" name="f" placeholder="" data-required="true" class="form-control" value="<?php echo $e[acara]; ?>">
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Rapat_Ke</label>
                            <div class="col-lg-8">
                            <input type="text" name="g" placeholder="" data-required="true" class="form-control" value="<?php echo $e[agenda]; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Masa_Sidang</label>
                            <div class="col-lg-4">
                            <input type="text" name="h" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo $e[ketua]; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tahun_Sidang</label>
                            <div class="col-lg-4">
                            <input type="text" name="i" placeholder="" data-required="true" class="bg-focus form-control" value="<?php echo $e[sekretaris]; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Sifat_Rapat</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='j' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo $e[jalan_rapat]; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Agenda</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='k' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo $e[hasil_rapat]; ?></textarea>
                            </div>
                        </div>
						
						  <div class="form-group">
                            <label class="col-lg-2 control-label">Pimpinan_Rapat</label>
                            <div class="col-lg-8">
                            <textarea placeholder="" name='l' rows="9" class="textarea form-control" data-trigger="keyup" ><?php echo $e[kesimpulan]; ?></textarea>
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
    $in = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from notulen where id_notulen='$_GET[id]'"));
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
                            $in[no_notulen]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Hari</label>
                            <div class='col-lg-4'>
                            $in[hari]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tanggal</label>
                            <div class='col-lg-8'>
                            ".tgl_indo($in[tanggal])."
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Jam</label>
                            <div class='col-lg-2'>
                            $in[jam]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tempat</label>
                            <div class='col-lg-8'>
                             $in[tempat]
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Jenis_Rapat</label>
                            <div class='col-lg-8'>
                             $in[jenis_rapat]
                            </div>
                        </div>
                
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Rapat_Ke</label>
                            <div class='col-lg-8'>
                             $in[rapat_ke]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Masa Sidang</label>
                            <div class='col-lg-4'>
                            $in[masa_sidang]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Tahun_Sidang</label>
                            <div class='col-lg-4'>
                            $in[tahun_sidang]
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Sifat_Rapat</label>
                            <div class='col-lg-8'>
                            $in[sifat_rapat]
                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <label class='col-lg-2 control-label'>Agenda</label>
                            <div class='col-lg-8'>
                            $in[agenda]
                            </div>
                        </div>
						
						<div class='form-group'>
                            <label class='col-lg-2 control-label'>Pimpinan_Rapat</label>
                            <div class='col-lg-8'>
                            $in[pimpinan_rapat]
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
                    <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy Aplikasi JDIH DPRD</font></p></small></p> 
                </div> 
            </footer> 