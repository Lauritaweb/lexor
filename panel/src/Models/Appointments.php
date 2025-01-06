<?php
namespace App\Models;

use App\Config\Database;

class Appointments
{
    private $db;
    private $table = 'appointments';

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function create($id_affiliate, $id_appointment_type, $id_urgency, $date, $time, $duration,$purpose, $filename, $name_lastname, $phone, $email) {
        $query = "INSERT INTO $this->table (id_affiliate, id_appointment_type, id_urgency, date, time, duration, purpose, filename, name_lastname, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);
        
        $time = substr($time, 0, 5);
        $stmt->bind_param("iiissssssss", $id_affiliate, $id_appointment_type, $id_urgency, $date, $time, $duration, $purpose, $filename, $name_lastname, $phone, $email);

        $result = false;
        if ($stmt->execute())
            $result = true;
        
        $stmt->close();
        return $result;        
    }

    public function update($id, $id_appointment_type, $id_urgency, $date, $time, $duration, $purpose, $filename, $name_lastname, $phone, $email) {
        $query = "UPDATE $this->table SET id_appointment_type = ?, id_urgency = ?, date = ?, time = ?, duration = ?, purpose = ?, name_lastname = ?, phone = ?, email = ?";
        
        // Añadir la parte de la consulta correspondiente a 'filename' solo si no es vacío o nulo
        if (!empty($filename)) {
            $query .= ", filename = ?";
        }
        
        $query .= " WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt === false) 
            die('Prepare failed: ' . $this->db->error);
        
        
        // Ajustar el tiempo
        $time = substr($time, 0, 5);
        
        // Vincular parámetros condicionalmente
        if (!empty($filename)) {
            $stmt->bind_param("iissssssssi", $id_appointment_type, $id_urgency, $date, $time, $duration, $purpose, $filename, $name_lastname, $phone, $email, $id);
        } else {
            $stmt->bind_param("iisssssssi", $id_appointment_type, $id_urgency, $date, $time, $duration, $purpose, $name_lastname, $phone, $email, $id);
        }
        
        $result = false;
        if ($stmt->execute()) {
            $result = true;
        }
        
        $stmt->close();
        return $result;
    }
    
    


    public function getNextAppointment($id_affiliate, $all = false){
        $query = "SELECT
        appointments.*,
        appointments_type.type,
        appointments_urgency.urgency,
        affiliates.`name`,
        affiliates.last_name,
        appointments_status.status
    FROM
        appointments
        LEFT OUTER JOIN appointments_type ON appointments_type.id = appointments.id_appointment_type
        INNER JOIN appointments_urgency ON appointments_urgency.id = appointments.id_urgency
        INNER JOIN affiliates ON affiliates.id = appointments.id_affiliate
        INNER JOIN appointments_status on appointments_status.id = appointments.id_status
        WHERE affiliates.id = ?
        and date > NOW()
        ORDER BY date 
        ";
        if (!$all)
            $query .= " limit 1 ";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("i", $id_affiliate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0){
            if($all){
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                $appointment = $users;
            }else
                $appointment = $result->fetch_assoc();
        }
            
        else 
            $appointment = null;
        
        $stmt->close();

        return $appointment;

    }

    public function getFromIdAffiliate($id_affiliate){
        $query = "SELECT
        appointments.*,
        appointments_type.type,
        appointments_urgency.urgency,
        affiliates.`name`,
        affiliates.last_name
    FROM
        $this->table
        INNER JOIN appointments_type ON appointments_type.id = appointments.id_appointment_type
        INNER JOIN appointments_urgency ON appointments_urgency.id = appointments.id_urgency
        INNER JOIN affiliates ON affiliates.id = appointments.id_affiliate
        WHERE affiliates.id = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("i", $id_affiliate);
        $stmt->execute();
        $result = $stmt->get_result();

        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }

        $stmt->close();

        return $appointments;
    }

    // Para usar desdse el perfil del tipo afiliado para recibir como parametro el id_affiliate de sesion y evitar que un afiliado veo los datos de otro afiliado
    public function getFromIdAffiliateAndAppointment($id_affiliate, $id_appointment){
        $query = "SELECT
        appointments.*,
        appointments_type.type,
        appointments_urgency.urgency,
        affiliates.`name`,
        affiliates.last_name,
        appointments.email,
        appointments.phone,
        appointments_status.status
    FROM
        $this->table
        LEFT OUTER JOIN appointments_type ON appointments_type.id = appointments.id_appointment_type
        INNER JOIN appointments_urgency ON appointments_urgency.id = appointments.id_urgency
        INNER JOIN appointments_status on appointments_status.id = appointments.id_status
        INNER JOIN affiliates ON affiliates.id = appointments.id_affiliate
        WHERE affiliates.id = ?
        and appointments.id = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("ii", $id_affiliate, $id_appointment);
        $stmt->execute();
        $result = $stmt->get_result();        

        if ($result->num_rows > 0) 
            $appointment = $result->fetch_assoc();
         else 
            $appointment = null;        

        $stmt->close();
        return $appointment;
    }
   

    public function getFromId($id_appointment){
        $query = "SELECT
        appointments.*,
        appointments_type.type,
        appointments_urgency.urgency,
        affiliates.`name`,
        affiliates.last_name,
        appointments.email,
        appointments.phone,
        appointments_status.status
    FROM
        $this->table
        INNER JOIN appointments_type ON appointments_type.id = appointments.id_appointment_type
        INNER JOIN appointments_urgency ON appointments_urgency.id = appointments.id_urgency
        INNER JOIN appointments_status on appointments_status.id = appointments.id_status
        INNER JOIN affiliates ON affiliates.id = appointments.id_affiliate
        WHERE appointments.id = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("i", $id_appointment);
        $stmt->execute();
        $result = $stmt->get_result();        

        if ($result->num_rows > 0) 
            $appointment = $result->fetch_assoc();
         else 
            $appointment = null;        

        $stmt->close();
        return $appointment;
    }


    public function getAll($future = false){
        $queryFuture = "";
        if ($future)
          $queryFuture =  " WHERE date > NOW()";


        $query = "SELECT
        appointments.*,
        appointments_type.type,
        appointments_urgency.urgency,
        appointments_urgency.html_style,
        affiliates.`name`,
        affiliates.last_name,
        appointments.email,
        appointments.phone,
        appointments_status.status
    FROM
        $this->table
        INNER JOIN appointments_type ON appointments_type.id = appointments.id_appointment_type
        INNER JOIN appointments_urgency ON appointments_urgency.id = appointments.id_urgency
        INNER JOIN appointments_status on appointments_status.id = appointments.id_status
        INNER JOIN affiliates ON affiliates.id = appointments.id_affiliate
        $queryFuture
        order by date, time";
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


    public function delete($id_appointments){
        
        $query = "UPDATE appointments
        set id_status = 2
        WHERE appointments.id = ?
        ";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        
        $stmt->bind_param("i", $id_appointments);        
        $stmt->execute();
        $result = $stmt->get_result();       

        $stmt->close();

        return;    
    }

    
    public function confirm($id_appointments){
        
        $query = "UPDATE appointments
        set id_status = 1
        WHERE appointments.id = ?
        ";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }
        
        $stmt->bind_param("i", $id_appointments);        
        $stmt->execute();
        $result = $stmt->get_result();       

        $stmt->close();

        return;    
    }


    public function getAvailableSlots($date)
    {
        $start_time = new \DateTime('09:00:00');
        $end_time = new \DateTime('18:00:00');
        $interval = new \DateInterval('PT1H');

        $query = "SELECT time, duration FROM appointments WHERE date = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $booked_slots = [];
        while ($row = $result->fetch_assoc()) {
            $booked_slots[] = [
                'start_time' => new \DateTime($row['time']),
                'end_time' => (new \DateTime($row['time']))->add(new \DateInterval('PT' . $row['duration'] . 'M'))
            ];
        }

        $stmt->close();

        $available_slots = [];
        while ($start_time < $end_time) {
            $slot_end_time = clone $start_time;
            $slot_end_time->add($interval);

            $is_available = true;
            foreach ($booked_slots as $booked_slot) {
                if ($start_time < $booked_slot['end_time'] && $slot_end_time > $booked_slot['start_time']) {
                    $is_available = false;
                    break;
                }
            }

            if ($is_available) {
                $available_slots[] = $start_time->format('H:i') . ' a ' . $slot_end_time->format('H:i');
            }

            $start_time->add($interval);
        }

        return $available_slots;
    }


    public function getUrgencies(){
        return $this->getGenericTable("appointments_urgency");
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
