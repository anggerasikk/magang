<?php
// a_functions.php - VERSI SEDERHANA

// Konfigurasi
define('SECRET_KEY', 'KunciRahasia32Karakter1234567890ABCDEF');
define('SECRET_IV', 'IV_JDIH_2025_RAHASIA');

// Enkripsi ID
function encode_id($id) {
    if (!is_numeric($id) || $id <= 0) return null;
    
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    
    $encrypted = openssl_encrypt($id, 'AES-256-CBC', $key, 0, $iv);
    if (!$encrypted) return null;
    
    return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
}

// Dekripsi ID
function decode_id($token) {
    if (empty($token)) return 0;
    
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    
    $decoded = base64_decode(strtr($token, '-_', '+/'));
    if (!$decoded) return 0;
    
    $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
    if (!$decrypted || !is_numeric($decrypted)) return 0;
    
    return (int)$decrypted;
}

// Sanitize input
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Safe output
function safe_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Security headers
function set_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
}
?>