<?php
namespace App\Models;
use App\Config\Database;
class Admin
{
    private $db;    
    private $encryption_key = "h4rdTod3c0d33ncrypt!0nKeyH4s";

    public function __construct(){
        $this->db = (new Database())->getConnection();
    }

/**
 * 
 * LOGIN
 * 
 */

 function login($user, $password) {    
    $query = "SELECT *
    FROM 	admins
    WHERE 	email 		 = ?   
    AND 	password 	 = (SELECT AES_ENCRYPT(?, ?))    
    AND 	active		= 1";	
    
    $stmt = $this->db->prepare($query);
        
    if ($stmt === false) 
        die('Prepare failed: ' . $this->db->error);              
 
    $stmt->bind_param(
        "sss",
        $user, $password, $this->encryption_key
    );        
    
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();        
    $stmt->close();

    return $user;
}

function revisa_login(){
    @session_start();

    if (isset($_SESSION['id_admin']) == false) {	
        $this->logout();
        exit();
    }		
  }

 function getUserId(){
    @session_start();
    return $_SESSION['id_admin'];
 }

 function logout(){
    session_start();
    session_destroy();
 }

}
