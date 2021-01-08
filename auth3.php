<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
include "config.php";

// include all parameter
$token_verif      = $_GET['token'];
$email            = strtolower ( base64_decode($_GET['email']) ) ;

// cek email apakah ada atau tidak

$cek_email = $conn->query("SELECT * FROM `akun` WHERE `email` = '$email' AND `token_verif` = '$token_verif' ; ");

$cek_rows  = mysqli_num_rows($cek_email);

if ( $cek_rows != 1 ) { // Jika email dan token tidak ketemu

    echo "

        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>

        <script type='text/javascript'>
            setTimeout(function () { 
            swal({
                    title: 'Verifikasi Gagal',
                    text: 'Link Verifikasi salah',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: true
                });  
            },10); 
            window.setTimeout(function(){ 
            window.location.replace('index.php?page=login');
            } ,3000); 
        </script> ";

    exit;

}


$cek = mysqli_fetch_assoc($cek_email);

    if ($cek['status_akun'] == "Not Verif") { // Display Halaman Login


        include 'auth_login.php';


            if($_POST['auth-login']) { // Jika Tekan Login

                $email_login = strtolower ( mysqli_real_escape_string( $conn , $_POST['email'] ) ) ;

                if ( $email_login != $email ) { // Jika email input tidak sama dengan email

                    echo "
                
                        <link rel='stylesheet' href='dist/css/app.css'>
                        <script src='dist/js/sweetalert.min.js'></script>
                    
                        <script type='text/javascript'>
                            setTimeout(function () { 
                                swal({
                                    title: 'Verifikasi Gagal',
                                    text: 'E-Mail harus sama dengan E-Mail terdaftar',
                                    icon: 'error',
                                    timer: 2000,
                                    showConfirmButton: true
                                });  
                            },10); 

                            window.setTimeout(function(){
                                window.location.replace('index.php');
                            } ,3000); 
                        </script> ";
                    exit;
                }

                $pass = mysqli_real_escape_string ( $conn , $_POST['password'] ) ;

                $cekemail_login = $conn->query("SELECT `email` , `password` FROM `akun` WHERE `email` = '$email_login';");
                $data = mysqli_fetch_assoc($cekemail_login);


                // Pengecekan Password
                if(password_verify($pass,$data['password'])) { // Jika pass benar

                    // Ganti status menjadi verif
                    $ganti_status = $conn->query("UPDATE `akun` SET `status_akun` = 'Verif' , `token_verif` = NULL WHERE `email` = '$email';");

                    
                    if ( $ganti_status === FALSE ) {

                        echo "
                
                            <link rel='stylesheet' href='dist/css/app.css'>
                            <script src='dist/js/sweetalert.min.js'></script>
                        
                            <script type='text/javascript'>
                                setTimeout(function () { 
                                    swal({
                                        title: 'Verifikasi Gagal',
                                        text: 'Unknown Error',
                                        icon: 'error',
                                        timer: 2000,
                                        showConfirmButton: true
                                    });  
                                },10); 

                                window.setTimeout(function(){
                                    window.location.replace('index.php');
                                } ,3000); 
                            </script> ";
                    exit;


                    }


                    echo "
                        <link rel='stylesheet' href='dist/css/app.css'>
                        <script src='dist/js/sweetalert.min.js'></script>

                        <script type='text/javascript'>
                            setTimeout(function () { 
                                swal({
                                            title: 'Verifikasi Berhasil',
                                            text:  'Akun berhasil di Verifikasi',
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: true
                                        });  
                                },10); 
                                window.setTimeout(function(){ 
                                    window.location.replace('index.php');
                                } ,3000); 
                                </script>
                                ";

                                session_start();

                                $_SESSION = [];
                                
                                session_unset();
                        
                                session_destroy();

                } else { // Jika pass salah
                        echo '<div class="alert alert-warning" role="alert">Password Salah.</div>'; 
                }


            } // Jika Tekan Login


    } 

    else { // Jika Akun Telah Verif

        echo "
            <link rel='stylesheet' href='dist/css/app.css'>
            <script src='dist/js/sweetalert.min.js'></script>
            
            <script type='text/javascript'>
                setTimeout(function () { 
                swal({
                        title: 'Akun telah diverifikasi',
                        icon: 'warning',
                        timer: 2000,
                        showConfirmButton: true
                    });  
                },10); 
                window.setTimeout(function(){ 
                window.location.replace('index.php');
                } ,3000); 
            </script> ";

        exit;

    } // Jika Akun Telah Verif


?>