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

$id_permohonan = $_GET['id_permohonan'];

$id_unit = $_GET['id_unit'];

$id_mapa = $_GET['id_mapa'];


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Validasi MAPA</title>
  
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
  <script src="bower_components/bootstrap/js/popover.js"></script>
  <script src="bower_components/bootstrap/dist/bootstrap.bundle.min.js"></script>
  
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
                  <a href="index.php?page=pengaturan_akun" class="btn-loading">
                    <i class="fa fa-user"></i>
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
            <a href="#"><?php echo $role ?></a>
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



      <!-- Main content -->
      <section class="content">

        <!-- Page header -->
        <h2 class="page-header">Form Merencanakan Asesmen dan Proses Asesmen (MAPA)

          <small>Form berdasarkan Form MAPA 01</small>

        </h2>
        <!-- End Page header -->


        <!-- Peralihan -->
        <div class="row">
          <div class="panel-group col col-md-12">
            <div class="panel box" >

              <!-- Judul -->
              <div class="panel-heading box-header with-border text-center" >
                <!-- <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon"> -->
                  <h4 class="panel-title with-border">

                    <i class="fa fa-user"> Data Asesi, Sertifikasi dan Unit Kompetensi </i>

                  </h4>
                <!-- </a> -->
              </div>

            </div>
          </div>
        </div>


        <!-- Row Data Asesi dan Sertifikasi dan Data Unit Kompetensi -->
        <div class="row">


          <!-- Box Kanan -->
          <div class="panel-group col col-md-6">

            <?php
            
              $sql = $conn->query("SELECT `akun`.`nama` , `skema_sertifikasi`.`nama_skema` , `skema_sertifikasi`.`nomor_skema` FROM `permohonan` , `akun` , `skema_sertifikasi` , `asesi`
              WHERE `permohonan`.`id_skema` = `skema_sertifikasi`.`id_skema` AND `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `asesi`.`email` = `akun`.`email` AND `permohonan`.`id_permohonan` = '$id_permohonan'");

              $data_sertifikasi = mysqli_fetch_assoc($sql);
            ?>

            <!-- Panel Data Asesi dan Sertifikasi -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Data Asesi dan Data Sertifikasi </i>
                      
                    </h4>

                </div>
                <!--End Judul Panel-->


                <!-- Isi -->
                <div class="panel-collapse">


                  <div class="panel-body">


                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label>Nama Asesi</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_sertifikasi['nama'] ?>" />

                    </div>


                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label>Judul Skema</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_sertifikasi['nama_skema'] ?>" />

                    </div>



                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label>Nomor Skema</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_sertifikasi['nomor_skema'] ?>" />

                    </div>





                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Asesi dan Sertifikasi -->


          </div>

          <!-- Box Kanan -->
          <div class="panel-group col col-md-6">

            <?php
            
              $sql = $conn->query("SELECT * FROM `unit_skema`
              WHERE `id_unit` = '$id_unit'");

              $data_unit = mysqli_fetch_assoc($sql);
            ?>
            

            <!-- Panel Data Unit -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Data Unit Kompetensi </i>
                      
                    </h4>

                </div>
                <!--End Judul Panel-->


                <!-- Isi -->
                <div class="panel-collapse">


                  <div class="panel-body">


                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label>Unit Kompetensi</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_unit['judul_unit']?>" />

                    </div>

                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label>Kode Unit</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_unit['kode_unit']?>" />

                    </div>


                    <!-- Jenis Standar -->
                    <div class="form-group">

                      <label>Jenis Standar</label>

                      <input disabled type="text" class="form-control" value="<?php echo $data_unit['jenis_standar']?>" />

                    </div>




                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Unit -->


          </div>





        </div>



        <!-- Peralihan -->
        <div class="row">
          <div class="panel-group col col-md-12">
            <div class="panel box" >

              <!-- Judul -->
              <div class="panel-heading box-header with-border text-center" >
                <!-- <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon"> -->
                  <h3 class="box-title with-border">

                    <i class="fa fa-user"> Pendekatan Asesmen </i>

                  </h3>

                  <a href="#" id="portofolio" class="fa fa-question-circle pull-right fade in" data-toggle="modal" data-target="#PanduanPortofolio"></a>
                <!-- </a> -->
              </div>

            </div>
          </div>
        </div>


        <!-- Pendekatan Asesmen -->
        <div class="row">


          <!-- Box Kanan -->
          <div class="panel-group col col-md-12">



            <!-- <form action="tes.php" name="tambah_mapa" 
            id="tambah_mapa" method="post" enctype="multipart/form-data"> -->


            <!-- Panel Pendekatan Asesmen -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Pendekatan Asesmen </i>
                      
                    </h4>

                </div>
                <!--End Judul Panel-->

                <?php
                
                $mapa = $conn->query("SELECT * FROM `mapa` WHERE `id_mapa` = '$id_mapa'");
                $d = mysqli_fetch_assoc($mapa);
                
                ?>


                <!-- Isi -->
                <div class="panel-collapse">


                  <div class="panel-body">


                    <!-- Kandidat -->
                    <div class="form-group">

                      <label class="label-control">Kandidat</label>

                      <select class="form-control" name="kandidat" disabled>


                        <option selected><?php echo $d['kandidat']?></option>


                      </select>


                    </div>



                    <!-- Tujuan -->
                    <div class="form-group">

                      <label class="label-control">Tujuan Asesmen</label>

                      <select class="form-control" name="tujuan_asesmen" disabled>


                        <option selected><?php echo $d['tujuan_asesmen']?></option>


                      </select>


                    </div>


                    <!-- Konteks Asesmen -->
                    <div class="form-group">

                      <label class="label-control">Konteks Asesmen</label>

                      <div class="panel-body box box-solid box-info">


                        <!-- Lingkungan -->
                        <div class="form-group">

                          <label class="label-control">Lingkungan</label>

                          <select class="form-control" disabled>

                            <option selected><?php echo $d['lingkungan'] ?></option>

                          </select>


                        </div>



                        <!-- Peluang bukti -->
                        <div class="form-group">

                          <label class="label-control">Peluang untuk mengumpulkan bukti dalam sejumlah situasi</label>

                          <select class="form-control"  disabled>

                            <option selected><?php echo $d['peluang']?></option>

                          </select>


                        </div>


                        <!-- Hubungan standar kompetensi -->
                        <div class="form-group">

                          <label class="label-control">Hubungan antara standar kompetensi dan :</label>

                          <select class="form-control" disabled>

                            <option selected><?php echo $d['hubungan_standar']?></option>

                          </select>


                        </div>


                        <div class="form-group">

                          <label class="label-control">Komentar Hubungan :</label>

                          <select class="form-control" disabled>

                          <?php
                          
                            if ( $d['komentar_hubungan_standar'] == 1 ) {

                              $komentar = 'Bagus';
                            }

                            if ( $d['komentar_hubungan_standar'] == 2 ) {

                              $komentar = 'Biasa Saja';
                            }

                            if ( $d['komentar_hubungan_standar'] == 3 ) {

                              $komentar = 'Buruk';
                            }
                          
                          ?>

                            <option selected hidden><?php echo $komentar ?></option>
                          </select>


                        </div>


                        <!-- Siapa yang melakukan asesmen -->
                        <div class="form-group">

                          <label class="label-control">Siapa yang melakukan asesmen / RPL</label>

                          <select class="form-control" disabled>

                            <option value="" selected hidden><?php echo $d['pelaku_asesmen']?></option>

                          </select>


                        </div>

              

                      </div>

                    </div>


                    <!-- Orang yang relevan -->
                    <div class="form-group">

                      <label class="label-control">Orang yang relevan untuk dikonfirmasi</label>

                      <select class="form-control" disabled>


                        <option value="" selected hidden><?php echo $d['konfirmasi'] ?></option>

                      </select>


                    </div>



                    <!-- Tolok ukur asesmen -->
                    <div class="form-group">

                      <label class="label-control">Tolok ukur asesmen</label>

                      <select class="form-control" disabled>


                        <option value="" selected hidden><?php echo $d['tolok_ukur']?></option>

                      </select>

                    </div>



                    <!-- Nama Tolok ukur asesmen -->
                    <div class="form-group">

                        <label class="label-control">Dasar Peraturan Tolok ukur asesmen</label>

                        <input disabled class="form-control" type="text" value="<?php echo $d['tolok_ukur_nama']?>"/>


                    </div>



                  </div>




                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Sertifikasi -->


          </div>





        </div>


        <!-- Peralihan -->
        <div class="row">
          <div class="panel-group col col-md-12">
            <div class="panel box" >

              <!-- Judul -->
              <div class="panel-heading box-header with-border text-center" >
                <!-- <a data-toggle="collapse" data-parent="#accordion" href="#KelengkapanPemohon"> -->
                  <h3 class="box-title with-border">

                    <i class="fa fa-user"> Modifikasi dan Kontekstualisasi </i>

                  </h3>

                  <a href="#" id="portofolio" class="fa fa-question-circle pull-right fade in" data-toggle="modal" data-target="#PanduanPortofolio"></a>
                <!-- </a> -->
              </div>

            </div>
          </div>
        </div>


        <!-- Pendekatan Asesmen -->
        <div class="row">


          <!-- Box Kanan -->
          <div class="panel-group col col-md-12">


            <!-- Panel Pendekatan Asesmen -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Modifikasi dan Kontekstualisasi </i>
                      
                    </h4>

                </div>
                <!--End Judul Panel-->


                <!-- Isi -->
                <div class="panel-collapse">


                  <div class="panel-body">


                    <!-- Kandidat -->
                    <div class="form-group">

                      <label class="label-control">Karakteristik Kandidat</label>

                      <input type="text" class="form-control" value="<?php echo $d['karakteristik_kandidat']?>" disabled />


                    </div>



                    <!-- Kebutuhan Kontekstualisasi -->
                    <div class="form-group">

                      <label class="label-control">Kebutuhan Kontekstualisasi</label>

                      <input type="text" class="form-control" value="<?php echo $d['kebutuhan_kontekstualisasi']?>" disabled/>


                    </div>



                    <!-- Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan -->
                    <div class="form-group">

                      <label class="label-control">Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan</label>

                      <input type="text" class="form-control" value="<?php echo $d['saran'] ?>" disabled  />


                    </div>



                    <!-- Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen -->
                    <div class="form-group">

                      <label class="label-control">Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</label>

                      <input type="text" class="form-control" value="<?php echo $d['peluang']?>" disabled />


                    </div>




                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Sertifikasi -->




          </div>





        </div>


        <!-- Peralihan -->
        <div class="row">
          <div class="panel-group col col-md-12">
            <div class="panel box" >

              <!-- Judul -->
              <div class="panel-heading box-header with-border text-center">
              
                
                  <h3 class="box-title with-border">

                    <i class="fa fa-file"> Rencana Asesmen </i>

                  </h3>

                  <a href="#" id="portofolio" class="fa fa-question-circle pull-right fade in" data-toggle="modal" data-target="#PanduanPortofolio"></a>
                
              </div>

            </div>
          </div>
        </div>


        <!-- Rencana Asesmen -->
        <div class="row">


          <!-- Box Kanan -->
          <div class="panel-group col col-md-12">

            <?php
            
              $sql1 = $conn->query("SELECT * FROM `elemen_unit` WHERE `id_unit` = '$id_unit' ");

              $no = 1;
              while ( $data1 = mysqli_fetch_assoc($sql1) ) {

                $id_elemen = $data1['id_elemen'];
            
            ?>

            <div class="panel box">

                  <!-- Judul -->
                  <div class="panel-heading box-header with-border" style="color: white">
                    <a data-toggle="collapse" data-parent="#accordion" href="#RencanaAsesmenKUK<?php echo $data1['no_elemen']?>">
                      <h4 class="box-title with-border">
                        
                        <i class="fa fa-file"> Elemen <?php echo $data1['no_elemen']?>: <?php echo $data1['elemen']?> </i>
                        
                      </h4>
                    </a>
                  </div>
                  <!--End Judul  -->

                  <div id="RencanaAsesmenKUK<?php echo $data1['no_elemen']?>"class="panel-collapse panel-body box-body collapse in">


                    <?php
                    
                      $sql2 = $conn->query("SELECT * FROM `kriteria_unjuk_kerja` WHERE `id_elemen` = '$id_elemen' ");

                      while ( $data2 = mysqli_fetch_assoc($sql2) ) {
                        $id_kuk = $data2['id_kuk'];
                    
                    ?>



                      <!-- Tarok perulangan while utk Elemen unit dari unit skema -->
                      <div class="box box-solid box-primary">

                        <div class="box-header with-border">

                          <!-- <a data-toggle="collapse" data-parent="#accordion" href="#DataPribadi"> -->
                          <h4 class="box-title with-border"> 
                            <strong>Kriteria Unjuk Kerja</strong>
                          </h4>

                          <a href="#" class="fa fa-question-circle pull-right" type="button" data-toggle="modal" data-target="#PanduanRencanaAsesmen"></a>
                    
                          <!-- </a> -->

                          

                        </div>

                            <div class="box-body">
                              <table class="table table-bordered">
                                <tr>
                                  <th width="30%"scope="row">No KUK</th>
                                  <td width="70%"><?php echo $data2['no_kuk']?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Kriteria Unjuk Kerja</th>
                                  <td><?php echo $data2['kuk']?></td>
                                </tr>
                              </table>
                            </div>


                            <div class="box-body">

                              <table class="table table-bordered">
                                
                                <thead>
                                  <tr>
                                    <th width="40%">Bukti - Bukti</th>
                                    <th width="20%">Jenis Bukti</th>
                                    <th width="20%">Metode Asesmen</th>
                                    <th width="20%">Perangkat Asesmen</th>
                                  </tr>
                                </thead>

                                <?php
                                
                                $sq = $conn->query("SELECT * FROM rencana_asesmen WHERE id_mapa = '$id_mapa' AND id_kuk = '$id_kuk' ");
                                $c = mysqli_fetch_assoc($sq);
                                
                                ?>
                                
                                <tbody>
                                

                                  <tr>
                                  

                                    <td>
                                        <input disabled type="text" value="<?php echo $c['bukti']?>" class="form-control"/>

                                    </td>

                                    <td>


                                        <select disabled class="form-control">
                                          <option selected hidden><?php echo $c['jenis_bukti']?></option>
                                        </select>

                                    </td>

                                    <td>

                                        <select disabled class="form-control">
                                          
                                          <option value="" selected disabled hidden><?php echo $c['metode_asesmen']?></option>

                                        </select>

                                    </td>

                                    <!-- Kalau pilih lainnya muncul kotak untuk input sesuatu -->




                                    <td>


                                        <select disabled class="form-control">
                                          
                                          <option selected disabled hidden><?php echo $c['perangkat_asesmen']?></option>
                                          

                                        </select>


                                    </td>


                                  </tr>

                                
                                
                                </tbody>

                              </table>
                              
                            </div>
                          



                      </div>
                      

                    <?php
                    
                      }

                    ?>


                        <a data-toggle="collapse" data-parent="#accordion" 
                        href="#RencanaAsesmenKUK<?php echo $data1['no_elemen']?>">
                          <h4 class="panel-footer with-border">
                          
                            <center><i class="fa fa-arrow-up"> Tutup </i></center>
                          
                          </h4>
                        </a>
                  
                    <!-- End Tombol Tutup -->


                  </div>

            </div>

            <?php
            
              $no = $no+1;
              }
            
            ?>


            <form action="proses_user_admin.php?aksi=validasi_mapa" name="validasi_mapa" 
            id="validasi_mapa" method="post" enctype="multipart/form-data">
            <!-- End Isi -->

            <div class="panel box">
              
              <div class="box-body">
              
                <div class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalKonfirmasi"  data-toogle="tooltip" data-placement="top" title="Lanjut Asesmen Mandiri">
                  Validasi MAPA
                </div>

              </div>
            
            </div>

            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_UA;?>" name="id_UA" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->

            <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
            <input type="hidden" name="id_unit" value="<?php echo $id_unit?>"/>
            <input type="hidden" name="id_mapa" value="<?php echo $id_mapa?>"/>

            <!-- Modal Cek Syarat Dasar 1 -->
            <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


              <!-- Modal Dialog -->
              <div class="modal-dialog modal-lg">


                <!-- Modal content-->
                <div class="modal-content">


                  <!-- Modal Header -->
                  <div class="modal-header">

                    <center>
                    <h4 class="modal-title">Konfirmasi Validasi MAPA</h4>
                    </center>

                  </div>
                  <!-- End Modal Header -->


                  <!-- Modal body -->
                  <div class="modal-body">

                    <center>
                      <h3>
                        <strong>Validasi MAPA ?</strong>
                      </h3>
                      <h4>Lakukan validasi MAPA</h4>
                    </center>

                  </div>
                  <!-- End Modal Body -->

                  <div class="modal-body">

                    <div class="form-group">

                      <label class="label-control">Password</label>
                      <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                      <small id='passwordHelpBlock' class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi MAPA</small>

                    </div>


                  </div>

                  <div class="modal-footer justify-content-center">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Validasi MAPA</button>

                  </div>


                </div>
                <!-- End Modal Content -->


              </div>
              <!-- End Modal Dialog -->


            </div>
            <!-- End Modal Cek Syarat Dasar 1 -->

          

          </div>





        </div>


          </form>

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
