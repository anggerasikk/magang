<?php
// beranda.php - VERSION ENHANCED SEARCH - NO CHARACTER LIMIT - FIXED TOKENS
// PERBAIKAN UTAMA: 
// 1. Konsistensi token encoding untuk semua tipe dokumen
// 2. TAMBAHAN: Pencarian di tabel monografi (sesuai dengan detail-monografi.php)
// 3. Perbaikan URL detail monografi menggunakan format token yang sama dengan detail-monografi.php
// 4. HAPUS BATAS MINIMAL 5 KARAKTER untuk pencarian

$pageTitle = 'Beranda - JDIH DPRD Kota Surakarta';
include 'header.php';

// Include file koneksi database
include 'a_db.php';
require_once 'a_functions.php'; // Include encryption functions

// ============================================
// FUNGSI ENKRIPSI/DESKRIPSI YANG KONSISTEN
// ============================================

/**
 * FUNGSI ENCODE TOKEN UNTUK SEMUA TIPE - DIKONSISTENKAN
 * Semua fungsi mengikuti format yang sama: jdih_[jenis]_[id]_[timestamp]
 */

// Periksa dan buat fungsi jika belum ada
if (!function_exists('encode_buku_id')) {
    function encode_buku_id($id) {
        if (empty($id) || !is_numeric($id)) return '';
        $timestamp = time();
        return base64_encode("jdih_buku_{$id}_{$timestamp}");
    }
}

if (!function_exists('encode_yurisprudensi_token')) {
    function encode_yurisprudensi_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        $timestamp = time();
        return base64_encode("jdih_yuri_{$id}_{$timestamp}");
    }
}

if (!function_exists('encode_article_token')) {
    function encode_article_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        $timestamp = time();
        return base64_encode("jdih_artikel_{$id}_{$timestamp}");
    }
}

// ============================================
// PERBAIKAN UTAMA: FUNGSI ENCODE MONOGRAFI SESUAI DENGAN detail-monografi.php
// ============================================
if (!function_exists('encode_monografi_token')) {
    function encode_monografi_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        // SESUAI DENGAN FORMAT DI DETAIL-MONOGRAFI.PHP: 'mono_' . $id
        return base64_encode('mono_' . $id);
    }
}

if (!function_exists('encode_kegiatan_token')) {
    function encode_kegiatan_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        $timestamp = time();
        return base64_encode("jdih_kegiatan_{$id}_{$timestamp}");
    }
}

// Fungsi untuk dokumen biasa (uploads) - PERBAIKAN UTAMA: SAMA DENGAN DETAIL-KEPUTUSAN
if (!function_exists('encode_dokumen_token')) {
    function encode_dokumen_token($id) {
        if (empty($id) || !is_numeric($id)) return '';
        $timestamp = time();
        // Format yang sama dengan decode_id() di detail-keputusan.php
        return base64_encode("jdih_{$id}_{$timestamp}");
    }
}



// ============================================
// FUNGSI TAMBAHAN UNTUK ROUTING
// ============================================
function getDetailPageByJenis($jenis) {
    $jenis = strtolower(trim($jenis));

    $detail_keputusan = [
        'keputisan dprd', 'keputusan dprd',
        'keputusan sekretariat', 'keputissen sekretaris dprd',
        'keputusan pimpinan', 'keputissen pimpinan dprd',
        'peraturan dprd',
        'peraturan daerah',
        'english legal product'
    ];

    // ============================================
    // PERBAIKAN: KATEGORI MONOGRAFI SESUAI DENGAN detail-monografi.php
    // ============================================
    $detail_monografi = [
        'pokok pikiran',
        'kajian raperda',
        'naskah akademik',
        'dokumen langka',
        'risalah',
        'hasil fasilitasi',
        'hasil harmonisasi',
        'penulisan karya ilmiah',
        'himpunan peraturan',
        // Tambahan sesuai dengan detail-monografi.php
        'keputusan',
        'peraturan',
        'kajian',
        'raperda'
    ];

    if (in_array($jenis, $detail_keputusan)) {
        return 'detail-keputusan';
    }

    if (in_array($jenis, $detail_monografi)) {
        return 'detail-monografi';
    }

    if ($jenis === 'artikel hukum') {
        return 'detail-artikel';
    }

    if ($jenis === 'yurisprudensi') {
        return 'detail-yurisprudensi';
    }

    if ($jenis === 'buku') {
        return 'detail-buku';
    }

    if ($jenis === 'artikel kegiatan') {
        return 'detail-kegiatan';
    }

    // default aman
    return 'detail-keputusan';
}

// ============================================
// BASE PATH KONSTAN - dipakai di semua fungsi
// ============================================
define('UPLOAD_BASE_URL', '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/');
define('UPLOAD_BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/');

// ============================================
// FUNGSI RESOLVE THUMBNAIL UNTUK KEGIATAN - DIPERBAIKI
// ============================================

/**
 * Validasi path gambar untuk mencegah directory traversal
 */
function validate_image_path($path) {
    if (empty($path)) return false;
    
    // Bersihkan path dari directory traversal
    $clean_path = str_replace(['../', './', '..\\', '.\\'], '', $path);
    $clean_path = basename($clean_path);
    
    // Validasi ekstensi file
    $extension = strtolower(pathinfo($clean_path, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    
    if (!in_array($extension, $allowed_extensions, true)) {
        return false;
    }
    
    // Validasi nama file
    $filename = pathinfo($clean_path, PATHINFO_FILENAME);
    if (!preg_match('/^[a-zA-Z0-9_\-\s\.]+$/', $filename)) {
        return false;
    }
    
    return $clean_path;
}

/**
 * RESOLVE THUMBNAIL UNTUK KEGIATAN - DIPERBAIKI
 */
function resolveKegiatanThumbnail($thumbnail_path, $artikel_id = null) {
    // Default placeholder image
    $default_image = 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
    
    // Inisialisasi dengan default
    $thumbnail = $default_image;
    
    // Cek apakah thumbnail_path ada dan valid
    if (!empty($thumbnail_path)) {
        $thumbnail_path = trim($thumbnail_path);
        
        // Jika path adalah URL lengkap (misal dari Unsplash), return langsung
        if (filter_var($thumbnail_path, FILTER_VALIDATE_URL)) {
            return $thumbnail_path;
        }
        
        // Validasi path lokal
        $validated_filename = validate_image_path($thumbnail_path);
        
        if ($validated_filename !== false) {
            // DAFTAR SEMUA LOKASI YANG MUNGKIN - DIPERLUAS
            $allowed_dirs = [
                // Laragon structure
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/images/',
                
                // XAMPP structure
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/images/',
                
                // Document root based - SEMUA KEMUNGKINAN
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/',
                $_SERVER['DOCUMENT_ROOT'] . '/',
            ];
            
            foreach ($allowed_dirs as $base_dir) {
                $full_path = $base_dir . $validated_filename;
                if (file_exists($full_path) && is_file($full_path)) {
                    // Verifikasi path
                    $real_path = realpath($full_path);
                    $real_base = realpath($base_dir);
                    
                    if ($real_path && $real_base && strpos($real_path, $real_base) === 0) {
                        // Verifikasi ini benar-benar file gambar
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $real_path);
                        finfo_close($finfo);
                        
                        if (strpos($mime_type, 'image/') === 0) {
                            // Buat URL web - Konversi path dengan lebih fleksibel
                            $web_path = '';
                            
                            if (strpos($real_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                                $web_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_path);
                            }
                            elseif (strpos($real_path, 'C:\\laragon\\www') === 0 || strpos($real_path, 'C:/laragon/www') === 0) {
                                $web_path = str_replace(['C:\\laragon\\www', 'C:/laragon/www'], '', $real_path);
                            }
                            elseif (strpos($real_path, 'C:\\xampp\\htdocs') === 0 || strpos($real_path, 'C:/xampp/htdocs') === 0) {
                                $web_path = str_replace(['C:\\xampp\\htdocs', 'C:/xampp/htdocs'], '', $real_path);
                            }
                            
                            // Normalize path
                            $web_path = str_replace('\\', '/', $web_path);
                            if (strpos($web_path, '/') !== 0) {
                                $web_path = '/' . $web_path;
                            }
                            
                            $thumbnail = $web_path;
                            break;
                        }
                    }
                }
            }
        }
    }
    
    // Fallback: Cari gambar berdasarkan ID artikel (jika ada)
    if ($thumbnail === $default_image && $artikel_id !== null) {
        // Validasi ID artikel
        if (is_numeric($artikel_id)) {
            $possible_image_files = [
                'artikel_' . $artikel_id . '.jpg',
                'kegiatan_' . $artikel_id . '.jpg',
                'article_' . $artikel_id . '.jpg',
                'kegiatan_' . $artikel_id . '.jpeg',
                'artikel_' . $artikel_id . '.jpeg',
                'article_' . $artikel_id . '.jpeg',
                'kegiatan_' . $artikel_id . '.png',
                'artikel_' . $artikel_id . '.png',
                'article_' . $artikel_id . '.png',
                $artikel_id . '.jpg',
                $artikel_id . '.jpeg',
                $artikel_id . '.png',
                $artikel_id . '.webp',
                'thumbnail_' . $artikel_id . '.jpg',
                'thumbnail_' . $artikel_id . '.jpeg',
                'thumbnail_' . $artikel_id . '.png',
            ];
            
            $base_dirs = [
                // Laragon
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                'C:/laragon/www/jdihcilacap_pasang_2026/uploads/images/',
                
                // XAMPP
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                'C:/xampp/htdocs/jdihcilacap_pasang_2026/uploads/images/',
                
                // Document root
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/panel_control/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/jdihcilacap_pasang_2026/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/panel_control/uploads/images/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/artikel_kegiatan/',
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/',
            ];
            
            foreach ($base_dirs as $base_dir) {
                foreach ($possible_image_files as $image_file) {
                    $check_path = $base_dir . $image_file;
                    if (file_exists($check_path)) {
                        $real_path = realpath($check_path);
                        $real_base = realpath($base_dir);
                        
                        if ($real_path && $real_base && strpos($real_path, $real_base) === 0) {
                            // Konversi path dengan lebih fleksibel
                            $web_path = '';
                            
                            if (strpos($real_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                                $web_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_path);
                            }
                            elseif (strpos($real_path, 'C:\\laragon\\www') === 0 || strpos($real_path, 'C:/laragon/www') === 0) {
                                $web_path = str_replace(['C:\\laragon\\www', 'C:/laragon/www'], '', $real_path);
                            }
                            elseif (strpos($real_path, 'C:\\xampp\\htdocs') === 0 || strpos($real_path, 'C:/xampp/htdocs') === 0) {
                                $web_path = str_replace(['C:\\xampp\\htdocs', 'C:/xampp/htdocs'], '', $real_path);
                            }
                            
                            // Normalize path
                            $web_path = str_replace('\\', '/', $web_path);
                            if (strpos($web_path, '/') !== 0) {
                                $web_path = '/' . $web_path;
                            }
                            
                            $thumbnail = $web_path;
                            break 2;
                        }
                    }
                }
            }
        }
    }
    
    return $thumbnail;
}

/**
 * FUNGSI HELPER UNTUK IMAGE RESOLUTION - DIPERBAIKI LAGI
 */

/**
 * Get default placeholder image URL
 */
function getDefaultImage() {
    return 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';
}

/**
 * Resolve URL gambar dari kolom image_path atau image_name untuk tabel UPLOADS
 * DIPERBAIKI: Cari di semua lokasi yang mungkin
 */
function resolveImageUrl($image_name, $image_path) {
    // Prioritas 1: Gunakan image_path jika tersedia
    if (!empty($image_path)) {
        $normalized = str_replace('\\', '/', trim($image_path));
        
        // Jika sudah format lengkap dengan 'uploads/'
        if (strpos($normalized, 'uploads/') === 0) {
            // Pastikan ada slash di depan
            if (strpos($normalized, '/') !== 0) {
                $normalized = '/' . $normalized;
            }
            
            // Coba akses langsung
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $normalized;
            if (file_exists($full_path)) {
                return $normalized;
            }
            
            // Coba alternatif
            $alt_paths = [
                '/jdihcilacap_pasang_2026' . $normalized,
                '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026' . $normalized,
                str_replace('/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026', '', $normalized)
            ];
            
            foreach ($alt_paths as $alt_path) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $alt_path)) {
                    return $alt_path;
                }
            }
        }
        
        // Jika hanya nama file (tanpa folder)
        if (strpos($normalized, '/') === false && strpos($normalized, '\\') === false) {
            // Cek semua kemungkinan lokasi
            $filename = $normalized;
            $possible_locations = [
                // Path relatif ke berbagai folder
                '/uploads/images/' . $filename,
                '/uploads/' . $filename,
                '/panel_control/uploads/images/' . $filename,
                '/panel_control/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/panel_control/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/' . $filename,
            ];
            
            foreach ($possible_locations as $location) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $location)) {
                    return $location;
                }
            }
            
            // Uploads umum: img_xxx.png
            if (strpos($filename, 'img_') === 0) {
                $locations = [
                    '/uploads/images/' . $filename,
                    '/panel_control/uploads/images/' . $filename,
                    '/jdihcilacap_pasang_2026/uploads/images/' . $filename,
                    '/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $filename,
                ];
                
                foreach ($locations as $location) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $location)) {
                        return $location;
                    }
                }
            }
        }
    }
    
    // Prioritas 2: Gunakan image_name jika image_path kosong
    if (!empty($image_name)) {
        $normalized = str_replace('\\', '/', trim($image_name));
        
        // Jika sudah format lengkap dengan 'uploads/'
        if (strpos($normalized, 'uploads/') === 0) {
            if (strpos($normalized, '/') !== 0) {
                $normalized = '/' . $normalized;
            }
            
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                return $normalized;
            }
        }
        
        // Jika hanya nama file
        if (strpos($normalized, '/') === false && strpos($normalized, '\\') === false) {
            $filename = $normalized;
            
            // Cek semua kemungkinan lokasi
            $possible_locations = [
                '/uploads/images/' . $filename,
                '/uploads/' . $filename,
                '/panel_control/uploads/images/' . $filename,
                '/panel_control/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/uploads/' . $filename,
                '/jdihcilacap_pasang_2026/panel_control/uploads/images/' . $filename,
                '/jdihcilacap_pasang_2026/panel_control/uploads/' . $filename,
            ];
            
            foreach ($possible_locations as $location) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $location)) {
                    return $location;
                }
            }
        }
    }
    
    // Tidak ditemukan
    return false;
}

/**
 * Resolve thumbnail untuk artikel_kegiatan
 */
function resolveThumbnailUrl($thumbnail_path) {
    return resolveKegiatanThumbnail($thumbnail_path);
}

/**
 * Resolve PDF URL untuk tabel UPLOADS
 */
function resolvePdfUrl($pdf_path, $fileDownload) {
    // Prioritas 1: pdf_path
    if (!empty($pdf_path)) {
        $normalized = str_replace('\\', '/', trim($pdf_path));
        
        // Jika sudah format lengkap dengan 'uploads/'
        if (strpos($normalized, 'uploads/') === 0) {
            if (strpos($normalized, '/') !== 0) {
                $normalized = '/' . $normalized;
            }
            
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                return $normalized;
            }
        }
        
        // Jika hanya nama file
        if (strpos($normalized, '/') === false && strpos($normalized, '\\') === false) {
            // Cek semua kemungkinan lokasi PDF
            $locations = [
                '/uploads/pdf/' . $normalized,
                '/uploads/' . $normalized,
                '/panel_control/uploads/pdf/' . $normalized,
                '/panel_control/uploads/' . $normalized,
                '/jdihcilacap_pasang_2026/uploads/pdf/' . $normalized,
                '/jdihcilacap_pasang_2026/uploads/' . $normalized,
                '/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $normalized,
                '/jdihcilacap_pasang_2026/panel_control/uploads/' . $normalized,
            ];
            
            foreach ($locations as $location) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $location)) {
                    return $location;
                }
            }
        }
    }
    
    // Prioritas 2: fileDownload
    if (!empty($fileDownload)) {
        $normalized = str_replace('\\', '/', trim($fileDownload));
        
        // Jika sudah format lengkap dengan 'uploads/'
        if (strpos($normalized, 'uploads/') === 0) {
            if (strpos($normalized, '/') !== 0) {
                $normalized = '/' . $normalized;
            }
            
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                return $normalized;
            }
        }
        
        // Jika hanya nama file
        if (strpos($normalized, '/') === false && strpos($normalized, '\\') === false) {
            $locations = [
                '/uploads/pdf/' . $normalized,
                '/uploads/' . $normalized,
                '/panel_control/uploads/pdf/' . $normalized,
                '/panel_control/uploads/' . $normalized,
                '/jdihcilacap_pasang_2026/uploads/pdf/' . $normalized,
                '/jdihcilacap_pasang_2026/uploads/' . $normalized,
                '/jdihcilacap_pasang_2026/panel_control/uploads/pdf/' . $normalized,
                '/jdihcilacap_pasang_2026/panel_control/uploads/' . $normalized,
            ];
            
            foreach ($locations as $location) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $location)) {
                    return $location;
                }
            }
        }
    }
    
    return false;
}

// ============================================
// SISTEM PENCARIAN GLOBAL YANG DIPERBAIKI - TANPA BATAS KARAKTER
// ============================================

/**
 * ENHANCED SEARCH FUNCTION - Tanpa batas minimal karakter, cari semua field
 * PERBAIKAN: TAMBAHAN pencarian di tabel monografi
 */
function searchGlobalEnhanced($keyword, $koneksi) {
    $results = [];
    
    // Validasi: hanya pastikan keyword tidak kosong
    if (empty($keyword)) {
        return $results;
    }
    
    $keyword_escaped = mysqli_real_escape_string($koneksi, trim($keyword));
    $keyword_lower = strtolower($keyword_escaped);
    
    // ============================================
    // 1. SEARCH DI TABEL YURISPRUDENSI - DIPERBAIKI PERTAMA
    // ============================================
    $query_yurisprudensi = "
        SELECT 
            'yurisprudensi' as tipe,
            id_yurisprudensi as id,
            judul,
            nomor_putusan as noPeraturan,
            tanggal_dibacakan as tanggal_penetapan,
            jenis_peradilan as jenis,
            singkatan_jenis_peradilan as singkatanJenis,
            cover_path as image_name,
            '' as image_path,
            lampiran_path as pdf_path,
            '' as fileDownload,
            status_putusan as status,
            teu_badan as sumber,
            bidang_hukum_jenis_perkara as bidangHukum
        FROM yurisprudensi 
        WHERE (
            LOWER(judul) LIKE LOWER('%$keyword_escaped%') 
            OR LOWER(nomor_putusan) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(teu_badan) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(kata_kunci) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(bidang_hukum_jenis_perkara) LIKE LOWER('%$keyword_escaped%')
        )
        ORDER BY tanggal_dibacakan DESC
        LIMIT 20
    ";
    
    $result_yurisprudensi = mysqli_query($koneksi, $query_yurisprudensi);
    if ($result_yurisprudensi) {
        while ($row = mysqli_fetch_assoc($result_yurisprudensi)) {
            $row['tipe_detail'] = 'yurisprudensi';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            $results[] = $row;
        }
    } else {
        error_log("Search query yurisprudensi error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // 2. SEARCH DI TABEL UPLOADS (DOKUMEN UTAMA) - DIPERBAIKI
    // ============================================
    $query_uploads = "
        SELECT 
            'dokumen' as tipe,
            idData as id,
            judul,
            noPeraturan,
            tanggal_penetapan,
            jenis,
            singkatanJenis,
            image_name,
            image_path,
            pdf_path,
            fileDownload,
            status,
            sumber,
            bidangHukum,
            subjek,
            tahun_pengundangan,
            penerbit
        FROM uploads 
        WHERE (
            -- Pencarian di semua field text (case-insensitive)
            LOWER(judul) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(noPeraturan) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(subjek) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(jenis) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(sumber) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(bidangHukum) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(singkatanJenis) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(penerbit) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(tahun_pengundangan) LIKE LOWER('%$keyword_escaped%')
        )
        AND (status IS NOT NULL AND status != '')
        ORDER BY 
            -- Prioritaskan yang judulnya mengandung keyword
            CASE 
                WHEN LOWER(judul) LIKE LOWER('%$keyword_escaped%') THEN 1
                WHEN LOWER(noPeraturan) LIKE LOWER('%$keyword_escaped%') THEN 2
                ELSE 3
            END,
            -- Kemudian yang terbaru
            tanggal_penetapan DESC
        LIMIT 50
    ";
    
    $result_uploads = mysqli_query($koneksi, $query_uploads);
    if ($result_uploads) {
        while ($row = mysqli_fetch_assoc($result_uploads)) {
            $row['tipe_detail'] = 'dokumen';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            $results[] = $row;
        }
    } else {
        error_log("Search query uploads error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // 3. SEARCH DI TABEL ARTIKEL_HUKUM
    // ============================================
    $query_artikel = "
        SELECT 
            'artikel_hukum' as tipe,
            id_artikel as id,
            judul,
            '' as noPeraturan,
            tanggal_terbit as tanggal_penetapan,
            jenis_artikel as jenis,
            '' as singkatanJenis,
            thumbnail as image_name,
            '' as image_path,
            file_pdf as pdf_path,
            '' as fileDownload,
            status,
            penulis as sumber,
            bidang_hukum as bidangHukum
        FROM artikel_hukum 
        WHERE (
            UPPER(status) = 'PUBLISHED'
            OR status = '1'
            OR status = 'publish'
        )
        AND (
            LOWER(judul) LIKE LOWER('%$keyword_escaped%') 
            OR LOWER(penulis) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(abstrak) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(keywords) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(bidang_hukum) LIKE LOWER('%$keyword_escaped%')
        )
        ORDER BY tahun_terbit DESC, tanggal_terbit DESC
        LIMIT 20
    ";
    
    $result_artikel = mysqli_query($koneksi, $query_artikel);
    if ($result_artikel) {
        while ($row = mysqli_fetch_assoc($result_artikel)) {
            $row['tipe_detail'] = 'artikel_hukum';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            $results[] = $row;
        }
    } else {
        error_log("Search query artikel_hukum error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // 4. SEARCH DI TABEL ARTIKEL_KEGIATAN
    // ============================================
    $query_kegiatan = "
        SELECT 
            'kegiatan' as tipe,
            id_artikel as id,
            judul,
            '' as noPeraturan,
            tanggal_publikasi as tanggal_penetapan,
            meta_keywords as jenis,
            '' as singkatanJenis,
            thumbnail_path as image_name,
            '' as image_path,
            '' as pdf_path,
            '' as fileDownload,
            status,
            nama_penulis as sumber,
            '' as bidangHukum
        FROM artikel_kegiatan 
        WHERE (
            UPPER(status) = 'PUBLIK'
            OR status = '1'
            OR status = 'public'
        )
        AND (
            LOWER(judul) LIKE LOWER('%$keyword_escaped%') 
            OR LOWER(konten_artikel) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(meta_keywords) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(nama_penulis) LIKE LOWER('%$keyword_escaped%')
        )
        ORDER BY tanggal_publikasi DESC
        LIMIT 20
    ";
    
    $result_kegiatan = mysqli_query($koneksi, $query_kegiatan);
    if ($result_kegiatan) {
        while ($row = mysqli_fetch_assoc($result_kegiatan)) {
            $row['tipe_detail'] = 'kegiatan';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            $results[] = $row;
        }
    } else {
        error_log("Search query artikel_kegiatan error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // 5. SEARCH DI TABEL BUKU_PERPUSTAKAAN
    // ============================================
    $query_buku = "
        SELECT 
            'buku' as tipe,
            id_buku as id,
            judul,
            '' as noPeraturan,
            tahun_terbit as tanggal_penetapan,
            subjek_topik as jenis,
            isbn as singkatanJenis,
            sampul_path as image_name,
            '' as image_path,
            file_buku_path as pdf_path,
            '' as fileDownload,
            status_ketersediaan as status,
            pengarang as sumber,
            bidang_hukum as bidangHukum
        FROM buku_perpustakaan 
        WHERE (
            LOWER(judul) LIKE LOWER('%$keyword_escaped%') 
            OR LOWER(pengarang) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(penerbit) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(subjek_topik) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(bidang_hukum) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(isbn) LIKE LOWER('%$keyword_escaped%')
        )
        ORDER BY tahun_terbit DESC
        LIMIT 20
    ";
    
    $result_buku = mysqli_query($koneksi, $query_buku);
    if ($result_buku) {
        while ($row = mysqli_fetch_assoc($result_buku)) {
            $row['tipe_detail'] = 'buku';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            $results[] = $row;
        }
    } else {
        error_log("Search query buku_perpustakaan error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // 6. SEARCH DI TABEL MONOGRAFI - DITAMBAHKAN
    // PERBAIKAN: Menambahkan pencarian di tabel monografi sesuai dengan detail-monografi.php
    // ============================================
    $query_monografi = "
        SELECT 
            'monografi' as tipe,
            id_monografi as id,
            judul,
            '' as noPeraturan,
            tanggal_pembuatan as tanggal_penetapan,
            jenis_monografi as jenis,
            '' as singkatanJenis,
            COALESCE(thumbnail, '') as image_name,
            '' as image_path,
            file_pdf as pdf_path,
            '' as fileDownload,
            status,
            penulis as sumber,
            bidang_hukum as bidangHukum,
            penerbit,
            isbn,
            tahun_terbit,
            deskripsi
        FROM monografi 
        WHERE (
            LOWER(judul) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(penulis) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(jenis_monografi) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(bidang_hukum) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(penerbit) LIKE LOWER('%$keyword_escaped%')
            OR LOWER(deskripsi) LIKE LOWER('%$keyword_escaped%')
        )
        ORDER BY tahun_terbit DESC, tanggal_pembuatan DESC
        LIMIT 20
    ";
    
    $result_monografi = mysqli_query($koneksi, $query_monografi);
    if ($result_monografi) {
        while ($row = mysqli_fetch_assoc($result_monografi)) {
            $row['tipe_detail'] = 'monografi';
            $row['relevance_score'] = calculateRelevanceScore($row, $keyword);
            
            // Tentukan kategori monografi untuk display (sesuai detail-monografi.php)
            $jenis_monografi = $row['jenis_monografi'] ?? '';
            if (stripos($jenis_monografi, 'pokok pikiran') !== false) {
                $row['kategori_display'] = 'Pokok Pikiran DPRD';
            } elseif (stripos($jenis_monografi, 'keputusan') !== false) {
                $row['kategori_display'] = 'Keputusan DPRD';
            } elseif (stripos($jenis_monografi, 'peraturan') !== false) {
                $row['kategori_display'] = 'Peraturan DPRD';
            } elseif (stripos($jenis_monografi, 'kajian') !== false || stripos($jenis_monografi, 'raperda') !== false) {
                $row['kategori_display'] = 'Kajian Raperda';
            } else {
                $row['kategori_display'] = 'Monografi Hukum';
            }
            
            $results[] = $row;
        }
    } else {
        // Log error jika tabel tidak ada
        error_log("Search query monografi error: " . mysqli_error($koneksi));
    }
    
    // ============================================
    // SORTING BERDASARKAN RELEVANSI
    // ============================================
    usort($results, function($a, $b) {
        return ($b['relevance_score'] ?? 0) - ($a['relevance_score'] ?? 0);
    });
    
    return $results;
}

/**
 * FUNGSI UNTUK MENGHITUNG SKOR RELEVANSI
 */
function calculateRelevanceScore($item, $keyword) {
    $score = 0;
    $keyword_lower = strtolower(trim($keyword));
    
    // 1. Cek di judul (skor tertinggi)
    if (isset($item['judul'])) {
        $judul_lower = strtolower($item['judul']);
        if (strpos($judul_lower, $keyword_lower) !== false) {
            $score += 100;
        }
        // Bonus jika kata persis sama
        if ($judul_lower == $keyword_lower) {
            $score += 50;
        }
        // Bonus jika diawal judul
        if (strpos($judul_lower, $keyword_lower) === 0) {
            $score += 30;
        }
    }
    
    // 2. Cek di nomor peraturan
    if (isset($item['noPeraturan']) && !empty($item['noPeraturan'])) {
        if (stripos($item['noPeraturan'], $keyword) !== false) {
            $score += 80;
        }
    }
    
    // 3. Cek tahun
    if (isset($item['tahun_pengundangan'])) {
        if (strpos($item['tahun_pengundangan'], $keyword) !== false) {
            $score += 60;
        }
    }
    
    // 4. Cek jenis
    if (isset($item['jenis'])) {
        if (stripos($item['jenis'], $keyword) !== false) {
            $score += 40;
        }
    }
    
    // 5. Bonus untuk dokumen terbaru (dalam 1 tahun)
    if (isset($item['tanggal_penetapan'])) {
        $item_date = strtotime($item['tanggal_penetapan']);
        $current_year = date('Y');
        $item_year = date('Y', $item_date);
        
        if ($item_year == $current_year) {
            $score += 20;
        }
    }
    
    return $score;
}

// ============================================
// ============================================
// GET DETAIL PAGE URL BASED ON TYPE - DIPERBAIKI
// ============================================
function getDetailUrl($item, $koneksi = null) {
    $tipe = $item['tipe_detail'] ?? 'dokumen';
    $id = $item['id'] ?? 0;
    
    if (empty($id)) {
        return '#';
    }
    
    switch ($tipe) {
        case 'dokumen':
            // PERBAIKAN UTAMA: Sekarang detail keputisan menggunakan token terenkripsi
            $jenis = strtolower($item['jenis'] ?? '');
            $detail_page = getDetailPageByJenis($jenis);
            
            // Gunakan token terenkripsi untuk detail-keputusan
            $encrypted_token = encode_dokumen_token($id);
            return "index.php?page={$detail_page}&token=" . urlencode($encrypted_token);
            
        case 'artikel_hukum':
            $encrypted_id = encode_article_token($id);
            return "index.php?page=detail-artikel&token=" . urlencode($encrypted_id);
            
        case 'kegiatan':
            $encrypted_id = encode_kegiatan_token($id);
            return "index.php?page=detail-kegiatan&token=" . urlencode($encrypted_id);
            
        case 'buku':
            $encrypted_id = encode_buku_id($id);
            return "index.php?page=detail-buku&token=" . urlencode($encrypted_id);
            
        case 'yurisprudensi':
            $encrypted_id = encode_yurisprudensi_token($id);
            return "index.php?page=detail-yurisprudensi&token=" . urlencode($encrypted_id);
            
        case 'monografi':
            $encrypted_id = encode_monografi_token($id);
            return "index.php?page=detail-monografi&token=" . urlencode($encrypted_id);
            
        default:
            $encrypted_token = encode_dokumen_token($id);
            return "index.php?page=detail-keputusan&token=" . urlencode($encrypted_token);
    }
}

// ============================================
// RESOLVE IMAGE URL BASED ON TYPE - DIPERBAIKI LAGI
// ============================================
function resolveItemImage($item) {
    $tipe = $item['tipe_detail'];
    
    switch ($tipe) {
        case 'dokumen':
            $img_url = resolveImageUrl($item['image_name'], $item['image_path']);
            if ($img_url && file_exists($_SERVER['DOCUMENT_ROOT'] . $img_url)) {
                return $img_url;
            }
            
            // Fallback: Cari di lokasi lain
            $possible_paths = [
                '/uploads/images/img_' . $item['id'] . '.jpg',
                '/uploads/images/img_' . $item['id'] . '.png',
                '/uploads/images/' . $item['id'] . '.jpg',
                '/panel_control/uploads/images/img_' . $item['id'] . '.jpg',
                '/panel_control/uploads/images/' . $item['id'] . '.jpg',
            ];
            
            foreach ($possible_paths as $path) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                    return $path;
                }
            }
            
            return false;
            
        case 'artikel_hukum':
            if (!empty($item['image_name'])) {
                $normalized = str_replace('\\', '/', trim($item['image_name']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                // Coba lokasi lain
                $paths = [
                    '/uploads/artikel_hukum/images/' . basename($normalized),
                    '/panel_control/uploads/artikel_hukum/images/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/images/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'kegiatan':
            return resolveThumbnailUrl($item['image_name']);
            
        case 'buku':
            if (!empty($item['image_name'])) {
                $normalized = str_replace('\\', '/', trim($item['image_name']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                // Coba lokasi lain
                $paths = [
                    '/uploads/buku/' . basename($normalized),
                    '/panel_control/uploads/buku/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/buku/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'yurisprudensi':
            if (!empty($item['image_name'])) {
                $normalized = str_replace('\\', '/', trim($item['image_name']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                // Coba lokasi lain
                $paths = [
                    '/uploads/yurisprudensi/' . basename($normalized),
                    '/panel_control/uploads/yurisprudensi/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'monografi':
            if (!empty($item['image_name'])) {
                $normalized = str_replace('\\', '/', trim($item['image_name']));
                
                $possible_paths = [
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/images/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/monografi/images/' . basename($normalized),
                    '/uploads/monografi/images/' . basename($normalized),
                    '/panel_control/uploads/monografi/images/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/monografi/images/' . basename($normalized),
                    $_SERVER['DOCUMENT_ROOT'] . '/uploads/monografi/images/' . basename($normalized),
                ];
                
                foreach ($possible_paths as $path) {
                    if (strpos($path, '/') !== 0 && strpos($path, 'C:/') !== 0) {
                        $full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
                    } elseif (strpos($path, 'C:/') === 0) {
                        $full_path = $path;
                    } else {
                        $full_path = $_SERVER['DOCUMENT_ROOT'] . $path;
                    }
                    
                    if (file_exists($full_path)) {
                        if (strpos($full_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                            return str_replace($_SERVER['DOCUMENT_ROOT'], '', $full_path);
                        }
                    }
                }
            }
            return false;
            
        default:
            return false;
    }
}

// ============================================
// RESOLVE PDF URL BASED ON TYPE - DIPERBAIKI LAGI
// ============================================
function resolveItemPdf($item) {
    $tipe = $item['tipe_detail'];
    
    switch ($tipe) {
        case 'dokumen':
            return resolvePdfUrl($item['pdf_path'], $item['fileDownload']);
            
        case 'artikel_hukum':
            if (!empty($item['pdf_path'])) {
                $normalized = str_replace('\\', '/', trim($item['pdf_path']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                $paths = [
                    '/uploads/artikel_hukum/pdf/' . basename($normalized),
                    '/panel_control/uploads/artikel_hukum/pdf/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/artikel_hukum/pdf/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'buku':
            if (!empty($item['pdf_path'])) {
                $normalized = str_replace('\\', '/', trim($item['pdf_path']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                $paths = [
                    '/uploads/buku/' . basename($normalized),
                    '/panel_control/uploads/buku/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/buku/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'yurisprudensi':
            if (!empty($item['pdf_path'])) {
                $normalized = str_replace('\\', '/', trim($item['pdf_path']));
                if (strpos($normalized, 'uploads/') === 0) {
                    if (strpos($normalized, '/') !== 0) {
                        $normalized = '/' . $normalized;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $normalized)) {
                        return $normalized;
                    }
                }
                
                $paths = [
                    '/uploads/yurisprudensi/' . basename($normalized),
                    '/panel_control/uploads/yurisprudensi/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/yurisprudensi/' . basename($normalized),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                        return $path;
                    }
                }
            }
            return false;
            
        case 'monografi':
            if (!empty($item['pdf_path'])) {
                $normalized = str_replace('\\', '/', trim($item['pdf_path']));
                
                $possible_paths = [
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/' . basename($normalized),
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/' . $item['id'] . '.pdf',
                    'C:/laragon/www/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/monografi_' . $item['id'] . '.pdf',
                    '/jdihcilacap_pasang_2026/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/' . basename($normalized),
                    '/uploads/monografi/pdf/' . basename($normalized),
                    '/panel_control/uploads/monografi/pdf/' . basename($normalized),
                    '/jdihcilacap_pasang_2026/panel_control/uploads/monografi/pdf/' . basename($normalized),
                    $_SERVER['DOCUMENT_ROOT'] . '/uploads/monografi/pdf/' . basename($normalized),
                ];
                
                foreach ($possible_paths as $path) {
                    if (strpos($path, '/') !== 0 && strpos($path, 'C:/') !== 0) {
                        $full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
                    } elseif (strpos($path, 'C:/') === 0) {
                        $full_path = $path;
                    } else {
                        $full_path = $_SERVER['DOCUMENT_ROOT'] . $path;
                    }
                    
                    if (file_exists($full_path)) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $full_path);
                        finfo_close($finfo);
                        
                        if (stripos($mime_type, 'pdf') !== false) {
                            if (strpos($full_path, $_SERVER['DOCUMENT_ROOT']) === 0) {
                                return str_replace($_SERVER['DOCUMENT_ROOT'], '', $full_path);
                            } elseif (strpos($full_path, 'C:/laragon/www') === 0) {
                                return str_replace('C:/laragon/www', '', $full_path);
                            }
                        }
                    }
                }
            }
            return false;
            
        default:
            return false;
    }
}

// ============================================
// QUERY UNTUK STATISTIK
// ============================================

// Total Dokumen
$query_total = "SELECT COUNT(*) as total FROM uploads";
$result_total = $koneksi->query($query_total);
$row_total = $result_total->fetch_assoc();
$total_dokumen = $row_total['total'];

// Dokumen Tahun Ini (berdasarkan tahun_pengundangan)
$current_year = date('Y');
$query_tahun_ini = "SELECT COUNT(*) as total FROM uploads WHERE tahun_pengundangan = '$current_year'";
$result_tahun_ini = $koneksi->query($query_tahun_ini);
$row_tahun_ini = $result_tahun_ini->fetch_assoc();
$dokumen_tahun_ini = $row_tahun_ini['total'];

// Dokumen Bulan Ini (berdasarkan uploaded_at)
$current_month = date('Y-m');
$query_bulan_ini = "SELECT COUNT(*) as total FROM uploads WHERE DATE_FORMAT(uploaded_at, '%Y-%m') = '$current_month'";
$result_bulan_ini = $koneksi->query($query_bulan_ini);
$row_bulan_ini = $result_bulan_ini->fetch_assoc();
$dokumen_bulan_ini = $row_bulan_ini['total'];

// Distribusi Jenis Dokumen
$query_jenis = "SELECT jenis, COUNT(*) as jumlah FROM uploads WHERE jenis IS NOT NULL AND jenis != '' GROUP BY jenis ORDER BY jumlah DESC";
$result_jenis = $koneksi->query($query_jenis);

$jenis_stats = [];
$jenis_labels = [];
$jenis_data = [];
$jenis_total = 0;

// Kategori dari gambar yang perlu di-mapping
$kategori_mapping = [
    'Keputusan_DPRD' => 'Keputisan DPRD',
    'Keputissen_Sekretaris_DPRD' => 'Keputissen Sekretaris DPRD',
    'Keputissen_Pimpinan_DPRD' => 'Keputissen Pimpinan DPRD',
    'Peraturan_DPRD' => 'Peraturan DPRD',
    'Peraturan_Daerah' => 'Peraturan Daerah',
    'English_Legal_Product' => 'English Legal Product'
];

// Kategori lainnya akan digabung sebagai "Lainnya"
$kategori_lainnya = [];

if ($result_jenis && $result_jenis->num_rows > 0) {
    while ($row = $result_jenis->fetch_assoc()) {
        $jenis_asli = $row['jenis'];
        $jumlah = $row['jumlah'];
        $jenis_total += $jumlah;
        
        // Mapping jenis berdasarkan data di database
        if (isset($kategori_mapping[$jenis_asli])) {
            $jenis_label = $kategori_mapping[$jenis_asli];
            if (!isset($jenis_stats[$jenis_label])) {
                $jenis_stats[$jenis_label] = 0;
            }
            $jenis_stats[$jenis_label] += $jumlah;
        } else {
            // Untuk jenis yang tidak ada di mapping, masukkan ke "Lainnya"
            $kategori_lainnya[] = ['nama' => $jenis_asli, 'jumlah' => $jumlah];
        }
    }
    
    // Tambahkan "Lainnya" jika ada
    if (!empty($kategori_lainnya)) {
        $total_lainnya = 0;
        foreach ($kategori_lainnya as $lainnya) {
            $total_lainnya += $lainnya['jumlah'];
        }
        $jenis_stats['Lainnya'] = $total_lainnya;
    }
    
    // Konversi ke array untuk chart
    foreach ($jenis_stats as $label => $jumlah) {
        $jenis_labels[] = $label;
        $jenis_data[] = $jumlah;
    }
}

// Tren 10 Tahun Terakhir berdasarkan tahun_pengundangan
$start_year = $current_year - 9;
$trend_labels = [];
$trend_data = [];

// Query untuk mendapatkan data per tahun
$query_trend = "SELECT tahun_pengundangan, COUNT(*) as jumlah 
                FROM uploads 
                WHERE tahun_pengundangan REGEXP '^[0-9]{4}$'
                GROUP BY tahun_pengundangan 
                HAVING tahun_pengundangan BETWEEN $start_year AND $current_year
                ORDER BY tahun_pengundangan";
$result_trend = $koneksi->query($query_trend);

$trend_data_db = [];
if ($result_trend && $result_trend->num_rows > 0) {
    while ($row = $result_trend->fetch_assoc()) {
        $trend_data_db[$row['tahun_pengundangan']] = $row['jumlah'];
    }
}

// Isi data untuk semua tahun (termasuk yang kosong)
for ($year = $start_year; $year <= $current_year; $year++) {
    $trend_labels[] = $year;
    $trend_data[] = isset($trend_data_db[$year]) ? $trend_data_db[$year] : 0;
}

// Status Dokumen
$query_status = "SELECT status, COUNT(*) as jumlah FROM uploads WHERE status IS NOT NULL AND status != '' GROUP BY status ORDER BY jumlah DESC";
$result_status = $koneksi->query($query_status);
$status_data = [];
if ($result_status && $result_status->num_rows > 0) {
    while ($row = $result_status->fetch_assoc()) {
        $status_data[] = $row;
    }
}

// Bidang Hukum
$query_bidang = "SELECT bidangHukum, COUNT(*) as jumlah FROM uploads WHERE bidangHukum IS NOT NULL AND bidangHukum != '' GROUP BY bidangHukum ORDER BY jumlah DESC LIMIT 5";
$result_bidang = $koneksi->query($query_bidang);
$bidang_data = [];
if ($result_bidang && $result_bidang->num_rows > 0) {
    while ($row = $result_bidang->fetch_assoc()) {
        $bidang_data[] = $row;
    }
}

// ============================================
// QUERY KEGIATAN TERBARU
// ============================================
$query_kegiatan = "SELECT * FROM artikel_kegiatan WHERE status = 'publik' ORDER BY tanggal_publikasi DESC LIMIT 3";
$result_kegiatan = $koneksi->query($query_kegiatan);
$kegiatan_terbaru = [];
if ($result_kegiatan && $result_kegiatan->num_rows > 0) {
    while ($row = $result_kegiatan->fetch_assoc()) {
        $kegiatan_terbaru[] = $row;
    }
}

// ============================================
// SEARCH FUNCTIONALITY - TANPA BATAS KARAKTER
// ============================================
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_results = [];
$show_search = false;

if (!empty($search_query)) {
    $show_search = true;
    $search_results = searchGlobalEnhanced($search_query, $koneksi);
}
?>
<style>
<?php include "beranda.css"; ?>

/* Statistics Section */
.stats-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #5b5fdc, #2ecc71);
}

.stats-title h2 {
    color: #2c3e50;
    font-weight: 700;
    margin-top: 10px;
}

.stats-title small {
    color: #5b5fdc;
    font-weight: 600;
    letter-spacing: 2px;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
    height: 100%;
    min-height: 350px;
    display: flex;
    flex-direction: column;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.stats-card h5 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.stats-card small {
    color: #666;
    font-size: 0.85rem;
    display: block;
    margin-bottom: 15px;
}

.stats-card .chart-container {
    flex: 1;
    position: relative;
    min-height: 250px;
}

.stats-card-small {
    background: white;
    border-radius: 12px;
    padding: 25px 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border-top: 4px solid #5b5fdc;
}

.stats-card-small:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-top-color: #2ecc71;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #5b5fdc;
    margin-bottom: 8px;
    line-height: 1;
}

.stats-label {
    color: #666;
    font-size: 0.9rem;
    font-weight: 500;
}

.btn-stats {
    background: linear-gradient(135deg, #5b5fdc 0%, #7f9cf5 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-stats:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(91, 95, 220, 0.3);
    color: white;
}

.stats-grid {
    margin-bottom: 30px;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        padding: 20px;
        min-height: 300px;
    }
    
    .stats-number {
        font-size: 2rem;
    }
    
    .stats-section {
        padding: 60px 0;
    }
    
    .stats-card .chart-container {
        min-height: 200px;
    }
}

/* Animation */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card-small {
    animation: countUp 0.6s ease-out;
}

/* Status and Field Lists */
.status-list, .bidang-list {
    margin-top: 15px;
}

.status-item, .bidang-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.status-item:hover, .bidang-item:hover {
    background-color: #f9f9f9;
    border-radius: 5px;
    padding-left: 10px;
    padding-right: 10px;
}

.status-item:last-child, .bidang-item:last-child {
    border-bottom: none;
}

.status-name, .bidang-name {
    flex: 1;
    color: #555;
    font-size: 0.9rem;
}

.status-count, .bidang-count {
    color: #5b5fdc;
    font-weight: 600;
    min-width: 80px;
    text-align: right;
    font-size: 0.9rem;
}

.bidang-rank {
    background: #5b5fdc;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.8rem;
    font-weight: bold;
}

.bidang-name {
    display: flex;
    align-items: center;
}

/* No Data State */
.text-center.py-3 {
    padding: 20px 0;
    color: #999;
    font-style: italic;
}

/* SEARCH RESULTS - Document Card & Kegiatan Card */
.document-card, .kegiatan-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.document-card:hover, .kegiatan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.document-card .card-image {
    position: relative;
    width: 100%;
    height: 200px;
    background: #f5f5f5;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.document-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.document-card:hover .card-image img {
    transform: scale(1.05);
}

.document-card .placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #999;
    gap: 10px;
}

.document-card .placeholder i {
    font-size: 3rem;
    color: #ccc;
}

.document-card .placeholder p {
    margin: 0;
    text-align: center;
    font-size: 0.9rem;
}

.document-card .placeholder small {
    font-size: 0.75rem;
    color: #aaa;
}

.document-card .card-content, .kegiatan-card .card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.document-card .card-number {
    font-size: 0.8rem;
    color: #5b5fdc;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.document-card .card-title, .kegiatan-card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
    line-height: 1.4;
    flex: 1;
}

.document-card .card-meta, .kegiatan-card .card-meta {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 12px;
}

.document-card .card-meta i, .kegiatan-card .card-meta i {
    margin-right: 5px;
    color: #5b5fdc;
}

.document-card .file-status {
    font-size: 0.85rem;
    padding: 5px 10px !important;
    margin-bottom: 12px !important;
}

.document-card .file-status i {
    margin-right: 5px;
}

.document-card .card-actions, .kegiatan-card .card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.document-card .btn, .kegiatan-card .btn {
    font-size: 0.85rem;
    padding: 7px 15px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer !important;
    color: inherit;
}

.document-card .btn:hover, .kegiatan-card .btn:hover {
    color: inherit;
    text-decoration: none !important;
}

.document-card .btn-primary {
    background: #5b5fdc;
    border: 1px solid #5b5fdc;
    color: white;
}

.document-card .btn-primary:hover {
    background: #4849b6;
    border-color: #4849b6;
    color: white;
    text-decoration: none !important;
}

.document-card .btn-outline-success {
    background: white;
    border: 1px solid #2ecc71;
    color: #2ecc71;
}

.document-card .btn-outline-success:hover {
    background: #2ecc71;
    color: white;
    text-decoration: none !important;
}

.kegiatan-card .btn-primary {
    background: #27ae60;
    border: 1px solid #27ae60;
    color: white;
}

.kegiatan-card .btn-primary:hover {
    background: #229954;
    border-color: #229954;
    color: white;
    text-decoration: none !important;
}

/* ============================================
   TAMBAHAN: Kegiatan card image area
   ============================================ */
.kegiatan-card .card-image {
    position: relative;
    width: 100%;
    height: 200px;
    background: #f5f5f5;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kegiatan-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.kegiatan-card:hover .card-image img {
    transform: scale(1.05);
}

.kegiatan-card .placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #999;
    gap: 10px;
}

.kegiatan-card .placeholder i {
    font-size: 3rem;
    color: #a5b4fc;
}

.kegiatan-card .placeholder p {
    margin: 0;
    text-align: center;
    font-size: 0.9rem;
    color: #6366f1;
}

/* Search Type Badge */
.search-type-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-type-badge.dokumen {
    background: rgba(91, 95, 220, 0.9);
}

.search-type-badge.artikel_hukum {
    background: rgba(46, 204, 113, 0.9);
}

.search-type-badge.kegiatan {
    background: rgba(39, 174, 96, 0.9);
}

.search-type-badge.buku {
    background: rgba(155, 89, 182, 0.9);
}

.search-type-badge.yurisprudensi {
    background: rgba(241, 196, 15, 0.9);
    color: #333;
}

.search-type-badge.monografi {
    background: rgba(52, 152, 219, 0.9);
}

/* ============================================
   MONOGRAFI CARD STYLES
   ============================================ */
.monografi-card {
    border-left: 4px solid #3498db;
}

.monografi-card .search-type-badge.monografi {
    background: rgba(52, 152, 219, 0.9);
}

.monografi-card .monografi-category {
    background: #e8f4fc;
    color: #2980b9;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 8px;
}

.monografi-card .monografi-author {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 8px;
}

.monografi-card .monografi-author i {
    color: #3498db;
    margin-right: 5px;
}

.monografi-card .monografi-year {
    font-size: 0.8rem;
    color: #95a5a6;
    margin-bottom: 10px;
}

/* Highlight monografi in search results */
.highlight-monografi {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid #2196f3;
}

/* Monografi detail button */
.btn-monografi {
    background: #3498db;
    border: 1px solid #3498db;
    color: white;
}

.btn-monografi:hover {
    background: #2980b9;
    border-color: #2980b9;
    color: white;
}
</style>

<!-- Hero Section with Video Background -->
<section class="hero-section">
    <!-- Video Background -->
    <div class="video-background">
        <div id="youtube-player"></div>
        <div class="video-overlay"></div>
    </div>
    
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="hero-title animate-fade-in">Selamat Datang di JDIH<br>Sekretariat DPRD Surakarta</h1>
                <p class="hero-subtitle animate-fade-in-delay">
                    Pusat Dokumentasi dan Informasi Hukum yang menyediakan akses mudah<br>
                    terhadap seluruh produk hukum DPRD Kota Surakarta
                </p>

                <!-- Search Box - DIPERBAIKI: TANPA BATAS KARAKTER -->
                <div class="search-box-wrapper mt-4 animate-slide-up">
                    <form method="GET" action="?" class="search-box-form" id="searchForm">
                        <div class="search-box-container">
                            <div class="search-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <input type="text" 
                                   name="q" 
                                   class="search-input" 
                                   id="searchInput"
                                   placeholder="Cari produk hukum: judul, nomor, tahun, jenis..."
                                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                                   required
                                   autocomplete="off">
                            <button type="submit" class="search-btn">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        
                        <!-- Search Tips -->
                        <div class="search-tips mt-2">
                            <small class="text-white d-block">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Tips:</strong> Cari dengan 
                                <strong>judul lengkap</strong> (contoh: "peraturan dprd"), 
                                <strong>nomor peraturan</strong> (contoh: "223/d.r/20/2025"), 
                                atau <strong>kata kunci</strong> (contoh: "bagus", "enggres", "2025")
                            </small>
                            <small class="text-white d-block mt-1">
                                <i class="bi bi-lightbulb"></i> 
                                <strong>Pencarian fleksibel:</strong> Bisa mencari dengan 1 karakter atau lebih
                            </small>
                        </div>
                    </form>
                    
                    <!-- Search Suggestions -->
                    <div class="search-suggestions">
                        <span class="suggestion-label">Pencarian Populer:</span>
                        <a href="?q=peraturan" class="suggestion-tag">peraturan</a>
                        <a href="?q=keputusan" class="suggestion-tag">keputisan</a>
                        <a href="?q=2025" class="suggestion-tag">2025</a>
                        <a href="?q=bagus" class="suggestion-tag">bagus</a>
                        <a href="?q=DPRD" class="suggestion-tag">DPRD</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="main-content">
    <div class="container">
        <!-- SEARCH RESULTS -->
        <?php if ($show_search): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title">Hasil Pencarian: "<?= htmlspecialchars($search_query) ?>"</h2>
                    <p class="text-muted mb-4">
                        Ditemukan <strong><?= count($search_results); ?></strong> hasil dari semua kategori
                    </p>
                </div>
            </div>
            
            <?php if (!empty($search_results)): ?>
                <!-- Search Stats -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <i class="bi bi-search"></i> 
                                    <strong>Keyword:</strong> "<?= htmlspecialchars($search_query) ?>"
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small>
                                        <i class="bi bi-lightbulb"></i> 
                                        Mencari di: <strong>judul, nomor, jenis, tahun, bidang hukum, sumber</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Results Grid -->
                <div class="row mb-5">
                    <?php 
                    $counter = 0;
                    $dokumen_count = 0;
                    $artikel_count = 0;
                    $kegiatan_count = 0;
                    $buku_count = 0;
                    $yurisprudensi_count = 0;
                    $monografi_count = 0;
                    
                    foreach ($search_results as $item): 
                        $counter++;
                        
                        // Hitung berdasarkan tipe
                        $tipe = $item['tipe_detail'];
                        switch ($tipe) {
                            case 'dokumen': $dokumen_count++; break;
                            case 'artikel_hukum': $artikel_count++; break;
                            case 'kegiatan': $kegiatan_count++; break;
                            case 'buku': $buku_count++; break;
                            case 'yurisprudensi': $yurisprudensi_count++; break;
                            case 'monografi': $monografi_count++; break;
                        }
                        
                        // Get item details
                        $judul = htmlspecialchars($item['judul'] ?? 'Tidak ada judul');
                        $no_peraturan = htmlspecialchars($item['noPeraturan'] ?? '');
                        $jenis = htmlspecialchars($item['jenis'] ?? '');
                        $sumber = htmlspecialchars($item['sumber'] ?? '');
                        $bidang = htmlspecialchars($item['bidangHukum'] ?? '');
                        
                        // Format date
                        $tanggal = 'N/A';
                        if (!empty($item['tanggal_penetapan'])) {
                            $tanggal = date('d F Y', strtotime($item['tanggal_penetapan']));
                        }
                        
                        // Get URLs - PERBAIKAN UTAMA DI SINI
                        $detail_url = getDetailUrl($item, $koneksi);
                        $image_url = resolveItemImage($item);
                        $pdf_url = resolveItemPdf($item);
                        $file_found = ($pdf_url !== false);
                        
                        // Type label
                        $type_labels = [
                            'dokumen' => 'Dokumen',
                            'artikel_hukum' => 'Artikel Hukum',
                            'kegiatan' => 'Kegiatan',
                            'buku' => 'Buku',
                            'yurisprudensi' => 'Yurisprudensi',
                            'monografi' => 'Monografi'
                        ];
                        $type_label = $type_labels[$tipe] ?? 'Item';
                        
                        // Type color classes
                        $type_classes = [
                            'dokumen' => 'dokumen',
                            'artikel_hukum' => 'artikel_hukum',
                            'kegiatan' => 'kegiatan',
                            'buku' => 'buku',
                            'yurisprudensi' => 'yurisprudensi',
                            'monografi' => 'monografi'
                        ];
                        $type_class = $type_classes[$tipe] ?? 'dokumen';
                        
                        // Highlight keyword in title
                        $highlighted_title = $judul;
                        if (!empty($search_query)) {
                            $highlighted_title = preg_replace(
                                '/(' . preg_quote($search_query, '/') . ')/i',
                                '<mark class="bg-warning px-1 rounded">$1</mark>',
                                $judul
                            );
                        }
                        
                        // Special handling for monografi
                        $is_monografi = ($tipe === 'monografi');
                        $card_class = $is_monografi ? 'document-card monografi-card' : 'document-card';
                    ?>
                    <div class="col-lg-6 mb-4">
                        <div class="<?= $card_class ?> h-100">
                            <!-- AREA GAMBAR / THUMBNAIL -->
                            <div class="card-image">
                                <?php if ($image_url && file_exists($_SERVER['DOCUMENT_ROOT'] . $image_url)): ?>
                                    <!-- Gambar ditemukan di server -->
                                    <img src="<?= htmlspecialchars($image_url) ?>" 
                                         alt="<?= $judul ?>"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="placeholder" style="display: none;">
                                        <i class="bi bi-<?= $tipe === 'dokumen' ? 'file-earmark-medical' : 
                                                         ($tipe === 'artikel_hukum' ? 'journal-text' : 
                                                         ($tipe === 'buku' ? 'book' : 
                                                         ($tipe === 'yurisprudensi' ? 'balance-scale' : 
                                                         ($tipe === 'monografi' ? 'journal-bookmark' : 'calendar-event')))); ?>"></i>
                                        <p><?= $type_label ?><br>
                                        <small><?= substr($judul, 0, 30) ?>...</small></p>
                                    </div>
                                <?php elseif ($tipe === 'kegiatan'): ?>
                                    <!-- Untuk kegiatan, gunakan fungsi khusus -->
                                    <?php 
                                    $kegiatan_image = resolveKegiatanThumbnail($item['image_name'], $item['id']);
                                    if ($kegiatan_image && strpos($kegiatan_image, 'http') === 0): ?>
                                        <img src="<?= htmlspecialchars($kegiatan_image) ?>" 
                                             alt="<?= $judul ?>"
                                             loading="lazy">
                                    <?php elseif ($kegiatan_image && file_exists($_SERVER['DOCUMENT_ROOT'] . $kegiatan_image)): ?>
                                        <img src="<?= htmlspecialchars($kegiatan_image) ?>" 
                                             alt="<?= $judul ?>"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="placeholder" style="display: none;">
                                            <i class="bi bi-calendar-event"></i>
                                            <p><?= $type_label ?><br>
                                            <small><?= substr($judul, 0, 30) ?>...</small></p>
                                        </div>
                                    <?php else: ?>
                                        <!-- Tidak ada gambar untuk kegiatan -->
                                        <div class="placeholder">
                                            <i class="bi bi-calendar-event"></i>
                                            <p><?= $type_label ?><br>
                                            <small><?= substr($judul, 0, 30) ?>...</small></p>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Tidak ada gambar di database atau tidak ditemukan -->
                                    <div class="placeholder">
                                        <i class="bi bi-<?= $tipe === 'dokumen' ? 'file-earmark-medical' : 
                                                         ($tipe === 'artikel_hukum' ? 'journal-text' : 
                                                         ($tipe === 'buku' ? 'book' : 
                                                         ($tipe === 'yurisprudensi' ? 'balance-scale' : 
                                                         ($tipe === 'monografi' ? 'journal-bookmark' : 'calendar-event')))); ?>"></i>
                                        <p><?= $type_label ?><br>
                                        <small><?= substr($judul, 0, 30) ?>...</small></p>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Type Badge -->
                                <div class="search-type-badge <?= $type_class ?>">
                                    <?= $type_label ?>
                                </div>
                            </div>

                            <!-- KONTEN CARD -->
                            <div class="card-content">
                                <!-- Nomor/Identifier -->
                                <?php if (!empty($no_peraturan)): ?>
                                    <div class="card-number">
                                        <i class="bi bi-hash"></i> 
                                        <?= $no_peraturan ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Judul dengan highlight -->
                                <h5 class="card-title"><?= $highlighted_title ?></h5>
                                
                                <!-- ============================================
                                     PERBAIKAN: Khusus untuk monografi, tambahkan info tambahan
                                     ============================================ -->
                                <?php if ($is_monografi): ?>
                                    <!-- Kategori Monografi -->
                                    <?php if (!empty($item['kategori_display'])): ?>
                                        <div class="monografi-category mb-2">
                                            <i class="bi bi-tag"></i> <?= htmlspecialchars($item['kategori_display']) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Penulis -->
                                    <?php if (!empty($sumber)): ?>
                                        <div class="monografi-author">
                                            <i class="bi bi-person"></i> Penulis: <?= $sumber ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Tahun Terbit -->
                                    <?php if (!empty($item['tahun_terbit'])): ?>
                                        <div class="monografi-year">
                                            <i class="bi bi-calendar"></i> Tahun: <?= htmlspecialchars($item['tahun_terbit']) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Metadata untuk non-monografi -->
                                    <div class="card-meta">
                                        <?php if ($tanggal !== 'N/A'): ?>
                                            <span><i class="bi bi-calendar"></i> <?= $tanggal; ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($jenis)): ?>
                                            <span class="ms-3"><i class="bi bi-tag"></i> <?= $jenis; ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($sumber) && $tipe !== 'kegiatan'): ?>
                                            <span class="ms-3"><i class="bi bi-person"></i> <?= $sumber; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Bidang Hukum -->
                                <?php if (!empty($bidang)): ?>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark" style="font-size: 0.75rem; padding: 3px 8px;">
                                            <i class="bi bi-bookmark"></i> <?= $bidang ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- STATUS FILE -->
                                <?php if ($file_found && $tipe !== 'kegiatan'): ?>
                                    <div class="mb-3">
                                        <span class="badge bg-success" style="font-size: 0.82rem; padding: 5px 10px;">
                                            <i class="bi bi-check-circle"></i> File tersedia
                                        </span>
                                    </div>
                                <?php elseif ($tipe !== 'kegiatan'): ?>
                                    <div class="mb-3">
                                        <span class="badge bg-secondary" style="font-size: 0.82rem; padding: 5px 10px;">
                                            <i class="bi bi-file-earmark"></i> Tanpa file
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- TOMBOL AKSI -->
                                <div class="card-actions mt-auto">
                                    <!-- Tombol Lihat Detail -->
                                    <?php if ($is_monografi): ?>
                                        <!-- Tombol khusus untuk monografi -->
                                        <a href="<?php echo !empty($detail_url) ? htmlspecialchars($detail_url) : '#'; ?>" 
                                           class="btn btn-sm btn-monografi"
                                           style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none; background: #3498db; color: white; padding: 7px 15px; border-radius: 6px; font-weight: 500; border: none; cursor: pointer;">
                                            <i class="bi bi-journal-bookmark"></i> Detail Monografi
                                        </a>
                                    <?php else: ?>
                                        <!-- Tombol untuk tipe lain -->
                                        <a href="<?php echo !empty($detail_url) ? htmlspecialchars($detail_url) : '#'; ?>" 
                                           class="btn btn-sm btn-primary"
                                           style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none; background: #5b5fdc; color: white; padding: 7px 15px; border-radius: 6px; font-weight: 500; border: none; cursor: pointer;">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    <?php endif; ?>
                                    
                                    <!-- Tombol Download PDF (jika ada) -->
                                    <?php if ($file_found && $tipe !== 'kegiatan'): ?>
                                        <a href="<?= htmlspecialchars($pdf_url) ?>" 
                                           class="btn btn-sm btn-outline-success"
                                           download 
                                           target="_blank"
                                           style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none; background: white; color: #2ecc71; padding: 7px 15px; border-radius: 6px; font-weight: 500; border: 1px solid #2ecc71; cursor: pointer;">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php 
                    // Reset counter setiap 2 item untuk row baru
                    if ($counter % 2 == 0) {
                        echo '</div><div class="row mb-5">';
                    }
                    ?>
                    
                    <?php endforeach; ?>
                </div>
                
                <!-- Search Summary -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-bar-chart"></i> Ringkasan Hasil Pencarian</h5>
                                <div class="row mt-3">
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-primary fw-bold"><?= $dokumen_count ?></div>
                                        <small>Dokumen</small>
                                    </div>
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-success fw-bold"><?= $artikel_count ?></div>
                                        <small>Artikel</small>
                                    </div>
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-info fw-bold"><?= $kegiatan_count ?></div>
                                        <small>Kegiatan</small>
                                    </div>
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-purple fw-bold"><?= $buku_count ?></div>
                                        <small>Buku</small>
                                    </div>
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-warning fw-bold"><?= $yurisprudensi_count ?></div>
                                        <small>Yurisprudensi</small>
                                    </div>
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="text-primary fw-bold"><?= $monografi_count ?></div>
                                        <small>Monografi</small>
                                    </div>
                                </div>
                                
                                <!-- ============================================
                                     PERBAIKAN: Tambahkan info khusus monografi
                                     ============================================ -->
                                <?php if ($monografi_count > 0): ?>
                                    <div class="mt-4 alert alert-info">
                                        <h6><i class="bi bi-info-circle"></i> Informasi Monografi:</h6>
                                        <p class="mb-0">
                                            <strong><?= $monografi_count ?> dokumen monografi</strong> ditemukan dalam pencarian. 
                                            Monografi mencakup: Pokok Pikiran DPRD, Keputusan DPRD, Peraturan DPRD, dan Kajian Raperda.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Tips -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-lightbulb"></i> Tips Pencarian Lanjutan:</h6>
                            <ul class="mb-0">
                                <li>Pencarian mencakup <strong>6 kategori</strong>: Dokumen, Artikel Hukum, Kegiatan, Buku, Yurisprudensi, dan <strong>Monografi</strong></li>
                                <li>Gunakan <strong>kata kunci spesifik</strong> untuk hasil yang lebih akurat</li>
                                <li>Cari berdasarkan <strong>judul, nomor, penulis, tahun, atau bidang hukum</strong></li>
                                <li><strong>Pencarian fleksibel</strong>: Bisa mencari dengan 1 karakter atau lebih</li>
                                <li><strong>Monografi</strong> mencakup: Pokok Pikiran DPRD, Keputusan DPRD, Peraturan DPRD, Kajian Raperda</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- No Results -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Tidak ada hasil!</strong> Pencarian untuk "<strong><?= htmlspecialchars($search_query); ?></strong>" tidak menemukan hasil di semua kategori. Coba:
                            <ul class="mt-2 mb-0">
                                <li>Periksa ejaan kata kunci</li>
                                <li>Gunakan kata kunci yang lebih umum (contoh: "peraturan" bukan "praturan")</li>
                                <li>Cari dengan nomor peraturan lengkap</li>
                                <li>Coba cari di kategori khusus: <a href="?q=monografi" class="alert-link">monografi</a>, <a href="?q=artikel" class="alert-link">artikel</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Search Suggestions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-search-heart"></i> Coba Pencarian Lain:</h5>
                                <p class="text-muted">Berdasarkan data di database:</p>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <a href="?q=peraturan" class="btn btn-outline-primary btn-sm">peraturan</a>
                                    <a href="?q=keputusan" class="btn btn-outline-primary btn-sm">keputisan</a>
                                    <a href="?q=bagus" class="btn btn-outline-success btn-sm">bagus</a>
                                    <a href="?q=enggres" class="btn btn-outline-success btn-sm">enggres</a>
                                    <a href="?q=2025" class="btn btn-outline-info btn-sm">2025</a>
                                    <a href="?q=DPRD" class="btn btn-outline-dark btn-sm">DPRD</a>
                                    <a href="?q=223/d.r/20/2025" class="btn btn-outline-warning btn-sm">223/d.r/20/2025</a>
                                    <a href="?q=monografi" class="btn btn-outline-primary btn-sm">monografi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-card">
                    <h2 class="section-title">Kata Pengantar</h2>
                    <div class="welcome-text">
                        <p>
                            Jaringan Dokumentasi dan Informasi Hukum (JDIH) Sekretariat DPRD Kota Surakarta 
                            hadir sebagai sarana penyebarluasan produk hukum secara online, sebagai media 
                            publikasi, serta menyediakan akses yang mudah dan cepat untuk informasi hukum. 
                            Dengan demikian, diharapkan jajaran sekretariat Dewan Perwakilan Rakyat Daerah 
                            Kota Surakarta, masyarakat kota Surakarta, dan semua pihak yang memerlukan 
                            dapat mengakses dan mendapatkan informasi hukum yang tepat dan akurat.
                        </p>
                        <div class="quote-box">
                            <i class="bi bi-quote quote-icon"></i>
                            <p class="quote-text">JDIH membawa hukum langsung dalam genggaman Anda</p>
                        </div>
                        
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-name">KINKIN SULTANIL HAKIM, SH, MM</p>
                            <p class="signature-title">Sekretaris DPRD Kota Surakarta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="container">
                <div class="text-center mb-5 stats-title">
                    <small>★ STATISTIK & DATA ★</small>
                    <h2>Statistik Produk Hukum</h2>
                    <p class="text-muted">Data real-time dari database JDIH DPRD Surakarta</p>
                </div>

                <!-- Quick Stats -->
                <div class="row g-4 mb-5 stats-grid">
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($total_dokumen); ?></div>
                            <div class="stats-label">Total Dokumen</div>
                            <small>Semua produk hukum yang tersedia</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($dokumen_tahun_ini); ?></div>
                            <div class="stats-label">Dokumen Tahun <?php echo $current_year; ?></div>
                            <small>Produk hukum tahun berjalan</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-small text-center">
                            <div class="stats-number"><?php echo number_format($dokumen_bulan_ini); ?></div>
                            <div class="stats-label">Dokumen Bulan Ini</div>
                            <small>Update terbaru sistem JDIH</small>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="stats-card">
                            <h5>Distribusi Jenis Dokumen</h5>
                            <small>Persentase berdasarkan kategori produk hukum</small>
                            <div class="chart-container">
                                <?php if (!empty($jenis_labels)): ?>
                                    <canvas id="pieProdukHukum"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <p class="text-muted">Data distribusi jenis belum tersedia</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="stats-card">
                            <h5>Tren Dokumen Per Tahun</h5>
                            <small>Perkembangan 10 tahun terakhir</small>
                            <div class="chart-container">
                                <?php if (!empty($trend_labels)): ?>
                                    <canvas id="barProdukHukum"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <p class="text-muted">Data tren belum tersedia</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row g-4 mt-4">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <h5>Status Dokumen</h5>
                            <?php if (!empty($status_data)): ?>
                                <div class="status-list">
                                    <?php foreach ($status_data as $status): ?>
                                        <div class="status-item">
                                            <span class="status-name"><?php echo htmlspecialchars($status['status']); ?></span>
                                            <span class="status-count"><?php echo number_format($status['jumlah']); ?> dokumen</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">Data status belum tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="stats-card">
                            <h5>Top 5 Bidang Hukum</h5>
                            <?php if (!empty($bidang_data)): ?>
                                <div class="bidang-list">
                                    <?php $counter = 1; ?>
                                    <?php foreach ($bidang_data as $bidang): ?>
                                        <div class="bidang-item">
                                            <span class="bidang-rank">#<?php echo $counter; ?></span>
                                            <span class="bidang-name"><?php echo htmlspecialchars($bidang['bidangHukum']); ?></span>
                                            <span class="bidang-count"><?php echo number_format($bidang['jumlah']); ?></span>
                                        </div>
                                        <?php $counter++; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">Data bidang hukum belum tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="index.php?page=statistik" class="btn btn-stats">
                        <i class="bi bi-graph-up-arrow me-2"></i>Lihat Statistik Lengkap
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Quick Links -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Akses Cepat</h2>
                <p class="section-subtitle">Jelajahi berbagai kategori informasi hukum</p>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-file-text card-icon"></i>
                    </div>
                    <h5>Keputisan DPRD</h5>
                    <p>Akses seluruh keputisan yang telah ditetapkan oleh DPRD Kota Surakarta</p>
                    <a href="?page=keputisan-dprd" class="btn-custom">
                        Lihat Keputisan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-journal-text card-icon"></i>
                    </div>
                    <h5>Peraturan DPRD</h5>
                    <p>Kumpulan peraturan yang berlaku di lingkungan DPRD Kota Surakarta</p>
                    <a href="?page=peraturan-dprd" class="btn-custom">
                        Lihat Peraturan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="quick-link-card">
                    <div class="card-icon-wrapper">
                        <i class="bi bi-journal-bookmark card-icon"></i>
                    </div>
                    <h5>Monografi Hukum</h5>
                    <p>Koleksi monografi, kajian, pokok pikiran, dan dokumen hukum lainnya</p>
                    <a href="?page=monografi" class="btn-custom">
                        Lihat Monografi <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Latest News -->
        <div class="row mb-5">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Kegiatan Terbaru</h2>
                <p class="section-subtitle">Update terkini seputar kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <?php if (!empty($kegiatan_terbaru)): ?>
                <?php foreach ($kegiatan_terbaru as $kegiatan): 
                    $encrypted_token = encode_kegiatan_token($kegiatan['id_artikel']);
                    $thumbnail_url = resolveKegiatanThumbnail($kegiatan['thumbnail_path'], $kegiatan['id_artikel']);
                    $tanggal_pub = date('d F Y', strtotime($kegiatan['tanggal_publikasi']));
                    $kategori = !empty($kegiatan['meta_keywords']) ? explode(',', $kegiatan['meta_keywords'])[0] : 'Umum';
                    $excerpt = substr(strip_tags($kegiatan['konten_artikel']), 0, 150) . '...';
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="news-card">
                        <div class="news-image-wrapper">
                            <img src="<?= htmlspecialchars($thumbnail_url); ?>" 
                                 alt="<?= htmlspecialchars($kegiatan['judul']); ?>" class="news-image"
                                 onerror="this.onerror=null; this.src='<?= getDefaultImage(); ?>';">
                            <div class="news-overlay">
                                <span class="news-category"><i class="bi bi-bookmark-fill me-2"></i><?= htmlspecialchars(trim($kategori)); ?></span>
                            </div>
                        </div>
                        <div class="news-content">
                            <span class="news-date"><i class="bi bi-calendar3 me-2"></i><?= $tanggal_pub; ?></span>
                            <h5 class="news-title"><?= htmlspecialchars($kegiatan['judul']); ?></h5>
                            <p class="news-excerpt"><?= htmlspecialchars($excerpt); ?></p>
                            <a href="?page=detail-kegiatan&token=<?= urlencode($encrypted_token); ?>" class="read-more">Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Tidak ada kegiatan terbaru saat ini.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Instagram Section -->
        <div class="row">
            <div class="col-12 mb-4 text-center">
                <h2 class="section-title">Aktivitas Terbaru di Instagram</h2>
                <p class="section-subtitle">Ikuti perkembangan terbaru kegiatan DPRD Kota Surakarta</p>
            </div>
            
            <div class="col-12">
                <div class="instagram-section">
                    <div class="row justify-content-center g-4">
                        <!-- Instagram Post 1 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DS69KHrj-1o/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 2 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DTPydpoEulZ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                        
                        <!-- Instagram Post 3 -->
                        <div class="col-lg-4 col-md-6">
                            <div class="instagram-card">
                                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DRorvdjj6EE/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14"></blockquote>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instagram Follow Button -->
                    <div class="text-center mt-4">
                        <a href="https://www.instagram.com/setwansurakarta/" target="_blank" class="btn-instagram">
                            <i class="bi bi-instagram me-2"></i> Ikuti @setwansurakarta di Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- YouTube API Script -->
<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            videoId: 'Yx_5XjMMy9k',
            playerVars: {
                autoplay: 1,
                controls: 0,
                showinfo: 0,
                modestbranding: 1,
                loop: 1,
                playlist: 'Yx_5XjMMy9k',
                fs: 0,
                cc_load_policy: 0,
                iv_load_policy: 3,
                autohide: 1,
                mute: 1
            },
            events: {
                onReady: onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        event.target.mute();
        event.target.playVideo();
    }
</script>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari PHP
    const pieLabels = <?php echo !empty($jenis_labels) ? json_encode($jenis_labels) : '[]'; ?>;
    const pieData = <?php echo !empty($jenis_data) ? json_encode($jenis_data) : '[]'; ?>;
    const barLabels = <?php echo !empty($trend_labels) ? json_encode($trend_labels) : '[]'; ?>;
    const barData = <?php echo !empty($trend_data) ? json_encode($trend_data) : '[]'; ?>;

    // Hitung total untuk persentase
    const pieTotal = pieData.reduce((a, b) => a + b, 0);

    /* PIE CHART */
    const pieCtx = document.getElementById('pieProdukHukum');
    if (pieCtx && pieLabels.length > 0 && pieTotal > 0) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: [
                        '#5b5fdc', '#ff7a45', '#2ecc71', 
                        '#7f9cf5', '#a855f7', '#38bdf8',
                        '#f59e0b', '#ef4444', '#10b981',
                        '#6b7280', '#ec4899', '#8b5cf6'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        const value = data.datasets[0].data[i];
                                        const percentage = pieTotal > 0 ? Math.round((value / pieTotal) * 100) : 0;
                                        
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor,
                                            lineWidth: data.datasets[0].borderWidth,
                                            hidden: isNaN(data.datasets[0].data[i]) || chart.getDatasetMeta(0).data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = pieTotal > 0 ? Math.round((value / pieTotal) * 100) : 0;
                                return `${label}: ${value} dokumen (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    /* BAR CHART */
    const barCtx = document.getElementById('barProdukHukum');
    if (barCtx && barLabels.length > 0) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: barData,
                    backgroundColor: '#5b5fdc',
                    borderColor: '#4a4ec9',
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: '#2ecc71'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Dokumen',
                            font: {
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: barData.length > 0 ? Math.ceil(Math.max(...barData) / 10) : 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Dokumen: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Animasi counter untuk statistik
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 20);
    }

    // Animate counters on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statsElements = document.querySelectorAll('.stats-number');
                statsElements.forEach(el => {
                    const target = parseInt(el.textContent.replace(/,/g, ''));
                    if (!isNaN(target)) {
                        animateCounter(el, target);
                    }
                });
                observer.disconnect();
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.stats-grid');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Search input validation - TANPA BATAS KARAKTER
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    if (searchForm && searchInput) {
        // Tidak ada validasi minimal karakter
        
        // Real-time validation (hanya pastikan tidak kosong)
        searchInput.addEventListener('input', function() {
            const value = this.value.trim();
            
            if (value.length === 0) {
                this.setCustomValidity('Masukkan kata kunci pencarian');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Highlight search results
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('q');

    if (searchQuery) {
        // Highlight search terms in results
        const titles = document.querySelectorAll('.card-title');
        titles.forEach(title => {
            const text = title.innerHTML;
            const highlighted = text.replace(
                new RegExp('(' + searchQuery + ')', 'gi'),
                '<mark class="bg-warning px-1 rounded">$1</mark>'
            );
            title.innerHTML = highlighted;
        });
    }
    
    // Handling khusus untuk monografi
    const monografiCards = document.querySelectorAll('.monografi-card');
    monografiCards.forEach(card => {
        // Tambahkan hover effect khusus untuk monografi
        card.addEventListener('mouseenter', function() {
            this.classList.add('highlight-monografi');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('highlight-monografi');
        });
    });
    
    // Animasi untuk card monografi
    const observerMonografi = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observerMonografi.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.monografi-card').forEach(card => {
        observerMonografi.observe(card);
    });
});
</script>

<!-- Instagram Embed Script -->
<script async src="//www.instagram.com/embed.js"></script>

<?php 
// ============================================
// CLEANUP
// ============================================
if (isset($result_total)) $result_total->free();
if (isset($result_tahun_ini)) $result_tahun_ini->free();
if (isset($result_bulan_ini)) $result_bulan_ini->free();
if (isset($result_jenis)) $result_jenis->free();
if (isset($result_trend)) $result_trend->free();
if (isset($result_status)) $result_status->free();
if (isset($result_bidang)) $result_bidang->free();
if (isset($result_kegiatan)) $result_kegiatan->free();

// Tutup koneksi
$koneksi->close();
?>
<center><iframe src="https://cdn.botpress.cloud/webchat/v2.2/shareable.html?configUrl=https://files.bpcontent.cloud/2025/01/13/04/20250113040131-0NXLMT64.json" width="1440" height="400"></iframe></center>
<?php include 'footer.php'; ?>