<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start();

// Untuk mengecek apakah user benar benar mau logout
$email = $_POST['email'];


    //  jika tidak ada email
    if ( !isset($email) && $email != $_SESSION['email'] ) {

        echo "

        <link rel='stylesheet' href='dist/css/app.css'>
        <script src='dist/js/sweetalert.min.js'></script>
            
            <script type='text/javascript'>
                setTimeout(function () { 
                    swal({
                            title: 'Logout Gagal',
                            icon: 'error',
                            timer: 2000,
                        });  
                },10); 
                
                window.setTimeout(function(){ 
                    window.location.replace('index.php?page=dashboard_asesi');
                } ,3000); 
            </script> ";

        exit ;  

    }
    // End Jika tidak ada email


    // Jika ada email maka bisa logout

        session_start();

        $_SESSION = array();
        
        session_unset();

        session_destroy();
        
        echo "

            <link rel='stylesheet' href='dist/css/app.css'>
            <script src='dist/js/sweetalert.min.js'></script>
            
            <script type='text/javascript'>
                setTimeout(function () { 
                    swal({
                        title: 'Logout Berhasil',
                        text:  'Harap tunggu anda akan diarahkan ke halaman awal',
                        icon: 'success',
                        timer: 2000,
                    });  
                },10); 
                
                window.setTimeout(function(){ 
                    window.location.replace('index.php');
                } ,3000); 
            </script>";

        exit;

    // End Bisa Logout

// End Logout
?>

