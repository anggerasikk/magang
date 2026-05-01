<style>
.bs-glyphicons {
  margin: 13px 20px 20px 0px;
  overflow: hidden;
}

.bs-glyphicons-list {
  padding-left: 0;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  margin: 0 -10px;
}

.bs-glyphicons-list a {
  color: #8a8a8a !important;
  text-decoration: none;
  display: block;
  flex: 0 0 auto;
  padding: 0 10px;
  box-sizing: border-box;
}

ol, ul {
  margin-top: 0;
  margin-bottom: 10px;
}

.bs-glyphicons li {
  padding: 20px 15px;
  font-size: 10px;
  line-height: 1.4;
  text-align: center;
  border: 1px solid #cecece;
  background-color: #ffffff;
  transition: all 0.3s ease;
  list-style: none;
  height: auto;
  min-height: 195px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.bs-glyphicons li:hover {
  background-color: #e3e3e3;
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.bs-glyphicons .glyphicon {
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 74px;
}

.glyphicon {
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.bs-glyphicons .glyphicon-class {
  display: block;
  text-align: center;
  word-wrap: break-word;
  margin-top: 10px;
  font-size: 14px;
  font-weight: 500;
}

.bs-glyphicons img {
  max-width: 80px;
  height: auto;
  transition: transform 0.3s ease;
}

.bs-glyphicons li:hover img {
  transform: scale(1.05);
}

/* Responsive Styles */
@media (min-width: 1200px) {
  .bs-glyphicons-list a {
    width: 20%; /* 5 items per row */
  }
}

@media (min-width: 992px) and (max-width: 1199px) {
  .bs-glyphicons-list a {
    width: 25%; /* 4 items per row */
  }
  
  .bs-glyphicons li {
    padding: 20px 10px;
    min-height: 180px;
  }
  
  .bs-glyphicons img {
    max-width: 70px;
  }
}

@media (min-width: 768px) and (max-width: 991px) {
  .bs-glyphicons-list a {
    width: 33.333%; /* 3 items per row */
  }
  
  .bs-glyphicons li {
    padding: 15px 10px;
    min-height: 170px;
  }
  
  .bs-glyphicons img {
    max-width: 65px;
  }
  
  .bs-glyphicons .glyphicon-class {
    font-size: 13px;
  }
}

@media (max-width: 767px) {
  .bs-glyphicons {
    margin: 13px 10px 20px 10px;
  }
  
  .bs-glyphicons-list {
    margin: 0 -5px;
  }
  
  .bs-glyphicons-list a {
    width: 50%; /* 2 items per row */
    padding: 0 5px;
  }
  
  .bs-glyphicons li {
    padding: 15px 8px;
    min-height: 150px;
  }
  
  .bs-glyphicons img {
    max-width: 55px;
  }
  
  .bs-glyphicons .glyphicon {
    font-size: 48px;
  }
  
  .bs-glyphicons .glyphicon-class {
    font-size: 12px;
    margin-top: 8px;
  }
}

@media (max-width: 480px) {
  .bs-glyphicons-list a {
    width: 100%; /* 1 item per row */
  }
  
  .bs-glyphicons li {
    padding: 12px 10px;
    min-height: auto;
    flex-direction: row;
    justify-content: flex-start;
    text-align: left;
    gap: 15px;
  }
  
  .bs-glyphicons li img,
  .bs-glyphicons li .glyphicon {
    width: 50px;
    height: 50px;
    margin: 0;
  }
  
  .bs-glyphicons .glyphicon-class {
    margin: 0;
    font-size: 14px;
    flex: 1;
  }
  
  .bs-glyphicons img {
    max-width: 50px;
  }
}

/* Tablet Portrait */
@media (min-width: 768px) and (max-width: 991px) and (orientation: portrait) {
  .bs-glyphicons-list a {
    width: 50%;
  }
}

/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
  .bs-glyphicons li {
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
  }
  
  .bs-glyphicons li:active {
    background-color: #d4d4d4;
    transform: scale(0.98);
  }
}

/* High DPI Screens */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .bs-glyphicons img {
    image-rendering: -webkit-optimize-contrast;
  }
}

/* Loading Animation */
.bs-glyphicons li {
  opacity: 0;
  animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Stagger animation for each item */
.bs-glyphicons-list a:nth-child(1) li { animation-delay: 0.1s; }
.bs-glyphicons-list a:nth-child(2) li { animation-delay: 0.2s; }
.bs-glyphicons-list a:nth-child(3) li { animation-delay: 0.3s; }
.bs-glyphicons-list a:nth-child(4) li { animation-delay: 0.4s; }
.bs-glyphicons-list a:nth-child(5) li { animation-delay: 0.5s; }
.bs-glyphicons-list a:nth-child(6) li { animation-delay: 0.6s; }

/* Card Style Enhancement */
.bs-glyphicons li {
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.bs-glyphicons li:hover {
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  border-color: #0ea5a4;
}

/* Active State */
.bs-glyphicons li:active {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}
</style>

<div class="bs-docs-section">
  <div style="padding: 10px 20px; background: linear-gradient(135deg, #0ea5a4 0%, #0f766e 100%); border-radius: 8px; margin-bottom: 20px;">
    <font color="white" style="font-size: 16px; font-weight: 500;">Silahkan Mengelola Semua Data yang ada Melalui menu-menu yang telah tersedia di bawah ini :</font>
  </div>
  
  <div style='margin-left:0px' class="bs-glyphicons">
    <ul class="bs-glyphicons-list">
      <?php 
        if ($_SESSION['level']=='user_admin'){
        ?>
        <a href='index.php'>
          <li>
            <span class="" aria-hidden="true"></span>
            <img src="images/home.png" width="120" height="120" alt="Dashboard">
            <span class="glyphicon-class">Dashboard</span>
          </li>
        </a>
      
        <a href='index.php?page=notulen'>
          <li>
            <span class="" aria-hidden="true"></span>
            <img src="images/notulen.png" width="120" height="120" alt="Produk Hukum">
            <span class="glyphicon-class">Produk Hukum</span>
          </li>
        </a>
      
        <a href='index.php?page=user&stat=2'>
          <li>
            <span class="" aria-hidden="true"></span>
            <img src="images/user.png" width="120" height="120" alt="Data User Input">
            <span class="glyphicon-class">Data User Input</span>
          </li>
        </a>
      
        <a href='index.php?page=user&stat=3'>
          <li>
            <span class="" aria-hidden="true"></span>
            <img src="images/admin.png" width="120" height="120" alt="Data User Admin">
            <span class="glyphicon-class">Data User Admin</span>
          </li>
        </a>
     
        <a href='logout.php'>
          <li>
            <span class="" aria-hidden="true"></span>
            <img src="images/keluar.png" width="120" height="120" alt="Logout">
            <span class="glyphicon-class">Logout</span>
          </li>
        </a>
		
        <?php
        } else {
        ?>
        <a href='index.php'>
          <li>
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            <span class="glyphicon-class">Dashboard</span>
          </li>
        </a>
      
        <a href='notulen.php'>
          <li>
            <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
            <span class="glyphicon-class">Notulen Rapat</span>
          </li>
        </a>
      
        <a href='logout.php'>
          <li>
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            <span class="glyphicon-class">Logout</span>
          </li>
        </a>
        <?php
        }
        ?>
    </ul>
  </div>
</div>

<!-- Optional: Add JavaScript for touch devices -->
<script>
// Add touch feedback for mobile devices
document.querySelectorAll('.bs-glyphicons-list a').forEach(function(link) {
  link.addEventListener('touchstart', function() {
    this.querySelector('li').style.transform = 'scale(0.98)';
  });
  
  link.addEventListener('touchend', function() {
    this.querySelector('li').style.transform = '';
  });
  
  link.addEventListener('touchcancel', function() {
    this.querySelector('li').style.transform = '';
  });
});

// Optional: Add loading state
window.addEventListener('load', function() {
  // Remove animation classes after load for better performance
  setTimeout(function() {
    document.querySelectorAll('.bs-glyphicons li').forEach(function(li) {
      li.style.animation = '';
    });
  }, 1000);
});
</script>