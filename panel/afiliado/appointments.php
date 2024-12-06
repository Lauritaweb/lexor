<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Appointments;
$appointmentModel = new Appointments();

use App\Models\Affiliate;
$affiliateModel = new Affiliate();
$affiliates = $affiliateModel->getAll();

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
  <title>Gestionar citas | Portal Respaldar Argentina</title>
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
<?php include('../header_sidebar.php');?>

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
      <form class="col-12" method="POST" action="processAppointment.php" enctype="multipart/form-data">
          <?php 
          if ($appointment != null)
              echo "<input type='hidden' name='id_appointment' value='$id_appointment'/>";
          ?>
          
          <?php if(Utils::isAssessorLogged() || Utils::isAdminLogged()){ ?>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Afiliado:
              <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
              <select class="form-select" aria-label="Default select example" required name="id_affiliate" id="id_affiliate">
                <?php foreach($affiliates as $affiliateActual){ ?>
                    <option value="<?= $affiliateActual['id'] ?>" <?php echo isset($id_affiliate) && $id_affiliate == $affiliateActual['id'] ? "selected":"" ?> </option> <?= $affiliateActual['last_name'] .', ' . $affiliateActual['name'] ?></option>
                <?php } ?>                
              </select>
            </div>
          </div>
        <?php } ?>

        <div class="row mb-3">
            <label for="name_lastname" class="col-sm-2 col-form-label">Nombre y Apellido 
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="name_lastname" id="name_lastname" value="<?php echo isset($name_lastname) ? $name_lastname : "" ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label for="phone" class="col-sm-2 col-form-label">Telefono 
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="phone" id="phone" value="<?php echo isset($phone) ? $phone : "" ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-sm-2 col-form-label">Email
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="email" id="email" value="<?php echo isset($email) ? $email : "" ?>">
            </div>
        </div>

          <div class="row mb-3">
            <label for="inputDate" class="col-sm-2 col-form-label">Fecha 
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
                <input type="date" class="form-control" required name="date" id="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($appointment) ? $date : "" ?>">
            </div>
        </div>

        <!-- ToDo: arreglarlo para que funcione dinamicamente -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Hora:
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-10">
                <select class="form-select" aria-label="Default select example" required name="hour" id="hour"  >
                    <!-- Opciones se llenarán dinámicamente -->
                </select>
            </div>
        </div>

        <div class="row mb-3">
          <label for="inputPassword" class="col-sm-2 col-form-label">Motivo de la cita
            <span class="text-danger">*</span>
          </label>
          <div class="col-sm-10">
            <textarea class="form-control" style="height: 100px" placeholder="Comenta brevemente el caso por el cual nos queres contactar" required  name="purpose" id="purpose"><?php echo isset($appointment) ? $purpose : "" ?></textarea>
          </div>
        </div>
        
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Indica la urgencia de tu cita</label>
          <div class="col-sm-10">
            <select class="form-select" aria-label="Default select example" name="urgency" id="urgency">
              <?php foreach($appointmentModel->getUrgencies() as $urgency){?>
                <option value="<?= $urgency['id'] ?>"  <?php echo  isset($id_urgency) && $urgency['id'] == $id_urgency ? "selected" : "";  ?> ><?= $urgency['urgency'] ?></option>
            <?php } ?>              
            </select>
          </div>
        </div>
<!--
        <div class="row mb-3">
          <label for="inputNumber" class="col-sm-2 col-form-label">Subir documentación</label>
          <div class="col-sm-10">
            <input class="form-control" type="file" id="formFile">
          </div>
        </div>

              -->         
        <div class="row mb-3">
        <label for="inputNumber" class="col-sm-2 col-form-label">Subir documentación</label>
            <div class="col-sm-10">
                <input class="form-control" type="file" name="file" id="formFile">
            </div>
        </div>
        <?php if ($appointment != null) { ?>
          <div class="row mb-3" id="alreadyAttachedFiles">
          <label for="inputNumber" class="col-sm-2 col-form-label">Documentacion</label>
              <a href="files/<?= $filename ?>" target="_blank"><?= $filename ?> </a>  
          </div>
        <?php } ?>               

        <div class="row mb-3">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Guardar</button>
        
            <button type="button" class="btn btn-danger" onclick="window.location.href = 'appointments_list.php'" >Cancelar</button>
          </div>
        </div>

      </form><!-- End General Form Elements -->
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
