<?php
session_start();
// Include the database connection and login checker scripts
include 'db_connection.php';
include 'login-checker.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Prepare user data for display (safe from XSS)
$adminName = htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']);
$Idnumber = htmlspecialchars($_SESSION['id_number']);

// --- DYNAMIC DATA FETCHING ---

// 1. Fetch Total Event Count
$eventCount = 0;
$stmt = $conn->prepare("SELECT COUNT(id) AS count FROM events");
if ($stmt->execute()) {
    $countResult = $stmt->get_result()->fetch_assoc();
    $eventCount = $countResult['count'];
}
$stmt->close();

// 2. Fetch Total Unique Volunteer Count
$volunteerCount = 0;
$stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) AS count FROM volunteer_events");
if ($stmt->execute()) {
    $countResult = $stmt->get_result()->fetch_assoc();
    $volunteerCount = $countResult['count'];
}
$stmt->close();

// 3. Initialize other counts (future use)
$donationCount = 0; 
$appointmentCount = 0; 

// 4. Fetch all events (optional for future display)
$stmt = $conn->prepare("SELECT * FROM events ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="new.css"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=notifications" />
    <style>
        /* Optional: styling for active tab */
        .appointment_btn button.active {
            background-color: #2c3e50;
            color: white;
        }

        .appointment_section {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 8px;
        }
    </style>
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
            <p><?php echo $Idnumber; ?></p>
            <p>Administrator</p>
        </div>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="event_admin.php">Manage Events</a></li>
            <li><a href="admin_volunteers.php">Volunteers</a></li>
            <li><a href="#">Donations</a></li>
            <li><a href="reports_admin.php">News</a></li>
            <li><a href="#">Settings</a></li>
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
        <h2>Appointments Request From (Non-Government Organization)</h2>
        <br>

        <!-- Tabs -->
        <div class="appointment_btn">
            <button class="active" onclick="showSection('request')">Request</button>
            <button onclick="showSection('accept')">Accept</button>
            <button onclick="showSection('archive')">Archive</button>
        </div>

        <!-- Request Section -->
        <div id="request" class="appointment_section">
            <?php include 'request.php'; ?>
        </div>

        <!-- Accept Section -->
        <div id="accept" class="appointment_section" style="display: none;">
            <p>✅ Accepted appointments will appear here.</p>
        </div>

        <!-- Archive Section -->
        <div id="archive" class="appointment_section" style="display: none;">
            <p>🗂️ Archived appointments will appear here.</p>
        </div>

    </main>

    <footer>&copy; <?php echo date("Y"); ?> Barangay Management System</footer>

    <script>
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.appointment_section').forEach(sec => {
            sec.style.display = 'none';
        });

        // Show selected section
        const target = document.getElementById(sectionId);
        if (target) {
            target.style.display = 'block';
        }

        // Highlight active button
        document.querySelectorAll('.appointment_btn button').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }
    </script>
</body>
</html>
