<?php

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
  include 'config.php';
  include 'Bcrypt.php';

  session_start();

  // Cek Login
  $token_session    = $_SESSION['token'] ;
  $idAsesor_session  = $_SESSION['id_asesor'] ;
  $role_session     = $_SESSION['role'] ;

  $token      = mysqli_real_escape_string( $conn, $_POST['token'] ) ;
  $id_asesor  = mysqli_real_escape_string( $conn, $_POST['id_asesor'] ) ;
  $role       = mysqli_real_escape_string( $conn, $_POST['role'] ) ;

  if ( $token != $token_session || $role_session != 'Asesor' ||
      $role != 'Asesor' || $token != $token_session ) {

    header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  } 

?>

<?php

$aksi = base64_decode( $_GET['aksi'] );


// Aksi = Asesmen Mandiri
if ( $aksi == "validasi_asesmen_mandiri" ) {

  include 'config.php';

  $password = mysqli_real_escape_string( $conn, $_POST['password']) ;

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`asesor` WHERE `akun`.`email` = `asesor`.`email` AND `asesor`.`id_asesor` = '$id_asesor'");

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
          window.location.replace('index.php?page=lihat_data_permohonan&&id_permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }

    // Set timezone to Jakarta
    date_default_timezone_set('Asia/Jakarta');


    // Buat Timestamp
    $timestamp_validasi   = date("Y-m-d H:i:s");



  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );
  $rekomendasi_asesor = mysqli_real_escape_string( $conn, $_POST['rekomendasi_asesor'] );

  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] ) ;
  $id_AM = mysqli_real_escape_string( $conn, $_POST['id_AM'] );


  $update_rekomendasi = $conn->query("UPDATE `asesmen_mandiri` SET `id_asesor` = '$id_asesor' , 
  `rekomendasi_asesor`= '$rekomendasi_asesor' , `timestamp_tinjauan_asesor`= '$timestamp_validasi'
  WHERE `id_permohonan` = '$id_permohonan' AND `id_asesmen_mandiri` = '$id_AM'");

  if ( $update_rekomendasi === FALSE ) { 

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Validasi Asesmen Mandiri',
            text:  'Mohon Tunggu',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_data_asesmen_mandiri&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";


  exit;
  }


  if ( $rekomendasi_asesor == 'Belum dapat lanjut uji kompetensi') {
    $update_status_permohonan = $conn->query("UPDATE `permohonan` SET `status_permohonan` = 'Ditolak' WHERE `id_permohonan` = '$id_permohonan'");
  }

  if ( $rekomendasi_asesor == 'Lanjut uji kompetensi') {
    $update_status_permohonan = $conn->query("UPDATE `permohonan` SET `status_permohonan` = 'Menunggu Validasi Berkas Asesmen' WHERE `id_permohonan` = '$id_permohonan'");
  }


  include 'cetak_APL_02.php';

    // kirim email ke asesi

  $id_asesi = $conn->query("SELECT `asesi`.`id_asesi` FROM `permohonan` , `asesi`  WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `permohonan`.`id_permohonan` = '$id_permohonan' ");
  $id_asesi = mysqli_fetch_assoc($id_asesi) ['id_asesi'];


  // DATA PRIBADI
  $nama = $conn->query("SELECT `akun`.`nama` , `akun`.`email` FROM `akun`,`asesi` WHERE `asesi`.`email` = `akun`.`email` AND `asesi`.`id_asesi` = '$id_asesi '");
  $da = mysqli_fetch_assoc($nama); $nama_asesi= $da['nama']; $email_asesi = $da['email'];

  $header_email = '<b>Yth. Bapak/Ibu ' .$nama_asesi. '</b> <br>';

  $body_email = '<br>Berikut ini merupakan hasil validasi asesmen mandiri yang telah Anda lakukan oleh tim Administrasi LSP Polibatam. <br> <br>';
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
    

    $mail->Subject = 'Pemberitahuan Validasi Asesmen Mandiri | LSP Polibatam';
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
            title: 'Berhasil Validasi Asesmen Mandiri',
            text:  'Mohon Tunggu, Anda akan dialihkan',
            icon: 'success',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=lihat_data_permohonan&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";

    }




exit;
}


if ( $aksi == "tambah_mapa" ) {


  $id_asesor = mysqli_real_escape_string( $conn, $_POST['id_asesor'] );
  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );
  $id_unit = mysqli_real_escape_string( $conn, $_POST['id_unit'] );


  $cek_password = $conn->query("SELECT akun.password FROM akun,asesor WHERE akun.email = asesor.email AND asesor.id_asesor = '$id_asesor' ");
  $data = mysqli_fetch_assoc($cek_password);

  $password = mysqli_real_escape_string( $conn, $_POST['password'] );

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
          window.location.replace('tambah_mapa?id_unit=$id_unit&&id_permohonan=$id_permohonan');
        } ,1500); 
      </script>";

  exit;
  }


  $cek_mapa = $conn->query("SELECT `id_mapa` FROM `mapa` WHERE `id_unit` = '$id_unit' AND `id_permohonan` = '$id_permohonan' ");

  if ( mysqli_num_rows($cek_mapa) != 0 ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menambah MAPA',
            text:  'MAPA untuk ID Unit yang dipilih sudah ada',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";

  exit;
  }

  $kandidat = mysqli_real_escape_string( $conn, $_POST['kandidat'] );
  $tujuan_asesmen = mysqli_real_escape_string( $conn, $_POST['tujuan_asesmen'] );


    // Konteks Asesmen
    $lingkungan = mysqli_real_escape_string( $conn, $_POST['lingkungan'] );
    $peluang = mysqli_real_escape_string( $conn, $_POST['peluang'] );
    $hubungan_standar = mysqli_real_escape_string( $conn, $_POST['hubungan_standar'] );
    $komentar_hubungan = mysqli_real_escape_string( $conn, $_POST['komentar_hubungan'] );

    $pelaku_asesmen = mysqli_real_escape_string( $conn, $_POST['pelaku_asesmen'] );
    $orang_konfirmasi = mysqli_real_escape_string( $conn, $_POST['orang_konfirmasi'] );
    $tolok_ukur = mysqli_real_escape_string( $conn, $_POST['tolok_ukur'] );
    $tolok_ukur_nama = mysqli_real_escape_string( $conn, $_POST['tolok_ukur_nama'] );


  $karekteristik_kandidat = mysqli_real_escape_string( $conn, $_POST['karekteristik_kandidat'] );
  $kebutuhan_kontekstualisasi = mysqli_real_escape_string( $conn, $_POST['kebutuhan_kontekstualisasi'] );
  $saran = mysqli_real_escape_string( $conn, $_POST['saran'] );
  $peluang_asesmen = mysqli_real_escape_string( $conn, $_POST['Peluang_untuk_kegiatan_asesmen_terintegrasi_dan_mencatat_setiap_perubahan_yang_diperlukan_untuk_alat_asesmen'] );


  // Buat ID MAPA

    $big = $conn->query("SELECT MAX(id_mapa) as `IDTerbesar` FROM `mapa`");
    $MAPA = mysqli_fetch_assoc( $big ) ['IDTerbesar'];

    $id_terbesar = (int) substr( $MAPA, 4 );
    $urutan = $id_terbesar + 1;

    $huruf = "MAPA";
    $id_mapa = $huruf . sprintf("%04s", $urutan); 

  // End Buat ID MAPA


  // Set timezone to Jakarta
  date_default_timezone_set('Asia/Jakarta');


  // Buat Timestamp
  $timestamp_mapa = date("Y-m-d H:i:s"); 
  



  $insert_mapa = $conn->query("INSERT INTO `mapa` (`id_mapa`, `id_permohonan`, `id_unit`, `kandidat`, `tujuan_asesmen`, `lingkungan`, 
  `peluang_bukti`, `hubungan_standar`,`komentar_hubungan_standar`, `pelaku_asesmen`, `konfirmasi`, `tolok_ukur`, `tolok_ukur_nama`, `karakteristik_kandidat`, `kebutuhan_kontekstualisasi`, 
  `saran`, `peluang`, `dibuat_oleh`, `timestamp_mapa`) VALUES ('$id_mapa' , '$id_permohonan' , '$id_unit' , '$kandidat' , '$tujuan_asesmen'
  ,'$lingkungan' , '$peluang' , '$hubungan_standar' , '$komentar_hubungan', '$pelaku_asesmen' , '$orang_konfirmasi' , '$tolok_ukur' , '$tolok_ukur_nama' , '$karekteristik_kandidat'
  , '$kebutuhan_kontekstualisasi' , '$saran' , '$peluang_asesmen' , '$id_asesor' , '$timestamp_mapa')");

  if ( $insert_mapa === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menambah MAPA',
            text:  'Mohon Tunggu',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";

  exit;
  }

  // While utk ID_KUK

  $sql_elemen = $conn->query("SELECT `id_elemen` FROM `elemen_unit` WHERE `id_unit` = '$id_unit'");
  while ( $elemen = mysqli_fetch_assoc($sql_elemen) ) {
    
    $id_elemen = $elemen['id_elemen'];

    $sql_kuk = $conn->query("SELECT * FROM `kriteria_unjuk_kerja` WHERE `id_elemen` = '$id_elemen'");

    while ( $kuk = mysqli_fetch_assoc($sql_kuk) ) {
      

      // Buat ID Rencana Asesmen

      $big = $conn->query("SELECT MAX(id_rencana) as `IDTerbesar` FROM `rencana_asesmen`");
      $rencana_asesmen = mysqli_fetch_assoc( $big ) ['IDTerbesar'];

      $id_terbesar = (int) substr( $rencana_asesmen, 1 );
      $urutan = $id_terbesar + 1;

      $huruf = "R";
      $rencana_asesmen = $huruf . sprintf("%05s", $urutan);


      // ID KUK
      $id_kuk = $kuk['id_kuk'];

      $bukti_bukti = mysqli_real_escape_string( $conn, $_POST['bukti_bukti'] [$id_kuk] );
      $jenis_bukti = mysqli_real_escape_string( $conn, $_POST['jenis_bukti'] [$id_kuk] );
      $metode_asesmen = mysqli_real_escape_string( $conn, $_POST['metode_asesmen'] [$id_kuk] );
      $perangkat_asesmen = mysqli_real_escape_string( $conn, $_POST['perangkat_asesmen'] [$id_kuk] );

      $insert_rencana = $conn->query("INSERT INTO `rencana_asesmen`(`id_rencana`, `id_mapa`, `id_kuk`, `bukti`, 
      `jenis_bukti`, `metode_asesmen`, `perangkat_asesmen`) VALUES( '$rencana_asesmen' , '$id_mapa' , '$id_kuk' ,
      '$bukti_bukti' , '$jenis_bukti' , '$metode_asesmen' , '$perangkat_asesmen') ");

      if ( $insert_rencana === FALSE ) { 

            // Kalau gagal Input AM KUK maka hapus data asesmen mandiri 

            $del_mapa = $conn->query("DELETE FROM `mapa` WHERE `id_mapa` = '$id_mapa' ");

            echo "
            <link rel='stylesheet' href='dist/css/app.css'>
            <script src='dist/js/sweetalert.min.js'></script>
              
            <script type='text/javascript'>
              setTimeout(function () { 
                swal({
                  title               : 'Gagal Menyimpan MAPA',
                  text                :  'Silahkan tunggu !',
                  icon                : 'error',
                  timer               : 3000,
                  showConfirmButton   : true
                });  
              },10); 
        
              window.setTimeout(function(){ 
                window.location.replace('index.php?page=tambah_mapa&&id_unit=$id_unit&id_permohonan=$id_permohonan');
              } ,3000); 
            </script> ";


      exit;
      }


      if ( $insert_rencana === TRUE ) { 

        // Kalau gagal Input AM KUK maka hapus data asesmen mandiri 
        $cek_rencana = TRUE;

      }


    }
  }


  if ( $cek_rencana === FALSE ) { 

    $del_mapa = $conn->query("DELETE FROM `mapa` WHERE `id_mapa` = '$id_mapa' ");

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title               : 'Gagal Menyimpan MAPA',
          text                :  'Silahkan tunggu !',
          icon                : 'error',
          timer               : 3000,
          showConfirmButton   : true
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=tambah_mapa&&id_unit=$id_unit&id_permohonan=$id_permohonan');
      } ,3000); 
    </script> ";


  exit;
  }

  if ( $cek_rencana === TRUE ) { 

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title               : 'Berhasil Menyimpan MAPA',
          text                :  'Silahkan tunggu !',
          icon                : 'success',
          timer               : 3000,
          showConfirmButton   : true
        });  
      },10); 

      window.setTimeout(function(){
        window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
      } ,3000); 
    </script> ";


  exit;
  }

exit;
}

if ( $aksi == "hapus_mapa" ) {


  $id_mapa = mysqli_real_escape_string( $conn, $_POST['id_mapa'] );
  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );
  $id_unit = mysqli_real_escape_string( $conn, $_POST['id_unit'] );

  $id_asesor = mysqli_real_escape_string( $conn, $_POST['id_asesor'] );

  $cek_password = $conn->query("SELECT akun.password FROM akun,asesor WHERE akun.email = asesor.email AND asesor.id_asesor = '$id_asesor' ");
  $data = mysqli_fetch_assoc($cek_password);

  $password = mysqli_real_escape_string( $conn, $_POST['password'] );

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
          window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
        } ,1500); 
      </script>";
  exit;
  }

  $del_mapa = $conn->query("DELETE FROM `mapa` WHERE `id_mapa` = '$id_mapa'");

  if ( $del_mapa === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menghapus MAPA',
            text:  'Mohon Tunggu',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";

  exit;
  }

  if ( $del_mapa === TRUE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Berhasil Menghapus MAPA',
            text:  'Mohon Tunggu',
            icon: 'success',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_mapa&&id_permohonan=$id_permohonan');
        } ,3000); 
      </script>";

  exit;
  }


exit;
}


if ( $aksi == "tambah_peta_muk" ){

  
  include 'config.php';

  $id_mapa = mysqli_real_escape_string ( $conn, $_POST['id_mapa'] );

  $password = mysqli_real_escape_string( $conn, $_POST['password']) ;

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`asesor` WHERE `akun`.`email` = `asesor`.`email` AND `asesor`.`id_asesor` = '$id_asesor'");

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
          window.location.replace('tambah_peta_muk.php?id_mapa=$id_mapa');
        } ,2000); 
      </script>";
  exit;
  }

  $cek = $conn->query("SELECT mapa.id_permohonan, mapa.id_unit FROM mapa WHERE id_mapa = '$id_mapa' ");
  $c = mysqli_fetch_assoc($cek);
  $id_permohonan = $c['id_permohonan']; $id_unit = $c['id_unit'];

  $cek_peta = $conn->query("SELECT * FROM peta_muk WHERE id_mapa = '$id_mapa'");

  if ( mysqli_num_rows($cek_peta) != 0 ) {


    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title               : 'Gagal Simpan Peta MUK',
            text                :  'Data Peta MUK sudah ada',
            icon                : 'error',
            timer               : 3000,
            showConfirmButton   : true
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_peta_muk&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,3000); 
      </script> ";
  exit;
  }

  $cek_pertanyaan = $conn->query("SELECT * FROM `pertanyaan_muk`");

  while ( $d = mysqli_fetch_assoc($cek_pertanyaan) ) {
    $no = $d['no_muk'];


    $no_muk = mysqli_real_escape_string( $conn, $_POST['no_muk'] [$no]);

    $muk = mysqli_real_escape_string( $conn, $_POST['muk'] [$no]);

    $potensi_kandidat = mysqli_real_escape_string( $conn, $_POST['potensi_kandidat'] [$no]);

    // Buat ID Peta
    $big = $conn->query("SELECT MAX(id_peta) AS ID_BIG FROM `peta_muk` ");
    $id_peta = mysqli_fetch_assoc($big) ['ID_BIG'];

    $urutan = (int) substr($id_peta, 2);

    $urutan = $urutan + 1;

    $huruf = "PT";

    $id_peta  = $huruf . sprintf("%05s", $urutan);


    $input_peta = $conn->query("INSERT INTO `peta_muk`(`id_peta`, `id_mapa`, `no_muk`, `muk`, `potensi_kandidat`)
    VALUES ( '$id_peta' , '$id_mapa' , '$no_muk' , '$muk' , '$potensi_kandidat' ) ");

    if ( $input_peta === FALSE ) {

      $del_peta = ("DELETE FROM `peta_muk` WHERE `id_mapa` = '$id_mapa' ");


      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Simpan Peta MUK',
              text                :  'Silahkan periksa kembali data yang Anda masukkan',
              icon                : 'error',
              timer               : 3000,
              showConfirmButton   : true
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('tambah_peta_muk.php?id_mapa=$id_mapa');
          } ,3000); 
        </script> ";
    exit;
    }

    if ( $input_peta === TRUE ){
      $cek_input = TRUE;
    }


  }




  if ( $cek_input === TRUE ) {

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title               : 'Berhasil Simpan Peta MUK',
            text                :  'Silahkan tunggu, Anda akan dialihkan',
            icon                : 'success',
            timer               : 3000,
            showConfirmButton   : true
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_peta_muk&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,3000); 
      </script> ";


  exit;
  }

  if ( $cek_input === FALSE ) {

    // $del_peta = ("DELETE FROM `peta_muk` WHERE `id_mapa` = '$id_mapa' ");


    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title               : 'Gagal Simpan Peta MUK',
            text                :  'Silahkan periksa kembali data yang Anda masukkan',
            icon                : 'error',
            timer               : 3000,
            showConfirmButton   : true
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('tambah_peta_muk.php?id_mapa=$id_mapa');
        } ,3000); 
      </script> ";
  exit;
  }

exit;
}


if ( $aksi == "edit_peta_muk" ) {


  $id_mapa = mysqli_real_escape_string( $conn, $_POST['id_mapa'] );
  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );
  $id_unit = mysqli_real_escape_string( $conn, $_POST['id_unit'] );

  $id_peta = mysqli_real_escape_string( $conn, $_POST['id_peta'] );

  $potensi_kandidat = mysqli_real_escape_string( $conn, $_POST['potensi_kandidat'] );

  $id_asesor = mysqli_real_escape_string( $conn, $_POST['id_asesor'] );

  $cek_password = $conn->query("SELECT akun.password FROM akun,asesor WHERE akun.email = asesor.email AND asesor.id_asesor = '$id_asesor' ");
  $data = mysqli_fetch_assoc($cek_password);

  $password = mysqli_real_escape_string( $conn, $_POST['password'] );

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
          window.location.replace('index.php?page=kelola_peta_muk&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,1500); 
      </script>";
  exit;
  }

  $update_peta = $conn->query("UPDATE `peta_muk` SET `potensi_kandidat` = '$potensi_kandidat' WHERE `id_peta` = '$id_peta' AND `id_mapa` = '$id_mapa'");

  if ( $update_peta === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Mengedit Peta MUK',
            text:  'Mohon Tunggu, Anda akan diaihkan',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_peta_muk&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,3000); 
      </script>";

  exit;
  }

  if ( $update_peta === TRUE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Berhasil Mengedit Peta MUK',
            text:  'Mohon Tunggu, Anda akan dialihkan',
            icon: 'success',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_peta_muk&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,3000); 
      </script>";

  exit;
  }


exit;
}


if ( $aksi == "persetujuan_asesmen" ) {

  session_start();

  $email_asesor = $_SESSION['email'];

  $password = mysqli_real_escape_string( $conn, $_POST['password'] );
  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan'] );

  $sql = $conn->query("SELECT `akun`.`password` FROM `akun` WHERE `akun`.`email` = '$email_asesor'");
  $data = mysqli_fetch_assoc($sql);

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
          window.location.replace('index.php?page=persetujuan_asesmen&&id_permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }

  $id_asesi         = mysqli_real_escape_string( $conn, $_POST['id_asesi'] );
  $id_skema         = mysqli_real_escape_string( $conn, $_POST['id_skema'] );
  
  $nama_asesi       = mysqli_real_escape_string( $conn, $_POST['nama_asesi'] );

  $nama_skema       = mysqli_real_escape_string( $conn, $_POST['nama_skema'] );

  $nomor_skema      = mysqli_real_escape_string( $conn, $_POST['nomor_skema'] );

  $bukti_L          = mysqli_real_escape_string( $conn, $_POST['bukti_L'] );

  $bukti_TL         = mysqli_real_escape_string( $conn, $_POST['bukti_TL'] );

  $bukti_tulis      = mysqli_real_escape_string( $conn, $_POST['bukti_tulis']  );

  $bukti_lisan      = mysqli_real_escape_string( $conn, $_POST['bukti_lisan']  );

  $bukti_wawancara  = mysqli_real_escape_string( $conn, $_POST['bukti_wawancara']  );

  $jenis_TUK        = mysqli_real_escape_string( $conn, $_POST['jenis_TUK']  );

  $nama_TUK         =  mysqli_real_escape_string( $conn, $_POST['nama_TUK']  );

  $tanggal_asesmen  =  mysqli_real_escape_string( $conn, $_POST['tanggal_asesmen']  );

  $jam_asesmen      =  mysqli_real_escape_string( $conn, $_POST['jam_asesmen']  );


  // Buat ID Persetujuan

  $big = $conn->query("SELECT MAX(id_persetujuan) as `IDTerbesar` FROM `persetujuan_asesmen`");

  $id_persetujuan = mysqli_fetch_assoc( $big ) ['IDTerbesar'];

  $id_terbesar = (int) substr( $MAPA, 4 );
  $urutan = $id_terbesar + 1;

  $huruf = "R";
  $id_persetujuan = $huruf . sprintf("%05s", $urutan);

  $cek_persetujuan = $conn->query("SELECT `id_persetujuan` FROM `persetujuan_asesmen` WHERE `id_permohonan` = '$id_permohonan'");

  if ( mysqli_num_rows($cek_persetujuan) != 1 ) {


    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menyimpan Persetujuan Asesmen',
            text:  'Telah mengajukan Persetujuan Asesmen',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=persetujuan_asesmen&&id_permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }

  

  $sql_persetujuan = $conn->query("INSERT INTO `persetujuan_asesmen`(`id_persetujuan`, `id_permohonan`, 
  `bukti_TL`, `bukti_L`, `bukti_tulis`, `bukti_lisan`, `bukti_wawancara`, `jenis_TUK`, `tanggal_asesmen`, 
  `waktu_asesmen`, `nama_TUK`, `id_asesor` , `timestamp_validasi_asesor`, `timestamp_validasi_asesi`) VALUES
  ('$id_persetujuan' , '$id_permohonan' , '$bukti_TL' , '$bukti_L' , '$bukti_tulis' , '$bukti_lisan' ,
  '$bukti_wawancara' , '$jenis_TUK' , '$tanggal_asesmen' , '$jam_asesmen' , '$nama_TUK' , '$id_asesor' , CURRENT_TIMESTAMP() , '')");

  $update_permohonan = $conn->query("UPDATE `permohonan` SET `status_permohonan` = 'Menunggu Persetujuan Asesmen' WHERE `id_permohonan` = '$id_permohonan' ");

  if ( $sql_persetujuan === FALSE ) {


    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menyimpan Persetujuan Asesmen',
            text:  'Silahkan periksa kembali data yang Anda masukkan',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=persetujuan_asesmen&&id_permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }


  if ( $sql_persetujuan === TRUE ) {


    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Berhasil Menyimpan Persetujuan Asesmen',
            text:  'Silahkan tunggu',
            icon: 'success',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=persetujuan_asesmen&&id_permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }




exit;
}


?>