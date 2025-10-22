<?php
session_start();
include '../Database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $conn = OpenCon();

        $role = $_POST['role'] ?? 'student';
        $roll_no = trim($_POST['roll_no'] ?? '');
        $password_input = trim($_POST['password'] ?? '');

        if (empty($roll_no) || empty($password_input)) {
            echo "Roll Number and Password are required.";
            CloseCon($conn);
            exit();
        }

        if ($role === 'staff') {
            $stmt = $conn->prepare("SELECT id, staff_name, password, designation FROM staff WHERE roll_no = ? LIMIT 1");
        } elseif ($role === 'admin') {
            $stmt = $conn->prepare("SELECT id, admin_name, password FROM admin WHERE roll_no = ? LIMIT 1");
        } else {
            $stmt = $conn->prepare("SELECT id, full_name, password, email FROM users WHERE roll_no = ? LIMIT 1");
        }

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Use password_verify for hashed passwords, fallback to plain text for backwards compatibility
            $password_matches = password_verify($password_input, $user['password']) || ($password_input === $user['password']);
            
            if ($password_matches) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['roll_no'] = $roll_no;

                if ($role === 'staff') {
                    $_SESSION['user_name'] = $user['staff_name'];
                    $_SESSION['designation'] = $user['designation'];
                    $_SESSION['role'] = 'staff';
                } elseif ($role === 'admin') {
                    $_SESSION['user_name'] = $user['admin_name'];
                    $_SESSION['role'] = 'admin';
                } else {
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'student';
                }
                echo "Login successful";
            } else {
                echo "Invalid roll number or password.";
            }
        } else {
            echo "Invalid roll number or password.";
        }

        $stmt->close();
        CloseCon($conn);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
