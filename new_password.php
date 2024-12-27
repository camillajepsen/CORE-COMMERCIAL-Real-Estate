<?php
session_start();
include 'includes/db.php';
include 'includes/login-functions.php';

if (isset($_POST['reset_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if ($user) {
        // verifying if the passwords match
        if (validatePassword($password, $confirm_password)) {
            // update the password in the database
            if (updatePassword($pdo, $user['id'], $password)) {
                //then informing the user of the outcome 
                echo htmlspecialchars("Password reset successfully.");
            } else {

                echo htmlspecialchars("Failed to reset password.");
            }
        } else {
            echo htmlspecialchars("Passwords do not match. Please try again.");
        }
    } else {
        echo htmlspecialchars("Email not found.");
    }
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
        <!--Header with nav bar-->
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

        <main class="register-form">
            <form action="reset-password.php" method="POST">
                <h2 id="formh2">RESET PASSWORD</h2><br>

                <input type="password" name="password" placeholder="NEW PASSWORD" required><br><br>

                <input type="password" name="confirm_password" placeholder="CONFIRM NEW PASSWORD" required><br><br>

                <button type="submit" name="reset_password">Reset Password</button>
            </form>

            <!-- success or error message -->
            <?php if (isset($message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>