<?php 
    if ($_GET['aksi']==''){
?>
        <h4 style='padding-top:15px'><p align="justify"><font face="Courier New" color="#00FF00" size="4">Semua Data Produk Hukum</font></p></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
                 <div class="panel-body">
                 <table class="table table-striped table-bordered table-hover dataTables-example" >
                 <tbody>
                 <?php include 'a_db.php'; ?>
                 <div class="container">
                 </div>
                 </tbody>
                 </table>
		   <h4 style='padding-top:15px'></h4>
            <!-- Basic Data Tables Example -->
            <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong>Tambahkan Data Produk Hukum</strong></div>
                <div class="panel-body">
				 <h2>Upload PDF & Gambar</h2>
                 <form action="a_upload.php" class="form-horizontal"  method="post" enctype="multipart/form-data">	

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tahun Pengundangan</label>
                            <div class="col-lg-6">
                            <input type="text" name="tahun_pengundangan" placeholder="Tahun Pengundangan" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>	

                         <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal Pengundangan</label>
                            <div class="col-lg-8">
                            <input type="text" class="combodate form-control" data-format="YYYY-MM-DD" data-template="D  MMM  YYYY" name="tanggal_pengundangan" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal Penetapan</label>
                            <div class="col-lg-8">
                            <input type="text" class="combodate form-control" data-format="YYYY-MM-DD" data-template="D  MMM  YYYY" name="tanggal_penetapan" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
						
						 <div class="form-group">
                        <label class="col-lg-2 control-label">Jenis</label>
                        <div class="col-lg-4">
                          <select name="jenis" class="form-control">
                            <option value=''>- Pilih -</option>
                           <option value='Keputusan_DPRD'>Keputusan_DPRD</option>
                           <option value='Keputusan_Sekretaris_DPRD'>Keputusan_Sekretaris_DPRD</option>
                           <option value='Keputusan_Pimpinan_DPRD'>Keputusan_Pimpinan_DPRD</option>
                           <option value='Peraturan_DPRD'>Peraturan_DPRD</option>
						   <option value='English_Legal_Product'>English_Legal_Product</option>
                           <option value='Peraturan_Daerah'>Peraturan_Daerah</option>
                          </select>
                        </div>
                        </div>

						 <div class="form-group">
                            <label class="col-lg-2 control-label">Nomor peraturan</label>
                            <div class="col-lg-6">
                            <input type="text" name="noPeraturan" placeholder="Nomor peraturan" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>	
						
						 <div class="form-group">
                            <label class="col-lg-2 control-label">Judul</label>
                            <div class="col-lg-8">
                            <textarea placeholder="Judul" name='judul' rows="5" class="form-control" data-trigger="keyup"></textarea>
                            </div>
                        </div>	
						
						 <div class="form-group">
                            <label class="col-lg-2 control-label">Singkatan jenis</label>
                            <div class="col-lg-6">
                            <input type="text" name="singkatanJenis" placeholder="Singkatan Jenis" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-lg-2 control-label">Tempat Terbit</label>
                            <div class="col-lg-6">
                            <input type="text" name="tempatTerbit" placeholder="Tempat Terbit" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Sumber</label>
                            <div class="col-lg-6">
                            <input type="text" name="sumber" placeholder="Sumber" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                        <label class="col-lg-2 control-label">Status</label>
                        <div class="col-lg-4">
                          <select name="status" class="form-control">
                            <option value=''>- Pilih -</option>
                           <option value='Berlaku'>Berlaku</option>
                           <option value='tidak_Berlaku'>Tidak Berlaku</option>
                           <option value='Mencabut'>Mencabut</option>
                          </select>
                        </div>
                        </div>
						
						<div class="form-group">
                        <label class="col-lg-2 control-label">Bahasa</label>
                        <div class="col-lg-4">
                          <select name="bahasa" class="form-control">
                            <option value=''>- Pilih -</option>
                           <option value='Indonesia'>Indonesia</option>
                           <option value='Inggris'>Inggris</option>
                           <option value='Lokal'>lokal</option>
                          </select>
                        </div>
                        </div>
						
						
			            <div class="form-group">
                            <label class="col-lg-2 control-label">Pilih PDF: ( Maksimal 2 MB )</label><br>
							<div class="col-lg-8">
                            <input type="file" name="pdf" accept="application/pdf" required><br><br>
							</div>
		                </div>
			  
			            <div class="form-group">
                            <label class="col-lg-2 control-label">Operasi</label>
                            <div class="col-lg-6">
                            <input type="text" name="operasi" placeholder="Operasi" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Display</label>
                            <div class="col-lg-6">
                            <input type="text" name="display" placeholder="Display" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Nomor Panggil</label>
                            <div class="col-lg-6">
                            <input type="text" name="noPanggil" placeholder="Nomor Panggil" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Penerbit</label>
                            <div class="col-lg-6">
                            <input type="text" name="penerbit" placeholder="Penerbit" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Subjek</label>
                            <div class="col-lg-6">
                            <input type="text" name="subjek" placeholder="Subjek" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">ISBN</label>
                            <div class="col-lg-6">
                            <input type="text" name="isbn" placeholder="isbn" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Bidang Hukum</label>
                            <div class="col-lg-6">
                            <input type="text" name="bidangHukum" placeholder="Bidang Hukum" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Nomor Induk Buku</label>
                            <div class="col-lg-6">
                            <input type="text" name="nomorIndukBuku" placeholder="Nomor Induk Buku" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Teu badan</label>
                            <div class="col-lg-6">
                            <input type="text" name="teuBadan" placeholder="Teu Badan" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Deskripsi Fisik</label>
                            <div class="col-lg-6">
                            <input type="text" name="deskripsiFisik" placeholder="Deskripsi Fisik" data-required="true" class="bg-focus form-control">
                            </div>
                        </div>
			  
			            <div class="form-group">
                             <label class="col-lg-2 control-label">Pilih Gambar (JPG/PNG): ( icon buku )</label><br>
							 <div class="col-lg-8">
                            <input type="file" name="image" accept="image/*" required><br><br>
							</div>
			            </div>
                  
				   
				        <div class="form-group">
                            <div class="col-lg-9 pull-right">    
                            <button type="submit"  class="btn btn-info">Upload</button>                  
                            <button type="reset" class="btn btn-default">Reset</button>

                            </div>
                        </div>
                </form>           
                </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            <!-- /Basic Data Tables Example --> 

<?php 
}
?>
             <footer id="footer"> 
                <div class="text-center clearfix">
                    <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy Aplikasi JDIH DPRD</font></p></small></p> 
                </div> 
            </footer> 