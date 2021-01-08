<div class="navbar-custom-menu">


<!-- Kontainer Dropdown Menu Profil  -->
<ul class="nav navbar-nav"> 


  <!-- Menu Dropdown Profile -->
  <li class="user user-menu">


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




  </li>


  <!-- Button logout -->
  <li>

    <a class="fa fa-sign-out" href="#"
      data-toggle="modal" data-target="#ModalLogout">
    </a>

  </li>


</ul> <!-- <ul class="nav navbar-nav"> -->


</div>