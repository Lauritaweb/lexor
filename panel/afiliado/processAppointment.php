<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Appointments;
$appointmentModel = new Appointments();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    if (!isset($id_affiliate) && !Utils::isAssessorLogged() && !Utils::isAdminLogged()  ) // Para asegurarme que solo el asesor pueda crear un appointment a un afiliado que no esta logueado
        $id_affiliate = $_SESSION['id_affiliate'];
    
    $filaname = manageFileUpload();
    if (!isset($type))
        $type = null;

    if (!isset($id_appointment))
        $id_appointment = $appointmentModel->create($id_affiliate, $type, $urgency, $date, $hour, 1, $purpose, $filaname, $name_lastname, $phone, $email);    // lo creo y me guardo la referencia al id
    else if (isset($id_appointment) ) // valido que haya id_appointment en sesion y q este seteado el campo update
         $appointmentModel->update($id_appointment, $type, $urgency, $date, $hour, 1, $purpose, $filaname, $name_lastname, $phone, $email);

    if (Utils::isAssessorLogged() || Utils::isAdminLogged())
        header("Location: ../asesor/dashboard-appointments.php");
    else if (Utils::isAffiliateLogged())
        header("Location: ../afiliado/index.php");
    
    
}else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if($_GET['action'] == "rm" )
        $appointmentModel->delete($_GET['id']);
    if($_GET['action'] == "confirm" )
        $appointmentModel->confirm($_GET['id']);
    
    header("Location: ../asesor/dashboard-appointments.php");

}


function manageFileUpload(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) { // Verificar si el archivo fue subido sin errores
        $uploadDir = __DIR__ . '/files/'; 
    
        $originalFileName = basename($_FILES['file']['name']);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    
        $date = new DateTime();
        $timestamp = $date->format('Ymd_His');
        // ToDo: agregar id_affiliate
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $fileExtension;
        $uploadFile = $uploadDir . $newFileName;       
        
        
        if (!is_dir($uploadDir)) 
            mkdir($uploadDir, 0777, true);
            
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))            
            $inputFileName = $uploadFile;        
        else{
            echo "Hubo un error al subir el archivo.";
            //header("Location: bank-reconciliation.php?success=false");
        }
    
        
    }else         
        return null; // "No se subió ningún archivo o hubo un error en la subida.";
        
    return $newFileName;
}   