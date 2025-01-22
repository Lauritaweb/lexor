
<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;

$id_affiliate = $_SESSION['id_affiliate']; // usuario logueado

use App\Models\Affiliate;
$userModel = new Affiliate();

use App\Models\Appointments;
$appointmentModel = new Appointments();
$appointments = $appointmentModel->getFromIdAffiliate($id_affiliate);
$nextAppointment = $appointmentModel->getNextAppointment($id_affiliate);
$affiliatePayments = $userModel->getPayments($id_affiliate);
$affiliatePaymentsSummarise = $userModel->getPayments($id_affiliate, true)[0];
$affiliateCompletedPercentage = $userModel->getCompletedPorcentage($id_affiliate);
// var_dump($affiliatePaymentsSummarise);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Lexor</title>
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

<?php  include('../header_sidebar.php');?>

  <main id="main" class="main bg-black">

    <div class="pagetitle">
      <h1>Tablero</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.ph">Inicio</a></li>
          <li class="breadcrumb-item active">Portal</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- DATOS PERSONALES -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Datos</h6>
                    </li>
                    <li><a class="dropdown-item" href="./user-profile.php">Editar</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title"><a href="user-profile.php">Tus Datos </a> <span>| Hoy</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>Completos</h6>
                      <span class="text-success small pt-1 fw-bold"><?= $affiliateCompletedPercentage ?>%</span> 
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- END DATOS PERSONALES -->

            

            <!-- CITAS -->
            <div class="col-xxl-7 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                     <h6>Tus citas</h6>
                    </li>
                    <li><a class="dropdown-item" href="./appointments.php">Gestionar citas</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title"> <a href="appointments_list.php">Tus Citas  </a> <span>| próxima </span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div class="ps-3">
                      <?php if ($nextAppointment != null) { ?>
                      <h6>Cita: <?= $nextAppointment != null ? $nextAppointment['type'] : "" ?></h6>
                      <div class="d-flex align-items-center">
                        <span class="text-success small pt-1 fw-bold"><?= $nextAppointment != null ? Utils::formatDateUser($nextAppointment['date']) : ""; ?></span> 
                        <span class="text-muted small pt-2 ps-1"><?= $nextAppointment != null ? $nextAppointment['status'] : "" ?> - </span>
                        <span class="text-muted small pt-2 ps-1"><?= $nextAppointment != null ? $nextAppointment['name_lastname'] : "" ?></span>
                        <div class="d-flex ms-3">
                          <button type="button" class="btn btn-danger me-2 btn-sm" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                            <i class="bi bi-trash"></i>
                          </button>
                          <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='appointments.php?id_appointment=<?= $nextAppointment['id'] ?>'">
                            <i class="bi bi-pencil"></i>
                          </button>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- END CITAS -->


          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- HISTORIAL DE CITAS -->
          <div class="card border-key text-key">

            <div class="card-body">
              <h5 class="card-title text-white">Historial de citas</h5>

              <div class="activity">
                <?php 
                foreach($appointments as $appointment){
                    if ($appointment['date'] >= $today = date('Y-m-d') )
                        $style = "success";
                    else
                        $style = "danger";
                  ?>                
                <div class="activity-item d-flex">
                  <div class="activite-label"><?=  Utils::formatDateUser($appointment['date']) . ' ' . $appointment['time'] ?></div>
                  <i class='bi bi-circle-fill activity-badge text-<?= $style ?> align-self-start'></i>
                  <div class="activity-content">
                  <?= $appointment['name_lastname'] ?>
                  </div>
                </div><!-- End activity item-->
                <?php } ?>
                
              </div>

            </div>
          </div><!-- END HISTORIAL DE CITAS -->

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- MODAL CANCELAR CITA -->
  <div class="modal fade" id="verticalycentered" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger">Cancelar Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-exclamation-circle-fill text-danger mx-auto d-block fs-1"></i>
          <p class="fw-bold">¿Confirma que desea eliminar la cita?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Eliminar cita</button>
        </div>
      </div>
    </div>
  </div><!-- End Vertically centered Modal-->
  <!-- END MODAL CANCELAR CITA -->

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
  <script src="../assets/js/main.js"></script>

</body>

</html>