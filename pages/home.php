<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>JDIH DPRD </title>
    
    <!-- CSS FILES -->        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">
</head>
<body>
    <?php
    // SISTEM ROUTING UNTUK MENAMPILKAN HALAMAN BERDASARKAN PARAMETER GET
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    
    // PERBAIKAN: switch statement tidak memiliki break untuk beberapa case
    switch($page) {
        case 'Beranda': 
        case '': // Jika kosong
            include "beranda.php"; // GANTI dari home_content.php ke beranda.php
            break;
        case 'perundangan':
            include "perundangan.php";
            break;
        case 'detail-keputusan':
            include "detail-keputusan.php";
            break;
        case 'struktur-organisasi':
            include "struktur-organisasi.php";
            break;
        case 'Profil-JDIH':
            include "Profil-JDIH.php"; 
            break;
        case 'alur-pengelolaan':
            include "alur-pengelolaan.php"; 
            break;
        case 'sop-pengelola':
            include "sop-pengelola.php"; 
            break;
        case 'dasar-hukum':
            include "dasar-hukum.php";
            break;
        case 'visi-misi':           
            include "visi-misi.php"; 
            break;
        case 'video-profile':
            include "video-profile.php";
            break;
        case 'keputusan-dprd':
            include "keputusan-dprd.php";
            break;
        case 'keputusan-pimpinan':
            include "keputusan-pimpinan.php";
            break;
        case 'keputusan-sekretariat':
            include "keputusan-sekretariat.php";
            break;
        case 'peraturan-dprd':
            include "peraturan-dprd.php";
            break;
        case 'perda':
            include "perda.php";
            break;
        case 'english-legal':
            include "english-legal.php";
            break;
        case 'kajian-raperda':
            include "kajian-raperda.php";                       
            break;
        case 'naskah-akademik':
            include "naskah-akademik.php"; 
            break;  
        case 'dokumen-langka':
            include "dokumen-langka.php";
            break;
        case 'risalah':          
            include "risalah.php"; 
            break;      
        case 'hasil-fasilitasi':          
            include "hasil-fasilitasi.php";
            break;
        case 'karya-ilmiah':          
            include "karya-ilmiah.php";
            break;
        case 'himpunan':
            // Tambahan untuk himpunan
            include "himpunan.php";
            break;
        case 'artikel-hukum':
            include "artikel-hukum.php";
            break;
        case 'surat-keputusan':
            include "surat-keputusan.php";
            break;
        case 'pokok-pikiran':
            include "pokok-pikiran-dprd.php";
            break;
        case 'hasil-harmonisasi':
            include "hasil-harmonisasi.php";
            break;
        case 'kegiatan':
            include "kegiatan-jdih.php";
            break;
        case 'detail-kegiatan-jdih':
case 'detail-kegiatan':  // Tambahkan alias
    include 'detail-kegiatan.php';
    break;

        break;
            case 'infografis':
            include "infografis.php";
            break;
        case 'penghargaan':
            include "penghargaan.php";
            break;
        case 'yurisprudensi':
            include "yurispudensi.php";
            break;  
        case 'buku':
            include "buku.php";
            break;
        case 'galeri':
            include "galeri.php";
            break;
        case 'kontak-kami':
            include "kontak-kami.php";
            break;
        // Di index.php, tambahkan:
        case 'detail-monografi':
        include 'detail-monografi.php';
        break;

        case 'detail-yurisprudensi':
        include 'detail-yurisprudensi.php';
        break;

        case 'detail-buku':
        include 'detail-buku.php';  
        break;

        case 'detail-artikel-kegiatan':
        include 'detail-artikel.php';
        break;

        default:
            include "beranda.php"; // Default ke beranda.php
            break;
    }
       
    ?>
    
    <!-- JS Files jika ada -->
</body>
</html>