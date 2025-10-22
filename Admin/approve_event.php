<?php
session_start();
include '../Database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Invalid request method.";
    exit();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Unauthorized. Please log in as admin.";
    exit();
}

try {
    $conn = OpenCon();
    $event_id = $_POST['event_id'] ?? '';

    if (empty($event_id)) {
        throw new Exception("Event ID is required.");
    }

    $stmt = $conn->prepare("UPDATE events SET status = 'approved' WHERE event_id = ?");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Event approved successfully.";
        } else {
            echo "No event found with the given ID.";
        }
    } else {
        throw new Exception("Error updating event: " . $stmt->error);
    }

    $stmt->close();
    CloseCon($conn);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
