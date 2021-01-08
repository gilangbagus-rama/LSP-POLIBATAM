<?php

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  include 'config.php';

  session_start();

  // Cek Login
  $token    = $_SESSION['token'];
    $role   = $_SESSION['role'];
    $email  = $_SESSION['email'];

    // Cek Login
    if ( !isset( $_SESSION['token'] ) || empty( $_SESSION['token'] ) ||
        !isset( $token ) || empty( $token ) || $token != $_SESSION['token']
    ) {

      header("location: index.php?page=forbidden&aksi=not_login");
      exit;

    };

  // End Cek Login


?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SI-LSP | Ganti Password</title>
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

<body class="hold-transition register-page">


  <div class="container register-box" style="width:1000px;">


    <div class="login-logo">
      <!--<img src="img/logo2.png" height="60%" width="180" class="rounded" alt="..."><br>-->
      <a href="index.php"><b>SI-</b>LSP</a>
      <h5>Sistem Informasi Lembaga Sertifikasi Profesi</h5>
      <h5><b>Politeknik Negeri Batam</b></h5>
    </div>
    <!-- /.login-logo -->


    <div class="register-box-body">
      <form action="" method="post">

      <div class="panel panel-default">

        <div class="panel-body">

          <div class="panel panel-default" style="background-color:white; color:black;">
            <div class="panel-body">
              <center><i class="fa fa-key"></i> Ganti Password</center>
            </div>
          </div>

        <div class="form-group row has-feedback">
          <label for="password_lama" class="col-sm-3 col-form-label">Password Lama</label>

        <div class="col-sm-9">
          <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Masukkan Password Lama" required="" />
          
        </div> </div>



        <div class="form-group row has-feedback">
          <label for="password_baru" class="col-sm-3 col-form-label">Password Baru</label>

        <div class="col-sm-9">
          <input type="password" name="password_baru" id="password_baru" class="form-control" 
          pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Masukkan Password Baru" required="" />
          <small id='passwordHelpBlock' class='form-text text-muted'>
          Password Baru minimal 8 Karakter, terdiri dari huruf besar, huruf kecil, & angka</small>

        </div> </div>



        <div class="form-group row has-feedback">
          <label for="password_baru2" class="col-sm-3 col-form-label">Konfirmasi Password Baru</label>

        <div class="col-sm-9">
          <input type="password" name="password_baru2" id="password_baru2" class="form-control" placeholder="Masukkan Password Baru" required="" />
          
        </div> </div>





      
      
      </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="ganti-pass" value="Log-in"
            data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading">Ganti Password</button>
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
          include 'Bcrypt.php';
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

          $bcrypt = new Bcrypt(16);

          if($_POST['ganti-pass']) {

            // Cek match password
            $password_baru = mysqli_real_escape_string($conn, $_POST['password_baru']);
            $password_baru2 = mysqli_real_escape_string($conn, $_POST['password_baru2']);

            if ( $password_baru != $password_baru2 ) {

              echo '
                <div class="alert alert-warning" 
                  role="alert">Password baru tidak sama.</div>';

            exit;
            }

            $cek_email = $conn->query( "SELECT * FROM `akun` WHERE `email` = '$email' ; " ) ;

            $cek   = mysqli_fetch_array($cek_email);

            if ( mysqli_num_rows( $cek_email ) == 0 ) {

              echo '
              <div class="alert alert-warning" role="alert">
              E-Mail tidak ditemukan.</div>';
            
            exit;
            }

            $password_lama = mysqli_real_escape_string($conn, $_POST['password_lama']);

            if ( password_verify( $password_lama , $cek['password'] )  ) {

              $password_baru = mysqli_real_escape_string($conn, $_POST['password_baru']);
              $password_baru = password_hash($password_baru,PASSWORD_DEFAULT);

              $SQL_Update = $conn->query("UPDATE `akun` SET `password` = '$password_baru' WHERE `email` = '$email'");

              $SQL_histori_password = $conn->query("INSERT INTO `histori_password`(`email`, `password`) VALUES ('$email','$password_baru')");

              if( $SQL_Update === TRUE ) {

                session_start();

                $_SESSION = [];

                session_unset();

                session_destroy();

                echo "
                  <script type='text/javascript'>

                    setTimeout(function () { 
                      swal({
                        title: 'Berhasil Ganti Password',
                        text:  'Silahkan Login kembali dengan password baru',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: true
                      });  
                    },10); 
                    window.setTimeout(function(){ 
                      window.location.replace('index.php');
                    } ,3000); 

                  </script>";


              exit;
              }

            } else { 
            echo'
              <div class="alert alert-warning" role="alert">
              Password Lama Salah.</div>';
            exit;
            }


          exit;
          }
          

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

