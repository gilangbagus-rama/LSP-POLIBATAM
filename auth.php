<?php
include 'config.php';


if ( !isset($_GET['email']) ) {
    header("location: 404.php");
exit;
}

$email_daftar = base64_decode($_GET['email']);

$cek_akun = $conn->query("SELECT * FROM `akun` WHERE `email` = '$email_daftar'");
$data11 = mysqli_fetch_assoc($cek_akun);

if ( mysqli_num_rows($cek_akun) != 1 ){

  echo "
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal Verifikasi',
          text:  'Tidak dapat menemukan akun',
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


if ( $data11['status_akun'] == 'Verif' ) {


    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Telah diverifikasi',
            text:  'Akun telah diverifikasi',
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


$token_daftar = $_GET['token'];

$cek_token = $conn->query( "SELECT `token_verif` FROM `akun` WHERE `email` = '$email_daftar' " ) ;
$cek_token = mysqli_fetch_assoc($cek_token);

if ( $cek_token['token_verif'] != $token_daftar ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Link verifikasi salah',
            text:  'Silahkan minta link verifikasi kembali',
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

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SI-LSP | Verifikasi Akun</title>
  <!-- Favicon -->
  <link rel='shortcut icon' href='img/favicon/favicon-96x96.png' type='image/x-icon' />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">



  <script src="bower_components/jquery/dist/jquery.min.js"></script>

  <link rel="stylesheet" href="dist/css/app.css">
  <script src="dist/js/sweetalert.min.js"></script>

  <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      margin: 0;
    }

    .login-page,
    .register-page {
      background: #fff
    }
  </style>

  <script src="dist/js/angular.min.js"></script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <!--<img src="img/logo2.png" height="60%" width="180" class="rounded" alt="..."><br>-->
      <a href="index.php"><b>SI-</b>LSP</a>
      <h5>Sistem Informasi Lembaga Sertifikasi Profesi</h5>
      <h5><b>Politeknik Negeri Batam</b></h5>
    </div>
    <!-- /.login-logo -->
    
    <div class="login-box-body">
      <form action="" method="post">
        <div class="form-group has-feedback">
          <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan E-Mail" autocomplete="off"
            required="">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="verif" value="Log-in"
            data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading">Verifikasi Akun</button>
        </div>

        <script type="text/javascript">
          $('.btn').on('click', function () {
            var $this = $(this);
            $this.button('loading');
            setTimeout(function () {
              $this.button('reset');
            }, 3000);
          });
        </script>

      </form>

      <div class="social-auth-links text-center">
        <?php

          include 'config.php';
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));


          if($_POST['verif']) {

            $email_daftar = base64_decode($_GET['email']);

            $email  = strtolower ( mysqli_real_escape_string( $conn , $_POST['email'] ) ) ;


            if ( $email_daftar != $email ) {

              echo 
                '<div class="alert alert-warning" 
                role="alert">E-Mail yang dimasukkan tidak sama dengan E-Mail terdaftar</div>';
                
              exit;

            }




            $pass   = mysqli_real_escape_string( $conn , $_POST['password'] ) ;

            $cek_pass = $conn->query( "SELECT `password` FROM `akun` WHERE `email` = '$email_daftar' " ) ;
            $cek_pass = mysqli_fetch_assoc($cek_pass);


            if( password_verify($pass,$cek_pass['password']) ) {

              $update_status = $conn->query("UPDATE `akun` SET `token_verif` = '' ,`status_akun` = 'Verif' WHERE `email` = '$email_daftar' ");

              echo "
              <link rel='stylesheet' href='dist/css/app.css'>
              <script src='dist/js/sweetalert.min.js'></script>
                
                <script type='text/javascript'>
                  setTimeout(function () { 
                    swal({
                      title: 'Berhasil Verifikasi',
                      text:  'Akun Anda berhasil di verifikasi',
                      icon: 'success',
                      timer: 2000,
                    });  
                  },10); 
                  
                  window.setTimeout(function(){ 
                    window.location.replace('index.php');
                  } ,3000); 
                </script>";

            } else{
              
                echo 
                  '<div class="alert alert-warning" 
                  role="alert">Password Salah.</div>';

              } 
          
          
          }
          exit;

        ?>
      </div>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->

  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    });
  </script>


</body>
</html>

