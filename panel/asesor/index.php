<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;
use App\Models\Assessor;

Utils::validateLoggedAssessor();

$assessorModel = new Assessor();

use App\Models\Appointments;
$appointmentModel = new Appointments();


$deudores = $assessorModel->getDebtors(true);
$no_deudores = $assessorModel->getDebtors(false);
$appointments = $appointmentModel->getAll(true);

$payments = $assessorModel->getPaymentsEverybody(); 

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal Asesor | Respaldar Argentina</title>
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
      <h1>Portal del Asesor</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="<?= $home ?>">Inicio</a></li>
          <li class="breadcrumb-item active">Portal</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-9">
          <div class="row">

            <!-- AFILIADOS 
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Afiliados</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Ver listado</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Afiliados <span>| Todos</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>Total</h6>
                      <span class="text-success small pt-1 fw-bold">4.520</span> 
                    </div>
                  </div>
                </div>

              </div>
            </div>--><!-- END AFILIADOS -->

            <!-- ESTADO DE CUENTA -->
            <div class="col-xxl-12">
              <div class="card info-card revenue-card">

                <!-- <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Cuenta</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Ver estado de mi cuenta</a></li>
                  </ul>
                </div> -->

                <div class="card-body">
                  <h5 class="card-title">Estados de cuentas <span>| Este mes</span></h5>
                  <section class="d-flex align-items-start">
                    
                    <div class="d-flex align-items-start me-3 border-end pe-3">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people text-primary"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-primary"><?= $deudores + $no_deudores?></h6>
                        <span class="text-primary small pt-1 fw-bold">Afiliados</span> 
                      </div>
                    </div>

                    <div class="d-flex align-items-start me-3 border-end pe-3">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-check-circle text-success"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-success"><?= $no_deudores  ?></h6>
                        <span class="text-success small pt-1 fw-bold">Afiliados al día</span> 
                      </div>
                    </div>

                    <div class="d-flex align-items-start">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-triangle text-danger"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="text-danger"><?= $deudores ?></h6>
                        <span class="text-danger small pt-1 fw-bold">Afiliados en mora</span> 
                      </div>
                    </div>

                  </section>

                </div>
              </div>
            </div><!-- END ESTADO DE CUENTA -->

            <!-- ESTADO DE CUENTA LISTADO -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>ESTADO DE CUENTA</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Este mes</a></li>
                    <li><a class="dropdown-item" href="#">El mes pasado</a></li>
                    <li><a class="dropdown-item" href="#">Todo el historial</a></li>
                  
                  </ul>
                </div>-->

                <div class="card-body">
                  <h5 class="card-title">Estado de cuenta <span>| Este mes</span></h5>

                  <table id="dataTable" class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Afiliado</th>
                        <th scope="col">Comprobante</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Concepto</th>
                        <th scope="col">Importe solicitado</th>
                        <th scope="col">Importe debitado</th>
                        <th scope="col">Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($payments as $payment){
                        extract($payment);
                        
                        if ($charged)
                            $estado = " <span class='badge bg-success'>$result</span>";
                        else
                            $estado = " <span class='badge bg-danger'>$result</span>";
                      ?>
                      <tr>
                        <th><?= $name . ' ' . $last_name?></th>
                        <td><?= $id ?> </td>
                        <td><?= Utils::formatDateUser($payment_date) ?> </td>
                        <td>FACTURA</td>
                      
                        <td> <?= $amount_requested ?> </td>
                       <td> <?= $amount?> </td>
                       <td><?= $estado  ?></td>
                      </tr>
                      <?php } ?>
                     
                    </tbody>
                  </table>

                </div>

              </div>
            </div>
            <!-- END ESTADO DE CUENTA LISTADO -->

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-3">

          <!-- HISTORIAL DE CITAS -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Citas</h6>
                </li>
<!--
                <li><a class="dropdown-item" href="#">Hoy</a></li>
                <li><a class="dropdown-item" href="#">Este mes</a></li>
                      -->
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Agenda de citas</h5>

              <div class="activity">
                <?php foreach($appointments as $appointment){
                  extract($appointment);
                 
                 ?>
                <div class="activity-item d-flex">
                  <div class="activite-label"><?= Utils::formatDateUser($date) ?> </div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    <ul class="appointments">
                      <li class="fw-bold"><?= $time ?> </li>
                      <li><?= $name . ' ' . $last_name ?> </li>
                      <li><?= $type ?></li>
                      <li><?= $email ?></li>
                      <li><?= $phone ?></li>
                      <li class="fw-bold"><?= $status ?></li>
                    </ul>
                  </div>
                </div><!-- End activity item-->
                <?php }?>

                

              </div>

            </div>
          </div><!-- END HISTORIAL DE CITAS -->

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
  <script src="../assets/js/main.js"></script>

</body>

</html>