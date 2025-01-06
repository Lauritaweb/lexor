<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Assessor;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $assessorModel = new Assessor();
    $user = $assessorModel->login($username, $password);
    if ($user != null){
        session_start();
        $_SESSION['id_assessor'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['last_name'] = $user['last_name'];
        header("Location: index.php");
    }else{
        session_start();
        session_destroy();
        header("Location: ../login-asesor.html?error=1"); 
    }        
}