<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include 'config.php';

session_start();

// Cek Login
$token = $_SESSION['token']; $id_asesi = $_SESSION['id_asesi'];
  $role = $_SESSION['role']; $email_asesi = $_SESSION['email'];


  if ( !isset( $_SESSION['token'] ) || !isset( $id_asesi ) || !isset( $role ) || $role != 'Asesi' ) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

  }

// End Cek Login


// Cek status akun
$status_akun = $_SESSION['status_akun'];

  // Cek Status Verif
  if ( $status_akun == 'Not Verif' ) {

    header("location: index.php?page=forbidden&aksi=not_verif");
    exit;

  }



// Query cek kebenaran ID Asesi
$sql =  $conn->query("SELECT * FROM `asesi` WHERE `id_asesi` = '$id_asesi' ");


  // Jika ID Asesi palsu
  if ( mysqli_num_rows($sql) == '0' ) {

    header ( "location: index.php?page=forbidden&aksi=not_found" );
    exit;
  }

  $data = mysqli_fetch_assoc($sql);
  // Cek Status Pengisian Data Diri
  if ( $data['status_data_diri'] == '0' ) {
    header("location: index.php?page=forbidden&aksi=not_complete");
    exit;

  }



// Start
$nama = $_SESSION['nama'];
$email = $_SESSION['email'];

$foto_profil = $_SESSION['foto_profil'];

?>


<!DOCTYPE html>
<html>

<head>


  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>SI-LSP | Ajukan Banding</title>

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
  
  <!-- Datatables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">


  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" />
  
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"/>


  <script src="dist/js/angular.min.js"></script>

  <script src="bower_components/jquery/dist/jquery.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->


  <script type="text/javascript" src="js/jquery.js">
  </script>

  <script type="text/javascript" src="js/list.js">
  </script>

	<script type="text/javascript">

    function showUser(str) {

		  if (str == "") {

			  document.getElementById("txtHint").innerHTML = "";
			  return;

		  } else { 

        if (window.XMLHttpRequest) {

          // code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp = new XMLHttpRequest();

        } else {

          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        }


        xmlhttp.onreadystatechange = function() {


          if (this.readyState == 4 && this.status == 200) {

            document.getElementById("txtHint").innerHTML = this.responseText;

          }

        };

        xmlhttp.open("GET","getdataunitskema.php?q="+str,true);

        xmlhttp.send();
    
      }

    }

  </script>

  <script type="text/javascript">

    function showSyaratDasar(str) {

      if (str == "") {

        document.getElementById("syaratDasar").innerHTML = "";
        return;

      } else { 

        if (window.XMLHttpRequest) {

          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();

        } else {

          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        }

        xmlhttp.onreadystatechange = function() {

          if (this.readyState == 4 && this.status == 200) {

            document.getElementById("syaratDasar").innerHTML = this.responseText;

          }

        };

        xmlhttp.open("GET","getsyaratdasar.php?s="+str,true);

        xmlhttp.send();


      }

    }

  </script>

  <script 
    src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js">
  </script>

</head>


<body class="hold-transition skin-blue-light sidebar-mini" onload="jQuery()">


  <!-- Site wrapper -->
  <div class="wrapper">



    <!-- Header  -->
    <header class="main-header">


      <!-- Logo -->
      <a href="index.php" class="logo">

        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">SI-LSP</span>

        <!-- logo for regular state and mobile devices -->
        <img src="img/logo.png" height="100%" class="rounded" alt="SI - LSP Logo">

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


      <!-- Modal Dialog -->
      <div class="modal-dialog" role="document" aria-labelledby="ModalLogoutTitle" aria-hidden="true">


        <!-- Modal Content -->
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title text-center"><b>Logout</b></h4>
            
          </div>


          <!-- Modal Body -->
          <div class="modal-body">

            <center>
              <h4>Yakin ingin Logout ? </h4>
            </center>


          </div>
          <!-- End Modal Body -->


          <form action="index.php?page=logout" role="form" method="post">


            <!-- Input email -->
            <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">
            <!-- End Input email -->


            <!-- Button Logout -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" id="btn-del" class="btn btn-danger">Logout</button>
            </div>


          </form>


        </div>
        <!-- End Modal Content -->


      </div>
      <!-- End Modal Dialog -->


    </div>
    <!-- End Modal Logout -->



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
            <a href="#"><?php echo $role ?></a>
          </div>


        </div>
        <!-- Sidebar user panel -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <!-- sidebar menu: : style can be found in sidebar.less -->


        <!-- include sidebar -->
        <?php
          include('sidebar.php');
        ?>


      </section>



    </aside>
    <!-- End Left side coloumn -->



    <!-- Inner style -->
    <style>

      .modal-center {
        background: none;
        position: absolute;
        float: left;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
      }

      table.grid {
        width: 100%;
        border: none;
        background-color: #3c8dbc;
        padding: 0px;
      }

      table.grid tr {
        text-align: center;
      }

      table.grid td {
        border: 4px solid white;
        padding: 6px;
      }

      .margin-top-responsive-5 {
      margin-top: 5px;
      }

      .btn-grid {
        background: #325d88;
        color: #ffffff;
      }

      td.active {
        background: #367fa9;
      }

      @media screen and (max-width: 780px) {

        .grid small {
          font-size: 11px;
        }

        .margin-top-responsive-5 {
          margin-top: 0px;
        }

      }



    </style>
    <!-- Inner style -->


    <!-- Modal PanduanDataSertifikasi -->
    <div id="PanduanDataSertifikasi" class="modal fade" tabindex="-1" role="dialog">


      <!-- Modal Dialog -->
      <div class="modal-dialog" role="document" aria-labelledby="ModalLogoutTitle" aria-hidden="true">


        <!-- Modal Content -->
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title text-center text-bold">Panduan Mengisi Data Sertifikasi</h4>
            
          </div>


          <!-- Modal Body -->
          <div class="modal-body">

            <div class="form-group">

            <label class="label-control text-bold">1. Tujuan Asesmen</label> 

              <p class="form-control no-border">Jika Anda belum pernah melakukan asesmen sertifikasi sebelumnya, silahkan pilih <b>Sertifikasi</b> </p>

              <p class="form-control no-border">Jika Anda ingin melakukan uji ulang, silahkan pilih <b>Sertifikasi Ulang</b> </p>


            </div>


            <div class="form-group">

              <label class="label-control text-bold">2. Skema Sertifikasi</label> 

                <p class="form-control no-border">Silahkan pilih satu dari beberapa Skema Sertifikasi yang tersedia.</p>

            </div>

            <div class="form-group">

              <label class="label-control text-bold">3. Unit Skema</label> 

                <p class="form-control no-border">Unit Skema akan muncul setelah Anda memilih Skema Sertifikasi</p>

            </div>


          </div>
          <!-- End Modal Body -->



            <!-- Button Logout -->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Tutup</button>
            </div>




        </div>
        <!-- End Modal Content -->


      </div>
      <!-- End Modal Dialog -->


    </div>
    <!-- End Modal PanduanDataSertifikasi -->



    <!-- Modal PanduanSyaratDasar -->
    <div id="PanduanSyaratDasar" class="modal fade" tabindex="-1" role="dialog">


      <!-- Modal Dialog -->
      <div class="modal-dialog" role="document" aria-labelledby="ModalLogoutTitle" aria-hidden="true">


        <!-- Modal Content -->
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title text-center text-bold">Panduan Mengisi Bukti Kelengkapan Pemohon</h4>
            
          </div>


          <!-- Modal Body -->
          <div class="modal-body">

            <div class="form-group">

              <p class="form-control no-border">Syarat Dasar akan muncul setelah Anda memilih Skema Sertifikasi.</p>
              <p class="form-control no-border">Apabila memenuhi persyaratan dasar, silahkan pilih <b>Terpenuhi</b></p>

              <p class="form-control no-border">Apabila tidak memenuhi persyaratan dasar, silahkan pilih <b>Tidak Terpenuhi</b></p>

            </div>


          </div>
          <!-- End Modal Body -->



            <!-- Button Logout -->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Tutup</button>
            </div>




        </div>
        <!-- End Modal Content -->


      </div>
      <!-- End Modal Dialog -->


    </div>
    <!-- End Modal PanduanSyaratDasar -->


    <!-- Modal PanduanPortofolio -->
    <div id="PanduanPortofolio" class="modal fade" tabindex="-1" role="dialog">


      <!-- Modal Dialog -->
      <div class="modal-dialog" role="document" aria-labelledby="ModalLogoutTitle" aria-hidden="true">


        <!-- Modal Content -->
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title text-center text-bold">Panduan Mengisi Portofolio</h4>
            
          </div>


          <!-- Modal Body -->
          <div class="modal-body">

            <p class="form-control no-border">Berikan bukti pendukung seperti KTP, Transkrip Nilai, Ijazah maupun Sertifikat yang berkaitan dengan Skema Sertifikasi yang dipilih.</p>

          </div>


          <div class="modal-body">

            <p class="form-control no-border">Untuk menambah baris upload, silahkan klik <b>Tambah Portofolio</b>, kemudian kolom baru akan muncul.</p>

          </div>

          <div class="modal-body">

            <p class="form-control no-border">Untuk menghapus baris upload, silahkan klik icon <b>Hapus Portofolio</b>, yang berada di samping kanan baris yang ingin dihapus.</p>

          </div>



          <div class="modal-body">

            <p class="form-control no-border">Bukti yang di upload dapat berupa Gambar (.jpg .jpeg .png), maupun berupa PDF (.pdf)</p>

          </div>
          <!-- End Modal Body -->



            <!-- Button Logout -->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Tutup</button>
            </div>




        </div>
        <!-- End Modal Content -->


      </div>
      <!-- End Modal Dialog -->


    </div>
    <!-- End Modal PanduanPortofolio -->





    <!-- Content Wrapper - Isi Halaman -->
    <div class="content-wrapper">


      <div class="loading">


        <!-- Main content -->
        <section class="content">


          <!-- Page header -->
          <h2 class="page-header">Form Banding Asesmen


          </h2>
          <!-- End Page header -->


          <!-- Form Ajukan Permohonan -->
          <form name="ajukan_permohonan" id="ajukan_permohonan" method="post" enctype="multipart/form-data"
            action="proses_user.php?aksi=<?php echo base64_encode("ajukan_permohonan"); ?>" >

            <!-- Input hidden id_asesi , token , role -->
            <input type="hidden" value="<?php echo $id_asesi;?>" name="id_asesi" />
            <input type="hidden" value="<?php echo $token; ?>" name="token" />
            <input type="hidden" value="<?php echo $role; ?>" name="role" />
            <!-- End input hidden -->


            <!-- Row -->
            <div class="row">


              <!-- Main Box -->
              <div class="col col-md-12 panel-group">


                <!-- Panel Panduan Mengisi -->
                <div class="panel box box-solid">


                  <!-- Judul Panel -->
                  <a data-toggle="collapse" data-parent="#accordion" href="#PanduanMengisi">
                    
                    <div class="box-header with-border">

                      <i class="fa fa-question-circle"></i>

                      <h3 class="box-title">
                        Panduan Mengisi Formulir Banding Asesmen
                      </h3>
                      

                    </div> 
                  </a>
                  <!--End Judul Panel-->


                    <!-- Isi -->
                    <div id="PanduanMengisi" class="panel-collapse collapse in">


                      <div class="panel-body">
                      Anda mempunyai hak mengajukan banding jika Anda mendapatkan hasil yang Tidak Sah dan/atau Proses Tidak Sah atau Tidak Adil. 
                      </div>


                    </div>
                    <!-- End Isi -->


                </div>
                <!-- End Panel Panduan Mengisi -->






                <!-- Box Data Sertifikasi -->
                <div class="panel box box-solid">


                  <!-- Box Header -->
                  <div class="panel-heading box-header with-border">
                    <i class="fa fa-user"></i>

                    <h3 class="box-title">Data Asesmen</h3>

                    <a href="#" class="fa fa-question-circle pull-right" type="button" data-toggle="modal" data-target="#PanduanDataSertifikasi"></a>
                    
                    <!--<a id="edit_btn" class="btn btn-primary btn-xs pull-right" name="submit">Ubah</a>-->
                  </div>
                  <!-- End Box Header -->


                  <!-- Box Body -->
                  <div class="box-body">


                    <!-- Tujuan Asesmen -->
                    <div class="form-group">

                      <label class="label-control">Nama Asesi</label>

                      <input class="form-control" type="text" name="nama_asesi" placeholder="Nama Asesi"/>

                    </div>


                    <div class="form-group">

                      <label class="label-control">Nama Asesor</label>

                      <input class="form-control" type="text" name="nama_asesor" placeholder="Nama Asesor"/>

                    </div>

                    <div class="form-group">
                      <label label class="label-control">Tanggal Asesmen</label>

                      <input class="form-control" type="date" name="tanggal_asesmen" placeholder="Tanggal Asesmen"/>

                    </div>


                    <div class="form-group">

                      <label class="label-control">Skema Sertifikasi</label>

                      <input class="form-control" type="text" name="nama_asesor" placeholder="Nama Asesor"/>

                    </div>

                    <div class="form-group">
                      <label label class="label-control">Nomor Skema</label>

                      <input class="form-control" type="date" name="tanggal_asesmen" placeholder="Tanggal Asesmen"/>

                    </div>



                  </div>
                  <!-- End Box Body -->


                </div>
                <!-- End Box Data Sertfikasi -->



                <!-- Box Data Bukti Kelengkapan Pemohon -->
                <div class="panel box box-solid">


                  <!-- Box Header -->
                  <div class="box-header with-border">

                    <i class="fa fa-list-alt"></i>

                    <h3 class="box-title">Pertanyaan Banding Asesmen</h3>

                    
                    <a href="#" class="fa fa-question-circle pull-right" data-toggle="modal" data-target="#PanduanSyaratDasar"></a>
                      
                    
                  </div>
                  <!-- End Box Header -->


                  <!-- Box Body -->
                  <div class="box-body">

                    <table class="table table-bordered">

                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="30%">Pertanyaan</th>
                          <th width="20%">Jawaban</th>

                        </tr>
                      </thead>

                      <tbody>

                        <tr>

                          <td>
                          1.
                          </td>

                          <td>
                          
                          Apakah Proses Banding telah dijelaskan kepada Anda? 
                          
                          </td>

                          <td>
                            <select name="pertanyaan_1" class="form-control">
                              <option value="" selected disabled hidden>Pilih Jawaban</option>
                              <option value="Ya">Ya</option>
                              <option value="Tidak">Tidak</option>
                            </select>
                          </td>

                        </tr>

                        <tr>

                          <td>
                          2.
                          </td>

                          <td>
                          
                          Apakah Anda telah mendiskusikan Banding dengan Asesor?  
                          
                          </td>

                          <td>
                            <select name="pertanyaan_2" class="form-control">
                              <option value="" selected disabled hidden>Pilih Jawaban</option>
                              <option value="Ya">Ya</option>
                              <option value="Tidak">Tidak</option>
                            </select>
                          </td>

                        </tr>

                        <tr>

                          <td>
                          3.
                          </td>

                          <td>
                          
                          Apakah Anda mau melibatkan “orang lain” membantu Anda dalam Proses Banding?   
                          
                          </td>

                          <td>
                            <select name="pertanyaan_3" class="form-control">
                              <option value="" selected disabled hidden>Pilih Jawaban</option>
                              <option value="Ya">Ya</option>
                              <option value="Tidak">Tidak</option>
                            </select>
                          </td>

                        </tr>

                      </tbody>

                    </table>


                  </div>
                  <!-- End Box Body -->


                </div>
                <!-- End Box Data Bukti Kelengkapan Pemohon -->


                <!-- Box Data Bukti Kelengkapan Pemohon -->
                <div class="panel box box-solid">


                  <!-- Box Header -->
                  <div class="box-header with-border">

                    <i class="fa fa-list-alt"></i>

                    <h3 class="box-title">Alasan Banding Asesmen</h3>

                    
                    <a href="#" class="fa fa-question-circle pull-right" data-toggle="modal" data-target="#PanduanSyaratDasar"></a>
                      
                    
                  </div>
                  <!-- End Box Header -->


                  <!-- Box Body -->
                  <div class="box-body">

                    <div class="form-group">
                        <label label class="label-control">Alasan Banding Asesmen</label>

                        <textarea class="form-control" rows="4" cols="50" name="tanggal_asesmen"
                        placeholder="Masukkan Alasan Banding Asesmen"></textarea>

                      </div>


                    </div>
                  <!-- End Box Body -->


                </div>
                <!-- End Box Data Bukti Kelengkapan Pemohon -->





                <!-- Box Tombol Ajukan -->
                <div class="panel box box-solid">


                  <div class="box-body">

                    <div data-toggle="modal" data-target="#ModalKonfirmasi" 
                    class="btn btn-primary btn-block">Ajukan Banding Asesmen</div>

                  </div>
                  <!-- /.box-body -->


                </div>


                <!-- Modal Konfirmasi Ajukan Permohonan Sertifikasi -->
                <div id="ModalKonfirmasi" class="modal fade" role="dialog" tabindex="-1">


                  <!-- Modal Dialog -->
                  <div class="modal-dialog modal-lg">


                    <!-- Modal content-->
                    <div class="modal-content">


                      <!-- Modal Header -->
                      <div class="modal-header">


                        <h4 class="modal-title text-center">Konfirmasi Ajukan Banding Asesmen</h4> 


                      </div>
                      <!-- End Modal Header -->


                      <!-- Modal body -->
                      <div class="modal-body text-center">

                        <h3 class="text-bold">
                          Ajukan Banding dengan data yang telah dimasukkan ?
                        </h3>

                        <h4>
                          Pastikan data yang Anda masukkan benar, karena Anda tidak bisa melakukan perubahan data !
                        </h4>

                      </div>


                      <!-- Modal body -->
                      <div class="modal-body">

                        <div class="form-group">
                        
                          <label class="label-control">Password</label>
                          <input class="form-control" type="password" name="password" required placeholder="Silahkan masukkan password Anda" />
                          <small id='passwordHelpBlock' 
                          class='label-control form-text text-muted'>Silahkan masukkan password Anda untuk validasi Ajukan Banding Asesmen</small>
                        
                        </div>


                      </div>
                      <!-- End Modal Body -->

                        


                      <!-- Button Batal dan Ajukan Permohonan -->
                      <div class="modal-footer justify-content-center">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ajukan Banding Asesmen</button>

                      </div>


                    </div>
                    <!-- End Modal Content -->


                  </div>
                  <!-- End Modal Dialog -->


                </div>
                <!-- End Modal Cek Syarat Dasar 1 -->


              </div>
              <!-- End Main Box -->


            </div>
            <!-- End Row 1 -->


          </form>


        </section>


      </div>


    </div>



    <!-- /.footer -->
    <?php
      include('footer.php');
    ?>



  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
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


  <!-- Script ON POP Over -->
  <script>
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();
      $('.popover-dismiss').popover({
        trigger: 'focus'
      });   
    });
  </script>


  <script>

    $(function () {
      $('.example-popover').popover({
        container: 'body'
      })
    })

  </script>


  <script>
    $(document).ready(function () {
      $('.sidebar-menu').tree()
    })
  </script>


</body>

</html>