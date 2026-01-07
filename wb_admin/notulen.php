<?php 
session_start();
include "a_db.php";

// CEK LOGIN
if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$jenis_filter = isset($_GET['jenis']) ? $_GET['jenis'] : '';

// Fungsi untuk menghitung jumlah data per jenis
function hitungJenis($koneksi, $jenis) {
    $query = "SELECT COUNT(*) as total FROM informasi WHERE jenis = '$jenis'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Fungsi konversi
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

// Data jenis produk hukum
$jenis_produk = [
    'Keputusan_DPRD' => [
        'nama' => 'Keputusan DPRD',
        'icon' => 'images/keputusan_dprd.png',
        'color' => '#3498db',
        'desc' => 'Data keputusan yang dikeluarkan oleh DPRD'
    ],
    'Keputusan_Sekretaris_DPRD' => [
        'nama' => 'Keputusan_Sekretaris',
        'icon' => 'images/keputusan_sekretaris.png',
        'color' => '#2ecc71',
        'desc' => 'Data keputusan dari Sekretaris DPRD'
    ],
    'Keputusan_Pimpinan_DPRD' => [
        'nama' => 'Keputusan Pimpinan DPRD',
        'icon' => 'images/keputusan_pimpinan.png',
        'color' => '#e74c3c',
        'desc' => 'Data keputusan dari Pimpinan DPRD'
    ],
    'Peraturan_DPRD' => [
        'nama' => 'Peraturan DPRD',
        'icon' => 'images/peraturan_dprd.png',
        'color' => '#f39c12',
        'desc' => 'Data peraturan yang dikeluarkan DPRD'
    ]
];

if ($aksi == '' && $jenis_filter == ''){
?>
<style>
.bs-glyphicons {
  margin: 13px 20px 20px 0px;
  overflow: hidden;
}
.bs-glyphicons-list {
  padding-left: 0;
  list-style: none;
}

.bs-glyphicons-list a {
  color:#8a8a8a !important;
  text-decoration: none;
}
ol, ul {
  margin-top: 0;
  margin-bottom: 10px;
}
.bs-glyphicons li {
  float: left;
  width: 23%;
  height: 280px;
  margin: 1%;
  padding: 20px;
  font-size: 10px;
  line-height: 1.4;
  text-align: center;
  border: 1px solid #cecece;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}
.bs-glyphicons li:hover {
  background-color: #f8f9fa;
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.bs-glyphicons .glyphicon {
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 74px;
}
.glyphicon {
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.bs-glyphicons .glyphicon-class {
  display: block;
  text-align: center;
  word-wrap: break-word;
  font-size: 16px;
  font-weight: bold;
  margin-top: 10px;
  margin-bottom: 10px;
}

.jumlah-badge {
  background-color: #e74c3c;
  color: white;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  line-height: 40px;
  display: inline-block;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
}

.deskripsi-jenis {
  font-size: 14px;
  color: #666;
  margin-top: 10px;
  min-height: 60px;
}

.container-title {
  background-color: #2c3e50;
  color: white;
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .bs-glyphicons li {
    width: 48%;
    margin: 1%;
  }
}

@media (max-width: 480px) {
  .bs-glyphicons li {
    width: 98%;
    margin: 1%;
  }
}
</style>

<div class="col-md-12">
    <div class="container-title">
        <h3><i class="fa fa-balance-scale"></i> Pilih Jenis Produk Hukum</h3>
        <p>Silakan pilih jenis produk hukum yang ingin Anda kelola</p>
    </div>

    <div class="bs-docs-section">
        <div class="bs-glyphicons">
            <ul class="bs-glyphicons-list">
                <?php foreach($jenis_produk as $kode => $jenis): 
                    $jumlah = hitungJenis($koneksi, $kode);
                ?>
                <a href='index.php?page=notulen&jenis=<?php echo $kode; ?>'>
                    <li>
                        <div class="jumlah-badge"><?php echo $jumlah; ?></div>
                        <img src="<?php echo $jenis['icon']; ?>" width="100" height="100" alt="<?php echo $jenis['nama']; ?>" style="border-radius: 10px;">
                        <span class="glyphicon-class"><?php echo $jenis['nama']; ?></span>
                        <div class="deskripsi-jenis">
                            <?php echo $jenis['desc']; ?>
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="badge" style="background-color: <?php echo $jenis['color']; ?>; padding: 5px 10px;">
                                <i class="fa fa-folder-open"></i> Lihat Data
                            </span>
                        </div>
                    </li>
                </a>
                <?php endforeach; ?>
                
                <?php if($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                <a href='index.php?page=informasi&aksi=tambah'>
                    <li style="background-color: #f8f9fa; border: 2px dashed #007bff;">
                        <div style="margin-top: 40px;">
                            <i class="fa fa-plus-circle" style="font-size: 80px; color: #007bff;"></i>
                        </div>
                        <span class="glyphicon-class">Tambah Data Baru</span>
                        <div class="deskripsi-jenis">
                            Tambah produk hukum baru ke sistem
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="badge badge-primary" style="padding: 5px 10px;">
                                <i class="fa fa-plus"></i> Tambah
                            </span>
                        </div>
                    </li>
                </a>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Statistik Ringkas -->
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-chart-bar"></i> Statistik Produk Hukum</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php 
                        $total_berlaku = mysqli_fetch_assoc(mysqli_query($koneksi, 
                            "SELECT COUNT(*) as total FROM informasi WHERE status = 'Berlaku'"))['total'];
                        $total_tidak_berlaku = mysqli_fetch_assoc(mysqli_query($koneksi, 
                            "SELECT COUNT(*) as total FROM informasi WHERE status = 'Tidak Berlaku'"))['total'];
                        $total_semua = mysqli_fetch_assoc(mysqli_query($koneksi, 
                            "SELECT COUNT(*) as total FROM informasi"))['total'];
                        ?>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="alert alert-success">
                                <h4><?php echo $total_berlaku; ?></h4>
                                <p><i class="fa fa-check-circle"></i> Produk Berlaku</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="alert alert-danger">
                                <h4><?php echo $total_tidak_berlaku; ?></h4>
                                <p><i class="fa fa-times-circle"></i> Tidak Berlaku</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="alert alert-warning">
                                <h4><?php echo $total_semua - ($total_berlaku + $total_tidak_berlaku); ?></h4>
                                <p><i class="fa fa-clock"></i> Status Lainnya</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="alert alert-info">
                                <h4><?php echo $total_semua; ?></h4>
                                <p><i class="fa fa-database"></i> Total Semua</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// TAMPILKAN DATA BERDASARKAN JENIS YANG DIPILIH
} elseif ($jenis_filter != '' && array_key_exists($jenis_filter, $jenis_produk)) {
?>
<h4 style='padding-top:15px'><p align="center">
    <font face="Courier New" color="#00FF00" size="4">
        Data <?php echo $jenis_produk[$jenis_filter]['nama']; ?>
    </font>
</p></h4>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        <i class="fa fa-filter"></i> Menampilkan: <?php echo $jenis_produk[$jenis_filter]['nama']; ?>
                    </h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="index.php?page=notulen" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali ke Pilihan
                    </a>
                    <?php if($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                    <a href='index.php?page=informasi&aksi=tambah' class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <?php 
            $query = "SELECT * FROM informasi WHERE jenis = '$jenis_filter' ORDER BY tanggal_tayang DESC";
            $result = mysqli_query($koneksi, $query);
            $count = mysqli_num_rows($result);
            
            if($count > 0):
            ?>
            <table class="table table-striped table-bordered table-hover">
                <thead class='alert-info'>
                    <tr>
                        <th>No</th>               
                        <th>Tanggal Tayang</th>
                        <th>Judul</th>
                        <th>Sumber</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1;
                while ($data = mysqli_fetch_array($result)){
                    echo "<tr>
                            <td>$no</td>
                            <td>" . konversiTanggal($data['tanggal_tayang']) . "</td>
                            <td>" . htmlspecialchars($data['judul']) . "</td>
                            <td>" . htmlspecialchars($data['sumber']) . "</td>
                            <td>" . formatStatus($data['status']) . "</td>
                            <td>";
                    
                    // Tampilkan dokumen jika ada
                    if(!empty($data['image_name'])) {
                        $file_ext = pathinfo($data['image_name'], PATHINFO_EXTENSION);
                        $icon = ($file_ext == 'pdf') ? 'fa-file-pdf-o' : 'fa-file-text-o';
                        echo "<a href='" . htmlspecialchars($data['image_path']) . "' target='_blank' class='btn btn-xs btn-info'>
                                <i class='fa $icon'></i> Dokumen
                              </a>";
                    } else {
                        echo "-";
                    }
                    
                    echo "</td>
                            <td style='width:130px'>
                                <a class='btn btn-xs btn-default' href='index.php?page=informasi&aksi=detail&id=$data[idData]' title='Detail'>
                                    <i class='fa fa-eye'></i>
                                </a>";
                    
                    if ($_SESSION['level']=='user_admin' || $_SESSION['level']=='user_input'){ 
                        echo "<a class='btn btn-xs btn-warning' href='index.php?page=informasi&aksi=edit&id=$data[idData]' title='Edit'>
                                <i class='fa fa-pencil'></i>
                              </a>";
                    }
                    
                    if ($_SESSION['level']=='user_admin'){ 
                        echo "<a class='btn btn-xs btn-danger' href='index.php?page=informasi&aksi=hapus&id=$data[idData]' title='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                                <i class='fa fa-trash'></i>
                              </a>";
                    }
                    
                    echo "</td></tr>";
                    $no++;
                }
                ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-warning">
                <h4><i class="fa fa-info-circle"></i> Tidak ada data</h4>
                <p>Belum ada data untuk jenis produk hukum ini.</p>
                <a href="index.php?page=informasi&aksi=tambah" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tambah Data Baru
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-6">
                    <p>Total Data: <strong><?php echo $count; ?></strong> dokumen</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-download"></i> Ekspor <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
                            <li><a href="#"><i class="fa fa-file-excel-o"></i> Excel</a></li>
                            <li><a href="#"><i class="fa fa-file-word-o"></i> Word</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
} else {
    echo "<div class='alert alert-danger'>
            <h4><i class='fa fa-exclamation-triangle'></i> Error</h4>
            <p>Jenis produk hukum tidak ditemukan.</p>
            <a href='index.php?page=notulen' class='btn btn-default'>Kembali ke Pilihan</a>
          </div>";
}
?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p><small>&copy; Sistem Informasi Produk Hukum DPRD</small></p> 
    </div> 
</footer>

<?php 
// Tutup koneksi
mysqli_close($koneksi);
?>