<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);        
  
    if(isset($passAction) && $_POST['passAction'] == "update"){
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
    }else if (isset($_POST['actionSchedule'])){
        foreach ($_POST['start_time'] as $day => $start_time) {
            $end_time = $_POST['end_time'][$day];
            $is_closed = isset($_POST['closed'][$day]) ? 1 : 0;
            $affiliateModelo->createScheduleDay($id_affiliate, $day, $start_time, $end_time, $is_closed);
        }
        die;
    } 
    else if (empty($update) ){ 
       if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && !isset($id_affiliate)){
        
            
            $affiliateModelo->createFull($id_document_type, $id_specialization, $id_province, $name, $last_name, $document_number, $about_me, $position,$email, $phone, $address, 
                                        $gender, $begin_year, $id_consultation_type);

            $destino = "../asesor/users-profiles.php?result=success";
            header("Location: $destino");
            exit();
       }else if ((Utils::isAssessorLogged() || Utils::isAdminLogged()) && isset($id_affiliate)){
            $id_bank_account_type = $BACuentaT;
            $bank_account_owner = $BANombre;
            $cbu = $BACvu;
            $bank_account_entity = $BABanco;    
            $affiliateModelo->update( $id_affiliate, $id_document_type, $id_specialization, $id_province,
                                        $name, $last_name, $document_number, $about_me, $position,      
                                        $email, $phone, $address, $gender, $begin_year, $id_consultation_type);
            $destino = "../asesor/users-profiles.php?result=success";
           
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
    }else{
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
        if ($affiliateModelo->update( $id_affiliate, $id_document_type, $id_specialization, $id_province,
                $name, $last_name, $document_number, $about_me, 
                $email, $phone, $address, $gender, $begin_year, $id_consultation_type)) {
           // echo "User update successfully.";
        } else {
            // echo "Failed to update user.";
        }

    }
    
    $destino = " user-profile.php$urlComplementary";
   
   header("Location: $destino");
}else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if($_GET['action'] == "rm" ){
        $affiliateModelo->delete($_GET['id']);   
        header("Location: ../asesor/users-profiles.php");
    }else if($_GET['action'] == "ac" ){
        $affiliateModelo->activate($_GET['id']);   
        header("Location: ../asesor/users-profiles.php");
    }    
}