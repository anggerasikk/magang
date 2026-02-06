<?php
/**
 * KONFIGURASI RAHASIA
 * JANGAN DIUBAH SETELAH PRODUKSI
 */
define('SECRET_KEY', 'GANTI_DENGAN_KUNCI_RAHASIA_PANJANG_DAN_UNIK');
define('SECRET_IV',  'IV_JDIH_2025_RAHASIA');

/**
 * ENKRIPSI ID (URL-SAFE)
 */
function encode_id($id) {
    if (!is_numeric($id)) return null;

    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);

    $encrypted = openssl_encrypt(
        $id,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    // URL-safe base64
    return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
}

/**
 * DEKRIPSI TOKEN KE ID ASLI
 */
function decode_id($token) {
    if (empty($token)) return 0;

    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);

    $decoded = base64_decode(strtr($token, '-_', '+/'));

    $decrypted = openssl_decrypt(
        $decoded,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return (is_numeric($decrypted)) ? (int)$decrypted : 0;
}
