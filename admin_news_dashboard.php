<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';

// Optional: check if logged in admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$adminName = htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']);

// Fetch all news
$result = $conn->query("SELECT * FROM news ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - News</title>
    <link rel="stylesheet" href="admin.design.css">
</head>
<body>
    <div class="header">
        <div class="welcome">Welcome, <?php echo $adminName; ?>!</div>
        <form method="post" action="logout.php">
            <button type="submit" class="btn-logout">LOGOUT</button>
        </form>
    </div>
    <a href="admin_event_dashboard.php" class="btn btn-add">Manage Events</a>
    <h1>Admin Dashboard - Manage News</h1>
    <a href="add_news.php" class="btn btn-add">+ Add New News</a>
    <br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Date Posted</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo substr($row['content'], 0, 100) . '...'; ?></td>
            <td>
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?php echo $row['image']; ?>" width="100">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_news.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this news?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
