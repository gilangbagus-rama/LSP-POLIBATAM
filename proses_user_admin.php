<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include 'config.php';

?>

<?php

// Get aksi dari form
$aksi = $_GET['aksi'];


// Untuk aksi = tambah skema
if ( $aksi == "tambah_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

    if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA )) {

      header("location: index.php?page=forbidden&aksi=not_login");


    exit;
    }
  // End Cek login


  // Cek error sewaktu upload file
  $error = $_FILES['file_skema']['error'];

    //  Array upload error
    $phpFileUploadErrors = array(
      0 => 'There is no error, the file uploaded with success',
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      3 => 'The uploaded file was only partially uploaded',
      4 => 'No file was uploaded',
      6 => 'Missing a temporary folder',
      7 => 'Failed to write file to disk.',
      8 => 'A PHP extension stopped the file upload.',
    );

    $phpUploadErrorCode = array(1,2,3,4,5,6,7,8);

    // Cek upload error
    if( in_array( $error , $phpUploadErrorCode ) === TRUE ) {

      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Upload File',
              text:  'Unknown Error !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
      </script>";

    exit;
    }
    // End Cek upload error , Jika error kode akan berhenti disini
  // End Cek error sewaktu upload file


  // Cek Ekstensi yang diperbolehkan
  $ekstensi_diperbolehkan = array('pdf');

    // Memisahkan nama dari ekstensi file
    $nama_file  = $_FILES['file_skema']['name'];
    $x = explode('.', $nama_file);

    $ekstensi = strtolower(end($x));

    // Jika eksensi file salah
    if( in_array ( $ekstensi , $ekstensi_diperbolehkan ) === FALSE )  {     

      echo "

        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Upload File',
              text:  'File harus berupa *PDF atau *DOCX !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script>";

    exit;
    }
    // End Jika ekstensi file salah , Jika salah maka kode berhenti
  // EndCek ekstensi yang diperbolehkan


  // Cek ukuran file , jika > 20 MB maka failed
  $ukuran_file  = $_FILES['file_skema']['size'];

    // Jika file > 20MB
    if( $ukuran_file > 20*1024*1024 )  {     


      echo "

        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Upload File',
              text:  'File terlalu besar !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000);
        </script>";


    exit;
    }
    // End Jika file ?20 MB , Jika ya maka kode berhenti
  // End Cek ukuran file , jika > 20 MB maka failed

  
  // Membuat kode skema otomatis
  $cari_max = $conn->query("SELECT max(id_skema) as idTerbesar FROM `skema_sertifikasi`");
    $data = mysqli_fetch_assoc($cari_max);
    
    // Mengambil data di array dengan indeks idTerbesar
    $kodeSkema = $data['idTerbesar'];

    // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
    // dan diubah ke integer dengan (int)
    // A(1) S(2) 0(3) 0(4) 1(5) S 1 K 2 M 3 0 4 0 1
    $urutan = (int) substr($kodeSkema, 3);

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $urutan = $urutan + 1 ;

    // membentuk kode barang baru
    // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
    $huruf = "SKM";
    $kodeSkema = $huruf . sprintf("%03s", $urutan);
  
  // End Membuat kode skema otomatis


  // Penomoran Skema
  $tahun = date("Y");
    $nomor_skema = $kodeSkema . '/' . $tahun ;
  // End Penomoran Skema


  // Memindahkan file
  
    // Panggil Nama Skema
    $nama_skema = mysqli_real_escape_string($conn, $_POST['nama_skema'] );

    // Panggil file upload sementara
    $tmp_file = $_FILES['file_skema']["tmp_name"];

    // Membuat nama file baru
    $namaFile_baru = $kodeSkema . '-' . $nama_skema . '.' .$ekstensi;


    // Memindahkan file ke lokasi yang ditentukan dengan nama baru
    $lokasi = "uploads/SI-LSP/Skema Sertifikasi/";


    // Buat direktori untuk Skema Sertfikasi
    
    if(!is_dir($lokasi)){
      //Directory does not exist, so lets create it.
      mkdir($lokasi, 0755, true);
    }


    $lokasi = "uploads/SI-LSP/Skema Sertifikasi/" .$namaFile_baru;


    // Cek apakah upload (memindahkan file) berhasil
    if( move_uploaded_file( $tmp_file , $lokasi ) === FALSE )  {     


      echo "

      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Upload File',
            text:  'Gagal mengupload file Skema Sertifikasi !',
            icon: 'error',
            timer: 2000,
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_skema_sertifikasi');
        } ,3000); 
      </script>";


    exit;
    }
    // End cek status upload. Jika gagal, kode berhenti disini


  // End memindahkan file


  // Input data Skema baru

    // Query tambah
    $tambah_skema = $conn->query("INSERT INTO `skema_sertifikasi`(`id_skema`, `nomor_skema`, `nama_skema`, `file_skema`, `validasi_id_admin`) 
    VALUES ( '$kodeSkema' , '$nomor_skema' , '$nama_skema' , '$namaFile_baru' , '$id_UA' ) ");

    // Cek status Query
    if ( $tambah_skema === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Skema Berhasil di Tambah',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 3000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script> ";


    exit;
    } else { // Jika gagal input

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
          
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Skema Sertifikasi gagal di tambah',
              text:  'Silahkan cek data kembali !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script>";


    exit;
    }
    // End Cek Status query
  
  // Input data Skema baru


exit; // Exit untuk IF ( $aksi == "tambah_skema" )
}
// End Untuk aksi = tambah skema


// Untuk aksi = edit skema
if ( $aksi == "edit_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek login
  $token = mysqli_real_escape_string($conn, $_POST['token'] );
    $id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
    $role = mysqli_real_escape_string($conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
          !isset($role) || empty($role) || $role != 'User Administrasi' ||
          !isset($id_UA) || empty($id_UA)) {

      header("location: index.php?page=forbidden&aksi=not_login");
      exit;

    }
  // End Cek login


  // Panggil data yang diperlukan
  $id_skema       = mysqli_real_escape_string($conn, $_POST['id_skema'] );
    $nama_skema     = mysqli_real_escape_string($conn, $_POST['nama_skema'] );
    $namaFile_lama = mysqli_real_escape_string($conn, $_POST['namaFile_lama'] );
    $file_baru      = $_FILES['file_skema']['name'];
    $lokasi         = "uploads/SI-LSP/Skema Sertifikasi/" ;
  // End Panggil data yang diperlukan


  //  Jika tidak mengganti file
  if ( empty($file_baru) ) {


    // Rename file ke yg baru
    $namaFile_baru = $id_skema . '-' . $nama_skema . '.pdf';

    // Ganti nama file ke nama file baru 
    $ganti_nama = rename( $lokasi .$namaFile_lama , $lokasi .$namaFile_baru);
    
    //  Cek status rename
    if ( $ganti_nama === FALSE ) { 

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
                title               : 'Gagal Update Skema',
                text                : 'Unknown Error',
                icon                : 'error',
                timer               : 3000,
                showConfirmButton   : true
              });  
          },10); 
          
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script> ";
        
        exit;

    }
    // End Cek status rename , Jika gagal kode berhenti disini

    
    // Update data di DB
    $update_skema = $conn->query("UPDATE `skema_sertifikasi` SET `nama_skema` = '$nama_skema' , `file_skema` = '$namaFile_baru' ,
    `validasi_id_admin` = '$id_UA' WHERE `id_skema` = '$id_skema' ; ");

    // Cek status update data
    if ( $update_skema === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
                title               : 'Skema Berhasil di Edit',
                text                :  'Silahkan tunggu',
                icon                : 'success',
                timer               : 3000,
                showConfirmButton   : true
              });  
          },10); 
          
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script> ";

    } else { // Jika gagal input

      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Skema Gagal di Update',
              text:  'Silahkan cek data kembali !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script>";
    }

    
    exit; // Exit punya IF - Jika tidak mengganti file
  }
  // End Jika tidak mengganti file
  

  // Jika mengganti file

    // Cek error sewaktu upload file
    $error = $_FILES['file_skema']['error'];

    // Array Upload error
    $phpFileUploadErrors = array(
      0 => 'There is no error, the file uploaded with success',
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      3 => 'The uploaded file was only partially uploaded',
      4 => 'No file was uploaded',
      6 => 'Missing a temporary folder',
      7 => 'Failed to write file to disk.',
      8 => 'A PHP extension stopped the file upload.',
    );


    $phpUploadErrorCode = array(1,2,3,4,5,6,7,8);

    // Cek upload error
    if( in_array($error, $phpUploadErrorCode) === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Upload File',
                text:  'Unknown Error !',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
            
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_skema_sertifikasi');
            } ,3000); 
              
          </script>";
      exit;

    }
    // End Cek upload error. Jika upload error, maka kode berhenti disini


    // Ekstensi yang diperbolehkan
    $ekstensi_diperbolehkan = array('pdf');

    // Memisahkan nama dari ekstensi file
    $nama_file  = $_FILES['file_skema']['name'];
    $x = explode('.', $nama_file);

    $ekstensi = strtolower(end($x));

    // Jika eksensi file salah
    if( in_array($ekstensi, $ekstensi_diperbolehkan) === FALSE )  {     

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
          
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Upload File',
                text:  'File harus berupa *PDF atau *DOCX !',
                icon: 'error',
                timer: 2000,
                });  
            },10); 

            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_skema_sertifikasi');
            } ,3000); 
            
          </script>";
      exit;
  
    }
    // End Jika ekstensi file salah. Jika salah, maka kode berhenti disini


    // Cek ukuran file , jika > 20 MB maka failed
    $ukuran_file  = $_FILES['file_skema']['size'];

      // Cek ukuran file yang di upload
      if( $ukuran_file > 20*1024*1024 )  {     

        echo "
          <link rel='stylesheet' href='dist/css/app.css'>
          <script src='dist/js/sweetalert.min.js'></script>
          
            <script type='text/javascript'>
              setTimeout(function () { 
                swal({
                  title: 'Gagal Upload File',
                  text:  'File terlalu besar !',
                  icon: 'error',
                  timer: 2000,
                });  
              },10); 
              
              window.setTimeout(function(){ 
                window.location.replace('index.php?page=kelola_skema_sertifikasi');
              } ,3000); 
              </script>";
          exit;
  
      }
      // End Cek ukuran file. Jika >20 MB kode berhenti disini


    // Panggil file upload sementara
    $tmp_file = $_FILES['file_skema']["tmp_name"];

    $namaFile_baru = $id_skema . '-' . $nama_skema . '.' .$ekstensi;

    $lokasi = "uploads/SI-LSP/Skema Sertifikasi/" .$namaFile_baru;

    if( move_uploaded_file($tmp_file, $lokasi ) === FALSE )  {     

      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Upload File',
              text:  'Gagal mengupload file Skema Sertifikasi !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script>";

      exit;
    
    }

    // Query Update Skema
    $update_skema = $conn->query("UPDATE `skema_sertifikasi` SET `nama_skema` = '$nama_skema' ,
      `file` = '$namaFile_baru' , `validasi_id_admin` = '$id_UA' WHERE `id_skema` = '$id_skema' ; ");
  
    if ( $update_skema === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
                title               : 'Skema Berhasil di Edit',
                text                :  'Silahkan tunggu',
                icon                : 'success',
                timer               : 3000,
                showConfirmButton   : true
              });  
          },10); 
          
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script> ";

      } else { // Jika gagal input

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Skema Gagal di Update',
                text:  'Silahkan cek data kembali !',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
            
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_skema_sertifikasi');
            } ,3000); 
          </script>";
      }


  // End Jika mengganti file


exit; // Exit dari IF ( $aksi == "edit_skema" )
}
// End Untuk aksi = edit skema


// Aksi = Hapus skema 
if ( $aksi == "hapus_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start();

  // Cek sudah login
  $token = mysqli_real_escape_string($conn, $_POST['token'] );
    $id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
    $role = mysqli_real_escape_string($conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }
  // End Cek Login


  // Panggil file skema untuk di hapus
  $file_skema   = mysqli_real_escape_string($conn, $_POST['file_skema']);
  
  // Path file skema
  $lokasi_file = "uploads/SI-LSP/Skema Sertifikasi/" .$file_skema;

  // Hapus file skema
  $hapus_file = unlink( $lokasi_file );

  // Cek status hapus
  if ( $hapus_file === FALSE ) {

    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'File Gagal di Hapus',
              text:  'Ara Ara !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
          </script>";
    
      exit;
  }
  // Jika gagal hapus file , maka kode berhenti disini


  // Panggil id Skema untuk Query delete skema
  $id_skema     = mysqli_real_escape_string($conn, $_POST['id_skema'] );


    // Query delete skema
    $delete_skema = $conn->query("DELETE FROM `skema_sertifikasi` WHERE `id_skema` = '$id_skema' ; ");

    
    // NB: Hapus file dari dir uploads/SI-LSP/Skema Sertifikasi
  
    // Cek query Delete
    if ( $delete_skema === TRUE ) { 
  
      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
                title               : 'Skema Berhasil di Hapus',
                text                :  'Silahkan tunggu',
                icon                : 'success',
                timer               : 3000,
                showConfirmButton   : true
              });  
          },10); 
          
          window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
        </script> "; 
  
      } else {

        echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
        
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Skema Gagal di Hapus',
              text:  'Silahkan cek data kembali !',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_skema_sertifikasi');
          } ,3000); 
          </script>";

    }
    // End Cek query Delete
  // End Panggil id Skema untuk Query delete skema


exit; // Exit untuk IF ( $aksi == "hapus_skema" )
}
// End Aksi = Hapus skema


// Aksi = Tambah syarat dasa
if ( $aksi == "tambah_syarat_dasar" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek sudah login
  $token = mysqli_real_escape_string($conn, $_POST['token'] );
    $id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
    $role = mysqli_real_escape_string($conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
          !isset($role) || empty($role) || $role != 'User Administrasi' ||
          !isset($id_UA) || empty($id_UA)) {

      header("location: index.php?page=forbidden&aksi=not_login");
      exit;

    }

  // End Cek sudah login


  // Panggil id_skema dan syarat
    $id_skema = mysqli_real_escape_string($conn, $_POST['id_skema'] );
    $syarat = mysqli_real_escape_string($conn, $_POST['syarat']) ;
  // End Panggil id_skema dan syarat


  // Untuk Assign nilai no_syarat
  $cari_max = $conn->query("SELECT max(no_syarat) as noTerbesar FROM `syarat_dasar` WHERE `id_skema` = '$id_skema' ; ");
    $data = mysqli_fetch_assoc($cari_max);
    
   // Mengambil data di array dengan indeks noTerbesar
    $noSyarat = $data['noTerbesar'];

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $noSyarat = $noSyarat + 1 ;

  // End Untuk Assign nilai no_syarat 


  // Untuk dapat assign nilai Id Syarat 
  $cari_max = $conn->query("SELECT max(id_syarat) as id_syaratTerbesar FROM `syarat_dasar` ; ");
    $data = mysqli_fetch_assoc($cari_max);

    // Mengambil data di array dengan indeks idTerbesar
    $Id_Syarat = $data['id_syaratTerbesar'];

    $urutan = (int) substr($Id_Syarat, 2);

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $urutan = $urutan + 1 ;


    // membentuk kode barang baru
    // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
    $huruf = "SY";
    $Id_Syarat = $huruf . sprintf("%04s", $urutan);
    
  // End Untuk dapat assign nilai Id Syarat


  // Insert Syarat Dasar
  $sql_tambah = $conn->query("INSERT INTO `syarat_dasar` (`id_syarat`, `id_skema`, `no_syarat`, `syarat`) 
    VALUES ( '$Id_Syarat' , '$id_skema' , '$noSyarat'  , '$syarat' ) ; ");

    if ( $sql_tambah === FALSE ) {

      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
                
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menambah Syarat Dasar',
            text:  'Silahkan cek data kembali!',
            icon: 'error',
            timer: 2000,
            });  
          },10); 
          
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
            } ,3000); 
      </script>";

    exit;
    } 

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
      
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
              title               : 'Berhasil Menambah Syarat Dasar',
              text                :  'Silahkan Tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
        },10); 
        
        window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
        } ,1500); 
      </script> ";

  // End Insert Syarat Dasar


exit; // Exit IF ( $aksi == "tambah_syarat_dasar" )
}
// End Aksi = Tambah syarat dasar


// Aksi = Hapus syarat dasar
if ( $aksi == "hapus_syarat_dasar") {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
  $token = mysqli_real_escape_string($conn, $_POST['token'] );
    $id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
    $role = mysqli_real_escape_string($conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
          !isset($role) || empty($role) || $role != 'User Administrasi' ||
          !isset($id_UA) || empty($id_UA)) {

      header("location: index.php?page=forbidden&aksi=not_login");
      exit;

    }
  // End Cek sudah login


  // Panggil id_skema dan id_syarat yang di hidden untuk Query hapus
  $id_skema = mysqli_real_escape_string($conn, $_POST['id_skema'] );
    $id_syarat = mysqli_real_escape_string($conn, $_POST['id_syarat']) ;

    // Query hapus
    $sql_hapus = $conn->query("DELETE FROM `syarat_dasar`
      WHERE `id_syarat` = '$id_syarat' ;" );


    // Cek query
    if ( $sql_hapus === FALSE ) { 


      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>
      <script type='text/javascript'>
      
        setTimeout(function () { 
          swal({
            title: 'Gagal Menghapus',
            text:  'Silahkan cek data kembali!',
            icon: 'error',
            timer: 2000,
          });  
        },10); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
        } ,3000); 
        </script>";

    exit;
    }



    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
            title               : 'Berhasil Menghapus Syarat Dasar',
            text                :  'Silahkan Tunggu',
            icon                : 'success',
            timer               : 1000,
            showConfirmButton   : true
          });  
      },10); 
      
      window.setTimeout(function(){ 
      window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
      } ,1500); 
    </script> ";

    // End Cek Query




  // End Panggil id_skema dan id_syarat yang di hidden untuk Query hapus


exit; // Exit IF ( $aksi == "hapus_syarat_dasar" )
}
// End Aksi = Hapus syarat dasar


// Aksi = Edit syarat dasar
if ( $aksi == "edit_syarat_dasar") {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token = mysqli_real_escape_string($conn, $_POST['token'] );
    $id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
    $role = mysqli_real_escape_string($conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek login


  // Panggil id_skema , id_syarat dan syarat utk Edit Syarat Dasar
  $id_skema = mysqli_real_escape_string($conn, $_POST['id_skema'] );
    $id_syarat = mysqli_real_escape_string($conn, $_POST['id_syarat'] );
    $syarat = mysqli_real_escape_string($conn, $_POST['syarat']) ;
  

    // Query Edit Syarat Dasar
    $sql_edit = $conn->query("UPDATE `syarat_dasar` SET `syarat` = '$syarat' 
      WHERE `id_syarat` = '$id_syarat' ;" );


    // Cek Query
    if ( $sql_edit === FALSE ) {


      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Mengedit Syarat Dasar',
            text:  'Silahkan cek data kembali!',
            icon: 'error',
            timer: 1000,
          });  
        },10); 
          
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
          } ,1500); 
      </script> ";

    }


    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
            title               : 'Berhasil Mengedit Syarat Dasar',
            text                :  'Silahkan Tunggu',
            icon                : 'success',
            timer               : 1000,
            showConfirmButton   : true
          });  
      },10); 
      
      window.setTimeout(function(){ 
      window.location.replace('index.php?page=kelola_syarat_dasar&id_skema=$id_skema');
      } ,1500); 
    </script> ";
  // End Cek query
  // End panggil id_skema , id_syarat dan syarat


exit; // Exit IF ( $aksi == "edit_syarat_dasar" )
}
// End Aksi = Edit syarat dasar


// Aksi = Tambah unit skema
if ( $aksi == "tambah_unit_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login


  // Untuk dapat assign nilai Id Syarat 
    $cari_max = $conn->query("SELECT max(id_unit) as id_unitTerbesar FROM `unit_skema` ; ");
      $data = mysqli_fetch_assoc($cari_max);

      // Mengambil data di array dengan indeks idTerbesar
      $id_unit = $data['id_unitTerbesar'];

      $urutan = (int) substr($id_unit, 1);

      // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
      $urutan = $urutan + 1 ;


      // membentuk kode barang baru
      // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
      $huruf = "U";
      $id_unit = $huruf . sprintf("%03s", $urutan);
      
  // End Untuk dapat assign nilai Id Syarat


  // Panggil id_skema dll , untuk proses tambah unit_skema
    $id_skema = mysqli_real_escape_string( $conn, $_POST['id_skema'] );

    $kode_unit  = mysqli_real_escape_string( $conn, $_POST['kode_unit'] );

    $judul_unit = mysqli_real_escape_string( $conn, $_POST['judul_unit'] );

    $jenis_standar = mysqli_real_escape_string( $conn, $_POST['jenis_standar'] );

    $sql_tambah_unit = $conn->query( "INSERT INTO `unit_skema` (`id_unit`, 
      `id_skema`, `kode_unit`, `judul_unit`, `jenis_standar`) 
      VALUES ('$id_unit' , '$id_skema' , '$kode_unit' , '$judul_unit' , '$jenis_standar' ) ; " ) ;


    if ( $sql_tambah_unit === FALSE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Menambah Unit Skema',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";

    exit;
    }


    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Menambah Unit Skema',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";


exit; // Exit IF ( $aksi == "tambah_unit_skema" )
}
// End Aksi = Tambah unit skema


// Aksi = Edit unit skema
if ( $aksi == "edit_unit_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login



  // Panggil id_skema dll , untuk proses tambah unit_skema
    $id_skema = mysqli_real_escape_string( $conn, $_POST['id_skema'] );

    $id_unit = mysqli_real_escape_string( $conn, $_POST['id_unit'] );

    $kode_unit  = mysqli_real_escape_string( $conn, $_POST['kode_unit'] );

    $judul_unit = mysqli_real_escape_string( $conn, $_POST['judul_unit'] );

    $jenis_standar = mysqli_real_escape_string( $conn, $_POST['jenis_standar'] );

    $sql_edit_unit = $conn->query( "UPDATE `unit_skema` SET `kode_unit` = '$kode_unit' ,
      `judul_unit` = '$judul_unit' , `jenis_standar` = '$jenis_standar' WHERE `id_unit` = '$id_unit' ;" );

    if ( $sql_edit_unit === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Mengedit Unit Skema ',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";

    exit;
    }


    if ( $sql_edit_unit === FALSE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Mengedit Unit Skema',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";

    exit;
    }


exit; // Exit IF ( $aksi == "edit_unit_skema" )
}
// End Aksi = Edit unit skema


// Aksi = Hapus unit skema
if ( $aksi == "hapus_unit_skema" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login



  // Panggil id_skema dll , untuk proses tambah unit_skema
    $id_skema = mysqli_real_escape_string( $conn, $_POST['id_skema'] );

    $id_unit = mysqli_real_escape_string( $conn, $_POST['id_unit'] );

    $sql_hapus_unit = $conn->query( "DELETE FROM `unit_skema` WHERE `id_unit` = '$id_unit' ;" );

    if ( $sql_hapus_unit === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Menghapus Unit Skema',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";

    exit;
    }


    if ( $sql_hapus_unit === FALSE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Menghapus Unit Skema',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_unit_skema&id_skema=$id_skema');
          } ,1500); 
        </script> ";

    exit;
    }


exit; // Exit IF ( $aksi == "hapus_unit_skema" )
}
// End Aksi = Hapus unit skema


// Aksi = Tambah Elemen Unit
if ( $aksi == "tambah_elemen_unit" ) {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login


  // Untuk dapat assign nilai Id Elemen 
  $cari_max = $conn->query("SELECT max(id_elemen) as id_elemenTerbesar FROM `elemen_unit` ; ");
    $data = mysqli_fetch_assoc($cari_max);

    // Mengambil data di array dengan indeks idTerbesar
    $id_elemen = $data['id_elemenTerbesar'];

    // Cth E0001
    //     12345
    $urutan = substr($id_elemen, 1);

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $urutan = $urutan + 1 ;


    // membentuk kode barang baru
    // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
    $huruf = "E";
    $id_elemen = $huruf . sprintf("%04s", $urutan);
    
  // End Untuk dapat assign nilai Id Elemen



  $id_unit     = mysqli_real_escape_string( $conn, $_POST['id_unit'] );

  // Untuk dapat assign nilai No Elemen 
  $cari_max = $conn->query("SELECT max(no_elemen) as no_elemenTerbesar FROM `elemen_unit` WHERE `id_unit` = '$id_unit'; ");
    $data = mysqli_fetch_assoc($cari_max);

    // Mengambil data di array dengan indeks idTerbesar
    $no_elemen = $data['no_elemenTerbesar'];

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $no_elemen = $no_elemen + 1 ;

  // End Untuk dapat assign nilai No Elemen


  $elemen_unit = mysqli_real_escape_string( $conn, $_POST['elemen_unit'] );
  $id_skema = mysqli_real_escape_string( $conn, $_POST['id_skema'] );

  $sql_elemen_unit = $conn->query( "INSERT INTO `elemen_unit` 
  (`id_elemen`, `id_unit`, `no_elemen`, `elemen`) 
  VALUES ('$id_elemen' , '$id_unit' , '$no_elemen' , '$elemen_unit' ) ; " ) ;

    if ( $sql_elemen_unit === TRUE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Menambah Elemen Unit',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";

    exit;
    }


    if ( $sql_elemen_unit === FALSE ) {

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Menambah Elemen Unit',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";

    exit;
    }

exit; // Exit IF ( $aksi == "tambah_elemen_unit" )
}
// End Aksi = Tambah Elemen Unit


// Aksi = Edit Elemen Unit
if ( $aksi == "edit_elemen_unit" ) {


  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login


  $id_unit      = mysqli_real_escape_string( $conn, $_POST['id_unit'] );
  $id_skema     = mysqli_real_escape_string( $conn, $_POST['id_skema'] ) ;

  $id_elemen    = mysqli_real_escape_string( $conn, $_POST['id_elemen'] ) ;
  $elemen_unit  = mysqli_real_escape_string( $conn, $_POST['elemen_unit'] );


  $sql_edit_elemen = $conn->query("UPDATE `elemen_unit` SET `elemen` = '$elemen_unit' 
  WHERE `id_elemen` = '$id_elemen' ");


  if ( $sql_edit_elemen === TRUE ) {


    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Mengedit Elemen Unit',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";


  exit;
  } 


  if ( $sql_edit_elemen === FALSE ) {


    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Mengedit Elemen Unit',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";


  exit;
  }

exit; // Exit IF ( $aksi == "edit_elemen_unit" )
}
// End Aksi = Edit Elemen Unit


// Aksi = Hapus Elemen Unit
if ( $aksi == "hapus_elemen_unit" ) {


  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login


  $id_unit      = mysqli_real_escape_string( $conn, $_POST['id_unit'] );
  $id_skema     = mysqli_real_escape_string( $conn, $_POST['id_skema'] ) ;

  $id_elemen    = mysqli_real_escape_string( $conn, $_POST['id_elemen'] ) ;
  $elemen_unit  = mysqli_real_escape_string( $conn, $_POST['elemen_unit'] );


  $sql_hapus_elemen = $conn->query("DELETE FROM `elemen_unit` WHERE `id_elemen` = '$id_elemen' ");


  if ( $sql_hapus_elemen === TRUE ) {


    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Berhasil Menghapus Elemen Unit',
              text                :  'Silahkan tunggu',
              icon                : 'success',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";


  exit;
  } 


  if ( $sql_hapus_elemen === FALSE ) {


    echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
      
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title               : 'Gagal Menghapus Elemen Unit',
              text                :  'Silahkan periksa data kembali',
              icon                : 'error',
              timer               : 1000,
              showConfirmButton   : true
            });  
          },10); 
        
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_elemen_unit&id_skema=$id_skema&id_unit=$id_unit');
          } ,1500); 
        </script> ";


  exit;
  }

exit; // Exit IF ( $aksi == "hapus_elemen_unit" );
}


// Aksi = Tambah Asesor
if ( $aksi == "tambah_asesor") {


  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }

  // End Cek Login


  // Cek E-Mail dan No HP

    $email_asesor = strtolower ( mysqli_real_escape_string( $conn , $_POST['email_asesor'] ) );
    $no_hp_asesor = mysqli_real_escape_string( $conn , $_POST['no_hp_asesor'] );


    $cek_email = $conn->query("SELECT `email` FROM `akun` WHERE `email` = '$email_asesor';");
    $email_count = mysqli_num_rows( $cek_email );


    $cek_nohp = $conn->query("SELECT `no_hp` FROM `asesor` WHERE `no_hp` = '$no_hp_asesor';");
    $nohp_count = mysqli_num_rows( $cek_nohp );


    if ( $email_count != 0 || $nohp_count != 0 ) {

      echo "
          <link rel='stylesheet' href='dist/css/app.css'>
          <script src='dist/js/sweetalert.min.js'></script>
  
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Menambah Asesor',
                text:  'E-Mail atau No HP telah digunakan',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
  
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_asesor');
            } ,1500); 
          </script>";

    exit;
    }

  // End



  // Cek Nomor Asesor


  // End Cek Nomor Asesor

  $nomor_asesor = mysqli_real_escape_string( $conn, $_POST['nomor_asesor'] );

  $cek_nomor = $conn->query("SELECT `nomor` FROM `asesor` WHERE `nomor` = '$nomor_asesor'; ");

  if ( mysqli_num_rows($cek_nomor) != 0 ) {

    echo "
          <link rel='stylesheet' href='dist/css/app.css'>
          <script src='dist/js/sweetalert.min.js'></script>
  
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Menambah Asesor',
                text:  'Nomor Asesor telah digunakan',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
  
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_asesor');
            } ,1500); 
          </script>";
  
  exit;
  }

  // Get Data Tambah Asesor


    $nama_asesor = mysqli_real_escape_string( $conn , $_POST['nama_asesor']  );

    $alamat_asesor = mysqli_real_escape_string( $conn , $_POST['alamat_asesor'] );

    $id_skema_sertifikasi = mysqli_real_escape_string( $conn , $_POST['skema_sertifikasi'] );


  // End Get Data


  // Input data akun

    // Generate Random Password

    // ambil username dari email => gilangbagus.rama@gmail.com -> gilangbagus.rama 
    $username = explode('@' , $email_asesor) [0];

    // tambah angka 12345 => gilangbagus.rama12345
    $password_baru1 = $username. '12345';

    $password_baru = password_hash( $password_baru1 , PASSWORD_DEFAULT);


    // username ( email tanpa @xx.com ) + 12345
    // gilangbagus.rama@gmail.com -> gilangbagus.rama12345
    
    // DATA PRIBADI

  $verif_length = strlen($email_asesor);
  $token_verif  = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $verif_length);

  $email_verif = base64_encode($email_asesor) ;


  $link = 'http://localhost/lsp/index.php?page=auth&email=' .$email_verif. '&token=' .$token_verif;


  $header_email = '<b>Yth. Bapak/Ibu ' .$nama_asesor. '</b> <br>';


  $body_email1 = '<br>Silahkan melakukan verifikasi akun melalui link berikut ini <br>' .$link;

  $body_email2 = '<br>Terimakasih, Anda telah melakukan pendaftaran akun di website LSP Polibatam. Berikut adalah detail akun Anda. <br> <br> Alamat Email : ' .$email_asesor. '<br> Password : ' .$password_baru1. '<br> <br>' ;

  $body_email = $body_email2. $body_email1;

  $body_email = $header_email. $body_email;

  $body_email = $body_email. '<br> <br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';

  

  // Kirim E-Mail Verifikasi
  require 'PHPMailerAutoload.php';
  require 'credential.php';

    $mail->addAddress($email_asesor);     // Add a recipient
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
  

    }





    $input_akun = $conn->query(" INSERT INTO `akun` (`email` , `password` , `role` , `nama` ,`no_hp` , `status_akun`, `token_verif`)
    VALUES ('$email_asesor' , '$password_baru' , 'Asesor' , '$nama_asesor' , '$no_hp_asesor' , 'Not Verif' , '$token_verif') ");


      if ( $input_akun === TRUE ) {


        // Buat id_asesor

          $cari_max = $conn->query("SELECT max(id_asesor) as idTerbesar FROM `asesor`");
          $data = mysqli_fetch_assoc($cari_max);

          // Mengambil data di array dengan indeks idTerbesar
          $id_asesor = $data['idTerbesar'];

          // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
          // dan diubah ke integer dengan (int)
          
          $urutan = (int) substr($id_asesor, 1);

          // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
          $urutan = $urutan + 1 ;

          // membentuk kode barang baru
          // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
          $huruf = "A";
          $id_asesor = $huruf . sprintf("%03s", $urutan);


        // End

        $input_asesor = $conn->query(" INSERT INTO `asesor` (`id_asesor`, `nomor`, `email`, `alamat`)
        VALUES ('$id_asesor' , '$nomor_asesor' , '$email_asesor' , '$alamat_asesor') ");

        $input_skema_asesor = $conn->query(" INSERT INTO `skema_asesor`(`id_skema`, `id_asesor`) 
        VALUES ('$id_skema_sertifikasi' , '$id_asesor')");

        if ( $input_asesor === FALSE || $input_skema_Asesor === FALSE  ) {

          $delete_akun = $conn->query(" DELETE FROM `akun` WHERE `email` = '$email_asesor' ");

          echo "
          <link rel='stylesheet' href='dist/css/app.css'>
          <script src='dist/js/sweetalert.min.js'></script>
  
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Menambah Asesor',
                text:  'Silahkan cek data kembali!',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
  
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_asesor');
            } ,1500); 
          </script>";
  
      

        exit;
        }


        //Kalau berhasil input akun , asesor dan skema asesor

        echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Berhasil Menambah Asesor',
              text:  'Silahkan Tunggu!',
              icon: 'success',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_asesor');
          } ,1500); 
        </script>";

      exit;
      }



    // Kalau Gagal input akun
    if ( $input_akun === FALSE ) {
      
      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Menambah Asesor',
              text:  'Silahkan cek data kembali!',
              icon: 'error',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_asesor');
          } ,1500); 
        </script>";

    exit;
    }


  // End Input data akun

exit;
}


// Aksi = Hapus Asesor
if( $aksi == "hapus_asesor") {


  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';


  // Cek sudah login
    $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
    $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
    $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

    if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
        !isset($role) || empty($role) || $role != 'User Administrasi' ||
        !isset($id_UA) || empty($id_UA)) {

    header("location: index.php?page=forbidden&aksi=not_login");
    exit;

    }
  // End Cek Login


  // Panggil E-Mail dan ID Asesor

    $id_asesor = mysqli_real_escape_string( $conn , $_POST['id_asesor'] );

    $email_asesor = mysqli_real_escape_string( $conn , $_POST['email_asesor'] );

  // End Panggil


  // Query hapus

  $hapus_asesor = $conn->query(" DELETE FROM `akun` WHERE `email` = '$email_asesor' AND `role` = 'Asesor'; ");

  if ( $hapus_asesor === TRUE ) {

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Berhasil Menghapus Asesor',
            text:  'Silahkan tunggu, Anda akan dialihkan',
            icon: 'success',
            timer: 2000,
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_asesor');
        } ,3000); 
      </script>";
  
  exit;
  }

  if ( $hapus_asesor === FALSE ) {
  

    echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Menghapus Asesor',
            text:  'Silahkan tunggu, Anda akan dialihkan',
            icon: 'error',
            timer: 2000,
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_asesor');
        } ,3000); 
      </script>";
  
  exit;
  }


exit;
}


// Aksi = Nilai Permohonan
if( $aksi == "nilai_permohonan") {

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }

  $id_permohonan = mysqli_real_escape_string( $conn, $_POST['id_permohonan']);

  $password = mysqli_real_escape_string( $conn, $_POST['password']) ;

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`user_administrasi` WHERE `akun`.`email` = `user_administrasi`.`email` AND `user_administrasi`.`id_UA` = '$id_UA'");

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
          window.location.replace('index.php?page=kelola_kelengkapan_bukti_sertifikasi&&permohonan=$id_permohonan');
        } ,2000); 
      </script>";
  exit;
  }




    $id_asesi = mysqli_real_escape_string( $conn, $_POST['id_asesi'] );

    $catatan = mysqli_real_escape_string( $conn, $_POST['catatan'] );
    $rekomendasi = mysqli_real_escape_string( $conn, $_POST['rekomendasi'] );


    // Set timezone to Jakarta
    date_default_timezone_set('Asia/Jakarta');


    // Buat Timestamp
    $hari_ini   = date("Y-m-d H:i:s");


    if ( $rekomendasi == "tidak diterima" ) {
      $status_permohonan = "Ditolak";
    } else if ( $rekomendasi == "diterima" ) {
      $status_permohonan = "Lanjut Asesmen Mandiri";
    }


  if ( $rekomendasi == "diterima" ) {
    $assign_asesor = mysqli_real_escape_string( $conn, $_POST['assign_asesor']);

    $sql_assign_asesor = $conn->query("INSERT INTO `assign_asesor` (`id_permohonan`, `id_asesor`) VALUES ('$id_permohonan' , '$assign_asesor');");


    if ( $sql_assign_asesor === FALSE ){


      echo "
      <link rel='stylesheet' href='dist/css/app.css'>
      <script src='dist/js/sweetalert.min.js'></script>

      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Mengvalidasi Data Permohonan',
            text:  'Silahkan periksa data!',
            icon: 'error',
            timer: 2000,
          });  
        },10); 

        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_permohonan_masuk');
        } ,2000); 
      </script>";

    exit;
    }


    $sql_permohonan = $conn->query("UPDATE `permohonan` SET `timestamp_validasi` = '$hari_ini' , `status_permohonan` = '$status_permohonan' , 
    `rekomendasi` = '$rekomendasi' , `catatan` = '$catatan' , `validasi_user_administrasi` = '$id_UA' 
    WHERE `id_permohonan` = '$id_permohonan' AND `id_asesi` = '$id_asesi';");

    if ( $sql_permohonan === FALSE ){

      $del_assign = $conn->query("DELETE FROM `assign_asesor` WHERE `id_permohonan` = '$id_permohonan'");

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
  
        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Mengvalidasi Data Permohonan',
              text:  'Silahkan Tunggu!',
              icon: 'error',
              timer: 2000,
            });  
          },10); 
  
          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_permohonan_masuk');
          } ,2000); 
        </script>";
  
    exit;
    }


  }


  if ( $rekomendasi == "tidak diterima" ) {

    $sql_permohonan = $conn->query("UPDATE `permohonan` SET `timestamp_validasi` = '$hari_ini' , `status_permohonan` = '$status_permohonan' , 
      `rekomendasi` = '$rekomendasi' , `catatan` = '$catatan' , `validasi_user_administrasi` = '$id_UA' 
      WHERE `id_permohonan` = '$id_permohonan' AND `id_asesi` = '$id_asesi';");

    if ( $sql_permohonan === FALSE ){

      echo "
        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
          setTimeout(function () { 
            swal({
              title: 'Gagal Mengvalidasi Data Permohonan',
              text:  'Silahkan Tunggu!',
              icon: 'error',
              timer: 2000,
            });  
          },10); 

          window.setTimeout(function(){ 
            window.location.replace('index.php?page=kelola_permohonan_masuk');
          } ,2000); 
        </script>";

    exit;
    }


    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil Mengvalidasi Data Permohonan',
          text:  'Silahkan Tunggu!',
          icon: 'success',
          timer: 2000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_permohonan_masuk');
      } ,2000); 
    </script>";

  exit;
  }


    // Penilaian Berkas Portofolio

      // Mencari panjang Portofolio

      $arr_length         = count($_POST['penilaian_portofolio']);

      // Perulangan Berdasarkan panjang syarat_dasar
      for ( $x = 1; $x <= $arr_length ; $x++ ) {

        // Case Jika Upload File 


        // Panggil file yang di upload
        $penilaian_portofolio = $_POST ["penilaian_portofolio"] [$x];

        $id_portofolio = $_POST ["id_portofolio"] [$x];

        $insert_penilaian_porto = $conn->query("UPDATE `portofolio` SET `terpenuhi` = '$penilaian_portofolio' 
        WHERE `id_portofolio` = '$id_portofolio' AND `id_permohonan` = '$id_permohonan' " );

        if ( $insert_penilaian_porto === FALSE){

      
          echo "
          <link rel='stylesheet' href='dist/css/app.css'>
          <script src='dist/js/sweetalert.min.js'></script>
  
          <script type='text/javascript'>
            setTimeout(function () { 
              swal({
                title: 'Gagal Mengvalidasi Data Permohonan',
                text:  'Silahkan Tunggu!',
                icon: 'error',
                timer: 2000,
              });  
            },10); 
  
            window.setTimeout(function(){ 
              window.location.replace('index.php?page=kelola_permohonan_masuk');
            } ,2000); 
          </script>";
        
        
        exit;
        }

        if ( $insert_penilaian_porto === TRUE){

          $hasil_penilaian = TRUE;

        }


        // End Input

      }


      if ( $hasil_penilaian === TRUE ){

        include 'cetak_APL_01.php';

        $id_asesi = $conn->query("SELECT `asesi`.`id_asesi` FROM `permohonan` , `asesi`  WHERE `permohonan`.`id_asesi` = `asesi`.`id_asesi` AND `permohonan`.`id_permohonan` = '$id_permohonan' ");
        $id_asesi = mysqli_fetch_assoc($id_asesi) ['id_asesi'];

        $skm = $conn->query("SELECT `skema_sertifikasi`.`nama_skema` , `skema_sertifikasi`.`nomor_skema` ,`permohonan`.`tujuan_asesmen` FROM `skema_sertifikasi` , `permohonan` WHERE `skema_sertifikasi`.`id_skema` = `permohonan`.`id_skema` AND `permohonan`.`id_permohonan` = '$id_permohonan'");
        $dat = mysqli_fetch_assoc($skm); $nomor_skema = $dat['nomor_skema']; $nama_skema = $dat['nama_skema']; $tujuan_asesmen = $dat['tujuan_asesmen'];
      

  // DATA PRIBADI
  $nama = $conn->query("SELECT `akun`.`nama` , `akun`.`email` FROM `akun`,`asesi` WHERE `asesi`.`email` = `akun`.`email` AND `asesi`.`id_asesi` = '$id_asesi '");
  $da = mysqli_fetch_assoc($nama); $nama_asesi= $da['nama']; $email_asesi = $da['email'];

  $header_email = '<b>Yth. Bapak/Ibu ' .$nama_asesi. '</b> <br>';


  $body_email = '<br>Berikut ini merupakan hasil validasi permohonan asesmen yang Anda ajukan oleh tim Administrasi LSP Polibatam. <br> <br>';
  $body_email = $body_email. 'ID Permohonan : ' .$id_permohonan. '<br> Nama Skema : ' .$nama_skema. '<br> Tujuan Asesmen : ' .$tujuan_asesmen. '<br> ';

  $body_email = $body_email. 'Status Permohonan : <b>' .$rekomendasi. '</b> <br> Catatan : <b>' .$catatan. '</b>';


  $body_email = $header_email. $body_email. '<br> <br> Hormat Kami, <br> <br> <br> LSP Polibatam';


  // Kirim E-Mail Verifikasi
  require 'PHPMailerAutoload.php';
  require 'credential.php';


    $mail->addAddress($email_asesi);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->addAttachment("./uploads/Asesi/".$id_asesi. "/" .$id_permohonan. "/".$file_APL_01);
    // $mail->addAttachment("./uploads/Asesi/default.png");
    

    $mail->Subject = 'Pemberitahuan Validasi Permohonan Sertifikasi | LSP Polibatam';
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
                  window.location.replace('index.php?page=kelola_permohonan_masuk');
                } ,2000); 
              </script>";

      
  

    } else {

              // Kalau berhasil kirim E-Mail
              echo "
              <link rel='stylesheet' href='dist/css/app.css'>
              <script src='dist/js/sweetalert.min.js'></script>
      
              <script type='text/javascript'>
                setTimeout(function () { 
                  swal({
                    title: 'Berhasil Mengvalidasi Data Permohonan',
                    text:  'Silahkan Tunggu!',
                    icon: 'success',
                    timer: 2000,
                  });  
                },10); 
      
                window.setTimeout(function(){ 
                  window.location.replace('index.php?page=kelola_permohonan_masuk');
                } ,2000); 
              </script>";
    
    }

      
  } 

    // Owari


exit;
}


// Aksi = Tambah KUK
if( $aksi == "tambah_kuk"){

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }

  // Input ID Skema , ID Unit , ID Elemen
  $id_skema = mysqli_real_escape_string( $conn , $_POST['id_skema']);
    $id_unit = mysqli_real_escape_string( $conn , $_POST['id_unit']);
    $id_elemen = mysqli_real_escape_string( $conn , $_POST['id_elemen']);
  
  // No KUK + KUK
  $no_kuk = mysqli_real_escape_string( $conn , $_POST['no_kuk']);
    $kuk = mysqli_real_escape_string( $conn , $_POST['kuk']);

  
  // Untuk dapat assign nilai ID KUK 
    $cari_max = $conn->query("SELECT max(id_kuk) as id_kukTerbesar FROM `kriteria_unjuk_kerja` ; ");
      $data = mysqli_fetch_assoc($cari_max);

      // Mengambil data di array dengan indeks idTerbesar
      $id_kuk = $data['id_kukTerbesar'];

      // K00001
      $urutan = (int) substr($id_kuk, 1);

      // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
      $urutan = $urutan + 1 ;


      // membentuk kode barang baru
      // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
      $huruf = "K";
      $id_kuk = $huruf . sprintf("%05s", $urutan);
      
  // End Untuk dapat assign nilai ID KUK


  $input_kuk = $conn->query("INSERT INTO `kriteria_unjuk_kerja`(`id_kuk`, `id_elemen`, `no_kuk`, `kuk`) VALUES
  ('$id_kuk' , '$id_elemen' , '$no_kuk' , '$kuk'); ");

  if ( $input_kuk === TRUE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil Menambah KUK',
          text:  'Silahkan Tunggu!',
          icon: 'success',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }


  if ( $input_kuk === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal Menambah KUK',
          text:  'Mohon periksa data kembali !',
          icon: 'error',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }
  



exit;
}



// Aksi = Edit KUK
if( $aksi == "edit_kuk"){

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }

  // Input ID Skema , ID Unit , ID Elemen
  $id_skema = mysqli_real_escape_string( $conn , $_POST['id_skema']);
    $id_unit = mysqli_real_escape_string( $conn , $_POST['id_unit']);
    $id_elemen = mysqli_real_escape_string( $conn , $_POST['id_elemen']);
  
  // ID_kuk No KUK + KUK
  $id_kuk = mysqli_real_escape_string( $conn , $_POST['id_kuk']);
  $no_kuk = mysqli_real_escape_string( $conn , $_POST['no_kuk']);
    $kuk = mysqli_real_escape_string( $conn , $_POST['kuk']);


  $update_kuk = $conn->query("UPDATE `kriteria_unjuk_kerja` SET `no_kuk` = '$no_kuk' , `kuk` = '$kuk' 
  WHERE `id_kuk` = '$id_kuk' ;");

  if ( $update_kuk === TRUE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil Mengubah KUK',
          text:  'Silahkan Tunggu!',
          icon: 'success',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }


  if ( $update_kuk === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal Mengubah KUK',
          text:  'Mohon periksa data kembali !',
          icon: 'error',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }
  



exit;
}


// Aksi = Hapus KUK
if( $aksi == "hapus_kuk"){

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }

  // Input ID Skema , ID Unit , ID Elemen
  $id_skema = mysqli_real_escape_string( $conn , $_POST['id_skema']);
    $id_unit = mysqli_real_escape_string( $conn , $_POST['id_unit']);
    $id_elemen = mysqli_real_escape_string( $conn , $_POST['id_elemen']);
  
  // ID_kuk No KUK + KUK
  $id_kuk = mysqli_real_escape_string( $conn , $_POST['id_kuk']);
  

  $del_kuk = $conn->query("DELETE FROM `kriteria_unjuk_kerja` WHERE `id_kuk` = '$id_kuk' ;");

  if ( $del_kuk === TRUE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil Menghapus KUK',
          text:  'Silahkan Tunggu !',
          icon: 'success',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }


  if ( $del_kuk === FALSE ) {

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>

    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Gagal Menghapus KUK',
          text:  'Silahkan Tunggu !',
          icon: 'error',
          timer: 1000,
        });  
      },10); 

      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_kuk&&id_skema=$id_skema&&id_unit=$id_unit&&id_elemen=$id_elemen');
      } ,1500); 
    </script>";

  exit;
  }
  



exit;
}


if( $aksi == "reset_passwordasesi"){

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");

  exit;
  }

  $password = mysqli_real_escape_string( $conn , $_POST['password'] );

  $cek_password = $conn->query("SELECT `akun`.`password` FROM `akun`,`user_administrasi` WHERE `akun`.`email` = `user_administrasi`.`email` AND `user_administrasi`.`id_UA` = '$id_UA' ");
  $data = mysqli_fetch_assoc($cek_password);

  if ( !password_verify( $password, $data['password'] ) ) {
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
          window.location.replace('index.php?page=kelola_asesi');
        } ,2000); 
      </script>";
      exit;
  }

  $email_asesi = mysqli_real_escape_string( $conn , $_POST['email_asesi'] );

  $username = explode('@' , $email_asesi) [0];

  // tambah angka 12345 => gilangbagus.rama12345
  $password_baru1 = $username. '12345';

  $password_baru = password_hash( $password_baru1 , PASSWORD_DEFAULT);

  $update_password = $conn->query("UPDATE `akun` SET `password` = '$password_baru' WHERE `akun`.`email` = '$email_asesi' ;");

  if ( $update_password === FALSE ) {

    echo "     
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Reset Password',
            text:  'Internal Error',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_asesi');
        } ,2000); 
      </script>";
      exit;
  }

  if ( $update_password === TRUE ) {

    echo "     
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Berhasil Reset Password',
            text:  'Silahkan tunggu, Anda akan dialihkan',
            icon: 'success',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=kelola_asesi');
        } ,2000); 
      </script>";
      exit;
  }



exit;
}

if( $aksi == "validasi_mapa"){

  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  session_start(); include 'config.php';

  // Cek login
  $token = mysqli_real_escape_string( $conn , $_POST['token'] );
    $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
    $role = mysqli_real_escape_string( $conn , $_POST['role'] );

  if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
          !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
          !isset ( $id_UA ) || empty ( $id_UA ) ) {

      header("location: index.php?page=forbidden&aksi=not_login");


  exit;
  }

  $id_permohonan = mysqli_real_escape_string( $conn , $_POST['id_permohonan'] );
  $id_mapa = mysqli_real_escape_string( $conn , $_POST['id_mapa'] );
  $id_unit = mysqli_real_escape_string( $conn , $_POST['id_unit'] );
  
  $password = mysqli_real_escape_string( $conn , $_POST['password'] );

  $sql = $conn->query("SELECT `akun`.`password` FROM `akun`,`user_administrasi` WHERE `user_administrasi`.`email` = `akun`.`email` AND `user_administrasi`.`id_UA` = '$id_UA'");
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
          window.location.replace('index.php?page=validasi_mapa&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,2000); 
      </script>";
  exit;
  }

  $validasi_mapa = $conn->query("UPDATE `mapa` SET `divalidasi_oleh` = '$id_UA' , `timestamp_validasi` = CURRENT_TIMESTAMP() WHERE `id_mapa` = '$id_mapa' ");

  if ( $validasi_mapa === FALSE ){

    echo "
        
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
    
      <script type='text/javascript'>
        setTimeout(function () { 
          swal({
            title: 'Gagal Validasi MAPA',
            text:  'Internal Error',
            icon: 'error',
            timer: 2000,
            showConfirmButton: true
          });  
        },1000); 
        
        window.setTimeout(function(){ 
          window.location.replace('index.php?page=validasi_mapa&&id_permohonan=$id_permohonan&&id_mapa=$id_mapa&&id_unit=$id_unit');
        } ,2000); 
      </script>";

  exit;
  }

  echo "
        
  <link rel='stylesheet' href='dist/css/app.css'>
  <script src='dist/js/sweetalert.min.js'></script>
  
    <script type='text/javascript'>
      setTimeout(function () { 
        swal({
          title: 'Berhasil Validasi MAPA',
          text:  'Silahkan tunggu, Anda akan dialihkan',
          icon: 'success',
          timer: 2000,
          showConfirmButton: true
        });  
      },1000); 
      
      window.setTimeout(function(){ 
        window.location.replace('index.php?page=kelola_validasi_mapa&&id_permohonan=$id_permohonan');
      } ,2000); 
    </script>";



exit;
}






// Edit masih belum bisaaa
// if ( $aksi == "edit_asesor") {


//   error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//   session_start(); include 'config.php';

//   // Cek login
//   $token = mysqli_real_escape_string( $conn , $_POST['token'] );
//     $id_UA = mysqli_real_escape_string( $conn , $_POST['id_UA'] );
//     $role = mysqli_real_escape_string( $conn , $_POST['role'] );

//   if  ( !isset ( $token ) || empty ( $token ) ||  $token != $_SESSION['token'] ||
//           !isset ( $role ) || empty ( $role ) || $role != 'User Administrasi' ||
//           !isset ( $id_UA ) || empty ( $id_UA ) ) {

//       header("location: index.php?page=forbidden&aksi=not_login");


//   exit;
//   }

//   // End Cek Login


//   // Panggil id_asesor
//   $id_asesor = mysqli_real_escape_string( $conn , $_POST['id_asesor'] );


//   // Cek ganti E-Mail atau enggak

//     // Panggil E-Mail Lama dan Baru
//     $email_baru = strtolower( mysqli_real_escape_string($conn ,$_POST['email'] ) );
//     $nomor_asesor_baru = mysqli_real_escape_string( $conn, $_POST['nomor_asesor'] );
//     $nohp_baru = mysqli_real_escape_string( $conn, $_POST['nohp'] );

//     $nama_asesor = mysqli_real_escape_string( $conn, $_POST['nama_asesor'] );
//     $alamat_asesor = mysqli_real_escape_string( $conn, $_POST['alamat_asesor'] );


//     $email_lama = strtolower( mysqli_real_escape_string($conn ,$_POST['email_lama'] ) );
//     $nomor_asesor_lama = mysqli_real_escape_string( $conn, $_POST['nomor_asesor_lama'] );
//     $nohp_lama = mysqli_real_escape_string( $conn, $_POST['nohp_lama'] );


//   $sql_update_akun = $conn->query("UPDATE `akun` SET `email` = '$email_baru' , `nama` = '$nama_asesor' WHERE `email` = '$email_baru' AND `role` = 'Asesor' ");

//   if ( $sql_update_akun === FALSE  ){

//     echo "
        
//     <link rel='stylesheet' href='dist/css/app.css'>
//     <script src='dist/js/sweetalert.min.js'></script>
    
//       <script type='text/javascript'>
//         setTimeout(function () { 
//           swal({
//             title: 'Gagal Edit Data Asesor',
//             text:  'Nomor Asesor telah digunakan $email_baru $nohp_baru $nama_asesor $email_lama',
//             icon: 'error',
//             timer: 2000,
//             showConfirmButton: true
//           });  
//         },1000); 
        
//         window.setTimeout(function(){ 
//           window.location.replace('index.php?page=kelola_asesor');
//         } ,1500); 
//       </script>";
//     exit;
//   }

// exit;
// }




// if( $aksi == "edit_asesor") {


//   error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//   session_start(); include 'config.php';


//   // Cek sudah login
//     $token  = mysqli_real_escape_string( $conn, $_POST['token'] );
//     $id_UA  = mysqli_real_escape_string( $conn, $_POST['id_UA'] );
//     $role   = mysqli_real_escape_string( $conn, $_POST['role'] );

//     if  ( !isset($token) || empty ($token) ||  $token != $_SESSION['token'] ||
//         !isset($role) || empty($role) || $role != 'User Administrasi' ||
//         !isset($id_UA) || empty($id_UA)) {

//     header("location: index.php?page=forbidden&aksi=not_login");
//     exit;

//     }

//   // End Cek Login


//   // Panggil E-Mail dan ID Asesor

//     $id_asesor = mysqli_real_escape_string( $conn , $_POST['id_asesor'] );

//     $email_lama = mysqli_real_escape_string( $conn , $_POST['email_lama'] );

//     $email_baru = mysqli_real_escape_string( $conn , $_POST['email_baru'] );

//   // End Panggil

//   if ( $email_lama != $email_baru ) {

//     $update_akun = $conn->query(" UPDATE `akun` SET `email` = '$email_baru' WHERE `email` = '$email_lama' ; ");

//   }


//     // Kalau update akun gagal maka kode berhenti disini
//     if ( $update_akun === FALSE ) {


//       echo "
//       <link rel='stylesheet' href='dist/css/app.css'>
//       <script src='dist/js/sweetalert.min.js'></script>

//       <script type='text/javascript'>
//         setTimeout(function () { 
//           swal({
//             title: 'Gagal Mengedit Data Asesor',
//             text:  'E-Mail telah digunakan',
//             icon: 'error',
//             timer: 2000,
//           });  
//         },10); 

//         window.setTimeout(function(){ 
//           window.location.replace('index.php?page=kelola_asesor');
//         } ,3000); 
//       </script>";


//     exit;
//     }


//     // Jika berhasil maka lakukan update data asesor
//     if ( $email_lama == $email_baru || $update_akun === TRUE ) {


//       // Panggil data diri Asesor

//       $nama_asesor = mysqli_real_escape_string( $conn , $_POST['nama_asesor']  );

//       $nomor_asesor = mysqli_real_escape_string( $conn, $_POST['nomor_asesor'] );

//       $no_hp_asesor = mysqli_real_escape_string( $conn , $_POST['no_hp_asesor'] );

//       $alamat_asesor = mysqli_real_escape_string( $conn , $_POST['alamat_asesor'] );


//       // End Panggil data diri Asesor

//       // Query hapus

//       $edit_asesor = $conn->query(" UPDATE `asesor` SET `nomor` = '$nomor_asesor' , 
//       `nama` = '$nama_asesor' , `email` = '$email_baru' , `no_hp` = '$no_hp_asesor' , 
//       `alamat` = '$alamat_asesor' WHERE `id_asesor` = '$id_asesor'; ");


//       // Kalau edit data asesor gagal , maka kode berhenti disini
//       if ( $edit_asesor === FALSE ) {
      

//         echo "
//           <link rel='stylesheet' href='dist/css/app.css'>
//           <script src='dist/js/sweetalert.min.js'></script>

//           <script type='text/javascript'>
//             setTimeout(function () { 
//               swal({
//                 title: 'Gagal Mengedit Data Asesor',
//                 text:  'Silahkan tunggu',
//                 icon: 'error',
//                 timer: 2000,
//               });  
//             },10); 

//             window.setTimeout(function(){ 
//               window.location.replace('index.php?page=kelola_asesor');
//             } ,3000); 
//           </script>";
      
//       exit;
//       }


//       if ( $edit_asesor === TRUE ) {

//         echo "
//           <link rel='stylesheet' href='dist/css/app.css'>
//           <script src='dist/js/sweetalert.min.js'></script>

//           <script type='text/javascript'>
//             setTimeout(function () { 
//               swal({
//                 title: 'Berhasil Mengedit Data Asesor',
//                 text:  'Silahkan tunggu',
//                 icon: 'success',
//                 timer: 2000,
//               });  
//             },10); 

//             window.setTimeout(function(){ 
//               window.location.replace('index.php?page=kelola_asesor');
//             } ,3000); 
//           </script>";
      
      
//       }


//     }


//     // pengecekan apakah ganti skema asesor
//     $id_skema_baru = mysqli_real_escape_string( $conn , $_POST['skema_sertifikasi'] );
//     $id_skema_lama = mysqli_real_escape_string( $conn , $_POST['id_skema_lama'] );

//     if ( $id_skema_baru != $id_skema_lama  ) {

//       $update_skema_asesor = $conn->query(" UPDATE `skema_asesor` SET `id_skema` = '$id_skema_baru'
//       WHERE `id_asesor` = '$id_asesor' ");

      
//       if ( $update_skema_asesor === FALSE ) {

//         echo "
//         <link rel='stylesheet' href='dist/css/app.css'>
//         <script src='dist/js/sweetalert.min.js'></script>

//         <script type='text/javascript'>
//           setTimeout(function () { 
//             swal({
//               title: 'Gagal Mengubah Skema Asesor',
//               text:  'Silahkan tunggu',
//               icon: 'error',
//               timer: 2000,
//             });  
//           },10); 

//           window.setTimeout(function(){ 
//             window.location.replace('index.php?page=kelola_asesor');
//           } ,3000); 
//         </script>";
        

//       exit;
//       }

//     }





// exit;
// }


// Aksi = Edit Asesi
// edit_asesi
// }
// End Aksi = Edit Asesi

?>