<?php
session_start();
include 'db_connection.php';

// Initialize variables BEFORE any HTML output
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name   = trim($_POST['first_name'] ?? '');
    $middle_name  = trim($_POST['middle_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $mobile       = trim($_POST['mobile_number'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $password     = trim($_POST['password'] ?? '');

    // Basic validation
    if ($first_name === '' || $last_name === '' || $email === '' || $mobile === '' || $username === '' || $password === '') {
        $error = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check username or email availability
        $stmt = $conn->prepare("SELECT user_id FROM users_info WHERE username = ? OR email = ?");
        if (!$stmt) {
            $error = "Database error (prepare): " . $conn->error;
        } else {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username or email already exists. Choose another.";
            } else {
                // Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_type = "user";

                // Insert new user
                $insert = $conn->prepare("INSERT INTO users_info 
                    (first_name, middle_name, last_name, email, mobile_number, username, password, user_type)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$insert) {
                    $error = "Database error (prepare insert): " . $conn->error;
                } else {
                    $insert->bind_param("ssssssss", $first_name, $middle_name, $last_name, $email, $mobile, $username, $hashed_password, $user_type);
                    if ($insert->execute()) {
                        // Alert + redirect
                        echo "<script>
                            alert('Registration successful! Please login.');
                            window.location.href='login.php';
                        </script>";
                        exit;
                    } else {
                        $error = "Registration failed: " . $insert->error;
                    }
                    $insert->close();
                }
            }
            $stmt->close();
        }
    }
}
?>
