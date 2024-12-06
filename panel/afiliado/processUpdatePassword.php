<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Affiliate;
define('JWT_IO_KEY', 'y8QJpX9hlykbSRJi00ttHeEfsXbMD9nWM6xXpRsi');

include('../src/Utils/Token.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $username = decodeJwtToken($token, JWT_IO_KEY);
    $affiliateModel = new Affiliate();    
    $user = $affiliateModel->updatePasswordUsingEmail($username, $password);
    header("Location: ../updateSuccessful.html");    
}