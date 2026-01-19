<?php
// sop-pengelola.php
$pageTitle = 'SOP Pengelola JDIH - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

<style>
    /* OVERRIDE UNTUK HALAMAN INI SAJA */
    body {
        padding-top: 70px; /* Diperkecil untuk mengurangi celah */
        margin: 0;
    }
    
    /* HEADER SECTION - DIUBAH UNTUK MENGHILANGKAN CELAH */
    .page-header {
        background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
        padding: 30px 0 25px 0;
        margin-top: -10px; /* Mendorong ke atas */
        color: white;
        position: relative;
        top: -10px; /* Mengurangi posisi */
    }
    
    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 8px;
    }
    
    .page-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .page-header .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .page-header .breadcrumb-item.active {
        color: white;
    }
    
    .page-header .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.8);
    }

    /* MAIN CONTENT - DIUBAH UNTUK MENGHILANGKAN CELAH */
    .main-content {
        padding: 20px 0 40px 0;
        background: #f0f8ff;
        margin-top: -5px; /* Mendorong konten lebih dekat ke header */
    }
    
    .content-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 2px solid #80d0c7;
    }

    /* CARD STYLING */
    .content-card {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }
    
    .content-card h4 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin-bottom: 18px;
        font-size: 1.3rem;
    }
    
    .content-card p {
        color: var(--p-color);
        font-size: var(--p-font-size);
        line-height: 1.6;
    }

    /* PDF PREVIEW CONTAINER */
    .pdf-preview-container {
        background: white;
        border-radius: var(--border-radius-medium);
        padding: 18px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        text-align: center;
    }
    
    .pdf-preview {
        width: 100%;
        height: 600px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
    }
    
    .pdf-caption {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        margin-top: 10px;
        font-style: italic;
    }

    /* INFO BOX */
    .info-box {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-radius: var(--border-radius-medium);
        padding: 22px;
        margin-bottom: 25px;
    }
    
    .info-box h4 {
        color: white;
        font-size: 1.2rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }
    
    .info-box h4 i {
        margin-right: 10px;
    }
    
    .info-box p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        line-height: 1.6;
    }

    /* SIDEBAR MENU */
    .sidebar-menu {
        background: white;
        border-radius: var(--border-radius-medium);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 22px;
        margin-bottom: 25px;
    }
    
    .sidebar-menu h4 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 18px;
        padding-bottom: 8px;
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
        margin-bottom: 10px;
    }
    
    .sidebar-menu ul li:last-child {
        margin-bottom: 0;
    }
    
    .sidebar-menu ul li a {
        color: var(--p-color);
        text-decoration: none;
        padding: 10px 12px;
        border-radius: var(--border-radius-small);
        display: flex;
        align-items: center;
        transition: all 0.3s;
        font-weight: 500;
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

    /* DOWNLOAD BUTTON */
    .download-btn {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border: none;
        border-radius: var(--border-radius-large);
        padding: 14px 22px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }
    
    .download-btn:hover {
        background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    /* PERBAIKAN UNTUK MENGHILANGKAN CELAH */
    .navbar-custom {
        margin-bottom: 0;
        border-bottom: none;
    }
    
    .site-footer {
        margin-top: 0;
        padding-top: 40px;
    }
    
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .row {
        margin-left: -8px;
        margin-right: -8px;
    }
    
    .col-lg-8, .col-lg-4 {
        padding-left: 8px;
        padding-right: 8px;
    }

    /* RESPONSIVE UNTUK PDF PREVIEW */
    @media (max-width: 768px) {
        body {
            padding-top: 60px; /* Diperkecil untuk mobile */
        }
        
        .page-header {
            padding: 20px 0 18px 0;
            margin-top: -8px;
            top: -8px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .main-content {
            padding: 15px 0 30px 0;
        }
        
        .content-card,
        .sidebar-menu,
        .info-box {
            padding: 18px;
        }
        
        .pdf-preview-container {
            padding: 15px;
        }
        
        .pdf-preview {
            height: 400px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .page-header {
            padding: 15px 0 12px 0;
        }
        
        .content-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .main-content {
            padding: 10px 0 25px 0;
        }
        
        .row {
            margin-left: -5px;
            margin-right: -5px;
        }
        
        .col-lg-8, .col-lg-4 {
            padding-left: 5px;
            padding-right: 5px;
        }
        
        .pdf-preview {
            height: 300px;
        }
    }
    
    /* FALLBACK STYLE JIKA PDF TIDAK BISA DITAMPILKAN */
    .pdf-fallback {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: var(--border-radius-medium);
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 25px;
    }
    
    .pdf-fallback-icon {
        font-size: 3rem;
        color: #13547a;
        margin-bottom: 15px;
    }
    
    .pdf-fallback h3 {
        color: #13547a;
        margin-bottom: 10px;
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>SOP PENGELOLA JDIH</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                        <li class="breadcrumb-item active" aria-current="page">SOP Pengelola JDIH</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <div class="row">
            
            <!-- KONTEN UTAMA -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="content-card">
                    <h3 class="content-title">Standar Operasional Prosedur (SOP) Pengelolaan Produk Hukum JDIH</h3>
                    
                    <!-- PDF PREVIEW -->
                    <div class="pdf-preview-container">
                        <?php
                        $pdfPath = 'dokumen/SOP Pengelolaan Produk Hukum (JDIH).pdf';
                        if (file_exists($pdfPath)) {
                            // Encode filename untuk URL
                            $encodedPath = urlencode('dokumen/SOP Pengelolaan Produk Hukum (JDIH).pdf');
                        ?>
                            <embed src="<?php echo $pdfPath; ?>" 
       type="application/pdf"
       class="pdf-preview">

                            <div class="pdf-caption">
                                Preview dokumen: SOP Pengelolaan Produk Hukum (JDIH).pdf
                            </div>
                        <?php
                        } else {
                            // Fallback jika file tidak ditemukan
                        ?>
                            <div class="pdf-fallback">
                                <div class="pdf-fallback-icon">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>
                                <h3>Dokumen SOP Tidak Ditemukan</h3>
                                <p class="mb-3">Dokumen "SOP Pengelolaan Produk Hukum (JDIH).pdf" tidak dapat ditemukan di server.</p>
                                <p>Pastikan file berada di lokasi: <code><?php echo $pdfPath; ?></code></p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    
                    <div class="info-box">
                        <h4><i class="bi bi-info-circle"></i> Tentang SOP Pengelolaan</h4>
                        <p>
                            Standar Operasional Prosedur (SOP) ini merupakan pedoman baku bagi pengelola JDIH 
                            DPRD Kota Surakarta dalam mengelola produk hukum secara sistematis dan terstruktur. 
                            SOP ini mencakup seluruh tahapan mulai dari penerimaan, verifikasi, katalogisasi, 
                            digitalisasi, hingga publikasi dan pemeliharaan dokumen hukum.
                        </p>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- INFO BOX -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                    <p class="mb-0">
                        SOP ini merupakan dokumen resmi yang harus diikuti oleh seluruh pengelola JDIH 
                        dalam menjalankan tugas dan fungsi pengelolaan produk hukum di lingkungan 
                        DPRD Kota Surakarta.
                    </p>
                </div>

                <!-- MENU TERKAIT -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                    <ul>
                        <li>
                            <a href="struktur-organisasi.php">
                                <i class="bi bi-diagram-3"></i>
                                Struktur Organisasi
                            </a>
                        </li>
                        <li>
                            <a href="alur-pengelolaan.php">
                                <i class="bi bi-diagram-3"></i>
                                Alur Pengelolaan Produk Hukum
                            </a>
                        </li>
                        <li>
                            <a href="dasar-hukum.php">
                                <i class="bi bi-journal-text"></i>
                                Dasar Hukum JDIH
                            </a>
                        </li>
                        <li>
                            <a href="visi-misi.php">
                                <i class="bi bi-bullseye"></i>
                                Visi & Misi
                            </a>
                        </li>
                        <li>
                            <a href="keputusan-dprd.php">
                                <i class="bi bi-file-earmark-text"></i>
                                Keputusan DPRD
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- DOWNLOAD BOX -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Download</h4>
                    <p class="mb-3">Unduh dokumen SOP lengkap dalam format PDF:</p>
                    <?php
                    if (file_exists($pdfPath)) {
                    ?>
                        <a href="<?php echo $pdfPath; ?>" download class="download-btn">
                            <i class="bi bi-download"></i>
                            Download SOP Lengkap (PDF)
                        </a>
                    <?php
                    } else {
                    ?>
                        <button class="download-btn" style="opacity: 0.6; cursor: not-allowed;">
                            <i class="bi bi-exclamation-circle"></i>
                            File Tidak Tersedia
                        </button>
                    <?php
                    }
                    ?>
                </div>

                <!-- INFORMASI DOKUMEN -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-file-earmark-text"></i> Informasi Dokumen</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted">Nama File:</small><br>
                            <strong>SOP Pengelolaan Produk Hukum (JDIH).pdf</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">Ukuran:</small><br>
                            <strong><?php echo file_exists($pdfPath) ? round(filesize($pdfPath) / 1024 / 1024, 2) . ' MB' : 'Tidak diketahui'; ?></strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">Format:</small><br>
                            <strong>PDF Document</strong>
                        </li>
                        <li>
                            <small class="text-muted">Terakhir Diperbarui:</small><br>
                            <strong><?php echo file_exists($pdfPath) ? date('d F Y', filemtime($pdfPath)) : 'Tidak diketahui'; ?></strong>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- SCRIPT UNTUK PDF FALLBACK -->
<script>
    // Cek apakah PDF bisa ditampilkan di iframe
    document.addEventListener('DOMContentLoaded', function() {
        const pdfFrame = document.querySelector('.pdf-preview');
        if (pdfFrame) {
            pdfFrame.onload = function() {
                // Cek jika iframe menampilkan error
                try {
                    const iframeDoc = pdfFrame.contentDocument || pdfFrame.contentWindow.document;
                    const bodyContent = iframeDoc.body.innerHTML;
                    
                    if (bodyContent.includes('error') || bodyContent.includes('tidak dapat') || 
                        bodyContent.includes('cannot') || bodyContent.includes('failed')) {
                        showPdfFallback();
                    }
                } catch (e) {
                    // Jika ada error akses cross-origin
                    console.log('Cannot access iframe content due to CORS');
                }
            };
            
            pdfFrame.onerror = function() {
                showPdfFallback();
            };
        }
        
        function showPdfFallback() {
            const container = document.querySelector('.pdf-preview-container');
            if (container) {
                container.innerHTML = `
                    <div class="pdf-fallback">
                        <div class="pdf-fallback-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <h3>Preview Tidak Tersedia</h3>
                        <p class="mb-3">Preview dokumen tidak dapat ditampilkan. Silakan download dokumen untuk melihat isinya.</p>
                        <a href="<?php echo file_exists($pdfPath) ? $pdfPath : '#'; ?>" download class="download-btn" style="width: auto; display: inline-block; padding: 10px 20px;">
                            <i class="bi bi-download"></i>
                            Download SOP
                        </a>
                    </div>
                `;
            }
        }
    });
</script>