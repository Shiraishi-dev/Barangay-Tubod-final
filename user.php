<?php
// Start session
session_start();

// Include database connection
include 'db_connection.php';
include 'login-checker.php';

// Query the events
$sql = "SELECT * FROM events ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Barangay Tubod</title>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <img src="logo.png" alt="">
            <a href="">HOME</a>
            <a href="">VOLUNTEER</a>
            <a href="">DONATION</a>
            <a href="">EVENTS</a>

            <?php if(isset($_SESSION['username'])): ?>
                <span class="welcome-msg">
                    Welcome, <?= htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']); ?>!
                </span>
                <button><a href="logout.php">LOGOUT</a></button>
            <?php else: ?>
                <button><a href="login.php">JOIN NOW</a></button>
            <?php endif; ?>
        </nav>
    </header>

    <!-- Hero Section -->
    <h1>Stronger barangay through giving</h1>
    <h2>and serving</h2>
    <h3>Be part of the change! Support our community events by giving and volunteering.</h3>
    <div class="image-container">
        <img src="Top1.png" class="image" alt="">
    </div>

    <!-- Volunteer Section -->
    <div class="volunteer">
        <div class="volunteer-text">
            <h1>Serve with heart,</h1>
            <h2>volunteer with pride</h2>
            <p>Be a volunteer today lend your time, share your skills, and help make our barangay events more meaningful for everyone.</p>
            <button><a href="">VOLUNTEER NOW</a></button>
        </div>
        <div class="image-volunteer">
            <img src="volunteer.jpg" alt="Volunteer helping community">
        </div>
    </div>

    <!-- Donation Section -->
    <div class="donation">
        <div class="donation-text">
            <h1>Your generosity is the</h1>
            <h2>heartbeat of our community.</h2>
            <p>Support our community by donating money, food, or any resources that can bring hope and help to those in need.</p>
            <button><a href="">DONATE NOW</a></button>
        </div>
        <div class="image-donation">
            <img src="donation.png" alt="Donation support">
        </div>
    </div>

    <!-- Events Section -->
    <div class="events-text">
        <h1>Available Events this month</h1>
    </div>

    <div class="events">
        <div class="event-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <button><a href="">JOIN NOW</a></button>
                        <button><a href="">DONATE NOW</a></button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

<?php $conn->close(); ?>
</body>
</html>
