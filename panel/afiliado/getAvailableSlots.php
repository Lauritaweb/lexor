<?php 
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Appointments;

if (isset($_POST['date'])) {
    $date = $_POST['date'];
    $scheduler = new Appointments();
    $available_slots = $scheduler->getAvailableSlots($date);

    echo json_encode($available_slots);
} else {
    echo json_encode([]);
}