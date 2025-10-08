<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';


// Check if logged in admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$adminName = htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']);
$Idnumber = htmlspecialchars($_SESSION['id_number']);

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=notifications" />
    <title>Admin Events Management</title>
</head>
<body>
    <nav>
        <img src="logo.png" alt="logo">
        <ul>
            <li><input type="text" placeholder="Search"></li>
            <span class="material-symbols-outlined">notifications</span>    
        </ul>
    </nav>
    <aside>
        <div>
            <img src="prof.png" alt="profile picture">
            <h2><?php echo $adminName; ?></h2>
            <p><?php echo $Idnumber ; ?></p>
            <p>Administrator</p>
        </div>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="event_admin.php">Manage Events</a></li>
            <li><a href="admin_volunteers.php">Volunteers</a></li>
            <li><a href="">Donations</a></li>
            <li><a href="reports_admin.php">News</a></li>
            <li><a href="">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>
    <main>
        <h1>Events Volunteer List</h1> <br>
        <br>
        <div class="event_list">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Volunteers</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo substr($row['description'], 0, 30) . '...'; ?></td>
                    <td><img src="uploads/<?php echo $row['image']; ?>" width="100"></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td>
                        <a href="view_volunteers.php?event_id=<?php echo $row['id']; ?>" class="btn btn-view">VIEW</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
    <footer>wowwers</footer>
</body>
</html>
<?php $conn->close(); ?>