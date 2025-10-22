<?php
session_start();
include '../Database/db_connection.php';

header('Content-Type: application/json');

// Check if logged in (staff or admin)
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

try {
    $conn = OpenCon();

    $stmt = $conn->prepare("SELECT id, full_name, roll_no FROM users ORDER BY full_name");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);

    $stmt->close();
    CloseCon($conn);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
