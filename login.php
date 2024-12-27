<?php
session_start();
include 'includes/db.php';
include 'includes/login-functions.php';

if (isset($_POST['login'])) {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    // authenticate the user
    $user = authenticateUser($pdo, $email, $password);

    if ($user) {
        loginUser($user);
    } else {
        echo htmlspecialchars("Invalid email or password.");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Login</title>
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
                        <!--links not signed in-->
                        <li><a href="login.php" class="accesslink">LOGIN</a></li>
                        <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                    </div>
                </ul>
            </nav>
        </header>

        <!-- Login form -->
        <div class="register-form">
            <form action="login.php" method="POST">
                <h2 id="formh2">LOGIN DETAILS</h2><br>
                <input type="email" name="email" placeholder="E-MAIL" required><br><br>

                <input type="password" name="password" placeholder="PASSWORD" required><br><br>

                <button type="submit" name="login" class="signin-button">Login</button><br>
                <br>
                <p id="reset-link"><a href="reset_password.php">Forgotten password? Reset here</a></p>
            </form>


            <!-- show any error if it exists -->
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
        </div>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>