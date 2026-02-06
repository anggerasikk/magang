<?php
// File: check_file.php
session_start();

echo '<!DOCTYPE html>
<html>
<head>
    <title>File Diagnostic Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>🔍 File Diagnostic Tool</h1>
    <hr>';

$filename = $_GET['file'] ?? '';

if (!$filename) {
    echo '<div class="alert alert-warning">Masukkan nama file di parameter ?file=</div>';
    exit;
}

echo '<h3>🔎 Mencari file: <code>' . htmlspecialchars($filename) . '</code></h3>';

// Fungsi pencarian file
function searchFileEverywhere($filename) {
    $locations = [];
    $root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    
    // Daftar semua lokasi yang mungkin
    $possible_locations = [
        // Uploads folder
        $root . '/uploads/' . $filename,
        $root . '/uploads/pdf/' . $filename,
        
        // Admin uploads
        $root . '/panel_control/uploads/' . $filename,
        $root . '/panel_control/uploads/pdf/' . $filename,
        
        // Variasi path
        $current_dir . '/../uploads/' . $filename,
        $current_dir . '/../uploads/pdf/' . $filename,
        
        // XAMPP paths
        'C:/xampp/htdocs/uploads/' . $filename,
        'C:/xampp/htdocs/uploads/pdf/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/' . $filename,
        'C:/xampp/htdocs/jdihcilacap_pasang/uploads/pdf/' . $filename,
        
        // Linux paths
        '/var/www/html/uploads/' . $filename,
        '/var/www/html/uploads/pdf/' . $filename,
    ];

    $results = [];
    foreach ($possible_locations as $location) {
        $exists = file_exists($location);
        $results[] = [
            'path' => $location,
            'exists' => $exists,
            'readable' => $exists ? is_readable($location) : false,
            'size' => $exists ? filesize($location) : 0,
            'permissions' => $exists ? substr(sprintf('%o', fileperms($location)), -4) : 'N/A'
        ];
    }
    
    return $results;
}

// Cari file
$search_results = searchFileEverywhere($filename);

echo '<table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Lokasi File</th>
            <th>Status</th>
            <th>Readable</th>
            <th>Size</th>
            <th>Permissions</th>
        </tr>
    </thead>
    <tbody>';

foreach ($search_results as $index => $result) {
    echo '<tr>';
    echo '<td>' . ($index + 1) . '</td>';
    echo '<td><code>' . htmlspecialchars($result['path']) . '</code></td>';
    echo '<td>';
    if ($result['exists']) {
        echo '<span class="badge bg-success">✅ DITEMUKAN</span>';
    } else {
        echo '<span class="badge bg-danger">❌ TIDAK ADA</span>';
    }
    echo '</td>';
    echo '<td>';
    if ($result['exists']) {
        echo $result['readable'] ? 
            '<span class="badge bg-success">✅ BISA DIBACA</span>' : 
            '<span class="badge bg-danger">❌ TIDAK BISA DIBACA</span>';
    }
    echo '</td>';
    echo '<td>' . ($result['exists'] ? round($result['size'] / 1024, 2) . ' KB' : '-') . '</td>';
    echo '<td>' . $result['permissions'] . '</td>';
    echo '</tr>';
}

echo '</tbody></table>';

// Tampilkan informasi server
echo '<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <strong>🖥️ Informasi Server</strong>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <td><strong>DOCUMENT_ROOT</strong></td>
                <td><code>' . $_SERVER['DOCUMENT_ROOT'] . '</code></td>
            </tr>
            <tr>
                <td><strong>Current Directory</strong></td>
                <td><code>' . __DIR__ . '</code></td>
            </tr>
            <tr>
                <td><strong>PHP Version</strong></td>
                <td>' . phpversion() . '</td>
            </tr>
        </table>
    </div>
</div>';

// Test download langsung
echo '<div class="card mt-4">
    <div class="card-header bg-warning">
        <strong>🧪 Test Download Langsung</strong>
    </div>
    <div class="card-body">';

foreach ($search_results as $result) {
    if ($result['exists'] && $result['readable']) {
        // Encode untuk URL
        $encoded_path = urlencode($result['path']);
        
        echo '<div class="mb-2">
            <strong>File ditemukan di:</strong><br>
            <code>' . htmlspecialchars($result['path']) . '</code><br>
            <a href="test_download.php?path=' . $encoded_path . '" class="btn btn-sm btn-success mt-2">
                📥 Test Download File Ini
            </a>
        </div>';
    }
}

echo '</div></div>';

echo '</body></html>';
?>