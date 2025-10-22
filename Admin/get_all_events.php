<?php
session_start();
include '../Database/db_connection.php';

header('Content-Type: application/json');

// Temporarily disable session check for testing
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     echo json_encode([]);
//     exit();
// }

try {
    $conn = OpenCon();

    $stmt = $conn->prepare("SELECT event_id, event_name, description, date, status, image_path FROM events ORDER BY date DESC");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);

    $stmt->close();
    CloseCon($conn);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
