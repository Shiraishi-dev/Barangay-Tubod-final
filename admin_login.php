<?php 
include 'login-checker.php'; 
include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-design.css">
    <title>Login</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="logo.png" alt="">
        </nav>
    </header>
    <div class="login-container">
        <div class="login-box"> 
            <h1>Welcome Admin!</h1>

            <!-- Show error message -->
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST" action="">
                <p>Username</p>
                <input type="text" name="username" placeholder="username" required>
                <p>Password</p>
                <input type="password" name="password" placeholder="password" required> <br>
                <button type="submit">Submit</button>
            </form>

            <br>
            <p>Don't have an account? <a href="admin_register.php">Sign Up</a></p> <br>
            <a href="login.php">User login</a>
        </div>
        <div class="image-container"></div>
    </div>
</body>
</html>
