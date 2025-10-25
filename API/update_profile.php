<?php
session_start();
include '../Database/db_connection.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['roll_no'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = OpenCon();
        $roll_no = $_SESSION['roll_no'];
        
        // Get current user data
        $stmt = $conn->prepare("SELECT id, profile_photo FROM users WHERE roll_no = ? LIMIT 1");
        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }
        
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $old_profile_photo = $user['profile_photo'];
        
        // Handle profile photo upload
        $profile_photo_path = $old_profile_photo; // Keep existing photo by default
        
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $file_type = $_FILES['profile_photo']['type'];
            $file_size = $_FILES['profile_photo']['size'];
            
            if (!in_array($file_type, $allowed_types)) {
                echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
                exit();
            }
            
            if ($file_size > $max_size) {
                echo json_encode(['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.']);
                exit();
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $unique_filename = 'profile_' . $roll_no . '_' . time() . '.' . $file_extension;
            $upload_dir = '../uploads/profiles/';
            $upload_path = $upload_dir . $unique_filename;
            
            // Create uploads directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                // Delete old profile photo if it exists and is not a default image
                if ($old_profile_photo && file_exists('../' . $old_profile_photo) && strpos($old_profile_photo, 'uploads/profiles/profile_') === 0) {
                    unlink('../' . $old_profile_photo);
                }
                
                $profile_photo_path = 'uploads/profiles/' . $unique_filename;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload profile photo.']);
                exit();
            }
        }
        
        // Update user profile
        $update_fields = [];
        $update_values = [];
        $types = '';
        
        if (isset($_POST['full_name']) && trim($_POST['full_name']) !== '') {
            $update_fields[] = 'full_name = ?';
            $update_values[] = trim($_POST['full_name']);
            $types .= 's';
        }
        
        if (isset($_POST['phone']) && trim($_POST['phone']) !== '') {
            $update_fields[] = 'phone = ?';
            $update_values[] = trim($_POST['phone']);
            $types .= 's';
        }
        
        if ($profile_photo_path !== $old_profile_photo) {
            $update_fields[] = 'profile_photo = ?';
            $update_values[] = $profile_photo_path;
            $types .= 's';
        }
        
        if (!empty($update_fields)) {
            $sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE roll_no = ?";
            $update_values[] = $roll_no;
            $types .= 's';
            
            $update_stmt = $conn->prepare($sql);
            $update_stmt->bind_param($types, ...$update_values);
            
            if ($update_stmt->execute()) {
                // Return updated user data
                $stmt = $conn->prepare("SELECT full_name, email, phone, profile_photo FROM users WHERE roll_no = ? LIMIT 1");
                $stmt->bind_param("s", $roll_no);
                $stmt->execute();
                $result = $stmt->get_result();
                $updated_user = $result->fetch_assoc();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Profile updated successfully',
                    'user' => $updated_user
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
            }
            
            $update_stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes to update']);
        }
        
        $stmt->close();
        CloseCon($conn);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>