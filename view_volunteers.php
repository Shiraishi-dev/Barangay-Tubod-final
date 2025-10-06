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
        ui.id_number,
        ve.address,
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
    <main>
        <h1>Volunteers for Event: <?php echo $event_title; ?></h1>
        <a href="event_admin.php"><button>← Back to Events List</button></a>
        <br><br>

        <div class="volunteer_details">
            <?php if ($volunteers_result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>ID Number</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Reason for Joining</th>
                        <th>Joined Date</th>
                    </tr>
                    <?php while ($volunteer = $volunteers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['id_number']); ?></td>
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