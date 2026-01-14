<?php
/**
 * FILE DIAGNOSTIC TOOL
 * File: file_diagnostic.php
 * Fungsi: Mendiagnosa masalah file PDF
 */

session_start();

echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Diagnostic Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .result-card { border-left: 4px solid #0d6efd; }
        .found-card { border-left: 4px solid #198754; }
        .notfound-card { border-left: 4px solid #dc3545; }
        .location-item:hover { background: #f8f9fa; }
        .badge-file { font-size: 0.9em; }
        code { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container-fluid py-4">';

// Ambil parameter file
$filename = isset($_GET['file']) ? trim($_GET['file']) : '';
$search_all = isset($_GET['search_all']);

if (!$filename && !$search_all) {
    echo '
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header header">
                    <h4 class="mb-0"><i class="fas fa-stethoscope"></i> File Diagnostic Tool</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label for="file" class="form-label">Nama File PDF</label>
                            <input type="text" class="form-control" id="file" name="file" 
                                   placeholder="contoh: keputusan_2023.pdf" required>
                            <div class="form-text">Masukkan nama file yang ingin didiagnosa</div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="search_all" name="search_all" value="1">
                                <label class="form-check-label" for="search_all">
                                    Cari semua file PDF di server
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Mulai Diagnosa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    
    echo '</div></body></html>';
    exit;
}

echo '
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header header">
                <h4 class="mb-0">
                    <i class="fas fa-stethoscope"></i> 
                    ' . ($search_all ? 'Diagnosa Semua File PDF' : 'Diagnosa File: ' . htmlspecialchars($filename)) . '
                </h4>
            </div>
            <div class="card-body">';

// Fungsi pencarian file
function searchFileEverywhere($filename = null) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    
    $locations_to_search = [
        $root . '/uploads',
        $root . '/uploads/pdf',
        $root . '/wb_admin/uploads',
        $root . '/wb_admin/uploads/pdf',
        $current_dir . '/../uploads',
        $current_dir . '/../uploads/pdf',
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads',
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/pdf',
        '/var/www/html/uploads',
        '/var/www/html/uploads/pdf'
    ];
    
    $results = [];
    
    foreach ($locations_to_search as $location) {
        if (!is_dir($location)) continue;
        
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($location, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $file_name = $file->getFilename();
                    
                    if ($filename === null || stripos($file_name, $filename) !== false) {
                        $file_path = $file->getPathname();
                        $results[] = [
                            'name' => $file_name,
                            'path' => $file_path,
                            'size' => filesize($file_path),
                            'readable' => is_readable($file_path),
                            'writable' => is_writable($file_path),
                            'permission' => substr(sprintf('%o', fileperms($file_path)), -4),
                            'mime' => mime_content_type($file_path),
                            'modified' => date('Y-m-d H:i:s', filemtime($file_path))
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            continue;
        }
    }
    
    return $results;
}

// Cari file
$search_results = searchFileEverywhere($search_all ? null : $filename);

// Tampilkan hasil pencarian
if (empty($search_results)) {
    echo '
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> File Tidak Ditemukan!</h5>
        <p>Tidak ada file yang cocok dengan pencarian.</p>
    </div>';
} else {
    echo '
    <div class="alert alert-success">
        <h5><i class="fas fa-check-circle"></i> Ditemukan ' . count($search_results) . ' File</h5>
    </div>';
    
    echo '<div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">Nama File</th>
                    <th width="30%">Lokasi</th>
                    <th width="10%">Ukuran</th>
                    <th width="10%">Permission</th>
                    <th width="15%">MIME Type</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($search_results as $index => $file) {
        $is_pdf = strpos($file['mime'], 'pdf') !== false;
        
        echo '<tr>
            <td>' . ($index + 1) . '</td>
            <td>
                <strong>' . htmlspecialchars($file['name']) . '</strong>
                ' . ($is_pdf ? '<span class="badge bg-danger badge-file">PDF</span>' : '') . '
            </td>
            <td><code>' . htmlspecialchars($file['path']) . '</code></td>
            <td>' . formatBytes($file['size']) . '</td>
            <td>
                <span class="badge ' . ($file['readable'] ? 'bg-success' : 'bg-danger') . '">
                    ' . $file['permission'] . '
                </span>
            </td>
            <td><span class="badge bg-info">' . $file['mime'] . '</span></td>
            <td>';
        
        if (!$file['readable']) {
            echo '<span class="badge bg-danger"><i class="fas fa-lock"></i> Tidak bisa dibaca</span>';
        } elseif (!$is_pdf) {
            echo '<span class="badge bg-warning"><i class="fas fa-exclamation"></i> Bukan PDF</span>';
        } else {
            echo '<span class="badge bg-success"><i class="fas fa-check"></i> OK</span>';
        }
        
        echo '</td>
        </tr>';
    }
    
    echo '</tbody></table></div>';
}

// Tampilkan informasi server
echo '
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-server"></i> Informasi Server</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr><th>DOCUMENT_ROOT</th><td><code>' . $_SERVER['DOCUMENT_ROOT'] . '</code></td></tr>
                    <tr><th>Script Directory</th><td><code>' . __DIR__ . '</code></td></tr>
                    <tr><th>Server Software</th><td>' . $_SERVER['SERVER_SOFTWARE'] . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr><th>PHP Version</th><td>' . phpversion() . '</td></tr>
                    <tr><th>Server OS</th><td>' . PHP_OS . '</td></tr>
                    <tr><th>Memory Limit</th><td>' . ini_get('memory_limit') . '</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>';

// Fungsi format bytes
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

echo '
            </div>
            <div class="card-footer text-center">
                <a href="file_diagnostic.php" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Diagnosa Lain
                </a>
                <a href="javascript:window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Print Report
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>';
?>