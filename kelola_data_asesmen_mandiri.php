<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start(); include 'config.php';

$no_hp  = $_SESSION['no_hp'];
$nama   = $_SESSION['nama'];

$token_verif = $_SESSION['token_verif'];

$status_akun  = $_SESSION['status_akun'];

// Cek Login
$token    = $_SESSION['token'];
  $role   = $_SESSION['role'];
  $email  = $_SESSION['email'];

  // Cek Login
  if ( !isset( $_SESSION['token'] ) || empty( $_SESSION['token'] ) ||
      !isset( $token ) || empty( $token ) || $token != $_SESSION['token'] ||

      !isset( $_SESSION['role'] ) || empty( $_SESSION['role'] ) ||
      !isset( $role ) || empty( $role ) || $role != 'Asesor' ||
      $_SESSION['role'] != 'Asesor' ) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

  };

// End Cek Login
$id_asesor = $_SESSION['id_asesor'];


// Get Permohonan
$id_permohonan = $_GET['id_permohonan'];

$cek_am = $conn->query("SELECT `id_asesor` FROM `asesmen_mandiri` WHERE `id_permohonan` = '$id_permohonan'");

if ( mysqli_num_rows($cek_am) == 1 ){

  echo "

  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>

  <script type='text/javascript'>

  setTimeout(function () { 
    swal({
          title: 'Tervalidasi',
          text:  'Anda telah melakukan validasi Asesmen Mandiri !',
          icon: 'warning',
          timer: 1500,
          showConfirmButton: true
        });  
  },10); 
  window.setTimeout(function(){ 
    window.location.replace('index.php?page=lihat_data_permohonan&&id_permohonan=$id_permohonan');
  } ,1500); 

  </script>";  

  exit;
}


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Kelola Data Asesmen Mandiri</title>
  
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
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">SI-LSP</span>
        <!-- logo for regular state and mobile devices -->
        <img src="img/logo.png" height="500%" class="rounded" alt="SI-LSP">
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
          <?php 
                      
                      $ekstensi = explode( '.', $foto_profil );
                      $ekstensi =  $ekstensi[1];
                    
                    
                    if ( is_null($foto_profil) || $ekstensi !== 'jpg' && $ekstensi !== 'png' ) { ?>

                    <img src="./uploads/Foto Profil/default.png" class="img-circle" alt="User Image">
                    <?php
                    } else {?> 
                    <img src="./uploads/Foto Profil/<?php echo $foto_profil?>" class="img-circle" alt="User Image">

                    <?php }
                    ?>

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


    <?php

    $sql_asesi = $conn->query("SELECT `permohonan`.`id_asesi`, `akun`.`nama` FROM `permohonan` , `akun`, `asesi` 
    WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `asesi`.`email` = `akun`.`email` AND `permohonan`.`id_permohonan` = '$id_permohonan'");
    $sql_asesi = mysqli_fetch_assoc($sql_asesi);
    $id_asesi = $sql_asesi ['id_asesi'];
    $nama_asesi = $sql_asesi ['nama'];

    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">



      <!-- Main content -->
      <section class="content">
      
      <?php
        $sql = $conn->query("SELECT `permohonan`.`id_asesi` , `asesmen_mandiri`.`file_APL_02` FROM `permohonan` , `asesmen_mandiri`  WHERE `asesmen_mandiri`.`id_permohonan` = `permohonan`.`id_permohonan` AND `permohonan`.`id_permohonan` = '$id_permohonan';");
        $data      = mysqli_fetch_assoc($sql); $id_asesi = $data['id_asesi']; $file_APL_02 = $data['file_APL_02'];
      ?>


        <!-- Input addon -->
        <div class="box box-widget" style="margin-bottom: 10px;background-color: #FDFDFD">


          <div class="box-body">


            <h4 style="margin-bottom: 0px;margin-top: 0px;" class="pull-left text-primary">
              <i class="fa fa-info-circle" style="margin-right: 6px;"></i>
              Data Asesmen Mandiri Asesi <?php echo $id_asesi?> | <?php echo $nama_asesi ?>
            </h4>

            <h4 style="margin-bottom: 0px;margin-top: 0px;" class="pull-right text-primary">
              <a href="./uploads/Asesi/<?php echo $id_asesi?>/<?php echo $id_permohonan?>/<?php echo $file_APL_02?>" target="_blank"><i class="fa fa-file" style="margin-right: 6px;"></i>
              Lihat File APL-02 </a>
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
          <div class="col col-md-7 panel-group">


              <!-- Panel Data Sertifikasi -->
              <div class="panel box">


                  <!-- Judul Panel -->
                  <div class="panel-heading">
                    
                      <h4 class="panel-title with-border">
                        
                        <i class="fa fa-user"> Data Sertifikasi </i>
                        
                      </h4>

                  </div>
                  <!--End Judul Panel-->


                  <!-- Isi -->
                  <div class="panel-collapse collapse in">


                    <div class="panel-body">

                      <?php
                        $sql=$conn->query("SELECT `skema_sertifikasi`.`nama_skema`, `skema_sertifikasi`.`id_skema` , 
                        `skema_sertifikasi`.`nomor_skema` , `permohonan`.`id_asesi` FROM `skema_sertifikasi` , `permohonan` 
                        WHERE `skema_sertifikasi`.`id_skema` = `permohonan`.`id_skema`
                        AND  `permohonan`.`id_permohonan` = '$id_permohonan'"); 
                        $data = mysqli_fetch_assoc($sql); $id_skema = $data['id_skema']; $id_asesi = $data['id_asesi'];

                        
                      ?>


                      <!-- Tujuan Asesmen -->
                      <div class="form-group">

                        <label>Nomor Skema</label>

                        <input disabled type="text" class="form-control" value="<?php echo $data['nomor_skema']?>" />

                      </div>


                      <!-- Tujuan Asesmen -->
                      <div class="form-group">

                        <label>Judul Skema</label>

                        <input disabled type="text" class="form-control" value="<?php echo $data['nama_skema']?>" />

                      </div>


                    </div>


                  </div>
                  <!-- End Isi -->


              </div>
              <!-- End Panel Data Sertifikasi -->

              <?php

                $sql_AM = $conn->query("SELECT `id_asesmen_mandiri` FROM `asesmen_mandiri` WHERE `id_permohonan` = '$id_permohonan' ");
                $id_AM = mysqli_fetch_assoc($sql_AM) ['id_asesmen_mandiri'];

              ?>


              <?php 
                $sql_unit = $conn->query("SELECT * FROM `unit_skema` WHERE `id_skema` = '$id_skema'  ORDER BY `id_unit` ASC ;");
                $no = 1; 
                  
                while ( $unit_skema = mysqli_fetch_assoc($sql_unit) ) { 
                  $id_unit =  $unit_skema['id_unit']; 
              ?>


              <!-- Panel Data KUK -->
              <div class="panel box">


                  <!-- Judul -->
                  <div class="panel-heading" style="color: white">
                    <a data-toggle="collapse" data-parent="#accordion" 
                    href="#DataSertifikasi<?php echo $no ?>">
                      <h4 class="panel-title with-border">
                        
                        <i class="fa fa-user"> Unit Kompetensi <?php echo $no ?> | <?php echo $unit_skema['judul_unit']?>  </i>
                        
                      </h4>
                    </a>
                  </div>
                  <!--End Judul  -->


                <!-- Isi -->
                <div id="DataSertifikasi<?php echo $no ?>" class="panel-collapse collapse in">

                  <div class="panel-body box-body">

                    <div>
                      <table class="table table-bordered">
                        <tr>
                          <th width="20%"scope="row">Kode Unit</th>
                          <td width="60%"><?php echo $unit_skema['kode_unit']?></td>
                        </tr>
                        <tr>
                          <th scope="row">Judul Unit</th>
                          <td><?php echo $unit_skema['judul_unit']?></td>
                        </tr>
                      </table>
                    </div>
                        
                    <?php

                      $sql_elemen = $conn->query("SELECT * FROM `elemen_unit` WHERE `id_unit` = '$id_unit' ");
                      $no_elemen = 1;
                      while ( $elemen_unit = mysqli_fetch_assoc($sql_elemen) ) { $id_elemen = $elemen_unit['id_elemen'];
                    ?>

                      <!-- Tarok perulangan while utk Elemen unit dari unit skema -->
                      <div class="box box-solid box-primary">

                        <div class="box-header with-border">

                          <!-- <a data-toggle="collapse" data-parent="#accordion" href="#DataPribadi"> -->
                          <h4 class="panel-title with-border"> 
                            <strong>
                          
                            Elemen Kompetensi <?php echo $no_elemen ?> </strong>
                          
                          </h4>
                          <!-- </a> -->


                        </div>

                        
                          <div class="box-body">

                            <div>
                              <table class="table table-bordered">
                                <tr>
                                  <th width="20%" scope="row">Elemen Kompetensi</th>
                                  <td width="60%"><?php echo $elemen_unit['elemen']?></td>
                                </tr>
                          
                              </table>
                            </div>

                            <table class="table table-bordered">
                              
                              <thead>
                                <tr>
                                  <th width="20%">Nomor KUK</th>
                                  <th width="40%">Daftar Pertanyaan (Asesmen Mandiri)</th>
                                  <th width="20%">Penilaian</th>
                                </tr>
                              </thead>
                              
                              <tbody>
                              
                                <?php

                                $sql_kuk = $conn->query("SELECT * FROM `kriteria_unjuk_kerja` WHERE `id_elemen` = '$id_elemen' ");
                                $no_kuk = 1;
                                while ( $kuk = mysqli_fetch_assoc($sql_kuk) ) { 
                                $id_kuk = $kuk['id_kuk'];

                                $sql_asesmen_kuk = $conn->query("SELECT `asesmen_mandiri_kuk`.`kompetensi` FROM `asesmen_mandiri` ,`asesmen_mandiri_kuk` WHERE
                                `asesmen_mandiri`.`id_asesmen_mandiri` = `asesmen_mandiri_kuk`.`id_asesmen_mandiri` AND `asesmen_mandiri`.`id_permohonan` = '$id_permohonan' AND `asesmen_mandiri_kuk`.`id_kuk` = '$id_kuk' ");
                                $asemen_kuk = mysqli_fetch_assoc($sql_asesmen_kuk); $kompetensi = $asemen_kuk['kompetensi'];

                                if ( $kompetensi == 'K' ){
                                  $kompetensi = 'Kompeten';
                                } else {
                                  $kompetensi = 'Belum Kompeten';
                                }

                                ?>

                                <tr>
                                
                                  <td><?php echo $kuk['no_kuk']?>
                                  </td>

                                  <td><?php echo $kuk['kuk']?></td>


                                  <td>

                                    <input type="text" class="form-control" disabled readonly value="<?php echo $kompetensi?>" />

                                  </td>


                                </tr>
                                
                                <?php
                                $no_kuk = $no_kuk + 1;
                                }
                                
                                ?>

                              
                              
                              </tbody>

                            </table>
                          


                          </div>


                      </div>
                      
                      
                      <?php
                      $no_elemen = $no_elemen + 1 ;
                      }
                      ?>


                        <a data-toggle="collapse" data-parent="#accordion" 
                        href="#DataSertifikasi<?php echo $no ?>">
                          <h4 class="panel-footer with-border">
                          
                            <center><i class="fa fa-arrow-up"> Tutup </i></center>
                          
                          </h4>
                        </a>
                  
                    <!-- End Tombol Gulung -->


                  </div>


                </div>
                <!-- End Isi -->


              </div>
              <!-- End Panel Data KUK -->


              <?php
              
                $no = $no + 1 ; 
              
                }
              
              ?>



          </div>

          <div class="col col-md-5 panel-group">



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
                              </tr>
                            </thead>

                            <tbody>

                              <?php
                              
                              $sql = $conn->query("SELECT `akun`.`nama` ,`permohonan`.`id_asesi` , `portofolio`.`portofolio` , `portofolio`.`keterangan` 
                              FROM `portofolio` , `permohonan` ,`asesi`, `akun` WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `asesi`.`email` = `akun`.`email` AND `permohonan`.`id_permohonan` = `portofolio`.`id_permohonan` AND `permohonan`.`id_permohonan` = '$id_permohonan' ");
                              
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
                                          src="uploads\Asesi\<?php echo $portofolio['id_asesi']?>\Portofolio\<?php echo $portofolio['portofolio']; ?>"
                                          title="<?php echo $portofolio['keterangan'] ?> | <?php echo $portofolio['nama'] ?>"></iframe>
                                          </div>

                                        </div>
                                        <!-- End Modal Body -->

                                        <div class="modal-footer justify-content-center">
                                          
                                          <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal">Tutup</button>

                                        </div>


                                      </div>
                                      <!-- End Modal Content -->


                                    </div>
                                    <!-- End Modal Dialog -->


                                  </div>
                                  <!-- End Modal Cek Portofolio -->

                                </td>
                                

                              
                              </tr>
                              
                              
                              <?php
                              $no = $no +1; }
                              ?>

                            </tbody>

                          </table>

                        </div>
                      </div>


                      <!-- Tombol Gulung -->
                      <div class="form-group">


                        <div class="form-row col-md-12">
                          <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon">
                            <h4 class="panel-footer with-border">
                            
                              <center><i class="fa fa-arrow-up"> Tutup </i></center>
                            
                            </h4>
                          </a>
                        </div>


                      </div>
                      <!-- End Tombol Gulung -->


                    </div>


                    

                  </div>
                  <!-- End Isi -->


              </div>
              <!-- End Panel Portofolio -->

            <form action="proses_asesor.php?aksi=<?php echo base64_encode('validasi_asesmen_mandiri')?>" name="validasi_asesmen_mandiri" 
              id="validasi_asesmen_mandiri" method="post">


              <!-- Panel Data Sertifikasi -->
              <div class="panel box">


                  <!-- Judul Panel -->
                  <div class="panel-heading">
                    
                      <h4 class="panel-title with-border">
                        
                        <i class="fa fa-user"> Validasi Asesor </i>
                        
                      </h4>

                  </div>
                  <!--End Judul Panel-->


                  <!-- Isi -->
                  <div class="panel-collapse collapse in">


                    <div class="panel-body">


                      <!-- Tujuan Asesmen -->
                      <div class="form-group">

                        <label>Rekomendasi</label>

                        <select class="form-control" name="rekomendasi_asesor">
                        
                          <option value="" selected disabled hidden>Silahkan Pilih</option>
                          <option value="Lanjut uji kompetensi">Lanjut uji kompetensi</option>
                          <option value="Belum dapat lanjut uji kompetensi">Belum dapat lanjut uji kompetensi</option>
                        
                        </select>

                      </div>


                    </div>


                  </div>
                  <!-- End Isi -->


              </div>
              <!-- End Panel Data Sertifikasi -->


              <!-- Button Validasi Asesmen Mandiri -->
              <div class="panel box">

                <div class="box-body">


                  <div class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalKonfirmasi"  data-toogle="tooltip" data-placement="top" title="Lanjut Asesmen Mandiri">
                      Validasi Asesmen Mandiri
                    </div>


                  </div>


                </div>

              </div>

                <!-- Input hidden id_asesi , token , role -->
                <input type="hidden" value="<?php echo $id_asesor;?>" name="id_asesor" />
                <input type="hidden" value="<?php echo $token; ?>" name="token" />
                <input type="hidden" value="<?php echo $role; ?>" name="role" />
                <!-- End input hidden -->

                <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
                <input type="hidden" name="id_AM" value="<?php echo $id_AM?>"/>

                <!-- Modal Cek Konfirmasi -->
                <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


                  <!-- Modal Dialog -->
                  <div class="modal-dialog modal-lg">


                    <!-- Modal content-->
                    <div class="modal-content">


                      <!-- Modal Header -->
                      <div class="modal-header">

                        <center>
                        <h4 class="modal-title">Konfirmasi Validasi Asesmen Mandiri</h4>
                        </center>

                      </div>
                      <!-- End Modal Header -->


                      <!-- Modal body -->
                      <div class="modal-body">

                        <center>
                          <h3>
                            <strong>Yakin ingin melakukan Validasi Asesmen Mandiri ?</strong>
                          </h3>
                          <h4>Pastikan data yang Anda masukkan benar, karena Anda tidak bisa melakukan perubahan data !</h4>
                        </center>

                      </div>
                      <!-- End Modal Body -->


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
                        <button type="submit" class="btn btn-danger">Validasi Asesmen Mandiri</button>

                      </div>


                    </div>
                    <!-- End Modal Content -->


                  </div>
                  <!-- End Modal Dialog -->


                </div>
                <!-- End Modal Cek Konfirmasi -->

            </form>
          
          
          </div>





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
