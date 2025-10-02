<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php'; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $entered_password = trim($_POST['password']); // renamed

    if (!empty($username) && !empty($entered_password)) {
        // Look for user in database
        $stmt = $conn->prepare("SELECT user_id, username, password, user_type, id_number, first_name, last_name 
                                FROM users_info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_username, $db_password, $user_type, $id_number, $first_name, $last_name);
            $stmt->fetch();

            // Verify password
            if (password_verify($entered_password, $db_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['user_type'] = $user_type;
                $_SESSION['id_number'] = $id_number;

                // Redirect based on role
                if ($user_type === "admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: user.php");
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
        $stmt->close();
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
