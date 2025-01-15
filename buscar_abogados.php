<?php
session_start();
require __DIR__ . '/panel/vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();

$requestPayload = file_get_contents('php://input');
$data = json_decode($requestPayload, true);

$tipo_abogado = $data['abogadoType'] ?? null;
$provincia = $data['provinceId'] ?? null;
$localidad = $data['localityId'] ?? null;

// var_dump($localidad);
$lawyers = $affiliateModelo->findLawyer($tipo_abogado,$provincia,$localidad);
header('Content-Type: application/json');
echo json_encode($lawyers);