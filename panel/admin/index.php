<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;

Utils::validateLoggedAssessor();

use App\Models\Affiliate;
$userModel = new Affiliate();
$activos = count($userModel->getLawyers(2));
$pendientes = count($userModel->getLawyers(1));
$deshabilitados = count($userModel->getLawyers(0));
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal Admin | Lexor Abogados</title>
  <!-- Favicons -->
  <link href="../assets/img/favicon.svg" rel="icon">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300..700&display=swap" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <!-- Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
</head>
<body>

<?php include('../header_sidebar.php');?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Portal del Administrador</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
          <li class="breadcrumb-item active">Portal</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-9">
          <div class="row">
           
            <!-- Abogados -->
            <div class="col-xxl-12">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Abogados</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Gestion de abogados</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Total de abogados</span></h5>
                  <section class="d-flex align-items-start">
                    
                    <div class="d-flex align-items-start me-3 border-end pe-3">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people text-primary"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-primary"><?= $activos + $pendientes + $deshabilitados ?></h6>
                        <span class="text-primary small pt-1 fw-bold">Total</span> 
                      </div>
                    </div>

                    <div class="d-flex align-items-start me-3 border-end pe-3">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-check-circle text-success"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-success"><?= $activos  ?></h6>
                        <span class="text-success small pt-1 fw-bold">Activos</span> 
                      </div>
                    </div>

                    <div class="d-flex align-items-start">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-warning"><?= $pendientes ?></h6>
                        <span class="text-warning small pt-1 fw-bold">Pendientes</span> 
                      </div>
                    </div>

                    <div class="d-flex align-items-start">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi  bi-x-octagon-fill text-danger"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-danger"><?= $deshabilitados ?></h6>
                        <span class="text-danger small pt-1 fw-bold">Deshabilitados</span> 
                      </div>
                    </div>

                  </section>

                </div>
              </div>
            </div><!-- END ESTADO DE CUENTA -->

            

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-3">

          

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Lexor</span></strong>. Todos los derechos reservados
    </div>
    <div class="credits">
      Diseñado <a href="https://twc.com.ar/">The Wireframes company</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Main JS File -->
  <script src="../assets/js/main.js?v=1"></script>

</body>

</html>