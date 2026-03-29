<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

// Create connection function
function OpenCon() {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Close connection function
function CloseCon($conn) {
    $conn->close();
}
?>
