<?php
$servername = "localhost";  
$username = "root";         
$password = "ashwitha@vm123";  
$database = "test_db";      

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
