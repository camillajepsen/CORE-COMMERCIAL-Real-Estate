<?php
include 'includes/db.php';
include 'includes/content-functions.php';

// start session 
session_start();

$contact_content = getContentByPage($pdo, 'contact');

$messageStatus = "";

// check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // function to send the email
    $messageStatus = htmlspecialchars(sendContactForm($name, $email, $phone, $message));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Contact Us</title>
</head>

<body class="info">
    <!--Header with nav bar-->
    <header class="navbar">
        <nav class="nav-bar-container">
            <div class="nav-left">
                <h1><a href="index.php"><b>CORE COMMERCIAL</b> •  Real Estate</a></h1>
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

    <main class="info-container">
        <div class="inner-info">
            <h2>Contact Us</h2>
            <hr><br>

            <p id="contact-text"><?php echo htmlspecialchars($contact_content); ?></p><br>

            <!-- Show message status -->
            <p><?php echo htmlspecialchars($messageStatus); ?></p>

            <form action="contact.php" method="POST" id="contact-form">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Phone:</label><br>
                <input type="text" id="phone" name="phone" required><br><br>

                <label for="message">Message:</label><br>
                <textarea id="message" name="message" required></textarea><br><br>

                <button type="submit" class="submit-button">SEND</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Core Commercial Real Estate</p>
    </footer>
</body>

</html>