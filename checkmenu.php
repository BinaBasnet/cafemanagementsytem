<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "myproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run the query
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>body{
        margin:0;
    }
         #menu ul {
    list-style: none;
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.7); /* semi-transparent background */
    margin: 0;
    display: flex;
    justify-content: flex-end;
}

#menu ul li {
    margin: 0 15px;
}

#menu ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}
        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 40px;
            

        }

        .menu-card {
            width: 200px;
            margin: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }.menu-card:hover{
            transform: scale(1.05); /* Slightly enlarge the item */
    box-shadow: 8px 4px 12px 6px rgba(78, 76, 76, 0.6); /* More pronounced shadow */}

        .menu-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .menu-card p {
            margin: 10px 0;
        }

      
        </style>
</head>
<body>
<div id="menu">
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="checkmenu.php">Menu</a></li>
                <li><a href="signup.php">Signup</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    
<div class="menu-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='menu-card'>";
                echo "<img src='" . $row["image"] . "' alt='Food Image'>";
                echo "<p><strong>Price:</strong> $" . number_format($row["price"], 2) . "</p>";
                echo "<p><strong>" . htmlspecialchars($row["item"]) . "</strong></p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No items found</p>";
        }
        ?>
    </div>
</body>
</html>

