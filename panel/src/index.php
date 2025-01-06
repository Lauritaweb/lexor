<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\User;

// Crear un nuevo usuario
$user = new User();
$user->create('John Doe', 'john@example.com');

// Obtener todos los usuarios
$users = $user->getAll();

foreach ($users as $user) {
    echo 'ID: ' . $user['id'] . ' Name: ' . $user['name'] . ' Email: ' . $user['email'] . PHP_EOL;
}