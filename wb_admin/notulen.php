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
    // Sesuaikan query dengan tabel yang sesuai untuk masing-masing halaman
    $query = "SELECT COUNT(*) as total FROM informasi WHERE jenis = '$jenis'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Data jenis produk hukum - HANYA DUA JENIS
$jenis_produk = [
    'kepdprd' => [
        'nama' => 'Keputusan DPRD',
        'icon' => 'images/keputusan_dprd.png',
        'color' => '#3498db',
        'desc' => 'Data keputusan yang dikeluarkan oleh DPRD',
        'link' => 'index.php?page=kepdprd'
    ],
    'kepsekdprd' => [
        'nama' => 'Keputusan Sekretaris DPRD',
        'icon' => 'images/keputusan_sekretaris.png',
        'color' => '#2ecc71',
        'desc' => 'Data keputusan dari Sekretaris DPRD',
        'link' => 'index.php?page=kepsekdprd'
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
  width: 45%; /* Diperbesar karena hanya 2 item */
  height: 280px;
  margin: 2.5%;
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
                    // Sesuaikan parameter untuk hitungJenis berdasarkan halaman tujuan
                    $jenis_param = ($kode == 'kepdprd') ? 'Keputusan_DPRD' : 'Keputusan_Sekretaris_DPRD';
                    $jumlah = hitungJenis($koneksi, $jenis_param);
                ?>
                <a href='<?php echo $jenis['link']; ?>'>
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
            </ul>
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
                    <p><small><p align="center"><font face="Courier New" color="#F0F8FF" size="4">&copy Aplikasi JDIH DPRD</font></p></small></p> 
                </div> 
</footer> 
<?php 
// Tutup koneksi
mysqli_close($koneksi);
?>