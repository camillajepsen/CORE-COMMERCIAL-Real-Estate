<?php
session_start();
include 'includes/db.php';
include 'includes/login-functions.php';

if (isset($_POST['reset_password'])) {
    $email = strtolower(trim($_POST['email']));
    
    // function to handle password reset and get the message
    $message = sendPasswordResetLink($pdo, $email);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Reset Password</title>
</head>

<body>

    <div class="register-page">
        <!-- Header with nav bar -->
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
                </div>
                <!--Header greeting-->
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span id="greeting">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
                    <?php endif; ?>
                </div>
            </nav>

            <nav class="nav-links">
                <ul>
                    <!--shared header links-->
                    <div class="centered-links">
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="customer/properties.php">Properties</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </div>
                    <!--signed in links-->
                    <div class="right-hand-links">
                        <li><a href="login.php" class="accesslink">LOGIN</a></li>
                        <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                    </div>
                </ul>
            </nav>
        </header>

        <!-- resetting password Form -->
        <div class="register-form">
            <form action="reset_password.php" method="POST">
                <h2 id="formh2">RESET PASSWORD</h2><br>
                <input type="email" name="email" placeholder="Enter your email" required><br><br>

                <button type="submit" name="reset_password">Send Reset Link</button>
            </form>

            <!-- result message -->
            <?php if (isset($message)): ?>
                <p style="color: white;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </div>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>
