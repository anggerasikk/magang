
<style>
.bs-glyphicons {
  margin: 13px 20px 20px 0px;
  overflow: hidden;
}
.bs-glyphicons-list {
  padding-left: 0;
  list-style: none;
}

.bs-glyphicons-list a {
  color:#8a8a8a !important;
}
ol, ul {
  margin-top: 0;
  margin-bottom: 10px;
}
.bs-glyphicons li {
  float: left;
  width: 25%;
  height: 195px;
  padding: 30px;
  font-size: 10px;
  line-height: 1.4;
  text-align: center;
  border: 1px solid #cecece;
  background-color: #ffffff;
}
.bs-glyphicons li:hover {
  background-color: #e3e3e3;
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
}
</style>
<div class="bs-docs-section">
 
  
                            <font color="white">  Silahkan Mengelola Semua Data yang ada Melalui menu-menu yang telah tersedia di bawah ini :</font> <br>
<div style='margin-left:20px' class="bs-glyphicons">
    <ul class="bs-glyphicons-list">
      <?php 
        if ($_SESSION['level']=='user_admin'){
             
        ?>
        <a href='index.php'><li>
          <span class="" aria-hidden="true"></span>
		  <img src="images/home.png" width="120" height="120"></img>
          <span class="glyphicon-class">Dashboard</span>
        </li></a>
      
      
        <a href='notulen'><li>
          <span class="" aria-hidden="true"></span>
		   <img src="images/notulen.png" width="120" height="120"></img>
          <span class="glyphicon-class">Produk Hukum</span>
        </li></a>
      
        <a href='index.php?page=user&stat=2'><li>
          <span class="" aria-hidden="true"></span>
		  <img src="images/user.png" width="120" height="120"></img>
          <span class="glyphicon-class">Data User Input</span>
        </li></a>
      
        <a href='index.php?page=user&stat=3'><li>
          <span class="" aria-hidden="true"></span>
		   <img src="images/admin.png" width="120" height="120"></img>
          <span class="glyphicon-class">Data User Admin</span>
        </li></a>
     
		 
        <a href='logout.php'><li>
          <span class="" aria-hidden="true"></span>
		   <img src="images/keluar.png" width="120" height="120"></img>
          <span class="glyphicon-class">Logout</span>
        </li></a>
		
		
		
		
        <?php
        }else{
        ?>
        <a href='index.php'><li>
          <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
          <span class="glyphicon-class">Dashboard</span>
        </li></a>
      
        <a href='notulen'><li>
          <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
          <span class="glyphicon-class">Notulen Rapat</span>
        </li></a>
      
      
        <a href='logout.php'><li>
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          <span class="glyphicon-class">Logout</span>
        </li></a>
        <?php
        }
        ?>
    </ul>
  </div>
</div> 