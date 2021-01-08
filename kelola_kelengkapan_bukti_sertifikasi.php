<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start(); include 'config.php';

$no_hp  = $_SESSION['no_hp'];
$nama   = $_SESSION['nama'];

$token_verif = $_SESSION['token_verif'];
$status_akun  = $_SESSION['status_akun'];

$id_UA = $_SESSION['id_UA'];

// Cek Login
$token    = $_SESSION['token'];
  $role     = $_SESSION['role'];
  $email    = $_SESSION['email'];

  // Cek Token Login
  if ( !isset($_SESSION['token'] ) || $token != $_SESSION['token']  ||
      !isset ( $_SESSION['role'] ) || $role != 'User Administrasi' ) :

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

  endif;

// End Cek Login

$id_permohonan = $_GET['permohonan'];

?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Kelola Kelengkapan Bukti Sertifikasi</title>
  
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

  <script>
  
  $(document).ready(function() {
    $('#load').DataTable();
  } );
  
  </script>

  <script>
  
  $(document).ready(function () {
    $("#nama_dosen").select2({
      placeholder: "Please Select"
    });
  });
  
  </script>

  <script>
  
  $(document).ready(function () {
    $("#nama_dosen2").select2({
      placeholder: "Please Select"
      });
    });
  
  </script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


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

<body class="hold-transition skin-blue-light sidebar-mini">


  <!-- Site Wrapper -->
  <div class="wrapper">

    <!-- Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="index.php?page=dashboard_user_admin" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">SI-LSP</span>
        <!-- logo for regular state and mobile devices -->
        <img src="img/logo.png" height="100%" class="rounded" alt="SI-LSP">
      </a>
      <!-- End Logo -->


      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
      
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          
          <span class="sr-only">
          Toggle navigation
          </span>
        
        </a>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">


          <ul class="nav navbar-nav">

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">

              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $nama; ?></span>
              </a>

            </li>

            <li>

              <a class="fa fa-sign-out"
                data-toggle="modal" data-target="#ModalLogout">
              </a>

            </li>

          </ul>
        </div>
        <!-- End Navbar Right Menu -->

      </nav>
      <!-- End Header Navbar -->


    </header>
    <!-- End Header -->

    
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


    <!-- =============================================== -->


    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">


      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">


        <!-- sidebar menu: : style can be found in sidebar.less -->
        <div class="user-panel">


          <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>

          <div class="pull-left info">
            <p style="white-space: normal"><?php echo $nama; ?></p>
            <a href="#"><?php echo $role; ?></a>
          </div>

        </div>
        <!-- End Sidebar menu  -->


        <!-- Include Sidebar_admin -->
        <?php
          include('sidebar.php');
        ?>
        <!-- End Include Sidebar_Admin -->


      </section>
      <!-- End Section Sidebar -->


    </aside>
    <!-- End Left Side coloumn -->


    <!-- Inner Style -->
    <style type="text/css">

      td {
        word-wrap: break-word;
      }

      table.grid {
        width:100%;
        border:none;
        background-color:#3c8dbc;
        padding:0px;
      }

      table.grid tr {
        text-align:center;
      }

      table.grid td {
        border:4px solid white;
        padding:6px;
      }

      .margin-top-responsive-5{
        margin-top:5px;
      }

      .btn-grid{
        background:#325d88;
        color:#ffffff;
      }

      td.active{
        background:#367fa9;
      }

      @media  screen and (max-width: 780px) {

        .grid small{
          font-size:11px;
        }

        .margin-top-responsive-5{
          margin-top:0px;
        } 
      }

    </style>
    <!-- End Inner Style -->


    <!-- File Upload inner style -->
    <style type="text/css">

      .fileUpload{
        position: absolute;
        overflow: hidden;
        margin: 0;
      }

      .fileUpload input.upload{
        position: absolute;
        top: 0;
        right: 0;
        margin:0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
      }

    </style>
    <!-- End File Upload inner style -->



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


      <!-- Content Header (Page header) -->
      <section class="content-header">

        <h1>
          Dashboard
          <small>SI - LSP</small>
        </h1>

        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li>Kelola Permohonan Sertifikasi</li>
          <li class="active">Kelola Kelengkapan Bukti Permohonan Sertifikasi</li>
        </ol>

      </section>
      <!-- End Content Header -->
      <?php
        $sql = $conn->query("SELECT`permohonan`.`id_asesi` , `permohonan`.`file_APL_01` FROM `permohonan` WHERE `permohonan`.`id_permohonan` = '$id_permohonan';");
        $data      = mysqli_fetch_assoc($sql); $id_asesi = $data['id_asesi']; $file_APL_01 = $data['file_APL_01'];
      ?>
      <!-- Main content -->
      <section class="content">


        <!-- Input addon -->
        <div class="box box-widget" style="margin-bottom: 10px;background-color: #FDFDFD">


          <div class="box-body">


            <h4 style="margin-bottom: 0px;margin-top: 0px;" class="pull-left text-primary">
              <i class="fa fa-info-circle" style="margin-right: 6px;"></i>
              Kelola Kelengkapan Bukti Permohonan Sertifikasi
            </h4>

            <h4 style="margin-bottom: 0px;margin-top: 0px;" class="pull-right text-primary">
              <a href="./uploads/Asesi/<?php echo $id_asesi?>/<?php echo $id_permohonan?>/<?php echo $file_APL_01?>" target="_blank"><i class="fa fa-file" style="margin-right: 6px;"></i>
              Lihat File APL-01 </a>
            </h4>

            
            <!-- <a class="btn-loading label label-primary pull-right"
              style="font-size: 13px;padding-bottom: 5px;padding-top: 5px;">

              <i class="fa fa-file-pdf-o" style="margin-right: 3px;"></i>
              Ekspor ke file PDF

            </a> -->


          </div>
          <!-- End Box Body -->


        </div>
        <!-- End Input Addon -->


        <div class="row">


          <!-- Box Kanan -->
          <div class="col col-lg-7 panel-group">



            <!-- Panel Rincian Data Pemohon -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading" style="color: white">
                  <a data-toggle="collapse" data-parent="#accordion" href="#RincianDataPemohon">
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Rincian Data Pemohon Sertifikasi </i>
                      
                    </h4>
                  </a>
                </div>
                <!--End Judul Panel-->


                <!-- Isi -->
                <div id="RincianDataPemohon" class="panel-collapse collapse in">


                  <div class="panel-body">


                    <!-- Box Data Pribadi -->
                    <div class="box box-danger">


                      <div class="box-header with-border">

                        <!-- <a data-toggle="collapse" data-parent="#accordion" href="#DataPribadi"> -->
                          <h4 class="panel-title with-border">
                          
                            <i class="fa fa-user"> Data Pribadi Pemohon Sertifikasi </i>
                          
                          </h4>
                        <!-- </a> -->


                      </div>

                      <!-- Query Data Pribadi -->
                      <?php
                      
                        $sql = $conn->query("SELECT `akun`.`nama` , `akun`.`email` , `akun`.`no_hp` , `asesi`.`id_asesi` , `asesi`.`no_nik` , `asesi`.`tmpt_lahir` , `asesi`.`tgl_lahir` ,
                        `asesi`.`jenkel` , `asesi`.`kebangsaan` , `asesi`.`alamat_rmh` , `asesi`.`kodepos` , `asesi`.`notelp_rmh` , `asesi`.`telppribadi_perusahaan`, `asesi`.`pendidikan` 
                        FROM `akun` , `asesi`, `permohonan` WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `asesi`.`email` = `akun`.`email` AND `permohonan`.`id_permohonan` = '$id_permohonan';");
                        $pribadi       = mysqli_fetch_assoc($sql);
                        if ( $pribadi['jenkel'] == 'lk' ){
                          $pribadi['jenkel'] = 'Laki - Laki';
                        } else {
                          $pribadi['jenkel'] = 'Perempuan';
                        };

                      ?>


                      <!-- Box Body -->
                      <div class="box-body">


                        <!-- Nama -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label for="inputEmail4">Nama</label>
                            <input type="email" class="form-control" placeholder="<?php echo $pribadi['nama'] ?>" readonly>
                          </div>

                        </div>


                        <!-- No KTP -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>No. KTP</label>
                            <input class="form-control" placeholder="<?php echo $pribadi['no_nik']?>" readonly>
                          </div>

                        </div>


                        <!-- Tempat/Tanggal Lahir -->
                        <div class="form-group">

                          <div class="form-row col-md-8">
                            <label for="inputEmail4">Tempat Lahir</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['tmpt_lahir']?>" readonly>
                          </div>

                          <div class="form-row col-md-4">
                            <label for="inputEmail4">Tanggal Lahir</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['tgl_lahir']?>"  readonly>
                          </div>

                        </div>


                        <!-- Jenis Kelamin -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>Jenis Kelamin</label>
                            <input type="email" class="form-control" placeholder="<?php echo $pribadi['jenkel'] ?>" readonly>
                          </div>

                        </div>


                        <!-- Kebangsaan -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>Kebangsaan</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['kebangsaan'] ?>" readonly>
                          </div>

                        </div>


                        <!-- Alamat Rumah - Kode Pos -->
                        <div class="form-group">

                          <div class="form-row col-md-8">
                            <label>Alamat Rumah</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['alamat_rmh']; ?>" readonly>
                          </div>

                          <div class="form-row col-md-4">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['kodepos']; ?>" readonly>
                          </div>

                        </div>



                        <!-- Phone/E-Mail -->
                        <div class="form-group">

                          <div class="form-row col-md-6">
                            <label>Telpon Rumah</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['notelp_rmh']; ?>" readonly>
                          </div>

                          <div class="form-row col-md-6">
                            <label>Telepon HP</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['no_hp']; ?>" readonly>
                          </div>

                          <div class="form-row col-md-6">
                            <label>Telpon Kantor</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['telppribadi_perusahaan']; ?>" readonly>
                          </div>

                          <div class="form-row col-md-6">
                            <label>E-Mail</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['email']; ?>" readonly>
                          </div>

                        </div>


                        <!-- Kualifikasi/Pendidikan -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>Kualifikasi/Pendidikan</label>
                            <input type="text" class="form-control" placeholder="<?php echo $pribadi['pendidikan']?>" readonly>
                          </div>

                        </div>


                      </div>



                    </div>
                    <!-- End Box Data Pribadi -->




                    <!-- Query Pekerjaan -->
                    <?php

                      $sql = $conn->query("SELECT `asesi`.`nama_perusahaan` , `asesi`.`jabatan` , `asesi`.`alamat_perusahaan` ,
                      `asesi`.`kodepos_perusahaan` , `asesi`.`telp_perusahaan` , `asesi`.`fax_perusahaan` , `asesi`.`email_perusahaan`
                      FROM `akun` , `asesi`, `permohonan` WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `asesi`.`email` = `akun`.`email` AND `permohonan`.`id_permohonan` = '$id_permohonan';");
                      $perusahaan      = mysqli_fetch_assoc($sql);

                    ?>

                    <!-- Box Data Pekerjaan -->
                    <div class="box box-warning">


                      <div class="box-header with-border">

                        <!-- <a data-toggle="collapse" data-parent="#accordion" href="#DataPribadi"> -->
                          <h4 class="panel-title with-border">
                          
                            <i class="fa fa-user"> Data Pekerjaan Pemohon Sertifikasi </i>
                          
                          </h4>
                        <!-- </a> -->


                      </div>


                      <div class="box-body">


                        <!-- Nama Perusahaan-->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>Perusahaan/Lembaga</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['nama_perusahaan']?>" readonly>
                          </div>

                        </div>


                        <!-- Jabatan -->
                        <div class="form-group">

                          <div class="form-row col-md-12">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['jabatan']?>" readonly>
                          </div>

                        </div>




                        <!-- Alamat Kantor - Kode Pos -->
                        <div class="form-group">

                          <div class="form-row col-md-8">
                            <label>Alamat Kantor</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['alamat_perusahaan']?>" readonly>
                          </div>

                          <div class="form-row col-md-4">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['kodepos_perusahaan']?>" readonly>
                          </div>

                        </div>



                        <!-- No. Telp/Fax/E-Mail -->
                        <div class="form-group">

                          <div class="form-row col-md-6">
                            <label>Telpon Kantor</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['telp_perusahaan']?>" readonly>
                          </div>

                          <div class="form-row col-md-6">
                            <label>Fax</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['fax_perusahaan']?>" readonly>
                          </div>

                          <div class="form-row col-md-12">
                            <label>E-Mail</label>
                            <input type="text" class="form-control" placeholder="<?php echo $perusahaan['email_perusahaan']?>" readonly>
                          </div>


                        </div>


                      </div>
                      
                    </div>
                    <!-- End Box Data Pekerjaan -->

                    <a data-toggle="collapse" data-parent="#accordion" href="#RincianDataPemohon">
                      <h4 class="panel-footer with-border">
                      
                        <center><i class="fa fa-arrow-up"> Tutup </i></center>
                      
                      </h4>
                    </a>


                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Rincian Data Pemohon -->



            <!-- Panel Data Sertifikasi -->
            <div class="panel box">


                <!-- Judul -->
                <div class="panel-heading" style="color: white">
                  <a data-toggle="collapse" data-parent="#accordion" href="#DataSertifikasi">
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Data Sertifikasi </i>
                      
                    </h4>
                  </a>
                </div>
                <!--End Judul  -->


                <!-- Isi -->
                <div id="DataSertifikasi" class="panel-collapse collapse in">



                  <!-- Query Data Sertifikasi -->
                  <?php
                  
                    $sql = $conn->query("SELECT `permohonan`.`file_APL_01` , `permohonan`.`id_skema`,`permohonan`.`tujuan_asesmen` , `skema_sertifikasi`.`nama_skema` , `skema_sertifikasi`.`nomor_skema`
                    FROM `permohonan` , `skema_sertifikasi` WHERE `permohonan`.`id_skema`=`skema_sertifikasi`.`id_skema` AND `permohonan`.`id_permohonan` = '$id_permohonan' ;");
                    $sertifikasi      = mysqli_fetch_assoc($sql); $id_skema = $sertifikasi['id_skema']; $file_APL_01 = $sertifikasi['file_APL_01'];;
                    
                  ?>


                  <div class="panel-body box-body">


                    <!-- Judul Skema -->
                    <div class="form-group">

                      <div class="form-row col-md-12">
                        <label>Judul Skema</label>
                        <input type="text" class="form-control" placeholder="<?php echo $sertifikasi['nama_skema'] ?>" readonly>
                      </div>

                    </div>
                    <!-- End Judul Skema -->


                    <!-- Nomor Skema -->
                    <div class="form-group">

                      <div class="form-row col-md-12">
                        <label>Nomor Skema</label>
                        <input type="text" class="form-control" placeholder="<?php echo $sertifikasi['nomor_skema'] ?>" readonly>
                      </div>

                    </div>
                    <!-- End Nomor Skema -->


                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <div class="form-row col-md-12">
                        <label>Tujuan Asesmen</label>
                        <input type="text" class="form-control" placeholder="<?php echo $sertifikasi['tujuan_asesmen'] ?>" readonly>
                      </div>

                    </div> <br>
                    <!-- End Tujuan Asesmen -->


                    <!-- Tabel Unit Skema -->
                    <div class="form-group">

                      <div class="form-row col-md-12">

                        <table class="table table-bordered">


                          <thead>
                            <tr>
                              <th width="5%">No</th>
                              <th width="15%">Kode Unit</th>
                              <th width="25%">Judul Unit</th>
                              <th width="15%">Jenis Standar</th>
                            </tr>
                          </thead>


                          <tbody>


                          <?php

                            error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                            include 'config.php';
                            $sql = $conn->query("SELECT `unit_skema`.`kode_unit` , `unit_skema`.`judul_unit` , `unit_skema`.`jenis_standar` 
                            FROM `unit_skema` , `permohonan` WHERE `permohonan`.`id_skema` = `unit_skema`.`id_skema` 
                            AND `permohonan`.`id_permohonan` = '$id_permohonan' ;"); $no = 1;

                            while ( $unit_skema      = mysqli_fetch_assoc($sql) ){
                              
                            
                            
                            ?>



                            <tr>
                              <th>
                                <?php echo $no;?>
                              </th>

                              <th>
                                <?php echo $unit_skema['kode_unit']; ?>
                              </th>

                              <th>
                                <?php echo $unit_skema['judul_unit']; ?>
                              </th>
                              
                              <th>
                                <?php echo $unit_skema['jenis_standar']; ?>
                              </th>

                            </tr>

                          <?php 
                          
                            $no = $no+1;
                              }
                          
                          ?>


                          </tbody>

                        </table>

                      </div>


                    </div>
                    <!-- End Tabel Unit Skema -->


                    <!-- Tombol Tutup -->
                    <div class="form-group">


                      <div class="form-row col-md-12">
                        <a data-toggle="collapse" data-parent="#accordion" href="#DataSertifikasi">
                          <h4 class="panel-footer with-border">
                          
                            <center><i class="fa fa-arrow-up"> Tutup </i></center>
                          
                          </h4>
                        </a>
                      </div>


                    </div>
                    <!-- End Tombol Tutup -->


                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Sertifikasi -->


            <!-- Panel Bukti Kelengkapan Pemohon -->
            <div class="panel box">


                <!-- Judul -->
                <div class="panel-heading" style="color: white">
                  <a data-toggle="collapse" data-parent="#accordion" href="#BuktiKelengkapanPemohon">
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-list-alt"> Bukti Kelengkapan Pemohon </i>
                      
                    </h4>
                  </a>
                </div>
                <!--End Judul  -->


                <!-- Isi -->
                <div id="BuktiKelengkapanPemohon" class="panel-collapse collapse in">


                  <div class="panel-body box-body">



                    <!-- Tabel Unit Skema -->
                    <div class="form-group">

                      <div class="form-row col-md-12">

                        <table class="table table-bordered">

                          <thead>
                            <tr>
                              <th width="10%">No</th>
                              <th width="50%">Syarat Dasar</th>
                              <th width="30%">#</th>
                            </tr>
                          </thead>

                          <tbody>

                            <?php
                            
                            $sql = $conn->query("SELECT `syarat_dasar`.`syarat` , `syarat_permohonan`.`id_permohonan` , `syarat_permohonan`.`id_syarat`, `syarat_permohonan`.`terpenuhi` 
                            FROM `syarat_dasar`,`syarat_permohonan` WHERE syarat_dasar.id_syarat = syarat_permohonan.id_syarat 
                            AND `syarat_permohonan`.`id_permohonan` = '$id_permohonan'; ");
                            
                            $no = 1 ;
                            while( $syarat_dasar = mysqli_fetch_assoc($sql) ){ ?>
                            

                            <tr>

                              <td>
                                <?php echo $no ?>
                              </td>

                              <td>
                                <?php echo $syarat_dasar['syarat']?>
                              </td>


                              <td>
                                <?php echo $syarat_dasar['terpenuhi']?>
                              </td>

                            
                            </tr>
                            
                            
                            <?php
                            $no = $no +1; }
                            ?>

                          </tbody>

                        </table>

                      </div>


                    </div>
                    <!-- End Tabel Unit Skema -->


                    <!-- Tombol Tutup -->
                    <div class="form-group">


                      <div class="form-row col-md-12">
                        <a data-toggle="collapse" data-parent="#accordion" href="#BuktiKelengkapanPemohon">
                          <h4 class="panel-footer with-border">
                          
                            <center><i class="fa fa-arrow-up"> Tutup </i></center>
                          
                          </h4>
                        </a>
                      </div>


                    </div>
                    <!-- End Tombol Tutup -->


                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Bukti Kelengkapan Pemohon -->



            <form action="proses_user_admin.php?aksi=nilai_permohonan" role="form" method="post">
              <input type="hidden" name="token" value="<?php echo $token ?>" />
              <input type="hidden" name="id_UA" value="<?php echo $id_UA ?>" />
              <input type="hidden" name="role" value="<?php echo $role ?>" />
              
              <!-- Panel Portofolio -->
              <div class="panel box">


                  <!-- Judul -->
                  <div class="panel-heading" style="color: white">
                    <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon">
                      <h4 class="panel-title with-border">
                        
                        <i class="fa fa-file"> Portofolio </i>
                        
                      </h4>
                    </a>
                  </div>
                  <!--End Judul  -->

                  <!-- Isi -->
                  <div id="KelengkapanPemohon" class="panel-collapse collapse in">


                    <div class="panel-body box-body">

                      <div class="form-group">

                        <div class="form-row col-md-12">


                          <table class="table table-bordered">

                            <thead>
                              <tr>
                                <th width="10%">No</th>
                                <th width="20%">Portofolio</th>
                                <th width="40%">Dokumen Portofolio</th>
                                <th width="30%">Penilaian</th>
                              </tr>
                            </thead>

                            <tbody>

                              <?php
                              
                              $sql = $conn->query("SELECT * FROM `portofolio` WHERE `id_permohonan` = '$id_permohonan' ");
                              
                              $no = 1 ;
                              while( $portofolio = mysqli_fetch_assoc($sql) ){ ?>
                              

                              <tr>

                                <td>
                                  <?php echo $no ?>
                                </td>

                                <td>
                                  <?php echo $portofolio['keterangan']?>
                                </td>

                                <td>

                                  <a class="btn btn-primary btn-block"
                                    data-toggle="modal" data-target="#ModalCekPortofolio<?php echo $no?>">

                                    <i class="fa fa-search" style="margin-right: 3px;"></i>
                                    Cek Portofolio

                                  </a>

                                  <!-- Modal Cek Portofolio -->
                                  <div id="ModalCekPortofolio<?php echo $no?>" class="modal fade" role="dialog" tabindex="-1">


                                    <!-- Modal Dialog -->
                                    <div class="modal-dialog modal-lg">


                                      <!-- Modal content-->
                                      <div class="modal-content">


                                        <!-- Modal Header -->
                                        <div class="modal-header">

                                          <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        </div>
                                        <!-- End Modal Header -->


                                        <!-- Modal body -->
                                        <div class="modal-body mb-0 p-0">

                                          <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">

                                          <iframe class="embed-responsive-item" style="height: 100%; width: 100%;" 
                                          src="uploads\Asesi\<?php echo $pribadi['id_asesi']?>\Portofolio\<?php echo $portofolio['portofolio']; ?>"
                                          title="<?php echo $portofolio['keterangan'] ?> | <?php echo $pribadi['nama'] ?>"></iframe>
                                          </div>

                                        </div>
                                        <!-- End Modal Body -->

                                        <div class="modal-footer justify-content-center">
                                          
                                          <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal">Close</button>

                                        </div>


                                      </div>
                                      <!-- End Modal Content -->


                                    </div>
                                    <!-- End Modal Dialog -->


                                  </div>
                                  <!-- End Modal Cek Portofolio -->

                                </td>
                                
                                <td>

                                  <div>

                                    <select name="penilaian_portofolio[<?php echo $no ?>]" 
                                      id="penilaian_portofolio[<?php echo $no ?>]" required class="form-control">
                                      <option value="" selected disabled hidden>Silahkan Pilih</option>
                                      <option value="Benar dan Valid">Dokumen Benar dan Valid</option>
                                      <option value="Salah">Dokumen Salah</option>
                                    </select>

                                    <input type="hidden" name="id_portofolio[<?php echo $no ?>]"
                                    value="<?php echo $portofolio['id_portofolio'] ?>"/>

                                  </div>

                                </td>

                              
                              </tr>
                              
                              
                              <?php
                              $no = $no +1; }
                              ?>

                            </tbody>

                          </table>

                        </div>
                      </div>


                      <!-- Tombol Tutup -->
                      <div class="form-group">


                        <div class="form-row col-md-12">
                          <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon">
                            <h4 class="panel-footer with-border">
                            
                              <center><i class="fa fa-arrow-up"> Tutup </i></center>
                            
                            </h4>
                          </a>
                        </div>


                      </div>
                      <!-- End Tombol Tutup -->


                    </div>


                    

                  </div>
                  <!-- End Isi -->


              </div>
              <!-- End Panel Portofolio -->
            



          </div>


          <!-- Box Kiri -->
          <div class="col col-lg-5">

            <!-- Panel -->
            <div class="panel-group" id="accordion2">





              <!-- Panel Penilaian -->
              <div class="panel box box-success">

                <!-- Judul -->
                <div class="panel-heading">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#Penilaian">
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-star"> Penilaian </i>
                      
                    </h4>
                  </a>
                </div>
                <!--End Judul  -->

                <!-- Isi -->
                <div id="Penilaian" class="panel-collapse panel-multi-collapse collapse in">


                  <div class="panel-body">

                    
                    <!-- Rekomendasi LSP -->
                    <div class="form-group form-row col-md-12">

                      

                        <label for="rekomendasi">Rekomendasi LSP</label> <br>

                        <small class='form-text text-muted'>
                        Berdasarkan persyaratan dasar pemohon, kandidat dapat :
                        </small>

                        <select name="rekomendasi" id="rekomendasi" required 
                          class="form-control" onclick="cek_rekomendasi()">

                          <option value="" selected hidden>Pilih Salah Satu</option>
                          <option value="diterima">Diterima</option>
                          <option value="tidak diterima">Ditolak</option>

                        </select>

                        <small class='form-text text-muted'>
                        Sebagai Asesi
                        </small>
                      

                    </div>


                    <script type="text/javascript">
                    
                      function cek_rekomendasi () {
                      
                        var rekomendasi = document.getElementById("rekomendasi").value;

                        if ( rekomendasi == 'diterima') {
                          document.getElementById('assign_asesor').style.display = 'block';
                          document.getElementById('option_assign_asesor').selectedIndex = "0";
                          document.getElementById('option_assign_asesor').setAttribute('required' , '""');
                        }

                        else {
                          document.getElementById('assign_asesor').style.display = 'none';
                        }

                      }
                    
                    
                    </script>



                    <style>
                      .comment-box {
                          width: 100%;
                          padding: 12px;
                          border: 1px solid #ccc;
                          border-radius: 4px;
                          resize: vertical; }

                    </style>


                    <!-- Catatan -->
                    <div class="form-group form-row col-md-12">

                      <label for="catatan">Catatan</label>

                        <textarea class="form-control comment-box" 
                        id="catatan" name="catatan"></textarea> 
                    
                  
                    </div>


                    <!-- Rekomendasi LSP -->
                    <div id="assign_asesor" class="form-group form-row col-md-12" style="display: none;">

                    
                      <label for="assign_asesor">Asesor</label> <br>


                      <select name="assign_asesor" id="option_assign_asesor" class="form-control">

                        <option id="assign_default" value="" selected hidden>Pilih Asesor</option>

                        <?php
                        
                        $sql_listasesor = $conn->query("SELECT `asesor`.`nomor`, `skema_asesor`.`id_skema` , `skema_sertifikasi`.`nama_skema`, `skema_asesor`.`id_asesor` , `akun`.`nama` FROM `skema_asesor` , `skema_sertifikasi` , `akun` ,`asesor`
                        WHERE skema_asesor.id_asesor = asesor.id_asesor AND asesor.email = akun.email AND `skema_sertifikasi`.`id_skema` = `skema_asesor`.`id_skema` AND `skema_asesor`.`id_skema` = '$id_skema' ");
                        while( $list_asesor = mysqli_fetch_assoc($sql_listasesor) ) {
                        
                        ?>

                          <option value="<?php echo $list_asesor['id_asesor'] ?>"><?php echo $list_asesor['nomor'] ?> | <?php echo $list_asesor['nama'] ?> </option>
                        
                        <?php
                        }
                        ?>




                      </select>
                    

                    </div>



                    <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
                    <input type="hidden" name="id_asesi" value="<?php echo $pribadi['id_asesi']?>" />


                    <div class="from-group form-row col-md-12">

                      <div class="btn btn-primary btn-block"
                      data-toggle="modal" data-target="#ModalKonfirmasi">Simpan</div>

                    </div>

                    <!-- Modal Cek Syarat Dasar 1 -->
                    <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


                      <!-- Modal Dialog -->
                      <div class="modal-dialog modal-lg">


                        <!-- Modal content-->
                        <div class="modal-content">


                          <!-- Modal Header -->
                          <div class="modal-header">

                            <center>
                            <h4 class="modal-title">Konfirmasi Asesmen Asesi</h4> </center>

                          </div>
                          <!-- End Modal Header -->


                          <!-- Modal body -->
                          <div class="modal-body">

                            <center>
                              <h3>
                                <strong>Simpan data permohonan asesmen asesi <?php echo $pribadi['nama']?> ?</strong>
                              </h3>
                              <h4>Pastikan data yang Anda masukkan benar !</h4>
                            </center>

                          </div>
                          <!-- End Modal Body -->

                                                <!-- Modal body -->
                      <div class="modal-body">

                        <div class="form-group">
                        
                          <label class="label-control">Password</label>
                          <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                          <small id='passwordHelpBlock' 
                          class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi permohonan.</small>
                        
                        </div>


                      </div>

                          <div class="modal-footer justify-content-center">
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Simpan</button>

                          </div>


                        </div>
                        <!-- End Modal Content -->


                      </div>
                      <!-- End Modal Dialog -->


                    </div>
                    <!-- End Modal Cek Syarat Dasar 1 -->




                  </div>
                  <!-- End Isi -->


                </div>
                <!-- End Panel Penilaian -->



              </div>

            

            </div>

          </form>


        </div>


      </section>
      <!-- End Main content -->


    </div>
    <!-- End Content Wrapper -->


    <!-- Footer -->
    <?php
      include('footer.php');
    ?>
    <!-- End Footer -->


  </div>
  <!-- End Site Wrapper -->


  <!-- Input Script -->


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


      if (document.documentElement.clientWidth < 780) {

        $('.btn-loading').on('click', function(){

          // $('.loading').html("<div class='hidden-lg' style='text-align:center;'><i class='fa fa-spinner fa-4x faa-spin animated text-primary' style='margin-top:100px;'></i></div>");
          $('.sidebar-mini').removeClass('sidebar-open');

        });

        $('.box-penjelasan').addClass('collapsed-box');
        $('.box-minus').removeAttr('style');
        $('.header-profile').removeAttr('style');

      }


      $('.submit').on('click', function(){
        $('.submit').html("<i class='fa fa-spinner faa-spin animated' style='margin-right:5px;'></i> Loading...");
        $('.submit').attr('style', 'cursor:not-allowed;pointer-events: none;');
      });


      function openCustomerSupport() {
        if (typeof AndroidNative !== 'undefined') {
          AndroidNative.openCustomerSupport();
        }
      }


      function notReady() {
        toastr.error("Halaman tidak dapat di akses, produk ini masih dalam tahap pengembangan.");
      }


    </script>


    <script>


      $('#filter-web').on('click', function(){
        var filterWeb = $('.filter-web').val();
        getDeposit(filterWeb);
        $('#load tbody').css('color', '#dfecf6');
      });


      $('#filter-mobile').on('click', function(){
        var filterMobile = $('.filter-mobile').val();
        getDeposit(filterMobile);
        $('#load tbody a').css('color', '#dfecf6');
      });


      setInterval(function() {
          $('.refresh').html("<i class='fa fa-refresh faa-spin animated'></i>");
          var filter = '';
          getDeposit(filter);
      }, 60000);


      $('.refresh').on('click', function(){
          $('.refresh').html("<i class='fa fa-refresh faa-spin animated'></i>");
          var filter = '';
          getDeposit(filter);
          $('#load tbody').css('color', '#dfecf6');
          $('#load tbody a').css('color', '#dfecf6');
      });


      $(function() {
          $('body').on('click', '.pagination a', function(e) {
              e.preventDefault();

              $('#load tbody').css('color', '#dfecf6');
              $('#load tbody a').css('color', '#dfecf6');
              // $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

              var url = $(this).attr('href');  
              getDeposit(url);
              // window.history.pushState("", "", url);
              
          });
      });


    </script>


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

  <!-- End Input Script -->


</body>


</html>
