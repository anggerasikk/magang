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
    <title>Dashboard - JDIH DPRD</title>
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
    <link rel="stylesheet" type="text/css" href="css/elements/bootstrap-wysihtml5.css" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shim for IE8 -->
    <!--[if lt IE 9]>
      <script src="js/html5.js"></script>
    <![endif]-->
</head>
<body>

    <!-- Navbar -->
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
        <?php include "header.php"; ?>
    </header>
    <!-- End Navbar -->

    <!-- Sidebar -->
    <div id="sidebar-nav">
        <div class="profile-hidder">
            <ul class="nav">
                <li class="nav-profile">
                    <div class="image">
                        <a href="javascript:void(0);"><img src="images/caption.png" alt="Profile"></a>
                    </div>
                    <div class="info">
                        <a href="javascript:void(0);"><?php echo htmlspecialchars($_SESSION['name_lengkap'] ?? 'User'); ?> <b class="caret"></b></a>
                    </div>
                </li>
            </ul>
        </div>

        <?php 
        // TAMPIL MENU BERDASARKAN LEVEL USER
        if ($_SESSION['level'] == 'user_admin'){
            include "menu.php"; 
        } else {
            include "menu_user.php";
        }
        ?>
    </div>
    <!-- End Sidebar -->

    <!-- Main Container -->
    <div class="content">
        <div id="pad-wrapper">
            <?php
            // SISTEM ROUTING UNTUK MENAMPILKAN HALAMAN BERDASARKAN PARAMETER GET
            $page = isset($_GET['page']) ? $_GET['page'] : '';
            
            switch($page) {
                case 'kepdprd':
                    include "kepdprd.php";
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
                default:
                    include "dashboard.php";
                    break;
            }
            ?>
        </div><!-- /pad-wrapper -->
    </div><!-- content --> 
    <!-- /Main Container -->

    <!-- Modal Konfirmasi Hapus -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4><strong>Konfirmasi Hapus</strong></h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button data-dismiss="modal" class="btn btn-danger btn-sm" id="btnYes">Konfirmasi</button> 
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/theme.js"></script>

    <!-- Dashboard Scripts -->
    <script src="js/dashboard/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="js/dashboard/jquery-jvectormap-world-mill-en.js"></script>
    <script src="js/dashboard/jquery.sparkline.min.js"></script>
    <script src="js/dashboard/waypoints.min.js"></script>
    <script src="js/dashboard/jquery.counterup.min.js"></script>
    <script src="js/bootstrap-modal.js"></script>

    <!-- Chart Scripts -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>

    <!-- Table Scripts -->
    <script src="js/tables/jquery.jeditable.js"></script>
    <script src="js/tables/jquery.dataTables.js"></script>
    <script src="js/tables/dataTables.bootstrap.js"></script>
    <script src="js/tables/jquery.peity.min.js"></script>
    <script src="js/tables/peity-demo.js"></script>
    <script src="js/tables/icheck.min.js"></script>

    <!-- Form Scripts -->
    <script src="js/form/app.js"></script>
    <script src="js/form/app.plugin.js"></script>
    <script src="js/form/datepicker/bootstrap-datepicker.js"></script>
    <script src="js/form/slider/bootstrap-slider.js"></script>
    <script src="js/form/bootstrap.file-input.js"></script>    
    <script src="js/form/combodate/moment.min.js"></script>
    <script src="js/form/combodate/combodate1.js"></script>
    <script src="js/form/parsley/parsley.min.js"></script>

    <!-- WYSIWYG Editor -->
    <script src="js/editor/wysihtml5-0.3.0.js"></script>
    <script src="js/editor/bootstrap3-wysihtml5.js"></script>

    <!-- Custom Scripts -->
    <script type="text/javascript">
        // Inisialisasi WYSIWYG Editor
        $('.textarea').wysihtml5({
            "font-styles": true,
            "emphasis": true,
            "lists": true,
            "html": true,
            "link": true,
            "image": true,
            "color": false,
            "size": 'sm'
        });

        // Inisialisasi iCheck
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });

        // Inisialisasi DataTables
        $(document).ready(function() {
            $('.dataTables-example').dataTable();
        });
    </script>

</body>
</html>