<?php 
include 'db_connection.php';
include 'register_function_admin.php';
$error = "";
$success = ""; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register-design.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Register</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="logo.png" alt="">
        </nav>
    </header>
    <div class="login-container">
        <div class="login-box"> 
            <h1>Register</h1>

            <?php if ($error): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color:green;"><?php echo $success; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <p>Firstname</p>
                <input type="text" name="first_name" placeholder="Firstname" required>
                <p>Middle name</p>
                <input type="text" name="middle_name" placeholder="Middlename">
                <p>Lastname</p>
                <input type="text" name="last_name" placeholder="Lastname" required>
                <p>ID number</p>
                <input type="text" name="id_number" placeholder="Email" required>
                <p>Email</p>
                <input type="email" name="email" placeholder="Email" required>
                <p>Mobile Number</p>
                <input type="text" name="mobile_number" placeholder="Mobile number" required>
                <p>Username</p>
                <input type="text" name="username" placeholder="Username" required>
                <p>Password</p>
                <input type="password" name="password" placeholder="Password" required> <br>
                <button type="submit">Register</button> <br>
            </form>

            <p>Already have an account? <a href="login.php">Login</a></p> <br>
            <a href="login.php">User login</a>
        </div>
        <div class="image-container"></div>
    </div>
</body>
</html>