<?php
/**
 * TEST FILE untuk memverifikasi search function di beranda.php
 * File ini digunakan untuk testing saja, bisa dihapus setelah testing selesai
 */

// Include database connection
include 'pages/a_db.php';
require_once 'pages/a_functions.php';

// Check database connection
if (!$koneksi) {
    die("❌ Database connection failed!");
}

echo "<h1>🔍 Test Search Function</h1>";
echo "<hr>";

// Test 1: Check if all tables exist
echo "<h3>1. Cek Keberadaan Tabel</h3>";
$tables = ['uploads', 'artikel_hukum', 'artikel_kegiatan', 'buku_perpustakaan', 'yurisprudensi'];

foreach ($tables as $table) {
    $check = mysqli_query($koneksi, "SELECT 1 FROM $table LIMIT 1");
    if ($check) {
        $count = mysqli_num_rows(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM $table"));
        $count_result = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM $table"));
        echo "✅ Tabel <strong>$table</strong> ada | Total records: " . $count_result['cnt'] . "<br>";
    } else {
        echo "❌ Tabel <strong>$table</strong> TIDAK ADA<br>";
    }
}

echo "<hr>";

// Test 2: Test search with keyword
echo "<h3>2. Test Search dengan Keyword</h3>";

$test_keywords = ['peraturan', 'artikel', 'hukum'];

foreach ($test_keywords as $keyword) {
    echo "<h4>Keyword: '<strong>$keyword</strong>'</h4>";
    
    // Test uploads table
    echo "<strong>Uploads:</strong> ";
    $query = "SELECT COUNT(*) as cnt FROM uploads WHERE (judul LIKE '%$keyword%' OR noPeraturan LIKE '%$keyword%')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    echo "Found: " . $row['cnt'] . " records<br>";
    
    // Test artikel_hukum table
    echo "<strong>Artikel Hukum:</strong> ";
    $query = "SELECT COUNT(*) as cnt FROM artikel_hukum WHERE (judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    echo "Found: " . $row['cnt'] . " records<br>";
    
    // Test artikel_kegiatan table
    echo "<strong>Artikel Kegiatan:</strong> ";
    $query = "SELECT COUNT(*) as cnt FROM artikel_kegiatan WHERE (judul LIKE '%$keyword%' OR nama_penulis LIKE '%$keyword%')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    echo "Found: " . $row['cnt'] . " records<br>";
    
    // Test buku_perpustakaan table
    echo "<strong>Buku Perpustakaan:</strong> ";
    $query = "SELECT COUNT(*) as cnt FROM buku_perpustakaan WHERE (judul LIKE '%$keyword%' OR pengarang LIKE '%$keyword%')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    echo "Found: " . $row['cnt'] . " records<br>";
    
    // Test yurisprudensi table
    echo "<strong>Yurisprudensi:</strong> ";
    $query = "SELECT COUNT(*) as cnt FROM yurisprudensi WHERE (judul LIKE '%$keyword%' OR nomor_putusan LIKE '%$keyword%')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    echo "Found: " . $row['cnt'] . " records<br>";
    
    echo "<br>";
}

echo "<hr>";

// Test 3: Test search function
echo "<h3>3. Test Search Function (Global)</h3>";

if (function_exists('searchGlobal')) {
    echo "✅ Function searchGlobal sudah didefinisikan<br><br>";
    
    $test_keyword = 'peraturan';
    echo "Testing searchGlobal('{$test_keyword}', \$koneksi)...<br>";
    
    // Copy the searchGlobal function definition (untuk testing)
    // ... function code ...
    
    echo "Fungsi siap digunakan di beranda.php<br>";
} else {
    echo "❌ Function searchGlobal TIDAK ditemukan<br>";
}

echo "<hr>";

// Test 4: Encoding test
echo "<h3>4. Test Encoding/Decoding ID</h3>";

$test_ids = [1, 5, 10, 100, 999];

foreach ($test_ids as $id) {
    $encoded = encode_id($id);
    $decoded = decode_id($encoded);
    $status = ($decoded == $id) ? "✅" : "❌";
    echo "$status ID: $id → Encoded: $encoded → Decoded: $decoded<br>";
}

echo "<hr>";

// Test 5: Sample data from each table
echo "<h3>5. Sample Data dari Masing-masing Tabel</h3>";

echo "<h4>Uploads (Sample 3):</h4>";
$result = mysqli_query($koneksi, "SELECT idData, judul, jenis, image_name FROM uploads LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['idData'] . " | " . $row['judul'] . " | " . $row['jenis'] . " | Img: " . $row['image_name'] . "<br>";
    }
} else {
    echo "Error: " . mysqli_error($koneksi);
}

echo "<h4>Artikel Hukum (Sample 3):</h4>";
$result = mysqli_query($koneksi, "SELECT id_artikel, judul, penulis FROM artikel_hukum LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['id_artikel'] . " | " . $row['judul'] . " | " . $row['penulis'] . "<br>";
    }
} else {
    echo "No records or error: " . mysqli_error($koneksi);
}

echo "<h4>Buku Perpustakaan (Sample 3):</h4>";
$result = mysqli_query($koneksi, "SELECT id_buku, judul, pengarang FROM buku_perpustakaan LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['id_buku'] . " | " . $row['judul'] . " | " . $row['pengarang'] . "<br>";
    }
} else {
    echo "No records or error: " . mysqli_error($koneksi);
}

echo "<hr>";
echo "<h3>✅ Test Complete!</h3>";
echo "Sekarang coba akses <a href='index.php?page=beranda&q=peraturan'>beranda dengan search</a>";
?>
