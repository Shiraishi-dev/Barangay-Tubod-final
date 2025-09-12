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

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Events</title>
    <link rel="stylesheet" href="admin.design.css">
</head>
<body>
    <div class="header">
        <div class="welcome">Welcome, <?php echo $adminName; ?>!</div>
        <form method="post" action="logout.php">
            <button type="submit" class="btn-logout">LOGOUT</button>
        </form>
    </div>
    <h1>Admin Dashboard - Manage Events</h1>
    <a href="add_event.php" class="btn btn-add">+ Add New Event</a>
    <br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Volunteers</th>
            <th>Donors</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><img src="uploads/<?php echo $row['image']; ?>" width="100"></td>
            <td><?php echo $row['start_date']; ?></td>
            <td><?php echo $row['end_date']; ?></td>
            <td>
                <a href="view_volunteers.php?event_id=<?php echo $row['id']; ?>" class="btn btn-view">VIEW</a>
            </td>
            <td>
                <a href="view_donors.php?event_id=<?php echo $row['id']; ?>" class="btn btn-view">VIEW</a>
            </td>
            <td>
                <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this event?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
