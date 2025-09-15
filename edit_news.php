<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']); // Securely get ID

// Fetch existing news
$sql = "SELECT * FROM news WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die("News not found.");
}
$news = $result->fetch_assoc();

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $imageName = $news['image']; // Keep old image if no new upload

    // Handle image upload if new image provided
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    $sql = "UPDATE news SET title='$title', content='$content', image='$imageName' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: admin_news_dashboard.php");
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
    <title>Edit News</title>
    <link rel="stylesheet" href="admin.design.css">
</head>
<body>
    <h1>Edit News</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" required><?php echo htmlspecialchars($news['content']); ?></textarea><br><br>

        <label>Current Image:</label><br>
        <?php if ($news['image']): ?>
            <img src="uploads/<?php echo $news['image']; ?>" width="120"><br>
        <?php else: ?>
            No Image<br>
        <?php endif; ?>
        <input type="file" name="image"><br><br>

        <button type="submit" class="btn btn-edit">Update News</button>
    </form>
</body>
</html>
