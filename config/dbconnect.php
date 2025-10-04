<?php
// Include configuration
require_once __DIR__ . '/../config/config.php';

// Database connection using environment variables
$servername = DB_HOST;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$dbname = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    if (APP_ENV === 'development') {
        die("Connection failed: " . $conn->connect_error);
    } else {
        die("Database connection failed. Please try again later.");
    }
}

// Set charset
$conn->set_charset("utf8");
?>