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
    <title>Document</title>
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
        <h1>Dashboard</h1> <br>
        <div class="dashboard_reports">
            <div>
                <h3>20</h3>
                <p>Events</p>
            </div>
            <div>
                <h3>20</h3>
                <p>Donations</p>
            </div>
            <div>
                <h3>20</h3>
                <p>Appointments</p>
            </div>
            <div>
                <h3>20</h3>
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
                    <img src="graph.png" alt="graph">
                </div>
            </div>
            <div class="donations_report">
                <div class="donation_graph">
                    <h2>Donations</h2> <br>
                    <img src="donations.png" alt="graph">
                </div>
            </div>
        </div>

    </main>
    <footer>wowwers</footer>
</body>
</html>