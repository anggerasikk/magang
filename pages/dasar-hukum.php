<?php
// dasar-hukum.php
$pageTitle = 'Dasar Hukum JDIH - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

<style>
    /* CSS KHUSUS UNTUK HALAMAN DASAR HUKUM */
    /* HEADER SECTION */
    .page-header {
        background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
        padding: 40px 0 30px 0;
        margin-top: 0;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
        opacity: 0.1;
    }
    
    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 2;
    }
    
    .page-header .subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }
    
    .page-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 2;
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

    /* MAIN CONTENT */
    .main-content {
        padding: 50px 0 60px 0;
        background: #f8f9fa;
    }
    
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #13547a;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 3px;
        background: linear-gradient(to right, #13547a, #80d0c7);
    }
    
    .section-title.no-line::after {
        display: none;
    }

    /* CONTENT CARD */
    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 40px;
        margin-bottom: 40px;
    }
    
    .legal-basis-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .legal-basis-title {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 20px;
    }
    
    .legal-basis-subtitle {
        color: #666;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    /* LEGAL DOCUMENTS TABLE */
    .legal-documents-table {
        width: 100%;
        margin-bottom: 30px;
        border-collapse: collapse;
    }
    
    .legal-documents-table thead {
        background: #13547a;
        color: white;
    }
    
    .legal-documents-table th {
        padding: 15px;
        font-weight: 600;
        text-align: left;
        font-family: 'Montserrat', sans-serif;
    }
    
    .legal-documents-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.3s;
    }
    
    .legal-documents-table tbody tr:hover {
        background-color: #f0f8ff;
    }
    
    .legal-documents-table td {
        padding: 20px 15px;
        vertical-align: middle;
    }
    
    .document-checkbox {
        width: 50px;
        text-align: center;
    }
    
    .document-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .document-title {
        font-weight: 600;
        color: #13547a;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    
    .document-details {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .document-size {
        color: #888;
        font-size: 0.85rem;
        font-style: italic;
    }
    
    .download-column {
        width: 150px;
        text-align: center;
    }
    
    .btn-download-doc {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        font-size: 0.9rem;
    }
    
    .btn-download-doc:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.2);
        color: white;
    }
    
    .btn-download-doc i {
        font-size: 1.1rem;
    }

    /* BULK ACTIONS */
    .bulk-actions {
        background: #f0f8ff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .bulk-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: #13547a;
    }
    
    .bulk-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }
    
    .bulk-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-bulk {
        padding: 8px 20px;
        border-radius: 5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        font-size: 0.9rem;
        border: 2px solid transparent;
    }
    
    .btn-bulk-download {
        background: #13547a;
        color: white;
    }
    
    .btn-bulk-download:hover {
        background: #0d3a58;
        color: white;
    }
    
    .btn-bulk-print {
        background: transparent;
        color: #13547a;
        border-color: #13547a;
    }
    
    .btn-bulk-print:hover {
        background: #13547a;
        color: white;
    }

    /* INFO BOX */
    .info-box {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        border-radius: 10px;
        padding: 30px;
        margin: 40px 0;
        color: white;
    }
    
    .info-box h3 {
        color: white;
        margin-bottom: 15px;
        font-size: 1.5rem;
    }
    
    .info-box p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 15px;
        line-height: 1.6;
    }
    
    .info-box ul {
        padding-left: 20px;
        margin-bottom: 0;
    }
    
    .info-box li {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 8px;
    }
    
    .info-box li:last-child {
        margin-bottom: 0;
    }

    /* SEARCH AND FILTER */
    .search-filter-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .search-form {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
    }
    
    .search-input .form-control {
        border: 2px solid #e0e0e0;
        padding: 10px 15px;
        border-radius: 5px;
    }
    
    .search-input .form-control:focus {
        border-color: #80d0c7;
        box-shadow: none;
    }
    
    .btn-search {
        background: #13547a;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-search:hover {
        background: #0d3a58;
        color: white;
    }
    
    .filter-options {
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
        margin-bottom: 0;
    }
    
    .filter-group select {
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        padding: 8px 15px;
        color: #333;
    }

    /* SIDEBAR */
    .sidebar-menu {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
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
    
    .quick-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .quick-links li {
        margin-bottom: 12px;
    }
    
    .quick-links li:last-child {
        margin-bottom: 0;
    }
    
    .quick-links a {
        color: #555;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .quick-links li.active a {
        background: #f0f8ff;
        color: #13547a;
        font-weight: 600;
    }
    
    .quick-links a:hover {
        background: #f0f8ff;
        color: #13547a;
        transform: translateX(5px);
    }
    
    .quick-links a i {
        color: #80d0c7;
        font-size: 0.9rem;
    }

    /* STATS CARD */
    .stats-card {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .stats-card h4 {
        color: white;
        font-size: 1.3rem;
        margin-bottom: 20px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    /* CONTACT INFO */
    .contact-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .contact-card h4 {
        color: #13547a;
        margin-bottom: 20px;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .contact-card h4 i {
        color: #80d0c7;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .contact-item:last-child {
        margin-bottom: 0;
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        background: #f0f8ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #13547a;
        flex-shrink: 0;
    }
    
    .contact-details h5 {
        color: #13547a;
        margin-bottom: 5px;
        font-size: 1rem;
    }
    
    .contact-details p {
        color: #666;
        margin: 0;
    }
    
    .contact-details a {
        color: #13547a;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .contact-details a:hover {
        color: #80d0c7;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .legal-basis-title {
            font-size: 1.8rem;
        }
        
        .content-card {
            padding: 30px;
        }
        
        .legal-documents-table {
            display: block;
            overflow-x: auto;
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 30px 0 20px 0;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .main-content {
            padding: 30px 0 40px 0;
        }
        
        .legal-documents-table td {
            padding: 15px 10px;
        }
        
        .bulk-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .bulk-buttons {
            width: 100%;
            justify-content: flex-start;
        }
        
        .search-form {
            flex-direction: column;
        }
        
        .search-input {
            width: 100%;
        }
        
        .btn-search {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .legal-basis-title {
            font-size: 1.5rem;
        }
        
        .content-card {
            padding: 20px;
        }
        
        .legal-documents-table th,
        .legal-documents-table td {
            padding: 10px 5px;
            font-size: 0.9rem;
        }
        
        .btn-download-doc {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .document-title {
            font-size: 1rem;
        }
    }
</style>

<!-- HEADER SECTION -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white">DASAR HUKUM JDIH</h1>
                <p class="subtitle">Dokumen Hukum Pendukung Jaringan Dokumentasi dan Informasi Hukum Sekretariat DPRD Kota Surakarta</p>
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
                
                <!-- SEARCH AND FILTER -->
                <div class="search-filter-container">
                    <h3 class="section-title no-line">Cari Dasar Hukum</h3>
                    <form class="search-form">
                        <div class="search-input">
                            <input type="text" class="form-control" placeholder="Cari berdasarkan nomor, tahun, atau judul peraturan...">
                        </div>
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>
                    </form>
                    <div class="filter-options">
                        <div class="filter-group">
                            <label for="filterYear">Tahun:</label>
                            <select id="filterYear" class="form-select form-select-sm">
                                <option selected>Semua Tahun</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                                <option value="2018">2018</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filterType">Jenis:</label>
                            <select id="filterType" class="form-select form-select-sm">
                                <option selected>Semua Jenis</option>
                                <option value="perwali">Peraturan Wali Kota</option>
                                <option value="permendagri">Peraturan Menteri Dalam Negeri</option>
                                <option value="permenkumham">Peraturan Menteri Hukum dan HAM</option>
                                <option value="perda">Peraturan Daerah</option>
                                <option value="keputusan">Keputusan</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- BULK ACTIONS -->
                <div class="bulk-actions">
                    <div class="bulk-checkbox">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll">Pilih Semua</label>
                    </div>
                    <div class="bulk-buttons">
                        <a href="#" class="btn-bulk btn-bulk-download">
                            <i class="bi bi-download"></i>
                            Download Terpilih
                        </a>
                        <a href="#" class="btn-bulk btn-bulk-print">
                            <i class="bi bi-printer"></i>
                            Cetak Terpilih
                        </a>
                    </div>
                </div>
                
                <!-- CONTENT CARD -->
                <div class="content-card">
                    <div class="legal-basis-header">
                        <h1 class="legal-basis-title">Dasar Hukum JDIH</h1>
                        <h2 class="legal-basis-subtitle">Sekretariat DPRD Kota Surakarta</h2>
                        <p class="text-muted">Kumpulan peraturan perundang-undangan yang menjadi dasar hukum pengelolaan JDIH</p>
                    </div>
                    
                    <!-- LEGAL DOCUMENTS TABLE -->
                    <table class="legal-documents-table">
                        <thead>
                            <tr>
                                <th class="document-checkbox"></th>
                                <th>Dokumen Hukum</th>
                                <th class="download-column">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dokumen 1 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Peraturan Wali Kota Surakarta Nomor 4 Tahun 2024 tentang Jaringan Dokumentasi dan Informasi Hukum Kota Surakarta
                                    </div>
                                    <div class="document-details">
                                        Sumber: Pemerintah Kota Surakarta
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 794.42 KB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Dokumen 2 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 2 Tahun 2014 Tentang Pengelolaan Jaringan Dokumentasi Dan Informasi Hukum Kementerian Dalam Negeri Dan Pemerintah Daerah
                                    </div>
                                    <div class="document-details">
                                        Sumber: Kementerian Dalam Negeri RI
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 42.36 KB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Dokumen 3 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Peraturan Menteri Hukum Dan Hak Asasi Manusia Republik Indonesia Nomor 8 Tahun 2019 Tentang Standar Pengelolaan Dokumen Dan Informasi Hukum
                                    </div>
                                    <div class="document-details">
                                        Sumber: Kementerian Hukum dan HAM RI
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 794.42 KB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Dokumen Tambahan 4 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Undang-Undang Nomor 12 Tahun 2011 tentang Pembentukan Peraturan Perundang-undangan
                                    </div>
                                    <div class="document-details">
                                        Sumber: Pemerintah Republik Indonesia
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 1.25 MB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Dokumen Tambahan 5 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Peraturan Sekretaris Jenderal DPRD Kota Surakarta Nomor 1 Tahun 2023 tentang Pengelolaan JDIH
                                    </div>
                                    <div class="document-details">
                                        Sumber: Sekretariat DPRD Kota Surakarta
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 356.78 KB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Dokumen Tambahan 6 -->
                            <tr>
                                <td class="document-checkbox">
                                    <input type="checkbox" class="document-check">
                                </td>
                                <td>
                                    <div class="document-title">
                                        Peraturan Daerah Kota Surakarta Nomor 5 Tahun 2022 tentang Organisasi dan Tata Kerja DPRD Kota Surakarta
                                    </div>
                                    <div class="document-details">
                                        Sumber: DPRD Kota Surakarta
                                    </div>
                                    <div class="document-size">
                                        Ukuran: 2.15 MB
                                    </div>
                                </td>
                                <td class="download-column">
                                    <a href="#" class="btn-download-doc">
                                        <i class="bi bi-download"></i>
                                        DOWNLOAD
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- PAGINATION -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                
                <!-- INFO BOX -->
                <div class="info-box">
                    <h3><i class="bi bi-info-circle me-2"></i> Informasi Penting</h3>
                    <p>Dasar hukum JDIH Sekretariat DPRD Kota Surakarta merupakan kumpulan peraturan perundang-undangan yang menjadi landasan hukum dalam pengelolaan sistem dokumentasi dan informasi hukum. Dokumen-dokumen ini penting untuk:</p>
                    <ul>
                        <li>Menjamin legalitas pengelolaan JDIH</li>
                        <li>Menjadi acuan dalam penyusunan SOP dan pedoman teknis</li>
                        <li>Memastikan kesesuaian dengan regulasi nasional</li>
                        <li>Menjadi dasar dalam pengambilan keputusan operasional</li>
                    </ul>
                    <p class="mt-3">Dokumen dapat diunduh dalam format PDF yang siap digunakan untuk keperluan referensi dan implementasi.</p>
                </div>
                
            </div>
            
            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- STATS CARD -->
                <div class="stats-card">
                    <h4><i class="bi bi-file-earmark-text me-2"></i> Statistik Dokumen</h4>
                    <div class="stat-number">24</div>
                    <div class="stat-label">Total Dasar Hukum Tersedia</div>
                    <div class="stat-number">2024</div>
                    <div class="stat-label">Update Terbaru</div>
                </div>
                
                <!-- QUICK LINKS -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-link-45deg"></i> Menu Cepat</h4>
                    <ul class="quick-links">
                        <li><a href="?page=profil-jdih"><i class="bi bi-house-door"></i> Profil JDIH</a></li>
                        <li><a href="?page=struktur-organisasi"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                        <li class="active"><a href="?page=dasar-hukum"><i class="bi bi-file-earmark-text"></i> Dasar Hukum</a></li>
                        <li><a href="?page=sop-pengelola"><i class="bi bi-list-check"></i> SOP Pengelola</a></li>
                        <li><a href="?page=alur-pengelolaan"><i class="bi bi-arrow-repeat"></i> Alur Pengelolaan</a></li>
                        <li><a href="?page=surat-keputusan"><i class="bi bi-file-earmark-medical"></i> Surat Keputusan</a></li>
                        <li><a href="?page=visi-misi"><i class="bi bi-bullseye"></i> Visi & Misi</a></li>
                    </ul>
                </div>
                
                <!-- DOWNLOAD POPULAR -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Paling Sering Diunduh</h4>
                    <ul class="quick-links">
                        <li>
                            <a href="#">
                                <i class="bi bi-file-earmark-pdf"></i>
                                <div>
                                    <div>Permendagri No. 2 Tahun 2014</div>
                                    <small class="text-muted">Diunduh 245 kali</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-earmark-pdf"></i>
                                <div>
                                    <div>Permenkumham No. 8 Tahun 2019</div>
                                    <small class="text-muted">Diunduh 187 kali</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-earmark-pdf"></i>
                                <div>
                                    <div>Perwali Surakarta No. 4 Tahun 2024</div>
                                    <small class="text-muted">Diunduh 156 kali</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-earmark-pdf"></i>
                                <div>
                                    <div>UU No. 12 Tahun 2011</div>
                                    <small class="text-muted">Diunduh 132 kali</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- CONTACT INFO -->
                <div class="contact-card">
                    <h4><i class="bi bi-info-circle"></i> Informasi Kontak</h4>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Koordinator JDIH</h5>
                            <p>Budi Santoso, SH., M.Hum.</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Telepon</h5>
                            <p><a href="tel:+62271234567">(0271) 234567</a> ext. 123</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email</h5>
                            <p><a href="mailto:jdih@dprd-surakarta.go.id">jdih@dprd-surakarta.go.id</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Jam Konsultasi</h5>
                            <p>Senin - Kamis: 09.00 - 15.00<br>
                            Jumat: 09.00 - 11.30</p>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</section>

<script>
    // JavaScript khusus untuk halaman dasar-hukum
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.document-check');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Fungsi untuk bulk download
        document.querySelector('.btn-bulk-download').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedDocs = Array.from(document.querySelectorAll('.document-check:checked')).length;
            
            if (selectedDocs === 0) {
                alert('Pilih setidaknya satu dokumen untuk diunduh.');
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin mengunduh ${selectedDocs} dokumen terpilih?`)) {
                alert(`Sedang mempersiapkan ${selectedDocs} dokumen untuk diunduh...\n\nDalam implementasi nyata, sistem akan membuat file ZIP yang berisi dokumen terpilih.`);
            }
        });
        
        // Fungsi untuk bulk print
        document.querySelector('.btn-bulk-print').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedDocs = Array.from(document.querySelectorAll('.document-check:checked')).length;
            
            if (selectedDocs === 0) {
                alert('Pilih setidaknya satu dokumen untuk dicetak.');
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin mencetak ${selectedDocs} dokumen terpilih?`)) {
                window.print();
            }
        });
        
        // Fungsi untuk individual download
        document.querySelectorAll('.btn-download-doc').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                    const docTitle = this.closest('tr').querySelector('.document-title').textContent;
                    const docSize = this.closest('tr').querySelector('.document-size').textContent.replace('Ukuran: ', '');
                    
                    if (confirm(`Unduh dokumen: ${docTitle}\n\nUkuran file: ${docSize}\n\nKlik OK untuk melanjutkan.`)) {
                        alert('Download akan dimulai...\n\nDalam implementasi nyata, file PDF akan didownload ke perangkat Anda.');
                    }
                }
            });
        });
        
        // Fungsi untuk pencarian
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const searchQuery = this.querySelector('input[type="text"]').value;
            
            if (!searchQuery.trim()) {
                alert('Masukkan kata kunci pencarian!');
                return;
            }
            
            alert(`Mencari dasar hukum dengan kata kunci: "${searchQuery}"\n\nDalam implementasi nyata, ini akan melakukan filter pada tabel dokumen.`);
        });
        
        // Fungsi untuk filter
        document.querySelectorAll('#filterYear, #filterType').forEach(select => {
            select.addEventListener('change', function() {
                const year = document.getElementById('filterYear').value;
                const type = document.getElementById('filterType').value;
                
                if (year !== 'Semua Tahun' || type !== 'Semua Jenis') {
                    alert(`Menerapkan filter:\nTahun: ${year}\nJenis: ${type}\n\nDalam implementasi nyata, ini akan melakukan filter pada tabel dokumen.`);
                }
            });
        });
        
        // Fungsi untuk animasi tabel row
        const tableRows = document.querySelectorAll('.legal-documents-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f0f8ff';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>

<?php include 'footer.php'; ?>