<?php
session_start();
include 'db_connection.php';

// Query events
$sql = "SELECT * FROM events ORDER BY id DESC";
$result = $conn->query($sql);

// Query news
$sql_news = "SELECT * FROM news ORDER BY created_at DESC";
$result_news = $conn->query($sql_news);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']); // Change to your session variable
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
            <button><a href="login.php">JOIN NOW</a></button>
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

                        <?php if ($isLoggedIn): ?>
                            <!-- If logged in, redirect to join page -->
                            <button onclick="window.location.href='join_event.php?id=<?= $row['id'] ?>'">JOIN NOW</button>
                        <?php else: ?>
                            <!-- If not logged in, show modal on click -->
                            <button class="join-btn">JOIN NOW</button>
                        <?php endif; ?>

                        <button>DONATE NOW</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

     <!-- News Section -->
    <div class="news-text">
        <h1>Latest News & Updates</h1>
    </div>

    <div class="events">
        <div class="event-container">
            <?php if ($result_news->num_rows > 0): ?>
                <?php while($news = $result_news->fetch_assoc()): ?>
                    <div class="event-card">
                        <?php if(!empty($news['image'])): ?>
                            <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($news['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars(substr($news['content'], 0, 150))) ?>...</p>
                        <a href="view_news.php?id=<?= $news['id'] ?>" class="btn-readmore">Read More</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No news available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h3>You need to login first before joining an event</h3>
            <button class="close-btn secondary" id="closeModalBtn">Close</button>
            <button class="close-btn" onclick="window.location.href='login.php'">Go to Login</button>
        </div>
    </div>

    <script>
        // Show modal only when JOIN button in event section is clicked
        document.querySelectorAll('.join-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('loginModal').style.display = 'block';
            });
        });

        // Close modal button
        document.getElementById('closeModalBtn').addEventListener('click', function () {
            document.getElementById('loginModal').style.display = 'none';
        });

        // Optional: close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

<?php $conn->close(); ?>
</body>
</html>
