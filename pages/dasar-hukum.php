<?php
// dasar-hukum.php
$pageTitle = 'Dasar Hukum JDIH - JDIH DPRD Kota Surakarta';
include 'header.php';

// Data dokumen dengan URL Google Drive
$documents = [
    [
        'id' => 1,
        'title' => 'Peraturan Wali Kota Surakarta Nomor 4 Tahun 2024 tentang Jaringan Dokumentasi dan Informasi Hukum Kota Surakarta',
        'source' => 'Pemerintah Kota Surakarta',
        'year' => 2024,
        'type' => 'perwali',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ],
    [
        'id' => 2,
        'title' => 'Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 2 Tahun 2014 Tentang Pengelolaan Jaringan Dokumentasi Dan Informasi Hukum Kementerian Dalam Negeri Dan Pemerintah Daerah',
        'source' => 'Kementerian Dalam Negeri RI',
        'year' => 2014,
        'type' => 'permendagri',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ],
    [
        'id' => 3,
        'title' => 'Peraturan Menteri Hukum Dan Hak Asasi Manusia Republik Indonesia Nomor 8 Tahun 2019 Tentang Standar Pengelolaan Dokumen Dan Informasi Hukum',
        'source' => 'Kementerian Hukum dan HAM RI',
        'year' => 2019,
        'type' => 'permenkumham',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ],
    [
        'id' => 4,
        'title' => 'Undang-Undang Nomor 12 Tahun 2011 tentang Pembentukan Peraturan Perundang-undangan',
        'source' => 'Pemerintah Republik Indonesia',
        'year' => 2011,
        'type' => 'uu',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ],
    [
        'id' => 5,
        'title' => 'Peraturan Sekretaris Jenderal DPRD Kota Surakarta Nomor 1 Tahun 2023 tentang Pengelolaan JDIH',
        'source' => 'Sekretariat DPRD Kota Surakarta',
        'year' => 2023,
        'type' => 'peraturan',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ],
    [
        'id' => 6,
        'title' => 'Peraturan Daerah Kota Surakarta Nomor 5 Tahun 2022 tentang Organisasi dan Tata Kerja DPRD Kota Surakarta',
        'source' => 'DPRD Kota Surakarta',
        'year' => 2022,
        'type' => 'perda',
        'file_url' => 'https://drive.google.com/uc?export=download&id=1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie',
        'preview_url' => 'https://drive.google.com/file/d/1VBbs_13pSz0pn4_2FSz_Zescf8h5V2ie/preview'
    ]
];

// Proses pencarian
$searchResults = $documents;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($searchQuery) {
    $searchResults = array_filter($documents, function($doc) use ($searchQuery) {
        $searchTerms = explode(' ', strtolower($searchQuery));
        $title = strtolower($doc['title']);
        $source = strtolower($doc['source']);
        $year = (string)$doc['year'];
        
        foreach ($searchTerms as $term) {
            if (strpos($title, $term) !== false || 
                strpos($source, $term) !== false || 
                strpos($year, $term) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Filter berdasarkan jenis
if (isset($_GET['type']) && $_GET['type'] !== 'all') {
    $filterType = $_GET['type'];
    $searchResults = array_filter($searchResults, function($doc) use ($filterType) {
        return $doc['type'] === $filterType;
    });
}

// Filter berdasarkan tahun
if (isset($_GET['year']) && $_GET['year'] !== 'all') {
    $filterYear = $_GET['year'];
    $searchResults = array_filter($searchResults, function($doc) use ($filterYear) {
        return $doc['year'] == $filterYear;
    });
}
?>

<style>
    /* CSS UTAMA */
    .page-header {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        padding: 60px 0 40px;
        color: white;
        text-align: center;
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .page-header .subtitle {
        font-size: 1.1rem;
        color : #ffffff;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .main-content {
        padding: 40px 0;
        background: #f8f9fa;
        min-height: 600px;
    }
    
    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
    }
    
    /* SEARCH BAR */
    .search-container {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    
    .search-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .search-input {
        flex: 1;
    }
    
    .search-input input {
        height: 50px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 0 20px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .search-input input:focus {
        border-color: #80d0c7;
        box-shadow: 0 0 0 3px rgba(128, 208, 199, 0.1);
    }
    
    .btn-search {
        height: 50px;
        padding: 0 30px;
        background: #13547a;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-search:hover {
        background: #0d3a58;
        transform: translateY(-2px);
    }
    
    .filter-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-group label {
        font-weight: 500;
        color: #13547a;
        margin: 0;
        white-space: nowrap;
    }
    
    .filter-group select {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        padding: 8px 15px;
        min-width: 150px;
        background: white;
    }
    
    /* TABLE STYLES */
    .documents-table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
    }
    
    .documents-table thead {
        background: #13547a;
        color: white;
    }
    
    .documents-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .documents-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s;
    }
    
    .documents-table tbody tr:hover {
        background: #f8fdff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.1);
    }
    
    .documents-table td {
        padding: 20px 15px;
        vertical-align: top;
    }
    
    .document-title {
        font-weight: 600;
        color: #13547a;
        margin-bottom: 8px;
        font-size: 1.05rem;
        line-height: 1.4;
    }
    
    .document-meta {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .document-type {
        display: inline-block;
        padding: 3px 10px;
        background: #e3f2fd;
        color: #13547a;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 10px;
    }
    
    .document-year {
        display: inline-block;
        padding: 3px 10px;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-download {
        background: #13547a;
        color: white;
    }
    
    .btn-download:hover {
        background: #0d3a58;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.2);
    }
    
    .btn-preview {
        background: #28a745;
        color: white;
    }
    
    .btn-preview:hover {
        background: #218838;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
    }
    
    /* RESULTS INFO */
    .results-info {
        background: #e3f2fd;
        border-radius: 8px;
        padding: 15px 20px;
        margin: 20px 0;
        border-left: 4px solid #13547a;
    }
    
    .results-info p {
        margin: 0;
        color: #13547a;
        font-weight: 500;
    }
    
    .results-info .highlight {
        background: #fff3cd;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    /* SIDEBAR */
    .sidebar-menu {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #13547a, #80d0c7);
    }
    
    .sidebar-menu h4 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
    }
    
    .sidebar-menu h4 i {
        color: #80d0c7;
        margin-right: 10px;
    }
    
    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-menu ul li {
        margin-bottom: 12px;
    }
    
    .sidebar-menu ul li:last-child {
        margin-bottom: 0;
    }
    
    .sidebar-menu ul li a {
        color: var(--p-color);
        text-decoration: none;
        padding: 12px 15px;
        border-radius: var(--border-radius-small);
        display: flex;
        align-items: center;
        transition: all 0.3s;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-menu ul li a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #80d0c7;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .sidebar-menu ul li a:hover::before {
        transform: scaleY(1);
    }
    
    .sidebar-menu ul li a:hover {
        background: #f0f8ff;
        color: #13547a;
        transform: translateX(5px);
    }
    
    .sidebar-menu ul li a i {
        color: #80d0c7;
        margin-right: 10px;
        font-size: 0.9rem;
    }
    
    /* STATS CARD */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f0f8ff;
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #13547a;
        line-height: 1;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #666;
    }
    
    /* NO RESULTS */
    .no-results {
        text-align: center;
        padding: 60px 20px;
    }
    
    .no-results i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .no-results h4 {
        color: #666;
        margin-bottom: 10px;
    }
    
    .no-results p {
        color: #888;
        max-width: 500px;
        margin: 0 auto 20px;
    }
    
    /* MODAL */
    .preview-modal .modal-dialog {
        max-width: 90%;
        height: 90vh;
        margin: 5vh auto;
    }
    
    .preview-modal .modal-content {
        height: 100%;
    }
    
    .preview-modal .modal-body {
        padding: 0;
        flex: 1;
    }
    
    .preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .search-form {
            flex-direction: column;
        }
        
        .btn-search {
            width: 100%;
        }
        
        .filter-container {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .filter-group select {
            width: 100%;
        }
        
        .documents-table {
            display: block;
            overflow-x: auto;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- HEADER -->
<section class="page-header">
    <div class="container">
        <h1>DASAR HUKUM JDIH</h1>
        <p class="subtitle">Dokumen Hukum Pendukung Jaringan Dokumentasi dan Informasi Hukum Sekretariat DPRD Kota Surakarta</p>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <div class="row">
            <!-- MAIN CONTENT -->
            <div class="col-lg-8 mb-4">
                
                <!-- SEARCH SECTION -->
                <div class="search-container">
                    <form method="GET" class="search-form">
                        <div class="search-input">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" 
                                   placeholder="Cari dokumen hukum..." class="form-control">
                        </div>
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <?php if ($searchQuery): ?>
                        <a href="dasar-hukum.php" class="btn btn-outline-secondary" style="height: 50px; padding: 0 20px;">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                        <?php endif; ?>
                    </form>
                    
                    <div class="filter-container">
                        <div class="filter-group">
                            <label for="type">Jenis Dokumen:</label>
                            <select id="type" name="type" class="form-select" onchange="this.form.submit()">
                                <option value="all" <?php echo (!isset($_GET['type']) || $_GET['type'] == 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
                                <option value="perwali" <?php echo (isset($_GET['type']) && $_GET['type'] == 'perwali') ? 'selected' : ''; ?>>Peraturan Wali Kota</option>
                                <option value="permendagri" <?php echo (isset($_GET['type']) && $_GET['type'] == 'permendagri') ? 'selected' : ''; ?>>Peraturan Menteri Dalam Negeri</option>
                                <option value="permenkumham" <?php echo (isset($_GET['type']) && $_GET['type'] == 'permenkumham') ? 'selected' : ''; ?>>Peraturan Menteri Hukum dan HAM</option>
                                <option value="perda" <?php echo (isset($_GET['type']) && $_GET['type'] == 'perda') ? 'selected' : ''; ?>>Peraturan Daerah</option>
                                <option value="uu" <?php echo (isset($_GET['type']) && $_GET['type'] == 'uu') ? 'selected' : ''; ?>>Undang-Undang</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="year">Tahun:</label>
                            <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                                <option value="all" <?php echo (!isset($_GET['year']) || $_GET['year'] == 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                                <?php
                                $years = array_unique(array_column($documents, 'year'));
                                rsort($years);
                                foreach ($years as $year):
                                ?>
                                <option value="<?php echo $year; ?>" <?php echo (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- RESULTS INFO -->
                <?php if ($searchQuery || isset($_GET['type']) || isset($_GET['year'])): ?>
                <div class="results-info">
                    <p>
                        <i class="bi bi-info-circle me-2"></i>
                        Menampilkan <span class="highlight"><?php echo count($searchResults); ?></span> dokumen
                        <?php if ($searchQuery): ?>
                            untuk pencarian "<span class="highlight"><?php echo htmlspecialchars($searchQuery); ?></span>"
                        <?php endif; ?>
                        <?php if (isset($_GET['type']) && $_GET['type'] != 'all'): ?>
                            dengan jenis <span class="highlight">
                                <?php 
                                $typeLabels = [
                                    'perwali' => 'Peraturan Wali Kota',
                                    'permendagri' => 'Peraturan Menteri Dalam Negeri',
                                    'permenkumham' => 'Peraturan Menteri Hukum dan HAM',
                                    'perda' => 'Peraturan Daerah',
                                    'uu' => 'Undang-Undang'
                                ];
                                echo $typeLabels[$_GET['type']] ?? $_GET['type'];
                                ?>
                            </span>
                        <?php endif; ?>
                        <?php if (isset($_GET['year']) && $_GET['year'] != 'all'): ?>
                            tahun <span class="highlight"><?php echo $_GET['year']; ?></span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <!-- DOCUMENTS TABLE -->
                <div class="content-card">
                    <h3 class="mb-4" style="color: #13547a;">
                        <i class="bi bi-file-earmark-text me-2"></i> Daftar Dokumen Hukum
                    </h3>
                    
                    <?php if (count($searchResults) > 0): ?>
                        <div class="table-responsive">
                            <table class="documents-table">
                                <thead>
                                    <tr>
                                        <th width="60%">Dokumen Hukum</th>
                                        <th width="40%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($searchResults as $doc): ?>
                                    <tr>
                                        <td>
                                            <div class="document-title"><?php echo htmlspecialchars($doc['title']); ?></div>
                                            <div class="document-meta">
                                                <span class="document-type">
                                                    <?php 
                                                    switch($doc['type']) {
                                                        case 'perwali': echo 'Peraturan Wali Kota'; break;
                                                        case 'permendagri': echo 'Peraturan Menteri Dalam Negeri'; break;
                                                        case 'permenkumham': echo 'Peraturan Menteri Hukum dan HAM'; break;
                                                        case 'perda': echo 'Peraturan Daerah'; break;
                                                        case 'uu': echo 'Undang-Undang'; break;
                                                        default: echo 'Peraturan';
                                                    }
                                                    ?>
                                                </span>
                                                <span class="document-year">Tahun <?php echo $doc['year']; ?></span>
                                            </div>
                                            <div class="document-meta">
                                                <i class="bi bi-building me-1"></i> <?php echo $doc['source']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="<?php echo $doc['file_url']; ?>" 
                                                   class="btn-action btn-download"
                                                   target="_blank"
                                                   onclick="trackDownload(<?php echo $doc['id']; ?>)">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button type="button" 
                                                        class="btn-action btn-preview"
                                                        onclick="openPreview('<?php echo $doc['preview_url']; ?>', '<?php echo htmlspecialchars(addslashes($doc['title'])); ?>')">
                                                    <i class="bi bi-eye"></i> Preview
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-results">
                            <i class="bi bi-file-earmark-x"></i>
                            <h4>Tidak ada dokumen ditemukan</h4>
                            <p>Maaf, tidak ada dokumen yang sesuai dengan kriteria pencarian Anda.</p>
                            <a href="dasar-hukum.php" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- INFO BOX -->
                <div class="content-card" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); color: white;">
                    <h4><i class="bi bi-info-circle me-2"></i> Informasi Penting</h4>
                    <p>Dokumen-dokumen ini merupakan dasar hukum resmi yang digunakan oleh JDIH Sekretariat DPRD Kota Surakarta. Semua dokumen dapat diunduh dan dilihat secara gratis untuk keperluan referensi hukum.</p>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Format dokumen: PDF</li>
                                <li>Akses: Terbuka untuk umum</li>
                                <li>Update: Real-time</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Lisensi: Dokumen publik</li>
                                <li>Bahasa: Bahasa Indonesia</li>
                                <li>Status: Berlaku</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- MENU TERKAIT -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                    <ul>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'Profil-JDIH') !== false) ? 'active' : '' ?>">
                            <a href="?page=profil-jdih">
                                <i class="bi bi-building"></i>
                                Profil JDIH
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'struktur-organisasi') !== false) ? 'active' : '' ?>">
                            <a href="?page=struktur-organisasi">
                                <i class="bi bi-diagram-3"></i>
                                Struktur Organisasi
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'alur-pengelolaan') !== false) ? 'active' : '' ?>">
                            <a href="?page=alur-pengelolaan">
                                <i class="bi bi-diagram-2"></i>
                                Alur Pengelolaan
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'sop-pengelola') !== false) ? 'active' : '' ?>">
                            <a href="?page=sop-pengelola">
                                <i class="bi bi-clipboard-check"></i>
                                SOP Pengelola
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'dasar-hukum') !== false) ? 'active' : '' ?>">
                            <a href="?page=dasar-hukum">
                                <i class="bi bi-journal-text"></i>
                                Dasar Hukum
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'surat-keputusan') !== false) ? 'active' : '' ?>">
                            <a href="?page=surat-keputusan">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Surat Keputusan
                            </a>
                        </li>
                        <li class="<?= (strpos($_SERVER['PHP_SELF'], 'video-profile') !== false) ? 'active' : '' ?>">
                            <a href="?page=video-profile">
                                <i class="bi bi-play-circle"></i>
                                Video Profile
                            </a>
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
</section>

<!-- PREVIEW MODAL -->
<div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe class="preview-iframe" id="previewIframe" src=""></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="downloadPreviewBtn" class="btn btn-primary" target="_blank">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk membuka preview
    function openPreview(url, title) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        document.getElementById('previewIframe').src = url;
        document.getElementById('previewModalLabel').textContent = title;
        
        // Set download link
        const downloadUrl = url.replace('/preview', '').replace('/file/d/', '/uc?export=download&id=');
        document.getElementById('downloadPreviewBtn').href = downloadUrl;
        
        modal.show();
    }
    
    // Fungsi untuk tracking download
    function trackDownload(docId) {
        // Simulasi tracking download
        console.log('Download tracked for document ID:', docId);
        
        // Dalam implementasi nyata, ini akan mengirim data ke server
        // fetch('track-download.php?doc_id=' + docId);
    }
    
    // Auto-submit filter saat berubah (untuk form yang sudah ada di HTML)
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan hidden input untuk search query di form filter
        const searchInput = document.querySelector('input[name="search"]');
        const filterSelects = document.querySelectorAll('select[name="type"], select[name="year"]');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Buat form baru
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = 'dasar-hukum.php';
                
                // Tambahkan search query jika ada
                if (searchInput && searchInput.value) {
                    const searchField = document.createElement('input');
                    searchField.type = 'hidden';
                    searchField.name = 'search';
                    searchField.value = searchInput.value;
                    form.appendChild(searchField);
                }
                
                // Tambahkan semua filter
                filterSelects.forEach(s => {
                    const field = document.createElement('input');
                    field.type = 'hidden';
                    field.name = s.name;
                    field.value = s.value;
                    form.appendChild(field);
                });
                
                // Submit form
                document.body.appendChild(form);
                form.submit();
            });
        });
        
        // Highlight pencarian di hasil
        const searchQuery = "<?php echo addslashes($searchQuery); ?>";
        if (searchQuery) {
            const titles = document.querySelectorAll('.document-title');
            titles.forEach(title => {
                const text = title.textContent;
                const regex = new RegExp(`(${searchQuery})`, 'gi');
                title.innerHTML = text.replace(regex, '<mark class="highlight">$1</mark>');
            });
        }
    });
</script>

<?php include 'footer.php'; ?>