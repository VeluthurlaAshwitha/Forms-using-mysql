<?php
include 'db_connect.php'; // Include database connection

// Check if 'password' column exists in the 'users' table
$check_column_sql = "SHOW COLUMNS FROM users LIKE 'password'";
$column_result = $conn->query($check_column_sql);

if ($column_result->num_rows == 0) {
    // Add 'password' column if it doesn't exist
    $add_column_sql = "ALTER TABLE users ADD password VARCHAR(255) NOT NULL";
    if ($conn->query($add_column_sql) === TRUE) {
        echo "Password column added successfully!<br>";
    } else {
        echo "Error adding password column: " . $conn->error . "<br>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Data</title>
</head>
<body>
    <h2>Insert New User</h2>
    <form method="POST" action="insert_data.php">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
