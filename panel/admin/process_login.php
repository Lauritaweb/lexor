<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Admin;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $adminModel = new Admin();
    $user = $adminModel->login($username, $password);
    if ($user != null){
        session_start();
        $_SESSION['id_admin'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['last_name'] = $user['last_name'];
        header("Location: index.php");
    }else{
        session_start();
        session_destroy();
        header("Location: ../login-admin.html?error=1"); 
    }        
}