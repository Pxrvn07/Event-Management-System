<?php
// get_user_info.php
include '../Database/db_connection.php';

$roll_no = $_GET['roll_no'] ?? '';
header('Content-Type: application/json');

if ($roll_no) {
    try {
        $conn = OpenCon();

        // Corrected table name from 'user' to 'users'
    $stmt = $conn->prepare("SELECT id, full_name, email, phone, profile_photo FROM users WHERE roll_no = ? LIMIT 1");

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                "id" => $row['id'],
                "full_name" => $row['full_name'],
                "email" => $row['email'],
                "phone" => $row['phone'],
                "profile_photo" => $row['profile_photo']
            ]);
        } else {
            echo json_encode(["id" => "", "full_name" => "", "email" => "", "phone" => "", "profile_photo" => ""]);
        }

        $stmt->close();
        CloseCon($conn);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["full_name" => "", "email" => "", "phone" => ""]);
}
?>
