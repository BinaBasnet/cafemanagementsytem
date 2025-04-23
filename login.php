<?php

session_start(); // Start session at the top of the script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $servername = "localhost";
        $db_user = "root";
        $db_password = "";
        $dbname = "myproject";

        try {
            // Create connection
            $conn = new mysqli($servername, $db_user, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            // First check in admin_users table
            $stmt = $conn->prepare("SELECT passwords FROM admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Admin found
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'admin';  // Store username in session
                    header("Location:adminhome.html"); // Redirect to admin dashboard
                    exit();
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                // Now check in regular_users table
                $stmt->close(); // Close the first statement
                $stmt = $conn->prepare("SELECT passwords FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Regular user found
                    $stmt->bind_result($hashed_password);
                    $stmt->fetch();

                    // Verify the password
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['username'] = $username;// Store username in session
                        $_SESSION['userid']=$userid;
                        $_SESSION['role'] = 'user'; // Set user role as regular user
                        header("Location:home.html"); // Redirect to user dashboard
                        exit();
                    } else {
                        echo "Invalid username or password.";
                    }
                } else {
                    echo "Invalid username or password.";
                }
            }

            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error for server-side review
            echo "An error occurred. Please try again.";
        }
    } else {
        echo "Please enter all the fields.";
    }
}
?>
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Login Form</h1>
    <form action="#" method="post" name="form" id="form" onsubmit="return validateForm(event)">
        <div class="container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="username">
            <span id="err_username" class="error"></span>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="password">
            <span id="err_password" class="error"></span>
            <div class="show-password">
                <input type="checkbox" id="showPassword" >
                <label for="showPassword">Show Password</label>
            </div>
            <br>
            <button class="submitbutton" type="submit" id="submit">Submit</button>
           <div class="container-box"> 
            <p>Don't have account?<a href="signup.php">Signup</a></p>
        </div>
        </div>
    </form>
    
    <script>
        // Get the password input field and the checkbox element
let passwords = document.getElementById('password');
let checkbox = document.getElementById('showPassword');

// Use addEventListener to listen for the 'click' event on the checkbox
checkbox.addEventListener('click', function() {
    // Check if the checkbox is checked (i.e., user wants to see the password)
    if (checkbox.checked) {
        passwords.type = "text";  // Change the password field's type to 'text' to show the password
    } else {
        passwords.type = "password";  // Change the password field's type back to 'password' to hide the password
    }
});

// Function to validate form
function validateForm(event) {
    let usernameCtrl = document.getElementById('username');
    let passwordCtrl = document.getElementById('password');
    let error = 0;  // Initialize the error variable to track validation failures

    // Check if the username is empty
    if (usernameCtrl.value.trim() == '') {
        usernameCtrl.style.border = '1px solid red';
        document.getElementById('err_username').innerText = "Username is required";
        error++;  // Increment the error counter
    } else {
        usernameCtrl.style.border = '1px solid black';
        document.getElementById('err_username').innerText = '';  // Clear error message
    }

    // Check if the password is empty
    if (passwordCtrl.value.trim() == '') {
        passwordCtrl.style.border = '1px solid red';
        document.getElementById('err_password').innerText = "Password is required";
        error++;  // Increment the error counter
    } else {
        passwordCtrl.style.border = '1px solid black';
        document.getElementById('err_password').innerText = '';  // Clear error message
    }

    // If there are any validation errors, prevent form submission
    if (error > 0) {
        return false;
    } else {
          
        return true;  // Allow form submission
    }
}

    </script> <!-- Move this here to make sure the DOM is ready -->
</body>
</html>
