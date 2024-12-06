<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\User;

// Crear un nuevo usuario
$user = new User();
// $user->create('John Doe', 'john@example.com');

// Obtener todos los usuarios
$users = $user->getAll();

foreach ($users as $user) {
    echo 'ID: ' . $user['id'] . ' Name: ' . $user['name'] . ' Email: ' . $user['email'] . PHP_EOL;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <form id="userForm"  action="process_form.php" method="post" onsubmit="return validateForm()">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" maxlength="50" required>
            <span id="nameError" class="error"></span>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" maxlength="50" required>
            <span id="lastNameError" class="error"></span>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" maxlength="50" required>
            <span id="emailError" class="error"></span>
        </div>
        <div>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select...</option>
                <option value="M">Hombre</option>
                <option value="F">Mujer</option>
                <option value="O">Other</option>
            </select>
            <span id="genderError" class="error"></span>
        </div>
        <div>
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" required>
            <span id="birthDateError" class="error"></span>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>

    <script>
        function validateForm() {
            let isValid = true;

            // Clear previous errors
            document.getElementById('nameError').textContent = '';
            document.getElementById('lastNameError').textContent = '';
            document.getElementById('emailError').textContent = '';
            document.getElementById('genderError').textContent = '';
            document.getElementById('birthDateError').textContent = '';

            // Name validation
            const name = document.getElementById('name').value;
            if (name.trim() === '') {
                document.getElementById('nameError').textContent = 'Name is required.';
                isValid = false;
            }

            // Last Name validation
            const lastName = document.getElementById('last_name').value;
            if (lastName.trim() === '') {
                document.getElementById('lastNameError').textContent = 'Last Name is required.';
                isValid = false;
            }

            // Email validation
            const email = document.getElementById('email').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('emailError').textContent = 'Invalid email format.';
                isValid = false;
            }

            // Gender validation
            const gender = document.getElementById('gender').value;
            if (gender === '') {
                document.getElementById('genderError').textContent = 'Gender is required.';
                isValid = false;
            }

            // Birth Date validation
            const birthDate = document.getElementById('birth_date').value;
            if (birthDate === '') {
                document.getElementById('birthDateError').textContent = 'Birth Date is required.';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>
