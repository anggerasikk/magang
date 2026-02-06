
<?php
// download.php - UNIVERSAL FILE DOWNLOAD HANDLER
session_start();
include_once "a_db.php";
include_once "a_encryption.php";

// ============================================
// KONFIGURASI PATH
// ============================================
// Cek environment untuk menentukan path
if (strpos($_SERVER['DOCUMENT_ROOT'], 'laragon') !== false) {
    // Laragon environment
    $base_upload_path = "C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/";
} else {
    // XAMPP environment
    $base_upload_path = "C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/";
}

$pdf_base_path = $base_upload_path . "pdf/";
$buku_base_path = $base_upload_path . "buku/";

// ============================================
// FUNGSI BANTU UNTUK MENCARI FILE
// ============================================

// Fungsi untuk mencari file PDF dokumen regulasi
function findPDFFile($id) {
    global $pdf_base_path, $koneksi;
    
    if (empty($id) || !is_numeric($id)) {
        return null;
    }
    
    // Lokasi yang mungkin untuk dokumen regulasi
    $possible_locations = [
        $pdf_base_path . $id . '.pdf',
        $pdf_base_path . $id . '.PDF',
        $pdf_base_path . 'doc_' . $id . '.pdf',
        $pdf_base_path . 'document_' . $id . '.pdf',
        $pdf_base_path . 'keputusan_' . $id . '.pdf',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/panel_control/uploads/pdf/' . $id . '.pdf',
        'C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/' . $id . '.pdf',
        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $id . '.pdf'
    ];
    
    // Cari dari database dulu
    $query = "SELECT pdf_path, fileDownload, jenis FROM uploads WHERE idData = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . mysqli_error($koneksi));
        return null;
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $file_from_db = !empty($row['pdf_path']) ? $row['pdf_path'] : $row['fileDownload'];
        if (!empty($file_from_db)) {
            $clean_filename = basename($file_from_db);
            $possible_locations[] = $pdf_base_path . $clean_filename;
            $possible_locations[] = $pdf_base_path . $id . '_' . $clean_filename;
            $possible_locations[] = dirname($pdf_base_path) . '/' . $clean_filename;
        }
    }
    mysqli_stmt_close($stmt);
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        $location = str_replace('\\', '/', $location);
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            if (stripos($mime_type, 'pdf') !== false || stripos($mime_type, 'application') !== false) {
                return $location;
            }
        }
    }
    
    return null;
}

// Fungsi untuk mencari file BUKU
function findBukuFile($id) {
    global $buku_base_path, $koneksi;
    
    if (empty($id) || !is_numeric($id)) {
        return null;
    }
    
    // Lokasi yang mungkin untuk buku
    $possible_locations = [
        $buku_base_path . $id . '.pdf',
        $buku_base_path . $id . '.PDF',
        $buku_base_path . $id . '.doc',
        $buku_base_path . $id . '.docx',
        $buku_base_path . 'buku_' . $id . '.pdf',
        $buku_base_path . 'book_' . $id . '.pdf',
        $buku_base_path . 'file_' . $id . '.pdf',
        $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang/panel_control/uploads/buku/' . $id . '.pdf',
        'C:/xampp/htdocs/jdihcilacap_pasang/panel_control/uploads/buku/' . $id . '.pdf',
        'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/buku/' . $id . '.pdf'
    ];
    
    // Cari dari database dulu
    $query = "SELECT file_buku_path, judul FROM buku_perpustakaan WHERE id_buku = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    if (!$stmt) {
        error_log("Failed to prepare statement for buku: " . mysqli_error($koneksi));
        return null;
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $file_from_db = $row['file_buku_path'] ?? '';
        if (!empty($file_from_db)) {
            $clean_filename = basename($file_from_db);
            $possible_locations[] = $buku_base_path . $clean_filename;
            $possible_locations[] = $buku_base_path . $id . '_' . $clean_filename;
            $possible_locations[] = dirname($buku_base_path) . '/' . $clean_filename;
            
            // Tambahkan ekstensi alternatif
            $base_name = pathinfo($clean_filename, PATHINFO_FILENAME);
            $possible_locations[] = $buku_base_path . $base_name . '.pdf';
            $possible_locations[] = $buku_base_path . $base_name . '.PDF';
            $possible_locations[] = $buku_base_path . $base_name . '.doc';
            $possible_locations[] = $buku_base_path . $base_name . '.docx';
        }
    }
    mysqli_stmt_close($stmt);
    
    // Cek semua lokasi
    foreach ($possible_locations as $location) {
        $location = str_replace('\\', '/', $location);
        if (file_exists($location) && is_file($location) && is_readable($location)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $location);
            finfo_close($finfo);
            
            $allowed_mimes = [
                'application/pdf',
                'application/x-pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            if (in_array($mime_type, $allowed_mimes)) {
                return $location;
            }
        }
    }
    
    return null;
}

// ============================================
// FUNGSI UTAMA DOWNLOAD
// ============================================

function downloadFile($file_path, $filename, $type = 'pdf') {
    if (!file_exists($file_path)) {
        return false;
    }
    
    // Dapatkan MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_path);
    finfo_close($finfo);
    
    // Set content type berdasarkan file
    $content_type = $mime_type;
    
    // Set headers untuk download
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Clean output buffer
    ob_clean();
    flush();
    
    // Read file
    readfile($file_path);
    return true;
}

// ============================================
// PROSES DOWNLOAD BERDASARKAN TOKEN
// ============================================

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Dekode token untuk mendapatkan ID dan tipe
    $decoded = base64_decode($token);
    
    if ($decoded) {
        $parts = explode('_', $decoded);
        
        // Format token: [prefix]_[type]_[id]_[timestamp]
        if (count($parts) >= 3) {
            $prefix = $parts[0] ?? '';
            $type = $parts[1] ?? '';
            $id = isset($parts[2]) ? (int)$parts[2] : 0;
            
            // Validasi ID
            if ($id <= 0) {
                http_response_code(400);
                echo "Token tidak valid (ID invalid).";
                exit;
            }
            
            $file_path = null;
            $filename = "document";
            $document_title = "Dokumen";
            
            // Cari file berdasarkan tipe
            if ($type === 'buku') {
                // Download buku perpustakaan
                $file_path = findBukuFile($id);
                
                // Dapatkan info buku
                $query = "SELECT judul, file_buku_path FROM buku_perpustakaan WHERE id_buku = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $document_title = $row['judul'];
                        if (!empty($row['file_buku_path'])) {
                            $filename = basename($row['file_buku_path']);
                        } else {
                            $filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $row['judul']) . '.pdf';
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                
            } else {
                // Download dokumen regulasi (default)
                $file_path = findPDFFile($id);
                
                // Dapatkan info dokumen
                $query = "SELECT noPeraturan, judul FROM uploads WHERE idData = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $document_title = $row['judul'];
                        $filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $row['noPeraturan']) . '.pdf';
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            
            // Jika file ditemukan, proses download
            if ($file_path && file_exists($file_path)) {
                // Log download activity (optional)
                error_log("Download: " . $document_title . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date('Y-m-d H:i:s'));
                
                // Proses download
                if (downloadFile($file_path, $filename)) {
                    exit; // Download berhasil
                } else {
                    http_response_code(500);
                    echo "Gagal memproses file.";
                    exit;
                }
            } else {
                // File tidak ditemukan
                http_response_code(404);
                echo "File tidak ditemukan di server.";
                error_log("File not found for ID: " . $id . " | Type: " . $type . " | Path: " . ($file_path ?? 'null'));
                exit;
            }
            
        } else {
            http_response_code(400);
            echo "Format token tidak valid.";
            exit;
        }
    } else {
        http_response_code(400);
        echo "Token tidak dapat didekode.";
        exit;
    }
    
} elseif (isset($_GET['id']) && isset($_GET['type'])) {
    // Alternatif: menggunakan parameter id dan type langsung (untuk backward compatibility)
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    
    if ($id <= 0) {
        http_response_code(400);
        echo "ID tidak valid.";
        exit;
    }
    
    $file_path = null;
    $filename = "document";
    
    if ($type === 'buku') {
        $file_path = findBukuFile($id);
        $filename = "buku_" . $id . ".pdf";
    } else {
        $file_path = findPDFFile($id);
        $filename = "document_" . $id . ".pdf";
    }
    
    if ($file_path && file_exists($file_path)) {
        downloadFile($file_path, $filename);
        exit;
    } else {
        http_response_code(404);
        echo "File tidak ditemukan.";
        exit;
    }
    
} else {
    http_response_code(400);
    echo "Parameter tidak lengkap. Gunakan token atau id+type.";
    exit;
}
?>
