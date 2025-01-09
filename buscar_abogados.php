<?php
session_start();
require __DIR__ . '/panel/vendor/autoload.php';
use App\Utils\Utils;

use App\Models\Affiliate;
$affiliateModelo = new Affiliate();

$tipo_abogado = $_POST['tipo_abogado'] ?? null;
$provincia = $_POST['provincia'] ?? null;
$localidad = $_POST['localidad'] ?? null;

var_dump($localidad);
$lawyers = $affiliateModelo->findLawyer($tipo_abogado,$provincia,$localidad);
header('Content-Type: application/json');
echo json_encode($lawyers);