<?php

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
  include 'config.php';
  include 'Bcrypt.php';

?>

<?php

$aksi = base64_decode($_GET['aksi']);


// Aksi = Register
if( $aksi == "register" ){


  // Cek Cek E-Mail dan No HP

    //  Panggil email dan no hp
    $email       = mysqli_real_escape_string($conn,$_POST['email']);
    $no_hp       = mysqli_real_escape_string($conn,$_POST['nohp']);

    $cek_nohp    = $conn->query("SELECT `no_hp` FROM `akun` WHERE `no_hp` = '$no_hp';");
    $cek_emailakun = $conn->query("SELECT `email` FROM `akun` WHERE `email` = '$email';");

    $cek_nohp    = mysqli_num_rows($cek_nohp);
    $cek_emailakun = mysqli_num_rows($cek_emailakun);

    // Cek row E-Mail dan No HP
    if ( $cek_nohp > 0 || $cek_emailakun > 0 ) {

      // Jika tidak avail , maka diasumsikan user telah register atau salah memasukkan email/nohp.
      // Karena satu asesi cuma punya satu email dan no hp.

      // Cek email terlebih dulu
      // jika email ada , button register disabled
      // Seharusnya pakai AJAX Search

        echo "
        
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Register Gagal',
                text:  'E-Mail atau No Telepon telah digunakan',
                icon: 'error',
                timer: 2000,
                showConfirmButton: true
              });  
            },1000); 
            
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=register');
            } ,1500); 
          </script>";


    exit;
    }
    // Jika E-Mail atau No HP sudah dipakai , maka kode berhenti disini

  // End Cek E-Mail dan No HP

  // DATA PRIBADI
  $nama_asesi     = mysqli_real_escape_string( $conn , $_POST['nama_asesi'] );

  $email        = mysqli_real_escape_string( $conn , $_POST['email'] );
  $verif_length = strlen($email);
  $token_verif  = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $verif_length);

  $email_verif = base64_encode($email) ;

  $link_batal = 'http://localhost/lsp/index.php?page=batal-daftar&email=' .$email_verif. '&token=' .$token_verif ;

  $link = 'http://localhost/lsp/index.php?page=auth&email=' .$email_verif. '&token=' .$token_verif;


  $header_email = '<b>Yth. Bapak/Ibu ' .$nama_asesi. '</b> <br>';


  $body_email = '<br>Berikut ini adalah link untuk melakukan verifikasi akun Anda <br>' .$link;

  $body_email = '<br>Terimakasih, Anda telah melakukan pendaftaran akun di website LSP Polibatam. Tinggal satu langkah lagi sebelum Anda dapat menggunakan layanan kami. <br> <br>' .$body_email. '<br>';


  $body_email = $body_email. '<br>Jika Anda merasa tidak melakukan pendaftaran ini, silahkan klik link berikut <br>';

  $body_email = $body_email. $link_batal;

  $body_email = $header_email. $body_email;

  $body_email = $body_email. '<br> <br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';

  

  // Kirim E-Mail Verifikasi
  require 'PHPMailerAutoload.php';
  require 'credential.php';

    $mail->addAddress($email);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML

    // $mail->addAttachment("./uploads/Asesi/11.jpg");
    // $mail->addAttachment("./uploads/Asesi/default.png");
    

    $mail->Subject = 'Link Verifikasi Akun LSP Polibatam';
    $mail->Body    = $body_email;
    // $mail->AltBody = $_POST['message'];

    if(!$mail->send()) {
      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal mengirim email verifikasi',
              text:  'Silahkan periksa kembali email Anda !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=register');
          } ,3000); 
        </script>";
  

    } else {


  // Buat ID Asesi

    // Mencari nilai max dari ID_Asesi
    $cari_max   = $conn->query("SELECT max(id_asesi) as idTerbesar FROM `asesi`");
    $data       = mysqli_fetch_assoc($cari_max);
      
    // Mengambil data di array dengan indeks idTerbesar
    $kodeAsesi  = $data['idTerbesar'];

    // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
    // dan diubah ke integer dengan (int)
    // A(1) S(2) 0(3) 0(4) 1(5)
    $urutan     = (int) substr($kodeAsesi, 2);

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $urutan = $urutan + 1;

    // membentuk kode barang baru
    // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
    // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
    // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
    $huruf      = "AS";
    $kodeAsesi  = $huruf . sprintf("%03s", $urutan);

  // End Buat ID Asesi


  // Insert Data Tabel Akun
    // DATA PRIBADI
    $nama_asesi     = mysqli_real_escape_string( $conn , $_POST['nama_asesi'] );

    // DATA AKUN
    $email        = mysqli_real_escape_string( $conn , $_POST['email'] );
    $password     = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role         = "Asesi";

    // Set Status Register
    $status         = "Not Verif" ;

    // Set timezone to Jakarta
    date_default_timezone_set('Asia/Jakarta');


    // Buat Timestamp
    $timestamp_akun   = date("Y-m-d H:i:s");


  // Insert Data Tabel Asesi

    // Data Status Data Diri
    $status_data_diri = "0" ;


  // Query input
  
  $input_akun = $conn->query("INSERT INTO `akun`(`email`, `password`, `role`, `nama`, `no_hp`, `timestamp_akun`, `status_akun`, `token_verif`) 
  VALUES ( '$email' , '$password' , '$role' , '$nama_asesi' , '$no_hp' , '$timestamp_akun' , '$status' , '$token_verif' )" );


  $input_asesi = $conn->query("INSERT INTO `asesi`(`id_asesi`, `email`, `status_data_diri`) 

  VALUES ('$kodeAsesi' , '$email' , '$status_data_diri' )");



  if ( $input_asesi === TRUE && $input_akun ) {

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Register Berhasil',
              text:  'Silahkan periksa email anda untuk link verifikasi',
              icon: 'success',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php');
          } ,3000); 
        </script>";


  exit;
  }

exit;
}

exit; // Exit , IF ( aksi == register )
} // End aksi = register



// Aksi = Verif Ulang
if ( $aksi == "verif_ulang" ) {

  include 'config.php';

  $email = base64_decode($_GET['email']) ;

  $verif_length = rand(1,99);
  $token2  = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $verif_length);

    // Update Token Verif

    $email_verif = base64_encode($email) ;

    $link = 'http://localhost/lsp/index.php?page=auth&&email=' .$email_verif. '&&token=' .$token2 ;
    
    $body_email = 'Klik link berikut untuk melakukan verifikasi akun Anda <br>' .$link;

    $body_email = 'Anda melakukan pendaftaran akun di website LSP Polibatam, tinggal satu langkah lagi sebelum Anda bisa menggunakan layanan kami. <br> <br>' .$body_email. '<br>';

    $link_batal = 'http://localhost/lsp/index.php?page=batal-daftar&&email=' .$email_verif. '&&token=' .$token2 ;
    $body_email = $body_email. '<br>Jika Anda merasa tidak melakukan pendaftaran, silahkan klik link berikut ini <br>';

    $body_email = $body_email. $link_batal;

    $body_email = $body_email. '<br> <br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';

    // Kirim E-Mail Verifikasi
    require 'PHPMailerAutoload.php';
    require 'credential.php';

    $mail->addAddress($email);     // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML
  
    $mail->Subject = 'Link Verifikasi Ulang Akun LSP Polibatam';
    $mail->Body    = $body_email;
    // $mail->AltBody = $_POST['message'];


    if ( !$mail->send() ) { 

      echo "
      
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

          <script type='text/javascript'>

            setTimeout(function () { 
  
              swal({
                title: 'E-Mail verifikasi gagal dikirim',
                text:  'Internal Error',
                icon: 'error',
                timer: 1500,
                showConfirmButton: true
              });  
            },10); 
            
            window.setTimeout(function(){ 
              window.location.replace('index.php');
            } ,1500); 
            
          </script>";

    exit;
    } 

    else {

      $sql_update_verif = $conn->query("UPDATE `akun` SET `token_verif` = '$token2' WHERE `email` = '$email' ");

      echo "
      
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

          <script type='text/javascript'>

            setTimeout(function () { 
  
              swal({
                title: 'E-Mail verifikasi berhasil dikirim',
                text:  'Silahkan Cek E-Mail Anda',
                icon: 'success',
                timer: 1500,
                showConfirmButton: true
              });  
            },10); 
            
            window.setTimeout(function(){ 
              window.location.replace('index.php');
            } ,1500); 
            
          </script>";

    exit;
    }


exit; // Exit , IF ( $aksi = verif ulang )
} // Jika aksi = verif ulang



// Aksi - Update (data diri)
if ( $aksi == "update" ) { //Jika aksi = update

  include "config.php" ;

  session_start();
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

  // Cek Login
    $token_session    = $_SESSION['token'] ;
    $idAsesi_session  = $_SESSION['id_asesi'] ;
    $role_session     = $_SESSION['role'] ;

    $token    = mysqli_real_escape_string( $conn, $_POST['token'] ) ;
    $id_asesi = mysqli_real_escape_string( $conn, $_POST['id_asesi'] ) ;
    $role     = mysqli_real_escape_string( $conn, $_POST['role'] ) ;

    if ( !isset( $token ) || empty( $token ) || $token != $token_session ||
        !isset( $id_asesi ) || empty( $id_asesi ) || $id_asesi != $idAsesi_session ||
        !isset( $role ) || empty( $role ) || $role != $role_session ||
        $role =! 'Asesi' || $role_session =! 'Asesi' ) {

      header("location: index.php?page=forbidden&aksi=not_login");


    exit;
    }
    // Jika tidak ada token atau id_Asesi , kode berhenti disini
  // End cek login

  // Data Akun
  $nama         = mysqli_real_escape_string($conn, $_POST['nama'] );
  $nohp         = mysqli_real_escape_string($conn, $_POST['nohp'] );
  $email_asesi  = mysqli_real_escape_string($conn, $_POST['email_asesi'] );

  $email_lama   = mysqli_real_escape_string($conn , $_POST['email_lama']);



  // Data Pribadi
  $no_nik       = mysqli_real_escape_string($conn, $_POST['no_nik'] );
  $tmpt_lahir   = mysqli_real_escape_string($conn, $_POST['tmpt_lahir'] );
  $tgl_lahir    = mysqli_real_escape_string($conn, $_POST['tgl_lahir'] );
  $jenkel       = mysqli_real_escape_string($conn, $_POST['jenkel'] );
  $kebangsaan   = mysqli_real_escape_string($conn, $_POST['kebangsaan'] );
  $alamat_rmh   = mysqli_real_escape_string($conn, $_POST['alamat_rmh'] );
  $kodepos      = mysqli_real_escape_string($conn, $_POST['kodepos'] );
  $notelp_rmh   = mysqli_real_escape_string($conn, $_POST['notelp_rmh'] );
  $telppribadi_perusahaan = mysqli_real_escape_string($conn, $_POST['telppribadi_perusahaan'] );
  $pendidikan   = mysqli_real_escape_string($conn, $_POST['pendidikan'] );
  
  // Data Perusahaan
  $nama_perusahaan    = mysqli_real_escape_string($conn, $_POST['nama_perusahaan'] );
  $jabatan            = mysqli_real_escape_string($conn, $_POST['jabatan'] );
  $email_perusahaan   = mysqli_real_escape_string($conn, $_POST['email_perusahaan'] );
  $telp_perusahaan    = mysqli_real_escape_string($conn, $_POST['telp_perusahaan'] );
  $fax_perusahaan     = mysqli_real_escape_string($conn, $_POST['fax_perusahaan'] );
  $alamat_perusahaan  = mysqli_real_escape_string($conn, $_POST['alamat_perusahaan'] );
  $kodepos_perusahaan = mysqli_real_escape_string($conn, $_POST['kodepos_perusahaan'] );


  // Update Status Data Diri
  $status_data_diri = '1';

  // Query update

  $update_akun  = $conn->query("UPDATE `akun` SET `email` = '$email_asesi' , `nama` = '$nama' , 
  `no_hp` = '$nohp' WHERE `email` = '$email_lama'; ") ;

  if ( $update_akun === FALSE ){

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Update Data Diri',
            text:  'Silahkan perikasa data Anda kembali',
            icon: 'success',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=identitas');
        } ,3000); 
      </script>";

  exit;
  }

  $_SESSION['nama'] = $nama;
  $_SESSION['email'] = $email_asesi;
  $_SESSION['no_hp'] = $nohp;
  

  $update_asesi = $conn->query("UPDATE `asesi` SET `status_data_diri` = '$status_data_diri' ,

  `no_nik` = '$no_nik' , `tmpt_lahir` = '$tmpt_lahir' , `tgl_lahir` = '$tgl_lahir' , `jenkel` = '$jenkel' , `kebangsaan` = '$kebangsaan' ,

  `alamat_rmh` = '$alamat_rmh' , `kodepos` = '$kodepos' , `notelp_rmh` = '$notelp_rmh' , `telppribadi_perusahaan` = '$telppribadi_perusahaan' ,
  
  `pendidikan` = '$pendidikan' ,   `nama_perusahaan` = '$nama_perusahaan' , `jabatan` = '$jabatan' , `email_perusahaan` = '$email_perusahaan' , 
  
  `telp_perusahaan` = '$telp_perusahaan' , `fax_perusahaan` = '$fax_perusahaan' , `alamat_perusahaan` = '$alamat_perusahaan' , 
  
  `kodepos_perusahaan` = '$kodepos_perusahaan' 
  
  WHERE `id_asesi` = '$id_asesi' " );

  // Cek Query
  if ( $update_asesi === TRUE ) {

    echo "
        
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Update Data Diri Berhasil',
            text:  'Silahkan tunggu , Anda akan dialihkan',
            icon: 'success',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php');
        } ,3000); 
      
      </script>";


  exit;
  } 
  
  if ( $update_asesi === FALSE ){

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Update Data Diri',
              text:  'Silahkan perikasa data Anda kembali',
              icon: 'success',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=identitas');
          } ,3000); 
        </script>";


  exit;
  }


exit; //Exit ,  IF ( aksi == update )
} //Jika aksi = update



// Aksi = Ajukan Permohonan
if ( $aksi == "ajukan_permohonan" ) {


  include "config.php" ;

  session_start();
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));



  // Cek Login
    $token_session    = $_SESSION['token'] ;
    $idAsesi_session  = $_SESSION['id_asesi'] ;
    $role_session     = $_SESSION['role'] ;

    $token    = mysqli_real_escape_string( $conn, $_POST['token'] ) ;
    $id_asesi = mysqli_real_escape_string( $conn, $_POST['id_asesi'] ) ;
    $role     = mysqli_real_escape_string( $conn, $_POST['role'] ) ;

    if ( !isset( $token ) || empty( $token ) || $token != $token_session ||
        !isset( $id_asesi ) || empty( $id_asesi ) || $id_asesi != $idAsesi_session ||
        !isset( $role ) || empty( $role ) || $role != $role_session ||
        $role =! 'Asesi' || $role_session =! 'Asesi' ) {

      header("location: index.php?page=forbidden&aksi=not_login");


    exit;
    }
    // Jika tidak ada token atau id_Asesi , kode berhenti disini
  // End cek login

  $password = mysqli_real_escape_string( $conn, $_POST['password']) ;

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`asesi` WHERE `akun`.`email` = `asesi`.`email` AND `asesi`.`id_asesi` = '$id_asesi'");

  $data = mysqli_fetch_assoc($cek_password);

  if ( !password_verify($password,$data['password']) ) {

    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Password Salah',
            text:  'Silahkan periksa kembali password yang Anda masukkan',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=ajukan_permohonan');
        } ,1500); 
      </script>";
  exit;
  }



  // Insert Data Permohonan


    // Buat ID Permohonan

      // Mencari nilai max dari ID_Asesi
      $cari_max   = $conn->query("SELECT max(id_permohonan) as idTerbesar FROM `permohonan`");
      $data       = mysqli_fetch_assoc($cari_max);

      // Mengambil data di array dengan indeks idTerbesar
      $kodePermohonan  = $data['idTerbesar'];

      // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
      // dan diubah ke integer dengan (int)
      // A(1) S(2) 0(3) 0(4) 1(5) p 1) 0 2) 0 3) 0 4) 1 5)
      $urutan     = (int) substr($kodePermohonan, 1);

      // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
      $urutan = $urutan + 1;

      // membentuk kode barang baru
      // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
      // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
      // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
      $huruf      = "P";
      $kodePermohonan  = $huruf . sprintf("%04s", $urutan);

    // End Buat ID Permohonan

    
    // Panggil Tujuan Asesmen dan ID Skema

      $tujuan_asesmen = mysqli_real_escape_string( $conn, $_POST['tujuan_asesmen'] ) ;
      $id_skema       = mysqli_real_escape_string($conn, $_POST['skema_sertifikasi'] ) ;
    
    // End Panggil Tujuan Asesmen dan ID Skema


    // Set timezone to Jakarta
    date_default_timezone_set('Asia/Jakarta');


    // Buat Timestamp
    $timestamp_permohonan   = date("Y-m-d H:i:s");


    $status_permohonan      = 'Menunggu Validasi Permohonan';

    // Query Permohonan

    $insert_permohonan = $conn->query("INSERT INTO `permohonan` (`id_permohonan` , `id_asesi` , `id_skema` , `tujuan_asesmen` , `timestamp_permohonan` , `status_permohonan` ) 
        VALUES ( '$kodePermohonan' , '$id_asesi' , '$id_skema' , '$tujuan_asesmen' , '$timestamp_permohonan' , '$status_permohonan' ) " ) ;
    

    if ( $insert_permohonan === FALSE ) {


      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Mengajukan Permohonan Asesmen',
              text                :  'Silahkan periksa data Anda kembali',
              icon                : 'error',
              timer               : 3000,
              showConfirmButton   : true
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=ajukan_permohonan');
          } ,3000); 
        </script> ";

    exit;
    }



  // End Insert Data permohonan



  // Input Data Syarat Dasar

      // Mencari panjang syarat dasar

      $arr_length         = count($_POST['cek_persyaratan']);

      // Perulangan Berdasarkan panjang syarat_dasar
      for ( $x = 1; $x <= $arr_length ; $x++ ) {


        // Panggil Id_Syarat
        $id_syarat = $_POST ['id_syarat'] [$x];
        $cek_persyaratan = $_POST ['cek_persyaratan'] [$x];


        // Input File Syarat Dasar       
      

          $insert_syarat_permohonan = $conn->query("INSERT INTO `syarat_permohonan`(`id_permohonan`, 
            `id_syarat`, `terpenuhi` ) 
            VALUES ('$kodePermohonan' , '$id_syarat' , '$cek_persyaratan') ;" );

          if ( $insert_syarat_permohonan === FALSE ) {

          $del_permohonan = $conn->query("DELETE FROM `permohonan` WHERE `id_permohonan` = $kodePermohonan");

            echo "
      
            <link rel='stylesheet' href='dist/css/app.css'>
            <script src='dist/js/sweetalert.min.js'></script>


            <script type='text/javascript'>
              setTimeout(function () { 
                swal({
                  title: 'Gagal Mengajukan Permohonan',
                  text:  'Silahkan periksa kembali Data yang Anda masukkan! ',
                  icon: 'error',
                  timer: 2000,
                });  
              },10); 

              window.setTimeout(function(){ 
                window.location.replace('index.php?page=ajukan_permohonan');
              } ,3000); 
            </script>";

          exit;          
          }


        // End Input

      }

  // End Input Data Syarat Dasar


  // Input portofolio

      // Mencari panjang syarat dasar

      $arr_length         = count($_POST['jdl_porto_input']);

      // Perulangan Berdasarkan panjang syarat_dasar
      for ( $x = 1; $x <= $arr_length ; $x++ ) {


          // Membuat kode portofolio otomatis
          $cari_max = $conn->query("SELECT max(id_portofolio) as idTerbesar FROM `portofolio`");
            $data = mysqli_fetch_assoc($cari_max);
            
            // Mengambil data di array dengan indeks idTerbesar
            $kodePortofolio = $data['idTerbesar'];

            // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
            // dan diubah ke integer dengan (int)
            // POR0001
            // A(1) S(2) 0(3) 0(4) 1(5) S 1 K 2 M 3 0 4 0 1
            $urutan = (int) substr($kodePortofolio, 3);

            // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
            $urutan = $urutan + 1 ;

            // membentuk kode barang baru
            // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
            $huruf = "POR";
            $kodePortofolio = $huruf . sprintf("%04s", $urutan);
          
          // End Membuat kode portofolio otomatis

        // Case Jika Upload File 


        // Input data portofolio     
        
          // Penamaan File // P001-SY001.pdf
          

          // Ambil judul portofolio
          $jdl_portofolio = $_POST["jdl_porto_input"] [$x];


          $ekstensi = explode('.',$tmp_name);
          $ekstensi = end($ekstensi);
          // $ekstensi = strtolower ( $ekstensi );

          $valid_ext = array('jpg','jpeg','png','bmp','jfif','pdf');

          // if ( !in_array($ekstensi, $valid_ext) ) {  

          //   $del_permohonan = $conn->query("DELETE FROM `permohonan` WHERE `id_permohonan` = '$kodePermohonan' ");

          //   echo "
        
          //   <link rel='stylesheet' href='dist/css/app.css'>
          //   <script src='dist/js/sweetalert.min.js'></script>


          //   <script type='text/javascript'>
          //     setTimeout(function () { 
          //       swal({
          //         title: 'Gagal Mengupload File',
          //         text:  'Format file tidak didukung !',
          //         icon: 'error',
          //         timer: 2000,
          //       });  
          //     },10); 

          //     window.setTimeout(function(){ 
          //       window.location.replace('index.php?page=ajukan_permohonan');
          //     } ,3000); 
          //   </script>";

          //   exit;
          // }



          // Membuat nama file baru
          $new_name = $kodePermohonan. '-' .$kodePortofolio. '-' .$id_asesi. '-' .$jdl_portofolio. '.pdf';

          $insert_portofolio = $conn->query("INSERT INTO `portofolio`(`id_portofolio`, 
            `id_permohonan`, `portofolio`, `keterangan` ) 
            VALUES ('$kodePortofolio' , '$kodePermohonan' , '$new_name' , '$jdl_portofolio') ;" );


          if ( $insert_portofolio === FALSE ) {

            $del_permohonan = $conn->query("DELETE FROM `permohonan` WHERE `id_permohonan` = $kodePermohonan");

              echo "
        
              <link rel='stylesheet' href='dist/css/app.css'>
              <script src='dist/js/sweetalert.min.js'></script>


              <script type='text/javascript'>
                setTimeout(function () { 
                  swal({
                    title: 'Gagal Mengajukan Permohonan',
                    text:  'Silahkan periksa kembali Data yang Anda masukkan! ',
                    icon: 'error',
                    timer: 2000,
                  });  
                },10); 

                window.setTimeout(function(){ 
                  window.location.replace('index.php?page=ajukan_permohonan');
                } ,3000); 
              </script>";

          exit;          
          }
        
        // Input data portofolio



        // Pindahkan File Portofolio
          // Panggil file yang di upload
          $tmp_name = $_FILES["file_porto_input"] ["tmp_name"] [$x];

          $error = $_FILES["file_porto_input"] ["error"] [$x];


          // Direktori simpan
          $lokasi = "uploads/Asesi/$id_asesi/$kodePermohonan/Portofolio/";


          // Buat direktori simpan jika tidak ada
          if( !is_dir ( $lokasi ) ) {
            //Directory does not exist, so lets create it.
            mkdir( $lokasi, 0755, true );
          }


          // Dir + nama file baru
          $lokasi = "uploads/Asesi/$id_asesi/$kodePermohonan/Portofolio/" .$new_name;

          $pindahkan_file = move_uploaded_file( $tmp_name , $lokasi );

          // Cek apakah upload (memindahkan file) berhasil
          if( $pindahkan_file === FALSE )  {     

            $sql_delete = $conn->query("DELETE FROM `permohonan` WHERE `id_permohonan` = '$kodePermohonan';");

            echo "

              <link rel='stylesheet' href='dist/css/app.css'>
              <script src='dist/js/sweetalert.min.js'></script>

              <script type='text/javascript'>
                setTimeout(function () { 
                  swal({
                    title: 'Gagal Upload File Portofolio $new_name | $tmp_name | $error',
                    text:  'Silahkan periksa kembali file Anda!',
                    icon: 'error',
                    timer: 2000,
                  });  
                },10); 
      
                window.setTimeout(function(){ 
                  window.location.replace('index.php');
                } ,3000); 
                </script>";
      
          exit;
          }


          if ( $pindahkan_file === TRUE ) {

            $berhasil_permohonan = '1';
          }



        // End Input

      }


      if ( $berhasil_permohonan === '1' ) {


          // Buat FR APL 01

        $id_permohonan = $kodePermohonan;

        include 'cetak_APL_01.php';


        $tambah_APL_01 = $conn->query("UPDATE `permohonan` SET `file_APL_01` = '$file_APL_01' WHERE `id_permohonan` = '$id_permohonan' ");

        echo "
        
              <link rel='stylesheet' href='dist/css/app.css'>
              <script src='dist/js/sweetalert.min.js'></script>


              <script type='text/javascript'>
                setTimeout(function () { 
                  swal({
                    title: 'Berhasil Mengajukan Permohonan',
                    text:  'Silahkan menunggu validasi Admin LSP Polibatam!',
                    icon: 'success',
                    timer: 2000,
                  });  
                },10); 

                window.setTimeout(function(){ 
                  window.location.replace('index.php');
                } ,3000); 
              </script>";
            

      }
  // End Input portofolio








exit; // End IF ( aksi == ajukan_permohonan )
} // Jika aksi = ajukan permohonan


// Aksi = Asesmen Mandiri
if ( $aksi == "asesmen_mandiri" ) {

  include "config.php" ;

  session_start();
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

  // Cek Login
  $token_session    = $_SESSION['token'] ;
  $idAsesi_session  = $_SESSION['id_asesi'] ;
  $role_session     = $_SESSION['role'] ;

  $token    = mysqli_real_escape_string( $conn, $_POST['token'] ) ;
  $id_asesi = mysqli_real_escape_string( $conn, $_POST['id_asesi'] ) ;
  $role     = mysqli_real_escape_string( $conn, $_POST['role'] ) ;

  if ( !isset( $token ) || empty( $token ) || $token != $token_session ||
      !isset( $id_asesi ) || empty( $id_asesi ) || $id_asesi != $idAsesi_session ||
      !isset( $role ) || empty( $role ) || $role != $role_session ||
      $role =! 'Asesi' || $role_session =! 'Asesi' ) {

    header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }
  // Jika tidak ada token atau id_Asesi , kode berhenti disini
  // End cek login

  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );

  // Validasi Password
  $password = mysqli_real_escape_string( $conn, $_POST['password']) ;

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`asesi` WHERE `akun`.`email` = `asesi`.`email` AND `asesi`.`id_asesi` = '$id_asesi'");

  $data = mysqli_fetch_assoc($cek_password);

  if ( !password_verify($password,$data['password']) ) {

    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Password Salah',
            text:  'Silahkan periksa kembali password yang Anda masukkan',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=asesmen_mandiri&&id_permohonan=$id_permohonan');
        } ,1500); 
      </script>";
  exit;
  }
  // End Validasi Password



  // Panggil ID Permohonan dan ID Skema

  $id_skema = mysqli_real_escape_string( $conn, $_POST['id_skema'] );

  // End

  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );

  // Insert Asesmen Mandiri


    // Buat ID ID Asesmen Mandiri

      // Mencari nilai max dari ID_Asesi
      $cari_max   = $conn->query("SELECT max(id_asesmen_mandiri) as idTerbesar FROM `asesmen_mandiri`");
      $data       = mysqli_fetch_assoc($cari_max);

      // Mengambil data di array dengan indeks idTerbesar
      $kodeAM  = $data['idTerbesar'];

      // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
      // dan diubah ke integer dengan (int)
      // A(1) S(2) 0(3) 0(4) 1(5) p 1) 0 2) 0 3) 0 4) 1 5)
      $urutan     = (int) substr($kodeAM, 2);

      // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
      $urutan = $urutan + 1;

      // membentuk kode barang baru
      // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
      // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
      // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
      $huruf      = "AM";
      $kodeAM  = $huruf . sprintf("%04s", $urutan);

    // End Buat ID Asesmen Mandiri


    // Set timezone to Jakarta
    date_default_timezone_set('Asia/Jakarta');


    // Buat Timestamp
    $timestamp_asesmen_mandiri   = date("Y-m-d H:i:s");


    // Query Permohonan

    $insert_AM = $conn->query("INSERT INTO `asesmen_mandiri`(`id_asesmen_mandiri`,`id_permohonan`, `timestamp_asesmen_mandiri`)
        VALUES ( '$kodeAM' , '$id_permohonan' , '$timestamp_asesmen_mandiri' ) ") ;


    if ( $insert_AM === FALSE ) {


      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Asesmen Mandiri',
              text                :  'Silahkan periksa data Anda kembali Ini $kodeAM  $id_permohonan $timestamp_asesmen_mandiri',
              icon                : 'error',
              timer               : 3000,
              showConfirmButton   : true
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=asesmen_mandiri&&id_permohonan=$id_permohonan');
          } ,3000); 
        </script> ";

    exit;
    }


  // Update permohonan
  $sql_update_permohonan = $conn->query("UPDATE `permohonan` SET `status_permohonan` = 'Menunggu Validasi Asesmen Mandiri' WHERE `id_permohonan` = '$id_permohonan'");

  if ( $sql_update_permohonan === FALSE ) {

    $del_AM = $conn->query("DELETE FROM `asesmen_mandiri` WHERE `id_asesmen_mandiri` = '$kodeAM' ");

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title               : 'Gagal Asesmen Mandiri',
            text                :  'Silahkan periksa data Anda kembali',
            icon                : 'error',
            timer               : 3000,
            showConfirmButton   : true
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=asesmen_mandiri&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script> ";

  exit;
  }



  // End Insert Asesmen Mandiri


  // Hehheh
  $sqlunit = $conn->query("SELECT * FROM `unit_skema` WHERE `id_skema` = '$id_skema'");
  while ( $unit = mysqli_fetch_assoc($sqlunit) ) {
    
    $id_unit = $unit['id_unit'];

    $sqlelemen = $conn->query("SELECT * FROM `elemen_unit` WHERE `id_unit` = '$id_unit'");
    while ( $elemen = mysqli_fetch_assoc($sqlelemen) ) {
      
      $id_elemen = $elemen['id_elemen'];

      $sqlkuk = $conn->query("SELECT * FROM `kriteria_unjuk_kerja` WHERE `id_elemen` = '$id_elemen'");
      while ( $kuk = mysqli_fetch_assoc($sqlkuk) ) { 
        
        $id_kuk = $kuk['id_kuk'];

        $nilai_kuk = mysqli_real_escape_string( $conn, $_POST['nilai_kuk'] [$id_kuk] );

        // Buat ID AM kuk

          // Mencari nilai max dari ID_Asesi
          $cari_max   = $conn->query("SELECT max(id_asesmen_mandiri_kuk) as IDTerbesar FROM `asesmen_mandiri_kuk` ");
          $data       = mysqli_fetch_assoc($cari_max);

          // Mengambil data di array dengan indeks idTerbesar
          $kodeAMK  = $data['IDTerbesar'];

          // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
          // dan diubah ke integer dengan (int)
          // A(1) S(2) 0(3) 0(4) 1(5) p 1) 0 2) 0 3) 0 4) 1 5)
          $urutan     = (int) substr($kodeAMK, 3);

          // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
          $urutan = $urutan + 1;

          // membentuk kode barang baru
          // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
          // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
          // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
          $huruf      = "AMK";
          $kodeAMK  = $huruf . sprintf("%04s", $urutan);

        // End Buat ID AM kuk

          $insert_AM_kuk = $conn->query("INSERT INTO `asesmen_mandiri_kuk`(`id_asesmen_mandiri_kuk`,`id_asesmen_mandiri`, `id_kuk`, `kompetensi`) VALUES 
          ('$kodeAMK','$kodeAM','$id_kuk' , '$nilai_kuk'); ");



          if ( $insert_AM_kuk === FALSE ) { 

            // Kalau gagal Input AM KUK maka hapus data asesmen mandiri 

            $del_asesmen = $conn->query("DELETE FROM `asesmen_mandiri` WHERE `id_asesmen_mandiri` = '$kodeAM' ");

            echo "
            <link rel='stylesheet' href='dist/css/app.css'>
            <script src='dist/js/sweetalert.min.js'></script>
              
            <script type='text/javascript'>
              setTimeout(function () { 
                swal({
                  title               : 'Gagal Asesmen Mandiri',
                  text                :  'Silahkan tunggu !',
                  icon                : 'error',
                  timer               : 3000,
                  showConfirmButton   : true
                });  
              },10); 
        
              window.setTimeout(function(){ 
                window.location.replace('index.php?page=asesmen_mandiri&&id_permohonan=$id_permohonan');
              } ,3000); 
            </script> ";


          exit;
          }

          if ( $insert_AM_kuk === TRUE ) {

            $berhasil_AM_kuk = TRUE;
          }
          


      }

    }
  }

  if ( $berhasil_AM_kuk === TRUE ) {

    $id_asesmen_mandiri = $kodeAM;
    $id_permohonan = $id_permohonan;

    include 'cetak_APL_02.php';

    
    $tambah_APL_01 = $conn->query("UPDATE `asesmen_mandiri` SET `file_APL_02` = '$file_APL_02' WHERE `id_asesmen_mandiri` = '$id_asesmen_mandiri' ");

    $id_asesi = $conn->query("SELECT `asesi`.`id_asesi` FROM `permohonan` , `asesi`  WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `permohonan`.`id_permohonan` = '$id_permohonan' ");
    $id_asesi = mysqli_fetch_assoc($id_asesi) ['id_asesi'];


    // DATA PRIBADI
    $nama = $conn->query("SELECT `akun`.`nama` , `akun`.`email` FROM `akun`,`asesi` WHERE `asesi`.`email` = `akun`.`email` AND `asesi`.`id_asesi` = '$id_asesi '");
    $da = mysqli_fetch_assoc($nama); $nama_asesi= $da['nama']; $email_asesi = $da['email'];

    $header_email = '<b>Yth. Bapak/Ibu ' .$nama_asesi. '</b> <br>';

    $body_email = '<br>Berikut ini merupakan hasil dari asesmen mandiri yang telah Anda lakukan di website LSP Polibatam. <br> <br>';
    // $body_email = $body_email. 'ID Asesmen Mandiri : ' .$id_AM. '<br> Nama Skema : ' .$nama_skema. '<br> Tujuan Asesmen : ' .$tujuan_asesmen. '<br> ';

    // $body_email = $body_email. 'Status Permohonan : <b>' .$rekomendasi. '</b> <br> Catatan : <b>' .$catatan. '</b>';


    $body_email = $header_email. $body_email. '<br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';


    // Kirim E-Mail Verifikasi
    require 'PHPMailerAutoload.php';
    require 'credential.php';


      $mail->addAddress($email_asesi);     // Add a recipient
      $mail->isHTML(true);                                  // Set email format to HTML

      $mail->addAttachment("./uploads/Asesi/".$id_asesi. "/" .$id_permohonan. "/".$file_APL_02);
      // $mail->addAttachment("./uploads/Asesi/default.png");
      

      $mail->Subject = 'Pemberitahuan Asesmen Mandiri | LSP Polibatam';
      $mail->Body    = $body_email;

      if( !$mail->send() ) {

                // Kalau berhasil kirim E-Mail
                echo "
                <link rel='stylesheet' href='dist/css/app.css'>
                <script src='dist/js/sweetalert.min.js'></script>
        
                <script type='text/javascript'>
                  setTimeout(function () { 
                    swal({
                      title: 'Gagal Mengirim E-Mail Notifikasi',
                      text:  'Silahkan Tunggu!',
                      icon: 'error',
                      timer: 2000,
                    });  
                  },10); 
        
                  window.setTimeout(function(){ 
                    window.location.replace('index.php?page=kelola_asesmen_asesi');
                  } ,2000); 
                </script>";

      } else {


        echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
          
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Asesmen Mandiri',
              text                :  'Silahkan tunggu !',
              icon                : 'success',
              timer               : 1500,
              showConfirmButton   : true
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=data_permohonan');
          } ,1500); 
        </script> ";

      }

  }

exit;
}



?>