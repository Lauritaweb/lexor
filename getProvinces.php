<?php
session_start();
require __DIR__ . '/panel/vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();

$provinces = $affiliateModelo->getAllProvinces();
header('Content-Type: application/json');
echo json_encode($provinces);