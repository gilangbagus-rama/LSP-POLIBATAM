<?php
// User akan dialihkan ke halaman ini jika tidak memenuhi persayaratan halaman
// Seperti harus login dulu

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

// Get aksi
$aksi = $_GET['aksi'];


if($aksi == "sudah_mengajukan") {


    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 

        swal ({
            title:  'Sudah Mengajukan',
            text:   'Selesaikan permohonan sertifikasi sebelumnya',
            icon:   'warning',
            showConfirmButton: false,
            timer: 2000,
                            
            });  
      },10); 
        
        window.setTimeout(function(){ 
        window.location.replace('index.php');
        } ,3000); 
    </script>
  ";


}

if($aksi == "belum_mengajukan") {


  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>

  <script type='text/javascript'>
    setTimeout(function () { 

      swal ({
          title:  'Belum Mengajukan',
          text:   'Ajukan permohonan sertifikasi terlebih dahulu',
          icon:   'warning',
          showConfirmButton: false,
          timer: 2000,
                          
          });  
    },10); 
      
      window.setTimeout(function(){ 
      window.location.replace('index.php');
      } ,3000); 
  </script>
";


}

if($aksi == "not_found") {

  session_start();

  $_SESSION = [];
  
  session_unset();

  session_destroy();

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 

        swal ({
            title:  'Tidak Terdaftar',
            text:   'Harap Registrasi terlebih dahulu',
            icon:   'error',
            showConfirmButton: false,
            timer: 2000,
                            
            });  
      },10); 
        
        window.setTimeout(function(){ 
        window.location.replace('index.php');
        } ,3000); 
    </script>
  ";


}

if($aksi == "not_login"){

  session_start();

  $_SESSION = [];
  
  session_unset();

  session_destroy();

  echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
       
        swal ({
            title:  'Belum Login',
            text:   'Harap Login terlebih dahulu',
            icon:   'error',
            showConfirmButton: false,
            timer: 2000,
                            
            });  
      },10); 
        
        window.setTimeout(function(){ 
        window.location.replace('index.php');
        } ,3000); 
    </script>
  ";

}


if($aksi == "not_verif"){

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    <script type='text/javascript'>
        setTimeout(function () { 
          swal ({
                title:    'Akun Belum Terverifikasi',
                text:     'Harap Verifikasi Akun terlebih dahulu',
                icon:     'warning',
                showConfirmButton: false,
                timer: 2000,
                          
                });  
        },10); 
        
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=dashboard_asesi');
          } ,3000); 
    </script>
  ";
}


if($aksi == "not_complete"){

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    <script type='text/javascript'>
        setTimeout(function () { 
          swal ({
                title:  'Data Diri Belum Lengkap',
                text:   'Harap Melengkapi Data Diri terlebih dahulu',
                icon:   'warning',
                showConfirmButton: false,
                timer: 2000,
                          
                });  
        },10); 
        
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=dashboard_asesi');
          } ,3000); 
    </script>
  ";

}

?>