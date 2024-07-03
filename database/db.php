<?php
// Assign the DB credentials to variables.
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'beeventful';

// Establish database connection using mysqli.
$conn = new mysqli($host, $username, $password, $database);

// Check the connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
