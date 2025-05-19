<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "license_management";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
