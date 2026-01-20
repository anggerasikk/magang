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
function uploadFileYurisprudensi($file, $type = 'cover') {
    $targetDir = "uploads/yurisprudensi/";
    
    // Buat direktori jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Generate nama file unik
    $fileName = time() . '_' . $type . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validasi file
    if ($type === 'cover') {
        // Validasi gambar
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return ['success' => false, 'message' => 'File cover bukan gambar'];
        }
        
        // Batasi ukuran file (max 2MB untuk cover)
        if ($file["size"] > 2000000) {
            return ['success' => false, 'message' => 'Ukuran file cover maksimal 2MB'];
        }
        
        // Format yang diperbolehkan
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Hanya format JPG, JPEG, PNG, GIF yang diperbolehkan'];
        }
    } else {
        // Untuk file lampiran (PDF, DOC, dll)
        // Batasi ukuran file (max 10MB untuk file lampiran)
        if ($file["size"] > 10000000) {
            return ['success' => false, 'message' => 'Ukuran file lampiran maksimal 10MB'];
        }
        
        // Format yang diperbolehkan
        $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Hanya format PDF, DOC, DOCX, TXT, RTF yang diperbolehkan'];
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

// Tambah/Edit Yurisprudensi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipe_dokumen = $koneksi->real_escape_string($_POST['tipe_dokumen'] ?? '');
    $judul = $koneksi->real_escape_string($_POST['judul'] ?? '');
    $slug = createSlug($judul);
    $teu_badan = $koneksi->real_escape_string($_POST['teu_badan'] ?? '');
    $nomor_putusan = $koneksi->real_escape_string($_POST['nomor_putusan'] ?? '');
    $jenis_peradilan = $koneksi->real_escape_string($_POST['jenis_peradilan'] ?? '');
    $singkatan_jenis_peradilan = $koneksi->real_escape_string($_POST['singkatan_jenis_peradilan'] ?? '');
    $tempat_peradilan = $koneksi->real_escape_string($_POST['tempat_peradilan'] ?? '');
    $tanggal_dibacakan = $koneksi->real_escape_string($_POST['tanggal_dibacakan'] ?? '');
    $sumber = $koneksi->real_escape_string($_POST['sumber'] ?? '');
    $subjek = $koneksi->real_escape_string($_POST['subjek'] ?? '');
    $status_putusan = $koneksi->real_escape_string($_POST['status_putusan'] ?? '');
    $bahasa = $koneksi->real_escape_string($_POST['bahasa'] ?? 'Indonesia');
    $bidang_hukum_jenis_perkara = $koneksi->real_escape_string($_POST['bidang_hukum_jenis_perkara'] ?? '');
    $lokasi = $koneksi->real_escape_string($_POST['lokasi'] ?? '');
    $kata_kunci = $koneksi->real_escape_string($_POST['kata_kunci'] ?? '');
    $deskripsi = $koneksi->real_escape_string($_POST['deskripsi'] ?? '');
    
    // Ekstrak tahun dari nomor putusan atau tanggal dibacakan
    $tahun_putusan = date('Y', strtotime($tanggal_dibacakan));
    
    // ID user dari session
    $id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
    
    if (($_POST['action'] ?? '') === 'tambah') {
        // Handle upload cover
        $cover_path = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
            $upload = uploadFileYurisprudensi($_FILES['cover'], 'cover');
            if ($upload['success']) {
                $cover_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Handle upload lampiran
        $lampiran_path = null;
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === 0) {
            $upload = uploadFileYurisprudensi($_FILES['lampiran'], 'lampiran');
            if ($upload['success']) {
                $lampiran_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Insert yurisprudensi baru
        $sql = "INSERT INTO yurisprudensi 
            (tipe_dokumen, judul, slug, teu_badan, nomor_putusan, jenis_peradilan, 
             singkatan_jenis_peradilan, tempat_peradilan, tanggal_dibacakan, sumber, 
             subjek, status_putusan, bahasa, bidang_hukum_jenis_perkara, lokasi, 
             cover_path, lampiran_path, tahun_putusan, kata_kunci, deskripsi, id_user) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssi", 
            $tipe_dokumen, $judul, $slug, $teu_badan, $nomor_putusan, $jenis_peradilan,
            $singkatan_jenis_peradilan, $tempat_peradilan, $tanggal_dibacakan, $sumber,
            $subjek, $status_putusan, $bahasa, $bidang_hukum_jenis_perkara, $lokasi,
            $cover_path, $lampiran_path, $tahun_putusan, $kata_kunci, $deskripsi, $id_user
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Yurisprudensi berhasil ditambahkan'); window.location.href='index.php?page=yurisprudensi';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan yurisprudensi. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
        
    } elseif (($_POST['action'] ?? '') === 'edit' && $id > 0) {
        // Update yurisprudensi
        $cover_path = $_POST['cover_existing'] ?? null;
        $lampiran_path = $_POST['lampiran_existing'] ?? null;
        
        // Jika ada file cover baru diupload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
            $upload = uploadFileYurisprudensi($_FILES['cover'], 'cover');
            if ($upload['success']) {
                // Hapus cover lama jika ada
                if ($cover_path && file_exists($cover_path)) {
                    unlink($cover_path);
                }
                $cover_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $cover_path = $koneksi->real_escape_string($cover_path);
        }
        
        // Jika ada file lampiran baru diupload
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === 0) {
            $upload = uploadFileYurisprudensi($_FILES['lampiran'], 'lampiran');
            if ($upload['success']) {
                // Hapus lampiran lama jika ada
                if ($lampiran_path && file_exists($lampiran_path)) {
                    unlink($lampiran_path);
                }
                $lampiran_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $lampiran_path = $koneksi->real_escape_string($lampiran_path);
        }
        
        // Update yurisprudensi
        $sql = "UPDATE yurisprudensi SET 
            tipe_dokumen = ?, judul = ?, slug = ?, teu_badan = ?, nomor_putusan = ?, 
            jenis_peradilan = ?, singkatan_jenis_peradilan = ?, tempat_peradilan = ?, 
            tanggal_dibacakan = ?, sumber = ?, subjek = ?, status_putusan = ?, 
            bahasa = ?, bidang_hukum_jenis_perkara = ?, lokasi = ?, cover_path = ?, 
            lampiran_path = ?, tahun_putusan = ?, kata_kunci = ?, deskripsi = ?, 
            updated_at = NOW()
            WHERE id_yurisprudensi = ?";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssi", 
            $tipe_dokumen, $judul, $slug, $teu_badan, $nomor_putusan, $jenis_peradilan,
            $singkatan_jenis_peradilan, $tempat_peradilan, $tanggal_dibacakan, $sumber,
            $subjek, $status_putusan, $bahasa, $bidang_hukum_jenis_perkara, $lokasi,
            $cover_path, $lampiran_path, $tahun_putusan, $kata_kunci, $deskripsi, $id
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Yurisprudensi berhasil diperbarui'); window.location.href='index.php?page=yurisprudensi';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui yurisprudensi. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Hapus Yurisprudensi
if ($action === 'hapus' && $id > 0) {
    // Ambil path cover dan lampiran untuk dihapus
    $sql = "SELECT cover_path, lampiran_path FROM yurisprudensi WHERE id_yurisprudensi = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $yurisprudensi = $result->fetch_assoc();
    $stmt->close();
    
    // Hapus file cover jika ada
    if ($yurisprudensi && $yurisprudensi['cover_path'] && file_exists($yurisprudensi['cover_path'])) {
        unlink($yurisprudensi['cover_path']);
    }
    
    // Hapus file lampiran jika ada
    if ($yurisprudensi && $yurisprudensi['lampiran_path'] && file_exists($yurisprudensi['lampiran_path'])) {
        unlink($yurisprudensi['lampiran_path']);
    }
    
    $sql = "DELETE FROM yurisprudensi WHERE id_yurisprudensi = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Yurisprudensi berhasil dihapus'); window.location.href='index.php?page=yurisprudensi';</script>";
    } else {
        echo "<script>alert('Gagal menghapus yurisprudensi. Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!-- CSS Tambahan untuk CRUD Yurisprudensi -->
<style>
/* Form Yurisprudensi */
.form-yurisprudensi {
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

.status-tetap {
    background: #d1fae5;
    color: #065f46;
}

.status-tidak-tetap {
    background: #fef3c7;
    color: #92400e;
}

.status-dibatalkan {
    background: #fee2e2;
    color: #991b1b;
}

.status-diubah {
    background: #dbeafe;
    color: #1e40af;
}

/* Yurisprudensi Card */
.yurisprudensi-card {
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

.yurisprudensi-card:hover {
    box-shadow: var(--s);
    transform: translateY(-2px);
}

.yurisprudensi-card-content {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    align-items: flex-start;
}

.yurisprudensi-cover {
    width: 120px;
    height: 160px;
    border-radius: var(--r);
    object-fit: cover;
    border: 2px solid var(--b);
    flex-shrink: 0;
    cursor: pointer;
    transition: 0.3s;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--c);
    font-size: 40px;
}

.yurisprudensi-cover:hover {
    transform: scale(1.05);
}

.yurisprudensi-cover i {
    opacity: 0.7;
}

.yurisprudensi-info {
    flex: 1;
}

.yurisprudensi-header {
    margin-bottom: 10px;
}

.yurisprudensi-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--d);
    margin: 0 0 5px 0;
    cursor: pointer;
    transition: 0.3s;
}

.yurisprudensi-title:hover {
    color: var(--c);
}

.yurisprudensi-subtitle {
    font-size: 14px;
    color: var(--c);
    font-weight: 500;
    margin-bottom: 5px;
}

.yurisprudensi-meta {
    font-size: 12px;
    color: #666;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 10px;
}

.yurisprudensi-deskripsi {
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

/* Current Files */
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
    max-width: 1000px;
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
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--b);
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
    word-break: break-word;
}

.detail-value.deskripsi {
    background: var(--bg);
    padding: 15px;
    border-radius: var(--r);
    border-left: 4px solid var(--c);
}

.modal-files-detail {
    display: flex;
    gap: 30px;
    justify-content: center;
    margin: 25px 0;
    flex-wrap: wrap;
    align-items: flex-start;
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
    max-width: 300px;
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
    margin-top: 15px;
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
    .yurisprudensi-card-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .yurisprudensi-cover {
        width: 150px;
        height: 200px;
    }
    
    .btn-group {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .form-yurisprudensi {
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
    
    .yurisprudensi-meta {
        justify-content: center;
    }
    
    .detail-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container">
    <!-- Button Tambah Yurisprudensi -->
    <a href="index.php?page=yurisprudensi&action=tambah" class="btn-add">
        <i class="fa fa-gavel"></i> Tambah Yurisprudensi Baru
    </a>

    <?php if ($action === 'tambah' || ($action === 'edit' && $id > 0)): ?>
        <!-- Form Tambah/Edit Yurisprudensi -->
        <?php
        $yurisprudensi = null;
        if ($action === 'edit' && $id > 0) {
            $sql = "SELECT * FROM yurisprudensi WHERE id_yurisprudensi = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $yurisprudensi = $result->fetch_assoc();
            $stmt->close();
            
            if (!$yurisprudensi) {
                echo "<script>alert('Yurisprudensi tidak ditemukan'); window.location.href='index.php?page=yurisprudensi';</script>";
            }
        }
        ?>
        
        <div class="form-yurisprudensi">
            <h3><i class="fa fa-gavel"></i> <?php echo $action === 'tambah' ? 'Tambah Yurisprudensi Baru' : 'Edit Yurisprudensi'; ?></h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($yurisprudensi): ?>
                    <input type="hidden" name="id" value="<?php echo $yurisprudensi['id_yurisprudensi']; ?>">
                    <input type="hidden" name="cover_existing" value="<?php echo htmlspecialchars($yurisprudensi['cover_path'] ?? ''); ?>">
                    <input type="hidden" name="lampiran_existing" value="<?php echo htmlspecialchars($yurisprudensi['lampiran_path'] ?? ''); ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="tipe_dokumen">Tipe Dokumen *</label>
                    <select id="tipe_dokumen" name="tipe_dokumen" class="form-control" required>
                        <option value="">-- Pilih Tipe Dokumen --</option>
                        <option value="Putusan Pengadilan" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Putusan Pengadilan' ? 'selected' : ''; ?>>Putusan Pengadilan</option>
                        <option value="Penetapan Pengadilan" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Penetapan Pengadilan' ? 'selected' : ''; ?>>Penetapan Pengadilan</option>
                        <option value="Putusan Mahkamah Agung" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Putusan Mahkamah Agung' ? 'selected' : ''; ?>>Putusan Mahkamah Agung</option>
                        <option value="Putusan Mahkamah Konstitusi" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Putusan Mahkamah Konstitusi' ? 'selected' : ''; ?>>Putusan Mahkamah Konstitusi</option>
                        <option value="Putusan Pengadilan Tinggi" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Putusan Pengadilan Tinggi' ? 'selected' : ''; ?>>Putusan Pengadilan Tinggi</option>
                        <option value="Putusan Pengadilan Negeri" <?php echo ($yurisprudensi['tipe_dokumen'] ?? '') === 'Putusan Pengadilan Negeri' ? 'selected' : ''; ?>>Putusan Pengadilan Negeri</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="judul">Judul *</label>
                    <input type="text" id="judul" name="judul" class="form-control" 
                           value="<?php echo htmlspecialchars($yurisprudensi['judul'] ?? ''); ?>" 
                           required placeholder="Contoh: Putusan Mahkamah Agung Nomor 48 P/HUM/2014">
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="teu_badan">T.E.U. Badan *</label>
                        <input type="text" id="teu_badan" name="teu_badan" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['teu_badan'] ?? ''); ?>" 
                               required placeholder="Contoh: Mahkamah Agung">
                    </div>
                    
                    <div class="form-group">
                        <label for="nomor_putusan">Nomor Putusan *</label>
                        <input type="text" id="nomor_putusan" name="nomor_putusan" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['nomor_putusan'] ?? ''); ?>" 
                               required placeholder="Contoh: 48 P/HUM/2014">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="jenis_peradilan">Jenis Peradilan *</label>
                        <select id="jenis_peradilan" name="jenis_peradilan" class="form-control" required>
                            <option value="">-- Pilih Jenis Peradilan --</option>
                            <option value="Mahkamah Agung" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Mahkamah Agung' ? 'selected' : ''; ?>>Mahkamah Agung</option>
                            <option value="Mahkamah Konstitusi" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Mahkamah Konstitusi' ? 'selected' : ''; ?>>Mahkamah Konstitusi</option>
                            <option value="Pengadilan Tinggi" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Pengadilan Tinggi' ? 'selected' : ''; ?>>Pengadilan Tinggi</option>
                            <option value="Pengadilan Negeri" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Pengadilan Negeri' ? 'selected' : ''; ?>>Pengadilan Negeri</option>
                            <option value="Pengadilan Tata Usaha Negara" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Pengadilan Tata Usaha Negara' ? 'selected' : ''; ?>>Pengadilan Tata Usaha Negara</option>
                            <option value="Pengadilan Militer" <?php echo ($yurisprudensi['jenis_peradilan'] ?? '') === 'Pengadilan Militer' ? 'selected' : ''; ?>>Pengadilan Militer</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="singkatan_jenis_peradilan">Singkatan Jenis Peradilan</label>
                        <input type="text" id="singkatan_jenis_peradilan" name="singkatan_jenis_peradilan" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['singkatan_jenis_peradilan'] ?? ''); ?>"
                               placeholder="Contoh: MA, MK, PT, PN">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tempat_peradilan">Tempat Peradilan</label>
                        <input type="text" id="tempat_peradilan" name="tempat_peradilan" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['tempat_peradilan'] ?? ''); ?>"
                               placeholder="Contoh: Jakarta">
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_dibacakan">Tanggal Dibacakan *</label>
                        <input type="date" id="tanggal_dibacakan" name="tanggal_dibacakan" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['tanggal_dibacakan'] ?? ''); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="sumber">Sumber</label>
                        <input type="text" id="sumber" name="sumber" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['sumber'] ?? ''); ?>"
                               placeholder="Contoh: Mahkamah Agung">
                    </div>
                    
                    <div class="form-group">
                        <label for="status_putusan">Status Putusan</label>
                        <select id="status_putusan" name="status_putusan" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="Tetap" <?php echo ($yurisprudensi['status_putusan'] ?? '') === 'Tetap' ? 'selected' : ''; ?>>Tetap</option>
                            <option value="Tidak Tetap" <?php echo ($yurisprudensi['status_putusan'] ?? '') === 'Tidak Tetap' ? 'selected' : ''; ?>>Tidak Tetap</option>
                            <option value="Dibatalkan" <?php echo ($yurisprudensi['status_putusan'] ?? '') === 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                            <option value="Diubah" <?php echo ($yurisprudensi['status_putusan'] ?? '') === 'Diubah' ? 'selected' : ''; ?>>Diubah</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="bahasa">Bahasa</label>
                        <select id="bahasa" name="bahasa" class="form-control">
                            <option value="Indonesia" <?php echo ($yurisprudensi['bahasa'] ?? 'Indonesia') === 'Indonesia' ? 'selected' : ''; ?>>Indonesia</option>
                            <option value="Inggris" <?php echo ($yurisprudensi['bahasa'] ?? '') === 'Inggris' ? 'selected' : ''; ?>>Inggris</option>
                            <option value="Arab" <?php echo ($yurisprudensi['bahasa'] ?? '') === 'Arab' ? 'selected' : ''; ?>>Arab</option>
                            <option value="Lainnya" <?php echo ($yurisprudensi['bahasa'] ?? '') === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="form-control" 
                               value="<?php echo htmlspecialchars($yurisprudensi['lokasi'] ?? ''); ?>"
                               placeholder="Contoh: Mahkamah Agung">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bidang_hukum_jenis_perkara">Bidang Hukum / Jenis Perkara</label>
                    <input type="text" id="bidang_hukum_jenis_perkara" name="bidang_hukum_jenis_perkara" class="form-control" 
                           value="<?php echo htmlspecialchars($yurisprudensi['bidang_hukum_jenis_perkara'] ?? ''); ?>"
                           placeholder="Contoh: Hukum Administrasi Negara">
                </div>
                
                <div class="form-group">
                    <label for="subjek">Subjek</label>
                    <textarea id="subjek" name="subjek" class="form-control" 
                              rows="3"><?php echo htmlspecialchars($yurisprudensi['subjek'] ?? ''); ?></textarea>
                    <small class="text-muted">Contoh: UJI MATERIIL - HPP - GULA KRISTAL PUTIH - 2014</small>
                </div>
                
                <div class="form-group">
                    <label for="kata_kunci">Kata Kunci</label>
                    <input type="text" id="kata_kunci" name="kata_kunci" class="form-control" 
                           value="<?php echo htmlspecialchars($yurisprudensi['kata_kunci'] ?? ''); ?>"
                           placeholder="Pisahkan dengan koma, contoh: uji materiil, HPP, gula">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" 
                              rows="5"><?php echo htmlspecialchars($yurisprudensi['deskripsi'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cover">Cover (Opsional)</label>
                        <?php if ($yurisprudensi && $yurisprudensi['cover_path']): ?>
                            <div class="current-files">
                                <div class="file-container">
                                    <img src="<?php echo $yurisprudensi['cover_path']; ?>" alt="Cover Yurisprudensi" class="preview-image">
                                    <p>Cover Saat Ini</p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti cover</p>
                        <?php endif; ?>
                        <input type="file" id="cover" name="cover" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="lampiran">Lampiran Dokumen *</label>
                        <?php if ($yurisprudensi && $yurisprudensi['lampiran_path']): ?>
                            <div class="current-files">
                                <div class="file-container">
                                    <div class="file-icon">
                                        <?php
                                        $ext = pathinfo($yurisprudensi['lampiran_path'], PATHINFO_EXTENSION);
                                        $icon = 'fa-file';
                                        if ($ext === 'pdf') $icon = 'fa-file-pdf-o';
                                        elseif (in_array($ext, ['doc', 'docx'])) $icon = 'fa-file-word-o';
                                        elseif ($ext === 'txt') $icon = 'fa-file-text-o';
                                        ?>
                                        <i class="fa <?php echo $icon; ?>"></i>
                                    </div>
                                    <p>File saat ini: <?php echo basename($yurisprudensi['lampiran_path']); ?></p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti lampiran</p>
                        <?php endif; ?>
                        <input type="file" id="lampiran" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.txt,.rtf" <?php echo !$yurisprudensi ? 'required' : ''; ?>>
                        <small class="text-muted">Format: PDF, DOC, DOCX, TXT, RTF (Max: 10MB)</small>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Yurisprudensi
                    </button>
                    <a href="index.php?page=yurisprudensi" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    <?php else: ?>
        <!-- Daftar Yurisprudensi -->
        <h3><i class="fa fa-gavel"></i> Daftar Yurisprudensi</h3>
        
        <?php
        // Ambil data yurisprudensi
        $sql = "SELECT * FROM yurisprudensi ORDER BY tanggal_dibacakan DESC, nomor_putusan ASC";
        $result = $koneksi->query($sql);
        
        if (!$result) {
            echo "<div class='alert alert-danger'>Error query: " . $koneksi->error . "</div>";
            $yurisprudensi_list = [];
        } else {
            $yurisprudensi_list = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if (empty($yurisprudensi_list)): ?>
            <div class="yurisprudensi-card" style="text-align: center; padding: 40px;">
                <i class="fa fa-gavel" style="font-size: 48px; color: var(--b); margin-bottom: 15px;"></i>
                <h4 style="color: var(--t);">Belum ada yurisprudensi</h4>
                <p style="color: #666;">Mulai dengan menambahkan yurisprudensi pertama</p>
                <a href="index.php?page=yurisprudensi&action=tambah" class="btn-add">
                    <i class="fa fa-plus"></i> Tambah Yurisprudensi Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="yurisprudensi-grid">
                <?php foreach ($yurisprudensi_list as $yurisprudensi): ?>
                    <div class="yurisprudensi-card" id="yurisprudensi-<?php echo $yurisprudensi['id_yurisprudensi']; ?>">
                        <div class="yurisprudensi-card-content">
                            <div class="yurisprudensi-cover" onclick="showDetailYurisprudensi(<?php echo htmlspecialchars(json_encode($yurisprudensi), ENT_QUOTES, 'UTF-8'); ?>)">
                                <?php if ($yurisprudensi['cover_path'] && file_exists($yurisprudensi['cover_path'])): ?>
                                    <img src="<?php echo $yurisprudensi['cover_path']; ?>" 
                                         alt="Cover: <?php echo htmlspecialchars($yurisprudensi['judul']); ?>"
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--r);">
                                <?php else: ?>
                                    <i class="fa fa-gavel"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="yurisprudensi-info">
                                <div class="yurisprudensi-header">
                                    <h4 class="yurisprudensi-title" onclick="showDetailYurisprudensi(<?php echo htmlspecialchars(json_encode($yurisprudensi), ENT_QUOTES, 'UTF-8'); ?>)">
                                        <?php echo htmlspecialchars($yurisprudensi['judul']); ?>
                                        <?php if ($yurisprudensi['tahun_putusan']): ?>
                                            <span class="tahun-badge"><?php echo htmlspecialchars($yurisprudensi['tahun_putusan']); ?></span>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <div class="yurisprudensi-subtitle">
                                        <i class="fa fa-balance-scale"></i> <?php echo htmlspecialchars($yurisprudensi['tipe_dokumen']); ?>
                                    </div>
                                    
                                    <div class="yurisprudensi-meta">
                                        <span>
                                            <i class="fa fa-building"></i> 
                                            <?php echo htmlspecialchars($yurisprudensi['teu_badan']); ?>
                                        </span>
                                        
                                        <span>
                                            <i class="fa fa-hashtag"></i> 
                                            <?php echo htmlspecialchars($yurisprudensi['nomor_putusan']); ?>
                                        </span>
                                        
                                        <?php if ($yurisprudensi['status_putusan']): ?>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $yurisprudensi['status_putusan'])); ?>">
                                                <?php echo htmlspecialchars($yurisprudensi['status_putusan']); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($yurisprudensi['bidang_hukum_jenis_perkara']): ?>
                                            <span style="background: var(--bg); padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                                                <?php echo htmlspecialchars($yurisprudensi['bidang_hukum_jenis_perkara']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($yurisprudensi['deskripsi']): ?>
                                    <div class="yurisprudensi-deskripsi">
                                        <?php 
                                        $deskripsi = strip_tags($yurisprudensi['deskripsi']);
                                        echo strlen($deskripsi) > 150 ? substr($deskripsi, 0, 150) . '...' : $deskripsi;
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="yurisprudensi-meta">
                                    <span>
                                        <i class="fa fa-calendar"></i> 
                                        <?php echo date('d/m/Y', strtotime($yurisprudensi['tanggal_dibacakan'])); ?>
                                    </span>
                                    <span>
                                        <i class="fa fa-map-marker"></i> 
                                        <?php echo htmlspecialchars($yurisprudensi['tempat_peradilan'] ?? '-'); ?>
                                    </span>
                                    <span>
                                        <i class="fa fa-language"></i> 
                                        <?php echo htmlspecialchars($yurisprudensi['bahasa']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-group">
                            <a href="index.php?page=yurisprudensi&action=edit&id=<?php echo $yurisprudensi['id_yurisprudensi']; ?>" 
                               class="btn-edit btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0);" 
                               onclick="confirmDelete(<?php echo $yurisprudensi['id_yurisprudensi']; ?>)" 
                               class="btn-delete btn-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                            <button type="button" 
                                    class="btn-detail btn-sm"
                                    onclick="showDetailYurisprudensi(<?php echo htmlspecialchars(json_encode($yurisprudensi), ENT_QUOTES, 'UTF-8'); ?>)">
                                <i class="fa fa-eye"></i> Lihat Detail
                            </button>
                            
                            <?php if ($yurisprudensi['lampiran_path'] && file_exists($yurisprudensi['lampiran_path'])): ?>
                                <a href="<?php echo $yurisprudensi['lampiran_path']; ?>" 
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

<!-- Modal Detail Yurisprudensi -->
<div id="modalDetailYurisprudensi" class="modal-detail">
    <div class="modal-content-detail">
        <div class="modal-header-detail">
            <h3 class="modal-title-detail" id="detailTitleYurisprudensi"></h3>
            <button class="close-modal" onclick="closeDetailYurisprudensi()">&times;</button>
        </div>
        <div class="modal-body-detail">
            <div class="detail-info-grid">
                <div class="detail-item">
                    <span class="detail-label">Tipe Dokumen</span>
                    <div class="detail-value" id="detailTipeDokumen"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Judul</span>
                    <div class="detail-value" id="detailJudul"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">T.E.U. Badan</span>
                    <div class="detail-value" id="detailTeuBadan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nomor Putusan</span>
                    <div class="detail-value" id="detailNomorPutusan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jenis Peradilan</span>
                    <div class="detail-value" id="detailJenisPeradilan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Singkatan Jenis Peradilan</span>
                    <div class="detail-value" id="detailSingkatanJenisPeradilan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tempat Peradilan</span>
                    <div class="detail-value" id="detailTempatPeradilan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Dibacakan</span>
                    <div class="detail-value" id="detailTanggalDibacakan"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Sumber</span>
                    <div class="detail-value" id="detailSumber"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Subjek</span>
                    <div class="detail-value" id="detailSubjek"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <div class="detail-value" id="detailStatus"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Bahasa</span>
                    <div class="detail-value" id="detailBahasa"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Bidang Hukum / Jenis Perkara</span>
                    <div class="detail-value" id="detailBidangHukum"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Lokasi</span>
                    <div class="detail-value" id="detailLokasi"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kata Kunci</span>
                    <div class="detail-value" id="detailKataKunci"></div>
                </div>
            </div>
            
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span class="detail-label">Deskripsi</span>
                <div class="detail-value deskripsi" id="detailDeskripsi"></div>
            </div>
            
            <div class="modal-files-detail" id="detailFilesYurisprudensi">
                <!-- Cover dan lampiran akan dimasukkan via JavaScript -->
            </div>
            
            <div class="modal-footer-detail">
                <button class="btn btn-default" onclick="closeDetailYurisprudensi()">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <button class="btn btn-primary" id="btnEditDetailYurisprudensi" onclick="editDetailYurisprudensi()">
                    <i class="fa fa-edit"></i> Edit Yurisprudensi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Konfirmasi hapus yurisprudensi
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus yurisprudensi ini?')) {
        window.location.href = 'index.php?page=yurisprudensi&action=hapus&id=' + id;
    }
}

// Variable global untuk menyimpan ID yurisprudensi saat ini
let currentYurisprudensiId = null;

// Fungsi untuk menampilkan detail yurisprudensi
function showDetailYurisprudensi(yurisprudensi) {
    currentYurisprudensiId = yurisprudensi.id_yurisprudensi;
    
    // Set data ke modal
    document.getElementById('detailTitleYurisprudensi').textContent = yurisprudensi.judul;
    document.getElementById('detailTipeDokumen').textContent = yurisprudensi.tipe_dokumen || '-';
    document.getElementById('detailJudul').textContent = yurisprudensi.judul || '-';
    document.getElementById('detailTeuBadan').textContent = yurisprudensi.teu_badan || '-';
    document.getElementById('detailNomorPutusan').textContent = yurisprudensi.nomor_putusan || '-';
    document.getElementById('detailJenisPeradilan').textContent = yurisprudensi.jenis_peradilan || '-';
    document.getElementById('detailSingkatanJenisPeradilan').textContent = yurisprudensi.singkatan_jenis_peradilan || '-';
    document.getElementById('detailTempatPeradilan').textContent = yurisprudensi.tempat_peradilan || '-';
    
    // Format tanggal dibacakan
    if (yurisprudensi.tanggal_dibacakan) {
        const tanggal = new Date(yurisprudensi.tanggal_dibacakan);
        document.getElementById('detailTanggalDibacakan').textContent = tanggal.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } else {
        document.getElementById('detailTanggalDibacakan').textContent = '-';
    }
    
    document.getElementById('detailSumber').textContent = yurisprudensi.sumber || '-';
    document.getElementById('detailSubjek').textContent = yurisprudensi.subjek || '-';
    
    // Set status dengan badge
    if (yurisprudensi.status_putusan) {
        const statusText = yurisprudensi.status_putusan;
        const statusClass = `status-${yurisprudensi.status_putusan.toLowerCase().replace(' ', '-')}`;
        document.getElementById('detailStatus').innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;
    } else {
        document.getElementById('detailStatus').textContent = '-';
    }
    
    document.getElementById('detailBahasa').textContent = yurisprudensi.bahasa || '-';
    document.getElementById('detailBidangHukum').textContent = yurisprudensi.bidang_hukum_jenis_perkara || '-';
    document.getElementById('detailLokasi').textContent = yurisprudensi.lokasi || '-';
    document.getElementById('detailKataKunci').textContent = yurisprudensi.kata_kunci || '-';
    document.getElementById('detailDeskripsi').textContent = yurisprudensi.deskripsi || 'Tidak ada deskripsi';
    
    // Set gambar dan file
    const filesContainer = document.getElementById('detailFilesYurisprudensi');
    filesContainer.innerHTML = '';
    
    // Tambahkan cover jika ada
    if (yurisprudensi.cover_path) {
        const coverDiv = document.createElement('div');
        coverDiv.innerHTML = `
            <img src="${yurisprudensi.cover_path}" alt="Cover Yurisprudensi" class="modal-image-detail">
            <div class="file-caption">Cover</div>
        `;
        filesContainer.appendChild(coverDiv);
    }
    
    // Tambahkan lampiran jika ada
    if (yurisprudensi.lampiran_path) {
        const lampiranDiv = document.createElement('div');
        const ext = yurisprudensi.lampiran_path.split('.').pop().toLowerCase();
        let icon = 'fa-file';
        if (ext === 'pdf') icon = 'fa-file-pdf-o';
        else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word-o';
        else if (ext === 'txt') icon = 'fa-file-text-o';
        else if (ext === 'rtf') icon = 'fa-file-text-o';
        
        lampiranDiv.innerHTML = `
            <div class="file-download-container">
                <div style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; background: var(--bg); border-radius: var(--r); border: 2px dashed var(--b);">
                    <i class="fa ${icon}" style="font-size: 60px; color: var(--c);"></i>
                </div>
                <a href="${yurisprudensi.lampiran_path}" class="file-download-btn" target="_blank">
                    <i class="fa fa-download"></i> Download Lampiran
                </a>
                <div class="file-caption">Lampiran Dokumen</div>
            </div>
        `;
        filesContainer.appendChild(lampiranDiv);
    }
    
    // Jika tidak ada file, tampilkan pesan
    if (!yurisprudensi.cover_path && !yurisprudensi.lampiran_path) {
        filesContainer.innerHTML = '<p style="color:#666; text-align:center; width: 100%;">Tidak ada file tersedia</p>';
    }
    
    // Tampilkan modal
    document.getElementById('modalDetailYurisprudensi').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal detail yurisprudensi
function closeDetailYurisprudensi() {
    document.getElementById('modalDetailYurisprudensi').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk edit dari modal detail
function editDetailYurisprudensi() {
    if (currentYurisprudensiId) {
        window.location.href = `index.php?page=yurisprudensi&action=edit&id=${currentYurisprudensiId}`;
    }
}

// Tutup modal jika klik di luar konten modal
window.onclick = function(event) {
    const modal = document.getElementById('modalDetailYurisprudensi');
    if (event.target === modal) {
        closeDetailYurisprudensi();
    }
}

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailYurisprudensi();
    }
});

// Preview gambar sebelum upload
document.addEventListener('DOMContentLoaded', function() {
    // Preview untuk cover
    const coverInput = document.getElementById('cover');
    if (coverInput) {
        coverInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = coverInput.nextElementSibling.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image';
                        coverInput.nextElementSibling.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Auto-set tanggal hari ini jika form tambah
    if (document.getElementById('tanggal_dibacakan') && !document.getElementById('tanggal_dibacakan').value) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_dibacakan').value = today;
    }
});
</script>