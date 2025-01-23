<?php
namespace App\Models;
use App\Config\Database;
class Affiliate
{
    private $db;
    private $table = 'affiliates';
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
    FROM 	affiliates
    WHERE 	email 		 = ?   
    AND 	password 	 = (SELECT AES_ENCRYPT(?, ?))    
    AND 	active		in (1,2)";	
    
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

    if (isset($_SESSION['id_user']) == false) {	
        $this->logout();
        exit();
    }		
  }

 function getUserId(){
     @session_start();
    return $_SESSION['id_user'];
 }

 function logout(){
    session_start();
    session_destroy();
  //  $this->direcciona("admin/login.html");	

 }



/**
 * 
 * Insert 
 * 
 */


 public function createLight($name, $email, $password){    
    if ( !$this->emailExists($email) ) {
        $query = "INSERT INTO affiliates (        
            name, email
        ) VALUES (?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);              
         
        $stmt->bind_param(
            "ss",        
            $name, $email
        );        
    
        if ($stmt->execute()) {
            $lastId = $this->db->insert_id;
            $this->updatePassword($lastId, $password);
            
            $stmt->close();
            return $lastId;
        } else {
            $stmt->close();
            return false;
        }
    }else
        return false;

    

   
}

    public function createFull(
        $id_document_type, $id_specialization, $id_province,
        $name, $last_name, $document_number, $about_me, $position,
        $email, $phone, $address, $gender, $begin_year, $id_consultation_type,  $urlImageFile, $degreeFileName
    ) {        

        $query = "INSERT INTO affiliates (
            id_document_type, id_specialization, id_province, 
            name, last_name, document_number, about_me, position,
            email, phone, address, gender, begin_year, id_consultation_type, url_file_image, url_file_degree
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);              
     
        $stmt->bind_param(
            "iiisssssssssiiss",
            $id_document_type, $id_specialization, $id_province,
            $name, $last_name, $document_number, $about_me, $position,
            $email, $phone, $address, $gender, $begin_year, $id_consultation_type, $urlImageFile, $degreeFileName
        );        

        if ($result = $stmt->execute()) {
            $lastId = $this->db->insert_id;
            $stmt->close();
            return $lastId;
        } else {
            $stmt->close();
            return false;
        }       
    }  


    public function createSpecialization($id_affiliate, $id_specialization
    ) { 
        $query = "
        INSERT INTO affiliate_specilities_assigned (id_affiliate, id_speciality)
        VALUES(?, ?)
        ";

        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);  
        
      
        $stmt->bind_param(
            "ii",
            $id_affiliate, 
            $id_specialization
        );       

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }       
    }

    function deleteSpecialization($id_affiliate){
        $query = "DELETE FROM affiliate_specilities_assigned WHERE id_affiliate = $id_affiliate";
    
        $stmt = $this->db->prepare($query);
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);        
        
        $stmt->execute();
    }

    public function emailExists($email) {
        $query = "SELECT * FROM `affiliates` WHERE email = ?";
        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);
        
    
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
    
        $stmt->close();
        
        return $numRows > 0;
    }
    
    public function createScheduleDay(
        $id_affiliate, $day, $start_time, $end_time, $is_closed
    ) { 
        $query = "
        INSERT INTO affliate_schedule (id_affiliate, dia, hora_inicio, hora_fin, cerrado)
        VALUES(?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);  
        
        if($is_closed){
            $start_time = null;
            $end_time = null;
        }   
            
        $stmt->bind_param(
            "isssi",
            $id_affiliate, 
            $day, 
            $start_time,
            $end_time, 
            $is_closed
        );       

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }       
    }
    
    function deleteScheduleDate($id_affiliate){
        $query = "DELETE FROM affliate_schedule WHERE id_affiliate = $id_affiliate";
    
        $stmt = $this->db->prepare($query);
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);        
        
        $stmt->execute();
    }

    function getScheduleData($id_affiliate) {
        // Consulta para obtener los horarios de cada día de la semana
        $query = "SELECT dia, hora_inicio, hora_fin, cerrado FROM affliate_schedule where id_affiliate = $id_affiliate";
    
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Array inicializado con días de la semana
        $scheduleData = [
            'lunes' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'martes' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'miercoles' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'jueves' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'viernes' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'sabado' => ['start_time' => '', 'end_time' => '', 'closed' => false],
            'domingo' => ['start_time' => '', 'end_time' => '', 'closed' => false],
        ];
    
        // Iterar sobre los resultados y asignarlos a $scheduleData
        while ($row = $result->fetch_assoc()) {
            $day = strtolower($row['dia']); // Suponiendo que 'dia' es el nombre del día (ej., 'lunes')
            
            // Verificar si el día existe en el array para evitar errores
            if (array_key_exists($day, $scheduleData)) {
                // Si el valor no es nulo, formatearlo a 'HH:MM'
                $start_time = !empty($row['hora_inicio']) ? substr($row['hora_inicio'], 0, 5) : '';
                $end_time = !empty($row['hora_fin']) ? substr($row['hora_fin'], 0, 5) : '';
    
                $scheduleData[$day]['start_time'] = $start_time;
                $scheduleData[$day]['end_time'] = $end_time;
                $scheduleData[$day]['closed'] = (bool)$row['cerrado'];
            }
        }
    
    
        $stmt->close();
        return $scheduleData;
    }
    
   

/**
 * 
 * Update 
 * 
 */

    public function update(
        $id, $id_document_type, $id_force, $id_province,
        $name, $last_name, $document_number, $about_me, 
        $email, $phone, $address, $gender, $begin_year, $id_consultation_type,
        $urlImageFile, $degreeFileName
    ) {        
        $updateAffiliate = $this->updateAffiliate(
            $id, $id_document_type, $id_force, $id_province,
            $name, $last_name, $document_number, $about_me, 
            $email, $phone, $address, $gender, $begin_year, $id_consultation_type,
            $urlImageFile,$degreeFileName
        );

        return $updateAffiliate;
    }
    

    public function updateAffiliate(
        $id, $id_document_type, $id_specialization, $id_province,
        $name, $last_name, $document_number, $about_me, 
        $email, $phone, $address, $gender, $begin_year, $id_consultation_type,
        $urlImageFile,$degreeFileName
    ) {
        $query = "UPDATE affiliates SET 
            id_document_type = ?, id_specialization = ?, id_province = ?, 
            name = ?, last_name = ?, document_number = ?, about_me = ?, 
            email = ?, phone = ?, address = ?, gender = ?, begin_year = ?,
            id_consultation_type = ?, url_file_image = ?, url_file_degree = ?
            WHERE id = ?";
    
        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
    
        $stmt->bind_param(
            "iiissssssssiissi",
            $id_document_type, $id_specialization, $id_province,
            $name, $last_name, $document_number, $about_me,
            $email, $phone, $address, $gender, $begin_year, $id_consultation_type,
            $urlImageFile, $degreeFileName,
            $id
        );
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function updatePassword($id, $pass) {
        $query = "UPDATE affiliates         	
                    SET password = (SELECT AES_ENCRYPT(?,?)) 
                    WHERE id = ?";

        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
    
        $stmt->bind_param(
            "ssi",
            $pass, $this->encryption_key, $id
        );
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    public function updatePasswordUsingEmail($email, $pass) {
        $query = "UPDATE affiliates         	
                    SET password = (SELECT AES_ENCRYPT(?,?)) 
                    WHERE email = ?";

        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
    
        $stmt->bind_param(
            "sss",
            $pass, $this->encryption_key, $email
        );
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }    
    

    public function delete($id) {
        $query = "UPDATE affiliates 
                SET active = 0 
                WHERE id = ?";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);    

        $stmt->bind_param(
            "i",
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function activate($id) {
        $query = "UPDATE affiliates 
                SET active = 2 
                WHERE id = ?";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);    

        $stmt->bind_param(
            "i",
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function deactivate($id) {
        $query = "UPDATE affiliates 
                SET active = 1 
                WHERE id = ?";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);    

        $stmt->bind_param(
            "i",
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

/**
 * 
 * Get 
 * 
 */

    public function getAll()
    {
        $query = "SELECT 
        a.id,
        adt.document_type,
        GROUP_CONCAT(assigned_specialties.description SEPARATOR ', ') AS specialiation,
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
        a.active,
        a.datetime,       
        YEAR(NOW()) - a.begin_year as experience,
        a.begin_year,
		act.description as consultantType,
        a.url_file_degree
    FROM 
        affiliates a
    LEFT JOIN 
        affiliate_document_types adt ON a.id_document_type = adt.id

    LEFT JOIN 
        affiliate_provinces ap ON a.id_province = ap.id
    LEFT JOIN 
        affiliate_bank_account aba ON a.id = aba.id_affiliate
    LEFT JOIN 
        affiliate_bank_account_types abt ON aba.id_bank_account_type = abt.id
	LEFT JOIN 
        affiliate_consultant_type act ON act.id= a.id_consultation_type       
	LEFT JOIN 
				affiliate_specilities_assigned ON affiliate_specilities_assigned.id_affiliate = a.id
	LEFT JOIN 
				affiliate_specialties AS assigned_specialties ON affiliate_specilities_assigned.id_speciality = assigned_specialties.id 
	GROUP BY a.id
    ORDER BY last_name asc
        ";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $stmt->close();

        return $users;
    }

    public function get($id) {
        $query = "SELECT 
        a.id,
        adt.document_type,
        asp.description as specialization,
        ap.province,        
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
        asp.id as id_specialization,
        ap.id as id_province,
				localities.locality,
		
        YEAR(NOW()) - a.begin_year as experience,
        a.begin_year,
		act.description as consultantType,
        a.id_consultation_type,
        a.url_file_image,
        a.url_file_degree
        FROM 
            affiliates a
        LEFT JOIN 
            affiliate_document_types adt ON a.id_document_type = adt.id
        LEFT JOIN 
            affiliate_specialties asp ON a.id_specialization = asp.id
        LEFT JOIN 
            affiliate_provinces ap ON a.id_province = ap.id               
        LEFT JOIN 
            affiliate_consultant_type act ON act.id= a.id_consultation_type   
				LEFT JOIN 
							localities on localities.id = a.id_locality
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

        if ($result->num_rows > 0) 
            $affiliate = $result->fetch_assoc();
        else 
            $affiliate = null;
        

        $stmt->close();

        return $affiliate;
    }

    public function getSpecialties($id) {
        $query = "SELECT affiliate_specilities_assigned.id_affiliate,
                        affiliate_specialties.description
                        from affiliate_specilities_assigned
                        INNER JOIN affiliate_specialties on affiliate_specilities_assigned.id_speciality = affiliate_specialties.id
                        where affiliate_specilities_assigned.id_affiliate = ?";
        
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        $specialties = [];
        while ($row = $result->fetch_assoc()) {
            $specialties[] = $row;
        }
       
        

        $stmt->close();

        return $specialties;
    }


    function getCompletedPorcentage($id){
        $query = "SELECT 
                id,
                (
                    (CASE WHEN id_document_type IS NOT NULL THEN 1 ELSE 0 END) +                    
                    (CASE WHEN id_province IS NOT NULL THEN 1 ELSE 0 END) +
                    
                    (CASE WHEN name IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN last_name IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN document_number IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN about_me IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN position IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN email IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN password IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN phone IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN address IS NOT NULL THEN 1 ELSE 0 END) +
                    (CASE WHEN gender IS NOT NULL THEN 1 ELSE 0 END)                    
                    
                ) / 12.0 * 100 AS completion_percentage
                FROM 
                    affiliates
                WHERE 
                    id = ?";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);


        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
            $affiliate = $result->fetch_assoc();
        else 
            $affiliate = null;

        $stmt->close();

        return round($affiliate['completion_percentage']);
    }

    /**
     * 
     * Find Lawyers
     * 
     */

    public function findLawyer($tipo_abogado, $provincia, $localidad, $status = 2 ) {        
        $query = "SELECT 
                    affiliates.id, 
                    name,
                    last_name,                
                    affiliates.id_province,
                    id_locality,
                    url_file_image,       
                    GROUP_CONCAT(DISTINCT assigned_specialties.description SEPARATOR ', ') AS specialty,
                    affiliate_provinces.province,
                    localities.locality,
                    (
                        SELECT GROUP_CONCAT(
                            CASE 
                                WHEN cerrado = 1 THEN CONCAT(dia, ': Cerrado')
                                ELSE CONCAT(dia, ': ', TIME_FORMAT(hora_inicio, '%H:%i'), ' - ', TIME_FORMAT(hora_fin, '%H:%i'))
                            END
                            SEPARATOR '<br>'
                        )
                        FROM affliate_schedule
                        WHERE affliate_schedule.id_affiliate = affiliates.id
                    ) AS schedule
                FROM affiliates 
                LEFT JOIN affiliate_specilities_assigned ON affiliate_specilities_assigned.id_affiliate = affiliates.id
                LEFT JOIN affiliate_specialties AS assigned_specialties ON affiliate_specilities_assigned.id_speciality = assigned_specialties.id
                LEFT JOIN affiliate_provinces ON affiliate_provinces.id = affiliates.id_province
                LEFT JOIN localities ON localities.id = affiliates.id_locality
                
                WHERE active = $status
                
";                    

        $conditions = [];
        if ($tipo_abogado != 0) {
            $conditions[] = "affiliate_specilities_assigned.id_speciality = " . intval($tipo_abogado);
        }
        if ($provincia != 0)  {
            $conditions[] = "affiliates.id_province = " . intval($provincia);
        }
        if ($localidad != 0 && $localidad != '') {
            $conditions[] = "id_locality = " . intval($localidad);
        }

        if (count($conditions) > 0) {
            $query .= " AND " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY affiliates.id";       
/*
        echo $query;
        var_dump($tipo_abogado, $provincia, $localidad);
*/
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
       
        $stmt->close();
        return $users;
    }

    public function getLocalities($id_province) {        
        $query = "SELECT id, locality FROM localities WHERE id_province = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_province);
        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $localities = [];
        while ($row = $result->fetch_assoc()) {
            $localities[] = $row;
        }
      
        $stmt->close();
        return $localities;
    }

    public function getLawyers($active = 2){
        return $this->findLawyer(null, null, null, $active);
    }



/**
 * 
 * Payments
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
 * Generic Getters
 * 
 */


    public function getAllDocumentTypes(){
        return $this->getGenericTable("affiliate_document_types");        
    }

    public function getAllSpecialties(){
        return $this->getGenericTable("affiliate_specialties");        
    }

    public function getAllProvinces(){
        return $this->getGenericTable("affiliate_provinces");        
    }
    public function getAllAccountTypes(){
        return $this->getGenericTable("affiliate_bank_account_types");        
    }

    public function getConsultantTypes(){
        return $this->getGenericTable("affiliate_consultant_type");        
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
