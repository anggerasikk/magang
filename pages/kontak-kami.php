<?php
// kontak-kami.php
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kontak Kami - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
    
    <!-- NAVBAR CSS -->
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        /* OVERRIDE UNTUK HALAMAN KONTAK */
        body {
            padding-top: 80px; /* Untuk fixed navbar */
            background: #f0f8ff;
        }
        
        /* PAGE HEADER - SAMA DENGAN BERANDA */
        .page-header {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            margin-bottom: 60px;
        }

        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: white;
        }

        .page-subtitle {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            color: white;
        }
        
        /* MAIN CONTENT */
        .main-content {
            padding: 60px 0;
            background: #f0f8ff;
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
        
        /* CONTACT CARDS */
        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 30px;
            border-top: 5px solid #13547a;
        }
        
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .contact-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
        }
        
        .contact-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .contact-info {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .contact-link {
            color: #13547a;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s;
        }
        
        .contact-link:hover {
            color: #80d0c7;
        }
        
        /* CONTACT FORM */
        .contact-form-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 50px;
        }
        
        .form-label {
            font-weight: 600;
            color: #13547a;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        
        .form-control:focus {
            border-color: #13547a;
            box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.1);
        }
        
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        
        .form-select:focus {
            border-color: #13547a;
            box-shadow: 0 0 0 3px rgba(19, 84, 122, 0.1);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(19, 84, 122, 0.3);
        }
        
        /* MAP SECTION */
        .map-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 50px;
        }
        
        .map-container {
            width: 100%;
            height: 400px;
        }
        
        .map-info {
            padding: 30px;
        }
        
        .map-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #13547a;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .map-address {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .direction-btn {
            background: transparent;
            border: 2px solid #13547a;
            color: #13547a;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .direction-btn:hover {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border-color: transparent;
        }
        
        /* OPERATIONAL HOURS */
        .hours-table {
            width: 100%;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .hours-table tr:nth-child(even) {
            background: white;
        }
        
        .hours-table td {
            padding: 15px 20px;
            color: #666;
        }
        
        .hours-table td:first-child {
            font-weight: 600;
            color: #13547a;
        }
        
        /* CUSTOM BUTTON - SAMA DENGAN BERANDA */
        .custom-btn {
            background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .custom-btn:hover {
            background: linear-gradient(135deg, #104366 0%, #6bc0b7 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(19, 84, 122, 0.3);
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 60px 0 40px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
            
            .contact-form-section {
                padding: 25px;
            }
            
            .contact-card {
                padding: 30px 20px;
            }
            
            .map-container {
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .contact-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .submit-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body id="top">

    <!-- NAVBAR SAMA DENGAN BERANDA -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <!-- BRAND -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <span class="fw-bold">JDIH DPRD</span>
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownTentangKami"
                            role="button" data-bs-toggle="dropdown">
                            Tentang Kami
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=Profil-JDIH">Profil JDIH</a></li>
                            <li><a class="dropdown-item" href="?page=struktur-organisasi">Struktur Organisasi</a></li>
                            <li><a class="dropdown-item" href="?page=alur-pengelolaan">Alur Pengelolaan Produk Hukum</a></li>
                            <li><a class="dropdown-item" href="?page=sop-pengelola">SOP Pengelola JDIH DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=dasar-hukum">Dasar Hukum JDIH Sekretariat DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=surat-keputusan">Surat Keputusan Pengelola JDIH Sekretariat DPRD Kota Surakarta</a></li>
                            <li><a class="dropdown-item" href="?page=video-profile">Video Profile JDIH</a></li>
                        </ul>
                    </li>

                    <!-- PERUNDANGAN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownPerundangan"
                            role="button" data-bs-toggle="dropdown">
                            Perundang-undangan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=keputusan-dprd">Keputusan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=keputusan-pimpinan">Keputusan Pimpinan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=keputusan-sekretariat">Keputusan Sekretariat DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=peraturan-dprd">Peraturan DPRD</a></li>
                            <li><a class="dropdown-item" href="?page=perda">Peraturan Daerah</a></li>
                            <li><a class="dropdown-item" href="?page=english-legal">English Legal Product</a></li>
                        </ul>
                    </li>

                    <!-- MONOGRAFI -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMonografi"
                            role="button" data-bs-toggle="dropdown">
                            Monografi Hukum
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=pokok-pikiran">Pokok Pikiran</a></li>
                            <li><a class="dropdown-item" href="?page=kajian-raperda">Kajian Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=naskah-akademik">Naskah Akademik</a></li>
                            <li><a class="dropdown-item" href="?page=dokumen-langka">Dokumen Langka</a></li>
                            <li><a class="dropdown-item" href="?page=risalah">Risalah</a></li>
                            <li><a class="dropdown-item" href="?page=hasil-fasilitasi">Hasil Fasilitasi Raperda</a></li>
                            <li><a class="dropdown-item" href="?page=karya-ilmiah">Penulisan Karya Ilmiah</a></li>
                            <li><a class="dropdown-item" href="?page=himpunan">Himpunan Peraturan</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?page=artikel-hukum">Artikel Hukum</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?page=yurisprudensi">Yurisprudensi</a>
                    </li>

                    <!-- INFORMASI -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownInformasi"
                            role="button" data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=kegiatan">Kegiatan JDIH</a></li>
                            <li><a class="dropdown-item" href="?page=infografis">Infografis & Videografis</a></li>
                            <li><a class="dropdown-item" href="?page=galeri">Galeri Foto</a></li>
                            <li><a class="dropdown-item" href="?page=penghargaan">Penghargaan</a></li>
                            <li><a class="dropdown-item" href="?page=buku">Koleksi Buku</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kontak-kami.php">Kontak Kami</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- PAGE HEADER - SAMA DENGAN BERANDA -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">Kontak Kami</h1>
            <p class="page-subtitle">
                Hubungi kami untuk informasi lebih lanjut mengenai JDIH DPRD Kota Surakarta
            </p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <!-- CONTACT INFORMATION -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">Informasi Kontak</h2>
                </div>
            </div>
            
            <div class="row">
                <!-- Alamat -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h3 class="contact-title">Alamat Kantor</h3>
                        <div class="contact-info">
                            <p>
                                Sekretariat DPRD Kota Surakarta<br>
                                Jl. Jenderal Sudirman No. 2<br>
                                Kecamatan Banjarsari<br>
                                Kota Surakarta 57139
                            </p>
                        </div>
                        <a href="#lokasi" class="contact-link">
                            Lihat di Peta <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Telepon -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h3 class="contact-title">Telepon & Fax</h3>
                        <div class="contact-info">
                            <p>
                                <strong>Telepon:</strong><br>
                                (0271) 712461
                            </p>
                            <p>
                                <strong>Fax:</strong><br>
                                (0271) 717620
                            </p>
                        </div>
                        <a href="tel:+62271712461" class="contact-link">
                            Hubungi Kami <i class="bi bi-telephone-outbound ms-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h3 class="contact-title">Email & WhatsApp</h3>
                        <div class="contact-info">
                            <p>
                                <strong>Email:</strong><br>
                                admin@sekwan.surakarta.go.id
                            </p>
                            <p>
                                <strong>WhatsApp:</strong><br>
                                6287872844090
                            </p>
                        </div>
                        <a href="mailto:admin@sekwan.surakarta.go.id" class="contact-link">
                            Kirim Email <i class="bi bi-envelope-arrow-up ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- CONTACT FORM -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="contact-form-section">
                        <h2 class="section-title mb-4">Kirim Pesan kepada Kami</h2>
                        
                        <form id="contactForm" method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Alamat Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="contoh@email.com" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Jenis Pesan *</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="" selected disabled>Pilih jenis pesan</option>
                                            <option value="informasi">Permintaan Informasi</option>
                                            <option value="saran">Saran dan Masukan</option>
                                            <option value="konsultasi">Konsultasi Hukum</option>
                                            <option value="kerjasama">Penawaran Kerjasama</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Isi Pesan *</label>
                                <textarea class="form-control" id="message" name="message" 
                                          placeholder="Tulis pesan Anda di sini..." rows="5" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        Saya setuju dengan <a href="#" class="text-decoration-none">kebijakan privasi</a> 
                                        dan <a href="#" class="text-decoration-none">persyaratan layanan</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="submit-btn">
                                    <i class="bi bi-send me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- MAP LOCATION -->
            <div class="row mb-5" id="lokasi">
                <div class="col-12">
                    <div class="map-section">
                        <div class="map-container">
                            <!-- Google Maps Embed -->
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.054274366597!2d110.81201097596528!3d-7.555634992473557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a168b2d5ffac7%3A0x7aeab88436dd2d4e!2sDPRD%20Kota%20Surakarta!5e0!3m2!1sid!2sid!4v1702358400000!5m2!1sid!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="map-info">
                            <h3 class="map-title">Lokasi Kantor</h3>
                            <p class="map-address">
                                <strong>Sekretariat DPRD Kota Surakarta</strong><br>
                                Jl. Jenderal Sudirman No. 2, Kecamatan Banjarsari<br>
                                Kota Surakarta 57139<br>
                                Jawa Tengah, Indonesia
                            </p>
                            <a href="https://maps.google.com/maps?q=Sekretariat+DPRD+Kota+Surakarta" 
                               target="_blank" class="direction-btn">
                                <i class="bi bi-geo-alt"></i> Dapatkan Petunjuk Arah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- OPERATIONAL HOURS -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-card h-100">
                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h3 class="contact-title">Jam Operasional</h3>
                        <p class="contact-info mb-4">
                            Kami siap melayani Anda selama jam kerja berikut:
                        </p>
                        <table class="hours-table">
                            <tr>
                                <td>Senin - Kamis</td>
                                <td>07.30 - 16.00 WIB</td>
                            </tr>
                            <tr>
                                <td>Jumat</td>
                                <td>07.30 - 16.30 WIB</td>
                            </tr>
                            <tr>
                                <td>Sabtu - Minggu</td>
                                <td>Libur</td>
                            </tr>
                            <tr>
                                <td>Hari Libur Nasional</td>
                                <td>Libur</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="contact-card h-100">
                        <div class="contact-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3 class="contact-title">Layanan Konsultasi</h3>
                        <div class="contact-info">
                            <p>
                                <strong>Konsultasi Hukum:</strong><br>
                                Senin - Kamis: 09.00 - 15.00 WIB
                            </p>
                            <p>
                                <strong>Peminjaman Buku:</strong><br>
                                Senin - Jumat: 08.00 - 16.00 WIB
                            </p>
                            <p>
                                <strong>Pengajuan Dokumen:</strong><br>
                                Senin - Kamis: 08.00 - 14.00 WIB
                            </p>
                        </div>
                        <p class="text-muted mt-3" style="font-size: 0.9rem;">
                            * Silakan hubungi kami terlebih dahulu untuk membuat janji temu
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER SAMA DENGAN BERANDA -->
    <footer class="site-footer section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4 pb-2">
                    <a class="navbar-brand mb-2" href="index.php">
                        <span>JDIH DPRD</span>
                    </a>
                    <p class="text-white mt-3">
                        Jaringan Dokumentasi dan Informasi Hukum<br>
                        Dewan Perwakilan Rakyat Daerah<br>
                        Kota Surakarta
                    </p>
                </div>

                <div class="col-lg-3 col-md-4 col-6">
                    <h6 class="site-footer-title mb-3">Menu Utama</h6>
                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="index.php" class="site-footer-link">Beranda</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=perundangan" class="site-footer-link">Perundangan</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="?page=keputusan-sekretaris" class="site-footer-link">Keputusan Sekretaris</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="kontak-kami.php" class="site-footer-link">Kontak</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-6">
                    <h6 class="site-footer-title mb-3">Informasi</h6>
                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="?page=tentang" class="site-footer-link">Tentang Kami</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="kontak-kami.php" class="site-footer-link">Kontak</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Kebijakan Privasi</a>
                        </li>
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0 ms-auto">
                    <h6 class="site-footer-title mb-3">Kontak Kami</h6>
                    <p class="text-white mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        Sekretariat DPRD Kota Surakarta<br>
                        <span style="margin-left: 24px;">Jl. Jenderal Sudirman No. 2, Surakarta</span>
                    </p>
                    <p class="text-white mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        admin@sekwan.surakarta.go.id
                    </p>
                    <p class="text-white mb-4">
                        <i class="bi bi-telephone me-2"></i>
                        (0271) 712461
                    </p>
                    
                    <div class="copyright">
                        <p class="mt-4">
                            &copy; <?php echo date('Y'); ?> JDIH Sekretariat DPRD Kota Surakarta.<br>
                            Hak Cipta Dilindungi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(contactForm);
                const name = formData.get('name');
                const email = formData.get('email');
                const subject = formData.get('subject');
                const message = formData.get('message');
                
                // Simple validation
                if (!name || !email || !subject || !message) {
                    alert('Harap lengkapi semua bidang yang wajib diisi.');
                    return;
                }
                
                // Show loading state
                const submitBtn = contactForm.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
                submitBtn.disabled = true;
                
                // Simulate form submission (replace with actual AJAX call)
                setTimeout(() => {
                    // Success message
                    alert('Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.');
                    
                    // Reset form
                    contactForm.reset();
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 1500);
            });
            
            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.startsWith('0')) {
                        value = value.substring(1);
                    }
                    if (value.length > 3 && value.length <= 6) {
                        value = value.replace(/(\d{3})(\d{1,3})/, '$1-$2');
                    } else if (value.length > 6 && value.length <= 9) {
                        value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1-$2-$3');
                    } else if (value.length > 9) {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1,4})/, '$1-$2-$3-$4');
                    }
                }
                e.target.value = value;
            });
            
            // WhatsApp click handler
            const whatsappLinks = document.querySelectorAll('a[href*="whatsapp"], a[href*="6287872844090"]');
            whatsappLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = encodeURIComponent('Halo, saya ingin bertanya tentang JDIH DPRD Surakarta');
                    window.open(`https://wa.me/6287872844090?text=${message}`, '_blank');
                });
            });
            
            // Smooth scroll to map
            const mapLinks = document.querySelectorAll('a[href="#lokasi"]');
            mapLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const mapSection = document.getElementById('lokasi');
                    if (mapSection) {
                        mapSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
            
            // Navbar active state
            const currentPage = 'kontak-kami.php';
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>

</body>
</html>