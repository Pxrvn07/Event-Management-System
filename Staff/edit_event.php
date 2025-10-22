<?php
session_start();
include '../Database/db_connection.php';


// Debugging output for troubleshooting
error_log('DEBUG: event_id=' . ($_POST['event_id'] ?? '') . ', user_id=' . ($_SESSION['user_id'] ?? '') . ', role=' . ($_SESSION['role'] ?? ''));
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized. Please log in.";
    exit();
}

try {
    $conn = OpenCon();

    // Get form data
    $event_id = $_POST['event_id'] ?? '';
    $event_name = $_POST['event_name'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_path = '';

    if (empty($event_id)) {
        throw new Exception("Event ID is required.");
    }

    // Permission check
    $allowed = false;
    if ($_SESSION['role'] === 'admin') {
        $allowed = true;
    } elseif ($_SESSION['role'] === 'staff') {
        $check_stmt = $conn->prepare("SELECT staff_id FROM events WHERE event_id = ?");
        $check_stmt->bind_param("i", $event_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            $event_staff_id = $check_result->fetch_assoc()['staff_id'];
            if ($event_staff_id == $_SESSION['user_id']) {
                $allowed = true;
            }
        }
        $check_stmt->close();
    }
    // Allow student coordinators to edit events they coordinate
    if ($_SESSION['role'] === 'student') {
        $coord_stmt = $conn->prepare("SELECT 1 FROM event_coordinators WHERE event_id = ? AND user_id = ? LIMIT 1");
        $coord_stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
        $coord_stmt->execute();
        $coord_result = $coord_stmt->get_result();
        if ($coord_result->num_rows > 0) {
            $allowed = true;
        }
        $coord_stmt->close();
    }
    if (!$allowed) {
        echo "Unauthorized. You can only edit your own events.";
        exit();
    }

    // Handle image upload if new
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
        // Update event
        if ($image_path) {
            $stmt = $conn->prepare("UPDATE events SET event_name = ?, description = ?, date = ?, image_path = ? WHERE event_id = ?");
            $stmt->bind_param("ssssi", $event_name, $description, $date, $image_path, $event_id);
        } else {
            $stmt = $conn->prepare("UPDATE events SET event_name = ?, description = ?, date = ? WHERE event_id = ?");
            $stmt->bind_param("sssi", $event_name, $description, $date, $event_id);
        }

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        if ($stmt->execute()) {
            // Update coordinators: delete old, insert new
            $del_stmt = $conn->prepare("DELETE FROM event_coordinators WHERE event_id = ?");
            $del_stmt->bind_param("i", $event_id);
            $del_stmt->execute();
            $del_stmt->close();

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

            // Ensure the student remains a coordinator if editing their own event
            if ($_SESSION['role'] === 'student') {
                $coord_stmt = $conn->prepare("INSERT IGNORE INTO event_coordinators (event_id, user_id) VALUES (?, ?)");
                $coord_stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
                $coord_stmt->execute();
                $coord_stmt->close();
            }

            echo "Event updated successfully.";
        } else {
            throw new Exception("Error updating event: " . $stmt->error);
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
