<?php
session_start();
include 'db_connection.php';

// Only allow admins
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date']; 

    // Handle image upload safely
    if (!empty($_FILES['image']['name'])) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('event_', true) . '.' . $ext; // unique filename
        $targetDir = "uploads/";

        // Create folder if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (!move_uploaded_file($imageTmp, $targetDir . $imageName)) {
            die("Error uploading the image.");
        }
    } else {
        $imageName = null; // optional, if no image uploaded
    }

    // Insert into events table
    $stmt = $conn->prepare("INSERT INTO events (title, description, image, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $imageName, $start_date, $end_date);
    $stmt->execute();

    header("Location: event_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
</head>
<body>
    <h1>Add New Event</h1>
    <form method="post" enctype="multipart/form-data">
        <p>Title: <input type="text" name="title" required></p>
        <p>Description: <textarea name="description" required></textarea></p>
        <p>Image: <input type="file" name="image" required></p>
        <p>Start Date: <input type="date" name="start_date" required></p>
        <p>End Date: <input type="date" name="end_date" required></p>
        <button type="submit">Add Event</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
