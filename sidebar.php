<?php

  session_start();

  $role = $_SESSION['role'] ;

  switch ($role) {

    case 'User Administrasi':
      include "sidebar_admin.php";
    break;

    case 'Asesi':
      include "sidebar_asesi.php";
    break;

    case 'Asesor':
      include "sidebar_asesor.php";
    break;

    default:
      include "sidebar_default.php";
    break;

  }

?>