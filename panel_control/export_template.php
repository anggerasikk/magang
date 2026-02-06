<?php
/**
 * FILE: export_template.php
 * DESKRIPSI: Template HTML untuk export PDF berbagai jenis dokumen
 */

// Pastikan variabel $type dan $data sudah didefinisikan
if(!isset($type) || !isset($data)) {
    die("Template tidak bisa diakses langsung!");
}

// Template berdasarkan jenis dokumen
switch($type) {
    case 'keppemimpin':
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php echo htmlspecialchars($data['judul']); ?></title>
            <style>
                @page { 
                    margin: 50px; 
                    size: A4;
                }
                @page :first {
                    margin-top: 80px;
                }
                body { 
                    font-family: "Times New Roman", Times, serif; 
                    font-size: 12pt; 
                    line-height: 1.6;
                    color: #000;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 40px;
                    padding-bottom: 20px;
                    border-bottom: 3px double #000;
                }
                .header h1 { 
                    font-size: 16pt; 
                    margin: 0 0 10px 0; 
                    font-weight: bold;
                    text-transform: uppercase;
                }
                .header h2 { 
                    font-size: 14pt; 
                    margin: 0; 
                    font-weight: normal;
                    font-style: italic;
                }
                .info-section { 
                    margin: 30px 0;
                }
                .info-section h3 { 
                    font-size: 13pt; 
                    margin-bottom: 15px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ccc;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: 200px 1fr;
                    gap: 10px;
                    margin: 15px 0;
                }
                .info-label {
                    font-weight: bold;
                    text-align: right;
                }
                .info-value {
                    padding-left: 10px;
                }
                .judul-box { 
                    margin: 40px 0; 
                    padding: 20px; 
                    background-color: #f8f9fa; 
                    border: 1px solid #dee2e6;
                    border-left: 5px solid #0066cc;
                    font-size: 12.5pt;
                    text-align: justify;
                }
                .footer { 
                    position: fixed; 
                    bottom: -40px; 
                    left: 0; 
                    right: 0; 
                    height: 40px;
                    text-align: center; 
                    font-size: 9pt; 
                    color: #666;
                    border-top: 1px solid #ddd;
                }
                .footer-content {
                    padding: 10px;
                }
                .page-break {
                    page-break-before: always;
                }
                .no-break {
                    page-break-inside: avoid;
                }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .mb-20 { margin-bottom: 20px; }
                .mt-30 { margin-top: 30px; }
                .pb-10 { padding-bottom: 10px; }
            </style>
        </head>
        <body>
            <!-- Header dengan logo/materai -->
            <div class="header">
                <div style="position: absolute; top: -60px; left: 0; right: 0; text-align: center;">
                    <div style="display: inline-block; padding: 5px 20px; border: 2px solid #000; font-size: 10pt;">
                        DOKUMEN RESMI JDIH
                    </div>
                </div>
                <h1>DEWAN PERWAKILAN RAKYAT DAERAH<br>KOTA SURAKARTA</h1>
                <h2>KEPUTUSAN PIMPINAN DPRD</h2>
                <div style="margin-top: 15px; font-size: 11pt;">
                    Sistem Jaringan Dokumentasi dan Informasi Hukum
                </div>
            </div>
            
            <!-- Informasi Dokumen -->
            <div class="info-section">
                <h3>INFORMASI DOKUMEN</h3>
                <div class="info-grid">
                    <div class="info-label">Nomor Keputusan:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['noPeraturan']); ?></div>
                    
                    <div class="info-label">Tahun Pengundangan:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['tahun_pengundangan']); ?></div>
                    
                    <div class="info-label">Tanggal Pengundangan:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['tanggal_pengundangan']); ?></div>
                    
                    <div class="info-label">Tanggal Penetapan:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['tanggal_penetapan']); ?></div>
                    
                    <div class="info-label">Jenis Dokumen:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['jenis']); ?></div>
                    
                    <div class="info-label">Status Berlaku:</div>
                    <div class="info-value">
                        <span style="font-weight: bold; color: <?php echo $data['status'] == 'Berlaku' ? 'green' : 'red'; ?>">
                            <?php echo htmlspecialchars($data['status']); ?>
                        </span>
                    </div>
                    
                    <div class="info-label">Sumber:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['sumber']); ?></div>
                    
                    <div class="info-label">Tempat Terbit:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['tempatTerbit']); ?></div>
                    
                    <div class="info-label">Penerbit:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['penerbit']); ?></div>
                    
                    <div class="info-label">Bahasa:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['bahasa']); ?></div>
                </div>
            </div>
            
            <!-- Judul Dokumen -->
            <div class="info-section no-break">
                <h3>JUDUL LENGKAP</h3>
                <div class="judul-box">
                    <?php echo nl2br(htmlspecialchars($data['judul'])); ?>
                </div>
            </div>
            
            <!-- Informasi Tambahan -->
            <?php if(!empty($data['deskripsiFisik']) || !empty($data['bidangHukum'])): ?>
            <div class="info-section">
                <h3>INFORMASI TAMBAHAN</h3>
                
                <?php if(!empty($data['bidangHukum'])): ?>
                <div class="mb-20">
                    <strong>Bidang Hukum:</strong><br>
                    <?php echo htmlspecialchars($data['bidangHukum']); ?>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($data['deskripsiFisik'])): ?>
                <div class="mb-20">
                    <strong>Deskripsi Fisik:</strong><br>
                    <?php echo htmlspecialchars($data['deskripsiFisik']); ?>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($data['subjek'])): ?>
                <div class="mb-20">
                    <strong>Subjek:</strong><br>
                    <?php echo htmlspecialchars($data['subjek']); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Metadata Sistem -->
            <div class="info-section mt-30" style="font-size: 10pt; color: #666; border-top: 1px dashed #ccc; padding-top: 15px;">
                <div class="info-grid">
                    <div class="info-label">ID Dokumen:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['idData']); ?></div>
                    
                    <div class="info-label">Tanggal Export:</div>
                    <div class="info-value"><?php echo date('d-m-Y H:i:s'); ?></div>
                    
                    <div class="info-label">Sistem:</div>
                    <div class="info-value">JDIH DPRD Surakarta v2.0</div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="footer-content">
                    Dokumen ini sah dan dihasilkan dari Sistem JDIH DPRD Surakarta<br>
                    Halaman <span class="page-number"></span> | <?php echo date('d F Y'); ?>
                </div>
            </div>
            
            <!-- Script untuk nomor halaman -->
            <script type="text/php">
                if (isset($pdf)) {
                    $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
                    $font = $fontMetrics->get_font("Times New Roman", "normal");
                    $size = 9;
                    $y = $pdf->get_height() - 30;
                    $x = $pdf->get_width() - 100;
                    $pdf->page_text($x, $y, $text, $font, $size);
                    
                    // Text di kiri footer
                    $text2 = "JDIH DPRD Surakarta";
                    $x2 = 50;
                    $pdf->page_text($x2, $y, $text2, $font, $size);
                }
            </script>
            
        </body>
        </html>
        <?php
        break;
        
    // TAMBAHKAN TEMPLATE LAIN DI SINI
    // case 'peraturan-daerah':
    //     // template untuk peraturan daerah
    //     break;
        
    default:
        // Default template
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php echo htmlspecialchars($data['judul']); ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                h1 { color: #333; }
                table { border-collapse: collapse; width: 100%; }
                td, th { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1><?php echo htmlspecialchars($data['judul']); ?></h1>
            <table>
                <tr><th>Informasi</th><th>Detail</th></tr>
                <tr><td>Nomor</td><td><?php echo htmlspecialchars($data['noPeraturan']); ?></td></tr>
                <tr><td>Tanggal</td><td><?php echo htmlspecialchars($data['tanggal_pengundangan']); ?></td></tr>
                <tr><td>Status</td><td><?php echo htmlspecialchars($data['status']); ?></td></tr>
            </table>
        </body>
        </html>
        <?php
        break;
}
?>