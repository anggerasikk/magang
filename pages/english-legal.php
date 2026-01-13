<?php
// english-legal.php
$pageTitle = 'English Legal Products - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

<style>
    /* CSS KHUSUS UNTUK HALAMAN ENGLISH LEGAL PRODUCTS */
    /* HEADER SECTION */
    .page-header {
        background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
        padding: 40px 0 30px 0;
        margin-top: 0;
        color: white;
        position: relative;
        overflow: hidden;
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
        background: #f0f8ff;
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

    /* DOCUMENT LIST */
    .document-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
    }
    
    .document-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .document-table th {
        background: #f8f9fa;
        color: #13547a;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #e0e0e0;
        font-family: 'Montserrat', sans-serif;
    }
    
    .document-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        color: #555;
    }
    
    .document-table tr:hover {
        background: #f0f8ff;
    }
    
    .document-number {
        color: #13547a;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .document-title {
        font-weight: 500;
    }
    
    .document-date {
        color: #666;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .document-actions {
        display: flex;
        gap: 10px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;
        color: #13547a;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .action-btn:hover {
        background: #13547a;
        color: white;
        transform: translateY(-2px);
    }

    /* YEAR FILTER */
    .year-filter {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .year-filter h5 {
        color: #13547a;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .year-filter h5 i {
        color: #80d0c7;
        margin-right: 10px;
    }
    
    .year-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .year-btn {
        padding: 8px 20px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        color: #13547a;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .year-btn:hover, .year-btn.active {
        background: #13547a;
        color: white;
        border-color: #13547a;
    }

    /* SIDEBAR MENU */
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
        color: #555;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .sidebar-menu ul li.active a {
        background: #f0f8ff;
        color: #13547a;
        font-weight: 600;
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

    /* PAGINATION */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
    }
    
    .page-item .page-link {
        border: none;
        background: #f8f9fa;
        color: #13547a;
        border-radius: 5px;
        padding: 8px 15px;
        transition: all 0.3s;
    }
    
    .page-item.active .page-link {
        background: #13547a;
        color: white;
    }
    
    .page-item .page-link:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    /* INFO BOX */
    .info-box {
        padding: 15px;
        margin-bottom: 20px;
        border-left: 4px solid #13547a;
        background: #f8f9fa;
        border-radius: 5px;
    }
    
    .info-box h5 {
        color: #13547a;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-box p {
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    /* BUTTON STYLES */
    .custom-btn {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 25px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .custom-btn:hover {
        background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }
    
    .custom-border-btn {
        background: transparent;
        color: #13547a;
        border: 2px solid #13547a;
        border-radius: 50px;
        padding: 10px 25px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .custom-border-btn:hover {
        background: #13547a;
        color: white;
        transform: translateY(-2px);
    }

    /* BADGE STYLES */
    .badge.bg-primary {
        background: #13547a !important;
    }

    /* RESPONSIVE */
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
        
        .document-container,
        .sidebar-menu {
            padding: 20px;
        }
        
        .document-table {
            display: block;
            overflow-x: auto;
        }
        
        .document-actions {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
        }
        
        .year-buttons {
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
    }
</style>

<!-- HEADER SECTION -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white">ENGLISH LEGAL PRODUCTS</h1>
                <p class="subtitle">English Translation of Legal Products from DPRD Surakarta City</p>
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
                
                <!-- FILTER TAHUN -->
                <div class="year-filter">
                    <h5><i class="bi bi-calendar"></i> Filter by Year</h5>
                    <div class="year-buttons">
                        <a href="#" class="year-btn active">All</a>
                        <a href="#" class="year-btn">2024</a>
                        <a href="#" class="year-btn">2023</a>
                        <a href="#" class="year-btn">2022</a>
                        <a href="#" class="year-btn">2021</a>
                        <a href="#" class="year-btn">2020</a>
                        <a href="#" class="year-btn">2019</a>
                        <a href="#" class="year-btn">2018</a>
                        <a href="#" class="year-btn">Others</a>
                    </div>
                </div>
                
                <!-- DAFTAR ENGLISH LEGAL PRODUCTS -->
                <div class="document-container">
                    <h3 class="section-title">List of English Legal Products</h3>
                    
                    <div class="table-responsive">
                        <table class="document-table">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Number & Year</th>
                                    <th style="width: 50%;">About</th>
                                    <th style="width: 15%;">Date</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="document-number">ELP-2024-001</td>
                                    <td class="document-title">Regional Regulation No. 4 of 2023 concerning Cultural Heritage Preservation in Surakarta City</td>
                                    <td class="document-date">15 March 2024</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2023-012</td>
                                    <td class="document-title">Regional Regulation No. 3 of 2022 concerning Sustainable Tourism Management in Surakarta City</td>
                                    <td class="document-date">10 December 2023</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2023-008</td>
                                    <td class="document-title">DPRD Regulation No. 8 of 2024 concerning Internal Rules of DPRD Surakarta City</td>
                                    <td class="document-date">5 October 2023</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2022-015</td>
                                    <td class="document-title">Regional Regulation No. 12 of 2021 concerning Air Pollution Control in Surakarta City</td>
                                    <td class="document-date">20 August 2022</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2022-010</td>
                                    <td class="document-title">DPRD Decision No. 25 of 2023 concerning Approval of Mayor's Accountability Report for 2022 Fiscal Year</td>
                                    <td class="document-date">15 June 2022</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2021-022</td>
                                    <td class="document-title">Regional Regulation No. 8 of 2020 concerning Green Open Space Management in Surakarta City</td>
                                    <td class="document-date">10 November 2021</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2021-018</td>
                                    <td class="document-title">Secretary Decision No. 45 of 2024 concerning Establishment of JDIH Management Team</td>
                                    <td class="document-date">5 September 2021</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="document-number">ELP-2020-025</td>
                                    <td class="document-title">Regional Regulation No. 15 of 2019 concerning Waste Management and Domestic Waste in Surakarta City</td>
                                    <td class="document-date">20 July 2020</td>
                                    <td class="document-actions">
                                        <a href="#" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination-container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
                <!-- INFORMASI -->
                <div class="document-container">
                    <h3 class="section-title">About English Legal Products</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-info-circle text-primary"></i> Definition</h5>
                                <p>English Legal Products are official translations of legal documents from DPRD Surakarta City into English, aimed at facilitating international access and comparative legal research.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5><i class="bi bi-journal-check text-primary"></i> Purpose</h5>
                                <p>To support international cooperation, foreign investment, academic research, and to enhance transparency and accessibility of local regulations for non-Indonesian speakers.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                
                <!-- MENU PERUNDANGAN -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-list"></i> Legislation Menu</h4>
                    <ul>
                        <li>
                            <a href="?page=perundangan">
                                <i class="bi bi-grid"></i>
                                All Categories
                            </a>
                        </li>
                        <li>
                            <a href="?page=keputusan-dprd">
                                <i class="bi bi-file-earmark-medical"></i>
                                DPRD Decisions
                            </a>
                        </li>
                        <li>
                            <a href="?page=keputusan-sekretaris">
                                <i class="bi bi-file-earmark-text"></i>
                                Secretary Decisions
                            </a>
                        </li>
                        <li>
                            <a href="?page=keputusan-pimpinan">
                                <i class="bi bi-file-earmark-ruled"></i>
                                Leadership Decisions
                            </a>
                        </li>
                        <li>
                            <a href="?page=peraturan-dprd">
                                <i class="bi bi-journal-text"></i>
                                DPRD Regulations
                            </a>
                        </li>
                        <li>
                            <a href="?page=perda">
                                <i class="bi bi-journal-bookmark"></i>
                                Regional Regulations
                            </a>
                        </li>
                        <li class="active">
                            <a href="?page=english-legal">
                                <i class="bi bi-translate"></i>
                                English Legal Products
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- STATISTIK -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-bar-chart"></i> English Products Statistics</h4>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between mb-2">
                            <span>Total Products:</span>
                            <span class="fw-bold">45 documents</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Year 2024:</span>
                            <span class="fw-bold">5 documents</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Year 2023:</span>
                            <span class="fw-bold">10 documents</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Year 2022:</span>
                            <span class="fw-bold">12 documents</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Year 2021:</span>
                            <span class="fw-bold">8 documents</span>
                        </li>
                    </ul>
                </div>

                <!-- DOKUMEN TERPOPULER -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-fire"></i> Popular Documents</h4>
                    <ul>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-text"></i>
                                ELP-2024-001
                                <span class="badge bg-primary ms-2">356x</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-text"></i>
                                ELP-2023-012
                                <span class="badge bg-primary ms-2">342x</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-text"></i>
                                ELP-2023-008
                                <span class="badge bg-primary ms-2">321x</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-text"></i>
                                ELP-2022-015
                                <span class="badge bg-primary ms-2">298x</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-file-text"></i>
                                ELP-2022-010
                                <span class="badge bg-primary ms-2">276x</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- DOWNLOAD -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-download"></i> Download</h4>
                    <p class="mb-3">Download collection of English legal products in compressed format:</p>
                    <a href="downloads/english-legal-2020-2024.zip" class="btn custom-btn w-100 mb-2">
                        <i class="bi bi-file-earmark-zip"></i>
                        2020-2024 Collection (ZIP)
                    </a>
                    <a href="downloads/english-legal-glossary.pdf" class="btn custom-border-btn w-100">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Legal Terms Glossary (PDF)
                    </a>
                </div>

                <!-- CONTACT FOR TRANSLATION -->
                <div class="sidebar-menu">
                    <h4><i class="bi bi-translate"></i> Translation Request</h4>
                    <p class="mb-3">Need translation of other legal documents? Contact our translation team:</p>
                    <a href="mailto:translation@dprd-surakarta.go.id" class="btn custom-btn w-100">
                        <i class="bi bi-envelope"></i>
                        Request Translation
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>

<script>
    // JavaScript khusus untuk halaman English Legal Products
    document.addEventListener('DOMContentLoaded', function() {
        // Filter tahun
        document.querySelectorAll('.year-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all buttons
                document.querySelectorAll('.year-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const tahun = this.textContent;
                const rows = document.querySelectorAll('.document-table tbody tr');
                
                if (tahun === 'All') {
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                } else {
                    rows.forEach(row => {
                        const docNumber = row.querySelector('.document-number').textContent;
                        if (docNumber.includes(tahun)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        });
        
        // Action button functionality
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.getAttribute('title');
                const docNumber = this.closest('tr').querySelector('.document-number').textContent;
                
                if (action === 'View') {
                    alert(`Opening document: ${docNumber}\n\nDocument preview feature will be displayed here.`);
                } else if (action === 'Download') {
                    alert(`Downloading document: ${docNumber}\n\nDownload will start automatically.`);
                }
            });
        });
        
        // Pagination interaction
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageItem = this.parentElement;
                
                if (!pageItem.classList.contains('disabled')) {
                    // Remove active class from all page items
                    document.querySelectorAll('.page-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Add active class to clicked page item
                    pageItem.classList.add('active');
                    
                    const pageText = this.textContent.trim();
                    if (!isNaN(pageText)) {
                        console.log(`Navigating to page: ${pageText}`);
                    }
                }
            });
        });
        
        // Translation request button
        document.querySelector('.btn[href^="mailto"]').addEventListener('click', function(e) {
            e.preventDefault();
            const email = this.getAttribute('href').replace('mailto:', '');
            alert(`Translation request email will be sent to: ${email}\n\nPlease make sure your email client is configured properly.`);
        });
        
        // Table row hover effect
        const tableRows = document.querySelectorAll('.document-table tbody tr');
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