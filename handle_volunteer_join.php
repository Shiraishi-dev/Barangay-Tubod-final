<?php
session_start();
include 'db_connection.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate inputs
    $event_id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT); 
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    // Use NULL for optional fields if empty, matching your DB structure (phone_number, reason can be NULL)
    $phone_number = !empty($_POST['phone_number']) ? filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING) : NULL;
    $reason = !empty($_POST['reason']) ? filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING) : NULL;

    // CRITICAL: Check for valid user ID
    if (empty($user_id) || $user_id === '0') {
        // This handles a security bypass or a state where login-checker failed
        $_SESSION['message'] = "Error: Invalid user session. Please log in again.";
        header("Location: login.php");
        exit();
    }
    
    if (empty($event_id) || !$address) {
        $_SESSION['message'] = "Error: Missing required event or address information.";
        header("Location: user.php");
        exit();
    }
    
    // SQL to insert data into volunteer_events
    // We bind five parameters: user_id, event_id, email, phone_number, reason
    $sql = "INSERT INTO volunteer_events (user_id, event_id, address, phone_number, reason) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        // 'iisss' = integer, integer, string, string, string (s is used for NULL fields too)
        $stmt->bind_param("iisss", $user_id, $event_id, $address, $phone_number, $reason);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Success! You have successfully volunteered for the event.";
        } else {
            // Check for MySQL error 1062 (Duplicate entry)
            if ($conn->errno == 1062) { 
                 $_SESSION['message'] = "⚠️ You have already joined this event.";
            } else {
                 $_SESSION['message'] = "❌ Error joining the event: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "❌ Database error preparing statement: " . $conn->error;
    }
    
    $conn->close();

    // Redirect back to the main page
    header("Location: user.php");
    exit();
} else {
    header("Location: user.php");
    exit();
}
?>