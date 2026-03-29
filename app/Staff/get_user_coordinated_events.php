<?php
// get_user_coordinated_events.php
include '../Database/db_connection.php';
header('Content-Type: application/json');

$roll_no = $_GET['roll_no'] ?? '';
if (!$roll_no) {
    echo json_encode([]);
    exit();
}

try {
    $conn = OpenCon();
    // Get user_id from roll_no
    $stmt = $conn->prepare("SELECT id FROM users WHERE roll_no = ? LIMIT 1");
    $stmt->bind_param("s", $roll_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_id = null;
    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
    }
    $stmt->close();
    if (!$user_id) {
        echo json_encode([]);
        CloseCon($conn);
        exit();
    }
    // Get coordinated events
    $events_stmt = $conn->prepare("
        SELECT e.event_id, e.event_name, e.description, e.date, e.status, e.image_path
        FROM events e
        JOIN event_coordinators ec ON e.event_id = ec.event_id
        WHERE ec.user_id = ? AND e.status = 'approved'
        ORDER BY e.date DESC
    ");
    $events_stmt->bind_param("i", $user_id);
    $events_stmt->execute();
    $events_result = $events_stmt->get_result();
    $events = [];
    while ($row = $events_result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode($events);
    $events_stmt->close();
    CloseCon($conn);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
