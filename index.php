<?php
//link dinamis
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

if( isset($_GET['page']) ) {
  
  $page = $_GET['page'];
  switch ($page) {

    // Jika page not_found
    default:
      include "404.php";
    break;
    // End jika page not_found

    case 'ganti-email':
      include "ganti_email.php";
    break;

    case 'reset-password':
      include "reset_password.php";
    break;

    case 'pengaturan_akun':
      include "pengaturan_akun.php";
    break;

    case 'ganti_password':
      include "ganti_password.php";
    break;

    case 'logout':
      include "logout.php";
    break;

    case 'forbidden':
      include "forbidden.php";
    break;

    case 'lupa_password':
      include "lupa_password.php";
    break;

    case 'batal-daftar':
      include "batal-daftar.php";
    break;


    case 'logout':
      include "logout.php";
    break;


    // User Administrasi

      case 'dashboard_user_admin':
        include "dashboard_user_admin.php";
      break;
      
      case 'kelola_validasi_mapa':
        include "kelola_validasi_mapa.php";
      break;

      case 'validasi_mapa':
        include "validasi_mapa.php";
      break;

      case 'detil_permohonan':
        include "detil_permohonan.php";
      break;

      case 'kelola_skema_sertifikasi':
        include "kelola_skema_sertifikasi.php";
      break;

      case 'kelola_syarat_dasar':
        include "kelola_syarat_dasar.php";
      break;

      case 'kelola_unit_skema':
        include "kelola_unit_skema.php";
      break;

      case 'kelola_elemen_unit':
        include "kelola_elemen_unit.php";
      break;

      case "kelola_kuk":
        include "kelola_kuk.php";
      break;

      case 'kelola_asesi':
        include "kelola_pengguna_asesi.php";
      break;

      case 'kelola_asesor':
        include "kelola_pengguna_asesor.php";
      break;

      case 'kelola_user_admin':
        include "kelola_pengguna_user_administrasi.php";
      break;

      case 'kelola_permohonan_masuk':
        include "kelola_permohonan_masuk.php";
      break;

      case 'kelola_permohonan_ditolak':
        include "kelola_permohonan_ditolak.php";
      break;

      case 'kelola_permohonan_diproses':
        include "kelola_permohonan_diproses.php";
      break;

      case 'kelola_kelengkapan_bukti_sertifikasi':
        include "kelola_kelengkapan_bukti_sertifikasi.php";
      break;

      case 'kelola_berita_sertifikasi':
        include "kelola_berita_sertifikasi.php";
      break;




    // End User Administrasi


    // Asesi
    
      case 'register':
        include "register.php";
      break;
      
      case 'login':
        include "login.php";
      break;

      case 'dashboard_asesi':
        include "dashboard_asesi.php";
      break;

      case 'data_permohonan':
        include "data_permohonan.php";
      break;

      case 'kelola_data_permohonan':
        include "kelola_data_permohonan.php";
      break;

      case 'auth':
        include "auth.php";
      break;

      case 'identitas':
        include "identitas.php";
      break;

      case 'ajukan_permohonan':
        include "ajukan_permohonan.php";
      break;

      case 'daftar_permohonan':
        include "daftar_permohonan.php";
      break;

      case 'asesmen_mandiri':
        include "asesmen_mandiri.php";
      break;

      case 'ajukan_banding':
        include "ajukan_banding.php";
      break;

    // End Asesi


    // Asesor

      case 'dashboard_asesor':
        include "dashboard_asesor.php";
      break;

      case 'tambah_mapa':
        include "tambah_mapa.php";
      break;

      case 'edit_mapa':
        include "edit_mapa.php";
      break;

      case 'kelola_mapa':
        include "kelola_mapa.php";
      break;

      case 'tambah_mapa':
        include "tambah_mapa.php";
      break;

      case 'kelola_peta_muk':
        include "kelola_peta_muk.php";
      break;

      case 'kelola_asesmen_asesi':
        include "kelola_asesmen_asesi.php";
      break;

      case 'lihat_data_permohonan':
        include "lihat_data_permohonan.php";
      break;

      case 'kelola_MAPA':
        include "kelola_MAPA.php";
      break;

      case 'kelola_data_asesmen_mandiri':
        include "kelola_data_asesmen_mandiri.php";
      break;

      case 'persetujuan_asesmen':
        include "persetujuan_asesmen.php";
      break;

    // End Asesor


    case 'home':
      include "home.php";
    break;

    case 'dashboard':
      include "dashboard.php";
    break;

    case 'dashboard_asesor':
      include "dashboard_asesor.php";
    break;

    case 'admin':
      include "admin.php";
    break;

    case 'profile':
      include "profile.php";
    break;

    case 'list_info':
      include "list_info.php";
    break;


  } // Switch
exit;
} // if isset page

if( !isset($_GET['page']) ) {

  session_start();

  $role = $_SESSION['role'] ;


    if( $role == 'User Administrasi' ) {
      include "dashboard_user_admin.php";
    }


    if( $role == 'Asesi' ) {
      include "dashboard_asesi.php";
    }


    if ( $role == 'Asesor' ) {
      include "dashboard_asesor.php";
    }

    if ( $role == '' ) {
      include "login.php";
    }


exit;
}

else {

  include 'login.php';

}

?>