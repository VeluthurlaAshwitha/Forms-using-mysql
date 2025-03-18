<?php
session_start(); // Start session for login tracking

// Include database connection
$servername = "localhost";
$username = "root";
$password = "ashwitha@vm123";
$database = "test_db"; 

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the 'users' table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

// Execute the query
$conn->query($sql);

// Check if the table already has data
$sql_check = "SELECT COUNT(*) as count FROM users";
$result_check = $conn->query($sql_check);

if ($result_check) {
    $row = $result_check->fetch_assoc();
    if ($row['count'] == 0) {
        $insert_sql = "INSERT INTO users (name, email, password) VALUES
        ('John Doe', 'john@example.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "'),
        ('Jane Smith', 'jane@example.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "')";

        $conn->query($insert_sql);
    }
}

// Fetch data from the database
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            padding: 20px;
        }

        .navbar {
            text-align: center;
            background: #4CAF50;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .navbar a {
            background: #2c3e50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }

        .navbar a:hover {
            background: #1e2a38;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .links {
            text-align: center;
            margin-bottom: 20px;
        }

        .links a {
            background: #4CAF50;
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }

        .links a:hover {
            background: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .welcome {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="index.php">🏠 Home</a>
    <a href="register.php">📝 Register</a>
    <a href="login.php">🔐 Login</a>
    <a href="users.php">👥 Users Management</a>
</div>

<div class="container">
    <h2>Users List</h2>

    <!-- Welcome Message -->
    <?php if (isset($_SESSION['user_name'])): ?>
        <div class="welcome">
            🎉 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </div>
        <div class="links">
            <a href="logout.php">Logout</a>
        </div>
    <?php else: ?>
        <div class="links">
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td>
                            <a href='edit.php?id=" . $row["id"] . "'>Edit</a> | 
                            <a href='delete.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
