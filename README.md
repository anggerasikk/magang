# Sistem Informasi JDIH DPRD

Aplikasi web **Jaringan Dokumentasi dan Informasi Hukum (JDIH)** untuk membantu instansi pemerintahan mengelola, mendokumentasikan, dan menyebarluaskan produk serta informasi hukum kepada masyarakat. Aplikasi menyediakan situs publik dan panel administrasi untuk pengelolaan konten dan dokumen.

> **Catatan identitas:** sejumlah halaman dan data contoh di dalam kode masih menyebut DPRD Kota Surakarta, sementara nama folder proyek merujuk Cilacap. Sesuaikan nama instansi, logo, konten, tautan, dan data sebelum dipublikasikan atau digunakan di lingkungan instansi.

## Fitur

### Situs publik
- Beranda, profil JDIH, struktur organisasi, alur pengelolaan, SOP pengelola, dan dasar hukum.
- Katalog produk hukum, termasuk Peraturan Daerah, Peraturan DPRD, keputusan DPRD, keputusan pimpinan, dan keputusan sekretariat.
- Katalog monografi dan dokumen pendukung, seperti naskah akademik, kajian Raperda, risalah, pokok pikiran, dokumen langka, hasil fasilitasi, hasil harmonisasi, himpunan peraturan, dan karya ilmiah.
- Informasi yurisprudensi, artikel hukum, kegiatan, buku/perpustakaan, galeri, infografis, serta videografis.
- Pencarian dan penyaringan dokumen pada beberapa katalog, halaman detail, serta akses unduh berkas yang tersedia.
- Halaman kontak/saran dan informasi kelembagaan.

### Panel administrasi
Panel administrasi menyediakan pengelolaan data berbasis operasi CRUD pada modul yang mendukungnya. Modul yang ada mencakup:
- Produk hukum dan dokumen, termasuk unggah PDF serta gambar sampul.
- Monografi, artikel hukum, yurisprudensi, buku, kegiatan, galeri, dan konten grafis sesuai modul yang tersedia.
- Akun pengguna, level/role, dan status aktif pengguna.
- Ekspor dokumen ke PDF atau Word pada modul tertentu.

Hak akses yang terlihat di aplikasi mencakup **admin**, **user input**, dan **user biasa**; izin untuk menambah, mengubah, menghapus, atau mengelola pengguna bergantung pada modul dan level akun. Ketersediaan operasi tidak seragam pada seluruh halaman.

## Teknologi

- PHP dengan MySQLi
- MySQL/MariaDB
- HTML, CSS, JavaScript, Bootstrap, dan jQuery
- Composer untuk dependensi PHP, termasuk PHPWord dan mPDF

## Persyaratan

- Web server Apache dengan PHP yang kompatibel dengan dependensi pada `composer.lock` (gunakan PHP 8.2 atau lebih baru untuk memenuhi dependensi terkunci saat ini).
- MySQL atau MariaDB.
- Composer.
- Ekstensi PHP yang diperlukan aplikasi/dependensi, antara lain `mysqli`, `fileinfo`, `openssl`, `mbstring`, dan `gd`.
- Hak tulis web server pada direktori unggahan yang dipakai aplikasi.

## Instalasi lokal

Contoh berikut menggunakan Laragon:

1. Clone atau salin repositori ke direktori web, misalnya `C:\laragon\www\jdihcilacap_pasang_2026`.
2. Jalankan Apache dan MySQL/MariaDB melalui Laragon.
3. Buat database bernama `jdihdprd`, lalu impor **dump root** `jdihdprd.sql` melalui phpMyAdmin atau klien database.
4. Pasang dependensi dari direktori proyek dengan `composer install`.
5. Sesuaikan konfigurasi koneksi database pada:
   - `pages/a_db.php`
   - `panel_control/a_db.php`
   - `panel_control/koneksi.php`
6. Pastikan direktori unggahan yang diperlukan tersedia dan dapat ditulis oleh PHP. Lokasi yang dipakai beberapa modul antara lain `panel_control/uploads/`, termasuk subdirektori dokumen, gambar, monografi, yurisprudensi, buku, galeri, dan artikel.
7. Buka situs melalui `http://localhost/jdihcilacap_pasang_2026/`. Panel administrasi tersedia melalui `http://localhost/jdihcilacap_pasang_2026/panel_control/`.

Konfigurasi koneksi dan lokasi berkas pada beberapa halaman masih menggunakan nilai/path lokal tertentu. Untuk server lain, periksa dan sesuaikan path upload, URL dasar, serta konfigurasi database pada modul terkait.

## Database

Database utama menggunakan nama `jdihdprd`. Dump root `jdihdprd.sql` berisi skema inti seperti `uploads`, `monografi`, `artikel_hukum`, `galeri_foto`, `grafis`, `phpmu_user`, dan `produkdprd`.

Repositori juga memiliki `database/jdihdprd.sql`, yaitu dump yang lebih lama dan berbeda strukturnya. Jangan mengimpor kedua dump ke database yang sama tanpa meninjau perbedaannya. Beberapa halaman aplikasi merujuk tabel tambahan yang tidak tercantum dalam skema inti tersebut; modul-modul itu mungkin memerlukan skema tambahan atau migrasi sebelum dapat digunakan sepenuhnya.

## Struktur direktori utama

```text
.
├── app/                 # Direktori aplikasi pendukung
├── database/            # Dump database versi terdahulu
├── pages/               # Halaman publik dan detail dokumen
├── panel_control/       # Panel administrasi dan modul pengelolaan
├── vendor/              # Dependensi Composer
├── composer.json        # Definisi dependensi PHP
├── jdihdprd.sql         # Dump database utama
└── index.php            # Entry point situs publik
```

## Catatan keamanan dan penggunaan

Repositori ini merupakan aplikasi PHP lama/berkembang dengan konfigurasi yang masih perlu ditinjau sebelum dipasang di server publik. Sebelum deployment:

- Ganti kredensial database dan rahasia yang masih berada di dalam source code; jangan menyimpan kredensial produksi di repositori.
- Tinjau dan migrasikan autentikasi lama yang masih menggunakan MD5 ke `password_hash()` dan `password_verify()`.
- Tinjau ulang validasi unggahan, otorisasi setiap aksi CRUD, perlindungan CSRF, konfigurasi session, dan penanganan error.
- Periksa path absolut/URL lokal yang tertanam pada beberapa modul agar sesuai lingkungan deployment.
- Jangan gunakan akun contoh, data pribadi, berkas internal, atau dokumen instansi tanpa izin. Bersihkan data dump dan direktori unggahan sebelum repositori dibuat publik.
- Matikan tampilan error/debug pada produksi dan batasi akses ke file diagnostik serta log.

## Status

Aplikasi berisi situs publik dan sejumlah modul administrasi yang sudah memiliki alur pengelolaan data. Karena skema, konfigurasi, dan dukungan operasi berbeda antar-modul, lakukan pengujian fungsional pada setiap modul setelah instalasi dan sebelum digunakan secara resmi.
