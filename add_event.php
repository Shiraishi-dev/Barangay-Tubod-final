<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Handle image upload
    $imageName = $_FILES['image']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($imageName);
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

    // Generate links
    $nextId = $conn->insert_id + 1;
    $join_link = "join.php?id=" . $nextId;
    $donate_link = "donate.php?id=" . $nextId;

    $stmt = $conn->prepare("INSERT INTO events (title, description, image, join_link, donate_link) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $imageName, $join_link, $donate_link);
    $stmt->execute();

    header("Location: admin_dashboard.php");
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
        <button type="submit">Add Event</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
