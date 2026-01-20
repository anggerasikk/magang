<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "jdihdprd";

$koneksi = new mysqli($host, $user, $pass, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}


// Set charset
$koneksi->set_charset("utf8mb4");

// Set session user_id default jika belum ada
if (!isset($_SESSION['user_id'])) {
    // Ambil user_id dari database atau set default
    // Contoh: ambil user pertama dari tabel users
    $result = $koneksi->query("SELECT id_user FROM phpmu_user LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_user'];
    } else {
        $_SESSION['user_id'] = 1; // Fallback
    }
}

?>
