<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) { // Verificar si el archivo fue subido sin errores
    $uploadDir = __DIR__ . '/files/'; 

    $originalFileName = basename($_FILES['file']['name']);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    $date = new DateTime();
    $timestamp = $date->format('Ymd_His');
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $fileExtension;
    $uploadFile = $uploadDir . $newFileName;
    
    
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
       // echo "El archivo ha sido subido exitosamente como $newFileName.";
        $inputFileName = $uploadFile;
        include('importExcel.php');
    } else{
        echo "Hubo un error al subir el archivo.";
        header("Location: bank-reconciliation.php?success=false");
    }

    
} else {
    echo "No se subió ningún archivo o hubo un error en la subida.";
    header("Location: bank-reconciliation.php?success=false");
}


?>
