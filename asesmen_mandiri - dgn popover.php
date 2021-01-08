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
      !isset( $role ) || empty( $role ) || $role != 'Asesi' ||
      $_SESSION['role'] != 'Asesi' ) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

  };

// End Cek Login

// Cek Status permohonan
  $sql =  $conn->query("SELECT `permohonan`.`id_asesi`
    FROM `permohonan` , `akun` , `asesi` WHERE permohonan.id_asesi = asesi.id_asesi 
    AND asesi.email = akun.email AND akun.email = '$email' ");


  // Jika ID Asesi palsu
  if ( mysqli_num_rows($sql) == '0' ) {

    header ( "location: index.php?page=forbidden&aksi=belum_mengajukan" );
    exit;
  };

// End


$data = mysqli_fetch_assoc ( $conn->query( "SELECT `id_asesi` FROM `asesi` WHERE `email` = '$email';" ) ) ;


// Panggil data asesi
$_SESSION['id_asesi'] = $data['id_asesi'];

$id_asesi = $_SESSION['id_asesi'];


$id_permohonan = $_GET['id_permohonan'];


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Asesmen Mandiri</title>
  
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
        <h2 class="page-header">Form Asesmen Mandiri

          <small>Form berdasarkan Form APL 02</small>

        </h2>
        <!-- End Page header -->


        <div class="row">


          <!-- Box Kanan -->
          <div class="col col-lg-12 panel-group">


          <form action="proses_user.php?aksi=<?php echo base64_encode('asesmen_mandiri')?>" name="asesmen_mandiri" 
            id="asesmen_mandiri" method="post" enctype="multipart/form-data">
            
            <!-- Panel Panduan Mengisi -->
            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  <a data-toggle="collapse" data-parent="#accordion" href="#PanduanMengisi">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Panduan Mengisi Form APL 02 - Asesmen Mandiri </i>
                      
                    </h4>
                  </a>

                </div>
                <!--End Judul Panel-->


                <!-- Isi -->
                <div id="PanduanMengisi" class="panel-collapse collapse out">


                  <div class="panel-body">

                      <li>Baca dengan teliti</li>
                      <li>Pilih Jawaban jika Anda yakin dapat melakukan tugas yang dijelaskan.</li>

                  </div>


                </div>
                <!-- End Isi -->


            </div>
            <!-- End Panel Panduan Mengisi -->


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
                <div class="panel-collapse">


                  <div class="panel-body">

                    <?php
                      $sql=$conn->query("SELECT `permohonan`.`id_permohonan` , `skema_sertifikasi`.`nomor_skema` , `skema_sertifikasi`.`nama_skema`, `skema_sertifikasi`.`id_skema`
                      FROM `skema_sertifikasi` , `permohonan`, `asesi` WHERE `skema_sertifikasi`.`id_skema` = `permohonan`.`id_skema`
                      AND  `permohonan`.`id_permohonan` = '$id_permohonan' AND `permohonan`.`id_asesi` = `asesi`.`id_asesi` 
                      AND `asesi`.`email` = '$email'"); $data = mysqli_fetch_assoc($sql); $id_skema = $data['id_skema'];
                      
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
              $sql_unit = $conn->query("SELECT * FROM `unit_skema` WHERE `id_skema` = '$id_skema'  ORDER BY `id_unit` ASC ;");
              $no = 1; 
                
              while ( $unit_skema = mysqli_fetch_assoc($sql_unit) ) { 
                $id_unit =  $unit_skema['id_unit']; 
            ?>


            <!-- Panel Data KUK -->
            <div class="panel box box-solid">


                <!-- Judul -->
                <div class="panel-heading" style="color: white">
                  <a data-toggle="collapse" data-parent="#accordion" 
                  href="#DataSertifikasi<?php echo $no ?>">
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Unit Kompetensi No. <?php echo $no ?> </i>
                      
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
                        </table></div>
                        


  <a href="#" data-toggle="popover" data-placement="bottom" title="Pilih Makanan" data-content="Hai Ini makanan">Toggle popover</a>


<script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();   
});



// comment this
$('[data-toggle="popover"]').popover({container: "body"});

// uncomment this
$('[data-toggle="popover"]').popover({container: "#fixed-div"});
</script>

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
                      
                        </table></div>

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
                                ?>

                                <tr>
                                
                                  <td><?php echo $kuk['no_kuk']?>
                                  </td>

                                  <td><?php echo $kuk['kuk']?></td>


                                  <td>

                                    <div>

                                      <select name="nilai_kuk[<?php echo $id_kuk?>]"
                                        id="nilai_kuk<?php echo $id_kuk?>" required class="form-control">
                                        <option value="" selected disabled hidden>Silahkan Pilih</option>
                                        <option value="K">Kompeten</option>
                                        <option value="BK">Belum Kompeten</option>
                                      </select>

                                      
                                    </div>

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
                          
                            <center><i class="fa fa-arrow-up"> Gulung </i></center>
                          
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

            <div class="panel box">
              
              <div class="box-body">
              
                <div class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalKonfirmasi"  data-toogle="tooltip" data-placement="top" title="Lanjut Asesmen Mandiri">
                  Asesmen Mandiri
                </div>

              </div>
            
            </div>

            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_asesi;?>" name="id_asesi" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->

            <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
            <input type="hidden" name="id_skema" value="<?php echo $id_skema?>"/>

            <!-- Modal Cek Syarat Dasar 1 -->
            <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


              <!-- Modal Dialog -->
              <div class="modal-dialog modal-lg">


                <!-- Modal content-->
                <div class="modal-content">


                  <!-- Modal Header -->
                  <div class="modal-header">

                    <center>
                    <h4 class="modal-title">Konfirmasi Asesmen Mandiri</h4>
                    </center>

                  </div>
                  <!-- End Modal Header -->


                  <!-- Modal body -->
                  <div class="modal-body">

                    <center>
                      <h3>
                        <strong>Simpan Asesmen Mandiri dengan data yang telah dimasukkan ?</strong>
                      </h3>
                      <h4>Pastikan data yang Anda masukkan benar, karena Anda tidak bisa melakukan perubahan data !</h4>
                    </center>

                  </div>
                  <!-- End Modal Body -->

                  <div class="modal-footer justify-content-center">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Asesmen Mandiri</button>

                  </div>


                </div>
                <!-- End Modal Content -->


              </div>
              <!-- End Modal Dialog -->


            </div>
            <!-- End Modal Cek Syarat Dasar 1 -->

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
