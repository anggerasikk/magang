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

// Fungsi untuk upload gambar penghargaan
function uploadGambarPenghargaan($file, $type = 'foto') {
    $targetDir = "uploads/penghargaan/";
    
    // Buat direktori jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Generate nama file unik
    $fileName = time() . '_' . $type . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validasi file gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['success' => false, 'message' => 'File bukan gambar'];
    }
    
    // Batasi ukuran file (max 5MB untuk foto penghargaan)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'Ukuran file maksimal 5MB'];
    }
    
    // Format yang diperbolehkan
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Hanya format JPG, JPEG, PNG, GIF yang diperbolehkan'];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ['success' => true, 'path' => $targetFile];
    } else {
        return ['success' => false, 'message' => 'Gagal upload file'];
    }
}

// Action CRUD
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tambah/Edit Penghargaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penghargaan = $koneksi->real_escape_string($_POST['nama_penghargaan'] ?? '');
    $deskripsi = $koneksi->real_escape_string($_POST['deskripsi'] ?? '');
    $tahun_penghargaan = $koneksi->real_escape_string($_POST['tahun_penghargaan'] ?? '');
    $penerima_penghargaan = $koneksi->real_escape_string($_POST['penerima_penghargaan'] ?? '');
    $instansi_pemberi = $koneksi->real_escape_string($_POST['instansi_pemberi'] ?? '');
    $kategori_penghargaan = $koneksi->real_escape_string($_POST['kategori_penghargaan'] ?? '');
    $tanggal_diterima = $koneksi->real_escape_string($_POST['tanggal_diterima'] ?? '');
    
    // ID user dari session
    $id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
    
    if (($_POST['action'] ?? '') === 'tambah') {
        // Handle upload foto penghargaan
        $foto_penghargaan_path = null;
        $thumbnail_path = null;
        
        // Upload foto utama
        if (isset($_FILES['foto_penghargaan']) && $_FILES['foto_penghargaan']['error'] === 0) {
            $upload = uploadGambarPenghargaan($_FILES['foto_penghargaan'], 'foto');
            if ($upload['success']) {
                $foto_penghargaan_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Upload thumbnail (opsional)
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            $upload = uploadGambarPenghargaan($_FILES['thumbnail'], 'thumbnail');
            if ($upload['success']) {
                $thumbnail_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('Thumbnail: ".$upload['message']."');</script>";
            }
        }
        
        // Insert penghargaan baru
        $sql = "INSERT INTO penghargaan 
            (nama_penghargaan, deskripsi, foto_penghargaan_path, thumbnail_path, 
             tahun_penghargaan, penerima_penghargaan, instansi_pemberi, 
             kategori_penghargaan, tanggal_diterima, id_user) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("sssssssssi", 
            $nama_penghargaan, $deskripsi, $foto_penghargaan_path, $thumbnail_path,
            $tahun_penghargaan, $penerima_penghargaan, $instansi_pemberi,
            $kategori_penghargaan, $tanggal_diterima, $id_user
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Penghargaan berhasil ditambahkan'); window.location.href='index.php?page=penghargaan';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan penghargaan. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
        
    } elseif (($_POST['action'] ?? '') === 'edit' && $id > 0) {
        // Update penghargaan
        $foto_penghargaan_path = $_POST['foto_existing'] ?? null;
        $thumbnail_path = $_POST['thumbnail_existing'] ?? null;
        
        // Jika ada file foto baru diupload
        if (isset($_FILES['foto_penghargaan']) && $_FILES['foto_penghargaan']['error'] === 0) {
            $upload = uploadGambarPenghargaan($_FILES['foto_penghargaan'], 'foto');
            if ($upload['success']) {
                // Hapus foto lama jika ada
                if ($foto_penghargaan_path && file_exists($foto_penghargaan_path)) {
                    unlink($foto_penghargaan_path);
                }
                $foto_penghargaan_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $foto_penghargaan_path = $koneksi->real_escape_string($foto_penghargaan_path);
        }
        
        // Jika ada file thumbnail baru diupload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            $upload = uploadGambarPenghargaan($_FILES['thumbnail'], 'thumbnail');
            if ($upload['success']) {
                // Hapus thumbnail lama jika ada
                if ($thumbnail_path && file_exists($thumbnail_path)) {
                    unlink($thumbnail_path);
                }
                $thumbnail_path = $koneksi->real_escape_string($upload['path']);
            }
        } else {
            $thumbnail_path = $koneksi->real_escape_string($thumbnail_path);
        }
        
        $sql = "UPDATE penghargaan SET 
            nama_penghargaan = ?, deskripsi = ?, foto_penghargaan_path = ?, thumbnail_path = ?,
            tahun_penghargaan = ?, penerima_penghargaan = ?, instansi_pemberi = ?,
            kategori_penghargaan = ?, tanggal_diterima = ?,
            updated_at = NOW()
            WHERE id_penghargaan = ?";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("sssssssssi", 
            $nama_penghargaan, $deskripsi, $foto_penghargaan_path, $thumbnail_path,
            $tahun_penghargaan, $penerima_penghargaan, $instansi_pemberi,
            $kategori_penghargaan, $tanggal_diterima, $id
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Penghargaan berhasil diperbarui'); window.location.href='index.php?page=penghargaan';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui penghargaan. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Hapus Penghargaan
if ($action === 'hapus' && $id > 0) {
    // Ambil path foto dan thumbnail untuk dihapus
    $sql = "SELECT foto_penghargaan_path, thumbnail_path FROM penghargaan WHERE id_penghargaan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $penghargaan = $result->fetch_assoc();
    $stmt->close();
    
    // Hapus file foto jika ada
    if ($penghargaan && $penghargaan['foto_penghargaan_path'] && file_exists($penghargaan['foto_penghargaan_path'])) {
        unlink($penghargaan['foto_penghargaan_path']);
    }
    
    // Hapus file thumbnail jika ada
    if ($penghargaan && $penghargaan['thumbnail_path'] && file_exists($penghargaan['thumbnail_path'])) {
        unlink($penghargaan['thumbnail_path']);
    }
    
    $sql = "DELETE FROM penghargaan WHERE id_penghargaan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Penghargaan berhasil dihapus'); window.location.href='index.php?page=penghargaan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus penghargaan. Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!-- CSS Tambahan untuk CRUD Penghargaan -->
<style>
/* Form Penghargaan */
.form-penghargaan {
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

/* Kategori Badge */
.kategori-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    min-width: 80px;
    text-align: center;
    box-sizing: border-box;
    white-space: nowrap;
}

.kategori-prestasi {
    background: #dbeafe;
    color: #1e40af;
}

.kategori-penghargaan {
    background: #fef3c7;
    color: #92400e;
}

.kategori-pengakuan {
    background: #d1fae5;
    color: #065f46;
}

.kategori-lainnya {
    background: #e5e7eb;
    color: #374151;
}

/* Penghargaan Card */
.penghargaan-card {
    background: var(--w);
    border-radius: var(--r);
    border: 1px solid var(--b);
    padding: 20px;
    margin-bottom: 20px;
    transition: 0.3s;
    position: relative;
}

.penghargaan-card:hover {
    box-shadow: var(--s);
    transform: translateY(-2px);
}

.penghargaan-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.penghargaan-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--d);
    margin: 0 0 5px 0;
}

.penghargaan-subtitle {
    font-size: 14px;
    color: var(--c);
    font-weight: 500;
    margin-bottom: 5px;
}

.penghargaan-meta {
    font-size: 12px;
    color: #666;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 10px;
}

.penghargaan-deskripsi {
    color: var(--t);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 15px;
}

/* Gambar Penghargaan */
.penghargaan-images {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.penghargaan-image {
    width: 150px;
    height: 150px;
    border-radius: var(--r);
    object-fit: cover;
    border: 2px solid var(--b);
    transition: 0.3s;
    cursor: pointer;
}

.penghargaan-image:hover {
    transform: scale(1.05);
}

/* Current Images */
.current-images {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.image-container {
    text-align: center;
}

.image-container p {
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
    height: 150px;
    object-fit: cover;
    border-radius: var(--r);
    margin-top: 10px;
    border: 1px solid var(--b);
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
    max-width: 800px;
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

.detail-value.deskripsi {
    background: var(--bg);
    padding: 15px;
    border-radius: var(--r);
    border-left: 4px solid var(--c);
}

.modal-images-detail {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin: 25px 0;
    flex-wrap: wrap;
}

.modal-image-detail {
    max-width: 300px;
    max-height: 300px;
    border-radius: var(--r);
    object-fit: contain;
    border: 3px solid var(--b);
    background: var(--bg);
    padding: 5px;
}

.image-caption {
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
    .penghargaan-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-group {
        flex-wrap: wrap;
    }
    
    .form-penghargaan {
        padding: 15px;
    }
    
    .penghargaan-images {
        justify-content: center;
    }
    
    .penghargaan-image {
        width: 120px;
        height: 120px;
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
}
</style>

<div class="container">
    <!-- Button Tambah Penghargaan -->
    <a href="index.php?page=penghargaan&action=tambah" class="btn-add">
        <i class="fa fa-trophy"></i> Tambah Penghargaan Baru
    </a>

    <?php if ($action === 'tambah' || ($action === 'edit' && $id > 0)): ?>
        <!-- Form Tambah/Edit Penghargaan -->
        <?php
        $penghargaan = null;
        if ($action === 'edit' && $id > 0) {
            $sql = "SELECT * FROM penghargaan WHERE id_penghargaan = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $penghargaan = $result->fetch_assoc();
            $stmt->close();
            
            if (!$penghargaan) {
                echo "<script>alert('Penghargaan tidak ditemukan'); window.location.href='index.php?page=penghargaan';</script>";
            }
        }
        ?>
        
        <div class="form-penghargaan">
            <h3><i class="fa fa-trophy"></i> <?php echo $action === 'tambah' ? 'Tambah Penghargaan Baru' : 'Edit Penghargaan'; ?></h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($penghargaan): ?>
                    <input type="hidden" name="id" value="<?php echo $penghargaan['id_penghargaan']; ?>">
                    <input type="hidden" name="foto_existing" value="<?php echo htmlspecialchars($penghargaan['foto_penghargaan_path'] ?? ''); ?>">
                    <input type="hidden" name="thumbnail_existing" value="<?php echo htmlspecialchars($penghargaan['thumbnail_path'] ?? ''); ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_penghargaan">Nama Penghargaan *</label>
                        <input type="text" id="nama_penghargaan" name="nama_penghargaan" class="form-control" 
                               value="<?php echo htmlspecialchars($penghargaan['nama_penghargaan'] ?? ''); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tahun_penghargaan">Tahun Penghargaan *</label>
                        <input type="number" id="tahun_penghargaan" name="tahun_penghargaan" class="form-control" 
                               min="1900" max="2099" step="1"
                               value="<?php echo htmlspecialchars($penghargaan['tahun_penghargaan'] ?? date('Y')); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="penerima_penghargaan">Penerima Penghargaan</label>
                        <input type="text" id="penerima_penghargaan" name="penerima_penghargaan" class="form-control" 
                               value="<?php echo htmlspecialchars($penghargaan['penerima_penghargaan'] ?? ''); ?>"
                               placeholder="Nama penerima penghargaan">
                    </div>
                    
                    <div class="form-group">
                        <label for="instansi_pemberi">Instansi Pemberi</label>
                        <input type="text" id="instansi_pemberi" name="instansi_pemberi" class="form-control" 
                               value="<?php echo htmlspecialchars($penghargaan['instansi_pemberi'] ?? ''); ?>"
                               placeholder="Instansi yang memberikan penghargaan">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="kategori_penghargaan">Kategori Penghargaan</label>
                        <select id="kategori_penghargaan" name="kategori_penghargaan" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Prestasi" <?php echo ($penghargaan['kategori_penghargaan'] ?? '') === 'Prestasi' ? 'selected' : ''; ?>>Prestasi</option>
                            <option value="Penghargaan" <?php echo ($penghargaan['kategori_penghargaan'] ?? '') === 'Penghargaan' ? 'selected' : ''; ?>>Penghargaan</option>
                            <option value="Pengakuan" <?php echo ($penghargaan['kategori_penghargaan'] ?? '') === 'Pengakuan' ? 'selected' : ''; ?>>Pengakuan</option>
                            <option value="Lainnya" <?php echo ($penghargaan['kategori_penghargaan'] ?? '') === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_diterima">Tanggal Diterima</label>
                        <input type="date" id="tanggal_diterima" name="tanggal_diterima" class="form-control" 
                               value="<?php echo htmlspecialchars($penghargaan['tanggal_diterima'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Penghargaan *</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" 
                              rows="5" required><?php echo htmlspecialchars($penghargaan['deskripsi'] ?? ''); ?></textarea>
                    <small class="text-muted">Deskripsi singkat tentang penghargaan ini</small>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="foto_penghargaan">Foto Penghargaan *</label>
                        <?php if ($penghargaan && $penghargaan['foto_penghargaan_path']): ?>
                            <div class="current-images">
                                <div class="image-container">
                                    <img src="<?php echo $penghargaan['foto_penghargaan_path']; ?>" alt="Foto Penghargaan" class="preview-image">
                                    <p>Foto Saat Ini</p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti foto</p>
                        <?php endif; ?>
                        <input type="file" id="foto_penghargaan" name="foto_penghargaan" class="form-control" accept="image/*" <?php echo !$penghargaan ? 'required' : ''; ?>>
                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 5MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail (Opsional)</label>
                        <?php if ($penghargaan && $penghargaan['thumbnail_path']): ?>
                            <div class="current-images">
                                <div class="image-container">
                                    <img src="<?php echo $penghargaan['thumbnail_path']; ?>" alt="Thumbnail" class="preview-image">
                                    <p>Thumbnail Saat Ini</p>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti thumbnail</p>
                        <?php endif; ?>
                        <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Penghargaan
                    </button>
                    <a href="index.php?page=penghargaan" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    <?php else: ?>
        <!-- Daftar Penghargaan -->
        <h3><i class="fa fa-trophy"></i> Daftar Penghargaan</h3>
        
        <?php
        // Ambil data penghargaan
        $sql = "SELECT * FROM penghargaan ORDER BY tahun_penghargaan DESC, tanggal_diterima DESC";
        $result = $koneksi->query($sql);
        
        if (!$result) {
            echo "<div class='alert alert-danger'>Error query: " . $koneksi->error . "</div>";
            $penghargaan_list = [];
        } else {
            $penghargaan_list = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if (empty($penghargaan_list)): ?>
            <div class="penghargaan-card" style="text-align: center; padding: 40px;">
                <i class="fa fa-trophy" style="font-size: 48px; color: var(--b); margin-bottom: 15px;"></i>
                <h4 style="color: var(--t);">Belum ada penghargaan</h4>
                <p style="color: #666;">Mulai dengan menambahkan penghargaan pertama</p>
                <a href="index.php?page=penghargaan&action=tambah" class="btn-add">
                    <i class="fa fa-plus"></i> Tambah Penghargaan Pertama
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($penghargaan_list as $penghargaan): ?>
                <div class="penghargaan-card" id="penghargaan-<?php echo $penghargaan['id_penghargaan']; ?>">
                    <div class="penghargaan-header">
                        <div style="flex: 1;">
                            <h4 class="penghargaan-title">
                                <?php echo htmlspecialchars($penghargaan['nama_penghargaan']); ?>
                                <span class="tahun-badge"><?php echo htmlspecialchars($penghargaan['tahun_penghargaan']); ?></span>
                            </h4>
                            
                            <?php if ($penghargaan['penerima_penghargaan']): ?>
                                <div class="penghargaan-subtitle">
                                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($penghargaan['penerima_penghargaan']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="penghargaan-meta">
                                <?php if ($penghargaan['instansi_pemberi']): ?>
                                    <span>
                                        <i class="fa fa-building"></i> 
                                        <?php echo htmlspecialchars($penghargaan['instansi_pemberi']); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($penghargaan['tanggal_diterima']): ?>
                                    <span>
                                        <i class="fa fa-calendar"></i> 
                                        <?php echo date('d/m/Y', strtotime($penghargaan['tanggal_diterima'])); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($penghargaan['kategori_penghargaan']): ?>
                                    <span class="kategori-badge kategori-<?php echo strtolower($penghargaan['kategori_penghargaan']); ?>">
                                        <?php echo htmlspecialchars($penghargaan['kategori_penghargaan']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($penghargaan['foto_penghargaan_path'] || $penghargaan['thumbnail_path']): ?>
                        <div class="penghargaan-images">
                            <?php if ($penghargaan['foto_penghargaan_path'] && file_exists($penghargaan['foto_penghargaan_path'])): ?>
                                <img src="<?php echo $penghargaan['foto_penghargaan_path']; ?>" 
                                     alt="Foto Penghargaan" 
                                     class="penghargaan-image"
                                     onclick="showDetail(<?php echo htmlspecialchars(json_encode($penghargaan), ENT_QUOTES, 'UTF-8'); ?>)">
                            <?php endif; ?>
                            
                            <?php if ($penghargaan['thumbnail_path'] && file_exists($penghargaan['thumbnail_path'])): ?>
                                <img src="<?php echo $penghargaan['thumbnail_path']; ?>" 
                                     alt="Thumbnail" 
                                     class="penghargaan-image"
                                     onclick="showDetail(<?php echo htmlspecialchars(json_encode($penghargaan), ENT_QUOTES, 'UTF-8'); ?>)">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="penghargaan-deskripsi">
                        <?php 
                        $deskripsi = strip_tags($penghargaan['deskripsi']);
                        echo strlen($deskripsi) > 200 ? substr($deskripsi, 0, 200) . '...' : $deskripsi;
                        ?>
                    </div>
                    
                    <div class="btn-group">
                        <a href="index.php?page=penghargaan&action=edit&id=<?php echo $penghargaan['id_penghargaan']; ?>" 
                           class="btn-edit btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="javascript:void(0);" 
                           onclick="confirmDelete(<?php echo $penghargaan['id_penghargaan']; ?>)" 
                           class="btn-delete btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </a>
                        <button type="button" 
                                class="btn-detail btn-sm"
                                onclick="showDetail(<?php echo htmlspecialchars(json_encode($penghargaan), ENT_QUOTES, 'UTF-8'); ?>)">
                            <i class="fa fa-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Modal Detail Penghargaan -->
<div id="modalDetail" class="modal-detail">
    <div class="modal-content-detail">
        <div class="modal-header-detail">
            <h3 class="modal-title-detail" id="detailTitle"></h3>
            <button class="close-modal" onclick="closeDetail()">&times;</button>
        </div>
        <div class="modal-body-detail">
            <div class="detail-info-grid">
                <div class="detail-item">
                    <span class="detail-label">Nama Penghargaan</span>
                    <div class="detail-value" id="detailNama"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tahun</span>
                    <div class="detail-value" id="detailTahun"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Penerima</span>
                    <div class="detail-value" id="detailPenerima"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Instansi Pemberi</span>
                    <div class="detail-value" id="detailInstansi"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kategori</span>
                    <div class="detail-value" id="detailKategori"></div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Diterima</span>
                    <div class="detail-value" id="detailTanggal"></div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <span class="detail-label">Deskripsi Lengkap</span>
                    <div class="detail-value deskripsi" id="detailDeskripsi"></div>
                </div>
            </div>
            
            <div class="modal-images-detail" id="detailImages">
                <!-- Gambar akan dimasukkan via JavaScript -->
            </div>
            
            <div class="modal-footer-detail">
                <button class="btn btn-default" onclick="closeDetail()">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <button class="btn btn-primary" id="btnEditDetail" onclick="editDetail()">
                    <i class="fa fa-edit"></i> Edit Penghargaan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Konfirmasi hapus penghargaan
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penghargaan ini?')) {
        window.location.href = 'index.php?page=penghargaan&action=hapus&id=' + id;
    }
}

// Variable global untuk menyimpan ID penghargaan saat ini
let currentPenghargaanId = null;

// Fungsi untuk menampilkan detail penghargaan
function showDetail(penghargaan) {
    currentPenghargaanId = penghargaan.id_penghargaan;
    
    // Set data ke modal
    document.getElementById('detailTitle').textContent = penghargaan.nama_penghargaan;
    document.getElementById('detailNama').textContent = penghargaan.nama_penghargaan;
    document.getElementById('detailTahun').textContent = penghargaan.tahun_penghargaan;
    document.getElementById('detailPenerima').textContent = penghargaan.penerima_penghargaan || '-';
    document.getElementById('detailInstansi').textContent = penghargaan.instansi_pemberi || '-';
    document.getElementById('detailKategori').textContent = penghargaan.kategori_penghargaan || '-';
    
    // Format tanggal
    if (penghargaan.tanggal_diterima) {
        const tanggal = new Date(penghargaan.tanggal_diterima);
        document.getElementById('detailTanggal').textContent = tanggal.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } else {
        document.getElementById('detailTanggal').textContent = '-';
    }
    
    // Set deskripsi
    document.getElementById('detailDeskripsi').textContent = penghargaan.deskripsi || 'Tidak ada deskripsi';
    
    // Set gambar
    const imagesContainer = document.getElementById('detailImages');
    imagesContainer.innerHTML = '';
    
    if (penghargaan.foto_penghargaan_path) {
        const imgDiv = document.createElement('div');
        imgDiv.innerHTML = `
            <img src="${penghargaan.foto_penghargaan_path}" alt="Foto Penghargaan" class="modal-image-detail">
            <div class="image-caption">Foto Penghargaan</div>
        `;
        imagesContainer.appendChild(imgDiv);
    }
    
    if (penghargaan.thumbnail_path) {
        const imgDiv = document.createElement('div');
        imgDiv.innerHTML = `
            <img src="${penghargaan.thumbnail_path}" alt="Thumbnail" class="modal-image-detail">
            <div class="image-caption">Thumbnail</div>
        `;
        imagesContainer.appendChild(imgDiv);
    }
    
    // Jika tidak ada gambar, tampilkan pesan
    if (!penghargaan.foto_penghargaan_path && !penghargaan.thumbnail_path) {
        imagesContainer.innerHTML = '<p style="color:#666; text-align:center;">Tidak ada gambar tersedia</p>';
    }
    
    // Tampilkan modal
    document.getElementById('modalDetail').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal detail
function closeDetail() {
    document.getElementById('modalDetail').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk edit dari modal detail
function editDetail() {
    if (currentPenghargaanId) {
        window.location.href = `index.php?page=penghargaan&action=edit&id=${currentPenghargaanId}`;
    }
}

// Tutup modal jika klik di luar konten modal
window.onclick = function(event) {
    const modal = document.getElementById('modalDetail');
    if (event.target === modal) {
        closeDetail();
    }
}

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetail();
    }
});

// Preview gambar sebelum upload
document.addEventListener('DOMContentLoaded', function() {
    // Preview untuk foto penghargaan
    const fotoInput = document.getElementById('foto_penghargaan');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = fotoInput.nextElementSibling.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image';
                        fotoInput.nextElementSibling.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Preview untuk thumbnail
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = thumbnailInput.nextElementSibling.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image';
                        thumbnailInput.nextElementSibling.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Auto-set tahun saat ini jika form tambah
    if (document.getElementById('tahun_penghargaan') && !document.getElementById('tahun_penghargaan').value) {
        document.getElementById('tahun_penghargaan').value = new Date().getFullYear();
    }
    
    // Auto-set tanggal hari ini jika form tambah
    if (document.getElementById('tanggal_diterima') && !document.getElementById('tanggal_diterima').value) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_diterima').value = today;
    }
});
</script>