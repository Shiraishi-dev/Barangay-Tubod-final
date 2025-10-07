<?php
// Start session
session_start();

// Include database connection
include 'db_connection.php';
include 'login-checker.php';

// Query the events
$sql = "SELECT * FROM events ORDER BY id DESC";
$result = $conn->query($sql);

// Query the news
$sql_news = "SELECT * FROM news ORDER BY created_at DESC";
$result_news = $conn->query($sql_news);

// Check for and display session message (success or error)
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Prepare user data for pre-filling the form (assuming user details are in session)
$current_user_id = $_SESSION['user_id'] ?? '0'; // Use the user_id if set, otherwise '0'
$current_user_email = $_SESSION['email'] ?? ''; // Assuming you store email in session
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
    <header>
        <nav class="navbar">
            <img src="logo.png" alt="">
            <a href="">HOME</a>
            <a href="">VOLUNTEER</a>
            <a href="">DONATION</a>
            <a href="">EVENTS</a>
            <a href="">NEWS</a>

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

    <?php if (!empty($message)): ?>
        <div class="alert-message">
            <p><?= htmlspecialchars($message) ?></p>
        </div>
    <?php endif; ?>

    <h1>Stronger barangay through giving</h1>
    <h2>and serving</h2>
    <h3>Be part of the change! Support our community events by giving and volunteering.</h3>
    <div class="image-container">
        <img src="Top1.png" class="image" alt="">
    </div>

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
                        
                        <button 
                            class="join-now-btn" 
                            onclick="openJoinModal(<?= htmlspecialchars($row['id']) ?>, '<?= addslashes(htmlspecialchars($row['title'])) ?>')">
                            JOIN NOW
                        </button>
                        <button><a href="">DONATE NOW</a></button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="news-text">
        <h1>Latest News & Updates</h1>
    </div>

    <div class="news">
        <div class="news-container">
            <?php if ($result_news->num_rows > 0): ?>
                <?php while($news = $result_news->fetch_assoc()): ?>
                    <div class="news-card">
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
    
    
    <div id="joinEventModal" class="simple-modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeJoinModal()">&times;</span>
            
            <h2>Join Event: <span id="event-title-placeholder"></span></h2>
            
            <form action="handle_volunteer_join.php" method="POST">
                <input type="hidden" name="event_id" id="modal-event-id">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($current_user_id); ?>">

                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input type="address" id="address" name="address" value="<?= htmlspecialchars($current_user_email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number (Optional)</label>
                    <input type="text" id="phone_number" name="phone_number">
                </div>
                <div class="form-group">
                    <label for="Age">Age (Required Age 18 above)</label>
                    <input type="number" id="age" name="Age" required>
                </div>
                <div class="form-group">
                    <label for="Sex">Sex</label>
                    <select id="sex" name="Sex">
                        <option value="Male">Male</option>
                        <option value="Male">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Joining (Optional)</label>
                    <textarea id="reason" name="reason" rows="3"></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Submit and Join</button>
            </form>
        </div>
    </div>
    <script>
        // Select the main elements
        const modal = document.getElementById('joinEventModal');
        const modalEventIdInput = document.getElementById('modal-event-id');
        const eventTitlePlaceholder = document.getElementById('event-title-placeholder');

        // Function to open the modal and set the event data
        function openJoinModal(eventId, eventTitle) {
            if (modalEventIdInput) {
                modalEventIdInput.value = eventId;
            }
            if (eventTitlePlaceholder) {
                eventTitlePlaceholder.textContent = eventTitle;
            }
            // CRITICAL STEP: Set display style to "block"
            if (modal) {
                modal.style.display = "block";
            }
        }

        // Function to close the modal
        function closeJoinModal() {
            // CRITICAL STEP: Set display style back to "none"
            if (modal) {
                modal.style.display = "none";
            }
        }

        // Close modal when clicking outside the content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <?php $conn->close(); ?>
</body>
</html>