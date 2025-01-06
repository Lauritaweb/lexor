<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Affiliate;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $affiliateModel = new Affiliate();
    $user = $affiliateModel->login($user, $pass);
    if ($user != null){
        session_start();
        $_SESSION['id_affiliate'] = $user['id'];
        header("Location: afiliados/user-profile.php");
    }else{
        session_start();
        session_destroy();
        header("Location: login.html?error=1"); 
    }        
}