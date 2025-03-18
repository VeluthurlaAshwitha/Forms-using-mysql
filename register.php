<?php
session_start(); // Start session for user tracking

// Database Connection
$servername = "localhost";
$username = "root";
$password = "ashwitha@vm123";
$database = "test_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add 'password' column if missing
$check_column_sql = "SHOW COLUMNS FROM users LIKE 'password'";
$column_result = $conn->query($check_column_sql);

if ($column_result->num_rows == 0) {
    $add_column_sql = "ALTER TABLE users ADD password VARCHAR(255) NOT NULL";
    $conn->query($add_column_sql);
}

// Registration Logic
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^A-Za-z0-9]/", $password)) {
        $message = "<div class='error-msg'>❌ Password must be at least 8 characters with letters, numbers, and symbols.</div>";
    } elseif ($password !== $confirm_password) {
        $message = "<div class='error-msg'>❌ Passwords do not match.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $name;  // Store username in session for welcome message
            header("Location: index.php"); // Redirect to Main Page
            exit;
        } else {
            $message = "<div class='error-msg'>❌ Error: " . $conn->error . "</div>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - User Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #4ca1af); /* Same as Login */
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #4ca1af; /* Heading color */
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #4ca1af;
            border-radius: 8px;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4ca1af;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #3a899d;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #4ca1af;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .success-msg, .error-msg {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php echo $message; ?>

        <form method="POST" action="register.php">
            <label for="name">Name:</label>
            <input type="text" name="name" placeholder="Enter your name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>

            <input type="submit" value="Register">

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
