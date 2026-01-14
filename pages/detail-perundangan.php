<?php
// pages/detail-perundangan.php
session_start();
include "header.php";
include_once "a_db.php";

// Parameter
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : null;

// Query data detail
if ($id) {
    $query = "SELECT * FROM uploads WHERE idData = '$id' AND display = 'yes'";
    $result = mysqli_query($koneksi, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        // Tentukan jenis untuk back link
        $jenis_mapping = [
            'Keputusan_DPRD' => 'keputusan-dprd',
            'Keputusan_Sekretaris' => 'keputusan-sekretariat',
            'Keputusan_Pimpinan' => 'keputusan-pimpinan',
            'Peraturan_DPRD' => 'peraturan-dprd',
            'Perda' => 'perda',
            'English_Legal' => 'english-legal'
        ];
        
        $jenis_key = isset($jenis_mapping[$data['jenis']]) ? $jenis_mapping[$data['jenis']] : 'perundang-undangan';
        
        // Format tanggal
        $tanggal_penetapan = date('d F Y', strtotime($data['tanggal_penetapan']));
        $tanggal_pengundangan = date('d F Y', strtotime($data['tanggal_pengundangan']));
    } else {
        header("Location: index.php?page=perundang-undangan");
        exit();
    }
} else {
    header("Location: index.php?page=perundang-undangan");
    exit();
}
?>
<!-- HEADER DETAIL -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white"><?php echo htmlspecialchars($data['judul']); ?></h1>
                <p class="subtitle"><?php echo htmlspecialchars($data['noPeraturan']); ?></p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=perundang-undangan">Perundang-Undangan</a></li>
                        <li class="breadcrumb-item active">Detail Dokumen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <div class="row">
            
            <!-- DETAIL KONTEN -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="document-container">
                    
                    <!-- INFORMASI UTAMA -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-file-text text-primary"></i> Informasi Dokumen</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nomor:</strong></td>
                                        <td><?php echo htmlspecialchars($data['noPeraturan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis:</strong></td>
                                        <td><?php echo htmlspecialchars($data['jenis']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td><?php echo htmlspecialchars($data['status']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bahasa:</strong></td>
                                        <td><?php echo htmlspecialchars($data['bahasa']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-calendar text-primary"></i> Informasi Waktu</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Tanggal Penetapan:</strong></td>
                                        <td><?php echo $tanggal_penetapan; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pengundangan:</strong></td>
                                        <td><?php echo $tanggal_pengundangan; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tahun:</strong></td>
                                        <td><?php echo htmlspecialchars($data['tahun_pengundangan']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- JUDUL LENGKAP -->
                    <div class="alert alert-primary">
                        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i> Tentang</h5>
                        <hr>
                        <p class="mb-0"><?php echo htmlspecialchars($data['judul']); ?></p>
                    </div>
                    
                    <!-- INFORMASI TAMBAHAN -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6><i class="bi bi-geo-alt text-primary"></i> Tempat Terbit</h6>
                                <p><?php echo htmlspecialchars($data['tempatTerbit']); ?></p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="bi bi-building text-primary"></i> Penerbit</h6>
                                <p><?php echo htmlspecialchars($data['penerbit']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6><i class="bi bi-journal text-primary"></i> Sumber</h6>
                                <p><?php echo htmlspecialchars($data['sumber']); ?></p>
                            </div>
                            <?php if(!empty($data['bidangHukum'])): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-briefcase text-primary"></i> Bidang Hukum</h6>
                                <p><?php echo htmlspecialchars($data['bidangHukum']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- DOWNLOAD -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-pdf display-4 text-primary mb-3"></i>
                            <h4>Download Dokumen</h4>
                            <p class="text-muted mb-4">Unduh dokumen dalam format PDF</p>
                            
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <?php if(!empty($data['pdf_path']) && file_exists($data['pdf_path'])): ?>
                                <a href="<?php echo $data['pdf_path']; ?>" 
                                   download 
                                   class="btn btn-primary btn-lg"
                                   target="_blank">
                                    <i class="bi bi-download me-2"></i>
                                    Download PDF
                                </a>
                                <?php elseif(!empty($data['fileDownload'])): ?>
                                <a href="<?php echo $data['fileDownload']; ?>" 
                                   download 
                                   class="btn btn-primary btn-lg"
                                   target="_blank">
                                    <i class="bi bi-download me-2"></i>
                                    Download Dokumen
                                </a>
                                <?php else: ?>
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    File Tidak Tersedia
                                </button>
                                <?php endif; ?>
                                
                                <a href="index.php?page=perundang-undangan&jenis=<?php echo $jenis_key; ?>" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- DOKUMEN TERKAIT -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-link-45deg me-2"></i> Dokumen Terkait
                        </h5>
                        
                        <?php 
                        // Query dokumen terkait (jenis yang sama)
                        $related_query = "SELECT idData, noPeraturan, judul, tanggal_penetapan 
                                        FROM uploads 
                                        WHERE jenis = '{$data['jenis']}'
                                          AND display = 'yes'
                                          AND idData != '$id'
                                        ORDER BY tanggal_penetapan DESC 
                                        LIMIT 5";
                        $related_result = mysqli_query($koneksi, $related_query);
                        
                        if(mysqli_num_rows($related_result) > 0):
                            while($related = mysqli_fetch_assoc($related_result)):
                                $related_date = date('d M Y', strtotime($related['tanggal_penetapan']));
                        ?>
                        <a href="index.php?page=detail-perundangan&id=<?php echo $related['idData']; ?>" 
                           class="text-decoration-none">
                            <div class="border rounded p-3 mb-2">
                                <div class="fw-bold small"><?php echo htmlspecialchars($related['noPeraturan']); ?></div>
                                <div class="text-muted small"><?php echo $related_date; ?></div>
                            </div>
                        </a>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <p class="text-muted text-center py-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Tidak ada dokumen terkait
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- INFORMASI -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-info-circle me-2"></i> Informasi Dokumen
                        </h5>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Dokumen ini telah dipublikasikan
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-eye text-primary me-2"></i>
                                Dapat diakses oleh publik
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-download text-info me-2"></i>
                                Dapat diunduh gratis
                            </li>
                            <li>
                                <i class="bi bi-shield-check text-warning me-2"></i>
                                Dokumen resmi DPRD Kota Surakarta
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</section>

<?php 
// Tutup koneksi
mysqli_close($koneksi);
include "footer.php";
?>