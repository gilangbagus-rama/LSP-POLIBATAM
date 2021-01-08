<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start(); include 'config.php';


$no_hp  = $_SESSION['no_hp'];
$nama   = $_SESSION['nama'];

$token_verif = $_SESSION['token_verif'];
$status_akun  = $_SESSION['status_akun'];

$id_asesor = $_SESSION['id_asesor'];

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

$id_permohonan = $_GET['id_permohonan'];

$id_unit = $_GET['id_unit'];


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Tambah MAPA</title>
  
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


          <form action="proses_asesor.php?aksi=<?php echo base64_encode('tambah_mapa')?>" name="tambah_mapa" 
            id="tambah_mapa" method="post" enctype="multipart/form-data">
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


                <!-- Isi -->
                <div class="panel-collapse">


                  <div class="panel-body">


                    <!-- Kandidat -->
                    <div class="form-group">

                      <label class="label-control">Kandidat</label>

                      <select class="form-control" name="kandidat">


                        <option value="" selected hidden>Pilih Kandidat</option>
                        <option value="Hasil pelatihan dan / atau pendidikan">Hasil pelatihan dan / atau pendidikan</option>
                        <option value="Pekerja berpengalaman">Pekerja berpengalaman</option>
                        <option value="pelatihan / belajar mandiri">Pelatihan / belajar mandiri</option>


                      </select>


                    </div>



                    <!-- Tujuan -->
                    <div class="form-group">

                      <label class="label-control">Tujuan Asesmen</label>

                      <select class="form-control" name="tujuan_asesmen">


                        <option value="" selected hidden>Pilih Tujuan Asesmen</option>
                        <option value="Sertifikasi">Sertifikasi</option>
                        <option value="RCC">RCC</option>
                        <option value="RPL">RPL</option>
                        <option value="Hasil pelatihan / proses pembelajaran">Hasil pelatihan / proses pembelajaran</option>
                        <option value="Lainnya">Lainnya</option>


                      </select>


                    </div>


                    <!-- Konteks Asesmen -->
                    <div class="form-group">

                      <label class="label-control">Konteks Asesmen</label>

                      <div class="panel-body box box-solid box-info">


                        <!-- Lingkungan -->
                        <div class="form-group">

                          <label class="label-control">Lingkungan</label>

                          <select class="form-control" name="lingkungan">

                            <option value="" selected hidden>Pilih Lingkungan</option>
                            <option value="Tempat kerja nyata">Tempat kerja nyata</option>
                            <option value="Tempat kerja simulasi">Tempat kerja simulasi</option>

                          </select>


                        </div>



                        <!-- Peluang bukti -->
                        <div class="form-group">

                          <label class="label-control">Peluang untuk mengumpulkan bukti dalam sejumlah situasi</label>

                          <select class="form-control" name="peluang">

                            <option value="" selected hidden>Pilih Peluang</option>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Terbatas">Terbatas</option>

                          </select>


                        </div>


                        <!-- Hubungan standar kompetensi -->
                        <div class="form-group">

                          <label class="label-control">Hubungan antara standar kompetensi dan :</label>

                          <select class="form-control" name="hubungan_standar">

                            <option value="" selected hidden>Pilih Hubungan</option>
                            <option value="Bukti untuk mendukung asesmen / RPL">Bukti untuk mendukung asesmen / RPL</option>
                            <option value="Aktivitas kerja di tempat kerja kandidat: ">Aktivitas kerja di tempat kerja kandidat</option>
                            <option value="Kegiatan Pembelajaran: ">Kegiatan Pembelajaran</option>

                          </select>


                        </div>


                        <div class="form-group">

                          <label class="label-control">Komentar Hubungan :</label>

                          <select class="form-control" name="komentar_hubungan">

                            <option value="" selected hidden>Pilih Komentar Hubungan</option>
                            <option value="1">Bagus</option>
                            <option value="2">Biasa Saja</option>
                            <option value="3">Buruk</option>

                          </select>


                        </div>


                        <!-- Siapa yang melakukan asesmen -->
                        <div class="form-group">

                          <label class="label-control">Siapa yang melakukan asesmen / RPL</label>

                          <select class="form-control" name="pelaku_asesmen">

                            <option value="" selected hidden>Pilih Pelaku asesmen</option>
                            <option value="Oleh Lembaga Sertifikasi">Oleh Lembaga Sertifikasi</option>
                            <option value="Oleh Organisasi Pelatihan">Oleh Organisasi Pelatihan</option>
                            <option value="Oleh asesor perusahaan">Oleh asesor perusahaan</option>

                          </select>


                        </div>

              

                      </div>

                    </div>


                    <!-- Orang yang relevan -->
                    <div class="form-group">

                      <label class="label-control">Orang yang relevan untuk dikonfirmasi</label>

                      <select class="form-control" name="orang_konfirmasi">


                        <option value="" selected hidden>Pilih Orang untuk dikonfirmasi</option>
                        <option value="Manajer sertifikasi LSP">Manajer sertifikasi LSP</option>
                        <option value="Master Asesor / Master Trainer / Asesor Utama kompetensi">Master Asesor / Master Trainer / Asesor Utama kompetensi</option>
                        <option value="Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar">Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</option>
                        <option value="Hasil pelatihan / proses pembelajaran">Hasil pelatihan / proses pembelajaran</option>
                        <option value="Lainnya:Ketua Program Studi">Lainnya:Ketua Program Studi</option>


                      </select>


                    </div>



                    <!-- Tolok ukur asesmen -->
                    <div class="form-group">

                      <label class="label-control">Tolok ukur asesmen</label>

                      <select class="form-control" name="tolok_ukur">


                        <option value="">Pilih Tolok ukur asesmen</option>
                        <option value="Standar Kompetensi:" selected>Standar Kompetensi: </option>
                        <option value="Kriteria asesmen dari kurikulum pelatihan">Kriteria asesmen dari kurikulum pelatihan</option>
                        <option value="Spesifikasi kinerja suatu perusahaan atau industri:">Spesifikasi kinerja suatu perusahaan atau industri:</option>
                        <option value="Spesifikasi Produk:">Spesifikasi Produk:</option>
                        <option value="Pedoman khusus:">Pedoman khusus:</option>


                      </select>

                    </div>



                    <!-- Nama Tolok ukur asesmen -->
                    <div class="form-group">

                        <label class="label-control">Dasar Peraturan Tolok ukur asesmen</label>

                        <input class="form-control" type="text" name="tolok_ukur_nama" value="-" placeholder="Nomor Peraturan"/>


                    </div>



                  </div>




                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Data Sertifikasi -->


            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_asesi;?>" name="id_asesor" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->

            <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
            <input type="hidden" name="id_unit" value="<?php echo $id_unit ?>" />


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

                      <input type="text" class="form-control" name="karekteristik_kandidat" placeholder="Karakteristik Kandidat" />


                    </div>



                    <!-- Kebutuhan Kontekstualisasi -->
                    <div class="form-group">

                      <label class="label-control">Kebutuhan Kontekstualisasi</label>

                      <input type="text" class="form-control" name="kebutuhan_kontekstualisasi" placeholder="Kebutuhan Kontekstualisasi" />


                    </div>



                    <!-- Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan -->
                    <div class="form-group">

                      <label class="label-control">Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan</label>

                      <input type="text" class="form-control" name="saran" placeholder="Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan" />


                    </div>



                    <!-- Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen -->
                    <div class="form-group">

                      <label class="label-control">Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</label>

                      <input type="text" class="form-control" name="Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen" 
                      placeholder="Peluang kegiatan asesmen" />


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
                                
                                <tbody>
                                

                                  <tr>
                                  

                                    <td>
                                        <input type="text" name="bukti_bukti[<?php echo $id_kuk?>]" class="form-control" placeholder="Masukkan bukti - bukti pendekatan asesmen"/>

                                    </td>

                                    <td>


                                        <select name="jenis_bukti[<?php echo $id_kuk?>]"
                                          class="form-control">
                                          <option value="" selected disabled hidden>Pilih Jenis Bukti</option>
                                          <option value="L">Langsung</option>
                                          <option value="TL">Tidak Langsung</option>
                                          <option value="T">Tulis</option>
                                        </select>

                                    </td>

                                    <td>

                                        <select name="metode_asesmen[<?php echo $id_kuk?>]"
                                          class="form-control">
                                          
                                          <option value="" selected disabled hidden>Silahkan Pilih</option>
                                          
                                          <option value="Observasi Langsung">Observasi Langsung</option>
                                          <option value="Kegiatan Terstruktur">Kegiatan Terstruktur</option>
                                          <option value="Tanya Jawab">Tanya Jawab</option>
                                          <option value="Verifikasi Portofolio">Verifikasi Portofolio</option>
                                          <option value="Review Produk">Review Produk</option>
                                          <option value="Lainnya">Lainnya</option>

                                        </select>

                                    </td>

                                    <!-- Kalau pilih lainnya muncul kotak untuk input sesuatu -->




                                    <td>


                                        <select name="perangkat_asesmen[<?php echo $id_kuk?>]"
                                           class="form-control">
                                          
                                          <option value="" selected disabled hidden>Silahkan Pilih</option>
                                          
                                          <option value="CL (Daftar Periksa)">CL | Daftar Periksa</option>
                                          <option value="DIT (Daftar Instruksi Terstruktur)">DIT | Daftar Instruksi Terstruktur</option>
                                          <option value="DPL (Daftar Pertanyaan Lisan)">DPL | Daftar Pertanyaan Lisan</option>
                                          <option value="DPT (Daftar Pertanyaan Tertulis)">DPT | Daftar Pertanyaan Tertulis</option>
                                          <option value="PW (Pertanyaan Wawancara)">PW | Pertanyaan Wawancara</option>
                                          <option value="VPK (Verifikasi Pihak Ketiga)">VPK | Verifikasi Pihak Ketiga</option>
                                          <option value="VP (Verifikasi Portofolio)">VP | Verifikasi Portofolio</option>
                                          <option value="CUP (Ceklis Ulasan Produk)">CUP | Ceklis Ulasan Produk</option>

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


            
            <!-- End Isi -->

            <div class="panel box">
              
              <div class="box-body">
              
                <div class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalKonfirmasi"  data-toogle="tooltip" data-placement="top" title="Lanjut Asesmen Mandiri">
                  Simpan MAPA
                </div>

              </div>
            
            </div>

            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_asesor;?>" name="id_asesor" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->

            <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
            <input type="hidden" name="id_unit" value="<?php echo $id_unit?>"/>

            <!-- Modal Cek Syarat Dasar 1 -->
            <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


              <!-- Modal Dialog -->
              <div class="modal-dialog modal-lg">


                <!-- Modal content-->
                <div class="modal-content">


                  <!-- Modal Header -->
                  <div class="modal-header">

                    <center>
                    <h4 class="modal-title">Konfirmasi Simpan MAPA</h4>
                    </center>

                  </div>
                  <!-- End Modal Header -->


                  <!-- Modal body -->
                  <div class="modal-body">

                    <center>
                      <h3>
                        <strong>Simpan MAPA dengan data yang telah dimasukkan ?</strong>
                      </h3>
                      <h4>Pastikan data yang Anda masukkan benar, karena Anda tidak bisa melakukan perubahan data !</h4>
                    </center>

                  </div>
                  <!-- End Modal Body -->

                  <div class="modal-body">

                    <div class="form-group">

                      <label class="label-control">Password</label>
                      <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                      <small id='passwordHelpBlock' class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi hapus MAPA</small>

                    </div>


                  </div>

                  <div class="modal-footer justify-content-center">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan MAPA</button>

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
