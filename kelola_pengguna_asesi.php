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


?>

<?php

// Agar Error tidak ditampilkan
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// include Koneksi
include 'config.php';

// Start Session
session_start();


// Cek Token Login

  // Input Token , id_UA dan role
  $token    = $_SESSION['token'];
  $id_UA    = $_SESSION['id_UA'];
  $role     = $_SESSION['role'];


  // // Cek Token Login
  // if ( !isset($_SESSION['token'] ) && 
  //     $token != $_SESSION['token']  &&
  //     !isset($_SESSION['id_UA'] ) &&
  //     $id_UA != $_SESSION['id_UA'] &&
  //     $role != 'User Admin') :

  //   header("location: index.php?page=forbidden&aksi=not_login");
  //   exit;

  // endif;

// End Cek token Login

// Start
$nama   = $_SESSION['nama'];
$email  = $_SESSION['email_UA'];

?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Kelola Asesi</title>
  
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


      <!-- Content Header (Page header) -->
      <section class="content-header">

        <h1>
          Dashboard
          <small>SI - LSP</small>
        </h1>

        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="">Kelola Pengguna</li>
          <li class="active">Kelola Asesi</li>
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
              Data Asesi
            </h4>
            
<!--             
              <a class="btn-loading label label-primary pull-right"
                style="font-size: 13px;padding-bottom: 5px;padding-top: 5px;" 
                data-toggle="modal" data-target="#ModalTambahAsesi">
                <span data-toggle="tooltip" data-placement="top" title="Tambah Asesi">
              
                <i class="fa fa-plus" style="margin-right: 3px;"></i>
                Tambah Asesi</span>

              </a> -->
            


          </div>
          <!-- End Box Body -->


        </div>
        <!-- End Input Addon -->


        <!-- Modal Tambah Skema Sertifikasi -->
        <div id="ModalTambahAsesi" class="modal fade" tabindex="-1" role="dialog">


          <!-- Modal Dialog -->
          <div class="modal-dialog modal-dialog-centered" role="document">


            <!-- Modal content-->
            <div class="modal-content">


              <!-- Modal Header -->
              <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
          
                <h4 class="modal-title">Tambah Asesi</h4>
              </div> -->
              <!-- End Modal Header -->


              <!-- Modal body -->
              <div class="modal-body">


                <!-- Form Tambah Skema -->
                <form role="form" method="post" enctype="multipart/form-data" id="form_tambah_skema"
                  action="proses_user_admin.php?aksi=tambah_asesi" >


                  <!-- Input token , role dan id_UA user admin -->
                  <input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
                  <input type="hidden" id="role" name="role" value="<?php echo $role; ?>">
                  <input type="hidden" id="id_UA" name="id_UA" value="<?php echo $id_UA; ?>">
                  <!-- End Input token , role dan id_UA user admin -->

                  <!-- Input Nama Asesi -->
                  <div class="form-group has-feedback">
                    
                    <label class="control-label" for="nama_asesi">
                      Nama Asesi
                    </label>

                    <input type="text" name="nama_asesi" id="nama_asesi" autofocus
                      class="form-control" placeholder="Masukan nama Asesi"  required="">
                    
                  </div>
                  <!-- End Input Nama Asesi -->

                  <!-- Input E-Mail Asesi -->
                  <div class="form-group has-feedback">
                    
                    <label class="control-label" for="email_asesi">
                      E - Mail Asesi
                    </label>

                    <input type="email" name="email_asesi" id="email_asesi" autofocus
                      class="form-control" placeholder="Masukan E-Mail Asesi"  required="">
                    
                  </div>
                  <!-- End Input E-Mail Asesi -->

                  <!-- Input No HP Asesi -->
                  <div class="form-group has-feedback">
                    
                    <label class="control-label" for="no_hp_asesi">
                      No HP Asesi
                    </label>

                    <input type="tel" name="no_hp_asesi" id="no_hp_asesi" autofocus
                      class="form-control" placeholder="Masukan No HP Asesi"  required="">
                    
                  </div>
                  <!-- End Input No HP Asesi -->


                  <!-- Button Tambahkan -->
                  <div class="form-group">

                    <button type="submit" id="btn-add" class="btn btn-primary btn-block">Tambahkan Asesi</button>

                  </div>
                  <!-- End Button Tambahkan -->


                </form>
                <!-- End Form Tambah Skema -->


              </div>
              <!-- End Modal Body -->


            </div>
            <!-- End Modal Content -->


          </div>
          <!-- End Modal Dialog -->


        </div>
        <!-- End Modal Tambah Skema Sertifikasi -->


        <!-- Box Tabel Skema Sertifikasi -->
        <div class="box box-info">


          <!-- Judul Box -->
          <div class="box-header with-border">

            <h3 class="box-title">
              Kelola Asesi
            </h3>

          </div>
          <!-- Judul Box -->


          <!-- Box Body utk Tabel -->
          <div class="box-body table-responsive">

            <!-- Tabel Skema Sertifikasi -->
            <table id="load" class="table table-bordered table-striped">


              <!-- Tabel Head -->
              <thead>

                <!-- Isi Head -->
                <tr>

                  <th width="5%">No</th>
                  
                  <th width="12%">Nama Asesi</th>
                  <th width="11%">E - Mail</th>
                  <th width="20%">No HP</th>
                  <th width="20%">#</th>
                  
                </tr>
                <!-- End Isi Head -->

              </thead>
              <!-- End Tabel Head -->


              <!-- Tabel Body -->
              <tbody>

                <?php

                  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                  include 'config.php';

                  $no = 1;

                  // Query SELECT * skema sertifikasi
                  $sql = $conn->query("SELECT `akun`.`email` , `akun`.`nama`, 
                  `akun`.`no_hp` , `asesi`.`id_asesi` FROM `akun` , `asesi` WHERE 
                  `akun`.`email` = `asesi`.`email` AND `akun`.`role` = 'Asesi' ");

                  while( $data = mysqli_fetch_assoc($sql) ) {
                ?>


                  <tr>

                    <td> 
                      <?php echo $no; ?> 
                    </td>

                    <!-- Id Skema -->
                    <td> 
                      <?php echo $data['nama']; ?> 
                    </td>


                    <!-- Nomor Skema -->
                    <td> 
                      <?php echo $data['email']; ?> 
                    </td> 


                    <!-- Nama Skema -->
                    <td>
                      <?php echo $data['no_hp']; ?>
                    </td>
                    
                    <!-- Button Aksi -->
                    <td>



                              <a href="#" class="btn btn-primary" role="button"
                                data-toggle="modal" data-target="#ModalResetPasswordAsesi<?php echo $no ?>">

                                <i class="fa fa-refresh"></i> 
                                Reset Password Akun Asesi

                              </a>


        
                            <!-- End Menu Edit Skema -->


                            <!-- Menu Edit Skema -->
                            <!-- <li style="padding-bottom: 10px" data-toggle="tooltip" data-placement="top" title="Edit Data Asesi <?php $data['nama_skema'] ?>">


                              <a href="#" class="dropdown-item"
                                data-toggle="modal" data-target="#ModalEditAsesi<?php echo $no ?>">

                                <i class="fa fa-pencil"></i> 
                                Ubah Data Akun Asesi

                              </a>


                            </li> -->
                            <!-- End Menu Edit Skema -->


                            <!-- Menu Hapus Skema -->
                            <!-- <li style="padding-bottom: 10px" data-toggle="tooltip" data-placement="top" title="Hapus Skema <?php $data['nama_skema'] ?>">


                              <a href="#" class="dropdown-item"
                                data-toggle="modal" data-target="#ModalHapusSkema<?php echo $no ?>">

                                <i class="fa fa-trash"></i>
                                Hapus Asesi

                              </a>


                            </li> -->
                            <!-- End Menu Hapus Skema -->



                          </ul>
                          <!-- End Menu Dropdown Button Aksi -->


                        </div>
                        <!-- End Button Dropdown -->

                    
                    </td>


                  </tr>

                        <div id="ModalResetPasswordAsesi<?php echo $no ?>" class="modal fade" tabindex="-1" role="dialog">


                          <!-- Modal Dialog -->
                          <div class="modal-dialog modal-dialog-centered" role="document" aria-labelledby="ModalHapusSkemaTitle" aria-hidden="true">


                            <!-- Modal Content -->
                            <div class="modal-content">


                              <!-- Modal Body -->
                              <div class="modal-body">


                                <center>
                                  <h3>
                                    <strong>Reset Password Asesi</strong>
                                  </h3>
                                </center>

                                <br>

                                <center>
                                  <h4>Yakin ingin melakukan reset password Asesi <?php echo $data['nama'] ?> ? </h4>
                                </center>

                                <center>
                                  <h5>


                              </div>

                              <form action="proses_user_admin.php?aksi=reset_passwordasesi"  role="form" method="post">
                              
                              <!-- Modal body -->
                              <div class="modal-body">

                                <div class="form-group">
                                
                                  <label class="label-control">Password</label>
                                  <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                                  <small id='passwordHelpBlock' 
                                  class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi tindakan</small>
                                
                                </div>


                              </div>
                              <!-- End Modal Body -->


                                <!-- Input token user admin -->
                                <input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
                                <input type="hidden" id="role" name="role" value="<?php echo $role; ?>">
                                <input type="hidden" id="id_UA" name="id_UA" value="<?php echo $id_UA; ?>">
                                <!-- End Input token user admin -->

                                <!-- Input hidden email -->
                                <input type="hidden" name="email_asesi" value="<?php echo $data['email']; ?>">
                                <!-- End input hidden email -->

                                <!-- Button Hapus dan Batal -->
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                  <button type="submit" id="btn-del" class="btn btn-danger">Reset Password</button>
                                </div>
                                <!-- End Button -->


                              </form>


                            </div>
                            <!-- End Modal Content -->


                          </div>
                          <!-- End Modal Dialog -->


                        </div>



                <?php
                  $no = $no + 1;
                }
                ?>


              </tbody>
              <!-- End Tabel Body -->


            </table>
            <!-- End Table Skema Sertifikasi -->

          </div>
          <!-- End Box Body utk Tabel -->

          


        </div>
        <!-- End Box Table Skema Sertifikasi -->


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
