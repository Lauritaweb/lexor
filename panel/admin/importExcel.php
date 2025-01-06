<?php
require '../vendor/autoload.php';
use App\Config\Database;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

$db = (new Database())->getConnection();
// $inputFileName = 'ren.xlsx';

// Cargar el archivo Excel
$spreadsheet = IOFactory::load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$firstIteration = true;

// Recorrer las filas del archivo Excel
foreach ($worksheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    $data = [];
    $cellIndex = 0;
    foreach ($cellIterator as $cell) {
        $cellIndex++;
        if ($cellIndex == 10) {            
            $data[] = (string) $cell->getValue(); // Asegurarse de que la celda en la posición 10 sea tratada como cadena
        }else 
        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {            
            $dateValue = Date::excelToDateTimeObject($cell->getValue())->format('Y-m-d'); // Convertir el valor a una fecha
            $data[] = $dateValue;
        } else {
            $data[] = $cell->getValue();
        }
    }

    if ($firstIteration == true){ // Para saltar los headers
        $firstIteration = false;
        continue;
    }
        
   
    $document_number = $data[4];
    $debit_date = $data[6];
    $id_result = trim((string)$data[10]);
    // $id_result = trim($id_result);
    $id_result = str_replace(' ', '', $id_result);
    $id_result = str_replace(chr(194), '', $id_result); // Elimina el carácter con código ASCII 194
    $id_result = str_replace(chr(160), '', $id_result); // Elimina el carácter con código ASCII 160
    $amount = $data[11];
    $amount_requested = $data[8];
   

   // echo "$debit_date |$id_result| $document_number \n";

    $stmt = $db->prepare("insert into affiliates_payments (id_affiliate, id_result, document_number, payment_date, amount,amount_requested)
    SELECT affiliates.id,
    ?,
    ?,
    ?,
    ?,
    ?
    FROM affiliates
    where affiliates.document_number = ?");
    
    if ($stmt === false) {
        // die('Prepare failed: ' . $db->error);
        header("Location: bank-reconciliation.php?success=false");
    }
 

    $stmt->bind_param("ssssss", $id_result, $document_number, $debit_date, $amount, $amount_requested, $document_number);

    if (!$stmt->execute()) {
        // echo "Error al insertar datos: " . $stmt->error;
        header("Location: bank-reconciliation.php?success=false");
    }
    
}

// Cerrar la conexión

$db->close();

// echo "Importación completa.";
header("Location: bank-reconciliation.php?success=true");

