<?php
// galeri.php - Sistem Galeri Foto
session_start();
require_once 'a_db.php';

// Variabel untuk pesan
$message = '';
$message_type = '';

// Handle AJAX request untuk mendapatkan data album (DIPINDAHKAN KE PALING ATAS)
if (isset($_GET['ajax']) && $_GET['ajax'] == 'get_album') {
    header('Content-Type: application/json');
    $album_id_ajax = intval($_GET['id']);
    $sql = "SELECT id, judul, deskripsi, url_gambar, is_cover FROM galeri_foto WHERE id = $album_id_ajax AND tipe = 'album'";
    $result = $koneksi->query($sql);
    if ($result && $result->num_rows > 0) {
        $album = $result->fetch_assoc();
        echo json_encode(['success' => true, 'album' => $album]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Album tidak ditemukan']);
    }
    $koneksi->close();
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // TAMBAH ALBUM BARU DENGAN FOTO
    if (isset($_POST['tambah_album'])) {
        $judul = $koneksi->real_escape_string($_POST['judul']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
        $is_cover = isset($_POST['is_cover']) ? 1 : 0;
        
        // Handle upload gambar cover
        $cover_url = '';
        if (isset($_FILES['cover_url']) && $_FILES['cover_url']['error'] == 0) {
            $upload_dir = 'uploads/galeri/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['cover_url']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['cover_url']['tmp_name'], $target_file)) {
                $cover_url = $target_file;
            }
        }
        
        // Insert album
        $sql_album = "INSERT INTO galeri_foto (tipe, judul, deskripsi, url_gambar, is_cover) 
                     VALUES ('album', '$judul', '$deskripsi', '$cover_url', $is_cover)";
        
        if ($koneksi->query($sql_album)) {
            $album_id = $koneksi->insert_id;
            $message = "Album berhasil ditambahkan!";
            header("Location: ?page=galeri&msg=" . urlencode($message) . "&type=success");
            exit();
            
            // Handle upload foto-foto tambahan (jika ada)
            if (isset($_FILES['foto_tambahan']) && count($_FILES['foto_tambahan']['name']) > 0) {
                $upload_count = 0;
                
                for ($i = 0; $i < count($_FILES['foto_tambahan']['name']); $i++) {
                    if ($_FILES['foto_tambahan']['error'][$i] == 0 && !empty($_FILES['foto_tambahan']['name'][$i])) {
                        $file_name = time() . '_' . $i . '_' . basename($_FILES['foto_tambahan']['name'][$i]);
                        $target_file = $upload_dir . $file_name;
                        
                        if (move_uploaded_file($_FILES['foto_tambahan']['tmp_name'][$i], $target_file)) {
                            $judul_foto = $koneksi->real_escape_string($_POST['judul_foto'][$i] ?? 'Foto ' . ($i+1));
                            $deskripsi_foto = $koneksi->real_escape_string($_POST['deskripsi_foto'][$i] ?? '');
                            
                            $sql_foto = "INSERT INTO galeri_foto (tipe, parent_id, judul, deskripsi, url_gambar, urutan) 
                                       VALUES ('foto', $album_id, '$judul_foto', '$deskripsi_foto', '$target_file', $i+1)";
                            $koneksi->query($sql_foto);
                            $upload_count++;
                        }
                    }
                }
                
                if ($upload_count > 0) {
                    $message .= " $upload_count foto berhasil diupload!";
                }
            }
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // EDIT ALBUM DENGAN FOTO TAMBAHAN
    elseif (isset($_POST['edit_album'])) {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $judul = $koneksi->real_escape_string($_POST['judul']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
        $is_cover = isset($_POST['is_cover']) ? 1 : 0;
        
        // Handle update cover (jika ada file baru)
        if (isset($_FILES['cover_url']) && $_FILES['cover_url']['error'] == 0 && !empty($_FILES['cover_url']['name'])) {
            $upload_dir = 'uploads/galeri/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Hapus cover lama jika ada
            $sql_old = "SELECT url_gambar FROM galeri_foto WHERE id = $id";
            $result_old = $koneksi->query($sql_old);
            if ($row = $result_old->fetch_assoc()) {
                if ($row['url_gambar'] && file_exists($row['url_gambar'])) {
                    unlink($row['url_gambar']);
                }
            }
            
            $file_name = time() . '_cover_' . basename($_FILES['cover_url']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['cover_url']['tmp_name'], $target_file)) {
                $cover_url = $target_file;
                $sql = "UPDATE galeri_foto SET 
                        judul = '$judul', 
                        deskripsi = '$deskripsi', 
                        url_gambar = '$cover_url',
                        is_cover = $is_cover 
                        WHERE id = $id";
            } else {
                $sql = "UPDATE galeri_foto SET 
                        judul = '$judul', 
                        deskripsi = '$deskripsi', 
                        is_cover = $is_cover 
                        WHERE id = $id";
            }
        } else {
            $sql = "UPDATE galeri_foto SET 
                    judul = '$judul', 
                    deskripsi = '$deskripsi', 
                    is_cover = $is_cover 
                    WHERE id = $id";
        }
        
        if ($koneksi->query($sql)) {
            $message = "Album berhasil diupdate!";
            header("Location: ?page=galeri&msg=" . urlencode($message) . "&type=success");
            exit();
            
            // Handle upload foto-foto tambahan (jika ada)
            if (isset($_FILES['foto_tambahan']) && count($_FILES['foto_tambahan']['name']) > 0) {
                $upload_dir = 'uploads/galeri/';
                $upload_count = 0;
                
                // Hitung urutan terakhir
                $sql_last = "SELECT MAX(urutan) as last_urutan FROM galeri_foto WHERE parent_id = $id";
                $result_last = $koneksi->query($sql_last);
                $last = $result_last->fetch_assoc();
                $urutan = $last['last_urutan'] ? $last['last_urutan'] + 1 : 1;
                
                for ($i = 0; $i < count($_FILES['foto_tambahan']['name']); $i++) {
                    if ($_FILES['foto_tambahan']['error'][$i] == 0 && !empty($_FILES['foto_tambahan']['name'][$i])) {
                        $file_name = time() . '_' . $i . '_' . basename($_FILES['foto_tambahan']['name'][$i]);
                        $target_file = $upload_dir . $file_name;
                        
                        if (move_uploaded_file($_FILES['foto_tambahan']['tmp_name'][$i], $target_file)) {
                            $judul_foto = $koneksi->real_escape_string($_POST['judul_foto'][$i] ?? 'Foto ' . $urutan);
                            $deskripsi_foto = $koneksi->real_escape_string($_POST['deskripsi_foto'][$i] ?? '');
                            
                            $sql_foto = "INSERT INTO galeri_foto (tipe, parent_id, judul, deskripsi, url_gambar, urutan) 
                                       VALUES ('foto', $id, '$judul_foto', '$deskripsi_foto', '$target_file', $urutan)";
                            $koneksi->query($sql_foto);
                            $upload_count++;
                            $urutan++;
                        }
                    }
                }
                
                if ($upload_count > 0) {
                    $message .= " $upload_count foto baru berhasil ditambahkan!";
                }
            }
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // TAMBAH FOTO KE ALBUM (jika ingin manual)
    elseif (isset($_POST['tambah_foto'])) {
        $parent_id = intval($_POST['parent_id']);
        $judul = $koneksi->real_escape_string($_POST['judul_foto']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi_foto']);
        
        // Handle upload foto
        $foto_url = '';
        if (isset($_FILES['foto_url']) && $_FILES['foto_url']['error'] == 0) {
            $upload_dir = 'uploads/galeri/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['foto_url']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['foto_url']['tmp_name'], $target_file)) {
                $foto_url = $target_file;
            }
        }
        
        // Cari urutan terakhir
        $sql_urutan = "SELECT MAX(urutan) as max_urutan FROM galeri_foto WHERE parent_id = $parent_id";
        $result_urutan = $koneksi->query($sql_urutan);
        $row_urutan = $result_urutan->fetch_assoc();
        $urutan = $row_urutan['max_urutan'] ? $row_urutan['max_urutan'] + 1 : 1;
        
        $sql = "INSERT INTO galeri_foto (tipe, parent_id, judul, deskripsi, url_gambar, urutan) 
                VALUES ('foto', $parent_id, '$judul', '$deskripsi', '$foto_url', $urutan)";
        
        if ($koneksi->query($sql)) {
            $message = "Foto berhasil ditambahkan!";
            header("Location: ?page=galeri&msg=" . urlencode($message) . "&type=success");
            exit();
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // EDIT FOTO INDIVIDUAL
    elseif (isset($_POST['edit_foto'])) {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $judul = $koneksi->real_escape_string($_POST['judul_foto']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi_foto']);
        $urutan = intval($_POST['urutan_foto']);
        
        $sql = "UPDATE galeri_foto SET 
                judul = '$judul', 
                deskripsi = '$deskripsi',
                urutan = $urutan
                WHERE id = $id";
        
        if ($koneksi->query($sql)) {
            $message = "Foto berhasil diupdate!";
            header("Location: ?page=galeri&msg=" . urlencode($message) . "&type=success");
            exit();
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // HAPUS DATA
    elseif (isset($_POST['hapus'])) {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $album_id_param = isset($_POST['album_id']) ? intval($_POST['album_id']) : 0;
        
        // Hapus file gambar dari server
        $sql_select = "SELECT url_gambar, tipe, parent_id FROM galeri_foto WHERE id = $id";
        $result = $koneksi->query($sql_select);
        if ($row = $result->fetch_assoc()) {
            // Hapus file fisik jika ada
            if ($row['url_gambar'] && file_exists($row['url_gambar'])) {
                unlink($row['url_gambar']);
            }
            
            // Jika yang dihapus adalah album, hapus juga semua fotonya
            if ($row['tipe'] == 'album') {
                // Hapus file foto-foto dalam album
                $sql_fotos = "SELECT url_gambar FROM galeri_foto WHERE parent_id = $id";
                $result_fotos = $koneksi->query($sql_fotos);
                while ($foto = $result_fotos->fetch_assoc()) {
                    if ($foto['url_gambar'] && file_exists($foto['url_gambar'])) {
                        unlink($foto['url_gambar']);
                    }
                }
                // Hapus foto-foto dari database
                $koneksi->query("DELETE FROM galeri_foto WHERE parent_id = $id");
            }
        }
        
        // Hapus dari database
        $sql = "DELETE FROM galeri_foto WHERE id = $id";
        if ($koneksi->query($sql)) {
            $message = "Data berhasil dihapus!";
            header("Location: ?page=galeri&msg=" . urlencode($message) . "&type=success");
            exit();
            
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
}

// Ambil parameter untuk menentukan tampilan
$album_id = isset($_GET['album']) ? intval($_GET['album']) : 0;

// Ambil pesan dari URL jika ada
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = isset($_GET['type']) ? $_GET['type'] : 'success';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Galeri Foto - JDIH DPRD Surakarta</title>
    
    <style>
    :root{
        --c:#0ea5a4;
        --d:#0f766e;
        --bg:#f0fdfa;
        --w:#fff;
        --t:#134e4a;
        --b:#d1fae5;
        --r:14px;
        --s:0 8px 24px rgba(0,0,0,.08);
    }

    *{font-family:Inter,sans-serif}
    
    .galeri-container {
        margin-top: 20px;
        padding: 0 20px;
    }
    
    .album-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform 0.3s;
    }
    
    .album-card:hover {
        transform: translateY(-5px);
    }
    
    .album-cover {
        height: 250px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .album-info {
        padding: 20px;
    }
    
    .album-title {
        color: var(--d);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.2em;
    }
    
    .album-desc {
        color: var(--t);
        margin-bottom: 15px;
        font-size: 0.9em;
        line-height: 1.5;
    }
    
    .album-actions {
        display: flex;
        gap: 10px;
    }
    
    .foto-item {
        margin-bottom: 20px;
    }
    
    .foto-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        overflow: hidden;
    }
    
    .foto-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
    }
    
    .foto-info {
        padding: 10px;
        background: var(--w);
    }
    
    .modal-header {
        background: var(--c);
        color: var(--w);
    }
    
    .modal-header .close {
        color: var(--w);
        opacity: 0.8;
    }
    
    .btn-custom {
        background: var(--c);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
    }
    
    .btn-custom:hover {
        background: var(--d);
        color: white;
    }
    
    .btn-danger {
        border-radius: 20px;
    }
    
    .btn-warning {
        border-radius: 20px;
    }
    
    /* Lightbox style */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    
    .lightbox img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
    }
    
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 40px;
        cursor: pointer;
    }
    
    /* Styling untuk form foto dinamis */
    .foto-item-form {
        background: var(--bg);
        border: 1px solid var(--b);
        border-radius: var(--r);
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .btn-add-foto {
        margin-bottom: 20px;
    }
    
    .foto-count {
        background: var(--c);
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 12px;
    }
    
    .current-cover {
        margin-top: 10px;
    }
    
    .current-cover img {
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 5px;
    }
    </style>
</head>
<body>

<div class="galeri-container">
    <!-- Pesan Notifikasi -->
    <?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
    
    <!-- Tombol Aksi -->
    <div class="row mb-3">
        <div class="col-md-12">
            <?php if ($album_id > 0): ?>
                <?php 
                $album_info = $koneksi->query("SELECT judul FROM galeri_foto WHERE id = $album_id");
                $album = $album_info->fetch_assoc();
                ?>
                <a href="?page=galeri" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali ke Album
                </a>
                <button class="btn btn-custom" data-toggle="modal" data-target="#modalTambahFoto">
                    <i class="fa fa-plus"></i> Tambah Foto
                </button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#modalEditAlbum" 
                    onclick="editAlbum(<?php echo $album_id; ?>)">
                    <i class="fa fa-edit"></i> Edit Album
                </button>
                <h3 style="display: inline-block; margin-left: 20px; color: var(--d);">
                    <?php echo htmlspecialchars($album['judul']); ?>
                </h3>
            <?php else: ?>
                <button class="btn btn-custom" data-toggle="modal" data-target="#modalTambahAlbum">
                    <i class="fa fa-plus"></i> Tambah Album Baru
                </button>
                <h2 style="display: inline-block; margin-left: 20px; color: var(--d);">
                    Galeri Foto
                </h2>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Tampilan Galeri -->
    <div class="row">
        <?php if ($album_id == 0): ?>
            <!-- TAMPILAN ALBUM -->
            <?php
            $sql_albums = "SELECT * FROM galeri_foto WHERE tipe = 'album' ORDER BY is_cover DESC, tanggal_dibuat DESC";
            $result_albums = $koneksi->query($sql_albums);
            
            if ($result_albums->num_rows > 0):
                while($album = $result_albums->fetch_assoc()):
                    // Hitung jumlah foto dalam album
                    $album_id_album = $album['id'];
                    $sql_count = "SELECT COUNT(*) as total FROM galeri_foto WHERE parent_id = $album_id_album";
                    $result_count = $koneksi->query($sql_count);
                    $count = $result_count->fetch_assoc();
            ?>
            <div class="col-md-4">
                <div class="album-card">
                    <div class="album-cover" style="background-image: url('<?php echo $album['url_gambar'] ?: 'img/default-cover.jpg'; ?>')">
                        <?php if ($album['is_cover']): ?>
                        <span style="position: absolute; top: 10px; right: 10px; background: var(--c); color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px;">
                            Cover Utama
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="album-info">
                        <h3 class="album-title"><?php echo htmlspecialchars($album['judul']); ?></h3>
                        <p class="album-desc"><?php echo htmlspecialchars(substr($album['deskripsi'], 0, 100) . (strlen($album['deskripsi']) > 100 ? '...' : '')); ?></p>
                        <p><small class="text-muted">
                            <i class="fa fa-images"></i> <?php echo $count['total']; ?> foto
                        </small></p>
                        <div class="album-actions">
                            <a href="?page=galeri&album=<?php echo $album['id']; ?>" class="btn btn-sm btn-custom">
                                <i class="fa fa-eye"></i> Lihat
                            </a>
                            <button class="btn btn-sm btn-warning" onclick="editAlbum(<?php echo $album['id']; ?>)">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus album ini beserta semua fotonya?')">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id" value="<?php echo $album['id']; ?>">
                                <input type="hidden" name="album_id" value="0">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Belum ada album. Silakan tambahkan album baru.
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- TAMPILAN FOTO DALAM ALBUM -->
            <?php
            $sql_fotos = "SELECT * FROM galeri_foto WHERE parent_id = $album_id ORDER BY urutan";
            $result_fotos = $koneksi->query($sql_fotos);
            
            if ($result_fotos->num_rows > 0):
                while($foto = $result_fotos->fetch_assoc()):
            ?>
            <div class="col-md-4 foto-item">
                <div class="foto-card">
                    <img src="<?php echo $foto['url_gambar']; ?>" 
                         class="foto-img" 
                         onclick="openLightbox('<?php echo $foto['url_gambar']; ?>')"
                         style="cursor: pointer;">
                    <div class="foto-info">
                        <h5><?php echo htmlspecialchars($foto['judul']); ?></h5>
                        <p><small><?php echo htmlspecialchars($foto['deskripsi']); ?></small></p>
                        <p><small class="text-muted">Urutan: <?php echo $foto['urutan']; ?></small></p>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning" onclick="editFoto(<?php echo $foto['id']; ?>, '<?php echo htmlspecialchars(addslashes($foto['judul'])); ?>', '<?php echo htmlspecialchars(addslashes($foto['deskripsi'])); ?>', <?php echo $foto['urutan']; ?>)">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus foto ini?')">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id" value="<?php echo $foto['id']; ?>">
                                <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Belum ada foto dalam album ini. Tambahkan foto untuk album ini.
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox untuk gambar -->
<div class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightbox-img" src="">
</div>

<!-- Modal Tambah Album dengan Foto -->
<div class="modal fade" id="modalTambahAlbum" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Tambah Album Baru dengan Foto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formTambahAlbum">
                <div class="modal-body">
                    <h5><span class="foto-count">1</span> Informasi Album</h5>
                    <div class="form-group">
                        <label>Judul Album *</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Album</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Cover Album *</label>
                        <input type="file" name="cover_url" class="form-control" accept="image/*" required>
                        <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF</small>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" name="is_cover" class="form-check-input" id="isCover">
                        <label class="form-check-label" for="isCover">Jadikan sebagai album utama (cover)</label>
                    </div>
                    
                    <hr>
                    
                    <h5><span class="foto-count">2</span> Foto Tambahan</h5>
                    <div id="foto-tambahan-container">
                        <!-- Foto tambahan akan ditambahkan di sini -->
                    </div>
                    
                    <button type="button" class="btn btn-info btn-add-foto" onclick="tambahFieldFoto()">
                        <i class="fa fa-plus"></i> Tambah Field Foto
                    </button>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fa fa-info-circle"></i> Anda bisa menambahkan foto sekarang atau nanti setelah album dibuat.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_album" class="btn btn-custom">Simpan Album & Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Album dengan Foto Tambahan -->
<div class="modal fade" id="modalEditAlbum" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Album & Tambah Foto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formEditAlbum">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <h5><span class="foto-count">1</span> Edit Informasi Album</h5>
                    <div class="form-group">
                        <label>Judul Album *</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Album</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ganti Cover Album</label>
                        <input type="file" name="cover_url" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti cover</small>
                        <div id="current-cover" class="current-cover"></div>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" name="is_cover" class="form-check-input" id="edit_isCover">
                        <label class="form-check-label" for="edit_isCover">Jadikan sebagai album utama (cover)</label>
                    </div>
                    
                    <hr>
                    
                    <h5><span class="foto-count">2</span> Tambah Foto Baru</h5>
                    <div id="edit-foto-tambahan-container">
                        <!-- Foto tambahan akan ditambahkan di sini -->
                    </div>
                    
                    <button type="button" class="btn btn-info btn-add-foto" onclick="tambahFieldFotoEdit()">
                        <i class="fa fa-plus"></i> Tambah Field Foto Baru
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_album" class="btn btn-custom">Update Album & Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Foto Manual -->
<div class="modal fade" id="modalTambahFoto" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-image"></i> Tambah Foto ke Album</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="parent_id" value="<?php echo $album_id; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Foto *</label>
                        <input type="text" name="judul_foto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_foto" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>File Foto *</label>
                        <input type="file" name="foto_url" class="form-control" accept="image/*" required>
                        <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_foto" class="btn btn-custom">Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Foto Individual -->
<div class="modal fade" id="modalEditFoto" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Foto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_foto_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Foto *</label>
                        <input type="text" name="judul_foto" id="edit_foto_judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_foto" id="edit_foto_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="urutan_foto" id="edit_foto_urutan" class="form-control" min="1" value="1">
                        <small class="text-muted">Urutan foto dalam album (semakin kecil angka, semakin awal tampil)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_foto" class="btn btn-custom">Update Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="js/jquery.min.js"></script>


<script>
// Counter untuk foto tambahan
let fotoCounter = 0;
let fotoCounterEdit = 0;

// Fungsi untuk menambah field foto di form tambah album
function tambahFieldFoto() {
    fotoCounter++;
    const container = document.getElementById('foto-tambahan-container');
    
    const div = document.createElement('div');
    div.className = 'foto-item-form';
    div.innerHTML = `
        <h6><span class="foto-count">${fotoCounter + 2}</span> Foto ${fotoCounter}</h6>
        <div class="form-group">
            <label>Judul Foto</label>
            <input type="text" name="judul_foto[]" class="form-control" placeholder="Foto ${fotoCounter}">
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi_foto[]" class="form-control" rows="2" placeholder="Deskripsi foto ${fotoCounter}"></textarea>
        </div>
        <div class="form-group">
            <label>File Foto</label>
            <input type="file" name="foto_tambahan[]" class="form-control" accept="image/*">
            <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF</small>
        </div>
        <button type="button" class="btn btn-sm btn-danger" onclick="hapusFieldFoto(this)">Hapus Field</button>
    `;
    
    container.appendChild(div);
}

// Fungsi untuk menambah field foto di form edit album
function tambahFieldFotoEdit() {
    fotoCounterEdit++;
    const container = document.getElementById('edit-foto-tambahan-container');
    
    const div = document.createElement('div');
    div.className = 'foto-item-form';
    div.innerHTML = `
        <h6><span class="foto-count">${fotoCounterEdit + 2}</span> Foto Baru ${fotoCounterEdit}</h6>
        <div class="form-group">
            <label>Judul Foto</label>
            <input type="text" name="judul_foto[]" class="form-control" placeholder="Foto Baru ${fotoCounterEdit}">
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi_foto[]" class="form-control" rows="2" placeholder="Deskripsi foto baru ${fotoCounterEdit}"></textarea>
        </div>
        <div class="form-group">
            <label>File Foto</label>
            <input type="file" name="foto_tambahan[]" class="form-control" accept="image/*">
            <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF</small>
        </div>
        <button type="button" class="btn btn-sm btn-danger" onclick="hapusFieldFotoEdit(this)">Hapus Field</button>
    `;
    
    container.appendChild(div);
}

// Fungsi untuk menghapus field foto
function hapusFieldFoto(button) {
    button.parentElement.remove();
    fotoCounter--;
}

function hapusFieldFotoEdit(button) {
    button.parentElement.remove();
    fotoCounterEdit--;
}

// Fungsi untuk edit album (tanpa file eksternal)
function editAlbum(albumId) {
    console.log('Mengedit album ID:', albumId);
    
    // AJAX untuk mendapatkan data album
    $.ajax({
        url: 'galeri.php?ajax=get_album&id=' + albumId,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Response:', data);
            if (data.success) {
                $('#edit_id').val(data.album.id);
                $('#edit_judul').val(data.album.judul);
                $('#edit_deskripsi').val(data.album.deskripsi);
                $('#edit_isCover').prop('checked', data.album.is_cover == 1);
                
                // Tampilkan cover saat ini
                let coverHtml = '<p><strong>Cover saat ini:</strong></p>';
                if (data.album.url_gambar && data.album.url_gambar !== '') {
                    coverHtml += `<img src="${data.album.url_gambar}" style="max-width: 100px; max-height: 100px; border-radius: 5px;">`;
                    coverHtml += `<p><small>${data.album.url_gambar}</small></p>`;
                } else {
                    coverHtml += '<p><em>Tidak ada cover</em></p>';
                }
                $('#current-cover').html(coverHtml);
                
                // Reset container foto tambahan
                $('#edit-foto-tambahan-container').empty();
                fotoCounterEdit = 0;
                
                // Tampilkan modal edit
                $('#modalEditAlbum').modal('show');
            } else {
                alert('Gagal mengambil data album: ' + (data.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            alert('Gagal mengambil data album. Silakan cek console untuk detail error.');
        }
    });
}

// Fungsi untuk edit foto individual
function editFoto(fotoId, judul, deskripsi, urutan) {
    $('#edit_foto_id').val(fotoId);
    $('#edit_foto_judul').val(judul);
    $('#edit_foto_deskripsi').val(deskripsi);
    $('#edit_foto_urutan').val(urutan);
    $('#modalEditFoto').modal('show');
}

// Lightbox functions
function openLightbox(imageSrc) {
    document.getElementById('lightbox-img').src = imageSrc;
    document.querySelector('.lightbox').style.display = 'flex';
}

function closeLightbox() {
    document.querySelector('.lightbox').style.display = 'none';
}

// Close lightbox with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// Reset form saat modal tambah album ditutup
$('#modalTambahAlbum').on('hidden.bs.modal', function () {
    fotoCounter = 0;
    $('#foto-tambahan-container').empty();
    $('#formTambahAlbum')[0].reset();
});

// Reset form saat modal edit album ditutup
$('#modalEditAlbum').on('hidden.bs.modal', function () {
    fotoCounterEdit = 0;
    $('#edit-foto-tambahan-container').empty();
    $('#current-cover').empty();
});
</script>

</body>
</html>
<?php $koneksi->close(); ?>