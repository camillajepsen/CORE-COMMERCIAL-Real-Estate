<?php
session_start();
include 'includes/db.php';
include 'includes/login-functions.php';

// checking if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// getting the user data
$userData = getUserById($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>My Account</title>
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
                        <!--admin links-->
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                            <li><a href="admin/dashboard.php">My Dashboard</a></li>
                            <!--owner links-->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'owner'): ?>
                            <li><a href="owner/dashboard.php">My Dashboard</a></li>
                            <!--customer links-->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer'): ?>
                            <li><a href="customer/review.php">Review Us</a></li>
                        <?php endif; ?>
                    </div>
                    <!--signed in links-->
                    <div class="right-hand-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="my_account.php">My Account</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <!--links not signed in-->
                            <li><a href="login.php" class="accesslink">LOGIN</a></li>
                            <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                        <?php endif; ?>
                    </div>
                </ul>
            </nav>
        </header>

        <main class="register-form">
            <form action="my_account.php" method="POST">
                <h2 id="formh2">MANAGE ACCOUNT</h2><br>

                <input type="text" name="name" placeholder="NAME"
                    value="<?php echo htmlspecialchars($userData['name']); ?>"><br><br>
                <input type="email" name="email" placeholder="EMAIL"
                    value="<?php echo htmlspecialchars($userData['email']); ?>"><br><br>

                <!-- Current Password Field -->
                <input type="password" name="current_password" placeholder="CURRENT PASSWORD" required><br><br>

                <input type="password" name="password" placeholder="NEW PASSWORD"><br><br>
                <input type="text" name="phone" placeholder="PHONE NUMBER"
                    value="<?php echo htmlspecialchars($userData['phone']); ?>"><br><br>

                <button type="submit" name="update_account">Update Account</button>
            </form>

            <!--  success or error message -->
            <?php if (isset($message)): ?>
                <p style="color: white;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>