<?php
include '../Database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = OpenCon();

        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $roll_no = trim($_POST['roll_no'] ?? '');
        $profile_photo_path = null;

        // Handle profile photo upload
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB
            
            $file_type = $_FILES['profile_photo']['type'];
            $file_size = $_FILES['profile_photo']['size'];
            
            if (!in_array($file_type, $allowed_types)) {
                echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
                exit();
            }
            
            if ($file_size > $max_size) {
                echo "File size too large. Maximum 2MB allowed.";
                exit();
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $unique_filename = 'profile_' . uniqid() . '.' . $file_extension;
            $upload_dir = '../uploads/';
            $upload_path = $upload_dir . $unique_filename;
            
            // Create uploads directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $profile_photo_path = 'uploads/' . $unique_filename;
            } else {
                echo "Failed to upload profile photo.";
                exit();
            }
        }

        if ($full_name && $email && $password && $phone && $roll_no) {
            // Check email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Email already registered. Please use a different email.";
                $stmt->close();
                CloseCon($conn);
                exit();
            }
            $stmt->close();

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (full_name, email, password, phone, roll_no, profile_photo) VALUES (?, ?, ?, ?, ?, ?)");

            if (!$insert) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $insert->bind_param("ssssss", $full_name, $email, $password_hashed, $phone, $roll_no, $profile_photo_path);

            if ($insert->execute()) {
                echo "Registration successful";
            } else {
                throw new Exception("Error during registration: " . $insert->error);
            }

            $insert->close();
        } else {
            echo "All fields are required.";
        }

        CloseCon($conn);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
