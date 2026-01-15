<?php 
session_start();

// CEK APAKAH USER SUDAH LOGIN
if(!isset($_SESSION['login']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>JDIH DPRD Surakarta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
    
    <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="css/theme/default.css" />
    
    <!-- Libraries -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/dataTables.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/tables.css" />
    <link rel="stylesheet" type="text/css" href="css/elements/form.css" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
    }
    .dropdown-menu>li>a{
        color: var(--t)!important;
        padding: 10px 20px;
        transition: 0.3s;
    }
    .dropdown-menu>li>a:hover{
        background: var(--bg);
        color: var(--c)!important;
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

    #pad-wrapper{background:var(--w);border-radius:var(--r);padding:30px;box-shadow:var(--s)}

    .page-title{color:var(--d);font-weight:600;border-bottom:2px solid var(--b);padding-bottom:12px}

    .card{
    text-align:center;
    padding:24px;
    border-radius:var(--r);
    border:1px solid var(--b);
    transition:.3s;
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
    
    
    <!--[if lt IE 9]>
      <script src="js/html5.js"></script>
    <![endif]-->
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <i class="fa fa-balance-scale"></i> JDIH
                </a>
            </div>
            
            <!-- Include navbar content -->
            <?php include "header.php"; ?>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="content">
        <div id="pad-wrapper">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : '';
            
            $pageTitles = [
                'kepdprd' => 'Keputusan DPRD',
                'kepsekdprd' => 'Keputusan Sekretaris DPRD',
                'informasi' => 'Informasi',
                'tambahprokum' => 'Tambah Produk Hukum',
                'user' => 'Manajemen User',
                'account' => 'Pengaturan Akun',
                'ruang' => 'Manajemen Ruangan',
                'notulen' => 'Notulen Rapat'
            ];
            
            $currentTitle = isset($pageTitles[$page]) ? $pageTitles[$page] : 'Dashboard';
            $icon = $page ? 'file-text' : 'dashboard';
            
            echo '<h2 class="page-title"><i class="fa fa-'.$icon.'"></i> '.$currentTitle.'</h2>';
            
            switch($page) {
                case 'kepdprd':
                    include "kepdprd.php";
                    break;
                case 'keppimdprd':
                    include "keppemimpin.php";
                    break;
                case 'kepsekdprd':
                    include "kepsekdprd.php";
                    break;
                case 'informasi':
                    include "informasi.php";
                    break;
                case 'tambahprokum':
                    include "a_tambah_prokum.php";
                    break; 
                case 'user':
                    include "user.php";
                    break;
                case 'account':
                    include "account.php";
                    break;
                case 'ruang':
                    include "ruang.php";
                    break;
                case 'notulen':
                    include "notulen.php";
                    break;
                case 'perdprd':
                    include "perdprd.php";
                    break;
                default:
                    include "dashboard.php";
                    break;
            }
            ?>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi Hapus</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnYes">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/theme.js"></script>

    <!-- Table Scripts -->
    <script src="js/tables/jquery.dataTables.js"></script>
    <script src="js/tables/dataTables.bootstrap.js"></script>

    <!-- Custom Script -->
    <script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        $('.dataTables-example').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
        
        // Smooth hover effects
        $('.card, .btn').hover(
            function() { $(this).addClass('shadow-sm'); },
            function() { $(this).removeClass('shadow-sm'); }
        );
        
        // Form validation
        $('form').submit(function(e) {
            var required = $(this).find('[required]');
            var valid = true;
            
            required.each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                return false;
            }
        });
    });
    </script>

</body>
</html>