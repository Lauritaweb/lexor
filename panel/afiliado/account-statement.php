<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Affiliate;
$userModel = new Affiliate();

if (isset($_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7'])){
    $id_affiliate = $_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7']; // Gestor logueado y desde pantalla listado afiliados
    $_SESSION['id_affiliate_edit'] = $id_affiliate;
}  
else
    $id_affiliate = $_SESSION['id_affiliate']; // usuario logueado 
  

$affiliatePayments = $userModel->getPayments($id_affiliate);


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Estado de cuenta | Portal Respaldar Argentina</title>
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

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Estado de cuenta</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
          <li class="breadcrumb-item active">Estado de cuenta</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
            <!-- ESTADO DE CUENTA LISTADO -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">
<!--
  //  No tiene sentido filtrar por este mes o el anterior, son pagos mensuales
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>ESTADO DE CUENTA</h6>
                    </li>
                    
                    <li><a class="dropdown-item" href="#">Este mes</a></li>
                    <li><a class="dropdown-item" href="#">El mes pasado</a></li>
                    <li><a class="dropdown-item" href="#">Todo el historial</a></li>
    
                  </ul>
                </div>
-->
                <div class="card-body">
                  <h5 class="card-title">Estado de cuenta <span>| Hoy</span></h5>

                  <table id="dataTable" class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Comprobante</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Concepto</th>
                        <th scope="col">Importe solicitado</th>
                        <th scope="col">Importe abonado</th>
                        <th scope="col">Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($affiliatePayments as $payment){ 
                        extract($payment);
                      ?>
                      <tr>
                        <th scope="row">#<?= $id ?></th>
                        <td><?= $payment_date ?></td>
                        <td>Débito automatico</td>
                        <td>$<?= $amount_requested ?></td>
                        <td>$<?= $amount ?></td>
                        <td>
                        <?php
                          $charged ? $class = "bg-success" :  $class = "bg-danger";
                          // $charged ? $text = "Abonado" :  $text = "No abonado" 
                        ?>
                        
                        <span class="badge <?= $class ?>"><?= $result ?></span></td>
                      </tr>
                      <?php } ?>
                     
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- END ESTADO DE CUENTA LISTADO -->

          </div>
        </div><!-- End Left side columns -->

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