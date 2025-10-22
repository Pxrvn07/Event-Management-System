<?php

include '../Database/db_connection.php';

header('Content-Type: application/json');

$roll_no = $_GET['roll_no'] ?? '';
if (empty($roll_no)) {
    echo json_encode([]);
    exit();
}

try {
    $conn = OpenCon();

    // Get events registered by the logged-in student
    $stmt = $conn->prepare("
        SELECT e.event_id, e.event_name, e.description, e.date, e.image_path, er.status
        FROM events e
        INNER JOIN event_registrations er ON e.event_id = er.event_id
        WHERE er.roll_no = ?
        ORDER BY e.date DESC
    ");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("s", $roll_no);
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
