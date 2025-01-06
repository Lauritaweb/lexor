<?php
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=div., initial-scale=1.0">
    <title>Document</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div style="width: 80%;margin: auto;background-color: #073E66; margin-top:0; padding:8px; text-align:center;">
        <img src="https://www.lexorabogados.com.ar/assets/img/logo.svg" alt="" style="width:200px; margin-top: 2px;">
    </div>
    <div style="width: 80%;margin: auto;padding: 6px;">
        <h1 style="color:#073E66; font-size:20px;font-family:Encode Sans, sans-serif;font-weight: 600">Estimado/a [Nombre del Afiliado],</h1>
        <p style="color:#073E66; font-family:Encode Sans, sans-serif; line-height: 22px;">
            Hemos recibido una solicitud para cambiar la contraseña de tu cuenta en el portal de Respaldar Argentina. 
        </p>
        <img src="https://www.lexorabogados.com.ar/assets/img/lines-reverse.svg" alt="" style="width: 200px;">
        <h2 style="color:#073E66; font-size:20px;font-family:Encode Sans, sans-serif; font-weight: 600;">Nueva Contraseña:</h2>
        <p style="color:#073E66; font-family:Encode Sans, sans-serif; line-height: 22px;">
            [Nueva Contraseña]
        </p>
        <a href="https://www.lexorabogados.com.ar/panel/update-password.php?'.$tokenUrl.'"> Enviar mail </a>
        <p style="color:#073E66; font-family:Encode Sans, sans-serif;font-style: italic; line-height: 20px; font-weight: 500;">
            <i>
                Un cordial saludo,
            </i>
        <br>
            Equipo de Respaldar
        <br>
        <a href="https://www.respaldarargentina.com" style="color:#073E66; font-family:Encode Sans, sans-serif;font-style: italic; line-height: 20px; font-weight: 500;">www.respaldarargentina.com</a>
        </p>
    </div>
</body>
</html>

';

