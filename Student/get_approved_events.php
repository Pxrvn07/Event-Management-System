<?php
session_start();
include '../Database/db_connection.php';

header('Content-Type: application/json');

try {
    $conn = OpenCon();

    $query = "SELECT e.event_id, e.event_name, e.description, e.date, e.status, e.image_path, e.total_participants,
              (SELECT COUNT(*) FROM event_registrations er WHERE er.event_id = e.event_id) as registered_count
              FROM events e 
              WHERE e.date >= CURDATE() AND e.status = 'approved' 
              ORDER BY e.date ASC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $row['registered_count'] = intval($row['registered_count']);
        $row['total_participants'] = intval($row['total_participants']);
        $events[] = $row;
    }

    echo json_encode($events);

    CloseCon($conn);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
