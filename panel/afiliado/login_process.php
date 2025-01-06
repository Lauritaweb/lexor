<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Affiliate;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $affiliateModel = new Affiliate();
    $user = $affiliateModel->login($username, $password);
    if ($user != null){
        session_start();
        $_SESSION['id_affiliate'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['last_name'] = $user['last_name'];
        header("Location: index.php");
    }else{
        session_start();
        session_destroy();
        header("Location: ../login.html?error=1"); 
    }        
}