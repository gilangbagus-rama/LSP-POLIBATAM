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

$foto_profil = $_SESSION['foto_profil'];


$data = mysqli_fetch_assoc ( $conn->query( "SELECT `id_asesi` FROM `asesi` WHERE `email` = '$email';" ) ) ;


// Panggil data asesi
$_SESSION['id_asesi'] = $data['id_asesi'];

$id_asesi = $_SESSION['id_asesi'];


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Data Permohonan</title>
  
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
        <a href="index.php?page=data_permohonan" class="btn-loading">
          <i class="fa fa-user"></i>
          <span>Data Permohonan</span>
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
          <li class="active"><a><i class="fa fa-list"></i> Data Permohonan</a></li>
        </ol>

      </section>
      <!-- End Content Header -->


      <!-- Main content -->
      <section class="content">


        <!-- Input addon -->
        <div class="box box-widget" style="margin-bottom: 10px;background-color: #FDFDFD">

          <div class="box-body">


            <h4 style="margin-bottom: 0px;margin-top: 0px;" class="pull-left text-primary">
              <i class="fa fa-info-circle" style="margin-right: 6px;"></i>
              Data Permohonan
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
          <div class="col col-lg-12 panel-group">

            <div class="box box-info container table-responsive">


              <!-- Box Body utk Tabel -->
              <div class="box-body">


                <!-- Tabel Skema Sertifikasi -->
                <table id="load" class="table  table-bordered table-striped">


                  <!-- Tabel Head -->
                  <thead>

                    <!-- Isi Head -->
                    <tr>


                      
                      <th width="5%">No</th>
                      <th width="15%">#</th>
                      <th width="20%">Skema Sertifikasi</th>
                      <th width="15%">Tujuan Asesmen</th>
                      <th width="5%">Rekomendasi</th>
                      <th width="20%">Catatan</th>
                      <th width="20%">Status Permohonan</th>
                      
                    </tr>
                    <!-- End Isi Head -->

                  </thead>
                  <!-- End Tabel Head -->


                  <!-- Tabel Body -->
                  <tbody>

                    <?php
                    include "config.php";

                    $sql = $conn->query("SELECT `permohonan`.`file_APL_01` ,  `permohonan`.`id_permohonan` , `skema_sertifikasi`.`nomor_skema` , `skema_sertifikasi`.`nama_skema` , `permohonan`.`status_permohonan` , `permohonan`.`rekomendasi` ,
                    `permohonan`.`catatan`, `permohonan`.`id_asesi` , `permohonan`.`tujuan_asesmen`
                    FROM `permohonan`, `skema_sertifikasi` WHERE `permohonan`.`id_skema` = `skema_sertifikasi`.`id_skema` AND `permohonan`.`id_asesi` = '$id_asesi' ORDER BY `id_permohonan` ");

                    $no = 1;

                    while ( $data = mysqli_fetch_assoc($sql) ) { 


                      $status = $data['status_permohonan'];

                      if ( $status == 'Ditolak' ) {

                        $status2 = 'label-danger';

                      }

                      if ( $status == 'Menunggu Validasi Permohonan' ) {

                        $status2 = 'label-warning';

                      }

                      
                      else {

                        $status2 = 'label-success';

                      }

                    ?>

                    



                      <tr>



                        <!-- Id Skema -->
                        <td> 
                          <?php echo $no ?>
                        </td>

                        <td>
                        


                              <a href="index.php?page=kelola_data_permohonan&&id_permohonan=<?php echo $data['id_permohonan']?>" target="_blank"
                                class="btn btn-primary">
                                  
                                <i class="fa fa-info" style="margin-right: 3px;"></i>
                                  Lihat Detil Data Permohonan

                              </a>




                          </div>
                          <!-- End Button Dropdown -->

                        </td>

                        <!-- Skema Sertifikasi -->
                        <td> 
                          <?php echo $data['nomor_skema'];?> | <?php echo $data['nama_skema']; ?> 
                        </td> 


                        <!-- Tujuan Sertifikasi -->
                        <td> 
                          <?php echo $data['tujuan_asesmen']; ?> 
                        </td> 



                        <!-- Nama File -->
                        <td> 
                          <?php echo $data['rekomendasi']; ?> 
                        </td> 


                        <!-- Nama File -->
                        <td> 
                          <?php echo $data['catatan']; ?> 
                        </td> 


                        <!-- Nama Skema -->
                        <td>
                          <label class="label <?php echo $status2 ?>"> <?php echo $data['status_permohonan']; ?> </label>
                        </td> 


                      </tr>



                    <?php
                    $no = $no+1;
                    }
                    ?>


                  </tbody>
                  <!-- End Tabel Body -->


                </table>
                <!-- End Table Skema Sertifikasi -->


              </div>
              <!-- End Box Body utk Tabel -->

            </div>





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
