<?php
/**
 * FILE: export_pdf.php
 * DESKRIPSI: File utama untuk menangani semua eksport PDF dari berbagai modul
 * VERSI: Tanpa TCPDF, hanya mPDF + HTML fallback
 */

session_start();
include "a_db.php";

// CEK LOGIN
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

// AMBIL PARAMETER
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// VALIDASI PARAMETER
if(empty($type) || empty($id) || !is_numeric($id)) {
    echo "<script>alert('Parameter tidak valid!');
          window.history.back();</script>";
    exit();
}

// AMBIL DATA BERDASARKAN TYPE
switch($type) {
    case 'keppemimpin':
        $jenis = 'Keputusan_Pimpinan_DPRD';
        $filename_prefix = 'Keputusan_Pimpinan_DPRD';
        $document_title = 'KEPUTUSAN PIMPINAN DPRD';
        break;
    // TAMBAHKAN CASE LAIN UNTUK MODUL LAIN DI SINI
    // case 'peraturan-daerah':
    //     $jenis = 'Peraturan_Daerah';
    //     $filename_prefix = 'Peraturan_Daerah';
    //     $document_title = 'PERATURAN DAERAH';
    //     break;
    default:
        echo "<script>alert('Tipe export tidak dikenali!');
              window.history.back();</script>";
        exit();
}

// QUERY DATA
$id = mysqli_real_escape_string($koneksi, $id);
$query = "SELECT * FROM uploads WHERE idData='$id' AND jenis='$jenis'";

// UNTUK USER BUKAN ADMIN, HANYA BISA MELIHAT DATA SENDIRI
if ($_SESSION['level'] != 'user_admin') {
    $user_id = mysqli_real_escape_string($koneksi, $_SESSION['login']);
    $query .= " AND id_user='$user_id'";
}

$result = mysqli_query($koneksi, $query);

if(!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data tidak ditemukan atau Anda tidak memiliki akses!');
          window.history.back();</script>";
    exit();
}

$data = mysqli_fetch_assoc($result);

// GENERATE DAN OUTPUT PDF
generatePDFDocument($type, $data, $filename_prefix, $document_title);

/**
 * FUNGSI: Generate dan output PDF document
 */
function generatePDFDocument($type, $data, $filename_prefix, $document_title) {
    // Generate HTML content
    $html = generatePDFContent($data, $document_title);
    
    // Cek jika mPDF tersedia
    if(isMPDFAvailable()) {
        generateWithMPDF($html, $filename_prefix, $data);
    } else {
        // Fallback ke HTML printable
        generatePrintableHTML($html, $filename_prefix, $data);
    }
}

/**
 * FUNGSI: Cek ketersediaan mPDF
 */
function isMPDFAvailable() {
    // Cek melalui Composer autoload
    $composer_autoload = __DIR__ . '/vendor/autoload.php';
    
    // Cek melalui manual include
    $mpdf_file = __DIR__ . '/mpdf/vendor/autoload.php';
    
    return (file_exists($composer_autoload) || file_exists($mpdf_file));
}

/**
 * FUNGSI: Generate konten HTML untuk PDF
 */
function generatePDFContent($data, $document_title) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($data['judul']); ?> - JDIH DPRD Surakarta</title>
        <style>
            /* Reset dan base styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 12pt;
                line-height: 1.6;
                color: #000000;
                background-color: #ffffff;
                margin: 0;
                padding: 20mm;
            }
            
            /* Container utama */
            .document-container {
                max-width: 100%;
                margin: 0 auto;
            }
            
            /* Header dokumen */
            .document-header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 3px double #000000;
            }
            
            .document-header .institution-name {
                font-size: 16pt;
                font-weight: bold;
                margin-bottom: 5px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            
            .document-header .document-type {
                font-size: 14pt;
                font-weight: normal;
                margin-bottom: 10px;
                color: #333333;
            }
            
            .document-header .system-name {
                font-size: 10pt;
                color: #666666;
                font-style: italic;
            }
            
            /* Tabel informasi */
            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 25px;
                font-size: 11pt;
            }
            
            .info-table th {
                background-color: #f5f5f5;
                border: 1px solid #000000;
                padding: 10px 12px;
                text-align: left;
                font-weight: bold;
                width: 35%;
                vertical-align: top;
            }
            
            .info-table td {
                border: 1px solid #000000;
                padding: 10px 12px;
                width: 65%;
                vertical-align: top;
            }
            
            /* Box judul */
            .title-box {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-left: 6px solid #2c3e50;
                padding: 25px;
                margin: 30px 0;
                page-break-inside: avoid;
            }
            
            .title-box .title-label {
                font-size: 11pt;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 10px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .title-box .title-content {
                font-size: 12.5pt;
                line-height: 1.7;
                text-align: justify;
            }
            
            /* Informasi tambahan */
            .additional-info {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px dashed #cccccc;
                font-size: 10.5pt;
                color: #555555;
            }
            
            .additional-info .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .additional-info .info-item {
                margin-bottom: 8px;
            }
            
            .additional-info .info-label {
                font-weight: bold;
                color: #333333;
            }
            
            /* Footer */
            .document-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 9pt;
                color: #666666;
                padding: 12px 20mm;
                background-color: #ffffff;
                border-top: 1px solid #eeeeee;
                z-index: 100;
            }
            
            /* Untuk halaman pertama */
            .first-page {
                min-height: 270mm;
            }
            
            /* Page break handling */
            .page-break {
                page-break-before: always;
            }
            
            .keep-together {
                page-break-inside: avoid;
            }
            
            /* Utility classes */
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-bold { font-weight: bold; }
            .mt-20 { margin-top: 20px; }
            .mb-15 { margin-bottom: 15px; }
            .p-15 { padding: 15px; }
            
            /* Status styling */
            .status-active {
                color: #27ae60;
                font-weight: bold;
            }
            
            .status-inactive {
                color: #e74c3c;
                font-weight: bold;
            }
            
            /* Print-specific styles */
            @media print {
                body {
                    margin: 15mm;
                    padding: 0;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                
                .document-footer {
                    position: fixed;
                    bottom: 0;
                }
                
                .no-print {
                    display: none;
                }
                
                .page-break {
                    page-break-before: always;
                }
            }
            
            /* Untuk tampilan screen (preview) */
            @media screen {
                body {
                    background-color: #f0f0f0;
                    padding: 20px;
                }
                
                .document-container {
                    background-color: #ffffff;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                    padding: 30px;
                    max-width: 210mm;
                    margin: 20px auto;
                }
            }
        </style>
    </head>
    <body>
        <div class="document-container first-page">
            <!-- Header Dokumen -->
            <div class="document-header">
                <div class="institution-name">
                    DEWAN PERWAKILAN RAKYAT DAERAH<br>
                    KOTA SURAKARTA
                </div>
                <div class="document-type">
                    <?php echo $document_title; ?>
                </div>
                <div class="system-name">
                    Sistem Jaringan Dokumentasi dan Informasi Hukum
                </div>
            </div>
            
            <!-- Informasi Utama Dokumen -->
            <table class="info-table">
                <tr>
                    <th>Nomor Dokumen</th>
                    <td class="text-bold"><?php echo htmlspecialchars($data['noPeraturan']); ?></td>
                </tr>
                <tr>
                    <th>Tahun Pengundangan</th>
                    <td><?php echo htmlspecialchars($data['tahun_pengundangan']); ?></td>
                </tr>
                <tr>
                    <th>Tanggal Pengundangan</th>
                    <td><?php echo htmlspecialchars($data['tanggal_pengundangan']); ?></td>
                </tr>
                <tr>
                    <th>Tanggal Penetapan</th>
                    <td><?php echo htmlspecialchars($data['tanggal_penetapan']); ?></td>
                </tr>
                <tr>
                    <th>Jenis Peraturan</th>
                    <td><?php echo htmlspecialchars($data['jenis']); ?></td>
                </tr>
                <tr>
                    <th>Status Berlaku</th>
                    <td>
                        <span class="<?php echo $data['status'] == 'Berlaku' ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo htmlspecialchars($data['status']); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Sumber</th>
                    <td><?php echo htmlspecialchars($data['sumber']); ?></td>
                </tr>
                <tr>
                    <th>Tempat Terbit</th>
                    <td><?php echo htmlspecialchars($data['tempatTerbit']); ?></td>
                </tr>
                <tr>
                    <th>Penerbit</th>
                    <td><?php echo htmlspecialchars($data['penerbit']); ?></td>
                </tr>
                <tr>
                    <th>Bahasa</th>
                    <td><?php echo htmlspecialchars($data['bahasa']); ?></td>
                </tr>
                <?php if(!empty($data['singkatanJenis'])): ?>
                <tr>
                    <th>Singkatan Jenis</th>
                    <td><?php echo htmlspecialchars($data['singkatanJenis']); ?></td>
                </tr>
                <?php endif; ?>
            </table>
            
            <!-- Judul Dokumen -->
            <div class="title-box keep-together">
                <div class="title-label">Judul Lengkap</div>
                <div class="title-content">
                    <?php echo nl2br(htmlspecialchars($data['judul'])); ?>
                </div>
            </div>
            
            <!-- Informasi Tambahan -->
            <?php if(!empty($data['deskripsiFisik']) || !empty($data['bidangHukum']) || !empty($data['subjek'])): ?>
            <div class="additional-info">
                <div class="mb-15 text-bold">Informasi Tambahan:</div>
                <div class="info-grid">
                    <?php if(!empty($data['bidangHukum'])): ?>
                    <div class="info-item">
                        <span class="info-label">Bidang Hukum:</span><br>
                        <?php echo htmlspecialchars($data['bidangHukum']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($data['subjek'])): ?>
                    <div class="info-item">
                        <span class="info-label">Subjek:</span><br>
                        <?php echo htmlspecialchars($data['subjek']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($data['isbn'])): ?>
                    <div class="info-item">
                        <span class="info-label">ISBN:</span><br>
                        <?php echo htmlspecialchars($data['isbn']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($data['deskripsiFisik'])): ?>
                <div class="mt-20">
                    <div class="text-bold mb-10">Deskripsi Fisik:</div>
                    <div><?php echo htmlspecialchars($data['deskripsiFisik']); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Metadata Sistem -->
            <div class="additional-info mt-20">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">ID Dokumen Sistem:</span><br>
                        <?php echo htmlspecialchars($data['idData']); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Generate:</span><br>
                        <?php echo date('d-m-Y H:i:s'); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dicetak oleh:</span><br>
                        <?php echo htmlspecialchars($_SESSION['login']); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="document-footer">
            Dokumen resmi JDIH DPRD Surakarta - <?php echo date('d F Y'); ?>
            <span style="margin-left: 20px; font-style: italic;">
                Halaman <span class="page-number">1</span>
            </span>
        </div>
        
        <!-- Script untuk auto print dan close (hanya untuk HTML mode) -->
        <div class="no-print">
            <script>
                // Auto print setelah 500ms
                setTimeout(function() {
                    if (typeof window.print === 'function') {
                        window.print();
                    }
                }, 500);
                
                // Tutup window setelah print (jika popup)
                window.onafterprint = function() {
                    setTimeout(function() {
                        if (window.opener) {
                            window.close();
                        }
                    }, 1000);
                };
                
                // Fallback untuk browser yang tidak support onafterprint
                setTimeout(function() {
                    if (!window.matchMedia || !window.matchMedia('print').matches) {
                        // Bukan mode print, bisa tutup setelah 10 detik
                        setTimeout(function() {
                            if (window.opener) {
                                window.close();
                            }
                        }, 10000);
                    }
                }, 2000);
            </script>
            
            <!-- Tombol manual untuk browser yang tidak auto print -->
            <div style="position: fixed; top: 20px; right: 20px; background: white; padding: 15px; border: 2px solid #333; border-radius: 5px; z-index: 1000; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
                <p style="margin: 0 0 10px 0; font-weight: bold;">Opsi Cetak:</p>
                <button onclick="window.print()" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; margin-right: 10px;">
                    Cetak PDF
                </button>
                <button onclick="window.close()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * FUNGSI: Generate PDF menggunakan mPDF
 */
function generateWithMPDF($html, $filename_prefix, $data) {
    try {
        // Cek lokasi autoload
        $autoload_paths = [
            __DIR__ . '/vendor/autoload.php',
            __DIR__ . '/mpdf/vendor/autoload.php',
            dirname(__DIR__) . '/vendor/autoload.php'
        ];
        
        $autoload_file = null;
        foreach ($autoload_paths as $path) {
            if (file_exists($path)) {
                $autoload_file = $path;
                break;
            }
        }
        
        if (!$autoload_file) {
            throw new Exception('mPDF tidak ditemukan. Menggunakan mode HTML.');
        }
        
        require_once $autoload_file;
        
        // Konfigurasi mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 25,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'times',
            'setAutoTopMargin' => 'stretch',
            'setAutoBottomMargin' => 'stretch'
        ]);
        
        // Informasi dokumen
        $mpdf->SetTitle($filename_prefix . ' - ' . $data['noPeraturan']);
        $mpdf->SetAuthor('JDIH DPRD Surakarta');
        $mpdf->SetCreator('JDIH DPRD Surakarta');
        $mpdf->SetSubject($data['judul']);
        $mpdf->SetKeywords('DPRD, Surakarta, JDIH, ' . $data['jenis']);
        
        // Header
        $mpdf->SetHTMLHeader('
        <div style="text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 9pt; color: #666;">
            JDIH DPRD SURAKARTA - DOKUMEN RESMI
        </div>');
        
        // Footer dengan nomor halaman
        $mpdf->SetHTMLFooter('
        <table width="100%" style="border-top: 1px solid #eee; font-size: 8pt; color: #666; padding-top: 5px;">
            <tr>
                <td width="33%" style="text-align: left;">' . date('d/m/Y H:i') . '</td>
                <td width="34%" style="text-align: center;">Halaman {PAGENO} dari {nbpg}</td>
                <td width="33%" style="text-align: right;">' . $filename_prefix . '</td>
            </tr>
        </table>');
        
        // Write HTML content
        $mpdf->WriteHTML($html);
        
        // Output PDF ke browser
        $filename = generateSafeFilename($filename_prefix . '_' . $data['noPeraturan']) . '.pdf';
        
        $mpdf->Output($filename, 'D');
        
    } catch (Exception $e) {
        // Jika mPDF error, fallback ke HTML printable
        error_log("mPDF Error: " . $e->getMessage());
        generatePrintableHTML($html, $filename_prefix, $data);
    }
}

/**
 * FUNGSI: Generate nama file yang aman
 */
function generateSafeFilename($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    $filename = preg_replace('/_{2,}/', '_', $filename);
    $filename = trim($filename, '_');
    return $filename;
}

/**
 * FUNGSI: Generate HTML yang bisa di-print (fallback)
 */
function generatePrintableHTML($html, $filename_prefix, $data) {
    // Output sebagai HTML
    header('Content-Type: text/html; charset=utf-8');
    
    // Tambahkan judul di tab browser
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>' . htmlspecialchars($filename_prefix . ' - ' . $data['noPeraturan']) . '</title>
    </head>
    <body>';
    
    echo $html;
    
    echo '</body></html>';
    exit();
}
?>