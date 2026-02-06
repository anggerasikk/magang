<?php 
$server = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "jdihdprd"; 

($GLOBALS["___mysqli_ston"] = mysqli_connect($server, $username, $password)) or die ("Gagal"); 
mysqli_select_db($GLOBALS["___mysqli_ston"], $database) or die ("Database tidak ditemukan"); 

    function tgl_indo($tgl){ 
            $tanggal = substr($tgl,8,2); 
            $bulan = getBulan(substr($tgl,5,2)); 
            $tahun = substr($tgl,0,4); 
            return $tanggal.' '.$bulan.' '.$tahun;          
    }     

    function getBulan($bln){ 
                switch ($bln){ 
                    case 1:  
                        return "Jan"; 
                        break; 
                    case 2: 
                        return "Feb"; 
                        break; 
                    case 3: 
                        return "Mar"; 
                        break; 
                    case 4: 
                        return "Apr"; 
                        break; 
                    case 5: 
                        return "Mei"; 
                        break; 
                    case 6: 
                        return "Jun"; 
                        break; 
                    case 7: 
                        return "Jul"; 
                        break; 
                    case 8: 
                        return "Agu"; 
                        break; 
                    case 9: 
                        return "Sep"; 
                        break; 
                    case 10: 
                        return "Okt"; 
                        break; 
                    case 11: 
                        return "Nov"; 
                        break; 
                    case 12: 
                        return "Des"; 
                        break; 
                } 
            }  
?> 