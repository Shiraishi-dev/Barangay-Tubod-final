<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';

// Security check: must be logged in as admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 1. Get and validate event ID from the URL
$event_id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);
if (!$event_id) {
    header("Location: event_admin.php");
    exit;
}

$adminName = htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']);
$Idnumber = htmlspecialchars($_SESSION['id_number']);

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY id DESC");

// 2. Fetch the event title for display purposes (using prepared statement)
$event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event_data = $event_result->fetch_assoc();
$event_title = $event_data ? htmlspecialchars($event_data['title']) : 'Unknown Event';
$event_stmt->close();

// 3. Fetch all volunteers for this event using JOIN
// CRITICAL FIX: The JOIN condition now uses ui.user_id to link to ve.user_id
$sql = "
    SELECT
        ui.first_name,
        ui.last_name,
        ui.user_id,
        ve.address,
        ve.volunteer_event_id,
        ve.phone_number,
        ve.reason,
        ve.joined_at
    FROM volunteer_events ve
    JOIN users_info ui ON ve.user_id = ui.user_id
    WHERE ve.event_id = ?
    ORDER BY ve.joined_at DESC
";

$volunteer_stmt = $conn->prepare($sql);
$volunteer_stmt->bind_param("i", $event_id);
$volunteer_stmt->execute();
$volunteers_result = $volunteer_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new.css"> <title>Volunteers for <?php echo $event_title; ?></title>
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
        <h1>Volunteers for Event: <?php echo $event_title; ?></h1> <br>
        <a href="admin_volunteers.php"><button>← Back to Events List</button></a>
        <br><br>

        <div class="volunteer_details">
            <?php if ($volunteers_result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Volunteer Event ID</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Reason for Joining</th>
                        <th>Joined Date</th>
                    </tr>
                    <?php while ($volunteer = $volunteers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($volunteer['volunteer_event_id']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['address']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['phone_number'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['reason'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(date("M d, Y H:i", strtotime($volunteer['joined_at']))); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No users have joined this event yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php 
$volunteer_stmt->close();
$conn->close(); 
?>