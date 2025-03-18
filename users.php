<?php
session_start(); // Session for tracking logged-in users

// Database Connection
$servername = "localhost";
$username = "root";
$password = "ashwitha@vm123";
$database = "test_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Create Operation
if (isset($_POST['create'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $insert_sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    $conn->query($insert_sql);
    header("Location: users.php");
    exit;
}

// Handle Update Operation
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    $update_sql = "UPDATE users SET name='$name', email='$email' WHERE id='$id'";
    $conn->query($update_sql);
    header("Location: users.php");
    exit;
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM users WHERE id='$id'";
    $conn->query($delete_sql);
    header("Location: users.php");
    exit;
}

// Fetch Data for Display
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #4ca1af);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            background: #ffffff;
            color: #333;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #4ca1af;
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
            padding: 12px;
            text-align: left;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #4ca1af;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .btn {
            background: #4ca1af;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            text-align: center;
        }

        .btn:hover {
            background: #3a899d;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .modal.active {
            display: block;
        }

        .modal h3 {
            margin-top: 0;
            text-align: center;
        }

        .modal input[type="text"],
        .modal input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid #4ca1af;
            border-radius: 5px;
        }

        .modal .btn {
            width: 100%;
            margin-top: 10px;
        }

        .close-btn {
            background: #f44336;
            color: #fff;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Management</h2>

    <button class="btn" onclick="openModal('createModal')">➕ Add New User</button>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td class="actions">
                    <a href="#" onclick="openEditModal(<?= $row['id'] ?>, '<?= $row['name'] ?>', '<?= $row['email'] ?>')">✏️ Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Create User Modal -->
<div id="createModal" class="modal">
    <span class="close-btn" onclick="closeModal('createModal')">&times;</span>
    <h3>Add New User</h3>
    <form method="POST">
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="submit" class="btn" name="create" value="Add User">
    </form>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
    <h3>Edit User</h3>
    <form method="POST">
        <input type="hidden" id="editId" name="id">
        <input type="text" id="editName" name="name" required>
        <input type="email" id="editEmail" name="email" required>
        <input type="submit" class="btn" name="update" value="Update User">
    </form>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function openEditModal(id, name, email) {
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        openModal('editModal');
    }
</script>

</body>
</html>
