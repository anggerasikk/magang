<?php
// kontak-kami.php - Admin untuk mengelola pesan kontak

// CEK LOGIN DAN LEVEL
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

// Include koneksi database
require_once 'a_db.php';

// Proses aksi
if (isset($_GET['action'])) {
    $id = intval($_GET['id'] ?? 0);
    
    switch ($_GET['action']) {
        case 'delete':
            $stmt = $koneksi->prepare("DELETE FROM pesan_kontak WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle"></i> Pesan berhasil dihapus!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger">Gagal menghapus pesan: ' . $stmt->error . '</div>';
            }
            $stmt->close();
            break;
            
        case 'mark_read':
            $stmt = $koneksi->prepare("UPDATE pesan_kontak SET status = 'dibaca' WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle"></i> Pesan ditandai sudah dibaca!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
            $stmt->close();
            break;
            
        case 'mark_unread':
            $stmt = $koneksi->prepare("UPDATE pesan_kontak SET status = 'terkirim' WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle"></i> Pesan ditandai belum dibaca!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
            $stmt->close();
            break;
    }
}

// Ambil data pesan dengan filter
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? 'all';

$where_conditions = [];
$params = [];
$types = "";

if (!empty($search)) {
    $where_conditions[] = "(nama LIKE ? OR email LIKE ? OR telepon LIKE ? OR pesan LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= "ssss";
}

if ($status_filter === 'unread') {
    $where_conditions[] = "status = 'terkirim'";
} elseif ($status_filter === 'read') {
    $where_conditions[] = "status = 'dibaca'";
}

$where_clause = empty($where_conditions) ? '' : 'WHERE ' . implode(' AND ', $where_conditions);
$query = "SELECT *, 
          DATE_FORMAT(tanggal_dikirim, '%d/%m/%Y %H:%i') as tanggal_format
          FROM pesan_kontak $where_clause ORDER BY tanggal_dikirim DESC";

$stmt = $koneksi->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hitung statistik
$total_messages = $koneksi->query("SELECT COUNT(*) FROM pesan_kontak")->fetch_row()[0];
$unread_messages = $koneksi->query("SELECT COUNT(*) FROM pesan_kontak WHERE status = 'terkirim'")->fetch_row()[0];
$read_messages = $koneksi->query("SELECT COUNT(*) FROM pesan_kontak WHERE status = 'dibaca'")->fetch_row()[0];
?>

<head><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
    
<!-- CSS untuk halaman admin kontak -->
<style>
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.stat-card {
    background: linear-gradient(135deg, var(--bg) 0%, var(--b) 100%);
    border-radius: var(--r);
    padding: 20px;
    text-align: center;
    border: 1px solid var(--b);
    transition: transform 0.3s;
    box-shadow: var(--s);
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin: 0 auto 10px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--d);
    margin-bottom: 5px;
}

.stat-label {
    color: var(--t);
    font-size: 0.9rem;
}

.badge-status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    margin: 2px;
}

.badge-unread {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.badge-read {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.btn-action {
    padding: 5px 12px;
    border-radius: 5px;
    border: none;
    font-size: 0.8rem;
    margin: 2px;
    transition: all 0.3s;
    cursor: pointer;
}

.btn-view {
    background: var(--c);
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-mark {
    background: #6c757d;
    color: white;
}

.btn-action:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.message-preview {
    max-height: 60px;
    overflow: hidden;
    position: relative;
    font-size: 0.9rem;
    color: #666;
}

.message-preview::after {
    content: '...';
    position: absolute;
    bottom: 0;
    right: 0;
    background: white;
    padding: 0 5px;
}

.filter-box {
    background: #f8fafc;
    border-radius: var(--r);
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #e2e8f0;
    box-shadow: var(--s);
}

.table-actions {
    min-width: 150px;
    white-space: nowrap;
}

.email-preview {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.unread-row {
    background-color: #fff9e6;
    font-weight: 500;
}

.read-row {
    background-color: #f8fff8;
}

/* Modal Styles */
.modal-header-custom {
    background: linear-gradient(135deg, var(--c) 0%, var(--d) 100%);
    color: white;
    border-radius: 10px 10px 0 0;
}

.modal-header-custom .close {
    color: white;
    opacity: 1;
}

.info-item {
    margin-bottom: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.info-item strong {
    color: var(--d);
    display: block;
    margin-bottom: 5px;
}

.message-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid var(--c);
    white-space: pre-wrap;
    max-height: 300px;
    overflow-y: auto;
}
</style>

<div class="row">
    <div class="col-md-12">
        <h3><i class="fa fa-envelope"></i> Manajemen Pesan Kontak</h3>
        <p class="text-muted">Lihat dan kelola pesan yang masuk dari halaman Kontak Kami</p>
        <hr>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fa fa-envelope"></i>
        </div>
        <div class="stat-number"><?php echo $total_messages; ?></div>
        <div class="stat-label">Total Pesan</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fa fa-envelope-open"></i>
        </div>
        <div class="stat-number"><?php echo $unread_messages; ?></div>
        <div class="stat-label">Belum Dibaca</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="stat-number"><?php echo $read_messages; ?></div>
        <div class="stat-label">Sudah Dibaca</div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="filter-box">
    <form method="GET" action="" class="row g-3 align-items-center">
        <input type="hidden" name="page" value="kontak-admin">
        
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" name="search" 
                       placeholder="Cari berdasarkan nama, email, telepon, atau pesan..."
                       value="<?php echo htmlspecialchars($search); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <select class="form-control" name="status" onchange="this.form.submit()">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>Semua Status</option>
                <option value="unread" <?php echo $status_filter == 'unread' ? 'selected' : ''; ?>>Belum Dibaca</option>
                <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Sudah Dibaca</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <a href="?page=kontak-admin" class="btn btn-secondary btn-block">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Tabel Pesan -->
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered dataTables-example" id="messagesTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Pengirim</th>
                        <th>Kontak</th>
                        <th>Subjek</th>
                        <th>Pesan</th>
                        <th width="120">Tanggal</th>
                        <th width="100">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; 
                    while ($row = $result->fetch_assoc()): 
                        $is_unread = $row['status'] == 'terkirim';
                    ?>
                    <tr class="<?php echo $is_unread ? 'unread-row' : 'read-row'; ?>">
                        <td><?php echo $counter++; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                        </td>
                        <td>
                            <div><i class="fa fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                            <?php if (!empty($row['telepon'])): ?>
                            <div><i class="fa fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['telepon']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo htmlspecialchars($row['subjek']); ?></span>
                        </td>
                        <td>
                            <div class="message-preview" title="<?php echo htmlspecialchars($row['pesan']); ?>">
                                <?php echo htmlspecialchars(substr($row['pesan'], 0, 100)); ?>
                            </div>
                        </td>
                        <td>
                            <small><i class="fa fa-calendar"></i> <?php echo $row['tanggal_format']; ?></small>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'terkirim'): ?>
                            <span class="badge-status badge-unread">Belum Dibaca</span>
                            <?php else: ?>
                            <span class="badge-status badge-read">Sudah Dibaca</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <button class="btn-action btn-view"
                                onclick='showDetailPesan(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, "UTF-8"); ?>)'>
                                <i class="fa fa-eye"></i> Lihat
                            </button>


                            
                            <?php if ($row['status'] == 'terkirim'): ?>
                            <a href="?page=kontak-kami&action=mark_read&id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-mark"
                               title="Tandai sudah dibaca">
                                <i class="fa fa-check"></i>
                            </a>
                            <?php else: ?>
                            <a href="?page=kontak-kami&action=mark_unread&id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-mark"
                               title="Tandai belum dibaca">
                                <i class="fa fa-undo"></i>
                            </a>
                            <?php endif; ?>
                            
                            <a href="?page=kontak-kami&action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-delete"
                               onclick="return confirm('Yakin ingin menghapus pesan ini?')"
                               title="Hapus pesan">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if ($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada pesan</h5>
                            <p class="text-muted">Belum ada pesan yang masuk</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal View Pesan Lengkap -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h4 class="modal-title"><i class="fa fa-envelope-open"></i> Detail Pesan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong><i class="fa fa-user mr-2"></i> Nama Lengkap</strong>
                            <div id="view-nama" class="text-primary"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong><i class="fa fa-envelope mr-2"></i> Email</strong>
                            <div id="view-email"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong><i class="fa fa-phone mr-2"></i> Telepon</strong>
                            <div id="view-telepon"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong><i class="fa fa-calendar mr-2"></i> Tanggal Kirim</strong>
                            <div id="view-tanggal"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-item">
                            <strong><i class="fa fa-tag mr-2"></i> Subjek Pesan</strong>
                            <div id="view-subjek"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-item">
                            <strong><i class="fa fa-file-text mr-2"></i> Isi Pesan</strong>
                            <div id="view-pesan" class="message-content mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <a href="#" class="btn btn-primary" id="mark-read-modal-btn">
                    <i class="fa fa-check"></i> Tandai Status
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showDetailPesan(pesan) {
    console.log(pesan); // DEBUG WAJIB ADA ISI

    $('#viewModal').modal('show');

    $('#view-nama').text(pesan.nama || '-');
    $('#view-email').html(`<a href="mailto:${pesan.email}">${pesan.email}</a>`);
    $('#view-telepon').text(pesan.telepon || '-');
    $('#view-subjek').text(pesan.subjek || '-');
    $('#view-tanggal').text(pesan.tanggal_format || '-');
    $('#view-pesan').html((pesan.pesan || '').replace(/\n/g, '<br>'));

    // Tombol status
    let btn = $('#mark-read-modal-btn');
    if (pesan.status === 'terkirim') {
        btn.html('<i class="fa fa-check"></i> Tandai Sudah Dibaca')
           .attr('href', `?page=kontak-kami&action=mark_read&id=${pesan.id}`);
    } else {
        btn.html('<i class="fa fa-undo"></i> Tandai Belum Dibaca')
           .attr('href', `?page=kontak-kami&action=mark_unread&id=${pesan.id}`);
    }
}

</script>