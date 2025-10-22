<?php
session_start();
include '../Database/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    echo "Unauthorized. Please log in as staff.";
    exit();
}

try {
    $conn = OpenCon();

    // Get form data
    $event_name = $_POST['event_name'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';
    $staff_id = $_SESSION['user_id'];
    $image_path = '';
    $total_participants = isset($_POST['total_participants']) ? intval($_POST['total_participants']) : 0;

    // Handle image upload
    if (isset($_FILES['brochure_image']) && $_FILES['brochure_image']['error'] == UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['brochure_image']['tmp_name'];
        $img_name = basename($_FILES['brochure_image']['name']);
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed)) {
            $img_new_name = uniqid('brochure_', true) . '.' . $img_ext;
            $img_dest = '../uploads/' . $img_new_name;

            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0777, true);
            }

            if (move_uploaded_file($img_tmp, $img_dest)) {
                $image_path = 'uploads/' . $img_new_name;
            } else {
                throw new Exception("Failed to upload image.");
            }
        } else {
            throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }
    }

    if ($event_name && $date) {
        $stmt = $conn->prepare("INSERT INTO events (event_name, description, date, staff_id, image_path, total_participants, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("sssisi", $event_name, $description, $date, $staff_id, $image_path, $total_participants);

        if ($stmt->execute()) {
            $event_id = $conn->insert_id;

            // Handle coordinators
            if (isset($_POST['coordinators']) && is_array($_POST['coordinators'])) {
                $coord_stmt = $conn->prepare("INSERT INTO event_coordinators (event_id, user_id) VALUES (?, ?)");
                if (!$coord_stmt) {
                    throw new Exception("Prepare statement failed for coordinators: " . $conn->error);
                }
                foreach ($_POST['coordinators'] as $user_id) {
                    $coord_stmt->bind_param("ii", $event_id, $user_id);
                    $coord_stmt->execute();
                }
                $coord_stmt->close();
            }

            echo "Event created successfully (pending approval).";
        } else {
            throw new Exception("Error creating event: " . $stmt->error);
        }

        $stmt->close();
    } else {
        echo "Please provide event name and date.";
    }

    CloseCon($conn);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
