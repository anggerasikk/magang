<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// koneksi database - MySQLi
require_once 'a_db.php';

// Debug: cek koneksi
if (!$koneksi) {
    die("Error: Koneksi database tidak ditemukan!");
}

// Fungsi untuk membuat slug
function createSlug($string) {
    $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($string));
    $slug = trim($slug, '-');
    return $slug;
}

// Fungsi untuk upload file
function uploadFile($file, $type = 'sampul') {
    $targetDir = "uploads/buku/";
    
    // Buat direktori jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Generate nama file unik
    $fileName = time() . '_' . $type . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validasi file
    if ($type === 'sampul') {
        // Validasi gambar
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return ['success' => false, 'message' => 'File sampul bukan gambar'];
        }
        
        // Batasi ukuran file (max 2MB untuk sampul)
        if ($file["size"] > 2000000) {
            return ['success' => false, 'message' => 'Ukuran file sampul maksimal 2MB'];
        }
        
        // Format yang diperbolehkan
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Hanya format JPG, JPEG, PNG, GIF yang diperbolehkan'];
        }
    } else {
        // Untuk file buku (PDF, DOC, dll)
        // Batasi ukuran file (max 10MB untuk file buku)
        if ($file["size"] > 10000000) {
            return ['success' => false, 'message' => 'Ukuran file buku maksimal 10MB'];
        }
        
        // Format yang diperbolehkan
        $allowedTypes = ['pdf', 'doc', 'docx', 'epub', 'txt'];
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Hanya format PDF, DOC, DOCX, EPUB, TXT yang diperbolehkan'];
        }
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ['success' => true, 'path' => $targetFile, 'type' => $fileType];
    } else {
        return ['success' => false, 'message' => 'Gagal upload file'];
    }
}

// Action CRUD
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tambah/Edit Buku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $koneksi->real_escape_string($_POST['judul'] ?? '');
    $slug = createSlug($judul);
    $pengarang = $koneksi->real_escape_string($_POST['pengarang'] ?? '');
    $penerbit = $koneksi->real_escape_string($_POST['penerbit'] ?? '');
    $tahun_terbit = $koneksi->real_escape_string($_POST['tahun_terbit'] ?? '');
    $kota_terbit = $koneksi->real_escape_string($_POST['kota_terbit'] ?? '');
    $deskripsi_fisik = $koneksi->real_escape_string($_POST['deskripsi_fisik'] ?? '');
    $isbn = $koneksi->real_escape_string($_POST['isbn'] ?? '');
    $subjek_topik = $koneksi->real_escape_string($_POST['subjek_topik'] ?? '');
    $bidang_hukum = $koneksi->real_escape_string($_POST['bidang_hukum'] ?? '');
    $bahasa = $koneksi->real_escape_string($_POST['bahasa'] ?? 'Indonesia');
    $tipe_dokumen = $koneksi->real_escape_string($_POST['tipe_dokumen'] ?? '');
    $nomor_panggil = $koneksi->real_escape_string($_POST['nomor_panggil'] ?? '');
    $nomor_induk = $koneksi->real_escape_string($_POST['nomor_induk'] ?? '');
    $lokasi_rak = $koneksi->real_escape_string($_POST['lokasi_rak'] ?? '');
    $sinopsis = $koneksi->real_escape_string($_POST['sinopsis'] ?? '');
    $status_ketersediaan = $koneksi->real_escape_string($_POST['status_ketersediaan'] ?? 'tersedia');
    $jumlah_eksemplar = intval($_POST['jumlah_eksemplar'] ?? 1);
    $jumlah_tersedia = intval($_POST['jumlah_tersedia'] ?? 1);
    $tanggal_masuk = $koneksi->real_escape_string($_POST['tanggal_masuk'] ?? '');
    
    // ID user dari session
    $id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
    
    if (($_POST['action'] ?? '') === 'tambah') {
        // Handle upload sampul
        $sampul_path = null;
        if (isset($_FILES['sampul']) && $_FILES['sampul']['error'] === 0) {
            $upload = uploadFile($_FILES['sampul'], 'sampul');
            if ($upload['success']) {
                $sampul_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Handle upload file buku
        $file_buku_path = null;
        if (isset($_FILES['file_buku']) && $_FILES['file_buku']['error'] === 0) {
            $upload = uploadFile($_FILES['file_buku'], 'file_buku');
            if ($upload['success']) {
                $file_buku_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Insert buku baru
        $sql = "INSERT INTO buku_perpustakaan 
            (judul, slug, pengarang, penerbit, tahun_terbit, kota_terbit, deskripsi_fisik, 
             isbn, subjek_topik, bidang_hukum, bahasa, tipe_dokumen, nomor_panggil, 
             nomor_induk, lokasi_rak, sinopsis, sampul_path, file_buku_path, 
             status_ketersediaan, jumlah_eksemplar, jumlah_tersedia, tanggal_masuk, id_user) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssissssssssssssssiisi", 
            $judul, $slug, $pengarang, $penerbit, $tahun_terbit, $kota_terbit, 
            $deskripsi_fisik, $isbn, $subjek_topik, $bidang_hukum, $bahasa, 
            $tipe_dokumen, $nomor_panggil, $nomor_induk, $lokasi_rak, $sinopsis, 
            $sampul_path, $file_buku_path, $status_ketersediaan, $jumlah_eksemplar, 
            $jumlah_tersedia, $tanggal_masuk, $id_user
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Buku berhasil ditambahkan'); window.location.href='index.php?page=buku';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan buku. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
        
    } elseif (($_POST['action'] ?? '') === 'edit' && $id > 0) {
        // Update buku
        $sampul_path = $_POST['sampul_existing'] ?? null;
        $file_buku_path = $_POST['file_buku_existing'] ?? null;
        
        // Jika ada file sampul baru diupload
        if (isset($_FILES['sampul']) && $_FILES['sampul']['error'] === 0) {
            $upload = uploadFile($_FILES['sampul'], 'sampul');
            if ($upload['success']) {
                // Hapus sampul lama jika ada
                if ($sampul_path && file_exists($sampul_path)) {
                    unlink($sampul_path);
                }
                $sampul_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $sampul_path = $koneksi->real_escape_string($sampul_path);
        }
        
        // Jika ada file buku baru diupload
        if (isset($_FILES['file_buku']) && $_FILES['file_buku']['error'] === 0) {
            $upload = uploadFile($_FILES['file_buku'], 'file_buku');
            if ($upload['success']) {
                // Hapus file buku lama jika ada
                if ($file_buku_path && file_exists($file_buku_path)) {
                    unlink($file_buku_path);
                }
                $file_buku_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $file_buku_path = $koneksi->real_escape_string($file_buku_path);
        }
        
        $sql = "UPDATE buku_perpustakaan SET 
            judul = ?, slug = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, 
            kota_terbit = ?, deskripsi_fisik = ?, isbn = ?, subjek_topik = ?, 
            bidang_hukum = ?, bahasa = ?, tipe_dokumen = ?, nomor_panggil = ?, 
            nomor_induk = ?, lokasi_rak = ?, sinopsis = ?, sampul_path = ?, 
            file_buku_path = ?, status_ketersediaan = ?, jumlah_eksemplar = ?, 
            jumlah_tersedia = ?, tanggal_masuk = ?, updated_at = NOW()
            WHERE id_buku = ?";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssissssssssssssssiisi", 
            $judul, $slug, $pengarang, $penerbit, $tahun_terbit, $kota_terbit, 
            $deskripsi_fisik, $isbn, $subjek_topik, $bidang_hukum, $bahasa, 
            $tipe_dokumen, $nomor_panggil, $nomor_induk, $lokasi_rak, $sinopsis, 
            $sampul_path, $file_buku_path, $status_ketersediaan, $jumlah_eksemplar, 
            $jumlah_tersedia, $tanggal_masuk, $id
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Buku berhasil diperbarui'); window.location.href='index.php?page=buku';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui buku. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Hapus Buku
if ($action === 'hapus' && $id > 0) {
    // Ambil path sampul dan file buku untuk dihapus
    $sql = "SELECT sampul_path, file_buku_path FROM buku_perpustakaan WHERE id_buku = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();
    $stmt->close();
    
    // Hapus file sampul jika ada
    if ($buku && $buku['sampul_path'] && file_exists($buku['sampul_path'])) {
        unlink($buku['sampul_path']);
    }
    
    // Hapus file buku jika ada
    if ($buku && $buku['file_buku_path'] && file_exists($buku['file_buku_path'])) {
        unlink($buku['file_buku_path']);
    }
    
    $sql = "DELETE FROM buku_perpustakaan WHERE id_buku = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Buku berhasil dihapus'); window.location.href='index.php?page=buku';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku. Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!-- CSS Tambahan untuk CRUD Buku -->
<style>
/* Form Buku */
.form-buku {
    background: var(--w);
    border-radius: var(--r);
    padding: 25px;
    box-shadow: var(--s);
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--d);
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--b);
    border-radius: var(--r);
    background: var(--bg);
    color: var(--t);
    font-size: 14px;
    transition: 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--c);
    box-shadow: 0 0 0 3px rgba(14, 165, 164, 0.1);
}

textarea.form-control {
    min-height: 150px;
    resize: vertical;
}

select.form-control {
    cursor: pointer;
}

/* Grid untuk form */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    min-width: 90px;
    text-align: center;
    box-sizing: border-box;
    white-space: nowrap;
}

.status-tersedia {
    background: #d1fae5;
    color: #065f46;
}

.status-dipinjam {
    background: #fef3c7;
    color: #92400e;
}

.status-hilang {
    background: #fee2e2;
    color: #991b1b;
}

.status-rusak {
    background: #e5e7eb;
    color: #374151;
}

/* Buku Card */
.buku-card {
    background: var(--w);
    border-radius: var(--r);
    border: 1px solid var(--b);
    padding: 20px;
    margin-bottom: 20px;
    transition: 0.3s;
    position: relative;
    display: flex;
    flex-direction: column;
}

.buku-card:hover {
    box-shadow: var(--s);
    transform: translateY(-2px);
}

.buku-card-content {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    align-items: flex-start;
}

.buku-cover {
    width: 120px;
    height: 160px;
    border-radius: var(--r);
    object-fit: cover;
    border: 2px solid var(--b);
    flex-shrink: 0;
    cursor: pointer;
    transition: 0.3s;
}

.buku-cover:hover {
    transform: scale(1.05);
}

.buku-info {
    flex: 1;
}

.buku-header {
    margin-bottom: 10px;
}

.buku-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--d);
    margin: 0 0 5px 0;
    cursor: pointer;
    transition: 0.3s;
}

.buku-title:hover {
    color: var(--c);
}

.buku-author {
    font-size: 14px;
    color: var(--c);
    font-weight: 500;
    margin-bottom: 5px;
}

.buku-meta {
    font-size: 12px;
    color: #666;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 10px;
}

.buku-sinopsis {
    color: var(--t);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 15px;
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
}

/* Current Images */
.current-files {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.file-container {
    text-align: center;
    max-width: 200px;
}

.file-container p {
    font-size: 11px;
    color: #666;
    margin-top: 5px;
}

/* Button Group */
.btn-group {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: var(--r);
}

/* Action Buttons */
.btn-edit {
    background: var(--c);
    color: white;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-edit:hover {
    background: var(--d);
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-delete:hover {
    background: #dc2626;
    color: white;
}

.btn-detail {
    background: var(--b);
    color: var(--t);
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-detail:hover {
    background: #a7f3d0;
    color: var(--t);
}

.btn-add {
    background: var(--c);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: var(--r);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    transition: 0.3s;
}

.btn-add:hover {
    background: var(--d);
    color: white;
}

/* Preview Gambar */
.preview-image {
    width: 150px;
    height: 200px;
    object-fit: cover;
    border-radius: var(--r);
    margin-top: 10px;
    border: 1px solid var(--b);
}

/* File Icon */
.file-icon {
    width: 100px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg);
    border-radius: var(--r);
    border: 2px dashed var(--b);
    margin-top: 10px;
}

.file-icon i {
    font-size: 40px;
    color: var(--c);
}

/* Tahun Badge */
.tahun-badge {
    display: inline-block;
    padding: 4px 10px;
    background: var(--d);
    color: white;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 10px;
}

/* Eksemplar Badge */
.eksemplar-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 8px;
    background: var(--bg);
    color: var(--t);
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 5px;
}

/* Modal Detail Styles */
.modal-detail {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow-y: auto;
}

.modal-content-detail {
    background-color: var(--w);
    margin: 50px auto;
    padding: 30px;
    border-radius: var(--r);
    width: 90%;
    max-width: 900px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--b);
}

.modal-title-detail {
    font-size: 24px;
    font-weight: 700;
    color: var(--d);
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
    padding: 5px;
    transition: 0.3s;
}

.close-modal:hover {
    color: var(--c);
    transform: rotate(90deg);
}

.modal-body-detail {
    padding: 20px 0;
}

.detail-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-item {
    margin-bottom: 15px;
}

.detail-label {
    font-weight: 600;
    color: var(--d);
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

.detail-value {
    color: var(--t);
    font-size: 16px;
    line-height: 1.5;
}

.detail-value.sinopsis {
    background: var(--bg);
    padding: 15px;
    border-radius: var(--r);
    border-left: 4px solid var(--c);
}

.modal-files-detail {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin: 25px 0;
    flex-wrap: wrap;
}

.modal-image-detail {
    max-width: 250px;
    max-height: 300px;
    border-radius: var(--r);
    object-fit: contain;
    border: 3px solid var(--b);
    background: var(--bg);
    padding: 5px;
}

.file-download-container {
    text-align: center;
}

.file-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--c);
    color: white;
    padding: 12px 20px;
    border-radius: var(--r);
    text-decoration: none;
    margin-top: 10px;
    transition: 0.3s;
}

.file-download-btn:hover {
    background: var(--d);
    color: white;
    transform: translateY(-2px);
}

.file-caption {
    text-align: center;
    font-size: 12px;
    color: #666;
    margin-top: 8px;
}

.modal-footer-detail {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--b);
}

/* Responsive */
@media (max-width: 768px) {
    .buku-card-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .buku-cover {
        width: 150px;
        height: 200px;
    }
    
    .btn-group {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .form-buku {
        padding: 15px;
    }
    
    .modal-content-detail {
        margin: 20px auto;
        padding: 20px;
        width: 95%;
    }
    
    .modal-title-detail {
        font-size: 20px;
    }
    
    .modal-image-detail {
        max-width: 100%;
        max-height: 250px;
    }
    
    .buku-meta {
        justify-content: center;
    }
}
</style>

<div class="container">
    <!-- Button Tambah Buku -->
    <a href="index.php?page=buku&action=tambah" class="btn-add">
        <i class="fa fa-book"></i> Tambah Buku Baru
    </a>

    <?php if ($action === 'tambah' || ($action === 'edit' && $id > 0)): ?>
        <!-- Form Tambah/Edit Buku -->
        <?php
        $buku = null;
        if ($action === 'edit' && $id > 0) {
            $sql = "SELECT * FROM buku_perpustakaan WHERE id_buku = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $buku = $result->fetch_assoc();
            $stmt->close();
            
            if (!$buku) {
                echo "<script>alert('Buku tidak ditemukan'); window.location.href='index.php?page=buku';</script>";
            }
        }
        ?>
        
        <div class="form-buku">
            <h3><i class="fa fa-book"></i> <?php echo $action === 'tambah' ? 'Tambah Buku Baru' : 'Edit Buku'; ?></h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($buku): ?>
                    <input type="hidden" name="id" value="<?php echo $buku['id_buku']; ?>">
                    <input type="hidden" name="sampul_existing" value="<?php echo htmlspecialchars($buku['sampul_path'] ?? ''); ?>">
                    <input type="hidden" name="file_buku_existing" value="<?php echo htmlspecialchars($buku['file_buku_path'] ?? ''); ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="judul">Judul Buku *</label>
                        <input type="text" id="judul" name="judul" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['judul'] ?? ''); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pengarang">Pengarang *</label>
                        <input type="text" id="pengarang" name="pengarang" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['pengarang'] ?? ''); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['penerbit'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control" 
                               min="1900" max="2099" step="1"
                               value="<?php echo htmlspecialchars($buku['tahun_terbit'] ?? date('Y')); ?>">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="kota_terbit">Kota Terbit</label>
                        <input type="text" id="kota_terbit" name="kota_terbit" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['kota_terbit'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['isbn'] ?? ''); ?>"
                               placeholder="Contoh: 978-602-8519-93-9">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="bahasa">Bahasa</label>
                        <select id="bahasa" name="bahasa" class="form-control">
                            <option value="Indonesia" <?php echo ($buku['bahasa'] ?? 'Indonesia') === 'Indonesia' ? 'selected' : ''; ?>>Indonesia</option>
                            <option value="Inggris" <?php echo ($buku['bahasa'] ?? '') === 'Inggris' ? 'selected' : ''; ?>>Inggris</option>
                            <option value="Jawa" <?php echo ($buku['bahasa'] ?? '') === 'Jawa' ? 'selected' : ''; ?>>Jawa</option>
                            <option value="Arab" <?php echo ($buku['bahasa'] ?? '') === 'Arab' ? 'selected' : ''; ?>>Arab</option>
                            <option value="Lainnya" <?php echo ($buku['bahasa'] ?? '') === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipe_dokumen">Tipe Dokumen</label>
                        <select id="tipe_dokumen" name="tipe_dokumen" class="form-control">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Buku" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Buku' ? 'selected' : ''; ?>>Buku</option>
                            <option value="Jurnal" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Jurnal' ? 'selected' : ''; ?>>Jurnal</option>
                            <option value="Majalah" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Majalah' ? 'selected' : ''; ?>>Majalah</option>
                            <option value="Skripsi" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Skripsi' ? 'selected' : ''; ?>>Skripsi</option>
                            <option value="Tesis" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Tesis' ? 'selected' : ''; ?>>Tesis</option>
                            <option value="Disertasi" <?php echo ($buku['tipe_dokumen'] ?? '') === 'Disertasi' ? 'selected' : ''; ?>>Disertasi</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="subjek_topik">Subjek/Topik</label>
                        <input type="text" id="subjek_topik" name="subjek_topik" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['subjek_topik'] ?? ''); ?>"
                               placeholder="Contoh: Hukum Perdata, Hukum Pidana">
                    </div>
                    
                    <div class="form-group">
                        <label for="bidang_hukum">Bidang Hukum</label>
                        <select id="bidang_hukum" name="bidang_hukum" class="form-control">
                            <option value="">-- Pilih Bidang --</option>
                            <option value="Hukum Perdata" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Perdata' ? 'selected' : ''; ?>>Hukum Perdata</option>
                            <option value="Hukum Pidana" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Pidana' ? 'selected' : ''; ?>>Hukum Pidana</option>
                            <option value="Hukum Tata Negara" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Tata Negara' ? 'selected' : ''; ?>>Hukum Tata Negara</option>
                            <option value="Hukum Administrasi" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Administrasi' ? 'selected' : ''; ?>>Hukum Administrasi</option>
                            <option value="Hukum Internasional" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Internasional' ? 'selected' : ''; ?>>Hukum Internasional</option>
                            <option value="Hukum Dagang" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Dagang' ? 'selected' : ''; ?>>Hukum Dagang</option>
                            <option value="Hukum Islam" <?php echo ($buku['bidang_hukum'] ?? '') === 'Hukum Islam' ? 'selected' : ''; ?>>Hukum Islam</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nomor_panggil">Nomor Panggil</label>
                        <input type="text" id="nomor_panggil" name="nomor_panggil" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['nomor_panggil'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="nomor_induk">Nomor Induk</label>
                        <input type="text" id="nomor_induk" name="nomor_induk" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['nomor_induk'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="lokasi_rak">Lokasi Rak</label>
                        <input type="text" id="lokasi_rak" name="lokasi_rak" class="form-control" 
                               value="<?php echo htmlspecialchars($buku['lokasi_rak'] ?? ''); ?>"
                               placeholder="Contoh: RA.01, RB.02">
                    </div>
                    
                    <div class="form-group">
                        <label for="status_ketersediaan">Status Ketersediaan</label>
                        <select id="status_ketersediaan" name="status_ketersediaan" class="form-control">
                            <option value="tersedia" <?php echo ($buku['status_ketersediaan'] ?? 'tersedia') === 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="dipinjam" <?php echo ($buku['status_ketersediaan'] ?? '') === 'dipinjam' ? 'selected' : ''; ?>>Dipinjam</option>
                            <option value="hilang" <?php echo ($buku['status_ketersediaan'] ?? '') === 'hilang' ? 'selected' : ''; ?>>Hilang</option>
                            <option value="rusak" <?php echo ($buku['status_ketersediaan'] ?? '') === 'rusak' ? 'selected' : ''; ?>>Rusak</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="jumlah_eksemplar">Jumlah Eksemplar</label>
                        <input type="number" id="jumlah_eksemplar" name="jumlah_eksemplar" class="form-control" 
                               min="1" value="<?php echo htmlspecialchars($buku['jumlah_eksemplar'] ?? 1); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah_tersedia">Jumlah Tersedia</label>
                        <input type="number" id="jumlah_tersedia" name="jumlah_tersedia" class="form-control" 
                               min="0" value="<?php echo htmlspecialchars($buku['jumlah_tersedia'] ?? 1); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi_fisik">Deskripsi Fisik</label>
                    <input type="text" id="deskripsi_fisik" name="deskripsi_fisik" class="form-control" 
                           value="<?php echo htmlspecialchars($buku['deskripsi_fisik'] ?? ''); ?>"
                           placeholder="Contoh: xii, 350 halaman; 23 cm">
                </div>
                
                <div class="form-group">
                    <label for="sinopsis">Sinopsis</label>
                    <textarea id="sinopsis" name="sinopsis" class="form-control" 
                              rows="5"><?php echo htmlspecialchars($buku['sinopsis'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="sampul">Sampul Buku</label>
                        <?php if ($buku && $buku['sampul_path']): ?>
                            <div class="current-files">
                                <div class="file-container">
                                    <img src="<?php echo $buku['sampul_path']; ?>" alt="Sampul Buku" class="preview-image">
                                    <p>Sampul Saat Ini</p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti sampul</p>
                        <?php endif; ?>
                        <input type="file" id="sampul" name="sampul" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="file_buku">File Buku (Digital)</label>
                        <?php if ($buku && $buku['file_buku_path']): ?>
                            <div class="current-files">
                                <div class="file-container">
                                    <div class="file-icon">
                                        <?php
                                        $ext = pathinfo($buku['file_buku_path'], PATHINFO_EXTENSION);
                                        $icon = 'fa-file';
                                        if ($ext === 'pdf') $icon = 'fa-file-pdf-o';
                                        elseif (in_array($ext, ['doc', 'docx'])) $icon = 'fa-file-word-o';
                                        elseif ($ext === 'epub') $icon = 'fa-book';
                                        elseif ($ext === 'txt') $icon = 'fa-file-text-o';
                                        ?>
                                        <i class="fa <?php echo $icon; ?>"></i>
                                    </div>
                                    <p>File saat ini: <?php echo basename($buku['file_buku_path']); ?></p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti file buku</p>
                        <?php endif; ?>
                        <input type="file" id="file_buku" name="file_buku" class="form-control" accept=".pdf,.doc,.docx,.epub,.txt">
                        <small class="text-muted">Format: PDF, DOC, DOCX, EPUB, TXT (Max: 10MB)</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" 
                           value="<?php echo htmlspecialchars($buku['tanggal_masuk'] ?? ''); ?>">
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Buku
                    </button>
                    <a href="index.php?page=buku" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    <?php else: ?>
        <!-- Daftar Buku -->
        <h3><i class="fa fa-book"></i> Daftar Buku Perpustakaan</h3>
        
        <?php
        // Ambil data buku
        $sql = "SELECT * FROM buku_perpustakaan ORDER BY tahun_terbit DESC, judul ASC";
        $result = $koneksi->query($sql);
        
        if (!$result) {
            echo "<div class='alert alert-danger'>Error query: " . $koneksi->error . "</div>";
            $buku_list = [];
        } else {
            $buku_list = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if (empty($buku_list)): ?>
            <div class="buku-card" style="text-align: center; padding: 40px;">
                <i class="fa fa-book" style="font-size: 48px; color: var(--b); margin-bottom: 15px;"></i>
                <h4 style="color: var(--t);">Belum ada buku</h4>
                <p style="color: #666;">Mulai dengan menambahkan buku pertama</p>
                <a href="index.php?page=buku&action=tambah" class="btn-add">
                    <i class="fa fa-plus"></i> Tambah Buku Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="buku-grid">
                <?php foreach ($buku_list as $buku): ?>
                    <div class="buku-card" id="buku-<?php echo $buku['id_buku']; ?>">
                        <div class="buku-card-content">
                            <?php if ($buku['sampul_path'] && file_exists($buku['sampul_path'])): ?>
                                <img src="<?php echo $buku['sampul_path']; ?>" 
                                     alt="Cover Buku: <?php echo htmlspecialchars($buku['judul']); ?>" 
                                     class="buku-cover"
                                     onclick="showDetailBuku(<?php echo htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)">
                            <?php else: ?>
                                <div class="file-icon" style="width: 120px; height: 160px; cursor: pointer;"
                                     onclick="showDetailBuku(<?php echo htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <i class="fa fa-book" style="font-size: 48px; color: var(--c);"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="buku-info">
                                <div class="buku-header">
                                    <h4 class="buku-title" onclick="showDetailBuku(<?php echo htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)">
                                        <?php echo htmlspecialchars($buku['judul']); ?>
                                        <?php if ($buku['tahun_terbit']): ?>
                                            <span class="tahun-badge"><?php echo htmlspecialchars($buku['tahun_terbit']); ?></span>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <div class="buku-author">
                                        <i class="fa fa-user"></i> <?php echo htmlspecialchars($buku['pengarang']); ?>
                                    </div>
                                    
                                    <div class="buku-meta">
                                        <?php if ($buku['penerbit']): ?>
                                            <span>
                                                <i class="fa fa-building"></i> 
                                                <?php echo htmlspecialchars($buku['penerbit']); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <span class="status-badge status-<?php echo $buku['status_ketersediaan']; ?>">
                                            <?php echo ucfirst($buku['status_ketersediaan']); ?>
                                        </span>
                                        
                                        <span>
                                            <i class="fa fa-copy"></i> 
                                            <?php echo $buku['jumlah_eksemplar']; ?> eks
                                            <span class="eksemplar-badge">Tersedia: <?php echo $buku['jumlah_tersedia']; ?></span>
                                        </span>
                                        
                                        <?php if ($buku['bidang_hukum']): ?>
                                            <span style="background: var(--bg); padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                                                <?php echo htmlspecialchars($buku['bidang_hukum']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($buku['sinopsis']): ?>
                                    <div class="buku-sinopsis">
                                        <?php 
                                        $sinopsis = strip_tags($buku['sinopsis']);
                                        echo strlen($sinopsis) > 150 ? substr($sinopsis, 0, 150) . '...' : $sinopsis;
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="btn-group">
                            <a href="index.php?page=buku&action=edit&id=<?php echo $buku['id_buku']; ?>" 
                               class="btn-edit btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0);" 
                               onclick="confirmDelete(<?php echo $buku['id_buku']; ?>)" 
                               class="btn-delete btn-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                            <button type="button" 
                                    class="btn-detail btn-sm"
                                    onclick="showDetailBuku(<?php echo htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)">
                                <i class="fa fa-eye"></i> Lihat Detail
                            </button>
                            
                            <?php if ($buku['file_buku_path'] && file_exists($buku['file_buku_path'])): ?>
                                <a href="<?php echo $buku['file_buku_path']; ?>" 
                                   class="btn-sm" 
                                   style="background: var(--c); color: white; text-decoration: none;"
                                   target="_blank">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Modal Detail Buku -->
<div id="modalDetailBuku" class="modal-detail">
    <div class="modal-content-detail">
        <div class="modal-header-detail">
            <h3 class="modal-title-detail" id="detailTitleBuku"></h3>
            <button class="close-modal" onclick="closeDetailBuku()">&times;</button>
        </div>
        <div class="modal-body-detail">
            <div class="detail-info-grid">
                <div class="detail-item">
                    <span class="detail-label">Judul Buku</span>
                    <div class="detail-value" id="detailJudul"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pengarang</span>
                    <div class="detail-value" id="detailPengarang"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Penerbit</span>
                    <div class="detail-value" id="detailPenerbit"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tahun Terbit</span>
                    <div class="detail-value" id="detailTahunTerbit"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kota Terbit</span>
                    <div class="detail-value" id="detailKotaTerbit"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ISBN</span>
                    <div class="detail-value" id="detailISBN"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Deskripsi Fisik</span>
                    <div class="detail-value" id="detailDeskripsiFisik"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Subjek/Topik</span>
                    <div class="detail-value" id="detailSubjek"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Bidang Hukum</span>
                    <div class="detail-value" id="detailBidangHukum"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Bahasa</span>
                    <div class="detail-value" id="detailBahasa"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tipe Dokumen</span>
                    <div class="detail-value" id="detailTipeDokumen"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nomor Panggil</span>
                    <div class="detail-value" id="detailNomorPanggil"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nomor Induk</span>
                    <div class="detail-value" id="detailNomorInduk"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Lokasi Rak</span>
                    <div class="detail-value" id="detailLokasiRak"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status Ketersediaan</span>
                    <div class="detail-value" id="detailStatus"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jumlah Eksemplar</span>
                    <div class="detail-value" id="detailJumlahEksemplar"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jumlah Tersedia</span>
                    <div class="detail-value" id="detailJumlahTersedia"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Masuk</span>
                    <div class="detail-value" id="detailTanggalMasuk"></div>
                </div>
            </div>
            
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span class="detail-label">Sinopsis</span>
                <div class="detail-value sinopsis" id="detailSinopsis"></div>
            </div>
            
            <div class="modal-files-detail" id="detailFilesBuku">
                <!-- Sampul dan file buku akan dimasukkan via JavaScript -->
            </div>
            
            <div class="modal-footer-detail">
                <button class="btn btn-default" onclick="closeDetailBuku()">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <button class="btn btn-primary" id="btnEditDetailBuku" onclick="editDetailBuku()">
                    <i class="fa fa-edit"></i> Edit Buku
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Konfirmasi hapus buku
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
        window.location.href = 'index.php?page=buku&action=hapus&id=' + id;
    }
}

// Variable global untuk menyimpan ID buku saat ini
let currentBukuId = null;

// Fungsi untuk menampilkan detail buku
function showDetailBuku(buku) {
    currentBukuId = buku.id_buku;
    
    // Set data ke modal
    document.getElementById('detailTitleBuku').textContent = buku.judul;
    document.getElementById('detailJudul').textContent = buku.judul;
    document.getElementById('detailPengarang').textContent = buku.pengarang || '-';
    document.getElementById('detailPenerbit').textContent = buku.penerbit || '-';
    document.getElementById('detailTahunTerbit').textContent = buku.tahun_terbit || '-';
    document.getElementById('detailKotaTerbit').textContent = buku.kota_terbit || '-';
    document.getElementById('detailISBN').textContent = buku.isbn || '-';
    document.getElementById('detailDeskripsiFisik').textContent = buku.deskripsi_fisik || '-';
    document.getElementById('detailSubjek').textContent = buku.subjek_topik || '-';
    document.getElementById('detailBidangHukum').textContent = buku.bidang_hukum || '-';
    document.getElementById('detailBahasa').textContent = buku.bahasa || '-';
    document.getElementById('detailTipeDokumen').textContent = buku.tipe_dokumen || '-';
    document.getElementById('detailNomorPanggil').textContent = buku.nomor_panggil || '-';
    document.getElementById('detailNomorInduk').textContent = buku.nomor_induk || '-';
    document.getElementById('detailLokasiRak').textContent = buku.lokasi_rak || '-';
    
    // Set status dengan badge
    const statusText = buku.status_ketersediaan.charAt(0).toUpperCase() + buku.status_ketersediaan.slice(1);
    const statusClass = `status-${buku.status_ketersediaan}`;
    document.getElementById('detailStatus').innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;
    
    // Set jumlah
    document.getElementById('detailJumlahEksemplar').textContent = buku.jumlah_eksemplar || '0';
    document.getElementById('detailJumlahTersedia').textContent = buku.jumlah_tersedia || '0';
    
    // Format tanggal masuk
    if (buku.tanggal_masuk) {
        const tanggal = new Date(buku.tanggal_masuk);
        document.getElementById('detailTanggalMasuk').textContent = tanggal.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } else {
        document.getElementById('detailTanggalMasuk').textContent = '-';
    }
    
    // Set sinopsis
    document.getElementById('detailSinopsis').textContent = buku.sinopsis || 'Tidak ada sinopsis';
    
    // Set gambar dan file
    const filesContainer = document.getElementById('detailFilesBuku');
    filesContainer.innerHTML = '';
    
    // Tambahkan sampul jika ada
    if (buku.sampul_path) {
        const sampulDiv = document.createElement('div');
        sampulDiv.innerHTML = `
            <img src="${buku.sampul_path}" alt="Sampul Buku" class="modal-image-detail">
            <div class="file-caption">Sampul Buku</div>
        `;
        filesContainer.appendChild(sampulDiv);
    }
    
    // Tambahkan file buku jika ada
    if (buku.file_buku_path) {
        const fileDiv = document.createElement('div');
        const ext = buku.file_buku_path.split('.').pop().toLowerCase();
        let icon = 'fa-file';
        if (ext === 'pdf') icon = 'fa-file-pdf-o';
        else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word-o';
        else if (ext === 'epub') icon = 'fa-book';
        else if (ext === 'txt') icon = 'fa-file-text-o';
        
        fileDiv.innerHTML = `
            <div class="file-download-container">
                <div style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; background: var(--bg); border-radius: var(--r); border: 2px dashed var(--b);">
                    <i class="fa ${icon}" style="font-size: 60px; color: var(--c);"></i>
                </div>
                <a href="${buku.file_buku_path}" class="file-download-btn" target="_blank">
                    <i class="fa fa-download"></i> Download File
                </a>
                <div class="file-caption">File Digital</div>
            </div>
        `;
        filesContainer.appendChild(fileDiv);
    }
    
    // Jika tidak ada file, tampilkan pesan
    if (!buku.sampul_path && !buku.file_buku_path) {
        filesContainer.innerHTML = '<p style="color:#666; text-align:center; width: 100%;">Tidak ada file tersedia</p>';
    }
    
    // Tampilkan modal
    document.getElementById('modalDetailBuku').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal detail buku
function closeDetailBuku() {
    document.getElementById('modalDetailBuku').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk edit dari modal detail
function editDetailBuku() {
    if (currentBukuId) {
        window.location.href = `index.php?page=buku&action=edit&id=${currentBukuId}`;
    }
}

// Tutup modal jika klik di luar konten modal
window.onclick = function(event) {
    const modal = document.getElementById('modalDetailBuku');
    if (event.target === modal) {
        closeDetailBuku();
    }
}

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailBuku();
    }
});

// Preview gambar sebelum upload
document.addEventListener('DOMContentLoaded', function() {
    // Preview untuk sampul
    const sampulInput = document.getElementById('sampul');
    if (sampulInput) {
        sampulInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = sampulInput.nextElementSibling.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image';
                        sampulInput.nextElementSibling.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Auto-set tahun saat ini jika form tambah
    if (document.getElementById('tahun_terbit') && !document.getElementById('tahun_terbit').value) {
        document.getElementById('tahun_terbit').value = new Date().getFullYear();
    }
    
    // Auto-set tanggal hari ini jika form tambah
    if (document.getElementById('tanggal_masuk') && !document.getElementById('tanggal_masuk').value) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_masuk').value = today;
    }
    
    // Auto-hitung jumlah tersedia berdasarkan jumlah eksemplar
    const jumlahEksemplar = document.getElementById('jumlah_eksemplar');
    const jumlahTersedia = document.getElementById('jumlah_tersedia');
    
    if (jumlahEksemplar && jumlahTersedia) {
        jumlahEksemplar.addEventListener('change', function() {
            if (!jumlahTersedia.value || parseInt(jumlahTersedia.value) > parseInt(this.value)) {
                jumlahTersedia.value = this.value;
            }
        });
    }
});
</script>