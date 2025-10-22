<?php
session_start();
include '../Database/db_connection.php';

header('Content-Type: application/json');

// Get staff_id from parameter or session
$staff_roll_no = $_GET['roll_no'] ?? ($_SESSION['roll_no'] ?? '');

// Temporarily disable session check for testing
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
//     echo json_encode([]);
//     exit();
// }

if (!$staff_roll_no) {
    echo json_encode([]);
    exit();
}

try {
    $conn = OpenCon();
    
    // First get the staff ID from the roll_no
    $stmt = $conn->prepare("SELECT id FROM staff WHERE roll_no = ?");
    $stmt->bind_param("s", $staff_roll_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $staff_id = $row['id'];
    } else {
        echo json_encode([]);
        exit();
    }
    $stmt->close();

    // Now get events for this staff member
    $stmt = $conn->prepare("SELECT event_id, event_name, description, date, status, image_path FROM events WHERE staff_id = ? ORDER BY date DESC");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("i", $staff_id);
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
