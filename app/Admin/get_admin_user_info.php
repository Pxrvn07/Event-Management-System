<?php
// get_admin_user_info.php
include '../Database/db_connection.php';

$roll_no = $_GET['roll_no'] ?? '';
header('Content-Type: application/json');

if ($roll_no) {
    try {
        $conn = OpenCon();

        // Get admin information from admin table
        $stmt = $conn->prepare("SELECT id, admin_name, email, roll_no FROM admin WHERE roll_no = ? LIMIT 1");

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                "id" => $row['id'],
                "admin_name" => $row['admin_name'],
                "email" => $row['email'],
                "roll_no" => $row['roll_no']
            ]);
        } else {
            echo json_encode([
                "id" => "", 
                "admin_name" => "", 
                "email" => "", 
                "roll_no" => ""
            ]);
        }

        $stmt->close();
        CloseCon($conn);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode([
        "admin_name" => "", 
        "email" => "", 
        "roll_no" => ""
    ]);
}
?>