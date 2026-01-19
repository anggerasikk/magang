<?php
// ================================================
// ARTIKEL HUKUM CRUD SYSTEM - SINGLE FILE
// ================================================

// PROSES CRUD
// ================================================

// 1. PROSES HAPUS
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil data untuk hapus file
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT file_pdf, thumbnail FROM artikel_hukum WHERE id_artikel = '$id'");
    $data = mysqli_fetch_array($query);
    
    // Hapus file PDF jika ada
    if (!empty($data['file_pdf']) && file_exists($data['file_pdf'])) {
        unlink($data['file_pdf']);
    }
    
    // Hapus thumbnail jika ada
    if (!empty($data['thumbnail']) && file_exists($data['thumbnail'])) {
        unlink($data['thumbnail']);
    }
    
    // Hapus dari database
    mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM artikel_hukum WHERE id_artikel = '$id'");
    
    echo "<script>
        alert('Data artikel hukum berhasil dihapus!');
        window.location.href = '?page=artikel_hukum';
    </script>";
    exit();
}

// 2. PROSES SIMPAN (Tambah Data Baru)
if (isset($_POST['simpan'])) {
    $error = '';
    
    // Ambil data dari form
    $judul = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['judul']);
    $jenis = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['jenis_artikel']);
    $tanggal = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['tanggal']);
    $tahun = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['tahun']);
    $penulis = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['penulis']);
    $jurnal = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['jurnal']);
    $issn = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['issn']);
    $volume = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['volume']);
    $issue = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['issue']);
    $halaman = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['halaman']);
    $bidang_hukum = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['bidang_hukum']);
    $keywords = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['keywords']);
    $abstrak = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['abstrak']);
    $status = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['status']);
    $bahasa = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['bahasa']);
    $id_user = $_SESSION['login'] ?? 'admin';

    // Validasi input wajib
    if (empty($judul) || empty($jenis) || empty($tanggal) || empty($tahun)) {
        $error = 'Field yang bertanda * wajib diisi!';
    }

    // Handle upload file PDF jika tidak ada error
    $file_pdf = '';
    if (empty($error) && isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
        $target_dir = "uploads/artikel_hukum/pdf/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = 'artikel_' . time() . '_' . basename($_FILES['file_pdf']['name']);
        $target_file = $target_dir . $file_name;
        
        // Validasi ukuran file (maks 10MB)
        if ($_FILES['file_pdf']['size'] > 10485760) {
            $error = 'Ukuran file PDF maksimal 10MB!';
        } elseif (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_file)) {
            $file_pdf = $target_file;
        } else {
            $error = 'Gagal mengupload file PDF!';
        }
    }

    // Handle upload thumbnail jika tidak ada error
    $thumbnail = '';
    if (empty($error) && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $target_dir = "uploads/artikel_hukum/images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = 'thumbnail_' . time() . '_' . basename($_FILES['thumbnail']['name']);
        $target_file = $target_dir . $image_name;
        
        // Validasi ukuran file (maks 2MB)
        if ($_FILES['thumbnail']['size'] > 2097152) {
            $error = 'Ukuran thumbnail maksimal 2MB!';
        } elseif (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
            $thumbnail = $target_file;
        } else {
            $error = 'Gagal mengupload thumbnail!';
        }
    }

    // Jika tidak ada error, simpan ke database
    if (empty($error)) {
        $query = "INSERT INTO artikel_hukum (
            judul, jenis_artikel, tanggal_terbit, tahun_terbit, 
            penulis, jurnal, issn, volume, issue, halaman, 
            bidang_hukum, keywords, abstrak, status, bahasa, 
            file_pdf, thumbnail, id_user, created_at
        ) VALUES (
            '$judul', '$jenis', '$tanggal', '$tahun',
            '$penulis', '$jurnal', '$issn', '$volume', '$issue', '$halaman',
            '$bidang_hukum', '$keywords', '$abstrak', '$status', '$bahasa',
            '$file_pdf', '$thumbnail', '$id_user', NOW()
        )";

        if (mysqli_query($GLOBALS["___mysqli_ston"], $query)) {
            echo "<script>
                alert('Data artikel hukum berhasil disimpan!');
                window.location.href = '?page=artikel_hukum';
            </script>";
            exit();
        } else {
            $error = 'Error database: ' . mysqli_error($GLOBALS["___mysqli_ston"]);
        }
    }
    
    // Tampilkan error jika ada
    if (!empty($error)) {
        echo "<script>alert('" . addslashes($error) . "');</script>";
    }
}

// 3. PROSES UPDATE (Edit Data)
if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $error = '';
    
    // Ambil data dari form
    $judul = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['judul']);
    $jenis = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['jenis_artikel']);
    $tanggal = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['tanggal']);
    $tahun = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['tahun']);
    $penulis = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['penulis']);
    $jurnal = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['jurnal']);
    $issn = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['issn']);
    $volume = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['volume']);
    $issue = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['issue']);
    $halaman = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['halaman']);
    $bidang_hukum = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['bidang_hukum']);
    $keywords = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['keywords']);
    $abstrak = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['abstrak']);
    $status = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['status']);
    $bahasa = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['bahasa']);

    // Query update dasar
    $query = "UPDATE artikel_hukum SET 
        judul = '$judul',
        jenis_artikel = '$jenis',
        tanggal_terbit = '$tanggal',
        tahun_terbit = '$tahun',
        penulis = '$penulis',
        jurnal = '$jurnal',
        issn = '$issn',
        volume = '$volume',
        issue = '$issue',
        halaman = '$halaman',
        bidang_hukum = '$bidang_hukum',
        keywords = '$keywords',
        abstrak = '$abstrak',
        status = '$status',
        bahasa = '$bahasa',
        updated_at = NOW()
    WHERE id_artikel = '$id'";

    // Handle update file PDF jika ada
    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
        // Hapus file lama
        $old_file = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT file_pdf FROM artikel_hukum WHERE id_artikel = '$id'"));
        if (!empty($old_file['file_pdf']) && file_exists($old_file['file_pdf'])) {
            unlink($old_file['file_pdf']);
        }
        
        // Upload file baru
        $target_dir = "uploads/artikel_hukum/pdf/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = 'artikel_' . time() . '_' . basename($_FILES['file_pdf']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_file)) {
            // Tambahkan update file_pdf ke query
            $query = "UPDATE artikel_hukum SET 
                judul = '$judul',
                jenis_artikel = '$jenis',
                tanggal_terbit = '$tanggal',
                tahun_terbit = '$tahun',
                penulis = '$penulis',
                jurnal = '$jurnal',
                issn = '$issn',
                volume = '$volume',
                issue = '$issue',
                halaman = '$halaman',
                bidang_hukum = '$bidang_hukum',
                keywords = '$keywords',
                abstrak = '$abstrak',
                status = '$status',
                bahasa = '$bahasa',
                file_pdf = '$target_file',
                updated_at = NOW()
            WHERE id_artikel = '$id'";
        }
    }

    // Handle update thumbnail jika ada
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        // Hapus file lama
        $old_thumb = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT thumbnail FROM artikel_hukum WHERE id_artikel = '$id'"));
        if (!empty($old_thumb['thumbnail']) && file_exists($old_thumb['thumbnail'])) {
            unlink($old_thumb['thumbnail']);
        }
        
        // Upload file baru
        $target_dir = "uploads/artikel_hukum/images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = 'thumbnail_' . time() . '_' . basename($_FILES['thumbnail']['name']);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
            // Jika file_pdf juga diupdate, query sudah diubah di atas
            if (strpos($query, "file_pdf = '$target_file'") !== false) {
                // Ganti query untuk menambahkan thumbnail juga
                $query = "UPDATE artikel_hukum SET 
                    judul = '$judul',
                    jenis_artikel = '$jenis',
                    tanggal_terbit = '$tanggal',
                    tahun_terbit = '$tahun',
                    penulis = '$penulis',
                    jurnal = '$jurnal',
                    issn = '$issn',
                    volume = '$volume',
                    issue = '$issue',
                    halaman = '$halaman',
                    bidang_hukum = '$bidang_hukum',
                    keywords = '$keywords',
                    abstrak = '$abstrak',
                    status = '$status',
                    bahasa = '$bahasa',
                    file_pdf = '" . (isset($target_file_pdf) ? $target_file_pdf : '') . "',
                    thumbnail = '$target_file',
                    updated_at = NOW()
                WHERE id_artikel = '$id'";
            } else {
                // Hanya thumbnail yang diupdate
                $query = "UPDATE artikel_hukum SET 
                    judul = '$judul',
                    jenis_artikel = '$jenis',
                    tanggal_terbit = '$tanggal',
                    tahun_terbit = '$tahun',
                    penulis = '$penulis',
                    jurnal = '$jurnal',
                    issn = '$issn',
                    volume = '$volume',
                    issue = '$issue',
                    halaman = '$halaman',
                    bidang_hukum = '$bidang_hukum',
                    keywords = '$keywords',
                    abstrak = '$abstrak',
                    status = '$status',
                    bahasa = '$bahasa',
                    thumbnail = '$target_file',
                    updated_at = NOW()
                WHERE id_artikel = '$id'";
            }
        }
    }

    if (mysqli_query($GLOBALS["___mysqli_ston"], $query)) {
        echo "<script>
            alert('Data artikel hukum berhasil diupdate!');
            window.location.href = '?page=artikel_hukum';
        </script>";
        exit();
    } else {
        $error = 'Error: ' . mysqli_error($GLOBALS["___mysqli_ston"]);
        echo "<script>alert('" . addslashes($error) . "');</script>";
    }
}

// ================================================
// TAMPILAN HTML
// ================================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// HALAMAN UTAMA (LIST DATA)
if ($aksi == '') {
?>
<!DOCTYPE html>
<html>

<body>
    <div class="container-fluid">
        <h4>DATA ARTIKEL HUKUM</h4>
        
        <div class="panel-heading clearfix">
            <a href="?page=artikel_hukum&aksi=tambah" class="btn btn-primary">
                <i class="fas fa-plus"></i> TAMBAH ARTIKEL
            </a>
            <span class="pull-right">
                <?php
                $count = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) as total FROM artikel_hukum");
                $total = mysqli_fetch_array($count);
                ?>
                <span class="badge badge-info">Total: <?php echo $total['total']; ?> Data</span>
            </span>
        </div>
        
        <div>
            <?php if ($total['total'] > 0): ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50">NO</th>
                            <th>JUDUL</th>
                            <th width="150">JENIS</th>
                            <th width="120">JURNAL</th>
                            <th width="100">TAHUN</th>
                            <th width="100">PENULIS</th>
                            <th width="100">STATUS</th>
                            <th width="180" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM artikel_hukum ORDER BY id_artikel DESC");
                        $no = 1;
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($data['judul']); ?></td>
                            <td>
                                <span class="badge badge-primary"><?php echo $data['jenis_artikel']; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($data['jurnal']); ?></td>
                            <td><?php echo $data['tahun_terbit']; ?></td>
                            <td><?php echo htmlspecialchars($data['penulis']); ?></td>
                            <td>
                                <?php
                                $badge_class = 'badge-warning';
                                if ($data['status'] == 'Published') $badge_class = 'badge-success';
                                if ($data['status'] == 'Unpublished') $badge_class = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $data['status']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="?page=artikel_hukum&aksi=detail&id=<?php echo $data['id_artikel']; ?>" 
                                   class="btn btn-info btn-xs" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?page=artikel_hukum&aksi=edit&id=<?php echo $data['id_artikel']; ?>" 
                                   class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?page=artikel_hukum&aksi=hapus&id=<?php echo $data['id_artikel']; ?>" 
                                   class="btn btn-danger btn-xs" 
                                   onclick="return confirm('Yakin hapus data ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php if (!empty($data['file_pdf'])): ?>
                                <a href="<?php echo $data['file_pdf']; ?>" target="_blank" 
                                   class="btn btn-success btn-xs" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center" style="padding: 40px;">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada data artikel hukum</h4>
                <p class="text-muted">Silakan tambah data artikel hukum pertama Anda</p>
                <a href="?page=artikel_hukum&aksi=tambah" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Tambah Data Pertama
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus data ini?');
    }
    </script>
</body>
</html>
<?php
}

// ================================================
// FORM TAMBAH DATA
// ================================================
elseif ($aksi == 'tambah') {
?>
<div class="container-fluid">
    <h4>FORM TAMBAH ARTIKEL HUKUM</h4>
    
    <div class="panel-heading clearfix">
        <strong>TAMBAH DATA ARTIKEL HUKUM BARU</strong>
        <a href="?page=artikel_hukum" class="btn btn-default pull-right" style="background: #6c757d; color: white;">
            <i class="fas fa-arrow-left"></i> KEMBALI
        </a>
    </div>
    
    <div>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Judul Artikel <span style="color:red">*</span></label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Jenis Artikel <span style="color:red">*</span></label>
                        <select name="jenis_artikel" class="form-control" required>
                            <option value="">- Pilih Jenis -</option>
                            <option value="Artikel Jurnal">Artikel Jurnal</option>
                            <option value="Artikel Konferensi">Artikel Konferensi</option>
                            <option value="Artikel Review">Artikel Review</option>
                            <option value="Artikel Penelitian">Artikel Penelitian</option>
                            <option value="Artikel Populer">Artikel Populer</option>
                            <option value="Kertas Kerja">Kertas Kerja</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Terbit <span style="color:red">*</span></label>
                        <input type="date" name="tanggal" class="form-control" required 
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Tahun Terbit <span style="color:red">*</span></label>
                        <input type="text" name="tahun" class="form-control" required 
                               value="<?php echo date('Y'); ?>" maxlength="4">
                    </div>
                    
                    <div class="form-group">
                        <label>Penulis <span style="color:red">*</span></label>
                        <input type="text" name="penulis" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Jurnal</label>
                        <input type="text" name="jurnal" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>ISSN</label>
                        <input type="text" name="issn" class="form-control">
                    </div>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Volume</label>
                        <input type="text" name="volume" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Issue/Nomor</label>
                        <input type="text" name="issue" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Halaman (contoh: 1-10)</label>
                        <input type="text" name="halaman" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Bidang Hukum</label>
                        <select name="bidang_hukum" class="form-control">
                            <option value="">- Pilih Bidang Hukum -</option>
                            <option value="Hukum Perdata">Hukum Perdata</option>
                            <option value="Hukum Pidana">Hukum Pidana</option>
                            <option value="Hukum Tata Negara">Hukum Tata Negara</option>
                            <option value="Hukum Administrasi Negara">Hukum Administrasi Negara</option>
                            <option value="Hukum Internasional">Hukum Internasional</option>
                            <option value="Hukum Islam">Hukum Islam</option>
                            <option value="Hukum Adat">Hukum Adat</option>
                            <option value="Hukum Bisnis">Hukum Bisnis</option>
                            <option value="Hukum Lingkungan">Hukum Lingkungan</option>
                            <option value="Hukum Kesehatan">Hukum Kesehatan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Keywords (Kata Kunci)</label>
                        <input type="text" name="keywords" class="form-control" placeholder="Pisahkan dengan koma">
                        <small class="text-muted">Contoh: hukum pidana, korupsi, peradilan</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Published" selected>Published</option>
                            <option value="Unpublished">Unpublished</option>
                            <option value="Draft">Draft</option>
                            <option value="Under Review">Under Review</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Bahasa</label>
                        <select name="bahasa" class="form-control">
                            <option value="Indonesia" selected>Indonesia</option>
                            <option value="Inggris">Inggris</option>
                            <option value="Arab">Arab</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>File PDF (optional)</label>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                        <small class="text-muted">Format PDF, maks 10MB</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Thumbnail/Gambar (optional)</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImage(this, 'thumbnail_preview')">
                        <small class="text-muted">Format JPG/PNG, maks 2MB</small>
                        <div id="thumbnail_preview" class="mt-2"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Abstrak/Abstract <span style="color:red">*</span></label>
                <textarea name="abstrak" class="form-control" rows="6" required></textarea>
                <small class="text-muted">Masukkan ringkasan isi artikel (minimal 100 karakter)</small>
            </div>
            
            <div class="text-center" style="margin-top: 20px;">
                <button type="submit" name="simpan" class="btn btn-primary btn-lg" style="padding:10px 20px; margin:5px;">
                    <i class="fas fa-save"></i> SIMPAN DATA
                </button>
                <button type="reset" class="btn btn-default btn-lg" style="padding:10px 20px; margin:5px; background:#f8f9fa; border:1px solid #ddd;">
                    <i class="fas fa-redo"></i> RESET
                </button>
                <a href="?page=artikel_hukum" class="btn btn-danger btn-lg" style="padding:10px 20px; margin:5px;">
                    <i class="fas fa-times"></i> BATAL
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function validateForm() {
    var judul = document.getElementsByName('judul')[0].value;
    var jenis = document.getElementsByName('jenis_artikel')[0].value;
    var tanggal = document.getElementsByName('tanggal')[0].value;
    var tahun = document.getElementsByName('tahun')[0].value;
    var penulis = document.getElementsByName('penulis')[0].value;
    var abstrak = document.getElementsByName('abstrak')[0].value;
    
    if (judul.trim() === '') {
        alert('Judul artikel harus diisi!');
        return false;
    }
    
    if (jenis === '') {
        alert('Jenis artikel harus dipilih!');
        return false;
    }
    
    if (tanggal === '') {
        alert('Tanggal terbit harus diisi!');
        return false;
    }
    
    if (tahun === '' || !/^\d{4}$/.test(tahun)) {
        alert('Tahun terbit harus berupa 4 digit angka!');
        return false;
    }
    
    if (penulis.trim() === '') {
        alert('Penulis harus diisi!');
        return false;
    }
    
    if (abstrak.trim().length < 100) {
        alert('Abstrak minimal 100 karakter!');
        return false;
    }
    
    return true;
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewDiv = document.getElementById(previewId);
            previewDiv.innerHTML = '<img src="' + e.target.result + '" style="max-width:200px; border:1px solid #ddd; padding:5px; border-radius:4px;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var tahunInput = document.querySelector('input[name="tahun"]');
    if (tahunInput) {
        tahunInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 4) {
                this.value = this.value.slice(0,4);
            }
        });
    }
});
</script>
<?php
}

// ================================================
// FORM EDIT DATA
// ================================================
elseif ($aksi == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM artikel_hukum WHERE id_artikel = '$id'");
    $data = mysqli_fetch_array($query);
    
    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?page=artikel_hukum';</script>";
        exit;
    }
?>
<div class="container-fluid">
    <h4>EDIT DATA ARTIKEL HUKUM</h4>
    
    <div class="panel-heading clearfix">
        <strong>EDIT DATA ARTIKEL HUKUM</strong>
        <a href="?page=artikel_hukum" class="btn btn-default pull-right" style="background: #6c757d; color: white;">
            <i class="fas fa-arrow-left"></i> KEMBALI
        </a>
    </div>
    
    <div>
        <form method="POST" enctype="multipart/form-data">
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Judul Artikel <span style="color:red">*</span></label>
                        <input type="text" name="judul" class="form-control" required 
                               value="<?php echo htmlspecialchars($data['judul']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Jenis Artikel <span style="color:red">*</span></label>
                        <select name="jenis_artikel" class="form-control" required>
                            <option value="">- Pilih Jenis -</option>
                            <?php
                            $jenis_list = ['Artikel Jurnal', 'Artikel Konferensi', 'Artikel Review', 
                                         'Artikel Penelitian', 'Artikel Populer', 'Kertas Kerja'];
                            foreach ($jenis_list as $jenis) {
                                $selected = ($data['jenis_artikel'] == $jenis) ? 'selected' : '';
                                echo "<option value='$jenis' $selected>$jenis</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Terbit <span style="color:red">*</span></label>
                        <input type="date" name="tanggal" class="form-control" required 
                               value="<?php echo $data['tanggal_terbit']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Tahun Terbit <span style="color:red">*</span></label>
                        <input type="text" name="tahun" class="form-control" required 
                               value="<?php echo $data['tahun_terbit']; ?>" maxlength="4">
                    </div>
                    
                    <div class="form-group">
                        <label>Penulis <span style="color:red">*</span></label>
                        <input type="text" name="penulis" class="form-control" required 
                               value="<?php echo htmlspecialchars($data['penulis']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Jurnal</label>
                        <input type="text" name="jurnal" class="form-control" 
                               value="<?php echo htmlspecialchars($data['jurnal']); ?>">
                    </div>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>ISSN</label>
                        <input type="text" name="issn" class="form-control" 
                               value="<?php echo htmlspecialchars($data['issn']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Volume</label>
                        <input type="text" name="volume" class="form-control" 
                               value="<?php echo htmlspecialchars($data['volume']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Issue/Nomor</label>
                        <input type="text" name="issue" class="form-control" 
                               value="<?php echo htmlspecialchars($data['issue']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Halaman (contoh: 1-10)</label>
                        <input type="text" name="halaman" class="form-control" 
                               value="<?php echo htmlspecialchars($data['halaman']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Bidang Hukum</label>
                        <select name="bidang_hukum" class="form-control">
                            <option value="">- Pilih Bidang Hukum -</option>
                            <?php
                            $bidang_list = ['Hukum Perdata', 'Hukum Pidana', 'Hukum Tata Negara', 
                                         'Hukum Administrasi Negara', 'Hukum Internasional', 
                                         'Hukum Islam', 'Hukum Adat', 'Hukum Bisnis', 
                                         'Hukum Lingkungan', 'Hukum Kesehatan'];
                            foreach ($bidang_list as $bidang) {
                                $selected = ($data['bidang_hukum'] == $bidang) ? 'selected' : '';
                                echo "<option value='$bidang' $selected>$bidang</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Keywords (Kata Kunci)</label>
                        <input type="text" name="keywords" class="form-control" 
                               value="<?php echo htmlspecialchars($data['keywords']); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <?php
                    $status_list = ['Published', 'Unpublished', 'Draft', 'Under Review'];
                    foreach ($status_list as $status_item) {
                        $selected = ($data['status'] == $status_item) ? 'selected' : '';
                        echo "<option value='$status_item' $selected>$status_item</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Bahasa</label>
                <select name="bahasa" class="form-control">
                    <?php
                    $bahasa_list = ['Indonesia', 'Inggris', 'Arab', 'Lainnya'];
                    foreach ($bahasa_list as $bahasa_item) {
                        $selected = ($data['bahasa'] == $bahasa_item) ? 'selected' : '';
                        echo "<option value='$bahasa_item' $selected>$bahasa_item</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Abstrak/Abstract <span style="color:red">*</span></label>
                <textarea name="abstrak" class="form-control" rows="6" required><?php echo htmlspecialchars($data['abstrak']); ?></textarea>
                <small class="text-muted">Masukkan ringkasan isi artikel (minimal 100 karakter)</small>
            </div>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>File PDF Saat Ini</label>
                        <?php if (!empty($data['file_pdf'])): ?>
                            <p>
                                <a href="<?php echo $data['file_pdf']; ?>" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                            </p>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada file PDF</p>
                        <?php endif; ?>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Thumbnail Saat Ini</label>
                        <?php if (!empty($data['thumbnail'])): ?>
                            <p>
                                <img src="<?php echo $data['thumbnail']; ?>" width="100" style="border:1px solid #ddd; padding:2px; border-radius:4px;">
                                <br>
                                <a href="<?php echo $data['thumbnail']; ?>" target="_blank" class="btn btn-info btn-sm mt-1">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </p>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada thumbnail</p>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImage(this, 'thumbnail_preview_edit')">
                        <div id="thumbnail_preview_edit" class="mt-2"></div>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>
                </div>
            </div>
            
            <div class="text-center" style="margin-top: 20px;">
                <button type="submit" name="update" class="btn btn-primary btn-lg" style="padding:10px 20px; margin:5px;">
                    <i class="fas fa-save"></i> UPDATE DATA
                </button>
                <a href="?page=artikel_hukum" class="btn btn-danger btn-lg" style="padding:10px 20px; margin:5px;">
                    <i class="fas fa-times"></i> BATAL
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewDiv = document.getElementById(previewId);
            previewDiv.innerHTML = '<img src="' + e.target.result + '" style="max-width:200px; border:1px solid #ddd; padding:5px; border-radius:4px;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php
}

// ================================================
// DETAIL DATA
// ================================================
elseif ($aksi == 'detail' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM artikel_hukum WHERE id_artikel = '$id'");
    $data = mysqli_fetch_array($query);
    
    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?page=artikel_hukum';</script>";
        exit;
    }
?>
<div class="container-fluid">
    <h4>DETAIL ARTIKEL HUKUM</h4>
    
    <div class="panel-heading clearfix">
        <strong>DETAIL DATA ARTIKEL HUKUM</strong>
        <a href="?page=artikel_hukum" class="btn btn-default pull-right" style="background: #6c757d; color: white;">
            <i class="fas fa-arrow-left"></i> KEMBALI
        </a>
    </div>
    
    <div>
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div style="flex: 2; min-width: 300px;">
                <h3><?php echo htmlspecialchars($data['judul']); ?></h3>
                <hr>
                
                <table style="width:100%; border-collapse:collapse;">
                    <tr><th width="30%" style="padding:10px; background:#f8f9fa;">Jenis Artikel</th><td style="padding:10px;"><?php echo $data['jenis_artikel']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Tanggal Terbit</th><td style="padding:10px;"><?php echo date('d-m-Y', strtotime($data['tanggal_terbit'])); ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Tahun Terbit</th><td style="padding:10px;"><?php echo $data['tahun_terbit']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Penulis</th><td style="padding:10px;"><?php echo $data['penulis']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Jurnal</th><td style="padding:10px;"><?php echo $data['jurnal']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">ISSN</th><td style="padding:10px;"><?php echo $data['issn']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Volume</th><td style="padding:10px;"><?php echo $data['volume']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Issue/Nomor</th><td style="padding:10px;"><?php echo $data['issue']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Halaman</th><td style="padding:10px;"><?php echo $data['halaman']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Bidang Hukum</th><td style="padding:10px;"><?php echo $data['bidang_hukum']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Keywords</th><td style="padding:10px;"><?php echo $data['keywords']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Status</th>
                        <td style="padding:10px;">
                            <?php
                            $badge_class = 'badge-warning';
                            if ($data['status'] == 'Published') $badge_class = 'badge-success';
                            if ($data['status'] == 'Unpublished') $badge_class = 'badge-danger';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo $data['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Bahasa</th><td style="padding:10px;"><?php echo $data['bahasa']; ?></td></tr>
                    <tr><th style="padding:10px; background:#f8f9fa;">Dibuat Pada</th><td style="padding:10px;"><?php echo date('d-m-Y H:i', strtotime($data['created_at'])); ?></td></tr>
                    <?php if ($data['updated_at'] != '0000-00-00 00:00:00' && !empty($data['updated_at'])): ?>
                    <tr><th style="padding:10px; background:#f8f9fa;">Diupdate Pada</th><td style="padding:10px;"><?php echo date('d-m-Y H:i', strtotime($data['updated_at'])); ?></td></tr>
                    <?php endif; ?>
                </table>
                
                <h4 style="margin-top: 20px;">Abstrak/Abstract</h4>
                <div style="background:#f5f5f5; border:1px solid #e3e3e3; border-radius:4px; padding:15px; white-space: pre-line;">
                    <?php echo htmlspecialchars($data['abstrak']); ?>
                </div>
            </div>
            
            <div style="flex: 1; min-width: 250px;">
                <?php if (!empty($data['thumbnail'])): ?>
                    <div class="text-center">
                        <img src="<?php echo $data['thumbnail']; ?>" style="max-width:100%; border:1px solid #ddd; padding:5px; border-radius:4px;">
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($data['file_pdf'])): ?>
                    <div style="background:#f5f5f5; border:1px solid #e3e3e3; border-radius:4px; padding:15px; margin-top:20px;">
                        <h4>File PDF</h4>
                        <a href="<?php echo $data['file_pdf']; ?>" target="_blank" class="btn btn-success" style="display:block; width:100%; padding:10px; text-align:center;">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                <?php endif; ?>
                
                <div style="background:#f5f5f5; border:1px solid #e3e3e3; border-radius:4px; padding:15px; margin-top:20px;">
                    <h4>Aksi</h4>
                    <a href="?page=artikel_hukum&aksi=edit&id=<?php echo $data['id_artikel']; ?>" 
                       class="btn btn-warning" style="display:block; width:100%; padding:10px; margin-bottom:10px; text-align:center;">
                        <i class="fas fa-edit"></i> Edit Data
                    </a>
                    <a href="?page=artikel_hukum&aksi=hapus&id=<?php echo $data['id_artikel']; ?>" 
                       class="btn btn-danger" style="display:block; width:100%; padding:10px; text-align:center;"
                       onclick="return confirm('Yakin hapus data ini?')">
                        <i class="fas fa-trash"></i> Hapus Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

// ================================================
// JIKA AKSI TIDAK DIKENAL
// ================================================
elseif ($aksi != '') {
    echo "<script>alert('Aksi tidak dikenali!'); window.location='?page=artikel_hukum';</script>";
}
?>

<script>
// Validasi form tambah
function validateForm() {
    var judul = document.getElementsByName('judul')[0].value;
    var jenis = document.getElementsByName('jenis_artikel')[0].value;
    var tanggal = document.getElementsByName('tanggal')[0].value;
    var tahun = document.getElementsByName('tahun')[0].value;
    var penulis = document.getElementsByName('penulis')[0].value;
    var abstrak = document.getElementsByName('abstrak')[0].value;
    
    if (judul.trim() === '') {
        alert('Judul artikel harus diisi!');
        return false;
    }
    
    if (jenis === '') {
        alert('Jenis artikel harus dipilih!');
        return false;
    }
    
    if (tanggal === '') {
        alert('Tanggal terbit harus diisi!');
        return false;
    }
    
    if (tahun === '' || !/^\d{4}$/.test(tahun)) {
        alert('Tahun terbit harus berupa 4 digit angka!');
        return false;
    }
    
    if (penulis.trim() === '') {
        alert('Penulis harus diisi!');
        return false;
    }
    
    if (abstrak.trim().length < 100) {
        alert('Abstrak minimal 100 karakter!');
        return false;
    }
    
    return true;
}

// Preview gambar
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewDiv = document.getElementById(previewId);
            previewDiv.innerHTML = '<img src="' + e.target.result + '" style="max-width:200px; border:1px solid #ddd; padding:5px; border-radius:4px;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Format tahun input
document.addEventListener('DOMContentLoaded', function() {
    var tahunInputs = document.querySelectorAll('input[name="tahun"]');
    tahunInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 4) {
                this.value = this.value.slice(0,4);
            }
        });
    });
});
</script>
<?php

?>