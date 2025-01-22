<?php 
use App\Utils\Utils;

if (Utils::isAssessorLogged() || Utils::isAdminLogged()){ 
    $perfil = "Admin";
    $home = "../admin/";    
?>
 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center bg-black">

<div class="d-flex align-items-center justify-content-between">
  <a href="<?= $home; ?>index.php" class="logo d-flex align-items-center">
    <img src="../assets/img/logo.svg" alt="">
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<nav class="header-nav ms-auto bg-black">  
  <ul>
    <li class="nav-item dropdown pe-3">
      <!-- users avatar -->
      <a class="nav-link nav-profile d-flex align-items-centheader_sidebarer pe-0" href="#" data-bs-toggle="dropdown">
        <img src="../assets/img/avatar.png" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['name'] ?></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
            <h6><?= $_SESSION['name'] ?></h6>
            <span><? $_SESSION['last_name'] ?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="../salir.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Cerrar sesión</span>
            </a>
        </li>
      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->
  </ul>
</nav><!-- End Icons Navigation -->
</header><!-- End Header -->
  

<?php } else if (Utils::isAffiliateLogged()){ ?>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="./index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/logo.svg" alt="">
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav  class="header-nav ms-auto">  
      <ul>
        <li class="nav-item dropdown pe-3">
          <!-- users avatar -->
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/avatar.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['name'] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
                <h6><?= $_SESSION['name'] ?></h6>
                <span><? $_SESSION['last_name'] ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="../salir.php">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Cerrar sesión</span>
                </a>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
          <a href="./index.php" class="fw-bold text-primary">
            <!-- <i class="bi bi-scale"></i> -->
            <img src="../assets/img/scale.svg" alt="" class="nav-item-icon me-1">
            <span>Portal del Abogado</span>
          </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="./user-profile.php">
          <i class="bi bi-person"></i>
          <span>Datos Personales</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="./appointments_list.php">
          <i class="bi bi-calendar2-check"></i>
          <span>Gestión de citas</span>
        </a>
      </li>    

<?php } ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../salir.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Cerrar sesión</span>
        </a>
      </li>
      
    </ul>
  </aside><!-- End Sidebar-->
