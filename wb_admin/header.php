<!-- HEADER NAVBAR -->
<div class="navbar-header">

    <!-- Tombol toggle menu untuk mobile -->
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#main-navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>

<!-- NAVIGASI UTAMA -->
<div class="collapse navbar-collapse" id="main-navbar">
    <ul class="nav navbar-nav">
        <!-- Dashboard -->
        <li class="active">
            <a href="index.php">
                <i class="fa fa-laptop"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <!-- Perundang-undangan Dropdown -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gavel"></i>
                <span>Perundang-undangan</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu multi-column">
                <div class="row">
                    <div class="col-sm-6">
                        <ul class="multi-column-dropdown">
                            <li><a href="index.php?page=kepdprd"><i class="fa fa-file-text"></i> Keputusan DPRD</a></li>
                            <li><a href="index.php?page=kepsekdprd"><i class="fa fa-file-text"></i> Keputusan Sekretaris DPRD</a></li>
                            <li><a href="index.php?page=keppemimpin"><i class="fa fa-file-text"></i> Keputusan Pimpinan DPRD</a></li>
                            <li><a href="index.php?page=perdprd"><i class="fa fa-balance-scale"></i> Peraturan DPRD</a></li>
                            <li><a href="index.php?page=perda"><i class="fa fa-university"></i> Peraturan Daerah</a></li>
                            <li><a href="index.php?page=englishj"><i class="fa fa-language"></i> English Legal Products</a></li>
                        </ul>
                    </div>
                </div>
            </ul>
        </li>
        
        <!-- Monografi Hukum Dropdown -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-book"></i>
                <span>Monografi Hukum</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu multi-column">
                <div class="row">
                    <div class="col-sm-6">
                        <ul class="multi-column-dropdown">
                            <li><a href="index.php?page=pokok-pikiran-dprd"><i class="fa fa-lightbulb-o"></i> Pokok Pikiran DPRD</a></li>
                            <li><a href="index.php?page=kajian-raperda"><i class="fa fa-search"></i> Kajian Raperda</a></li>
                            <li><a href="index.php?page=naskah-akademik"><i class="fa fa-graduation-cap"></i> Naskah Akademik</a></li>
                            <li><a href="index.php?page=dokumen-langka"><i class="fa fa-archive"></i> Dokumen Langka</a></li>
                            <li><a href="index.php?page=risalah"><i class="fa fa-comments"></i> Risalah</a></li>
                            <li><a href="index.php?page=hasil-fasilitasi-raperda"><i class="fa fa-handshake-o"></i> Hasil Fasilitasi Raperda</a></li>
                            <li><a href="index.php?page=hasil-harmonisasi-raperda"><i class="fa fa-refresh"></i> Hasil Harmonisasi Raperda</a></li>
                            <li><a href="index.php?page=penulisan-karya-ilmiah"><i class="fa fa-pencil"></i> Penulisan Karya Ilmiah</a></li>
                            <li><a href="index.php?page=himpunan-peraturan"><i class="fa fa-folder-open"></i> Himpunan Peraturan</a></li>
                        </ul>
                    </div>
                </div>
            </ul>
        </li>
        
        <!-- Artikel Hukum -->
        <li>
            <a href="index.php?page=artikel-hukum">
                <i class="fa fa-newspaper-o"></i>
                <span>Artikel Hukum</span>
            </a>
        </li>
        
        <!-- Informasi Dropdown -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-info-circle"></i>
                <span>Informasi</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="index.php?page=kegiatan-jdih"><i class="fa fa-calendar"></i> Kegiatan JDIH</a></li>
                <li><a href="index.php?page=infografis-videografis"><i class="fa fa-bar-chart"></i> Infografis & Videografis</a></li>
                <li><a href="index.php?page=galeri-foto"><i class="fa fa-camera"></i> Galeri Foto</a></li>
                <li><a href="index.php?page=penghargaan"><i class="fa fa-trophy"></i> Penghargaan</a></li>
                <li><a href="index.php?page=koleksi-buku-perpustakaan"><i class="fa fa-book"></i> Koleksi Buku Perpustakaan</a></li>
            </ul>
        </li>
        
        <!-- Yurisprudensi -->
        <li>
            <a href="index.php?page=yurisprudensi">
                <i class="fa fa-balance-scale"></i>
                <span>Yurisprudensi</span>
            </a>
        </li>
        
        <!-- Kontak Kami -->
        <li>
            <a href="index.php?page=kontak-kami">
                <i class="fa fa-envelope"></i>
                <span>Kontak Kami</span>
            </a>
        </li>
        
        <!-- Data User Dropdown (Admin Only) -->
        <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin'): ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-users"></i>
                <span>Data User</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="index.php?page=user&stat=2"><i class="fa fa-user"></i> Data User Input</a></li>
                <li><a href="index.php?page=user&stat=3"><i class="fa fa-user-secret"></i> Data User Admin</a></li>
                <li><a href="index.php?page=account"><i class="fa fa-cog"></i> Atur User</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
    
    <!-- USER MENU di kanan -->
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img class="img-circle" src="images/admin.png" alt="avatar" width="30" height="30">
                Selamat Datang ! 
                <b style='color:red'>
                    <?php 
                    echo isset($_SESSION['name_lengkap']) ? htmlspecialchars($_SESSION['name_lengkap']) : 'User';
                    ?>
                </b>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="index.php?page=account"><i class="fa fa-cog"></i> Pengaturan Akun</a></li>
                <li class="divider"></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Keluar</a></li>
            </ul>
        </li>
    </ul>
</div>

<style>
    :root{
    --c:#0ea5a4;
    --d:#0f766e;
    --bg:#f0fdfa;
    --w:#fff;
    --t:#134e4a;
    --b:#d1fae5;
    --r:14px;
    --s:0 8px 24px rgba(0,0,0,.08);
    }

    *{font-family:Inter,sans-serif}
    body{background:var(--bg);color:var(--t);padding-top:70px}

    /* NAVBAR BARU - BUKAN PUTIH LAGI */
    .navbar{
        background: linear-gradient(135deg, var(--d) 0%, var(--c) 100%)!important;
        box-shadow: var(--s);
        border: none;
        margin-bottom: 15px;
    }
    
    .navbar-brand{
        color: var(--w)!important;
        font-weight: 700;
        font-size: 1.4em;
    }
    .navbar-brand i{
        margin-right: 8px;
    }

    .navbar-nav>li>a{
        color: rgba(255,255,255,0.9)!important;
        border-radius: 20px;
        margin: 0 2px;
        padding: 10px 18px!important;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    /* HAPUS SEMUA STYLE KHUSUS UNTUK .button DI DASHBOARD */
    .navbar-nav>li>a span {
        background-color: transparent !important;
        border-radius: 0 !important;
        border: none !important;
        color: inherit !important;
        cursor: pointer;
        display: inline !important;
        font-family: inherit !important;
        padding: 0 !important;
        text-align: left !important;
        text-decoration: none !important;
        animation: none !important;
        font-size: inherit !important;
        margin: 0 !important;
        font-weight: inherit !important;
    }
    
    .navbar-nav>li>a:hover,
    .navbar-nav>li>a:focus,
    .navbar-nav>.active>a,
    .navbar-nav>.active>a:hover,
    .navbar-nav>.active>a:focus,
    .navbar-nav>.open>a,
    .navbar-nav>.open>a:hover,
    .navbar-nav>.open>a:focus{
        background: rgba(255,255,255,0.15)!important;
        color: #fff!important;
        backdrop-filter: blur(10px);
    }

    /* Dropdown menu styling */
    .dropdown-menu{
        background: var(--w);
        border: none;
        border-radius: var(--r);
        box-shadow: var(--s);
        margin-top: 5px;
        padding: 10px 0;
    }
    .dropdown-menu>li>a{
        color: var(--t)!important;
        padding: 8px 20px;
        transition: 0.3s;
    }
    .dropdown-menu>li>a:hover{
        background: var(--bg);
        color: var(--c)!important;
        padding-left: 25px;
    }
    
    /* Multi-column dropdown */
    .multi-column-dropdown {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .multi-column-dropdown li a {
        display: block;
        padding: 8px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
    }
    
    .multi-column-dropdown li a i {
        margin-right: 8px;
        width: 20px;
        text-align: center;
    }

    /* Navbar toggle button for mobile */
    .navbar-toggle{
        border-color: rgba(255,255,255,0.5);
    }
    .navbar-toggle .icon-bar{
        background-color: #fff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        body{padding-top:50px}
        
        .navbar-nav{
            margin: 0 -15px;
        }
        .navbar-nav>li>a{
            border-radius: 0;
            margin: 0;
            padding: 12px 20px!important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .dropdown-menu{
            position: static;
            float: none;
            width: auto;
            margin-top: 0;
            background: rgba(15, 118, 110, 0.95);
            box-shadow: none;
            border-radius: 0;
            border: none;
        }
        .dropdown-menu>li>a{
            color: rgba(255,255,255,0.9)!important;
            padding-left: 40px;
        }
        .dropdown-menu>li>a:hover{
            background: rgba(255,255,255,0.1)!important;
            color: #fff!important;
        }
    }

    #pad-wrapper{
        background:var(--w);
        border-radius:var(--r);
        padding:25px;
        box-shadow:var(--s);
        margin-top: 20px;
    }

    .page-title{
        color:var(--d);
        font-weight:600;
        border-bottom:2px solid var(--b);
        padding-bottom:12px;
        margin-top: 0;
        margin-bottom: 25px;
    }

    .card{
    text-align:center;
    padding:24px;
    border-radius:var(--r);
    border:1px solid var(--b);
    transition:.3s;
    margin-bottom: 20px;
    }
    .card:hover{transform:translateY(-5px);box-shadow:var(--s)}

    .card img,.card i{
    width:56px;height:56px;
    border-radius:50%;
    background:rgba(14,165,164,.12);
    padding:14px;color:var(--c);
    }

    .table thead{background:var(--c);color:#fff}
    .table tbody tr:hover{background:#ecfeff}

    .btn-primary{background:var(--c);border:none;border-radius:20px}
    .btn-primary:hover{background:var(--d)}

    /* FIX SIDEBAR & BACKGROUND */
    .content,
    .wrapper,
    .main-content{
    margin-left:0!important;
    padding-left:0!important;
    }

    .sidebar,
    .left-side{
    display:none!important;
    }

    body,
    .content,
    #pad-wrapper{
    background-image:none!important;
    }

</style>
<!-- JAVASCRIPT untuk navbar -->
<script>
    $(document).ready(function() {
        // Add active class based on current page
        var currentPage = window.location.href;
        $('.navbar-nav li a').each(function() {
            var href = $(this).attr('href');
            if (href && href !== '#' && currentPage.indexOf(href.split('?')[0]) > -1) {
                $(this).parents('li').addClass('active');
            }
        });
        
        // Handle dropdown toggle
        $('.dropdown-toggle').dropdown();
        
        // Mobile menu close on click
        $(document).on('click', '.navbar-collapse.in', function(e) {
            if ($(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle') {
                $(this).collapse('hide');
            }
        });
        
        // Highlight parent dropdown when child is active
        $('.dropdown-menu li.active').parents('.dropdown').addClass('active');
    });
</script>