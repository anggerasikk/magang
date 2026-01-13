<?php
// galeri.php
$pageTitle = 'Galeri Foto - JDIH DPRD Kota Surakarta';
include 'header.php';
?>

<style>
    /* CSS KHUSUS UNTUK HALAMAN GALERI */
    body {
        background: #f0f8ff;
    }
    
    /* PAGE HEADER */
    .page-header {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        padding: 80px 0 60px;
        text-align: center;
        margin-bottom: 60px;
    }
    
    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.8rem;
        margin-bottom: 20px;
    }
    
    .page-header .subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
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
    
    /* GALLERY FILTER */
    .gallery-filter {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        color: #13547a;
        padding: 10px 25px;
        border-radius: 50px;
        margin-right: 10px;
        margin-bottom: 10px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
        color: white;
        border-color: transparent;
    }
    
    /* GALLERY GRID */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        height: 300px;
    }
    
    .gallery-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(19, 84, 122, 0.9), transparent);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 25px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .gallery-title {
        color: white;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    
    .gallery-date {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    
    .gallery-date i {
        margin-right: 5px;
    }
    
    /* MODAL STYLES */
    .modal-content {
        background: transparent;
        border: none;
    }
    
    .modal-body {
        padding: 0;
        text-align: center;
    }
    
    .modal-body img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .modal-body .text-white {
        background: rgba(0, 0, 0, 0.7);
        padding: 15px;
        border-radius: 0 0 10px 10px;
        margin-top: -4px;
    }
    
    /* PAGINATION */
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }
    
    .page-link {
        color: #13547a;
        border: 2px solid #e0e0e0;
        margin: 0 5px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .page-item.active .page-link {
        background: #13547a;
        border-color: #13547a;
        color: white;
    }
    
    .page-link:hover {
        background: #f0f8ff;
        border-color: #13547a;
        color: #13547a;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2.2rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header .subtitle {
            font-size: 1rem;
        }
        
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
            margin-right: 5px;
            margin-bottom: 8px;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Galeri Foto</h1>
                <p class="subtitle">
                    Dokumentasi visual kegiatan dan acara JDIH DPRD Kota Surakarta
                </p>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="main-content">
    <div class="container">
        <!-- FILTER SECTION -->
        <div class="gallery-filter">
            <h4 class="mb-4" style="color: #13547a;">Filter Album</h4>
            <div class="filter-tags">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="sosialisasi">Sosialisasi</button>
                <button class="filter-btn" data-filter="pelatihan">Pelatihan</button>
                <button class="filter-btn" data-filter="rapat">Rapat</button>
                <button class="filter-btn" data-filter="kunjungan">Kunjungan Kerja</button>
                <button class="filter-btn" data-filter="acara">Acara Khusus</button>
                <button class="filter-btn" data-filter="workshop">Workshop</button>
            </div>
        </div>
        
        <!-- GALLERY GRID -->
        <div class="gallery-grid">
            <!-- Item 1 -->
            <div class="gallery-item" data-category="sosialisasi" data-bs-toggle="modal" data-bs-target="#imageModal1">
                <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Sosialisasi CFD">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Sosialisasi JDIH di CFD</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 25 November 2024
                    </div>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="gallery-item" data-category="pelatihan" data-bs-toggle="modal" data-bs-target="#imageModal2">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Pelatihan Digital">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Pelatihan Infrastruktur Digital</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 10 Januari 2021
                    </div>
                </div>
            </div>
            
            <!-- Item 3 -->
            <div class="gallery-item" data-category="rapat" data-bs-toggle="modal" data-bs-target="#imageModal3">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Public Hearing">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Public Hearing Pansus</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 10 Januari 2021
                    </div>
                </div>
            </div>
            
            <!-- Item 4 -->
            <div class="gallery-item" data-category="workshop" data-bs-toggle="modal" data-bs-target="#imageModal4">
                <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Workshop">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Workshop Penyusunan Produk Hukum</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 8 Januari 2021
                    </div>
                </div>
            </div>
            
            <!-- Item 5 -->
            <div class="gallery-item" data-category="kunjungan" data-bs-toggle="modal" data-bs-target="#imageModal5">
                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Kunjungan Kerja">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Kunjungan Kerja ke DPRD Lain</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 15 Desember 2024
                    </div>
                </div>
            </div>
            
            <!-- Item 6 -->
            <div class="gallery-item" data-category="acara" data-bs-toggle="modal" data-bs-target="#imageModal6">
                <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Penghargaan">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Penerimaan Penghargaan Nasional</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 5 Desember 2024
                    </div>
                </div>
            </div>
            
            <!-- Item 7 -->
            <div class="gallery-item" data-category="sosialisasi" data-bs-toggle="modal" data-bs-target="#imageModal7">
                <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Sosialisasi Perda">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Sosialisasi Perda di Kelurahan</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 20 November 2024
                    </div>
                </div>
            </div>
            
            <!-- Item 8 -->
            <div class="gallery-item" data-category="pelatihan" data-bs-toggle="modal" data-bs-target="#imageModal8">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Pelatihan JDIH">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Pelatihan Pengelolaan JDIH</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 5 November 2024
                    </div>
                </div>
            </div>
            
            <!-- Item 9 -->
            <div class="gallery-item" data-category="acara" data-bs-toggle="modal" data-bs-target="#imageModal9">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                     alt="Launching Aplikasi">
                <div class="gallery-overlay">
                    <h4 class="gallery-title">Launching Aplikasi JDIH Mobile</h4>
                    <div class="gallery-date">
                        <i class="bi bi-calendar"></i> 1 Oktober 2024
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PAGINATION -->
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
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<!-- IMAGE MODALS -->
<div class="modal fade" id="imageModal1" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Sosialisasi CFD" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Sosialisasi JDIH di Car Free Day Surakarta</h5>
                    <p>Kegiatan sosialisasi produk hukum kepada masyarakat di CFD Surakarta - 25 November 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Pelatihan Digital" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Pelatihan Infrastruktur Digital JDIH</h5>
                    <p>Pelatihan peningkatan kompetensi pengelola JDIH dalam teknologi informasi - 10 Januari 2021</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal3" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Public Hearing" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Public Hearing Panitia Khusus</h5>
                    <p>Sidang dengar pendapat publik dengan stakeholders terkait Raperda - 10 Januari 2021</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal4" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Workshop" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Workshop Penyusunan Produk Hukum</h5>
                    <p>Kegiatan workshop teknik penyusunan peraturan perundang-undangan - 8 Januari 2021</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal5" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Kunjungan Kerja" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Kunjungan Kerja ke DPRD Kota Lain</h5>
                    <p>Studi banding dan pertukaran pengalaman pengelolaan JDIH - 15 Desember 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal6" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1551135049-8a33b2fb2f61?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Penghargaan" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Penerimaan Penghargaan Nasional</h5>
                    <p>JDIH DPRD Kota Surakarta menerima penghargaan sebagai Sistem JDIH Terbaik Tingkat Nasional - 5 Desember 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal7" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Sosialisasi Perda" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Sosialisasi Perda di Kelurahan</h5>
                    <p>Kegiatan sosialisasi peraturan daerah di tingkat kelurahan - 20 November 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal8" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Pelatihan JDIH" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Pelatihan Pengelolaan JDIH</h5>
                    <p>Pelatihan teknis pengelolaan sistem JDIH untuk staf sekretariat - 5 November 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal9" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="Launching Aplikasi" class="img-fluid">
                <div class="mt-3 text-white">
                    <h5>Launching Aplikasi JDIH Mobile</h5>
                    <p>Peluncuran aplikasi mobile JDIH untuk akses produk hukum melalui smartphone - 1 Oktober 2024</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript khusus untuk halaman galeri
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                // Show/hide gallery items based on filter
                galleryItems.forEach(item => {
                    if (filterValue === 'all') {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 10);
                    } else {
                        const category = item.getAttribute('data-category');
                        if (category === filterValue) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                            }, 10);
                        } else {
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 300);
                        }
                    }
                });
            });
        });
        
        // Gallery item click animation
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // Modal close animation
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                this.style.opacity = '0';
                setTimeout(() => {
                    this.style.opacity = '1';
                    this.style.transition = 'opacity 0.3s ease';
                }, 10);
            });
            
            modal.addEventListener('hide.bs.modal', function() {
                this.style.opacity = '0';
                this.style.transition = 'opacity 0.3s ease';
            });
        });
        
        // Pagination functionality
        const pageLinks = document.querySelectorAll('.page-link');
        pageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageItem = this.parentElement;
                
                if (!pageItem.classList.contains('disabled') && !pageItem.classList.contains('active')) {
                    // Remove active class from all page items
                    document.querySelectorAll('.page-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Add active class to clicked page item
                    pageItem.classList.add('active');
                    
                    const pageText = this.textContent.trim();
                    if (!isNaN(pageText)) {
                        console.log(`Navigating to gallery page: ${pageText}`);
                        // Here you would typically load new gallery items via AJAX
                    }
                }
            });
        });
        
        // Keyboard navigation for gallery
        document.addEventListener('keydown', function(e) {
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) {
                if (e.key === 'Escape') {
                    bootstrap.Modal.getInstance(activeModal).hide();
                }
                if (e.key === 'ArrowLeft') {
                    // Navigate to previous image
                    console.log('Previous image');
                }
                if (e.key === 'ArrowRight') {
                    // Navigate to next image
                    console.log('Next image');
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>