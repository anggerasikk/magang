<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'a_db.php';

// Variabel untuk pesan
$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // TAMBAH VIDEOGRAFIS
    if (isset($_POST['tambah_videografis'])) {
        $judul = $koneksi->real_escape_string($_POST['judul']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
        $link_youtube = $koneksi->real_escape_string($_POST['link_youtube']);
        $status = isset($_POST['status']) ? 'aktif' : 'nonaktif';
        
        // Konversi link YouTube ke format embed
        $embed_link = convertYoutubeToEmbed($link_youtube);
        
        // Generate thumbnail dari YouTube
        $thumbnail = generateYoutubeThumbnail($link_youtube);
        
        $sql = "INSERT INTO grafis (tipe, judul, deskripsi, sumber, thumbnail, status) 
                VALUES ('videografis', '$judul', '$deskripsi', '$embed_link', '$thumbnail', '$status')";
        
        if ($koneksi->query($sql)) {
            $message = "Videografis berhasil ditambahkan!";
            $message_type = "success";
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // TAMBAH INFOGRAFIS
    elseif (isset($_POST['tambah_infografis'])) {
        $judul = $koneksi->real_escape_string($_POST['judul']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
        $status = isset($_POST['status']) ? 'aktif' : 'nonaktif';
        
        // Handle upload file infografis
        $file_path = '';
        $thumbnail = 'img/document-icon.png';
        
        if (isset($_FILES['file_infografis']) && $_FILES['file_infografis']['error'] == 0) {
            $upload_dir = 'uploads/infografis/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Validasi file
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'svg'];
            $file_ext = strtolower(pathinfo($_FILES['file_infografis']['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_types)) {
                $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $_FILES['file_infografis']['name']);
                $target_file = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['file_infografis']['tmp_name'], $target_file)) {
                    $file_path = $target_file;
                    
                    // Generate thumbnail untuk gambar
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $thumbnail_dir = $upload_dir . 'thumbs/';
                        if (!file_exists($thumbnail_dir)) {
                            mkdir($thumbnail_dir, 0777, true);
                        }
                        $thumbnail = generateThumbnail($target_file, $thumbnail_dir . $file_name, 300, 200);
                    }
                }
            } else {
                $message = "Format file tidak didukung. Gunakan JPG, PNG, GIF, PDF, atau SVG.";
                $message_type = "danger";
            }
        }
        
        if ($file_path) {
            $sql = "INSERT INTO grafis (tipe, judul, deskripsi, sumber, thumbnail, status) 
                    VALUES ('infografis', '$judul', '$deskripsi', '$file_path', '$thumbnail', '$status')";
            
            if ($koneksi->query($sql)) {
                $message = "Infografis berhasil ditambahkan!";
                $message_type = "success";
            } else {
                $message = "Error: " . $koneksi->error;
                $message_type = "danger";
            }
        }
    }
    
    // EDIT DATA (ditangani tanpa AJAX)
    elseif (isset($_POST['edit_data'])) {
        $id = intval($_POST['id']);
        $tipe = $koneksi->real_escape_string($_POST['tipe']);
        $judul = $koneksi->real_escape_string($_POST['judul']);
        $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
        $status = isset($_POST['status']) ? 'aktif' : 'nonaktif';
        
        if ($tipe == 'videografis') {
            // Untuk videografis
            $link_youtube = $koneksi->real_escape_string($_POST['link_youtube']);
            $embed_link = convertYoutubeToEmbed($link_youtube);
            $thumbnail = generateYoutubeThumbnail($link_youtube);
            
            $sql = "UPDATE grafis SET 
                    judul = '$judul', 
                    deskripsi = '$deskripsi', 
                    sumber = '$embed_link',
                    thumbnail = '$thumbnail',
                    status = '$status',
                    tanggal_diperbarui = NOW()
                    WHERE id = $id";
        } else {
            // Untuk infografis
            $sql = "UPDATE grafis SET 
                    judul = '$judul', 
                    deskripsi = '$deskripsi', 
                    status = '$status',
                    tanggal_diperbarui = NOW()
                    WHERE id = $id";
            
            // Jika ada file baru diupload
            if (isset($_FILES['file_infografis']) && $_FILES['file_infografis']['error'] == 0) {
                $upload_dir = 'uploads/infografis/';
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'svg'];
                $file_ext = strtolower(pathinfo($_FILES['file_infografis']['name'], PATHINFO_EXTENSION));
                
                if (in_array($file_ext, $allowed_types)) {
                    // Ambil data lama untuk menghapus file
                    $sql_old = "SELECT sumber FROM grafis WHERE id = $id";
                    $result_old = $koneksi->query($sql_old);
                    if (($row_old = $result_old->fetch_assoc()) && $row_old['sumber'] && file_exists($row_old['sumber'])) {
                        
                        // Hapus thumbnail jika ada
                        $old_thumb = dirname($row_old['sumber']) . '/thumbs/' . basename($row_old['sumber']);
                        if (file_exists($old_thumb)) {
                            unlink($old_thumb);
                        }
                    }
                    
                    // Upload file baru
                    $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $_FILES['file_infografis']['name']);
                    $target_file = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['file_infografis']['tmp_name'], $target_file)) {
                        // Generate thumbnail baru untuk gambar
                        $thumbnail = 'img/document-icon.png';
                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $thumbnail_dir = $upload_dir . 'thumbs/';
                            if (!file_exists($thumbnail_dir)) {
                                mkdir($thumbnail_dir, 0777, true);
                            }
                            $thumbnail = generateThumbnail($target_file, $thumbnail_dir . $file_name, 300, 200);
                        }
                        
                        $sql = "UPDATE grafis SET 
                                judul = '$judul', 
                                deskripsi = '$deskripsi', 
                                sumber = '$target_file',
                                thumbnail = '$thumbnail',
                                status = '$status',
                                tanggal_diperbarui = NOW()
                                WHERE id = $id";
                    }
                }
            }
        }
        
        if ($koneksi->query($sql)) {
            $message = "Data berhasil diupdate!";
            $message_type = "success";
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // HAPUS DATA
    elseif (isset($_GET['hapus'])) {
        $id = intval($_GET['hapus']);
        
        // Ambil data untuk menghapus file fisik
        $sql_select = "SELECT tipe, sumber FROM grafis WHERE id = $id";
        $result = $koneksi->query($sql_select);
        if ($row = $result->fetch_assoc()) {
            // Hapus file fisik untuk infografis
            if ($row['tipe'] == 'infografis' && $row['sumber'] && file_exists($row['sumber'])) {
                unlink($row['sumber']);
                
                // Hapus thumbnail jika ada
                $thumb_path = dirname($row['sumber']) . '/thumbs/' . basename($row['sumber']);
                if (file_exists($thumb_path)) {
                    unlink($thumb_path);
                }
            }
        }
        
        $sql = "DELETE FROM grafis WHERE id = $id";
        if ($koneksi->query($sql)) {
            $message = "Data berhasil dihapus!";
            $message_type = "success";
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
    
    // TOGGLE STATUS
    elseif (isset($_GET['toggle_status'])) {
        $id = intval($_GET['toggle_status']);
        
        $sql = "UPDATE grafis SET 
                status = IF(status = 'aktif', 'nonaktif', 'aktif'),
                tanggal_diperbarui = NOW()
                WHERE id = $id";
        
        if ($koneksi->query($sql)) {
            $message = "Status berhasil diubah!";
            $message_type = "success";
        } else {
            $message = "Error: " . $koneksi->error;
            $message_type = "danger";
        }
    }
}

// Fungsi bantuan
function convertYoutubeToEmbed($url) {
    // Extract YouTube ID dari berbagai format URL
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
    }
    
    return $url;
}

function generateYoutubeThumbnail($url) {
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
        }
    }
    
    return 'img/youtube-default.jpg';
}

function generateThumbnail($source, $destination, $width, $height) {
    // Pastikan folder tujuan ada
    $dest_dir = dirname($destination);
    if (!file_exists($dest_dir)) {
        mkdir($dest_dir, 0777, true);
    }
    
    $info = getimagesize($source);
    if (!$info) {
        return $destination;
    }
    
    $mime = $info['mime'];
    
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return $destination;
    }
    
    // Hitung rasio
    $src_width = imagesx($image);
    $src_height = imagesy($image);
    
    if ($src_width == 0 || $src_height == 0) {
        return $destination;
    }
    
    $ratio = min($width/$src_width, $height/$src_height);
    $new_width = round($src_width * $ratio);
    $new_height = round($src_height * $ratio);
    
    // Buat gambar baru
    $thumb = imagecreatetruecolor($new_width, $new_height);
    
    // Transparansi untuk PNG dan GIF
    if ($mime == 'image/png' || $mime == 'image/gif') {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resize
    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
    
    // Simpan
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($thumb, $destination, 90);
            break;
        case 'image/png':
            imagepng($thumb, $destination, 9);
            break;
        case 'image/gif':
            imagegif($thumb, $destination);
            break;
    }
    
    if (isset($image)) imagedestroy($image);
    if (isset($thumb)) imagedestroy($thumb);
    
    return $destination;
}

// Ambil data untuk edit jika ada parameter edit_id
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql_edit = "SELECT * FROM grafis WHERE id = $edit_id";
    $result_edit = $koneksi->query($sql_edit);
    if ($result_edit->num_rows > 0) {
        $edit_data = $result_edit->fetch_assoc();
    }
}

// Ambil filter tipe
$filter_tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'semua';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Videografis & Infografis - JDIH DPRD Surakarta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
    
    <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="css/theme/default.css" />
    
    <!-- Libraries -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/dataTables.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/tables.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/form.css" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
    
    .grafis-container {
        margin-top: 20px;
    }
    
    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 20px;
        border: 2px solid var(--b);
        background: var(--w);
        color: var(--t);
        border-radius: 20px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: var(--c);
        color: var(--w);
        border-color: var(--c);
        text-decoration: none;
    }
    
    /* Card Styles */
    .grafis-card {
        background: var(--w);
        border-radius: var(--r);
        box-shadow: var(--s);
        margin-bottom: 25px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
    }
    
    .grafis-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,.12);
    }
    
    .grafis-thumbnail {
        height: 200px;
        background-size: cover;
        background-position: center;
        position: relative;
        cursor: pointer;
    }
    
    .video-thumbnail:after {
        content: '\f04b';
        font-family: 'FontAwesome';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--w);
        background: rgba(14, 165, 164, 0.8);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .grafis-info {
        padding: 20px;
    }
    
    .grafis-title {
        color: var(--d);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.1em;
        height: 50px;
        overflow: hidden;
        display: -webkit-box;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .grafis-desc {
        color: var(--t);
        margin-bottom: 15px;
        font-size: 0.9em;
        line-height: 1.5;
        height: 65px;
        overflow: hidden;
        display: -webkit-box;
        line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    
    .grafis-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--b);
    }
    
    .grafis-tipe {
        background: var(--c);
        color: var(--w);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8em;
        text-transform: uppercase;
    }
    
    .grafis-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .modal-header {
        background: var(--c);
        color: var(--w);
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
    
    .status-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 500;
    }
    
    .status-aktif {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-nonaktif {
        background: #fef2f2;
        color: #991b1b;
    }
    
    /* Lightbox/Modal untuk video */
    .video-modal .modal-content {
        background: #000;
    }
    
    .video-modal .modal-header {
        background: #000;
        border-bottom: 1px solid #333;
    }
    
    .video-modal .close {
        color: #fff;
        opacity: 0.8;
    }
    
    .video-modal .close:hover {
        color: #fff;
        opacity: 1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .grafis-card {
            margin-bottom: 15px;
        }
        
        .grafis-thumbnail {
            height: 180px;
        }
        
        .filter-tabs {
            justify-content: center;
        }
        
        .grafis-actions {
            justify-content: center;
        }
    }
    
    .current-file-info {
        background: var(--bg);
        padding: 10px;
        border-radius: var(--r);
        margin-top: 10px;
        border: 1px solid var(--b);
    }
    
    .current-file-info img {
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
        margin-right: 10px;
    }
    </style>
</head>
<body>

<div class="grafis-container">
    <!-- Pesan Notifikasi -->
    <?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <!-- Header dengan Tombol Aksi -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 style="color: var(--d);">
                <i class="fa fa-video-camera"></i> Videografis & 
                <i class="fa fa-image"></i> Infografis
            </h2>
            <p class="text-muted">Kelola konten video dan infografis untuk website JDIH</p>
        </div>
            <div class="col-md-4 text-right" style="background-color: transparent;">
            <button class="btn btn-custom" data-toggle="modal" data-target="#modalTambahVideografis" style="display: inline-block;">
                <i class="fa fa-youtube"></i> Tambah Videografis
            </button>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahInfografis" style="display: inline-block;">
                <i class="fa fa-image"></i> Tambah Infografis
            </button>
        </div>
    </div>
    
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="?page=grafis&tipe=semua" class="filter-btn <?php echo $filter_tipe == 'semua' ? 'active' : ''; ?>">
            <i class="fa fa-list"></i> Semua
        </a>
        <a href="?page=grafis&tipe=videografis" class="filter-btn <?php echo $filter_tipe == 'videografis' ? 'active' : ''; ?>">
            <i class="fa fa-youtube"></i> Videografis
        </a>
        <a href="?page=grafis&tipe=infografis" class="filter-btn <?php echo $filter_tipe == 'infografis' ? 'active' : ''; ?>">
            <i class="fa fa-image"></i> Infografis
        </a>
        <a href="?page=grafis&tipe=aktif" class="filter-btn <?php echo $filter_tipe == 'aktif' ? 'active' : ''; ?>">
            <i class="fa fa-check-circle"></i> Aktif
        </a>
        <a href="?page=grafis&tipe=nonaktif" class="filter-btn <?php echo $filter_tipe == 'nonaktif' ? 'active' : ''; ?>">
            <i class="fa fa-times-circle"></i> Nonaktif
        </a>
    </div>
    
    <!-- Konten Grafis -->
    <div class="row">
        <?php
        // Query berdasarkan filter
        $where = "";
        if ($filter_tipe == 'videografis') {
            $where = "WHERE tipe = 'videografis'";
        } elseif ($filter_tipe == 'infografis') {
            $where = "WHERE tipe = 'infografis'";
        } elseif ($filter_tipe == 'aktif') {
            $where = "WHERE status = 'aktif'";
        } elseif ($filter_tipe == 'nonaktif') {
            $where = "WHERE status = 'nonaktif'";
        }
        
        $sql = "SELECT * FROM grafis $where ORDER BY tanggal_dibuat DESC";
        $result = $koneksi->query($sql);
        
        if ($result->num_rows > 0):
            while($data = $result->fetch_assoc()):
                $is_video = ($data['tipe'] == 'videografis');
                $thumbnail = $data['thumbnail'] ?: ($is_video ? 'img/youtube-default.jpg' : 'img/document-icon.png');
                
                // Extract YouTube ID untuk embed URL
                $embed_url = $data['sumber'];
                if ($is_video) {
                    // Pastikan format embed
                    if (strpos($embed_url, 'youtube.com/embed/') === false) {
                        $embed_url = convertYoutubeToEmbed($embed_url);
                    }
                }
                
                // Buat array data untuk edit
                $data_array = [
                    'id' => $data['id'],
                    'tipe' => $data['tipe'],
                    'judul' => htmlspecialchars($data['judul'], ENT_QUOTES),
                    'deskripsi' => htmlspecialchars($data['deskripsi'], ENT_QUOTES),
                    'sumber' => htmlspecialchars($data['sumber'], ENT_QUOTES),
                    'status' => $data['status']
                ];
        ?>
        <div class="col-md-4 col-sm-6">
            <div class="grafis-card">
                <div class="grafis-thumbnail <?php echo $is_video ? 'video-thumbnail' : ''; ?>" 
                     style="background-image: url('<?php echo $thumbnail; ?>')"
                     data-type="<?php echo $is_video ? 'video' : 'image'; ?>"
                     data-src="<?php echo $is_video ? $embed_url : $data['sumber']; ?>"
                     data-title="<?php echo htmlspecialchars($data['judul']); ?>">
                </div>
                
                <div class="grafis-info">
                    <h4 class="grafis-title" title="<?php echo htmlspecialchars($data['judul']); ?>">
                        <?php echo htmlspecialchars($data['judul']); ?>
                    </h4>
                    
                    <p class="grafis-desc" title="<?php echo htmlspecialchars($data['deskripsi']); ?>">
                        <?php echo $data['deskripsi'] ? htmlspecialchars(substr($data['deskripsi'], 0, 150)) . '...' : 'Tidak ada deskripsi'; ?>
                    </p>
                    
                    <div class="grafis-meta">
                        <span class="grafis-tipe">
                            <?php echo $is_video ? '<i class="fa fa-youtube"></i> Video' : '<i class="fa fa-image"></i> Infografis'; ?>
                        </span>
                        
                        <span class="status-badge status-<?php echo $data['status']; ?>">
                            <?php echo $data['status'] == 'aktif' ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>
                    
                    <div class="grafis-actions">
                        <a href="?page=grafis&edit_id=<?php echo $data['id']; ?>" 
                           class="btn btn-sm btn-warning"
                           data-toggle="modal" 
                           data-target="#modalEditData"
                           data-info='<?php echo htmlspecialchars(json_encode($data_array), ENT_QUOTES); ?>'>
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        
                        <a href="?page=grafis&toggle_status=<?php echo $data['id']; ?>" 
                           class="btn btn-sm btn-info"
                           onclick="return confirm('Ubah status data ini?')">
                            <i class="fa fa-exchange"></i> Status
                        </a>
                        
                        <a href="?page=grafis&hapus=<?php echo $data['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus data ini?')">
                            <i class="fa fa-trash"></i> Hapus
                        </a>
                        
                        <?php if ($is_video): ?>
                        <button class="btn btn-sm btn-primary btn-play-video"
                                data-src="<?php echo $embed_url; ?>"
                                data-title="<?php echo htmlspecialchars($data['judul']); ?>">
                            <i class="fa fa-play"></i> Putar
                        </button>
                        <?php else: ?>
                        <a href="<?php echo $data['sumber']; ?>" 
                           class="btn btn-sm btn-primary" 
                           target="_blank" 
                           download>
                            <i class="fa fa-download"></i> Unduh
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="col-md-12">
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle fa-2x"></i><br>
                <h4>Belum ada data</h4>
                <p>Mulai dengan menambahkan videografis atau infografis baru.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Videografis -->
<div class="modal fade" id="modalTambahVideografis" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-youtube"></i> Tambah Videografis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="formVideografis">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Video *</label>
                        <input type="text" name="judul" class="form-control" required placeholder="Contoh: Tutorial Penggunaan JDIH">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang video"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Link YouTube *</label>
                        <input type="text" name="link_youtube" class="form-control" required 
                               placeholder="Contoh: https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID">
                        <small class="text-muted">Masukkan link YouTube lengkap (watch, youtu.be, atau embed)</small>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="status" class="form-check-input" id="statusVideo" checked>
                            <label class="form-check-label" for="statusVideo">Aktifkan video (tampilkan di website)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_videografis" class="btn btn-custom">Simpan Videografis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Infografis -->
<div class="modal fade" id="modalTambahInfografis" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-image"></i> Tambah Infografis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formInfografis">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Infografis *</label>
                        <input type="text" name="judul" class="form-control" required placeholder="Contoh: Infografis Alur Pembuatan Perda">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang infografis"></textarea>
                    </div>
                    <div class="form-group">
                        <label>File Infografis *</label>
                        <input type="file" name="file_infografis" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.svg" required>
                        <small class="text-muted">Format: JPG, PNG, GIF, PDF, SVG. Maksimal 5MB</small>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="status" class="form-check-input" id="statusInfografis" checked>
                            <label class="form-check-label" for="statusInfografis">Aktifkan infografis (tampilkan di website)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_infografis" class="btn btn-custom">Simpan Infografis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="modalEditData" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formEditData">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="tipe" id="edit_tipe">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul *</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group" id="edit_video_fields">
                        <label>Link YouTube *</label>
                        <input type="text" name="link_youtube" id="edit_link_youtube" class="form-control">
                        <small class="text-muted">Link YouTube lengkap (watch, youtu.be, atau embed)</small>
                    </div>
                    
                    <div class="form-group" id="edit_infografis_fields" style="display: none;">
                        <label>Ganti File Infografis (Opsional)</label>
                        <input type="file" name="file_infografis" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.svg">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Format: JPG, PNG, GIF, PDF, SVG</small>
                        <div id="edit_current_file" class="current-file-info mt-2"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="status" class="form-check-input" id="edit_status">
                            <label class="form-check-label" for="edit_status">Aktif (tampilkan di website)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_data" class="btn btn-custom">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Video Player -->
<div class="modal fade video-modal" id="modalVideoPlayer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="videoTitle">Video Player</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="videoFrame" class="embed-responsive-item" 
                            src="" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Viewer -->
<div class="modal fade" id="modalImageViewer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="imageTitle">Infografis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <img id="imageView" src="" alt="" style="max-width: 100%; max-height: 70vh;">
                <div class="mt-3" id="imageActions"></div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>

<script>
// Fungsi untuk menampilkan video
$(document).on('click', '.btn-play-video, .grafis-thumbnail.video-thumbnail', function() {
    var videoSrc = $(this).data('src');
    var videoTitle = $(this).data('title') || 'Video';
    
    if (!videoSrc) {
        // Jika thumbnail diklik, ambil data dari parent
        videoSrc = $(this).closest('.grafis-card').find('.btn-play-video').data('src');
        videoTitle = $(this).closest('.grafis-card').find('.grafis-title').text();
    }
    
    $('#videoTitle').text(videoTitle);
    $('#videoFrame').attr('src', videoSrc + '?autoplay=1');
    $('#modalVideoPlayer').modal('show');
});

// Fungsi untuk menampilkan gambar
$(document).on('click', '.grafis-thumbnail:not(.video-thumbnail)', function() {
    var imageSrc = $(this).data('src');
    var imageTitle = $(this).data('title') || 'Infografis';
    
    $('#imageTitle').text(imageTitle);
    $('#imageView').attr('src', imageSrc);
    
    // Tambahkan tombol download
    var ext = imageSrc.split('.').pop().toLowerCase();
    var isPdf = ext === 'pdf';
    
    var actionsHtml = '';
    if (isPdf) {
        actionsHtml = `
            <div class="alert alert-info">
                <i class="fa fa-file-pdf-o"></i> Dokumen PDF
            </div>
            <a href="${imageSrc}" class="btn btn-primary" target="_blank" download>
                <i class="fa fa-download"></i> Unduh PDF
            </a>
            <a href="${imageSrc}" class="btn btn-success" target="_blank">
                <i class="fa fa-eye"></i> Lihat di Tab Baru
            </a>
        `;
    } else {
        actionsHtml = `
            <a href="${imageSrc}" class="btn btn-primary" target="_blank" download>
                <i class="fa fa-download"></i> Unduh Gambar
            </a>
        `;
    }
    
    $('#imageActions').html(actionsHtml);
    $('#modalImageViewer').modal('show');
});

// Fungsi untuk mengisi modal edit dengan data
$(document).on('click', 'a[data-target="#modalEditData"]', function(e) {
    e.preventDefault();
    
    var info = $(this).data('info');
    if (!info) {
        // Coba parse dari JSON string
        try {
            info = JSON.parse($(this).attr('data-info'));
        } catch (e) {
            console.error('Error parsing JSON:', e);
            return;
        }
    }
    
    // Isi form edit
    $('#edit_id').val(info.id);
    $('#edit_tipe').val(info.tipe);
    $('#edit_judul').val(info.judul);
    $('#edit_deskripsi').val(info.deskripsi);
    $('#edit_status').prop('checked', info.status === 'aktif');
    
    // Tampilkan field sesuai tipe
    if (info.tipe === 'videografis') {
        $('#edit_video_fields').show();
        $('#edit_infografis_fields').hide();
        
        // Konversi link embed ke link watch untuk ditampilkan
        var embedUrl = info.sumber;
        var watchUrl = convertEmbedToWatch(embedUrl);
        $('#edit_link_youtube').val(watchUrl).prop('required', true);
    } else {
        $('#edit_video_fields').hide();
        $('#edit_infografis_fields').show();
        $('#edit_link_youtube').prop('required', false);
        
        // Tampilkan info file saat ini
        var filePath = info.sumber;
        var fileName = filePath.split('/').pop();
        var fileExt = fileName.split('.').pop().toLowerCase();
        var isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
        var isPdf = fileExt === 'pdf';
        
        var fileHtml = '<p><strong>File saat ini:</strong></p>';
        if (isImage) {
            fileHtml += `<img src="${filePath}" style="max-width: 100px; max-height: 100px; border-radius: 5px; margin-right: 10px;">`;
        }
        fileHtml += `<span>${fileName}</span>`;
        
        if (isPdf) {
            fileHtml += ' <span class="badge badge-danger">PDF</span>';
        } else if (isImage) {
            fileHtml += ' <span class="badge badge-success">Gambar</span>';
        }
        
        $('#edit_current_file').html(fileHtml);
    }
    
    // Tampilkan modal
    $('#modalEditData').modal('show');
});

// Fungsi konversi embed URL ke watch URL
function convertEmbedToWatch(embedUrl) {
    if (!embedUrl) return '';
    
    // Jika sudah format watch, return as is
    if (embedUrl.includes('youtube.com/watch')) {
        return embedUrl;
    }
    
    // Jika format youtu.be
    if (embedUrl.includes('youtu.be/')) {
        var videoId = embedUrl.split('youtu.be/')[1].split('?')[0];
        return 'https://www.youtube.com/watch?v=' + videoId;
    }
    
    // Jika format embed
    if (embedUrl.includes('youtube.com/embed/')) {
        var videoId = embedUrl.split('youtube.com/embed/')[1].split('?')[0];
        return 'https://www.youtube.com/watch?v=' + videoId;
    }
    
    return embedUrl;
}

// Fungsi konversi watch URL ke embed URL
function convertWatchToEmbed(watchUrl) {
    if (!watchUrl) return '';
    
    // Jika sudah format embed, return as is
    if (watchUrl.includes('youtube.com/embed/')) {
        return watchUrl;
    }
    
    // Jika format watch
    if (watchUrl.includes('youtube.com/watch?v=')) {
        var videoId = watchUrl.split('youtube.com/watch?v=')[1].split('&')[0];
        return 'https://www.youtube.com/embed/' + videoId;
    }
    
    // Jika format youtu.be
    if (watchUrl.includes('youtu.be/')) {
        var videoId = watchUrl.split('youtu.be/')[1].split('?')[0];
        return 'https://www.youtube.com/embed/' + videoId;
    }
    
    return watchUrl;
}

// Reset iframe saat modal video ditutup
$('#modalVideoPlayer').on('hidden.bs.modal', function () {
    $('#videoFrame').attr('src', '');
});

// Reset modal edit saat ditutup
$('#modalEditData').on('hidden.bs.modal', function () {
    $('#edit_id').val('');
    $('#edit_tipe').val('');
    $('#edit_judul').val('');
    $('#edit_deskripsi').val('');
    $('#edit_link_youtube').val('');
    $('#edit_status').prop('checked', false);
    $('#edit_current_file').html('');
    $('#edit_video_fields').show();
    $('#edit_infografis_fields').hide();
    
    // Hapus parameter edit_id dari URL tanpa reload
    var url = new URL(window.location);
    url.searchParams.delete('edit_id');
    window.history.replaceState({}, '', url);
});

// Validasi form
$('#formVideografis, #formInfografis, #formEditData').submit(function(e) {
    var valid = true;
    var form = $(this);
    
    // Validasi field required
    form.find('[required]').each(function() {
        if (!$(this).val().trim()) {
            $(this).addClass('is-invalid');
            valid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validasi khusus untuk link YouTube
    var youtubeInput = form.find('input[name="link_youtube"]');
    if (youtubeInput.length && youtubeInput.val()) {
        var youtubeUrl = youtubeInput.val();
        var youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/;
        
        if (!youtubeRegex.test(youtubeUrl)) {
            alert('Format link YouTube tidak valid!');
            youtubeInput.addClass('is-invalid');
            valid = false;
        }
    }
    
    if (!valid) {
        e.preventDefault();
        return false;
    }
});

// Auto-buka modal edit jika ada parameter edit_id di URL
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var editId = urlParams.get('edit_id');
    
    if (editId) {
        // Cari tombol edit dengan data-id yang sesuai
        var editBtn = $('a[href*="edit_id=' + editId + '"]');
        if (editBtn.length) {
            editBtn.click();
        }
    }
});
</script>

</body>
</html>
<?php $koneksi->close(); ?>