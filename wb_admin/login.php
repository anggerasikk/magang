<!DOCTYPE html>
<html>
<head>
  <title>Login - Administrator JDIH</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    .button {
      background-color: #7FFFD4;
      -webkit-border-radius: 60px;
      border-radius: 60px;
      border: none;
      color: #7FFFD4;
      cursor: pointer;
      display: inline-block;
      font-family: sans-serif;
      font-size: 20px;
      padding: 5px 15px;
      text-align: center;
      text-decoration: none;
    }
    @keyframes glowing {
      0% { background-color: #7FFFD4; box-shadow: 0 0 5px #2ba805; }
      50% { background-color: #7FFFD4; box-shadow: 0 0 20px #49e819; }
      100% { background-color: #7FFFD4; box-shadow: 0 0 5px #2ba805; }
    }
    .button { animation: glowing 1300ms infinite; }
  </style>
</head>
<body>
<?php
session_start();

// KONEKSI DATABASE LANGSUNG
$host = "localhost";
$username = "root";
$password = "";
$database = "jdihdprd";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if(isset($_POST['login'])){
    // Gunakan isset() untuk mencegah undefined index
    $input_user = isset($_POST['user']) ? $_POST['user'] : '';
    $input_pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    
    $user = mysqli_real_escape_string($koneksi, $input_user);
    $pass = md5($input_pass);
    
    $query = "SELECT * FROM phpmu_user 
              WHERE username='$user' 
              AND password='$pass' 
              AND status='Y'";
    
    $login = mysqli_query($koneksi, $query);
    
    if($login === false) {
        // Jika query error
        echo "<script>alert('Error query: " . mysqli_error($koneksi) . "');</script>";
    } else {
        $cook = mysqli_num_rows($login);
        
        if($cook > 0){
            $r = mysqli_fetch_assoc($login);
            
            // Cek dulu apakah kolom ada sebelum mengaksesnya
            $_SESSION['login'] = isset($r['id_user']) ? $r['id_user'] : '';
            $_SESSION['username'] = isset($r['username']) ? $r['username'] : '';
            
            // Coba beberapa kemungkinan nama kolom
            if(isset($r['name_lengkap'])) {
                $_SESSION['name_lengkap'] = $r['name_lengkap'];
            } elseif(isset($r['nama_lengkap'])) {
                $_SESSION['name_lengkap'] = $r['nama_lengkap'];
            } elseif(isset($r['name_ipewkapi'])) {
                $_SESSION['name_lengkap'] = $r['name_ipewkapi'];
            } else {
                $_SESSION['name_lengkap'] = $r['username'];
            }
            
            $_SESSION['level'] = isset($r['level']) ? $r['level'] : '';
            
            header('Location: index.php');
            exit();
        } else {
            echo "<script>
                    alert('Maaf, username atau password salah!');
                    window.location.href='login.php';
                  </script>";
            exit();
        }
    }
}
?>

<div class="kotak_login">
  <fieldset bgcolor="blue">
    <p><font color="black" size="4">Silahkan Login Melalui Form Dibawah ini.</font></p>
    <form class="m-t" role="form" action="" method='POST'>
      <div class="form-group">
        <label>Username</label>
        <input type="text" class="form_login" placeholder="Username" required name='user'>
        <label>Password</label>
        <input type="password" class="form_login" placeholder="Password" required name='pass'>
      </div>
      <button name='login' type="submit" class="tombol_login">Masuk</button>
    </form>
  </fieldset>
  <p class="m-t"> <small>&copy; JDIH DPRD</small> </p>
</div>

<!-- scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/theme.js"></script>

</body>
</html>