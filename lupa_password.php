<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SI-LSP | Lupa Password</title>
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
          <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan E-Mail" autocomplete="off" autofocus onpaste="return false;"
            required="">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="reset_pass" value="Log-in"
            data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading">Kirim E-Mail Reset Password</button>
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
      <p>- OR -</p>
      </div>

      <div class="social-auth-links text-center">
        <a href="index.php" class="btn btn-block btn-social btn-facebook btn-flat"><i
            class="glyphicon glyphicon-user"></i>Login</a>
      </div>
      
      

      <div class="social-auth-links text-center">
      
        <?php

          include 'config.php';
          include 'Bcrypt.php';
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

          $bcrypt = new Bcrypt(16);

          if($_POST['reset_pass']) {


            $email  = strtolower ( mysqli_real_escape_string( $conn , $_POST['email'] ) ) ;

            $cekEMAIL = $conn->query("SELECT * FROM `akun` WHERE `email` = '$email' OR `no_hp` = '$email'") ;

            if ( mysqli_num_rows($cekEMAIL) == 0 ) {

              echo 
                '<div class="alert alert-warning" 
                role="alert">E-Mail tidak terdaftar di sistem.</div>';
                
              exit;

            }


            // Buat Token Reset Password

              $email_length = strlen( $email );
              $length = '30';
              $length = $length + $email_length;
              $token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);


              $nama = $conn->query("SELECT `nama` FROM `akun` WHERE `email` = '$email' ");
              $nama = mysqli_fetch_assoc($nama) ['nama'];

              $email_verif = base64_encode($email) ;

              $link_batal = 'http://localhost/lsp/index.php?page=reset-password&&aksi=batal-reset&&email=' .$email_verif. '&token=' .$token ;

              $link = 'http://localhost/lsp/index.php?page=reset-password&&aksi=reset&&email=' .$email_verif. '&token=' .$token;


              $header_email = '<b>Yth. Bapak/Ibu ' .$nama. '</b> <br>';


              $body_email = '<br>Berikut ini adalah link untuk melakukan reset password akun Anda <br>' .$link;

              $body_email = $body_email. '<br>Jika Anda merasa tidak melakukan permintaan reset password, silahkan klik link berikut <br>';

              $body_email = $body_email. $link_batal;

              $body_email = $header_email. $body_email;

              $body_email = $body_email. '<br> <br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';


    // Kirim E-Mail Verifikasi
    require 'PHPMailerAutoload.php';
    require 'credential.php';

    $mail->addAddress($email);     // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML

    

    $mail->Subject = 'Link Reset Password Akun LSP Polibatam';
    $mail->Body    = $body_email;
    // $mail->AltBody = $_POST['message'];

    if(!$mail->send()) {
      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal mengirim email verifikasi',
              text:  'Silahkan periksa kembali email Anda !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=lupa_password');
          } ,3000); 
        </script>";
  

    }



    else {


      $sql_input = $conn->query("UPDATE `akun` SET `token_verif` = '$token' WHERE `email` = '$email' ");

      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Berhasil mengirim email verifikasi',
              text:  'Silahkan periksa email Anda untuk melakukan reset password !',
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

