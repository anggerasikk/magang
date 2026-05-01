<?php
// grafis.php - VERSI SIMPLE DENGAN SEARCH & TANPA SIDEBAR

// ============================================
// KONFIGURASI KEAMANAN (tersembunyi)
// ============================================

// 1. Error Handling dan Logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_error.log');

function log_security_event($event, $details = '') {
    $log_file = __DIR__ . '/logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $log_entry = "[{$timestamp}] [IP: {$ip}] {$event}";
    if (!empty($details)) {
        $log_entry .= " - Details: {$details}";
    }
    $log_entry .= PHP_EOL;
    
    if (!file_exists(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    log_security_event("PHP_ERROR", "{$errstr} in {$errfile} on line {$errline}");
    return true;
});

set_exception_handler(function($exception) {
    log_security_event("EXCEPTION", $exception->getMessage());
    die("<div style='padding:20px;text-align:center;color:#721c24;background-color:#f8d7da;border:1px solid #f5c6cb;border-radius:5px;margin:20px;'>Terjadi kesalahan sistem. Silakan hubungi administrator.</div>");
});

// 2. Pengamanan Session
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true,
    'use_only_cookies' => true
]);

if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// 3. Validasi dan Sanitasi Input GET
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    
    $input = urldecode($input);
    $input = str_replace(chr(0), '', $input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $input = trim($input);
    
    return $input;
}

function validate_tab_parameter($tab) {
    $allowed_tabs = ['infografis', 'videografis'];
    return in_array($tab, $allowed_tabs);
}

// Proses input GET (tanpa search)
$tab_raw = isset($_GET['tab']) ? $_GET['tab'] : 'infografis';
$page_raw = isset($_GET['page']) ? $_GET['page'] : '';

$tab_raw = sanitize_input($tab_raw);
$page_raw = sanitize_input($page_raw);

if ($page_raw !== 'grafis' && $page_raw !== 'infografis') {
    header('HTTP/1.0 400 Bad Request');
    die("Invalid request");
}

if (!validate_tab_parameter($tab_raw)) {
    $tab_raw = 'infografis';
}

$tab = $tab_raw;

// 4. INCLUDE KONEKSI DATABASE
try {
    require_once 'a_db.php';
    
    if (!$koneksi || !mysqli_ping($koneksi)) {
        throw new Exception("Database connection failed");
    }
    
    mysqli_set_charset($koneksi, 'utf8mb4');
    
} catch (Exception $e) {
    die("<div style='padding:20px;text-align:center;color:#856404;background-color:#fff3cd;border:1px solid #ffeaa7;border-radius:5px;margin:20px;'>System maintenance in progress. Please try again later.</div>");
}

// 5. QUERY DATABASE AMAN (tanpa filter search)
$grafis_data = [];
$total_infografis = 0;
$total_videografis = 0;

try {
    $result = mysqli_query($koneksi, 
        "SELECT * FROM grafis WHERE status = 'aktif' ORDER BY tanggal_dibuat DESC");
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $grafis_data[] = $row;
            
            if ($row['tipe'] == 'videografis') {
                $total_videografis++;
            } else {
                $total_infografis++;
            }
        }
        mysqli_data_seek($result, 0);
    }
    
} catch (Exception $e) {
    $grafis_data = [];
}

// ============================================
// INCLUDE HEADER
// ============================================
$page_title = "Infografis & Videografis - JDIH DPRD Kota Surakarta";
include "header.php";
?>

<style>
<?php 
// Include style utama dari file CSS
include "style-keputusan-dprd.css";
?>

/* STYLE KHUSUS UNTUK HALAMAN GRAFIS */
body {
    background: var(--bg-gray);
}

/* Tabs Section */
.tabs-section {
    background: var(--bg-white);
    padding: 20px;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
}

.nav-tabs {
    border-bottom: 3px solid var(--border-light);
    background: transparent;
    margin-bottom: 0;
}

.nav-tabs .nav-link {
    border: none;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    color: var(--text-medium);
    padding: 12px 30px;
    border-radius: 8px 8px 0 0;
    margin-right: 5px;
    transition: all 0.3s ease;
    font-size: 1rem;
    background: var(--bg-gray);
}

.nav-tabs .nav-link:hover {
    color: var(--primary-main);
    background: var(--bg-white);
}

.nav-tabs .nav-link.active {
    background: var(--bg-white);
    color: var(--primary-main);
    border-bottom: 3px solid var(--primary-main);
}

/* Card untuk grafis */
.grafis-card {
    background: var(--bg-white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 30px;
}

.grafis-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.grafis-image {
    height: 200px;
    position: relative;
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    overflow: hidden;
}

.grafis-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.grafis-card:hover .grafis-image img {
    transform: scale(1.1) rotate(2deg);
}

/* Placeholder untuk grafis */
.grafis-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--bg-white);
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    position: relative;
}

.grafis-placeholder i {
    font-size: 3.5rem;
    margin-bottom: 12px;
    opacity: 0.9;
}

.grafis-placeholder p {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0;
    opacity: 0.95;
}

/* Image overlay badge */
.image-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 2;
}

.image-badge {
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-main);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Video play button */
.video-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    width: 60px;
    height: 60px;
    background: rgba(19, 84, 122, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    transition: all 0.3s;
    cursor: pointer;
    opacity: 0;
}

.grafis-card:hover .video-play-btn {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.video-play-btn:hover {
    background: rgba(19, 84, 122, 1);
    transform: translate(-50%, -50%) scale(1.1);
}

/* Grafis content */
.grafis-content {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.grafis-number {
    color: var(--secondary-main);
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.grafis-title {
    font-weight: 600;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 15px;
    color: var(--text-dark);
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.2em;
}

.grafis-description {
    font-size: 0.9rem;
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

/* Grafis meta */
.grafis-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.grafis-date {
    font-size: 0.85rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}

.grafis-date i {
    color: var(--secondary-main);
}

.grafis-type {
    background: linear-gradient(135deg, #e6fffa, #b2f5ea);
    color: var(--primary-dark);
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.grafis-type.infografis {
    background: linear-gradient(135deg, #e6f7ff, #b3e0ff);
    color: #0066cc;
}

.grafis-type.videografis {
    background: linear-gradient(135deg, #ffe6f2, #ffb3d9);
    color: #cc0066;
}

/* Grafis actions */
.grafis-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.grafis-btn {
    flex: 1;
    padding: 12px 16px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    color: var(--bg-white);
    box-shadow: 0 4px 12px rgba(19, 84, 122, 0.25);
}

.btn-view:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-main));
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(19, 84, 122, 0.35);
    color: var(--bg-white);
}

/* Empty state */
.empty-grafis {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-white);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    margin: 30px 0;
}

.empty-grafis i {
    font-size: 4rem;
    color: var(--secondary-light);
    margin-bottom: 20px;
    opacity: 0.6;
}

.empty-grafis h4 {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-grafis p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Modal styles */
.modal-video {
    width: 100%;
    height: 400px;
    border: none;
    border-radius: 10px;
}

/* Full width untuk konten utama */
.full-width-content {
    width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 10px 15px;
        font-size: 0.9rem;
        width: 100%;
        margin-bottom: 5px;
    }
    
    .grafis-card {
        margin-bottom: 20px;
    }
    
    .grafis-actions {
        flex-direction: column;
    }
    
    .grafis-btn {
        width: 100%;
    }
    
    .modal-video {
        height: 300px;
    }
}

@media (max-width: 576px) {
    .grafis-content {
        padding: 20px;
    }
    
    .modal-video {
        height: 200px;
    }
}
</style>

<!-- ===============================
     HEADER SECTION
================================ -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Infografis & Videografis</h1>
                <p class="subtitle">Informasi hukum dalam bentuk visual yang menarik dan mudah dipahami</p>
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mt-4">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Infografis & Videografis</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- ===============================
     MAIN CONTENT - FULL WIDTH
================================ -->
<section class="main-content">
    <div class="container">
        <!-- TABS SECTION (SEARCH REMOVED) -->
        <div class="tabs-section">
            <ul class="nav nav-tabs" id="grafisTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($tab == 'infografis' || empty($tab)) ? 'active' : ''; ?>" 
                            id="infografis-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#infografis-tab-pane" 
                            type="button" 
                            role="tab"
                            onclick="updateTab('infografis')">
                        <i class="bi bi-bar-chart me-2"></i>Infografis
                        <span class="badge bg-primary ms-2"><?php echo $total_infografis; ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($tab == 'videografis') ? 'active' : ''; ?>" 
                            id="videografis-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#videografis-tab-pane" 
                            type="button" 
                            role="tab"
                            onclick="updateTab('videografis')">
                        <i class="bi bi-camera-video me-2"></i>Videografis
                        <span class="badge bg-primary ms-2"><?php echo $total_videografis; ?></span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- TAB CONTENT - FULL WIDTH -->
        <div class="tab-content mt-4 full-width-content" id="grafisTabContent">
            
            <!-- INFOGRAFIS TAB -->
            <div class="tab-pane fade <?php echo ($tab == 'infografis' || empty($tab)) ? 'show active' : ''; ?>" 
                 id="infografis-tab-pane" 
                 role="tabpanel">
                
                <h2 class="section-title">Koleksi Infografis</h2>
                
                <div class="card-container">
                    <?php 
                    $infografis_displayed = 0;
                    if ($total_infografis > 0): 
                        foreach ($grafis_data as $item): 
                            if ($item['tipe'] != 'videografis'): 
                                $tanggal = date('d F Y', strtotime($item['tanggal_dibuat']));
                                $infografis_displayed++;
                                
                                // INFOGRAFIS: thumbnail dari panel_control/
                                $thumbnail_path = '';
                                if (!empty($item['thumbnail'])) {
                                    // Validasi file infografis
                                    $file_path = 'panel_control/' . $item['thumbnail'];
                                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    $file_extension = strtolower(pathinfo($item['thumbnail'], PATHINFO_EXTENSION));
                                    
                                    if (in_array($file_extension, $allowed_extensions) && file_exists($file_path)) {
                                        $thumbnail_path = $file_path;
                                    }
                                }
                    ?>
                        <div class="grafis-card">
                            <div class="grafis-image">
                                <?php if (!empty($thumbnail_path)): ?>
                                        <img src="<?php echo htmlspecialchars($thumbnail_path); ?>"
                                         alt="<?php echo htmlspecialchars($item['judul']); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="grafis-placeholder">
                                        <i class="bi bi-bar-chart"></i>
                                        <p>Infografis</p>
                                    </div>
                                <?php endif; ?>
                                <div class="image-overlay">
                                    <span class="image-badge">Infografis</span>
                                </div>
                            </div>
                            
                            <div class="grafis-content">
                                <div class="grafis-number">INF-<?php echo htmlspecialchars($item['id']); ?></div>
                                <h3 class="grafis-title"><?php echo htmlspecialchars($item['judul']); ?></h3>
                                
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <p class="grafis-description"><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                                <?php endif; ?>
                                
                                <div class="grafis-meta">
                                    <div class="grafis-date">
                                        <i class="bi bi-calendar"></i> <?php echo htmlspecialchars($tanggal); ?>
                                    </div>
                                    <span class="grafis-type infografis">Infografis</span>
                                </div>
                                
                                <div class="grafis-actions">
                                    <?php if (!empty($thumbnail_path)): ?>
                                    <button type="button"
                                            class="grafis-btn btn-view"
                                            data-bs-toggle="modal"
                                            data-bs-target="#infografisModal<?php echo htmlspecialchars($item['id']); ?>">
                                        <i class="bi bi-eye me-2"></i>Lihat Infografis
                                    </button>
                                    <?php else: ?>
                                    <button class="grafis-btn btn-view disabled" disabled>
                                        <i class="bi bi-exclamation-triangle me-2"></i>Infografis Tidak Tersedia
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Infografis Modal -->
                        <?php if (!empty($thumbnail_path)): ?>
                        <div class="modal fade" id="infografisModal<?php echo htmlspecialchars($item['id']); ?>" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-bar-chart me-2"></i>
                                            <?php echo htmlspecialchars($item['judul']); ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <img src="<?php echo htmlspecialchars($thumbnail_path); ?>"
                                                 alt="<?php echo htmlspecialchars($item['judul']); ?>"
                                                 class="img-fluid rounded shadow mb-3"
                                                 style="max-height: 70vh; object-fit: contain;">
                                        </div>
                                        
                                        <?php if (!empty($item['deskripsi'])): ?>
                                            <div class="mt-4">
                                                <h6 class="text-primary">
                                                    <i class="bi bi-info-circle me-2"></i>Deskripsi
                                                </h6>
                                                <p class="text-muted">
                                                    <?php echo nl2br(htmlspecialchars($item['deskripsi'])); ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="modal-footer">
                                        <?php if (!empty($item['sumber'])): ?>
                                            <a href="panel_control/<?php echo htmlspecialchars($item['sumber']); ?>" 
                                                target="_blank" 
                                                class="btn btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Buka Sumber Asli
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-lg me-1"></i>Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; endforeach; ?>
                        
                        <?php if ($infografis_displayed == 0): ?>
                            <div class="empty-grafis">
                                <i class="bi bi-bar-chart"></i>
                                <h4>Belum ada infografis tersedia</h4>
                                <p>Infografis akan segera ditambahkan</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-grafis">
                            <i class="bi bi-bar-chart"></i>
                            <h4>Belum ada infografis tersedia</h4>
                            <p>Infografis akan segera ditambahkan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- VIDEOGRAFIS TAB -->
            <div class="tab-pane fade <?php echo ($tab == 'videografis') ? 'show active' : ''; ?>" 
                 id="videografis-tab-pane" 
                 role="tabpanel">
                
                <h2 class="section-title">Koleksi Videografis</h2>
                
                <div class="card-container">
                    <?php 
                    $videografis_displayed = 0;
                    if ($total_videografis > 0): 
                        foreach ($grafis_data as $item): 
                            if ($item['tipe'] == 'videografis'): 
                                $tanggal = date('d M Y', strtotime($item['tanggal_dibuat']));
                                $videografis_displayed++;
                                
                                // VIDEOGRAFIS: thumbnail langsung dari input admin
                                $thumbnail_path = '';
                                if (!empty($item['thumbnail'])) {
                                    $thumbnail_path = htmlspecialchars($item['thumbnail']);
                                }
                                
                                // Extract video embed URL
                                $video_url = $item['sumber'] ?? '';
                                $embed_url = '';
                                
                                if (!empty($video_url)) {
                                    // YouTube URLs
                                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                        // Jika sudah embed URL
                                        if (strpos($video_url, 'embed') !== false) {
                                            $embed_url = htmlspecialchars($video_url);
                                        } 
                                        // Jika link YouTube biasa
                                        else if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches)) {
                                            $video_id = $matches[1];
                                            $embed_url = "https://www.youtube.com/embed/{$video_id}";
                                        }
                                    }
                                    // Link lainnya
                                    else {
                                        $embed_url = htmlspecialchars($video_url);
                                    }
                                }
                    ?>
                        <div class="grafis-card">
                            <div class="grafis-image">
                                <?php if (!empty($thumbnail_path)): ?>
                                    <img src="<?php echo $thumbnail_path; ?>" 
                                         alt="<?php echo htmlspecialchars($item['judul']); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="grafis-placeholder">
                                        <i class="bi bi-camera-video"></i>
                                        <p>Videografis</p>
                                    </div>
                                <?php endif; ?>
                                <div class="image-overlay">
                                    <span class="image-badge">Videografis</span>
                                </div>
                                <?php if (!empty($embed_url)): ?>
                                <div class="video-play-btn" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#videoModal<?php echo htmlspecialchars($item['id']); ?>">
                                    <i class="bi bi-play-fill"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="grafis-content">
                                <div class="grafis-number">VID-<?php echo htmlspecialchars($item['id']); ?></div>
                                <h3 class="grafis-title"><?php echo htmlspecialchars($item['judul']); ?></h3>
                                
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <p class="grafis-description"><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                                <?php endif; ?>
                                
                                <div class="grafis-meta">
                                    <div class="grafis-date">
                                        <i class="bi bi-calendar"></i> <?php echo htmlspecialchars($tanggal); ?>
                                    </div>
                                    <span class="grafis-type videografis">Videografis</span>
                                </div>
                                
                                <div class="grafis-actions">
                                    <?php if (!empty($embed_url)): ?>
                                    <button type="button" 
                                            class="grafis-btn btn-view" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#videoModal<?php echo htmlspecialchars($item['id']); ?>">
                                        <i class="bi bi-play-circle me-2"></i>Tonton Video
                                    </button>
                                    <?php else: ?>
                                    <button class="grafis-btn btn-view disabled" disabled>
                                        <i class="bi bi-exclamation-triangle me-2"></i>Video Tidak Tersedia
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Modal -->
                        <?php if (!empty($embed_url)): ?>
                        <div class="modal fade" id="videoModal<?php echo htmlspecialchars($item['id']); ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-camera-video me-2"></i>
                                            <?php echo htmlspecialchars($item['judul']); ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="ratio ratio-16x9 mb-3">
                                            <iframe src="<?php echo $embed_url; ?>" 
                                                    title="<?php echo htmlspecialchars($item['judul']); ?>" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen
                                                    class="modal-video"></iframe>
                                        </div>
                                        <?php if (!empty($item['deskripsi'])): ?>
                                            <div class="mt-3">
                                                <h6 class="text-primary"><i class="bi bi-info-circle me-2"></i>Deskripsi</h6>
                                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($item['deskripsi'])); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <?php if (!empty($video_url)): ?>
                                            <a href="<?php echo htmlspecialchars($video_url); ?>" 
                                               target="_blank" 
                                               class="btn btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Buka di YouTube
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; endforeach; ?>
                        
                        <?php if ($videografis_displayed == 0): ?>
                            <div class="empty-grafis">
                                <i class="bi bi-camera-video"></i>
                                <h4>Belum ada videografis tersedia</h4>
                                <p>Video akan segera ditambahkan</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-grafis">
                            <i class="bi bi-camera-video"></i>
                            <h4>Belum ada videografis tersedia</h4>
                            <p>Video akan segera ditambahkan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>

<script>
// ============================================
// JAVASCRIPT FUNCTIONS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const triggerTabList = [].slice.call(document.querySelectorAll('#grafisTab button'));
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    
    // Close modal when clicking outside
    const videoModals = document.querySelectorAll('.modal');
    videoModals.forEach(function(modal) {
        modal.addEventListener('show.bs.modal', function() {
            // Pause all videos when opening a new modal
            const iframes = document.querySelectorAll('.modal-video');
            iframes.forEach(function(iframe) {
                iframe.src = iframe.src; // Reload iframe to stop video
            });
        });
    });
    
    // Video play button click to open modal
    document.querySelectorAll('.video-play-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.grafis-card');
            const watchBtn = card.querySelector('.btn-view');
            if (watchBtn) {
                watchBtn.click();
            }
        });
    });
    
    // Check URL for tab parameter
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const tabElement = document.getElementById(tabParam + '-tab');
        if (tabElement) {
            const tabTrigger = new bootstrap.Tab(tabElement);
            tabTrigger.show();
        }
    }
});

// Function to update URL with tab parameter
function updateTab(tabName) {
    const url = new URL(window.location);
    // Build new URL without search parameter
    let newUrl = '?page=infografis';
    if (tabName !== 'infografis') {
        newUrl += '&tab=' + tabName;
    }
    
    window.history.pushState({}, '', newUrl);
}
</script>

<?php 
// ============================================
// CLEANUP
// ============================================
// Cleanup resources
if (isset($result)) mysqli_free_result($result);

// Close database connection
if (isset($koneksi)) mysqli_close($koneksi);
?>