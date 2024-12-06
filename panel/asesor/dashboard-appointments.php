<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

Utils::validateLoggedAssessor();

use App\Models\Assessor;
$assessorModel = new Assessor();

use App\Models\Appointments;
$appointmentModel = new Appointments();

$appointments = $appointmentModel->getAll(true);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal Asesor | Gestión de Citas | Respaldar Argentina</title>
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
      <h1>Gestión de citas</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item">
            <a href="../asesor/index.php">Inicio</a>
          </li>
          <li class="breadcrumb-item active">Crear una cita</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile card">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12 card-body">
          <button type="button" class="btn btn-success mt-4 mx-2" id="btn-add-appoiment">
            <i class="bi bi-plus-circle"></i> Agregar cita
          </button>
          <div class="row p-4">
            <table id="dataTable" class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">Afiliado</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Hora</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Prioridad</th>
                  <th scope="col">Status</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>

              <?php foreach($appointments as $appointment){
                  extract($appointment);
               ?>
                <tr>
                  <th><?= $name . ' ' . $last_name ?></th>
                  <td><?= Utils::formatDateUser($date) ?></td>
                  <td><?= $time ?>hs</td>
                  <td><?= $type ?></td>
                  <td><span class="badge <?= $html_style ?>"><?= $urgency ?></span></td>
                  <td><?= $status ?></span></td>

                  <td>
                    <button type="button" class="btn btn-primary me-2  btn-sm btn-modify" data-val="<?= $id ?>">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-success me-2 btn-sm btn-status" data-bs-toggle="modal" data-bs-target="#divSetStatus" data-val="<?= $id ?>">
                      <i class="bi bi-check"></i>
                    </button>
                    <button type="button" class="btn btn-danger me-2 btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#verticalycentered" data-val="<?= $id ?>">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php } ?>
                
              </tbody>
            </table>

          </div><!-- ends row -->
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

  
<!-- MODAL ELIMINAR CITA -->
<div class="modal fade" id="verticalycentered" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Eliminar cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-circle-fill text-danger mx-auto d-block fs-1"></i>
        <p class="fw-bold">¿Confirma que desea rechazar la cita?</p>
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger btn-confirm-delete" data-bs-dismiss="modal">Eliminar cita</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL ELIMINAR CITA -->


<!-- MODAL CONFIRMAR CITA -->
<div class="modal fade" id="divSetStatus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success">Confirmar cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-circle-fill text-success mx-auto d-block fs-1"></i>
        <p class="fw-bold">¿Confirma que desea confirmar la cita?</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-confirm-status" data-bs-dismiss="modal">Confirmar cita</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL CONFIRMAR CITA -->

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- JS File -->
  <script src="../assets/js/main.js"></script>

  <script>
    document.getElementById('btn-add-appoiment').onclick = function() {
        window.location.href = '../afiliado/appointments.php'; 
    };
  </script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejador para el botón de modificar
    document.querySelectorAll('.btn-modify').forEach(function(button) {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-val');
        window.location.href = '../afiliado/appointments.php?xIZZvbK2khQytHRK5h43HnuRh1aip7=' + id;
      });
    });

    
    let deleteId;

    document.querySelectorAll('.btn-delete').forEach(function(button) {
      button.addEventListener('click', function() {
        deleteId = this.getAttribute('data-val');
      });
    });
    
    document.querySelector('.btn-confirm-delete').addEventListener('click', function() {
      if (deleteId) {
        window.location.href = '../afiliado/processAppointment.php?action=rm&id=' + deleteId;
      }
    });


    let updateStatusId;

    document.querySelectorAll('.btn-status').forEach(function(button) {
      console.log("confirmar");
      button.addEventListener('click', function() {
        updateStatusId = this.getAttribute('data-val');

      });
    });
    
    document.querySelector('.btn-confirm-status').addEventListener('click', function() {
      if (updateStatusId) {
        window.location.href = '../afiliado/processAppointment.php?action=confirm&id=' + updateStatusId;
      }
    });

  });
</script>


</body>

</html>