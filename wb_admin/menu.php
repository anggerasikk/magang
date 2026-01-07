<style>
      .button {
        background-color: #1c87c9;
        -webkit-border-radius: 60px;
        border-radius: 60px;
        border: none;
        color: #eeeeee;
        cursor: pointer;
        display: inline-block;
        font-family: sans-serif;
        font-size: 20px;
        padding: 5px 15px;
        text-align: center;
        text-decoration: none;
      }
      @keyframes glowing {
        0% {
          background-color: #2ba805;
          box-shadow: 0 0 5px #2ba805;
        }
        50% {
          background-color: #49e819;
          box-shadow: 0 0 20px #49e819;
        }
        100% {
          background-color: #7CFC00;
          box-shadow: 0 0 5px #2ba805;
        }
      }
      .button {
        animation: glowing 1300ms infinite;
      }
	  
	  .button2 {
        background-color: #F08080;
        -webkit-border-radius: 60px;
        border-radius: 60px;
        border: none;
        color: #eeeeee;
        cursor: pointer;
        display: inline-block;
        font-family: sans-serif;
        font-size: 20px;
        padding: 5px 15px;
        text-align: center;
        text-decoration: none;
      }
      
	  
	  
    </style>

<ul id="dashboard-menu">
             
              <li class="active">
                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>
                <a href="index.php">
                    <i class="fa fa-laptop"></i>
                    <span class="button2" ><font size="2">Dashboard</font></span>
                </a>
            </li> 		
			
			
			<li>
                <a class="dropdown-toggle" href="#">              
                    <i class="fa fa-table"></i>
                    <span>Produk_Hukum</span> <b class="caret"></b>
                </a>
                 <ul class="submenu">
                    <li><a href="index.php?page=kepdprd">Keputusan_DPRD</a></li>
                    <li><a href="index.php?page=kepsekdprd">Keputusan_Sekretaris_DPRD</a></li>
                </ul>
            </li>  

            <li>
                <a class="dropdown-toggle" href="#">              
                    <i class="fa fa-table"></i>
                    <span>Informasi</span> <b class="caret"></b>
                </a>
                 <ul class="submenu">
                    <li><a href="informasi">Informasi</a></li>
                </ul>
            </li>  			
			
           <li>
                <a class="dropdown-toggle" href="#">              
                    <i class="fa fa-table"></i>
                    <span>Monografi</span> <b class="caret"></b>
                </a>
                 <ul class="submenu">
                    <li><a href="index.php?page=kepdprd">Keputusan_DPRD</a></li>
                    <li><a href="index.php?page=kepsekdprd">Keputusan_Sekretaris_DPRD</a></li>
                </ul>
            </li>  
			
			
			
			

            <li>
                <a class="dropdown-toggle" href="#">              
                    <i class="fa fa-table"></i>
                    <span>Data User</span> <b class="caret"></b>
                </a>
                 <ul class="submenu">
                    <li><a href="index.php?page=user&stat=2">Data User Input</a></li>
                    <li><a href="index.php?page=user&stat=3">Data User Admin</a></li>
                </ul>
            </li>   

                
         
        </ul> 