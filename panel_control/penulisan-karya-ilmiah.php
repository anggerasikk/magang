<?php 
session_start();
include "a_db.php";

// CEK LOGIN DAN LEVEL
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// ================================================
// HALAMAN UTAMA (LIST DATA PENULISAN KARYA ILMIAH)
// ================================================
if ($aksi == ''){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Penulisan Karya Ilmiah - Admin JDIH</title>
    <link rel="stylesheet" href="style-admin.css">
    <style>
        .btn-xs { padding: 3px 8px; font-size: 12px; }
        .badge { padding: 5px 10px; }
        .badge-success { background: #28a745; }
        .badge-danger { background: #dc3545; }
        .badge-warning { background: #ffc107; }
        .badge-primary { background: #007bff; }
        .badge-info { background: #17a2b8; }
        .img-preview { width: 50px; height: 50px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h4 style='padding-top:15px; text-align:center;'>
            <font face="Courier New" color="#000000" size="4">
                DATA PENULISAN KARYA ILMIAH
            </font>
        </h4>
        
        <!-- Basic Data Tables Example -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                        <!-- LINK KE HALAMAN TAMBAH MONOGRAFI YANG SUDAH ADA -->
                        <a href='http://localhost/jdihcilacap_pasang_2026/panel_control/index.php?page=monografi&aksi=tambah' class='btn btn-primary'>
                            <i class='fa fa-plus'></i> Tambah Penulisan Karya Ilmiah
                        </a>
                    <?php endif; ?>
                    <div class="pull-right">
                        <span class="badge badge-info">Total: 
                            <?php 
                            $count_query = "SELECT COUNT(*) as total FROM monografi WHERE jenis_monografi = 'Penulisan Karya Ilmiah'";
                            $count_result = mysqli_query($koneksi, $count_query);
                            $count_data = mysqli_fetch_assoc($count_result);
                            echo $count_data['total'];
                            ?>
                        </span>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">NO</th>
                                    <th>JUDUL KARYA ILMIAH</th>
                                    <th width="120">TAHUN</th>
                                    <th width="150">PENULIS</th>
                                    <th width="100">STATUS</th>
                                    <th width="150" class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            // Query data berdasarkan level user
                            if ($_SESSION['level'] == 'user_admin'){
                                $query = "SELECT * FROM monografi WHERE jenis_monografi = 'Penulisan Karya Ilmiah' ORDER BY id_monografi DESC";
                            } else {
                                $user_id = mysqli_real_escape_string($koneksi, $_SESSION['login']);
                                $query = "SELECT * FROM monografi WHERE id_user = '$user_id' AND jenis_monografi = 'Penulisan Karya Ilmiah' ORDER BY id_monografi DESC";
                            }
                            
                            $result = mysqli_query($koneksi, $query);
                            
                            if(!$result) {
                                die("Query error: " . mysqli_error($koneksi));
                            }
                            
                            if(mysqli_num_rows($result) > 0) {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($result)){
                                    // Status badge warna
                                    $status_badge = "";
                                    if ($data['status'] == 'Berlaku') {
                                        $status_badge = "<span class='badge badge-success'>{$data['status']}</span>";
                                    } elseif ($data['status'] == 'Tidak Berlaku') {
                                        $status_badge = "<span class='badge badge-danger'>{$data['status']}</span>";
                                    } else {
                                        $status_badge = "<span class='badge badge-warning'>{$data['status']}</span>";
                                    }
                                    
                                    echo "<tr>
                                            <td class='text-center'>$no</td>
                                            <td>{$data['judul']}</td>
                                            <td>{$data['tahun_terbit']}</td>                            
                                            <td>{$data['penulis']}</td>
                                            <td>$status_badge</td>
                                            <td class='text-center'>";
                                    
                                    // Tombol Lihat Detail
                                    echo "<a class='btn btn-info btn-xs' href='?page=penulisan-karya-ilmiah&aksi=detail&id={$data['id_monografi']}' title='Detail'>
                                            <i class='fa fa-eye'></i>
                                          </a>";
                                    
                                    // Tombol Edit - LINK KE HALAMAN EDIT YANG SUDAH ADA
                                    if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'){ 
                                        echo "<a class='btn btn-warning btn-xs' href='http://localhost/jdihcilacap_pasang_2026/panel_control/index.php?page=monografi&aksi=edit&id={$data['id_monografi']}' title='Edit'>
                                                <i class='fa fa-edit'></i>
                                              </a>";
                                    }
                                    
                                    // Tombol Hapus hanya untuk admin
                                    if ($_SESSION['level'] == 'user_admin'){ 
                                        echo "<a class='btn btn-danger btn-xs' href='?page=penulisan-karya-ilmiah&aksi=hapus&id={$data['id_monografi']}' 
                                              title='Hapus' onclick='return confirm(\"Yakin hapus data ini?\");'>
                                                <i class='fa fa-trash'></i>
                                              </a>";
                                    }
                                    
                                    // Tombol Download PDF jika ada
                                    if (!empty($data['file_pdf'])): ?>
                                        <a href="<?php echo $data['file_pdf']; ?>" target="_blank" 
                                           class="btn btn-success btn-xs" title="Download PDF">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    <?php endif;
                                    
                                    echo "</td></tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Belum ada data Penulisan Karya Ilmiah</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php 
// ================================================
// AKSI HAPUS DATA PENULISAN KARYA ILMIAH
// ================================================
} elseif ($aksi == 'hapus') { 
    // VALIDASI: Cek apakah user adalah admin
    if ($_SESSION['level'] != 'user_admin') {
        echo "<script>
                alert('Anda tidak memiliki akses untuk menghapus!');
                window.location='?page=penulisan-karya-ilmiah'
              </script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data untuk hapus file fisik
    $query = "SELECT file_pdf, thumbnail FROM monografi WHERE id_monografi='$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Hapus file PDF jika ada
    if (!empty($data['file_pdf']) && file_exists($data['file_pdf'])) {
        unlink($data['file_pdf']);
    }
    
    // Hapus thumbnail jika ada
    if (!empty($data['thumbnail']) && file_exists($data['thumbnail'])) {
        unlink($data['thumbnail']);
    }
    
    // Hapus data dari database
    $delete_query = "DELETE FROM monografi WHERE id_monografi='$id'";
    
    if(mysqli_query($koneksi, $delete_query)) {
        echo "<script>
                alert('Data Penulisan Karya Ilmiah berhasil dihapus!');
                window.location='?page=penulisan-karya-ilmiah'
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($koneksi) . "');
                window.location='?page=penulisan-karya-ilmiah'
              </script>";
    }

// ================================================
// AKSI EDIT DATA PENULISAN KARYA ILMIAH - REDIRECT KE HALAMAN YANG SUDAH ADA
// ================================================
} elseif ($aksi == 'edit' && isset($_GET['id'])) { 
    // REDIRECT KE HALAMAN EDIT YANG SUDAH ADA DI MONOGRAFI.PHP
    $id = $_Get['id'];
    header("Location: http://localhost/jdihcilacap_pasang_2026/panel_control/index.php?page=monografi&aksi=edit&id=$id");
    exit();

// ================================================
// AKSI DETAIL DATA PENULISAN KARYA ILMIAH
// ================================================
} elseif ($aksi == 'detail' && isset($_GET['id'])) {  
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM monografi WHERE id_monografi='$id' AND jenis_monografi = 'Penulisan Karya Ilmiah'";
    $result = mysqli_query($koneksi, $query);
    
    if(!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location='?page=penulisan-karya-ilmiah'
              </script>";
        exit();
    }
    
    $data = mysqli_fetch_assoc($result);
?>
<div class="container-fluid">
    <h4 style='padding-top:15px; text-align:center;'>
        <font face="Courier New" color="#000000" size="4">
            DETAIL PENULISAN KARYA ILMIAH
        </font>
    </h4>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>DETAIL DATA PENULISAN KARYA ILMIAH</strong>
                    <a href="?page=penulisan-karya-ilmiah" class="btn btn-default btn-xs pull-right">
                        <i class="fa fa-arrow-left"></i> KEMBALI
                    </a>
                </div>
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3><?php echo htmlspecialchars($data['judul']); ?></h3>
                            <hr>
                            
                            <table class="table table-bordered">
                                <tr><th width="30%">Jenis Dokumen</th><td>Penulisan Karya Ilmiah</td></tr>
                                <tr><th>Tanggal Pembuatan</th><td><?php echo date('d-m-Y', strtotime($data['tanggal_pembuatan'])); ?></td></tr>
                                <tr><th>Tahun Terbit</th><td><?php echo $data['tahun_terbit']; ?></td></tr>
                                <tr><th>Penulis</th><td><?php echo $data['penulis']; ?></td></tr>
                                <tr><th>Penerbit</th><td><?php echo $data['penerbit']; ?></td></tr>
                                <tr><th>ISBN</th><td><?php echo $data['isbn']; ?></td></tr>
                                <tr><th>Bidang Hukum</th><td><?php echo $data['bidang_hukum']; ?></td></tr>
                                <tr><th>Jumlah Halaman</th><td><?php echo $data['jumlah_halaman']; ?></td></tr>
                                <tr><th>Status</th>
                                    <td>
                                        <?php
                                        $badge_class = 'badge-warning';
                                        if ($data['status'] == 'Berlaku') $badge_class = 'badge-success';
                                        if ($data['status'] == 'Tidak Berlaku') $badge_class = 'badge-danger';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>">
                                            <?php echo $data['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr><th>Bahasa</th><td><?php echo $data['bahasa']; ?></td></tr>
                                <tr><th>Dibuat Pada</th><td><?php echo date('d-m-Y H:i', strtotime($data['created_at'])); ?></td></tr>
                                <?php if ($data['updated_at'] != '0000-00-00 00:00:00' && !empty($data['updated_at'])): ?>
                                <tr><th>Diupdate Pada</th><td><?php echo date('d-m-Y H:i', strtotime($data['updated_at'])); ?></td></tr>
                                <?php endif; ?>
                            </table>
                            
                            <h4>Deskripsi</h4>
                            <div class="well">
                                <?php echo nl2br(htmlspecialchars($data['deskripsi'])); ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <?php if (!empty($data['thumbnail'])): ?>
                                <div class="text-center">
                                    <img src="<?php echo $data['thumbnail']; ?>" class="img-thumbnail" style="max-width: 100%;">
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data['file_pdf'])): ?>
                                <div class="well mt-3">
                                    <h4>File PDF</h4>
                                    <a href="<?php echo $data['file_pdf']; ?>" target="_blank" class="btn btn-success btn-block">
                                        <i class="fa fa-download"></i> Download PDF
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="well mt-3">
                                <h4>Aksi</h4>
                                <?php if ($_SESSION['level'] == 'user_admin' || $_SESSION['level'] == 'user_input'): ?>
                                    <!-- LINK KE HALAMAN EDIT YANG SUDAH ADA -->
                                    <a href="http://localhost/jdihcilacap_pasang_2026/panel_control/index.php?page=monografi&aksi=edit&id=<?php echo $data['id_monografi']; ?>" 
                                       class="btn btn-warning btn-block">
                                        <i class="fa fa-edit"></i> Edit Data
                                    </a>
                                <?php endif; ?>
                                <?php if ($_SESSION['level'] == 'user_admin'): ?>
                                    <a href="?page=penulisan-karya-ilmiah&aksi=hapus&id=<?php echo $data['id_monografi']; ?>" 
                                       class="btn btn-danger btn-block"
                                       onclick="return confirm('Yakin hapus data ini?')">
                                        <i class="fa fa-trash"></i> Hapus Data
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 

// ================================================
// AKSI TAMBAH DATA - REDIRECT KE HALAMAN YANG SUDAH ADA
// ================================================
} elseif ($aksi == 'tambah') {
    // REDIRECT KE HALAMAN TAMBAH YANG SUDAH ADA DI MONOGRAFI.PHP
    header("Location: http://localhost/jdihcilacap_pasang_2026/panel_control/index.php?page=monografi&aksi=tambah");
    exit();
}
?>

<footer id="footer"> 
    <div class="text-center clearfix">
        <p>
            <small>
                <font face="Courier New" color="#F0F8FF" size="4">
                    &copy; Aplikasi JDIH DPRD
                </font>
            </small>
        </p> 
    </div> 
</footer>