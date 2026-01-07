<div class="navbar-header">
    <!-- Spasi untuk alignment -->
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <!-- Tombol toggle menu untuk mobile -->
    <button class="navbar-toggle" type="button" data-toggle="collapse" id="menu-toggler">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>

<ul class="nav navbar-nav pull-right hidden-xs">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown">
            <img class="img-circle" src="images/admin.png" alt="avatar" width="30" height="30">
            Selamat Datang ! 
            <b style='color:red'>
                <?php 
                // PERBAIKAN: Gunakan isset() dan htmlspecialchars()
                echo isset($_SESSION['name_lengkap']) ? htmlspecialchars($_SESSION['name_lengkap']) : 'User';
                ?>
            </b>
            <b class="caret"></b>
        </a>
        
        <ul class="dropdown-menu">
            <li><a style='width:260px' href="index.php?page=account">Atur User</a></li>
            <li class="divider"></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </li>
</ul>