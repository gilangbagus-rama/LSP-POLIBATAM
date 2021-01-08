<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SI-LSP | Halaman Register</title>
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

    input.invalid {
  background-color: #ffdddd;
}
  </style>

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

    <!-- Form Register -->
    <div class="register-box-body">
      
      <form class="needs-validation" action="proses_user.php?aksi=<?php echo base64_encode("register");?>" method="post">

        <div class="panel panel-default"> <!-- Data Akun -->
          

          <div class="panel-body">


            <div class="panel panel-default" style="background-color:white; color:black;">
              <div class="panel-body">
                <center><i class="glyphicon glyphicon-user"></i> Data Akun</center>
              </div>
            </div>

            <!-- Input Nama -->
            <div class="form-group row has-feedback tab">
              <label for=for="validationCustom03" class="col-sm-3 form-label">Nama Lengkap</label>

              <div class="col-sm-9">
                <input type="text" id="validationCustom03" name="nama_asesi" class="form-control" placeholder="Masukkan Nama Anda" required="">
              </div>
            </div>

            <!-- Input Email -->
            <div class="form-group row has-feedback tab">
              <label for="email" class="col-sm-3 col-form-label">E-Mail</label>

              <div class="col-sm-9">

                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan E-Mail Anda"
                  required="">

              </div>
            </div>

            <!-- Input No Hp -->
            <div class="form-group row has-feedback">

              <label for="nohp" class="col-sm-3 col-form-label">No HP</label>

              <div class="col-sm-9">

                <input type="tel" min-length="4" max-length="20" id="nohp" name="nohp" class="form-control" placeholder="Masukkan No HP Anda"
                  placeholder="" required="">

              </div>
            </div>

            <!-- Input Password -->
            <div class="form-group row has-feedback">


              <label for="password" class="col-sm-3 col-form-label">Password</label>

              <div class="col-sm-9">

                <input type="password" id="password" name="password" class="form-control"
                  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Masukan Password" required="">

                <!-- pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" = sebagai pembatas / REGEX -->

                <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            Password Helper -->
                <small id='passwordHelpBlock' class='form-text text-muted'>
                  Password minimal 8 Karakter, terdiri dari huruf besar, huruf kecil, & angka</small>

              </div>
            </div>


            <!-- Input Re Password -->
            <div class="form-group row has-feedback">
              <label for="password2" class="col-sm-3 col-form-label">Konfirmasi Password</label>

              <div class="col-sm-9">
                <input type="password" id="password2" name="password2" class="form-control"
                  placeholder="Masukan Password Kembali" required="">
              </div>
            </div>


          </div>
        </div>

        <!-- /.col -->
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="register"
            data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading">Register</button>
        </div>
        <script type="text/javascript">
          $('.btn').on('click', function () {
            var $this = $(this);
            $this.button('loading');
            setTimeout(function () {
              $this.button('reset');
            }, 5000);
          });

          (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
              .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                  if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                  }

                  form.classList.add('was-validated')
                }, false)
              })
          })()

        </script>
 
        <!-- /.col -->
      </form>

      <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="index.php" class="btn btn-block btn-social btn-facebook btn-flat"><i
            class="glyphicon glyphicon-user"></i> LOGIN</a>
      </div>
    </div>
    <!-- /.form-box -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery 3 -->

  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->

</body>

</html>