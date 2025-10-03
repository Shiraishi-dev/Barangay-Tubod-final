<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Handle file upload
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    $sql = "INSERT INTO news (title, content, image) VALUES ('$title', '$content', '$imageName')";
    if ($conn->query($sql)) {
        header("Location: reports_admin.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add News</title>
    <link rel="stylesheet" href="admin.design.css">
</head>
<body>
    <h1>Add News</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>
        
        <label>Content:</label><br>
        <textarea name="content" rows="6" required></textarea><br><br>
        
        <label>Image:</label><br>
        <input type="file" name="image"><br><br>
        
        <button type="submit" class="btn btn-add">Add News</button>
    </form>
</body>
</html>
