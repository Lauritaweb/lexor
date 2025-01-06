<?php

namespace App\Utils;

define('MAIL_ADDRESS_CONTACT', 'no-reply@respaldarargentina.com '); 
define('MAIL_NAME_CONTACT', 'Respaldar');
define('MAILJET_API_KEY', 'c90ee4c8e3dd0f14a4e6338a970fa1db:8c8d0fac35d91213d2201d32daaa40f3');

define('JWT_IO_KEY', 'y8QJpX9hlykbSRJi00ttHeEfsXbMD9nWM6xXpRsi');

class Utils
{
    public static function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function formatDate($date){
        $dateObject = new \DateTime($date);
        return $dateObject->format('Y-m-d');
    }

    public static function formatDateUser($date){
        $dateObject = new \DateTime($date);
        return $dateObject->format('d/m/Y');
    }

    public static function isAdminLogged(){
        return (isset($_SESSION['id_admin']) && $_SESSION['id_admin'] > 0 );
    }

    public static function isAssessorLogged(){
        return (isset($_SESSION['id_assessor']) && $_SESSION['id_assessor'] > 0 );
    }

    public static function isAffiliateLogged(){
        return (isset($_SESSION['id_affiliate']) && $_SESSION['id_affiliate'] > 0 );
    }

    public static function validateLoggedAssessor(){
        if(!Utils::isAssessorLogged())
            header('../salir.php');
    }

    
    public static function validateLoggedAffiliate(){
        if(!Utils::isAffiliateLogged())
            header('../salir.php');
    }

    public static function mailCreate($email, $name){
        include('templateCreateAccount.php');
        Utils::mandarMail($email, "Bienvenido a Respaldar", $html, $name);
    }

    public static function mailForgotPassword($email){
        include('Token.php');
        $token = createJwtToken($email,JWT_IO_KEY);
        $tokenUrl = "token=$token";
        include('templateForgotPassword.php');

        Utils::mandarMail($email, "Recuperar contraseña", $html, $email);
    }
    
    public static function mandarMail($email, $subject, $message, $name = ""){                
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => MAIL_ADDRESS_CONTACT,
                        'Name' => MAIL_NAME_CONTACT
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $name
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $message
                ]
            ]
        ];
        
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json')
        );
        curl_setopt($ch, CURLOPT_USERPWD, MAILJET_API_KEY);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        
        $response = json_decode($server_output);    
        $status= $response->Messages[0]->Status == 'success';
        return $status;
    }




}
