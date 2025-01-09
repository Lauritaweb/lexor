<?php
session_start();
require __DIR__ . '/panel/vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();

$id_province = $_GET['provinceId'];


$localities = $affiliateModelo->getLocalities($id_province);
header('Content-Type: application/json');
echo json_encode($localities);