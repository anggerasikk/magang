<?php

include_once "a_db.php";
// ============================================
// KEAMANAN LEVEL 1: ERROR HANDLING
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 0); // Tidak tampilkan error ke user
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// ============================================
// KEAMANAN LEVEL 2: SESSION SECURITY
// ============================================
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' => 'Strict',
    'use_only_cookies' => 1
]);

// ============================================
// KEAMANAN LEVEL 3: INCLUDE REQUIRED FILES
// ============================================
// Check and include a_functions.php dan a_db.php dengan error handling
if (!file_exists('a_functions.php')) {
    error_log("CRITICAL: a_functions.php not found in " . __DIR__);
    
    // Fallback functions if a_functions.php doesn't exist
    function sanitize($input) {
        if (is_array($input)) {
            return array_map('sanitize', $input);
        }
        // Handle null values untuk PHP 8.1+
        if ($input === null) {
            return '';
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    function safe_output($data) {
        // Handle null values untuk PHP 8.1+
        if ($data === null) {
            return '';
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    function sanitize_input($input) {
        return sanitize($input);
    }
} else {
    include_once "a_functions.php";
    
    // Override fungsi safe_output jika perlu
    if (!function_exists('safe_output')) {
        function safe_output($data) {
            if ($data === null) {
                return '';
            }
            if (is_array($data)) {
                return array_map('safe_output', $data);
            }
            if (!is_string($data)) {
                $data = (string)$data;
            }
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
}



// ============================================
// KEAMANAN LEVEL 4: INPUT VALIDATION & SANITIZATION
// ============================================
// Sanitize all POST parameters
$clean_post = [];
foreach ($_POST as $key => $value) {
    $clean_post[$key] = sanitize_input($value);
}

// Inisialisasi variabel dengan data sanitized
$success_message = '';
$error_message = '';
$validation_errors = [];

// ============================================
// KEAMANAN LEVEL 5: ANTI-SPAM & RATE LIMITING
// ============================================
// Cek jika ada session untuk rate limiting
if (!isset($_SESSION['last_submission'])) {
    $_SESSION['last_submission'] = 0;
}

$current_time = time();
$time_since_last = $current_time - $_SESSION['last_submission'];

// Rate limiting: minimal 30 detik antara submission
if ($time_since_last < 30 && $_SERVER["REQUEST_METHOD"] == "POST") {
    $error_message = "Silakan tunggu 30 detik sebelum mengirim pesan lagi.";
}

// ============================================
// KEAMANAN LEVEL 6: HONEYPOT TRAP
// ============================================
// Honeypot field untuk menangkap bot
$honeypot_field = isset($_POST['honeypot']) ? trim($_POST['honeypot']) : '';

// Jika honeypot diisi (hanya bot yang akan mengisi), tolak submission
if (!empty($honeypot_field)) {
    error_log("Bot detected via honeypot on contact form");
    // Tampilkan pesan sukses palsu untuk menipu bot
    $success_message = "Terima kasih! Pesan Anda telah berhasil dikirim.";
    // Log aktivitas tanpa menyimpan ke database
    $access_log = sprintf(
        "[%s] BOT DETECTED - IP: %s | Form: contact | Honeypot triggered\n",
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    );
    error_log($access_log);
}

// ============================================
// KEAMANAN LEVEL 7: CSRF PROTECTION
// ============================================
// Generate CSRF token jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ============================================
// KEAMANAN LEVEL 8: PROSES FORM DENGAN VALIDASI KETAT
// ============================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message) && empty($honeypot_field)) {
    // Validasi CSRF token
    $csrf_token = $clean_post['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error_message = "Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.";
        error_log("CSRF token mismatch on contact form");
    } else {
        // Ambil dan sanitasi data dari clean_post (sudah disanitasi)
        $nama = $clean_post['name'] ?? '';
        $email = $clean_post['email'] ?? '';
        $telepon = $clean_post['phone'] ?? '';
        $subjek = $clean_post['subject'] ?? '';
        $pesan = $clean_post['message'] ?? '';
        
        // Validasi ketat
        $errors = [];
        
        // Validasi nama dengan regex
        if (empty($nama)) {
            $errors[] = "Nama lengkap wajib diisi";
        } elseif (strlen($nama) < 3) {
            $errors[] = "Nama minimal 3 karakter";
        } elseif (strlen($nama) > 100) {
            $errors[] = "Nama maksimal 100 karakter";
        } elseif (!preg_match('/^[a-zA-Z\s\.\-\']+$/u', $nama)) {
            $errors[] = "Nama hanya boleh mengandung huruf, spasi, titik, tanda hubung, dan apostrof";
        }
        
        // Validasi email ketat
        if (empty($email)) {
            $errors[] = "Email wajib diisi";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        } elseif (strlen($email) > 100) {
            $errors[] = "Email maksimal 100 karakter";
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $errors[] = "Format email tidak sesuai";
        }
        
        // Validasi telepon opsional
        if (!empty($telepon)) {
            if (strlen($telepon) > 20) {
                $errors[] = "Nomor telepon maksimal 20 karakter";
            } elseif (!preg_match('/^[\d\s\-\+\(\)]+$/', $telepon)) {
                $errors[] = "Format nomor telepon tidak valid";
            }
        }
        
        // Validasi subjek
        $allowed_subjects = ['informasi', 'saran', 'konsultasi', 'kerjasama', 'lainnya'];
        if (empty($subjek)) {
            $errors[] = "Jenis pesan wajib dipilih";
        } elseif (!in_array($subjek, $allowed_subjects)) {
            $errors[] = "Jenis pesan tidak valid";
        }
        
        // Validasi pesan ketat
        if (empty($pesan)) {
            $errors[] = "Pesan wajib diisi";
        } elseif (strlen($pesan) < 10) {
            $errors[] = "Pesan minimal 10 karakter";
        } elseif (strlen($pesan) > 2000) {
            $errors[] = "Pesan maksimal 2000 karakter";
        } elseif (preg_match('/http[s]?:\/\/|www\.|\[url|\[link/i', $pesan)) {
            $errors[] = "Pesan mengandung link yang tidak diperbolehkan";
        }
        
        // Cek checkbox persetujuan
        if (!isset($clean_post['privacy'])) {
            $errors[] = "Anda harus menyetujui kebijakan privasi";
        }
        
        // Cek jumlah kata kunci spam
        $spam_keywords = ['viagra', 'cialis', 'casino', 'lottery', 'porn', 'xxx', 'buy now', 'click here'];
        $message_lower = strtolower($pesan);
        foreach ($spam_keywords as $keyword) {
            if (strpos($message_lower, $keyword) !== false) {
                $errors[] = "Pesan mengandung konten yang tidak diizinkan";
                break;
            }
        }
        
        // Jika tidak ada error, simpan ke database
        if (empty($errors)) {
            try {
                // Siapkan query dengan prepared statement untuk mencegah SQL injection
                $sql = "INSERT INTO pesan_kontak 
                (nama, email, telepon, subjek, pesan) 
                VALUES (?, ?, ?, ?, ?)";

                
                if ($stmt = $koneksi->prepare($sql)) {
                    // Bind parameter
                    $stmt->bind_param("sssss", 
                        $nama, 
                        $email, 
                        $telepon, 
                        $subjek, 
                        $pesan
                    );
                    
                    // Eksekusi
                    if ($stmt->execute()) {
                        $success_message = "Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.";
                        
                        // Set rate limiting timestamp
                        $_SESSION['last_submission'] = $current_time;
                        
                        // Generate new CSRF token untuk mencegah replay attacks
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        
                        // Log aktivitas sukses
                        $access_log = sprintf(
                            "[%s] CONTACT FORM - IP: %s | Name: %s | Email: %s | Subject: %s\n",
                            date('Y-m-d H:i:s'),
                            $ip_address,
                            substr($nama, 0, 50),
                            substr($email, 0, 50),
                            $subjek
                        );
                        error_log($access_log);
                        
                        // Reset form data dengan session untuk mencegah resubmission
                        $_SESSION['form_submitted'] = true;
                        
                        $_SESSION['success_token'] = bin2hex(random_bytes(8));

                        header("Location: index.php?page=kontak-kami&success=" . $_SESSION['success_token']);
                        exit();


                        
                    } else {
                        $error_message = "Maaf, terjadi kesalahan saat menyimpan data. Silakan coba lagi nanti.";
                        error_log("Database error on contact form: " . $stmt->error);
                    }
                    
                    $stmt->close();
                } else {
                    $error_message = "Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.";
                    error_log("Prepare statement failed: " . $koneksi->error);
                }
                
            } catch (Exception $e) {
                $error_message = "Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.";
                error_log("Exception on contact form: " . $e->getMessage());
            }
        } else {
            $validation_errors = $errors;
            
            // Log validation errors
            $validation_log = sprintf(
                "[%s] VALIDATION FAILED - IP: %s | Errors: %s\n",
                date('Y-m-d H:i:s'),
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                implode(", ", $errors)
            );
            error_log($validation_log);
        }
    }
}

if (
    isset($_GET['success'], $_SESSION['success_token'], $_SESSION['form_submitted']) &&
    hash_equals($_SESSION['success_token'], $_GET['success'])
) {
    $success_message = "Terima kasih! Pesan Anda telah berhasil dikirim.";
    unset($_SESSION['success_token'], $_SESSION['form_submitted']);
}


// ============================================
// KEAMANAN LEVEL 9: SET SECURITY HEADERS
// ============================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Include header setelah semua pemrosesan
include 'header.php';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kontak Kami - JDIH DPRD Kota Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Security: Prevent auto-complete pada form -->
    <meta name="autocomplete" content="off">
    
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
        
        /* ALERT MESSAGES */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .alert ul {
            margin-top: 10px;
            padding-left: 20px;
        }
        
        .alert ul li {
            margin-bottom: 5px;
        }
        
        .alert .btn-close {
            padding: 1rem;
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
        
        /* HONEYPOT FIELD - Tersembunyi dari user normal */
        .honeypot-field {
            display: none !important;
            position: absolute !important;
            left: -9999px !important;
            height: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
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
                        
                        <!-- Alert Messages -->
                        <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?php echo safe_output($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php echo safe_output($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($validation_errors)): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <strong>Perbaiki kesalahan berikut:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($validation_errors as $error): ?>
                                <li><?php echo safe_output($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form id="contactForm" method="POST" action="" autocomplete="off">
                            <!-- CSRF Protection Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo safe_output($_SESSION['csrf_token']); ?>">
                            
                            <!-- Honeypot Field (untuk menangkap bot) -->
                            <div class="honeypot-field">
                                <label for="website">Website</label>
                                <input type="text" id="website" name="honeypot" tabindex="-1" autocomplete="off">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Masukkan nama lengkap" 
                                               value="<?php echo isset($clean_post['name']) ? safe_output($clean_post['name']) : ''; ?>"
                                               required maxlength="100" pattern="^[a-zA-Z\s\.\-\']+$">
                                        <div class="form-text">Hanya huruf, spasi, titik, tanda hubung, dan apostrof</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Alamat Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="contoh@email.com" 
                                               value="<?php echo isset($clean_post['email']) ? safe_output($clean_post['email']) : ''; ?>"
                                               required maxlength="100" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                                        <div class="form-text">Contoh: nama@domain.com</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="08xxxxxxxxxx" maxlength="20"
                                               value="<?php echo isset($clean_post['phone']) ? safe_output($clean_post['phone']) : ''; ?>"
                                               pattern="^[\d\s\-\+\(\)]+$">
                                        <div class="form-text">Contoh: 081234567890 atau +6281234567890</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Jenis Pesan *</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="" selected disabled>Pilih jenis pesan</option>
                                            <option value="informasi" <?php echo (isset($clean_post['subject']) && $clean_post['subject'] == 'informasi') ? 'selected' : ''; ?>>Permintaan Informasi</option>
                                            <option value="saran" <?php echo (isset($clean_post['subject']) && $clean_post['subject'] == 'saran') ? 'selected' : ''; ?>>Saran dan Masukan</option>
                                            <option value="konsultasi" <?php echo (isset($clean_post['subject']) && $clean_post['subject'] == 'konsultasi') ? 'selected' : ''; ?>>Konsultasi Hukum</option>
                                            <option value="kerjasama" <?php echo (isset($clean_post['subject']) && $clean_post['subject'] == 'kerjasama') ? 'selected' : ''; ?>>Penawaran Kerjasama</option>
                                            <option value="lainnya" <?php echo (isset($clean_post['subject']) && $clean_post['subject'] == 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Isi Pesan *</label>
                                <textarea class="form-control" id="message" name="message" 
                                          placeholder="Tulis pesan Anda di sini..." rows="5" required
                                          minlength="10" maxlength="2000"><?php echo isset($clean_post['message']) ? safe_output($clean_post['message']) : ''; ?></textarea>
                                <div class="form-text">Minimal 10 karakter, maksimal 2000 karakter</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required <?php echo isset($clean_post['privacy']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="privacy">
                                        Saya setuju dengan <a href="#" class="text-decoration-none">kebijakan privasi</a> 
                                        dan <a href="#" class="text-decoration-none">persyaratan layanan</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="submit-btn" id="submitBtn">
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
                            <!-- Google Maps Embed dengan referrer policy strict -->
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.054274366597!2d110.81201097596528!3d-7.555634992473557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a168b2d5ffac7%3A0x7aeab88436dd2d4e!2sDPRD%20Kota%20Surakarta!5e0!3m2!1sid!2sid!4v1702358400000!5m2!1sid!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="strict-origin-when-cross-origin">
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
                               target="_blank" rel="noopener noreferrer" class="direction-btn">
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
            
            // WhatsApp click handler dengan rel="noopener noreferrer"
            const whatsappLinks = document.querySelectorAll('a[href*="whatsapp"], a[href*="6287872844090"]');
            whatsappLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = encodeURIComponent('Halo, saya ingin bertanya tentang JDIH DPRD Surakarta');
                    window.open(`https://wa.me/6287872844090?text=${message}`, '_blank', 'noopener,noreferrer');
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
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                });
            }, 5000);
            
            // Form submission rate limiting
            const submitBtn = document.getElementById('submitBtn');
            const contactForm = document.getElementById('contactForm');
            
            if (submitBtn && contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
                    
                    // Add timestamp untuk mencegah form resubmission
                    const timestamp = Date.now();
                    const timestampField = document.createElement('input');
                    timestampField.type = 'hidden';
                    timestampField.name = 'timestamp';
                    timestampField.value = timestamp;
                    this.appendChild(timestampField);
                });
            }
            
            // Character counter untuk pesan
            const messageTextarea = document.getElementById('message');
            if (messageTextarea) {
                const counter = document.createElement('div');
                counter.className = 'form-text text-end';
                counter.id = 'charCounter';
                messageTextarea.parentNode.appendChild(counter);
                
                function updateCounter() {
                    const length = messageTextarea.value.length;
                    counter.textContent = `${length}/2000 karakter`;
                    if (length > 1900) {
                        counter.style.color = '#dc3545';
                    } else if (length > 1500) {
                        counter.style.color = '#ffc107';
                    } else {
                        counter.style.color = '#666';
                    }
                }
                
                messageTextarea.addEventListener('input', updateCounter);
                updateCounter();
            }
            
            // Spam detection pada client side
            if (messageTextarea) {
                messageTextarea.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    const spamWords = ['viagra', 'cialis', 'casino', 'lottery', 'porn', 'xxx'];
                    
                    for (const word of spamWords) {
                        if (value.includes(word)) {
                            alert('Pesan Anda mengandung kata-kata yang tidak diizinkan.');
                            this.value = '';
                            break;
                        }
                    }
                });
            }
            
            // Debug logging hanya di development
            <?php if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost'): ?>
            console.log("Contact form loaded with CSRF token: <?php echo substr($_SESSION['csrf_token'] ?? '', 0, 10); ?>...");
            <?php endif; ?>
            
            // Log form submission attempts
            contactForm.addEventListener('submit', function() {
                console.log("Form submission attempt logged");
            });
        });
    </script>

</body>
</html>