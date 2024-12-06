<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;
use App\Models\Assessor;

$assessorModel = new Assessor();

$payments = $assessorModel->getPaymentsEverybody(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal Administrador | Respaldar Argentina</title>
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
      <h1>Rendición bancaria
        <i class="bi bi-bank"></i>
      </h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="<?= $home ?>">Inicio</a></li>
          <li class="breadcrumb-item active">Rendición bancaria</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- ESTADO DE CUENTA -->
            <div class="col-xxl-12">
              <div class="card info-card revenue-card">
                  <div class="card-body">
                      <h5 class="card-title">Carga de archivo</h5>
                      <section>
                          <div class="pt-4 pb-2 bg-danger" id="divError"  <?php if(!(isset($_GET['success']) && $_GET['success'] == "false")) echo 'style="display: none;"'; ?> >
                              <p class="text-center small">Error subiendo y/o procesando el archivo</p>
                          </div>
                          <div class="pt-4 pb-2 bg-success" id="divSucess" <?php if(!(isset($_GET['success']) && $_GET['success'] == "true")) echo 'style="display: none;"'; ?> >
                              <p class="text-center small">Archivo subido y procesado correctamente</p>
                          </div>
                          <div class="card-body">
                              <p class="activity">
                                  <i class="bi bi-exclamation-triangle-fill"></i>
                                  Cargue el archivo .xls del banco para actualizar el estado de cuenta de los afiliados este mes. 
                                  Asegúrese de usar el archivo correcto.
                              </p>
                          </div>
                      </section>
                      <section>
                          <div class="card-body">
                              <form action="upload_file.php" method="post" enctype="multipart/form-data">
                                  <div class="row mb-3">
                                      <div class="col-sm-10">
                                          <input class="form-control" type="file" name="file" id="formFile">
                                      </div>
                                  </div>
                                  <div class="row mb-3">
                                      <div class="col-sm-10">
                                          <button type="submit" class="btn btn-primary">Subir archivo al sistema</button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </section>
                  </div>
              </div>
          </div>
          <!-- ESTADO DE CUENTA LISTADO -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>ESTADO DE CUENTA</h6>
                  </li>
                </ul>
              </div>

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
          </div>
        </div><!-- End Left side columns -->
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