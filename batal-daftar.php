<?php
include 'config.php';

$email = base64_decode($_GET['email']);
$token = $_GET['token'];



// Cek apakah akun masih ada atau tidak
$cek_email = $conn->query("SELECT `nama` FROM `akun` WHERE `email` = '$email';");

if ( mysqli_num_rows($cek_email) != 1 ) {

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal menghapus Akun',
          text:  'Tidak dapat menemukan akun !',
          icon: 'error',
          timer: 2000,
        });  
      },10); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php');
      } ,3000); 
    </script>";

exit;
}


// Cek apakah akun masih ada atau tidak
$cek_verif = $conn->query("SELECT `status_akun` FROM `akun` WHERE `email` = '$email'; ");
$data1 = mysqli_fetch_assoc($cek_verif);

if ( $data1['status_akun'] == 'Verif' ) {

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal menghapus Akun',
          text:  'Tidak dapat menghapus akun yang telah diverifikasi !',
          icon: 'error',
          timer: 2000,
        });  
      },10); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php');
      } ,3000); 
    </script>";

exit;
}




// Kalau akun udah di verifikasi
$cek_permohonan = $conn->query("SELECT `permohonan`.`id_asesi` FROM `permohonan`,`asesi`,`akun` WHERE 
`akun`.`email` = `asesi` .`email` AND `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `akun`.`email` = '$email'");

if ( mysqli_num_rows($cek_permohonan) > 0 ) {

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal menghapus Akun',
          text:  'Tidak dapat menghapus akun yang telah mengajukan permohonan sertifikasi !',
          icon: 'error',
          timer: 2000,
        });  
      },10); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php');
      } ,3000); 
    </script>";

exit;
}


$delete_akun = $conn->query("DELETE FROM `akun` WHERE `email` = '$email'");

if ( $delete_akun === TRUE ) {

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil menghapus Akun',
          text:  'Silahkan Tunggu',
          icon: 'success',
          timer: 2000,
        });  
      },10); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php');
      } ,3000); 
    </script>";

exit;
}



if ( $delete_akun === FALSE ) {

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal menghapus Akun',
          text:  'Unknown Error',
          icon: 'error',
          timer: 2000,
        });  
      },10); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php');
      } ,3000); 
    </script>";

exit;
}




?>

