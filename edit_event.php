<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM events WHERE id = $event_id");
    $event = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Handle new image
    if (!empty($_FILES['image']['name'])) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('event_', true) . '.' . $ext;
        $targetDir = "uploads/";

        if (move_uploaded_file($imageTmp, $targetDir . $imageName)) {
            // Remove old image
            if ($event['image'] && file_exists("uploads/" . $event['image'])) {
                unlink("uploads/" . $event['image']);
            }
            $event['image'] = $imageName;
        }
    }

    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, image=?, start_date=?, end_date=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $description, $event['image'], $start_date, $end_date, $event_id);
    $stmt->execute();

    header("Location: admin_event_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="design.css">
</head>
<body>
    <h1>Edit Event</h1>
    <form method="post" enctype="multipart/form-data">
        <p>Title: <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required></p>
        <p>Description: <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea></p>
        <p>Current Image: <img src="uploads/<?php echo $event['image']; ?>" width="100"></p>
        <p>New Image: <input type="file" name="image"></p>
        <p>Start Date: <input type="date" name="start_date" value="<?php echo $event['start_date']; ?>" required></p>
        <p>End Date: <input type="date" name="end_date" value="<?php echo $event['end_date']; ?>" required></p>
        <button type="submit">Update Event</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
