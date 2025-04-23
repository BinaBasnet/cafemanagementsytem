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

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // convert to int to prevent SQL injection
    $sql = "DELETE FROM menu WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item deleted successfully'); window.location.href='editmenu.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting item: " . $conn->error . "');</script>";
    }
}

// Fetch all menu items
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items</title>
    <style>
        body {
            margin: 0;
        }

        #menu ul {
            list-style: none;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.7);
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

        .add-button-container {
            text-align: left;
            margin-left: 60px;
            margin-top:10px;
        }

        .add-button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .add-button:hover {
            background-color: #555;
        }

        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
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
            padding-bottom: 10px;
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition */
            }

/* Hover Effect for Info Item */
.menu-card:hover {
    transform: scale(1.05); /* Slightly enlarge the item */
    box-shadow: 8px 4px 12px 6px rgba(78, 76, 76, 0.6); /* More pronounced shadow */
}
        

        .menu-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .menu-card p {
            margin: 10px 0;
        }

        .menu-card .btn-group {
            margin-top: 10px;
        }

        .menu-card a.button {
            display: inline-block;
            margin: 5px;
            padding: 6px 12px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .menu-card a.button:hover {
            background-color: #555;
        }
    </style>
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
        </ul>
    </nav>
</div>

<div class="add-button-container">
    <a href="additem.php">
        <button class="add-button">Add New Item</button>
    </a>
</div>

<div class="menu-container">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='menu-card'>";
            echo "<img src='" . $row["image"] . "' alt='Food Image'>";
            echo "<p><strong>Price:</strong> $" . number_format($row["price"], 2) . "</p>";
            echo "<p><strong>" . htmlspecialchars($row["item"]) . "</strong></p>";
            echo "<div class='btn-group'>";
            echo "<a class='button' href='updateitem.php?id=" . $row["id"] . "'>Update</a>";
            echo "<a class='button' href='editmenu.php?delete=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</a>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No items found</p>";
    }
    ?>
</div>

</body>
</html>
