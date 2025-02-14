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
    <div style="width: 80%; margin: auto; background-color: #033333; padding: 8px; text-align: center;">
        <img src="https://www.lexorabogados.com.ar/assets/img/Lexor-logo.svg" alt="Lexor" style="width:200px; margin-top: 2px;">
    </div>
    <div style="width: 80%; margin: auto; padding: 6px;">
        <h1 style="color:#033333; font-size:20px; font-family: Encode Sans, sans-serif; font-weight: 600;">
            ¡Tu cuenta ha sido activada, '.$name.'!
        </h1>
        <p style="color:#033333; font-family: Encode Sans, sans-serif; line-height: 22px;">
            Nos complace informarte que hemos validado tu registro y tu cuenta en Lexor ya está activa. 
            Ahora puedes acceder a la plataforma utilizando los mismos datos con los que te registraste.
        </p>
        
        <img src="https://www.lexorabogados.com.ar/panel/assets/img/lines-reverse.svg" alt="" style="width: 200px;">
        
        <h2 style="color:#033333; font-size:20px; font-family: Encode Sans, sans-serif; font-weight: 600;">Inicia sesión en Lexor</h2>
        <p style="color:#033333; font-family: Encode Sans, sans-serif; line-height: 22px;">
            Para ingresar, usa el correo y la contraseña que estableciste durante el registro.
        </p>

        <p style="color:#033333; font-family: Encode Sans, sans-serif; line-height: 22px;">
            <strong>Usuario:</strong> '.$email.' <br>
            <strong>Contraseña:</strong> La que definiste al registrarte
        </p>

        <p style="color:#033333; font-family: Encode Sans, sans-serif; line-height: 22px;">
            <a href="https://lexorabogados.com.ar/panel/login.html" 
               style="display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #033333; text-decoration: none; font-weight: 600; border-radius: 5px;">
                Iniciar sesión
            </a>
        </p>

        <img src="https://www.lexorabogados.com.ar/panel/assets/img/lines-reverse.svg" alt="" style="width: 200px;">
        
        <h2 style="color:#033333; font-size:20px; font-family: Encode Sans, sans-serif; font-weight: 600;">¿Necesitas ayuda?</h2>
        <p style="color:#033333; font-family: Encode Sans, sans-serif; line-height: 22px;">
            Si tienes alguna consulta o necesitas asistencia, nuestro equipo está disponible para ayudarte.
        </p>
        
        <img src="https://www.lexorabogados.com.ar/panel/assets/img/shield.svg" alt="" style="width: 100px;">
        <p style="color:#033333; font-family: Encode Sans, sans-serif; font-style: italic; line-height: 20px; font-weight: 500;">
            <i>Un cordial saludo,</i>
            <br>
            Equipo de Lexor
            <br>
            <a href="https://www.lexorabogados.com.ar" style="color:#033333; font-family: Encode Sans, sans-serif; font-style: italic; line-height: 20px; font-weight: 500;">www.lexorabogados.com.ar</a>
        </p>
    </div>
</body>
</html>
';