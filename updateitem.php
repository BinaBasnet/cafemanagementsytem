<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "myproject";
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$upload_dir = "uploads/foodimages/";
$item = $price = $image_path = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM menu WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $item = $row['item'];
        $price = $row['price'];
        $image_path = $row['image'];
    }
}
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $item = $_POST['item'];
    $price = $_POST['price'];
    $update_image = false;
    if (!empty($_FILES['food_images']['name'])) {
        $image_name = $_FILES['food_images']['name'];
        $tmp_name = $_FILES['food_images']['tmp_name'];
        $image_path = $upload_dir . basename($image_name);
        move_uploaded_file($tmp_name, $image_path);
        $update_image = true;
    }
    if ($update_image) {
        $sql = "UPDATE menu SET item='$item', price='$price', image='$image_path' WHERE id=$id";
    } else {
        $sql = "UPDATE menu SET item='$item', price='$price' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item updated successfully'); window.location.href='editmenu.php';</script>";
        exit();
    } else {
        echo "Error updating item: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
    <link rel="stylesheet" href="addandupitem.css">
</head>
<body>

<div id="menu">
    <nav>
        <ul>
            <li><a href="adminhome.html">Home</a></li>
            <li><a href="editmenu.php">Menu</a></li>
            <li><a href="adminsignup.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>    
            <li><a href="logout.php">Logout</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>

<div class="form-container">
    <h3>Update Item</h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" name="item" placeholder="Item Name" value="<?php echo htmlspecialchars($item); ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $price; ?>" required>
        
        <label>Upload New Image (optional):</label>
        <input type="file" name="food_images">
        <?php if (!empty($image_path)): ?>
            <p>Current Image:</p>
            <img src="<?php echo $image_path; ?>" alt="Food Image" style="width:200px;border-radius:10px;">
        <?php endif; ?>
        <input type="submit" name="update" value="Update Item">
    </form>
</div>
</body>
</html>
