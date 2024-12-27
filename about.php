<?php
session_start();

include 'includes/db.php';
include 'includes/content-functions.php';

$about_content = getContentByPage($pdo, 'about');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>About</title>
</head>

<body class="info">
    <!--Header with nav bar-->
    <header class="navbar">
        <nav class="nav-bar-container">
            <div class="nav-left">
                <h1><a href="index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span id="greeting">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
                <?php endif; ?>
            </div>
        </nav>

        <nav class="nav-links">
            <ul>
                <div class="centered-links">
                    <!-- shared nav links -->
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="customer/properties.php">Properties</a></li>
                    <li><a href="contact.php">Contact Us</a></li>

                    <!-- admin nav links -->
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                        <li><a href="admin/dashboard.php">My Dashboard</a></li>

                        <!-- owner nav links -->
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'owner'): ?>
                        <li><a href="owner/dashboard.php">My Dashboard</a></li>

                        <!-- customer nav links -->
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer'): ?>
                        <li><a href="customer/review.php">Review Us</a></li>
                    <?php endif; ?>
                </div>

                <div class="right-hand-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="my_account.php">My Account</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="accesslink">LOGIN</a></li>
                        <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                    <?php endif; ?>
                </div>
            </ul>
        </nav>
    </header>
    <main class="info-container">

        <div class="inner-info">
            <h2>About CORE COMMERCIAL Real Estate</h2>
            <hr><br>
            <p id="about-text">
                <?php echo htmlspecialchars($about_content); ?>
            </p>
        </div>
    </main>
    <footer>
        <p>Core Commercial Real Estate</p>
    </footer>
</body>

</html>