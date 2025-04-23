<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'], $_POST['num'], $_POST['username'], $_POST['password'])) {
       $fullname = trim($_POST['name']);
        $phone = trim($_POST['num']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
      
        $server = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "myproject";

        try {
            $conn = new mysqli($server, $db_username, $db_password);
            if ($conn->connect_error) throw new Exception("Connection failed: " . $conn->connect_error);

            $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
            $conn->select_db($dbname);

            $sql = "CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                FullName VARCHAR(100) NOT NULL,
                Phone_Number VARCHAR(15) NOT NULL,
                username VARCHAR(100) NOT NULL UNIQUE,
                passwords VARCHAR(255) NOT NULL
            )";
            if (!$conn->query($sql)) throw new Exception("Table creation failed: " . $conn->error);

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (FullName, Phone_Number, username, passwords) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $phone, $username, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                if ($conn->errno === 1062) {
                    echo "This username is already registered.";
                } else {
                    throw new Exception("Registration failed: " . $conn->error);
                }
            }
        } catch (Exception $e) {
            echo "An error occurred. Please try again later.";
            error_log($e->getMessage());
        } finally {
            if (isset($stmt)) $stmt->close();
            $conn->close();
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<h1>Sign up</h1>
<form method="POST" name="form" onsubmit="return validateForm(event)">
    <label for="name" class="mandatory">Full Name</label>
    <input type="text" id="name" name="name">
    <span id="err_name" class="error"></span>

    <label for="num" class="mandatory">Phone Number</label>
    <input type="text" id="num" name="num">
    <span id="err_num" class="error"></span>

    <label for="username" class="mandatory">Create Username</label>
    <input type="text" id="username" name="username">
    <span id="err_username" class="error"></span>

    <label for="password" class="mandatory">Create Password</label>
    <input type="password" id="password" name="password">
    <span id="err_password" class="error"></span>

    <button type="submit">Submit</button>
</form>

<script>
    function validateForm(event) {
        // Get form controls
        const form = document.forms['form'];
        const nameCtrl = form.name;
        const phoneCtrl = form.num;
        const usernameCtrl = form.username;
        const passwordCtrl = form.password;

        let error = 0; // Error counter

        // Validate Full Name
        const namePattern = /^[A-Z][a-z]*(?: [A-Z][a-z]*){1,2}$/; // first name, middle name, and last name
        const invalidCharPattern = /[^a-zA-Z\s]/; // Checks for any character that is not a letter or space
        const nameParts = nameCtrl.value.trim().split(' ');

        if (nameCtrl.value.trim() === '') {
            nameCtrl.style.border = '1px solid red';
            document.getElementById('err_name').innerText = 'Full name is required.';
            error++;
        } else if (invalidCharPattern.test(nameCtrl.value.trim()) || nameParts.length < 2) {
            nameCtrl.style.border = '1px solid red';
            document.getElementById('err_name').innerText = 'Invalid name';
            error++;
        } else if (!namePattern.test(nameCtrl.value.trim())) {
            nameCtrl.style.border = '1px solid red';
            document.getElementById('err_name').innerText = 'First name, middle name, and last name should start with a capital letter.';
            error++;
        } else {
            nameCtrl.style.border = '1px solid black';
            document.getElementById('err_name').innerText = '';
        }

        // Validate Phone Number
        const phonePattern = /^(97|98)[0-9]{8}$/;
        if (phoneCtrl.value.trim() === '') {
            phoneCtrl.style.border = '1px solid red';
            document.getElementById('err_num').innerText = 'Phone number is required.';
            error++;
        } else if (!phonePattern.test(phoneCtrl.value.trim())) {
            phoneCtrl.style.border = '1px solid red';
            document.getElementById('err_num').innerText = 'Invalid phone number';
            error++;
        } else {
            phoneCtrl.style.border = '1px solid black';
            document.getElementById('err_num').innerText = '';
        }

        // Validate Username
        if (usernameCtrl.value.trim() === '') {
            usernameCtrl.style.border = '1px solid red';
            document.getElementById('err_username').innerText = 'Username is required.';
            error++;
        } else {
            usernameCtrl.style.border = '1px solid black';
            document.getElementById('err_username').innerText = '';
        }

        // Validate Password
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{4,}$/; // Must include letter, number, and special character
        if (passwordCtrl.value.trim() === '' || !passwordPattern.test(passwordCtrl.value.trim())) {
            passwordCtrl.style.border = '1px solid red';
            document.getElementById('err_password').innerText = 
                'Password must contain at least one letter, one number, and one special character.';
            error++;
        } else {
            passwordCtrl.style.border = '1px solid black';
            document.getElementById('err_password').innerText = '';
        }

        // If errors exist, prevent form submission
        if (error > 0) {
            console.log('Form validation failed.');
            return false;
        }

        // If no errors, submit the form
        console.log('Form validated successfully.');
        alert('You are now registered.');
        return true;
    }
</script>

</body>
</html>
