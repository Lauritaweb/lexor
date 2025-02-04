<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();
/*
echo "<pre>";
var_dump($_REQUEST);
*/


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);        
 
    if(isset($passAction) && $_POST['passAction'] == "update"){ // Actualizacion de contraseña
        $urlComplementary = "";
        if (isset($_SESSION['id_affiliate']))
            $id_affiliate = $_SESSION['id_affiliate'];            
        else if (isset($_SESSION['id_affiliate_edit'])){
            $id_affiliate = $_SESSION['id_affiliate_edit'];
            $urlComplementary = "?xIZZvbK2khQytHRK5h43HnuRh1aip7=$id_affiliate";
        }
      
        $affiliateModelo->updatePassword($id_affiliate, $newPassword);
        $destino = " user-profile.php$urlComplementary";
      
        header("Location: $destino");
    }else if (isset($_POST['actionSchedule']) && $_POST['actionSchedule'] == "update" ){ // Actualizacion de horarios
        $affiliateModelo->deleteScheduleDate($id_affiliate);
        
        foreach ($_POST['start_time'] as $day => $start_time) {
            $end_time = $_POST['end_time'][$day];
            $is_closed = isset($_POST['closed'][$day]) ? 1 : 0;
            $affiliateModelo->createScheduleDay($id_affiliate, $day, $start_time, $end_time, $is_closed);
        }
        $destino = " user-profile.php$urlComplementary";      
        header("Location: $destino"); 
      
    } 
    else if (isset($_POST['actionSchedule'])){        
        foreach ($_POST['start_time'] as $day => $start_time) {
            $end_time = $_POST['end_time'][$day];
            $is_closed = isset($_POST['closed'][$day]) ? 1 : 0;
            $affiliateModelo->createScheduleDay($id_affiliate, $day, $start_time, $end_time, $is_closed);
        }        
    } 
    else if (empty($update) ){ 
       // ! CREATE

      
      // die;
       if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && !isset($id_affiliate)){ 
            $newFileName = uploadImage();
            
            $id_affiliate = $affiliateModelo->createFull($id_document_type, $id_specialization, $id_province, $name, $last_name, $document_number, $about_me, $position,$email, $phone, $address, 
                                        $gender, $begin_year, $id_consultation_type, $newFileName, $degreeFileName, $id_locality);

            // $affiliateModelo->deleteSpecialization($id_affiliate);
            die;
            foreach ($_POST['id_specialization'] as $id_specialization_actual) {                
                $affiliateModelo->createSpecialization($id_affiliate, $id_specialization_actual);
            }

            $destino = "../admin/index.php?result=success";
            header("Location: $destino");
            exit();
       }else if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && isset($id_affiliate)){ // Cuando el admin edita a un usuario
            // ! Update desde admin
            $id_bank_account_type = $BACuentaT;
            $bank_account_owner = $BANombre;
            $cbu = $BACvu;
            $bank_account_entity = $BABanco;    
            $affiliateModelo->update( $id_affiliate, $id_document_type, $id_specialization, $id_province,
                                        $name, $last_name, $document_number, $about_me, $position,      
                                        $email, $phone, $address, $gender, $begin_year, $id_consultation_type, $newFileName, $degreeFileName, $id_locality);
            die;
            $destino = "../admin/index.php?result=success";
           
            header("Location: $destino");
            exit();          
       }else{ // el asesor entro a Login y luego a Create Account
            $id_affiliate = $affiliateModelo->createLight($name, $email, $password); 
            if ($id_affiliate){
                session_start();
                $_SESSION['id_affiliate'] = $id_affiliate;
                $_SESSION['name'] = $name;
                $urlComplementary = "?result=createSuccess";
                Utils::mailCreate($email, $name);                
            }else
                die("Ya existe este usuario. No deberia llegar aca");
            
        }
    }else{ // ! UPDATE
        // var_dump($_POST);
       
        // Obtengo el id_affiliate de acuerdo si esta logueado un usuario o si es el asesor
        $urlComplementary = "";
        if (isset($_SESSION['id_affiliate']))
            $id_affiliate = $_SESSION['id_affiliate'];            
        else if (isset($_SESSION['id_affiliate_edit'])){
            $id_affiliate = $_SESSION['id_affiliate_edit'];
            $urlComplementary = "?xIZZvbK2khQytHRK5h43HnuRh1aip7=$id_affiliate";
        }
        
        if (Utils::isAffiliateLogged()){ // Solo evaluar la completitud de los datos en caso de que sea afiliado. No quiero obligar al asesor a completar todos los datos
            if (empty($id_document_type) || empty($id_specialization) || empty($id_province) ||  
                empty($name) || empty($last_name) || empty($document_number) || 
                empty($email) || empty($phone) || empty($address) || empty($gender) ) {
                // die('Por favor, complete todos los campos.');
                // ToDo: mejorar esta rta
            }
        }      

        $newFileName = uploadImage();


        // Subir archivo general
        if (isset($_FILES['general_file']) && $_FILES['general_file']['error'] === UPLOAD_ERR_OK) {
            $generalFile = $_FILES['general_file'];
            $degreeFileName = uniqid() . '-' . basename($generalFile['name']);
            $uploadDir = __DIR__ . '/uploads/';
            $generalFilePath = $uploadDir . $degreeFileName;

            if (move_uploaded_file($generalFile['tmp_name'], $generalFilePath)) {
                // echo "Archivo subido exitosamente: $degreeFileName";
            } else {
               // echo "Error al subir el archivo.";
            }
        }
        
        if ($affiliateModelo->update( $id_affiliate, $id_document_type, $id_specialization, $id_province,
                $name, $last_name, $document_number, $about_me, 
                $email, $phone, $address, $gender, $begin_year, $id_consultation_type, $newFileName, $degreeFileName,  $id_locality)) {
            
            $affiliateModelo->deleteSpecialization($id_affiliate);
            foreach ($_POST['id_specialization'] as $id_specialization_actual) {                
                $affiliateModelo->createSpecialization($id_affiliate, $id_specialization_actual);
            }
           
           // echo "User update successfully.";
        } else {
            // echo "Failed to update user.";
        }

    }
    
    $destino = " user-profile.php$urlComplementary";
   
   header("Location: $destino");
}else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET['action']) && $_GET['action'] == "rm" ){
        $affiliateModelo->delete($_GET['id']);   
        header("Location: ../admin/index.php");
    }else if(isset($_GET['action']) &&  $_GET['action'] == "ac" ){
        $affiliateModelo->activate($_GET['id']);   
        header("Location: ../admin/index.php");
    } else if(isset($_GET['action']) &&  $_GET['action'] == "de" ){
        $affiliateModelo->deactivate($_GET['id']);   
        header("Location: ../admin/index.php");
    }      
}



function uploadImage(){
    $newFileName = "";
    // Verificar si se subió una imagen
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
    // Ruta donde se guardarán las imágenes subidas
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Crear directorio si no existe
    }

    // Obtener información del archivo
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = $_FILES['profile_image']['name'];
    $fileSize = $_FILES['profile_image']['size'];
    $fileType = $_FILES['profile_image']['type'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowedExtensions)) {
        // Generar un nombre único para el archivo
        $newFileName = uniqid('profile_', true) . '.' . $fileExt;

        // Mover archivo a la carpeta de destino
        $destPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
           // echo "Imagen subida exitosamente: $newFileName";
        } else {
           // echo "Error al mover el archivo.";
        }
    } else {
        // echo "Extensión no permitida. Solo se permiten: " . implode(', ', $allowedExtensions);
    }
    } else {
        // echo "No se subió ninguna imagen o hubo un error.";
    }

    return $newFileName;

}