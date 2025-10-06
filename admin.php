<?php
session_start();
// Include the database connection and login checker scripts
include 'db_connection.php';
include 'login-checker.php';

// --- SECURITY CHECK ---
// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit; // IMPORTANT: Always exit after a header redirect
}

// Prepare user data for display (safe from XSS)
$adminName = htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']);
$Idnumber = htmlspecialchars($_SESSION['id_number']);

// --- DYNAMIC DATA FETCHING ---

// 1. Fetch Total Event Count
$eventCount = 0;
// Use prepared statement for secure and efficient counting
$stmt = $conn->prepare("SELECT COUNT(id) AS count FROM events");
if ($stmt->execute()) {
    $countResult = $stmt->get_result()->fetch_assoc();
    $eventCount = $countResult['count'];
}

// 2. Fetch Total Unique Participant/Volunteer Count
$volunteerCount = 0;
// Counts the total *unique* people (user_id) who have joined an event.
$stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) AS count FROM volunteer_events");
if ($stmt->execute()) {
    $countResult = $stmt->get_result()->fetch_assoc();
    $volunteerCount = $countResult['count'];
}

// 3. Initialize other counts (assuming corresponding tables exist or will exist)
// You will need to add database queries for these later.
$donationCount = 0; 
$appointmentCount = 0; 

// 4. Fetch all events (Using prepared statement for consistency)
$stmt = $conn->prepare("SELECT * FROM events ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result(); // $result is the mysqli_result object containing all events
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new.css"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=notifications" />
    <title>Admin Dashboard</title>
</head>
<body>
    <nav>
        <img src="logo.png" alt="website logo">
        <ul>
            <li><input type="text" placeholder="Search"></li>
            <span class="material-symbols-outlined">notifications</span>    
        </ul>
    </nav>
    <aside>
        <div>
            <img src="prof.png" alt="Profile picture of <?php echo $adminName; ?>">
            <h2><?php echo $adminName; ?></h2>
            <p><?php echo $Idnumber ; ?></p>
            <p>Administrator</p>
        </div>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="event_admin.php">Manage Events</a></li>
            <li><a href="admin_volunteers.php">Volunteers</a></li>
            <li><a href="">Donations</a></li>
            <li><a href="reports_admin.php">News</a></li> <li><a href="">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>
    <main>
        <h1>Dashboard</h1> <br>
        <div class="dashboard_reports">
            <div>
                <h3><?php echo $eventCount; ?></h3> 
                <p>Events</p>
            </div>
            <div>
                <h3><?php echo $donationCount; ?></h3>
                <p>Donations</p>
            </div>
            <div>
                <h3><?php echo $appointmentCount; ?></h3>
                <p>Appointments</p>
            </div>
            <div>
                <h3><?php echo $volunteerCount; ?></h3>
                <p>Participants</p>
            </div>
        </div>
        <br>
        <h2>Graph Reports</h2>
        <br>
        <div class="graph_reports">
            <div class="participant_report">
                <div class="volunteer_graph">
                    <h2>Volunteers</h2> <br>
                    <img src="graph.png" alt="Volunteer trend graph">
                </div>
            </div>
            <div class="donations_report">
                <div class="donation_graph">
                    <h2>Donations</h2> <br>
                    <img src="donations.png" alt="Donations trend graph">
                </div>
            </div>
        </div>

    </main>
    <footer>&copy; <?php echo date("Y"); ?> Barangay Management System</footer>
</body>
</html>