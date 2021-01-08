<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include 'config.php';

session_start();

$no_hp  = $_SESSION['no_hp'];
$nama   = $_SESSION['nama'];


$id_asesi = $_SESSION['id_asesi'];


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


if ( $status_akun == 'Not Verif' ) {

  header("location: index.php?page=forbidden&aksi=not_verif");
  exit;

}


// Query cek kebenaran id_asesi
$sql =  $conn->query("SELECT * FROM `asesi` WHERE `id_asesi` = '$id_asesi' ");


  // Kalau id asesi palsu
  if ( mysqli_num_rows($sql) == '0' ) {

    header ( "location: index.php?page=forbidden&aksi=not_found" );
    exit;
  }

  $foto_profil = $_SESSION['foto_profil'];


?>

<!DOCTYPE html>
<html>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>SI-LSP | Identitas</title>

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
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <script src="dist/js/angular.min.js"></script> 
  <script src="bower_components/jquery/dist/jquery.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <script type="text/javascript">

    jQuery(function ($) {
      var $inputs = $('#contact_form :input').prop('disabled', true);
        $('#edit_btn').click(function () {
          $inputs.prop('disabled', false);
        });
      })

  </script>


  <script type="text/javascript">

    jQuery(function ($) {
      var $inputs = $('#contact_form2 :input').prop('disabled', true);
        $('#edit_btn2').click(function () {
          $inputs.prop('disabled', false);
      });
    })

  </script>


  <script type="text/javascript">
    jQuery(function ($) {
      var $inputs = $('#contact_form3 :input').prop('disabled', true);
        $('#edit_btn3').click(function () {
          $inputs.prop('disabled', false);
      });
    })
  </script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

</head>


<body class="hold-transition skin-blue-light sidebar-mini" onload="jQuery()">

  <!-- Site wrapper -->
  <div class="wrapper">

    <header class="main-header">

      <!-- Nanti ganti logo ke SI-LSP -->
      <!-- Logo -->
      <a href="index.php" class="logo">
        
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">SI-LSP</span>
        
        <!-- logo for regular state and mobile devices -->
        <img src="img/logo.png" height="100%" class="rounded" alt="...">
      </a>


      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>

        </a>


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


    <!-- =============================================== -->


    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
    
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
      
        <!-- Sidebar user panel -->
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
            <a href="#">Asesi</a>
          </div>

        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <?php
          include('sidebar.php');
        ?>
    
      </section>
      <!-- /.sidebar -->

    </aside>

    <!-- =============================================== -->

    <style>

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


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="loading">

        <!-- Main Content -->
        <section class="content">

          <h2 class="page-header">Data Diri

            <small>
              Harap lengkapi identitas diri untuk melakukan permohonan asesi
            </small>

          </h2>

          <!-- Form Update Identitas -->
          <form name="contact_form" id="contact_form" action="proses_user.php?aksi=<?php echo base64_encode("update"); ?>" method="post">


            <div class="row">


              <!-- Div Box -->
              <div class="col-md-12">


                <?php

                  include 'config.php';
                  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

                  $sql_asesi  = $conn->query("SELECT * FROM `asesi` WHERE `id_asesi` = '$id_asesi'; ");
                  $data       = mysqli_fetch_assoc($sql_asesi);


                  // Data Pribadi
                  $no_nik = $data['no_nik']; $tmpt_lahir = $data['tmpt_lahir']; $tgl_lahir = $data['tgl_lahir'];
                  $jenkel = $data['jenkel']; $kebangsaan = $data['kebangsaan']; $alamat_rmh = $data['alamat_rmh'];
                  $kodepos = $data['kodepos']; $notelp_rmh = $data['notelp_rmh']; $pendidikan = $data['pendidikan'];
                  $telppribadi_perusahaan = $data['telppribadi_perusahaan'];

                  // Data Perusahaan
                  $nama_perusahaan = $data['nama_perusahaan']; $jabatan = $data['jabatan']; $email_perusahaan = $data['email_perusahaan'];
                  $telp_perusahaan = $data['telp_perusahaan']; $fax_perusahaan = $data['fax_perusahaan']; $alamat_perusahaan = $data['alamat_perusahaan'];
                  $kodepos_perusahaan = $data['kodepos_perusahaan'];

                ?>


                <!-- Box Data Akun Asesi -->
                <div class="box box-solid"> 
          

                  <!-- Send Token dan ID -->
                  <input type="hidden" name="token" id="token"
                    value="<?php echo $token; ?>" />

                  <input type="hidden" name="id_asesi" id="id_asesi"
                    value="<?php echo $id_asesi; ?>" />

                  <input type="hidden" name="role" id="role"
                    value="<?php echo $role; ?>" />
                  
                  <!-- End -->


                  <!-- Header Data Akun -->
                  <div class="box-header with-border">
                    
                    <i class="fa fa-user"></i>

                    <h3 class="box-title">
                      Data Akun 
                    </h3>

                  </div>
                  <!-- End Header -->


                  <!-- Body Data Akun -->
                  <div class="box-body">


                    <!-- Input Nama -->
                    <div class="form-group">

                      <label for="nama">Nama</label>
                  
                      <input type="text" class="form-control"
                        name="nama" id="nama" required
                        value="<?php echo $nama; ?>" />
                
                    </div>


                    <input type="hidden" name="email_lama" id="email_lama"
                    value="<?php echo $email; ?>" />


                    <!-- Input E-Mail -->
                    <div class="form-group">

                      <label for="email_asesi">E-Mail</label>
                  
                      <input type="email" class="form-control"
                        name="email_asesi" id="email_asesi" required
                        value="<?php echo $email; ?>" />
                
                    </div>


                    <!-- Input No HP -->
                    <div class="form-group">

                      <label for="nohp">No HP</label>

                      <input type="tel" class="form-control" min-length="4" max-length="20"
                        name="nohp" id="nohp" required value="<?php echo $no_hp; ?>" />

                    </div>


                  </div>
                  <!-- End Body Data Akun -->

                </div>
                <!-- End Box Data Akun Asesi -->


                <!-- Box Data Pribadi Asesi -->
                <div class="box box-solid"> 


                  <!-- Header Box Data Pribadi -->
                  <div class="box-header with-border">
                    
                    <i class="fa fa-user"></i>

                    <h3 class="box-title">
                      Data Identitas Diri 
                    </h3>

                  </div>
                  <!-- End Hedaer Box Data Pribadi -->


                  <!-- Box Body Data Pribadi Asesi -->
                  <div class="box-body">


                    <!-- Input No NIK -->
                    <div class="form-group">

                      <label for="no_nik">No NIK</label>

                      <input type="number" class="form-control"
                        name="no_nik" id="no_nik" required
                        value="<?php echo $no_nik; ?>" />

                    </div>


                    <!-- Input Tempat Lahir -->
                    <div class="form-group">

                      <label for="tmpt_lahir">Tempat Lahir</label>

                      <input type="text" class="form-control"
                        name="tmpt_lahir" id="tmpt_lahir" required
                        value="<?php echo $tmpt_lahir; ?>" />

                    </div>


                    <!-- Input Tgl Lahir -->
                    <div class="form-group">

                      <label for="tgl_lahir">Tanggal Lahir</label>

                      <input type="date" class="form-control"
                        name="tgl_lahir" id="tgl_lahir" required
                        value="<?php echo $tgl_lahir; ?>" />

                    </div>


                    <!-- Input Jenis Kelamin -->
                    <div class="form-group">

                      <label for="jenkel">Jenis Kelamin</label>

                      <div class="col-sm-20" value="<?php echo $jenkel; ?>">

                        <select name="jenkel" id="jenkel" required 
                          class="form-control">

                          <?php 

                            if ( $jenkel == "lk" ) {

                              echo '<option value="lk" selected hidden> Laki Laki </option>' ;

                            } else if ( $jenkel == "pr" ) {

                              echo '<option value="pr" selected hidden> Perempuan </option>' ;

                            } else {

                              echo '<option value="" selected disabled hidden>Pilih...</option>';

                            } 
                          ?>
                          
                          <option value="lk">Laki Laki</option>
                          <option value="pr">Perempuan</option>

                        </select>

                      </div>

                    </div>


                    <!-- Input Kebangsaan -->
                    <div class="form-group">

                      <label for="kebangsaan">Kebangsaan</label>

                      <input type="text" class="form-control"
                        name="kebangsaan" id="kebangsaan" required
                        value="<?php echo $kebangsaan; ?>" />

                    </div>


                    <!-- Input Alamat Rumah -->
                    <div class="form-group">

                      <label for="alamat_rmh">Alamat Rumah</label>

                      <input type="text"  class="form-control" max-length="80"
                        name="alamat_rmh" id="alamat_rmh" required
                        value="<?php echo $alamat_rmh; ?>" />

                    </div>


                    <!-- Input Kode Pos Rumah -->
                    <div class="form-group">

                      <label type="kodepos">Kode Pos Rumah</label>
                      
                      <input type="number" class="form-control"
                        name="kodepos" id="kodepos" required
                        value="<?php echo $kodepos; ?>" />

                    </div>


                    <!-- Input No Telp Rumah -->
                    <div class="form-group">

                      <label for="notelp_rmh">No Telp Rumah</label>

                      <input type="tel" class="form-control"
                        name="notelp_rmh" id="notelp_rmh" 
                        value="<?php echo $notelp_rmh; ?>" />

                      <small class='form-text text-muted'>
                        Jika tidak ada , boleh dikosongkan.
                      </small>

                    </div>


                    <!-- Input No Telp Pribadi Perusahaan -->
                    <div class="form-group">

                      <label for="telppribadi_perusahaan">No Telp Pribadi Perusahaan</label>

                      <input type="tel" class="form-control"
                        name="telppribadi_perusahaan" id="telppribadi_perusahaan" 
                        value="<?php echo $telppribadi_perusahaan; ?>" />

                      <small class='form-text text-muted'>
                        Jika tidak ada , boleh dikosongkan.
                      </small>

                    </div>


                    <!-- Input Pendidikan -->
                    <div class="form-group">

                      <label for="pendidikan">Pendidikan</label>

                      <input type="text" class="form-control"
                        name="pendidikan" id="pendidikan" required
                        value="<?php echo $pendidikan; ?>" />

                    </div>
                
                  </div>
                  <!-- End Box Body Data Pribadi Asesi -->

                </div>
                <!-- End Box Data Pribadi Asesi -->


                <!-- Box Data Identitas Perusahaan -->
                <div class="box box-solid"> 


                  <!-- Header Data Identitas Perusahaan -->
                  <div class="box-header with-border">

                    <i class="fa fa-user"></i>

                    <h3 class="box-title">
                      Data Identitas Perusahaan
                    </h3>

                  </div>
                  <!-- End Header Data Identitas Perusahaan -->


                  <!-- Body Data Identitas Perusahaan -->
                  <div class="box-body">


                    <!-- Input Nama Perusahaan -->
                    <div class="form-group">

                      <label for="nama_perusahaan">Nama Perusahaan</label>

                        <input type="text" class="form-control"
                          name="nama_perusahaan" id="nama_perusahaan" required
                          value="<?php echo $nama_perusahaan; ?>" />

                    </div>
                    <!-- End Input Nama Perusahaan -->


                    <!-- Input Jabatan -->
                    <div class="form-group">

                      <label for="jabatan">Jabatan</label>

                        <input type="text" class="form-control"
                          name="jabatan" id="jabatan" required
                          value="<?php echo $jabatan; ?>" />

                    </div>
                    <!-- End Input Jabatan -->


                    <!-- Input E-Mail Perusahaan -->
                    <div class="form-group">

                      <label for="email_perusahaan">E-Mail Perusahaan</label>

                      <input type="email" class="form-control"
                        name="email_perusahaan" id="email_perusahaan" required
                        value="<?php echo $email_perusahaan; ?>" />

                    </div>
                    <!--End Input E-Mail Perusahaan  -->


                    <!-- Input No Telp Perusahaan -->
                    <div class="form-group">

                      <label for="telp_perusahaan">No Telp Perusahaan</label>

                      <input type="tel" class="form-control"
                        name="telp_perusahaan" id="telp_perusahaan" required
                        value="<?php echo $telp_perusahaan; ?>" />

                    </div>
                    <!-- End Input No Telp Perusahaan -->


                    <!-- Input Fax Perusahaan -->
                    <div class="form-group">

                      <label for="fax_perusahaan">Fax Perusahaan</label>

                      <input type="tel" class="form-control"
                        name="fax_perusahaan" id="fax_perusahaan"
                        value="<?php echo $fax_perusahaan; ?>" />

                      <small class='form-text text-muted'>
                        Jika tidak ada , boleh dikosongkan.
                      </small>

                    </div>
                    <!-- End Input Fax Perusahaan -->


                    <!-- Input Alamat Perusahaan -->
                    <div class="form-group">

                      <label for="alamat_perusahaan">Alamat Perusahaan</label>

                      <input type="tel" class="form-control"
                        name="alamat_perusahaan" id="alamat_perusahaan" required
                        value="<?php echo $alamat_perusahaan; ?>" />

                    </div>
                    <!-- End Input Alamat Perusahaan -->


                    <!-- Input Kode Pos Perusahaan -->
                    <div class="form-group">

                      <label for="kodepos_perusahaan">Kode Pos Perusahaan</label>

                      <input type="number" class="form-control"
                        name="kodepos_perusahaan" id="kodepos_perusahaan" required
                        value="<?php echo $kodepos_perusahaan; ?>" />

                    </div>
                    <!-- End Input Kode Pos Perusahaan -->


                  </div>
                  <!-- End Body Data Identitas Perusahaan -->


                  <!-- Box Footer -->
                  <div class="box-footer">

                    <a id="edit_btn" class="btn btn-primary" name="submit">Ubah</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>

                  </div>
                  <!-- End Box Footer -->


                </div>
                <!-- End Box Data Identitas Perusahaan -->


              </div>
              <!-- End Div Box -->


          </form>
          <!-- End Form Update Identitas -->


          <script type="text/javascript">
          
            $(document).ready(function() {
              $('#provinsi').select2({
                placeholder: 'Pilih Kompetensi',
                theme: "bootstrap",
                allowClear: true
              });
            });

          </script>


        </section>
        <!-- End Main Content -->


      </div>
    </div>
    <!-- End Content Wrapper. Contains page content -->
  
    <!-- Footer -->
    <?php
      include('footer.php');
    ?>


  </div>
  <!-- End Site Wrapper -->


  <!-- jQuery 3 
  <script src="bower_components/jquery/dist/jquery.min.js"></script>-->

  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>

  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

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
