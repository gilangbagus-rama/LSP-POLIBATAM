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

$id_mapa = $_GET['id_mapa'];


?>


<!DOCTYPE html>
<html>


<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible " content="IE=edge">

  <title>SI-LSP | Tambah Peta MUK</title>
  
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



      <!-- Main content -->
      <section class="content">

        <!-- Page header -->
        <h2 class="page-header">Form Peta MUK dari Hasil Pendekatan Asesmen dan Perencanaan Asesmen

          <small>Form berdasarkan Form MAPA 02</small>

        </h2>
        <!-- End Page header -->


        <!-- Pendekatan Asesmen -->
        <div class="row">


          <!-- Box Kanan -->
          <div class="panel-group col col-md-12">


          <form action="proses_asesor.php?aksi=<?php echo base64_encode('tambah_peta_muk')?>" name="tambah_peta_muk" 
            id="tambah_peta_muk" method="post" enctype="multipart/form-data">
            <!-- <form action="tes.php" name="tambah_mapa" 
            id="tambah_mapa" method="post" enctype="multipart/form-data"> -->


            <?php
            
            $sql = $conn->query("SELECT unit_skema.kode_unit, unit_skema.judul_unit FROM unit_skema,mapa WHERE mapa.id_unit = unit_skema.id_unit AND mapa.id_mapa= '$id_mapa' ");

            $d = mysqli_fetch_assoc($sql);
            
            ?>



            <div class="panel box">


              <!-- Judul Panel -->
              <div class="panel-heading">

                <h4 class="panel-title with-border">
                
                  <i class="fa fa-file"> Informasi Unit Skema</i>
                
                </h4>

              </div>
                <!--End Judul Panel-->

              <div class="panel-body">


                <!-- Unit Kompetensi -->
                <div class="form-group">

                  <label class="label-control">Unit Kompetensi</label>

                  <input class="form-control" type="text" readonly value="<?php echo $d['judul_unit']?>"/>

                </div>



                <!-- Tujuan -->
                <div class="form-group">

                  <label class="label-control">No Unit</label>

                  <input class="form-control" type="text" readonly value="<?php echo $d['kode_unit']?>"/>

                </div>






                </div>




                <!-- End Isi -->


            </div>


            <div class="panel box">


                <!-- Judul Panel -->
                <div class="panel-heading">
                  
                    <h4 class="panel-title with-border">
                      
                      <i class="fa fa-user"> Peta MUK </i>
                      
                    </h4>

                </div>
                <!--End Judul Panel-->



                <div class="panel-body table-responsive">

                    <table class="table table-stripped">
                    

                      <thead>
                      
                      <th width="5%">No.</th>
                      <th width="45%">MUK</th>
                      <th width="50%">Potensi Kandidat
                      
                      </thead>

                      <tbody>

                      <?php 
                      
                      $pertanyaan_muk = $conn->query("SELECT * FROM `pertanyaan_muk`");
                      
                      while ($data = mysqli_fetch_assoc($pertanyaan_muk) ) {?>
                      
                      <tr>

                        <td>
                          <input type="hidden" name="no_muk[<?php echo $data['no_muk']?>]" value="<?php echo $data['no_muk']?>"/>
                          <?php echo $data['no_muk']?>.
                        </td>

                        <td>
                          <input type="hidden" name="muk[<?php echo $data['no_muk']?>]" value="<?php echo $data['muk']?>"/>
                          <?php echo $data['muk']?>

                          
                        </td>

                        <td>
                        
                          <select name="potensi_kandidat[<?php echo $data['no_muk']?>]" required class="form-control">

                            <option value="" selected hidden>Silahkan pilih Potensi Kandidat</option>

                            <option value="1">1.	Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi.</option>
                            <option value="2">2.	Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.</option>
                            <option value="3">3.	Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi.</option>
                            <option value="4">4.	Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.</option>
                            <option value="5">5.	Pelatihan / belajar mandiri atau otodidak.</option>

                          </select>
                        
                        </td>

                      </tr>

                      <?php }?>
                      
                      </tbody>
                    
                    </table>

                </div>
                
                <!-- End Isi -->


            </div>


            <div class="panel box">


              <div class="panel-body ">
                <a  data-toggle="modal" data-target="#ModalKonfirmasi"
                class="btn btn-primary btn-block">Simpan Peta MUK</a>
              
              </div>
                
                <!-- End Isi -->


            </div>


            <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


              <!-- Modal Dialog -->
              <div class="modal-dialog modal-lg">


              <!-- Modal content-->
              <div class="modal-content">


              <!-- Modal Header -->
              <div class="modal-header">


                <h4 class="modal-title text-center">Konfirmasi Simpan Peta MUK</h4> 


              </div>
              <!-- End Modal Header -->


              <!-- Modal body -->
              <div class="modal-body text-center">

                <h3 class="text-bold">
                Simpan Peta MUK dengan data yang telah dimasukkan ?
                </h3>

              </div>


              <!-- Modal body -->
              <div class="modal-body">

                <div class="form-group">
                
                  <label class="label-control">Password</label>
                  <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                  <small id='passwordHelpBlock' 
                  class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi simpan Peta MUK</small>
                        
                </div>


              </div>
              <!-- End Modal Body -->




              <!-- Button Batal dan Ajukan Permohonan -->
              <div class="modal-footer justify-content-center">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Simpan Peta MUK</button>

              </div>


            </div>
                    <!-- End Modal Content -->


            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_asesor;?>" name="id_asesor" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->

            <input type="hidden" name="id_permohonan" value="<?php echo $id_permohonan?>" />
            <input type="hidden" name="id_unit" value="<?php echo $id_unit ?>" />
            <input type="hidden" name="id_mapa" value="<?php echo $id_mapa?>" />


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
