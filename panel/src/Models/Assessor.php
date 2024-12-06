<?php

namespace App\Models;

use App\Config\Database;

class Assessor
{
    private $db;
    private $table = 'assessors';

    public function __construct(){
        $this->db = (new Database())->getConnection();
    }

/**
 * 
 * LOGIN
 * 
 */

 function login($user, $password) {
    //  -- AND 	password 	 	 = (SELECT AES_ENCRYPT(' . devolverDatoSeguro($password) . ', ' . devolverDatoSeguro(USER_ADMIN_ENCRYPTION_KEY) . '))	
    $query = "SELECT *
    FROM 	$this->table
    WHERE 	email 		 = '$user'   
    AND 	password 	 = '$password'
    AND 	active		 = 1";	
    
    $stmt = $this->db->prepare($query);

    if ($stmt === false) 
        die('Prepare failed: ' . $this->db->error);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();        
    $stmt->close();

    return $user;
}

function revisa_login(){
    @session_start();

    if (isset($_SESSION['id_assessor']) == false) {	
        $this->logout();
        exit();
    }		
  }

 function getUserId(){
    @session_start();
    return $_SESSION['id_assessor'];
 }

 function logout(){
    session_start();
    session_destroy();
  //  $this->direcciona("admin/login.html");	

 }




 public function getDebtors($debtor = true) {
    if ($debtor)
        $signo = "<";
    else
        $signo = ">=";


    $query = "select count(*) as cantidad
    from (
    SELECT
        affiliates_payments.id_affiliate,
        SUM(amount_requested) as total_amount_requested,
        sum(amount) as total_amount_paid
    FROM
        `affiliates_payments`	     
    GROUP BY 
        affiliates_payments.id_affiliate	 
    HAVING (total_amount_requested - total_amount_paid $signo 0)
     ) as calculo
     ";
    $stmt = $this->db->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . $this->db->error);
    }

    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0)
        $debtors = $result->fetch_assoc();
    else 
        $debtors = null;
    
    $stmt->close();
 
    return $debtors['cantidad'];
}



public function getPaymentsEverybody() {  
    $query = "SELECT
    affiliates.`name`,
    affiliates.last_name,
    affiliates_payments.*,
    affiliates_payments_results_type.result,
    affiliates_payments_results_type.charged
    FROM
        affiliates_payments
    INNER JOIN
        affiliates ON affiliates.id = affiliates_payments.id_affiliate
    INNER JOIN
        affiliates_payments_results_type ON affiliates_payments_results_type.id = affiliates_payments.id_result
    INNER JOIN
        (
            SELECT
                id_affiliate,
                MAX(payment_date) AS latest_payment_date
            FROM
                affiliates_payments
            GROUP BY
                id_affiliate
        ) AS latest_payments ON affiliates_payments.id_affiliate = latest_payments.id_affiliate
        AND affiliates_payments.payment_date = latest_payments.latest_payment_date
    ORDER BY
        affiliates_payments.payment_date DESC;
";
    $stmt = $this->db->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . $this->db->error);
    }
  
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }

    $stmt->close();

    return $payments;
}




/**
 * 
 * Insert 
 * 
 */

    public function get($id) {
        $query = "SELECT 
        a.id,
        adt.document_type,
        af.force,
        ap.province,
        aba.bank_account_owner,
        aba.cbu,
        aba.bank_account_entity,
        abt.bank_account_type,
        a.name,
        a.last_name,
        a.document_number,
        a.about_me,
        a.position,
        a.email,
        a.phone,
        a.address,
        a.gender,
        a.birth_date,
        a.datetime,
        adt.id as id_document_type,
        af.id as id_force,
        ap.id as id_province,
		abt.id as id_account_type
        
    FROM 
        affiliates a
    LEFT JOIN 
        affiliate_document_types adt ON a.id_document_type = adt.id
    LEFT JOIN 
        affiliate_forces af ON a.id_force = af.id
    LEFT JOIN 
        affiliate_provinces ap ON a.id_province = ap.id
    LEFT JOIN 
        affiliate_bank_account aba ON a.id = aba.id_affiliate
    LEFT JOIN 
        affiliate_bank_account_types abt ON aba.id_bank_account_type = abt.id
         WHERE a.id = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $appointment = $result->fetch_assoc();
        } else {
            $appointment = null;
        }

        $stmt->close();

        return $appointment;
    }



/**
 * 
 * 
 */


 public function getPayments($id, $summarise = false) {
    $querySummarise = "";
    if ($summarise)
        $querySummarise = ",
        SUM(amount_requested) as total_amount_requested,
        sum(amount) as total_amount_paid";
    

    $query = "SELECT
	affiliates_payments.*,
	affiliates_payments_results_type.result,
	affiliates_payments_results_type.charged
    $querySummarise
FROM
	`affiliates_payments`
	INNER JOIN affiliates_payments_results_type ON affiliates_payments_results_type.id = affiliates_payments.id_result
     WHERE affiliates_payments.id_affiliate = ?
     ORDER BY payment_date desc";
    $stmt = $this->db->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . $this->db->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
/*
    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
    } else {
        $appointment = null;
    }
  */ 
    $stmt->close();

    return $payments;
}




/**
 * 
 * 
 */


    public function getAllDocumentTypes(){
        return $this->getGenericTable("affiliate_document_types");        
    }

    public function getAllForces(){
        return $this->getGenericTable("affiliate_forces");        
    }

    public function getAllProvinces(){
        return $this->getGenericTable("affiliate_provinces");        
    }
    public function getAllAccountTypes(){
        return $this->getGenericTable("affiliate_bank_account_types");        
    }

    
    



    public function getGenericTable($table)
    {
        $query = "SELECT * FROM $table";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $response = [];
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }

        $stmt->close();

        return $response;
    }
}
