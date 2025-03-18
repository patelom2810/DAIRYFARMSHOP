<?php
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Leave empty for XAMPP
$database = "dairy_farm"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
