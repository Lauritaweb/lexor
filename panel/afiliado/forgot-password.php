<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Affiliate;
use App\Utils\Utils;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];  

    $affiliate = new Affiliate();
    $exists = $affiliate->emailExists($email);
    if ($exists)
        Utils::mailForgotPassword($email);        
        
    echo json_encode(['exists' => $exists]);
}
?>
