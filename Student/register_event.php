<?php
// register_event.php
include '../Database/db_connection.php';
include '../Config/java_email_config.php';
session_start();

// Get POST data
$event_id = $_POST['event_id'] ?? '';
$roll_no = $_POST['roll_no'] ?? '';

if ($event_id && $roll_no) {
    try {
        $conn = OpenCon();

        // Get student info from users table
        $user_stmt = $conn->prepare("SELECT email, phone FROM users WHERE roll_no = ? LIMIT 1");
        $user_stmt->bind_param("s", $roll_no);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();

        if ($user_result->num_rows === 0) {
            echo "Student not found.";
            $user_stmt->close();
            CloseCon($conn);
            exit;
        }

        $user = $user_result->fetch_assoc();
        $email = $user['email'];
        $phone = $user['phone'];
        $user_stmt->close();

        // Prevent duplicate registration
        $check = $conn->prepare("SELECT * FROM event_registrations WHERE event_id=? AND roll_no=?");
        $check->bind_param("is", $event_id, $roll_no);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "Already registered for this event.";
            $check->close();
            CloseCon($conn);
            exit;
        }
        $check->close();

        // Insert registration
        $stmt = $conn->prepare("INSERT INTO event_registrations (event_id, roll_no, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $event_id, $roll_no, $email, $phone);

        if ($stmt->execute()) {
            // Registration successful, now send confirmation email
            try {
                // Get event details for email
                $event_stmt = $conn->prepare("SELECT event_name, description, date, image_path FROM events WHERE event_id = ? LIMIT 1");
                $event_stmt->bind_param("i", $event_id);
                $event_stmt->execute();
                $event_result = $event_stmt->get_result();
                
                if ($event_result->num_rows > 0) {
                    $event_data = $event_result->fetch_assoc();
                    
                    // Get user's full name
                    $user_name = getUserFullName($roll_no, $conn);
                    
                    // Initialize Java email service and send confirmation
                    $javaEmailService = new JavaEmailService();
                    
                    // Check if Java service is running
                    if (!$javaEmailService->checkHealth()) {
                        echo "Registered successfully, but email service is not available.";
                        $event_stmt->close();
                        return;
                    }
                    
                    $email_sent = $javaEmailService->sendRegistrationConfirmation(
                        $email,
                        $user_name,
                        $event_data['event_name'],
                        $event_data['date'],
                        $event_data['description'],
                        $event_data['image_path']
                    );
                    
                    if ($email_sent) {
                        echo "Registered successfully. Confirmation email sent to " . $email . ".";
                    } else {
                        echo "Registered successfully, but failed to send confirmation email.";
                    }
                } else {
                    echo "Registered successfully, but could not retrieve event details for email.";
                }
                
                $event_stmt->close();
                
            } catch (Exception $e) {
                // Registration was successful, but email failed
                error_log("Email sending failed: " . $e->getMessage());
                echo "Registered successfully, but failed to send confirmation email.";
            }
        } else {
            echo "Registration failed: " . $stmt->error;
        }

        $stmt->close();
        CloseCon($conn);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Missing data.";
}
?>
