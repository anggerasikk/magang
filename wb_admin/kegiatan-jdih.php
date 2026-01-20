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

// Fungsi untuk upload thumbnail
function uploadThumbnail($file) {
    $targetDir = "uploads/artikel/";
    
    // Buat direktori jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Generate nama file unik
    $fileName = time() . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validasi file gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['success' => false, 'message' => 'File bukan gambar'];
    }
    
    // Batasi ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return ['success' => false, 'message' => 'Ukuran file maksimal 2MB'];
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

// Tambah/Edit Artikel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $koneksi->real_escape_string($_POST['judul'] ?? '');
    $slug = createSlug($judul);
    $konten = $koneksi->real_escape_string($_POST['konten_artikel'] ?? '');
    $nama_penulis = $koneksi->real_escape_string($_POST['nama_penulis'] ?? '');
    $status = $koneksi->real_escape_string($_POST['status'] ?? 'draft');
    $meta_keywords = $koneksi->real_escape_string($_POST['meta_keywords'] ?? '');
    $meta_description = $koneksi->real_escape_string($_POST['meta_description'] ?? '');
    
    // ID user dari session
    $id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
    
    if (($_POST['action'] ?? '') === 'tambah') {
        // Handle upload thumbnail
        $thumbnail_path = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            $upload = uploadThumbnail($_FILES['thumbnail']);
            if ($upload['success']) {
                $thumbnail_path = $koneksi->real_escape_string($upload['path']);
            } else {
                echo "<script>alert('".$upload['message']."');</script>";
            }
        }
        
        // Insert artikel baru
        $sql = "INSERT INTO artikel_kegiatan 
            (judul, slug, thumbnail_path, konten_artikel, nama_penulis, id_user, status, meta_keywords, meta_description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("sssssisss", 
            $judul, $slug, $thumbnail_path, $konten, 
            $nama_penulis, $id_user, $status, $meta_keywords, $meta_description
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Artikel berhasil ditambahkan'); window.location.href='index.php?page=kegiatan-jdih';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan artikel. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
        
    } elseif (($_POST['action'] ?? '') === 'edit' && $id > 0) {
        // Update artikel
        $thumbnail_path = $_POST['thumbnail_existing'] ?? null;
        
        // Jika ada file baru diupload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            $upload = uploadThumbnail($_FILES['thumbnail']);
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
        
        $sql = "UPDATE artikel_kegiatan SET 
            judul = ?, slug = ?, thumbnail_path = ?, konten_artikel = ?, 
            nama_penulis = ?, status = ?, meta_keywords = ?, meta_description = ?,
            updated_at = NOW()
            WHERE id_artikel = ?";
        
        $stmt = $koneksi->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssssssi", 
            $judul, $slug, $thumbnail_path, $konten, 
            $nama_penulis, $status, $meta_keywords, $meta_description, $id
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('Artikel berhasil diperbarui'); window.location.href='index.php?page=kegiatan-jdih';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui artikel. Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Hapus Artikel
if ($action === 'hapus' && $id > 0) {
    // Ambil path thumbnail untuk dihapus
    $sql = "SELECT thumbnail_path FROM artikel_kegiatan WHERE id_artikel = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $artikel = $result->fetch_assoc();
    $stmt->close();
    
    if ($artikel && $artikel['thumbnail_path'] && file_exists($artikel['thumbnail_path'])) {
        unlink($artikel['thumbnail_path']);
    }
    
    $sql = "DELETE FROM artikel_kegiatan WHERE id_artikel = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil dihapus'); window.location.href='index.php?page=kegiatan-jdih';</script>";
    } else {
        echo "<script>alert('Gagal menghapus artikel. Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!-- CSS Tambahan untuk CRUD Artikel -->
<style>
/* Form Artikel */
.form-artikel {
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

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    min-width: 75px;
    text-align: center;
    box-sizing: border-box;
    white-space: nowrap;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-publik {
    background: #d1fae5;
    color: #065f46;
}

.status-arsip {
    background: #e5e7eb;
    color: #374151;
}

/* Artikel Card */
.artikel-card {
    background: var(--w);
    border-radius: var(--r);
    border: 1px solid var(--b);
    padding: 20px;
    margin-bottom: 20px;
    transition: 0.3s;
}

.artikel-card:hover {
    box-shadow: var(--s);
    transform: translateY(-2px);
}

.artikel-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.artikel-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--d);
    margin: 0 0 5px 0;
}

.artikel-meta {
    font-size: 12px;
    color: #666;
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}

.artikel-excerpt {
    color: var(--t);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 15px;
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

/* Preview Thumbnail */
.thumbnail-preview {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: var(--r);
    margin-top: 10px;
    border: 1px solid var(--b);
}

.current-thumbnail {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border-radius: var(--r);
    margin: 10px 0;
    border: 2px solid var(--b);
}
</style>

<div class="container">
    <!-- Button Tambah Artikel -->
    <a href="index.php?page=kegiatan-jdih&action=tambah" class="btn-add">
        <i class="fa fa-plus"></i> Tambah Artikel Baru
    </a>

    <?php if ($action === 'tambah' || ($action === 'edit' && $id > 0)): ?>
        <!-- Form Tambah/Edit Artikel -->
        <?php
        $artikel = null;
        if ($action === 'edit' && $id > 0) {
            $sql = "SELECT * FROM artikel_kegiatan WHERE id_artikel = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $artikel = $result->fetch_assoc();
            $stmt->close();
            
            if (!$artikel) {
                echo "<script>alert('Artikel tidak ditemukan'); window.location.href='index.php?page=kegiatan-jdih';</script>";
            }
        }
        ?>
        
        <div class="form-artikel">
            <h3><?php echo $action === 'tambah' ? 'Tambah Artikel Baru' : 'Edit Artikel'; ?></h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($artikel): ?>
                    <input type="hidden" name="id" value="<?php echo $artikel['id_artikel']; ?>">
                    <input type="hidden" name="thumbnail_existing" value="<?php echo htmlspecialchars($artikel['thumbnail_path'] ?? ''); ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="judul">Judul Artikel *</label>
                    <input type="text" id="judul" name="judul" class="form-control" 
                           value="<?php echo htmlspecialchars($artikel['judul'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="nama_penulis">Nama Penulis *</label>
                    <input type="text" id="nama_penulis" name="nama_penulis" class="form-control" 
                           value="<?php echo htmlspecialchars($artikel['nama_penulis'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="thumbnail">Thumbnail</label>
                    <?php if ($artikel && $artikel['thumbnail_path']): ?>
                        <div>
                            <img src="<?php echo $artikel['thumbnail_path']; ?>" alt="Thumbnail" class="current-thumbnail">
                            <p class="text-muted" style="font-size: 12px;">Upload file baru untuk mengganti thumbnail</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="konten_artikel">Konten Artikel *</label>
                    <textarea id="konten_artikel" name="konten_artikel" class="form-control" 
                              rows="8" required><?php echo htmlspecialchars($artikel['konten_artikel'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="draft" <?php echo ($artikel['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="publik" <?php echo ($artikel['status'] ?? '') === 'publik' ? 'selected' : ''; ?>>Publik</option>
                        <option value="arsip" <?php echo ($artikel['status'] ?? '') === 'arsip' ? 'selected' : ''; ?>>Arsip</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" 
                           value="<?php echo htmlspecialchars($artikel['meta_keywords'] ?? ''); ?>"
                           placeholder="pisahkan dengan koma, contoh: hukum, perda, dprd">
                </div>
                
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" 
                              rows="3"><?php echo htmlspecialchars($artikel['meta_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Artikel
                    </button>
                    <a href="index.php?page=kegiatan-jdih" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    <?php else: ?>
        <!-- Daftar Artikel -->
        <h3>Daftar Artikel Kegiatan</h3>
        
        <?php
        // Ambil data artikel
        $sql = "SELECT * FROM artikel_kegiatan ORDER BY tanggal_publikasi DESC";
        $result = $koneksi->query($sql);
        
        if (!$result) {
            echo "<div class='alert alert-danger'>Error query: " . $koneksi->error . "</div>";
            $artikel_list = [];
        } else {
            $artikel_list = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if (empty($artikel_list)): ?>
            <div class="artikel-card" style="text-align: center; padding: 40px;">
                <i class="fa fa-newspaper-o" style="font-size: 48px; color: var(--b); margin-bottom: 15px;"></i>
                <h4 style="color: var(--t);">Belum ada artikel</h4>
                <p style="color: #666;">Mulai dengan membuat artikel pertama Anda</p>
                <a href="index.php?page=kegiatan-jdih&action=tambah" class="btn-add">
                    <i class="fa fa-plus"></i> Buat Artikel Pertama
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($artikel_list as $artikel): ?>
                <div class="artikel-card">
                    <div class="artikel-header">
                        <div style="flex: 1;">
                            <h4 class="artikel-title">
                                <?php echo htmlspecialchars($artikel['judul']); ?>
                            </h4>
                            <div class="artikel-meta">
                                <span>
                                    <i class="fa fa-user"></i> 
                                    <?php echo htmlspecialchars($artikel['nama_penulis']); ?>
                                </span>
                                <span>
                                    <i class="fa fa-calendar"></i> 
                                    <?php echo date('d/m/Y', strtotime($artikel['tanggal_publikasi'])); ?>
                                </span>
                                <span>
                                    <i class="fa fa-eye"></i> 
                                    <?php echo number_format($artikel['views']); ?> views
                                </span>
                            </div>
                        </div>
                        <span class="status-badge status-<?php echo $artikel['status']; ?>">
                            <?php echo ucfirst($artikel['status']); ?>
                        </span>
                    </div>
                    
                    <?php if ($artikel['thumbnail_path'] && file_exists($artikel['thumbnail_path'])): ?>
                        <img src="<?php echo $artikel['thumbnail_path']; ?>" 
                             alt="Thumbnail" 
                             style="max-width: 200px; max-height: 120px; object-fit: cover; border-radius: var(--r); margin-bottom: 10px;">
                    <?php endif; ?>
                    
                    <div class="artikel-excerpt">
                        <?php 
                        $excerpt = strip_tags($artikel['konten_artikel']);
                        echo strlen($excerpt) > 200 ? substr($excerpt, 0, 200) . '...' : $excerpt;
                        ?>
                    </div>
                    
                    <div class="btn-group">
                        <a href="index.php?page=kegiatan-jdih&action=edit&id=<?php echo $artikel['id_artikel']; ?>" 
                           class="btn-edit btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="javascript:void(0);" 
                           onclick="confirmDelete(<?php echo $artikel['id_artikel']; ?>)" 
                           class="btn-delete btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
// Konfirmasi hapus artikel
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        window.location.href = 'index.php?page=kegiatan-jdih&action=hapus&id=' + id;
    }
}

// Preview thumbnail sebelum upload
document.addEventListener('DOMContentLoaded', function() {
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Cek apakah sudah ada preview
                    let preview = thumbnailInput.nextElementSibling.querySelector('.thumbnail-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'thumbnail-preview';
                        thumbnailInput.nextElementSibling.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>