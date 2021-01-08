<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include 'config.php';

// Nomor Skema
$cari_max = $conn->query("SELECT max(id_skema) as idTerbesar FROM `skema_asesi`");
$data = mysqli_fetch_assoc($cari_max);
    
    // Mengambil data di array dengan indeks idTerbesar
    $kodeSkema = $data['idTerbesar'];

        // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
        // dan diubah ke integer dengan (int)
        // A(1) S(2) 0(3) 0(4) 1(5) S 1 K 2 M 3 0 4 0 1
        $urutan = (int) substr($kodeSkema, 3, 3);

        // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
        $urutan++;

        // membentuk kode barang baru
        // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
        // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
        // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
        $huruf = "SKM";
        $kodeSkema = $huruf . sprintf("%03s", $urutan);

// Khusus nomor
$garing = "/";
$tahun = date("Y");
$nomor_skema = $kodeSkema . '/' . $tahun ;

$id_UA = mysqli_real_escape_string($conn, $_POST['id_UA'] );
$nama_skema = mysqli_real_escape_string($conn, $_POST['nama_skema'] );

// Ganti ke file asli di $_FILES
$file_skema = "ini skema.pdf";

$tambah_skema = $conn->query("INSERT INTO `skema_asesi`(`id_skema`, `nomor_skema`, `nama_skema`, `file`, `validasi_id_admin`) 
VALUES ( '$kodeSkema' , '$nomor_skema' , '$nama_skema' , '$file_skema' , '$id_UA' ) ");

if ( $tambah_skema ) {
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

} else { // Jika gagal input

    echo "
    <link rel='stylesheet' href='dist/css/app.css'>
    <script src='dist/js/sweetalert.min.js'></script>
              <script type='text/javascript'>
               setTimeout(function () { 
               swal({
                          title: 'Skema Gagal di Tambah',
                          text:  'Silahkan $nomor_skema !',
                          icon: 'error',
                          timer: 2000,
                      });  
               },10); 
               window.setTimeout(function(){ 
                window.location.replace('index.php?page=kelola_skema_sertifikasi');
               } ,3000); 
              </script>
              ";
}

?>