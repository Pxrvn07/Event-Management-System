<?php

include '../Database/db_connection.php';

header('Content-Type: application/json');

$event_id = $_GET['event_id'] ?? '';

if (empty($event_id)) {
    echo json_encode(["error" => "Event ID is required."]);
    exit();
}

try {
    $conn = OpenCon();

    // Get event details including total_participants
    $stmt = $conn->prepare("SELECT event_id, event_name, description, date, status, image_path, total_participants FROM events WHERE event_id = ?");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Event not found."]);
        exit();
    }

    $event = $result->fetch_assoc();

    // Get coordinators
    $coord_stmt = $conn->prepare("SELECT user_id FROM event_coordinators WHERE event_id = ?");
    $coord_stmt->bind_param("i", $event_id);
    $coord_stmt->execute();
    $coord_result = $coord_stmt->get_result();

    $coordinators = [];
    while ($row = $coord_result->fetch_assoc()) {
        $coordinators[] = $row['user_id'];
    }
    $event['coordinators'] = $coordinators;

    // Get registered participants count
    $reg_stmt = $conn->prepare("SELECT COUNT(*) as registered_count FROM event_registrations WHERE event_id = ?");
    $reg_stmt->bind_param("i", $event_id);
    $reg_stmt->execute();
    $reg_result = $reg_stmt->get_result();
    $reg_row = $reg_result->fetch_assoc();
    $event['registered_count'] = $reg_row ? intval($reg_row['registered_count']) : 0;

    echo json_encode($event);

    $stmt->close();
    $coord_stmt->close();
    $reg_stmt->close();
    CloseCon($conn);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
