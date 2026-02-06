# Perbaikan Fungsi Search Dokumen - beranda.php

## Tanggal Perbaikan
Februari 3, 2026

## Ringkasan Perbaikan
Fungsi pencarian dokumen di halaman beranda telah diperbaiki untuk memastikan:
1. Pencarian dari seluruh database (5 tabel)
2. Tombol detail dapat diklik dan berfungsi dengan baik
3. Hasil pencarian lebih komprehensif
4. URL routing yang benar untuk setiap tipe dokumen

---

## Detail Perbaikan

### 1. **Pencarian Database Tanpa Filter Gambar**
**Sebelum:**
- Pencarian dokumen hanya menampilkan dokumen yang memiliki gambar (`image_name IS NOT NULL OR image_path IS NOT NULL`)
- Menyebabkan banyak dokumen tidak muncul di hasil pencarian

**Sesudah:**
- Menghilangkan filter gambar dari query
- Semua dokumen dapat ditemukan, bahkan yang tidak memiliki gambar
- Limit pencarian ditingkatkan dari 15 menjadi 20 untuk uploads

### 2. **Penambahan Field Pencarian**
**Field baru yang ditambahkan:**
- `singkatanJenis` - singkatan jenis dokumen
- `deskripsi` - untuk pencarian berdasarkan deskripsi dokumen

### 3. **Sorting Hasil Pencarian**
**Perbaikan:**
- Hasil dari semua tabel diurutkan berdasarkan `tanggal_penetapan` DESC
- Dokumen terbaru muncul di paling atas
- Menggunakan `usort()` PHP untuk menggabungkan dan mengurutkan hasil

### 4. **Perbaikan URL Detail Page**
**Perubahan Fungsi `getDetailUrl()`:**

```php
// Sebelum: Menggunakan relative URL (?)
return "?page=detail-keputusan&id=" . $id;

// Sesudah: Menggunakan full path dengan index.php
return "index.php?page=detail-keputusan&id=" . intval($id);
```

**Keuntungan:**
- URL lebih konsisten
- Berfungsi dari berbagai level path
- Lebih robust untuk routing

### 5. **Perbaikan Tombol Detail & Download**
**CSS Enhancement:**
- Added `!important` declarations untuk override styling
- Tambah `cursor: pointer` untuk clarity
- Perbaikan hover state yang lebih jelas
- Inline styles untuk fallback styling

**HTML Improvement:**
- Simplified button structure
- Menambahkan safety checks untuk URL
- Better error handling dengan fallback `#`

### 6. **Tabel Pencarian yang Dioptimalkan**

#### Uploads (Produk Hukum)
- Limit: 20 hasil
- Field: judul, noPeraturan, subjek, jenis, sumber, bidangHukum, singkatanJenis, deskripsi

#### Artikel Hukum
- Limit: 15 hasil  
- Field: judul, penulis, abstrak, keywords, bidang_hukum
- Status: Published

#### Artikel Kegiatan
- Limit: 15 hasil
- Field: judul, konten, meta_keywords, nama_penulis
- Status: publik

#### Buku Perpustakaan
- Limit: 15 hasil
- Field: judul, pengarang, penerbit, subjek_topik, bidang_hukum, isbn

#### Yurisprudensi
- Limit: 15 hasil
- Field: judul, nomor_putusan, teu_badan, kata_kunci, bidang_hukum

---

## Fitur-Fitur Pencarian

### ✅ Pencarian Multitabel
Sistem pencarian sekarang mencari di 5 tabel sekaligus:
1. Uploads (Dokumen Hukum)
2. Artikel Hukum
3. Artikel Kegiatan
4. Buku Perpustakaan
5. Yurisprudensi

### ✅ Tombol Detail Funktional
Setiap hasil pencarian memiliki tombol "Lihat Detail" yang:
- Mengarahkan ke halaman detail yang sesuai
- Menggunakan enkripsi ID untuk keamanan (artikel, kegiatan, buku, yurisprudensi)
- Fallback ke plain ID untuk dokumen uploads

### ✅ Tombol Download (jika ada file)
- Hanya muncul untuk dokumen yang memiliki file PDF
- Download langsung tanpa membuka halaman baru
- Badge status "File tersedia" / "Tanpa file"

### ✅ Metadata Lengkap
Setiap hasil menampilkan:
- Judul
- Nomor/Identifier (jika ada)
- Tanggal penetapan/terbit/publikasi
- Jenis dokumen
- Penulis/Sumber (jika ada)
- Bidang hukum
- Status file (tersedia/tanpa file)

### ✅ Validasi Input
- Minimum 2 karakter untuk pencarian
- XSS Protection dengan htmlspecialchars()
- SQL Injection Protection dengan mysqli_real_escape_string()

---

## Testing Checklist

- [x] Pencarian dokumen umum (uploads)
- [x] Pencarian artikel hukum
- [x] Pencarian kegiatan
- [x] Pencarian buku
- [x] Pencarian yurisprudensi
- [x] Tombol detail clickable
- [x] Tombol download berfungsi
- [x] URL routing benar
- [x] Styling buttons konsisten
- [x] Hasil diurutkan berdasarkan tanggal

---

## Cara Menggunakan

1. **Pencarian dari Search Box (Hero Section)**
   - Ketik minimal 2 karakter
   - Klik tombol search atau tekan Enter
   - Hasil ditampilkan dalam grid

2. **Tombol Detail**
   - Klik "Lihat Detail" untuk membuka halaman detail
   - Setiap tipe dokumen memiliki halaman detail sendiri

3. **Download PDF**
   - Klik "Download" untuk mendownload file
   - Hanya muncul jika dokumen memiliki file

---

## Notes Teknis

### Fungsi-Fungsi Penting:
- `searchGlobal($keyword, $koneksi)` - Mencari di semua tabel
- `getDetailUrl($item, $koneksi)` - Generate URL detail berdasarkan tipe
- `resolveItemImage($item)` - Resolve path gambar
- `resolveItemPdf($item)` - Resolve path PDF

### Security:
- Menggunakan `mysqli_real_escape_string()` untuk SQL injection protection
- Menggunakan `htmlspecialchars()` untuk XSS protection
- Enkripsi ID untuk artikel, kegiatan, buku, yurisprudensi

---

## Daftar File yang Dimodifikasi
- `pages/beranda.php` - Perbaikan fungsi search dan UI

---

## Kontribusi
Perbaikan ini meningkatkan UX dan fungsionalitas sistem JDIH DPRD Surakarta.
