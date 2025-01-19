<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Utils;

use App\Models\Affiliate;

$userModel = new Affiliate();
$sidebarHide = '';

if (Utils::isAssessorLogged() || Utils::isAdminLogged()) {
  $action = null;
  $sidebarHide = 'class="toggle-sidebar"';
  if (isset($_REQUEST['action']))
    $action = $_REQUEST['action'];

  if (isset($_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7'])) {
    $id_affiliate = $_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7']; // Gestor logueado y desde pantalla listado afiliados
    $_SESSION['id_affiliate_edit'] = $id_affiliate;
    $affiliate = $userModel->get($id_affiliate);
    $scheduleData = $userModel->getScheduleData($id_affiliate);
    extract($affiliate);
  } else
    $name = $last_name = $document_number = $position =  $force = $province = $address = $phone = $email = $bank_account_owner =  $cbu = $bank_account_entity = $bank_account_type = $about_me = null;
} else if (Utils::isAffiliateLogged()) {
  $id_affiliate = $_SESSION['id_affiliate']; // me guardo el usuario logueado
  $affiliate = $userModel->get($id_affiliate);
  $scheduleData = $userModel->getScheduleData($id_affiliate);

  extract($affiliate);
} else
  $name = "";

$hideBecauseNew = false;
if (!(Utils::isAssessorLogged() || Utils::isAdminLogged() && $action == "new"))
  $hideBecauseNew = true;

$isViewing = false;
if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && $action == "view")
  $isViewing = true;

$isEditing = false;
if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && ($action == "edit" || $action == "new"))
  $isEditing = true;

if (Utils::isAffiliateLogged() && $_GET['result'] == "createSuccess") // Para que luego del createLight sigas editando
  $isEditing = true;

$document_types = $userModel->getAllDocumentTypes();

$specialties = $userModel->getAllSpecialties();
$provinces = $userModel->getAllProvinces();
$consultationTypes = $userModel->getConsultantTypes();




$account_types = $userModel->getAllAccountTypes();

/*
// Ejemplo de datos precargados (simulación de datos traídos de la base de datos)
$scheduleData = [
  'lunes' => ['start_time' => '09:00', 'end_time' => '18:00', 'closed' => false],
  'martes' => ['start_time' => '10:00', 'end_time' => '17:00', 'closed' => false],
  'miercoles' => ['start_time' => '08:00', 'end_time' => '15:00', 'closed' => false],
  'jueves' => ['start_time' => '09:00', 'end_time' => '18:00', 'closed' => true], // Ejemplo de día cerrado
  'viernes' => ['start_time' => '09:00', 'end_time' => '18:00', 'closed' => false],
  'sabado' => ['start_time' => '', 'end_time' => '', 'closed' => true],
  'domingo' => ['start_time' => '', 'end_time' => '', 'closed' => true],
];
*/
// Función para generar las opciones de horarios de 30 minutos
function generateTimeOptions($selectedTime = null)
{
  $options = '';
  for ($hour = 0; $hour < 24; $hour++) {
    foreach (['00', '30'] as $minute) {
      $time = sprintf('%02d:%s', $hour, $minute);
      $selected = ($time === $selectedTime) ? 'selected' : '';
      $options .= "<option value='$time' $selected>$time</option>";
    }
  }
  return $options;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Datos de abogado | Portal Lexor</title>
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


<style>
  .card-day {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
  }

  .closed-checkbox {
    font-size: 0.9em;
    font-weight: bold;
    color: #ff5a5f;
  }

  .time-selects label {
    font-weight: 600;
    margin-right: 10px;
  }

  .btn-submit {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    margin-top: 20px;
  }
</style>


<body <?= $sidebarHide ?>>

  <?php include('../header_sidebar.php'); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Datos del abogado</h1>
      <nav>
        <ol class="breadcrumb mt-4">
          <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
          <li class="breadcrumb-item active">Datos del abogado</li>

        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">

        <div class="col-xl-9">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <?php if ($hideBecauseNew) {  ?>
                  <li class="nav-item">
                    <button id="overview-tab" class="nav-link   <?php echo (Utils::isAffiliateLogged() && !$isEditing) || (Utils::isAssessorLogged() || Utils::isAdminLogged()) && $action == "view" ? "show active" : ""; ?>" data-bs-toggle="tab" data-bs-target="#profile-overview"> Ver Datos personales</button>
                  </li>
                <?php } ?>
                <li class="nav-item">
                  <button id="edit-tab" class="nav-link  <?php echo $isEditing ? "active show" : ""; ?>" data-bs-toggle="tab" data-bs-target="#profile-edit"> <?php echo ($isEditing) ? "Editar" : "Editar"  ?> datos personales</button>
                </li>
                <?php if ($hideBecauseNew) {  ?>
                  <li class="nav-item">
                    <button id="schedule-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-schedule">Horarios</button>
                  </li>
                  <li class="nav-item">
                    <button id="change-password-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar contraseña</button>
                  </li>
                <?php } ?>
              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade <?php echo (Utils::isAffiliateLogged() && !$isEditing) || (Utils::isAssessorLogged() || Utils::isAdminLogged()) && $action == "view" ? "show active" : ""; ?> profile-overview" id="profile-overview">
                  <h5 class="card-title"> Breve biografía y educación</h5>
                  <p class="small fst-italic"><?= $about_me ?></p>

                  <h5 class="card-title">Datos personales</h5>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nombre</div>
                    <div class="col-lg-9 col-md-8"><?= $name ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Apellido</div>
                    <div class="col-lg-9 col-md-8"><?= $last_name ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">DNI</div>
                    <div class="col-lg-9 col-md-8"><?= $document_number ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Especializacion</div>
                    <div class="col-lg-9 col-md-8"><?= $specialization ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Años experiencia</div>
                    <div class="col-lg-9 col-md-8"><?= $experience ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Provincia</div>
                    <div class="col-lg-9 col-md-8"><?= $province ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Dirección</div>
                    <div class="col-lg-9 col-md-8"><?= $address ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Teléfono</div>
                    <div class="col-lg-9 col-md-8"><?= $phone ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $email ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Titulo</div>
                    <div class="col-lg-9 col-md-8"><?php
                                                    if (isset($url_file_degree))
                                                      echo "<a href='uploads/$url_file_degree' target='_blank' > $url_file_degree </a>";
                                                    ?></div>
                  </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3 <?php echo $isEditing ? "show active" : ""; ?> " id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form action="process_form.php" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
                    <?php if ($hideBecauseNew) { ?>
                      <input type="hidden" name="update" value="true">
                    <?php } ?>
                    <?php
                    if (isset($_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7'])) {
                    ?>
                      <input type="hidden" name="id_affiliate" value="<?= $id_affiliate ?>">
                    <?php } ?>

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto de perfil</label>
                      <div class="col-md-8 col-lg-9">
                        <?php
                        if (isset($url_file_image) && $url_file_image != null)
                          $url_file_image = 'uploads/' . $url_file_image;
                        else
                          $url_file_image = "../assets/img/avatar.png";

                        ?>
                        <img src="<?= $url_file_image ?>" alt="Profile" id="profilePreview" style="max-width: 150px;">
                        <div class="pt-2">
                          <input type="file" name="profile_image" id="profileImage" accept="image/*" class="form-control mb-2" onchange="previewImage(event)">
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="name" class="col-md-4 col-lg-3 col-form-label">Nombre
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="<?= $name ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Apellido
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="last_name" type="text" class="form-control" id="fullName" value="<?= $last_name ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Sexo
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <select id="account-type" name="gender" class="form-select">
                          <option value="M">Hombre</option>
                          <option value="F">Mujer</option>
                          <option value="O">Otro</option>

                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Tipo de documento
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <select id="id_document_type" name="id_document_type" class="form-select">
                          <?php
                          foreach ($document_types as $registry) {
                            if (isset($id_document_type) && $registry['id'] == $id_document_type)
                              $selected = "selected";
                            else
                              $selected = "";
                          ?>
                            <option value="<?= $registry['id'] ?>" <?= $selected ?>><?= $registry['document_type'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="document_number" class="col-md-4 col-lg-3 col-form-label">Número de documento
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="document_number" type="number" class="form-control" id="fullName" value="<?= $document_number ?>" required max="99999999">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">Breve biografía y educación</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about_me" class="form-control" id="about" style="height: 100px"><?= $about_me ?></textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Specialization" class="col-md-4 col-lg-3 col-form-label">Especialidad
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <select id="id_specialization" name="id_specialization" class="form-select">
                          <?php
                          foreach ($specialties as $registry) {
                            if (isset($id_specialization) && $registry['id'] == $id_specialization)
                              $selected = "selected";
                            else
                              $selected = "";
                          ?>
                            <option value="<?= $registry['id'] ?>" <?= $selected ?>><?= $registry['description'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="begin_year" class="col-md-4 col-lg-3 col-form-label">Año de inicio
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="begin_year" type="number" class="form-control" id="fullName" value="<?= $begin_year ?>" required max="2024" min="1924">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Tipo de consultor
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <select id="id_consultation_type" name="id_consultation_type" class="form-select">
                          <?php
                          foreach ($consultationTypes as $registry) {
                            if (isset($id_consultation_type) && $registry['id'] == $id_consultation_type)
                              $selected = "selected";
                            else
                              $selected = "";
                          ?>
                            <option value="<?= $registry['id'] ?>" <?= $selected ?>><?= $registry['description'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Province" class="col-md-4 col-lg-3 col-form-label">Provincia
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <select id="id_province" name="id_province" class="form-select">
                          <?php
                          foreach ($provinces as $registry) {
                            if (isset($id_province) && $registry['id'] == $id_province)
                              $selected = "selected";
                            else
                              $selected = "";
                          ?>
                            <option value="<?= $registry['id'] ?>" <?= $selected ?>><?= $registry['province'] ?></option>
                          <?php } ?>
                        </select>

                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Address" class="col-md-4 col-lg-3 col-form-label">Dirección
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Address" value="<?= $address ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Teléfono
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="phone" type="text" class="form-control" id="Phone" value="<?= $phone ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?= $email ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Foto del Título
                        <span class="text-danger">*</span>
                      </label>

                      <div class="col-md-8 col-lg-9">
                        <input type="file" name="general_file" id="generalFile" class="form-control mb-2" onchange="previewFileName(event)">
                        <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                      </div>
                      <div id="filePreview">
                        <?php
                        if (isset($url_file_degree))
                          echo "<a href='uploads/$url_file_degree' target='_blank' > $url_file_degree </a>";
                        ?>
                      </div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                      </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">

                  <!-- Settings Form -->
                  <form>
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" checked>
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" checked>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Marketing and promo offers
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End settings Form -->

                </div>

                <!--Schedule / Horarios Pane -->
                <div class="tab-pane fade pt-3" id="profile-schedule">



                  <div class="container mt-4">
                    <h2 class="mb-4 text-center">Editar Horarios</h2>
                    <form action="process_form.php" method="post">
                      <input type="hidden" name="actionSchedule" value="update">
                      <?php
                      if (isset($_GET['xIZZvbK2khQytHRK5h43HnuRh1aip7']) || (isset($id_affiliate) && $id_affiliate != null)) {
                      ?>
                        <input type="hidden" name="id_affiliate" value="<?= $id_affiliate ?>">
                      <?php } ?>

                      <?php
                      foreach ($scheduleData as $day => $data):
                        $dayName = ucfirst($day);
                        $isClosed = $data['closed'];
                        $startTime = $data['start_time'];
                        $endTime = $data['end_time'];
                      ?>
                        <div class="card card-day">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <h5 class="card-title"><?= $dayName ?></h5>
                              <!-- Checkbox de cerrado -->
                              <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="closed_<?= $day ?>"
                                  name="closed[<?= $day ?>]" <?= $isClosed ? 'checked' : '' ?>
                                  onchange="toggleTimeSelects('<?= $day ?>')">
                                <label class="form-check-label closed-checkbox" for="closed_<?= $day ?>">Cerrado</label>
                              </div>
                            </div>
                            <!-- Selects de horario -->
                            <div id="time-selects-<?= $day ?>" class="time-selects row g-2" style="display: <?= $isClosed ? 'none' : 'flex' ?>;">
                              <div class="col">
                                <label>Desde:</label>
                                <select name="start_time[<?= $day ?>]" class="form-select">
                                  <?= generateTimeOptions($startTime); ?>
                                </select>
                              </div>
                              <div class="col">
                                <label>Hasta:</label>
                                <select name="end_time[<?= $day ?>]" class="form-select">
                                  <?= generateTimeOptions($endTime); ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                      <button type="submit" class="btn btn-submit w-100">Guardar Cambios</button>
                    </form>
                  </div>







                </div>
                <!-- END Schedule / Horarios Pane -->

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="process_form.php" method="post" onsubmit="return validateFormPassword()">
                    <input type="hidden" name="passAction" value="update">

                    <!-- 
                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña actual</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="currentPassword" type="password" class="form-control" id="currentPassword" >
                      </div>
                    </div>
                    -->

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nueva contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword">
                        <span id="newPasswordError" class="text-danger"></span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Repetir nueva contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewPassword" type="password" class="form-control" id="renewPassword">
                        <span id="renewPasswordError" class="text-danger"></span>

                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>

        <div class="col-xl-3">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="<?= $url_file_image ?>" alt="Profile" class="rounded-circle">
              <h2 class="text-key"><?= $name ?></h2>
              <h3 class="text-key"><?= $last_name ?></h3>
            </div>
          </div>

        </div><!-- ends col-xl-3 -->

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


  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function() {
        const output = document.getElementById('profilePreview');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }


    function previewFileName(event) {
      const fileName = event.target.files[0].name;
      const filePreview = document.getElementById('filePreview');
      filePreview.innerHTML = `<p>Archivo seleccionado: ${fileName}</p>`;
    }
  </script>

  <script>
    function validateForm() {
      let isValid = true;

      // Clear previous errors
      document.getElementById('nameError').textContent = '';
      document.getElementById('lastNameError').textContent = '';
      document.getElementById('emailError').textContent = '';
      document.getElementById('genderError').textContent = '';
      document.getElementById('birthDateError').textContent = '';
      document.getElementById('documentTypeError').textContent = '';
      document.getElementById('documentNumberError').textContent = '';
      document.getElementById('aboutMeError').textContent = '';
      document.getElementById('positionError').textContent = '';
      document.getElementById('forceError').textContent = '';
      document.getElementById('provinceError').textContent = '';
      document.getElementById('addressError').textContent = '';
      document.getElementById('phoneError').textContent = '';
      document.getElementById('BANombre').textContent = '';
      document.getElementById('BACvu').textContent = '';
      document.getElementById('BABanco').textContent = '';
      document.getElementById('BACuentaT').textContent = '';

      // Name validation
      const name = document.getElementsByName('name')[0].value;
      if (name.trim() === '') {
        document.getElementById('nameError').textContent = 'Name is required.';
        isValid = false;
      }

      // Last Name validation
      const lastName = document.getElementsByName('last_name')[0].value;
      if (lastName.trim() === '') {
        document.getElementById('lastNameError').textContent = 'Last Name is required.';
        isValid = false;
      }

      // Email validation
      const email = document.getElementsByName('email')[0].value;
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Invalid email format.';
        isValid = false;
      }

      // Gender validation
      const gender = document.getElementById('gender').value;
      if (gender === '') {
        document.getElementById('genderError').textContent = 'Gender is required.';
        isValid = false;
      }

      // Birth Date validation
      const birthDate = document.getElementsByName('birth_date')[0].value;
      if (birthDate === '') {
        document.getElementById('birthDateError').textContent = 'Birth Date is required.';
        isValid = false;
      }

      // Document Type validation
      const documentType = document.getElementById('account-type').value;
      if (documentType === '') {
        document.getElementById('documentTypeError').textContent = 'Document Type is required.';
        isValid = false;
      }

      // Document Number validation
      const documentNumber = document.getElementsByName('document_number')[0].value;
      if (documentNumber.trim() === '') {
        document.getElementById('documentNumberError').textContent = 'Document Number is required.';
        isValid = false;
      }

      // About Me validation
      const aboutMe = document.getElementsByName('about_me')[0].value;
      // No specific validation for About Me field

      // Position validation
      const position = document.getElementsByName('position')[0].value;
      if (position.trim() === '') {
        document.getElementById('positionError').textContent = 'Position is required.';
        isValid = false;
      }

      // Force validation
      const force = document.getElementById('id_force').value;
      if (force === '') {
        document.getElementById('forceError').textContent = 'Force is required.';
        isValid = false;
      }

      // Province validation
      const province = document.getElementById('id_province').value;
      if (province === '') {
        document.getElementById('provinceError').textContent = 'Province is required.';
        isValid = false;
      }

      // Address validation
      const address = document.getElementsByName('address')[0].value;
      if (address.trim() === '') {
        document.getElementById('addressError').textContent = 'Address is required.';
        isValid = false;
      }

      // Phone validation
      const phone = document.getElementsByName('phone')[0].value;
      if (phone.trim() === '') {
        document.getElementById('phoneError').textContent = 'Phone is required.';
        isValid = false;
      }

      const bANombre = document.getElementsByName('BANombre')[0].value;
      if (bANombre.trim() === '') {
        document.getElementById('BANombreError').textContent = 'BANombre is required.';
        isValid = false;
      }

      const bACvu = document.getElementsByName('BACvu')[0].value;
      if (bACvu.trim() === '') {
        document.getElementById('BACvuError').textContent = 'BACvu is required.';
        isValid = false;
      }

      const bABanco = document.getElementsByName('BABanco')[0].value;
      if (bABanco.trim() === '') {
        document.getElementById('BABancoError').textContent = 'BABanco is required.';
        isValid = false;
      }

      const bACuentaT = document.getElementsByName('BACuentaT')[0].value;
      if (bCuentaT.trim() === '') {
        document.getElementById('BACuentaTError').textContent = 'BACuentaT is required.';
        isValid = false;
      }

      return isValid;
    }

    function validateFormPassword() {
      let isValid = true;

      document.getElementById('newPasswordError').textContent = '';
      document.getElementById('renewPasswordError').textContent = '';

      const newPassword = document.getElementById('newPassword').value;
      if (newPassword === '') {
        document.getElementById('newPasswordError').textContent = 'Nueva contraseña es un campo requerido.';
        isValid = false;
      }

      const renewPassword = document.getElementById('renewPassword').value;
      if (renewPassword === '') {
        document.getElementById('renewPasswordError').textContent = 'Repetir nueva contraseña es un campo requerido.';
        isValid = false;
      }

      if (newPassword != renewPassword) {
        document.getElementById('newPasswordError').textContent = 'Las contraseñas no coinciden.';
        isValid = false;
      }

      return isValid;

      document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-link');
        let activeFound = false;

        tabs.forEach(tab => {
          if (tab.classList.contains('active')) {
            activeFound = true;
          }
        });

        if (!activeFound) {
          document.getElementById('overview-tab').classList.add('active', 'show');
          document.getElementById('profile-overview').classList.add('active', 'show');
        }
      });
  </script>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleTimeSelects(day) {
      const isClosed = document.getElementById('closed_' + day).checked;
      const timeSelects = document.getElementById('time-selects-' + day);
      timeSelects.style.display = isClosed ? 'none' : 'flex';
    }
  </script>



</body>

</html>