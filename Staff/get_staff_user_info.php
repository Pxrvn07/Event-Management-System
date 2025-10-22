<?php
// get_staff_user_info.php
include '../Database/db_connection.php';

$roll_no = $_GET['roll_no'] ?? '';
header('Content-Type: application/json');

if ($roll_no) {
    try {
        $conn = OpenCon();

        // Get staff information from staff table
        $stmt = $conn->prepare("SELECT id, staff_name, email, designation, roll_no FROM staff WHERE roll_no = ? LIMIT 1");

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                "id" => $row['id'],
                "staff_name" => $row['staff_name'],
                "email" => $row['email'],
                "designation" => $row['designation'],
                "roll_no" => $row['roll_no'],
                "phone" => "" // Staff table doesn't have phone field, can be added if needed
            ]);
        } else {
            echo json_encode([
                "id" => "", 
                "staff_name" => "", 
                "email" => "", 
                "designation" => "", 
                "roll_no" => "",
                "phone" => ""
            ]);
        }

        $stmt->close();
        CloseCon($conn);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode([
        "staff_name" => "", 
        "email" => "", 
        "designation" => "", 
        "roll_no" => "",
        "phone" => ""
    ]);
}
?>