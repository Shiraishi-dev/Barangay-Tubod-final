<?php
session_start();
include 'db_connection.php';

// Check if admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    // Get image filename first
    $stmt = $conn->prepare("SELECT image FROM events WHERE id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Delete image from folder
    if ($image && file_exists("uploads/" . $image)) {
        unlink("uploads/" . $image);
    }

    // Delete event
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();

    header("Location: event_admin.php");
    exit;
}
?>
