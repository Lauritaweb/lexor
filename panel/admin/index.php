<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;

Utils::validateLoggedAdmin();

use App\Models\Affiliate;
$userModel = new Affiliate();
$activos = count($userModel->getLawyers(2));
$pendientes = count($userModel->getLawyers(1));
$deshabilitados = count($userModel->getLawyers(0));

$affiliates = $userModel->getAll();
?>


<!DOCTYPE html>
<html lang="es">

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
<body class="toggle-sidebar">

<?php include('../header_sidebar.php');?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Portal del Administrador</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
          <li class="breadcrumb-item active">Portal></li>
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
                        <h6 class="text-success"><?= $activos ?></h6>
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
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- ESTADO DE CUENTA LISTADO -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="card-body pb-3 account-statement">

                  <div class="d-flex my-3">
                    <button type="button" class="btn btn-success" id="btn-add">
                      <i class="bi bi-plus-circle"></i> Agregar abogado
                    </button>
                    

                  </div>

                  <table id="dataTable" class="table table-borderless pt-4 mt-4">
                    <thead>
                      <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                      <!--  <th scope="col">Fecha de alta</th> -->
                        <th scope="col">Especialidad</th>
                        <th scope="col">Provincia</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($affiliates as $affiliate) { extract($affiliate); ?>
                      <tr style="text-align:left">
                        <td><?= $name ?></td>
                        <td><?= $last_name ?></td>
                        <!-- <td><?= Utils::formatDateUser($datetime); ?></td> -->
                        <td><?= $specialiation ?></td>
                        <td><?= $province ?></td>
                        <td>
                          <?php if ($active == 2) { ?>
                            <span class="badge bg-success">Activo</span>
                          <?php } else if ($active == 1) { ?>
                            <span class="badge bg-warning">Pendiente</span>
                          <?php } else{ ?>
                            <span class="badge bg-danger">Deshabilitado</span>
                          <?php }  ?>
                        </td>
                        <td class="btns-actions d-flex g-1">
                          <button type="button" class="btn btn-secondary btn-sm btn-view me-1" data-val="<?= $id ?>"><i class="bi bi-search" title="Ver"></i></button>
                          <button type="button" class="btn btn-primary btn-sm btn-view-degree me-1" data-val="<?= $url_file_degree ?>"><i class="bi bi-book" title="Ver titulo"></i></button>
                          <button type="button" class="btn btn-primary btn-sm btn-edit me-1" data-val="<?= $id ?>"><i class="bi bi-pencil" title="Editar"></i></button>
                          <?php if ($active == 1 || $active == 2) { ?>
                          <button type="button" class="btn btn-danger btn-sm btn-delete me-1" data-bs-toggle="modal" data-bs-target="#verticalycentered" data-val="<?= $id ?>"><i class="bi bi-trash" title="Eliminar"></i></button>
                          <?php } 
                              if ($active == 1 || $active == 0) { ?>
                              <button type="button" class="btn btn-danger btn-sm btn-activate" data-bs-toggle="modal" data-bs-target="#verticalycenteredActivate" data-val="<?= $id ?>"><i class="bi bi-check" title="Activar"></i></button>
                          <?php } 
                              if ($active == 2 || $active == 0) { ?>
                              <button type="button" class="btn btn-danger btn-sm btn-deactivate" data-bs-toggle="modal" data-bs-target="#verticalycenteredDeactivate" data-val="<?= $id ?>"><i class="bi bi-x-circle" title="Pendiente"></i></button>
                          <?php } ?> 
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>


                </div>

              </div>
            </div><!-- END ESTADO DE CUENTA LISTADO -->

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
  <script src="../assets/js/main.js?v=1"></script>

  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



<!-- MODAL activar usuario -->
<div class="modal fade" id="verticalycenteredActivate" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success">Activar abogado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-circle-fill text-success mx-auto d-block fs-1"></i>
        <p class="fw-bold">¿Confirma que desea activar al abogado?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-confirm-activate" data-bs-dismiss="modal">Activar abogado</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL activar usuario -->


<!-- MODAL ELIMINAR USUARIO -->
<div class="modal fade" id="verticalycentered" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Eliminar abogado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-circle-fill text-danger mx-auto d-block fs-1"></i>
        <p class="fw-bold">¿Confirma que desea eliminar al abogado?</p>
        <p class="fw-bold">Esta operación no se puede deshacer</p>
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger btn-confirm-delete" data-bs-dismiss="modal">Eliminar abogado</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL ELIMINAR USUARIO -->

<!-- MODAL DESACTIVAR USUARIO -->
<div class="modal fade" id="verticalycenteredDeactivate" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">desactivar abogado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-circle-fill text-danger mx-auto d-block fs-1"></i>
        <p class="fw-bold">¿Confirma que desea desactivar al abogado?</p>        
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger btn-confirm-deactivate" data-bs-dismiss="modal">Desactivar abogado</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL ELIMINAR USUARIO -->

  <script>
    document.getElementById('btn-add').onclick = function() {
        window.location.href = '../afiliado/user-profile.php?action=new'; 
    };
    document.querySelectorAll('.btn-view').forEach(function(button) {  
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-val');
            window.location.href = '../afiliado/user-profile.php?action=view&xIZZvbK2khQytHRK5h43HnuRh1aip7=' + id;
        });
    });
    document.querySelectorAll('.btn-edit').forEach(function(button) {  
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-val');
            window.location.href = '../afiliado/user-profile.php?action=edit&xIZZvbK2khQytHRK5h43HnuRh1aip7=' + id;
        });
    });
    document.querySelectorAll('.btn-view-degree').forEach(function(button) {  
        button.addEventListener('click', function() {
            var file = this.getAttribute('data-val');
            if (file != undefined && file != "")
              window.open('../afiliado/uploads/'+file, '_blank'); 
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
    window.location.href = '../afiliado/process_form.php?action=rm&id=' + deleteId;
  }
});

let activateId;

document.querySelectorAll('.btn-activate').forEach(function(button) {
  button.addEventListener('click', function() {
    activateId = this.getAttribute('data-val');
  });
});

document.querySelector('.btn-confirm-activate').addEventListener('click', function() {
  if (activateId) {
    window.location.href = '../afiliado/process_form.php?action=ac&id=' + activateId;
  }
});

document.querySelectorAll('.btn-deactivate').forEach(function(button) {
  button.addEventListener('click', function() {
    activateId = this.getAttribute('data-val');
  });
});

document.querySelector('.btn-confirm-deactivate').addEventListener('click', function() {
  if (activateId) {
    window.location.href = '../afiliado/process_form.php?action=de&id=' + activateId;
  }
});


  </script>


</body>

</html>