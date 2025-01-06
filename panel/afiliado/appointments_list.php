<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Appointments;
$appointmentModel = new Appointments();

use App\Models\Affiliate;
$affiliateModel = new Affiliate();

$id_affiliate = $_SESSION['id_affiliate']; 
$allNextAppointment = $appointmentModel->getNextAppointment($id_affiliate, true);



$appointment = null;

if (Utils::isAssessorLogged() || Utils::isAdminLogged() ){
	if (isset($_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7'])){ // Es usuario asesor
		$id_appointment = $_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7']; // Gestor logueado y desde pantalla listado afiliados
		$_SESSION['id_appointment_edit'] = $id_appointment;
		$appointment = $appointmentModel->getFromId($id_appointment);  
	}
} else{
	$id_affiliate = $_SESSION['id_affiliate']; // usuario logueado
	$id_appointment = null;
	if (isset($_REQUEST['id_appointment']) && $_REQUEST['id_appointment'] != null){
		$id_appointment = $_SESSION['id_appointment_edit'] = $_REQUEST['id_appointment']; 
		$appointment = $appointmentModel->getFromIdAffiliateAndAppointment($id_affiliate, $id_appointment);
	}  
}

// var_dump($appointment);

if ($appointment != null) {
    extract($appointment);
    $start_time = new \DateTime($time);
    $end_time = (new \DateTime($time))->add(new \DateInterval('PT' . 60 . 'M'));
    $timeFinal = $start_time->format('H:i') . ' a ' . $end_time->format('H:i');  
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Gestionar citas | Lexor</title>
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
</head>
<body>
<?php include('../header_sidebar.php');

?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Gestión de citas</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
          <li class="breadcrumb-item active">Gestión de citas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- FORM CITAS -->
    <!-- General Form Elements -->
    <section class="row">
        <div class="card-body pb-3">
            <button type="button" class="btn btn-success" id="btn-add" onclick="window.location.href='appointments.php'" >
                <i class="bi bi-plus-circle"></i> Agregar cita
            </button>
        </div>
    <div class="d-flex mt-3">
        
        
    
        <table id="dataTable" class="table table-borderless pt-4 mt-4">
            <thead>
                <tr class="text-key">
                    <th scope="col">Nombre y Apellido</th>
                    
                    <th scope="col">Urgencia</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha y hora</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-white">
                <?php 
                
                foreach($allNextAppointment as $appointment) { 
                
                    extract($appointment);?>
                <tr>
                    <td class="text-white"><?= htmlspecialchars($appointment['name_lastname']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($appointment['urgency']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($appointment['status']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($appointment['date'] . ' ' . $appointment['time']) ?></td>
                    <td>          
                    <button type="button" class="btn btn-primary btn-sm btn-edit" data-val="<?= $appointment['id'] ?>" onclick="window.location.href='appointments.php?id_appointment=<?= $appointment['id'] ?>'" ><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#verticalycentered" data-val="<?= $appointment['id'] ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>


    </div>
    </section>
    <!-- END FORM CITA -->

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function() {
    $('#date').change(function() {
        var selectedDate = $(this).val();
        
        $.ajax({
            url: 'getAvailableSlots.php', // Cambia esto al path real de tu archivo PHP
            type: 'POST',
            data: { date: selectedDate },
            success: function(response) {
                var slots = JSON.parse(response);
                var hourSelect = $('#hour');
                
                // Limpia las opciones anteriores
                hourSelect.empty();

                // Agrega las nuevas opciones
                if (slots.length > 0) {
                    $.each(slots, function(index, slot) {
                        hourSelect.append('<option value="' + slot + '">' + slot + '</option>');
                    });
                } else {
                    hourSelect.append('<option value="">No hay horarios disponibles</option>');
                }
            },
            error: function() {
                alert('Error al obtener los horarios disponibles.');
            }
        });
    });

    
    <?php 
      if ($appointment != null) {	  
    ?>    
    $('#date').trigger('change');
    
    setTimeout(function() {            
        $('#hour').append('<option value="<?= $timeFinal ?>" selected><?= $timeFinal ?> (horario seleccionado) </option>');
    }, 1000); 
	<?php } ?>

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.needs-validation');

    form.addEventListener('submit', function (event) {
        var isValid = true;
        var inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(function (input) {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, false);
    
});

        document.getElementById('formFile').addEventListener('change', function(event) {
            const inputFile = event.target;
            if (inputFile.files && inputFile.files.length > 0) {
                console.log('Archivo seleccionado:', inputFile.files[0].name);
                $('#alreadyAttachedFiles').css('display', 'none');

            }
        });
    </script>

</body>

</html>
