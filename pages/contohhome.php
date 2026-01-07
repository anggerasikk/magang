<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beranda</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .section-title {
      @apply text-2xl font-bold text-center mb-6;
    }
    .card {
      @apply bg-white rounded-lg shadow-lg p-4 text-center;
    }
    .card img {
      @apply mx-auto mb-2 w-16 h-16;
    }
    .doc-entry {
      @apply border border-gray-200 rounded-md p-3 mb-3;
    }
    .badge {
      @apply bg-green-500 text-white text-xs px-2 py-1 rounded;
    }
  </style>
</head>
<body class="bg-gray-100">
  <header class="bg-blue-900 text-white p-6">
    <h1 class="text-3xl font-bold">JDIH - Beranda</h1>
    <nav class="mt-2">
      <a href="/" class="mr-4 hover:underline">Beranda</a>
      <a href="?page=a_view_produkhukum" class="mr-4 hover:underline">Produk Hukum</a>
      <a href="?page=kontak" class="hover:underline">Kontak</a>
    </nav>
  </header>

  <main class="p-6">
    <h2 class="section-title">Jenis Dokumen Pilihan</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
      <div class="card">
        <img src="assets/img/hukum.png" alt="Peraturan Hukum">
        <p>Peraturan Hukum</p>
      </div>
      <div class="card">
        <img src="assets/img/monografi.png" alt="Monografi Hukum">
        <p>Monografi Hukum</p>
      </div>
      <div class="card">
        <img src="assets/img/artikel.png" alt="Artikel Hukum">
        <p>Artikel Hukum</p>
      </div>
      <div class="card">
        <img src="assets/img/yurisprudensi.png" alt="Yurisprudensi">
        <p>Yurisprudensi</p>
      </div>
      <div class="card">
        <img src="assets/img/infografis.png" alt="Infografis">
        <p>Infografis</p>
      </div>
    </div>

    <h2 class="section-title">Produk Hukum Terbaru</h2>
    <div class="doc-entry">
      <h3 class="text-blue-700 font-semibold">REGIONAL REGULATION OF SURAKARTA CITY NUMBER 3 YEAR 2020</h3>
      <p class="text-sm text-gray-600">CAPITAL PARTICIPATION OF SURAKARTA CITY GOVERNMENT...</p>
      <div class="text-xs text-gray-500">📄 English legal products • 📅 19 Juni 2025 • 👁 4x Dilihat</div>
      <span class="badge">Baru</span>
    </div>
    <div class="doc-entry">
      <h3 class="text-blue-700 font-semibold">REGIONAL REGULATION OF SURAKARTA CITY NUMBER 2 YEAR 2016</h3>
      <p class="text-sm text-gray-600">PREVENTION AND QUALITY IMPROVEMENT...</p>
      <div class="text-xs text-gray-500">📄 English legal products • 📅 19 Juni 2025 • 👁 3x Dilihat</div>
      <span class="badge">Baru</span>
    </div>
    <div class="doc-entry">
      <h3 class="text-blue-700 font-semibold">REGIONAL REGULATION OF SURAKARTA CITY NUMBER 12 OF 2017</h3>
      <p class="text-sm text-gray-600">PROVISION OF EDUCATION...</p>
      <div class="text-xs text-gray-500">📄 English legal products • 📅 19 Juni 2025 • 👁 5x Dilihat</div>
      <span class="badge">Baru</span>
    </div>

    <h2 class="section-title">Berita dan Peristiwa</h2>
    <div class="grid md:grid-cols-2 gap-6">
      <div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">Soroti Sejumlah Masalah, DPRD Surakarta Sampaikan Rekomendasi atas LKPJ Wali Kota TA. 2024</h3>
          <p class="text-sm text-gray-600">SURAKARTA - DPRD Kota Surakarta kembali menunjukkan peran strategisnya sebagai mitra kritis Pemerintah Daerah...</p>
          <div class="text-xs text-gray-500">📅 26 Mei 2025 • 👁 Dilihat</div>
          <span class="badge">Berita</span>
        </div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">Raperda Trantibum Digodok Lewat Public Hearing</h3>
          <p class="text-sm text-gray-600">SURAKARTA - Panitia Khusus Raperda tentang Penyelenggaraan Ketertiban Umum...</p>
          <div class="text-xs text-gray-500">📅 25 Mei 2025 • 👁 Dilihat</div>
          <span class="badge">Berita</span>
        </div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">DPRD dan Wali Kota Sepakati Ranwal RPJMD 2025</h3>
          <p class="text-sm text-gray-600">SURAKARTA - DPRD Kota Surakarta resmi menyepakati Rencana Awal (Ranwal) RPJMD...</p>
          <div class="text-xs text-gray-500">📅 22 April 2025 • 👁 Dilihat</div>
          <span class="badge">Berita</span>
        </div>
      </div>
      <div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">Perkuat Pengelolaan JDIH, Setwan Kota Surakarta Lakukan Studi Banding ke Setwan Kabupaten Batang</h3>
          <p class="text-sm text-gray-600">BATANG - Sekretariat DPRD Kota Surakarta melakukan kunjungan kerja ke Batang...</p>
          <div class="text-xs text-gray-500">📅 Oktober 2024 • 👁 Dilihat</div>
          <span class="badge">Kunjungan Kerja</span>
        </div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">DPRD Surakarta Lakukan Studi Tiru Pengelolaan JDIH</h3>
          <p class="text-sm text-gray-600">SEMARANG - Dalam rangka meningkatkan kualitas pengelolaan JDIH, DPRD Kota Surakarta melakukan studi tiru...</p>
          <div class="text-xs text-gray-500">📅 Oktober 2024 • 👁 Dilihat</div>
          <span class="badge">Kunjungan Kerja</span>
        </div>
        <div class="doc-entry">
          <h3 class="text-blue-700 font-semibold">Studi Komparasi ke Perpustakaan dan JDIH BPK RI</h3>
          <p class="text-sm text-gray-600">SURAKARTA - Studi komparasi ke JDIH dan perpustakaan BPK RI Kantor Perwakilan...</p>
          <div class="text-xs text-gray-500">📅 Mei 2024 • 👁 Dilihat</div>
          <span class="badge">Kunjungan Kerja</span>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-200 text-center text-sm text-gray-600 p-4 mt-6">
    &copy; 2025 - JDIH Kota Anda
  </footer>
</body>
</html>
