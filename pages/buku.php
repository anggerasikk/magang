<?php
// buku.php - SEMUA KODE DALAM SATU FILE
session_start();

// =============================================
// KONEKSI DATABASE - SESUAI DENGAN DATABASE ANDA
// =============================================
$host = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$database = "jdihdprd"; // Nama database sesuai dengan yang Anda berikan

$koneksi = new mysqli($host, $username, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8");

// =============================================
// KONSTANTA DAN FUNGSI
// =============================================
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/jdihcilacap_pasang');
define('UPLOAD_DIR', 'wb_admin/uploads/buku/');
include "header.php";
// Fungsi untuk mengonversi path filesystem ke URL cover
function getCoverUrl($file_path) {
    if(empty($file_path)) {
        return BASE_URL . '/assets/img/default-book.jpg';
    }
    
    if(strpos($file_path, 'http://') === 0 || strpos($file_path, 'https://') === 0) {
        return $file_path;
    }
    
    $file_path = str_replace('\\', '/', $file_path);
    
    if(strpos($file_path, 'uploads/buku/') !== false) {
        $relative_path = substr($file_path, strpos($file_path, 'uploads/buku/'));
        return BASE_URL . '/wb_admin/' . $relative_path;
    }
    
    $server_paths = [
        'C:/xampp/htdocs/jdihcilacap_pasang/',
        'C:\\xampp\\htdocs\\jdihcilacap_pasang\\',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/'
    ];
    
    foreach($server_paths as $server_path) {
        $server_path = str_replace('\\', '/', $server_path);
        if(strpos($file_path, $server_path) === 0) {
            $relative = str_replace($server_path, '', $file_path);
            return BASE_URL . '/' . $relative;
        }
    }
    
    return BASE_URL . '/assets/img/default-book.jpg';
}

// Fungsi untuk mendapatkan URL file untuk download (menggunakan file_buku_path)
function getFileDownloadUrl($file_path) {
    if(empty($file_path)) {
        return '';
    }
    
    $file_path = str_replace('\\', '/', $file_path);
    
    if(strpos($file_path, 'http://') === 0 || strpos($file_path, 'https://') === 0) {
        return $file_path;
    }
    
    $filename = basename($file_path);
    return BASE_URL . '/buku.php?action=download&file=' . urlencode($file_path) . '&name=' . urlencode($filename);
}

// Fungsi untuk mendownload file
function downloadFile($filepath, $filename = null) {
    if (!file_exists($filepath)) {
        die('File tidak ditemukan.');
    }
    
    if ($filename === null) {
        $filename = basename($filepath);
    }
    
    $file_extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    
    $content_types = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'txt' => 'text/plain',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed'
    ];
    
    $content_type = isset($content_types[$file_extension]) ? 
                    $content_types[$file_extension] : 
                    'application/octet-stream';
    
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    
    ob_clean();
    flush();
    
    readfile($filepath);
    exit;
}

// =============================================
// HANDLE DOWNLOAD ACTION
// =============================================
if (isset($_GET['action']) && $_GET['action'] == 'download' && isset($_GET['file'])) {
    $filepath = urldecode($_GET['file']);
    $filename = isset($_GET['name']) ? urldecode($_GET['name']) : null;
    
    $filepath = str_replace('\\', '/', $filepath);
    
    // Validasi path untuk keamanan
    $allowed_dirs = [
        'C:/xampp/htdocs/jdihcilacap_pasang/',
        'C:/xampp/htdocs/jdihcilacap_pasang/wb_admin/uploads/buku/',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/wb_admin/uploads/buku/'
    ];
    
    foreach ($allowed_dirs as &$dir) {
        $dir = str_replace('\\', '/', $dir);
        if (substr($dir, -1) !== '/') {
            $dir .= '/';
        }
    }
    
    $is_valid = false;
    foreach ($allowed_dirs as $allowed_dir) {
        if (strpos($filepath, $allowed_dir) === 0) {
            $is_valid = true;
            break;
        }
    }
    
    $allowed_extensions = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar'];
    $file_extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    
    if (!$is_valid) {
        die('Akses ke file ini tidak diizinkan.');
    }
    
    if (!in_array($file_extension, $allowed_extensions)) {
        die('Tipe file tidak diizinkan.');
    }
    
    if (file_exists($filepath)) {
        // Log download activity jika ada session user
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $log_query = "INSERT INTO download_log (user_id, file_path, download_time) 
                          VALUES ('$user_id', '" . $koneksi->real_escape_string($filepath) . "', NOW())";
            $koneksi->query($log_query);
        }
        
        downloadFile($filepath, $filename);
    } else {
        die('File tidak ditemukan: ' . htmlspecialchars(basename($filepath)));
    }
}

// =============================================
// QUERY DAN LOGIKA HALAMAN BUKU
// =============================================
$pageTitle = 'Koleksi Buku - JDIH DPRD Kota Surakarta';

// Query untuk mengambil data buku (sesuai struktur tabel Anda)
$query = "SELECT *, file_buku_path FROM buku_perpustakaan ORDER BY created_at DESC LIMIT 12";
$result = $koneksi->query($query);

// Handle search
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $koneksi->real_escape_string($_GET['search']);
    $search_query = " WHERE judul LIKE '%$search%' OR pengarang LIKE '%$search%' OR subjek_topik LIKE '%$search%' OR penerbit LIKE '%$search%'";
}

// Handle filters
$filter_conditions = [];
if (isset($_GET['bidang_hukum']) && !empty($_GET['bidang_hukum'])) {
    $bidang_hukum = $koneksi->real_escape_string($_GET['bidang_hukum']);
    $filter_conditions[] = "bidang_hukum = '$bidang_hukum'";
}

if (isset($_GET['tahun']) && !empty($_GET['tahun'])) {
    $tahun = $koneksi->real_escape_string($_GET['tahun']);
    $filter_conditions[] = "tahun_terbit = '$tahun'";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $koneksi->real_escape_string($_GET['status']);
    if ($status == 'tersedia') {
        $filter_conditions[] = "status_ketersediaan = 'tersedia' AND jumlah_tersedia > 0";
    } elseif ($status == 'dipinjam') {
        $filter_conditions[] = "status_ketersediaan = 'dipinjam' OR jumlah_tersedia = 0";
    }
}

// Build final query
$where_clause = "";
if (!empty($search_query)) {
    $where_clause = $search_query;
}

if (!empty($filter_conditions)) {
    if (empty($where_clause)) {
        $where_clause = " WHERE " . implode(" AND ", $filter_conditions);
    } else {
        $where_clause .= " AND " . implode(" AND ", $filter_conditions);
    }
}

// Query final dengan semua kolom yang dibutuhkan
$final_query = "SELECT * FROM buku_perpustakaan" . $where_clause . " ORDER BY created_at DESC";
$final_result = $koneksi->query($final_query);

// Get unique years and bidang_hukum for filters
$years_query = "SELECT DISTINCT tahun_terbit FROM buku_perpustakaan ORDER BY tahun_terbit DESC";
$years_result = $koneksi->query($years_query);

$bidang_query = "SELECT DISTINCT bidang_hukum FROM buku_perpustakaan WHERE bidang_hukum IS NOT NULL AND bidang_hukum != ''";
$bidang_result = $koneksi->query($bidang_query);

// Hitung total buku
$total_query = "SELECT COUNT(*) as total FROM buku_perpustakaan";
$total_result = $koneksi->query($total_query);
$total_buku = $total_result->fetch_assoc()['total'];

// Get unique bahasa for filters
$bahasa_query = "SELECT DISTINCT bahasa FROM buku_perpustakaan WHERE bahasa IS NOT NULL AND bahasa != ''";
$bahasa_result = $koneksi->query($bahasa_query);

// Get unique tipe_dokumen for filters
$tipe_query = "SELECT DISTINCT tipe_dokumen FROM buku_perpustakaan WHERE tipe_dokumen IS NOT NULL AND tipe_dokumen != ''";
$tipe_result = $koneksi->query($tipe_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* CSS KHUSUS UNTUK HALAMAN BUKU */
        body {
            background: #f0f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* PAGE HEADER */
        .page-header {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 Z"/></svg>');
            background-size: cover;
        }
        
        .page-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .page-header .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .page-header .book-count {
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 8px 20px;
            display: inline-block;
            margin-top: 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        /* MAIN CONTENT */
        .main-content {
            padding: 40px 0 80px;
        }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #13547a;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #80d0c7;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: #ff7e5f;
        }
        
        /* SEARCH SECTION */
        .search-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            transition: transform 0.3s ease;
        }
        
        .search-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .search-box {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            height: 55px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0 25px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #13547a;
            box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.1);
            background: white;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0 35px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(19, 84, 122, 0.3);
            background: linear-gradient(135deg, #114566 0%, #6bc0b8 100%);
        }
        
        /* FILTER SIDEBAR */
        .filter-sidebar {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .filter-sidebar:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .filter-title {
            color: #13547a;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-title i {
            font-size: 1.2rem;
        }
        
        .filter-category {
            margin-bottom: 25px;
        }
        
        .filter-category h6 {
            color: #13547a;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-category h6 i {
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        .filter-options {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .filter-options::-webkit-scrollbar {
            width: 6px;
        }
        
        .filter-options::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .filter-options::-webkit-scrollbar-thumb {
            background: #13547a;
            border-radius: 3px;
        }
        
        .filter-option {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .filter-option label {
            color: #555;
            cursor: pointer;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .filter-option label:hover {
            background: #f0f8ff;
            color: #13547a;
        }
        
        .filter-option input {
            margin-right: 10px;
            cursor: pointer;
        }
        
        /* BOOK GRID */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .book-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #eef7f6;
        }
        
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #80d0c7;
        }
        
        .book-cover-container {
            position: relative;
            overflow: hidden;
            height: 320px;
            background: linear-gradient(135deg, #f0f8ff 0%, #e0f7f5 100%);
        }
        
        .book-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .book-card:hover .book-cover {
            transform: scale(1.05);
        }
        
        .book-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .book-category {
            display: inline-block;
            background: linear-gradient(135deg, #e0f7f5 0%, #c2f0ed 100%);
            color: #13547a;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            align-self: flex-start;
            border: 1px solid #80d0c7;
        }
        
        .book-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            font-size: 1.15rem;
            line-height: 1.4;
            margin-bottom: 12px;
            height: 65px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        .book-author {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .book-author i {
            color: #80d0c7;
            font-size: 1rem;
        }
        
        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }
        
        .book-year {
            color: #13547a;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .book-year i {
            color: #80d0c7;
        }
        
        .book-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .status-available {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #155724;
        }
        
        .status-borrowed {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #721c24;
        }
        
        .book-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .btn-detail, .btn-download {
            flex: 1;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-detail {
            background: #f8f9fa;
            border: 2px solid #13547a;
            color: #13547a;
        }
        
        .btn-detail:hover {
            background: #13547a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.2);
        }
        
        .btn-download {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: 2px solid transparent;
            color: white;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
            background: linear-gradient(135deg, #218838 0%, #1ca67c 100%);
        }
        
        .btn-download:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.7;
        }
        
        /* PAGINATION */
        .pagination-container {
            margin-top: 60px;
            display: flex;
            justify-content: center;
        }
        
        .pagination {
            background: white;
            border-radius: 50px;
            padding: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .page-item {
            margin: 0 5px;
        }
        
        .page-link {
            border: none;
            border-radius: 50% !important;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #13547a;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .page-link:hover {
            background: #f0f8ff;
            color: #13547a;
            transform: translateY(-2px);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
        }
        
        /* RESULTS INFO */
        .results-info {
            background: white;
            border-radius: 10px;
            padding: 15px 25px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .results-info .found-count {
            color: #13547a;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .results-info .active-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-tag {
            background: #e0f7f5;
            color: #13547a;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-tag i {
            cursor: pointer;
            font-size: 0.8rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .filter-tag i:hover {
            opacity: 1;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .book-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-header h1 {
                font-size: 2.2rem;
            }
            
            .search-box {
                flex-direction: column;
            }
            
            .search-btn {
                width: 100%;
                height: 55px;
                margin-top: 10px;
            }
            
            .book-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 20px;
            }
            
            .book-cover-container {
                height: 280px;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .page-header .subtitle {
                font-size: 1rem;
            }
            
            .book-grid {
                grid-template-columns: 1fr;
            }
            
            .results-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin: 40px 0;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #80d0c7;
            margin-bottom: 25px;
            opacity: 0.8;
        }
        
        .empty-state h4 {
            color: #13547a;
            margin-bottom: 15px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .empty-state p {
            color: #666;
            max-width: 400px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        
        /* Loading animation */
        .loading-skeleton {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        /* Animation for new books */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .book-card {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        /* DOWNLOAD INFO */
        .download-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 5px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        .download-info i {
            color: #2196f3;
            margin-right: 8px;
        }
        
        /* BOOK INFO BADGES */
        .book-info-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .book-badge {
            background: #e9ecef;
            color: #495057;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .book-badge i {
            font-size: 0.7rem;
            margin-right: 4px;
        }
        
        .book-badge-language {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .book-badge-type {
            background: #d4edda;
            color: #155724;
        }
        
        .book-badge-isbn {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <!-- HEADER NAVIGASI -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book me-2"></i>
                <strong>JDIH DPRD Surakarta</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>/buku.php">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-book me-1"></i> Koleksi Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Koleksi Buku Perpustakaan</h1>
                    <p class="subtitle">
                        Koleksi lengkap buku hukum dan literatur pendukung di Perpustakaan JDIH DPRD Kota Surakarta
                    </p>
                    <div class="book-count">
                        <i class="bi bi-book me-2"></i>Total <?php echo $total_buku; ?> Buku Tersedia
                    </div>
                    <div class="download-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        Klik tombol <strong>Download</strong> untuk mengunduh file buku. File tersedia dalam format PDF, DOC, DOCX, dan format lainnya.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- SEARCH SECTION -->
            <div class="search-section">
                <h4 class="mb-4" style="color: #13547a;">
                    <i class="bi bi-search me-2"></i>Cari Buku
                </h4>
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Judul buku, penulis, penerbit, atau subjek..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search me-2"></i>CARI
                    </button>
                </form>
            </div>
            
            <div class="row">
                <!-- FILTER SIDEBAR -->
                <div class="col-lg-3">
                    <div class="filter-sidebar">
                        <h5 class="filter-title">
                            <i class="bi bi-funnel"></i>Filter Koleksi
                        </h5>
                        <form method="GET" action="" id="filterForm">
                            <?php if(isset($_GET['search'])): ?>
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                            <?php endif; ?>
                            
                            <div class="filter-category">
                                <h6><i class="bi bi-tags"></i> Bidang Hukum</h6>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="bidang_hukum" value="" 
                                                   <?php echo !isset($_GET['bidang_hukum']) ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Semua Bidang
                                        </label>
                                    </div>
                                    <?php if($bidang_result && $bidang_result->num_rows > 0): ?>
                                        <?php while($bidang = $bidang_result->fetch_assoc()): ?>
                                            <?php if(!empty($bidang['bidang_hukum'])): ?>
                                                <div class="filter-option">
                                                    <label>
                                                        <input type="radio" name="bidang_hukum" value="<?php echo $bidang['bidang_hukum']; ?>"
                                                               <?php echo (isset($_GET['bidang_hukum']) && $_GET['bidang_hukum'] == $bidang['bidang_hukum']) ? 'checked' : ''; ?>
                                                               onchange="document.getElementById('filterForm').submit()">
                                                        <?php echo htmlspecialchars($bidang['bidang_hukum']); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                        <?php $bidang_result->data_seek(0); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="filter-category">
                                <h6><i class="bi bi-calendar"></i> Tahun Terbit</h6>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="tahun" value="" 
                                                   <?php echo !isset($_GET['tahun']) ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Semua Tahun
                                        </label>
                                    </div>
                                    <?php if($years_result && $years_result->num_rows > 0): ?>
                                        <?php while($year = $years_result->fetch_assoc()): ?>
                                            <?php if(!empty($year['tahun_terbit'])): ?>
                                                <div class="filter-option">
                                                    <label>
                                                        <input type="radio" name="tahun" value="<?php echo $year['tahun_terbit']; ?>"
                                                               <?php echo (isset($_GET['tahun']) && $_GET['tahun'] == $year['tahun_terbit']) ? 'checked' : ''; ?>
                                                               onchange="document.getElementById('filterForm').submit()">
                                                        <?php echo $year['tahun_terbit']; ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                        <?php $years_result->data_seek(0); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="filter-category">
                                <h6><i class="bi bi-translate"></i> Bahasa</h6>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="bahasa" value="" 
                                                   <?php echo !isset($_GET['bahasa']) ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Semua Bahasa
                                        </label>
                                    </div>
                                    <?php if($bahasa_result && $bahasa_result->num_rows > 0): ?>
                                        <?php while($bahasa = $bahasa_result->fetch_assoc()): ?>
                                            <?php if(!empty($bahasa['bahasa'])): ?>
                                                <div class="filter-option">
                                                    <label>
                                                        <input type="radio" name="bahasa" value="<?php echo $bahasa['bahasa']; ?>"
                                                               <?php echo (isset($_GET['bahasa']) && $_GET['bahasa'] == $bahasa['bahasa']) ? 'checked' : ''; ?>
                                                               onchange="document.getElementById('filterForm').submit()">
                                                        <?php echo htmlspecialchars($bahasa['bahasa']); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                        <?php $bahasa_result->data_seek(0); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="filter-category">
                                <h6><i class="bi bi-file-earmark"></i> Tipe Dokumen</h6>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="tipe_dokumen" value="" 
                                                   <?php echo !isset($_GET['tipe_dokumen']) ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Semua Tipe
                                        </label>
                                    </div>
                                    <?php if($tipe_result && $tipe_result->num_rows > 0): ?>
                                        <?php while($tipe = $tipe_result->fetch_assoc()): ?>
                                            <?php if(!empty($tipe['tipe_dokumen'])): ?>
                                                <div class="filter-option">
                                                    <label>
                                                        <input type="radio" name="tipe_dokumen" value="<?php echo $tipe['tipe_dokumen']; ?>"
                                                               <?php echo (isset($_GET['tipe_dokumen']) && $_GET['tipe_dokumen'] == $tipe['tipe_dokumen']) ? 'checked' : ''; ?>
                                                               onchange="document.getElementById('filterForm').submit()">
                                                        <?php echo htmlspecialchars($tipe['tipe_dokumen']); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                        <?php $tipe_result->data_seek(0); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="filter-category">
                                <h6><i class="bi bi-check-circle"></i> Status Ketersediaan</h6>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="status" value="" 
                                                   <?php echo !isset($_GET['status']) ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Semua Status
                                        </label>
                                    </div>
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="status" value="tersedia"
                                                   <?php echo (isset($_GET['status']) && $_GET['status'] == 'tersedia') ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Tersedia
                                        </label>
                                    </div>
                                    <div class="filter-option">
                                        <label>
                                            <input type="radio" name="status" value="dipinjam"
                                                   <?php echo (isset($_GET['status']) && $_GET['status'] == 'dipinjam') ? 'checked' : ''; ?>
                                                   onchange="document.getElementById('filterForm').submit()">
                                            Dipinjam
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                            </button>
                        </form>
                    </div>
                    
                    <!-- LIBRARY INFO -->
                    <div class="filter-sidebar">
                        <h5 class="filter-title">
                            <i class="bi bi-info-circle"></i>Informasi Download
                        </h5>
                        <div class="mb-3">
                            <h6 style="color: #13547a; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="bi bi-download me-2"></i>Cara Download:
                            </h6>
                            <p style="color: #666; font-size: 0.9rem; margin-left: 24px;">
                                1. Klik tombol <strong>Download</strong><br>
                                2. File akan otomatis terunduh<br>
                                3. Simpan file di folder yang diinginkan
                            </p>
                        </div>
                        <div class="mb-3">
                            <h6 style="color: #13547a; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="bi bi-file-earmark me-2"></i>Format File:
                            </h6>
                            <p style="color: #666; font-size: 0.9rem; margin-left: 24px;">
                                • PDF (.pdf)<br>
                                • Word (.doc, .docx)<br>
                                • Gambar (.jpg, .png)<br>
                                • Lainnya (.txt, .zip)
                            </p>
                        </div>
                        <div>
                            <h6 style="color: #13547a; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="bi bi-shield-check me-2"></i>Keamanan:
                            </h6>
                            <p style="color: #666; font-size: 0.9rem; margin-left: 24px;">
                                Semua file telah dipindai dan aman dari virus
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- BOOK COLLECTION -->
                <div class="col-lg-9">
                    <!-- Results Info -->
                    <?php if(isset($_GET['search']) || isset($_GET['bidang_hukum']) || isset($_GET['tahun']) || isset($_GET['status']) || isset($_GET['bahasa']) || isset($_GET['tipe_dokumen'])): ?>
                        <div class="results-info">
                            <div class="found-count">
                                <?php 
                                $result_count = $final_result ? $final_result->num_rows : 0;
                                echo "<i class='bi bi-book me-2'></i>Ditemukan $result_count buku";
                                ?>
                            </div>
                            <div class="active-filters">
                                <?php if(isset($_GET['search'])): ?>
                                    <span class="filter-tag">
                                        Pencarian: "<?php echo htmlspecialchars($_GET['search']); ?>"
                                        <i class="bi bi-x" onclick="removeFilter('search')"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if(isset($_GET['bidang_hukum']) && !empty($_GET['bidang_hukum'])): ?>
                                    <span class="filter-tag">
                                        Bidang: <?php echo htmlspecialchars($_GET['bidang_hukum']); ?>
                                        <i class="bi bi-x" onclick="removeFilter('bidang_hukum')"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if(isset($_GET['tahun']) && !empty($_GET['tahun'])): ?>
                                    <span class="filter-tag">
                                        Tahun: <?php echo htmlspecialchars($_GET['tahun']); ?>
                                        <i class="bi bi-x" onclick="removeFilter('tahun')"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if(isset($_GET['bahasa']) && !empty($_GET['bahasa'])): ?>
                                    <span class="filter-tag">
                                        Bahasa: <?php echo htmlspecialchars($_GET['bahasa']); ?>
                                        <i class="bi bi-x" onclick="removeFilter('bahasa')"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if(isset($_GET['tipe_dokumen']) && !empty($_GET['tipe_dokumen'])): ?>
                                    <span class="filter-tag">
                                        Tipe: <?php echo htmlspecialchars($_GET['tipe_dokumen']); ?>
                                        <i class="bi bi-x" onclick="removeFilter('tipe_dokumen')"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if(isset($_GET['status']) && !empty($_GET['status'])): ?>
                                    <span class="filter-tag">
                                        Status: <?php echo htmlspecialchars(ucfirst($_GET['status'])); ?>
                                        <i class="bi bi-x" onclick="removeFilter('status')"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Books Grid -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h2 class="section-title">Koleksi Buku 
                                <?php if(isset($_GET['search'])): ?>
                                    <small class="text-muted">Hasil pencarian: "<?php echo htmlspecialchars($_GET['search']); ?>"</small>
                                <?php endif; ?>
                            </h2>
                        </div>
                    </div>
                    
                    <?php if($final_result && $final_result->num_rows > 0): ?>
                        <div class="book-grid">
                            <?php while($buku = $final_result->fetch_assoc()): ?>
                                <?php
                                // Tentukan status ketersediaan
                                $status = ($buku['status_ketersediaan'] == 'tersedia' && $buku['jumlah_tersedia'] > 0) ? 'tersedia' : 'dipinjam';
                                $status_class = ($status == 'tersedia') ? 'status-available' : 'status-borrowed';
                                $status_text = ($status == 'tersedia') ? 'Tersedia' : 'Dipinjam';
                                
                                // Gunakan fungsi getCoverUrl untuk mendapatkan URL gambar yang benar
                                $cover_image = getCoverUrl($buku['sampul_path']);
                                
                                // Dapatkan URL untuk download file (menggunakan file_buku_path)
                                $file_url = getFileDownloadUrl($buku['file_buku_path']);
                                
                                // Cek apakah file tersedia untuk didownload
                                $file_available = !empty($file_url) && !empty($buku['file_buku_path']);
                                
                                // Truncate judul jika terlalu panjang
                                $judul = htmlspecialchars($buku['judul']);
                                if(strlen($judul) > 80) {
                                    $judul = substr($judul, 0, 77) . '...';
                                }
                                
                                // Dapatkan ekstensi file
                                $file_extension = '';
                                if(!empty($buku['file_buku_path'])) {
                                    $file_extension = strtoupper(pathinfo($buku['file_buku_path'], PATHINFO_EXTENSION));
                                }
                                
                                // Siapkan informasi tambahan
                                $penerbit_info = '';
                                if(!empty($buku['penerbit'])) {
                                    $penerbit_info = $buku['penerbit'];
                                    if(!empty($buku['kota_terbit'])) {
                                        $penerbit_info .= ', ' . $buku['kota_terbit'];
                                    }
                                }
                                ?>
                                
                                <div class="book-card">
                                    <div class="book-cover-container">
                                        <img src="<?php echo $cover_image; ?>" 
                                             alt="<?php echo htmlspecialchars($buku['judul']); ?>" 
                                             class="book-cover"
                                             onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>/assets/img/default-book.jpg';">
                                    </div>
                                    <div class="book-content">
                                        <!-- Badge informasi -->
                                        <div class="book-info-badges">
                                            <?php if(!empty($buku['bidang_hukum'])): ?>
                                                <span class="book-category"><?php echo htmlspecialchars($buku['bidang_hukum']); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($buku['bahasa'])): ?>
                                                <span class="book-badge book-badge-language">
                                                    <i class="bi bi-translate"></i><?php echo htmlspecialchars($buku['bahasa']); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($buku['tipe_dokumen'])): ?>
                                                <span class="book-badge book-badge-type">
                                                    <i class="bi bi-file-earmark"></i><?php echo htmlspecialchars($buku['tipe_dokumen']); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($buku['isbn'])): ?>
                                                <span class="book-badge book-badge-isbn">
                                                    <i class="bi bi-upc-scan"></i>ISBN
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <h4 class="book-title" title="<?php echo htmlspecialchars($buku['judul']); ?>">
                                            <?php echo $judul; ?>
                                        </h4>
                                        
                                        <?php if(!empty($buku['pengarang'])): ?>
                                            <p class="book-author">
                                                <i class="bi bi-person"></i>
                                                <?php echo htmlspecialchars($buku['pengarang']); ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($penerbit_info)): ?>
                                            <p class="book-author" style="font-size: 0.85rem; margin-bottom: 10px;">
                                                <i class="bi bi-building"></i>
                                                <?php echo htmlspecialchars($penerbit_info); ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="book-meta">
                                            <?php if(!empty($buku['tahun_terbit'])): ?>
                                                <span class="book-year">
                                                    <i class="bi bi-calendar"></i>
                                                    <?php echo $buku['tahun_terbit']; ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="book-status <?php echo $status_class; ?>">
                                                <?php if($status == 'tersedia'): ?>
                                                    <i class="bi bi-check-circle me-1"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-x-circle me-1"></i>
                                                <?php endif; ?>
                                                <?php echo $status_text; ?>
                                            </span>
                                        </div>
                                        
                                        <div class="book-actions">
                                            <a href="?page=detail-buku&id=<?php echo $buku['id_buku']; ?>" class="btn-detail">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            
                                            <?php if($file_available): ?>
                                                <a href="<?php echo $file_url; ?>" class="btn-download">
                                                    <i class="bi bi-download"></i> Download
                                                    <?php if($file_extension): ?>
                                                        <small>(<?php echo $file_extension; ?>)</small>
                                                    <?php endif; ?>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn-download" disabled>
                                                    <i class="bi bi-download"></i> File Tidak Tersedia
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($file_available && !empty($buku['file_buku_path'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle"></i> 
                                                    File: <?php echo htmlspecialchars(basename($buku['file_buku_path'])); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <!-- Empty state -->
                        <div class="empty-state">
                            <i class="bi bi-book-x"></i>
                            <h4>Buku Tidak Ditemukan</h4>
                            <p>Maaf, tidak ada buku yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="<?php echo BASE_URL; ?>/buku.php" class="btn btn-primary" style="background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); border: none; padding: 10px 30px;">
                                    <i class="bi bi-house-door me-2"></i>Lihat Semua Buku
                                </a>
                                <button class="btn btn-outline-primary" onclick="resetFilters()" style="padding: 10px 30px;">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- PAGINATION -->
                    <?php if($final_result && $final_result->num_rows > 0): ?>
                        <div class="pagination-container">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item <?php echo (!isset($_GET['page']) || $_GET['page'] == 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?<?php 
                                            $params = $_GET;
                                            if(isset($params['page'])) unset($params['page']);
                                            $params['page'] = (isset($_GET['page']) && $_GET['page'] > 1) ? $_GET['page']-1 : 1;
                                            echo http_build_query($params);
                                        ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <?php for($i = 1; $i <= 3; $i++): ?>
                                        <li class="page-item <?php echo (isset($_GET['page']) ? $_GET['page'] : 1) == $i ? 'active' : ''; ?>">
                                            <a class="page-link" href="?<?php 
                                                $params = $_GET;
                                                $params['page'] = $i;
                                                echo http_build_query($params);
                                            ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?php 
                                            $params = $_GET;
                                            $params['page'] = isset($_GET['page']) ? $_GET['page']+1 : 2;
                                            echo http_build_query($params);
                                        ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="bi bi-book me-2"></i>JDIH DPRD Surakarta</h5>
                    <p class="mt-3">Sistem Informasi Perpustakaan Digital JDIH DPRD Kota Surakarta</p>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled mt-3">
                        <li><i class="bi bi-geo-alt me-2"></i>Jl. Jenderal Sudirman No. 2, Surakarta</li>
                        <li><i class="bi bi-telephone me-2"></i>(0271) 1234567</li>
                        <li><i class="bi bi-envelope me-2"></i>info@jdih-surakarta.go.id</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Download Center</h5>
                    <p class="mt-3">Semua file buku tersedia untuk diunduh secara gratis. Pastikan koneksi internet stabil untuk pengalaman download yang optimal.</p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> JDIH DPRD Kota Surakarta. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JavaScript khusus untuk halaman buku
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchBtn = document.querySelector('.search-btn');
            const searchInput = document.querySelector('.search-input');
            
            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function(e) {
                    const query = searchInput.value.trim();
                    if (!query) {
                        alert('Silakan masukkan kata kunci pencarian');
                        e.preventDefault();
                        return false;
                    }
                    return true;
                });
                
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (!query) {
                            alert('Silakan masukkan kata kunci pencarian');
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            }
            
            // Book card hover effect
            const bookCards = document.querySelectorAll('.book-card');
            bookCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Reset filters function
            window.resetFilters = function() {
                window.location.href = '<?php echo BASE_URL; ?>/buku.php';
            }
            
            // Remove single filter
            window.removeFilter = function(filterName) {
                const url = new URL(window.location.href);
                url.searchParams.delete(filterName);
                window.location.href = url.toString();
            }
            
            // Auto-submit filter form when selection changes
            const filterRadios = document.querySelectorAll('#filterForm input[type="radio"]');
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });
            
            // Download button confirmation
            const downloadButtons = document.querySelectorAll('.btn-download:not(:disabled)');
            downloadButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Optional: Add confirmation or tracking
                    const filename = this.querySelector('small') ? 
                        this.textContent.trim() : 'File';
                    console.log('Memulai download:', filename);
                });
            });
        });
    </script>
</body>
</html>

<?php 
// Tutup koneksi database
if(isset($koneksi)) {
    $koneksi->close();
}
?>