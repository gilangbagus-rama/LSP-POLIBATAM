<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start(); include 'config.php';

$no_hp  = $_SESSION['no_hp'];
$nama   = $_SESSION['nama'];

$token_verif = $_SESSION['token_verif'];
$status_akun  = $_SESSION['status_akun'];

// Cek Login
$token    = $_SESSION['token'];
  $role     = $_SESSION['role'];
  $email    = $_SESSION['email'];

  // Cek Token Login
  if ( !isset($_SESSION['token'] ) || $token != $_SESSION['token']  ||
      !isset ( $_SESSION['role'] ) || $role != 'Asesor' ) :

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

  endif;

// End Cek Login
$id_asesor = $conn->query( "SELECT `id_asesor` FROM `asesor` WHERE `email` = '$email';" );
$data = mysqli_fetch_assoc ( $id_asesor ) ;

// Panggil data User Administrasi
$_SESSION['id_asesor'] = $data['id_asesor'];
$id_asesor = $_SESSION['id_asesor'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">
  <title>SI-LSP | Dashboard Asesor</title>
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
<script src="bower_components/jquery/dist/jquery.min.js"></script>
  
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
      folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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

    
    <script type="text/javascript">
    jQuery(function ($) {
    var $inputs = $('#button_atas :a id="ajukan" ').prop('disabled', true);
    $('#ajukan').click(function () {
        $inputs.prop('disabled', false);
    });
    })
  </script>


</head>
<body class="hold-transition skin-blue-light layout-top-nav">

<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand"><b>SI-</b>LSP</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="">Home</a></li>     

          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- /.navbar-custom-menu -->
            <div class="navbar-custom-menu">

              <ul class="nav navbar-nav"> <!-- Kontainer Dropdown Menu Profil  -->

                <li class="dropdown show user user-menu">  <!-- Menu Dropdown Profile -->
                  
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">


                    <?php 
                      
                      $ekstensi = explode( '.', $foto_profil );
                      $ekstensi =  $ekstensi[1];
                    
                    
                      if ( is_null($foto_profil) || $ekstensi !== 'jpg' && $ekstensi !== 'png' ) { ?>

                      <img src="./uploads/Foto Profil/default.png" class="user-image" alt="User Image">
                      <?php
                      } else {?> 
                      <img src="./uploads/Foto Profil/<?php echo $foto_profil?>" class="user-image" alt="User Image">

                      <?php }
                    ?>


                    <span class="hidden-xs">
                        
                        <?php echo $nama; ?>
                        
                    </span>
              
                  
                  </a>


                  <!-- Dropdown Profile Menu -->
                  <ul class="dropdown-menu treeview-menu" id="dropdown">


                    <li class="with-border">
                      <a class="btn-loading">
                        <i class="fa fa-user-circle-o"></i>
                        <span><?php echo $nama ?></span>
                      </a>
                    </li>



                    <li class="">
                      <a href="index.php" class="btn-loading">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                      </a>
                    </li>


                    <li class="">
                      <a href="index.php?page=kelola_asesmen_asesi" class="btn-loading">
                        <i class="fa fa-file"></i>
                        <span>Kelola Asesmen Asesi</span>
                      </a>
                    </li>

                    <li class="">
                      <a href="index.php?page=kelola_asesmen_asesi" class="btn-loading">
                        <i class="fa fa-file"></i>
                        <span>Kelola Berita Sertifikasi</span>
                      </a>
                    </li>


                    <li class="">
                      <a href="index.php?page=pengaturan_akun" class="btn-loading">
                        <i class="fa fa-gear"></i>
                        <span>Pengaturan Akun</span>
                      </a>
                    </li>





                  </ul>
                
                </li>

                <li>

                  <a class="fa fa-sign-out" href="#"
                    data-toggle="modal" data-target="#ModalLogout">
                  </a>

                </li>


              </ul> <!-- <ul class="nav navbar-nav"> -->
          
            </div>
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>

      <!-- Modal Logout -->
      <div id="ModalLogout" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog modal-dialog-centered" role="document" aria-labelledby="ModalLogoutTitle" aria-hidden="true">

          <div class="modal-content">

            <div class="modal-body">

              <center>
                <h3><strong>Logout</strong></h3>
              </center>

              <br>

              <center>
                <h4>Yakin ingin Logout ? </h4>
              </center>
                    
            </div>
                    
            <form action="index.php?page=logout" role="form" method="post">

              <!-- Input token user admin -->
              <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">
              <!-- End Input token user admin -->


              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" id="btn-del" class="btn btn-danger">Logout</button>
              </div>

            </form>

          </div>

        </div>

      </div>
      <!-- End Modal Logout -->

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Sistem Informasi LSP
          <small>Politeknik Negeri Batam</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        
        <div class="row" style="margin-top:10px;">
          

          <div class="col-md-8"> <!-- Sisi Kanan -->
            
            <div style="margin-bottom:5px;padding:5px 10px;background-color: #FDFDFD !important;border-radius:3px;">
              <small style="font-weight:bold;color:#3c8dbc;font-size:13px;">Menu Asesor</small>
            </div>
            
            <div class="btn-group btn-group-justified"> <!-- 3 Menu atas -->
            
              <!-- Menu Data Diri -->
              <a href="index.php?page=kelola_asesmen_asesi" class="btn btn-menu" style="white-space: normal">
                <img src="img/file.png" class="icon-menu" alt="Kelola Asesmen Asesi" style="margin-bottom:7px;">
                <br> Kelola Asesmen Asesi</br>
              </a>
                        
              
              <!-- Menu Ajukan Permohonan Sertifikasi -->
              <a href="index.php?page=kelola_beritaLSP" class="btn btn-menu" style="white-space: normal">
                <img src="img/member.png" class="icon-menu" alt="Kelola KUK" style="margin-bottom:7px; white-space: normal">
                <br> Kelola Berita Sertifikasi</br>
              </a>
                        
              <!--  Menu 3 -->
              <a href="index.php?page=pengaturan_akun" class="btn btn-menu">

                <img src="img/gear.png" class="icon-menu" 
                  alt="Pengaturan Akun" style="margin-bottom:7px;">
                  
                <br>Pengaturan Akun</br>

              </a>
              

            </div> <!-- 3 Menu pertama -->
                      
            
            <div class="btn-group btn-group-justified"> <!-- 3 Menu dibawah -->

              <!-- Menu 4 -->
              <a class="btn btn-menu" style="white-space: normal">
                
              </a>
              
              <!-- Menu 5 -->
              <a href="index.php?page=#" class="btn btn-menu">
              
              </a>
                        
              <a href="index.php?page=#" class="btn btn-menu">

              </a>    
            
            </div> <!-- 3 Menu dibawah -->
            
            <br>

            
            <div class="box box-danger"> <!-- Card Berita -->
              
              
              <div class="box-header with-border">
                <h3 class="box-title">Informasi Penting</h3>
              </div>
              
              <div class="box-body"> <!-- Isi Berita -->
                
                <li>nama</li>

                <?php 
                var_dump($_SESSION);
                ?>

              </div>
              
            </div> <!-- Card Berita -->
          
          </div> <!-- Sisi Kanan -->
        
        
        <div class="col-md-4"> <!-- Sisi Kiri -->
        
        <!-- Card Perhatian -->
        <?php

          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

          $data = mysqli_fetch_assoc( $conn->query("SELECT `status_akun` FROM `akun` WHERE `email` = '$email' ") );


          if ($status_akun == "Not Verif") {
          ?>

          <div class="box box-widget" style="margin-bottom: 10px;background-color: #FDFDFD">
            
            <div class="alert alert-warning alert-dismissible">
              
              <button type="button" class="close" 
                data-dismiss="alert" aria-hidden="true">&times;</button>
              
              <h4>
                <i class="icon fa fa-warning"></i> 
                Perhatian!
              </h4>
              
              Akun Anda belum di Verifikasi , Silahkan cek E-Mail dan verifikasi Akun Anda segera.
              
              <br>

              <a href="proses_user.php?aksi=<?php echo base64_encode('verif_ulang')?>&&email=<?php echo base64_encode($email) ?>">
                Kirim Ulang Kode Verifikasi
              </a>

              <br>
              <a href="index.php?page=ganti-email">
                Ganti E-Mail Verifikasi
              </a>

            </div>

          </div>

        <?php }?>

          <!-- Card Perhatian -->

          <div class="box box-success"> <!-- Card Skema LSP Polibatam -->
            
            <div class="box-header with-border"> <!-- Judul Card -->
              <h5 class="box-title">Skema LSP Polibatam</h5>
            </div>
            
            <div class="box-body"> <!-- Isi Card -->
            <p><strong>Skema uji yang tersedia di LSP Polibatam</strong><br>
            <!-- <table id="load" class="table table-hover">
                <tbody> -->
                  <ol>
                  <?php
                    include "config.php";
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    $sql_skema  = $conn->query("SELECT * FROM `skema_sertifikasi`;");
                    
                    while ($dat = mysqli_fetch_array($sql_skema)) {
            
                    echo '<li>' .$dat['nama_skema']. '</li>' ;
                    
                    
                  } ?>

                  </ol>
              <!-- </tbody>

            </table> -->
          </div> <!-- Isi Card -->
        
        </div> <!-- Card Skema LSP Polibatam -->
        


        </div>
      </div>    
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->

  <!-- /.footer -->
  <?php
      include('footer.php');
  ?>



</div>
<!-- ./wrapper -->

<!-- inner style -->
<style>
.height-info{
    height: 920px;
}
.icon-menu{
    margin-top:5px;
    width:50%;
}
@media  screen and (max-width: 780px) {
  .height-info{
      height: 500px;
  }
  .icon-menu{
      margin-top:5px;
        width:80%;
    }
}
.btn-menu{
    background-color: #fff;
    border-color:#f5f5f5;
    color:#3c8dbc;
    font-size:12px;
}
.btn-menu:hover{
    background-color: #fff;
    border-color:#3c8dbc;
    color:#3c8dbc;
    font-size:12px;
}
.btn-menu:focus{
    background-color: #fff;
    border-color:#3c8dbc;
    color:#3c8dbc;
    font-size:12px;
}
.btn-menu-disabled{
    background-color: #fff;
    border-color:#f5f5f5;
    color:#BDBFC1;
    font-size:12px;
}
.btn-menu-disabled:hover{
    background-color: #fff;
    border-color:#f5f5f5;
    color:#BDBFC1;
    font-size:12px;
}
.btn-menu-disabled:focus{
    background-color: #fff;
    border-color:#f5f5f5;
    color:#BDBFC1;
    font-size:12px;
}
</style>
  
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) 
<script src="dist/js/pages/dashboard2.js"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>


</body>
</html>
