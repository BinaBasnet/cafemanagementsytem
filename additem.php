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

$upload_dir = "uploads/foodimages/";

// Create menu table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(100),
    price DECIMAL(10, 2),
    image VARCHAR(255)
)";
$conn->query($createTable);

// Insert item logic
if (isset($_POST['add'])) {
    $item = $_POST['item'];
    $price = $_POST['price'];

    $image_name = $_FILES['food_images']['name'];
    $tmp_name = $_FILES['food_images']['tmp_name'];
    $image_path = $upload_dir . basename($image_name);
    move_uploaded_file($tmp_name, $image_path);

    $sql = "INSERT INTO menu (item, price, image) VALUES ('$item', '$price', '$image_path')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item updated successfully'); window.location.href='editmenu.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="addandupitem.css">
</head>

<body><div id="menu">
    <nav>
        <ul>
            <li><a href="adminhome.html">Home</a></li>
            <li><a href="editmenu.php">Menu</a></li>
            <li><a href="adminsignup.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>
     <!-- Insert Item Form -->
     <div class="form-container">
        <h3>Add New Item</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="item" placeholder="Item Name" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="file" name="food_images" required>
            <input type="submit" name="add" value="Add Item">
        </form>
    </div>

   
</body>
</html>