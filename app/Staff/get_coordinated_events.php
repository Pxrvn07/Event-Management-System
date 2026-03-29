<?php

include '../Database/db_connection.php';

header('Content-Type: application/json');

$user_id = $_GET['user_id'] ?? '';
if (empty($user_id)) {
    echo json_encode([]);
    exit();
}

try {
    $conn = OpenCon();

    $stmt = $conn->prepare("
        SELECT e.event_id, e.event_name, e.description, e.date, e.status, e.image_path
        FROM events e
        JOIN event_coordinators ec ON e.event_id = ec.event_id
        WHERE ec.user_id = ? AND e.status = 'approved'
        ORDER BY e.date DESC
    ");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
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
