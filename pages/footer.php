<?php
// footer.php
?>
    <!-- FOOTER -->
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
                            <a href="?page=kontak" class="site-footer-link">Kontak</a>
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
                            <a href="?page=kontak" class="site-footer-link">Kontak</a>
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
                        jdih@dprd-surakarta.go.id
                    </p>
                    <p class="text-white mb-4">
                        <i class="bi bi-telephone me-2"></i>
                        (0271) 123456
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
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.querySelector('.search-btn');
            const searchInput = document.querySelector('.search-input');

            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function() {
                    performSearch();
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    }
                });

                function performSearch() {
                    const query = searchInput.value.trim();
                    if (query) {
                        // Redirect to search results page with query parameter
                        window.location.href = '?page=search&q=' + encodeURIComponent(query);
                    } else {
                        alert('Silakan masukkan kata kunci pencarian');
                    }
                }
            }
        });
    </script>
</body>
</html>